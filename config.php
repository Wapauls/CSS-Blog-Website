<?php
// ==================================================
// CONFIG.PHP IMPROVEMENTS
// ==================================================

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Database configuration with error handling
$servername = "sql201.infinityfree.com";
$username = "if0_39690732";
$password = "rKHfALTWwY8";
$dbname = "if0_39690732_css_blog";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    // Set charset to prevent character encoding issues
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    // Display user-friendly error message
    die("Sorry, we're experiencing technical difficulties. Please try again later.");
}

// ==================================================
// SESSION MANAGEMENT (if needed)
// ==================================================
session_start();

// ==================================================
// UTILITY FUNCTIONS
// ==================================================

function sanitizeInput($data) {
    if (is_null($data)) return '';
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function validateImageFile($file) {
    $errors = [];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = "File is too large.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errors[] = "File upload was interrupted.";
                break;
            case UPLOAD_ERR_NO_FILE:
                // This is okay - no file uploaded
                break;
            default:
                $errors[] = "Unknown upload error.";
        }
        return $errors;
    }
    // Check file size (5MB limit)
    if ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = "File size must be less than 5MB.";
    }
    // Check file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime_type, $allowed_types)) {
        $errors[] = "Only JPEG, PNG, GIF, and WebP images are allowed.";
    }
    // Check if it's actually an image
    $image_info = getimagesize($file['tmp_name']);
    if ($image_info === false) {
        $errors[] = "File is not a valid image.";
    }
    return $errors;
}

function generateSecureFilename($original_name) {
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    return uniqid('img_', true) . '.' . $extension;
}

function ensureUploadDirectory($path) {
    if (!is_dir($path)) {
        if (!mkdir($path, 0755, true)) {
            throw new Exception("Failed to create upload directory.");
        }
    }
    if (!is_writable($path)) {
        throw new Exception("Upload directory is not writable.");
    }
}

function safeDeleteFile($filepath) {
    if (file_exists($filepath) && is_file($filepath)) {
        return unlink($filepath);
    }
    return true; // File doesn't exist, so "deletion" successful
}

function logError($message, $context = []) {
    $log_message = date('Y-m-d H:i:s') . " - " . $message;
    if (!empty($context)) {
        $log_message .= " | Context: " . json_encode($context);
    }
    error_log($log_message);
}

function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: $url");
    exit();
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// ==================================================
// CSRF PROTECTION
// ==================================================

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ==================================================
// TEMPLATE HELPERS
// ==================================================

function e($value, $default = '') {
    return htmlspecialchars($value ?? $default, ENT_QUOTES, 'UTF-8');
}

function formatDate($date, $format = 'F j, Y') {
    if (empty($date)) return '';
    try {
        return date($format, strtotime($date));
    } catch (Exception $e) {
        return '';
    }
}

function getImagePath($image, $default = null) {
    if (empty($image)) return $default;
    $path = __DIR__ . '/../uploads/' . $image;
    return file_exists($path) ? $path : $default;
}
?> 