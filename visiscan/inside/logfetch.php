<?php
$conn = new mysqli('localhost', 'root', '', 'visiscan_db');

if ($conn->connect_error) {
    die(json_encode(["error" => "Database Connection Failed"]));
}

header('Content-Type: application/json');

// Fetch logs including reason (purpose of visit)
$sql = "SELECT username, scan_date, TIME_FORMAT(check_in, '%h:%i %p') AS check_in, 
        TIME_FORMAT(check_out, '%h:%i %p') AS check_out, reason 
        FROM logs ORDER BY scan_date DESC";

$result = $conn->query($sql);
$logs = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = [
            "username" => $row["username"],
            "scan_date" => $row["scan_date"],
            "check_in" => $row["check_in"],
            "check_out" => $row["check_out"] ?? "--",
            "reason" => !empty($row["reason"]) ? htmlspecialchars($row["reason"]) : "--"
        ];
    }
    $result->free();
}

// Close connection
$conn->close();

// Output JSON
echo json_encode($logs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
