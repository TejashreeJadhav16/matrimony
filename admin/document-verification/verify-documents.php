<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   CSRF TOKEN
===================== */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* =====================
   VERIFY ACTIONS
===================== */
if (isset($_GET['approve'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $docId = (int) $_GET['approve'];

    $stmt = $conn->prepare("
        UPDATE documents 
        SET verified = 'approved'
        WHERE id = ? AND verified = 'pending'
    ");
    $stmt->execute([$docId]);

    header("Location: verify-documents.php?success=1");
    exit;
}

if (isset($_GET['reject'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $docId = (int) $_GET['reject'];

    $stmt = $conn->prepare("
        UPDATE documents 
        SET verified = 'rejected'
        WHERE id = ? AND verified = 'pending'
    ");
    $stmt->execute([$docId]);

    header("Location: verify-documents.php?rejected=1");
    exit;
}

/* =====================
   FETCH PENDING DOCUMENTS
===================== */
$stmt = $conn->prepare("
    SELECT 
        d.id AS doc_id,
        d.document_type,
        d.document_path,
        u.name,
        u.email
    FROM documents d
    JOIN users u ON d.user_id = u.id
    WHERE d.verified = 'pending'
    ORDER BY d.id DESC
");
$stmt->execute();
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}

.card{
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.08)
}

h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:14px 12px;
    border-bottom:1px solid #eee;
    font-size:14px;
    text-align:left;
}
th{
    background:#f3e8f6;
    color:#4b0053;
}

.btn{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}

.btn.approve{background:#2e7d32;color:#fff}
.btn.approve:hover{background:#1b5e20}

.btn.reject{background:#c62828;color:#fff}
.btn.reject:hover{background:#8e0000}

.success{
    background:#e8f5e9;
    color:#2e7d32;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px
}

.error{
    background:#ffebee;
    color:#c62828;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
</style>

<div class="admin-layout">

<?php include '../sidebar.php'; ?>

<main class="admin-main">
<div class="card">

<h2>Verify Documents</h2>
<p class="sub">Approve or reject pending user documents</p>

<?php if(isset($_GET['success'])): ?>
    <div class="success">✔ Document approved successfully</div>
<?php endif; ?>

<?php if(isset($_GET['rejected'])): ?>
    <div class="error">❌ Document rejected</div>
<?php endif; ?>

<table>
<thead>
<tr>
    <th>User</th>
    <th>Email</th>
    <th>Document Type</th>
    <th>Document</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if(count($docs) === 0): ?>
<tr>
    <td colspan="5" style="text-align:center;color:#777">
        No pending documents
    </td>
</tr>
<?php endif; ?>

<?php foreach($docs as $d): ?>
<tr>
    <td><?= htmlspecialchars($d['name']) ?></td>
    <td><?= htmlspecialchars($d['email']) ?></td>
    <td><?= ucfirst($d['document_type']) ?></td>
    <td>
        <?php if (!empty($d['document_path'])): ?>
            <a href="/matrimony/uploads/documents/<?= htmlspecialchars($d['document_path']) ?>" target="_blank">
                View
            </a>
        <?php else: ?>
            <span style="color:#999">File missing</span>
        <?php endif; ?>
    </td>
    <td>
        <a 
            href="verify-documents.php?approve=<?= $d['doc_id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"
            class="btn approve"
            onclick="return confirm('Approve this document?')">
            Approve
        </a>

        <a 
            href="verify-documents.php?reject=<?= $d['doc_id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"
            class="btn reject"
            onclick="return confirm('Reject this document?')">
            Reject
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
