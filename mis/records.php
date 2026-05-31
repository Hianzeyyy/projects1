<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$conn = getDB();
$user = currentUser();

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT title FROM records WHERE id=?");
    $stmt->bind_param('i', $del_id);
    $stmt->execute();
    $rec = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($rec) {
        $stmt = $conn->prepare("DELETE FROM records WHERE id=?");
        $stmt->bind_param('i', $del_id);
        $stmt->execute();
        $stmt->close();
        logActivity($user['id'], 'DELETE_RECORD', "Deleted: " . $rec['title']);
        header('Location: records.php?deleted=1');
        exit;
    }
}

// Handle edit/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id          = (int)$_POST['edit_id'];
    $title       = sanitize($_POST['title'] ?? '');
    $category    = sanitize($_POST['category'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $status      = sanitize($_POST['status'] ?? 'active');
    $priority    = sanitize($_POST['priority'] ?? 'medium');
    $assigned_to = sanitize($_POST['assigned_to'] ?? '');
    $due_date    = sanitize($_POST['due_date'] ?? '');
    $due = $due_date ?: null;

    if (!empty($title) && !empty($category)) {
        $stmt = $conn->prepare("UPDATE records SET title=?,category=?,description=?,status=?,priority=?,assigned_to=?,due_date=? WHERE id=?");
        $stmt->bind_param('sssssssi', $title, $category, $description, $status, $priority, $assigned_to, $due, $id);
        $stmt->execute();
        $stmt->close();
        logActivity($user['id'], 'EDIT_RECORD', "Updated record ID: $id — $title");
        header('Location: records.php?updated=1');
        exit;
    }
}

// Filters
$search   = sanitize($_GET['search'] ?? '');
$f_status = sanitize($_GET['status'] ?? '');
$f_prio   = sanitize($_GET['priority'] ?? '');
$f_cat    = sanitize($_GET['category'] ?? '');
$sort     = in_array($_GET['sort'] ?? '', ['title','created_at','priority','status','category']) ? $_GET['sort'] : 'created_at';
$order    = ($_GET['order'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

$where = ['1=1'];
$params = [];
$types  = '';
if ($search)   { $where[] = "(r.title LIKE ? OR r.category LIKE ? OR r.description LIKE ?)"; $s = "%$search%"; $params[] = $s; $params[] = $s; $params[] = $s; $types .= 'sss'; }
if ($f_status) { $where[] = "r.status=?"; $params[] = $f_status; $types .= 's'; }
if ($f_prio)   { $where[] = "r.priority=?"; $params[] = $f_prio;   $types .= 's'; }
if ($f_cat)    { $where[] = "r.category=?"; $params[] = $f_cat;    $types .= 's'; }

$where_sql = implode(' AND ', $where);

// Count
$count_sql = "SELECT COUNT(*) as c FROM records r WHERE $where_sql";
$count_stmt = $conn->prepare($count_sql);
if ($types) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['c'];
$count_stmt->close();
$total_pages = ceil($total_rows / $per_page);

// Fetch
$sql = "SELECT r.*, u.fullname FROM records r LEFT JOIN users u ON r.created_by=u.id WHERE $where_sql ORDER BY r.$sort $order LIMIT $per_page OFFSET $offset";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$records = $stmt->get_result();
$stmt->close();

// Categories for filter
$cats = $conn->query("SELECT DISTINCT category FROM records ORDER BY category");
$all_cats = [];
while ($r = $cats->fetch_assoc()) $all_cats[] = $r['category'];

$page_title = 'Records Management';
$breadcrumb = '<a href="dashboard.php" style="color:var(--text-muted);text-decoration:none;">Home</a> › <span>Records</span>';

function query_str($overrides = []) {
    $p = array_merge($_GET, $overrides);
    unset($p['action'], $p['id']);
    return http_build_query($p);
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success" data-auto-dismiss>✓ Record deleted successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
<div class="alert alert-success" data-auto-dismiss>✓ Record updated successfully.</div>
<?php endif; ?>

<!-- FILTERS -->
<div class="card mb-4">
  <form method="GET" action="">
    <div class="filters">
      <!-- Search -->
      <div style="flex:1;min-width:200px;position:relative;">
        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);">🔍</span>
        <input type="text" name="search" id="table-search" class="form-control" style="padding-left:34px;" placeholder="Search records…" value="<?= htmlspecialchars($search) ?>"/>
      </div>

      <select name="status" class="form-control" style="width:auto;">
        <option value="">All Status</option>
        <?php foreach (['active','pending','inactive'] as $s): ?>
        <option value="<?= $s ?>" <?= $f_status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="priority" class="form-control" style="width:auto;">
        <option value="">All Priority</option>
        <?php foreach (['low','medium','high','critical'] as $p): ?>
        <option value="<?= $p ?>" <?= $f_prio === $p ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="category" class="form-control" style="width:auto;">
        <option value="">All Categories</option>
        <?php foreach ($all_cats as $c): ?>
        <option value="<?= htmlspecialchars($c) ?>" <?= $f_cat === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      <a href="records.php" class="btn btn-secondary btn-sm">Reset</a>
    </div>
  </form>

  <!-- TABLE HEADER -->
  <div class="card-header" style="border-top:1px solid var(--border);">
    <div class="card-title">
      📋 Records
      <span style="font-size:.75rem;font-weight:400;color:var(--text-muted);margin-left:8px;"><?= number_format($total_rows) ?> total</span>
    </div>
    <a href="add_record.php" class="btn btn-primary btn-sm">＋ Add Record</a>
  </div>

  <!-- TABLE -->
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:40px;">#</th>
          <th><a href="?<?= query_str(['sort'=>'title','order'=>$sort==='title'&&$order==='ASC'?'desc':'asc']) ?>" style="color:inherit;text-decoration:none;">Title <?= $sort==='title'?($order==='ASC'?'▲':'▼'):'' ?></a></th>
          <th><a href="?<?= query_str(['sort'=>'category','order'=>$sort==='category'&&$order==='ASC'?'desc':'asc']) ?>" style="color:inherit;text-decoration:none;">Category <?= $sort==='category'?($order==='ASC'?'▲':'▼'):'' ?></a></th>
          <th><a href="?<?= query_str(['sort'=>'status','order'=>$sort==='status'&&$order==='ASC'?'desc':'asc']) ?>" style="color:inherit;text-decoration:none;">Status <?= $sort==='status'?($order==='ASC'?'▲':'▼'):'' ?></a></th>
          <th><a href="?<?= query_str(['sort'=>'priority','order'=>$sort==='priority'&&$order==='ASC'?'desc':'asc']) ?>" style="color:inherit;text-decoration:none;">Priority <?= $sort==='priority'?($order==='ASC'?'▲':'▼'):'' ?></a></th>
          <th>Assigned</th>
          <th>Due Date</th>
          <th><a href="?<?= query_str(['sort'=>'created_at','order'=>$sort==='created_at'&&$order==='ASC'?'desc':'asc']) ?>" style="color:inherit;text-decoration:none;">Created <?= $sort==='created_at'?($order==='ASC'?'▲':'▼'):'' ?></a></th>
          <th style="width:120px;text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($records->num_rows === 0): ?>
        <tr>
          <td colspan="9">
            <div class="empty-state">
              <div class="empty-icon">📭</div>
              <div class="empty-title">No records found</div>
              <div class="empty-sub">Try adjusting your filters or <a href="add_record.php" style="color:var(--accent);">add a new record</a></div>
            </div>
          </td>
        </tr>
      <?php else: ?>
        <?php $i = $offset + 1; while ($row = $records->fetch_assoc()): ?>
        <tr>
          <td class="text-muted text-mono"><?= $i++ ?></td>
          <td>
            <div style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($row['title']) ?></div>
            <?php if ($row['description']): ?>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($row['description']) ?></div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
          <td><span class="badge badge-<?= $row['priority'] ?>"><?= ucfirst($row['priority']) ?></span></td>
          <td style="font-size:.82rem;"><?= $row['assigned_to'] ? htmlspecialchars($row['assigned_to']) : '<span class="text-muted">—</span>' ?></td>
          <td style="font-size:.82rem;white-space:nowrap;"><?= $row['due_date'] ? date('M j, Y', strtotime($row['due_date'])) : '<span class="text-muted">—</span>' ?></td>
          <td style="font-size:.78rem;white-space:nowrap;" class="text-muted"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
          <td>
            <div style="display:flex;gap:6px;justify-content:center;">
              <button class="btn btn-secondary btn-sm" data-modal="view-modal-<?= $row['id'] ?>" title="View">👁</button>
              <button class="btn btn-success btn-sm"
                data-edit='<?= json_encode(['id'=>$row['id'],'title'=>$row['title'],'category'=>$row['category'],'description'=>$row['description'],'status'=>$row['status'],'priority'=>$row['priority'],'assigned_to'=>$row['assigned_to'],'due_date'=>$row['due_date']]) ?>'
                title="Edit">✏</button>
              <button class="btn btn-danger btn-sm" data-delete="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['title']) ?>" title="Delete">🗑</button>
            </div>
          </td>
        </tr>
        <!-- View Modal per row -->
        <div class="modal-overlay" id="view-modal-<?= $row['id'] ?>">
          <div class="modal">
            <div class="modal-header">
              <div class="modal-title">Record Details</div>
              <button class="modal-close" data-modal-close>✕</button>
            </div>
            <div class="modal-body">
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                  <div class="form-label">Title</div>
                  <div style="color:var(--text-primary);font-weight:600;"><?= htmlspecialchars($row['title']) ?></div>
                </div>
                <div>
                  <div class="form-label">Category</div>
                  <div><?= htmlspecialchars($row['category']) ?></div>
                </div>
                <div>
                  <div class="form-label">Status</div>
                  <span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
                </div>
                <div>
                  <div class="form-label">Priority</div>
                  <span class="badge badge-<?= $row['priority'] ?>"><?= ucfirst($row['priority']) ?></span>
                </div>
                <div>
                  <div class="form-label">Assigned To</div>
                  <div><?= $row['assigned_to'] ? htmlspecialchars($row['assigned_to']) : '—' ?></div>
                </div>
                <div>
                  <div class="form-label">Due Date</div>
                  <div><?= $row['due_date'] ? date('F j, Y', strtotime($row['due_date'])) : '—' ?></div>
                </div>
              </div>
              <?php if ($row['description']): ?>
              <div>
                <div class="form-label">Description</div>
                <div style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;"><?= nl2br(htmlspecialchars($row['description'])) ?></div>
              </div>
              <?php endif; ?>
              <div class="divider"></div>
              <div style="display:flex;gap:20px;font-size:.78rem;color:var(--text-muted);">
                <div>Created: <?= date('M j, Y H:i', strtotime($row['created_at'])) ?></div>
                <div>By: <?= htmlspecialchars($row['fullname'] ?? 'Unknown') ?></div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-modal-close>Close</button>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- PAGINATION -->
  <?php if ($total_pages > 1 || $total_rows > 0): ?>
  <div class="pagination">
    <div class="pagination-info">
      Showing <?= number_format($offset + 1) ?>–<?= number_format(min($offset + $per_page, $total_rows)) ?> of <?= number_format($total_rows) ?> records
    </div>
    <div class="page-btns">
      <?php if ($page > 1): ?>
      <a href="?<?= query_str(['page'=>$page-1]) ?>" class="page-btn">‹</a>
      <?php endif; ?>
      <?php for ($p = max(1,$page-2); $p <= min($total_pages,$page+2); $p++): ?>
      <a href="?<?= query_str(['page'=>$p]) ?>" class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
      <a href="?<?= query_str(['page'=>$page+1]) ?>" class="page-btn">›</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="edit-modal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">✏ Edit Record</div>
      <button class="modal-close" data-modal-close>✕</button>
    </div>
    <form method="POST" action="" data-validate>
      <div class="modal-body">
        <input type="hidden" name="edit_id" id="edit_id"/>
        <div class="form-group">
          <label class="form-label">Title *</label>
          <input type="text" name="title" id="edit_title" class="form-control" required/>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Category *</label>
            <input type="text" name="category" id="edit_category" class="form-control" list="cat-list-edit" required/>
            <datalist id="cat-list-edit">
              <?php foreach ($all_cats as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>
          <div class="form-group">
            <label class="form-label">Assigned To</label>
            <input type="text" name="assigned_to" id="edit_assigned" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" id="edit_status" class="form-control">
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Priority</label>
            <select name="priority" id="edit_priority" class="form-control">
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="critical">Critical</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Due Date</label>
          <input type="date" name="due_date" id="edit_due_date" class="form-control" style="max-width:200px;"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div class="modal-overlay" id="delete-modal">
  <div class="modal" style="max-width:420px;">
    <div class="modal-header">
      <div class="modal-title" style="color:var(--danger);">🗑 Confirm Delete</div>
      <button class="modal-close" data-modal-close>✕</button>
    </div>
    <div class="modal-body">
      <p id="delete-msg" style="color:var(--text-secondary);"></p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-modal-close>Cancel</button>
      <form id="delete-form" method="GET" action="" style="display:inline;">
        <button type="submit" class="btn btn-danger">Delete</button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
