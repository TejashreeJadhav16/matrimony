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
   REMOVE ACTION
===================== */
if (isset($_GET['remove'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $photoId = (int) $_GET['remove'];

    // Fetch photo path before deleting
    $stmt = $conn->prepare("SELECT photo_path FROM photos WHERE id = ?");
    $stmt->execute([$photoId]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($photo) {
        // Delete photo record
        $del = $conn->prepare("DELETE FROM photos WHERE id = ?");
        $del->execute([$photoId]);

        // Delete file from server
        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/matrimony/uploads/photos/" . $photo['photo_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    header("Location: /matrimony/admin/profile-control/remove-photos.php?success=1");
    exit;
}

/* =====================
   FETCH APPROVED PHOTOS
===================== */
$stmt = $conn->prepare("
    SELECT 
        ph.id AS photo_id,
        ph.photo_path,
        u.name,
        u.email
    FROM photos ph
    JOIN users u ON ph.user_id = u.id
    WHERE ph.status = 'approved'
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
    justify-content:center
}

.btn{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-block
}

.btn.remove{
    background:#d32f2f;
    color:#fff
}
.btn.remove:hover{
    background:#b71c1c
}

.success{
    background:#ffebee;
    color:#b71c1c;
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

            <h2>Remove Photos</h2>
            <p class="sub">Remove approved or inappropriate user photos</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="success">🗑 Photo removed successfully</div>
            <?php endif; ?>

            <div class="grid">
                <?php if (count($photos) === 0): ?>
                    <p style="color:#777">No approved photos found</p>
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
                              href="/matrimony/admin/profile-control/remove-photos.php?remove=<?= $p['photo_id'] ?>&token=<?= $_SESSION['csrf_token'] ?>" 
                              class="btn remove"
                              onclick="return confirm('This will permanently delete the photo. Continue?')">
                              Remove
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
