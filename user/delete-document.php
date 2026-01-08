<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

$id   = $_POST['id'] ?? 0;
$path = $_POST['path'] ?? '';

$stmt = $conn->prepare("
SELECT document_path FROM documents
WHERE id=? AND user_id=?
");
$stmt->execute([$id,$user_id]);
$doc = $stmt->fetch();

if(!$doc){
    $_SESSION['error']="Document not found";
    header("Location: documents.php"); exit;
}

$file = "../uploads/documents/".$doc['document_path'];
if(file_exists($file)) unlink($file);

$conn->prepare("DELETE FROM documents WHERE id=?")->execute([$id]);

$_SESSION['success']="Document deleted successfully";
header("Location: documents.php");
exit;
