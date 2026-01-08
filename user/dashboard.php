<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* =====================
   FETCH USER NAME
===================== */
$stmt = $conn->prepare("SELECT name, status FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* =====================
   DASHBOARD STATS
===================== */
$stmt = $conn->prepare("
    SELECT COUNT(*) FROM interests 
    WHERE sender_id = ? OR receiver_id = ?
");
$stmt->execute([$user_id, $user_id]);
$totalInterests = $stmt->fetchColumn();

$stmt = $conn->prepare("
    SELECT COUNT(*) FROM matches 
    WHERE user_one = ? OR user_two = ?
");
$stmt->execute([$user_id, $user_id]);
$totalMatches = $stmt->fetchColumn();

$stmt = $conn->prepare("
    SELECT COUNT(*) FROM chats 
    WHERE sender_id = ?
");
$stmt->execute([$user_id]);
$totalChats = $stmt->fetchColumn();
?>

<style>
/* =====================
   DASHBOARD LAYOUT
===================== */
.dashboard-layout {
    display: flex;
    min-height: 100vh;
    background: #f6f7fb;
}

.dashboard-main {
    flex: 1;
    padding: 30px;
}

/* HEADER TEXT */
.welcome {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 4px;
}

.sub-text {
    font-size: 14px;
    color: #777;
    margin-bottom: 25px;
}

/* =====================
   STATS CARDS
===================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
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
    font-size: 30px;
    color: #5a0c5f;
    margin-top: 6px;
}

/* =====================
   DASHBOARD GRID
===================== */
.dashboard-grid {
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

.quick-links a {
    display: block;
    padding: 8px 0;
    font-size: 14px;
    color: #333;
    text-decoration: none;
}

.quick-links a:hover {
    color: #5a0c5f;
}

/* =====================
   RESPONSIVE
===================== */
@media (max-width: 900px) {
    .dashboard-layout {
        flex-direction: column;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-main {
        padding: 20px;
    }
}
</style>

<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="dashboard-main">

        <!-- ✅ USER NAME INSTEAD OF "USER DASHBOARD" -->
        <div class="welcome">
            Welcome, <?= htmlspecialchars($user['name']) ?> 👋
        </div>
        <div class="sub-text">
            Overview of your matrimony activity
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <span>Total Interests</span>
                <h2><?= $totalInterests ?></h2>
            </div>

            <div class="stat-card">
                <span>Total Matches</span>
                <h2><?= $totalMatches ?></h2>
            </div>

            <div class="stat-card">
                <span>Chats Started</span>
                <h2><?= $totalChats ?></h2>
            </div>

            <div class="stat-card">
                <span>Profile Status</span>
                <h2><?= ucfirst($user['status']) ?></h2>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="dashboard-grid">

            <div class="box">
                <h3>Recent Activity</h3>
                <p>✔ Profile approved by admin</p>
                <p>💌 Interests sent & received</p>
                <p>❤️ Matches created</p>
                <p>💬 Chat enabled after match</p>
            </div>

            <div class="box">
                <h3>Quick Actions</h3>
                <div class="quick-links">
                    <a href="my-profile.php">👤 View Profile</a>
                    <a href="edit-profile.php">✏️ Edit Profile</a>
                    <a href="search.php">🔍 Search Matches</a>
                    <a href="interests.php">💌 Interests</a>
                    <a href="privacy-settings.php">🔒 Privacy Settings</a>
                </div>
            </div>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
