<?php

namespace App\Models\Firebird;

use PDO;

abstract class FirebirdModel
{
    protected static function getConnection()
    {
        static $pdo = null;

        if (!$pdo) {
            $pdo = new PDO(
                sprintf(
                    'firebird:dbname=%s/%s:%s;charset=%s',
                    env('FIREBIRD_HOST'),
                    env('FIREBIRD_PORT'),
                    env('FIREBIRD_DB_PATH'),
                    env('FIREBIRD_CHARSET', 'UTF8')
                ),
                env('FIREBIRD_USERNAME'),
                env('FIREBIRD_PASSWORD'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                ]
            );
        }

        return $pdo;
    }
}
