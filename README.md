# Elif Nur Hirli Portfolio

Personal portfolio website built with PHP, MySQL, CSS, and JavaScript.

## Features

- Semantic HTML structure with `header`, `nav`, `main`, `section`, `footer`, and `form`.
- Responsive portfolio layout.
- Dark and light theme toggle.
- Dynamic project listing from MySQL.
- Dynamic experience log from MySQL.
- Contact form with AJAX submission.
- Admin login with PHP sessions and secure cookies.
- Admin dashboard for adding and editing projects.
- Admin dashboard for adding and editing experience records.
- SQL export included in `database.sql`.

## Technologies

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- XAMPP

## Setup

1. Copy the project folder into `xampp/htdocs/portfolio`.
2. Start Apache and MySQL from XAMPP.
3. Import `database.sql` into MySQL.
4. Check database settings in `config/database.php`.
5. Open `http://localhost/portfolio`.

## Admin

Admin login is available at:

```text
http://localhost/portfolio/admin-login.php
```

Admin users are stored in the `admin_users` table. Passwords are stored as hashes.

## Git History

The repository is committed in multiple steps to show the development process:

- Initial commit
- Navbar added
- Responsive layout completed
- Admin panel added
- Database connection fixed
- Documentation added
