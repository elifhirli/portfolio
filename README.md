# Elif Nur Hırlı Portfolio

Personal portfolio website developed using PHP, MySQL, JavaScript, HTML5, and CSS3. The project was designed as a dynamic full-stack web application to showcase academic background, projects, and technical skills.

## Project Purpose

The purpose of this project is to create a professional portfolio platform that can be used as a digital identity and career showcase. The system allows visitors to explore projects and contact the owner, while an administrator can manage content through a secure dashboard.

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- AJAX
- XAMPP
- InfinityFree Hosting

## Features

- Semantic HTML structure (header, nav, main, section, footer)
- Responsive layout using CSS
- Dark / light theme toggle
- Dynamic project and experience data from MySQL
- AJAX contact form
- JavaScript form validation
- Secure admin authentication
- Session and cookie management
- Admin dashboard
- Database integration

## System Architecture

Frontend technologies were used to create an interactive interface, while PHP handled backend logic and database communication. AJAX was implemented to submit forms asynchronously without page refresh.

Database tables include:

- `admin_users`
- `messages`
- `projects`
- `experience`

## Challenges and Solutions

During deployment several issues appeared, including routing problems, CSS path issues, session handling problems, and database configuration differences between local and production environments.

These problems were solved by updating file paths, configuring session handling correctly, and adapting the application for InfinityFree hosting.

## Setup

1. Copy project into `xampp/htdocs/portfolio`
2. Start Apache and MySQL
3. Import `database.sql`
4. Configure `database.php`
5. Open `localhost/portfolio`

## Live Demo

[https://elifportfolio.42web.io](https://elifportfolio.42web.io)

## GitHub Repository

[https://github.com/elifhirli/portfolio](https://github.com/elifhirli/portfolio)
