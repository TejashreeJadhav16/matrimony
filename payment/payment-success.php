<?php
require_once 'includes/config.php';
require_once 'includes/session.php';

if (!isset($_GET['pid'], $_GET['pay_id'], $_SESSION['user_id'])) {
    die("Invalid payment request");
}

$payment_db_id = (int)$_GET['pid'];
$payment_id    = $_GET['pay_id'];
$user_id       = $_SESSION['user_id'];

/* UPDATE PAYMENT */
$stmt = $conn->prepare("
    UPDATE payments 
    SET payment_status = 'success',
        payment_id = ?
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$payment_id, $payment_db_id, $user_id]);

/* UPDATE USER STATUS */
$stmt = $conn->prepare("
    UPDATE users 
    SET status = 'pending'
    WHERE id = ?
");
$stmt->execute([$user_id]);

header("Location: payment-success-page.php");
exit;
