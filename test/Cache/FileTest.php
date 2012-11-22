<?php
namespace Cache;

class FileTest extends ICacheTest
{
    protected function setUp()
    {
        $d = __DIR__."/../cache/";
        if( is_dir($d) == false ) mkdir($d);
        $this->object = new \Cache\File($d);
    }

    protected function tearDown()
    {
        $d = __DIR__."/../cache/";
        $this->object->purge();
        if( is_dir($d) ) rmdir($d);
    }
}
