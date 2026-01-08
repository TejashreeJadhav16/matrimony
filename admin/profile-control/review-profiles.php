<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   FETCH USERS WITH PROFILES
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.mobile, u.status, u.created_at,
        MAX(p.gender) AS gender,
        MAX(p.dob) AS dob,
        MAX(p.marital_status) AS marital_status,
        MAX(p.location) AS location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.status = 'approved'
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
table{width:100%;border-collapse:collapse;min-width:1000px}
th,td{padding:14px 12px;border-bottom:1px solid #eee;font-size:14px;text-align:left}
th{background:#f3e8f6;color:#4b0053}

.status{
    font-weight:600;
    text-transform:capitalize;
    color:#2e7d32;
}

.btn{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
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
.btn.edit{
    background:#1976d2;
    color:#fff;
}
.btn.edit:hover{
    background:#0d47a1;
}

</style>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN -->
    <main class="admin-main">

        <div class="card">

            <h2>Review Profiles</h2>
            <p class="sub">Review approved user profiles</p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Marital Status</th>
                            <th>Location</th>
                            <th>Profile</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($users) === 0): ?>
                            <tr>
                                <td colspan="9" style="text-align:center;color:#777">
                                    No profiles available
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
                                <td><?= $u['dob'] ?? '-' ?></td>
                                <td><?= $u['marital_status'] ?? '-' ?></td>
                                <td><?= $u['location'] ?? '-' ?></td>
                                <td>
    <a 
      href="/matrimony/admin/user-management/view-user.php?id=<?= $u['id'] ?>" 
      class="btn view">
      View
    </a>

    <a 
      href="/matrimony/admin/profile-control/edit-profile.php?id=<?= $u['id'] ?>" 
      class="btn edit">
      Edit
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
