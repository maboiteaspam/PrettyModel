<?php
namespace Cache;
/**
 *
 */
trait ArrayLikeActor{
    public function __set($offset, $value) {
        $this->write($offset, $value);
    }
    public function __get($offset) {
        return $this->read($offset);
    }
    public function __isset($offset) {
        return $this->exists($offset);
    }
    public function __unset($offset) {
        return $this->delete($offset);
    }
    public function offsetSet($offset, $value) {
        $this->write($offset, $value);
    }
    public function offsetExists($offset) {
        return $this->exists($offset);
    }
    public function offsetUnset($offset) {
        return $this->delete($offset);
    }
    public function offsetGet($offset) {
        return $this->read($offset);
    }
}
