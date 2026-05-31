<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$page_title = 'My Profile';
$breadcrumb = '<a href="dashboard.php" style="color:var(--text-muted);text-decoration:none;">Home</a> › <span>Profile</span>';
$conn = getDB();
$user = currentUser();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'update_profile') {
        $fullname = sanitize($_POST['fullname'] ?? '');
        $email    = sanitize($_POST['email'] ?? '');
        if (empty($fullname) || empty($email)) {
            $error = 'Name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            // Check email uniqueness
            $stmt = $conn->prepare("SELECT id FROM users WHERE email=? AND id!=?");
            $stmt->bind_param('si', $email, $user['id']);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Email already in use by another account.';
            } else {
                $stmt->close();
                $stmt = $conn->prepare("UPDATE users SET fullname=?, email=? WHERE id=?");
                $stmt->bind_param('ssi', $fullname, $email, $user['id']);
                $stmt->execute();
                $stmt->close();
                logActivity($user['id'], 'UPDATE_PROFILE', 'Updated profile info');
                $success = 'Profile updated successfully.';
                $user = currentUser();
            }
        }
    } elseif ($action === 'change_password') {
        $current  = $_POST['current_password'] ?? '';
        $new_pw   = $_POST['new_password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $hash = $stmt->get_result()->fetch_assoc()['password'];
        $stmt->close();
        if (!password_verify($current, $hash)) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($new_pw) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($new_pw !== $confirm) {
            $error = 'New passwords do not match.';
        } else {
            $new_hash = password_hash($new_pw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param('si', $new_hash, $user['id']);
            $stmt->execute();
            $stmt->close();
            logActivity($user['id'], 'CHANGE_PASSWORD', 'Password changed via profile');
            $success = 'Password changed successfully.';
        }
    }
}

// Stats for this user
$my_records = $conn->query("SELECT COUNT(*) as c FROM records WHERE created_by={$user['id']}")->fetch_assoc()['c'];
$my_active  = $conn->query("SELECT COUNT(*) as c FROM records WHERE created_by={$user['id']} AND status='active'")->fetch_assoc()['c'];

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?><div class="alert alert-danger" data-auto-dismiss>⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success" data-auto-dismiss>✓ <?= htmlspecialchars($success) ?></div><?php endif; ?>

<!-- Profile Header -->
<div class="profile-header">
  <div class="profile-avatar"><?= htmlspecialchars(getInitials($user['fullname'])) ?></div>
  <div class="profile-info">
    <div class="profile-name"><?= htmlspecialchars($user['fullname']) ?></div>
    <div class="profile-email">@<?= htmlspecialchars($user['username']) ?> · <?= htmlspecialchars($user['email']) ?></div>
    <div class="profile-meta">
      <div class="profile-meta-item">Role: <b><?= ucfirst($user['role']) ?></b></div>
      <div class="profile-meta-item">Records: <b><?= $my_records ?></b></div>
      <div class="profile-meta-item">Active: <b><?= $my_active ?></b></div>
      <div class="profile-meta-item">Member since: <b><?= date('M Y', strtotime($user['created_at'])) ?></b></div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

  <!-- Update Profile -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">👤 Update Profile</div>
    </div>
    <div class="card-body">
      <form method="POST" action="" data-validate>
        <input type="hidden" name="action" value="update_profile"/>
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Username</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled style="opacity:.5;cursor:not-allowed;"/>
          <p class="form-hint">Username cannot be changed</p>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>
    </div>
  </div>

  <!-- Change Password -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">🔒 Change Password</div>
    </div>
    <div class="card-body">
      <form method="POST" action="" data-validate>
        <input type="hidden" name="action" value="change_password"/>
        <div class="form-group">
          <label class="form-label">Current Password *</label>
          <input type="password" name="current_password" id="pw-curr" class="form-control" required/>
          <button type="button" data-pw-toggle="pw-curr" style="background:none;border:none;cursor:pointer;font-size:.75rem;color:var(--text-muted);margin-top:3px;">👁️ Show</button>
        </div>
        <div class="form-group">
          <label class="form-label">New Password *</label>
          <input type="password" name="new_password" id="pw-new" class="form-control" placeholder="Min. 6 characters" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Confirm New Password *</label>
          <input type="password" name="confirm_password" class="form-control" required/>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
      </form>
    </div>
  </div>
</div>

<div style="margin-top:20px;">
  <a href="logout.php" class="btn btn-danger">⏻ Logout from all sessions</a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
