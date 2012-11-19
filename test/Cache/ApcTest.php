<?php
namespace Cache;

class ApcTest extends ICacheTest
{
    protected function setUp()
    {
        $this->markTestSkipped("APC does not support well cli interface");
        //$this->object = new \Pretty\Cache\Apc();
    }


}
