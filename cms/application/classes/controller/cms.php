<?php defined('SYSPATH') or die('No direct script access.');

require("CmsController.php");

class Controller_Cms extends CmsController
{
    public function action_index()
    {
        $this->request->redirect("/contract/");
    }
}

