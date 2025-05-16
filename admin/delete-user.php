<?php
session_start();
include "config.php"; // Database connection

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']); // Convert to integer to prevent SQL injection

        $sql = "DELETE FROM userinfo WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('✅ User deleted successfully!'); window.location='members.php';</script>";
        } else {
            echo "<script>alert('❌ Error deleting user. Please try again.'); window.location='members.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('❌ No user ID provided.'); window.location='members.php';</script>";
    }


    $stmt->close();
    $conn->close();
}
?>
