<?php
// Handle form submissions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'register':
        handleRegistration();
        break;
    case 'login':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'add_book':
        addBook();
        break;
    case 'update_book':
        updateBook();
        break;
    case 'delete_book':
        deleteBook();
        break;
    case 'issue_book':
        issueBook();
        break;
    case 'return_book':
        returnBook();
        break;
    case 'add_user':
        addUser();
        break;
    case 'update_user':
        updateUser();
        break;
    case 'delete_user':
        deleteUser();
        break;
    case 'update_profile':
        updateProfile();
        break;
    case 'submit_feedback':
        submitFeedback();
        break;
    case 'submit_contact':
        submitContact();
        break;
    case 'update_role':
        updateUserRole();
        break;
    case 'mark_feedback_read':
        markFeedbackAsRead();
        break;
}
?>