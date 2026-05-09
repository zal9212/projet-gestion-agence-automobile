<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'car_rental_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    return $pdo;
}