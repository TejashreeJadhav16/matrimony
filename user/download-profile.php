<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

/* =====================
   FETCH ALL USER DATA
===================== */
$stmt = $conn->prepare("
SELECT 
    u.name, u.email, u.mobile, u.hide_contact,
    p.gender, p.dob, p.marital_status, p.location,
    f.father_name, f.mother_name, f.siblings, f.family_type,
    e.education, e.occupation, e.company, e.income,
    h.rashi, h.nakshatra, h.manglik,
    ex.age_range, ex.education_pref, ex.location_pref, ex.other_details
FROM users u
LEFT JOIN profiles p ON u.id = p.user_id
LEFT JOIN family_details f ON u.id = f.user_id
LEFT JOIN education_employment e ON u.id = e.user_id
LEFT JOIN horoscope h ON u.id = h.user_id
LEFT JOIN expectations ex ON u.id = ex.user_id
WHERE u.id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

/* PHOTOS */
$photos = $conn->prepare("
    SELECT photo_path, status FROM photos WHERE user_id = ?
");
$photos->execute([$user_id]);
$photos = $photos->fetchAll(PDO::FETCH_ASSOC);

/* DOCUMENTS */
$docs = $conn->prepare("
    SELECT document_type, verified FROM documents WHERE user_id = ?
");
$docs->execute([$user_id]);
$docs = $docs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($data['name']) ?> – Matrimony Profile</title>

<style>
body{font-family:Arial;background:#f6f7fb;padding:30px}
.sheet{max-width:900px;margin:auto;background:#fff;padding:30px;border-radius:16px}
h1{color:#5a0c5f}
.section{margin-bottom:25px}
.section h3{color:#5a0c5f;border-bottom:2px solid #eee;padding-bottom:6px}
.row{display:flex;margin-bottom:6px}
.label{width:220px;font-weight:600;color:#444}
.value{color:#333}
.photos{display:grid;grid-template-columns:repeat(auto-fit,120px);gap:12px}
.photos img{width:120px;height:150px;object-fit:cover;border-radius:10px}
.badge{font-size:12px;padding:3px 8px;border-radius:12px}
.pending{background:#fff3cd;color:#856404}
.verified{background:#d4edda;color:#155724}
.print{text-align:center;margin-top:30px}
button{background:#5a0c5f;color:#fff;border:none;padding:12px 30px;border-radius:30px}
@media print{button{display:none}body{background:#fff}}
.back-wrap{
    margin-bottom:20px;
}

.back-btn{
    background:#fff;
    border:2px solid #5a0c5f;
    color:#5a0c5f;
    padding:8px 18px;
    border-radius:30px;
    font-size:14px;
    cursor:pointer;
    font-weight:600;
}

.back-btn:hover{
    background:#5a0c5f;
    color:#fff;
}

</style>
</head>

<body>

<div class="sheet">

<h1><?= htmlspecialchars($data['name']) ?></h1>
<p>Karadi Samaaj Matrimony – Complete Profile</p>

<!-- PERSONAL -->
<div class="section">
<h3>Personal Details</h3>
<div class="row"><div class="label">Gender</div><div class="value"><?= $data['gender'] ?></div></div>
<div class="row"><div class="label">Date of Birth</div><div class="value"><?= $data['dob'] ?></div></div>
<div class="row"><div class="label">Marital Status</div><div class="value"><?= $data['marital_status'] ?></div></div>
<div class="row"><div class="label">Location</div><div class="value"><?= $data['location'] ?></div></div>
</div>

<!-- FAMILY -->
<div class="section">
<h3>Family Details</h3>
<div class="row"><div class="label">Father</div><div class="value"><?= $data['father_name'] ?></div></div>
<div class="row"><div class="label">Mother</div><div class="value"><?= $data['mother_name'] ?></div></div>
<div class="row"><div class="label">Siblings</div><div class="value"><?= $data['siblings'] ?></div></div>
</div>

<!-- EDUCATION -->
<div class="section">
<h3>Education & Employment</h3>
<div class="row"><div class="label">Education</div><div class="value"><?= $data['education'] ?></div></div>
<div class="row"><div class="label">Occupation</div><div class="value"><?= $data['occupation'] ?></div></div>
<div class="row"><div class="label">Company</div><div class="value"><?= $data['company'] ?></div></div>
<div class="row"><div class="label">Income</div><div class="value"><?= $data['income'] ?></div></div>
</div>

<!-- HOROSCOPE -->
<div class="section">
<h3>Horoscope</h3>
<div class="row"><div class="label">Rashi</div><div class="value"><?= $data['rashi'] ?></div></div>
<div class="row"><div class="label">Nakshatra</div><div class="value"><?= $data['nakshatra'] ?></div></div>
<div class="row"><div class="label">Manglik</div><div class="value"><?= $data['manglik'] ?></div></div>
</div>

<!-- EXPECTATIONS -->
<div class="section">
<h3>Partner Expectations</h3>
<div class="row"><div class="label">Age Range</div><div class="value"><?= $data['age_range'] ?></div></div>
<div class="row"><div class="label">Education</div><div class="value"><?= $data['education_pref'] ?></div></div>
<div class="row"><div class="label">Location</div><div class="value"><?= $data['location_pref'] ?></div></div>
<div class="row"><div class="label">Other</div><div class="value"><?= $data['other_details'] ?></div></div>
</div>

<!-- PHOTOS -->
<div class="section">
<h3>Photos</h3>
<div class="photos">
<?php foreach($photos as $p): ?>
<div>
<img src="../uploads/photos/<?= $p['photo_path'] ?>">
<div class="badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- DOCUMENTS -->
<div class="section">
<h3>Documents (Admin Only)</h3>
<?php foreach($docs as $d): ?>
<div class="row">
<div class="label"><?= ucfirst(str_replace('_',' ',$d['document_type'])) ?></div>
<div class="value"><span class="badge <?= $d['verified'] ?>"><?= ucfirst($d['verified']) ?></span></div>
</div>
<?php endforeach; ?>
</div>

<!-- CONTACT -->
<div class="section">
<h3>Contact</h3>
<?php if($data['hide_contact']=='yes'): ?>
<p>🔒 Contact hidden as per privacy settings</p>
<?php else: ?>
<p><?= $data['mobile'] ?> | <?= $data['email'] ?></p>
<?php endif; ?>
</div>

<div class="print">
<button onclick="window.print()">⬇️ Download / Save as PDF</button>
</div>
<div class="back-wrap">
    <button class="back-btn" onclick="history.back()">← Back</button>
</div>

</div>
</body>
</html>
