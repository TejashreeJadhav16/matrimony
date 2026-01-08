<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

/* =====================
   ONLY POST REQUEST
===================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: education-employment.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =====================
   SANITIZE INPUTS
===================== */
$education  = $_POST['education'] ?? '';
$occupation = $_POST['occupation'] ?? '';
$company    = trim($_POST['company'] ?? '');
$income     = trim($_POST['income'] ?? '');

/* =====================
   BASIC VALIDATION
===================== */
if ($education === '' || $occupation === '') {
    $_SESSION['error'] = "Education and occupation are required";
    header("Location: education-employment.php");
    exit;
}

/* =====================
   CHECK EXISTING RECORD
===================== */
$stmt = $conn->prepare("
    SELECT id FROM education_employment
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
        UPDATE education_employment
        SET
            education = ?,
            occupation = ?,
            company = ?,
            income = ?
        WHERE user_id = ?
    ");
    $stmt->execute([
        $education,
        $occupation,
        $company,
        $income,
        $user_id
    ]);

} else {

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO education_employment
        (user_id, education, occupation, company, income)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $education,
        $occupation,
        $company,
        $income
    ]);
}

/* =====================
   SUCCESS REDIRECT
===================== */
header("Location: education-employment.php?saved=1");
exit;
