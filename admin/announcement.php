<?php
// admin.php - Admin interface for posting announcements
session_start();
include "config.php"; // Database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];

    $sql = "INSERT INTO announcements (title, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $message);

    if ($stmt->execute()) {
        echo "Announcement posted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Post Announcement</title>
    <link rel="stylesheet" href="css/announce.css">
    <link rel="stylesheet" href="css/styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body> 
<?php include "sidebar.php"; ?>
<div class="main-content">
<div class="main-content">

<div class="container">
    <h1>Post an Announcement</h1>
    <form method="post" action="">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="Post Announcement">
    </form>
    </div>
    </div>
    <script src="script.js"></script>
</body>
</html>