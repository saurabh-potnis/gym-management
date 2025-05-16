<?php
session_start();
include "config.php"; // Database connection

// Ensure admin is logged in
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

// Fetch admin details
$admin_username = $_SESSION["admin"];
$sql = "SELECT id, username, password FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["username"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate current password
    if (!password_verify($current_password, $admin["password"])) {
        $error_message = "❌ Incorrect current password!";
    } else {
        // Update username & password
        $update_fields = [];
        $params = [];
        $param_types = "";

        if (!empty($new_username) && $new_username !== $admin["username"]) {
            $update_fields[] = "username = ?";
            $params[] = $new_username;
            $param_types .= "s";
        }

        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $error_message = "❌ Password must be at least 6 characters long.";
            } elseif ($new_password !== $confirm_password) {
                $error_message = "❌ Passwords do not match!";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_fields[] = "password = ?";
                $params[] = $hashed_password;
                $param_types .= "s";
            }
        }

        if (empty($error_message) && !empty($update_fields)) {
            $sql = "UPDATE admins SET " . implode(", ", $update_fields) . " WHERE id = ?";
            $params[] = $admin["id"];
            $param_types .= "i";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($param_types, ...$params);
            if ($stmt->execute()) {
                $success_message = "✅ Profile updated successfully!";
                $_SESSION["admin"] = $new_username; // Update session with new username
            } else {
                $error_message = "❌ Failed to update profile. Please try again.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile Update</title>
    
    <link rel="stylesheet" href="css/styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <style>
     body{
        background:rgb(216, 217, 240);
       }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #1c1c1c;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }
        h2 {
            text-align: center;
        }
        label {
    font-size: 16px;
    font-weight: bold;
    text-align: left;
    margin-bottom: 5px;
    display: block;
    width: 80%;
}


.form-control {
    width: 96%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #555;
    border-radius: 5px;
    font-size: 14px;
    background-color: #333;
    color: white;
    transition: border 0.3s ease-in-out, background 0.3s ease-in-out;
}

.form-control:focus {
    background-color: #222;
    border-color: #ff5733;
    outline: none;
}

        .btn-primary {
            background: linear-gradient(135deg, #ff5733, #c70039);
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #c70039, #900c3f);
        }
        .form-control {
            background-color: #333;
            color: white;
            border: 1px solid #555;
        }
        .form-control:focus {
            background-color: #222;
            color: white;
            border: 1px solid #ff5733;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #28a745;
            color: white;
        }
        .error {
            background-color: #dc3545;
            color: white;
        }
 </style>
</head>
<body>
<?php include "sidebar.php"; ?>
<!-- ===== Heading Section ===== -->
<div class="main-content">
<div class="container">
    <h2>Update Profile</h2>

    <?php if ($success_message): ?>
        <div class="message success"><?= $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="message error"><?= $error_message; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">New Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">New Password (optional)</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>

</body>
</html>
