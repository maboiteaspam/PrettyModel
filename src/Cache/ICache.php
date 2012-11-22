<?php
namespace Cache;

interface ICache extends \ArrayAccess {
    /**
     *
     * @param $key
     * @param $data
     * @return bool
     */
    public function write($key, $data);

    /**
     * @param $key
     * @return mixed
     */
    public function read($key);

    /**
     * @param $key
     * @return bool
     */
    public function exists($key);

    /**
     * @param $key
     * @return bool
     */
    public function delete($key);

    /**
     * Purge the cache when possible
     * @return bool
     */
    public function purge();

    /**
     * Count number of entries within the cache
     * @return int
     */
    public function count();
}