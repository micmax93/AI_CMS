<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

require("CmsController.php");

class Controller_Day extends CmsController {
    public function action_month_list()
    {
        echo "<form action='/cms/index.php/month/list'><input type='submit' value='back'></form>";
        $cid=$_SESSION['contract'];
        $m=$_GET['m'];
        $y=$_GET['y'];
        $list=Model_Day::getMonthList($cid,$y,$m);
        echo "<form action='summary' method='get'>";
        echo "<table border='1'><tr><td>date</td><td>type</td><td>hours</td><td>description</td><td>edit</td></tr>";
        foreach($list as $e)
        {
            echo "<tr>";
            echo "<td>$e->date</td>";
            echo "<td>$e->type</td>";
            echo "<td>$e->hours</td>";
            echo "<td>\"$e->description\"</td>";
            echo "<td><button type='submit' name='date' value='$e->date'>edit</button></td>";
            echo "</tr>";
        }
        echo "</table></form>";
    }

    public function action_week_list()
    {
        echo "<form action='/cms/index.php/week/list'><input type='submit' value='back'></form>";
        $cid=$_SESSION['contract'];
        $w=$_GET['w'];
        $y=$_GET['y'];
        $list=Model_Day::getWeekList($cid,$y,$w);
        echo "<form action='summary' method='get'>";
        echo "<table border='1'><tr><td>date</td><td>type</td><td>hours</td><td>description</td><td>edit</td></tr>";
        foreach($list as $e)
        {
            echo "<tr>";
            echo "<td>$e->date</td>";
            echo "<td>$e->type</td>";
            echo "<td>$e->hours</td>";
            echo "<td>\"$e->description\"</td>";
            echo "<td><button type='submit' name='date' value='$e->date'>edit</button></td>";
            echo "</tr>";
        }
        echo "</table></form>";
    }

    public function action_back()
    {
        if(isset($_GET['mdate']))
        {
            $date=new DateTime($_GET['mdate']);
            $y=$date->format("Y");
            $m=$date->format("m");
            $this->request->redirect("day/month_list?y=$y&m=$m");
        }
        if(isset($_GET['ddate']))
        {
            $date=$_GET['ddate'];
            $this->request->redirect("day/summary?date=$date");
        }
    }

    public function action_summary()
    {
        $cid=$_SESSION['contract'];
        $date=new DateTime($_GET['date']);
        $dt=$_GET['date'];
        echo "<form action='back'><button type='submit' name='mdate' value='$dt'>month view</button></form>";
        $day=new Model_Day($cid,$date->format("Y"),$date->format("m"),$date->format("d"));
        echo "Date: $day->date<br>";
        echo "Type: $day->type<br>";
        echo "Hours: $day->hours ";
        if($day->type=='present') {echo " (from $day->beg till $day->end)";}
        echo "<br>Descryption: $day->description<br><hr>";
        echo "<form action='edit' method='get'>";
        echo "Edit: ";
        echo "<button type='submit' name='absent' value='$day->date'>Absent</button>";
        echo "<button type='submit' name='present' value='$day->date'>Present</button>";
        echo "<button type='submit' name='onleave' value='$day->date'>On leave</button>";
        //echo "<button type='submit' name='medical leave' value='$day->date'>Medical leave</button>";
        //echo "<button type='submit' name='day off' value='$day->date'>Day off</button>";
        echo "</form>";
    }

    public function action_edit()
    {
        if(isset($_GET['absent']))
        {
            $cid=$_SESSION['contract'];
            $dt=$_GET['absent'];
            $date=new DateTime($dt);
            $day=new Model_Day($cid,$date->format("Y"),$date->format("m"),$date->format("d"));
            $day->type='absent';
            $day->save();
            $this->request->redirect("day/summary?date=$dt");
        }
        else if(isset($_GET['present']))
        {
            $date=$_GET['present'];
            echo "<form action='back'><button type='submit' name='ddate' value='$date'>back</button></form>";
            echo "<form action='present' method='get'>";
            echo "Present: <br>";
            echo "Working from <input name='start' type='number' value='8' size='2'>";
            echo " till <input name='end' type='number' value='16' size='2'><br>";
            //echo "<input type='text' name='description' placeholder='description' ><br>";
            echo "<textarea name='description' placeholder='description' cols='30' rows='1' maxlength='250'></textarea><br>";
            echo "<button type='submit' name='date' value='$date'>Save</button>";
            echo "</form>";
        }
        else if(isset($_GET['onleave']))
        {
            $date=$_GET['onleave'];
            echo "<form action='back'><button type='submit' name='ddate' value='$date'>back</button></form>";
            echo "<form action='leave' method='get'>";
            echo "Leave: <select name='type'>
                             <option value='on leave' selected>vacation</option>
                             <option value='medical leave'>medical</option>
                             <option value='day off'>day off</option>
                         </select><br>";
            echo "(<input name='factor' type='radio' value='1' checked>Count /
                   <input name='factor' type='radio' value='0'>Don't count)
                   this day into working hours<br>";
            echo "<textarea name='description' placeholder='description' cols='30' rows='1' maxlength='250'></textarea><br>";
            echo "<button type='submit' name='date' value='$date'>Save</button>";
            echo "</form>";
        }
    }

    public function action_present()
    {
        $cid=$_SESSION['contract'];
        $dt=$_GET['date'];
        $date=new DateTime($dt);
        $day=new Model_Day($cid,$date->format("Y"),$date->format("m"),$date->format("d"));
        $day->type='present';
        $day->beg=$_GET['start'];
        $day->end=$_GET['end'];
        $day->description=$_GET['description'];
        $day->save();
        $this->request->redirect("day/summary?date=$dt");
    }
    public function action_leave()
    {
        $cid=$_SESSION['contract'];
        $dt=$_GET['date'];
        $date=new DateTime($dt);
        $day=new Model_Day($cid,$date->format("Y"),$date->format("m"),$date->format("d"));
        $day->type=$_GET['type'];
        $day->factor=$_GET['factor'];
        $day->description=$_GET['description'];
        $day->save();
        $this->request->redirect("day/summary?date=$dt");
    }
}