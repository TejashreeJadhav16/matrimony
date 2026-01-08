<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
require_once '../includes/config.php';
include '../includes/header.php';
?>

<style>
/* =====================
   FAMILY DETAILS PAGE
===================== */
.page-wrapper {
    display: flex;
}

/* CONTENT */
.page-content {
    flex: 1;
    padding: 40px 20px;
    background: #f6f7fb;
    min-height: 100vh;
}

.family-card {
    max-width: 700px;
    margin: auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.family-card h2 {
    color: #5a0c5f;
    margin-bottom: 10px;
}

.family-card p {
    font-size: 14px;
    color: #777;
    margin-bottom: 25px;
}

/* FORM */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 13px;
    color: #555;
    margin-bottom: 6px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}

/* BUTTON */
.btn {
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.btn.primary {
    background: #5a0c5f;
    color: #fff;
}

/* MOBILE */
@media (max-width: 900px) {
    .page-wrapper {
        flex-direction: column;
    }

    .family-card {
        padding: 25px;
    }
}
</style>

<div class="page-wrapper">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENT -->
    <div class="page-content">

        <div class="family-card">
            <h2>Family Details</h2>
            <p>Provide your family background information</p>

            <form method="post" action="family-details-save.php">

                <div class="form-group">
                    <label>Father's Name *</label>
                    <input type="text" name="father_name" placeholder="Enter father's name" required>
                </div>

                <div class="form-group">
                    <label>Mother's Name *</label>
                    <input type="text" name="mother_name" placeholder="Enter mother's name" required>
                </div>

                <div class="form-group">
                    <label>Siblings</label>
                    <select name="siblings">
                        <option value="">Select</option>
                        <option>None</option>
                        <option>1 Brother</option>
                        <option>1 Sister</option>
                        <option>2 Brothers</option>
                        <option>2 Sisters</option>
                        <option>Brother & Sister</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Family Type</label>
                    <select name="family_type">
                        <option value="">Select</option>
                        <option>Joint Family</option>
                        <option>Nuclear Family</option>
                    </select>
                </div>

                <button type="submit" class="btn primary">
                    Save Family Details
                </button>

            </form>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
