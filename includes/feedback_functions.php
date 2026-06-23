<?php
// Feedback and Contact Functions
function submitFeedback() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'] ?? '';
    $rating = $_POST['rating'] ?? 5;
    
    if (empty($message)) {
        $_SESSION['error'] = "Please fill in all required fields!";
        header("Location: index.php?page=feedback");
        exit();
    }
    
    $sql = "INSERT INTO feedback (user_id, message, rating) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: index.php?page=feedback");
        exit();
    }
    
    $stmt->bind_param("isi", $user_id, $message, $rating);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Feedback submitted successfully!";
    } else {
        $_SESSION['error'] = "Failed to submit feedback: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=feedback");
    exit();
}

function markFeedbackAsRead() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $feedback_id = $_GET['id'] ?? 0;
    $conn = getConnection();
    
    $sql = "UPDATE feedback SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Feedback marked as read!";
    } else {
        $_SESSION['error'] = "Failed to update feedback status: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=feedback");
    exit();
}
?>
