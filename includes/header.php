<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Role checks
$isUser  = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['admin_id']) && ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Karadi Samaaj Matrimony</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Main CSS -->
    <link rel="stylesheet" href="/matrimony/assets/css/main.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- ===================== TOP BAR ===================== -->
<div class="top-bar">
    <div class="container">
        <span>✔ Trusted Community Matrimony Platform</span>

        <!-- Google Translate -->
        <div class="lang-switch">
            <div id="google_translate_element"></div>
        </div>
    </div>
</div>

<!-- ===================== HEADER ===================== -->
<header class="main-header">
    <div class="container header-flex">

        <!-- LOGO -->
        <div class="logo">
            <a href="/matrimony/index.php">
                <strong>Karadi Samaaj</strong> Matrimony
            </a>
        </div>

        <!-- MOBILE MENU BUTTON -->
        <button class="menu-toggle" id="menuToggle">☰</button>

        <!-- NAVIGATION -->
        <nav class="nav-menu" id="navMenu">

            <!-- PUBLIC LINKS -->
            <a href="/matrimony/index.php">Home</a>
            <a href="/matrimony/about.php">About</a>
            <a href="/matrimony/how-it-works.php">How It Works</a>
            <a href="/matrimony/contact.php">Contact</a>

            <?php if ($isAdmin): ?>
                <!-- ADMIN -->
                <a href="/matrimony/admin/dashboard.php">Admin Dashboard</a>
                <a href="/matrimony/auth/logout.php" class="btn-nav outline">Logout</a>

            <?php elseif ($isUser): ?>
                <!-- USER -->
                <a href="/matrimony/user/dashboard.php">Dashboard</a>
                <a href="/matrimony/auth/logout.php" class="btn-nav outline">Logout</a>

            <?php else: ?>
                <!-- GUEST -->
                <a href="/matrimony/auth/register.php" class="btn-nav">Register</a>
                <a href="/matrimony/auth/login.php" class="btn-nav outline">Login</a>
            <?php endif; ?>

        </nav>

    </div>
</header>

<!-- ===================== MOBILE MENU SCRIPT ===================== -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const navMenu = document.getElementById("navMenu");

    if (menuToggle && navMenu) {
        menuToggle.addEventListener("click", function () {
            navMenu.classList.toggle("show");
        });
    }
});
</script>

<!-- ===================== GOOGLE TRANSLATE SCRIPT ===================== -->
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement(
        {
            pageLanguage: 'en',
            includedLanguages: 'en,mr',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        },
        'google_translate_element'
    );
}
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
