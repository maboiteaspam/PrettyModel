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
         'price' => 
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
            'property_name' => 'price',
            'property_type' => 'scalar',
            'value_type' => 'int',
            'size' => NULL,
            'autoincrement' => false,
            'encoding' => NULL,
            'default_value' => NULL,
            'nullable' => NULL,
          ),
        )),
         'carla_bruni' => 
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
               'carla_bruni_id' => 'id',
            )),
            'property_name' => 'carla_bruni',
            'property_type' => 'association',
            'value_type' => 'CarlaBruni',
            'on' => NULL,
            'association' => 'BelongsTo',
          ),
        )),
         'carla_bruni_id' => 
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
            'property_name' => 'carla_bruni_id',
            'property_type' => 'scalar',
            'value_type' => 'int',
            'size' => NULL,
            'autoincrement' => false,
            'encoding' => NULL,
            'default_value' => NULL,
            'nullable' => NULL,
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
      'class_name' => 'Jewel',
      'table_name' => 'jewel',
      'autoincrements' => 
      array (
        0 => 'id',
      ),
      'pk' => 'PRIMARY',
      'encoding' => 'utf8',
    ),
  )),
  'file' => '/home/clement/Bureau/PrettyModel/www/model/Jewel.php',
  'fp' => '788ecd11c040ebcc3a823c2e078698ff96c5116d',
);