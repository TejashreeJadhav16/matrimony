<?php include '../includes/header.php'; ?> 
<link rel="stylesheet" href="../assets/css/register.css">

<section class="register-section">
    <div class="register-container">

        <h2>Karadi Samaaj Matrimony – Registration</h2>

        <p class="info-text">
            Registration is only for members of Karadi Samaaj.
            Documents are collected strictly for verification.
            Profiles are visible only after admin approval.
        </p>

        <ul class="progress-bar">
            <li class="active">Account</li>
            <li>Personal</li>
            <li>Family</li>
            <li>Education</li>
            <li>Photos</li>
            <li>Documents</li>
            <li>Payment</li>
        </ul>

        <!-- ✅ SUBMIT GOES TO register-process.php -->
        <form action="register-process.php" method="post" enctype="multipart/form-data">
            <!-- STEP 1 -->
            <div class="form-step">
                <h3>Account Setup</h3>
                <input type="text" name="login_id" placeholder="Mobile Number or Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="button" class="btn next">Next</button>
            </div>

            <!-- STEP 2 -->
            <div class="form-step">
                <h3>Personal Information</h3>
                <input type="text" name="full_name" placeholder="Full Name (as per Aadhaar)" required>

                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>

                <select name="marital_status" required>
                    <option value="">Marital Status</option>
                    <option>Never Married</option>
                    <option>Divorced</option>
                    <option>Widowed</option>
                </select>

                <input type="date" name="dob" required>

                <button type="button" class="btn prev">Back</button>
                <button type="button" class="btn next">Next</button>
            </div>

            <!-- STEP 3 -->
            <div class="form-step">
                <h3>Family Details</h3>
                <input type="text" name="father_name" placeholder="Father's Name" required>
                <input type="text" name="mother_name" placeholder="Mother's Name" required>
                <button type="button" class="btn prev">Back</button>
                <button type="button" class="btn next">Next</button>
            </div>

            <!-- STEP 4 -->
            <div class="form-step">
                <h3>Education & Employment</h3>

                <select name="education" required>
                    <option value="">Highest Education</option>
                    <option>Graduate</option>
                    <option>Post Graduate</option>
                    <option>Professional</option>
                </select>

                <select name="employment" required>
                    <option value="">Employment Type</option>
                    <option>Job</option>
                    <option>Business</option>
                    <option>Not Employed</option>
                </select>

                <button type="button" class="btn prev">Back</button>
                <button type="button" class="btn next">Next</button>
            </div>

            <!-- STEP 5 -->
            <div class="form-step">
                <h3>Photo Upload</h3>
                <label>Main Profile Photo *</label>
                <input type="file" name="profile_photo" required>
                <button type="button" class="btn prev">Back</button>
                <button type="button" class="btn next">Next</button>
            </div>

            <!-- STEP 6 -->
            <div class="form-step">
                <h3>Document Upload</h3>

                <label>Aadhaar Card *</label>
                <input type="file" name="aadhaar" required>

                <label>Caste Certificate *</label>
                <input type="file" name="caste_certificate" required>

                <p class="note">Documents are visible only to Admin.</p>

                <button type="button" class="btn prev">Back</button>
                <button type="button" class="btn next">Next</button>
            </div>

            <!-- STEP 7 -->
            <div class="form-step">
                <h3>Registration Fee</h3>

                <div class="payment-box">
                    <p>One-time Registration Fee</p>
                    <h2>₹500</h2>
                    <p class="small">Non-refundable</p>
                </div>

                <label class="checkbox">
                    <input type="checkbox" required>
                    I agree to the Terms & Privacy Policy
                </label>

                <input type="hidden" name="amount" value="500">

                <button type="button" class="btn prev">Back</button>
               
            <button type="submit" class="btn primary">
                Proceed to Pay ₹500
            </button>
            </div>

        </form>
    </div>
</section>

<script src="../assets/js/registration-steps.js"></script>
<?php include '../includes/footer.php'; ?>