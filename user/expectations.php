<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'] ?? 0;

/* FETCH EXPECTATIONS */
$stmt = $conn->prepare("
    SELECT age_range, education_pref, location_pref, other_details
    FROM expectations
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
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.form-group textarea {
    resize: vertical;
    min-height: 90px;
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

            <h2>Partner Expectations</h2>
            <p class="sub-text">
                Mention your expectations to get better and accurate matches
            </p>

            <?php if (isset($_GET['saved'])): ?>
                <p style="color:green;font-size:14px;margin-bottom:15px;">
                    ✔ Expectations saved successfully
                </p>
            <?php endif; ?>

            <form method="post" action="expectations-save.php">

                <div class="form-grid">

                    <!-- AGE RANGE -->
                    <div class="form-group">
                        <label>Preferred Age Range *</label>
                        <input type="text"
                               name="age_range"
                               placeholder="e.g. 24 - 30"
                               value="<?= htmlspecialchars($data['age_range'] ?? '') ?>"
                               required>
                    </div>

                    <!-- EDUCATION -->
                    <div class="form-group">
                        <label>Preferred Education *</label>
                        <select name="education_pref" required>
                            <option value="">Select</option>
                            <?php
                            $edu = ['Any','Graduate','Post Graduate','Professional'];
                            foreach ($edu as $e) {
                                $sel = (($data['education_pref'] ?? '') == $e) ? 'selected' : '';
                                echo "<option $sel>$e</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- LOCATION -->
                    <div class="form-group">
                        <label>Preferred Location *</label>
                        <input type="text"
                               name="location_pref"
                               placeholder="City / State"
                               value="<?= htmlspecialchars($data['location_pref'] ?? '') ?>"
                               required>
                    </div>

                    <!-- OTHER DETAILS -->
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Other Expectations</label>
                        <textarea name="other_details"
                                  placeholder="Nature, habits, family background, etc."><?= htmlspecialchars($data['other_details'] ?? '') ?></textarea>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn primary">Save Expectations</button>
                    <a href="dashboard.php" class="btn secondary">Back</a>
                </div>

            </form>

        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
