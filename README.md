# AKU Digital Library - Organized Structure

This project has been reorganized from a single monolithic PHP file into a clean, modular folder structure for better maintainability and scalability.

## Project Structure

```
aku_digital_library/
│
├── index.php                    # Main entry point and router
├── style.css                    # External stylesheet (extracted from inline styles)
│
├── config/
│   └── database.php            # Database configuration constants
│
├── includes/
│   ├── db_connection.php       # Database connection handler
│   ├── init_database.php       # Database initialization and table creation
│   ├── actions_handler.php     # Form action router/handler
│   ├── auth_functions.php      # Authentication (login, register, logout)
│   ├── book_functions.php      # Book management (add, update, delete)
│   ├── issue_functions.php     # Book issuing and returning
│   ├── user_functions.php      # User management functions
│   ├── feedback_functions.php  # Feedback and contact functions
│   ├── header.php              # HTML header and navigation
│   ├── footer.php              # Footer section
│   ├── modals.php              # Bootstrap modals
│   └── js_scripts.php          # JavaScript includes
│
├── views/
│   ├── pages/
│   │   ├── home.php           # Landing/home page
│   │   ├── about.php          # About page
│   │   ├── contact.php        # Contact page
│   │   └── profile.php        # User profile page
│   │
│   ├── admin/
│   │   ├── dashboard.php      # Admin dashboard
│   │   ├── books.php          # Books management
│   │   ├── users.php          # User management
│   │   ├── issued_books.php   # Issued books management
│   │   └── feedback_management.php  # Feedback management
│   │
│   ├── librarian/
│   │   └── dashboard.php      # Librarian dashboard
│   │
│   ├── student/
│   │   └── dashboard.php      # Student dashboard
│   │
│   ├── auth/
│   │   ├── login.php          # Login page
│   │   └── register.php       # Registration page
│   │
│   └── feedback/
│       └── feedback.php       # Feedback submission page
│
└── uploads/                   # User uploads and images
    ├── default.png
    ├── book_default.png
    └── aku_logo.png
```

## Key Changes

###  1. **Separated CSS from HTML**
   - All inline `<style>` tags have been extracted into `style.css`
   - The file is placed in the same folder as `index.php` for easy access
   - Linked via `<link rel="stylesheet" href="style.css">` in the header

### 2. **Modular PHP Structure**
   - **Config folder**: Database configuration
   - **Includes folder**: Core functions and common elements
   - **Views folder**: Page templates organized by role and type

### 3. **Maintained Functionality**
   - All original functionality is preserved
   - No changes to database schema or business logic
   - All user interactions work exactly as before

## Installation

1. Place all files in your web server directory
2. Import the database or let `init_database.php` create tables automatically
3. Configure database credentials in `config/database.php`
4. Access through your web browser

## Default Admin Credentials

- **Username**: admin
- **Password**: admin123
- **Email**: admin@aku.edu

## Features

- Role-based access control (Admin, Librarian, Student)
- Book management system
- Book issuing and returning with fine calculation
- User management
- Feedback system
- Responsive design with Bootstrap 5
- Modern UI with custom styling

## Technology Stack

- PHP 7.4+
- MySQL/MariaDB
- Bootstrap 5
- Font Awesome icons
- DataTables for enhanced tables
- jQuery

## Benefits of This Structure

1. **Easier Maintenance**: Each file has a single responsibility
2. **Better Collaboration**: Multiple developers can work on different files
3. **Improved Debugging**: Issues are easier to locate and fix
4. **Scalability**: New features can be added without modifying core files
5. **Code Reusability**: Functions can be reused across different pages
6. **Clean Separation**: HTML, CSS, and PHP logic are properly separated

## Notes

- The `style.css` file contains all styles previously inline in the HTML
- All files use PHP `require_once` to avoid code duplication
- Session management is handled at the application entry point
- Database initialization runs automatically on first access
