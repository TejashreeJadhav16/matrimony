<?php
/* =====================================================
   COMMON FUNCTIONS
===================================================== */

require_once __DIR__ . '/config.php';

/* =====================================================
   SANITIZE INPUT
===================================================== */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/* =====================================================
   PASSWORD FUNCTIONS
===================================================== */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/* =====================================================
   REDIRECT
===================================================== */
function redirect($url) {
    header("Location: $url");
    exit;
}

/* =====================================================
   USER HELPERS
===================================================== */
function getUserByLogin($login) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT * FROM users 
        WHERE email = ? OR mobile = ?
        LIMIT 1
    ");
    $stmt->execute([$login, $login]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserById($id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/* =====================================================
   ROLE CHECK
===================================================== */
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/* =====================================================
   INTEREST & MATCH
===================================================== */
function sendInterest($senderId, $receiverId) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO interests (sender_id, receiver_id)
        VALUES (?, ?)
    ");
    return $stmt->execute([$senderId, $receiverId]);
}

function isMatch($user1, $user2) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT id FROM matches
        WHERE (user_one = ? AND user_two = ?)
           OR (user_one = ? AND user_two = ?)
    ");
    $stmt->execute([$user1, $user2, $user2, $user1]);
    return $stmt->rowCount() > 0;
}

function createMatch($user1, $user2) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO matches (user_one, user_two)
        VALUES (?, ?)
    ");
    return $stmt->execute([$user1, $user2]);
}

/* =====================================================
   BLOCK USER
===================================================== */
function blockUser($blockerId, $blockedId) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO blocked_users (blocker_id, blocked_id)
        VALUES (?, ?)
    ");
    return $stmt->execute([$blockerId, $blockedId]);
}

function isBlocked($userId, $otherId) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT id FROM blocked_users
        WHERE blocker_id = ? AND blocked_id = ?
    ");
    $stmt->execute([$userId, $otherId]);
    return $stmt->rowCount() > 0;
}
