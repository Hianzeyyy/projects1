<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$page_title = 'Activity Log';
$breadcrumb = '<a href="dashboard.php" style="color:var(--text-muted);text-decoration:none;">Home</a> › <span>Activity</span>';
$conn = getDB();

$per_page = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;
$total_rows = $conn->query("SELECT COUNT(*) as c FROM activity_log")->fetch_assoc()['c'];
$total_pages = ceil($total_rows / $per_page);

$logs = $conn->query("SELECT a.*, u.fullname FROM activity_log a LEFT JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC LIMIT $per_page OFFSET $offset");

require_once __DIR__ . '/includes/header.php';
?>
<div class="card">
  <div class="card-header">
    <div class="card-title">📋 System Activity Log</div>
    <span style="font-size:.8rem;color:var(--text-muted);"><?= number_format($total_rows) ?> entries</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>User</th><th>Action</th><th>Details</th><th>IP Address</th><th>Timestamp</th></tr>
      </thead>
      <tbody>
      <?php if ($logs->num_rows === 0): ?>
        <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">📋</div><div class="empty-title">No activity yet</div></div></td></tr>
      <?php else: ?>
        <?php $i=$offset+1; while ($l=$logs->fetch_assoc()): ?>
        <tr>
          <td class="text-mono text-muted"><?= $i++ ?></td>
          <td><b style="color:var(--text-primary)"><?= htmlspecialchars($l['fullname'] ?? 'System') ?></b></td>
          <td><span class="badge badge-active"><?= htmlspecialchars($l['action']) ?></span></td>
          <td style="font-size:.82rem;color:var(--text-muted);"><?= htmlspecialchars($l['details']) ?: '—' ?></td>
          <td class="text-mono" style="font-size:.78rem;"><?= htmlspecialchars($l['ip_address']) ?></td>
          <td style="font-size:.78rem;white-space:nowrap;" class="text-muted"><?= date('M j, Y H:i:s', strtotime($l['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($total_pages > 1): ?>
  <div class="pagination">
    <div class="pagination-info">Showing <?= $offset+1 ?>–<?= min($offset+$per_page,$total_rows) ?> of <?= $total_rows ?></div>
    <div class="page-btns">
      <?php if ($page > 1): ?><a href="?page=<?= $page-1 ?>" class="page-btn">‹</a><?php endif; ?>
      <?php for ($p = max(1,$page-2); $p <= min($total_pages,$page+2); $p++): ?>
      <a href="?page=<?= $p ?>" class="page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?><a href="?page=<?= $page+1 ?>" class="page-btn">›</a><?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
