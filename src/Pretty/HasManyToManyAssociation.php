<?php
namespace Pretty;

class HasManyToManyAssociation extends Association{
    public function attach( Model $model ){
        if( $this->target_class !== get_class($model) ){
            return false;
        }
        $source_meta = Facade::get(null)->get_meta_data($this->source_class);
        $source_pk_name = $source_meta["class"]["pk"];
        $source_pk = $source_meta["class"]["index"][ $source_pk_name ]["fields"];

        $target_property = $this->target_property;

        foreach( $source_pk as $pk_field ){
            $foreign_key_name   = $target_property."_".$pk_field;
            $model->$foreign_key_name = $this->model->$pk_field;
        }
        return $model->save();
    }
    protected function apply_association(){

        $source_meta = Facade::get(null)->get_meta_data($this->source_class);
        $source_table_name = $source_meta->table_name;
        $source_pk = $source_meta->getPKFields();

        //$target_meta = Facade::get(null)->get_meta_data($this->target_class);
        $target_table_name = $source_meta->table_name;
        $target_pk = $source_meta->getPKFields();

        // The table name of the join model,
        // formed by concatenating the names of the base table
        // and the associated table , in alphabetical order.
        $table_names = array($source_table_name, $target_table_name);
        sort($table_names, SORT_STRING);
        $join_table_name = join("_", $table_names);

        $target_class = $this->target_class;
        $this->orm = $target_class::query();


        $mid_pk = $target_table_name ."_". $target_pk[0];
        $this->orm
            ->join($join_table_name, array("{$target_table_name}.{$target_pk[0]}", '=', "{$join_table_name}.{$mid_pk}"));
        if( count($target_pk)  > 1 ){
            array_shift($target_pk);
            foreach( $target_pk as $pk ){
                $mid_pk = $target_table_name ."_". $pk;
                $this->orm
                    ->on("{$target_table_name}.{$pk}", '=', "{$join_table_name}.{$mid_pk}");
            }
        }

        $mid_pk = $source_table_name ."_". $source_pk[0];
        $this->orm
            ->where( "{$join_table_name}.{$mid_pk}", $this->model->{$source_pk[0]} );
        if( count($source_pk)  > 1 ){
            array_shift($source_pk);
            foreach( $source_pk as $pk ){
                $mid_pk = $source_table_name ."_". $pk;
                $this->orm
                    ->where( "{$join_table_name}.{$mid_pk}", $this->model->{$pk} );
            }
        }

        $property_names = array($source_table_name, $target_table_name);
        sort($property_names, SORT_STRING);
        $join_property_value = join("_", $property_names);
        $this->orm
            ->where( "{$join_table_name}.properties_name", $join_property_value );
    }
    public function find(){
        return $this->orm->find_many();
    }
}
