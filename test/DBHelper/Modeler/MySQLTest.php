<?php
namespace DBHelper\Modeler;
/**
 */
class MySQLTest extends IModelerTest
{
    protected function setUp()
    {
        $db = new \PDO(
            'mysql:host=localhost;dbname=test',
            'root',
            '123456',
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->object = new \DBHelper\Modeler\MySQL();
        $this->object->setLayer( new \DBHelper\Layer\PHPpdo( $db ) );
        $this->object->setContainerName("test");
    }
    protected function tearDown()
    {
        $this->object->purge();
        $this->object = null;
    }
}