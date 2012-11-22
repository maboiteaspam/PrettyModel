<?php
namespace DBHelper\Layer;

/**
 */
class PHPpdoTest extends ILayerTest
{
    protected function setUp()
    {
        $db = new \PDO(
        'mysql:host=localhost;dbname=test',
        'root',
        '123456',
        array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->object = new \DBHelper\Layer\PHPpdo( $db );
    }

    protected function tearDown()
    {
        $this->object->exec("DROP TABLE IF EXISTS test_layer");
        $this->object = null;
    }
}