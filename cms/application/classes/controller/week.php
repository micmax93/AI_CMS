<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

require("CmsController.php");

class Controller_Week  extends CmsController {
    public function action_list()
    {
        echo "<form action='/cms/index.php/contract/view_summary'><input type='submit' value='back'></form>";
        $cid=$_SESSION['contract'];

        echo "Week list for contract " . (new Model_Contract())->getItem($cid)->name . ":<br>";
        $list=Model_Week::getOverhours($cid);
        $y=null;
        echo "<form action='test' method='get'>";
        echo "<table border='1'><tr>";
        foreach($list as $week)
        {
            if($y==null)
            {
                $y=$week->year;
                echo "<td> $y </td>";
                echo "<td><table border='1'>";
            }
            else if($y!=$week->year)
            {
                echo "</table></td></tr>";
                $y=$week->year;
                echo "<tr><td> $y </td>";
                echo "<td><table border='1'>";
            }
            echo "<tr>";
            $val = $y+($week->week*10000);
            echo "<td> $week->week w</td> <td>  $week->duration h</td>";
            echo "<td><button name='w' type='submit' value='$val'>Show</button></td>";
            echo "</tr>";
        }
        echo "</table></td></tr></table>";
        echo "</form>";
    }

    public function action_test()
    {
        $dt=$_GET['w'];
        $y=$dt%10000;
        $w=($dt-$y)/10000;
        $this->request->redirect("day/week_list?w=$w&y=$y");
    }
}