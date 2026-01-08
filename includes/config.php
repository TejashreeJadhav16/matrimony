<?php
/* =====================================================
   DATABASE CONFIGURATION
===================================================== */

$host = "localhost";
$db_name = "matrimony";
$username = "root";
$password = "";

/* =====================================================
   CREATE DATABASE CONNECTION
===================================================== */

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/* =====================================================
   SITE SETTINGS
===================================================== */

define("SITE_NAME", "Karadi Samaaj Matrimony");
define("BASE_URL", "http://localhost/matrimony");

/* =====================================================
   TIMEZONE
===================================================== */

date_default_timezone_set("Asia/Kolkata");
