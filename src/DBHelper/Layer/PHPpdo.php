<?php
namespace DBHelper\Layer;
/**
 *
 */
class PHPpdo implements ILayer
{
    protected $pdo;
    protected $db_name;

    public function __construct( \PDO $pdo){
        $this->pdo      = $pdo;
    }
    public function exec($sql){
        try{
            $retour = $this->pdo->exec($sql);
        }catch (\PDOException $ex ){
            throw new \DBHelper\SQLException($sql,0,$ex);
        }
        return $retour;
    }
    public function query($sql){
        try{
            return $this->pdo->query($sql);
        }catch (\PDOException $ex ){
            throw new \DBHelper\SQLException($sql,0,$ex);
        }
    }
    public function get_resource(){
        return $this->pdo;
    }
}
