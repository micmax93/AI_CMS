<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */
class CommunicationInterpreter
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new SecureLoginManager();
    }

    public function newChanel($chanelId, $ws)
    {
        $this->manager->newConnection($chanelId, $ws);
    }

    public function chanelDisconnected($chanelId)
    {
        $this->manager->closeConnection($chanelId);
    }

    function isValidStruct($data)
    {
        return (isset($data->cmd) and isset($data->args));
    }

    public function messageReceived($sender, $msg)
    {
        $data = json_decode($msg);
        if (!$this->isValidStruct($data)) {
            return;
        }

        if ($data->cmd == 'register') {
            $this->manager->registerUser($sender, $data->args->auth, $data->args->user, $data->args->hash);
        } else if ($data->cmd == 'get_verify') {
            $this->manager->getVerify($sender);
        } else if ($data->cmd == 'login') {
            $this->manager->login($sender, $data->args->user, $data->args->hash);
        } else if ($data->cmd == 'logout') {
            $this->manager->logout($sender);
        } else if ($data->cmd == 'update') {
            $this->manager->update($sender, $data->args->user, $data->args->hash);
        }
    }
}