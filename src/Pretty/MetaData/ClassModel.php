<?php
namespace Pretty\MetaData;

class ExportableArrayObject extends \ArrayObject{
    public static function __set_state($an_array) // As of PHP 5.1.0
    {
        $obj = new \ArrayObject($an_array);
        return $obj;
    }
}
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
class ClassModel extends ObjectMeta
{
    public function __construct(){
        parent::__construct();
        $this->properties = new ExportableArrayObject();
        $this->index = new ExportableArrayObject();
    }
    /**
     * @var string
     */
    public $class_name;
    /**
     * @var string
     */
    public $table_name;
    /**
     * @var string
     */
    public $encoding;
    /**
     * @var string
     */
    public $engine;
    /**
     * @var array
     */
    public $index;
    /**
     * name of the index as the pk
     * @var string
     */
    public $pk;
    /**
     * name of AI fields
     *
     * @var array
     */
    public $autoincrements;

    /**
     *
     * @var array
     */
    public $properties;

    public function getPKFields(){
        $foreign_pk_name = $this->pk;
        return $this->index[$foreign_pk_name]["fields"];
    }

    public function toString( $instance ){
        $retour = array(
            "table_name"=>$this->table_name,
            "class_name"=>$this->class_name,
            "properties"=>array(),
        );
        foreach( $this->properties as $prop_name=>$prop_meta ){
            /* @var $prop_meta Property */
            if( $prop_meta->property_type == "scalar" ){
                $retour["properties"][$prop_name] = $instance->$prop_name;

            }elseif( $prop_meta->property_type == "association" ){
                if( $prop_meta->association == "BelongsTo" ){
                    foreach(  $prop_meta->related_keys as $local_key=>$related_key ){
                        $retour["properties"][$related_key] = $instance->$local_key;
                    }
                }else if( $prop_meta->association == "HasOne" ){
                }
                $retour["associations"][$prop_name] = "".$prop_meta->association."(".$prop_meta->value_type.")";
            }

        }

        return var_export($retour, true);
    }
}
