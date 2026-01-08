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
   ACTION HANDLER
===================== */
if (isset($_GET['action'], $_GET['id'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $userId = (int) $_GET['id'];
    $action = $_GET['action'];

    $allowedActions = [
        'approve'  => 'approved',
        'reject'   => 'rejected',
        'suspend'  => 'suspended'
    ];

    if (array_key_exists($action, $allowedActions)) {

        $stmt = $conn->prepare("
            UPDATE users 
            SET status = ? 
            WHERE id = ?
        ");
        $stmt->execute([$allowedActions[$action], $userId]);

        header("Location: approve-users.php?{$action}=1");
        exit;
    }
}

/* =====================
   FETCH PENDING USERS
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.mobile, u.created_at,
        p.gender, p.location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.status = 'pending'
    ORDER BY u.created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:900px}
th,td{padding:14px 12px;border-bottom:1px solid #eee;font-size:14px}
th{background:#f3e8f6;color:#4b0053}

.status{font-weight:600;color:#ff9800}

.btn{
    padding:7px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    margin-right:6px;
}

.btn.approve{background:#2e7d32;color:#fff}
.btn.reject{background:#c62828;color:#fff}
.btn.suspend{background:#ff9800;color:#fff}

.alert{
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px;
}
.success{background:#e8f5e9;color:#2e7d32}
.error{background:#ffebee;color:#c62828}
.warning{background:#fff8e1;color:#ff9800}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
</style>

<div class="admin-layout">

<?php include '../sidebar.php'; ?>

<main class="admin-main">

<div class="card">

<h2>Approve Users</h2>
<p class="sub">Approve, reject, or suspend newly registered users</p>

<?php if (isset($_GET['approve'])): ?>
    <div class="alert success">✔ User approved successfully</div>
<?php endif; ?>

<?php if (isset($_GET['reject'])): ?>
    <div class="alert error">❌ User rejected</div>
<?php endif; ?>

<?php if (isset($_GET['suspend'])): ?>
    <div class="alert warning">⚠ User suspended</div>
<?php endif; ?>

<div class="table-wrap">
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Gender</th>
    <th>Location</th>
    <th>Joined On</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if (count($users) === 0): ?>
<tr>
    <td colspan="9" style="text-align:center;color:#777">
        No pending users
    </td>
</tr>
<?php endif; ?>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['mobile']) ?></td>
    <td><?= $u['gender'] ?? '-' ?></td>
    <td><?= $u['location'] ?? '-' ?></td>
    <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
    <td class="status">Pending</td>
    <td>

        <a href="?action=approve&id=<?= $u['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"
           class="btn approve"
           onclick="return confirm('Approve this user?')">
           Approve
        </a>

        <a href="?action=reject&id=<?= $u['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"
           class="btn reject"
           onclick="return confirm('Reject this user?')">
           Reject
        </a>

        <a href="?action=suspend&id=<?= $u['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"
           class="btn suspend"
           onclick="return confirm('Suspend this user?')">
           Suspend
        </a>

    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

</div>
</main>
</div>

<?php include '../../includes/footer.php'; ?>
