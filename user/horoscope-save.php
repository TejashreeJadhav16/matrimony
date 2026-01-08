<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

/* =====================
   ONLY POST REQUEST
===================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: horoscope.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =====================
   SANITIZE INPUTS
===================== */
$rashi     = $_POST['rashi'] ?? '';
$nakshatra = trim($_POST['nakshatra'] ?? '');
$manglik   = $_POST['manglik'] ?? '';

/* =====================
   BASIC VALIDATION
===================== */
if ($rashi === '' || $nakshatra === '' || $manglik === '') {
    $_SESSION['error'] = "All horoscope fields are required";
    header("Location: horoscope.php");
    exit;
}

/* =====================
   CHECK EXISTING RECORD
===================== */
$stmt = $conn->prepare("
    SELECT id FROM horoscope
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$exists = $stmt->fetch(PDO::FETCH_ASSOC);

/* =====================
   INSERT OR UPDATE
===================== */
if ($exists) {

    // UPDATE
    $stmt = $conn->prepare("
        UPDATE horoscope
        SET
            rashi = ?,
            nakshatra = ?,
            manglik = ?
        WHERE user_id = ?
    ");
    $stmt->execute([
        $rashi,
        $nakshatra,
        $manglik,
        $user_id
    ]);

} else {

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO horoscope
        (user_id, rashi, nakshatra, manglik)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $rashi,
        $nakshatra,
        $manglik
    ]);
}

/* =====================
   SUCCESS REDIRECT
===================== */
header("Location: horoscope.php?saved=1");
exit;
