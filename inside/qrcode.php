<?php
session_start();
if (!isset($_SESSION['firstname'])) {
    die("User not logged in!");
}

$fullname = $_SESSION['firstname'] . " " . $_SESSION['lastname']; // Full name
$username = $_SESSION['username']; // Username

include('phpqrcode/qrlib.php'); 

// Generate QR Code
$qrData = "User: " . $username . " | Name: " . $fullname;
$filename = "temp_qr.png";
QRcode::png($qrData, $filename, QR_ECLEVEL_L, 10);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your QR Code</title>
</head>
<body>
    <h1>QR Code for <?php echo htmlspecialchars($fullname); ?></h1>
    <img src="<?php echo $filename; ?>" alt="QR Code">
</body>
</html>