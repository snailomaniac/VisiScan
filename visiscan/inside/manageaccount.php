<?php
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
        header("Location: ../form/login.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "visiscan_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $search = "";
    if (isset($_POST['search'])) {
        $search = $conn->real_escape_string($_POST['search']);
        $sql = "SELECT id, username, first_name, last_name, email, phone, profile_picture, account_status FROM users 
                WHERE username LIKE '%$search%' 
                OR first_name LIKE '%$search%' 
                OR last_name LIKE '%$search%' 
                OR phone LIKE '%$search%'";
    } else {
        $sql = "SELECT id, username, first_name, last_name, email, phone, profile_picture, account_status FROM users";
    }
    $result = $conn->query($sql);

    $message = "";

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // In your user creation code
        $profile_picture = "default_profile_picture.png"; // Set default
        $sql = "INSERT INTO users (username, first_name, last_name, email, phone, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $first_name, $last_name, $email, $phone, $password, $profile_picture);
        
        if ($stmt->execute()) {
            echo "<script>alert('User added successfully!'); window.location.href='manageaccount.php';</script>";
            exit();
        } else {
            $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

    // Handle delete request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
        $id = intval($_POST['user_id']);
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('User deleted successfully!'); window.location.href='manageaccount.php';</script>";
            exit();
        } else {
            $message = "<p style='color: red;'>Error deleting user: " . $stmt->error . "</p>";
        }
        $stmt->close();
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
    }    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account | VisiScan</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="manageaccount.css">
    <script>
    function filterAccounts() {
        let input = document.getElementById("accountSearch");
        let filter = input.value.toLowerCase();
        let table = document.getElementById("accountTable");
        let rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            let rowText = rows[i].textContent || rows[i].innerText;
            rows[i].style.display = rowText.toLowerCase().includes(filter) ? "" : "none";
        }
    }
    </script>
    <script>
    function updateStatus(userId, newStatus) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Optionally, you can show a confirmation message or handle the response here
                alert(xhr.responseText); // This will show the response from the PHP script

                // Reload the page to reflect the changes
                window.location.reload();
            }
        };
        xhr.send("user_id=" + userId + "&new_status=" + encodeURIComponent(newStatus));
    }
    </script>
</head>

<body>
    <hamburger-menu-admin></hamburger-menu-admin>
    <header-registered-admin></header-registered-admin>
    <main>
        <div class="container">
            <h1>Account Management</h1>
            <input type="text" id="accountSearch" placeholder="Search accounts..." onkeyup="filterAccounts()">
            <div class="logTable">
                <table cellspacing="0px" cellpadding="16px">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Picture</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody id="accountTable">
                        <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><img src="../profiles/<?php echo htmlspecialchars($row['profile_picture']); ?>"
                                    width="100px" height="100px"></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['account_status']); ?></td>
                            <td class='buttonContainer'>
                                <form method='POST' style='display:inline-block;'
                                    onsubmit="event.preventDefault(); updateStatus(<?php echo htmlspecialchars($row['id']); ?>, 'true');">
                                    <button type='submit' class='accountverify-button'>True</button>
                                </form>
                                <form method='POST' style='display:inline-block;'
                                    onsubmit="event.preventDefault(); updateStatus(<?php echo htmlspecialchars($row['id']); ?>, 'pending');">
                                    <button type='submit' class='accountverify-button'>Pending</button>
                                </form>
                                <form method='POST' style='display:inline-block;'
                                    onsubmit="event.preventDefault(); updateStatus(<?php echo htmlspecialchars($row['id']); ?>, 'false');">
                                    <button type='submit' class='accountverify-button'>False</button>
                                </form>
                                <form method='POST' style='display:inline-block;'
                                    onsubmit="event.preventDefault(); updateStatus(<?php echo htmlspecialchars($row['id']); ?>, 'restricted');">
                                    <button type='submit' class='accountverify-button'>Restricted</button>
                                </form>
                                <form method='POST' style='display:inline;'>
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_user" onclick='return confirm("Are you sure?")'
                                        class='delete-button'>Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan='5'>No users found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p> <?php echo $message; ?></p>
            <table class="regular" cellspacing="0px" cellpadding="4px">
                <form method="POST">
                    <input type="hidden" name="profile_picture" value="default_profile_picture.png">
                    <tr>
                        <td><label>Username:</label></td>
                        <td><input type="text" name="username" required></td>
                        <td><label>Password:</label></td>
                        <td><input type="password" name="password" required></td>
                    </tr>
                    <td><label>First Name:</label></td>
                    <td><input type="text" name="first_name" required></td>
                    <td><label>Last Name:</label></td>
                    <td><input type="text" name="last_name" required></td>
                    </tr>
                    <tr>
                        <td><label>Email:</label></td>
                        <td><input type="email" name="email" required></td>
                        <td><label>Phone:</label></td>
                        <td><input type="text" name="phone" required></td>
                    </tr>
                    <tr>
                        <td colspan="4"><button type="submit" name="add_user">Add User</button></td>
                    </tr>
                </form>
            </table>
        </div>
    </main>
    <footer-registered></footer-registered>
    <script src="../overlay.js"></script>
</body>

</html>
<?php
$conn->close();
?>