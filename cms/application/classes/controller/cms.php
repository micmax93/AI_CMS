<?php defined('SYSPATH') or die('No direct script access.');

require("CmsController.php");

class Controller_Cms extends CmsController
{
    public function action_index()
    {
        $widok = View::factory('index');
        $widok->set('title', 'CMS');

        $this->response->body($widok->render());
    }
}

