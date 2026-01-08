<?php
session_start();

/*
|--------------------------------------------------------------------------
| HARD SECURITY CHECK
|--------------------------------------------------------------------------
| If OTP not generated, block direct access
*/
if (
    !isset($_SESSION['otp']) ||
    !isset($_SESSION['otp_expires']) ||
    !isset($_SESSION['otp_attempts'])
) {
    header("Location: login.php");
    exit;
}

$error = "";

/*
|--------------------------------------------------------------------------
| OTP VERIFICATION
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userOtp = trim($_POST['otp'] ?? '');

    // Empty OTP
    if ($userOtp === '') {
        $error = "Please enter OTP.";
    }

    // OTP expired
    elseif (time() > $_SESSION['otp_expires']) {
        $error = "OTP expired. Please request a new OTP.";
        session_unset();
        session_destroy();
    }

    // Too many attempts
    elseif ($_SESSION['otp_attempts'] >= 3) {
        $error = "Too many invalid attempts. Please login again.";
        session_unset();
        session_destroy();
    }

    // Invalid OTP
    elseif ($userOtp !== $_SESSION['otp']) {
        $_SESSION['otp_attempts']++;
        $error = "Invalid OTP. Attempts left: " . (3 - $_SESSION['otp_attempts']);
    }

    // SUCCESS
    else {
        // OTP verified successfully
        unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['otp_attempts']);

        // Mark login / registration verified
        $_SESSION['is_verified'] = true;

        // Redirect user
        header("Location: ../user/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f6f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .otp-container {
            background: #fff;
            padding: 32px;
            width: 100%;
            max-width: 380px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .otp-container h2 {
            color: #5a0c5f;
            margin-bottom: 8px;
        }

        .otp-container p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .otp-container input {
            width: 100%;
            padding: 14px;
            font-size: 20px;
            letter-spacing: 5px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .otp-container input:focus {
            border-color: #5a0c5f;
            outline: none;
        }

        .otp-container button {
            width: 100%;
            background: #5a0c5f;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }

        .otp-container button:hover {
            background: #4a0a4f;
        }

        .error {
            color: #d32f2f;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .hint {
            font-size: 12px;
            color: #777;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="otp-container">
    <h2>OTP Verification</h2>
    <p>Enter the 6-digit OTP sent to your registered mobile/email</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input
            type="text"
            name="otp"
            maxlength="6"
            pattern="[0-9]{6}"
            placeholder="••••••"
            required
            autofocus
        >
        <button type="submit">Verify OTP</button>
    </form>

    <div class="hint">
        OTP valid for 5 minutes • Max 3 attempts
    </div>
</div>

</body>
</html>
