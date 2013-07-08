<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

class Model_Month extends Model_Base {
    public $_table_name = "month_summary";
    //    contract_id
    //    user_id
    //    year
    //    moth
    //    hours
    //    todo

    public static  function getSummary($cid,$y,$m)
    {
        $q=new Model_Month();
        $q=$q->where('contract_id','=',$cid);
        $q=$q->where('year','=',$y);
        $q=$q->where('month','=',$m);
        return $q->find();
    }

    public static  function filter($cid,$y=null,$m=null)
    {
        $q=new Model_Month();
        $q=$q->where('contract_id','=',$cid);
        if($y!=null) {$q=$q->where('year','=',$y);}
        if($m!=null) {$q=$q->where('month','=',$m);}
        return $q->find_all();
    }

}