<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

/* FETCH SETTINGS */
$stmt = $conn->prepare("
    SELECT * FROM privacy_settings WHERE user_id = ?
");
$stmt->execute([$user_id]);
$privacy = $stmt->fetch(PDO::FETCH_ASSOC);

/* DEFAULT INSERT */
if (!$privacy) {
    $conn->prepare("
        INSERT INTO privacy_settings (user_id) VALUES (?)
    ")->execute([$user_id]);

    $stmt->execute([$user_id]);
    $privacy = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* SAVE SETTINGS */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE privacy_settings SET
            hide_contact = ?,
            profile_visibility = ?,
            allow_messages = ?
        WHERE user_id = ?
    ");

    $stmt->execute([
        $_POST['hide_contact'],
        $_POST['profile_visibility'],
        $_POST['allow_messages'],
        $user_id
    ]);

    $_SESSION['success'] = "Privacy settings updated successfully";
    header("Location: privacy-settings.php");
    exit;
}

include '../includes/header.php';
?>

<style>
/* =====================
   PRIVACY SETTINGS
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
    padding: 32px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
    max-width: 900px;
}

.card h2 {
    color: #5a0c5f;
    margin-bottom: 10px;
}

.sub-text {
    font-size: 14px;
    color: #777;
    margin-bottom: 25px;
}

.success-msg {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
}

/* FORM */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    color: #444;
    display: block;
    margin-bottom: 6px;
}

.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.btn {
    padding: 12px 30px;
    border-radius: 30px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.btn.primary {
    background: #5a0c5f;
    color: #fff;
}

/* MOBILE */
@media (max-width: 900px) {
    .user-layout {
        flex-direction: column;
    }
}
</style>

<div class="user-layout">

    <?php include 'sidebar.php'; ?>

    <main class="user-main">

        <div class="card">

            <h2>Privacy Settings</h2>
            <p class="sub-text">
                Control who can view your profile and contact you.
            </p>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-msg">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <!-- HIDE CONTACT -->
                <div class="form-group">
                    <label>Hide Contact Number</label>
                    <select name="hide_contact">
                        <option value="no" <?= $privacy['hide_contact']=='no'?'selected':'' ?>>Show to matches</option>
                        <option value="yes" <?= $privacy['hide_contact']=='yes'?'selected':'' ?>>Hide from all</option>
                    </select>
                </div>

                <!-- PROFILE VISIBILITY -->
                <div class="form-group">
                    <label>Profile Visibility</label>
                    <select name="profile_visibility">
                        <option value="public" <?= $privacy['profile_visibility']=='public'?'selected':'' ?>>Public</option>
                        <option value="members" <?= $privacy['profile_visibility']=='members'?'selected':'' ?>>Only Members</option>
                        <option value="hidden" <?= $privacy['profile_visibility']=='hidden'?'selected':'' ?>>Hidden</option>
                    </select>
                </div>

                <!-- ALLOW MESSAGES -->
                <div class="form-group">
                    <label>Allow Messages</label>
                    <select name="allow_messages">
                        <option value="yes" <?= $privacy['allow_messages']=='yes'?'selected':'' ?>>Allow</option>
                        <option value="no" <?= $privacy['allow_messages']=='no'?'selected':'' ?>>Disable</option>
                    </select>
                </div>

                <button type="submit" class="btn primary">
                    Save Privacy Settings
                </button>

            </form>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
