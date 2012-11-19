<?php
namespace Pretty\MetaLoader;

use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\MetaBuilder\TableBuilder as TableBuilder;
use Pretty\MetaBuilder\ClassBuilder as ClassBuilder;
use Pretty\MetaBuilder\ClassEnhancer as ClassEnhancer;
use DBHelper\Modeler\ITableModeler as Modeler;

/**
 */
class LoaderBuilder implements ILoader
{
    /**
     * @var Modeler
     */
    public $modeler;
    /**
     * @var TableBuilder
     */
    public $table_builder;

    /**
     * @var \Pretty\MetaBuilder\ClassBuilder
     */
    public $meta_class_builder;

    /**
     * @var \Pretty\MetaBuilder\ClassEnhancer
     */
    public $meta_class_enhancer;
    /**
     * @var array
     */
    public $cache;

    private $meta_data_dressing = array();


    /**
     * @param \DBHelper\Modeler\ITableModeler $modeler
     * @param  $cache
     */
    public function __construct( \DBHelper\Modeler\ITableModeler $modeler, $cache){
        $this->cache = $cache;
        $this->table_builder = new TableBuilder( $this, $modeler );
        $this->meta_class_builder = new ClassBuilder();
        $this->meta_class_enhancer = new ClassEnhancer( $this );
    }

    /**
     * @param $class_name
     * @return ClassModel
     */
    public function get_meta_data( $class_name ){
        if( ! isset($this->meta_data_dressing[$class_name]) ){

            $r = new \ReflectionClass($class_name);
            $file = $r->getFileName();
            $create_model = true;

            if( isset($this->cache[$class_name]) ){
                $storable = $this->cache[$class_name];
                if( $storable["file"] == $file
                    && $storable["time"] == filemtime($file) ){
                    $create_model = false;
                    $this->meta_data_dressing[$class_name] = $storable["meta"];
                }else{
                    unset($this->cache[$class_name]);
                }
            }

            if( $create_model ){
                $this->meta_data_dressing[$class_name] = $this->meta_class_builder->create_class_meta_data($class_name);
                $this->meta_class_enhancer->cold_enhancement($this->meta_data_dressing[$class_name]);

                $this->cache[$class_name] = array(
                    "meta"=>$this->meta_data_dressing[$class_name],
                    "file"=>$file,
                    "time"=>filemtime($file),
                );

                $this->table_builder->build_a_table($this->meta_data_dressing[$class_name]);
            }
            $this->meta_class_enhancer->JIT_enhancement($this->meta_data_dressing[$class_name]);
        }
        return $this->meta_data_dressing[$class_name];
    }

}
