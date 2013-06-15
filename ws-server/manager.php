#!/php -q
<?php

class UserList
{
    protected $user_list=array();
    protected $online_list=array();
    protected $proxy_list=array();

    protected $proxy_delay=20;
    protected $removal_delay=20;

    public function register($name,$hash)
    {
        $this->refresh();
        $this->user_list[$name]=$hash;
    }

    function isValid($name,$hash=null)
    {
        if(isset($this->user_list[$name]))
        {
            if(($hash==null) or ($hash==$this->user_list[$name]))
            {
                return true;
            }
        }
        return false;
    }

    public function login($name,$hash)
    {
        $this->refresh();
        if($this->isValid($name,$hash))
        {
            $this->online_list[$name]=true;
            return true;
        }
        return false;
    }

    public function logout($name)
    {
        $this->refresh();
        if($this->isValid($name))
        {
            $this->online_list[$name]=time();
            return true;
        }
        return false;
    }

    public function update($name,$hash)
    {
        $this->refresh();
        if($this->isValid($name,$hash))
        {
            if(isset($this->online_list[$name]))
            {
                unset($this->online_list[$name]);
            }
            $this->proxy_list[$name]=time();
            return true;
        }
        return false;
    }

    public function refresh()
    {
        $time=time();
        foreach($this->online_list as $name => $val)
        {
            if($val!=true)
            {
                if($time-$val>$this->removal_delay)
                {
                    unset($this->online_list[$name]);
                }
            }
        }
        foreach($this->proxy_list as $name => $val)
        {
            if($time-$val>$this->proxy_delay+$this->removal_delay)
            {
                unset($this->proxy_list[$name]);
            }
        }
    }

    public function getList()
    {
        $time=time();
        $this->refresh();
        $active=array();
        $delayed=array();
        foreach($this->online_list as $name => $val)
        {
            if($val==true)
            {
                $active[$name]='inf';
            }
            else
            {
                $delayed[$name]=$this->removal_delay-($time-$val);
            }
        }
        foreach($this->proxy_list as $name => $val)
        {
            $lag = $time-$val;
            $rem = $this->proxy_delay+$this->removal_delay-$lag;
            if($lag<$this->proxy_delay)
            {
                $active[$name]=$rem;
            }
            else
            {
                $delayed[$name]=$rem;
            }
        }
        $list['active']=$active;
        $list['delayed']=$delayed;
        return $list;
    }

    public function getActiveUsers()
    {
        $this->refresh();
        $list=array();
        foreach($this->online_list as $name => $val)
        {
            if($val==true)
            {
                array_push($list,$name);
            }
        }
        return $list;
    }
}

class ConnectionManager
{
    protected $conn_list=array();

    function getUname($cid)
    {
        return $this->conn_list[$cid]['name'];
    }

    public function new_connection($uid,$uws)
    {
        $this->conn_list[$uid]['ws']=$uws;
        $this->conn_list[$uid]['name']=null;
        $this->say("New user id=$uid");
    }
    public function close_connection($cid)
    {
        unset($this->conn_list[$cid]);
        $this->say("Removing user id=$cid");
    }

    function sendData($cid,$data)
    {
        $msg=new WebSocketMessage();
        $msg->setData(json_encode($data));
        $this->conn_list[$cid]['ws']->sendMessage($msg);
    }

    function bcastData($data,$root=null)
    {
        $msg=new WebSocketMessage();
        $msg->setData(json_encode($data));
        foreach($this->conn_list as $val)
        {
            if(($val['name']!=null)&&($val['name']!=$root))
            {
                $val['ws']->sendMessage($msg);
            }
        }
    }

    protected function say($msg) {
        echo '<CMD>' . "$msg \r\n";
    }
}

class LoginManager extends ConnectionManager
{
    protected $user_list;

    public function __construct()
    {
        $this->user_list=new UserList();
    }

    function sendList($cid)
    {
        $data['cmd']='list';
        $data['args']=$this->user_list->getList();
        $this->sendData($cid,$data);
    }

    function bcastUpdate($user,$action)
    {
        $data['cmd']='update';
        $data['args']['user']=$user;
        $data['args']['action']=$action;
        $this->bcastData($data,$user);
    }

    public function close_connection($cid)
    {
        $this->logout($cid);
        parent::close_connection($cid);
    }

    protected function register($name,$hash)
    {
        $this->user_list->register($name,$hash);
        $this->say("Register user $name");
    }

    public function login($sender, $name, $hash)
    {
        if($this->user_list->login($name,$hash))
        {
            $this->conn_list[$sender]['name']=$name;
            $this->sendList($sender);
            $this->bcastUpdate($name,'login');
            $this->say("Login user $name");
        }
    }

    public function update($sender, $name, $hash)
    {
        if($this->user_list->update($name,$hash))
        {
            $this->sendList($sender);
            $this->bcastUpdate($name,'update');
            $this->say("Update user $name");
        }
    }

    public function logout($cid)
    {
        if(($user=$this->getUname($cid))!=null)
        {
            $this->user_list->logout($user);
            $this->bcastUpdate($user,'logout');
        }
    }
}


class SecureLoginManager extends LoginManager
{
    public function __construct()
    {
        parent::__construct();
    }

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
        //$hash = crypt($str2, 'ai_projekt_na_100_procent');
        return 'ai_projekt_na_100_procent';
    }

    public function get_verify($uid)
    {
        $str=$this->rand_string(10);
        $this->conn_list[$uid]['auth']=$this->encode_string($str);
        $this->sendData($uid,$str);

    }

    public function register_user($sender,$auth,$user,$hash)
    {
        if(isset($this->conn_list[$sender]['auth']))
        {
            if($this->conn_list[$sender]['auth']==$auth)
            {
                $this->register($user,$hash);
            }
            else
            {
                $this->say("Invalid hash :) ".$this->conn_list[$sender]['auth']);
            }

        }
    }
}

class CommunicationInterpreter
{
    protected $manager;

    public function __construct()
    {
        $this->manager=new SecureLoginManager();
    }

    public function newChanel($chanelId, $ws)
    {
        $this->manager->new_connection($chanelId,$ws);
    }

    public function chanelDisconnected($chanelId)
    {
        $this->manager->close_connection($chanelId);
    }

    function isValidStruct($data)
    {
        return (isset($data->cmd) and isset($data->args));
    }

    public function messageReceived($sender,$msg)
    {
        $data=json_decode($msg);
        if(!$this->isValidStruct($data)) {return;}

        if($data->cmd=='register')
        {
            $this->manager->register_user($sender,$data->args->auth,$data->args->user,$data->args->hash);
        }
        else if($data->cmd=='get_verify')
        {
            $this->manager->get_verify($sender);
        }
        else if($data->cmd=='login')
        {
            $this->manager->login($sender,$data->args->user,$data->args->hash);
        }
        else if($data->cmd=='logout')
        {
            $this->manager->logout($sender);
        }
        else if($data->cmd=='update')
        {
            $this->manager->update($sender,$data->args->user,$data->args->hash);
        }
    }
}

?>