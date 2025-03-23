<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../form/login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch profile picture and account status from the database
$conn = new mysqli("localhost", "root", "", "visiscan_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT profile_picture, account_status FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profilePic, $accountStatus);
$stmt->fetch();
$stmt->close();
$conn->close();

// Check if the user has a profile picture (not the default one)
$hasProfilePicture = ($profilePic && $profilePic !== 'default_profile_picture.png');

// Use a default profile picture if empty
$profilePic = $profilePic ?: "uploads/default_profile_picture.png";

// Check if account status is "pending"
$isPending = ($accountStatus === "pending");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code | VisiScan</title>
    <link rel="stylesheet" href="qrcode.css"/>
    <link rel="stylesheet" href="../style.css"/>
</head>
<body>
    <hamburger-menu></hamburger-menu>
    <header-registered></header-registered>
    <main>
        <div class="layout">
            <input type="hidden" id="username" value="<?php echo htmlspecialchars($username); ?>">
            <div class="container">
                <!-- Camera Section (Hidden if user has a profile picture) -->
                <div id="cameraContainer" style="display: <?php echo (!$hasProfilePicture && $isPending) ? 'flex' : 'none'; ?>;">
                    <video id="cameraFeed" autoplay></video>
                    <button onclick="captureImage()">Capture Image</button>
                </div>

                <!-- Image Preview Section -->
                <div id="imagePreview" style="display: none;">
                    <img id="capturedImage" src="" alt="Captured Profile Picture"><br>
                    <button id="confirmButton" onclick="confirmImage()">Confirm</button>
                    <button onclick="retakeImage()">Retake Picture</button>
                </div>

                <!-- Reasoning Section (Visible Before QR Code) -->
                <div id="reasoningSection" style="display: none;">
                    <textarea id="reasonInput" class="Reasonofvisit" placeholder="Enter Reason of Visit.<?="\n"?><?="\n"?>e.g. Card Distribution,<?="\n"?>School Event, Parents Meeting,<?="\n"?>etc." maxlength="50"></textarea>
                    <button onclick="submitReason()">Submit Reason</button>
                </div>

                <!-- QR Code Section (Initially Hidden) -->
                <div id="qrSection" style="display: none;">
                    <div class="qrimgholder">
                        <img src="" id="qrImageGen" alt="Your QR Code">
                    </div>
                    <button onclick="generateQR()" style="grid-area:2 / 1 / 3 / 3;">QR didn't work?</button>
                </div>
            </div>
            <div class="text-details">
                <h3>Important!</h3>
                <p>Any actions related to this QR Code are linked to your name. Do not share it to avoid any issues.</p>
                <input type="hidden" id="username" value="<?php echo htmlspecialchars($username); ?>">
                <p>Account Verified: <span class="status <?php echo strtolower(str_replace(' ', '-', $accountStatus)); ?>"><?php echo htmlspecialchars($accountStatus); ?></span></p>
                <p><br>Know your account status?</p>
                <p><b>false</b> - means your account has not met all the requirements perhaps the picture or the other details of your account.</p>
                <p><b>pending</b> - means waiting for an admin to verify your account.</p>
                <p><b>true</b> - means your account has met all the requirements.</p>
                <p><b>restricted</b> - means you are currently restricted / prohibited for entering inside the school. </p>
            </div>
        </div>
    </main>
    <footer-registered></footer-registered>
    
    <script>
        let hasProfilePicture = <?php echo json_encode($hasProfilePicture); ?>;
        let isPending = <?php echo json_encode($isPending); ?>;
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let submitBtn = document.getElementById("submit-btn");
            if (submitBtn) {
                submitBtn.addEventListener("click", function () {
                    if (typeof window.submitReason === "function") {
                        window.submitReason();
                    } else {
                        console.error("submitReason function is not defined!");
                    }
                });
            }
        });
    </script>
    <script src="../overlay.js"></script>
    <script src="qrgen.js"></script>
    <script src="camera.js"></script>

</body>
</html>
