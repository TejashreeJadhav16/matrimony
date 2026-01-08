<?php
require_once '../includes/config.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';

/* =========================
   ONLY POST REQUEST
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

/* =========================
   INPUTS
========================= */
$login_id = sanitize($_POST['login_id']);
$password = $_POST['password'];

/* =========================
   1️⃣ ADMIN LOGIN
========================= */
$stmt = $conn->prepare("
    SELECT * FROM admin_users 
    WHERE username = ? OR email = ?
    LIMIT 1
");
$stmt->execute([$login_id, $login_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && md5($password) === $admin['password']) {

    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['role']     = $admin['role']; // admin / moderator

    redirect('../admin/dashboard.php');
}

/* =========================
   2️⃣ USER LOGIN
========================= */
$stmt = $conn->prepare("
    SELECT * FROM users
    WHERE email = ? OR mobile = ?
    LIMIT 1
");
$stmt->execute([$login_id, $login_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Invalid login credentials";
    redirect('login.php');
}

/* =========================
   PASSWORD CHECK
========================= */
$isValidPassword = false;

/* New secure hash */
if (password_verify($password, $user['password'])) {
    $isValidPassword = true;
}

/* Old MD5 fallback */
if (!$isValidPassword && md5($password) === $user['password']) {
    $isValidPassword = true;

    /* Auto-upgrade password */
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$newHash, $user['id']]);
}

if (!$isValidPassword) {
    $_SESSION['error'] = "Invalid login credentials";
    redirect('login.php');
}

/* =========================
   🔐 PAYMENT CHECK (COMPULSORY)
========================= */
$stmt = $conn->prepare("
    SELECT payment_status
    FROM payments
    WHERE user_id = ?
    ORDER BY id DESC
    LIMIT 1
");
$stmt->execute([$user['id']]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment || $payment['payment_status'] !== 'success') {
    $_SESSION['error'] = "Payment required to activate your account";
    redirect('../payment-init.php');
}

/* =========================
   STATUS CHECK
========================= */
if ($user['status'] !== 'approved') {
    $_SESSION['error'] = "Profile pending admin approval";
    redirect('login.php');
}

/* =========================
   USER LOGIN SUCCESS
========================= */
$_SESSION['user_id'] = $user['id'];
$_SESSION['role']    = 'user';
$_SESSION['status']  = $user['status'];

/* =========================
   REMEMBER ME
========================= */
if (isset($_POST['remember'])) {

    $token = bin2hex(random_bytes(32));

    setcookie(
        'remember_token',
        $token,
        time() + (86400 * 30),
        "/",
        "",
        false,
        true
    );

    $stmt = $conn->prepare("
        UPDATE users SET remember_token = ?
        WHERE id = ?
    ");
    $stmt->execute([$token, $user['id']]);
}

/* =========================
   REDIRECT TO DASHBOARD
========================= */
redirect('../user/dashboard.php');
