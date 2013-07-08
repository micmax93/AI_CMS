<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

class Model_Dayoff extends Model_Base {
    public $_table_name = "days_off";

    public static  function getDayOff($cid,$y,$m,$d)
    {
        $q=new Model_Dayoff();
        $date="$y-$m-$d";
        $q=$q->where('contract_id','=',$cid);
        $q=$q->where('date','=',$date);
        return $q->find();
    }

    public static function countVacation($cid)
    {
        $q=new Model_Dayoff();
        $q=$q->where('contract_id','=',$cid);
        $q=$q->where('type','=','on leave');
        return $q->count_all();
    }

}