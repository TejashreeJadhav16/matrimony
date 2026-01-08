<?php
require_once '../includes/session.php';
require_once '../includes/config.php';

/* =========================
   ADMIN AUTH CHECK
========================= */
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* =========================
   FETCH ADMIN DETAILS
========================= */
$admin_id = $_SESSION['admin_id'];

$stmt = $conn->prepare("SELECT username, email FROM admin_users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   DASHBOARD STATS
========================= */
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pendingUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='pending'")->fetchColumn();
$approvedUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='approved'")->fetchColumn();

$totalPhotos = $conn->query("SELECT COUNT(*) FROM photos WHERE status='pending'")->fetchColumn();
$totalDocuments = $conn->query("SELECT COUNT(*) FROM documents WHERE verified='pending'")->fetchColumn();
$totalReports = $conn->query("SELECT COUNT(*) FROM reports WHERE status='open'")->fetchColumn();
?>

<?php include '../includes/header.php'; ?>

<style>
/* =====================
   ADMIN DASHBOARD
===================== */
.admin-layout {
    display: flex;
    min-height: 100vh;
    background: #f6f7fb;
}

.admin-main {
    flex: 1;
    padding: 30px;
}

/* HEADER */
.welcome {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 5px;
}

.sub-text {
    font-size: 14px;
    color: #777;
    margin-bottom: 30px;
}

/* STATS */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 20px;
    margin-bottom: 35px;
}

.stat-card {
    background: #fff;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.stat-card span {
    font-size: 13px;
    color: #777;
}

.stat-card h2 {
    font-size: 28px;
    color: #5a0c5f;
    margin-top: 6px;
}

/* PANELS */
.admin-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
}

.box {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.box h3 {
    color: #5a0c5f;
    margin-bottom: 15px;
}

/* QUICK LINKS */
.quick-links a {
    display: block;
    padding: 8px 0;
    font-size: 14px;
    text-decoration: none;
    color: #333;
}

.quick-links a:hover {
    color: #5a0c5f;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .admin-layout {
        flex-direction: column;
    }
    .admin-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-layout">

    <!-- ADMIN SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <div class="welcome">
            Welcome, Admin <?= htmlspecialchars($admin['username']) ?> 👋
        </div>
        <div class="sub-text">
            Platform overview & moderation controls
        </div>

        <!-- STATS -->
        <div class="stats-grid">

            <div class="stat-card">
                <span>Total Users</span>
                <h2><?= $totalUsers ?></h2>
            </div>

            <div class="stat-card">
                <span>Pending Approvals</span>
                <h2><?= $pendingUsers ?></h2>
            </div>

            <div class="stat-card">
                <span>Approved Profiles</span>
                <h2><?= $approvedUsers ?></h2>
            </div>

            <div class="stat-card">
                <span>Pending Photos</span>
                <h2><?= $totalPhotos ?></h2>
            </div>

            <div class="stat-card">
                <span>Pending Documents</span>
                <h2><?= $totalDocuments ?></h2>
            </div>

            <div class="stat-card">
                <span>Open Reports</span>
                <h2><?= $totalReports ?></h2>
            </div>

        </div>

        <!-- PANELS -->
        <div class="admin-grid">

            <div class="box">
                <h3>Recent Admin Tasks</h3>
                <p>✔ Review pending user approvals</p>
                <p>📄 Verify uploaded documents</p>
                <p>🖼️ Approve / reject photos</p>
                <p>⚠️ Handle reported profiles</p>
            </div>

            <div class="box">
                <h3>Quick Actions</h3>
                <div class="quick-links">
                    <a href="user-management/view-users.php">👥 View Users</a>
                    <a href="user-management/approve-users.php">✅ Approve Users</a>
                    <a href="profile-control/approve-photos.php">🖼️ Approve Photos</a>
                    <a href="document-verification/view-documents.php">📄 Verify Documents</a>
                    <a href="safety-reports/view-reports.php">⚠️ View Reports</a>
                    <a href="platform-management/site-rules.php">⚙️ Site Rules</a>
                </div>
            </div>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
