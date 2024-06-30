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
            'host' => 'db',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8mb4',
        ],
        'development' => [
            'adapter' => getenv("DB_ADAPTER") ?? 'mysql',
            'host' => getenv("DB_HOSTNAME") ?? 'db',
            'name' => getenv("DB_DBNAME") ?? 'development_db',
            'user' => getenv("DB_USERNAME") ?? 'root',
            'pass' => getenv("DB_PASSWORD") ?? '',
            'port' => getenv("DB_PORT") ?? '3306',
            'charset' => getenv("DB_CHARSET") ?? 'utf8mb4',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8mb4',
        ]
    ],
    'version_order' => 'creation'
];
