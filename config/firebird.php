<?php

return [
    'host' => env('FIREBIRD_HOST', 'localhost'),
    'port' => env('FIREBIRD_PORT', 3050),
    'database' => env('FIREBIRD_DB_PATH', ''),
    'username' => env('FIREBIRD_USERNAME', 'SYSDBA'),
    'password' => env('FIREBIRD_PASSWORD', ''),
    'charset' => env('FIREBIRD_CHARSET', 'UTF8'),
];
