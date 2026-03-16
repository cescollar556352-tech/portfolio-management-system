<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Manage Experience';
$activePage = 'experiences';
$success = '';

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM experiences WHERE id=" . (int)$_GET['delete']);
    $success = 'Experience deleted.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = (int)($_POST['id'] ?? 0);
    $position   = htmlspecialchars(trim($_POST['position']));
    $company    = htmlspecialchars(trim($_POST['company']));
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'] ?: null;
    $is_current = isset($_POST['is_current']) ? 1 : 0;
    $description= htmlspecialchars(trim($_POST['description']));
    $user_id    = $_SESSION['admin_id'];

    if ($is_current) $end_date = null;

    if ($position && $company) {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE experiences SET position=?,company=?,start_date=?,end_date=?,is_current=?,description=? WHERE id=?");
            $stmt->bind_param("ssssiisi", $position, $company, $start_date, $end_date, $is_current, $description, $id);
            // Fix: 7 params
            $stmt = $conn->prepare("UPDATE experiences SET position=?,company=?,start_date=?,end_date=?,is_current=?,description=? WHERE id=?");
            $stmt->bind_param("ssssisi", $position, $company, $start_date, $end_date, $is_current, $description, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO experiences (user_id,position,company,start_date,end_date,is_current,description) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("issssisi", $user_id, $position, $company, $start_date, $end_date, $is_current, $description);
            // Fix types
            $stmt = $conn->prepare("INSERT INTO experiences (user_id,position,company,start_date,end_date,is_current,description) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("issssiss", $user_id, $position, $company, $start_date, $end_date, $is_current, $description);
        }
        $stmt->execute();
        header("Location: experiences.php?success=" . urlencode($id > 0 ? 'Updated.' : 'Added.'));
        exit();
    }
}

if (isset($_GET['success'])) $success = htmlspecialchars($_GET['success']);

$editExp = null;
if (isset($_GET['edit'])) {
    $editExp = $conn->query("SELECT * FROM experiences WHERE id=" . (int)$_GET['edit'])->fetch_assoc();
}

$exps = $conn->query("SELECT * FROM experiences WHERE user_id=" . $_SESSION['admin_id'] . " ORDER BY is_current DESC, start_date DESC");
include 'includes/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Manage Experience</h1>
</div>

<div class="admin-form" style="margin-bottom:2rem;">
    <h3 style="margin-bottom:1.25rem;"><?= $editExp ? 'Edit Experience' : 'Add Experience' ?></h3>
    <form method="POST">
        <?php if ($editExp): ?><input type="hidden" name="id" value="<?= $editExp['id'] ?>"><?php endif; ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label>Position / Job Title *</label>
                <input type="text" name="position" value="<?= htmlspecialchars($editExp['position'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Company / Organization *</label>
                <input type="text" name="company" value="<?= htmlspecialchars($editExp['company'] ?? '') ?>" required>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label>Start Date *</label>
                <input type="date" name="start_date" value="<?= $editExp['start_date'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" value="<?= $editExp['end_date'] ?? '' ?>" id="end_date_field">
            </div>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;">
            <input type="checkbox" name="is_current" id="is_current"
                   <?= !empty($editExp['is_current']) ? 'checked' : '' ?>
                   onchange="document.getElementById('end_date_field').disabled=this.checked">
            <label for="is_current" style="margin:0;">Currently working here</label>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($editExp['description'] ?? '') ?></textarea>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn btn-primary"><?= $editExp ? 'Update' : 'Add Experience' ?></button>
            <?php if ($editExp): ?><a href="experiences.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
        </div>
    </form>
</div>

<table class="data-table">
    <thead><tr><th>#</th><th>Position</th><th>Company</th><th>Period</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        <?php $i = 1; while ($e = $exps->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><strong><?= htmlspecialchars($e['position']) ?></strong></td>
            <td><?= htmlspecialchars($e['company']) ?></td>
            <td><?= date('M Y', strtotime($e['start_date'])) ?> – <?= $e['is_current'] ? 'Present' : ($e['end_date'] ? date('M Y', strtotime($e['end_date'])) : '—') ?></td>
            <td><?= $e['is_current'] ? '<span class="current-badge">Current</span>' : '—' ?></td>
            <td>
                <div class="action-btns">
                    <a href="experiences.php?edit=<?= $e['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <a href="experiences.php?delete=<?= $e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
