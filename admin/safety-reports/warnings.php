<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   GET USER ID
===================== */
$userId = $_GET['user_id'] ?? 0;
$reportId = $_GET['report_id'] ?? null;

if (!$userId) {
    header("Location: view-reports.php");
    exit;
}

/* =====================
   FETCH USER
===================== */
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

/* =====================
   ADD WARNING
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = trim($_POST['reason']);

    if ($reason !== '') {
        $stmt = $conn->prepare("
            INSERT INTO warnings (user_id, report_id, reason)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $reportId, $reason]);

        header("Location: warnings.php?user_id=$userId&success=1");
        exit;
    }
}

/* =====================
   FETCH WARNINGS
===================== */
$stmt = $conn->prepare("
    SELECT * FROM warnings
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$warnings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}

.card{
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    max-width:800px;
}

h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

textarea{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
    resize:none;
}

.btn{
    padding:8px 18px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
    border:none;
    cursor:pointer;
}

.btn.warn{background:#ff9800;color:#fff}
.btn.back{background:#ccc;color:#333;text-decoration:none}

.success{
    background:#e8f5e9;
    color:#2e7d32;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}

.warning-box{
    background:#f6f7fb;
    padding:12px;
    border-radius:10px;
    margin-bottom:10px;
    font-size:14px;
}
</style>

<div class="admin-layout">
<?php include '../sidebar.php'; ?>

<main class="admin-main">
<div class="card">

<h2>Issue Warning</h2>
<p class="sub">
    Warning user: <strong><?= htmlspecialchars($user['name']) ?></strong>
    (<?= htmlspecialchars($user['email']) ?>)
</p>

<?php if (isset($_GET['success'])): ?>
<div class="success">✔ Warning issued successfully</div>
<?php endif; ?>

<form method="post">
    <label>Warning Reason</label><br><br>
    <textarea name="reason" rows="4" required></textarea>
    <br><br>

    <button type="submit" class="btn warn">Issue Warning</button>
    <a href="view-reports.php" class="btn back">Back</a>
</form>

<hr style="margin:30px 0">

<h3 style="color:#5a0c5f">Previous Warnings</h3>

<?php if (count($warnings) === 0): ?>
<p style="color:#777">No warnings issued yet</p>
<?php endif; ?>

<?php foreach ($warnings as $w): ?>
<div class="warning-box">
    <strong><?= date('d M Y, h:i A', strtotime($w['created_at'])) ?></strong><br>
    <?= htmlspecialchars($w['reason']) ?>
</div>
<?php endforeach; ?>

</div>
</main>
</div>

<?php include '../../includes/footer.php'; ?>
