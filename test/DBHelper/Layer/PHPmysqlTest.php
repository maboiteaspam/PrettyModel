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

    protected function tearDown()
    {
        $this->object = null;
    }
}