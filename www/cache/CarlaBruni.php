<?php return array (
  'meta' => 
  Pretty\MetaData\ClassModel::__set_state(array(
     '_obj_properties' => 
    array (
      0 => 'class_name',
      1 => 'table_name',
      2 => 'encoding',
      3 => 'engine',
      4 => 'index',
      5 => 'pk',
      6 => 'autoincrements',
      7 => 'properties',
    ),
     '_obj_values' => 
    array (
      'properties' => 
      Pretty\MetaData\ExportableArrayObject::__set_state(array(
         'id' => 
        Pretty\MetaData\Property::__set_state(array(
           '_obj_properties' => 
          array (
            0 => 'property_name',
            1 => 'property_type',
            2 => 'value_type',
            3 => 'size',
            4 => 'autoincrement',
            5 => 'encoding',
            6 => 'default_value',
            7 => 'nullable',
            8 => 'firewall',
            9 => 'association',
            10 => 'on',
            11 => 'related_keys',
          ),
           '_obj_values' => 
          array (
            'related_keys' => 
            Pretty\MetaData\ExportableArrayObject::__set_state(array(
            )),
            'property_name' => 'id',
            'property_type' => 'scalar',
            'value_type' => 'int',
            'size' => NULL,
            'autoincrement' => false,
            'encoding' => NULL,
            'default_value' => NULL,
            'nullable' => NULL,
          ),
        )),
         'nom' => 
        Pretty\MetaData\Property::__set_state(array(
           '_obj_properties' => 
          array (
            0 => 'property_name',
            1 => 'property_type',
            2 => 'value_type',
            3 => 'size',
            4 => 'autoincrement',
            5 => 'encoding',
            6 => 'default_value',
            7 => 'nullable',
            8 => 'firewall',
            9 => 'association',
            10 => 'on',
            11 => 'related_keys',
          ),
           '_obj_values' => 
          array (
            'related_keys' => 
            Pretty\MetaData\ExportableArrayObject::__set_state(array(
            )),
            'property_name' => 'nom',
            'property_type' => 'scalar',
            'value_type' => 'string',
            'size' => 255,
            'autoincrement' => false,
            'encoding' => NULL,
            'default_value' => NULL,
            'nullable' => NULL,
          ),
        )),
         'prenom' => 
        Pretty\MetaData\Property::__set_state(array(
           '_obj_properties' => 
          array (
            0 => 'property_name',
            1 => 'property_type',
            2 => 'value_type',
            3 => 'size',
            4 => 'autoincrement',
            5 => 'encoding',
            6 => 'default_value',
            7 => 'nullable',
            8 => 'firewall',
            9 => 'association',
            10 => 'on',
            11 => 'related_keys',
          ),
           '_obj_values' => 
          array (
            'related_keys' => 
            Pretty\MetaData\ExportableArrayObject::__set_state(array(
            )),
            'property_name' => 'prenom',
            'property_type' => 'scalar',
            'value_type' => 'string',
            'size' => 2,
            'autoincrement' => false,
            'encoding' => NULL,
            'default_value' => NULL,
            'nullable' => NULL,
          ),
        )),
         'jewels' => 
        Pretty\MetaData\Property::__set_state(array(
           '_obj_properties' => 
          array (
            0 => 'property_name',
            1 => 'property_type',
            2 => 'value_type',
            3 => 'size',
            4 => 'autoincrement',
            5 => 'encoding',
            6 => 'default_value',
            7 => 'nullable',
            8 => 'firewall',
            9 => 'association',
            10 => 'on',
            11 => 'related_keys',
          ),
           '_obj_values' => 
          array (
            'related_keys' => 
            Pretty\MetaData\ExportableArrayObject::__set_state(array(
               'id' => 'carla_bruni_id',
            )),
            'property_name' => 'jewels',
            'property_type' => 'association',
            'value_type' => 'Jewel',
            'on' => 'carla_bruni',
            'association' => 'HasMany',
          ),
        )),
      )),
      'index' => 
      Pretty\MetaData\ExportableArrayObject::__set_state(array(
         'PRIMARY' => 
        Pretty\MetaData\Index::__set_state(array(
           '_obj_properties' => 
          array (
            0 => 'name',
            1 => 'type',
            2 => 'engine',
            3 => 'fields',
          ),
           '_obj_values' => 
          array (
            'fields' => 
            Pretty\MetaData\ExportableArrayObject::__set_state(array(
               0 => 'id',
            )),
            'type' => 'PK',
            'name' => 'PRIMARY',
          ),
        )),
      )),
      'class_name' => 'CarlaBruni',
      'table_name' => 'carlabruni',
      'autoincrements' => 
      array (
        0 => 'id',
      ),
      'pk' => 'PRIMARY',
      'encoding' => 'utf8',
    ),
  )),
  'file' => '/home/clement/Bureau/PrettyModel/www/model/CarlaBruni.php',
  'fp' => '456047785f8b840e1fc8d5571fc4d9974647d1c2',
);