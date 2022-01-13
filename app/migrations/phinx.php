<?php

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/scripts',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_database' => 'wedding',

            'prod' => [
                'adapter' => 'mysql',
                'host' => '@todo-projectname-mysql',
                'name' => "@todo-projectname",
                'user' => 'web',
                'pass' => 'xE8$gu==>qFtkzHP',
                'port' => '3306',
                'charset' => 'utf8'],

            'dev' => [
                'adapter' => 'mysql',
                'host' => '@todo-projectname-mysql',
                'name' => "@todo-projectname",
                'user' => 'web',
                'pass' => 'xE8$gu==>qFtkzHP',
                'port' => '3306',
                'charset' => 'utf8'],
],
'version_order' => 'creation'];