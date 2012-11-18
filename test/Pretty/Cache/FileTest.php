<?php
namespace Pretty\Cache;

class FileTest extends ICacheTest
{
    protected function setUp()
    {
        if( is_dir("./cache/") == false ) mkdir("./cache/");
        $this->object = new \Pretty\Cache\File("./cache/");
    }
}
