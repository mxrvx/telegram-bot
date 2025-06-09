<?php

declare(strict_types=1);
$xpdo_meta_map['mxrvxTelegramBotUser'] =  [
    'package' => 'mxrvx-telegram-bot',
    'version' => '1.1',
    'table' => 'mxrvx_telegram_bot_users',
    'extends' => 'xPDOObject',
    'tableMeta' =>
     [
         'engine' => 'InnoDB',
     ],
    'fields' =>
     [
         'id' => null,
         'first_name' => '',
         'last_name' => '',
         'username' => '',
         'status' => '',
         'created_at' => 0,
         'updated_at' => 0,
     ],
    'fieldMeta' =>
     [
         'id' =>
          [
              'dbtype' => 'bigint',
              'precision' => '20',
              'phptype' => 'integer',
              'null' => false,
              'index' => 'pk',
              'attributes' => 'unsigned',
          ],
         'first_name' =>
          [
              'dbtype' => 'varchar',
              'precision' => '191',
              'phptype' => 'string',
              'null' => true,
              'default' => '',
          ],
         'last_name' =>
          [
              'dbtype' => 'varchar',
              'precision' => '191',
              'phptype' => 'string',
              'null' => true,
              'default' => '',
          ],
         'username' =>
          [
              'dbtype' => 'varchar',
              'precision' => '191',
              'phptype' => 'string',
              'null' => true,
              'default' => '',
          ],
         'status' =>
          [
              'dbtype' => 'varchar',
              'precision' => '100',
              'phptype' => 'string',
              'null' => true,
              'default' => '',
          ],
         'created_at' =>
          [
              'dbtype' => 'int',
              'precision' => '20',
              'phptype' => 'timestamp',
              'null' => true,
              'default' => 0,
          ],
         'updated_at' =>
          [
              'dbtype' => 'int',
              'precision' => '20',
              'phptype' => 'timestamp',
              'null' => true,
              'default' => 0,
          ],
     ],
    'indexes' =>
     [
         'PRIMARY' =>
          [
              'alias' => 'PRIMARY',
              'primary' => true,
              'unique' => true,
              'type' => 'BTREE',
              'columns' =>
               [
                   'id' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
         'first_name' =>
          [
              'alias' => 'first_name',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' =>
               [
                   'first_name' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
         'last_name' =>
          [
              'alias' => 'last_name',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' =>
               [
                   'last_name' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
         'username' =>
          [
              'alias' => 'username',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' =>
               [
                   'username' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
         'status' =>
          [
              'alias' => 'status',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' =>
               [
                   'status' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
         'created_at' =>
          [
              'alias' => 'created_at',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' =>
               [
                   'created_at' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
         'updated_at' =>
          [
              'alias' => 'updated_at',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' =>
               [
                   'updated_at' =>
                    [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
               ],
          ],
     ],
];
