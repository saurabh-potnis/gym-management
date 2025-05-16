<?php
include "config.php"; // Include the database connection

$username = "admin1";
$password = password_hash("admin123", PASSWORD_DEFAULT); // Hash password

$sql = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";

if (mysqli_query($conn, $sql)) {
    echo "Admin created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

