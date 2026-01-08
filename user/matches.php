<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* FETCH MATCHES */
$stmt = $conn->prepare("
    SELECT 
        m.id AS match_id,
        u.id AS partner_id,
        u.name,
        p.location,
        p.gender
    FROM matches m
    JOIN users u 
        ON (u.id = m.user_one AND m.user_two = ?)
        OR (u.id = m.user_two AND m.user_one = ?)
    LEFT JOIN profiles p ON u.id = p.user_id
    ORDER BY m.matched_at DESC
");
$stmt->execute([$user_id, $user_id]);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* =====================
   MATCHES PAGE
===================== */
.user-layout{
    display:flex;
    min-height:100vh;
    background:#f6f7fb;
}

.user-main{
    flex:1;
    padding:35px;
}

.card{
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 14px 35px rgba(0,0,0,0.08);
}

.card h2{
    color:#5a0c5f;
    margin-bottom:20px;
}

.match-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:22px;
}

.match-card{
    background:#f9f9fb;
    padding:20px;
    border-radius:14px;
}

.match-card h4{
    font-size:17px;
    margin-bottom:6px;
}

.match-card p{
    font-size:13px;
    color:#666;
    margin-bottom:10px;
}

.actions{
    display:flex;
    gap:10px;
    margin-top:12px;
}

.btn{
    padding:8px 16px;
    border-radius:20px;
    font-size:13px;
    text-decoration:none;
    font-weight:600;
}

.btn.chat{
    background:#5a0c5f;
    color:#fff;
}

.btn.view{
    border:2px solid #5a0c5f;
    color:#5a0c5f;
}

/* MOBILE */
@media(max-width:900px){
    .user-layout{flex-direction:column}
}
</style>

<div class="user-layout">

    <?php include 'sidebar.php'; ?>

    <main class="user-main">

        <div class="card">
            <h2>❤️ My Matches</h2>

            <?php if(!$matches): ?>
                <p style="color:#777;font-size:14px">
                    No matches yet. Accept interests to get matches.
                </p>
            <?php endif; ?>

            <div class="match-grid">

                <?php foreach($matches as $m): ?>
                    <div class="match-card">
                        <h4><?= htmlspecialchars($m['name']) ?></h4>
                        <p>
                            <?= $m['gender'] ?? 'Gender not specified' ?> |
                            <?= $m['location'] ?? 'Location not specified' ?>
                        </p>

                        <div class="actions">
                            <a href="chat.php?match_id=<?= $m['match_id'] ?>" class="btn chat">
                                💬 Chat
                            </a>

                            <a href="my-profile.php?id=<?= $m['partner_id'] ?>" class="btn view">
                                👁 View Profile
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
