<?php
require_once __DIR__ . '/includes/db.php';

$user        = $conn->query("SELECT * FROM users LIMIT 1")->fetch_assoc();
$uid         = $user['id'] ?? 1;
$projects    = $conn->query("SELECT * FROM projects WHERE user_id=$uid AND is_featured=1 ORDER BY created_at DESC LIMIT 6");
$skills      = $conn->query("SELECT * FROM skills WHERE user_id=$uid ORDER BY skill_level DESC");
$hobbies     = $conn->query("SELECT * FROM hobbies WHERE user_id=$uid ORDER BY id ASC");

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    if ($name && $email && $message) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $msg = '<div class="alert alert-success">Your message has been sent successfully!</div>';
    } else {
        $msg = '<div class="alert alert-error">Please fill in all required fields.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christen Jefferson T. Escollar | Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        html { scroll-behavior: smooth; }
        .hobbies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.25rem;
        }
        .hobby-card {
            background: var(--white);
            border-radius: 14px;
            padding: 2rem 1rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .hobby-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .hobby-icon { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }
        .hobby-name { font-weight: 600; color: var(--dark); font-size: 1rem; }
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .contact-info-item {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: rgba(255,255,255,0.9);
            font-size: 0.95rem;
        }
        nav.main-nav ul a.active { color: var(--secondary); }
    </style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="main-nav">
    <div class="logo">&lt;<span>CJE</span>/&gt;</div>
    <ul>
        <li><a href="#about">About</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#skills">Skills</a></li>
        <li><a href="#hobbies">Hobbies</a></li>
        <li><a href="#experience">Experience</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="admin/login.php" style="color:var(--secondary);">Admin</a></li>
    </ul>
</nav>

<!-- HERO / ABOUT -->
<section class="hero" id="about">
    <div class="container">
        <img src="assets/uploads/<?= htmlspecialchars($user['profile_image'] ?? 'profile.png') ?>"
             alt="<?= htmlspecialchars($user['full_name']) ?>"
             style="width:150px;height:150px;border-radius:50%;object-fit:cover;
                    border:4px solid rgba(255,255,255,0.4);margin-bottom:1.5rem;
                    box-shadow:0 8px 30px rgba(0,0,0,0.3);">
        <h1>Hi, I'm <span><?= htmlspecialchars($user['full_name'] ?? 'Christen Jefferson T. Escollar') ?></span></h1>
        <p style="max-width:680px;margin:1rem auto 2rem;line-height:1.8;">
            <?= htmlspecialchars($user['bio'] ?? '') ?>
        </p>
        <a href="#projects" class="btn btn-secondary">View My Projects</a>
        &nbsp;
        <a href="#contact" class="btn btn-outline">Get In Touch</a>
    </div>
</section>

<!-- PROJECTS -->
<section id="projects" style="background:var(--white);">
    <div class="container">
        <h2 class="section-title">My Projects</h2>
        <p class="section-subtitle">A showcase of my work and creations</p>
        <div class="cards-grid">
            <?php
            $gradients = ['#4f46e5,#06b6d4','#7c3aed,#f59e0b','#059669,#3b82f6'];
            $pNum = 0;
            while ($p = $projects->fetch_assoc()):
                $grad = $gradients[$pNum % 3];
                $pNum++;
            ?>
            <div class="card">
                <div class="card-img-placeholder"
                     style="background:linear-gradient(135deg,<?= $grad ?>);">💻</div>
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($p['title']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($p['description']) ?></p>
                    <div class="tags">
                        <?php foreach (explode(',', $p['technologies']) as $tech): ?>
                            <span class="tag"><?= htmlspecialchars(trim($tech)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($p['github_url'])): ?>
                    <div class="card-links">
                        <a href="<?= htmlspecialchars($p['github_url']) ?>"
                           target="_blank" class="btn btn-sm btn-primary">GitHub</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- SKILLS -->
<section id="skills" style="background:var(--light-bg);">
    <div class="container">
        <h2 class="section-title">Skills</h2>
        <p class="section-subtitle">Capabilities I bring to every task</p>
        <div class="skills-grid">
            <?php while ($s = $skills->fetch_assoc()): ?>
            <div class="skill-card">
                <div class="skill-header">
                    <span class="skill-name"><?= htmlspecialchars($s['skill_name']) ?></span>
                    <span class="skill-pct"><?= $s['skill_level'] ?>%</span>
                </div>
                <div class="skill-bar">
                    <div class="skill-fill" style="width:<?= $s['skill_level'] ?>%"></div>
                </div>
                <small style="color:var(--text-light);font-size:0.78rem;margin-top:0.3rem;display:block;">
                    <?= htmlspecialchars($s['category']) ?>
                </small>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- HOBBIES -->
<section id="hobbies" style="background:var(--white);">
    <div class="container">
        <h2 class="section-title">Hobbies &amp; Interests</h2>
        <p class="section-subtitle">Things I enjoy outside of work</p>
        <div class="hobbies-grid">
            <?php while ($h = $hobbies->fetch_assoc()): ?>
            <div class="hobby-card">
                <span class="hobby-icon"><?= $h['hobby_icon'] ?></span>
                <span class="hobby-name"><?= htmlspecialchars($h['hobby_name']) ?></span>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- EXPERIENCE -->
<section id="experience" style="background:var(--light-bg);">
    <div class="container" style="max-width:760px;">
        <h2 class="section-title">Experience</h2>
        <p class="section-subtitle">My professional journey so far</p>
        <?php
        $expResult = $conn->query("SELECT * FROM experiences WHERE user_id=$uid ORDER BY is_current DESC, start_date DESC");
        ?>
        <?php if ($expResult->num_rows > 0): ?>
        <div class="timeline">
            <?php while ($e = $expResult->fetch_assoc()): ?>
            <div class="timeline-item">
                <div class="timeline-date">
                    <?= date('M Y', strtotime($e['start_date'])) ?> &ndash;
                    <?= $e['is_current'] ? 'Present' : ($e['end_date'] ? date('M Y', strtotime($e['end_date'])) : '?') ?>
                    <?php if ($e['is_current']): ?><span class="current-badge">Current</span><?php endif; ?>
                </div>
                <div class="timeline-title"><?= htmlspecialchars($e['position']) ?></div>
                <div class="timeline-company"><?= htmlspecialchars($e['company']) ?></div>
                <p style="color:var(--text-light);font-size:0.9rem;margin-top:0.4rem;">
                    <?= nl2br(htmlspecialchars($e['description'])) ?>
                </p>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:3rem;background:var(--white);border-radius:12px;box-shadow:var(--shadow);">
            <span style="font-size:3rem;">💼</span>
            <p style="color:var(--text-light);margin-top:1rem;">No experience entries yet.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CONTACT -->
<section class="contact-section" id="contact">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <p class="section-subtitle">Feel free to reach out anytime!</p>

        <div class="contact-info">
            <div class="contact-info-item">
                <span>📧</span>
                <a href="mailto:c.escollar.556352@umindanao.edu.ph"
                   style="color:inherit;">c.escollar.556352@umindanao.edu.ph</a>
            </div>
            <div class="contact-info-item">
                <span>📞</span>
                <a href="tel:09350777287" style="color:inherit;">09350777287</a>
            </div>
        </div>

        <?= $msg ?>

        <form class="contact-form" method="POST">
            <div class="form-group">
                <label>Your Name *</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" placeholder="What is this about?">
            </div>
            <div class="form-group">
                <label>Message *</label>
                <textarea name="message" placeholder="Write your message here..." required></textarea>
            </div>
            <button type="submit" name="contact_submit" class="btn btn-secondary"
                    style="width:100%;font-size:1rem;padding:0.9rem;">
                Send Message
            </button>
        </form>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>&copy; <?= date('Y') ?> Christen Jefferson T. Escollar &mdash; All Rights Reserved.</p>
    <p style="margin-top:0.4rem;font-size:0.8rem;opacity:0.6;">Built with PHP &amp; MySQL</p>
</footer>

<script>
// Highlight active nav link on scroll
const sections = document.querySelectorAll('section[id]');
const navLinks  = document.querySelectorAll('nav.main-nav ul a');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
        if (window.scrollY >= s.offsetTop - 90) current = s.getAttribute('id');
    });
    navLinks.forEach(a => {
        a.classList.remove('active');
        if (a.getAttribute('href') === '#' + current) a.classList.add('active');
    });
});
</script>

</body>
</html>
