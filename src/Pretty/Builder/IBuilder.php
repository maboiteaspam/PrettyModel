<?php
namespace Pretty\Builder;
/**
 */
interface IBuilder
{
    /**
     * @param $facade
     * @param $repository
     * @param $class_name
     * @return \Pretty\MetaData\ClassModel
     */
    public function build_meta_data($facade, $repository, $class_name);
    public function create_table($facade, $meta);
    public function update_table($facade, $meta);
    public function enhance_jit($facade, $meta);
}
