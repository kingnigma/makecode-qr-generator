<?php
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create account - MakeCode</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .auth-container {
            max-width: 420px;
            margin: 48px auto;
            padding: 24px;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        .auth-input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            background: #00a86b;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container nav-container">
            <!-- Logo -->
            <div class="logo">
                <img src="images/logo.png" alt="MakeCode logo" width="80px">
            </div>

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="index.php" class="nav-link" id="whatIsQRBtn">Home</a>
                <a href="#" class="nav-link" id="whatIsQRBtn">What is a QR Code</a>
                <a href="#" class="nav-link" id="howToUseBtn">How to use</a>
                <a href="#" class="nav-link">Scan Code</a>
                <a href="login.php" class="btn btn-login">Login</a>

            </div>
        </div>
    </nav>
    <div class="auth-container">
        <h2>Create an account</h2>
        <?php if (!empty($_GET['error'])): ?>
            <div style="color:#b00020;margin-bottom:8px;"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <form action="register_handler.php" method="post">
            <label>Username</label>
            <input class="auth-input" type="text" name="username" required maxlength="50">

            <label>Email</label>
            <input class="auth-input" type="email" name="email" required maxlength="100">

            <label>Password</label>
            <input class="auth-input" type="password" name="password" id="pswd1" required minlength="6">

            <label>Confirm Password</label>
            <input class="auth-input" type="password" name="password_confirm" id="pswd2" onkeyup="check_pass();" required minlength="6">
            <p style="text-align: center;" id='pass_message'></p>

            <div style="margin-top:12px;">
                <button class="btn" type="submit">Create account</button>
                <a href="login.php" style="margin-left:12px;">Already have an account?</a>
            </div>
        </form>
    </div>
    <script>
        function check_pass() {
            let $pass1 = document.getElementById("pswd1").value;
            let $pass2 = document.getElementById("pswd2").value;
            if ($pass1 !== $pass2) {
                document.getElementById("pass_message").innerHTML = "<span style='color:#e30b0b; font-weight: bolder; text-align: center;' id='pass_message'>Make sure password match!</span>";
            } else {
                document.getElementById("pass_message").innerHTML = "<span style='color:#00a86b; font-weight: bolder; text-align: center;' id='pass_message'>Password match!</span>";
            }
        }
    </script>
</body>

</html>