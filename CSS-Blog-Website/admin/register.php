<?php
session_start();
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Invalid email format.";
    }

    // Validate password match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = "❌ Passwords do not match.";
    }
    // Validate required fields
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        $error = "⚠️ All fields are required.";
    }
    // Sanitize and trim input
    $first_name = trim($_POST['first_name']);
    $middle_name = isset($_POST['middle_name']) && trim($_POST['middle_name']) !== '' ? trim($_POST['middle_name']) : null;
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $image = '';

    // Check if there are any previous errors before proceeding
    if (!isset($error)) {
        // Check if username already exists
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "⚠️ Username already taken.";
        }
    }

    // Only insert new user if there are still no errors
    if (!isset($error)) {

        // Handle file upload and validate image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $img_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $random_name = uniqid('profile_', true) . '.' . $img_ext;
            $target = '../uploads/' . $random_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image = $random_name;
            }
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insert_sql = "INSERT INTO users (first_name, middle_name, last_name, profile_image, username, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $middle_name, $last_name, $image, $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            $success = "🎉 Registration successful! <a href='login.php'>Login here</a>.";
        } else {
            $error = "❌ Error during registration.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <label>Profile Image:</label>
        <input type="file" name="image" accept="image/*"><br><br>
        
        <label>First Name:</label>
        <input type="text" name="first_name" required><br><br>

        <label>Middle Name:</label>
        <input type="text" name="middle_name"><br><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" required><br><br>

        <label>Email:</label>
        <input type="text" name="email" required><br><br>
        
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
