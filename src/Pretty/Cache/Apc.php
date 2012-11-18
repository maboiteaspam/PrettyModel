<?php
namespace Pretty\Cache;

class Apc implements ICache
{
    public function write($key, $data){
        return apc_store($key , ($data));
    }
    public function read($key){
        return apc_fetch($key);
    }
    public function exists($key){
        return apc_exists($key);
    }
    public function delete($key){
        return apc_delete($key);
    }
    public function purge(){
        return apc_clear_cache();
    }
}
