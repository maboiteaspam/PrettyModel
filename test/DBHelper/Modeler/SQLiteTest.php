<?php
namespace DBHelper\Modeler;
/**
 */
class SQLiteTest extends IModelerTest
{
    protected function setUp()
    {
        $db = new \PDO(
            'sqlite::memory:',
            null,
            null,
            array(\PDO::ATTR_PERSISTENT => false)
        );
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->object = new \DBHelper\Modeler\SQLite();
        $this->object->setLayer( new \DBHelper\Layer\PHPpdo( $db ) );
        $this->object->setContainerName(":memory:");
    }
    protected function tearDown()
    {
        $this->object->purge();
        $this->object = null;
    }

    function testRemoveField(){
        $this->markTestSkipped("SQLite does not know how to remove fields.");
    }
}