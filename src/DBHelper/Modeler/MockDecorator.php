<?php
namespace DBHelper\Modeler;

use DBHelper\Layer\MockLayer as MockLayer;

/**
 * A Mock decorator for
 * ITableModeler instances replacement
 */
class MockDecorator implements ITableModeler
{
    function setContainerName( $name ){
    }
    function getLayer( ){
        return new MockLayer();
    }
    function hasTable( $name ){
        return false;
    }
    function hasField( $raw_table_name, $field_name ){
        return false;
    }
    function hasIndex( $raw_table_name, $index_name ){
        return false;
    }
    function createTable( $raw_table_name, $options=array() ){
        return 1;
    }
    function createField( $raw_table_name, $field_name, $options=array() ){
        return 1;
    }
    function createIndex( $raw_table_name, $index_name, $options=array() ){
        return 1;
    }
    function removeTable( $raw_table_name ){
        return 1;
    }
    function removeField( $raw_table_name, $field_name  ){
        return 1;
    }
    function clean( $raw_table_name ){
        return 1;
    }
    public function listTables(){
        return array();
    }
    public function listColumns( $table_name ){
        return array();
    }
    public function listIndex( $table_name ){
        return array();
    }
    function updateField($table_name, $field_name, $field_table){
        return false;
    }
    function purge(){
        return 0;
    }
}