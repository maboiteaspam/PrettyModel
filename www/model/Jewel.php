<?php
use \Pretty\Model as Model;

/**
 *
 * @Table(name='jewel',encoding='utf8')
 * @Index(name='PRIMARY', type='PK', fields={{name='id'}} )
 * @AutoIncrement('id')
 */
class Jewel extends Model{
    /**
     * @Column(type='int')
     * @var Int
     */
    public $id;
    /**
     * @Column(type='int')
     * @var Int
     */
    public $price;
    /**
     * @BelongsTo(type='CarlaBruni')
     * @var PrettyORM
     */
    public $carla_bruni;
}
