<?php
namespace Pretty\MetaData;

/**
 */
class Property extends ObjectMeta
{
    public function __construct(){
        parent::__construct();
        $this->related_keys = new ExportableArrayObject();
    }
    public $property_name;
    public $property_type;
    public $value_type;
    public $size;
    public $autoincrement;
    public $encoding;
    public $default_value;
    public $nullable;

    public $firewall;

    public $association;
    public $on;
    public $related_keys;
}
