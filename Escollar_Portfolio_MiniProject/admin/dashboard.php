<?php
require_once __DIR__ . '/../includes/auth.php';
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';

$projectCount   = $conn->query("SELECT COUNT(*) as c FROM projects")->fetch_assoc()['c'];
$skillCount     = $conn->query("SELECT COUNT(*) as c FROM skills")->fetch_assoc()['c'];
$expCount       = $conn->query("SELECT COUNT(*) as c FROM experiences")->fetch_assoc()['c'];
$msgCount       = $conn->query("SELECT COUNT(*) as c FROM contacts")->fetch_assoc()['c'];
$unreadCount    = $conn->query("SELECT COUNT(*) as c FROM contacts WHERE is_read=0")->fetch_assoc()['c'];
$recentMessages = $conn->query("SELECT * FROM contacts ORDER BY sent_at DESC LIMIT 5");

include 'includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon">💻</span>
        <span class="stat-value"><?= $projectCount ?></span>
        <span class="stat-label">Total Projects</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">⚡</span>
        <span class="stat-value"><?= $skillCount ?></span>
        <span class="stat-label">Skills Listed</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">💼</span>
        <span class="stat-value"><?= $expCount ?></span>
        <span class="stat-label">Experiences</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">✉️</span>
        <span class="stat-value"><?= $msgCount ?></span>
        <span class="stat-label">Messages
            <?php if ($unreadCount > 0): ?>
                <span class="badge badge-unread"><?= $unreadCount ?> new</span>
            <?php endif; ?>
        </span>
    </div>
</div>

<!-- Recent Messages -->
<div style="background:#fff;border-radius:10px;box-shadow:var(--shadow);overflow:hidden;">
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
        <h3 style="font-weight:700;color:var(--dark);">Recent Messages</h3>
        <a href="messages.php" class="btn btn-sm btn-primary">View All</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($m = $recentMessages->fetch_assoc()): ?>
            <tr>
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
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
