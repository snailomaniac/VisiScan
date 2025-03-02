<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visiscan_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT username, scan_date, TIME_FORMAT(check_in, '%h : %i %p') AS check_in, 
        TIME_FORMAT(check_out, '%h : %i %p') AS check_out FROM scan_logs ORDER BY scan_date DESC";
$result = $conn->query($sql);

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);
$conn->close();
?>