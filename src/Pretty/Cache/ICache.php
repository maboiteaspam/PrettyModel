<?php
namespace Pretty\Cache;

interface ICache{
    public function write($key, $data);
    public function read($key);
    public function exists($key);
    public function delete($key);
    public function purge();
}