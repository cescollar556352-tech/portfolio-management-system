<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">
<div class="login-box">
    <h2>Admin Login</h2>
    <p>Sign in to manage your portfolio</p>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="admin" required autofocus>
        </div>
        <div class="form-group" style="margin-top:1rem;">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:1.5rem;">Sign In</button>
    </form>
    <p style="margin-top:1.5rem;text-align:center;font-size:0.875rem;color:#64748b;">
        <a href="../index.php">← Back to Portfolio</a>
    </p>
    <p style="margin-top:0.5rem;text-align:center;font-size:0.8rem;color:#94a3b8;">
        Default: admin / admin123
    </p>
</div>
</body>
</html>
