<?php
namespace DBHelper\Layer;

/**
 */
class PHPmysqlTest extends ILayerTest
{
    protected function setUp()
    {
        $link = mysql_connect("localhost","root","123456",true);
        if (!$link) {
            die('Not connected : ' . mysql_error());
        }
        if (!mysql_select_db('test', $link)) {
            die('Not connected : ' . mysql_error());
        }
        $this->object = new \DBHelper\Layer\PHPmysql( $link );
    }

    function testGetResource(){
        $this->assertEquals(true,
            is_resource($this->object->get_resource()),
            "");
        $this->assertEquals(true,
            is_resource($this->object->get_resource()) && strpos(get_resource_type($this->object->get_resource()), 'mysql') !== false,
            "");
    }

    protected function tearDown()
    {
        $this->object->exec("DROP TABLE IF EXISTS test_layer");
        $this->object = null;
    }
}