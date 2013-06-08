<?php
/**
 * Created by JetBrains PhpStorm.
 * User: micmax93
 * Date: 18.03.13
 */
class Model_Base extends ORM
{
    public function getItem($id)
    {
        return ORM::factory($this->_table_name)->where('id','=',$id)->find();
    }
    public function getAllItems()
    {
        return ORM::factory($this->_table_name)->find_all();
    }
    public function newQuery()
    {
        return ORM::factory($this->_table_name);
    }
}
