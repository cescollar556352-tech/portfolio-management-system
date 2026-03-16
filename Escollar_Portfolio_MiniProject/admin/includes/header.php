<?php requireLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> | Portfolio Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .sidebar-nav a svg { width:18px; height:18px; }
    </style>
</head>
<body>
<div class="admin-layout">
<!-- Sidebar -->
<aside class="admin-sidebar">
    <div class="sidebar-brand">&lt;<span>Portfolio</span>/&gt; Admin</div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" <?= ($activePage??'')==='dashboard'?'class="active"':'' ?>>
            <span class="icon">📊</span> Dashboard
        </a>
        <a href="projects.php" <?= ($activePage??'')==='projects'?'class="active"':'' ?>>
            <span class="icon">💻</span> Projects
        </a>
        <a href="skills.php" <?= ($activePage??'')==='skills'?'class="active"':'' ?>>
            <span class="icon">⚡</span> Skills
        </a>
        <a href="experiences.php" <?= ($activePage??'')==='experiences'?'class="active"':'' ?>>
            <span class="icon">💼</span> Experience
        </a>
        <a href="hobbies.php" <?= ($activePage??'')==='hobbies'?'class="active"':'' ?>>
            <span class="icon">🎯</span> Hobbies
        </a>
        <a href="messages.php" <?= ($activePage??'')==='messages'?'class="active"':'' ?>>
            <span class="icon">✉️</span> Messages
        </a>
        <a href="profile.php" <?= ($activePage??'')==='profile'?'class="active"':'' ?>>
            <span class="icon">👤</span> Profile
        </a>
        <a href="../index.php" target="_blank">
            <span class="icon">🌐</span> View Portfolio
        </a>
        <a href="logout.php" style="margin-top:auto;">
            <span class="icon">🚪</span> Logout
        </a>
    </nav>
</aside>
<!-- Main -->
<main class="admin-main">
<div class="admin-topbar">
    <span style="font-weight:600;color:#1e1b4b;"><?= $pageTitle ?? 'Dashboard' ?></span>
    <span style="color:#64748b;">Welcome, <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? '') ?></strong></span>
</div>
<div class="admin-content">
