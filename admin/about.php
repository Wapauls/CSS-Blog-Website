<?php
require_once '../config.php';
$message = '';
$action_type = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM about WHERE id=?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        // Redirect to prevent resubmission
        header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=deleted');
        exit();
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
    $result = $conn->query("SELECT * FROM about WHERE id=$id");
    $edit_entry = $result->fetch_assoc();
}

// Handle Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only proceed if title and content are set
    if (isset($_POST['title']) && isset($_POST['content'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $category = trim($_POST['category'] ?? '');
        $image = null; // Initialize as null instead of empty string
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['image']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $img_name = time() . '_' . basename($_FILES['image']['name']);
                $target = '../uploads/' . $img_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image = $img_name;
                }
            }
        }
        
        if (isset($_POST['id']) && $_POST['id']) {
            // Update
            $id = intval($_POST['id']);
            
            // Check if we need to remove the existing image
            $remove_image = isset($_POST['remove_image']) && $_POST['remove_image'] === 'true';
            
            // Get the current image name before making any changes
            $stmt = $conn->prepare('SELECT image FROM about WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_image = null;
            if ($row = $result->fetch_assoc()) {
                $current_image = $row['image'];
            }
            $stmt->close();
            
            // Prepare the update based on image state
            if ($remove_image) {
                // Remove image case
                if ($current_image && file_exists('../uploads/' . $current_image)) {
                    unlink('../uploads/' . $current_image);
                }
                $stmt = $conn->prepare('UPDATE about SET title=?, content=?, image=NULL, category=? WHERE id=?');
                $stmt->bind_param('sssi', $title, $content, $category, $id);
            } else if ($image) {
                // New image uploaded case
                if ($current_image && file_exists('../uploads/' . $current_image)) {
                    unlink('../uploads/' . $current_image);
                }
                $stmt = $conn->prepare('UPDATE about SET title=?, content=?, image=?, category=? WHERE id=?');
                $stmt->bind_param('ssssi', $title, $content, $image, $category, $id);
            } else {
                // No image change case
                $stmt = $conn->prepare('UPDATE about SET title=?, content=?, category=? WHERE id=?');
                $stmt->bind_param('sssi', $title, $content, $category, $id);
            }
            
            if ($stmt->execute()) {
                // Redirect to prevent resubmission
                header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=updated');
                exit();
            } else {
                $message = 'Update failed: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            // Create new entry
            $stmt = $conn->prepare('INSERT INTO about (title, content, image, category) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $title, $content, $image, $category);
            if ($stmt->execute()) {
                // Redirect to prevent resubmission
                header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=added');
                exit();
            } else {
                $message = 'Error: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle Developer Management Operations
if (isset($_POST['developer_action'])) {
    $action = $_POST['developer_action'];
    
    if ($action === 'create' || $action === 'update') {
        $name = trim($_POST['developer_name'] ?? '');
        $position = trim($_POST['developer_position'] ?? '');
        $image = null;
        
        // Handle image upload
        if (isset($_FILES['developer_image']) && $_FILES['developer_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['developer_image']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $img_name = time() . '_' . basename($_FILES['developer_image']['name']);
                $target = '../uploads/' . $img_name;
                if (move_uploaded_file($_FILES['developer_image']['tmp_name'], $target)) {
                    $image = $img_name;
                }
            }
        }
        
        if ($action === 'update' && isset($_POST['developer_id'])) {
            // Update developer
            $id = intval($_POST['developer_id']);
            $remove_image = isset($_POST['remove_developer_image']) && $_POST['remove_developer_image'] === 'true';
            
            // Get current image
            $stmt = $conn->prepare('SELECT image FROM about_developers WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_image = null;
            if ($row = $result->fetch_assoc()) {
                $current_image = $row['image'];
            }
            $stmt->close();
            
            if ($remove_image) {
                if ($current_image && file_exists('../uploads/' . $current_image)) {
                    unlink('../uploads/' . $current_image);
                }
                $stmt = $conn->prepare('UPDATE about_developers SET name=?, position=?, image=NULL WHERE id=?');
                $stmt->bind_param('ssi', $name, $position, $id);
            } else if ($image) {
                if ($current_image && file_exists('../uploads/' . $current_image)) {
                    unlink('../uploads/' . $current_image);
                }
                $stmt = $conn->prepare('UPDATE about_developers SET name=?, position=?, image=? WHERE id=?');
                $stmt->bind_param('sssi', $name, $position, $image, $id);
            } else {
                $stmt = $conn->prepare('UPDATE about_developers SET name=?, position=? WHERE id=?');
                $stmt->bind_param('ssi', $name, $position, $id);
            }
            
            if ($stmt->execute()) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=developer_updated');
                exit();
            }
            $stmt->close();
        } else {
            // Create new developer
            $stmt = $conn->prepare('INSERT INTO about_developers (name, position, image) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $position, $image);
            if ($stmt->execute()) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=developer_added');
                exit();
            }
            $stmt->close();
        }
    } else if ($action === 'delete' && isset($_POST['developer_id'])) {
        // Delete developer
        $id = intval($_POST['developer_id']);
        
        // Get image before deletion
        $stmt = $conn->prepare('SELECT image FROM about_developers WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if ($row['image'] && file_exists('../uploads/' . $row['image'])) {
                unlink('../uploads/' . $row['image']);
            }
        }
        $stmt->close();
        
        $stmt = $conn->prepare('DELETE FROM about_developers WHERE id=?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=developer_deleted');
            exit();
        }
        $stmt->close();
    } else if ($action === 'reorder') {
        // Handle drag and drop reordering
        $order_data = json_decode($_POST['order_data'], true);
        if ($order_data) {
            foreach ($order_data as $index => $id) {
                $stmt = $conn->prepare('UPDATE about_developers SET display_order=? WHERE id=?');
                $stmt->bind_param('ii', $index, $id);
                $stmt->execute();
                $stmt->close();
            }
            echo json_encode(['success' => true]);
            exit();
        }
    }
}

// Handle Content Order Management Operations
if (isset($_POST['content_order_action'])) {
    $action = $_POST['content_order_action'];
    
    if ($action === 'add') {
        $content_type = $_POST['content_type'];
        $content_id = $_POST['content_id'];
        
        // Get the next display order
        $max_order = $conn->query('SELECT MAX(display_order) as max_order FROM about_content_order');
        $max_order_result = $max_order->fetch_assoc();
        $next_order = ($max_order_result['max_order'] ?? -1) + 1;
        
        $stmt = $conn->prepare('INSERT INTO about_content_order (content_type, content_id, display_order) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $content_type, $content_id, $next_order);
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=content_added');
            exit();
        }
        $stmt->close();
    } else if ($action === 'remove') {
        $content_type = $_POST['content_type'];
        $content_id = $_POST['content_id'];
        
        $stmt = $conn->prepare('DELETE FROM about_content_order WHERE content_type = ? AND content_id = ?');
        $stmt->bind_param('ss', $content_type, $content_id);
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=content_removed');
            exit();
        }
        $stmt->close();
    } else if ($action === 'reorder') {
        $order_data = json_decode($_POST['order_data'], true);
        if ($order_data) {
            foreach ($order_data as $index => $item) {
                $stmt = $conn->prepare('UPDATE about_content_order SET display_order = ? WHERE content_type = ? AND content_id = ?');
                $stmt->bind_param('iss', $index, $item['type'], $item['id']);
                $stmt->execute();
                $stmt->close();
            }
            echo json_encode(['success' => true]);
            exit();
        }
    }
}

// Show message from redirect
if (isset($_GET['msg'])) {
    switch($_GET['msg']) {
        case 'added':
            $message = 'About entry added successfully!';
            $action_type = 'create';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'updated':
            $message = 'About entry updated successfully!';
            $action_type = 'update';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'deleted':
            $message = 'About entry deleted successfully!';
            $action_type = 'delete';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'developer_added':
            $message = 'Developer added successfully!';
            $action_type = 'create';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'developer_updated':
            $message = 'Developer updated successfully!';
            $action_type = 'update';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'developer_deleted':
            $message = 'Developer deleted successfully!';
            $action_type = 'delete';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'content_added':
            $message = 'Content added to layout successfully!';
            $action_type = 'create';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'content_removed':
            $message = 'Content removed from layout successfully!';
            $action_type = 'delete';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
    }
    
    // Clean URL after showing message
    echo "<script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.pathname);
        }
    </script>";
}

// Handle error messages
if (strpos($message, 'Error') === 0 || strpos($message, 'failed') !== false) {
    $message_type = 'error';
    $message_icon = 'fa-exclamation-circle';
} elseif (!isset($message_type)) {
    $message_type = 'warning';
    $message_icon = 'fa-exclamation-triangle';
}

// Fetch all entries
$entries = $conn->query('SELECT * FROM about ORDER BY created_at DESC');

// Fetch all developer entries
$developer_entries = $conn->query('SELECT * FROM about_developers ORDER BY display_order ASC, created_at DESC');

// Get current page for active state
$current_page = 'about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - About</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div id="popupMessage" class="popup-message<?= $message ? ' show ' . ($message_type ?? '') : '' ?>">
            <?php if ($message): ?>
                <i class="fas <?= $message_icon ?? 'fa-info-circle' ?>"></i>
                <?= $message ?>
            <?php endif; ?>
        </div>
        
        <h1>About Management</h1>
        <button class="stat-link" id="openCreateModal">
            <i class="fas fa-plus"></i> Create New About Entry
        </button>

        <button class="stat-link" id="openDeveloperModal">
            <i class="fas fa-user-plus"></i> Add New Developer
        </button>
        
        <div class="all-posts-container" id="allPostsContainer">
            <?php if ($entries->num_rows > 0): ?>
                <?php while ($row = $entries->fetch_assoc()): ?>
                    <div class="post-row" data-post-id="<?= $row['id'] ?>">
                        <?php if ($row['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                        <span class="post-title"><?= htmlspecialchars($row['title']) ?></span>
                        <span class="post-category"><?= htmlspecialchars($row['category']) ?></span>
                        <?php
                        $content = strip_tags($row['content']);
                        $truncated = mb_substr($content, 0, 105);
                        if (mb_strlen($content) > 105) {
                            $truncated .= '...';
                        }
                        ?>
                        <span class="post-content"><?= htmlspecialchars($truncated) ?></span>
                        <div class="post-actions">
                            <button class="edit-btn" data-id="<?= $row['id'] ?>" data-title="<?= htmlspecialchars($row['title']) ?>" data-content="<?= htmlspecialchars($row['content']) ?>" data-image="<?= htmlspecialchars($row['image']) ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="delete-btn" data-id="<?= $row['id'] ?>" data-title="<?= htmlspecialchars($row['title']) ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-posts">
                    <i class="fas fa-info-circle"></i>
                    <p>No about entries found. Start by creating your first entry!</p>
                    <button class="stat-link" id="openCreateModalEmpty">
                        <i class="fas fa-plus"></i> Create Your First Entry
                    </button>
                </div>
            <?php endif; ?>
        </div>

            <!-- Developer Management Section -->
            <div class="section-divider">
            <h2>Developer Team Management</h2>
            <p>Manage the developer team that appears on the About page. Drag and drop to reorder.</p>
        </div>

        <div class="developers-container" id="developersContainer">
            <?php if ($developer_entries->num_rows > 0): ?>
                <div class="developers-list" id="developersList">
                    <?php while ($row = $developer_entries->fetch_assoc()): ?>
                        <div class="developer-item" data-id="<?= $row['id'] ?>" data-order="<?= $row['display_order'] ?>">
                            <div class="drag-handle">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <?php if ($row['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <div class="developer-info">
                                <span class="developer-name"><?= htmlspecialchars($row['name']) ?></span>
                                <span class="developer-position"><?= htmlspecialchars($row['position']) ?></span>
                            </div>
                            <div class="developer-actions">
                                <button class="edit-developer-btn" data-id="<?= $row['id'] ?>" 
                                    data-name="<?= htmlspecialchars($row['name']) ?>" 
                                    data-position="<?= htmlspecialchars($row['position']) ?>" 
                                    data-image="<?= htmlspecialchars($row['image'] ?? '') ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-developer-btn" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-developers">
                    <i class="fas fa-users"></i>
                    <p>No developers added yet. Start by adding your first team member!</p>
                    <button class="stat-link" id="openDeveloperModalEmpty">
                        <i class="fas fa-user-plus"></i> Add Your First Developer
                    </button>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Content Order Management Section -->
        <div class="section-divider">
            <h2>Content Order Management</h2>
            <p>Drag and drop to reorder how content appears on the About page. Posts and Developer sections can be mixed.</p>
        </div>
        
        <div class="content-order-container" id="contentOrderContainer">
            <?php
            // Get current content order
            $content_order = $conn->query('SELECT * FROM about_content_order ORDER BY display_order ASC');
            $ordered_items = [];

            if ($content_order && $content_order->num_rows > 0) {
                while ($order_row = $content_order->fetch_assoc()) {
                    if ($order_row['content_type'] === 'post') {
                        $post_id = intval($order_row['content_id']);
                        $post_query = $conn->prepare('SELECT * FROM about WHERE id = ?');
                        if ($post_query) {
                            $post_query->bind_param('i', $post_id);
                            $post_query->execute();
                            $post_result = $post_query->get_result();
                            if ($post_result->num_rows > 0) {
                                $ordered_items[] = [
                                    'type' => 'post',
                                    'id' => $order_row['content_id'],
                                    'data' => $post_result->fetch_assoc()
                                ];
                            }
                            $post_query->close();
                        }
                    } elseif ($order_row['content_type'] === 'developers') {
                        $ordered_items[] = [
                            'type' => 'developers',
                            'id' => 'developers',
                            'data' => ['title' => 'Developer Team Section']
                        ];
                    }
                }
            }
            
            if (!empty($ordered_items)):
            ?>
            <div class="content-order-list" id="contentOrderList">
                <?php foreach ($ordered_items as $item): ?>
                    <div class="content-order-item" data-type="<?= $item['type'] ?>" data-id="<?= $item['id'] ?>" draggable="true">
                        <div class="drag-handle">
                            <i class="fas fa-grip-vertical"></i>
                        </div>
                        <div class="content-info">
                            <?php if ($item['type'] === 'post'): ?>
                                <?php $img = $item['data']['image'] ?? null; ?>
                                <?php if ($img): ?>
                                    <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($item['data']['title']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:5px;margin-right:10px;">
                                <?php endif; ?>
                                <span class="content-type">Post</span>
                                <span class="content-title"><?= htmlspecialchars($item['data']['title']) ?></span>
                            <?php else: ?>
                                <img src="../assets/images/code.png" alt="Developers" style="width:40px;height:40px;object-fit:cover;border-radius:5px;margin-right:10px;">
                                <span class="content-type">Developer Section</span>
                                <span class="content-title"><?= htmlspecialchars($item['data']['title']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="content-actions">
                            <button class="remove-content-btn" data-type="<?= $item['type'] ?>" data-id="<?= $item['id'] ?>">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="no-content-order">
                <i class="fas fa-info-circle"></i>
                <p>No content order set. Add posts and developers to create the page layout.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Available Content Section -->
        <div class="section-divider">
            <h2>Available Content</h2>
            <p>Add content to the About page layout.</p>
        </div>
        
        <!-- Available Posts -->
        <div class="available-content-section">
            <h3>Available Posts</h3>
            <div class="available-posts">
                <?php
                $all_posts = $conn->query('SELECT * FROM about ORDER BY created_at DESC');
                if ($all_posts && $all_posts->num_rows > 0):
                    while ($post = $all_posts->fetch_assoc()):
                        // Check if post is already in order
                        $in_order = $conn->prepare('SELECT id FROM about_content_order WHERE content_type = "post" AND content_id = ?');
                        $in_order->bind_param('i', $post['id']);
                        $in_order->execute();
                        $in_order_result = $in_order->get_result();
                        $is_ordered = $in_order_result->num_rows > 0;
                        $in_order->close();
                        
                        if (!$is_ordered):
                ?>
                <div class="available-item" data-type="post" data-id="<?= $post['id'] ?>">
                    <div class="item-info">
                        <span class="item-title"><?= htmlspecialchars($post['title']) ?></span>
                        <span class="item-category"><?= htmlspecialchars($post['category']) ?></span>
                    </div>
                    <button class="add-content-btn" data-type="post" data-id="<?= $post['id'] ?>" data-title="<?= htmlspecialchars($post['title']) ?>">
                        <i class="fas fa-plus"></i> Add to Layout
                    </button>
                </div>
                <?php 
                        endif;
                    endwhile;
                endif;
                ?>
            </div>
        </div>
        
        <!-- Available Developer Section -->
        <div class="available-content-section">
            <h3>Developer Section</h3>
            <div class="available-developers">
                <?php
                $dev_in_order = $conn->prepare('SELECT id FROM about_content_order WHERE content_type = "developers"');
                $dev_in_order->execute();
                $dev_in_order_result = $dev_in_order->get_result();
                $dev_is_ordered = $dev_in_order_result->num_rows > 0;
                $dev_in_order->close();
                
                if (!$dev_is_ordered):
                ?>
                <div class="available-item" data-type="developers" data-id="developers">
                    <div class="item-info">
                        <span class="item-title">Developer Team Section</span>
                        <span class="item-category">Team Members</span>
                    </div>
                    <button class="add-content-btn" data-type="developers" data-id="developers" data-title="Developer Team Section">
                        <i class="fas fa-plus"></i> Add to Layout
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        

        
        <!-- Modal Container -->
        <div id="blogModalContainer"></div>

        <!-- Image Preview Modal -->
        <div id="imagePreviewModal" class="image-preview-modal">
            <div class="image-preview-modal-content">
                <button type="button" class="close-preview">&times;</button>
                <img src="" alt="Image Preview">
            </div>
        </div>
    </div>

    <script>
    // Use IIFE to prevent global variable conflicts and multiple initializations
    (function() {
        'use strict';
        
        // Prevent multiple initializations
        if (window.aboutManagerInitialized) {
            return;
        }
        window.aboutManagerInitialized = true;

        const modalContainer = document.getElementById('blogModalContainer');
        let currentModal = null;
        let isModalOpen = false;

        // Popup message auto-hide
        const popup = document.getElementById('popupMessage');
        if (popup && popup.textContent.trim() !== '') {
            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }

        // Prevent body scroll when modal is open
        function toggleBodyScroll(disable) {
            document.body.style.overflow = disable ? 'hidden' : '';
        }

        // Open Create Modal
        const openCreateModalBtn = document.getElementById('openCreateModal');
        if (openCreateModalBtn) {
            openCreateModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal('create');
            });
        }

        // Add click handler for empty state create button
        const openCreateModalEmptyBtn = document.getElementById('openCreateModalEmpty');
        if (openCreateModalEmptyBtn) {
            openCreateModalEmptyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal('create');
            });
        }

        // Developer Management Event Handlers
        const openDeveloperModalBtn = document.getElementById('openDeveloperModal');
        if (openDeveloperModalBtn) {
            openDeveloperModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openDeveloperModal('create');
            });
        }

        const openDeveloperModalEmptyBtn = document.getElementById('openDeveloperModalEmpty');
        if (openDeveloperModalEmptyBtn) {
            openDeveloperModalEmptyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openDeveloperModal('create');
            });
        }

        // Event delegation for edit and delete buttons
        document.addEventListener('click', function(e) {
            // Edit button handler
            if (e.target.closest('.edit-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.edit-btn');
                const postData = {
                    id: btn.getAttribute('data-id'),
                    title: btn.getAttribute('data-title'),
                    content: btn.getAttribute('data-content'),
                    image: btn.getAttribute('data-image')
                };
                openModal('edit', postData);
                return;
            }
            
            // Delete button handler
            if (e.target.closest('.delete-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-btn');
                const postData = {
                    id: btn.getAttribute('data-id'),
                    title: btn.getAttribute('data-title')
                };
                openModal('delete', postData);
                return;
            }
            
            // Developer edit button handler
            if (e.target.closest('.edit-developer-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.edit-developer-btn');
                const developerData = {
                    id: btn.getAttribute('data-id'),
                    name: btn.getAttribute('data-name'),
                    position: btn.getAttribute('data-position'),
                    image: btn.getAttribute('data-image')
                };
                openDeveloperModal('edit', developerData);
                return;
            }
            
            // Developer delete button handler
            if (e.target.closest('.delete-developer-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-developer-btn');
                const developerData = {
                    id: btn.getAttribute('data-id'),
                    name: btn.getAttribute('data-name')
                };
                openDeveloperModal('delete', developerData);
                return;
            }
            
            // Content order add button handler
            if (e.target.closest('.add-content-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.add-content-btn');
                const contentData = {
                    type: btn.getAttribute('data-type'),
                    id: btn.getAttribute('data-id'),
                    title: btn.getAttribute('data-title')
                };
                addContentToOrder(contentData);
                return;
            }
            
            // Content order remove button handler
            if (e.target.closest('.remove-content-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.remove-content-btn');
                const contentData = {
                    type: btn.getAttribute('data-type'),
                    id: btn.getAttribute('data-id')
                };
                removeContentFromOrder(contentData);
                return;
            }
            
            // Close modal handler
            if (e.target.classList.contains('close-modal')) {
                e.preventDefault();
                closeModal();
                return;
            }

            // Close modal on outside click
            if (e.target.classList.contains('modal') && isModalOpen) {
                closeModal();
                return;
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isModalOpen) {
                closeModal();
            }
        });

        function openModal(type, data = null) {
            if (isModalOpen) return;
            
            let modalHTML = '';
            
            if (type === 'create') {
                modalHTML = `
                <div class="modal">
                    <div class="modal-content">
                        <button type="button" class="close-modal">&times;</button>
                        <h2><i class="fas fa-plus-circle"></i> Create About Entry</h2>
                        <form id="createPostForm" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="createTitle"><i class="fas fa-heading"></i> Title</label>
                                <input type="text" id="createTitle" name="title" placeholder="Enter entry title" required>
                            </div>
                            <div class="form-group">
                                <label for="createContent"><i class="fas fa-align-left"></i> Content</label>
                                <textarea id="createContent" name="content" placeholder="Write your entry content here..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="createCategory"><i class="fas fa-tags"></i> Category</label>
                                <input type="text" id="createCategory" name="category" placeholder="Enter category (e.g., News, Events, Announcements)" required>
                            </div>
                            <div class="custom-file">
                                <label><i class="fas fa-image"></i> Featured Image</label>
                                <label class="file-input-label" for="createImage">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload image</span>
                                </label>
                                <input type="file" name="image" id="createImage" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-plus"></i> Create Entry
                            </button>
                        </form>
                    </div>
                </div>`;
            } 
            else if (type === 'edit') {
                modalHTML = `
                <div class="modal">
                    <div class="modal-content">
                        <button type="button" class="close-modal">&times;</button>
                        <h2><i class="fas fa-edit"></i> Edit About Entry</h2>
                        <form id="editPostForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="${data.id}">
                            <input type="hidden" name="remove_image" value="false" id="removeImageFlag">
                            <div class="form-group">
                                <label for="editTitle"><i class="fas fa-heading"></i> Title</label>
                                <input type="text" id="editTitle" name="title" value="${data.title}" required>
                            </div>
                            <div class="form-group">
                                <label for="editContent"><i class="fas fa-align-left"></i> Content</label>
                                <textarea id="editContent" name="content" required>${data.content}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="editCategory"><i class="fas fa-tags"></i> Category</label>
                                <input type="text" id="editCategory" name="category" value="${data.category}" required>
                            </div>
                            <div class="custom-file">
                                <label><i class="fas fa-image"></i> Featured Image</label>
                                <div id="existingImageContainer">
                                    ${data.image ? `
                                        <div class="img-preview-container show existing-image" data-image="${data.image}">
                                            <div class="previewImageBlur">
                                                <i class="fas fa-eye"></i>
                                                <span>Preview image</span>
                                            </div>
                                            <img src="../uploads/${data.image}" alt="Current image">
                                            <button type="button" class="remove-preview" title="Remove image">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    ` : ''}
                                </div>
                                <label class="file-input-label" for="editImage">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>${data.image ? 'Change image' : 'Click to upload image'}</span>
                                </label>
                                <input type="file" name="image" id="editImage" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>`;
            } 
            else if (type === 'delete') {
                modalHTML = `
                <div class="modal">
                    <div class="modal-content">
                        <button type="button" class="close-modal">&times;</button>
                        <h2><i class="fas fa-trash-alt"></i> Delete About Entry</h2>
                        <div class="delete-confirmation">
                            <i class="fas fa-exclamation-triangle headTrash"></i>
                            <p>Are you sure you want to delete "<strong>${data.title}</strong>"?</p>
                            <p>This action cannot be undone.</p>
                            <div class="delete-actions">
                                <button type="button" class="deleting-btn" onclick="confirmDelete(${data.id})">
                                    <i class="fas fa-trash btnTrash"></i> Delete Entry
                                </button>
                                <button type="button" class="cancel-btn" onclick="closeModal()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            
            showModal(modalHTML);
            
            // Setup image preview after modal is shown
            setTimeout(() => {
                if (type === 'create') {
                    setupImagePreview('createImage');
                } else if (type === 'edit') {
                    // Handle existing image removal
                    const existingContainer = document.querySelector('#existingImageContainer .img-preview-container.existing-image');
                    if (existingContainer) {
                        handleExistingImageRemove(existingContainer);
                    }
                    
                    // Setup new image preview
                    setupImagePreview('editImage');
                }
            }, 100);
        }

        function showModal(html) {
            modalContainer.innerHTML = html;
            currentModal = modalContainer.querySelector('.modal');
            isModalOpen = true;
            
            // Show modal with animation
            setTimeout(() => {
                currentModal.classList.add('show');
                toggleBodyScroll(true);
            }, 10);
            
            // Focus first input
            const firstInput = currentModal.querySelector('input:not([type="hidden"]):not([type="file"]), textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 300);
            }
        }

        function closeModal() {
            if (!currentModal || !isModalOpen) return;
            
            currentModal.classList.remove('show');
            toggleBodyScroll(false);
            
            setTimeout(() => {
                modalContainer.innerHTML = '';
                currentModal = null;
                isModalOpen = false;
            }, 300);
        }

        // Developer Modal Functions
        function openDeveloperModal(type, data = null) {
            if (isModalOpen) return;
            
            let modalHTML = '';
            
            if (type === 'create') {
                modalHTML = `
                <div class="modal">
                    <div class="modal-content">
                        <button type="button" class="close-modal">&times;</button>
                        <h2><i class="fas fa-user-plus"></i> Add New Developer</h2>
                        <form id="createDeveloperForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="developer_action" value="create">
                            <div class="form-group">
                                <label for="createDeveloperName"><i class="fas fa-user"></i> Name</label>
                                <input type="text" id="createDeveloperName" name="developer_name" placeholder="Enter developer name" required>
                            </div>
                            <div class="form-group">
                                <label for="createDeveloperPosition"><i class="fas fa-briefcase"></i> Position</label>
                                <input type="text" id="createDeveloperPosition" name="developer_position" placeholder="Enter developer position" required>
                            </div>
                            <div class="custom-file">
                                <label><i class="fas fa-image"></i> Profile Image</label>
                                <label class="file-input-label" for="createDeveloperImage">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload image</span>
                                </label>
                                <input type="file" name="developer_image" id="createDeveloperImage" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-plus"></i> Add Developer
                            </button>
                        </form>
                    </div>
                </div>`;
            } 
            else if (type === 'edit') {
                modalHTML = `
                <div class="modal">
                    <div class="modal-content">
                        <button type="button" class="close-modal">&times;</button>
                        <h2><i class="fas fa-edit"></i> Edit Developer</h2>
                        <form id="editDeveloperForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="developer_action" value="update">
                            <input type="hidden" name="developer_id" value="${data.id}">
                            <input type="hidden" name="remove_developer_image" value="false" id="removeDeveloperImageFlag">
                            <div class="form-group">
                                <label for="editDeveloperName"><i class="fas fa-user"></i> Name</label>
                                <input type="text" id="editDeveloperName" name="developer_name" value="${data.name}" required>
                            </div>
                            <div class="form-group">
                                <label for="editDeveloperPosition"><i class="fas fa-briefcase"></i> Position</label>
                                <input type="text" id="editDeveloperPosition" name="developer_position" value="${data.position}" required>
                            </div>
                            <div class="custom-file">
                                <label><i class="fas fa-image"></i> Profile Image</label>
                                <div id="existingDeveloperImageContainer">
                                    ${data.image ? `
                                        <div class="img-preview-container show existing-image" data-image="${data.image}">
                                            <div class="previewImageBlur">
                                                <i class="fas fa-eye"></i>
                                                <span>Preview image</span>
                                            </div>
                                            <img src="../uploads/${data.image}" alt="Current image">
                                            <button type="button" class="remove-preview" title="Remove image">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    ` : ''}
                                </div>
                                <label class="file-input-label" for="editDeveloperImage">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>${data.image ? 'Change image' : 'Click to upload image'}</span>
                                </label>
                                <input type="file" name="developer_image" id="editDeveloperImage" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>`;
            } 
            else if (type === 'delete') {
                modalHTML = `
                <div class="modal">
                    <div class="modal-content">
                        <button type="button" class="close-modal">&times;</button>
                        <h2><i class="fas fa-trash-alt"></i> Delete Developer</h2>
                        <div class="delete-confirmation">
                            <i class="fas fa-exclamation-triangle headTrash"></i>
                            <p>Are you sure you want to delete "<strong>${data.name}</strong>"?</p>
                            <p>This action cannot be undone.</p>
                            <div class="delete-actions">
                                <button type="button" class="deleting-btn" onclick="confirmDeveloperDelete(${data.id})">
                                    <i class="fas fa-trash btnTrash"></i> Delete Developer
                                </button>
                                <button type="button" class="cancel-btn" onclick="closeModal()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            
            showModal(modalHTML);
            
            // Setup image preview after modal is shown
            setTimeout(() => {
                if (type === 'create') {
                    setupImagePreview('createDeveloperImage');
                } else if (type === 'edit') {
                    // Handle existing image removal
                    const existingContainer = document.querySelector('#existingDeveloperImageContainer .img-preview-container.existing-image');
                    if (existingContainer) {
                        handleExistingImageRemove(existingContainer);
                    }
                    
                    // Setup new image preview
                    setupImagePreview('editDeveloperImage');
                }
            }, 100);
        }

        // Drag and Drop Functionality
        function initializeDragAndDrop() {
            const developersList = document.getElementById('developersList');
            if (!developersList) return;

            let draggedItem = null;

            developersList.addEventListener('dragstart', function(e) {
                if (e.target.closest('.developer-item')) {
                    draggedItem = e.target.closest('.developer-item');
                    e.target.closest('.developer-item').style.opacity = '0.5';
                    e.dataTransfer.effectAllowed = 'move';
                }
            });

            developersList.addEventListener('dragend', function(e) {
                if (draggedItem) {
                    draggedItem.style.opacity = '1';
                    draggedItem = null;
                }
            });

            developersList.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });

            developersList.addEventListener('drop', function(e) {
                e.preventDefault();
                const targetItem = e.target.closest('.developer-item');
                
                if (draggedItem && targetItem && draggedItem !== targetItem) {
                    const allItems = Array.from(developersList.querySelectorAll('.developer-item'));
                    const draggedIndex = allItems.indexOf(draggedItem);
                    const targetIndex = allItems.indexOf(targetItem);
                    
                    if (draggedIndex < targetIndex) {
                        targetItem.parentNode.insertBefore(draggedItem, targetItem.nextSibling);
                    } else {
                        targetItem.parentNode.insertBefore(draggedItem, targetItem);
                    }
                    
                    // Save the new order
                    saveDeveloperOrder();
                }
            });
        }

        function saveDeveloperOrder() {
            const developersList = document.getElementById('developersList');
            if (!developersList) return;

            const items = Array.from(developersList.querySelectorAll('.developer-item'));
            const orderData = items.map(item => item.getAttribute('data-id'));

            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `developer_action=reorder&order_data=${JSON.stringify(orderData)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const popup = document.getElementById('popupMessage');
                    if (popup) {
                        popup.textContent = 'Developer order updated successfully!';
                        popup.className = 'popup-message show success';
                        setTimeout(() => {
                            popup.classList.remove('show');
                        }, 3000);
                    }
                }
            })
            .catch(error => {
                console.error('Error saving order:', error);
            });
        }

        // Initialize drag and drop when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            initializeContentOrderDragAndDrop();
        });

        // Content Order Functions
        function initializeContentOrderDragAndDrop() {
            const contentOrderList = document.getElementById('contentOrderList');
            if (!contentOrderList) return;

            let draggedItem = null;

            contentOrderList.addEventListener('dragstart', function(e) {
                if (e.target.closest('.content-order-item')) {
                    draggedItem = e.target.closest('.content-order-item');
                    e.target.closest('.content-order-item').style.opacity = '0.5';
                    e.dataTransfer.effectAllowed = 'move';
                }
            });

            contentOrderList.addEventListener('dragend', function(e) {
                if (draggedItem) {
                    draggedItem.style.opacity = '1';
                    draggedItem = null;
                }
            });

            contentOrderList.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });

            contentOrderList.addEventListener('drop', function(e) {
                e.preventDefault();
                const targetItem = e.target.closest('.content-order-item');
                
                if (draggedItem && targetItem && draggedItem !== targetItem) {
                    const allItems = Array.from(contentOrderList.querySelectorAll('.content-order-item'));
                    const draggedIndex = allItems.indexOf(draggedItem);
                    const targetIndex = allItems.indexOf(targetItem);
                    
                    if (draggedIndex < targetIndex) {
                        targetItem.parentNode.insertBefore(draggedItem, targetItem.nextSibling);
                    } else {
                        targetItem.parentNode.insertBefore(draggedItem, targetItem);
                    }
                    
                    // Save the new order
                    saveContentOrder();
                }
            });
        }

        function saveContentOrder() {
            const contentOrderList = document.getElementById('contentOrderList');
            if (!contentOrderList) return;

            const items = Array.from(contentOrderList.querySelectorAll('.content-order-item'));
            const orderData = items.map(item => ({
                type: item.getAttribute('data-type'),
                id: item.getAttribute('data-id')
            }));

            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `content_order_action=reorder&order_data=${JSON.stringify(orderData)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const popup = document.getElementById('popupMessage');
                    if (popup) {
                        popup.textContent = 'Content order updated successfully!';
                        popup.className = 'popup-message show success';
                        setTimeout(() => {
                            popup.classList.remove('show');
                        }, 3000);
                    }
                }
            })
            .catch(error => {
                console.error('Error saving content order:', error);
            });
        }

        function addContentToOrder(contentData) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="content_order_action" value="add">
                <input type="hidden" name="content_type" value="${contentData.type}">
                <input type="hidden" name="content_id" value="${contentData.id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        function removeContentFromOrder(contentData) {
            if (confirm(`Are you sure you want to remove "${contentData.type === 'post' ? contentData.id : 'Developer Section'}" from the layout?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="content_order_action" value="remove">
                    <input type="hidden" name="content_type" value="${contentData.type}">
                    <input type="hidden" name="content_id" value="${contentData.id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Make functions globally available
        window.closeModal = closeModal;
        window.openDeveloperModal = openDeveloperModal;
        
        window.confirmDelete = function(id) {
            const deleteBtn = document.querySelector('.delete-actions .deleting-btn');
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                deleteBtn.disabled = true;
            }
            
            // Redirect to delete
            window.location.href = `?delete=${id}`;
        };

        window.confirmDeveloperDelete = function(id) {
            const deleteBtn = document.querySelector('.delete-actions .deleting-btn');
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                deleteBtn.disabled = true;
            }
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="developer_action" value="delete">
                <input type="hidden" name="developer_id" value="${id}">
            `;
            document.body.appendChild(form);
            form.submit();
        };

        // Image Preview Modal functionality
        const imagePreviewModal = document.getElementById('imagePreviewModal');
        const modalImg = imagePreviewModal.querySelector('img');
        const closePreviewBtn = imagePreviewModal.querySelector('.close-preview');
        
        // Function to show image preview modal
        function showImagePreview(src) {
            modalImg.src = src;
            imagePreviewModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        // Function to close image preview modal
        function closeImagePreview() {
            imagePreviewModal.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        // Close modal on button click
        closePreviewBtn.addEventListener('click', closeImagePreview);
        
        // Close modal on outside click
        imagePreviewModal.addEventListener('click', function(e) {
            if (e.target === imagePreviewModal) {
                closeImagePreview();
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && imagePreviewModal.classList.contains('show')) {
                closeImagePreview();
            }
        });
        
        // Handle existing images in edit mode
        document.addEventListener('click', function(e) {
            if (e.target.closest('.img-preview-container img') || e.target.closest('.previewImageBlur')) {
                const container = e.target.closest('.img-preview-container');
                const img = container.querySelector('img');
                if (img && img.src) {
                    showImagePreview(img.src);
                }
            }
        });

        // Fixed function to handle existing image removal
        function handleExistingImageRemove(container) {
            const removeBtn = container.querySelector('.remove-preview');
            const removeImageFlag = document.getElementById('removeImageFlag');
            
            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Set the remove_image flag to true for existing images
                    if (removeImageFlag) {
                        removeImageFlag.value = 'true';
                    }
                    
                    // Remove the preview container with animation
                    container.style.opacity = '0';
                    container.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        if (container.parentNode) {
                            container.remove();
                        }
                    }, 300);
                });
            }
        }

        // Updated setupImagePreview function with proper flag handling
        function setupImagePreview(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const customFileDiv = input.closest('.custom-file');
            const fileInputLabel = customFileDiv.querySelector('.file-input-label');
            
            // Remove any existing preview containers for new uploads
            const existingPreviews = customFileDiv.querySelectorAll('.img-preview-container:not(.existing-image)');
            existingPreviews.forEach(preview => preview.remove());
            
            // Remove existing event listeners by cloning the input
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);
            
            // Create preview container function
            function createPreviewContainer() {
                const previewContainer = document.createElement('div');
                previewContainer.className = 'img-preview-container new-image';
                previewContainer.innerHTML = `
                    <div class="previewImageBlur">
                        <i class="fas fa-eye"></i>
                        <span>Preview image</span>
                    </div>
                    <img src="" alt="Preview">
                    <button type="button" class="remove-preview" title="Remove image">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                // Insert before the file input label
                customFileDiv.insertBefore(previewContainer, fileInputLabel);
                
                const previewImg = previewContainer.querySelector('img');
                const blurOverlay = previewContainer.querySelector('.previewImageBlur');
                const removeBtn = previewContainer.querySelector('.remove-preview');
                
                // Add click handlers for preview modal
                [previewImg, blurOverlay].forEach(element => {
                    element.addEventListener('click', function() {
                        if (previewImg.src && previewImg.src !== '') {
                            showImagePreview(previewImg.src);
                        }
                    });
                });
                
                // Add remove button handler for new images
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Clear the file input
                    newInput.value = '';
                    
                    // If this is edit mode, handle the remove image flag properly
                    if (inputId === 'editImage') {
                        const removeImageFlag = document.getElementById('removeImageFlag');
                        const existingContainer = document.querySelector('#existingImageContainer .existing-image');
                        
                        if (removeImageFlag && !existingContainer) {
                            const editForm = document.getElementById('editPostForm');
                            const originalImageData = editForm ? editForm.querySelector('input[name="id"]') : null;
                            if (originalImageData) {
                                // We're in edit mode and removing a new upload, keep existing remove flag state
                            }
                        }
                    }
                    
                    // Remove with animation
                    previewContainer.style.opacity = '0';
                    previewContainer.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        if (previewContainer.parentNode) {
                            previewContainer.remove();
                        }
                    }, 300);
                });
                
                return { previewContainer, previewImg };
            }
            
            // Add change event listener to the new input
            newInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                // Remove any existing preview containers for new uploads first
                const existingPreviews = customFileDiv.querySelectorAll('.img-preview-container.new-image');
                existingPreviews.forEach(preview => preview.remove());
                
                if (file) {
                    // Validate file size (5MB limit)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Image size should be less than 5MB');
                        newInput.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPEG, PNG, GIF, WebP)');
                        newInput.value = '';
                        return;
                    }
                    
                    // Create new preview container
                    const { previewContainer, previewImg } = createPreviewContainer();
                    
                    // Read and display the file
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.add('show');
                        
                        // If this is edit mode and we're uploading a new image
                        if (inputId === 'editImage') {
                            const removeImageFlag = document.getElementById('removeImageFlag');
                            
                            // Reset remove flag to false since we're uploading a new image
                            if (removeImageFlag) {
                                removeImageFlag.value = 'false';
                            }
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    })(); // End IIFE
    </script>
</body>
</html> 