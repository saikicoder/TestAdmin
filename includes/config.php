<?php
$host = 'localhost';
$dbname = 'admin_db';
$username = 'root';
$password = '';
$port = '4306'; // Replace with your MySQL port number if different

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for error handling
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays by default
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4" // Optional: Set the character set
    ]);

    //echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
