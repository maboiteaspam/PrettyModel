<?php
namespace Pretty;

class BelongsToAssociation extends Association{
    public function attachTo( Model $model ){
        if( $this->target_class !== get_class($model) ){
            return false;
        }
        $target_meta        = Facade::get(null)->get_meta_data($this->target_class);
        $target_pk          = $target_meta->getPKFields();

        $source_property = $this->source_property;

        foreach( $target_pk as $pk_field ){
            $foreign_key_name   = $source_property."_".$pk_field;
            $this->model->$foreign_key_name = $model->$pk_field;
        }
        return $model->save();
    }
    protected function apply_association(){
        $target_meta = Facade::get(null)->get_meta_data($this->target_class);
        $target_pk = $target_meta->getPKFields();

        $source_property = $this->source_property;
        $target_class = $this->target_class;

        $this->orm = $target_class::query();
        foreach( $target_pk as $pk_field ){
            $foreign_key_name   = $source_property."_".$pk_field;
            $this->orm->where($pk_field, $this->model->$foreign_key_name );
        }
    }
    public function find(){
        return $this->orm->find_one();
    }
}
