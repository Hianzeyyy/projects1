<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$page_title = 'User Management';
$breadcrumb = '<a href="dashboard.php" style="color:var(--text-muted);text-decoration:none;">Home</a> › <span>Users</span>';
$conn = getDB();
$current_user = currentUser();

$error = '';
$success = '';

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    if ($del_id !== $current_user['id']) {
        $conn->query("DELETE FROM users WHERE id=$del_id");
        logActivity($current_user['id'], 'DELETE_USER', "Deleted user ID: $del_id");
        header('Location: users.php?deleted=1');
        exit;
    }
}

// Handle role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    $uid  = (int)$_POST['user_id'];
    $role = sanitize($_POST['role'] ?? 'staff');
    if (in_array($role, ['admin','staff','viewer'])) {
        $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
        $stmt->bind_param('si', $role, $uid);
        $stmt->execute();
        $stmt->close();
        logActivity($current_user['id'], 'CHANGE_ROLE', "Changed role for user ID: $uid to $role");
        header('Location: users.php?updated=1');
        exit;
    }
}

$search  = sanitize($_GET['search'] ?? '');
$per_page = 10;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $per_page;

$where = '1=1';
$params = [];
$types  = '';
if ($search) {
    $where = "(fullname LIKE ? OR email LIKE ? OR username LIKE ?)";
    $s = "%$search%"; $params = [$s,$s,$s]; $types = 'sss';
}

$count_stmt = $conn->prepare("SELECT COUNT(*) as c FROM users WHERE $where");
if ($types) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_rows  = $count_stmt->get_result()->fetch_assoc()['c'];
$count_stmt->close();
$total_pages = ceil($total_rows / $per_page);

$stmt = $conn->prepare("SELECT u.*, (SELECT COUNT(*) FROM records WHERE created_by=u.id) as record_count FROM users u WHERE $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$users = $stmt->get_result();
$stmt->close();

require_once __DIR__ . '/includes/header.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success" data-auto-dismiss>✓ User deleted.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
<div class="alert alert-success" data-auto-dismiss>✓ User role updated.</div>
<?php endif; ?>

<div class="card">
  <form method="GET" action="">
    <div class="filters">
      <div style="flex:1;min-width:200px;position:relative;">
        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);">🔍</span>
        <input type="text" name="search" class="form-control" style="padding-left:34px;" placeholder="Search users…" value="<?= htmlspecialchars($search) ?>"/>
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Search</button>
      <a href="users.php" class="btn btn-secondary btn-sm">Reset</a>
    </div>
  </form>

  <div class="card-header" style="border-top:1px solid var(--border);">
    <div class="card-title">👥 Users <span style="font-size:.75rem;font-weight:400;color:var(--text-muted);margin-left:8px;"><?= $total_rows ?> total</span></div>
    <a href="signup.php" class="btn btn-primary btn-sm">＋ New User</a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Records</th>
          <th>Joined</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($users->num_rows === 0): ?>
        <tr><td colspan="8"><div class="empty-state"><div class="empty-icon">👤</div><div class="empty-title">No users found</div></div></td></tr>
      <?php else: ?>
        <?php $i = $offset + 1; while ($u = $users->fetch_assoc()): ?>
        <?php $init = getInitials($u['fullname']); ?>
        <tr>
          <td class="text-muted text-mono"><?= $i++ ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="user-avatar" style="width:32px;height:32px;font-size:.7rem;"><?= htmlspecialchars($init) ?></div>
              <div style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($u['fullname']) ?></div>
            </div>
          </td>
          <td class="text-mono"><?= htmlspecialchars($u['username']) ?></td>
          <td style="font-size:.85rem;"><?= htmlspecialchars($u['email']) ?></td>
          <td>
            <?php $role_colors = ['admin'=>'badge-critical','staff'=>'badge-active','viewer'=>'badge-inactive']; ?>
            <span class="badge <?= $role_colors[$u['role']] ?? '' ?>"><?= ucfirst($u['role']) ?></span>
          </td>
          <td style="text-align:center;font-weight:600;color:var(--accent);"><?= $u['record_count'] ?></td>
          <td style="font-size:.78rem;" class="text-muted"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
          <td>
            <div style="display:flex;gap:6px;justify-content:center;">
              <?php if ($u['id'] !== $current_user['id']): ?>
              <button class="btn btn-success btn-sm" data-modal="role-modal-<?= $u['id'] ?>" title="Change Role">⚙</button>
              <a href="users.php?action=delete_user&id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user <?= htmlspecialchars(addslashes($u['fullname'])) ?>?')" title="Delete">🗑</a>
              <?php else: ?>
              <span style="font-size:.75rem;color:var(--text-muted);">You</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <!-- Role Modal -->
        <div class="modal-overlay" id="role-modal-<?= $u['id'] ?>">
          <div class="modal" style="max-width:380px;">
            <div class="modal-header">
              <div class="modal-title">⚙ Change Role</div>
              <button class="modal-close" data-modal-close>✕</button>
            </div>
            <form method="POST" action="">
              <div class="modal-body">
                <p style="color:var(--text-secondary);margin-bottom:16px;font-size:.9rem;">Changing role for <b style="color:var(--text-primary)"><?= htmlspecialchars($u['fullname']) ?></b></p>
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>"/>
                <div class="form-group">
                  <label class="form-label">New Role</label>
                  <select name="role" class="form-control">
                    <?php foreach (['admin','staff','viewer'] as $r): ?>
                    <option value="<?= $r ?>" <?= $u['role'] === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" name="change_role" class="btn btn-primary">Update Role</button>
              </div>
            </form>
          </div>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($total_pages > 1): ?>
  <div class="pagination">
    <div class="pagination-info">Showing <?= $offset+1 ?>–<?= min($offset+$per_page,$total_rows) ?> of <?= $total_rows ?></div>
    <div class="page-btns">
      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
      <a href="?search=<?= urlencode($search) ?>&page=<?= $p ?>" class="page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
