<?php
require_once __DIR__ . '/db.php';

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /admin/login.php");
        exit();
    }
}

function login($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, username, password, full_name FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_user'] = $user['username'];
            $_SESSION['admin_name'] = $user['full_name'];
            return true;
        }
    }
    return false;
}

function logout() {
    session_destroy();
    header("Location: /admin/login.php");
    exit();
}
