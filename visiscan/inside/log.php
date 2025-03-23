<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../form/login.php");
        exit();
    }
    $username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Logs | VisiScan</title>
    <link rel="stylesheet" href="../style.css"/>
    <link rel="stylesheet" href="log.css"/>
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
</head>
<body>
    <hamburger-menu></hamburger-menu>
    <header-registered></header-registered>

    <main>        
        <div class="container">
            <h1>Scanned Logs</h1>
            <input type="text" id="logSearch" placeholder="Search Logs..." onkeyup="filterLogs()">

            <div class="logTable">
                <table cellspacing="0px" cellpadding="16px">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Purpose of Visit</th>
                        </tr>
                    </thead>
                    <tbody id="logTable">
                        <tr><td colspan="4">Loading logs...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer-registered></footer-registered>

    <script src="log.js"></script>
    <script src="../overlay.js"></script>  
</body>
</html>
