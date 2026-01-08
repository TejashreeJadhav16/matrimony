<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* FETCH USER DOCUMENTS */
$stmt = $conn->prepare("
    SELECT id, document_type, document_path, verified, uploaded_at
    FROM documents
    WHERE user_id = ?
    ORDER BY uploaded_at DESC
");
$stmt->execute([$user_id]);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.user-layout { display:flex; min-height:100vh; background:#f6f7fb; }
.user-main { flex:1; padding:30px; }

.card {
    background:#fff; padding:30px; border-radius:18px;
    box-shadow:0 14px 35px rgba(0,0,0,0.1);
}

.upload-box {
    background:#f6f7fb; padding:20px; border-radius:12px;
    margin-bottom:30px;
}

.upload-box input, .upload-box select {
    width:100%; padding:12px; margin-bottom:15px;
    border-radius:8px; border:1px solid #ddd;
}

.btn { padding:10px 26px; border-radius:30px; font-weight:600; border:none; cursor:pointer; }
.btn.primary { background:#5a0c5f; color:#fff; }
.btn.danger { background:#c62828; color:#fff; margin-top:10px; width:100%; }

.doc-list {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.doc-card {
    background:#f6f7fb; padding:18px; border-radius:14px;
}
/* SMALL DELETE BUTTON */
.btn-delete {
    background: #fdecea;
    color: #c62828;
    border: 1px solid #f5c6cb;
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 8px;
    width: auto;
}

.btn-delete:hover {
    background: #c62828;
    color: #fff;
}

.status { font-size:12px; font-weight:600; }
.status.pending { color:#ff9800; }
.status.verified { color:#2e7d32; }
.status.rejected { color:#c62828; }

@media(max-width:900px){
    .user-layout{flex-direction:column;}
}
</style>

<div class="user-layout">
<?php include 'sidebar.php'; ?>

<main class="user-main">
<div class="card">

<h2>Documents</h2>
<p style="color:#777;margin-bottom:20px">
🔒 Documents are visible only to Admin
</p>

<?php if(isset($_SESSION['success'])): ?>
<p style="color:green"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
<p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<!-- UPLOAD FORM -->
<form class="upload-box" method="post" action="documents-upload.php" enctype="multipart/form-data">
    <select name="document_type" required>
        <option value="">Select Document</option>
        <option value="aadhaar">Aadhaar Card</option>
        <option value="caste_certificate">Caste Certificate</option>
        <option value="other">Other</option>
    </select>

    <input type="file" name="document" required>

    <button class="btn primary">Upload Document</button>
</form>

<!-- DOCUMENT LIST -->
<div class="doc-list">

<?php if(count($documents)===0): ?>
<p style="color:#777">No documents uploaded yet.</p>
<?php endif; ?>

<?php foreach($documents as $doc): ?>
<div class="doc-card">
    <h4><?= ucfirst(str_replace('_',' ',$doc['document_type'])) ?></h4>
    <p><?= date('d M Y', strtotime($doc['uploaded_at'])) ?></p>

    <span class="status <?= $doc['verified'] ?>">
        <?= ucfirst($doc['verified']) ?>
    </span>

    <form method="post" action="delete-document.php"
          onsubmit="return confirm('Delete this document?');">
        <input type="hidden" name="id" value="<?= $doc['id'] ?>">
        <input type="hidden" name="path" value="<?= $doc['document_path'] ?>">
        <button class="btn btn-delete" title="Delete document">
    🗑
</button>

    </form>
</div>
<?php endforeach; ?>

</div>
</div>
</main>
</div>

<?php include '../includes/footer.php'; ?>
