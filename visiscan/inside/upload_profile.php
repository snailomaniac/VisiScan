<?php
session_start();
header("Content-Type: application/json");

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized access"]);
    exit;
}

$username = $_SESSION['username'];

// Check if image data is provided
if (!isset($_POST['image'])) {
    echo json_encode(["success" => false, "error" => "No image data received"]);
    exit;
}

$imageData = $_POST['image'];
$imageData = str_replace("data:image/png;base64,", "", $imageData);
$imageData = str_replace(" ", "+", $imageData);
$decodedImage = base64_decode($imageData);

// Check if the image was decoded successfully
if (!$decodedImage) {
    echo json_encode(["success" => false, "error" => "Image decoding failed"]);
    exit;
}

$uploadDir = "../profiles/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = $uploadDir . $username . "_profile.png";
if (file_put_contents($fileName, $decodedImage)) {
    // Update profile picture in the database
    $conn = new mysqli("localhost", "root", "", "visiscan_db");
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => "Database connection failed"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET profile_picture = ?, account_status = 'pending' WHERE username = ?");
    $stmt->bind_param("ss", $fileName, $username);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "image_path" => $fileName]);
    } else {
        echo json_encode(["success" => false, "error" => "Database update failed"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Failed to save image"]);
}
?>
