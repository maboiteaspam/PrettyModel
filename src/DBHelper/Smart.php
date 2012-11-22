<?php
namespace DBHelper;

class Smart
{
    /**
     * @param $db_type
     * @param $db_name
     * @param $resource_layer
     * @return Modeler\ITableModeler
     */
    public static function factory($db_type, $db_name, $resource_layer){

        if( $db_type === "mysql" ){
            $modeler = new \DBHelper\Modeler\MySQL();
        }elseif( $db_type === "sqlite" ){
            $modeler = new \DBHelper\Modeler\SQLite();
        }else{
            throw new \Exception("Unsupported database type $db_type");
        }

        if( $resource_layer instanceof \DBHelper\Layer\ILayer )
            $modeler->setLayer( $resource_layer );
        else if( $resource_layer instanceof \PDO )
            $modeler->setLayer( new \DBHelper\Layer\PHPpdo($resource_layer) );
        else if( is_resource($resource_layer) && strpos(get_resource_type($resource_layer), 'mysql') !== false ){
            $modeler->setLayer( new \DBHelper\Layer\PHPmysql($resource_layer) );
        }else{
            throw new \Exception("Unsupported connection database resource type.");
        }

        $modeler->setContainerName($db_name);

        // the active recorder is an helper to speed up the table builder layer that
        // generates lots of useless sql queries by default
        $modeler    = new \DBHelper\Modeler\ActiveRecorder($modeler);

        return $modeler;
    }
}
