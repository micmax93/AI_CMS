<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */
class ConnectionManager
{
    protected $conn_list = array();

    function getUname($cid)
    {
        return $this->conn_list[$cid]['name'];
    }

    public function newConnection($uid, $uws)
    {
        $this->conn_list[$uid]['ws'] = $uws;
        $this->conn_list[$uid]['name'] = null;
        $this->say("New user id=$uid");
    }

    public function closeConnection($cid)
    {
        unset($this->conn_list[$cid]);
        $this->say("Removing user id=$cid");
    }

    function sendData($cid, $data)
    {
        $msg = new WebSocketMessage();
        $msg->setData(json_encode($data));
        $this->conn_list[$cid]['ws']->sendMessage($msg);
    }

    function bcastData($data, $root = null)
    {
        $msg = new WebSocketMessage();
        $msg->setData(json_encode($data));
        foreach ($this->conn_list as $val) {
            if (($val['name'] != null) && ($val['name'] != $root)) {
                $val['ws']->sendMessage($msg);
            }
        }
    }

    protected function say($msg)
    {
        echo '<CMD>' . "$msg \r\n";
    }
}