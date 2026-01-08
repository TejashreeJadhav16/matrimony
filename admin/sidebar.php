<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$base = '/matrimony/admin';
?>

<style>
/* =====================
   ADMIN SIDEBAR BASE
===================== */
.admin-sidebar {
    width: 270px;
    background: linear-gradient(180deg, #2c0030, #4b0053);
    color: #fff;
    min-height: 100vh;
    padding: 20px;
    display: flex;
    flex-direction: column;
}

/* LOGO */
.admin-sidebar .logo {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 25px;
    text-align: center;
}

/* MENU ITEM */
.menu-item {
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 14px;
    color: #e5d4ea;
    margin-bottom: 6px;
    text-decoration: none;
}

.menu-item:hover {
    background: rgba(255,255,255,0.15);
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
    color: #d7c2dd;
    border-radius: 8px;
    margin: 4px 0;
    text-decoration: none;
}

.submenu a:hover,
.submenu a.active {
    background: #ffffff;
    color: #4b0053;
    font-weight: 600;
}

/* OPEN */
.menu.open .submenu {
    display: block;
}

/* LOGOUT */
.logout {
    margin-top: auto;
    border-top: 1px solid rgba(255,255,255,0.25);
    padding-top: 15px;
}

/* MOBILE */
.admin-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    background: #4b0053;
    color: #fff;
    border: none;
    padding: 10px 14px;
    border-radius: 8px;
    z-index: 1200;
}

.overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1100;
}

@media (max-width: 1024px) {
    .admin-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1200;
    }

    .admin-sidebar.show {
        transform: translateX(0);
    }

    .admin-toggle {
        display: block;
    }

    .overlay.show {
        display: block;
    }
}
</style>

<!-- MOBILE TOGGLE -->
<button class="admin-toggle" onclick="toggleAdminSidebar()">☰</button>
<div class="overlay" onclick="toggleAdminSidebar()"></div>

<aside class="admin-sidebar">

    <div class="logo">Admin Panel</div>

    <!-- DASHBOARD -->
    <a href="<?= $base ?>/dashboard.php"
       class="menu-item <?= $currentPage=='dashboard.php'?'active':'' ?>">
        🏠 Dashboard
    </a>

    <!-- USER MANAGEMENT -->
    <div class="menu <?= in_array($currentPage,[
        'view-users.php','approve-users.php','reject-users.php',
        'suspend-users.php','delete-users.php','view-user.php'
    ])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            👥 User Management <span>▾</span>
        </div>
        <div class="submenu">
            <a href="<?= $base ?>/user-management/view-users.php">View Users</a>
            <a href="<?= $base ?>/user-management/approve-users.php">Approve Users</a>
            <a href="<?= $base ?>/user-management/reject-users.php">Reject Users</a>
            <a href="<?= $base ?>/user-management/suspend-users.php">Suspend Users</a>
            <a href="<?= $base ?>/user-management/delete-users.php">Delete Users</a>
        </div>
    </div>

    <!-- PROFILE CONTROL -->
    <div class="menu <?= in_array($currentPage,[
        'review-profiles.php','approve-photos.php','remove-photos.php'
    ])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            🖼️ Profile Control <span>▾</span>
        </div>
        <div class="submenu">
            <a href="<?= $base ?>/profile-control/review-profiles.php">Review Profiles</a>
            <a href="<?= $base ?>/profile-control/approve-photos.php">Approve Photos</a>
            <a href="<?= $base ?>/profile-control/remove-photos.php">Remove Photos</a>
        </div>
    </div>

    <!-- DOCUMENT VERIFICATION -->
    <div class="menu <?= in_array($currentPage,['view-documents.php','verify-documents.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            📄 Documents <span>▾</span>
        </div>
        <div class="submenu">
            <a href="<?= $base ?>/document-verification/view-documents.php">View Documents</a>
            <a href="<?= $base ?>/document-verification/verify-documents.php">Verify Documents</a>
        </div>
    </div>

    <!-- REPORTS -->
    <div class="menu <?= in_array($currentPage,['view-reports.php','investigate-reports.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            ⚠️ Safety Reports <span>▾</span>
        </div>
        <div class="submenu">
            <a href="<?= $base ?>/safety-reports/view-reports.php">View Reports</a>
            <a href="<?= $base ?>/safety-reports/investigate-reports.php">Investigate</a>
        </div>
    </div>

    <!-- DATA -->
    <div class="menu <?= in_array($currentPage,['total-users.php','active-users.php'])?'open':'' ?>">
        <div class="menu-item" onclick="toggleMenu(this)">
            📊 Data Monitoring <span>▾</span>
        </div>
        <div class="submenu">
            <a href="<?= $base ?>/data-monitoring/total-users.php">Total Users</a>
            <a href="<?= $base ?>/data-monitoring/active-users.php">Active Users</a>
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="logout">
        <a href="/matrimony/auth/logout.php" class="menu-item">🚪 Logout</a>
    </div>

</aside>

<script>
function toggleMenu(el) {
    el.parentElement.classList.toggle('open');
}

function toggleAdminSidebar() {
    document.querySelector('.admin-sidebar').classList.toggle('show');
    document.querySelector('.overlay').classList.toggle('show');
}
</script>
