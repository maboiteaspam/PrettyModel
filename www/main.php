<?php

var_dump("Init start....");

# region configuration (to externalize ?)
$class_path = array("model/");
$cache_dir = "cache/";

// MySQL setup
$db_type = "mysql";
$db_name = "test";
$db_user = "root";
$db_pwd = "123456";
$db_host= "localhost";
$db = new PDO(
    "$db_type:host=$db_host;dbname=$db_name",
    $db_user,
    $db_pwd,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

/*
    SQLite memory setup
$db_type = "sqlite";
$db_name = ":memory:";
$db_user = null;
$db_pwd = null;
$db_host= null;

$db = new PDO(
    'sqlite::memory:',
    $db_user,
    $db_pwd
);
 */

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
# endregion

# region vendors libs to package later
require("../src/Addedum-0.4.1/annotations.php");
require("../src/idiorm/idiorm.php");
# endregion

# region auto loading PSR0 compliant
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
$dir = array("../src/");
spl_autoload_register( function ($class_name) use($dir){
    $f = resolve_class_name($class_name);
    foreach( $dir as $d ){
        if( file_exists($d.$f) ){
            require $d.$f;
        }
    }
});
# endregion




# region Initialization
use Pretty\Facade as Facade;

$cache      = new Pretty\Cache\File($cache_dir);
$modeler    = \DBHelper\Smart::factory($db_type, $db_name, $db);

// the active recorder is an helper to speed up the modeler layer that
// makes lots of useless sql queries by default
$modeler    = new \DBHelper\Modeler\ActiveRecorder($modeler);

// set up the Facade with required module, a classpath, a modeler and a cache system
$Facade = \Pretty\Facade::auto($class_path, $modeler, $cache);

// for dev prupose it is usefull to clean up database and cache
// $Facade->clean_up();

# endregion

var_dump("Init done....");

$car    = new Car();
$color  = new Color();
$dumb   = new CarlaBruni();
$jewel  = new Jewel();


$dumb = new CarlaBruni();
$dumb->nom = "Bruni";
$dumb->prenom = "Carla";
$dumb->save();
$retour = CarlaBruni::query()->where_id_is(1)->find_one();

$jewel = new Jewel();
$jewel->price = "Bruni";
$jewel->carla_bruni_id = $dumb->id;
$jewel->save();

$retour = Jewel::query()->where_id_is(1)->find_one();
$retour->carla_bruni->attachTo($dumb);
$retour->save();
echo Jewel::query()->where_id_is(1)->find_one();

$carla = $jewel->carla_bruni->find();
$carla->nom = "tomate";
$carla->prenom = "tomate";
$carla->save();
($jewel->carla_bruni->find()->nom);

$s = microtime(true);
for( $i=0,$m=2000;$i<$m;$i++){
    ($jewel->carla_bruni->find()->nom);
    $carla->save();
}
$s = microtime(true)-$s;
var_dump((($m*2)/$s)." qps with $m queries in $s s") ;
var_dump($jewel->carla_bruni->find()->prenom);
var_dump("ok");



