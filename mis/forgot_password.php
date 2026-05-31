<?php
require_once __DIR__ . '/includes/auth.php';
startSession();
if (isLoggedIn()) { header('Location: dashboard.php'); exit; }

$step = 'request'; // 'request' or 'reset'
$msg = '';
$msg_type = '';
$token = sanitize($_GET['token'] ?? '');
if (!empty($token)) $step = 'reset';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 'request') {
        $email = sanitize($_POST['email'] ?? '');
        if (empty($email)) {
            $msg = 'Please enter your email address.';
            $msg_type = 'danger';
        } else {
            $result = generateResetToken($email);
            if ($result['success']) {
                $demo_token = $result['token'];
                $msg = 'A password reset link has been generated. Demo token: ' . $demo_token;
                $msg_type = 'info';
            } else {
                $msg = $result['message'];
                $msg_type = 'danger';
            }
        }
    } elseif ($step === 'reset') {
        $reset_token = sanitize($_POST['token'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if (empty($new_password) || strlen($new_password) < 6) {
            $msg = 'Password must be at least 6 characters.';
            $msg_type = 'danger';
        } elseif ($new_password !== $confirm) {
            $msg = 'Passwords do not match.';
            $msg_type = 'danger';
        } else {
            $result = resetPassword($reset_token, $new_password);
            if ($result['success']) {
                header('Location: index.php?reset_done=1');
                exit;
            } else {
                $msg = $result['message'];
                $msg_type = 'danger';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $step === 'reset' ? 'Reset Password' : 'Forgot Password' ?> — NexusMIS</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
<div class="auth-bg">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-logo-icon">⚡</div>
      <span class="auth-logo-text">NexusMIS</span>
    </div>

    <?php if ($step === 'request'): ?>
    <h1 class="auth-title">Forgot Password?</h1>
    <p class="auth-sub">Enter your email and we'll send you a reset link</p>

    <?php if ($msg): ?>
    <div class="alert alert-<?= $msg_type ?>">
      <?= $msg_type === 'danger' ? '⚠' : 'ℹ' ?> <?= htmlspecialchars($msg) ?>
      <?php if (!empty($demo_token)): ?>
        <br><br>
        <a href="forgot_password.php?token=<?= urlencode($demo_token) ?>" style="color:var(--accent);text-decoration:none;font-weight:600;">→ Use this token to reset password</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="" data-validate>
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="form-control-icon">
          <span class="icon">📧</span>
          <input type="email" name="email" class="form-control" placeholder="your@email.com" required/>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-full btn-lg">Send Reset Link →</button>
    </form>

    <?php else: ?>
    <h1 class="auth-title">Reset Password</h1>
    <p class="auth-sub">Enter your new password below</p>

    <?php if ($msg): ?>
    <div class="alert alert-<?= $msg_type ?>">⚠ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="" data-validate>
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>"/>
      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" id="pw-new" class="form-control" placeholder="Min. 6 characters" required/>
        <button type="button" data-pw-toggle="pw-new" style="background:none;border:none;cursor:pointer;font-size:.75rem;color:var(--text-muted);margin-top:3px;">👁️ Show</button>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required/>
      </div>
      <button type="submit" class="btn btn-primary btn-full btn-lg">Reset Password →</button>
    </form>
    <?php endif; ?>

    <div class="divider"></div>
    <p style="text-align:center;font-size:.88rem;color:var(--text-muted);">
      Remembered your password?
      <a href="index.php" style="color:var(--accent);text-decoration:none;font-weight:600;"> Sign in</a>
    </p>
  </div>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
