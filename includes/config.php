<?php
// index.php - Display package details on the index page

// Database connection
$servername = "localhost";
$username = "root";
$password = "saurabh";
$dbname = "gym";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

