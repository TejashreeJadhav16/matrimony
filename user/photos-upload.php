<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: photos.php");
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Please select a photo.";
    header("Location: photos.php");
    exit;
}

$file = $_FILES['photo'];

/* =====================
   VALIDATION
===================== */
$allowed = ['jpg','jpeg','png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    $_SESSION['error'] = "Only JPG, JPEG, PNG files allowed.";
    header("Location: photos.php");
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    $_SESSION['error'] = "Photo must be under 5MB.";
    header("Location: photos.php");
    exit;
}

/* =====================
   FILE SYSTEM PATH
===================== */
$uploadDir = __DIR__ . '/../uploads/photos/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$photoName = $user_id . "_photo_" . time() . "." . $ext;
$destination = $uploadDir . $photoName;

/* =====================
   MOVE FILE
===================== */
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $_SESSION['error'] = "Upload failed. Check folder permissions.";
    header("Location: photos.php");
    exit;
}

/* =====================
   SAVE IN DATABASE
===================== */
$stmt = $conn->prepare("
    INSERT INTO photos (user_id, photo_path, status)
    VALUES (?, ?, 'pending')
");
$stmt->execute([$user_id, $photoName]);

header("Location: photos.php?uploaded=1");
exit;
