<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => 'example',
            'port' => '3307',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => getenv("DB_ADAPTER") ?? 'mysql',
            'host' => getenv("DB_HOSTNAME") ?? 'localhost',
            'name' => getenv("DB_DBNAME") ?? 'tents',
            'user' => getenv("DB_USERNAME") ?? 'root',
            'pass' => getenv("DB_PASSWORD") ?? 'example',
            'port' => getenv("DB_PORT") ?? '3307',
            'charset' => getenv("DB_CHARSET") ?? 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => 'example',
            'port' => '3307',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];