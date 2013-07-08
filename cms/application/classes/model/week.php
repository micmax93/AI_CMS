<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 */

class Model_Week extends Model_Base {
    public $_table_name = "week_summary";
    private $cid;

    public static function getContractsWeeks($cid)
    {
        $w=new Model_Week();
        $w->cid=$cid;
        return $w;
    }

    public function getSummary($y,$w)
    {
        $q=new Model_Week();
        $q=$q->where('contract_id','=',$this->cid);
        $q=$q->where('year','=',$y);
        $q=$q->where('week','=',$w);
        return $q->find();
    }

    public function filter($y=null,$w=null)
    {
        $q=new Model_Week();
        $q=$q->where('contract_id','=',$this->cid);
        if($y!=null) {$q=$q->where('year','=',$y);}
        if($w!=null) {$q=$q->where('week','=',$w);}
        return $q->find_all();
    }

    public static function getOverhours($cid)
    {
        $q=new Model_Week();
        $q=$q->where('contract_id','=',$cid);
        $q=$q->where('duration','>',40);
        return $q->find_all();
    }

    public function countOverhours()
    {
        return count(Model_Week::getOverhours($this->cid));
    }
}