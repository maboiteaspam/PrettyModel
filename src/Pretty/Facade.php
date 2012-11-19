<?php
namespace Pretty;

use Pretty\MetaData\ClassModel as ClassModel;
use Pretty\MetaLoader\LoaderBuilder as LoaderBuilder;

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
     * Initialize a nez facade, register it, and select it
     * If cache is null, a default PhpArray object is used instead
     *
     * @param $class_path
     * @param $modeler
     * @param  $cache
     * @return Facade
     */
    public static function auto($class_path, \DBHelper\Modeler\ITableModeler $modeler, $cache=array()){
        $class_path = is_string($class_path)?array($class_path):$class_path;
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
     * @var array
     */
    public $paths_to_models;
    /**
     * @var MetaLoader\LoaderBuilder
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

    /**
     * Returns the raw DB connection resource
     *
     * @return mixed
     */
    public function get_db_resource(  ){
        return $this->loader_builder->table_builder->getModeler()->getLayer()->get_resource();
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
        spl_autoload_register( array($this, "live_loader") );
    }

    /**
     * Detach the auto loader
     */
    public function disable(  ){
        ModelORM::set_db( null );
        spl_autoload_unregister( array($this, "live_loader") );
    }

    /**
     * Handler to listen loaded
     * class and prepare their meta
     * @param $class_name
     */
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