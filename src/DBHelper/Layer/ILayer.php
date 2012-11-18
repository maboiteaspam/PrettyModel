<?php
namespace DBHelper\Layer;
/**
 *
 * @author clement
 */
interface ILayer {

    /**
     * Execute a query
     *
     * @param $sql
     * @return int|false
     */
    function exec($sql);


    /**
     * Read a query
     * and return results
     *
     * @param $sql
     * @return Traversable|false
     */
    function query($sql);


    /**
     * Return the provided
     * handle required to
     * execute request on the server
     *
     * @return mixed
     */
    function get_resource();

}

