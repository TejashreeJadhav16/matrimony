<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: documents.php");
    exit;
}

$docType = $_POST['document_type'] ?? '';
$file = $_FILES['document'] ?? null;

/* =====================
   VALIDATION
===================== */
$allowed = ['pdf','jpg','jpeg','png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    $_SESSION['error'] = "Invalid file type";
    header("Location: documents.php");
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    $_SESSION['error'] = "File too large (max 5MB)";
    header("Location: documents.php");
    exit;
}

/* =====================
   CORRECT UPLOAD PATH
===================== */
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/matrimony/uploads/documents/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = $user_id . "_" . $docType . "_" . time() . "." . $ext;
$destination = $uploadDir . $filename;

/* =====================
   MOVE FILE (CRITICAL)
===================== */
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $_SESSION['error'] = "Document upload failed";
    header("Location: documents.php");
    exit;
}

/* =====================
   SAVE IN DATABASE
===================== */
$stmt = $conn->prepare("
    INSERT INTO documents (user_id, document_type, document_path, verified)
    VALUES (?, ?, ?, 'pending')
");
$stmt->execute([$user_id, $docType, $filename]);

$_SESSION['success'] = "Document uploaded successfully. Await admin verification.";
header("Location: documents.php");
exit;
