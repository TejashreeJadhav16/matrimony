<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   FETCH COUNTS
===================== */
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$approvedUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='approved'")->fetchColumn();
$pendingUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='pending'")->fetchColumn();
$suspendedUsers = $conn->query("SELECT COUNT(*) FROM users WHERE status='suspended'")->fetchColumn();

/* =====================
   FETCH USERS
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id,
        u.name,
        u.email,
        u.mobile,
        u.status,
        u.created_at,
        p.gender,
        p.location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    ORDER BY u.created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HEADER -->
<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}

/* STATS */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:25px
}
.stat-card{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.08)
}
.stat-card span{
    font-size:13px;
    color:#777
}
.stat-card h2{
    font-size:28px;
    color:#5a0c5f;
    margin-top:6px
}

/* ACTION BUTTONS */
.action-bar{
    display:flex;
    gap:15px;
    margin-bottom:30px;
    flex-wrap:wrap
}
.action-btn{
    padding:12px 22px;
    border-radius:30px;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:8px
}
.action-btn.daily{
    background:#5a0c5f;
    color:#fff
}
.action-btn.health{
    background:#fff;
    color:#5a0c5f;
    border:2px solid #5a0c5f
}
.action-btn:hover{opacity:.9}

/* TABLE */
.card{
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.08)
}
h2{color:#4b0053;margin-bottom:6px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

.table-wrap{overflow-x:auto}
table{
    width:100%;
    border-collapse:collapse;
    min-width:1000px
}
th,td{
    padding:14px 12px;
    border-bottom:1px solid #eee;
    font-size:14px;
    text-align:left
}
th{
    background:#f3e8f6;
    color:#4b0053
}

/* STATUS */
.status{
    font-weight:600;
    text-transform:capitalize
}
.status.approved{color:#2e7d32}
.status.pending{color:#ff9800}
.status.suspended{color:#c62828}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
</style>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN -->
    <main class="admin-main">

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <span>Total Users</span>
                <h2><?= $totalUsers ?></h2>
            </div>
            <div class="stat-card">
                <span>Approved</span>
                <h2><?= $approvedUsers ?></h2>
            </div>
            <div class="stat-card">
                <span>Pending</span>
                <h2><?= $pendingUsers ?></h2>
            </div>
            <div class="stat-card">
                <span>Suspended</span>
                <h2><?= $suspendedUsers ?></h2>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="action-bar">
            <a href="daily-registrations.php" class="action-btn daily">
                📅 Daily Registrations
            </a>

            <a href="platform-health.php" class="action-btn health">
                ⚙️ Platform Health
            </a>
        </div>

        <!-- TABLE -->
        <div class="card">
            <h2>All Users</h2>
            <p class="sub">Complete list of registered users</p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Gender</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Joined On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) === 0): ?>
                            <tr>
                                <td colspan="8" style="text-align:center;color:#777">
                                    No users found
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['mobile']) ?></td>
                                <td><?= $u['gender'] ?? '-' ?></td>
                                <td><?= $u['location'] ?? '-' ?></td>
                                <td class="status <?= $u['status'] ?>">
                                    <?= ucfirst($u['status']) ?>
                                </td>
                                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
