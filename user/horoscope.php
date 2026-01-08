<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'] ?? 0;

/* FETCH EXISTING HOROSCOPE */
$stmt = $conn->prepare("
    SELECT rashi, nakshatra, manglik
    FROM horoscope
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
    max-width: 850px;
    margin: auto;
    background: #ffffff;
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

            <h2>Horoscope Details</h2>
            <p class="sub-text">
                Add your horoscope information for better matchmaking
            </p>

            <?php if (isset($_GET['saved'])): ?>
                <p style="color:green;font-size:14px;margin-bottom:15px;">
                    ✔ Horoscope details saved successfully
                </p>
            <?php endif; ?>

            <form method="post" action="horoscope-save.php">

                <div class="form-grid">

                    <!-- RASHI -->
                    <div class="form-group">
                        <label>Rashi *</label>
                        <select name="rashi" required>
                            <option value="">Select Rashi</option>
                            <?php
                            $rashis = ['Mesh','Vrishabh','Mithun','Karka','Sinh','Kanya','Tula','Vrischik','Dhanu','Makar','Kumbh','Meen'];
                            foreach ($rashis as $r) {
                                $selected = (($data['rashi'] ?? '') == $r) ? 'selected' : '';
                                echo "<option $selected>$r</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- NAKSHATRA -->
                    <div class="form-group">
                        <label>Nakshatra *</label>
                        <input type="text" name="nakshatra"
                               placeholder="e.g. Rohini, Ashwini"
                               value="<?= htmlspecialchars($data['nakshatra'] ?? '') ?>"
                               required>
                    </div>

                    <!-- MANGLIK -->
                    <div class="form-group">
                        <label>Manglik *</label>
                        <select name="manglik" required>
                            <option value="">Select</option>
                            <option <?= ($data['manglik'] ?? '')=='Yes'?'selected':'' ?>>Yes</option>
                            <option <?= ($data['manglik'] ?? '')=='No'?'selected':'' ?>>No</option>
                        </select>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn primary">Save Horoscope</button>
                    <a href="dashboard.php" class="btn secondary">Back</a>
                </div>

            </form>

        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
