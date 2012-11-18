<?php
namespace Pretty\MetaData;

class AnnotatedModel
{
    private $model;
    private $model_annotations;
    public function __construct( $model_name ){
        $this->model = is_object($model_name)?get_class($model_name):$model_name;
        $this->model_annotations = new \ReflectionAnnotatedClass($model_name);
    }

    /**
     * responsible to collect public properties
     * that define the model and the db schema
     *
     * @return array
     */
    public function collect_public_properties(){
        $retour = array();
        $R = new \ReflectionClass( $this->model );
        foreach( $R->getProperties( \ReflectionProperty::IS_PUBLIC) as $p ){
            if( $p->class ===  $R->getName() ){
                $retour[] = $p->name;
            }
        }
        return $retour;
    }

    public function get_table_name(){
        $annot = $this->class_get_annotation( "Pretty\Table" );
        if( $annot === null )
            return strtolower(str_replace("\\","_", $this->class_name()));
        return $annot->name;
    }
    public function get_class_name(){
        return $this->class_name();
    }

    /**
     * Tells if a property has a DB definition
     *
     * @param $property_name
     * @return bool
     */
    public function is_model_property( $property_name ){
        return $this->property_has_annotation($property_name, "Column");
    }
    /**
     * Tells if a property is an association
     *
     * @param $property_name
     * @return bool
     */
    public function is_model_association( $property_name ){
        return $this->property_has_annotation($property_name, "HasOne")
            || $this->property_has_annotation($property_name, "HasMany")
            || $this->property_has_annotation($property_name, "BelongsTo")
            || $this->property_has_annotation($property_name, "HasManyToMany");
    }


    /**
     */
    public function get_auto_incremented_columns( ){
        if( $this->class_get_annotation("AutoIncrement") == false )
            return false;
        $fields = $this->class_get_annotation("AutoIncrement")->value;
        $fields = is_array($fields)?$fields:array($fields);
        return $fields;
    }

    /**
     *
     */
    public function class_name( ){
        return $this->model_annotations->getName();
    }

    /**
     *
     * @param $annotation_type
     * @return bool
     */
    public function class_has_annotation( $annotation_type ){
        return $this->model_annotations->hasAnnotation($annotation_type);
    }

    /**
     *
     * @param $annotation_type
     * @return bool
     */
    public function class_get_annotation( $annotation_type ){
        if( $this->model_annotations->hasAnnotation($annotation_type) ){
            return $this->model_annotations->getAnnotation($annotation_type);
        }
        return null;
    }

    /**
     *
     * @param $annotation_type
     * @return bool
     */
    public function class_get_annotations( $annotation_type ){
        return $this->model_annotations->getAllAnnotations($annotation_type);
    }


    /**
     * Tells if a property has a certain type of annotation
     *
     * @param $property_name
     * @param $annotation_type
     * @return bool
     */
    public function property_has_annotation( $property_name, $annotation_type ){
        return $this->property_get_annotation($property_name, $annotation_type) !== false;
    }

    /**
     * Tells if a property has a certain type of annotation
     *
     * @param $property_name
     * @param $annotation_type
     * @return bool
     */
    public function property_get_annotation( $property_name, $annotation_type ){
        $property_annotation = $this->get_property_annotations($property_name);
        if($property_annotation === null ) return false;
        return $property_annotation->getAnnotation($annotation_type);
    }

    /**
     * Get annotations of a property
     *
     * @param $property_name
     * @return null|\ReflectionAnnotatedProperty
     */
    public function get_property_annotations( $property_name ){
        foreach( $this->model_annotations->getProperties() as $property_annotations ){
            /* @var $property_annotations \ReflectionAnnotatedProperty */
            if( $property_annotations->name === $property_name ){
                return $property_annotations;
            }
        }
        return null;
    }
}
