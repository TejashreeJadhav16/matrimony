<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

/* UNBLOCK */
if (isset($_POST['unblock_id'])) {
    $conn->prepare("
        DELETE FROM blocked_users 
        WHERE blocker_id = ? AND blocked_id = ?
    ")->execute([$user_id, $_POST['unblock_id']]);

    $_SESSION['success'] = "User unblocked successfully";
    header("Location: blocked-users.php");
    exit;
}

/* FETCH BLOCKED USERS */
$stmt = $conn->prepare("
    SELECT u.id, u.name, p.gender
    FROM blocked_users b
    JOIN users u ON b.blocked_id = u.id
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE b.blocker_id = ?
");
$stmt->execute([$user_id]);
$blocked = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<style>
.user-layout{display:flex;min-height:100vh;background:#f6f7fb}
.user-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:18px;box-shadow:0 14px 35px rgba(0,0,0,.1)}
h2{color:#5a0c5f;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}
.success{background:#e8f5e9;color:#2e7d32;padding:12px;border-radius:8px;margin-bottom:15px}
.user-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px}
.user-box{background:#f6f7fb;padding:18px;border-radius:14px}
.user-box h4{margin-bottom:6px}
.btn-small{background:#c62828;color:#fff;border:none;padding:6px 14px;border-radius:20px;font-size:12px;cursor:pointer}
@media(max-width:900px){.user-layout{flex-direction:column}}
</style>

<div class="user-layout">
<?php include 'sidebar.php'; ?>

<main class="user-main">
<div class="card">

<h2>Blocked Users</h2>
<p class="sub">Users you have blocked will not be able to contact you</p>

<?php if(isset($_SESSION['success'])): ?>
<div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="user-list">

<?php if(count($blocked)==0): ?>
<p style="color:#777">You have not blocked any users.</p>
<?php endif; ?>

<?php foreach($blocked as $b): ?>
<div class="user-box">
    <h4><?= htmlspecialchars($b['name']) ?></h4>
    <p><?= $b['gender'] ?? '—' ?></p>

    <form method="post">
        <input type="hidden" name="unblock_id" value="<?= $b['id'] ?>">
        <button class="btn-small">Unblock</button>
    </form>
</div>
<?php endforeach; ?>

</div>
</div>
</main>
</div>

<?php include '../includes/footer.php'; ?>
