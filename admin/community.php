<?php
require_once '../config.php';
$message = '';
$action_type = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM community WHERE id=?');
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
    $result = $conn->query("SELECT * FROM community WHERE id=$id");
    $edit_entry = $result->fetch_assoc();
}

// Handle Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only proceed if title and content are set
    if (isset($_POST['title']) && isset($_POST['content'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $section = trim($_POST['section'] ?? '');
        $year = intval($_POST['year'] ?? 0);
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
            $stmt = $conn->prepare('SELECT image FROM community WHERE id = ?');
            if ($stmt) {
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
                    $stmt = $conn->prepare('UPDATE community SET title = ?, content = ?, section = ?, year = ?, image = NULL WHERE id = ?');
                    if ($stmt) {
                        $stmt->bind_param('sssii', $title, $content, $section, $year, $id);
                        if ($stmt->execute()) {
                            header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=updated');
                            exit();
                        } else {
                            $message = 'Update failed: ' . $stmt->error;
                        }
                        $stmt->close();
                    }
                } else if ($image) {
                    // New image uploaded case
                    if ($current_image && file_exists('../uploads/' . $current_image)) {
                        unlink('../uploads/' . $current_image);
                    }
                    $stmt = $conn->prepare('UPDATE community SET title = ?, content = ?, section = ?, year = ?, image = ? WHERE id = ?');
                    if ($stmt) {
                        $stmt->bind_param('sssisi', $title, $content, $section, $year, $image, $id);
                        if ($stmt->execute()) {
                            header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=updated');
                            exit();
                        } else {
                            $message = 'Update failed: ' . $stmt->error;
                        }
                        $stmt->close();
                    }
                } else {
                    // No image change case
                    $stmt = $conn->prepare('UPDATE community SET title = ?, content = ?, section = ?, year = ? WHERE id = ?');
                    if ($stmt) {
                        $stmt->bind_param('sssii', $title, $content, $section, $year, $id);
                        if ($stmt->execute()) {
                            header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=updated');
                            exit();
                        } else {
                            $message = 'Update failed: ' . $stmt->error;
                        }
                        $stmt->close();
                    }
                }
            }
        } else {
            // Create new entry
            $stmt = $conn->prepare('INSERT INTO community (title, content, section, year, image) VALUES (?, ?, ?, ?, ?)');
            if ($stmt) {
                $stmt->bind_param('sssis', $title, $content, $section, $year, $image);
                if ($stmt->execute()) {
                    header('Location: ' . $_SERVER['PHP_SELF'] . '?msg=added');
                    exit();
                } else {
                    $message = 'Error: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = 'Error preparing statement: ' . $conn->error;
            }
        }
    }
}

// Show message from redirect
if (isset($_GET['msg'])) {
    switch($_GET['msg']) {
        case 'added':
            $message = 'Community entry added successfully!';
            $action_type = 'create';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'updated':
            $message = 'Community entry updated successfully!';
            $action_type = 'update';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'deleted':
            $message = 'Community entry deleted successfully!';
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
$entries = $conn->query('SELECT * FROM community ORDER BY created_at DESC');

// Get current page for active state
$current_page = 'community';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Community</title>
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
        
        <h1>Community Management</h1>
        <button class="stat-link" id="openCreateModal">
            <i class="fas fa-plus"></i> Create New Community Entry
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
                        <span class="post-title"><?= htmlspecialchars($row['title']) ?><div class="post-meta">
                            <span class="post-year"><?= $row['year'] ?></span>
                            <span>-</span>
                            <span class="post-section"><?= htmlspecialchars($row['section']) ?></span>
                            </div></span>
                        <div class="post-info">
                            
                            
                            <?php
                            $content = strip_tags($row['content']);
                            $truncated = mb_substr($content, 0, 105);
                            if (mb_strlen($content) > 105) {
                                $truncated .= '...';
                            }
                            ?>
                            <span class="post-content"><?= htmlspecialchars($truncated) ?></span>
                        </div>
                        <div class="post-actions">
                            <button class="edit-btn" data-id="<?= $row['id'] ?>" data-title="<?= htmlspecialchars($row['title']) ?>" data-content="<?= htmlspecialchars($row['content']) ?>" data-section="<?= htmlspecialchars($row['section']) ?>" data-year="<?= $row['year'] ?>" data-image="<?= htmlspecialchars($row['image']) ?>">
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
                    <i class="fas fa-users"></i>
                    <p>No community entries found. Start by creating your first entry!</p>
                    <button class="stat-link" id="openCreateModalEmpty">
                        <i class="fas fa-plus"></i> Create Your First Entry
                    </button>
                </div>
            <?php endif; ?>
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
        if (window.communityManagerInitialized) {
            return;
        }
        window.communityManagerInitialized = true;

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
                    section: btn.getAttribute('data-section'),
                    year: btn.getAttribute('data-year'),
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
                        <h2><i class="fas fa-plus-circle"></i> Create Community Entry</h2>
                        <form id="createPostForm" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="createTitle"><i class="fas fa-heading"></i> Title</label>
                                <input type="text" id="createTitle" name="title" placeholder="Enter community title" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group form-col">
                                    <label for="createYear"><i class="fas fa-calendar"></i> Year</label>
                                    <input type="number" id="createYear" name="year" placeholder="Enter year" min="1" max="4" required>
                                </div>
                                <div class="form-group form-col">
                                    <label for="createSection"><i class="fas fa-tag"></i> Section</label>
                                    <input type="text" id="createSection" name="section" placeholder="Enter section" required>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="createContent"><i class="fas fa-align-left"></i> Content</label>
                                <textarea id="createContent" name="content" placeholder="Write community content here..." required></textarea>
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
                        <h2><i class="fas fa-edit"></i> Edit Community Entry</h2>
                        <form id="editPostForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="${data.id}">
                            <input type="hidden" name="remove_image" value="false" id="removeImageFlag">
                            <div class="form-group">
                                <label for="editTitle"><i class="fas fa-heading"></i> Title</label>
                                <input type="text" id="editTitle" name="title" value="${data.title}" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group form-col">
                                    <label for="editYear"><i class="fas fa-calendar"></i> Year</label>
                                    <input type="number" id="editYear" name="year" value="${data.year}" min="1" max="4" required>
                                </div>
                                <div class="form-group form-col">
                                    <label for="editSection"><i class="fas fa-tag"></i> Section</label>
                                    <input type="text" id="editSection" name="section" value="${data.section}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="editContent"><i class="fas fa-align-left"></i> Content</label>
                                <textarea id="editContent" name="content" required>${data.content}</textarea>
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
                        <h2><i class="fas fa-trash-alt"></i> Delete Community Entry</h2>
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

        // Make functions globally available
        window.closeModal = closeModal;
        
        window.confirmDelete = function(id) {
            const deleteBtn = document.querySelector('.delete-actions .deleting-btn');
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                deleteBtn.disabled = true;
            }
            
            // Redirect to delete
            window.location.href = `?delete=${id}`;
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