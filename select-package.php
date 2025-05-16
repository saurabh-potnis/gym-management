<?php
session_start();
include "includes/config.php"; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $package_id = $_POST['package_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Debug: Check if the previous package exists before deleting
        $check_query = "SELECT * FROM user_packages WHERE user_id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $existing_package = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($existing_package) {
            echo "<script>
                if (confirm('You already have a package booked. Do you want to change it?')) {
                    window.location.href='change-package.php?package_id=$package_id';
                } else {
                    window.location.href='userhome.php';
                }
            </script>";
            exit();
        }

        if ($existing_package) {
            // Remove old package
            $delete_query = "DELETE FROM user_packages WHERE user_id = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }


        // Insert new package selection
        $insert_query = "INSERT INTO user_packages (user_id, package_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $package_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Refresh session package selection
        $_SESSION['selected_package'] = $package_id;

        echo "<script>alert('Package updated successfully!'); window.location='userhome.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        $conn->rollback();
        echo "<script>alert('Error selecting package. Please try again.'); window.location='index.php';</script>";
    }

    $conn->close();
}
?>
