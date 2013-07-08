<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

require("contract_summary.php");
require("month_.php");

class Model_Contract extends Model_Base {
    public $_table_name = "contract";
    //id serial NOT NULL,
    //user_id integer,
    //start_date date,
    //end_date date,
    //hours_per_day integer,
    //name character varying(50),

    public static function newContract($name,$start,$end,$hpw)
    {
        $c = new Model_Contract();
        $c->name=$name;
        $c->start_date=$start;
        $c->end_date=$end;
        $c->hours_per_day=$hpw/5;
        $c->user_id=Auth::instance()->get_user()->id;
        $c->save();
    }

    public static function getUsersContracts($uid)
    {
        $q=new Model_Contract();
        $q=$q->where('user_id','=',$uid);
        return $q->find_all();
    }

    public static function getContractSummary($cid)
    {
        return (new Model_ContractSummary())->where('contract_id','=',$cid)->find();
    }

    public static function getWeeks($cid)
    {
        return Model_Week::getContractsWeeks($cid);
    }
}