<?php
namespace DBHelper\Layer;
/**
 */
class PHPmysql implements ILayer
{
    protected $mysql;
    protected $db_name;

    public function __construct( $mysql_resource ){
        $this->mysql    = $mysql_resource;
    }
    public function exec($sql){
        if( mysql_query($sql, $this->mysql) != false ){
            return mysql_affected_rows($this->mysql);
        }
        $errno = mysql_errno($this->mysql);
        throw new \DBHelper\SQLException($sql,$errno);
    }
    public function query($sql){
        $query = mysql_query($sql, $this->mysql);
        if( !$query ){
            $errno = mysql_errno($this->mysql);
            throw new \DBHelper\SQLException($sql,$errno);
        }
        $retour = new PHPmysqlIterator($query);
        return $retour;
    }

    public function get_resource(){
        return $this->mysql;
    }
    public function close(){
        return mysql_close($this->mysql);
    }
    public function __destruct(){
        return $this->close();
    }
}
class PHPmysqlIterator implements \Iterator {
    private $query;
    private $current_;
    private $is_valid_=true;

    public function __construct( $query ) {
        $this->query = $query;
        $this->current_ = mysql_fetch_assoc($this->query);
    }
    function rewind() {
        return false;
    }
    function current() {
        return $this->current_;
    }
    function key() {
        return null;
    }
    function next() {
        $this->current_ = mysql_fetch_assoc($this->query);
        $this->is_valid_ =  $this->current_!==false;
        return $this->current_;
    }
    function valid() {
        return $this->is_valid_;
    }
}