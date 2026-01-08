<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   PLATFORM HEALTH STATS
===================== */
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$activeUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='approved'")->fetchColumn();
$pendingUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='pending'")->fetchColumn();

$pendingPhotos = $conn->query("SELECT COUNT(*) FROM photos WHERE status='pending'")->fetchColumn();
$pendingDocuments = $conn->query("SELECT COUNT(*) FROM documents WHERE verified='pending'")->fetchColumn();
$openReports = $conn->query("SELECT COUNT(*) FROM reports WHERE status='open'")->fetchColumn();
?>

<!-- HEADER -->
<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{
    display:flex;
    min-height:100vh;
    background:#f6f7fb
}
.admin-main{
    flex:1;
    padding:30px
}

/* GRID */
.health-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:22px
}

.health-card{
    background:#fff;
    padding:24px;
    border-radius:16px;
    box-shadow:0 12px 30px rgba(0,0,0,.08)
}
.health-card span{
    font-size:13px;
    color:#777
}
.health-card h2{
    font-size:30px;
    color:#5a0c5f;
    margin-top:8px
}

.health-card.pending h2{color:#ff9800}
.health-card.danger h2{color:#c62828}
.health-card.success h2{color:#2e7d32}

h2.page-title{
    color:#4b0053;
    margin-bottom:6px
}
p.sub{
    font-size:14px;
    color:#777;
    margin-bottom:25px
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
.back-btn{
    display:inline-block;
    margin-bottom:25px;
    padding:10px 22px;
    border-radius:30px;
    background:#fff;
    color:#5a0c5f;
    border:2px solid #5a0c5f;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
}
.back-btn:hover{
    background:#5a0c5f;
    color:#fff;
}

</style>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN -->
    <main class="admin-main">

        <h2 class="page-title">Platform Health</h2>
        <p class="sub">Live overview of system activity and pending actions</p>
<!-- BACK BUTTON -->
<a href="total-users.php" class="back-btn">⬅ Back</a>

        <div class="health-grid">

            <div class="health-card">
                <span>Total Users</span>
                <h2><?= $totalUsers ?></h2>
            </div>

            <div class="health-card success">
                <span>Active Users</span>
                <h2><?= $activeUsers ?></h2>
            </div>

            <div class="health-card pending">
                <span>Pending User Approvals</span>
                <h2><?= $pendingUsers ?></h2>
            </div>

            <div class="health-card pending">
                <span>Photos Pending Approval</span>
                <h2><?= $pendingPhotos ?></h2>
            </div>

            <div class="health-card pending">
                <span>Documents Pending Verification</span>
                <h2><?= $pendingDocuments ?></h2>
            </div>

            <div class="health-card danger">
                <span>Open Safety Reports</span>
                <h2><?= $openReports ?></h2>
            </div>

        </div>

    </main>
</div>

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
