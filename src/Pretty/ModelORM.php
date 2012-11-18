<?php
namespace Pretty;

class ModelORM extends \ORM
{
    /**
     * Factory method, return an instance of this
     * class bound to the supplied table name.
     */
    public static function for_table($table_name) {
        $orm = new ModelORM($table_name);
        return $orm;
    }

    #region RE IMPORTED METHODS

    /**
     * The wrapped find_one and find_many classes will
     * return an instance or instances of this class.
     */
    protected $_class_name;

    /**
     * Set the name of the class which the wrapped
     * methods should return instances of.
     */
    public function set_class_name($class_name) {
        $this->_class_name = $class_name;
    }

    /**
     * Add a custom filter to the method chain specified on the
     * model class. This allows custom queries to be added
     * to models. The filter should take an instance of the
     * ORM wrapper as its first argument and return an instance
     * of the ORM wrapper. Any arguments passed to this method
     * after the name of the filter will be passed to the called
     * filter function as arguments after the ORM class.
     */
    public function filter() {
        $args = func_get_args();
        $filter_function = array_shift($args);
        array_unshift($args, $this);
        if (method_exists($this->_class_name, $filter_function)) {
            return call_user_func_array(array($this->_class_name, $filter_function), $args);
        }
    }

    /**
     * Method to create an instance of the model class
     * associated with this wrapper and populate
     * it with the supplied Idiorm instance.
     */
    protected function _create_model_instance($orm) {
        if ($orm === false) {
            return false;
        }
        $model = new $this->_class_name();
        $model->set_orm($orm);
        return $model;
    }


    /**
     * Wrap Idiorm's find_many method to return
     * an array of instances of the class associated
     * with this wrapper instead of the raw ORM class.
     */
    public function find_many() {
        return array_map(array($this, '_create_model_instance'), parent::find_many());
    }

    /**
     * Wrap Idiorm's create method to return an
     * empty instance of the class associated with
     * this wrapper instead of the raw ORM class.
     */
    public function create($data=null) {
        return $this->_create_model_instance(parent::create($data));
    }
    #endregion


    /**
     * Add a simple JOIN source to the query
     */
    public function on($first_column, $operator, $second_column) {
        $first_column = $this->_quote_identifier($first_column);
        $second_column = $this->_quote_identifier($second_column);
        $this->_join_sources[count($this->_join_sources)-1] = " AND {$first_column} {$operator} {$second_column}";
        return $this;
    }



    public function set_new($data=null) {
        $this->_is_new = true;
        if (!is_null($data)) {
            return $this->hydrate($data)->force_all_dirty();
        }
        return $this;
    }

    /**
     */
    public function set_known($data) {
        $this->_is_new = false;
        $this->hydrate($data, false);
        $this->clean_dirty_fields(array_keys($data));
        return $this;
    }

    /**
     * Wrap Idiorm's find_one method to return
     * an instance of the class associated with
     * this wrapper instead of the raw ORM class.
     */
    public function find_one() {
        $this->limit(1);
        $rows = $this->_run();

        if (empty($rows)) {
            return false;
        }

        $retour = new $this->_class_name();
        $retour->set_known($rows[0]);

        return $retour;
    }

    /**
     */
    public function clean_dirty_fields($fields_to_clean) {
        foreach( $fields_to_clean as $f ) unset( $this->_dirty_fields[$f] );
        return $this;
    }



    /**
     * Execute the SELECT query that has been built up by chaining methods
     * on this class. Return an array of rows as associative arrays.
     */
    protected function _run() {
        try{
            $retour = parent::_run();
        }catch( \PDOException $Ex ){
            $query = $this->print_query();
            echo $query;
            throw $Ex;
        }
        return $retour;
    }
    public function print_query() {
        $query = parent::_build_select();
        $parameters = $this->_values;
        if (count($parameters) > 0) {
            // Escape the parameters
            $parameters = array_map(array(self::$_db, 'quote'), $parameters);

            // Replace placeholders in the query for vsprintf
            $query = str_replace("?", "%s", $query);

            // Replace the question marks in the query with the parameters
            $bound_query = vsprintf($query, $parameters);
        } else {
            $bound_query = $query;
        }
        return $bound_query;
    }

}