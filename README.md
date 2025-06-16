# University Library Management System

A comprehensive library management system for universities, allowing librarians to manage books, users, issues, returns, fines, and reservations.

## Features

- User authentication and role-based access control (Admin, Librarian, Student, Faculty)
- Book management (add, edit, delete, search)
- User management
- Book issuing and returning
- Fine calculation and management
- Book reservations
- Dashboard with statistics
- Activity logging
- System settings

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server (XAMPP, WAMP, or similar)

### Setup Instructions

1. **Clone or download the repository to your web server's document root**
   - For XAMPP: `C:\xampp\htdocs\university-library-management`
   - For WAMP: `C:\wamp\www\university-library-management`

2. **Configure the database connection**
   - Make sure the `.env` file exists in the root directory with the correct database configuration:
   ```
   DB_HOST=localhost
   DB_NAME=library_db
   DB_USER=root
   DB_PASS=
   ```
   - If the `.env` file doesn't exist, create it with the above content

3. **Create the database and tables**
   - Make sure your MySQL server is running
   - Navigate to `http://localhost/university-library-management/install.php` in your browser
   - This will create the necessary database and tables with initial users

4. **Access the application**
   - Navigate to `http://localhost/university-library-management/` in your browser
   - Use the following demo credentials to log in:
     - Admin: admin@university.edu / admin123
     - Librarian: librarian@university.edu / lib123
     - Student: alice@university.edu / student123
     - Faculty: emma@university.edu / faculty123

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure MySQL server is running
   - Verify database credentials in `config/database.php`
   - Make sure the database user has sufficient privileges

2. **Page Not Found (404) Error**
   - Check if Apache mod_rewrite is enabled
   - Verify .htaccess file is properly configured
   - Make sure the project is in the correct directory

3. **Permission Issues**
   - Ensure the web server has read/write permissions to the project directory
   - For file uploads, check permissions on the uploads directory

## License

This project is licensed under the MIT License - see the LICENSE file for details.