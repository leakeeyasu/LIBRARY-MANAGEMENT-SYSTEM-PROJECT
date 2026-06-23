<?php
// Initialize session and buffer
session_start();
ob_start();

// Include configuration and core files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/init_database.php';
require_once __DIR__ . '/actions_handler.php';
require_once __DIR__ . '/auth_functions.php';
require_once __DIR__ . '/book_functions.php';
require_once __DIR__ . '/issue_functions.php';
require_once __DIR__ . '/user_functions.php';
require_once __DIR__ . '/feedback_functions.php';

// No automatic updates needed
?>
