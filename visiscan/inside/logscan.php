<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$scannedUsername = $data['username'];
$reason = isset($data['reason']) ? trim($data['reason']) : "No reason provided";
$current_date = date('Y-m-d');

$mysqli = new mysqli('localhost', 'root', '', 'visiscan_db');

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// **Get user details before logging check-in/out** 
$userStmt = $mysqli->prepare("SELECT id, first_name, last_name, email, phone, address, birth_date, profile_picture, account_status FROM users WHERE username = ?");
$userStmt->bind_param("s", $scannedUsername);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$userData = $userResult->fetch_assoc();
$userId = $userData['id'];

// **Check if user already checked in today without checking out**
$stmt = $mysqli->prepare("SELECT * FROM logs WHERE username = ? AND scan_date = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("ss", $scannedUsername, $current_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // If check_out is NULL, update it (user is checking out)
    if ($row['check_out'] === null) {
        $stmt = $mysqli->prepare("UPDATE logs SET check_out = NOW() WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $action = "Check-Out";
        $reason = $row['reason']; // Keep original reason
    } else {
        // User already checked out, so create a new check-in record
        $stmt = $mysqli->prepare("INSERT INTO logs (user_id, username, scan_date, check_in, reason) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->bind_param("isss", $userId, $scannedUsername, $current_date, $reason);
        $stmt->execute();
        $action = "Check-In";
    }
} else {
    // First time scanning today -> insert check-in record
    $stmt = $mysqli->prepare("INSERT INTO logs (user_id, username, scan_date, check_in, reason) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->bind_param("isss", $userId, $scannedUsername, $current_date, $reason);
    $stmt->execute();
    $action = "Check-In";
}

// **Return user details and action type, including reason**
echo json_encode([
    'success' => true, 
    'message' => $action . ' logged!', 
    'user' => $userData, 
    'action' => $action,
    'reason' => $reason // Include reason in the response
]);

$userStmt->close();
$stmt->close();
$mysqli->close();
?>
