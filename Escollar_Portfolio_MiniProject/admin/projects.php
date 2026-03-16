<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Manage Projects';
$activePage = 'projects';

$success = $error = '';

// DELETE
if (isset($_GET['delete'])) {
    $id   = (int)$_GET['delete'];
    $row  = $conn->query("SELECT image FROM projects WHERE id=$id")->fetch_assoc();
    if ($row && $row['image']) @unlink("../assets/uploads/" . $row['image']);
    $conn->query("DELETE FROM projects WHERE id=$id");
    $success = 'Project deleted successfully.';
}

// CREATE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $title       = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $technologies= htmlspecialchars(trim($_POST['technologies']));
    $project_url = htmlspecialchars(trim($_POST['project_url']));
    $github_url  = htmlspecialchars(trim($_POST['github_url']));
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $user_id     = $_SESSION['admin_id'];

    // Handle image upload
    $image = $_POST['existing_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('proj_') . '.' . $ext;
        $dest     = "../assets/uploads/$filename";
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            if ($image) @unlink("../assets/uploads/$image");
            $image = $filename;
        }
    }

    if ($title) {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE projects SET title=?,description=?,technologies=?,project_url=?,github_url=?,image=?,is_featured=? WHERE id=?");
            $stmt->bind_param("ssssssii", $title, $description, $technologies, $project_url, $github_url, $image, $is_featured, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO projects (user_id,title,description,technologies,project_url,github_url,image,is_featured) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("issssssi", $user_id, $title, $description, $technologies, $project_url, $github_url, $image, $is_featured);
        }
        $stmt->execute();
        $success = $id > 0 ? 'Project updated successfully.' : 'Project added successfully.';
        header("Location: projects.php?success=" . urlencode($success));
        exit();
    }
}

if (isset($_GET['success'])) $success = htmlspecialchars($_GET['success']);

// Edit mode
$editProject = null;
if (isset($_GET['edit'])) {
    $editId      = (int)$_GET['edit'];
    $editProject = $conn->query("SELECT * FROM projects WHERE id=$editId")->fetch_assoc();
}

// Fetch all projects
$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");

include 'includes/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Manage Projects</h1>
</div>

<!-- Form -->
<div class="admin-form" style="margin-bottom:2rem;">
    <h3 style="margin-bottom:1.25rem;color:var(--dark);">
        <?= $editProject ? 'Edit Project' : 'Add New Project' ?>
    </h3>
    <form method="POST" enctype="multipart/form-data">
        <?php if ($editProject): ?>
            <input type="hidden" name="id" value="<?= $editProject['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editProject['image'] ?? '') ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($editProject['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($editProject['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Technologies (comma separated)</label>
            <input type="text" name="technologies" value="<?= htmlspecialchars($editProject['technologies'] ?? '') ?>" placeholder="PHP, MySQL, Bootstrap">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label>Project URL</label>
                <input type="url" name="project_url" value="<?= htmlspecialchars($editProject['project_url'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>GitHub URL</label>
                <input type="url" name="github_url" value="<?= htmlspecialchars($editProject['github_url'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Project Image</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($editProject['image'])): ?>
                <small style="color:#64748b;">Current: <?= htmlspecialchars($editProject['image']) ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;">
            <input type="checkbox" name="is_featured" id="is_featured" <?= !empty($editProject['is_featured']) ? 'checked' : '' ?>>
            <label for="is_featured" style="margin:0;">Show on Homepage (Featured)</label>
        </div>
        <div style="display:flex;gap:0.75rem;margin-top:0.5rem;">
            <button type="submit" class="btn btn-primary">
                <?= $editProject ? 'Update Project' : 'Add Project' ?>
            </button>
            <?php if ($editProject): ?>
                <a href="projects.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Table -->
<table class="data-table">
    <thead>
        <tr>
            <th>#</th><th>Title</th><th>Technologies</th><th>Featured</th><th>Date</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; while ($p = $projects->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
            <td><?= htmlspecialchars($p['technologies']) ?></td>
            <td><?= $p['is_featured'] ? '✅ Yes' : '—' ?></td>
            <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
            <td>
                <div class="action-btns">
                    <a href="projects.php?edit=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <a href="projects.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this project?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
