<?php
namespace Pretty\MetaBuilder;
/**
 */
interface ILoader
{
    /**
     * @param $class_name
     * @return \Pretty\MetaData\ClassModel
     */
    public function get_meta_data( $class_name );
}