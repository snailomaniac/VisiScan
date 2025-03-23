<?php
    session_start();
    
    // Check if the user is logged in as admin
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
        <title> Scanner | VisiScan</title>         
        <link rel="stylesheet" href="qrscanner.css"/> 
        <link rel="stylesheet" href="../style.css"/> 
    </head>     
    <body>
        <hamburger-menu-admin></hamburger-menu-admin>
        <header-registered-admin></header-registered-admin>    
        <main>
            <div class="container">
                <div id="qrReader" class="camera"></div>
                <div class="content-details">
                    <p id="qrResult" class="text">Scan a QR Code</p>
                    <div id="qrUserInfo" class="user-details"></div>
                </div>
            </div>
        </main> 
        <footer-registered></footer-registered>         
    </body>
    <!--Scanner library-->
    <script src="html5-qrcode.js"></script>
    <script src="../overlay.js"></script>
    <script src="qrscan.js"></script>
</html>