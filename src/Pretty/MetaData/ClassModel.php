<?php
namespace Pretty\MetaData;

class ClassModel extends ObjectMeta
{
    public function __construct(){
        parent::__construct();
        $this->properties = new ExportableArrayObject();
        $this->index = new ExportableArrayObject();
    }
    /**
     * @var string
     */
    public $class_name;
    /**
     * @var string
     */
    public $table_name;
    /**
     * @var string
     */
    public $encoding;
    /**
     * @var string
     */
    public $engine;
    /**
     * @var array
     */
    public $index;
    /**
     * name of the index as the pk
     * @var string
     */
    public $pk;
    /**
     * name of AI fields
     *
     * @var array
     */
    public $autoincrements;

    /**
     *
     * @var array
     */
    public $properties;

    public function getPKFields(){
        $foreign_pk_name = $this->pk;
        return $this->index[$foreign_pk_name]["fields"];
    }

    public function toString( $instance ){
        $retour = array(
            "table_name"=>$this->table_name,
            "class_name"=>$this->class_name,
            "properties"=>array(),
        );
        foreach( $this->properties as $prop_name=>$prop_meta ){
            /* @var $prop_meta Property */
            if( $prop_meta->property_type == "scalar" ){
                $retour["properties"][$prop_name] = $instance->$prop_name;

            }elseif( $prop_meta->property_type == "association" ){
                if( $prop_meta->association == "BelongsTo" ){
                    foreach(  $prop_meta->related_keys as $local_key=>$related_key ){
                        $retour["properties"][$related_key] = $instance->$local_key;
                    }
                }else if( $prop_meta->association == "HasOne" ){
                }
                $retour["associations"][$prop_name] = "".$prop_meta->association."(".$prop_meta->value_type.")";
            }

        }

        return var_export($retour, true);
    }
}
