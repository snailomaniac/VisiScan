<?php
session_start();
$username = $_SESSION['username']; // Assuming username is stored in session

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "visiscan_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT username, scan_date, check_in, check_out FROM logs WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$logs = array();
while($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($logs);
?>