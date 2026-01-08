<?php
/* =====================================================
   COMMON AUTH CHECK
===================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =====================================================
   CHECK LOGIN (USER OR ADMIN)
===================================================== */

$isUser  = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['admin_id']) && ($_SESSION['role'] ?? '') === 'admin';

if (!$isUser && !$isAdmin) {
    header("Location: /matrimony/auth/login.php");
    exit();
}

/* =====================================================
   ROLE BASED ACCESS (OPTIONAL)
   Usage before include:
   $required_role = 'admin';
===================================================== */

if (isset($required_role)) {
    if (($_SESSION['role'] ?? '') !== $required_role) {
        header("Location: /matrimony/index.php");
        exit();
    }
}

/* =====================================================
   USER STATUS CHECK (ONLY FOR USERS)
===================================================== */

if ($isUser && isset($_SESSION['status'])) {

    if ($_SESSION['status'] === 'pending') {
        header("Location: /matrimony/user/pending-verification.php");
        exit();
    }

    if ($_SESSION['status'] === 'suspended') {
        header("Location: /matrimony/user/account-suspended.php");
        exit();
    }
}
