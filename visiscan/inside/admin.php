<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: ../form/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page | VisiScan</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <hamburger-menu-admin></hamburger-menu-admin>
    <header-registered-admin></header-registered-admin>
    <main>
        <div class="maincontainer">
            <div class="sectioncontainer">
                <h2>QR Scanner</h2>
                <p>Log visistor by using their QR Codes.</p>
                <button onclick="location.href='qrscanner.php'">Scan Qr Codes</button>
            </div>

            <div class="sectioncontainer">
                <h2>Entry Logs</h2>
                <p>View visitors log list.</p>
                <button onclick="location.href='adminlog.php'">Visitors Log List</button>
            </div>

            <div class="sectioncontainer">
                <h2>Account Management</h2>
                <p>View, Add, and Delete user accounts.</p>
                <button onclick="location.href='manageaccount.php'">Visitors Log List</button>
            </div>
        </div>
    </main>
    <footer-registered></footer-registered>
</body>
<script src="../overlay.js"></script>

</html>