<?php
namespace Cache;
/**
 */
class ICacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Cache\ICache
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

    function testCount(){
        $this->object->purge();
        $this->assertEquals(0, $this->object->count(), "");
        $this->object->write("test", "test");
        $this->assertEquals(1, $this->object->count(), "");
        $this->object->purge();
        $this->assertEquals(0, $this->object->count(), "");
        $this->object->write("test", "test");
        $this->object->write("test", "test");
        $this->assertEquals(1, $this->object->count(), "");
        $this->object->write("test2", "test");
        $this->assertEquals(2, $this->object->count(), "");
        $this->object->delete("test2");
        $this->assertEquals(1, $this->object->count(), "");
        $this->object->delete("test");
        $this->assertEquals(0, $this->object->count(), "");
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

    function testArrayLike(){
        $this->object->purge();
        $this->assertEquals(false,
            $this->object->exists("test"),
            "");
        $this->object["test"] = "test";
        $this->assertEquals(true,
            $this->object->exists("test"),
            "");
        $this->assertEquals(true,
            isset($this->object["test"]),
            "");
        $this->assertEquals("test",
            $this->object->read("test"),
            "");
        $this->assertEquals("test",
            $this->object["test"],
            "");
        unset($this->object["test"]);
        $this->assertEquals(false,
            $this->object->exists("test2"),
            "");
        $this->assertEquals(false,
            isset($this->object["test"]),
            "");
        $this->assertEquals(null,
            $this->object->read("test"),
            "");
        $this->assertEquals(null,
            $this->object["test"],
            "");


        $this->object->test2 = "test2";
        $this->assertEquals(true,
            $this->object->exists("test2"),
            "");
        $this->assertEquals(true,
            isset($this->object->test2),
            "");
        $this->assertEquals("test2",
            $this->object->read("test2"),
            "");
        $this->assertEquals("test2",
            $this->object->test2,
            "");
        unset($this->object->test2);
        $this->assertEquals(false,
            $this->object->exists("test2"),
            "");
        $this->assertEquals(false,
            isset($this->object->test2),
            "");
        $this->assertEquals(null,
            $this->object->read("test2"),
            "");
        $this->assertEquals(null,
            $this->object->test2,
            "");
    }
}