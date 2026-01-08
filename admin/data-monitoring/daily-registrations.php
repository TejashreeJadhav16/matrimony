<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   TODAY COUNT
===================== */
$todayCount = $conn->query("
    SELECT COUNT(*) 
    FROM users 
    WHERE DATE(created_at) = CURDATE()
")->fetchColumn();

/* =====================
   DAILY REGISTRATIONS
===================== */
$stmt = $conn->prepare("
    SELECT 
        DATE(created_at) AS reg_date,
        COUNT(*) AS total
    FROM users
    GROUP BY DATE(created_at)
    ORDER BY reg_date DESC
");
$stmt->execute();
$daily = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    margin-bottom:30px
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

/* TABLE */
.card{
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.08)
}
h2{color:#4b0053;margin-bottom:6px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

table{
    width:100%;
    border-collapse:collapse;
    min-width:600px
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

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
.back-btn{
    display:inline-block;
    margin-bottom:20px;
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

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <span>Today's Registrations</span>
                <h2><?= $todayCount ?></h2>
            </div>
        </div>
        <!-- BACK BUTTON -->
<a href="total-users.php" class="back-btn">⬅ Back</a>
        <!-- TABLE -->
        <div class="card">
            <h2>Daily Registrations</h2>
            <p class="sub">User registrations grouped by date</p>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Registrations</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (count($daily) === 0): ?>
                    <tr>
                        <td colspan="2" style="text-align:center;color:#777">
                            No registrations found
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($daily as $d): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($d['reg_date'])) ?></td>
                        <td><?= $d['total'] ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>


    </main>
</div>

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
