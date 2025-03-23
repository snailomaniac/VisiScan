<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$mysqli = new mysqli('localhost', 'root', '', 'visiscan_db');

if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database Connection Failed"]));
}

$sql = "SELECT users.username, users.profile_picture, users.first_name, users.last_name, 
               users.email, users.phone, users.address, users.birth_date, 
               logs.scan_date, logs.check_in, logs.check_out, logs.reason
        FROM logs 
        JOIN users ON logs.user_id = users.id 
        ORDER BY logs.scan_date DESC, logs.check_in DESC";

$result = $mysqli->query($sql);

$logs = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
} else {
    die(json_encode(["error" => "No logs found"]));
}

$mysqli->close();

header('Content-Type: application/json');
echo json_encode($logs);
?>
