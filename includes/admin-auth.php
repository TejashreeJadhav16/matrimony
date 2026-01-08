<?php
/* =====================================================
   ADMIN AUTH CHECK
   Protects admin-only pages
===================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------- ADMIN LOGIN CHECK ---------- */
if (!isset($_SESSION['admin_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: /matrimony/auth/login.php");
    exit();
}
