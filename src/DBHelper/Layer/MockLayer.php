<?php
namespace DBHelper\Layer;
/**
 */
class MockLayer implements ILayer
{
    protected $sql;

    public function __construct( ){
        $this->sql = array();
    }
    public function exec($sql){
        $this->sql[] = $sql;
        return true;
    }
    public function query($sql){
        $this->sql[] = $sql;
        return array(false);
    }
    public function get_resource(){
        return null;
    }
}
