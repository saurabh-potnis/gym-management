<?php
ob_start();

// DB Connection
define('DB_HOST','db');       // Docker service name for MySQL
define('DB_USER','gymuser');  // From docker-compose.yml
define('DB_PASS','gympass');  // From docker-compose.yml
define('DB_NAME','gymdb');    // From docker-compose.yml

// Establish PDO database connection
try {
    $dbh = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
    );
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

// Optional: establish mysqli connection if some files use it
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("MySQLi Connection failed: " . $mysqli->connect_error);
}
