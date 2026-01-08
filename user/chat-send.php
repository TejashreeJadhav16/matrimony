<?php
require_once '../includes/session.php';
require_once '../includes/auth-check.php';
require_once '../includes/config.php';

$user_id  = $_SESSION['user_id'];
$match_id = $_POST['match_id'] ?? 0;
$message  = trim($_POST['message'] ?? '');

if(!$match_id || !$message){
    header("Location: matches.php");
    exit;
}

/* VERIFY MATCH */
$stmt = $conn->prepare("
    SELECT id FROM matches
    WHERE id = ? AND (user_one = ? OR user_two = ?)
");
$stmt->execute([$match_id, $user_id, $user_id]);

if(!$stmt->fetch()){
    header("Location: matches.php");
    exit;
}

/* INSERT MESSAGE */
$conn->prepare("
    INSERT INTO chats (match_id, sender_id, message)
    VALUES (?, ?, ?)
")->execute([$match_id, $user_id, htmlspecialchars($message)]);

header("Location: chat.php?match_id=".$match_id);
exit;
