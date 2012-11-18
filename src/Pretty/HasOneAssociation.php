<?php
namespace Pretty;

class HasOneAssociation extends Association{
    public function attach( Model $model ){
        $source_meta = Facade::get(null)->get_meta_data($this->source_class);
        $source_pk = $source_meta->getPKFields();

        if( $this->target_class !== get_class($model) ){
            return false;
        }

        $target_property = $this->target_property;

        foreach( $source_pk as $pk_field ){
            $foreign_key_name   = $target_property."_".$pk_field;
            $model->$foreign_key_name = $this->model->$pk_field;
        }
        return $model->save();
    }
    protected function apply_association(){
        $source_meta = Facade::get(null)->get_meta_data($this->source_class);
        $source_pk = $source_meta->getPKFields();

        $target_property = $this->target_property;
        $target_class = $this->target_class;

        $this->orm = $target_class::query();
        foreach( $source_pk as $pk_field ){
            $foreign_key_name   = $target_property."_".$pk_field;
            $this->orm->where($foreign_key_name, $this->model->$pk_field );
        }
    }
    public function find(){
        return $this->orm->find_one();
    }
}
