<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "visiscan_DB");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $signup_error = ""; // Variable to store signup errors

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_POST['user'];
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = $_POST['address'];
        $birthdate = $_POST['birthdate'];

        $check_user_sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $check_user_stmt = $conn->prepare($check_user_sql);
        $check_user_stmt->bind_param("s", $user);
        $check_user_stmt->execute();
        $check_user_stmt->bind_result($user_count);
        $check_user_stmt->fetch();
        $check_user_stmt->close();

        if ($user_count > 0) {
            $signup_error = "Username already exists!";
        } else {
            $check_email_sql = "SELECT COUNT(*) FROM users WHERE email = ?";
            $check_email_stmt = $conn->prepare($check_email_sql);
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_stmt->bind_result($email_count);
            $check_email_stmt->fetch();
            $check_email_stmt->close();

            if ($email_count > 0) {
                $signup_error = "Email already exists!";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone, address, birth_date) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $user, $pass, $fname, $lname, $email, $phone, $address, $birthdate);

                if ($stmt->execute()) {
                    $_SESSION['username'] = $user;
                    $_SESSION['firstname'] = $fname;
                    $_SESSION['lastname'] = $lname;

                    header("Location: login.php");
                    exit();
                } else {
                    $signup_error = "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Signup | VisiScan</title>
        <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="forms.css" />
    </head>
    <body>
        <article class="login">
            <div class="container-signup">
                <a href="../index.html"><img src="../assets/icons/arrow-left.svg" alt="return" title="Return Back" class="btn-back"></a>
                <h1>Sign Up</h1>
                <?php if (empty($signup_error)) { ?>
                    <br>
                <?php } ?>
                <?php if (!empty($signup_error)) { ?>
                    <p class="errorpara"><strong><?php echo $signup_error; ?></strong></p><br>
                <?php } ?>
                <form action="signup.php" method="POST">
                <div class="signupformwrapper">
                    <h4 class="signupinstructiontext">Username:</h4>
                    <h4 class="signupinstructiontext">Password:</h4>
                    <input type="text" name="user" id="signupuser" placeholder="Username" required>
                    <input type="password" name="pass" id="signuppass" placeholder="Password" required>
                    <h4 class="signupinstructiontext">First Name:</h4>
                    <h4 class="signupinstructiontext">Last Name:</h4>
                    <input type="text" name="fname" id="signupfname" placeholder="e.g. Juan" required>
                    <input type="text" name="lname" id="signuplname" placeholder="e.g. Dela Cruz" required>
                    <h4 class="signupinstructiontext">Email:</h4>
                    <h4 class="signupinstructiontext">Phone no.:</h4>
                    <input type="email" name="email" id="signupemail" placeholder="e.g. example@gmail.com" required>
                    <input type="number" name="phone" id="signuppnum" placeholder="e.g. 0963-911-1234" minlength="11" maxlength="11" oninput="maxLengthCheck(this)" required>
                    <h4 class="signupinstructiontext">Address:</h4>
                    <h4 class="signupinstructiontext">Birth Date:</h4>
                    <input type="text" name="address" id="signupaddr" placeholder="e.g. Blk. #40 Bayanihan st. brgy. Batasan Hills Q.C." required>
                    <input type="date" name="birthdate" id="signupbirth" required>
                </div>
                <button type="submit" class="btn">Signup</button>
                </form>
            </div>
        </article>
    </body>
    <script>
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
              object.value = object.value.slice(0, object.maxLength)
        }
    </script>
    <script src="main.js"></script>
</html>