<?php
$host = "localhost";
$user = "root"; // Default XAMPP user
$pass = "saurabh"; // Leave empty for XAMPP
$db = "gym";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
