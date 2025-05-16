<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch bookings from database with search and filter functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// SQL Query with filtering
$sql = "SELECT b.id, u.username, u.email, u.mobile, p.package_name, p.duration, p.price, b.booking_date, b.status 
        FROM user_packages b 
        JOIN userinfo u ON b.user_id = u.id 
        JOIN packages p ON b.package_id = p.package_id 
        WHERE (u.username LIKE ? OR u.email LIKE ? OR p.package_name LIKE ?)";

// Add status filter if selected
if (!empty($statusFilter)) {
    $sql .= " AND b.status = ?";
}

$sql .= " ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
if (!empty($statusFilter)) {
    $searchParam = "%$search%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $statusFilter);
} else {
    $searchParam = "%$search%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Bookings</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .main-content {
            padding: 20px;
            margin-left: 250px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Search & Filter Section */
        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .search-container input, .search-container select {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-container button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        .search-container button:hover {
            background: #0056b3;
        }

        /* Table Styling */
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }

        /* Buttons */
        .btn {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-update {
            background: #28a745;
            color: white;
        }
        .btn-update:hover {
            background: #218838;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <h2>Manage Bookings</h2>

    <!-- Search and Filter -->
    <form method="GET" class="search-container">
        <input type="text" name="search" placeholder="Search by Username, Email, Package..." value="<?= htmlspecialchars($search) ?>">
        <select name="status">
            <option value="">All Status</option>
            <option value="Pending" <?= ($statusFilter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Active" <?= ($statusFilter == 'Active') ? 'selected' : ''; ?>>Active</option>
            <option value="Completed" <?= ($statusFilter == 'Completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?= ($statusFilter == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <!-- Bookings Table -->
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Package</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['mobile']); ?></td>
                    <td><?= htmlspecialchars($row['package_name']); ?></td>
                    <td><?= htmlspecialchars($row['duration']); ?></td>
                    <td>$<?= number_format($row['price'], 2); ?></td>
                    <td><?= htmlspecialchars($row['booking_date']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>
                        <!-- Update Status -->
                        <form action="update-booking.php" method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Active" <?= ($row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?= ($row['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-update">Update</button>
                        </form>
                        <!-- Delete Booking -->
                        <form action="delete-booking.php" method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
