<?php
namespace Pretty\Builder;

use Pretty\Annotations\Converter as Converter;
use Pretty\MetaData\ClassModel as ClassModel;
use DBHelper\Modeler\ITableModeler as Modeler;

/**
 */
class Builder implements IBuilder
{
    /**
     * @var TableBuilder
     */
    protected $table_builder;

    /**
     * @var \Pretty\Annotations\Converter
     */
    protected $class_converter;

    /**
     * @var \Pretty\Builder\ClassEnhancer
     */
    protected $meta_class_enhancer;

    /**
     * @param TableBuilder $table_builder
     * @param \Pretty\Annotations\Converter $class_converter
     * @param ClassEnhancer $meta_class_enhancer
     */
    public function __construct( TableBuilder $table_builder,
                                 Converter $class_converter,
                                 ClassEnhancer $meta_class_enhancer){
        $this->table_builder        = $table_builder;
        $this->class_converter      = $class_converter;
        $this->meta_class_enhancer  = $meta_class_enhancer;
    }

    /**
     * @param $facade
     * @param $repository
     * @param $class_name
     * @return \Pretty\MetaData\ClassModel
     */
    public function build_meta_data( $facade, $repository, $class_name ){
        // it is required to directly save the meta in the current scope,
        // so that if their is re entrant calls, they would know about
        // the current meta that are created now, so that they wont
        // re trigger build of the meta that leads to build their meta
        // ....
        // it sucks.
        //
        // this call is supposed to be not re entrant, it means that
        // it does all the job that does not involve to query for other models meta
        $repository[$class_name] = $this->class_converter->create_class_meta_data($class_name);
        // this is actually the place where the re entrant class can occur
        // and where the call to other models meta should occurs
        // (for example, think to the FK problem, at one moment you must ask to the foreign model
        // "hey dude what s your pk definition so that i can id yourself in my table ?")
        $this->meta_class_enhancer->cold_enhancement($facade, $repository[$class_name]);
        return $repository[$class_name];
    }


    public function create_table($facade, $meta){
        $this->table_builder->build_a_table($facade, $meta);
    }
    public function update_table($facade, $meta){
        $this->table_builder->update_a_table($facade, $meta);
    }
    public function enhance_jit($facade, $meta){
        $this->meta_class_enhancer->JIT_enhancement($facade, $meta);
    }

    public static function factory(){
        $table_builder          = new \Pretty\Builder\TableBuilder();
        $class_converter        = new \Pretty\Annotations\Converter();
        $meta_class_enhancer    = new \Pretty\Builder\ClassEnhancer( );
        // the builder transform a class definition
        // into a meta object
        // into a concrete sql table
        $builder = new \Pretty\Builder\Builder($table_builder, $class_converter, $meta_class_enhancer);
        return $builder;
    }
}
