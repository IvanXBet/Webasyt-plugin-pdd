<?php
return array(
    'shop_pdd_catalogs' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'product_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'showcase' => array('varchar', 255),
        'size' => array('int', 11, 'null' => 0),
        'original_filename' => array('varchar', 255, 'null' => 0),
        'ext' => array('varchar', 32, 'null' => 0),
        'hash' => array('varchar', 255, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'shop_pdd_certificates' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'product_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'text' => array('varchar', 255),
        'size' => array('int', 11, 'null' => 0),
        'original_filename' => array('varchar', 255, 'null' => 0),
        'ext' => array('varchar', 32, 'null' => 0),
        'hash' => array('varchar', 255, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'shop_pdd_prices' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'product_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'showcase' => array('varchar', 255),
        'size' => array('int', 11, 'null' => 0),
        'original_filename' => array('varchar', 255, 'null' => 0),
        'ext' => array('varchar', 32, 'null' => 0),
        'hash' => array('varchar', 255, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
