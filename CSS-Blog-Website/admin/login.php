<?php
session_start();
require '../config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input (you can also use prepared statements)
    $username = mysqli_real_escape_string($conn, $username);

    // Query user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Password verification
        if (password_verify($password, $user['password'])) {
            // Check if user is an admin or not
            if ($user['is_admin'] == 1) {
                // Set session and redirect to admin dashboard
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = true;
                header("Location: index.php");
                exit;
            } else {
                $error = "⛔ Access denied. You are not an admin.";
            }
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <button type="submit">Login</button>
    </form>
</body>
</html>