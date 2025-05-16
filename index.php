<?php
session_start();
include "includes/config.php"; 

$user_id = $_SESSION['user_id'] ?? null;

// Fetch package data
$sql = "SELECT package_name, description, duration, price, package_id, font_style, font_size FROM packages";
$result = $conn->query($sql);

// --- STEP 1: Collect Google Font links ---
$fontLinks = [];
if ($result && $result->num_rows > 0) {
    mysqli_data_seek($result, 0); // Reset pointer
    while ($rowFont = $result->fetch_assoc()) {
        if (!empty($rowFont['font_style'])) {
            preg_match("/'?(.*?)'?,/", $rowFont['font_style'], $matches);
            if (isset($matches[1])) {
                $fontName = str_replace(' ', '+', $matches[1]);
                $fontLinks[$fontName] = "https://fonts.googleapis.com/css2?family={$fontName}&display=swap";
            }
        }
    }
    mysqli_data_seek($result, 0); // Reset again for main loop
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="css/style.css">

    <?php foreach ($fontLinks as $link): ?>
        <link href="<?= $link ?>" rel="stylesheet">
    <?php endforeach; ?>

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
<header>
    <a href="#home" class="logo">Powerhouse<span>Fitness</span></a>
    <div class='bx bx-menu' id="menu-icon"></div>
    <ul class="navbar">
        <li><a href="#home">Home</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#plans">Pricing</a></li>
        <li><a href="#about">About Us</a></li>
        <?php if ($user_id): ?>
            <li><a href="userhome.php">User Home</a></li>
            <li><a class="nav-link btn-custom" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="admin/login.php">Admin</a></li>
        <?php endif; ?>
    </ul>
    <div class="top-btn">
        <a href="login.php" class="nav-btn">Join Us</a>
    </div>
</header>

<section class="home" id="home">
    <div class="home-content" data-aos="zoom-in">
        <h3>Build Your</h3>
        <h1>Dream Physique</h1>
        <h3><span class="multiple-text">Bodybuilding</span></h3>
        <p>ITS NOW OR NEVER</p>
        <a href="login.php" class="btn">Join Us</a>
    </div>
</section>

<section class="services" id="services">
    <h2 class="heading" data-aos="zoom-in-down">Our <span> Services </span></h2>
    <div class="services-content" data-aos="zoom-in-up">
        <div class="row"><img src="img/image1.jpg"><h4>Powerhouse Fitness</h4></div>
        <div class="row"><img src="img/image2.jpg"><h4>Weight Gain</h4></div>
        <div class="row"><img src="img/image3.jpg"><h4>Strength Training</h4></div>
        <div class="row"><img src="img/image4.jpg"><h4>Fat Lose</h4></div>
        <div class="row"><img src="img/image5.jpg"><h4>Weight Lifting</h4></div>
        <div class="row"><img src="img/about.jpg"><h4>Running</h4></div>
    </div>
</section>

<section class="plans" id="plans">
    <h2 class="heading" data-aos="zoom-in-down">Our <span> Packages</span></h2>
    <div class="container" data-aos="zoom-in-up">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="package"
                 style="font-family: <?= htmlspecialchars($row['font_style'] ?? "'Poppins', sans-serif"); ?>;
                        font-size: <?= htmlspecialchars($row['font_size'] ?? '16px'); ?>;">
                <h2><?= htmlspecialchars($row['package_name']); ?></h2>
                <p><?= nl2br(htmlspecialchars($row['description'])); ?></p>
                <p><?= htmlspecialchars($row['duration']); ?></p>
                <p><strong>Price:</strong> $<?= number_format($row['price'], 2); ?></p>
                <?php if ($user_id): ?>
                    <form action="select-package.php" method="POST" onsubmit="return confirmSelection()">
                        <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                        <input type="hidden" name="package_id" value="<?= $row['package_id']; ?>">
                        <button type="submit" class="btn btn-success">Select Package</button>
                    </form>
                    <script>
                        function confirmSelection() {
                            return confirm("Are you sure you want to book this package?");
                        }
                    </script>
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

<section class="about" id="about">
    <div class="about-img" data-aos="zoom-in-down"></div>
    <img src="img/r1.jpg">
    <div class="about-content" data-aos="zoom-in-up">
        <h2 class="heading">Why Choose Us?</h2>
        <p>WE PROVIDE DIVERSE MEMBERSHIP BASE CREATES A FRIENDLY AND SUPPORTIVE ATMOSPHERE...</p>
        <p>CONTACT US:</p>
        <p>Phone no.: 988256677</p>
        <p>Address: Sagaon, Dombivali (East)</p>
    </div>
</section>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init({ offset: 300, duration: 1400 });</script>
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
<script src="script.js"></script>
</body>
</html>

<?php $conn->close(); ?>
