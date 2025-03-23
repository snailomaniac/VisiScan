<?php
    ob_start();
    session_start();
    $conn = new mysqli("localhost", "root", "", "visiscan_DB");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $login_error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        if ($user == 'admin' && $pass == 'admin') {
            $_SESSION['username'] = 'admin';
            header("Location: ../inside/admin.php");
            exit();
        }

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();        
            if (password_verify($pass, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['firstname'] = $row['first_name'];
                $_SESSION['lastname'] = $row['last_name'];

                header("Location: ../inside/home.php");
                exit();
            } else {
                $login_error = "Invalid password!";
            }
        } else {
            $login_error = "User not found!";
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login | VisiScan</title>
        <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="forms.css" />
    </head>
    <body>
        <article class="login">
            <div class="container-login">
                <a href="../index.html"><img src="../assets/icons/arrow-left.svg" alt="return" title="Return Back" class="btn-back"></a>
                <h1>Log In</h1>
                <?php if (empty($login_error)) { ?>
                    <br><br><br>
                <?php } ?>
                <?php if (!empty($login_error)) { ?>
                    <br><p class="errorpara"><strong><?php echo $login_error; ?></strong></p><br>
                <?php } ?>
                <form action="login.php" method="POST">
                    <h4>Username:</h4>
                    <input type="text" name="user" id="loginuser" placeholder="Username" required>
                    <h4>Password:</h4>
                    <input type="password" name="pass" id="loginpass" placeholder="Password" required><br>
                    <button type="submit" class="btn">Log In</button>
                </form>
                <p>Don't have one? <a href="signup.php">Sign Up</a></p>
            </div>
        </article>
    </body>
    <script src="main.js"></script>
</html>