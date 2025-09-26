<?php
session_start();

// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once 'include/config.php'; // $mysqli is defined here

$user_id = $_SESSION['user_id'] ?? null;

// Fetch packages from database
$sql = "SELECT package_name, description, price, package_id FROM packages";
$result = $mysqli->query($sql);
if (!$result) {
    die("Database query failed: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style.css">
    <title>Powerhouse Fitness</title>
    <style>
        body {
            background-image: url(img/1.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <a href="#home" class="logo">Powerhouse<span>Fitness</span></a>
        <div class='bx bx-menu' id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="#home">Home</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#plans">Pricing</a></li>
            <?php if ($user_id): ?>
                <li><a href="userhome.php">User Home</a></li>
                <li><a class="nav-link btn-custom" href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="admin/login.php">Admin</a></li>
            <?php endif; ?>
        </ul>
        <div class="top-btn">
            <a href="#" class="nav-btn">Join Us</a>
        </div>
    </header>

    <!-- Home Section -->
    <section class="home" id="home">
        <div class="home-content">
            <h3>Build Your</h3>
            <h1>Dream Physique</h1>
            <h3><span class="multiple-text">Bodybuilding</span></h3>
            <p>ITS NOW OR NEVER</p>
            <a href="#" class="btn">Join Us</a>  
            <div class="home-img"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <h2 class="heading">Our <span>Services</span></h2>
        <div class="services-content">
            <?php 
            $service_images = ['image1.jpg','image2.jpg','image3.jpg','image4.jpg','image5.jpg','about.jpg'];
            $service_titles = ['Powerhouse Fitness','Weight Gain','Strength Training','Fat Lose','Weight Lifting','Running'];
            foreach($service_images as $i => $img): ?>
                <div class="row">
                    <img src="img/<?= $img ?>" alt="">
                    <h4><?= $service_titles[$i] ?></h4>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <img src="img/r1.jpg" alt="">
        <div class="about-content">
            <h2 class="heading">Why Choose Us?</h2>
            <p>WE PROVIDE DIVERSE MEMBERSHIP BASE CREATES A FRIENDLY AND SUPPORTIVE ATMOSPHERE, WHERE YOU CAN MAKE FRIENDS AND STAY MOTIVATED AND YOU SHOULD MOTIVATE ALL THE MEMBERS IN THIS JOURNEY</p>
            <p>CONTACT US:</p>
            <a href="#" class="btn">Book A Class</a>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="plans" id="plans">
        <h2 class="heading">Our <span>Packages</span></h2>
        <div class="container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="package">
                        <h2><?= htmlspecialchars($row['package_name']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($row['price'], 2) ?></p>
                        <?php if ($user_id): ?>
                            <form action="select-package.php" method="POST">
                                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                <input type="hidden" name="package_id" value="<?= $row['package_id']; ?>">
                                <button type="submit" class="btn btn-success">Select Package</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="select-btn">Login to Select</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No packages available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
