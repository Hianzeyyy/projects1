<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$page_title = 'Add Record';
$breadcrumb = '<a href="dashboard.php" style="color:var(--text-muted);text-decoration:none;">Home</a> › <span>Add Record</span>';
$user = currentUser();
$conn = getDB();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = sanitize($_POST['title'] ?? '');
    $category    = sanitize($_POST['category'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $status      = sanitize($_POST['status'] ?? 'active');
    $priority    = sanitize($_POST['priority'] ?? 'medium');
    $assigned_to = sanitize($_POST['assigned_to'] ?? '');
    $due_date    = sanitize($_POST['due_date'] ?? '');
    $created_by  = $user['id'];

    if (empty($title) || empty($category)) {
        $error = 'Title and Category are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO records (title, category, description, status, priority, assigned_to, due_date, created_by) VALUES (?,?,?,?,?,?,?,?)");
        $due = $due_date ?: null;
        $stmt->bind_param('sssssssi', $title, $category, $description, $status, $priority, $assigned_to, $due, $created_by);
        if ($stmt->execute()) {
            $new_id = $stmt->insert_id;
            logActivity($created_by, 'ADD_RECORD', "Added record: $title (ID: $new_id)");
            $success = 'Record "' . htmlspecialchars($title) . '" added successfully!';
        } else {
            $error = 'Failed to add record. Please try again.';
        }
        $stmt->close();
    }
}

// Existing categories for suggestions
$cats = $conn->query("SELECT DISTINCT category FROM records ORDER BY category");
$existing_cats = [];
while ($r = $cats->fetch_assoc()) $existing_cats[] = $r['category'];

require_once __DIR__ . '/includes/header.php';
?>

<div style="max-width:760px;">
  <?php if ($error): ?>
  <div class="alert alert-danger" data-auto-dismiss>⚠ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
  <div class="alert alert-success" data-auto-dismiss>
    ✓ <?= $success ?>
    <a href="records.php" style="color:var(--success);text-decoration:none;font-weight:600;margin-left:8px;">View All Records →</a>
  </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">
      <div class="card-title">📝 New Record Form</div>
      <a href="records.php" class="btn btn-secondary btn-sm">← Back to Records</a>
    </div>
    <div class="card-body">
      <form method="POST" action="" data-validate>

        <div class="form-group">
          <label class="form-label">Record Title *</label>
          <input type="text" name="title" class="form-control" placeholder="Enter a descriptive title for this record" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required/>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Category *</label>
            <input type="text" name="category" class="form-control" list="cat-list" placeholder="e.g. Finance, HR, IT, Operations…" value="<?= htmlspecialchars($_POST['category'] ?? '') ?>" required/>
            <datalist id="cat-list">
              <?php foreach ($existing_cats as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>">
              <?php endforeach; ?>
              <option value="Finance"><option value="Human Resources"><option value="IT">
              <option value="Operations"><option value="Marketing"><option value="Legal"><option value="Compliance">
            </datalist>
            <p class="form-hint">Type or select an existing category</p>
          </div>
          <div class="form-group">
            <label class="form-label">Assigned To</label>
            <input type="text" name="assigned_to" class="form-control" placeholder="Person or team responsible" value="<?= htmlspecialchars($_POST['assigned_to'] ?? '') ?>"/>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" placeholder="Provide details, context, or notes about this record…"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <?php foreach (['active','pending','inactive'] as $s): ?>
              <option value="<?= $s ?>" <?= ($_POST['status'] ?? 'active') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-control">
              <?php foreach (['low','medium','high','critical'] as $p): ?>
              <option value="<?= $p ?>" <?= ($_POST['priority'] ?? 'medium') === $p ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Due Date</label>
          <input type="date" name="due_date" class="form-control" style="max-width:220px;" value="<?= htmlspecialchars($_POST['due_date'] ?? '') ?>"/>
          <p class="form-hint">Optional — leave blank if no deadline</p>
        </div>

        <div class="divider"></div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
          <button type="submit" class="btn btn-primary">＋ Add Record</button>
          <button type="reset" class="btn btn-secondary">↺ Clear Form</button>
          <a href="records.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
