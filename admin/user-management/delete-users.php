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
   DELETE ACTION (SOFT DELETE)
===================== */
if (isset($_GET['delete'], $_GET['token'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_GET['token'])) {
        die('Invalid request');
    }

    $userId = (int) $_GET['delete'];

    // Soft delete user
    $stmt = $conn->prepare(
        "UPDATE users 
         SET status = 'deleted' 
         WHERE id = ?"
    );
    $stmt->execute([$userId]);

    header("Location: /matrimony/admin/user-management/delete-users.php?success=1");
    exit;
}

/* =====================
   FETCH USERS (NOT DELETED)
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.mobile, u.status, u.created_at,
        MAX(p.gender) AS gender,
        MAX(p.location) AS location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.status != 'deleted'
    GROUP BY u.id
    ORDER BY u.created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HEADER -->
<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:900px}
th,td{padding:14px 12px;border-bottom:1px solid #eee;font-size:14px;text-align:left}
th{background:#f3e8f6;color:#4b0053}

.btn{
    padding:8px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-block;
}

.btn.delete{
    background:#d32f2f;
    color:#fff;
}
.btn.delete:hover{
    background:#b71c1c;
}

.success{
    background:#ffebee;
    color:#b71c1c;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px;
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

            <h2>Delete Users</h2>
            <p class="sub">Soft delete user accounts (recoverable)</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="success">🗑 User deleted successfully</div>
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
                            <th>Status</th>
                            <th>Joined On</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($users) === 0): ?>
                            <tr>
                                <td colspan="9" style="text-align:center;color:#777">
                                    No users available
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
                                <td><?= ucfirst($u['status']) ?></td>
                                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                                <td>
                                    <a
                                        href="/matrimony/admin/user-management/delete-users.php?delete=<?= $u['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>"
                                        class="btn delete"
                                        onclick="return confirm('This will delete the user account. Continue?')">
                                        Delete
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

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
