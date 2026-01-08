<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   FETCH DOCUMENTS
===================== */
$stmt = $conn->prepare("
    SELECT 
        d.id AS doc_id,
        d.document_type,
        d.document_path,
        d.verified,
        u.name,
        u.email
    FROM documents d
    JOIN users u ON d.user_id = u.id
    ORDER BY d.id DESC
");
$stmt->execute();
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}

h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

table{width:100%;border-collapse:collapse}
th,td{padding:14px 12px;border-bottom:1px solid #eee;font-size:14px;text-align:left}
th{background:#f3e8f6;color:#4b0053}

/* STATUS COLORS */
.status{
    font-weight:600;
    text-transform:capitalize;
}
.status.pending{color:#ff9800}
.status.approved{color:#2e7d32}
.status.rejected{color:#c62828}

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
</style>

<div class="admin-layout">
<?php include '../sidebar.php'; ?>

<main class="admin-main">
<div class="card">

<h2>View Documents</h2>
<p class="sub">All uploaded user documents</p>

<table>
<thead>
<tr>
    <th>User</th>
    <th>Email</th>
    <th>Document Type</th>
    <th>Status</th>
    <th>Document</th>
</tr>
</thead>

<tbody>
<?php if(count($docs) === 0): ?>
<tr>
    <td colspan="5" style="text-align:center;color:#777">No documents found</td>
</tr>
<?php endif; ?>

<?php foreach ($docs as $d): ?>

<?php
// ✅ NORMALIZE STATUS (THIS IS THE KEY FIX)
$status = strtolower(trim($d['verified']));
if (!in_array($status, ['pending','approved','rejected'])) {
    $status = 'pending';
}
?>

<tr>
    <td><?= htmlspecialchars($d['name']) ?></td>
    <td><?= htmlspecialchars($d['email']) ?></td>
    <td><?= ucfirst(str_replace('_',' ',$d['document_type'])) ?></td>
    <td class="status <?= $status ?>">
        <?= ucfirst($status) ?>
    </td>
    <td>
        <a 
          href="/matrimony/uploads/documents/<?= htmlspecialchars($d['document_path']) ?>" 
          target="_blank" 
          class="btn view">
          View
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
