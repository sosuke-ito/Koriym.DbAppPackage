<?php

use josegonzalez\Dotenv\Loader as Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv::load([
    'filepath' => dirname(__DIR__) . '/.env',
    'expect' => ['DB_DSN', 'DB_USER'],
    'toEnv' => true
]);

preg_match("/dbname=(\w+)/",  $_ENV['DB_DSN'], $parts);
$dbName = $parts[1];

try {
    $pdo = new \PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->exec("DROP DATABASE {$dbName}");
    $pdo->exec("DROP DATABASE {$dbName}_test");
    error_log("Database [{$dbName}] and [{$dbName}_test] are dropped.");
} catch (PDOException $e) {
    echo $e;
    echo "Database connection failed: user:{$_ENV['DB_USER']} passwd:{$_ENV['DB_PASS']}" . PHP_EOL;
    exit(1);
}
