<?php
require_once __DIR__ . '/includes/auth.php';
startSession();
if (isLoggedIn()) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'fullname' => sanitize($_POST['fullname'] ?? ''),
        'email'    => sanitize($_POST['email'] ?? ''),
        'username' => sanitize($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? '',
    ];
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($data['fullname']) || empty($data['email']) || empty($data['username']) || empty($data['password'])) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($data['password']) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($data['password'] !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $result = register($data);
        if ($result['success']) {
            header('Location: index.php?registered=1');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account — NexusMIS</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
<div class="auth-bg">
  <div class="auth-card" style="max-width:520px;">
    <div class="auth-logo">
      <div class="auth-logo-icon">⚡</div>
      <span class="auth-logo-text">NexusMIS</span>
    </div>

    <h1 class="auth-title">Create an account</h1>
    <p class="auth-sub">Join NexusMIS to manage your records efficiently</p>

    <?php if ($error): ?>
    <div class="alert alert-danger">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" data-validate>
      <div class="form-group">
        <label class="form-label">Full Name *</label>
        <div class="form-control-icon">
          <span class="icon">👤</span>
          <input type="text" name="fullname" class="form-control" placeholder="John Doe" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Username *</label>
          <input type="text" name="username" class="form-control" placeholder="johndoe" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" placeholder="john@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Password *</label>
          <input type="password" name="password" id="pw1" class="form-control" placeholder="Min. 6 characters" required/>
          <button type="button" data-pw-toggle="pw1" style="background:none;border:none;cursor:pointer;font-size:.75rem;color:var(--text-muted);margin-top:3px;">👁️ Show</button>
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password *</label>
          <input type="password" name="confirm_password" id="pw2" class="form-control" placeholder="Repeat password" required/>
        </div>
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:flex;gap:8px;align-items:flex-start;cursor:pointer;font-size:.85rem;color:var(--text-secondary);">
          <input type="checkbox" required style="margin-top:3px;accent-color:var(--accent);"/>
          I agree to the Terms of Service and Privacy Policy
        </label>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-lg">Create Account →</button>
    </form>

    <div class="divider"></div>
    <p style="text-align:center;font-size:.88rem;color:var(--text-muted);">
      Already have an account?
      <a href="index.php" style="color:var(--accent);text-decoration:none;font-weight:600;"> Sign in</a>
    </p>
  </div>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
