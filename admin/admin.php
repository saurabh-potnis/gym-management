<?php
session_start();
include "config.php"; 
if (!isset($_SESSION["admin"])) {
     header("Location: login.php"); // Redirect to login page
     exit();
}


// Fetch total users
$sql_users = "SELECT COUNT(*) AS total_users FROM userinfo";
$result_users = $conn->query($sql_users);
$total_users = ($result_users->num_rows > 0) ? $result_users->fetch_assoc()['total_users'] : 0;

// Fetch total packages
$sql_packages = "SELECT COUNT(*) AS total_packages FROM packages";
$result_packages = $conn->query($sql_packages);
$total_packages = ($result_packages->num_rows > 0) ? $result_packages->fetch_assoc()['total_packages'] : 0;

// Fetch total revenue
$sql_revenue = "SELECT COALESCE(SUM(p.price), 0) AS total_revenue FROM user_packages up 
                JOIN packages p ON up.package_id = p.package_id";
$result_revenue = $conn->query($sql_revenue);
$total_revenue = ($result_revenue->num_rows > 0) ? $result_revenue->fetch_assoc()['total_revenue'] : 0;


// Format revenue properly
$formatted_revenue = number_format($total_revenue, 2);
// Fetch recent user activities (Profile Updates & Package Selections)
$sql_user_activities = "
    SELECT 'User ' AS prefix, u.username, ' updated profile' AS activity, u.updated_at AS activity_time 
    FROM userinfo u 
    WHERE u.updated_at IS NOT NULL 

    UNION 

    SELECT 'User ', u.username, ' selected a new package', up.updated_at 
    FROM user_packages up 
    JOIN userinfo u ON up.user_id = u.id 
    WHERE up.updated_at IS NOT NULL 

    ORDER BY activity_time DESC 
    LIMIT 5";  // Fetch only recent 5 activities

$result_activities = $conn->query($sql_user_activities);
$activities = ($result_activities->num_rows > 0) ? $result_activities->fetch_all(MYSQLI_ASSOC) : [];

// Fetch recent package additions
$sql_new_packages = "SELECT package_name, created_at FROM packages ORDER BY created_at DESC LIMIT 5";
$result_packages = $conn->query($sql_new_packages);
$new_packages = ($result_packages->num_rows > 0) ? $result_packages->fetch_all(MYSQLI_ASSOC) : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styless.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
<?php include "sidebar.php"; ?>
    <div class="main-content">
        <header>
            <h1>Welcome to the Admin Dashboard</h1>
        </header>
        <section class="stats">
            <div class="card">
            <i class="fas fa-users fa-3x"></i> <!-- User Icon -->

                <h3>Total Users</h3>
                <p><?= $total_users; ?></p>
            </div>
            <div class="card">
            <i class="fas fa-dumbbell fa-3x"></i> <!-- Gym Dumbbell Icon -->

                <h3>Packages</h3>
                    
                <p><?= $total_packages; ?></p>
            </div>
            <div class="card">
            <i class="fas fa-dollar-sign fa-3x"></i> <!-- Revenue Icon -->

                <h3>Revenue</h3>

                <p>$<?= $formatted_revenue; ?></p>
            </div>
        </section>
        <section class="recent-activities">
    <h2>Recent Activities</h2>
    <ul>
        <?php if (!empty($activities)): ?>
            <?php foreach ($activities as $activity): ?>
                <li><?= htmlspecialchars($activity['prefix'] . $activity['username'] . $activity['activity']); ?> (<?= date('d M Y, h:i A', strtotime($activity['activity_time'])); ?>)</li>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($new_packages)): ?>
            <?php foreach ($new_packages as $package): ?>
                <li>New Package <strong><?= htmlspecialchars($package['package_name']); ?></strong> was added (<?= date('d M Y, h:i A', strtotime($package['created_at'])); ?>)</li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No recent activities.</li>
        <?php endif; ?>
    </ul>
</section>
    </div>
    <script src="script.js"></script>
</body>
</html>
