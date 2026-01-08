<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

/* =====================
   ONLY POST REQUEST
===================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: expectations.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =====================
   SANITIZE INPUTS
===================== */
$age_range      = trim($_POST['age_range'] ?? '');
$education_pref = $_POST['education_pref'] ?? '';
$location_pref  = trim($_POST['location_pref'] ?? '');
$other_details  = trim($_POST['other_details'] ?? '');

/* =====================
   BASIC VALIDATION
===================== */
if ($age_range === '' || $education_pref === '' || $location_pref === '') {
    $_SESSION['error'] = "All required fields must be filled";
    header("Location: expectations.php");
    exit;
}

/* =====================
   CHECK EXISTING RECORD
===================== */
$stmt = $conn->prepare("
    SELECT id FROM expectations
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
        UPDATE expectations
        SET 
            age_range = ?,
            education_pref = ?,
            location_pref = ?,
            other_details = ?
        WHERE user_id = ?
    ");
    $stmt->execute([
        $age_range,
        $education_pref,
        $location_pref,
        $other_details,
        $user_id
    ]);

} else {

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO expectations
        (user_id, age_range, education_pref, location_pref, other_details)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $age_range,
        $education_pref,
        $location_pref,
        $other_details
    ]);
}

/* =====================
   SUCCESS REDIRECT
===================== */
header("Location: expectations.php?saved=1");
exit;
