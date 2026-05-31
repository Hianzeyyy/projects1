<?php
require_once __DIR__ . '/includes/db.php';
initDB();
require_once __DIR__ . '/includes/auth.php';
startSession();

if (isLoggedIn()) { header('Location: dashboard.php'); exit; }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
        $error = 'Please enter your username and password.';
    } else {
        $result = login($username, $password);
        if ($result['success']) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

if (isset($_GET['registered'])) $success = 'Account created successfully! Please log in.';
if (isset($_GET['reset_done'])) $success = 'Password reset successfully! Please log in.';
if (isset($_GET['logout']))     $success = 'You have been logged out.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — NexusMIS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
<div class="auth-bg">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-logo-icon">⚡</div>
      <span class="auth-logo-text">NexusMIS</span>
    </div>

    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-sub">Sign in to your account to continue</p>

    <?php if ($error): ?>
    <div class="alert alert-danger" data-auto-dismiss>⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="alert alert-success" data-auto-dismiss>✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="" data-validate>
      <div class="form-group">
        <label class="form-label">Username or Email</label>
        <div class="form-control-icon">
          <span class="icon">👤</span>
          <input type="text" name="username" class="form-control" placeholder="Enter username or email" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username"/>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="form-control-icon">
          <span class="icon">🔒</span>
          <input type="password" name="password" id="pw-field" class="form-control" placeholder="Enter password" required autocomplete="current-password"/>
        </div>
        <button type="button" data-pw-toggle="pw-field" style="background:none;border:none;cursor:pointer;font-size:.78rem;color:var(--text-muted);margin-top:4px;padding:0;">👁️ Show password</button>
      </div>

      <div style="display:flex;justify-content:flex-end;margin-bottom:20px;">
        <a href="forgot_password.php" style="font-size:.85rem;color:var(--accent);text-decoration:none;">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-lg">Sign In →</button>
    </form>

    <div class="divider"></div>
    <p style="text-align:center;font-size:.88rem;color:var(--text-muted);">
      Don't have an account?
      <a href="signup.php" style="color:var(--accent);text-decoration:none;font-weight:600;"> Create account</a>
    </p>

    <div style="margin-top:20px;padding:12px;background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--border);">
      <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:4px;font-weight:600;">DEMO CREDENTIALS</p>
      <p style="font-size:.82rem;color:var(--text-secondary);">Username: <span style="color:var(--accent);font-family:var(--font-mono);">admin</span> &nbsp; Password: <span style="color:var(--accent);font-family:var(--font-mono);">admin123</span></p>
    </div>
  </div>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
