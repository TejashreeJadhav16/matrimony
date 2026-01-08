<?php
/* =====================================================
   USER AUTH CHECK
   Protects user-only pages
===================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------- USER LOGIN CHECK ---------- */
if (!isset($_SESSION['user_id'])) {
    header("Location: /matrimony/auth/login.php");
    exit();
}

/* ---------- USER STATUS CHECK ---------- */
if (isset($_SESSION['status'])) {

    if ($_SESSION['status'] === 'pending') {
        header("Location: /matrimony/user/pending-verification.php");
        exit();
    }

    if ($_SESSION['status'] === 'suspended') {
        header("Location: /matrimony/user/account-suspended.php");
        exit();
    }
}
