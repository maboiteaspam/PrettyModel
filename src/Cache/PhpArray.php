<?php
namespace Cache;

class PhpArray implements ICache
{
    use ArrayLikeActor;

    public $items;
    public function __construct(  ){
        $this->items = array();
    }
    public function write($key, $data){
        $this->items[$key] = $data;
        return true;
    }
    public function read($key){
        if( isset($this->items[$key]) )
            return $this->items[$key];
        return false;
    }
    public function exists($key){
        return isset($this->items[$key]);
    }
    public function delete($key){
        if( isset($this->items[$key]) ){
            unset($this->items[$key]);
            return true;
        }
        return false;
    }
    public function purge(){
        $this->items = array();
        return true;
    }
}
