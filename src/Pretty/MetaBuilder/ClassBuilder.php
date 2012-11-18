<?php
namespace Pretty\MetaBuilder;
use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\MetaData\AnnotatedModel as AnnotatedModel;
use Pretty\MetaData\Index as Index;
use Pretty\MetaData\Property as Property;

class ClassBuilder
{
    public function __construct(  ){
    }


    /**
     * @var AnnotatedModel
     */
    private $annotated_model;

    /**
     * @param $class_name
     * @return ClassModel
     */
    public function create_class_meta_data( $class_name ){
        $this->annotated_model = new AnnotatedModel($class_name);
        $class_meta = new ClassModel();
        $class_meta->class_name = $this->annotated_model->get_class_name();
        $class_meta->table_name = $this->annotated_model->get_table_name();
        $class_meta->autoincrements = $this->annotated_model->get_auto_incremented_columns();
        $class_meta->pk = false;
        $class_meta->encoding = null;

        $annotation = $this->annotated_model->class_get_annotation( "Table" );
        if( $annotation != null ){
            if( $annotation->encoding !== null )
                $class_meta->encoding = $annotation->encoding;
            if( $annotation->engine !== null )
                $class_meta->engine = $annotation->engine;
        }
        $annotations = $this->annotated_model->class_get_annotations( "Index" );
        foreach( $annotations as $annotation ){
            $class_meta->index[$annotation->name] = $this->create_index_meta_data($annotation);
            if( $class_meta->index[$annotation->name]["type"] === "PK" )
                $class_meta->pk = $annotation->name;
        }
        foreach( $this->annotated_model->collect_public_properties() as $index=>$property_name ){
            if( $this->annotated_model->is_model_property($property_name) ){
                $class_meta->properties[$property_name] = $this->create_property_meta_data( $property_name );
            }elseif( $this->annotated_model->is_model_association($property_name) ){
                $class_meta->properties[$property_name] = $this->create_association_meta_data( $property_name );
            }
        }
        return $class_meta;
    }

    private function create_index_meta_data( $annotation ){
        $index = new Index();
        $index->type = $annotation->type;
        $index->name = $annotation->name;

        if( $annotation->engine !== null )
            $index->engine = $annotation->engine;
        foreach( $annotation->fields as $field_name=>$field ){
            $index->fields->append($field["name"]);
        }
        return $index;
    }

    /**
     * Build property meta data
     *
     * @param $property_name
     * @return array
     */
    private function create_property_meta_data( $property_name ){
        $property = new Property();
        $property->property_name = $property_name;
        $property->property_type = "scalar";
        $property->value_type = "string";
        $property->size = null;
        $property->autoincrement = false;
        $property->encoding = null;
        $property->default_value = null;
        $property->nullable = null;

        $annotation = $this->annotated_model->property_get_annotation( $property_name, "Column" );
        if( $annotation->type !== null )            $property->value_type = $annotation->type;
        if( $annotation->size !== null )            $property->size = (int)$annotation->size;
        if( $annotation->autoincrement !== null )   $property->autoincrement = (bool)$annotation->autoincrement;
        if( $annotation->encoding !== null )        $property->encoding = $annotation->encoding;
        if( isset($annotation->default_value) )   $property->default_value = $annotation->default_value;
        if( isset($annotation->nullable) )   $property->nullable = (bool)$annotation->nullable;


        return $property;
    }

    /**
     * Creates the association meta data
     *
     * @param $property_name
     * @return array
     */
    private function create_association_meta_data( $property_name ){
        $property = new Property();
        $property->property_name = $property_name;
        $property->property_type = "association";
        $property->value_type = null;
        $property->on = null;

        if( $this->annotated_model->property_has_annotation($property_name, "HasOne") ){
            $property->association = "HasOne";
            $annotation = $this->annotated_model->property_get_annotation( $property_name, "HasOne" );
            if( $annotation->type !== null )          $property->value_type = $annotation->type;
            if( $annotation->on !== null )            $property->on = $annotation->on;
        }
        elseif( $this->annotated_model->property_has_annotation($property_name, "HasMany") ){
            $property->association = "HasMany";
            $annotation = $this->annotated_model->property_get_annotation( $property_name, "HasMany" );
            if( $annotation->type !== null )          $property->value_type = $annotation->type;
            if( $annotation->on !== null )            $property->on = $annotation->on;
        }
        elseif( $this->annotated_model->property_has_annotation($property_name, "HasManyToMany") ){
            $property->association = "HasManyToMany";
            $annotation = $this->annotated_model->property_get_annotation( $property_name, "HasManyToMany" );
            if( $annotation->type !== null )          $property->value_type = $annotation->type;
            if( $annotation->on !== null )            $property->on = $annotation->on;
        }
        elseif( $this->annotated_model->property_has_annotation($property_name, "BelongsTo") ){
            $property->association = "BelongsTo";
            $annotation = $this->annotated_model->property_get_annotation( $property_name, "BelongsTo" );
            if( $annotation->type !== null )            $property->value_type = $annotation->type;
        }

        return $property;
    }

}

