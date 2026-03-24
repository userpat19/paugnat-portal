# PAUGNAT Portal

A web application for managing the USTP PAUGNAT 2027 events, including college leaderboards, event management, and admin dashboard.

## Features

- **Admin Dashboard**: Login system for administrators to manage college points and events
- **Public Pages**: View events, leaderboards, and contact information
- **College Leaderboards**: Real-time display of college points and rankings
- **Event Management**: Update event details and dates
- **Responsive Design**: Bootstrap-based UI that works on all devices

## Setup Instructions

1. **Prerequisites**:
   - XAMPP (or similar PHP/MySQL environment)
   - PHP 8.2+
   - MySQL

2. **Installation**:
   - Clone or download the project to `C:\xampp\htdocs\PAUGNAT`
   - Start XAMPP (Apache and MySQL)
   - Import the database: Run the SQL script in `database/paugnat.sql`
   - Access the application at `http://localhost/PAUGNAT/home.php`

3. **Default Admin Credentials**:
   - Username: `admin`
   - Password: `admin`

## Project Structure

```
PAUGNAT/
├── home.php               # Public home page
├── index.php              # Admin login page
├── admin/
│   ├── dashboard.php      # Admin panel
│   └── logout.php         # Logout script
├── pages/
│   ├── about.php          # About page
│   ├── events.php         # Events page
│   ├── leaderboards.php   # Rankings page
│   └── contact.php        # Contact page
├── backend/               # PHP API endpoints
├── css/style.css          # Styles
├── js/                    # JavaScript files
├── database/paugnat.sql   # Database schema
└── images/                # Assets
```

## Technologies Used

- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: PHP 8.2, MySQL
- **Database**: MySQL
- **Styling**: Custom CSS with USTP color scheme