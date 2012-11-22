<?php
namespace Pretty\MetaData;

/**
 */
class ExportableArrayObjectTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    function test__set_state()
    {
        $object = ExportableArrayObject::__set_state( array("rr","gg"=>"h") );

        $this->assertEquals( true,
            isset($object[0]),
            ""
        );
        $this->assertEquals( "rr",
            ($object[0]),
            ""
        );
        $this->assertEquals( "h",
            ($object["gg"]),
            ""
        );
        $this->assertEquals( 2,
            count($object),
            ""
        );
    }

    protected function tearDown()
    {
    }
}
