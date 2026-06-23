<?php
// Book Management Functions
function addBook() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category = $_POST['category'];
    $publisher = $_POST['publisher'] ?? '';
    $year = $_POST['publication_year'] ?? date('Y');
    $quantity = $_POST['quantity'];
    $description = $_POST['description'] ?? '';
    $added_by = $_SESSION['user_id'];
    
    $sql = "INSERT INTO books (title, author, isbn, category, publisher, publication_year, quantity, available, description, added_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $available = $quantity;
    $stmt->bind_param("ssssssiisi", $title, $author, $isbn, $category, $publisher, $year, $quantity, $available, $description, $added_by);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Book added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add book: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=books");
    exit();
}

function updateBook() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category = $_POST['category'];
    $publisher = $_POST['publisher'] ?? '';
    $year = $_POST['publication_year'] ?? '';
    $quantity = $_POST['quantity'];
    $description = $_POST['description'] ?? '';
    
    // Get current available count
    $sql = "SELECT available, quantity FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
    
    // Calculate new available count
    $current_quantity = $book['quantity'];
    $current_available = $book['available'];
    $quantity_change = $quantity - $current_quantity;
    $new_available = $current_available + $quantity_change;
    if ($new_available < 0) $new_available = 0;
    
    $sql = "UPDATE books SET title = ?, author = ?, isbn = ?, category = ?, publisher = ?, 
            publication_year = ?, quantity = ?, available = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiisi", $title, $author, $isbn, $category, $publisher, $year, $quantity, $new_available, $description, $book_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Book updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update book: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=books");
    exit();
}

function deleteBook() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $book_id = $_POST['book_id'] ?? $_GET['id'] ?? 0;
    if (!$book_id) {
        $_SESSION['error'] = "Invalid book ID!";
        header("Location: index.php?page=books");
        exit();
    }
    
    $conn = getConnection();
    
    // Check if book has any issue records (current or historical)
    $check_sql = "SELECT COUNT(*) as count FROM issued_books WHERE book_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();
    
    if ($row['count'] > 0) {
        $_SESSION['error'] = "Cannot delete book that has issue history! Please contact system administrator.";
    } else {
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Book deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete book: " . $stmt->error;
        }
        $stmt->close();
    }
    
    $conn->close();
    header("Location: index.php?page=books");
    exit();
}
?>
