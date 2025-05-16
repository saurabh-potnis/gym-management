<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $package_name = trim($_POST['package_name']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);
    $price = trim($_POST['price']);

    // Validation
    if (empty($package_name) || empty($description) ||empty($duration) || empty($price)) {
        $errorMessage = "All fields are required!";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errorMessage = "Price must be a positive number!";
    } else {
        // Insert into database
        $sql = "INSERT INTO packages (package_name, description,duration, price) VALUES (?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssd", $package_name, $description,$duration, $price);
        
        if ($stmt->execute()) {
            echo "<script>alert('✅package added successfully!'); window.location='add-package.php';</script>";
        } else {
            $errorMessage = "Error adding package: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Package</title>
    <link rel="stylesheet" href="css/styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
       
        body{
            align-items:center;
            justify-content: center;
            background-color:rgb(231, 222, 248);
        }
   /* Container Styling */
.container {
    max-width: 800px;
    margin: 70px auto;
    padding: 30px;
    background: linear-gradient(135deg, #161855, #1a9ea4); /* Gym-style gradient */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    color: white;
}

/* Heading Style */
.heading h1 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
    color: white;
    margin-left: 20px;
       
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

/* Label Styles */
label {
    font-size: 18px;
    margin-bottom: 5px;
    font-weight: bold;
}

/* Input Fields */
input[type="text"], input[type="number"], textarea {
    width: 95%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
}

/* Placeholder Text Color */
input::placeholder, textarea::placeholder {
    color: #ddd;
}

/* Textarea Styling */
textarea {
    resize: none;
    height: 100px;
}

/* Button Styling */
button {
    background:rgb(233, 68, 3);
    color: white;
    padding: 12px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    border: none;
    border-radius: 5px;
    transition: 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}

/* Button Hover Effect */
button:hover {
    background:rgb(247, 9, 40);
    box-shadow: 0 0 15px rgba(47, 224, 248, 0.8);
    transform: scale(1.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
}

    </style>
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="main-content">
   
    <div class="container">
    <div class="heading">    <h1>Add New Package</h1>
    </div>


        <form method="post">
            <label for="package_name">Package Name:</label>
            <input type="text" id="package_name" name="package_name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" required>
            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" step="0.01" required>
           
                
            <button type="submit">Add Package</button>
        </form>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>
