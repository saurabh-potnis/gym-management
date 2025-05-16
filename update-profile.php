<?php
session_start();
include "includes/config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $dob = $_POST['dob'];
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    $errors = [];

    // Validate Username
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    // Validate Date of Birth (User must be at least 18 years old)
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    if ($age < 18) {
        $errors[] = "You must be at least 18 years old.";
    }

    // Validate Mobile Number (10 digits)
    if (!preg_match("/^\d{10}$/", $mobile)) {
        $errors[] = "Mobile number must be exactly 10 digits.";
    }

    // Validate Email Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation (if provided)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    }

    // Check for errors
    if (!empty($errors)) {
        echo implode("\n", $errors);
        exit();
    }

    // Update user profile (if password provided, update it)
    if (!empty($password)) {
        $sql = "UPDATE userinfo SET username = ?, dob = ?, mobile = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $username, $dob, $mobile, $email, $hashed_password, $user_id);
    } else {
        $sql = "UPDATE userinfo SET username = ?, dob = ?, mobile = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $dob, $mobile, $email, $user_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile.";
    }

    $stmt->close();
    $conn->close();
}
?>
