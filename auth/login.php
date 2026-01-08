<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/login.css">

<section class="login-section">
    <div class="login-container">

        <h2>Login to Your Account</h2>
        <p class="info-text">
            Login using Email / Mobile / Admin ID
        </p>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-msg">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </p>
        <?php endif; ?>

        <form method="post" action="login-process.php">

            <div class="form-group">
                <label>Email / Mobile / Admin ID</label>
                <input type="text" name="login_id" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-actions">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    Remember Me
                </label>

                <a href="forgot-password.php" class="forgot-link">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="btn primary">Login</button>
        </form>

        <p class="register-link">
            New user?
            <a href="register.php">Create Account</a>
        </p>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
