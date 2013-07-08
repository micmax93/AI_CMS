<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

class Model_DaySummary extends Model_Base {
    public $_table_name = "daily_summary";

    function isValid()
    {
        return isset($this->user_id);
    }

    public static function getDaySummary($cid,$y,$m,$d)
    {
        $q=new Model_DaySummary();
        $query=$q->where('contract_id','=',$cid)->where('year','=',$y)->where('month','=',$m)->where('day','=',$d);
        $result=$query->find();
        if($result->isValid())
        {
            return $result;
        }
        else
        {
            return null;
        }
    }

    public static  function getWeekList($cid,$y,$w)
    {
        $q=new Model_DaySummary();
        $q=$q->where('contract_id','=',$cid);
        $q=$q->where('year','=',$y);
        $q=$q->where('week','=',$w);
        return $q->find_all();
    }
}