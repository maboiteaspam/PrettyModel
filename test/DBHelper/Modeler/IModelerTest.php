<?php
namespace DBHelper\Modeler;
/**
 */
class IModelerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBHelper\Modeler\ITableModeler
     */
    protected $object;

    protected function setUp()
    {
        $this->markTestSkipped();
    }

    function testTable(){

        $this->object->purge();

        $this->assertEquals(false,
            $this->object->hasTable("test_table"),
            "hasTable must return false when the table does not exists");

        $this->assertEquals(true,
            $this->object->createTable("test_table"),
            "createTable must return true when the table is created with success");

        $this->assertEquals(true,
            $this->object->hasTable("test_table"),
            "hasTable must return true when table exists");

        $this->assertEquals(true,
            $this->object->hasField("test_table", "required_first_field"),
            "createTable must have a first default field named required_first_field");


        $this->assertEquals(1,
            $this->object->purge(),
            "purge must return the correct number of deleted tables");


        $this->object->createTable("test_table");

        $this->assertEquals(true,
            $this->object->removeTable("test_table"),
            "removeTable must return true when succeed");

        $this->object->createTable("test_table");

        try {
            $this->object->clean("test_table");
        }
        catch (\DBHelper\SQLException $attendu) {
            return;
        }
        $this->fail("clean must fail to realize when only one field exists.");
    }

    function testField(){
        $this->object->purge();
        $this->object->createTable("test_table");


        $this->assertEquals(true,
            $this->object->createField("test_table", "test_field"),
            "createField must return true when succeed");

        $this->assertEquals(true,
            $this->object->hasField("test_table", "test_field"),
            "hasField must return true when field exists");
    }

    function testRemoveField(){
        $this->object->purge();
        $this->object->createTable("test_table");
        $this->object->createField("test_table", "test_field");

        $this->assertEquals(true,
            $this->object->removeField("test_table", "test_field"),
            "removeField must return true when succeed");
    }

}
