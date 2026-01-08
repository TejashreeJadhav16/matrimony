<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

/* =====================
   VALIDATE REQUEST
===================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('edit-profile.php');
}

$user_id = $_SESSION['user_id'];

/* =====================
   SANITIZE INPUTS
===================== */
$name           = sanitize($_POST['name']);
$gender         = $_POST['gender'] ?? null;
$dob            = $_POST['dob'] ?? null;
$marital_status = $_POST['marital_status'] ?? null;
$location       = sanitize($_POST['location'] ?? null);

/* =====================
   UPDATE USERS TABLE
===================== */
$stmt = $conn->prepare("
    UPDATE users 
    SET name = ?
    WHERE id = ?
");
$stmt->execute([$name, $user_id]);

/* =====================
   UPDATE / INSERT PROFILE
   (SAFE UPSERT)
===================== */
$stmt = $conn->prepare("
    INSERT INTO profiles (
        user_id,
        gender,
        dob,
        marital_status,
        location
    )
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        gender = VALUES(gender),
        dob = VALUES(dob),
        marital_status = VALUES(marital_status),
        location = VALUES(location)
");

$stmt->execute([
    $user_id,
    $gender,
    $dob,
    $marital_status,
    $location
]);

/* =====================
   SUCCESS REDIRECT
===================== */
redirect('my-profile.php?updated=1');
