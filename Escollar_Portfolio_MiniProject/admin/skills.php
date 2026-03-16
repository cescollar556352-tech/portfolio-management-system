<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Manage Skills';
$activePage = 'skills';
$success = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM skills WHERE id=$id");
    $success = 'Skill deleted.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = (int)($_POST['id'] ?? 0);
    $skill_name = htmlspecialchars(trim($_POST['skill_name']));
    $skill_level= (int)$_POST['skill_level'];
    $category   = htmlspecialchars(trim($_POST['category']));
    $user_id    = $_SESSION['admin_id'];

    if ($skill_name) {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE skills SET skill_name=?, skill_level=?, category=? WHERE id=?");
            $stmt->bind_param("sisi", $skill_name, $skill_level, $category, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name, skill_level, category) VALUES (?,?,?,?)");
            $stmt->bind_param("isis", $user_id, $skill_name, $skill_level, $category);
        }
        $stmt->execute();
        header("Location: skills.php?success=" . urlencode($id > 0 ? 'Skill updated.' : 'Skill added.'));
        exit();
    }
}

if (isset($_GET['success'])) $success = htmlspecialchars($_GET['success']);

$editSkill = null;
if (isset($_GET['edit'])) {
    $editSkill = $conn->query("SELECT * FROM skills WHERE id=" . (int)$_GET['edit'])->fetch_assoc();
}

$skills = $conn->query("SELECT * FROM skills WHERE user_id=" . $_SESSION['admin_id'] . " ORDER BY category, skill_level DESC");
include 'includes/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Manage Skills</h1>
</div>

<div class="admin-form" style="margin-bottom:2rem;">
    <h3 style="margin-bottom:1.25rem;"><?= $editSkill ? 'Edit Skill' : 'Add Skill' ?></h3>
    <form method="POST">
        <?php if ($editSkill): ?>
            <input type="hidden" name="id" value="<?= $editSkill['id'] ?>">
        <?php endif; ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label>Skill Name *</label>
                <input type="text" name="skill_name" value="<?= htmlspecialchars($editSkill['skill_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" value="<?= htmlspecialchars($editSkill['category'] ?? 'General') ?>" placeholder="Frontend, Backend, Tools...">
            </div>
        </div>
        <div class="form-group">
            <label>Skill Level: <strong id="lvl_display"><?= $editSkill['skill_level'] ?? 50 ?>%</strong></label>
            <input type="range" name="skill_level" min="1" max="100"
                   value="<?= $editSkill['skill_level'] ?? 50 ?>"
                   oninput="document.getElementById('lvl_display').innerText=this.value+'%'"
                   style="width:100%;cursor:pointer;">
        </div>
        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn btn-primary"><?= $editSkill ? 'Update' : 'Add Skill' ?></button>
            <?php if ($editSkill): ?><a href="skills.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
        </div>
    </form>
</div>

<table class="data-table">
    <thead><tr><th>#</th><th>Skill</th><th>Category</th><th>Level</th><th>Actions</th></tr></thead>
    <tbody>
        <?php $i = 1; while ($s = $skills->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><strong><?= htmlspecialchars($s['skill_name']) ?></strong></td>
            <td><?= htmlspecialchars($s['category']) ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="flex:1;background:var(--border);border-radius:10px;height:8px;overflow:hidden;">
                        <div style="width:<?= $s['skill_level'] ?>%;height:100%;background:linear-gradient(90deg,var(--primary),var(--secondary));border-radius:10px;"></div>
                    </div>
                    <span style="font-weight:600;color:var(--primary);min-width:36px;"><?= $s['skill_level'] ?>%</span>
                </div>
            </td>
            <td>
                <div class="action-btns">
                    <a href="skills.php?edit=<?= $s['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <a href="skills.php?delete=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
