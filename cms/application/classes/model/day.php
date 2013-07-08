<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

require("day_summary.php");
require("dayoff.php");

class Model_Day {
    protected $record=null;
    protected $summary=null;
    protected $leave=null;
    public $type='absent';
    public $cid,$y,$m,$d;
    public $date;
    public $description="";
    public $hours=0;
    public $factor=1;
    public $beg,$end;

    public function __construct($cid,$y,$m,$d)
    {
        $this->cid=$cid;
        $this->y=$y;
        $this->m=$m;
        $this->d=$d;
        $this->date="$y-$m-$d";
        $this->summary=Model_DaySummary::getDaySummary($cid,$y,$m,$d);
        if($this->summary!=null)
        {
            $this->type=$this->summary->type;
            $this->description=$this->summary->description;
            $this->hours=$this->summary->duration;
            if($this->type=='present')
            {
                $this->record=Model_Timesheet::getDayRecord($cid,$y,$m,$d);
                $this->beg=$this->record->start_hour;
                $this->end=$this->record->end_hour;
            }
            else
            {
                $this->leave=Model_Dayoff::getDayOff($cid,$y,$m,$d);
                $this->factor=$this->leave->hour_factor;
            }
        }
        else
        {
            $w=new DateTime($this->date);
            $w=$w->format("w")%6;
            if($w==0)
            {
                $this->type='day off';
                $this->description="weekend";
            }
        }
    }

    public static function getMonthList($cid,$y,$m)
    {
        $list=array();
        $len=cal_days_in_month(CAL_GREGORIAN,$m,$y);
        for($i=1;$i<=$len;$i++)
        {
            array_push($list,new Model_Day($cid,$y,$m,$i));
        }
        return $list;
    }

    public static function getWeekList($cid,$y,$w)
    {
        $week=Model_DaySummary::getWeekList($cid,$y,$w);
        $list=array();
        for($i=0;$i<count($week);$i++)
        {
            array_push($list,new Model_Day($cid,$y,$week[$i]->month,$week[$i]->day));
        }
        return $list;
    }

    public function toString()
    {
        $str="$this->date:  $this->type  hours=$this->hours   >>$this->description";
        return $str;
    }

    public function save()
    {
        if($this->type=='absent')
        {
            if($this->record!=null)
            {
                $this->record->delete();
                $this->record=null;
            }
            if($this->leave!=null)
            {
                $this->leave->delete();
                $this->leave=null;
            }
        }
        else if($this->type=='present')
        {
            if($this->leave!=null)
               {$this->leave->delete();}
            if($this->record==null)
            {
                $this->record=new Model_Timesheet();
                $this->record->contract_id=$this->cid;
                $this->record->date=$this->date;
            }
            $this->record->start_hour=$this->beg;
            $this->record->end_hour=$this->end;
            $this->record->description=$this->description;
            $this->record->save();
        }
        else
        {
            if($this->record!=null)
                {$this->record->delete();}
            if($this->leave==null)
            {
                $this->leave=new Model_Dayoff();
                $this->leave->contract_id=$this->cid;
                $this->leave->date=$this->date;
            }
            $this->leave->type=$this->type;
            $this->leave->descryption=$this->description;
            $this->leave->hour_factor=$this->factor;
            $this->leave->save();
        }
    }
}