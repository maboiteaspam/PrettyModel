<?php
use \Pretty\Model as Model;
/**
 *
 * @Table(name='carlabruni',encoding='utf8')
 * @Index(name='PRIMARY',type='PK',fields={{name='id'}} )
 * @AutoIncrement('id')
 */
class CarlaBruni extends Model{
    /**
     * @Column(type='int')
     * @var Int
     */
    public $id;
    /**
     * @Column(type='string', size=255)
     * @var String
     */
    public $nom;

    /**
     * @Column(type='string', size=2)
     * @var String
     */
    public $prenom;

    /**
     * @HasMany(type='Jewel', on='carla_bruni')
     * @var PrettyORM
     */
    public $jewels;
}

