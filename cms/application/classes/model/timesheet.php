<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

class Model_Timesheet extends Model_Base {
    public $_table_name = "timesheet";

    public static  function getDayRecord($cid,$y,$m,$d)
    {
        $q=new Model_Timesheet();
        $date="$y-$m-$d";
        $q=$q->where('contract_id','=',$cid);
        $q=$q->where('date','=',$date);
        return $q->find();
    }

    public static function countWorkDays($cid)
    {
        $q=new Model_Timesheet();
        $q=$q->where('contract_id','=',$cid);
        return $q->count_all();
    }
}