<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

/* FETCH CURRENT SETTING */
$stmt = $conn->prepare("SELECT hide_contact FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* UPDATE SETTING */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hide = $_POST['hide_contact'];

    $conn->prepare("
        UPDATE users SET hide_contact = ? WHERE id = ?
    ")->execute([$hide, $user_id]);

    $_SESSION['success'] = "Contact visibility updated successfully";
    header("Location: hide-contact.php");
    exit;
}

include '../includes/header.php';
?>

<style>
.user-layout{display:flex;min-height:100vh;background:#f6f7fb}
.user-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:18px;box-shadow:0 14px 35px rgba(0,0,0,.1);max-width:600px}
h2{color:#5a0c5f;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}
.success{background:#e8f5e9;color:#2e7d32;padding:12px;border-radius:8px;margin-bottom:15px}
select{width:100%;padding:12px;border-radius:8px;border:1px solid #ddd}
.btn{margin-top:20px;padding:12px 30px;border-radius:30px;border:none;font-weight:600;background:#5a0c5f;color:#fff}
@media(max-width:900px){.user-layout{flex-direction:column}}
</style>

<div class="user-layout">
<?php include 'sidebar.php'; ?>

<main class="user-main">
<div class="card">

<h2>Hide Contact</h2>
<p class="sub">Control who can see your phone number or email</p>

<?php if(isset($_SESSION['success'])): ?>
<div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<form method="post">
    <label><strong>Contact Visibility</strong></label>
    <select name="hide_contact">
        <option value="no" <?= $user['hide_contact']=='no'?'selected':'' ?>>Show to matches</option>
        <option value="yes" <?= $user['hide_contact']=='yes'?'selected':'' ?>>Hide from everyone</option>
    </select>

    <button class="btn">Save Settings</button>
</form>

</div>
</main>
</div>

<?php include '../includes/footer.php'; ?>
