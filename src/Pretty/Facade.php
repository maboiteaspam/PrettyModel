<?php
namespace Pretty;

use Cache\ICache as ICache;
use DBHelper\Modeler\ITableModeler as TableModeler;
use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\Builder\IBuilder as Builder;
use Pretty\Repository\Repository as Repository;

class Facade
{
    /**
     * List of known facade
     *
     * @var array
     */
    public static $_facades;

    /**
     * Name of the facade selected
     * @var string
     */
    public static $_selected_facade;

    /**
     * @param null $facade
     * @return Facade
     */
    public static function get($facade=null){
        $facade = $facade==null?self::$_selected_facade:$facade;
        if( isset(self::$_facades[ $facade ]) )
            return self::$_facades[ $facade ];
        return null;
    }

    /**
     * Initialize a new facade, register it, and select it
     *
     * @param  $class_path
     * @param \DBHelper\Modeler\ITableModeler $modeler
     * @param \Pretty\Builder\IBuilder $builder
     * @param ICache $cache
     * @return Facade
     */
    public static function auto($class_path,
                                TableModeler $modeler,
                                Builder $builder,
                                ICache $cache){
        $repository = new Repository();
        $Facade = new Facade($class_path, $modeler, $repository, $builder, $cache);
        $facade_name = "automatic";
        self::set($facade_name, $Facade);
        self::select($facade_name);
        return $Facade;
    }

    /**
     * @param $facade_name
     */
    public static function select($facade_name){
        $facade = self::get();
        if( $facade !== null ){
            $facade->disable();
        }
        self::$_selected_facade = $facade_name;
        $facade = self::get(self::$_selected_facade);
        if( $facade !== null ){
            $facade->enable();
        }
    }

    /**
     * @param $facade_name
     * @param Facade $facade
     */
    public static function set($facade_name, Facade $facade){
        self::$_facades[ $facade_name ] = $facade;
    }


    /**
     * @var \Pretty\Repository\Repository
     */
    public $repository;
    /**
     * @var \Pretty\Builder\IBuilder
     */
    private $builder;
    private $class_loader;
    private $cache;
    private $modeler;


    public function __construct ( $class_path,
                                  TableModeler $modeler,
                                  Repository $repository,
                                  Builder $builder,
                                  ICache $cache){
        $this->modeler      = $modeler;
        $this->repository   = $repository;
        $this->builder      = $builder;
        $this->class_loader = new \Pretty\Util\ClassLoader($class_path);
        $this->cache        = $cache;

        $Facade = $this;
        $this->class_loader->listen(function($class_name) use ($Facade) {
            $parents = \class_parents($class_name);
            if( $parents !== false ){
                if( in_array("Pretty\Model", $parents) ){
                    $this->get_meta_data($class_name);
                }
            }
        });
    }

    /**
     * @param $class_name
     * @return ClassModel
     */
    public function get_meta_data( $class_name ){
        if( ! isset($this->repository[$class_name]) ){

            $r = new \ReflectionClass($class_name);
            $file = $r->getFileName();
            $create_model = true;
            $update_model = false;
            $meta = null;

            if( isset($this->cache[$class_name]) ){
                $create_model = false;
                $storable = $this->cache[$class_name];

                if( $storable["file"] == $file
                    && $storable["fp"] == sha1_file($file) ){
                    $meta = $storable["meta"];
                    $this->repository[$class_name] = $meta;
                }else{
                    //$previous_version = $this->cache[$class_name];
                    unset($this->cache[$class_name]);
                    $update_model = true;
                }
            }

            if( $create_model ){
                $meta = $this->builder->build_meta_data($this, $this->repository, $class_name);

                $this->cache[$class_name] = array(
                    "meta"=>$meta,
                    "file"=>$file,
                    "fp"=>sha1_file($file),
                );
                $this->builder->create_table($this, $meta);
            }else if( $update_model ){
                $meta = $this->builder->build_meta_data($this, $this->repository, $class_name);
                // can do some diff with $previous_version
                $this->cache[$class_name] = array(
                    "meta"=>$meta,
                    "file"=>$file,
                    "fp"=>sha1_file($file),
                );
                $this->builder->update_table($this, $meta);
            }
            $this->builder->enhance_jit($this, $meta);
        }
        return $this->repository[$class_name];
    }

    /**
     * Returns the raw DB connection resource
     *
     * @return mixed
     */
    public function get_db_resource(  ){
        return $this->modeler->getLayer()->get_resource();
    }

    public function get_modeler( ){
        return $this->modeler;
    }

    /**
     * Set default ModelORM connection to
     * the current resource
     * attach this instance
     * to the auto loading to intercept
     * class calls and prepare their meta
     */
    public function enable(  ){
        ModelORM::set_db( $this->get_db_resource() );
        $this->class_loader->enable(  );
    }

    /**
     * Detach the auto loader
     */
    public function disable(  ){
        ModelORM::set_db( null );
        $this->class_loader->disable( );
    }

    /**
     * Purge the cache and delete all tables
     * for dev purpose only
     */
    public function clean_up( ){
        $this->modeler->purge();
        $this->cache->purge();
    }
}

if( function_exists("resolve_class_name") == false ){
    /**
     * PSR-0 compliant auto loader
     *
     * @param $className
     * @return string
     */
    function resolve_class_name($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        return $fileName;
    }
}