<?php
// User Management Functions
function addUser() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    $sql = "INSERT INTO users (username, email, password, full_name, role, phone, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $email, $password, $full_name, $role, $phone, $address);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add user: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=users");
    exit();
}

function updateUser() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, address = ?, is_active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $full_name, $email, $phone, $address, $is_active, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update user: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=users");
    exit();
}

function deleteUser() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $user_id = $_POST['user_id'] ?? $_GET['id'] ?? 0;
    if (!$user_id) {
        $_SESSION['error'] = "Invalid user ID!";
        header("Location: index.php?page=users");
        exit();
    }
    
    $conn = getConnection();
    
    // Prevent deleting yourself
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Cannot delete your own account!";
        $conn->close();
        header("Location: index.php?page=users");
        exit();
    }
    
    // Check if user has issued books
    $check_sql = "SELECT COUNT(*) as count FROM issued_books WHERE user_id = ? AND status = 'issued'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();
    
    if ($row['count'] > 0) {
        $_SESSION['error'] = "Cannot delete user with issued books!";
    } else {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete user: " . $stmt->error;
        }
        $stmt->close();
    }
    
    $conn->close();
    header("Location: index.php?page=users");
    exit();
}

function updateUserRole() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        $_SESSION['error'] = "Unauthorized access!";
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $role, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "User role updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update role: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=users");
    exit();
}

function updateProfile() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    
    $conn = getConnection();
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    $profile_photo = $_SESSION['profile_photo'];
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $allowed)) {
            $profile_photo = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['profile_photo']['tmp_name'], 'uploads/' . $profile_photo);
            $_SESSION['profile_photo'] = $profile_photo;
        }
    }
    
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET full_name = ?, phone = ?, address = ?, profile_photo = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $full_name, $phone, $address, $profile_photo, $new_password, $user_id);
    } else {
        $sql = "UPDATE users SET full_name = ?, phone = ?, address = ?, profile_photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $full_name, $phone, $address, $profile_photo, $user_id);
    }
    
    if ($stmt->execute()) {
        $_SESSION['full_name'] = $full_name;
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php?page=profile");
    exit();
}
?>
