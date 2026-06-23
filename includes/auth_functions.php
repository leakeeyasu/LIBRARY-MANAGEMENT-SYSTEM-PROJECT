<?php
// Authentication Functions
function handleRegistration() {
    $conn = getConnection();
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    // Handle profile photo upload
    $profile_photo = 'default.png';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $allowed)) {
            $profile_photo = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['profile_photo']['tmp_name'], 'uploads/' . $profile_photo);
        }
    }
    
    $sql = "INSERT INTO users (username, email, password, full_name, phone, address, profile_photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $email, $password, $full_name, $phone, $address, $profile_photo);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! Admin will approve your account.";
    } else {
        $_SESSION['error'] = "Registration failed: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
}

function handleLogin() {
    $conn = getConnection();
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = ? AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['profile_photo'] = $user['profile_photo'];
            $_SESSION['message'] = "Login successful!";
            
            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: index.php?page=dashboard");
            } elseif ($user['role'] == 'librarian') {
                header("Location: index.php?page=librarian_dashboard");
            } else {
                header("Location: index.php?page=student_dashboard");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "User not found or inactive!";
    }
    
    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
}

function handleLogout() {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
