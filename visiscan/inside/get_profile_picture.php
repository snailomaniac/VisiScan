<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "visiscan_db");
$username = $_SESSION['username'];

$sql = "SELECT profile_picture FROM users WHERE username = '$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$profile_picture = "../profiles/";
$profile_picture .= empty($user['profile_picture']) ? "default_profile_picture.png" : $user['profile_picture'];

echo json_encode(["profile_picture" => $profile_picture]);
?>
