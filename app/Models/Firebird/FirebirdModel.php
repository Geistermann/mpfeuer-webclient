<?php

namespace App\Models\Firebird;

use PDO;

abstract class FirebirdModel
{
    protected static function getConnection()
    {                
        static $pdo = null;

        if (!$pdo) {
            $config = config('firebird');

            $dsn = sprintf(
                'firebird:dbname=%s/%s:%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ]);
        }

        return $pdo;
    }
}
