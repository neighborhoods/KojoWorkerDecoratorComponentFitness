<?php

function assertSet($value) {
    if (!isset($value)) {
        throw new RuntimeException('Assertion failed: Value not set.');
    }
    return $value;
}

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_database' => 'production',
            'production' => [
                'adapter' => assertSet(env('DATABASE_ADAPTER')) ,
                'host' => assertSet(env('DATABASE_HOST')),
                'name' => assertSet(env('DATABASE_NAME')),
                'user' => assertSet(env('DATABASE_USERNAME')),
                'pass' => assertSet(env('DATABASE_PASSWORD')),
                'port' => assertSet(env('DATABASE_PORT')),
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];