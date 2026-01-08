<?php
require_once '../includes/session.php';
require_once '../includes/auth-check.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id  = $_SESSION['user_id'];
$match_id = $_GET['match_id'] ?? 0;

/* VERIFY MATCH */
$stmt = $conn->prepare("
    SELECT * FROM matches
    WHERE id = ? AND (user_one = ? OR user_two = ?)
");
$stmt->execute([$match_id, $user_id, $user_id]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    echo "<p style='text-align:center;color:red'>Invalid or unauthorized chat.</p>";
    include '../includes/footer.php';
    exit;
}

/* FETCH MESSAGES */
$msgStmt = $conn->prepare("
    SELECT sender_id, message, sent_at
    FROM chats
    WHERE match_id = ?
    ORDER BY sent_at ASC
");
$msgStmt->execute([$match_id]);
$messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* =====================
   CHAT PAGE
===================== */
.user-layout{display:flex;min-height:100vh;background:#f6f7fb}
.user-main{flex:1;padding:30px}

.chat-card{
    background:#fff;
    padding:25px;
    border-radius:18px;
    box-shadow:0 14px 35px rgba(0,0,0,0.08);
    max-width:900px;
    margin:auto;
    display:flex;
    flex-direction:column;
    height:80vh;
}

.chat-title{
    color:#5a0c5f;
    font-size:22px;
    margin-bottom:15px;
}

/* CHAT BOX */
.chat-box{
    flex:1;
    overflow-y:auto;
    padding:15px;
    background:#f9f9fb;
    border-radius:12px;
    margin-bottom:15px;
}

/* MESSAGE */
.msg{
    max-width:70%;
    padding:10px 14px;
    border-radius:14px;
    margin-bottom:10px;
    font-size:14px;
    line-height:1.5;
}

.msg.me{
    background:#5a0c5f;
    color:#fff;
    margin-left:auto;
    border-bottom-right-radius:4px;
}

.msg.other{
    background:#e0e0e0;
    color:#333;
    border-bottom-left-radius:4px;
}

.time{
    font-size:11px;
    opacity:.7;
    margin-top:4px;
}

/* INPUT */
.chat-form{
    display:flex;
    gap:10px;
}

.chat-form input{
    flex:1;
    padding:12px;
    border-radius:25px;
    border:1px solid #ddd;
}

.chat-form button{
    background:#5a0c5f;
    color:#fff;
    border:none;
    border-radius:25px;
    padding:12px 22px;
    cursor:pointer;
}

/* MOBILE */
@media(max-width:900px){
    .user-layout{flex-direction:column}
    .chat-card{height:85vh}
}
</style>

<div class="user-layout">

    <?php include 'sidebar.php'; ?>

    <main class="user-main">

        <div class="chat-card">
            <h2 class="chat-title">💬 Chat</h2>

            <div class="chat-box">
                <?php if(!$messages): ?>
                    <p style="color:#777;font-size:13px">Start the conversation 👋</p>
                <?php endif; ?>

                <?php foreach($messages as $m): ?>
                    <div class="msg <?= $m['sender_id']==$user_id?'me':'other' ?>">
                        <?= htmlspecialchars($m['message']) ?>
                        <div class="time">
                            <?= date('d M, h:i A', strtotime($m['sent_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="post" action="chat-send.php" class="chat-form">
                <input type="hidden" name="match_id" value="<?= $match_id ?>">
                <input type="text" name="message" placeholder="Type your message..." required>
                <button type="submit">Send</button>
            </form>

        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
