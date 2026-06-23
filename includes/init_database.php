<?php
// Initialize database tables
function initializeDatabase() {
    $conn = getConnection();
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'librarian', 'student') DEFAULT 'student',
        profile_photo VARCHAR(255) DEFAULT 'default.png',
        phone VARCHAR(20),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_active BOOLEAN DEFAULT TRUE
    )";
    $conn->query($sql);
    
    // Create books table
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        isbn VARCHAR(20) UNIQUE NOT NULL,
        category VARCHAR(100),
        publisher VARCHAR(255),
        publication_year YEAR,
        quantity INT DEFAULT 1,
        available INT DEFAULT 1,
        description TEXT,
        cover_image VARCHAR(255) DEFAULT 'book_default.png',
        added_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (added_by) REFERENCES users(id)
    )";
    $conn->query($sql);
    
    // Create issued_books table
    $sql = "CREATE TABLE IF NOT EXISTS issued_books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        book_id INT NOT NULL,
        user_id INT NOT NULL,
        issued_by INT NOT NULL,
        issue_date DATE NOT NULL,
        due_date DATE NOT NULL,
        return_date DATE,
        status ENUM('issued', 'returned') DEFAULT 'issued',
        FOREIGN KEY (book_id) REFERENCES books(id),
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (issued_by) REFERENCES users(id)
    )";
    $conn->query($sql);
    
    // Create feedback table
    $sql = "CREATE TABLE IF NOT EXISTS feedback (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        message TEXT NOT NULL,
        rating INT DEFAULT 5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_read BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    $conn->query($sql);
    
    // Check if admin exists, if not create one
    $result = $conn->query("SELECT * FROM users WHERE role='admin' LIMIT 1");
    if ($result->num_rows == 0) {
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, full_name, role, phone) 
                VALUES ('admin', 'admin@aku.edu', '$default_password', 'System Administrator', 'admin', '0934234556')";
        $conn->query($sql);
    }
    
    // Check if uploads directory exists
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    // Create default images if they don't exist
    $default_images = ['default.png', 'book_default.png', 'aku_logo.png'];
    foreach ($default_images as $image) {
        if (!file_exists("uploads/$image")) {
            // Create a simple placeholder
            $im = imagecreate(100, 100);
            $bg_color = imagecolorallocate($im, 52, 152, 219);
            $text_color = imagecolorallocate($im, 255, 255, 255);
            imagestring($im, 5, 10, 45, "AKU", $text_color);
            if ($image == 'default.png') {
                imagepng($im, "uploads/$image");
            } elseif ($image == 'aku_logo.png') {
                imagepng($im, "uploads/$image");
            }
            imagedestroy($im);
        }
    }
    
    $conn->close();
}

// Initialize database
initializeDatabase();
?>
