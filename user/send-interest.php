<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$sender_id   = $_SESSION['user_id'];
$receiver_id = (int)($_POST['receiver_id'] ?? 0);

/* BASIC VALIDATION */
if ($receiver_id <= 0 || $receiver_id === $sender_id) {
    $_SESSION['error'] = "Invalid user selected.";
    header("Location: search.php");
    exit;
}

/* CHECK RECEIVER EXISTS */
$checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
$checkUser->execute([$receiver_id]);

if (!$checkUser->fetch()) {
    $_SESSION['error'] = "User does not exist.";
    header("Location: search.php");
    exit;
}

/* CHECK DUPLICATE INTEREST */
$check = $conn->prepare("
    SELECT id FROM interests 
    WHERE sender_id = ? AND receiver_id = ?
");
$check->execute([$sender_id, $receiver_id]);

if ($check->rowCount() > 0) {
    $_SESSION['error'] = "Interest already sent.";
    header("Location: search.php");
    exit;
}

/* INSERT INTEREST */
$conn->prepare("
    INSERT INTO interests (sender_id, receiver_id, status)
    VALUES (?, ?, 'sent')
")->execute([$sender_id, $receiver_id]);

$_SESSION['success'] = "❤️ Interest sent successfully!";
header("Location: search.php");
exit;
