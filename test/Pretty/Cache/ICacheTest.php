<?php
namespace Pretty\Cache;
/**
 */
class ICacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Pretty\Cache\ICache
     */
    protected $object;

    protected function setUp()
    {
        $this->markTestSkipped();
    }

    function testWriteReturnValues(){
        $this->object->purge();
        $this->assertEquals(true,
            $this->object->write("test", "test"),
            "ICache instance must return true when succeed to write data.");
    }

    function testOverWriteReturnValues(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->object->read("test");

        $this->assertEquals(true,
            $this->object->write("test", "test2"),
            "ICache instance must return true when succeed to over-write data.");
    }

    function testOverWriteReadValues(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->object->read("test");
        $this->object->write("test", "test2");

        $this->assertEquals("test2",
            $this->object->read("test"),
            "ICache instance must return the correct value when key read.");
    }

    function testWrite(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->assertEquals(true,
            $this->object->exists("test"),
            "ICache instance must return true when key exists.");

        $this->assertEquals("test",
            $this->object->read("test"),
            "ICache instance must return the correct value when key read.");
    }

    function testRead(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->assertEquals("test",
            $this->object->read("test"),
            "ICache instance must return the correct value when key read.");
    }

    function testExists(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->assertEquals(true,
            $this->object->exists("test"),
            "ICache instance must return true when key exists.");
        $this->assertEquals(false,
            $this->object->exists("test2"),
            "ICache instance must return false when key not exists.");
    }

    function testDelete(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->assertEquals(true,
            $this->object->delete("test"),
            "");
        $this->assertEquals(false,
            $this->object->exists("test"),
            "");
        $this->assertEquals(false,
            $this->object->read("test"),
            "");
    }

    function testTwiceDelete(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->object->delete("test");
        $this->assertEquals(false,
            $this->object->delete("test"),
            "");
        $this->assertEquals(false,
            $this->object->exists("test"),
            "");
    }

    function testPurge(){
        $this->object->purge();
        $this->object->write("test", "test");
        $this->object->purge();
        $this->assertEquals(false,
            $this->object->exists("test"),
            "");
        $this->assertEquals(false,
            $this->object->read("test"),
            "");
    }
}