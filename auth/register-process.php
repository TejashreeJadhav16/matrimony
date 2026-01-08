<?php
require_once '../includes/config.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

/* CREATE USER */
$login_id = sanitize($_POST['login_id']);
$email  = filter_var($login_id, FILTER_VALIDATE_EMAIL) ? $login_id : null;
$mobile = !$email ? $login_id : null;

$stmt = $conn->prepare("
    INSERT INTO users (name, email, mobile, password)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([
    sanitize($_POST['full_name']),
    $email,
    $mobile,
    hashPassword($_POST['password'])
]);

$user_id = $conn->lastInsertId();
$_SESSION['user_id'] = $user_id;

/* PROFILE */
$conn->prepare("
    INSERT INTO profiles (user_id, gender, dob, marital_status)
    VALUES (?, ?, ?, ?)
")->execute([
    $user_id,
    $_POST['gender'],
    $_POST['dob'],
    $_POST['marital_status']
]);

/* FAMILY */
$conn->prepare("
    INSERT INTO family_details (user_id, father_name, mother_name)
    VALUES (?, ?, ?)
")->execute([
    $user_id,
    sanitize($_POST['father_name']),
    sanitize($_POST['mother_name'])
]);

/* EDUCATION */
$conn->prepare("
    INSERT INTO education_employment (user_id, education, occupation)
    VALUES (?, ?, ?)
")->execute([
    $user_id,
    $_POST['education'],
    $_POST['employment']
]);

/* PHOTO */
$photo = time().'_'.$_FILES['profile_photo']['name'];
move_uploaded_file($_FILES['profile_photo']['tmp_name'], "../uploads/photos/$photo");
$conn->prepare("
    INSERT INTO photos (user_id, photo_path)
    VALUES (?, ?)
")->execute([$user_id, $photo]);

/* DOCUMENTS */
foreach (['aadhaar','caste_certificate'] as $doc) {
    $file = time().'_'.$doc.'_'.$_FILES[$doc]['name'];
    move_uploaded_file($_FILES[$doc]['tmp_name'], "../uploads/documents/$file");

    $conn->prepare("
        INSERT INTO documents (user_id, document_type, document_path)
        VALUES (?, ?, ?)
    ")->execute([$user_id, $doc, $file]);
}

/* GO TO PAYMENT */
header("Location: ../payment/payment-init.php");
exit;
