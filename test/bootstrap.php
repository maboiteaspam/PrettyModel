<?php
$dir = __DIR__ . "/";
// Load externals libs
// @todo unify this with autoloader strategy (composer is a solution?)
require( $dir."../src/Addedum-0.4.1/annotations.php");
require( $dir."../src/idiorm/idiorm.php");

// path where is hosted the pretty namespace
$dir = array($dir."../src/", $dir);

/**
 * Default method to resolve class name
 * to file path, PSR compliant
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

// default auto loader
spl_autoload_register( function ($class_name) use($dir){
    $f = resolve_class_name($class_name);
    foreach( $dir as $d ){
        if( file_exists($d.$f) ){
            require $d.$f;
        }
    }
});


/*
 * Original boostrap from Slim framework
set_include_path(dirname(__FILE__) . '/../' . PATH_SEPARATOR . get_include_path());

require_once 'Slim/Slim.php';

// Register Slim's autoloader
\Slim\Slim::registerAutoloader();

//Register non-Slim autoloader
function customAutoLoader( $class )
{
    $file = rtrim(dirname(__FILE__), '/') . '/' . $class . '.php';
    if ( file_exists($file) ) {
        require $file;
    } else {
        return;
    }
}
spl_autoload_register('customAutoLoader');
*/