<?php
session_start();

/*
|--------------------------------------------------------------------------
| If user already logged in, redirect
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['user_id'])) {
    header("Location: ../user/dashboard.php");
    exit;
}

$error = "";
$success = "";

/*
|--------------------------------------------------------------------------
| FORM SUBMIT
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identity = trim($_POST['identity'] ?? '');

    if ($identity === '') {
        $error = "Please enter registered mobile number or email.";
    } else {

        /*
        |--------------------------------------------------------------------------
        | HERE YOU SHOULD CHECK USER IN DATABASE
        |--------------------------------------------------------------------------
        | Example (pseudo):
        | SELECT id FROM users WHERE email = ? OR mobile = ?
        |
        | For now, we assume user exists
        */

        // Generate OTP
        $otp = (string) rand(100000, 999999);

        // Store OTP in session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expires'] = time() + 300; // 5 minutes
        $_SESSION['otp_attempts'] = 0;

        // OPTIONAL: store identity for reset
        $_SESSION['reset_identity'] = $identity;

        /*
        |--------------------------------------------------------------------------
        | SEND OTP (SMS / EMAIL)
        |--------------------------------------------------------------------------
        | Integrate SMS or Email API here
        |
        | For now (testing), OTP is logged
        */
        // error_log("OTP for $identity is $otp");

        // Redirect to OTP verification
        header("Location: otp-verify.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f6f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .forgot-box {
            background: #ffffff;
            padding: 32px;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .forgot-box h2 {
            text-align: center;
            color: #5a0c5f;
            margin-bottom: 10px;
        }

        .forgot-box p {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .forgot-box input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .forgot-box input:focus {
            border-color: #5a0c5f;
            outline: none;
        }

        .forgot-box button {
            width: 100%;
            background: #5a0c5f;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        .forgot-box button:hover {
            background: #4a0a4f;
        }

        .error {
            color: #d32f2f;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: center;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }

        .back-link a {
            color: #5a0c5f;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="forgot-box">
    <h2>Forgot Password</h2>
    <p>Enter your registered mobile number or email to receive OTP</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input
            type="text"
            name="identity"
            placeholder="Mobile number or Email"
            required
        >
        <button type="submit">Send OTP</button>
    </form>

    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>
</div>

</body>
</html>
