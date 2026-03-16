<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Manage Hobbies';
$activePage = 'hobbies';
$success    = '';

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM hobbies WHERE id=" . (int)$_GET['delete']);
    $success = 'Hobby deleted.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = (int)($_POST['id'] ?? 0);
    $hobby_name = htmlspecialchars(trim($_POST['hobby_name']));
    $hobby_icon = htmlspecialchars(trim($_POST['hobby_icon']));
    $user_id    = $_SESSION['admin_id'];

    if ($hobby_name) {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE hobbies SET hobby_name=?, hobby_icon=? WHERE id=?");
            $stmt->bind_param("ssi", $hobby_name, $hobby_icon, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO hobbies (user_id, hobby_name, hobby_icon) VALUES (?,?,?)");
            $stmt->bind_param("iss", $user_id, $hobby_name, $hobby_icon);
        }
        $stmt->execute();
        header("Location: hobbies.php?success=" . urlencode($id > 0 ? 'Hobby updated.' : 'Hobby added.'));
        exit();
    }
}

if (isset($_GET['success'])) $success = htmlspecialchars($_GET['success']);

$editHobby = null;
if (isset($_GET['edit'])) {
    $editHobby = $conn->query("SELECT * FROM hobbies WHERE id=" . (int)$_GET['edit'])->fetch_assoc();
}

$hobbies = $conn->query("SELECT * FROM hobbies WHERE user_id=" . $_SESSION['admin_id'] . " ORDER BY id ASC");
include 'includes/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Manage Hobbies</h1>
</div>

<div class="admin-form" style="margin-bottom:2rem;">
    <h3 style="margin-bottom:1.25rem;"><?= $editHobby ? 'Edit Hobby' : 'Add Hobby' ?></h3>
    <form method="POST">
        <?php if ($editHobby): ?><input type="hidden" name="id" value="<?= $editHobby['id'] ?>"><?php endif; ?>
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;">
            <div class="form-group">
                <label>Hobby Name *</label>
                <input type="text" name="hobby_name"
                       value="<?= htmlspecialchars($editHobby['hobby_name'] ?? '') ?>"
                       placeholder="e.g. Playing Guitar" required>
            </div>
            <div class="form-group">
                <label>Icon (emoji)</label>
                <input type="text" name="hobby_icon"
                       value="<?= htmlspecialchars($editHobby['hobby_icon'] ?? '🎯') ?>"
                       placeholder="🎯" maxlength="5">
            </div>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn btn-primary"><?= $editHobby ? 'Update' : 'Add Hobby' ?></button>
            <?php if ($editHobby): ?><a href="hobbies.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
        </div>
    </form>
</div>

<table class="data-table">
    <thead><tr><th>#</th><th>Icon</th><th>Hobby</th><th>Actions</th></tr></thead>
    <tbody>
        <?php $i = 1; while ($h = $hobbies->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td style="font-size:1.5rem;"><?= $h['hobby_icon'] ?></td>
            <td><strong><?= htmlspecialchars($h['hobby_name']) ?></strong></td>
            <td>
                <div class="action-btns">
                    <a href="hobbies.php?edit=<?= $h['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <a href="hobbies.php?delete=<?= $h['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this hobby?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
