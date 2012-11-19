<?php
namespace Pretty\MetaBuilder;
use Pretty\MetaLoader\ILoader as ILoader;
use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\MetaData\Property as Property;
/**
 */
class ClassEnhancer
{
    /**
     * @var ILoader
     */
    protected $loader;

    public function __construct( ILoader $loader ){
        $this->loader = $loader;
    }

    public function cold_enhancement( ClassModel $class_meta ){
        foreach( $class_meta->properties as $property_name=>$property_ ){
            /* @var $property_ Property */
            if( $property_->property_type == "association" ){

                if( $property_->association == "BelongsTo" ){
                    $foreign_meta = $this->loader->get_meta_data($property_->value_type);
                    $foreign_pk_fields = $foreign_meta->getPKFields();

                    foreach( $foreign_pk_fields as $foreign_pk_field ){

                        $field = clone($foreign_meta->properties[$foreign_pk_field]);
                        unset($field["firewall"]);
                        $field->property_name = $property_name."_".$field->property_name;

                        if( isset($class_meta->properties[$field->property_name]) === false ){
                            $class_meta->properties[$field->property_name] = $field;
                        }

                        $property_->related_keys[$field->property_name] = $foreign_pk_field;

                    }
                }else if( $property_->association == "HasMany" ){
                    $pk_fields = $class_meta->getPKFields();
                    $on = $property_->on==null?" @todo@ ":$property_->on;

                    foreach( $pk_fields as $pk_field ){
                        $property_->related_keys[$pk_field] = $on."_".$pk_field;
                    }

                }else if( $property_->association == "HasOne" ){
                    $pk_fields = $class_meta->getPKFields();
                    $on = $property_->on==null?" @todo@ ":$property_->on;

                    foreach( $pk_fields as $pk_field ){
                        $property_->related_keys[$pk_field] = $on."_".$pk_field;
                    }

                }else if( $property_->association == "HasManyToMany" ){
                }
            }
        }

        return $class_meta;
    }

    public function JIT_enhancement( ClassModel $class_meta ){
        foreach( $class_meta->properties as $property_name => $property_){
            /* @var $property_ Property */
            if( $property_->property_type == "scalar" ){
                $property_->firewall = make_property_firewall( $class_meta->class_name, $property_name, $property_ );
            }
        }

        foreach( $class_meta->properties as $property_name => $property_){
            /* @var $property_ Property */
            if( $property_->property_type == "association" ){
                $property_->firewall = make_association_firewall( $class_meta->class_name, $property_name, $property_ );
            }
        }

        return $class_meta;
    }

}


/**
 * Create the firewall that controls
 * the input / output
 * of the value
 *
 * We make it in a function so that the closure ar not binded
 * to an object, avoiding recursion in var_dump function...
 * And so far more more close to what i want to write.
 *
 * @param $class_name
 * @param $property_name
 * @param $meta_data
 * @return array
 * @throws \Exception
 */
function make_property_firewall( $class_name, $property_name, Property $meta_data ){
    $retour = array(
        "set"=>function($model_instance, $value){return $value;},
        "get"=>function($model_instance, $value){return $value;},
    );
    if( $meta_data->value_type === "string" ){
        if( $meta_data->size !== null ){
            $size   = $meta_data->size;
            $retour = array(
                "set"=>function($model_instance, $value)use($size){return substr($value,0,$size);},
                "get"=>function($model_instance, $value)use($size){return substr($value,0,$size);},
            );
        }else{
            $retour = array(
                "set"=>function($model_instance, $value){return (string)$value;},
                "get"=>function($model_instance, $value){return (string)$value;},
            );
        }
    }else if( $meta_data->value_type === "int" ){
        $retour = array(
            "set"=>function($model_instance, $value){return (int)$value;},
            "get"=>function($model_instance, $value){return (int)$value;},
        );
    }

    if( $meta_data->autoincrement ){
        $retour = array(
            "set"=>function($model_instance, $value)use($property_name){
                if( isset($model_instance->{$property_name}) )
                    throw new \Exception("Auto-incremented field cannot be manually modified.");
                return (int)$value;
            },
        );
    }

    return $retour;
}

function make_association_firewall( $class_name, $property_name, Property $meta_data ){
    $retour = array(
        "set"=>function($model_instance, $value){throw new \Exception("Cannot set an association.");},
        "get"=>null,
    );
    $association = "\Pretty\\".$meta_data->association."Association";
    $source_class = $class_name;
    $source_property = $property_name;
    $target_class = $meta_data->value_type;
    $target_property = $meta_data->on;
    $retour["get"] = function($model_instance)use($association, $source_class, $source_property, $target_class, $target_property){
        return new $association( $model_instance, $source_class,  $source_property, $target_class, $target_property );
    };
    return $retour;
}