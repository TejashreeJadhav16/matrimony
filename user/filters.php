<?php
require_once '../includes/session.php';
require_once '../includes/user-auth.php';
include '../includes/header.php';
?>

<style>
/* =====================
   USER LAYOUT
===================== */
.user-layout{
    display:flex;
    min-height:100vh;
    background:#f6f7fb;
}

/* MAIN CONTENT */
.user-main{
    flex:1;
    padding:40px 20px;
}

/* =====================
   FILTER CARD
===================== */
.filter-card{
    background:#ffffff;
    padding:30px;
    border-radius:18px;
    max-width:700px;
    box-shadow:0 14px 35px rgba(0,0,0,0.08);
}

.filter-card h2{
    color:#5a0c5f;
    margin-bottom:20px;
}

/* GRID */
.filter-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.filter-grid input,
.filter-grid select{
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
}

/* BUTTON */
.btn{
    margin-top:20px;
    background:#5a0c5f;
    color:#fff;
    padding:12px 28px;
    border-radius:30px;
    border:none;
    font-weight:600;
    cursor:pointer;
}

/* =====================
   RESPONSIVE
===================== */
@media(max-width:900px){
    .user-layout{
        flex-direction:column;
    }
}

@media(max-width:768px){
    .filter-grid{
        grid-template-columns:1fr;
    }
}
</style>

<div class="user-layout">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- MAIN -->
    <main class="user-main">

        <div class="filter-card">
            <h2>Filter Matches</h2>

            <form method="get" action="search.php">
                <div class="filter-grid">
                    <select name="gender">
                        <option value="">Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>

                    <input type="text" name="location" placeholder="Location">

                    <input type="number" name="age_from" placeholder="Age From">
                    <input type="number" name="age_to" placeholder="Age To">
                </div>

                <button class="btn">Apply Filters</button>
            </form>
        </div>

    </main>
</div>

<?php include '../includes/footer.php'; ?>
