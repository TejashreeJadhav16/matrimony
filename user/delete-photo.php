<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: photos.php");
    exit;
}

$photoId = $_POST['photo_id'] ?? 0;

if (!$photoId) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: photos.php");
    exit;
}

/* VERIFY OWNERSHIP */
$stmt = $conn->prepare("
    SELECT photo_path 
    FROM photos 
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$photoId, $user_id]);
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$photo) {
    $_SESSION['error'] = "Photo not found or access denied.";
    header("Location: photos.php");
    exit;
}

/* DELETE FILE */
$filePath = "../uploads/photos/" . $photo['photo_path'];
if (file_exists($filePath)) {
    unlink($filePath);
}

/* DELETE DB RECORD */
$conn->prepare("DELETE FROM photos WHERE id = ?")->execute([$photoId]);

$_SESSION['success'] = "Photo deleted successfully.";
header("Location: photos.php");
exit;
