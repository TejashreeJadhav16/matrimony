<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'] ?? 0;

/* FETCH USER + PROFILE */
$stmt = $conn->prepare("
    SELECT 
        u.name,
        u.email,
        u.mobile,
        p.gender,
        p.dob,
        p.marital_status,
        p.location
    FROM users u
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<p style='text-align:center;color:red'>Profile not found</p>";
    include '../includes/footer.php';
    exit;
}
?>

<style>
/* =====================
   USER LAYOUT
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

/* =====================
   EDIT PROFILE – PRO UI
===================== */
.edit-card {
    max-width: 900px;
    background: #ffffff;
    padding: 32px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
}

.edit-card h2 {
    color: #5a0c5f;
    margin-bottom: 10px;
    font-size: 26px;
}

.edit-card .sub-text {
    font-size: 14px;
    color: #777;
    margin-bottom: 25px;
}

/* GRID */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.form-group label {
    font-size: 13px;
    color: #555;
    margin-bottom: 6px;
    display: block;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

/* ACTIONS */
.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.btn {
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
}

.btn.primary {
    background: #5a0c5f;
    color: #ffffff;
    border: none;
}

.btn.secondary {
    border: 2px solid #5a0c5f;
    background: transparent;
    color: #5a0c5f;
}

/* SUCCESS */
.success-msg {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .user-layout {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="user-layout">

    <!-- ✅ SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- ✅ MAIN CONTENT -->
    <main class="user-main">

        <div class="edit-card">

            <h2>Edit Profile</h2>
            <p class="sub-text">Update your personal details</p>

            <?php if (isset($_GET['updated'])): ?>
                <div class="success-msg">✔ Profile updated successfully</div>
            <?php endif; ?>

            <form method="post" action="edit-profile-save.php">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name"
                               value="<?= htmlspecialchars($data['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required>
                            <option value="">Select</option>
                            <option <?= ($data['gender']=='Male')?'selected':'' ?>>Male</option>
                            <option <?= ($data['gender']=='Female')?'selected':'' ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob"
                               value="<?= htmlspecialchars($data['dob'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Marital Status *</label>
                        <select name="marital_status" required>
                            <option value="">Select</option>
                            <option <?= ($data['marital_status']=='Never Married')?'selected':'' ?>>Never Married</option>
                            <option <?= ($data['marital_status']=='Divorced')?'selected':'' ?>>Divorced</option>
                            <option <?= ($data['marital_status']=='Widowed')?'selected':'' ?>>Widowed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location"
                               value="<?= htmlspecialchars($data['location'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Contact</label>
                        <input type="text"
                               value="<?= htmlspecialchars($data['mobile'] ?? $data['email']) ?>"
                               disabled>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn primary">Save Changes</button>
                    <a href="my-profile.php" class="btn secondary">Cancel</a>
                </div>

            </form>

            <p style="margin-top:20px;font-size:13px;color:#777;text-align:center">
                💡 Keep your profile updated to get better matches
            </p>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
