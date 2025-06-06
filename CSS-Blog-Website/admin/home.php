<?php
require_once '../config.php';
// Start session
session_start();

// Set page title
$title = "Admin - Blog Posts";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM posts WHERE id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $message = 'Post deleted!';
    } else {
        $message = 'Delete failed.';
    }
    $stmt->close();
}

// Handle Edit (fetch data)
$edit = false;
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM posts WHERE id=$id");
    $edit_post = $result->fetch_assoc();
}

// Handle Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
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
            $stmt = $conn->prepare('UPDATE posts SET title=?, content=?, image=? WHERE id=?');
            $stmt->bind_param('sssi', $title, $content, $image, $id);
        } else {
            $stmt = $conn->prepare('UPDATE posts SET title=?, content=? WHERE id=?');
            $stmt->bind_param('ssi', $title, $content, $id);
        }
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: home.php?msg=updated');
            exit();
        } else {
            $message = 'Update failed.';
        }
        $stmt->close();
    } else {
        // Create
        $stmt = $conn->prepare('INSERT INTO posts (title, content, image) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $title, $content, $image);
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: home.php?msg=added');
            exit();
        } else {
            $message = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Show message from redirect
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') $message = 'Post added successfully!';
    if ($_GET['msg'] === 'updated') $message = 'Post updated!';
}

// Fetch all posts
$posts = $conn->query('SELECT * FROM posts ORDER BY created_at DESC');

// Get current page for active state
$current_page = 'home';

// Start buffering the content
ob_start();

?>
<!-- Main Content -->
<div class="main-content">
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <h1>Blog Posts Management</h1>
    <h2><?= $edit ? 'Edit Post' : 'Add New Post' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?= $edit_post['id'] ?>">
        <?php endif; ?>
        <label>Title:<br><input type="text" name="title" required value="<?= $edit ? htmlspecialchars($edit_post['title']) : '' ?>"></label><br><br>
        <label>Content:<br><textarea name="content" required><?= $edit ? htmlspecialchars($edit_post['content']) : '' ?></textarea></label><br><br>
        <label>Image:<br><input type="file" name="image" accept="image/*"></label>
        <?php if ($edit && $edit_post['image']): ?>
            <br><img src="../uploads/<?= htmlspecialchars($edit_post['image']) ?>" alt="" style="max-width:100px;">
        <?php endif; ?>
        <br><br>
        <button type="submit"><?= $edit ? 'Update Post' : 'Add Post' ?></button>
        <?php if ($edit): ?>
            <a href="home.php">Cancel</a>
        <?php endif; ?>
    </form>

    <h2>All Posts</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Actions</th>
            <th>Created At</th>
        </tr>
        <?php while ($row = $posts->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                <td><?php if ($row['image']): ?><img src="../uploads/<?= htmlspecialchars($row['image']) ?>" style="max-width:80px;"/><?php endif; ?></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>">Edit</a> |
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this post?');">Delete</a>
                </td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php
// Get the buffered content
$content = ob_get_clean();
// Include the base template
include 'includes/base.php';