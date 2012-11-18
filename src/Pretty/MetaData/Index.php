<?php
namespace Pretty\MetaData;

class Index extends ObjectMeta
{
    public function __construct(){
        parent::__construct();
        $this->fields = new ExportableArrayObject();
    }
    public $name;
    public $type;
    public $engine;
    /**
     * list of related fields name
     *
     * @var array
     */
    public $fields;
}
