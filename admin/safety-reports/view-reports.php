<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   FETCH REPORTS
===================== */
$stmt = $conn->prepare("
    SELECT 
        r.id,
        r.reason,
        r.status,
        r.reported_at,
        ru.name AS reported_user,
        rb.name AS reported_by
    FROM reports r
    JOIN users ru ON r.reported_user = ru.id
    JOIN users rb ON r.reported_by = rb.id
    ORDER BY r.reported_at DESC
");
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}

h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

table{width:100%;border-collapse:collapse;min-width:900px}
th,td{padding:14px 12px;border-bottom:1px solid #eee;font-size:14px;text-align:left}
th{background:#f3e8f6;color:#4b0053}

.status{
    font-weight:600;
    text-transform:capitalize;
}
.status.open{color:#ff9800}
.status.investigating{color:#1565c0}
.status.closed{color:#2e7d32}

.btn{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}
.btn.view{
    background:#5a0c5f;
    color:#fff;
}
.btn.view:hover{
    background:#3d0040;
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
.btn.warn{
    background:#ff9800;
    color:#fff;
}
.btn.warn:hover{
    background:#fb8c00;
}

</style>

<div class="admin-layout">
<?php include '../sidebar.php'; ?>

<main class="admin-main">
<div class="card">

<h2>Safety Reports</h2>
<p class="sub">View reported user complaints</p>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Reported User</th>
    <th>Reported By</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Date</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if (count($reports) === 0): ?>
<tr>
    <td colspan="7" style="text-align:center;color:#777">
        No reports found
    </td>
</tr>
<?php endif; ?>

<?php foreach ($reports as $r): ?>

<?php
$status = strtolower(trim($r['status']));
if (!in_array($status, ['open','investigating','closed'])) {
    $status = 'open';
}
?>

<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['reported_user']) ?></td>
    <td><?= htmlspecialchars($r['reported_by']) ?></td>
    <td><?= htmlspecialchars($r['reason']) ?></td>
    <td class="status <?= $status ?>">
        <?= ucfirst($status) ?>
    </td>
    <td><?= date('d M Y', strtotime($r['reported_at'])) ?></td>
    <td>
    <a 
      href="/matrimony/admin/safety-reports/investigate-reports.php?id=<?= $r['id'] ?>" 
      class="btn view">
      View
    </a>

    <a 
      href="/matrimony/admin/safety-reports/warnings.php?user_id=<?= $r['reported_user'] ?>&report_id=<?= $r['id'] ?>" 
      class="btn warn"
      onclick="return confirm('Issue warning to this user?')">
      Warn
    </a>
</td>

</tr>

<?php endforeach; ?>
</tbody>
</table>

</div>
</main>
</div>

<?php include '../../includes/footer.php'; ?>
