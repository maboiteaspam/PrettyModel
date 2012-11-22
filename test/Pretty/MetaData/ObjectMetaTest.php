<?php
namespace Pretty\MetaData;

class SomeConcreteClass extends ObjectMeta{
    public $tomates = "5";
    public $poireaux = "10";
}
/**
 */
class ObjectMetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SomeConcreteClass
     */
    public $object;

    protected function setUp()
    {
        $this->object = new SomeConcreteClass();
    }

    function testObject()
    {
        $object = $this->object;

        $this->assertEquals( true, isset($object["tomates"]),
            ""
        );
        $this->assertEquals( true, isset($object->tomates),
            ""
        );
        $this->assertEquals( false, isset($object["_obj_properties"]),
            ""
        );
        $this->assertEquals( false, isset($object->_obj_properties),
            ""
        );

        $object->not_a_property = "some value";
        $this->assertEquals( false, isset($object->not_a_property),
            ""
        );
        $this->assertEquals( false, isset($object["not_a_property"]),
            ""
        );

        $this->assertEquals( false, $object->is_defined("not_a_property"),
            ""
        );

        $this->assertEquals( true, $object->is_defined("tomates"),
            ""
        );

        $this->assertEquals( "5", $object->tomates,
            ""
        );

        $this->assertEquals( "5", $object["tomates"],
            ""
        );
        $this->assertEquals( null, $object->not_a_property,
            ""
        );
        $this->assertEquals( null, $object["not_a_property"],
            ""
        );

        unset($object->tomates);
        $this->assertEquals( false, $object->is_defined("tomates"),
            ""
        );
        $this->assertEquals( false, isset($object["tomates"]),
            ""
        );
        $this->assertEquals( false, isset($object->tomates),
            ""
        );

        $object["tomates"] = "8";
        $this->assertEquals( true, $object->is_defined("tomates"),
            ""
        );
        $this->assertEquals( true, isset($object["tomates"]),
            ""
        );
        $this->assertEquals( true, isset($object->tomates),
            ""
        );

        $this->assertEquals( "8", $object["tomates"],
            ""
        );

        unset($object["tomates"]);
        $this->assertEquals( false, $object->is_defined("tomates"),
            ""
        );
        $this->assertEquals( false, isset($object["tomates"]),
            ""
        );
        $this->assertEquals( false, isset($object->tomates),
            ""
        );

        $values = $object->to_array();
        $this->assertEquals( false, isset($values["tomates"]),
            ""
        );
        $this->assertEquals( true, isset($values["poireaux"]),
            ""
        );
        $this->assertEquals( "10", ($values["poireaux"]),
            ""
        );

        $object = SomeConcreteClass::__set_state($values);
        $this->assertEquals( false, isset($object["tomates"]),
            ""
        );
        $this->assertEquals( true, isset($object["poireaux"]),
            ""
        );
        $this->assertEquals( "10", ($object["poireaux"]),
            ""
        );

    }

    protected function tearDown()
    {
        $this->object = null;
    }
}
