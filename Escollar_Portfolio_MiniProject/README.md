# Web-Based Portfolio Management System

**Course:** IT9a/L – Professional Track for IT 3  
**Project:** Mini Project – Dynamic Web-Based Portfolio  
**Tech Stack:** PHP, MySQL, HTML, CSS, JavaScript

---

## 📋 Project Description

A fully dynamic, database-driven portfolio management system built with PHP and MySQL. Administrators can manage portfolio projects, skills, work experiences, and view contact messages through a secure admin dashboard. The public-facing side displays a responsive, professional portfolio website.

---

## ✅ Features

### Public Portfolio
- Responsive homepage with hero section
- Featured projects showcase
- Skills with progress bars
- Work experience timeline
- Contact form (messages saved to database)
- All Projects page

### Admin Dashboard
- Secure login with password hashing (bcrypt)
- Dashboard with stats overview
- **Projects** – Add, Edit, Delete, image upload, featured toggle
- **Skills** – Add, Edit, Delete with level slider
- **Experience** – Add, Edit, Delete with date range
- **Messages** – View, mark as read, delete, reply via email
- **Profile** – Edit bio, upload photo, change password

---

## 🗂️ Folder Structure

```
portfolio/
├── index.php               # Public homepage
├── database.sql            # Database schema + seed data
├── css/
│   └── style.css           # Main stylesheet
├── js/                     # JavaScript (reserved)
├── assets/
│   └── uploads/            # Uploaded images (gitignore content)
├── includes/
│   ├── db.php              # Database connection
│   └── auth.php            # Auth helper functions
├── public/
│   └── projects.php        # All projects public page
└── admin/
    ├── login.php            # Admin login
    ├── logout.php           # Session destroy
    ├── dashboard.php        # Admin dashboard
    ├── projects.php         # Manage projects (CRUD)
    ├── skills.php           # Manage skills (CRUD)
    ├── experiences.php      # Manage experiences (CRUD)
    ├── messages.php         # View contact messages
    ├── profile.php          # Edit admin profile
    └── includes/
        ├── header.php       # Admin layout header
        └── footer.php       # Admin layout footer
```

---

## 🚀 Setup Instructions

### Requirements
- PHP 7.4+
- MySQL 5.7+ or MariaDB 10+
- Apache/Nginx with mod_rewrite (XAMPP, WAMP, LAMP, etc.)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/portfolio-management-system.git
   cd portfolio-management-system
   ```

2. **Set up the database**
   - Open phpMyAdmin or your MySQL client
   - Run the `database.sql` file to create the database and tables
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Configure the database connection**
   - Open `includes/db.php`
   - Update the credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');   // your MySQL username
   define('DB_PASS', '');       // your MySQL password
   define('DB_NAME', 'portfolio_db');
   ```

4. **Place the project in your web server root**
   - For XAMPP: `C:/xampp/htdocs/portfolio/`
   - For WAMP: `C:/wamp64/www/portfolio/`

5. **Open in browser**
   ```
   http://localhost/portfolio/         → Public portfolio
   http://localhost/portfolio/admin/   → Admin login
   ```

### Default Admin Credentials
| Field    | Value      |
|----------|------------|
| Username | `admin`    |
| Password | `admin123` |

> **⚠️ Change the password** after first login via Admin → Profile.

---

## 🗄️ Database Tables

| Table         | Description                          |
|---------------|--------------------------------------|
| `users`       | Admin accounts with hashed passwords |
| `projects`    | Portfolio projects with images       |
| `skills`      | Skills with proficiency levels       |
| `experiences` | Work experience entries              |
| `contacts`    | Contact form submissions             |

---

## 📸 Screenshots

*(Add screenshots of your running application here)*

---

## 📄 License

This project was created for academic purposes as a course requirement for IT9a/L.
