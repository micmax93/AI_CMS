<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 * Date: 03.04.13
 */
require("lib.websocket.php");

class SimpleWebSocketClient
{
    public $socket;
    public $isOpen;

    public function __construct($addr="ws://127.0.0.1:12345/echo/")
    {
        $this->socket = new WebSocket($addr);
        $this->isOpen=true;

        try {
            $this->socket->open();
        }
        catch(Exception $exception)
        {
            //echo $exception->getMessage();
            $this->isOpen=false;
        }
    }

    public function __destruct()
    {
        if(!$this->isOpen) {return;}
        $this->socket->close();
    }

    public function sendMsg($msg)
    {
        if(!$this->isOpen) {return;}
        $ws_msg = WebSocketMessage::create($msg);
        $this->socket->sendMessage($ws_msg);
    }

    public function recvMsg()
    {
        if(!$this->isOpen) {return;}
        $msg = $this->socket->readMessage();
        return $msg->getData();
    }
}

class WebSocketBroadcastAdmin
{
    public $ws;
    public $adminCode,$adminHash;
    function encodeString($str)
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
    function makeJsonMsg($cmd,$type,$id,$hash=null)
    {
        $msg['cmd']=$cmd;
        $msg['type']=$type;
        $msg['id']=$id;
        if($hash!=null)
        {
            $msg['hash']=$hash;
        }
        return json_encode($msg);
    }
    public function __construct()
    {
        $this->ws=new SimpleWebSocketClient();
        if(!$this->ws->isOpen)
        {
            throw new Exception('Unable to open connection!');
        }
        $this->ws->sendMsg($this->makeJsonMsg('register','admin',0));
        $this->adminCode=$this->ws->recvMsg();
        $this->adminHash=$this->encodeString($this->adminCode);
    }
    public function update($type,$id)
    {
        $msg=$this->makeJsonMsg('update',$type,$id,$this->adminHash);
        $this->ws->sendMsg($msg);
    }
    public static function single_update($type,$id)
    {
        (new WebSocketBroadcastAdmin())->update($type,$id);
    }
}