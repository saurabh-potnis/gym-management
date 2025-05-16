<?php
session_start();
include "config.php"; // Database connection

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit();
}

if (isset($_GET["package_id"])) {
    $package_id = $_GET["package_id"];

    // Check if the package exists
    $check_query = "SELECT * FROM packages WHERE package_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('❌ Package not found!'); window.location='package.php';</script>";
        exit();
    }

    // Delete the package safely
    $delete_query = "DELETE FROM packages WHERE package_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $package_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Package deleted successfully!'); window.location='package.php';</script>";
    } else {
        echo "<script>alert('❌ Error deleting package!'); window.location='package.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
