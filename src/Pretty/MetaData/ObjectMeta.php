<?php
namespace Pretty\MetaData;

class ObjectMeta implements \ArrayAccess {
    private $_obj_properties = array();
    private $_obj_values = array();
    public function __construct(){
        foreach( $this as $k=>$v){
            if( in_array($k,array("_obj_properties","_obj_values")) == false ){
                $this->_obj_properties[] = $k;
                if( $v !== null )
                    $this->_obj_values[$k] = $v;
                unset($this->$k);
            }
        }
    }
    public static function __set_state($an_array) // As of PHP 5.1.0
    {
        $class = get_called_class();
        $obj = new $class();
        foreach( $an_array as $k=>$v )
            $obj->$k = $v;
        return $obj;
    }
    public function is_defined( $k ){
        return isset($this->_obj_values[$k]);
    }
    public function __get($p){
        if( in_array($p,$this->_obj_properties) && isset($this->_obj_values[$p]))
            return $this->_obj_values[$p];
        return null;
    }
    public function __set($p,$v){
        if( in_array($p,$this->_obj_properties))
            $this->_obj_values[$p] = $v;
    }
    public function __isset($p) {
        return isset($this->_obj_values[$p]);
    }
    public function __unset($p) {
        unset($this->_obj_values[$p]);
    }
    public function offsetSet($p, $v) {
        if (is_null($p)) {
            $this->container[] = $v;
        } else {
            if( in_array($p,$this->_obj_properties)){
                $this->_obj_values[$p] = $v;
            }
        }
    }
    public function offsetExists($p) {
        return isset($this->_obj_values[$p]);
    }
    public function offsetUnset($p) {
        unset($this->_obj_values[$p]);
    }
    public function offsetGet($p) {
        if( in_array($p,$this->_obj_properties) && isset($this->_obj_values[$p]))
            return $this->_obj_values[$p];
        return null;
    }
    public function to_array() {
        return $this->_obj_values;
    }
}
