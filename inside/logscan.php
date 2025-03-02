<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visiscan_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$qr_data = $_POST['qr_data'];
$current_date = date("Y-m-d");
$current_time = date("H:i:s");

$sql = "SELECT id, username FROM users WHERE qr_code = '$qr_data'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    $username = $row['username'];

    $check_sql = "SELECT * FROM scan_logs WHERE user_id = '$user_id' AND scan_date = '$current_date'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE scan_logs SET check_out = '$current_time' WHERE user_id = '$user_id' AND scan_date = '$current_date'";
        $conn->query($update_sql);
        echo "Check-Out logged!";
    } else {
        $insert_sql = "INSERT INTO scan_logs (user_id, username, scan_date, check_in) VALUES ('$user_id', '$username', '$current_date', '$current_time')";
        $conn->query($insert_sql);
        echo "Check-In logged!";
    }
} else {
    echo "User not found!";
}

$conn->close();
?>