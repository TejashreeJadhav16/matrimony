<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   GET REPORT ID
===================== */
$reportId = $_GET['id'] ?? 0;
if (!$reportId) {
    header("Location: view-reports.php");
    exit;
}

/* =====================
   FETCH REPORT DETAILS
===================== */
$stmt = $conn->prepare("
    SELECT 
        r.*,
        ru.name AS reported_user_name,
        ru.email AS reported_user_email,
        rb.name AS reported_by_name,
        rb.email AS reported_by_email
    FROM reports r
    JOIN users ru ON r.reported_user = ru.id
    JOIN users rb ON r.reported_by = rb.id
    WHERE r.id = ?
");
$stmt->execute([$reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die('Report not found');
}

/* =====================
   UPDATE STATUS
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];

    if (!in_array($newStatus, ['open','investigating','closed'])) {
        $newStatus = 'open';
    }

    $stmt = $conn->prepare("
        UPDATE reports 
        SET status = ?
        WHERE id = ?
    ");
    $stmt->execute([$newStatus, $reportId]);

    header("Location: investigate-reports.php?id=".$reportId."&updated=1");
    exit;
}
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
    max-width:900px;
}

h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
    margin-bottom:20px;
}

.field{
    background:#f6f7fb;
    padding:12px;
    border-radius:10px;
    font-size:14px;
}
.field label{
    display:block;
    font-size:12px;
    color:#777;
}

.status{
    font-weight:600;
    text-transform:capitalize;
}
.status.open{color:#ff9800}
.status.investigating{color:#1565c0}
.status.closed{color:#2e7d32}

.btn{
    padding:8px 16px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
    border:none;
    cursor:pointer;
}

.btn.update{background:#5a0c5f;color:#fff}
.btn.back{background:#ccc;color:#333;text-decoration:none}

.success{
    background:#e8f5e9;
    color:#2e7d32;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
    .grid{grid-template-columns:1fr}
}
</style>

<div class="admin-layout">
<?php include '../sidebar.php'; ?>

<main class="admin-main">
<div class="card">

<h2>Investigate Report</h2>
<p class="sub">Review and take action on this report</p>

<?php if(isset($_GET['updated'])): ?>
<div class="success">✔ Report status updated</div>
<?php endif; ?>

<div class="grid">
    <div class="field">
        <label>Reported User</label>
        <?= htmlspecialchars($report['reported_user_name']) ?><br>
        <small><?= htmlspecialchars($report['reported_user_email']) ?></small>
    </div>

    <div class="field">
        <label>Reported By</label>
        <?= htmlspecialchars($report['reported_by_name']) ?><br>
        <small><?= htmlspecialchars($report['reported_by_email']) ?></small>
    </div>

    <div class="field">
        <label>Reason</label>
        <?= htmlspecialchars($report['reason']) ?>
    </div>

    <div class="field">
        <label>Reported On</label>
        <?= date('d M Y, h:i A', strtotime($report['reported_at'])) ?>
    </div>

    <div class="field">
        <label>Status</label>
        <span class="status <?= $report['status'] ?>">
            <?= ucfirst($report['status']) ?>
        </span>
    </div>
</div>

<form method="post">
    <label style="font-size:13px;color:#555">Update Status</label><br><br>
    <select name="status" required style="padding:10px;border-radius:8px">
        <option value="open" <?= $report['status']=='open'?'selected':'' ?>>Open</option>
        <option value="investigating" <?= $report['status']=='investigating'?'selected':'' ?>>Investigating</option>
        <option value="closed" <?= $report['status']=='closed'?'selected':'' ?>>Closed</option>
    </select>

    <br><br>

    <button type="submit" class="btn update">Update Status</button>
    <a href="view-reports.php" class="btn back">Back</a>
</form>

</div>
</main>
</div>

<?php include '../../includes/footer.php'; ?>
