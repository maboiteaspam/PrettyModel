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
        if( $errno > 0 ){
            throw new \DBHelper\SQLException($sql,$errno);
        }
        return false;
    }
    public function query($sql){
        $retour = array();
        $query = mysql_query($sql, $this->mysql);
        if( !$query ){
            $errno = mysql_errno($this->mysql);
            if( $errno > 0 )
                throw new \DBHelper\SQLException($sql,$errno);
            return false;
        }
        while($r=mysql_fetch_assoc($query)) $retour[] = $r;
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
