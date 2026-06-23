<?php
// Book Issue/Return Functions

function issueBook() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'librarian')) {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $book_id = $_POST['book_id'];
    $user_id = $_POST['user_id'] ?? $_POST['student_id'];
    $issued_by = $_SESSION['user_id'];
    $issue_date = date('Y-m-d');
    $due_date = $_POST['due_date'];
    
    // Check if book is available
    $check_sql = "SELECT available, title FROM books WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $book = $result->fetch_assoc();
    $check_stmt->close();
    
    if ($book['available'] < 1) {
        $_SESSION['error'] = "Book is not available!";
        $conn->close();
        header("Location: index.php?page=issued_books");
        exit();
    }
    
    // Check if student already has this book issued
    $check_sql = "SELECT COUNT(*) as count FROM issued_books WHERE book_id = ? AND user_id = ? AND status = 'issued'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $book_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();
    
    if ($row['count'] > 0) {
        $_SESSION['error'] = "Student already has this book issued!";
        $conn->close();
        header("Location: index.php?page=issued_books");
        exit();
    }
    
    // Issue book
    $sql = "INSERT INTO issued_books (book_id, user_id, issued_by, issue_date, due_date, status) 
            VALUES (?, ?, ?, ?, ?, 'issued')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $book_id, $user_id, $issued_by, $issue_date, $due_date);
    
    if ($stmt->execute()) {
        // Update book availability
        $update_sql = "UPDATE books SET available = available - 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $book_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        $_SESSION['message'] = "Book issued successfully!";
    } else {
        $_SESSION['error'] = "Failed to issue book: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=" . ($_SESSION['role'] == 'admin' ? 'issued_books' : 'librarian_dashboard'));
    exit();
}

function returnBook() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'librarian')) {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $issue_id = $_POST['issue_id'] ?? $_GET['issue_id'] ?? $_GET['id'];
    
    // Debug: Check if issue_id is received
    if (!$issue_id) {
        $_SESSION['error'] = "No issue ID provided!";
        header("Location: index.php?page=" . ($_SESSION['role'] == 'admin' ? 'issued_books' : 'librarian_dashboard'));
        exit();
    }
    
    $return_date = date('Y-m-d');
    
    // Get book_id before returning
    $get_sql = "SELECT book_id, user_id FROM issued_books WHERE id = ?";
    $get_stmt = $conn->prepare($get_sql);
    $get_stmt->bind_param("i", $issue_id);
    $get_stmt->execute();
    $result = $get_stmt->get_result();
    
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Issue record not found!";
        $get_stmt->close();
        $conn->close();
        header("Location: index.php?page=" . ($_SESSION['role'] == 'admin' ? 'issued_books' : 'librarian_dashboard'));
        exit();
    }
    
    $issue = $result->fetch_assoc();
    $book_id = $issue['book_id'];
    $user_id = $issue['user_id'];
    $get_stmt->close();
    
    // Update issued book record
    $sql = "UPDATE issued_books SET return_date = ?, status = 'returned' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $return_date, $issue_id);
    
    if ($stmt->execute()) {
        // Update book availability
        $update_sql = "UPDATE books SET available = available + 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $book_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        $_SESSION['message'] = "Book returned successfully!";
    } else {
        $_SESSION['error'] = "Failed to return book: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=" . ($_SESSION['role'] == 'admin' ? 'issued_books' : 'librarian_dashboard'));
    exit();
}
?>
