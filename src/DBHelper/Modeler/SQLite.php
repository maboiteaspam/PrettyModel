<?php
namespace DBHelper\Modeler;

use DBHelper\Layer\ILayer;

class SQLite /*implements ITableModeler*/
{
    /**
     * @var iLayer
     */
    protected $layer;

    /**
     * @var string
     */
    protected $database_name;

    /**
     * @return \DBHelper\Layer\ILayer
     */
    public function getLayer(){
        return $this->layer;
    }

    /**
     * @param \DBHelper\Layer\ILayer $layer
     */
    public function setLayer( ILayer $layer ){
        $this->layer = $layer;
    }

    /**
     * @param $name
     * @return mixed|void
     */
    public function setContainerName( $name ){
        $this->database_name = $name;
    }

    /**
     * @param $sql
     * @return false|int
     * @throws \DBHelper\SQLException
     */
    protected function exec($sql){
        try{
            $retour = $this->layer->exec($sql)!==false;;
            if( $retour === false ){
                echo "failed : ".$sql;
                echo "<br/>\n";
            }
            return $retour;
        }catch(\Exception $Ex ){
            echo "failed : ".$sql;
            echo "\n";
            throw new \DBHelper\SQLException($sql,0,$Ex);
        }
    }

    /**
     * @param $sql
     * @return false|\Traversable
     * @throws \DBHelper\SQLException
     */
    protected function query($sql){
        try{
            $retour = $this->layer->query($sql);
            if( $retour === false ){
                echo "failed : ".$sql;
                echo "<br/>\n";
            }
            return $retour;
        }catch(\Exception $Ex ){
            echo "failed : ".$sql;
            echo "\n";
            throw new \DBHelper\SQLException($sql,0,$Ex);
        }
    }

    public function listTables(){
        $tables_list  = array();
        $sql = "SELECT name FROM sqlite_master
                WHERE type='table'
                ORDER BY name";
        foreach ($this->query($sql) as $row) {
            $tables_list[] = $row[0];
        }
        return $tables_list;
    }

    public function listColumns( $table_name ){
        $columns_list = array();
        $sql = "PRAGMA table_info(`".$table_name."`)";
        foreach ($this->query($sql) as $row) {
            $columns_list[] = $row['name'];
        }
        return $columns_list;
    }

    public function listIndex( $table_name ){
        $indexs_list = array();
        $sql = "SHOW INDEX FROM ".$this->proper_name( $table_name )." FROM `".$this->database_name."`; ";
        $indexs_list[$table_name] = array();
        foreach ($this->query($sql) as $row) {
            $indexs_list[] = $row["Key_name"];
        }
        return $indexs_list;
    }

    protected function proper_name( $value_name, $with_quote=true ){
        $value_name = strtolower($value_name);
        return $with_quote?"`".$value_name."`" : $value_name;
    }

    public function hasTable( $raw_table_name ){
        return in_array($this->proper_name( $raw_table_name, false ),
            $this->listTables());
    }
    public function hasField( $raw_table_name, $field_name ){
        return in_array($this->proper_name( $field_name, false ),
            $this->listColumns( $raw_table_name ));
    }
    public function hasIndex( $raw_table_name, $index_name ){
        return in_array($index_name,
            $this->listIndex( $raw_table_name ));
    }

    public function createTable( $raw_table_name, $options=array() ){
        $sql = "CREATE TABLE ".$this->proper_name( $raw_table_name )."";
        /* Create a default required field, will be removed in later time */
        $sql .= " (`required_first_field` INT) ";
        if( isset($options["engine"]) )
            $sql .= " ENGINE = " . $options["engine"];
        if( isset($options["encoding"]) ){
            $char_set = substr($options["encoding"], 0, strpos($options["encoding"], "_"));
            $char_set = $char_set===""?$options["encoding"]:$char_set;
            $sql .= " DEFAULT CHARACTER SET = '".$char_set."'";
            if( $char_set != $options["encoding"] )
                $sql .= " COLLATE = '".$options["encoding"]."'";
        }
        if( isset($options["comment"]) )
            $sql .= " COMMENT = '" . addslashes($options["comment"]) . "'";
        $sql .= ";";


        return $this->exec($sql);
    }
    public function createField( $raw_table_name, $field_name, $options=array() ){

        $mysql_type = null;
        $size       = isset($options["size"]) ? $options["size"] : null;
        $nullable   = isset($options["nullable"]) ? $options["nullable"] : null;
        $default    = isset($options["default_value"]) ? $options["default_value"] : null;
        $comment    = isset($options["comment"]) ? $options["comment"] : null;
        $char_set   = null;
        $collate    = null;
        if( isset($options["encoding"]) ){
            $char_set   = substr($options["encoding"], 0, strpos($options["encoding"], "_"));
            $collate    = $options["encoding"];
        }

        if( isset($options["type"]) ){
            switch( $options["type"] ){
                case "text":
                    if( $size === null ) $mysql_type = "TEXT";
                    else $mysql_type = "VARCHAR";
                    break;
                default:
                    $mysql_type = strtoupper($options["type"]);
                    break;
            }
        }

        $sql = "ALTER TABLE ".$this->proper_name( $raw_table_name )."";
        $sql .= " ADD ".$this->proper_name( $field_name )."";
        $sql .= " ".$mysql_type."";
        if( $size !== NULL )            $sql .= " (".$size.") ";

        if(in_array($mysql_type, array("INT","FLOAT")) === false ){
            if( $char_set !== NULL )        $sql .= " CHARACTER SET " . $char_set;
            if( $collate !== NULL )         $sql .= " COLLATE " . $collate;
        }

        if( $nullable === true  )                $sql .= " NULL ";
        else if( $nullable === false  )          $sql .= " NOT NULL ";

        if( isset($options["default_value"])  ){
            if( $default === 'null' )   $sql .= " DEFAULT NULL ";
            else  if( $default !== null )  $sql .= " DEFAULT '".strval($default)."' ";
        }
        if( $comment !== NULL )         $sql .= " COMMENT '" .  addslashes($comment) . "' ";

        $sql .= ";";

        return $this->exec($sql);
    }
    public function createIndex( $raw_table_name, $index_name, $options=array() ){

        $type       = isset($options["type"]) ? strtoupper($options["type"]) : null;
        $engine     = isset($options["engine"]) ? $options["engine"] : null;

        if( $type !== "PK" ){
            switch( $type ){
                case "UNIQUE":
                    $type = "UNIQUE INDEX";
                    break;
                case "FULLTEXT":
                    $type = "FULLTEXT INDEX";
                    break;
                case "SPATIAL":
                    $type = "SPATIAL INDEX";
                    break;
            }

            $sql = "CREATE ".$type." ".$this->proper_name( $index_name )." ";
            if( $engine !== null )
                $sql .= " USING ".$engine." ";
            $sql .= " ON ".$this->proper_name( $raw_table_name )." ";
            $sql .= " ( ";
            foreach( $options["fields"] as $field_name=>$field_options ){
                $sql .= " ".$this->proper_name( $field_name )." ";
                if( isset($field_options["size"]) )
                    $sql .= "(".$field_options["size"].") ";
                if( isset($field_options["order"]) )
                    $sql .= " ".$field_options["order"]." ";
                $sql .= ", ";
            }
            $sql = substr($sql,0,-2);
            $sql .= " ) ";
            $sql .= ";";
        }else{
            $sql = "ALTER TABLE ".$this->proper_name( $raw_table_name )." ";
            $sql .= "ADD PRIMARY KEY";
            $sql .= "( ";
            foreach( $options["fields"] as $field_name=>$field_options ){
                $sql .= " ".$this->proper_name( $field_name )." ";
                if( isset($field_options["size"]) )
                    $sql .= "(".$field_options["size"].") ";
                if( isset($field_options["order"]) )
                    $sql .= " ".$field_options["order"]." ";
                $sql .= ", ";
            }
            $sql = substr($sql,0,-2);
            $sql .= ") ";
            $sql .= "; ";
        }

        return $this->exec($sql);
    }

    public function removeTable( $raw_table_name ){
        $sql    = "DROP TABLE ".$this->proper_name( $raw_table_name )." ; ";

        return $this->exec($sql);
    }
    public function removeField( $raw_table_name, $field_name ){
        if( count($this->listColumns($raw_table_name)) == 1 )
            throw new \DBHelper\SQLException("Cannot remove the last field, drop table instead.");
        return false;

    }
    public function removeIndex( $raw_table_name, $index_name ){
        $sql    = "DROP INDEX ".$this->proper_name( $index_name )." ON ".$this->proper_name( $raw_table_name )." ; ";

        return $this->exec($sql);
    }

    public function updateField( $raw_table_name, $field_name, $options ){

        $mysql_type = null;
        $size       = isset($options["size"]) ? $options["size"] : null;
        $nullable   = isset($options["nullable"]) ? $options["nullable"] : null;
        $default    = isset($options["default_value"]) ? $options["default_value"] : null;
        $comment    = isset($options["comment"]) ? $options["comment"] : null;
        $char_set   = null;
        $collate    = null;
        if( isset($options["encoding"]) ){
            $char_set   = substr($options["encoding"], 0, strpos($options["encoding"], "_"));
            $collate    = $options["encoding"];
        }

        if( isset($options["type"]) ){
            switch( $options["type"] ){
                case "text":
                    if( $size === null ) $mysql_type = "TEXT";
                    else $mysql_type = "VARCHAR";
                    break;
                default:
                    $mysql_type = strtoupper($options["type"]);
                    break;
            }
        }

        $sql = "ALTER TABLE ".$this->proper_name( $raw_table_name )."";
        $sql .= " CHANGE ".$this->proper_name( $field_name )."";
        $sql .= "  ".$this->proper_name( $field_name )."";
        $sql .= " ".$mysql_type."";
        if( $size !== NULL )            $sql .= " (".$size.") ";

        if(in_array($mysql_type, array("INT","FLOAT")) === false ){
            if( $char_set !== NULL )        $sql .= " CHARACTER SET " . $char_set;
            if( $collate !== NULL )         $sql .= " COLLATE " . $collate;
        }

        if( $nullable === true  )                $sql .= " NULL ";
        else if( $nullable === false  )          $sql .= " NOT NULL ";

        if( isset($options["autoincrement"])  ){
            if( $options["autoincrement"] )
                $sql .= " AUTO_INCREMENT ";
        }

        if( isset($options["default_value"])  ){
            if( $default === 'null' )   $sql .= " DEFAULT NULL ";
            else  if( $default !== null )  $sql .= " DEFAULT '".strval($default)."' ";
        }
        if( $comment !== NULL )         $sql .= " COMMENT '" .  addslashes($comment) . "' ";

        $sql .= ";";

        return $this->exec($sql);
    }

    public function clean( $raw_table_name ){
        $this->removeField( $raw_table_name, "required_first_field" );
    }
    public function purge( ){
        $count = 0;
        foreach($this->listTables() as $table ){
            $this->removeTable($table);
            $count++;
        }
        return $count;
    }
}
