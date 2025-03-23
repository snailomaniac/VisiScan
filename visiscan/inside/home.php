<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../form/login.php");
    }

    $username = $_SESSION['username'];
?>
<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title> Home | VisiScan</title>         
        <link rel="stylesheet" href="home.css"/> 
        <link rel="stylesheet" href="../style.css"/> 
    </head>
    <body> 
        <hamburger-menu></hamburger-menu>
        <header-registered></header-registered>
        <article>
            <div class="bigcontainer"> 
                <div class="container"> 
                    <h1>VisiScan</h1> 
                    <h4>Visitors log management<br>system</h4> 
                    <br>
                    <br> 
                    <h3>"Safer student, brighter days"</h3> 
                    <button class="qrCodeButton" onclick="navigateTo('qrcode.php')">QR Code</button>                     
                </div>                 
                <div class="backgroundcontainer"> 
                    <img src="../assets/bg.jpg" class="background"> 
                </div>
            </div>
        </article>
        <footer-registered></footer-registered>
        <script src="../overlay.js"></script>     
    </body>
</html>