<?php
session_start();
include "includes/config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST["user_id"];

    // Delete existing package for the user
    $deleteSql = "DELETE FROM user_packages WHERE user_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect user to index.php to select a new package
    header("Location: index.php#plans ?change_package=1");
    exit();
}
?>
