<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Define roots
$this_file = str_replace('\\', '/', __FILE__);
$doc_root = $_SERVER['DOCUMENT_ROOT'];

$web_root = str_replace([$doc_root, "include/config.php"], '', $this_file);
$server_root = str_replace('config/config.php', '', $this_file);

define('web_root', $web_root);
define('server_root', $server_root);

// Set timezone from .env
date_default_timezone_set($_ENV['TIMEZONE']);

// PDO Connection
try {
    $db = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $db->query('SET SESSION SQL_BIG_SELECTS=1');

    // Optional second connection (if really needed)
    $conn = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

ob_end_flush();
