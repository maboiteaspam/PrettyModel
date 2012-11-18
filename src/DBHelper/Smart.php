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

        if( $db_type === "mysql" )
            $modeler = new \DBHelper\Modeler\MySQL();

        if( $resource_layer instanceof \DBHelper\Layer\ILayer )
            $modeler->setLayer( $resource_layer );
        else if( $resource_layer instanceof \PDO )
            $modeler->setLayer( new \DBHelper\Layer\PHPpdo($resource_layer) );

        $modeler->setContainerName($db_name);

        return $modeler;
    }
}
