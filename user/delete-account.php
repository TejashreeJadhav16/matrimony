<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}

/* =====================
   HANDLE DELETE REQUEST
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* DELETE USER PHOTOS */
    $photos = $conn->prepare("SELECT photo_path FROM photos WHERE user_id = ?");
    $photos->execute([$user_id]);

    foreach ($photos->fetchAll(PDO::FETCH_ASSOC) as $photo) {
        $path = "../uploads/photos/" . $photo['photo_path'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /* DELETE USER DOCUMENTS */
    $docs = $conn->prepare("SELECT document_path FROM documents WHERE user_id = ?");
    $docs->execute([$user_id]);

    foreach ($docs->fetchAll(PDO::FETCH_ASSOC) as $doc) {
        $path = "../uploads/documents/" . $doc['document_path'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /* DELETE USER (CASCADE WILL HANDLE REST) */
    $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);

    /* DESTROY SESSION */
    session_unset();
    session_destroy();

    header("Location: ../auth/login.php?deleted=1");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<style>
.delete-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-card {
    background: #fff;
    max-width: 500px;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
    text-align: center;
}

.delete-card h2 {
    color: #c62828;
    margin-bottom: 12px;
}

.delete-card p {
    font-size: 14px;
    color: #555;
    margin-bottom: 25px;
}

.delete-actions {
    display: flex;
    gap: 15px;
}

.btn {
    padding: 12px 26px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn.danger {
    background: #c62828;
    color: #fff;
}

.btn.secondary {
    background: #eee;
    color: #333;
}

@media (max-width: 600px) {
    .delete-actions {
        flex-direction: column;
    }
    .btn {
        width: 100%;
    }
}
</style>

<section class="delete-page container">

    <div class="delete-card">

        <h2>Delete Account</h2>

        <p>
            ⚠ This action is <strong>permanent</strong>.<br>
            Your profile, photos, documents and chats will be deleted forever.
        </p>

        <form method="post">
            <div class="delete-actions">
                <button type="submit" class="btn danger"
                        onclick="return confirm('Are you absolutely sure? This cannot be undone.')">
                    Yes, Delete My Account
                </button>

                <a href="dashboard.php" class="btn secondary">
                    Cancel
                </a>
            </div>
        </form>

    </div>

</section>

<?php include '../includes/footer.php'; ?>
