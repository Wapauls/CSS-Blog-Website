<?php
require_once '../config.php';

// Handle flash messages
$flash = getFlashMessage();
$message = $flash['message'] ?? '';
$message_type = $flash['type'] ?? '';
$message_icon = $message_type === 'success' ? 'fa-check-circle' : ($message_type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');

// Handle embedded image uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'embedded') {
    try {
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) throw new Exception('Invalid security token.');
        
        $image_type = trim($_POST['image_type']);
        $image = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['image']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception('Please select a valid image file (JPEG, PNG, GIF, WebP)');
            }
            
            ensureUploadDirectory('../uploads/');
            $image = generateSecureFilename($_FILES['image']['name']);
            $target = '../uploads/' . $image;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                throw new Exception('Failed to save uploaded image.');
            }
        }
        
        if ($image) {
            if (isset($_POST['id']) && $_POST['id']) {
                // Update existing image
                $id = intval($_POST['id']);
                $stmt = $conn->prepare('SELECT image FROM about WHERE id = ?');
                if ($stmt) {
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $current_image = null;
                    if ($row = $result->fetch_assoc()) {
                        $current_image = $row['image'];
                    }
                    $stmt->close();
                    
                    if ($current_image && file_exists('../uploads/' . $current_image)) {
                        unlink('../uploads/' . $current_image);
                    }
                    
                    $stmt = $conn->prepare('UPDATE about SET image = ? WHERE id = ?');
                    if ($stmt) {
                        $stmt->bind_param('si', $image, $id);
                        if ($stmt->execute()) {
                            redirectWithMessage($_SERVER['PHP_SELF'], 'Embedded image updated successfully!', 'success');
                        } else {
                            $message = 'Update failed: ' . $stmt->error;
                        }
                        $stmt->close();
                    }
                }
            } else {
                // Create new embedded image entry
                $title = ucfirst(str_replace('_', ' ', $image_type)) . ' Image';
                $content = 'Embedded image for ' . $image_type;
                $category = 'embedded';
                $section = $image_type;
                
                $stmt = $conn->prepare('INSERT INTO about (title, content, image) VALUES (?, ?, ?)');
                if ($stmt) {
                    $stmt->bind_param('sss', $title, $content, $image);
                    if ($stmt->execute()) {
                        redirectWithMessage($_SERVER['PHP_SELF'], 'Embedded image uploaded successfully!', 'success');
                    } else {
                        $message = 'Error: ' . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = 'Error preparing statement: ' . $conn->error;
                }
            }
        } else {
            $message = 'Please select a valid image file.';
        }
    } catch (Exception $e) {
        logError('Embedded image upload error: ' . $e->getMessage(), $_POST);
        redirectWithMessage($_SERVER['PHP_SELF'], $e->getMessage(), 'error');
    }
}

// Show message from redirect
if (isset($_GET['msg'])) {
    switch($_GET['msg']) {
        case 'added':
            $message = 'Embedded image uploaded successfully!';
            $message_type = 'success';
            $message_icon = 'fa-check-circle';
            break;
        case 'updated':
            $message = 'Embedded image updated successfully!';
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

// Get current page for active state
$current_page = 'about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - About Embedded Images</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        var csrfToken = "<?= e(generateCSRFToken()) ?>";
    </script>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div id="popupMessage" class="popup-message<?= $message ? ' show ' . ($message_type ?? '') : '' ?>">
            <?php if ($message): ?>
                <i class="fas <?= $message_icon ?? 'fa-info-circle' ?>"></i>
                <?= e($message) ?>
            <?php endif; ?>
        </div>
        
        <h1>About Page - Embedded Images Management</h1>
        <p class="section-description">Upload or replace images that appear embedded within the About page content. These images are part of the static content and are not managed as posts.</p>

        <div class="embedded-images-section">
            <h3>Manage Embedded Images</h3>
            <p>Upload or replace images that appear in the About page content.</p>
            
            <div class="embedded-images-grid">
                <!-- Hero Image -->
                <div class="embedded-image-card">
                    <h4>Hero Image (BLEZY.png)</h4>
                    <div class="image-upload-area" data-image-type="hero">
                        <?php
                        $hero_image = $conn->query('SELECT * FROM about WHERE category = "embedded" AND section = "hero" LIMIT 1');
                        if ($hero_image && $hero_image->num_rows > 0) {
                            $hero = $hero_image->fetch_assoc();
                        ?>
                            <div class="current-image">
                                <img src="../uploads/<?= htmlspecialchars($hero['image']) ?>" alt="Hero Image">
                                <button class="replace-image-btn" data-image-type="hero" data-image-id="<?= $hero['id'] ?>">
                                    <i class="fas fa-sync-alt"></i> Replace
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>No image uploaded</p>
                                <button class="upload-image-btn" data-image-type="hero">
                                    <i class="fas fa-plus"></i> Upload Image
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Poster Image -->
                <div class="embedded-image-card">
                    <h4>Poster Image (blezy what cs.jpg)</h4>
                    <div class="image-upload-area" data-image-type="poster">
                        <?php
                        $poster_image = $conn->query('SELECT * FROM about WHERE category = "embedded" AND section = "poster" LIMIT 1');
                        if ($poster_image && $poster_image->num_rows > 0) {
                            $poster = $poster_image->fetch_assoc();
                        ?>
                            <div class="current-image">
                                <img src="../uploads/<?= htmlspecialchars($poster['image']) ?>" alt="Poster Image">
                                <button class="replace-image-btn" data-image-type="poster" data-image-id="<?= $poster['id'] ?>">
                                    <i class="fas fa-sync-alt"></i> Replace
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>No image uploaded</p>
                                <button class="upload-image-btn" data-image-type="poster">
                                    <i class="fas fa-plus"></i> Upload Image
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- BSCS Image -->
                <div class="embedded-image-card">
                    <h4>BSCS Section Image (what is BSCS.jpg)</h4>
                    <div class="image-upload-area" data-image-type="bscs">
                        <?php
                        $bscs_image = $conn->query('SELECT * FROM about WHERE category = "embedded" AND section = "bscs" LIMIT 1');
                        if ($bscs_image && $bscs_image->num_rows > 0) {
                            $bscs = $bscs_image->fetch_assoc();
                        ?>
                            <div class="current-image">
                                <img src="../uploads/<?= htmlspecialchars($bscs['image']) ?>" alt="BSCS Image">
                                <button class="replace-image-btn" data-image-type="bscs" data-image-id="<?= $bscs['id'] ?>">
                                    <i class="fas fa-sync-alt"></i> Replace
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>No image uploaded</p>
                                <button class="upload-image-btn" data-image-type="bscs">
                                    <i class="fas fa-plus"></i> Upload Image
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Enrollment Image -->
                <div class="embedded-image-card">
                    <h4>Enrollment Section Image (enrollment.jpg)</h4>
                    <div class="image-upload-area" data-image-type="enrollment">
                        <?php
                        $enrollment_image = $conn->query('SELECT * FROM about WHERE category = "embedded" AND section = "enrollment" LIMIT 1');
                        if ($enrollment_image && $enrollment_image->num_rows > 0) {
                            $enrollment = $enrollment_image->fetch_assoc();
                        ?>
                            <div class="current-image">
                                <img src="../uploads/<?= htmlspecialchars($enrollment['image']) ?>" alt="Enrollment Image">
                                <button class="replace-image-btn" data-image-type="enrollment" data-image-id="<?= $enrollment['id'] ?>">
                                    <i class="fas fa-sync-alt"></i> Replace
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>No image uploaded</p>
                                <button class="upload-image-btn" data-image-type="enrollment">
                                    <i class="fas fa-plus"></i> Upload Image
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Edusuite Image -->
                <div class="embedded-image-card">
                    <h4>Edusuite Section Image (edusuite.png)</h4>
                    <div class="image-upload-area" data-image-type="edusuite">
                        <?php
                        $edusuite_image = $conn->query('SELECT * FROM about WHERE category = "embedded" AND section = "edusuite" LIMIT 1');
                        if ($edusuite_image && $edusuite_image->num_rows > 0) {
                            $edusuite = $edusuite_image->fetch_assoc();
                        ?>
                            <div class="current-image">
                                <img src="../uploads/<?= htmlspecialchars($edusuite['image']) ?>" alt="Edusuite Image">
                                <button class="replace-image-btn" data-image-type="edusuite" data-image-id="<?= $edusuite['id'] ?>">
                                    <i class="fas fa-sync-alt"></i> Replace
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>No image uploaded</p>
                                <button class="upload-image-btn" data-image-type="edusuite">
                                    <i class="fas fa-plus"></i> Upload Image
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload Modal -->
        <div id="imageUploadModal" class="modal">
            <div class="modal-content">
                <button type="button" class="close-modal">&times;</button>
                <h2><i class="fas fa-cloud-upload-alt"></i> Upload Embedded Image</h2>
                <form id="imageUploadForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= e(generateCSRFToken()) ?>">
                    <input type="hidden" name="type" value="embedded">
                    <input type="hidden" name="image_type" id="modalImageType">
                    <input type="hidden" name="id" id="modalImageId">
                    
                    <div class="form-group">
                        <label for="imageFile"><i class="fas fa-image"></i> Select Image</label>
                        <input type="file" id="imageFile" name="image" accept="image/*" required>
                        <p class="help-text">Supported formats: JPEG, PNG, GIF, WebP (Max 5MB)</p>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-upload"></i> Upload Image
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Popup message auto-hide
        const popup = document.getElementById('popupMessage');
        if (popup && popup.textContent.trim() !== '') {
            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }

        // Modal functionality
        const modal = document.getElementById('imageUploadModal');
        const closeModal = document.querySelector('.close-modal');
        const uploadBtns = document.querySelectorAll('.upload-image-btn, .replace-image-btn');

        // Open modal
        uploadBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const imageType = this.getAttribute('data-image-type');
                const imageId = this.getAttribute('data-image-id') || '';
                
                document.getElementById('modalImageType').value = imageType;
                document.getElementById('modalImageId').value = imageId;
                
                modal.classList.add('show');
            });
        });

        // Close modal
        closeModal.addEventListener('click', function() {
            modal.classList.remove('show');
        });

        // Close modal on outside click
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                modal.classList.remove('show');
            }
        });
    </script>
</body>
</html> 