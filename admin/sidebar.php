<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin Dashboard</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
      
/* Hide submenu by default */
.submenu {
    display: none;
    list-style: none;
    padding-left: 20px;
}
.submenu li {
    margin-top: 5px;
}
.sidebar ul li a i {
            width: 25px; /* Fixed width for icons */
            text-align: center;
            margin-right: 12px;
        }
       
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h2><i class="fas fa-dumbbell"></i>Admin Dashboard</h2>
        <ul>
            <li><a href="admin.php"><i class="fas fa-home"></i>  <span> Home</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Manage Users</span></a></li>
            <li>
    <a href="#" onclick="togglePackages()">
        <i class="fas fa-box"></i> <span>Manages Packages</span> 
        <i id="packageArrow" class="fas fa-chevron-down" style="float: right;"></i>
    </a>
    <ul id="packageSubMenu" class="submenu">
        <li><a href="add-package.php">&#9711; Add Packages</a></li>
        <li><a href="package.php"> &#9711; Update Packages</a></li>
    </ul>
     </li>
            <li><a href="booking-history.php"><i class="fas fa-calendar-check"></i> <span>Booking History</span></a></li>

            <li><a href="announcement.php"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="update-profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
        </ul>
    </div>
 
    <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>

    

 <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("main-content");
            sidebar.classList.toggle("closed");
            mainContent.classList.toggle("shifted");
        }
        function togglePackages() {
    var submenu = document.getElementById("packageSubMenu");
    var arrowIcon = document.getElementById("packageArrow");
    
    if (submenu.style.display === "none" || submenu.style.display === "") {
        submenu.style.display = "block";
        arrowIcon.classList.remove("fa-chevron-down");
        arrowIcon.classList.add("fa-chevron-up");
    } else {
        submenu.style.display = "none";
        arrowIcon.classList.remove("fa-chevron-up");
        arrowIcon.classList.add("fa-chevron-down");
    }
}
    </script>
</body>
</html>
