<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

require("CmsController.php");

class Controller_Month extends CmsController {

    public function action_list()
    {
        echo "<form action='/cms/index.php/contract/view_summary'><input type='submit' value='back'></form>";
        $cid=$_SESSION['contract'];

        echo "Month list for contract " . (new Model_Contract())->getItem($cid)->name . ":<br>";
        $list=Model_Month::filter($cid);
        $y=null;
        echo "<form action='test' method='get'>";
        echo "<table border='1'><tr>";
        foreach($list as $month)
        {
            if($y==null)
            {
                $y=$month->year;
                echo "<td> $y </td>";
                echo "<td><table border='1'>";
            }
            else if($y!=$month->year)
            {
                echo "</table></td></tr>";
                $y=$month->year;
                echo "<tr><td> $y </td>";
                echo "<td><table border='1'>";
            }
            echo "<tr>";
            $val = ($y*12)+$month->month;
            echo "<td> $month->month m</td> <td>  $month->hours h/ $month->todo h </td>";
            echo "<td><button name='m' type='submit' value='$val'>Show</button></td>";
            echo "</tr>";
        }
        echo "</table></td></tr></table>";
        echo "</form>";
    }

    public function action_test()
    {
        $dt=$_GET['m'];
        $m=$dt%12;
        $y=($dt-$m)/12;
        $this->request->redirect("day/month_list?m=$m&y=$y");
    }


}