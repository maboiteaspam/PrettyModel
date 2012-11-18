<?php
namespace Pretty\Cache;

class PhpArrayTest extends ICacheTest
{
    protected function setUp()
    {
        $this->object = new \Pretty\Cache\PhpArray();
    }
}