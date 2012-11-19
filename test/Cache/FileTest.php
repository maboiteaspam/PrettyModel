<?php
namespace Cache;

class FileTest extends ICacheTest
{
    protected function setUp()
    {
        if( is_dir("./cache/") == false ) mkdir("./cache/");
        $this->object = new \Cache\File("./cache/");
    }
}
