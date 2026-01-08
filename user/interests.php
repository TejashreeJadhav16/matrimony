<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* RECEIVED INTERESTS */
$received = $conn->prepare("
    SELECT i.id, u.name, p.location, i.status
    FROM interests i
    JOIN users u ON i.sender_id = u.id
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE i.receiver_id = ?
    ORDER BY i.created_at DESC
");
$received->execute([$user_id]);
$receivedInterests = $received->fetchAll(PDO::FETCH_ASSOC);

/* SENT INTERESTS */
$sent = $conn->prepare("
    SELECT i.id, u.name, p.location, i.status
    FROM interests i
    JOIN users u ON i.receiver_id = u.id
    LEFT JOIN profiles p ON u.id = p.user_id
    WHERE i.sender_id = ?
    ORDER BY i.created_at DESC
");
$sent->execute([$user_id]);
$sentInterests = $sent->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* =====================
   INTERESTS PAGE
===================== */
.user-layout{display:flex;min-height:100vh;background:#f6f7fb}
.user-main{flex:1;padding:35px}

.card{
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 14px 35px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.card h2{
    color:#5a0c5f;
    margin-bottom:15px;
}

.interest-list{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.interest-card{
    background:#f9f9fb;
    padding:18px;
    border-radius:14px;
}

.interest-card h4{
    font-size:16px;
    margin-bottom:6px;
}

.interest-card p{
    font-size:13px;
    color:#666;
    margin-bottom:8px;
}

.status{
    font-size:12px;
    font-weight:600;
}

.status.sent{color:#1976d2}
.status.accepted{color:#2e7d32}
.status.rejected{color:#c62828}

.action-btns{
    margin-top:10px;
    display:flex;
    gap:8px;
}

.btn{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    border:none;
    cursor:pointer;
}

.btn.accept{background:#2e7d32;color:#fff}
.btn.reject{background:#c62828;color:#fff}

/* MOBILE */
@media(max-width:900px){
    .user-layout{flex-direction:column}
}
</style>

<div class="user-layout">

    <?php include 'sidebar.php'; ?>

    <main class="user-main">

        <!-- RECEIVED -->
        <div class="card">
            <h2>💌 Received Interests</h2>

            <div class="interest-list">
                <?php if(!$receivedInterests): ?>
                    <p>No interests received yet.</p>
                <?php endif; ?>

                <?php foreach($receivedInterests as $i): ?>
                    <div class="interest-card">
                        <h4><?= htmlspecialchars($i['name']) ?></h4>
                        <p>Location: <?= $i['location'] ?? 'Not specified' ?></p>

                        <span class="status <?= $i['status'] ?>">
                            <?= ucfirst($i['status']) ?>
                        </span>

                        <?php if($i['status'] === 'sent'): ?>
                            <div class="action-btns">
                                <form method="post" action="interest-action.php">
                                    <input type="hidden" name="id" value="<?= $i['id'] ?>">
                                    <input type="hidden" name="action" value="accepted">
                                    <button class="btn accept">Accept</button>
                                </form>

                                <form method="post" action="interest-action.php">
                                    <input type="hidden" name="id" value="<?= $i['id'] ?>">
                                    <input type="hidden" name="action" value="rejected">
                                    <button class="btn reject">Reject</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- SENT -->
        <div class="card">
            <h2>📤 Sent Interests</h2>

            <div class="interest-list">
                <?php if(!$sentInterests): ?>
                    <p>No interests sent yet.</p>
                <?php endif; ?>

                <?php foreach($sentInterests as $i): ?>
                    <div class="interest-card">
                        <h4><?= htmlspecialchars($i['name']) ?></h4>
                        <p>Location: <?= $i['location'] ?? 'Not specified' ?></p>

                        <span class="status <?= $i['status'] ?>">
                            <?= ucfirst($i['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
