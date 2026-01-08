<?php
/* =====================================================
   SESSION START
===================================================== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

/* =====================================================
   SESSION FIXATION PROTECTION
===================================================== */
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

/* =====================================================
   REMEMBER ME (AUTO LOGIN)
===================================================== */
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    $stmt = $conn->prepare("
        SELECT id FROM users WHERE remember_token = ?
    ");
    $stmt->execute([$_COOKIE['remember_token']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'user';
    }
}

/* =====================================================
   SESSION TIMEOUT (30 MIN)
===================================================== */
$timeout = 1800;

if (isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {

    session_unset();
    session_destroy();

    header("Location: /matrimony/auth/login.php?timeout=1");
    exit;
}

$_SESSION['LAST_ACTIVITY'] = time();

/* =====================================================
   LOGOUT HELPER
===================================================== */
function destroySession() {

    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }

    session_unset();
    session_destroy();

    header("Location: /matrimony/auth/login.php");
    exit;
}
