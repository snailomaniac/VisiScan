<?php
session_start();
header("Content-Type: application/json");

// Database connection
$conn = new mysqli("localhost", "root", "", "visiscan_db");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Get input data
$username = $_SESSION['username'];
$reason = $_POST['reason'] ?? '';

if (empty($reason)) {
    echo json_encode(['success' => false, 'message' => 'Reason cannot be empty.']);
    exit();
}

// Retrieve user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit();
}

$user = $result->fetch_assoc();
$user_id = $user['id'];

// Insert log entry
$stmt = $conn->prepare("INSERT INTO logs (user_id, reason, scan_date) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $user_id, $reason);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Log recorded successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
