<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'] ?? 0;

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
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    echo '<p style="color:red;text-align:center">Profile data not found.</p>';
    include '../includes/footer.php';
    exit;
}
?>

<style>
/* =====================
   LAYOUT
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
   MY PROFILE – UI
===================== */
.profile-card {
    max-width: 900px;
    background: #ffffff;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
}

.profile-title {
    color: #5a0c5f;
    font-size: 26px;
    margin-bottom: 25px;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.profile-field {
    background: #f6f7fb;
    padding: 14px 16px;
    border-radius: 10px;
}

.profile-field label {
    font-size: 12px;
    color: #777;
    display: block;
    margin-bottom: 4px;
}

.profile-field span {
    font-size: 15px;
    color: #333;
    font-weight: 500;
}

.profile-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.btn {
    padding: 12px 26px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
}

.btn.primary {
    background: #5a0c5f;
    color: #fff;
}

.btn.secondary {
    border: 2px solid #5a0c5f;
    color: #5a0c5f;
    background: transparent;
}

/* =====================
   RESPONSIVE
===================== */
@media (max-width: 900px) {
    .user-layout {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    .profile-actions {
        flex-direction: column;
    }
    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="user-layout">

    <!-- ✅ LEFT SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- ✅ MAIN CONTENT -->
    <main class="user-main">

        <div class="profile-card">

            <h2 class="profile-title">My Profile</h2>

            <div class="profile-grid">

                <div class="profile-field">
                    <label>Full Name</label>
                    <span><?= htmlspecialchars($profile['name'] ?? 'Not Provided') ?></span>
                </div>

                <div class="profile-field">
                    <label>Gender</label>
                    <span><?= htmlspecialchars($profile['gender'] ?? 'Not Provided') ?></span>
                </div>

                <div class="profile-field">
                    <label>Date of Birth</label>
                    <span><?= htmlspecialchars($profile['dob'] ?? 'Not Provided') ?></span>
                </div>

                <div class="profile-field">
                    <label>Marital Status</label>
                    <span><?= htmlspecialchars($profile['marital_status'] ?? 'Not Provided') ?></span>
                </div>

                <div class="profile-field">
                    <label>Location</label>
                    <span><?= htmlspecialchars($profile['location'] ?? 'Not Provided') ?></span>
                </div>

                <div class="profile-field">
                    <label>Contact</label>
                    <span><?= htmlspecialchars($profile['mobile'] ?? $profile['email'] ?? 'Not Provided') ?></span>
                </div>

            </div>

            <div class="profile-actions">
                <a href="edit-profile.php" class="btn primary">Edit Profile</a>
                <a href="dashboard.php" class="btn secondary">Back to Dashboard</a>
            </div>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
