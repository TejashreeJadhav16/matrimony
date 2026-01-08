<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   FETCH ALL USERS
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.mobile, u.status, u.created_at,
        p.gender, p.location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
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

.status{
    font-weight:600;
    text-transform:capitalize;
}
.status.pending{color:#ff9800}
.status.approved{color:#2e7d32}
.status.suspended{color:#c62828}

.btn{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    text-decoration:none;
    font-weight:600;
    display:inline-block;
}
.btn.view{
    background:#5a0c5f;
    color:#fff;
}
.btn.view:hover{
    background:#3d0040;
}

@media(max-width:900px){
    .admin-layout{flex-direction:column}
}
</style>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <div class="card">
            <h2>All Users</h2>
            <p class="sub">View all registered users</p>

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
                                    No users found
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
                                <td class="status <?= $u['status'] ?>">
                                    <?= ucfirst($u['status']) ?>
                                </td>
                                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                                <td>
                                    <!-- ✅ CORRECT VIEW LINK -->
                                    <a 
                                      href="/matrimony/admin/user-management/view-user.php?id=<?= $u['id'] ?>" 
                                      class="btn view">
                                      View
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
