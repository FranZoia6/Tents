<?php

namespace Tents\Core;

class Config {

    private array $configs;

    public function __construct() {
        $this -> configs["LOG_LEVEL"] = getenv("LOG_LEVEL", "INFO");
        $path = getenv("LOG_PATH", "/logs/app.log");
        $this -> configs["LOG_PATH"] = $this -> joinPaths('..', $path);

        $this -> configs['DB_ADAPTER'] = getenv("DB_ADAPTER") ?? 'mysql';
        $this -> configs['DB_HOSTNAME'] = getenv("DB_HOSTNAME") ?? 'db';
        $this -> configs['DB_DBNAME'] = getenv("DB_DBNAME") ?? 'tents';
        $this -> configs['DB_USERNAME'] = getenv("DB_USERNAME") ?? 'root';
        $this -> configs['DB_PASSWORD'] = getenv("DB_PASSWORD") ?? 'example';
        $this -> configs['DB_PORT'] = getenv("DB_PORT") ?? '3306';
        $this -> configs['DB_CHARSET'] = getenv("DB_CHARSET") ?? 'utf8mb4';

    }

    public function joinPaths() {
        $paths = array();
        foreach (func_get_args() as $args) {
            if ($args <> '') {
                $paths[] = $args;
            }
        }
        return preg_replace('#\+#', '/', join('/', $paths));
    }

    public function get($name) {
        return $this -> configs[$name] ?? null;
    }
}