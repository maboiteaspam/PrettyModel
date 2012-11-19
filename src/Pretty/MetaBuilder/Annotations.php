<?php
class Table extends \Annotation {
    public $name;
    public $encoding;
    public $engine;
    public $comment;
}
class Column extends \Annotation {
    public $autoincrement;
    public $type;
    public $size;
    public $encoding;
    public $nullable;
}
class Index extends \Annotation {
    public $name;
    public $type;
    public $engine;
    public $fields;
}
class AutoIncrement extends \Annotation {
}
class HasMany extends \Annotation {
    public $type;
    public $on;
}
class HasOne extends \Annotation {
    public $type;
    public $on;
}
class BelongsTo extends \Annotation {
    public $type;
}
class HasManyToMany extends \Annotation {
    public $type;
    public $on;
}