<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../form/login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "visiscan_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logs
$sql = "SELECT users.username, users.profile_picture, users.first_name, users.last_name, users.email, users.phone, 
               users.address, users.birth_date, logs.scan_date, logs.check_in, logs.check_out, logs.reason 
        FROM logs 
        JOIN users ON logs.user_id = users.id 
        ORDER BY logs.scan_date DESC, logs.check_in DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Log | VisiScan</title>
    <link rel="stylesheet" href="adminlog.css">
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <hamburger-menu-admin></hamburger-menu-admin>
    <header-registered-admin></header-registered-admin>

    <main>
        <div class="container">
            <h1>Visitors Logs</h1>
            <input type="text" id="logSearch" placeholder="Search Logs..." onkeyup="filterLogs()">
            <div class="logTable">
                <table cellspacing="0" cellpadding="16px">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Username</th>
                            <th>Picture</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Purpose of Visit</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Birthdate</th>
                        </tr>
                    </thead>
                    <tbody id="logTable">
                        <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['scan_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_in']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_out']) ?: '--'; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <img src="../profiles/<?php echo htmlspecialchars($row['profile_picture']); ?>"
                                    width="100px" height="100px" alt="Profile Picture">
                            </td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['birth_date']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12">No logs found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer-registered></footer-registered>

    <script>
    function filterLogs() {
        let input = document.getElementById("logSearch").value.toLowerCase();
        let rows = document.querySelectorAll("#logTable tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
    </script>
    <script src="adminlog.js"></script>
    <script src="../overlay.js"></script>

</body>

</html>

<?php
$conn->close();
?>