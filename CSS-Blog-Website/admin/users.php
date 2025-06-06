<?php
require_once '../config.php';
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $message = 'User entry deleted!';
    } else {
        $message = 'Delete failed.';
    }
    $stmt->close();
}

// Handle Edit (fetch data)
$edit = false;
$edit_entry = null;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    $edit_entry = $result->fetch_assoc();
}

// Handle Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $image = '';
    
    // Handle file upload and validate image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $random_name = uniqid('profile_', true) . '.' . $img_ext;
        $target = '../uploads/' . $random_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $random_name;
        }
    }

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Invalid email format.";
    }
    
    // Proceed only if there are no errors
    if (isset($_POST['id']) && $_POST['id'] && !isset($error)) {
        // Update
        $id = intval($_POST['id']);

        // Check if new password is given (Both Add and Update)
        $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        if ($image && $new_password) {
            $stmt = $conn->prepare('UPDATE users SET username=?, first_name=?, middle_name=?, last_name=?, email=?, password=?, profile_image=?, is_admin=? WHERE id=?');
            $stmt->bind_param('sssssssii', $username, $first_name, $middle_name, $last_name, $email, $new_password, $image, $is_admin, $id);
        } elseif ($image) {
            $stmt = $conn->prepare('UPDATE users SET username=?, first_name=?, middle_name=?, last_name=?, email=?, profile_image=?, is_admin=? WHERE id=?');
            $stmt->bind_param('ssssssii', $username, $first_name, $middle_name, $last_name, $email, $image, $is_admin, $id);
        } elseif ($new_password) {
            $stmt = $conn->prepare('UPDATE users SET username=?, first_name=?, middle_name=?, last_name=?, email=?, password=?, is_admin=? WHERE id=?');
            $stmt->bind_param('ssssssii', $username, $first_name, $middle_name, $last_name, $email, $new_password, $is_admin, $id);
        } else {
            $stmt = $conn->prepare('UPDATE users SET username=?, first_name=?, middle_name=?, last_name=?, email=?, is_admin=? WHERE id=?');
            $stmt->bind_param('ssssssi', $username, $first_name, $middle_name, $last_name, $email, $is_admin, $id);
        }
        
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: users.php?msg=updated');
            exit();
        } else {
            $message = 'Update failed.';
        }
        $stmt->close();
    } else {
        // Create
        $stmt = $conn->prepare('INSERT INTO users (username, first_name, middle_name, last_name, email, password, profile_image, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssssi', $username, $first_name, $middle_name, $last_name, $email, $password, $image, $is_admin);
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: users.php?msg=added');
            exit();
        } else {
            $message = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Show message from redirect
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') $message = 'User entry added successfully!';
    if ($_GET['msg'] === 'updated') $message = 'User entry updated!';
}

// Fetch all entries
$entries = $conn->query('SELECT * FROM users ORDER BY id DESC');

// Get current page for active state
$current_page = 'users';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Users</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Include Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <h1>Admin Approval Panel</h1>
        <h2><?= $edit ? 'Edit User Entry' : 'Add New User Entry' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?= $edit_entry['id'] ?>">
            <?php endif; ?>
            <label>Email:<br><input type="email" name="email" required value="<?= $edit ? htmlspecialchars($edit_entry['email']) : '' ?>"></label><br><br>
            <label>Username:<br><input type="text" name="username" required value="<?= $edit ? htmlspecialchars($edit_entry['username']) : '' ?>"></label><br><br>
            <label>First Name:<br><input type="text" name="first_name" required value="<?= $edit ? htmlspecialchars($edit_entry['first_name']) : '' ?>"></label><br><br>
            <label>Middle Name:<br><input type="text" name="middle_name" value="<?= $edit ? htmlspecialchars($edit_entry['middle_name']) : '' ?>"></label><br><br>
            <label>Last Name:<br><input type="text" name="last_name" required value="<?= $edit ? htmlspecialchars($edit_entry['last_name']) : '' ?>"></label><br><br>
            <label>
                <?php if ($edit): ?>Reset Password:<br>
                <?php else: ?>Password:<br>
                <?php endif; ?>
            <input type="password" name="password" <?= $edit ? '' : 'required' ?>>
            <?php if ($edit): ?>
                <small>Leave blank to keep the existing password.</small>
            <?php endif; ?>
            </label><br><br>
            <label>Image:<br><input type="file" name="image" accept="image/*"></label>
            <?php if ($edit && $edit_entry['profile_image']): ?>
                <br><img src="../uploads/<?= htmlspecialchars($edit_entry['profile_image']) ?>" alt="" style="max-width:100px;">
            <?php endif; ?>
            <br><br>
            <label for="is_admin">Access Admin Panel:  <input class="option-input checkbox" type="checkbox" name="is_admin" <?= $edit && $edit_entry['is_admin'] ? 'checked' : '' ?>></label>
            <br><br>
            <button type="submit"><?= $edit ? 'Update Entry' : 'Add Entry' ?></button>
            <?php if ($edit): ?>
                <a href="users.php">Cancel</a>
            <?php endif; ?>
        </form>

        <h2>All User Entries</h2>
        <table>
            <tr>
                <th>Image</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $entries->fetch_assoc()): ?>
                <tr>
                    <td><?php if ($row['profile_image']): ?><img src="../uploads/<?= htmlspecialchars($row['profile_image']) ?>" style="max-width:80px;"/><?php endif; ?></td>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= htmlspecialchars($row['last_name']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['email'])) ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>">Edit</a> |
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this entry?');">Delete</a>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html> 