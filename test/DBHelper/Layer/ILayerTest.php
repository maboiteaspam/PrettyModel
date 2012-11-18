<?php
namespace DBHelper\Layer;
/**
 */
class ILayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBHelper\Layer\ILayer
     */
    protected $object;

    protected function setUp()
    {
        $this->markTestSkipped();
    }

    function testExec(){

        $this->assertEquals(true,
            $this->object->exec("DROP TABLE IF EXISTS test_layer")!==false,
            "exec must not return false when deleting a known table");

        $this->assertEquals( 0,
            $this->object->exec(" CREATE TABLE test_layer ( `test_field` text NOT NULL ) "),
            "exec must return 0 for a create table"
        );

        $this->assertEquals( 0,
            $this->object->exec(" CREATE TABLE IF NOT EXISTS test_layer ( `test_field` text NOT NULL ) "),
            "exec must return the number of affected raws"
        );


        $this->assertEquals( 1,
            $this->object->exec(" INSERT INTO test_layer ( test_field ) VALUES ( 'test' ) "),
            "exec must return the correct number of rows after an INSERT"
        );

        try {
            $this->object->exec(" CREA TE TABLE tes t_layer ( `test_field` text NOT NUcLL ) ");
        }
        catch (\DBHelper\SQLException $attendu) {
            return;
        }
        $this->fail("Layer must throw a \DBHelper\SQLException it fail to exec a sql order.");

    }

    function testQuery(){

        $this->assertEquals(true,
            $this->object->exec("DROP TABLE IF EXISTS test_layer")!==false,
            "exec must not return false when deleting a known table");

        $this->object->exec(" CREATE TABLE IF NOT EXISTS test_layer ( `test_field` text NOT NULL ) ");
        $this->object->exec(" TRUNCATE TABLE test_layer ");

        $this->assertNotEquals(false,
            $this->object->query("SELECT * FROM test_layer"),
            "query must not return false for empty result sets.");

        $this->object->exec(" INSERT INTO test_layer VALUES ( 'test' ) ");

        $this->assertNotEquals(false,
            $this->object->query("SELECT * FROM test_layer"),
            "query must not return false for result sets.");

        try {
            $this->object->exec(" SELECT not_a_field FROM not_a_table ");
        }
        catch (\DBHelper\SQLException $attendu) {
            return;
        }

        $this->fail("Layer must throw a \DBHelper\SQLException it fail to query a sql order.");
    }

    function testGet_resource(){

        $this->assertNotEquals(false, (
                is_resource($this->object->get_resource()) || is_object($this->object->get_resource())),
            "resource must be an object or a resource.");
    }

}