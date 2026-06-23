<?php
// Start session and output buffering
session_start();
ob_start();

// Include configuration
require_once 'config/database.php';

// Include core functions
require_once 'includes/db_connection.php';
require_once 'includes/init_database.php';
require_once 'includes/auth_functions.php';
require_once 'includes/book_functions.php';
require_once 'includes/issue_functions.php';
require_once 'includes/user_functions.php';
require_once 'includes/feedback_functions.php';

// Handle actions
require_once 'includes/actions_handler.php';

// Include header
require_once 'includes/header.php';

// Main routing logic
$page = $_GET['page'] ?? 'home';

if (isset($_SESSION['user_id'])) {
    switch($page) {
        case 'dashboard':
            if ($_SESSION['role'] == 'admin') {
                require_once 'views/admin/dashboard.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'librarian_dashboard':
            if ($_SESSION['role'] == 'librarian') {
                require_once 'views/librarian/dashboard.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'student_dashboard':
            if ($_SESSION['role'] == 'student') {
                require_once 'views/student/dashboard.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'profile':
            require_once 'views/pages/profile.php';
            break;
        case 'books':
            if ($_SESSION['role'] == 'admin') {
                require_once 'views/admin/books.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'users':
            if ($_SESSION['role'] == 'admin') {
                require_once 'views/admin/users.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'issued_books':
            if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'librarian') {
                require_once 'views/admin/issued_books.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'feedback':
            if ($_SESSION['role'] == 'student' || $_SESSION['role'] == 'librarian') {
                require_once 'views/feedback/feedback.php';
            } elseif ($_SESSION['role'] == 'admin') {
                require_once 'views/admin/feedback_management.php';
            } else {
                require_once 'views/pages/home.php';
            }
            break;
        case 'about':
            require_once 'views/pages/about.php';
            break;
        case 'contact':
            require_once 'views/pages/contact.php';
            break;
        default:
            if ($_SESSION['role'] == 'admin') {
                require_once 'views/admin/dashboard.php';
            } elseif ($_SESSION['role'] == 'librarian') {
                require_once 'views/librarian/dashboard.php';
            } else {
                require_once 'views/student/dashboard.php';
            }
    }
} else {
    switch($page) {
        case 'register':
            require_once 'views/auth/register.php';
            break;
        case 'login':
            require_once 'views/auth/login.php';
            break;
        case 'about':
            require_once 'views/pages/about.php';
            break;
        case 'contact':
            require_once 'views/pages/contact.php';
            break;
        default:
            require_once 'views/pages/home.php';
    }
}

// Include footer, modals, and scripts
require_once 'includes/footer.php';
require_once 'includes/modals.php';
require_once 'includes/js_scripts.php';
?>
