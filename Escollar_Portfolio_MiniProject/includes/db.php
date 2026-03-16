<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    bio TEXT,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    technologies VARCHAR(255),
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    image VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    skill_level INT DEFAULT 50,
    category VARCHAR(100) DEFAULT 'General',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    position VARCHAR(200) NOT NULL,
    company VARCHAR(200) NOT NULL,
    start_date DATE,
    end_date DATE DEFAULT NULL,
    is_current TINYINT(1) DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS hobbies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hobby_name VARCHAR(100) NOT NULL,
    hobby_icon VARCHAR(10) DEFAULT '🎯',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$check = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc();
if ($check['c'] == 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, email, bio, profile_image) VALUES (?, ?, ?, ?, ?, ?)");
    $full_name = 'Christen Jefferson T. Escollar';
    $email = 'c.escollar.556352@umindanao.edu.ph';
    $bio = 'I am a motivated and reliable individual who is always willing to learn and improve. I work well with others and do my best to complete tasks responsibly and efficiently. I am eager to grow my skills and contribute positively in any environment.';
    $profile_image = 'profile.png';
    $username = 'admin';
    $stmt->bind_param("ssssss", $username, $hash, $full_name, $email, $bio, $profile_image);
    $stmt->execute();

    $conn->query("INSERT INTO projects (user_id, title, description, technologies, github_url, is_featured) VALUES
        (1, 'Project 1', 'A system designed to manage and organize information efficiently, making tasks easier and faster for users.', 'PHP, MySQL, HTML, CSS', '', 1),
        (1, 'Project 2', 'A simple application that helps users perform specific tasks by providing an easy-to-use interface and organized data management.', 'PHP, MySQL, JavaScript', '', 1),
        (1, 'Project 3', 'A project focused on improving productivity by automating processes and making information more accessible.', 'PHP, MySQL, Bootstrap, CSS', '', 1)");

    $conn->query("INSERT INTO skills (user_id, skill_name, skill_level, category) VALUES
        (1, 'Communication Skills', 90, 'Soft Skills'),
        (1, 'Problem-Solving', 85, 'Soft Skills'),
        (1, 'Time Management', 80, 'Soft Skills'),
        (1, 'Teamwork', 88, 'Soft Skills')");

    $conn->query("INSERT INTO hobbies (user_id, hobby_name, hobby_icon) VALUES
        (1, 'Thrifting', '🛍️'),
        (1, 'Drawing', '🎨'),
        (1, 'Video Games', '🎮'),
        (1, 'Playing Guitar', '🎸')");
} else {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username=?, password=?, full_name=?, email=?, bio=?, profile_image=? WHERE id=1");
    $username = 'admin';
    $full_name = 'Christen Jefferson T. Escollar';
    $email = 'c.escollar.556352@umindanao.edu.ph';
    $bio = 'I am a motivated and reliable individual who is always willing to learn and improve. I work well with others and do my best to complete tasks responsibly and efficiently. I am eager to grow my skills and contribute positively in any environment.';
    $profile_image = 'profile.png';
    $stmt->bind_param("ssssss", $username, $hash, $full_name, $email, $bio, $profile_image);
    $stmt->execute();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
