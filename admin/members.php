<?php
session_start();
include "config.php"; // Database connection

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit();
}

$sql = "SELECT id,username, dob, mobile, email,create_date FROM userinfo ORDER BY create_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users | Gym Management</title>
    <link rel="stylesheet" href="css/styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body{
            background-color:rgb(223, 204, 235) ;
        }
.container {
    background:transparent ;
    width: 850px;
    margin: 20px auto;
    text-align: center;
    border-radius: 10px;
    border-color: #000;
    height: 500px;

}
.container h2{
    color: black;
}
table {
    background-color: white;
    border: 2px solid #000; /* Border color for the table */
    height: 350px;

    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #000; /* Border color for table cells */
    color: #000;
    padding: 10px;
    text-align: center;
    height: 50px; /* Adjust as needed */
    vertical-align: middle;


}

th {
    background-color:rgb(147, 5, 235);
    color: white;
}
.delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .delete-btn:hover {
            background-color: darkred;
        }
.back-button {
    display: inline-block;
    padding: 10px;
    background: rgb(218, 98, 6);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    margin-top: 18px;
    margin-bottom: 10px;
    width: 100px;
}

.back-button:hover {
    background:rgb(233, 156, 55);
}
.search-bar {
    width: 50%;
    padding: 10px;
    margin-bottom: 15px;
    border: 2px solid #FF00FF;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
    outline: none;
}

.search-bar:focus {
    border-color: #D500F9;
    box-shadow: 0 0 8px rgba(213, 0, 249, 0.5);
}


    </style>
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="main-content">
    <div class="container">
        <h2>Registered Users</h2>

        <table id="usersTable">
        <thead>
                <tr>
                <input type="text" id="searchInput" class="search-bar" placeholder="Search by Username..." onkeyup="searchUsers()"></tr>
                <tr>
                    <th>Username</th>
                    <th>Date of Birth</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Registered At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    
                    <td><?= htmlspecialchars($row["username"]); ?></td>
                    <td><?= htmlspecialchars($row["dob"]); ?></td>
                    <td><?= htmlspecialchars($row["mobile"]); ?></td>
                    <td><?= htmlspecialchars($row["email"]); ?></td>
                    <td><?= htmlspecialchars($row["create_date"]); ?></td>
                    <td>
                        <form action="delete-user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
     </div>
    </div>
<script>
function searchUsers() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("usersTable");
    let rows = table.getElementsByTagName("tr");
    let anyRowVisible = false; // Track if any row is shown

    for (let i = 1; i < rows.length; i++) { // Skip table header
        let usernameCell = rows[i].getElementsByTagName("td")[0]; // First column (Username)
        
        if (usernameCell) {
            let username = usernameCell.textContent.toLowerCase();
            
            if (username.includes(input)) {
                rows[i].style.display = ""; // Show row if match found
                anyRowVisible = true;
            } else {
                rows[i].style.display = "none"; // Hide row if no match
            }
        }
    }

    // If no results are found, display a full-width row with a message
    let noResultsRow = document.getElementById("noResultsRow");
    if (!anyRowVisible) {
        if (!noResultsRow) {
            noResultsRow = document.createElement("tr");
            noResultsRow.id = "noResultsRow";
            noResultsRow.innerHTML = `<td colspan="6">No users found.</td>`;
            table.appendChild(noResultsRow);
        }
        noResultsRow.style.display = "";
    } else {
        if (noResultsRow) {
            noResultsRow.style.display = "none";
        }
    }
}
</script>
<script src="script.js"></script>

</body>
</html>
