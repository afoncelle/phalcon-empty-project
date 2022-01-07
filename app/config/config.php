<?php

use Phalcon\Config;

date_default_timezone_set('Europe/Paris');

$config = [
    'dev' => [
        'analyticsId' => 'G-'
    ],
    'prod' => [
        'analyticsId' => 'G-'
    ]
];

return new Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'cryptokart-mysql',
        'username'    => 'web',
        'password'    => 'xE8$gu==>qFtkzHP',
        'dbname'      => 'cryptokart',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'modelsDir'      => APP_PATH . '/modules/base/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/',
    ],
    'envconf' => $config[ENV]

]);
