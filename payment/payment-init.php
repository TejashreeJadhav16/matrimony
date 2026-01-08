<?php
require_once 'includes/config.php';
require_once 'includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$amount  = 500; // ₹500 compulsory

/* SAVE PAYMENT INITIALLY */
$stmt = $conn->prepare("
    INSERT INTO payments (user_id, amount, payment_status)
    VALUES (?, ?, 'initiated')
");
$stmt->execute([$user_id, $amount]);

$payment_db_id = $conn->lastInsertId();
?>

<!DOCTYPE html>
<html>
<head>
<title>Complete Payment</title>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body onload="payNow()">

<script>
function payNow() {
    var options = {
        "key": "rzp_test_xxxxx", // 🔴 replace
        "amount": "<?= $amount * 100 ?>",
        "currency": "INR",
        "name": "Karadi Samaj Matrimony",
        "description": "Registration Fee",
        "handler": function (response) {
            window.location.href =
                "payment-success.php?pid=<?= $payment_db_id ?>&pay_id=" + response.razorpay_payment_id;
        },
        "theme": { "color": "#5a0c5f" }
    };
    var rzp = new Razorpay(options);
    rzp.open();
}
</script>

</body>
</html>
