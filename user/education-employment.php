<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'] ?? 0;

/* FETCH EXISTING DATA */
$stmt = $conn->prepare("
    SELECT education, occupation, company, income
    FROM education_employment
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
/* =====================
   PAGE LAYOUT
===================== */
.user-page {
    display: flex;
    min-height: 100vh;
    background: #f6f7fb;
}

.page-content {
    flex: 1;
    padding: 40px 20px;
}

/* =====================
   CARD
===================== */
.card {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.1);
}

.card h2 {
    color: #5a0c5f;
    font-size: 26px;
    margin-bottom: 8px;
}

.card .sub-text {
    font-size: 14px;
    color: #777;
    margin-bottom: 25px;
}

/* =====================
   FORM GRID
===================== */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.form-group label {
    font-size: 13px;
    color: #555;
    margin-bottom: 6px;
    display: block;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

/* =====================
   ACTIONS
===================== */
.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.btn {
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

.btn.primary {
    background: #5a0c5f;
    color: #fff;
    border: none;
}

.btn.secondary {
    background: transparent;
    border: 2px solid #5a0c5f;
    color: #5a0c5f;
}

/* =====================
   MOBILE
===================== */
@media (max-width: 900px) {
    .user-page {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="user-page">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENT -->
    <div class="page-content">

        <div class="card">

            <h2>Education & Employment</h2>
            <p class="sub-text">
                Add your educational background and work details
            </p>

            <?php if (isset($_GET['saved'])): ?>
                <p style="color:green;font-size:14px;margin-bottom:15px;">
                    ✔ Details saved successfully
                </p>
            <?php endif; ?>

            <form method="post" action="education-employment-save.php">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Highest Education *</label>
                        <select name="education" required>
                            <option value="">Select</option>
                            <option <?= ($data['education'] ?? '')=='Graduate'?'selected':'' ?>>Graduate</option>
                            <option <?= ($data['education'] ?? '')=='Post Graduate'?'selected':'' ?>>Post Graduate</option>
                            <option <?= ($data['education'] ?? '')=='Professional'?'selected':'' ?>>Professional</option>
                            <option <?= ($data['education'] ?? '')=='Doctorate'?'selected':'' ?>>Doctorate</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Occupation *</label>
                        <select name="occupation" required>
                            <option value="">Select</option>
                            <option <?= ($data['occupation'] ?? '')=='Job'?'selected':'' ?>>Job</option>
                            <option <?= ($data['occupation'] ?? '')=='Business'?'selected':'' ?>>Business</option>
                            <option <?= ($data['occupation'] ?? '')=='Self Employed'?'selected':'' ?>>Self Employed</option>
                            <option <?= ($data['occupation'] ?? '')=='Not Working'?'selected':'' ?>>Not Working</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Company / Organization</label>
                        <input type="text" name="company"
                            value="<?= htmlspecialchars($data['company'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Annual Income</label>
                        <input type="text" name="income"
                            placeholder="e.g. 5 LPA"
                            value="<?= htmlspecialchars($data['income'] ?? '') ?>">
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn primary">Save Details</button>
                    <a href="dashboard.php" class="btn secondary">Back</a>
                </div>

            </form>

        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
