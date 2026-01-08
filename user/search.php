<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

/* FETCH CURRENT USER (FOR BLOCK & PRIVACY) */
$currentUser = $conn->prepare("SELECT hide_contact FROM users WHERE id = ?");
$currentUser->execute([$user_id]);
$self = $currentUser->fetch(PDO::FETCH_ASSOC);

/* FILTERS */
$gender   = $_GET['gender']   ?? '';
$location = $_GET['location'] ?? '';

/* SEARCH QUERY */
$sql = "
SELECT 
    u.id, u.name, u.hide_contact,
    p.gender, p.dob, p.location
FROM users u
JOIN profiles p ON u.id = p.user_id
WHERE u.id != ?
AND u.status = 'approved'
AND u.id NOT IN (
    SELECT blocked_id FROM blocked_users WHERE blocker_id = ?
)
";

$params = [$user_id, $user_id];

if ($gender) {
    $sql .= " AND p.gender = ?";
    $params[] = $gender;
}

if ($location) {
    $sql .= " AND p.location LIKE ?";
    $params[] = "%$location%";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<style>
.user-layout{display:flex;min-height:100vh;background:#f6f7fb}
.user-main{flex:1;padding:30px}
.card{background:#fff;padding:30px;border-radius:18px;box-shadow:0 14px 35px rgba(0,0,0,.1)}
h2{color:#5a0c5f;margin-bottom:10px}
.sub{font-size:14px;color:#777;margin-bottom:20px}

/* FILTERS */
.filter-box{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:30px}
.filter-box select,.filter-box input{padding:12px;border-radius:8px;border:1px solid #ddd}

/* RESULTS */
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
.profile{background:#f6f7fb;padding:20px;border-radius:16px}
.profile h4{margin-bottom:6px}
.meta{font-size:13px;color:#666;margin-bottom:6px}
.actions{margin-top:10px;display:flex;gap:10px}
.btn{padding:8px 16px;border-radius:20px;font-size:13px;text-decoration:none}
.btn.primary{background:#5a0c5f;color:#fff}
.btn.outline{border:2px solid #5a0c5f;color:#5a0c5f;background:transparent}

/* MOBILE */
@media(max-width:900px){
.user-layout{flex-direction:column}
}
</style>

<div class="user-layout">
<?php include 'sidebar.php'; ?>

<main class="user-main">
<div class="card">

<h2>Search Matches</h2>
<p class="sub">Find suitable profiles based on your preferences</p>

<!-- FILTER FORM -->
<form class="filter-box" method="get">
    <select name="gender">
        <option value="">Gender</option>
        <option value="Male" <?= $gender=='Male'?'selected':'' ?>>Male</option>
        <option value="Female" <?= $gender=='Female'?'selected':'' ?>>Female</option>
    </select>

    <input type="text" name="location" placeholder="Location"
           value="<?= htmlspecialchars($location) ?>">

    <button class="btn primary">Search</button>
</form>

<div class="grid">

<?php if(count($results) === 0): ?>
    <p style="color:#777">No profiles found.</p>
<?php endif; ?>

<?php foreach($results as $r): ?>
<div class="profile">

    <h4><?= htmlspecialchars($r['name']) ?></h4>

    <div class="meta">Gender: <?= htmlspecialchars($r['gender']) ?></div>
    <div class="meta">Location: <?= htmlspecialchars($r['location'] ?? 'Not specified') ?></div>

    <?php if ($r['hide_contact'] === 'no'): ?>
        <div class="meta">📞 Contact visible after match</div>
    <?php else: ?>
        <div class="meta">🔒 Contact hidden</div>
    <?php endif; ?>

    <div class="actions">

        <!-- VIEW PROFILE -->
        <a href="view-profile.php?id=<?= $r['id'] ?>" class="btn outline">
            View
        </a>

        <!-- SEND INTEREST (CORRECT) -->
        <form method="post" action="send-interest.php" style="display:inline">
            <input type="hidden" name="receiver_id" value="<?= $r['id'] ?>">
            <button type="submit" class="btn primary">
                ❤️ Send Interest
            </button>
        </form>

    </div>

</div>
<?php endforeach; ?>

</div>


</div>
</main>
</div>

<?php include '../includes/footer.php'; ?>
