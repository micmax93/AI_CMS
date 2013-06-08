#!/php -q
<?php

class Manager
{
    protected $user_list=array();
    protected $resource_list=array();
    protected $live_channels=array();

    function new_user($uid,$uws)
    {
        $this->user_list[$uid]['ws']=$uws;
        $this->user_list[$uid]['req']=array();
        $this->say("New user id=$uid");
    }
    public function request_resource($user,$type,$id)
    {
        $this->user_list[$user][$type][$id]=true;
        $this->resource_list[$type][$id][$user]=true;
        $this->say("User id=$user request resource $type($id)");
    }
    public function leave_resource($user,$type,$id)
    {
        unset($this->user_list[$user][$type][$id]);
        unset($this->resource_list[$type][$id][$user]);
        $this->say("User id=$user leaves resource $type($id)");
    }
    public function remove_user($uid)
    {
        foreach($this->user_list[$uid] as $type => $res)
        {
            foreach($res as $rid => $val)
            {
                unset($this->resource_list[$type][$rid][$uid]);
            }
        }
        unset($this->user_list[$uid]);
        $this->close_live($uid);
        $this->say("Removing user id=$uid");
    }
    public function user_exists($user)
    {
        return isset($this->user_list[$user]);
    }
    public function send_update($type,$id,$data,$admin=null,$hash=null)
    {
        $n=0;
        if(isset($this->resource_list[$type][$id]))
        {
            $msg=new WebSocketMessage();
            $msg->setData($data);

            foreach($this->resource_list[$type][$id] as $uid => $val)
            {
                $this->user_list[$uid]['ws']->sendMessage($msg);
                $n++;
            }
        }
        $this->say("Resource $type($id) update notify send to $n users");
    }

    protected function say($msg) {
        echo '<CMD>' . "$msg \r\n";
    }
}

class LiveManager extends Manager
{
    public function open_live($id,$chanel,$hash)
    {
        if($hash!=crypt("user=" . $chanel,"live_view"))
        {
            $this->say("User id=$id - incorrect hash");
            return;
        }
        foreach($this->live_channels as $chl)
        {
            if($chl==$chanel) {return;}
        }
        $this->live_channels[$id]=$chanel;
        $this->resource_list['live'][$chanel]=array();
        $this->say("User id=$id opened chanel=$chanel");
        $this->send_update('list',0,json_encode(['cmd'=>'update','type'=>'list','id'=>0]));
    }
    public function close_live($id)
    {
        if(!isset($this->live_channels[$id])) {return;}

        $chanel=$this->live_channels[$id];
        $msg['cmd']='update';
        $msg['type']='live';
        $msg['id']=$chanel;
        $msg['error']='Chanel disconnected.';
        $this->update_live($id,json_encode($msg));

        unset($this->live_channels[$id]);
        unset($this->resource_list['live'][$chanel]);

        $this->say("Chanel=$chanel closed");
        $this->send_update('list',0,json_encode(['cmd'=>'update','type'=>'list','id'=>0]));
    }
    public function request_live($uid,$chanel)
    {
        if(isset($this->resource_list['live'][$chanel]))
        {
            if(isset($this->user_list[$uid]['live']))
            {
                foreach($this->user_list[$uid]['live'] as $rid => $val)
                {
                    $this->leave_resource($uid,'live',$rid);
                }
            }
            $this->request_resource($uid,'live',$chanel);
        }
        else
        {
            $data['cmd']='update';
            $data['type']='live';
            $data['id']=$chanel;
            $data['error']='Chanel offline.';
            $msg=new WebSocketMessage();
            $msg->setData(json_encode($data));
            $this->user_list[$uid]['ws']->sendMessage($msg);
        }
    }
    public function leave_live($uid,$chanel)
    {
        $this->leave_resource($uid,'live',$chanel);
    }
    public function update_live($id,$data)
    {
        if(!isset($this->live_channels[$id])) {return;}

        $chanel=$this->live_channels[$id];
        $n=0;
        if(isset($this->resource_list['live'][$chanel]))
        {
            $msg=new WebSocketMessage();
            $msg->setData($data);

            $n=0;
            $q=0;
            foreach($this->resource_list['live'][$chanel] as $uid => $val)
            {
                if($val==true)
                {
                    $this->resource_list['live'][$chanel][$uid]=false;
                    $this->user_list[$uid]['ws']->sendMessage($msg);
                    $n++;
                }
                else
                {
                    $this->resource_list['live'][$chanel][$uid]=$msg;
                    $q++;
                }
            }
        }
        $this->say("Resource live($chanel) update notify send to $n users, $q messages added to queue");
    }
    public function list_live($uid)
    {
        $ls=array();
        foreach($this->live_channels as $chl)
        {
            array_push($ls,$chl);
        }
        $data['cmd']='list';
        $data['type']='live';
        $data['id']=count($ls);
        $data['data']=$ls;

        $msg=new WebSocketMessage();
        $msg->setData(json_encode($data));
        $this->user_list[$uid]['ws']->sendMessage($msg);
        $this->say("Live chanel list send to user=$uid");
    }
    public function update_ack_live($uid,$chanel)
    {
        if(!isset($this->resource_list['live'][$chanel][$uid])) {return;}
        $str="Recieved ack from user $uid";

        $val=$this->resource_list['live'][$chanel][$uid];
        if($val==false)
        {
            $this->resource_list['live'][$chanel][$uid]=true;
        }
        else if($val!=true)
        {
            $this->resource_list['live'][$chanel][$uid]=false;
            $this->user_list[$uid]['ws']->sendMessage($val);
            $str+=", sending waiting messages";
        }
        $this->say($str);
    }
}


class SecureManager extends LiveManager
{
    protected function rand_string($len)
    {
        $str='';
        for($i=0;$i<$len;$i++)
        {
            $str= $str . chr(rand(40,120));
        }
        return $str;
    }
    protected function encode_string($str)
    {
        $str2='';
        for($i=0;$i<strlen($str);$i++)
        {
            $ch=substr($str,$i,1);
            $str2= $str2 . $ch . chr(ord($ch)+((strlen($str)%($i+2))%5)-2);
        }
        $hash=crypt($str2,strrev($str));
        return $hash;
    }

    protected $admin_list;
    public function new_admin($uid,$uws)
    {
        $this->admin_list[$uid]['ws']=$uws;
        $str=$this->rand_string(16);
        $this->admin_list[$uid]['code']=$str;
        $this->admin_list[$uid]['hash']=$this->encode_string($str);
        $this->say("New admin id=$uid");
    }
    public function send_verify($uid)
    {
        $str=$this->admin_list[$uid]['code'];
        $msg=new WebSocketMessage();
        $msg->create($str);
        $msg->setData($str);
        $this->admin_list[$uid]['ws']->sendMessage($msg);
        $this->say("Verification code send to user id= $uid");
    }
    public function is_admin($uid)
    {
        return isset($this->admin_list[$uid]);
    }
    public function user_exists($user)
    {
        return (parent::user_exists($user) or $this->is_admin($user));
    }
    public function remove_admin($uid)
    {
        unset($this->admin_list[$uid]);
        $this->say("Removing admin id=$uid");
    }
    public function remove_user($uid)
    {
        if(!$this->user_exists($uid)) {return;}
        if($this->is_admin($uid))
        {
            $this->remove_admin($uid);
        }
        else
        {
            parent::remove_user($uid);
        }
    }
    public function send_update($type,$id,$data,$admin=null,$hash=null)
    {
        if($this->admin_list[$admin]['hash']==$hash)
        {
            parent::send_update($type,$id,$data);
        }
    }
}

class CommunicationInterpreter
{
    protected $chanel_list;
    protected $manager;

    public function __construct()
    {
        $this->manager=new SecureManager();
    }
    public function newChanel($chanelId,$socket)
    {
        $this->chanel_list[$chanelId]=$socket;
    }
    function isValidStruct($data)
    {
        return (isset($data->cmd) and isset($data->type) and isset($data->id));
    }
    public function messageReceived($sender,$msg)
    {
        $data=json_decode($msg);
        if(!$this->isValidStruct($data)) {return;}

        if(!$this->manager->user_exists($sender))
        {
            if($data->cmd=='register')
            {
                if($data->type=='admin')
                {
                    $this->manager->new_admin($sender,$this->chanel_list[$sender]);
                    $this->manager->send_verify($sender);
                }
                if($data->type=='user')
                {
                    $this->manager->new_user($sender,$this->chanel_list[$sender]);
                }
            }
        }
        else
        {
            if($data->type=='live')
            {
                if($data->cmd=='open')
                {
                    $this->manager->open_live($sender,$data->id,$data->hash);
                }
                else if($data->cmd=='close')
                {
                    $this->manager->close_live($sender);
                }
                else if($data->cmd=='request')
                {
                    $this->manager->request_live($sender,$data->id);
                }
                else if($data->cmd=='ignore')
                {
                    $this->manager->leave_live($sender,$data->id);
                }
                else if($data->cmd=='update')
                {
                    $this->manager->update_live($sender,$msg);
                }
                else if($data->cmd=='ls')
                {
                    $this->manager->list_live($sender);
                }
                else if($data->cmd=='ack')
                {
                    $this->manager->update_ack_live($sender,$data->id);
                }
            }
            else if($this->manager->is_admin($sender))
            {
                if($data->cmd=='update')
                {
                    if(isset($data->hash))
                    {
                        $this->manager->send_update($data->type,$data->id,$msg,$sender,$data->hash);
                    }
                }
            }
            else
            {
                if($data->cmd=='request')
                {
                    $this->manager->request_resource($sender,$data->type,$data->id);
                }
                else if($data->cmd=='ignore')
                {
                    $this->manager->leave_resource($sender,$data->type,$data->id);
                }
            }
        }
    }
    public function chanelDisconnected($chanelId)
    {
        unset($this->chanel_list[$chanelId]);
        $this->manager->remove_user($chanelId);
    }
}

?>