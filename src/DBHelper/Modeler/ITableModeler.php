<?php
namespace DBHelper\Modeler;
/**
 * TableModeler is a class
 * that knows how to model
 * a table according
 * to a database type
 *
 * @author clement
 */
interface ITableModeler {
    /**
     * Name of the database to modify
     * @param $name
     * @return mixed
     */
    function setContainerName( $name );
    /**
     * @param $name
     * @return bool
     */
    function hasTable( $name );
    /**
     * @param $raw_table_name
     * @param $field_name
     * @return bool
     */
    function hasField( $raw_table_name, $field_name );
    /**
     * @param $raw_table_name
     * @param $index_name
     * @return bool
     */
    function hasIndex( $raw_table_name, $index_name );
    /**
     * @param $raw_table_name
     * @param array $options
     * @return int|false
     */
    function createTable( $raw_table_name, $options=array() );
    /**
     * @param $raw_table_name
     * @param $field_name
     * @param array $options
     * @return int|false
     */
    function createField( $raw_table_name, $field_name, $options=array() );

    /**
     * @param $raw_table_name
     * @param $index_name
     * @param array $options
     * @return int|false
     */
    function createIndex( $raw_table_name, $index_name, $options=array() );

    /**
     * @param $raw_table_name
     * @return int|false
     */
    function removeTable( $raw_table_name );

    /**
     * @param $raw_table_name
     * @param $field_name
     * @return int|false
     */
    function removeField( $raw_table_name, $field_name  );

    /**
     * @param $table_name
     * @param $field_name
     * @param $field_table
     * @return int|false
     */
    function updateField($table_name, $field_name, $field_table);

    /**
     * It is required to call this method
     * at least after the first concrete field added
     *
     * This is because the table is created with
     * a default first field
     * this automatic field need to be cleaned
     *
     * @param $raw_table_name
     * @return mixed
     */
    function clean( $raw_table_name );

    /**
     * DROP all tables
     * Return number of dropper tables
     *
     * @return int
     */
    function purge(  );

    /**
     * @return \DBHelper\Layer\ILayer
     */
    public function getLayer();

    /**
     * @return array
     */
    public function listTables();

    /**
     * @param $table_name
     * @return array
     */
    public function listColumns( $table_name );

    /**
     * @param $table_name
     * @return array
     */
    public function listIndex( $table_name );
}

