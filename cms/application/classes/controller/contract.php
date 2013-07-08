<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

require("CmsController.php");

class Controller_Contract extends CmsController {

    public function action_index()
    {
        $url = URL::base() . "/index.php/user/logout";
        echo "<form action='$url'><input type='submit' value='logout'></form>";
        $uid=Auth::instance()->get_user()->id;
        $clist = Model_Contract::getUsersContracts($uid);
        echo "<form method='post' action='/cms/index.php/contract/set_contract'>
        Choose contract:<br><select name='cid'>";
        echo "<option value='-1'> new contract </option>";
        foreach($clist as $c)
        {
            if((isset($_SESSION['contract']))&&($_SESSION['contract']==$c->id))
                echo "<option value='$c->id' selected> $c->name </option>";
            else
                echo "<option value='$c->id'> $c->name </option>";
        }
        echo "</select><input type='submit' value='submit'></form>";
    }

    public function action_set_contract()
    {
        if($_POST['cid']==-1)
            $this->action_new_contract();
        else
        {
            $_SESSION['contract']=$_POST['cid'];
            $this->request->redirect('contract/view_summary');
        }
    }

    public function action_new_contract()
    {
        if(!isset($_POST['new_contract']))
        {
            echo "<form method='post' action='new_contract'>
            Name: <input name='name' type='text' value='Staż'><br>
            Begin: <input name='start' type='date' value='2013-07-01'><br>
            End: <input name='end' type='date' value='2013-09-30'><br>
            Hours per week: <input name='hpw' type='number' value='30'><br>
            <input name='new_contract' type='submit' value='submit'></form>";
        }
        else
        {
            Model_Contract::newContract($_POST['name'],$_POST['start'],$_POST['end'],$_POST['hpw']);
            $this->request->redirect('contract/index');
        }
    }

    public function action_view_summary()
    {
        $url = URL::base() . "/index.php";
        echo "<form action='index'><input type='submit' value='home'></form>";
        $cid=$_SESSION['contract'];
        $sum=Model_Contract::getContractSummary($cid);
        echo "$sum->hours h / $sum->todo h <br>";
        echo "Contract completion: " . floor(100*$sum->hours/$sum->todo) . "%<br>";
        echo "Days at work: " . Model_Timesheet::countWorkDays($cid) . "<br>";
        echo "Days on vacation: " . Model_Dayoff::countVacation($cid) . "<br>";
//        echo "Months completed: <br>";
//        echo "Months yet to be completed: <br>";
//        echo "Months with overhours: <br>";
        echo "Weeks with overhours: " . Model_Contract::getWeeks($cid)->countOverhours() . " <br><hr>";
        echo "<form action='$url/month/list'><input type='submit' value='Month Summary'></form>";
        echo "<form action='$url/week/list'><input type='submit' value='Weeks with overhours'></form>";
    }
}