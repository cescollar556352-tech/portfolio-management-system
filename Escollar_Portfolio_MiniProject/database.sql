CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS hobbies;
DROP TABLE IF EXISTS experiences;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    bio TEXT,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    skill_level INT DEFAULT 50,
    category VARCHAR(100) DEFAULT 'General',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    position VARCHAR(200) NOT NULL,
    company VARCHAR(200) NOT NULL,
    start_date DATE,
    end_date DATE DEFAULT NULL,
    is_current TINYINT(1) DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE hobbies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hobby_name VARCHAR(100) NOT NULL,
    hobby_icon VARCHAR(10) DEFAULT '🎯',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, full_name, email, bio, profile_image) VALUES (
    'admin',
    '$2y$10$TKh8H1.PfunQSWPTWFxGpuM9LhMX/l6yF9LKWpGR6Ox2S5iGM8nMq',
    'Christen Jefferson T. Escollar',
    'c.escollar.556352@umindanao.edu.ph',
    'I am a motivated and reliable individual who is always willing to learn and improve. I work well with others and do my best to complete tasks responsibly and efficiently. I am eager to grow my skills and contribute positively in any environment.',
    'profile.png'
);

INSERT INTO projects (user_id, title, description, technologies, github_url, is_featured) VALUES
(1, 'Project 1', 'A system designed to manage and organize information efficiently, making tasks easier and faster for users.', 'PHP, MySQL, HTML, CSS', '', 1),
(1, 'Project 2', 'A simple application that helps users perform specific tasks by providing an easy-to-use interface and organized data management.', 'PHP, MySQL, JavaScript', '', 1),
(1, 'Project 3', 'A project focused on improving productivity by automating processes and making information more accessible.', 'PHP, MySQL, Bootstrap, CSS', '', 1);

INSERT INTO skills (user_id, skill_name, skill_level, category) VALUES
(1, 'Communication Skills', 90, 'Soft Skills'),
(1, 'Problem-Solving', 85, 'Soft Skills'),
(1, 'Time Management', 80, 'Soft Skills'),
(1, 'Teamwork', 88, 'Soft Skills');

INSERT INTO hobbies (user_id, hobby_name, hobby_icon) VALUES
(1, 'Thrifting', '🛍️'),
(1, 'Drawing', '🎨'),
(1, 'Video Games', '🎮'),
(1, 'Playing Guitar', '🎸');
