<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$page_title = 'Dashboard';
$breadcrumb = '<span>Home</span>';
$conn = getDB();
$user = currentUser();

// Stats
$total_records   = $conn->query("SELECT COUNT(*) as c FROM records")->fetch_assoc()['c'];
$active_records  = $conn->query("SELECT COUNT(*) as c FROM records WHERE status='active'")->fetch_assoc()['c'];
$pending_records = $conn->query("SELECT COUNT(*) as c FROM records WHERE status='pending'")->fetch_assoc()['c'];
$total_users     = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$high_priority   = $conn->query("SELECT COUNT(*) as c FROM records WHERE priority IN ('high','critical')")->fetch_assoc()['c'];
$this_month      = $conn->query("SELECT COUNT(*) as c FROM records WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->fetch_assoc()['c'];

// Recent records
$recent_records = $conn->query("SELECT r.*, u.fullname FROM records r LEFT JOIN users u ON r.created_by=u.id ORDER BY r.created_at DESC LIMIT 6");

// Recent activity
$recent_activity = $conn->query("SELECT a.*, u.fullname FROM activity_log a LEFT JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC LIMIT 8");

// Category breakdown
$cat_data = $conn->query("SELECT category, COUNT(*) as cnt FROM records GROUP BY category ORDER BY cnt DESC LIMIT 5");
$categories = [];
while ($row = $cat_data->fetch_assoc()) $categories[] = $row;

require_once __DIR__ . '/includes/header.php';
?>

<!-- STATS GRID -->
<div class="stats-grid">
  <div class="stat-card accent">
    <div class="stat-icon">📁</div>
    <div class="stat-label">Total Records</div>
    <div class="stat-value" data-count="<?= $total_records ?>"><?= number_format($total_records) ?></div>
    <div class="stat-change up">▲ <?= $this_month ?> this month</div>
  </div>
  <div class="stat-card success">
    <div class="stat-icon">✓</div>
    <div class="stat-label">Active Records</div>
    <div class="stat-value" data-count="<?= $active_records ?>"><?= number_format($active_records) ?></div>
    <div class="stat-change">of <?= $total_records ?> total</div>
  </div>
  <div class="stat-card warning">
    <div class="stat-icon">⏳</div>
    <div class="stat-label">Pending</div>
    <div class="stat-value" data-count="<?= $pending_records ?>"><?= number_format($pending_records) ?></div>
    <div class="stat-change">awaiting action</div>
  </div>
  <div class="stat-card danger">
    <div class="stat-icon">🔺</div>
    <div class="stat-label">High Priority</div>
    <div class="stat-value" data-count="<?= $high_priority ?>"><?= number_format($high_priority) ?></div>
    <div class="stat-change down">requires attention</div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon">👥</div>
    <div class="stat-label">System Users</div>
    <div class="stat-value" data-count="<?= $total_users ?>"><?= number_format($total_users) ?></div>
    <div class="stat-change">registered accounts</div>
  </div>
</div>

<!-- MAIN DASHBOARD GRID -->
<div class="dashboard-grid">
  <!-- Recent Records -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">📋 Recent Records</div>
      <a href="records.php" class="btn btn-secondary btn-sm">View All →</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Priority</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($recent_records->num_rows === 0): ?>
          <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted);">No records yet. <a href="add_record.php" style="color:var(--accent);">Add one →</a></td></tr>
        <?php else: ?>
          <?php while ($row = $recent_records->fetch_assoc()): ?>
          <tr>
            <td><a href="records.php?view=<?= $row['id'] ?>" style="color:var(--text-primary);text-decoration:none;font-weight:600;"><?= htmlspecialchars($row['title']) ?></a></td>
            <td><span style="color:var(--text-muted)"><?= htmlspecialchars($row['category']) ?></span></td>
            <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
            <td><span class="badge badge-<?= $row['priority'] ?>"><?= ucfirst($row['priority']) ?></span></td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Right Column -->
  <div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Activity Log -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">📡 Recent Activity</div>
        <a href="activity.php" class="btn btn-secondary btn-sm">All →</a>
      </div>
      <div class="card-body" style="padding:12px 20px;">
        <div class="activity-list">
        <?php if ($recent_activity->num_rows === 0): ?>
          <p style="color:var(--text-muted);font-size:.85rem;text-align:center;padding:20px 0;">No activity yet</p>
        <?php else: ?>
          <?php while ($log = $recent_activity->fetch_assoc()): ?>
          <div class="activity-item">
            <div class="activity-dot"></div>
            <div class="activity-content">
              <div class="activity-action"><b><?= htmlspecialchars($log['fullname'] ?? 'System') ?></b> — <?= htmlspecialchars($log['action']) ?></div>
              <?php if ($log['details']): ?>
              <div class="activity-action text-muted text-sm"><?= htmlspecialchars($log['details']) ?></div>
              <?php endif; ?>
              <div class="activity-time"><?= date('M j, Y H:i', strtotime($log['created_at'])) ?></div>
            </div>
          </div>
          <?php endwhile; ?>
        <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Category Breakdown -->
    <?php if (!empty($categories)): ?>
    <div class="card">
      <div class="card-header">
        <div class="card-title">📊 By Category</div>
      </div>
      <div class="card-body">
        <?php foreach ($categories as $cat): ?>
        <?php $pct = $total_records > 0 ? round(($cat['cnt'] / $total_records) * 100) : 0; ?>
        <div style="margin-bottom:14px;">
          <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;">
            <span style="color:var(--text-secondary)"><?= htmlspecialchars($cat['category']) ?></span>
            <span style="color:var(--text-muted)"><?= $cat['cnt'] ?> (<?= $pct ?>%)</span>
          </div>
          <div style="height:6px;background:var(--bg-raised);border-radius:3px;overflow:hidden;">
            <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,var(--accent),var(--accent-dim));border-radius:3px;transition:width .8s ease;"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Quick Actions -->
<div style="margin-top:20px;display:flex;flex-wrap:wrap;gap:12px;">
  <a href="add_record.php" class="btn btn-primary">＋ Add New Record</a>
  <a href="records.php" class="btn btn-secondary">📋 Manage Records</a>
  <a href="users.php" class="btn btn-secondary">👥 Manage Users</a>
  <a href="activity.php" class="btn btn-secondary">📡 View Activity</a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
