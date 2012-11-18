<?php
namespace DBHelper\Modeler;
/**
 * Base class to support decoration
 * of ITableModeler instances
 */
abstract class Decorator implements ITableModeler
{
    protected $decorated;
    public function __construct( ITableModeler $decorated ){
        $this->decorated = $decorated;
    }

    public function getLayer(){
        return $this->decorated->getLayer();
    }
    function setContainerName( $name ){
        return $this->decorated->setContainerName($name);
    }
    function hasTable( $name ){
        return $this->decorated->hasTable($name);
    }
    function hasField( $raw_table_name, $field_name ){
        return $this->decorated->hasField($raw_table_name, $field_name);
    }
    function hasIndex( $raw_table_name, $index_name ){
        return $this->decorated->hasIndex($raw_table_name, $index_name);
    }
    function createTable( $raw_table_name, $options=array() ){
        return $this->decorated->createTable($raw_table_name, $options);
    }
    function createField( $raw_table_name, $field_name, $options=array() ){
        return $this->decorated->createField($raw_table_name, $field_name, $options);
    }
    function createIndex( $raw_table_name, $index_name, $options=array() ){
        return $this->decorated->createIndex($raw_table_name, $index_name, $options);
    }
    function removeTable( $raw_table_name ){
        return $this->decorated->removeTable($raw_table_name);
    }
    function removeField( $raw_table_name, $field_name  ){
        return $this->decorated->removeField($raw_table_name, $field_name);
    }
    function clean( $raw_table_name ){
        return $this->decorated->clean($raw_table_name);
    }
    function updateField($table_name, $field_name, $field_table){
        return $this->decorated->updateField($table_name, $field_name, $field_table);
    }
    function purge(){
        return $this->decorated->purge();
    }
}
