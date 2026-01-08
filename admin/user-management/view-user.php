<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   GET USER ID
===================== */
$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($userId <= 0) {
    echo "<p style='color:red;text-align:center'>Invalid user ID</p>";
    exit;
}

/* =====================
   FETCH USER DATA
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.mobile, u.status, u.created_at,
        p.gender, p.dob, p.marital_status, p.location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.id = ?
    LIMIT 1
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p style='color:red;text-align:center'>User not found</p>";
    exit;
}

/* =====================
   FETCH PHOTOS
===================== */
$photosStmt = $conn->prepare("SELECT photo_path FROM photos WHERE user_id = ?");
$photosStmt->execute([$userId]);
$photos = $photosStmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   FETCH DOCUMENTS
===================== */
$docsStmt = $conn->prepare("
    SELECT document_type, verified 
    FROM documents 
    WHERE user_id = ?
");
$docsStmt->execute([$userId]);
$docs = $docsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HEADER -->
<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{color:#4b0053;margin-bottom:10px}
.section{margin-top:25px}
.section h3{color:#5a0c5f;margin-bottom:12px}

.grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px
}

.field{
    background:#f6f7fb;
    padding:12px;
    border-radius:10px;
    font-size:14px
}

.field label{
    font-size:12px;
    color:#777;
    display:block
}

.photos{
    display:flex;
    gap:12px;
    flex-wrap:wrap
}

.photos img{
    width:120px;
    height:140px;
    object-fit:cover;
    border-radius:10px
}

.doc{
    background:#f6f7fb;
    padding:10px;
    border-radius:10px;
    margin-bottom:8px;
    font-size:14px
}

.back-btn{
    margin-top:25px;
    display:inline-block;
    padding:10px 18px;
    border-radius:30px;
    background:#5a0c5f;
    color:#fff;
    text-decoration:none
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
    .grid{grid-template-columns:1fr}
}
</style>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN -->
    <main class="admin-main">

        <div class="card">
            <h2>User Details</h2>

            <!-- BASIC INFO -->
            <div class="section">
                <h3>Basic Information</h3>
                <div class="grid">
                    <div class="field"><label>Name</label><?= htmlspecialchars($user['name']) ?></div>
                    <div class="field"><label>Email</label><?= htmlspecialchars($user['email']) ?></div>
                    <div class="field"><label>Mobile</label><?= htmlspecialchars($user['mobile']) ?></div>
                    <div class="field"><label>Gender</label><?= $user['gender'] ?? '-' ?></div>
                    <div class="field"><label>DOB</label><?= $user['dob'] ?? '-' ?></div>
                    <div class="field"><label>Marital Status</label><?= $user['marital_status'] ?? '-' ?></div>
                    <div class="field"><label>Location</label><?= $user['location'] ?? '-' ?></div>
                    <div class="field"><label>Status</label><?= ucfirst($user['status']) ?></div>
                </div>
            </div>

            <!-- PHOTOS -->
            <div class="section">
                <h3>Photos</h3>
                <div class="photos">
                    <?php if (count($photos) === 0): ?>
                        <p>No photos uploaded</p>
                    <?php endif; ?>
                    <?php foreach ($photos as $p): ?>
                        <img src="/matrimony/uploads/photos/<?= htmlspecialchars($p['photo_path']) ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- DOCUMENTS -->
            <div class="section">
                <h3>Documents</h3>
                <?php if (count($docs) === 0): ?>
                    <p>No documents uploaded</p>
                <?php endif; ?>
                <?php foreach ($docs as $d): ?>
                    <div class="doc">
                        <?= ucfirst($d['document_type']) ?> —
                        <strong><?= ucfirst($d['verified']) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- BACK -->
            <a href="/matrimony/admin/user-management/view-users.php" class="back-btn">
                ⬅ Back to Users
            </a>
        </div>

    </main>
</div>

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
