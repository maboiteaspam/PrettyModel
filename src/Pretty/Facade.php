<?php
namespace Pretty;

use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\MetaBuilder\LoaderBuilder as LoaderBuilder;
use Pretty\Cache\ICache as Cache;

include("Annotations.php");

class Facade
{
    public static $_facades;
    public static $_facade;

    /**
     * @param null $facade
     * @return Facade
     */
    public static function get($facade=null){
        $facade = $facade==null?self::$_facade:$facade;
        if( isset(self::$_facades[ $facade ]) )
            return self::$_facades[ $facade ];
        return null;
    }

    /**
     * @param $class_path
     * @param $modeler
     * @param \Pretty\Cache\ICache $cache
     * @return Facade
     */
    public static function auto($class_path, \DBHelper\Modeler\ITableModeler $modeler, Cache $cache=null){
        $class_path = is_string($class_path)?array($class_path):$class_path;
        $cache = $cache === null ? new \Pretty\Cache\PhpArray() : $cache;
        $loader = new LoaderBuilder($modeler, $cache);
        $Facade = new Facade($class_path, $loader);
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
        self::$_facade = $facade_name;
        $facade = self::get(self::$_facade);
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
     * @var LoaderBuilder
     */
    public $loader_build;
    /**
     * @var array
     */
    public $paths_to_models;
    /**
     * @var MetaBuilder\LoaderBuilder
     */
    private $loader_builder;
    public function __construct ($paths_to_models, LoaderBuilder $loader){
        $this->paths_to_models = $paths_to_models;
        $this->loader_builder = $loader;
    }

    /**
     * @param $class_name
     * @return ClassModel
     */
    public function get_meta_data( $class_name ){
        return $this->loader_builder->get_meta_data($class_name);
    }
    public function get_db_resource(  ){
        return $this->loader_builder->table_builder->getModeler()->getLayer()->get_resource();
    }
    public function enable(  ){
        ModelORM::set_db( $this->get_db_resource() );
        spl_autoload_register( array($this, "live_loader") );
    }
    public function disable(  ){
        spl_autoload_unregister( array($this, "live_loader") );
    }
    public function live_loader( $class_name ){
        $Facade = $this;
        if( $Facade !== null ){
            $f = resolve_class_name($class_name);
            foreach( $this->paths_to_models as $d ){
                if( file_exists($d.$f) ){
                    require $d.$f;
                    $parents = \class_parents($class_name);
                    if( $parents !== false ){
                        if( in_array("Pretty\Model", $parents) ){
                            $Facade->get_meta_data($class_name);
                        }
                    }
                }
            }
        }
    }

    /**
     * Purge the cache and delete all tables
     * for dev purpose only
     */
    public function clean_up( ){
        $this->loader_builder->table_builder->purge();
        $this->loader_builder->cache->purge();
    }
}

if( function_exists("resolve_class_name") == false ){
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