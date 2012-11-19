<?php
namespace Cache;

class File implements ICache
{
    use ArrayLikeActor;

    public $path;
    public function __construct( $path ){
        $this->path = $path;
    }
    public function write($key, $data){
        return file_put_contents($this->path."/".$key.".php", "<?php return ".var_export($data,true).";" );
    }
    public function read($key){
        $file = $this->path."/".$key.".php";
        if( file_exists($file) )
            return include($file);
    }
    public function exists($key){
        return file_exists($this->path."/".$key.".php");
    }
    public function delete($key){
        $file = $this->path."/".$key.".php";
        if( file_exists($file) )
            return unlink($this->path."/".$key.".php");
        return false;
    }
    public function purge(){
        $retour = false;
        foreach( scandir("./cache") as $f ){
            if( in_array($f, array(".","..")) == false ){
                unlink("./cache/".$f);
                $retour = true;
            }
        }
        return $retour;
    }

}
