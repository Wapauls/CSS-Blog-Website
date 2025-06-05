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
    $stmt = $conn->prepare('DELETE FROM community WHERE id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $message = 'Community entry deleted!';
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
    $result = $conn->query("SELECT * FROM community WHERE id=$id");
    $edit_entry = $result->fetch_assoc();
}

// Handle Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $members = intval($_POST['members']);
    $image = '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['image']['name']);
        $target = '../uploads/' . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $img_name;
        }
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        // Update
        $id = intval($_POST['id']);
        if ($image) {
            $stmt = $conn->prepare('UPDATE community SET title=?, content=?, image=?, members=? WHERE id=?');
            $stmt->bind_param('sssis', $title, $content, $image, $members, $id);
        } else {
            $stmt = $conn->prepare('UPDATE community SET title=?, content=?, members=? WHERE id=?');
            $stmt->bind_param('ssii', $title, $content, $members, $id);
        }
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: community.php?msg=updated');
            exit();
        } else {
            $message = 'Update failed.';
        }
        $stmt->close();
    } else {
        // Create
        $stmt = $conn->prepare('INSERT INTO community (title, content, image, members) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('sssi', $title, $content, $image, $members);
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: community.php?msg=added');
            exit();
        } else {
            $message = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Show message from redirect
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') $message = 'Community entry added successfully!';
    if ($_GET['msg'] === 'updated') $message = 'Community entry updated!';
}

// Fetch all entries
$entries = $conn->query('SELECT * FROM community ORDER BY id DESC');

// Get current page for active state
$current_page = 'community';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Community</title>
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

        <h1>Community Management</h1>
        <h2><?= $edit ? 'Edit Community Entry' : 'Add New Community Entry' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?= $edit_entry['id'] ?>">
            <?php endif; ?>
            <label>Title:<br><input type="text" name="title" required value="<?= $edit ? htmlspecialchars($edit_entry['title']) : '' ?>"></label><br><br>
            <label>Content:<br><textarea name="content" required><?= $edit ? htmlspecialchars($edit_entry['content']) : '' ?></textarea></label><br><br>
            <label>Members:<br><input type="number" name="members" required value="<?= $edit ? intval($edit_entry['members']) : '0' ?>"></label><br><br>
            <label>Image:<br><input type="file" name="image" accept="image/*"></label>
            <?php if ($edit && $edit_entry['image']): ?>
                <br><img src="../uploads/<?= htmlspecialchars($edit_entry['image']) ?>" alt="" style="max-width:100px;">
            <?php endif; ?>
            <br><br>
            <button type="submit"><?= $edit ? 'Update Entry' : 'Add Entry' ?></button>
            <?php if ($edit): ?>
                <a href="community.php">Cancel</a>
            <?php endif; ?>
        </form>

        <h2>All Community Entries</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Members</th>
                <th>Image</th>
                <th>Actions</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $entries->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                    <td><?= $row['members'] ?></td>
                    <td><?php if ($row['image']): ?><img src="../uploads/<?= htmlspecialchars($row['image']) ?>" style="max-width:80px;"/><?php endif; ?></td>
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