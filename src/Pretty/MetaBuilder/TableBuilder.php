<?php
namespace Pretty\MetaBuilder;

use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\MetaData\Property as Property;
use Pretty\MetaData\Index as Index;
use Pretty\MetaLoader\ILoader as ILoader;

use DBHelper\Modeler\ITableModeler as ITableModeler;


class TableBuilder
{
    /**
     * @var ILoader
     */
    protected $loader;

    /**
     * @var \DBHelper\Modeler\ITableModeler
     */
    protected $modeler;

    public function __construct( ILoader $loader, ITableModeler $modeler ){
        $this->modeler = $modeler;
        $this->loader = $loader;
    }
    public function getModeler(){
        return $this->modeler;
    }

    public function build_a_table( ClassModel $meta_data ){
        $modeler = $this->modeler;
        /* @var $modeler ITableModeler */

        /**
         * build table
         */
        $is_new_table   = false;
        $table_name     = $meta_data->table_name;
        if( $modeler->hasTable($table_name) === false ){
            $modeler->createTable($table_name, $meta_data );
            $is_new_table = true;
        }

        /**
         * build fields
         */
        foreach( $meta_data->properties as $field_name=>$field_infos ){
            /* @var $field_infos Property */
            if( $field_infos->property_type == "scalar" ){
                if( ! $modeler->hasField($table_name, $field_name) ){
                    $field_meta = $this->transform_scalar_propety($field_name, $field_infos);
                    $modeler->createField($table_name, $field_name, $field_meta);

                }
            }
        }

        /**
         * build indexs
         */
        foreach( $meta_data->index as $index_name=>$index ){
            /* @var $index Index */
            if( $modeler->hasIndex($table_name, $index->name) == false ){
                $index_table = ($index->to_array());
                $index_table["fields"] = array();
                foreach( $index->fields as $concrete_field ){
                    $index_table["fields"][$concrete_field] = $this->transform_scalar_propety($concrete_field, $meta_data->properties[$concrete_field]);
                }

                $modeler->createIndex( $table_name, $index->name, $index_table );
            }
        }

        // it requires builded index.
        if( $meta_data->autoincrements !== false ){
            foreach( $meta_data->properties as $field_name=>$field_infos ){
                /* @var $field_infos Property */
                if( $field_infos->property_type == "scalar" ){
                    $field_table = ($field_infos->to_array());

                    $field_table["name"] = $field_infos->property_name;
                    if( strtolower($field_infos->value_type) === "string"){
                        if( $field_infos->is_defined("size") ){
                            $field_table["type"] = "VARCHAR";
                        }else{
                            $field_table["type"] = "TEXT";
                        }
                    }
                    elseif( strtolower($field_infos->value_type) === "int")
                        $field_table["type"] = "INT";

                    $field_table["autoincrement"] = in_array($field_name, $meta_data->autoincrements);

                    $modeler->updateField($table_name, $field_name, $field_table);
                }
            }
        }

        if( $is_new_table )
            $modeler->clean($table_name);



        /**
         * build fields
         */
        foreach( $meta_data->properties as $field_name=>$property_meta ){
            /* @var $property_meta Property */
            if( $property_meta->property_type != "scalar" ){

                if( $property_meta->association === "HasManyToMany" ){
                    $foreign_meta = $this->loader->get_meta_data( $property_meta->value_type );
                    $mid_table_name = array(
                        $foreign_meta->table_name,
                        $meta_data->table_name,
                    );
                    sort($mid_table_name);
                    $mid_table_name     = $mid_table_name[0]."_".$mid_table_name[1];
                    $options            = array();
                    $is_new_table       = false;
                    if( $modeler->hasTable($mid_table_name) == false ){
                        $modeler->createTable($mid_table_name, $options);
                        $is_new_table = true;
                    }


                    $local_pk   = $meta_data->pk;
                    $foreign_pk = $foreign_meta->pk;

                    if( $local_pk === false ){
                        throw new \Exception("missing pk on ".$meta_data->class_name);
                    }

                    if( $foreign_pk === false ){
                        throw new \Exception("missing pk on ".$foreign_meta->class_name);
                    }

                    $local_pk_fields   = $meta_data->index[$local_pk]->fields;
                    foreach( $local_pk_fields as $concrete_field ){
                        $f = $meta_data->table_name."_".$concrete_field;
                        if( $modeler->hasField($mid_table_name, $f) === false ){
                            $field_meta = $this->transform_scalar_propety($f, $meta_data->properties[$concrete_field] );
                            $modeler->createField($mid_table_name, $f, $field_meta);
                        }
                    }
                    $foreign_pk_fields = $meta_data->index[$foreign_pk]->fields;
                    foreach( $foreign_pk_fields as $concrete_field ){
                        $f = $foreign_meta->table_name."_".$concrete_field;
                        if( $modeler->hasField($mid_table_name, $f) === false ){
                            $field_meta = $this->transform_scalar_propety($f, $foreign_meta->properties[$concrete_field] );
                            $modeler->createField($mid_table_name, $f, $field_meta);
                        }
                    }

                    $props_fname = "properties_name";
                    $props_relation = new Property();
                    $props_relation->property_name = $props_fname;
                    $props_relation->property_type = "scalar";
                    $props_relation->value_type = "string";
                    $props_relation->size = 50;
                    $props_relation->autoincrement = false;
                    $props_relation->encoding = null;
                    $props_relation->nullable = null;
                    $props_relation->default_value = false;
                    $props_meta = $this->transform_scalar_propety($props_fname, $props_relation);

                    if( $modeler->hasField($mid_table_name, $props_fname) === false ){
                        $modeler->createField($mid_table_name, $props_fname, $props_meta);
                    }

                    if( $modeler->hasIndex($mid_table_name, "PRIMARY") == false ){
                        $pk = array(
                            "type"=>"PK",
                            "engine"=>null,
                            "fields"=>array(),
                        );
                        foreach( $local_pk_fields as $concrete_field ){
                            $field_name = $meta_data->table_name."_".$concrete_field;
                            $pk["fields"][$field_name] = $this->transform_scalar_propety($field_name, $meta_data->properties[$concrete_field]);
                        }
                        foreach( $foreign_pk_fields as $concrete_field ){
                            $field_name = $foreign_meta->table_name."_".$concrete_field;
                            $pk["fields"][$field_name] = $this->transform_scalar_propety($field_name, $foreign_meta->properties[$concrete_field]);
                        }
                        $pk["fields"][$props_fname] = $props_meta;


                        $modeler->createIndex( $mid_table_name, "PRIMARY", $pk );
                    }

                    if( $is_new_table )
                        $modeler->clean($mid_table_name);

                }
            }
        }
    }

    protected function transform_scalar_propety( $name, Property $meta ){
        if( $meta->property_type != "scalar" ){
            return false;
        }
        $retour = $meta->to_array();
        if( strtolower($meta->value_type) === "string"){
            if( isset($meta->size) ){
                $retour["type"] = "VARCHAR";
            }else{
                $retour["type"] = "TEXT";
            }
        }
        elseif( strtolower($meta->value_type) === "int")
            $retour["type"] = "INT";

        return $retour;
    }

    /**
     * @return int
     */
    public function purge(){
        return $this->modeler->purge();
    }
}
