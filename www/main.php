<?php

require("../src/Addedum-0.4.1/annotations.php");
require("../src/idiorm/idiorm.php");
//require("vendors/db_helper/db_helper.php");
//require("vendors/pretty/model.php");

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


use Pretty\Facade as Facade;

// could be a testing DSN : sqlite::memory:
$db = new PDO(
    'mysql:host=localhost;dbname=test',
    'root',
    '123456',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db_type= "mysql";
$db_name= "test";
$modeler = \DBHelper\Smart::factory($db_type, $db_name, $db);
$modeler = new \DBHelper\Modeler\ActiveRecorder($modeler);
$cache = new Pretty\Cache\File("cache/");
$class_path = array("model/");

$Facade = \Pretty\Facade::auto($class_path, $modeler, $cache);

/*
$Facade->clean_up();
 */

var_dump("ok");

$car = new Car();
$color = new Color();
$dumb = new CarlaBruni();
$jewel = new Jewel();


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



