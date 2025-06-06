<?php
// Database configuration
$host = 'localhost';
$db   = 'css_blog';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Create default admin user if no users exist to access the admin panel
$check = $conn->query("SELECT COUNT(*) AS total FROM users");
$row = $check->fetch_assoc();
if ($row['total'] == 0) {
    $default_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, email, password, profile_image, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $fname, $mname, $lname, $email, $pass, $img, $admin);

    $fname = "Admin";
    $mname = "";
    $lname = "User";
    $email = "admin@example.com";
    $pass = $default_pass;
    $img = "";
    $admin = 1;
    $stmt->execute();
    $stmt->close();
}

?> 