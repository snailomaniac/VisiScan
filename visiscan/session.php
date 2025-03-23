<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['username'])) {
    echo json_encode([
        "firstname" => $_SESSION['firstname'],
        "lastname" => $_SESSION['lastname'],
        "username" => $_SESSION['username']
    ]);
} else {
    echo json_encode(["error" => "User not logged in"]);
}
?>
