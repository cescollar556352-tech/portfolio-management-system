<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Messages';
$activePage = 'messages';
$success = '';

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM contacts WHERE id=" . (int)$_GET['delete']);
    $success = 'Message deleted.';
}

// View / Mark as read
$viewMsg = null;
if (isset($_GET['view'])) {
    $id = (int)$_GET['view'];
    $conn->query("UPDATE contacts SET is_read=1 WHERE id=$id");
    $viewMsg = $conn->query("SELECT * FROM contacts WHERE id=$id")->fetch_assoc();
}

$messages = $conn->query("SELECT * FROM contacts ORDER BY sent_at DESC");
include 'includes/header.php';
?>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Contact Messages</h1>
</div>

<?php if ($viewMsg): ?>
<div class="admin-form" style="margin-bottom:2rem;">
    <h3>Message from <?= htmlspecialchars($viewMsg['name']) ?></h3>
    <p style="color:var(--text-light);margin-bottom:1rem;font-size:0.875rem;"><?= date('F d, Y H:i', strtotime($viewMsg['sent_at'])) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($viewMsg['email']) ?></p>
    <p><strong>Subject:</strong> <?= htmlspecialchars($viewMsg['subject'] ?: '(No subject)') ?></p>
    <hr style="margin:1rem 0;border-color:var(--border);">
    <p style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($viewMsg['message'])) ?></p>
    <div style="margin-top:1rem;display:flex;gap:0.75rem;">
        <a href="mailto:<?= htmlspecialchars($viewMsg['email']) ?>?subject=Re: <?= urlencode($viewMsg['subject']) ?>" class="btn btn-primary">Reply via Email</a>
        <a href="messages.php" class="btn btn-secondary">Back to List</a>
    </div>
</div>
<?php endif; ?>

<table class="data-table">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        <?php $i = 1; while ($m = $messages->fetch_assoc()): ?>
        <tr <?= !$m['is_read'] ? 'style="font-weight:600;"' : '' ?>>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><?= htmlspecialchars($m['email']) ?></td>
            <td><?= htmlspecialchars($m['subject'] ?: '(No subject)') ?></td>
            <td><?= date('M d, Y', strtotime($m['sent_at'])) ?></td>
            <td>
                <?php if (!$m['is_read']): ?>
                    <span class="badge badge-unread">Unread</span>
                <?php else: ?>
                    <span class="badge badge-read">Read</span>
                <?php endif; ?>
            </td>
            <td>
                <div class="action-btns">
                    <a href="messages.php?view=<?= $m['id'] ?>" class="btn btn-sm btn-secondary">View</a>
                    <a href="messages.php?delete=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
