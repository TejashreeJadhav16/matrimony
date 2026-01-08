<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

/* =====================
   ONLY POST REQUEST
===================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: family-details.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =====================
   SANITIZE INPUTS
===================== */
$father_name = trim($_POST['father_name']);
$mother_name = trim($_POST['mother_name']);
$siblings    = $_POST['siblings'] ?? null;
$family_type = $_POST['family_type'] ?? null;

/* =====================
   BASIC VALIDATION
===================== */
if ($father_name === '' || $mother_name === '') {
    $_SESSION['error'] = "Father and Mother names are required";
    header("Location: family-details.php");
    exit;
}

/* =====================
   CHECK EXISTING RECORD
===================== */
$stmt = $conn->prepare("
    SELECT id FROM family_details 
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
        UPDATE family_details 
        SET 
            father_name = ?,
            mother_name = ?,
            siblings = ?,
            family_type = ?
        WHERE user_id = ?
    ");
    $stmt->execute([
        $father_name,
        $mother_name,
        $siblings,
        $family_type,
        $user_id
    ]);

} else {

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO family_details 
        (user_id, father_name, mother_name, siblings, family_type)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $father_name,
        $mother_name,
        $siblings,
        $family_type
    ]);
}

/* =====================
   SUCCESS REDIRECT
===================== */
$_SESSION['success'] = "Family details saved successfully";
header("Location: family-details.php");
exit;
