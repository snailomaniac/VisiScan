<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visiscan_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['user'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$birthdate = $_POST['birthdate'];

// Correct SQL column names
$stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone, address, birth_date) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $user, $pass, $fname, $lname, $email, $phone, $address, $birthdate);

if ($stmt->execute()) {
    $_SESSION['username'] = $user;
    $_SESSION['firstname'] = $fname;
    $_SESSION['lastname'] = $lname;

    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
