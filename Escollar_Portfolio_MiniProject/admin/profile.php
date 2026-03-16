<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Edit Profile';
$activePage = 'profile';
$success = $error = '';

$user = $conn->query("SELECT * FROM users WHERE id=" . $_SESSION['admin_id'])->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email     = htmlspecialchars(trim($_POST['email']));
    $bio       = htmlspecialchars(trim($_POST['bio']));

    // Profile image
    $profile_image = $user['profile_image'] ?? '';
    if (!empty($_FILES['profile_image']['name'])) {
        $ext      = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $_SESSION['admin_id'] . '.' . $ext;
        $dest     = "../assets/uploads/$filename";
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
            $profile_image = $filename;
        }
    }

    // Password change (optional)
    $newPass = trim($_POST['new_password'] ?? '');
    if ($newPass) {
        if (strlen($newPass) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $conn->prepare("UPDATE users SET password=? WHERE id=?")->execute() ;
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hash, $_SESSION['admin_id']);
            $stmt->execute();
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, bio=?, profile_image=? WHERE id=?");
        $stmt->bind_param("ssssi", $full_name, $email, $bio, $profile_image, $_SESSION['admin_id']);
        $stmt->execute();
        $_SESSION['admin_name'] = $full_name;
        $success = 'Profile updated successfully!';
        $user = $conn->query("SELECT * FROM users WHERE id=" . $_SESSION['admin_id'])->fetch_assoc();
    }
}

include 'includes/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Edit Profile</h1>
</div>

<div class="admin-form">
    <form method="POST" enctype="multipart/form-data">
        <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem;">
            <?php if (!empty($user['profile_image'])): ?>
                <img src="../assets/uploads/<?= htmlspecialchars($user['profile_image']) ?>"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--border);">
            <?php else: ?>
                <div style="width:80px;height:80px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:2rem;color:white;">👤</div>
            <?php endif; ?>
            <div>
                <label style="font-weight:600;">Profile Photo</label>
                <input type="file" name="profile_image" accept="image/*" style="display:block;margin-top:0.4rem;">
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Bio / About Me</label>
            <textarea name="bio" rows="5"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>
        <hr style="margin:1.5rem 0;border-color:var(--border);">
        <h4 style="margin-bottom:1rem;color:var(--dark);">Change Password (leave blank to keep current)</h4>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Min. 6 characters">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
