<?php
/**
 * Created by JetBrains PhpStorm.
 * User: phisikus
 * Date: 13.04.13
 * Time: 21:40
 * To change this template use File | Settings | File Templates.
 */

require("CmsController.php");
class Controller_User extends CmsController
{
    public function action_index()
    {
        $this->action_manage();
    }

    public function action_login()
    {
        $widok = View::factory('login');
        $widok->set('title', 'CMS');
        $this->response->body($widok->render());
    }


    public function action_authorize()
    {
        //$user = $muser->getUserByUsername('admin');
        //$mrole = new Model_Role();
        //$muser->setUserProfile(0,"admin","admin@admin.pl","Administrator","admin");
        //$mrole->setRoleProfile(0,"login","Podstawowa rola umożliwiająca logowanie");
        //$user->addRole('login');
        ;
        //$this->request->post('password');
        $muser = new Model_User();
        $user = $muser->getUserByUsername($this->request->post('username'));
        if ($user->id != 0) {
            if (Auth::instance()->login($user->username, $this->request->post('password')))
                $res["status"] = "ok";
            else
                $res["status"] = "fail";

        } else
            $res["status"] = "fail";


        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($res));
    }

    public function action_authorized()
    {
        echo "<body onload=\"javascript:window.location ='" . url::base() . "';\"> ";
    }

    public function action_manage()
    {
        $muser = new Model_User();
        $users = $muser->getAll();
        $view = View::factory('manageuser');
        $view->set('users', $users);
        $this->response->body($view->render());

    }


    public function action_logout()
    {
        Auth::instance()->logout();
        $this->request->redirect('user/login');
    }


    public function action_delete()
    {
        $id = $this->request->param('id');
        preg_replace('/[\s\W]+/', '-', $id);
        $muser = new Model_User();
        if ($muser->deleteUser($id)) {
            $this->response->body(json_encode(array("status" => "ok")));

        } else {
            $this->response->body(json_encode(array("status" => "fail")));
        }
    }

    public function action_set()
    {
        $id = $this->request->param('id');
        preg_replace('/[\s\W]+/', '-', $id);
        $arr["username"] = Arr::get($_POST, 'username');
        $arr["email"] = Arr::get($_POST, 'email');
        $arr["full_name"] = Arr::get($_POST, 'full_name');
        $arr["password"] = Arr::get($_POST, 'password');

        $muser = new Model_User();
        if ($muser->setUserProfile((int)$id, $arr["username"], $arr["email"],$arr["full_name"], $arr["password"])
        ) {
            $this->response->body(json_encode(array("status" => "ok")));

        } else {
            $this->response->body(json_encode(array("status" => "fail")));
        }
    }
}