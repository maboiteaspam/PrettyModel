<?php
namespace DBHelper\Modeler;
/**
 */
class ActiveRecorderTest extends IModelerTest
{
    protected function setUp()
    {
        $db = new \PDO(
            'mysql:host=localhost;dbname=test',
            'root',
            '123456',
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $modeler = new \DBHelper\Modeler\MySQL();
        $modeler->setLayer( new \DBHelper\Layer\PHPpdo( $db ) );
        $modeler->setContainerName("test");
        $this->object = new \DBHelper\Modeler\ActiveRecorder( $modeler );
    }
}