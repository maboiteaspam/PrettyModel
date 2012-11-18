<?php
use \Pretty\Model as Model;
/**
 *
 * @Table(name='car',encoding='utf8')
 * @Index(name='PRIMARY', type='PK', fields={{name='id'}} )
 * @AutoIncrement('id')
 */
class Car extends Model{
    /**
     * @Column(type='int')
     * @var Int
     */
    public $id;

    /**
     * @Column(type='string')
     * @var String
     */
    public $name;
    /**
     * @HasManyToMany(type='Color')
     * @var PrettyORM
     */
    public $colors;
}