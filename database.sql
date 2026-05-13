/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

/* =========================
   ADMIN USERS
========================= */

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin_users`
(`id`, `username`, `password_hash`, `created_at`, `updated_at`)
VALUES
(1,'elifhrl','$2y$10$32oBaiAvJ6MY3f.v31BCoOdcZnkOxEDWJnkoRsj8OrSX1QikmH69K','2026-05-13 16:43:19','2026-05-13 16:51:37');

/* =========================
   BLOG POSTS
========================= */

DROP TABLE IF EXISTS `blog_posts`;

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================
   EXPERIENCE
========================= */

DROP TABLE IF EXISTS `experience`;

CREATE TABLE `experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(120) NOT NULL,
  `role` varchar(120) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `technologies` varchar(255) DEFAULT NULL,
  `details` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `experience`
(`id`, `company`, `role`, `start_date`, `end_date`, `technologies`, `details`, `sort_order`, `created_at`)
VALUES
(
1,
'REZERVEM A.Ş.',
'INTERN_ROLE',
'2025-06-01',
'2025-08-11',
'PostgreSQL, REST APIs, Next.js, .NET, Docker',
'I completed a software development internship at Rezervem.
Where I worked on modern web application development and UI implementation processes. During the internship, I contributed to responsive interface design, frontend development, database-driven features, and project structure organization in a collaborative development environment.',
1,
'2026-05-12 14:38:25'
);

/* =========================
   PROJECTS
========================= */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `technologies` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `github_link` varchar(255) DEFAULT NULL,
  `live_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(30) DEFAULT 'completed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `projects`
(`id`, `title`, `description`, `technologies`, `image`, `github_link`, `live_link`, `created_at`, `status`)
VALUES

(
1,
'Meridyen İklimlendirme',
'A responsive and modern corporate web interface developed for Meridyen HVAC. Features dynamic product catalogs and series filtering. Project successfully delivered; source code and live server are currently under client management.',
'HTML5, CSS3, JAVASCRIPT, POSTGRESQL',
'assets/images/projects/klima-1.jpg',
'https://github.com/elifhirli/KlimaSitesi',
'',
'2026-05-09 15:45:44',
'offline'
),

(
3,
'EDUSTREAM APP',
'A comprehensive full-stack school management and communication platform connecting teachers, parents, and students. Engineered with a robust architecture featuring role-based dashboards, secure session authentication, and database-driven assignment and messaging modules. Includes interactive role-based interfaces, QR-based class access, and detailed system planning. Source code is kept private to protect system integrity and intellectual property.',
'NEXT.JS 16, TYPESCRIPT, POSTGRESQL, PRISMA',
'assets/images/projects/edustream.jpg',
'',
'',
'2026-05-09 15:45:44',
'completed'
),

(
4,
'emlak.ai',
'An AI-supported real estate staging platform for agents and homeowners. Users upload room photos and enter measurements, room type, style, and budget to receive layout recommendations, measured 2D plans, furniture lists, design reports, and listing drafts.',
'NEXT.JS, REACT 19, TYPESCRIPT, POSTGRESQL, JWT, GEMINI API, DOCKER',
'assets/images/projects/emlak1.jpg',
'',
'',
'2026-05-12 14:29:18',
'in_progress'
);

/* =========================
   MESSAGES
========================= */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;