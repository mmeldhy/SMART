# RT Environmental Management Information System

A Progressive Web App (PWA) for neighborhood (RT) management system built with PHP 8.x (OOP + MVC), MySQL, and Tailwind CSS.

## Features

- 🔐 User Authentication (Admin & Resident roles)
- 👥 Resident Management (CRUD)
- 💰 Fee Management
- 📢 Announcements & Schedules
- 📝 Incident Reports
- 🔔 Notifications and Offline Mode (PWA)

## Requirements

- PHP 8.0 or higher
- MySQL/MariaDB
- Web server with URL rewriting (Apache/Nginx)
- Modern browser with JavaScript enabled

## Installation

1. Clone or download this repository to your Laragon's www directory
2. Import the database structure from `sql/database.sql`
3. Configure database connection in `config/database.php`
4. Access the application via `http://rt-management.test` (if using Laragon's auto-hostname)

## Default Accounts

- Admin:
  - Username: admin
  - Password: admin123

- Resident:
  - Username: warga1
  - Password: warga123

## Project Structure

\`\`\`
/rt-management/
├── app/
│   ├── controllers/    # Controller classes
│   ├── models/         # Model classes
│   └── views/          # View templates
├── config/
│   └── database.php    # Database configuration
├── public/
│   ├── index.php       # Entry point
│   ├── css/            # CSS files
│   ├── js/             # JavaScript files
│   ├── img/            # Image assets
│   ├── manifest.json   # PWA manifest
│   └── service-worker.js # PWA service worker
├── routes/
│   └── web.php         # Route definitions
├── sql/
│   └── database.sql    # Database structure and initial data
├── .htaccess           # URL rewriting rules
└── README.md           # Project documentation
\`\`\`

## License

MIT
