<?php
namespace Pretty\Util;
/**
 */
class ClassLoader
{
    protected $classpath;
    protected $listener;

    public function __construct($classpath){
        $this->classpath = $classpath;
    }

    /**
     * attach the auto loader
     *
     * @param $callback
     */
    public function enable( ){
        spl_autoload_register( array($this, "auto_load_class") );
        return $this;
    }

    /**
     * Detach the auto loader
     */
    public function disable( ){
        spl_autoload_unregister( array($this, "auto_load_class") );
        return $this;
    }
    public function listen( $func ){
        $this->listener[] = $func;
        return $this;
    }

    /**
     * Detach the auto loader
     */
    public function auto_load_class( $class_name ){
        $f = resolve_class_name($class_name);
        foreach( $this->classpath as $d ){
            if( file_exists($d.$f) ){
                require $d.$f;
                foreach( $this->listener as $l ) $l($class_name);
                return true;
            }
        }
        return false;
    }

}
