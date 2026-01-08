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
   APPROVE / REJECT ACTION
===================== */
if (isset($_GET['approve'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $photoId = (int) $_GET['approve'];

    $stmt = $conn->prepare("
        UPDATE photos 
        SET status = 'approved' 
        WHERE id = ? AND status = 'pending'
    ");
    $stmt->execute([$photoId]);

    header("Location: /matrimony/admin/profile-control/approve-photos.php?success=1");
    exit;
}

if (isset($_GET['reject'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $photoId = (int) $_GET['reject'];

    $stmt = $conn->prepare("
        UPDATE photos 
        SET status = 'rejected' 
        WHERE id = ? AND status = 'pending'
    ");
    $stmt->execute([$photoId]);

    header("Location: /matrimony/admin/profile-control/approve-photos.php?rejected=1");
    exit;
}

/* =====================
   FETCH PENDING PHOTOS
===================== */
$stmt = $conn->prepare("
    SELECT 
        ph.id AS photo_id,
        ph.photo_path,
        u.id AS user_id,
        u.name,
        u.email
    FROM photos ph
    JOIN users u ON ph.user_id = u.id
    WHERE ph.status = 'pending'
    ORDER BY ph.id DESC
");
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HEADER -->
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

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    gap:20px
}

.photo-card{
    background:#f6f7fb;
    padding:12px;
    border-radius:14px;
    text-align:center
}

.photo-card img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px
}

.user{
    font-size:13px;
    margin-bottom:10px;
    color:#333
}

.actions{
    display:flex;
    justify-content:center;
    gap:10px
}

.btn{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-block
}

.btn.approve{background:#2e7d32;color:#fff}
.btn.approve:hover{background:#1b5e20}

.btn.reject{background:#c62828;color:#fff}
.btn.reject:hover{background:#8e0000}

.success{
    background:#e8f5e9;
    color:#2e7d32;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px
}

.error{
    background:#ffebee;
    color:#c62828;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
</style>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN -->
    <main class="admin-main">

        <div class="card">

            <h2>Approve Photos</h2>
            <p class="sub">Review and approve user uploaded photos</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="success">✔ Photo approved successfully</div>
            <?php endif; ?>

            <?php if (isset($_GET['rejected'])): ?>
                <div class="error">❌ Photo rejected successfully</div>
            <?php endif; ?>

            <div class="grid">
                <?php if (count($photos) === 0): ?>
                    <p style="color:#777">No pending photos</p>
                <?php endif; ?>

                <?php foreach ($photos as $p): ?>
                    <div class="photo-card">
                        <img src="/matrimony/uploads/photos/<?= htmlspecialchars($p['photo_path']) ?>" alt="Photo">

                        <div class="user">
                            <?= htmlspecialchars($p['name']) ?><br>
                            <small><?= htmlspecialchars($p['email']) ?></small>
                        </div>

                        <div class="actions">
                            <a 
                              href="/matrimony/admin/profile-control/approve-photos.php?approve=<?= $p['photo_id'] ?>&token=<?= $_SESSION['csrf_token'] ?>" 
                              class="btn approve"
                              onclick="return confirm('Approve this photo?')">
                              Approve
                            </a>

                            <a 
                              href="/matrimony/admin/profile-control/approve-photos.php?reject=<?= $p['photo_id'] ?>&token=<?= $_SESSION['csrf_token'] ?>" 
                              class="btn reject"
                              onclick="return confirm('Reject this photo?')">
                              Reject
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </main>
</div>

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
