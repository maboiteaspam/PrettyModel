<?php
namespace Pretty;

abstract class Association{
    /**
     * @var ModelORM
     */
    protected $orm;
    /**
     * @var Model
     */
    protected $model;
    protected $source_class;
    protected $source_property;
    protected $target_class;
    protected $target_property;

    public function __construct( $model, $source_class,  $source_property, $target_class, $target_property ){
        $this->source_class = $source_class;
        $this->source_property = $source_property;
        $this->target_class = $target_class;
        $this->target_property = $target_property;
        $this->model = $model;
        $this->apply_association();
    }
    public function cache(){
        return $this;
    }
    public abstract function find();
    protected abstract function apply_association();




    // region ORM like methods
    public function count() {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function select($column, $alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function select_expr($expr, $alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function distinct() {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function join($table, $constraint, $table_alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function inner_join($table, $constraint, $table_alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function left_outer_join($table, $constraint, $table_alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function right_outer_join($table, $constraint, $table_alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function full_outer_join($table, $constraint, $table_alias=null) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
        return $this;
    }
    public function where_equal($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_not_equal($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_id_is($id) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args()); return $this;
    }
    public function where_like($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args()); return $this;
    }
    public function where_not_like($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args()); return $this;
    }
    public function where_gt($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args()); return $this;
    }
    public function where_lt($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args()); return $this;
    }
    public function where_gte($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args()); return $this;
    }
    public function where_lte($column_name, $value) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_in($column_name, $values) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_not_in($column_name, $values) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_null($column_name) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_not_null($column_name) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function where_raw($clause, $parameters=array()) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function limit($limit) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function offset($offset) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function order_by_desc($column_name) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function order_by_asc($column_name) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function group_by($column_name) {
        call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());         return $this;
    }
    public function print_query() {
        return call_user_func_array(array($this->orm,__FUNCTION__), func_get_args());
    }
    // endregion
}
