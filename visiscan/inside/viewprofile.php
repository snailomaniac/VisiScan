<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../form/login.php");
    exit();
}

$username = $_SESSION['username'];
$conn = new mysqli("localhost", "root", "", "visiscan_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "../profiles/";
    $file_name = basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an actual image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Allow only certain formats
    if(!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Move and update database if all checks pass
    if ($uploadOk && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $sql = "UPDATE users SET profile_picture='$file_name' WHERE username='$username'";
        if ($conn->query($sql) === TRUE) {
            echo "Profile picture updated successfully.";
        } else {
            echo "Error updating profile picture.";
        }
    }
}

// Get user info
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Get profile picture
$profile_picture = "../profiles/";
$profile_picture .= empty($user['profile_picture']) ? "default_profile_picture.png" : $user['profile_picture'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | VisiScan</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="viewprofile.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <hamburger-menu></hamburger-menu>
    <header-registered></header-registered>
    <main>
        <div class="container">
            <h1 style="text-align: center;">Your Profile</h1>
            <br>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone No.:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <br>
            <h3>Your Latest Picture:</h3>
            <img src="../profiles/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="pfp">
        </div>
    </main>
    <footer-registered></footer-registered>
</body>
<script src="../overlay.js"></script>
</html>
<?php $conn->close(); ?>
