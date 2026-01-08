<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* FETCH USER PHOTOS */
$stmt = $conn->prepare("
    SELECT id, photo_path, status, uploaded_at
    FROM photos
    WHERE user_id = ?
    ORDER BY uploaded_at DESC
");
$stmt->execute([$user_id]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* =====================
   PHOTOS – USER PANEL
===================== */
.user-layout {
    display: flex;
    min-height: 100vh;
    background: #f6f7fb;
}

.user-main {
    flex: 1;
    padding: 30px;
}

.card {
    background: #fff;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
}

.card h2 {
    color: #5a0c5f;
    margin-bottom: 6px;
}

.sub-text {
    font-size: 14px;
    color: #777;
    margin-bottom: 20px;
}

/* SUCCESS / ERROR */
.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 15px;
}

/* UPLOAD */
.upload-box {
    background: #f6f7fb;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
}

.upload-box input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}

.btn {
    padding: 10px 24px;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.btn.primary {
    background: #5a0c5f;
    color: #fff;
}

/* PHOTO LIST */
.photo-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.photo-card {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #f6f7fb;
    padding: 15px;
    border-radius: 14px;
}

/* IMAGE */
.photo-card img {
    width: 140px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
}

/* INFO */
.photo-info {
    flex: 1;
}

.photo-info p {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.status {
    font-size: 12px;
    font-weight: 600;
}

.status.pending { color: #ff9800; }
.status.approved { color: #2e7d32; }
.status.rejected { color: #c62828; }

/* DELETE BUTTON */
.delete-btn {
    background: #ffe5e5;
    color: #c62828;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

.delete-btn:hover {
    background: #ffcdd2;
}

/* MOBILE */
@media (max-width: 768px) {
    .photo-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .photo-card img {
        width: 100%;
        height: auto;
    }

    .delete-btn {
        align-self: flex-end;
    }
}
</style>

<div class="user-layout">

    <?php include 'sidebar.php'; ?>

    <main class="user-main">

        <div class="card">

            <h2>My Photos</h2>
            <p class="sub-text">Upload profile photos (Admin approval required)</p>

            <?php if (isset($_GET['uploaded'])): ?>
                <div class="alert-success">
                    ✅ Photo uploaded successfully. Await admin approval.
                </div>
            <?php endif; ?>

            <!-- UPLOAD FORM -->
            <form class="upload-box" method="post" action="photos-upload.php" enctype="multipart/form-data">
                <input type="file" name="photo" required>
                <button type="submit" class="btn primary">Upload Photo</button>
            </form>

            <!-- PHOTO LIST -->
            <div class="photo-list">

                <?php if (count($photos) === 0): ?>
                    <p style="font-size:14px;color:#777">No photos uploaded yet.</p>
                <?php endif; ?>

                <?php foreach ($photos as $photo): ?>
                    <div class="photo-card">

                        <img src="../uploads/photos/<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo">

                        <div class="photo-info">
                            <p>Uploaded: <?= date('d M Y', strtotime($photo['uploaded_at'])) ?></p>
                            <span class="status <?= $photo['status'] ?>">
                                <?= ucfirst($photo['status']) ?>
                            </span>
                        </div>

                        <!-- DELETE -->
                        <form method="post" action="delete-photo.php" 
                              onsubmit="return confirm('Delete this photo?')">
                            <input type="hidden" name="photo_id" value="<?= $photo['id'] ?>">
                            <button type="submit" class="delete-btn">🗑</button>
                        </form>

                    </div>
                <?php endforeach; ?>

            </div>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
