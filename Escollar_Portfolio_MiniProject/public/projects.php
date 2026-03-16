<?php
require_once __DIR__ . '/../includes/db.php';
$user     = $conn->query("SELECT * FROM users LIMIT 1")->fetch_assoc();
$projects = $conn->query("SELECT * FROM projects ORDER BY is_featured DESC, created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects | Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav class="main-nav">
    <div class="logo"><a href="../index.php" style="color:inherit;">&lt;<span>Portfolio</span>/&gt;</a></div>
    <ul>
        <li><a href="../index.php#about">About</a></li>
        <li><a href="../index.php#skills">Skills</a></li>
        <li><a href="projects.php" style="color:var(--secondary);">Projects</a></li>
        <li><a href="../index.php#contact">Contact</a></li>
    </ul>
</nav>

<div style="padding:3rem 2rem;background:var(--dark);color:white;text-align:center;">
    <h1 style="font-size:2.5rem;font-weight:800;">All Projects</h1>
    <p style="opacity:0.7;margin-top:0.5rem;">Everything I've built</p>
</div>

<section>
    <div class="container">
        <div class="cards-grid">
            <?php while ($p = $projects->fetch_assoc()): ?>
            <div class="card">
                <?php if (!empty($p['image'])): ?>
                    <img class="card-img" src="../assets/uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                <?php else: ?>
                    <div class="card-img-placeholder">💻</div>
                <?php endif; ?>
                <div class="card-body">
                    <?php if ($p['is_featured']): ?>
                        <span style="font-size:0.75rem;background:var(--accent);color:white;padding:0.2rem 0.6rem;border-radius:20px;font-weight:600;">⭐ Featured</span>
                    <?php endif; ?>
                    <h3 class="card-title" style="margin-top:0.5rem;"><?= htmlspecialchars($p['title']) ?></h3>
                    <p class="card-text"><?= nl2br(htmlspecialchars(substr($p['description'], 0, 150))) ?>...</p>
                    <div class="tags">
                        <?php foreach (explode(',', $p['technologies']) as $tech): ?>
                            <span class="tag"><?= htmlspecialchars(trim($tech)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-links">
                        <?php if (!empty($p['github_url'])): ?>
                            <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="btn btn-sm btn-primary">GitHub</a>
                        <?php endif; ?>
                        <?php if (!empty($p['project_url'])): ?>
                            <a href="<?= htmlspecialchars($p['project_url']) ?>" target="_blank" class="btn btn-sm btn-secondary">Live Demo</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<footer>
    <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($user['full_name'] ?? 'Portfolio') ?>. Built with PHP &amp; MySQL.</p>
</footer>
</body>
</html>
