<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['new_status'])) {
        $user_id = intval($_POST['user_id']);
        $new_status = $_POST['new_status'];

        // Check if valid status
        $valid_statuses = ['true', 'pending', 'false', 'restricted'];
        if (in_array($new_status, $valid_statuses)) {
            $conn = new mysqli("localhost", "root", "", "visiscan_db");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Update the user's account status
            $sql = "UPDATE users SET account_status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_status, $user_id);

            if ($stmt->execute()) {
                echo "Status updated successfully!";
            } else {
                echo "Error updating status: " . $stmt->error;
            }
            $stmt->close();
            $conn->close();
        } else {
            echo "Invalid status!";
        }
    }
?>
