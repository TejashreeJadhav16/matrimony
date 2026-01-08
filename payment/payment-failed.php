<?php
require_once '../includes/config.php';
require_once '../includes/session.php';
require_once '../includes/header.php';

/* =========================
   UPDATE PAYMENT STATUS
========================= */
if (isset($_SESSION['user_id'])) {

    $stmt = $conn->prepare("
        UPDATE payments
        SET payment_status = 'failed'
        WHERE user_id = ?
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt->execute([$_SESSION['user_id']]);
}
?>

<section class="container" style="padding:80px 20px; text-align:center;">

    <h2 style="color:#c62828;">Payment Failed ❌</h2>

    <p style="margin-top:15px; font-size:15px;">
        Your registration payment could not be completed.
    </p>

    <p style="font-size:14px; color:#555;">
        If any amount was deducted, it will be refunded within 5–7 working days.
    </p>

    <div style="margin-top:30px;">
        <a href="../auth/register.php" class="btn secondary">Try Again</a>
        <a href="../contact.php" class="btn primary">Contact Support</a>
    </div>

</section>

<?php require_once '../includes/footer.php'; ?>
