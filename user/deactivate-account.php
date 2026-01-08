<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}

/* =====================
   HANDLE DEACTIVATION
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update user status to deactivated
    $stmt = $conn->prepare("
        UPDATE users 
        SET status = 'suspended'
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);

    // Destroy session
    session_unset();
    session_destroy();

    header("Location: ../auth/login.php?deactivated=1");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<style>
/* =====================
   DEACTIVATE ACCOUNT UI
===================== */
.deactivate-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.deactivate-card {
    background: #fff;
    max-width: 520px;
    padding: 36px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
    text-align: center;
}

.deactivate-card h2 {
    color: #ff9800;
    margin-bottom: 12px;
}

.deactivate-card p {
    font-size: 14px;
    color: #555;
    margin-bottom: 25px;
    line-height: 1.6;
}

.deactivate-actions {
    display: flex;
    gap: 15px;
}

.btn {
    padding: 12px 26px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn.warning {
    background: #ff9800;
    color: #fff;
}

.btn.secondary {
    background: #eee;
    color: #333;
}

@media (max-width: 600px) {
    .deactivate-actions {
        flex-direction: column;
    }
    .btn {
        width: 100%;
    }
}
</style>

<section class="deactivate-page container">

    <div class="deactivate-card">

        <h2>Deactivate Account</h2>

        <p>
            ⚠ Your account will be <strong>temporarily disabled</strong>.<br><br>
            • Your profile will be hidden from search  
            • Matches & chat will be paused  
            • You can reactivate by logging in again  
        </p>

        <form method="post">
            <div class="deactivate-actions">
                <button type="submit"
                        class="btn warning"
                        onclick="return confirm('Do you want to temporarily deactivate your account?')">
                    Deactivate My Account
                </button>

                <a href="dashboard.php" class="btn secondary">
                    Cancel
                </a>
            </div>
        </form>

    </div>

</section>

<?php include '../includes/footer.php'; ?>
