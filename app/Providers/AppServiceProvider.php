<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Database\Connection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::extend('firebird', function ($config, $name) {
            // Build DSN: host/port:path (Windows path needs escaped backslashes)
            $host = $config['host'] ?? '127.0.0.1';
            $port = $config['port'] ?? 3050;
            $dbPath = $config['database'];

            // If the path contains backslashes (Windows), ensure they are doubled in PHP string
            // Charset parameter appended
            $charset = $config['charset'] ?? 'UTF8';

            $dsn = "firebird:dbname={$host}/{$port}:{$dbPath};charset={$charset}";

            // Create PDO
            $user = $config['username'] ?? null;
            $pass = $config['password'] ?? null;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            $pdo = new PDO($dsn, $user, $pass, $options);

            // Return a generic Illuminate Database Connection
            return new Connection($pdo, $config['database'], $config['prefix'] ?? '', $config);
        });
    }
}
