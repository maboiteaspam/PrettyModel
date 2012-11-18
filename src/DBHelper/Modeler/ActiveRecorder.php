<?php
namespace DBHelper\Modeler;
/**
 * ActiveRecorder is an intelligent
 * helper that is able to maintain
 * an image of your db in memory
 *
 * the main purpose was to reduce the
 * numbers of sql query
 */
class ActiveRecorder extends Decorator
{
    protected $table_list;
    protected $field_list;
    protected $index_list;
    public function __construct( ITableModeler $decorated ){
        $this->decorated = $decorated;
        $this->table_list = null;
        $this->field_list = array();
        $this->index_list = array();
    }
    function hasTable( $name ){
        return in_array($name, $this->listTables());
    }
    function hasField( $raw_table_name, $field_name ){
        return in_array($field_name, $this->listColumns($raw_table_name));
    }
    function hasIndex( $raw_table_name, $index_name ){
        return in_array($index_name, $this->listIndex($raw_table_name));
    }
    function createTable( $raw_table_name, $options=array() ){
        $retour = $this->decorated->createTable($raw_table_name, $options);
        if( $retour !== false ){
            $this->listTables();
            array_push ( $this->table_list , $raw_table_name );
        }
        return $retour;
    }
    function createField( $raw_table_name, $field_name, $options=array() ){
        $retour = $this->decorated->createField($raw_table_name, $field_name, $options);
        if( $retour !== false ){
            $this->listColumns($raw_table_name);
            array_push ( $this->field_list[$raw_table_name] , $field_name );
        }
        return $retour;
    }
    function createIndex( $raw_table_name, $index_name, $options=array() ){
        $retour = $this->decorated->createIndex($raw_table_name, $index_name, $options);
        if( $retour !== false ){
            $this->listIndex($raw_table_name);
            array_push ( $this->index_list[$raw_table_name] , $index_name );
        }
        return $retour;
    }
    function removeTable( $raw_table_name ){
        $retour = $this->decorated->removeTable($raw_table_name);
        if( $retour!==false && $this->table_list !== null )
            foreach(array_keys($this->table_list, $raw_table_name) as $k ) unset($this->table_list[$k]);
        return $retour;
    }
    function removeField( $raw_table_name, $field_name  ){
        $retour = $this->decorated->removeField($raw_table_name, $field_name);
        if( $retour!==false && isset($this->field_list[$raw_table_name]) )
            foreach(array_keys($this->field_list[$raw_table_name], $field_name) as $k ) unset($this->field_list[$raw_table_name][$k]);
        return $retour;
    }
    public function listTables(){
        if( $this->table_list === null )
            $this->table_list = $this->decorated->listTables();
        return $this->table_list;
    }
    public function listColumns( $table_name ){
        if( isset($this->field_list[$table_name]) === false )
            $this->field_list[$table_name] = $this->decorated->listColumns( $table_name );
        return $this->field_list[$table_name];
    }

    public function listIndex( $table_name ){
        if( isset($this->index_list[$table_name]) === false )
            $this->index_list[$table_name] = $this->decorated->listIndex( $table_name );
        return $this->index_list[$table_name];
    }
}
