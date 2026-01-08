<?php
require_once '../../includes/session.php';
require_once '../../includes/admin-auth.php';
require_once '../../includes/config.php';

/* =====================
   GET USER ID
===================== */
$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($userId <= 0) {
    echo "<p style='color:red;text-align:center'>Invalid user ID</p>";
    exit;
}

/* =====================
   FETCH USER + PROFILE
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.id, u.name, u.email, u.mobile,
        p.gender, p.dob, p.marital_status, p.location, p.bio
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.id = ?
    LIMIT 1
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p style='color:red;text-align:center'>User not found</p>";
    exit;
}

/* =====================
   SAVE PROFILE (ADMIN)
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $gender = $_POST['gender'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $marital_status = $_POST['marital_status'] ?? null;
    $location = $_POST['location'] ?? null;
    $bio = $_POST['bio'] ?? null;

    $stmt = $conn->prepare("
        INSERT INTO profiles (user_id, gender, dob, marital_status, location, bio)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            gender = VALUES(gender),
            dob = VALUES(dob),
            marital_status = VALUES(marital_status),
            location = VALUES(location),
            bio = VALUES(bio)
    ");
    $stmt->execute([$userId, $gender, $dob, $marital_status, $location, $bio]);

    header("Location: /matrimony/admin/profile-control/edit-profile.php?id=$userId&success=1");
    exit;
}
?>

<!-- HEADER -->
<?php include '../../includes/header.php'; ?>

<style>
.admin-layout{display:flex;min-height:100vh;background:#f6f7fb}
.admin-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
h2{color:#4b0053;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

form{max-width:600px}
label{font-size:13px;color:#555;display:block;margin-bottom:4px}
input,select,textarea{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:1px solid #ddd;
    margin-bottom:15px;
    font-size:14px
}

textarea{resize:vertical;min-height:80px}

.btn{
    padding:10px 20px;
    border-radius:30px;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    display:inline-block
}

.btn.save{
    background:#5a0c5f;
    color:#fff;
}
.btn.save:hover{
    background:#3d0040;
}

.success{
    background:#e8f5e9;
    color:#2e7d32;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px
}

.back{
    margin-left:10px;
    color:#5a0c5f;
    text-decoration:none;
    font-weight:600
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
            <h2>Edit Profile</h2>
            <p class="sub">Admin override profile details</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="success">✔ Profile updated successfully</div>
            <?php endif; ?>

            <form method="post">

                <label>Gender</label>
                <select name="gender">
                    <option value="">Select</option>
                    <option value="male" <?= $user['gender']=='male'?'selected':'' ?>>Male</option>
                    <option value="female" <?= $user['gender']=='female'?'selected':'' ?>>Female</option>
                    <option value="other" <?= $user['gender']=='other'?'selected':'' ?>>Other</option>
                </select>

                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?= $user['dob'] ?>">

                <label>Marital Status</label>
                <select name="marital_status">
                    <option value="">Select</option>
                    <option value="single" <?= $user['marital_status']=='single'?'selected':'' ?>>Single</option>
                    <option value="divorced" <?= $user['marital_status']=='divorced'?'selected':'' ?>>Divorced</option>
                    <option value="widowed" <?= $user['marital_status']=='widowed'?'selected':'' ?>>Widowed</option>
                </select>

                <label>Location</label>
                <input type="text" name="location" value="<?= htmlspecialchars($user['location']) ?>">

                <label>Bio</label>
                <textarea name="bio"><?= htmlspecialchars($user['bio']) ?></textarea>

                <button class="btn save">Save Changes</button>
                <a href="/matrimony/admin/profile-control/review-profiles.php" class="back">
                    Cancel
                </a>
            </form>

        </div>

    </main>
</div>

<!-- FOOTER -->
<?php include '../../includes/footer.php'; ?>
