<?php
session_start();
include "config.php"; // Database connection

// Check if admin is logged in
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

// Handle search query
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
}

// Pagination settings
$limit = 10; // Entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch package booking history
$sql = "SELECT u.username, p.package_name,p.duration, p.price, up.updated_at 
        FROM user_packages up
        JOIN userinfo u ON up.user_id = u.id
        JOIN packages p ON up.package_id = p.package_id
        WHERE u.username LIKE ?
        ORDER BY up.updated_at DESC
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$searchTerm = "%$searchQuery%";
$stmt->bind_param("sii", $searchTerm, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Get total records for pagination
$sql_count = "SELECT COUNT(*) AS total FROM user_packages up 
              JOIN userinfo u ON up.user_id = u.id
              WHERE u.username LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("s", $searchTerm);
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

$stmt->close();
$stmt_count->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Booking History | Gym Management</title>
    <link rel="stylesheet" href="css/styless.css">
    <style>
        body {
            background-color:rgb(240, 219, 144);
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            text-align: center;
            background:rgb(13, 125, 237);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }
       
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            color: black;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #f39c12;
            color: white;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 10px;
            width: 250px;
            border-radius: 5px;
            border: none;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 12px;
            background: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }
        .pagination a:hover {
            background: #d35400;
        }
    </style>
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <div class="container">
        <h2>Package Booking History</h2>

        <!-- Search Bar -->
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by username..." value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Package Name</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["username"]); ?></td>
                            <td><?= htmlspecialchars($row["package_name"]); ?></td>
                            <td><?= htmlspecialchars($row["duration"]); ?></td>

                            <td>₹<?= number_format($row["price"], 2); ?></td>
                            <td><?= date("d M Y, h:i A", strtotime($row["updated_at"])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No booking history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($total_pages > 1): ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($searchQuery) ?>" 
                       <?= $i == $page ? 'style="background:#d35400;"' : '' ?>>
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
</body>
</html>
