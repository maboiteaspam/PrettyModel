<?php
namespace Cache;

class PhpArrayTest extends ICacheTest
{
    protected function setUp()
    {
        $this->object = new \Cache\PhpArray();
    }
}