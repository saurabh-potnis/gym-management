<?php
session_start();
include "includes/config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $package_id = $_POST["package_id"];

    // Insert new package for user
    $insertSql = "INSERT INTO user_packages (user_id, package_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("ii", $user_id, $package_id);

    if ($stmt->execute()) {
        echo "<script>alert('Package updated successfully!'); window.location.href='userhome.php';</script>";
    } else {
        echo "<script>alert('Error updating package.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
