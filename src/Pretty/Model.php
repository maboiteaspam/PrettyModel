<?php
namespace Pretty;

use Pretty\MetaData\Property as Property;

abstract class Model{

    /**
     * instantiate a new ModelORM
     * for a Model class
     *
     *
     * @param $class_name
     * @param null $row
     * @return ModelORM
     */
    public static function wrapper( $class_name, $row = null ){
        $meta_data  = Facade::get(null)->get_meta_data( $class_name );

        $orm = ModelORM::for_table($meta_data->table_name);
        $orm->set_class_name($class_name);

        $ai_field = $meta_data->autoincrements;
        if( $ai_field !== false )
            $orm->use_id_column($ai_field[0]);

        if( $row !== null )
            $orm->hydrate($row);

        return $orm;
    }

    public static function query(){
        return Model::wrapper( get_called_class() );
    }

    /**
     * @var \Pretty\MetaData\ClassModel
     */
    private $meta_data;
    /**
     * @var ModelORM
     */
    private $orm;

    public function __construct(){
        $this->init_model();
    }

    private function init_model(){
        $class_name = get_class($this);
        $this->meta_data = Facade::get(null)->get_meta_data( $class_name );

        $orm = Model::wrapper( $class_name );
        $orm->set_new( $this->unset_all() );
        $this->set_orm( $orm );
    }

    public function unset_all(){
        $current_values = array();
        foreach( $this->meta_data->properties as $p ){
            /* @var $p Property */
            unset( $this->{$p->property_name} );
            if( $p->property_type === "scalar" ){
                if( $p->default_value !== null )
                    $current_values[ $p->property_name ] = $p->default_value;
            }
        }
        return $current_values;
    }

    /**
     * Set the wrapped ORM instance associated with this Model instance.
     * ORMWrapper will set it for us JIT
     */
    public function set_orm($orm) {
        $this->orm = $orm;
    }

    public function __get($property){
        $value = null;
        if( $this->orm !== NULL && $this->orm->__isset($property) ){
            $value = $this->orm->get($property);
            if( isset($this->meta_data->properties[$property]) ){
                $firewall   = $this->meta_data->properties[$property]->firewall["get"];
                $value      = $firewall($this,$value);
            }
        }elseif( isset($this->meta_data->properties[$property]["default_value"]) ){
            $value      = $this->meta_data->properties[$property]["default_value"];
            $firewall   = $this->meta_data->properties[$property]->firewall["get"];
            $value      = $firewall($this,$value);
        }elseif( isset($this->meta_data->properties[$property]) ){
            if( $this->meta_data->properties[$property]->property_type == "association"){
                $firewall   = $this->meta_data->properties[$property]->firewall["get"];
                $value      = $firewall($this);
            }
        }
        return $value;
    }

    public function __set($property, $value){
        if( isset($this->meta_data->properties[$property]) ){
            $firewall   = $this->meta_data->properties[$property]->firewall["set"];
            $value      = $firewall($this,$value);
            if( $this->orm === NULL )
                $this->set_orm( Model::wrapper( get_class($this) ) );
            $this->orm->set($property, $value);
        }

    }

    /**
     * Check whether the given field has changed since the object was created or saved
     */
    public function is_dirty($property) {
        return $this->orm->is_dirty($property);
    }

    /**
     * Wrapper for Idiorm's as_array method.
     */
    public function as_array() {
        $args = func_get_args();
        return call_user_func_array(array($this->orm, 'as_array'), $args);
    }

    /**
     * Save the data associated with this model instance to the database.
     */
    public function save() {
        return $this->orm->save();
    }

    /**
     * Delete the database row associated with this model instance.
     */
    public function delete() {
        return $this->orm->delete();
    }

    /**
     * Get the database ID of this model instance.
     */
    public function id() {
        return $this->orm->id();
    }

    /**
     * Hydrate this model instance with an associative array of data.
     * WARNING: The keys in the array MUST match with columns in the
     * corresponding database table. If any keys are supplied which
     * do not match up with columns, the database will throw an error.
     */
    public function hydrate($data, $is_dirty=true) {
        $data_to_use = array();
        foreach( $data as $key=>$value ){
            if( isset($this->meta_data["properties"][$key]) ){
                $firewall   = $this->meta_data["properties"][$key]["firewall"]["set"];
                $value      = $firewall($this, $value);
                $data_to_use[$key] = $value;
            }
        }
        $this->orm->hydrate($data_to_use)->force_all_dirty();
        if( $is_dirty )
            $this->orm->force_all_dirty();
    }

    /**
     */
    public function set_known($data) {
        $this->orm->set_known( $data );
        return $this;
    }

    public function __toString(){
        return $this->meta_data->toString($this);
    }
}
