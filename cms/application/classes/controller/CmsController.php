<?php
/**
 * Created by: Marcin Biernacki <phisikus@platinum.linux.pl>
 * User: phisikus
 * Date: 15.03.13
 */

class CmsController extends Controller
{
    public function before()
    {
       if($this->request->controller() != "user")
       {
           if(!Auth::instance()->logged_in())
           {
               $this->request->redirect("user/login");
           }
       }
    }

}
