<?php defined('SYSPATH') or die('No direct script access.');

require("CmsController.php");
require("websocket.php");

class Controller_Cms extends CmsController
{
    //register(uname,hash,type)
    //login(uname,hash)
    //logout()/disconnect()
    //update(uid)
    //list

    public function action_index()
    {
        $widok = View::factory('index');
        $widok->set('title', 'CMS');

        $this->response->body($widok->render());
    }

    protected function rand_hash($len=10)
    {
        $str='';
        for($i=0;$i<$len;$i++)
        {
            $str= $str . chr(rand(40,120));
        }
        $hash=crypt($str,"rand_hash");
        return $hash;
    }

    public function action_get_code()
    {
        $tab['uname'] = Auth::instance()->get_user()->username;
        $tab['hash'] = $this->rand_hash();

        $ws = new WebSocketController();
        $ws->register($tab['uname'],$tab['hash']);

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($tab));
    }

}

