<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<style>
/* =====================
   SIDEBAR BASE
===================== */
.user-sidebar {
    width: 260px;
    background: linear-gradient(180deg, #5a0c5f, #3f0442);
    color: #fff;
    min-height: 100vh;
    padding: 20px;
    display: flex;
    flex-direction: column;
    position: relative;
}

/* LOGO */
.user-sidebar .logo {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 25px;
    text-align: center;
}

/* MAIN MENU ITEM */
.menu-item {
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 14px;
    color: #e8d8ee;
    margin-bottom: 6px;
    text-decoration: none;
}

.menu-item:hover {
    background: rgba(255,255,255,0.15);
}

/* ARROW */
.menu-item span {
    transition: transform 0.3s ease;
}

/* SUBMENU */
.submenu {
    display: none;
    margin-left: 10px;
    margin-bottom: 10px;
}

.submenu a {
    display: block;
    padding: 9px 14px;
    font-size: 13px;
    color: #d9c5df;
    border-radius: 8px;
    margin: 4px 0;
    text-decoration: none;
}

.submenu a:hover,
.submenu a.active {
    background: #ffffff;
    color: #5a0c5f;
    font-weight: 600;
}

/* OPEN STATE */
.menu.open .submenu {
    display: block;
}

.menu.open .menu-item span {
    transform: rotate(180deg);
}

/* LOGOUT */
.logout {
    margin-top: auto;
    border-top: 1px solid rgba(255,255,255,0.25);
    padding-top: 15px;
}

/* =====================
   MOBILE & TABLET
===================== */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1200;
    background: #5a0c5f;
    color: #fff;
    border: none;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
}

.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1100;
}

@media (max-width: 1024px) {
    .user-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1200;
    }

    .user-sidebar.show {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: block;
    }

    .sidebar-overlay.show {
        display: block;
    }
}
</style>

<!-- MOBILE BUTTON -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<aside class="user-sidebar">

    <div class="logo">Karadi Matrimony</div>

    <!-- DASHBOARD -->
    <a href="dashboard.php" class="menu-item <?= $currentPage=='dashboard.php'?'active':'' ?>">
        🏠 Dashboard
    </a>

    <!-- PROFILE -->
    <div class="menu <?= in_array($currentPage,[
        'my-profile.php','edit-profile.php','family-details.php',
        'education-employment.php','horoscope.php','expectations.php'
    ])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            👤 Profile <span>▾</span>
        </div>
        <div class="submenu">
            <a href="my-profile.php" class="<?= $currentPage=='my-profile.php'?'active':'' ?>">My Profile</a>
            <a href="edit-profile.php" class="<?= $currentPage=='edit-profile.php'?'active':'' ?>">Edit Profile</a>
            <a href="family-details.php" class="<?= $currentPage=='family-details.php'?'active':'' ?>">Family</a>
            <a href="education-employment.php" class="<?= $currentPage=='education-employment.php'?'active':'' ?>">Education</a>
            <a href="horoscope.php" class="<?= $currentPage=='horoscope.php'?'active':'' ?>">Horoscope</a>
            <a href="expectations.php" class="<?= $currentPage=='expectations.php'?'active':'' ?>">Expectations</a>
        </div>
    </div>

    <!-- MEDIA -->
    <div class="menu <?= in_array($currentPage,['photos.php','documents.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            🖼️ Media <span>▾</span>
        </div>
        <div class="submenu">
            <a href="photos.php" class="<?= $currentPage=='photos.php'?'active':'' ?>">Photos</a>
            <a href="documents.php" class="<?= $currentPage=='documents.php'?'active':'' ?>">Documents</a>
            <a href="download-profile.php"
   class="<?= $currentPage=='download-profile.php'?'active':'' ?>">Download Profile
</a>

        </div>
    </div>

    <!-- MATCHES -->
    <div class="menu <?= in_array($currentPage,['search.php','filters.php','interests.php','matches.php','chat.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            ❤️ Matches <span>▾</span>
        </div>
        <div class="submenu">
            <a href="search.php" class="<?= $currentPage=='search.php'?'active':'' ?>">Search</a>
            <a href="filters.php" class="<?= $currentPage=='filters.php'?'active':'' ?>">Filters</a>
            <a href="interests.php" class="<?= $currentPage=='interests.php'?'active':'' ?>">Interests</a>
            <a href="matches.php" class="<?= $currentPage=='matches.php'?'active':'' ?>">Matches</a>
        </div>
    </div>

    <!-- PRIVACY -->
    <div class="menu <?= in_array($currentPage,['privacy-settings.php','hide-contact.php','blocked-users.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            🔒 Privacy <span>▾</span>
        </div>
        <div class="submenu">
            <a href="privacy-settings.php" class="<?= $currentPage=='privacy-settings.php'?'active':'' ?>">Settings</a>
            <a href="hide-contact.php" class="<?= $currentPage=='hide-contact.php'?'active':'' ?>">Hide Contact</a>
            <a href="blocked-users.php" class="<?= $currentPage=='blocked-users.php'?'active':'' ?>">Blocked Users</a>
        </div>
    </div>

    <!-- ACCOUNT -->
    <div class="menu <?= in_array($currentPage,['deactivate-account.php','delete-account.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            ⚙️ Account <span>▾</span>
        </div>
        <div class="submenu">
            <a href="deactivate-account.php" class="<?= $currentPage=='deactivate-account.php'?'active':'' ?>">Deactivate</a>
            <a href="delete-account.php" class="<?= $currentPage=='delete-account.php'?'active':'' ?>">Delete</a>
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="logout">
        <a href="../auth/logout.php" class="menu-item">🚪 Logout</a>
    </div>

</aside>

<script>
function toggleMenu(clickedItem) {
    const parentMenu = clickedItem.parentElement;
    parentMenu.classList.toggle('open');
}

function toggleSidebar() {
    document.querySelector('.user-sidebar').classList.toggle('show');
    document.querySelector('.sidebar-overlay').classList.toggle('show');
}
</script>
