<?php
if (isset($_SESSION['selected_package'])) {
    unset($_SESSION['selected_package']); // Clear old selection
}

session_start();
include "includes/config.php"; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Debugging: Check what package ID is being retrieved
if (isset($_SESSION['selected_package'])) {
    unset($_SESSION['selected_package']); // Clear session cache
}
// Fetch user's selected package
$sql = "SELECT p.package_name, p.description, p.price 
        FROM user_packages up 
        JOIN packages p ON up.package_id= p.package_id
        WHERE up.user_id = ?
        ORDER BY up.id DESC LIMIT 1"; // Get the latest package

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

// Debugging: Check what package ID is being retrieved
error_log("Fetched package ID: " . ($package ? $package['package_name'] : "None"));
// Fetch the latest announcement
$sql_latest_announcement = "SELECT id, title, message, created_at FROM announcements ORDER BY created_at DESC LIMIT 1";
$result_latest_announcement = $conn->query($sql_latest_announcement);

$latest_announcement = null;
if ($result_latest_announcement->num_rows > 0) {
    $latest_announcement = $result_latest_announcement->fetch_assoc();
}

// Delete older announcements (keep only the latest one)
if ($latest_announcement) {
    $latest_id = $latest_announcement['id'];
    $sql_delete_old = "DELETE FROM announcements WHERE id != ?";
    $stmt = $conn->prepare($sql_delete_old);
    $stmt->bind_param("i", $latest_id);
    $stmt->execute();
    $stmt->close();
}


$userQuery = "SELECT username, dob, mobile, email FROM userinfo WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home | Gym Management</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="css/userhome.css">

   
</head>
<body>

<!-- Navbar -->
<header>
        <a href="#home" class="logo">Powerhouse<span>Fitness</span><i class="fa-solid fa-dumbbell" i></i> <!-- Alternative gym icon -->

        <!-- Strength Icon -->

        <!-- Fighting/Muscle Icon -->
        </a>

        <div class='bx bx-menu' id="menu-icon"></div>

        <ul class="navbar">
            <li> <a href="index.php#home">Home</a></li>
            
            <li> <a href="index.php#plans">Pricing</a></li>
            <li><a href="logout.php">Logout <i class="fas fa-sign-out-alt"></i> </a></li>
           
            </ul>
            <div class="top-btn">
            <a href="#" class="nav-btn "  data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a>
        </div>
        
        


        
</header>


       

<section class="content">
<!-- Main Content -->
<div class="containers">
    <h2 class="text-center">Welcome to Your Dashboard !!</h2>

   <!-- Announcements Section -->
<div class="lable">
    <h3>📢 Latest Announcement</h3>
    <?php if ($latest_announcement): ?>
        <div class="announcement-card">
            <h5><?= htmlspecialchars($latest_announcement['title']); ?></h5>
            <p><?= htmlspecialchars($latest_announcement['message']); ?></p>
            <small class="text-muted">Posted on: <?= $latest_announcement['created_at']; ?></small>
        </div>
    <?php else: ?>
        <p>No announcements at the moment.</p>
    <?php endif; ?>
</div>


    <!-- User Package Section -->
    <div class="block">
        <h3><a>🎟 </a>Your Selected Package</h3>
        <?php if ($package): ?>
            <div class="package-card">
                <h5><?= htmlspecialchars($package['package_name']); ?></h5>
                <p><?= htmlspecialchars($package['description']); ?></p>
                <p><strong>Price:</strong> ₹<?= number_format($package['price'], 2); ?></p>
                <form action="change-package.php" method="POST" onsubmit="return confirmSelection()">
                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                <button type="submit" class="btn-primary">Change Package</button>
            </form>   
            <script>
                function confirmSelection() {
                     return confirm("Are you sure you want to change this package?");
                }
                </script>
            
        </div>
        <?php else: ?>
            <p>You have not selected a package yet.</p>
        <?php endif; ?>
    </div>
</div>
    </section>
<!-- Profile Update Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Update Profile</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body">
                <form id="updateProfileForm">
                    <input type="hidden" name="id" value="<?= $user_id ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (optional)</label>
                         <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                     </div>

                    <div class="mb-3">
                       <label for="confirm_password" class="form-label">Confirm Password</label>
                          <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                 </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("updateProfileForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let username = document.getElementById("username").value.trim();
    let dob = document.getElementById("dob").value;
    let mobile = document.getElementById("mobile").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let today = new Date();
    let birthDate = new Date(dob);
    let age = today.getFullYear() - birthDate.getFullYear();
    let errorMessage = "";

    // Validate Username
    if (username.length < 3) {
        errorMessage += "❌ Username must be at least 3 characters long.\n";
    }

    // Validate Date of Birth (User must be 18+)
    if (age < 18) {
        errorMessage += "❌ You must be at least 18 years old.\n";
    }

    // Validate Mobile Number (10 digits)
    if (!/^\d{10}$/.test(mobile)) {
        errorMessage += "❌ Mobile number must be exactly 10 digits.\n";
    }

    // Validate Email Format
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
        errorMessage += "❌ Please enter a valid email address.\n";
    }
    // Password validation
    if (password.length > 0 && password.length < 6) {
        errorMessage += "❌ Password must be at least 6 characters long.\n";
    }

    // Confirm password match
    if (password && password !== confirmPassword) {
        errorMessage += "❌ Passwords do not match.\n";
    }

    // Display error message if any
    if (errorMessage) {
        alert(errorMessage);
        return;
    }

    // If all validations pass, submit the form via AJAX
    let formData = new FormData(this);
    
    fetch("update-profile.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        window.location.reload();
    })
    .catch(error => console.error("Error:", error));
});
</script>



</body>
</html>
