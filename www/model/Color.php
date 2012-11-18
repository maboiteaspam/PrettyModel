<?php
use \Pretty\Model as Model;
/**
 *
 * @Table(name='color',encoding='utf8')
 * @Index(name='PRIMARY', type='PK', fields={{name='id'}} )
 * @AutoIncrement('id')
 */
class Color extends Model{
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
     * @Column(type='string')
     * @var String
     */
    public $rgb_value;
    /**
     * @HasManyToMany(type='Car')
     * @var PrettyORM
     */
    public $cars;
}
