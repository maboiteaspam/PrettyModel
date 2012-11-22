<?php
namespace Pretty\MetaData;

class ExportableArrayObject extends \ArrayObject{

    public static function __set_state($an_array) // As of PHP 5.1.0
    {
        $obj = new ExportableArrayObject($an_array);
        return $obj;
    }
}
