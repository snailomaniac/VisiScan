<?php
session_start();
if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname'])) {
    echo "<script>alert('No user is logged in!');</script>";
    exit;
}
$firstName = $_SESSION['firstname'];
$lastName = $_SESSION['lastname'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code | VisiScan</title>
    <script src="qrgen.js" defer></script>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($firstName . " " . $lastName); ?>!</h2>
    
    <!-- Hidden fields for JavaScript to use -->
    <input type="hidden" id="firstname" value="<?php echo htmlspecialchars($firstName); ?>">
    <input type="hidden" id="lastname" value="<?php echo htmlspecialchars($lastName); ?>">

    <button onclick="generateQR()">Generate QR</button>
    <img id="qrImageGen" src="" alt="Your QR Code">
</body>
</html>