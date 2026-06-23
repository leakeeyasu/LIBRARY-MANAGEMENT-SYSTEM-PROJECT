<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$user = $conn->query($sql)->fetch_assoc();
$conn->close();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="uploads/<?php echo $user['profile_photo']; ?>" 
                         class="profile-img-lg mb-3" 
                         onerror="this.src='uploads/default.png'"
                         id="profilePreview">
                    <h4><?php echo $user['full_name']; ?></h4>
                    <p class="text-muted"><?php echo ucfirst($user['role']); ?></p>
                    <p class="text-muted"><i class="fas fa-envelope me-2"></i><?php echo $user['email']; ?></p>
                    <?php if ($user['phone']): ?>
                        <p class="text-muted"><i class="fas fa-phone me-2"></i><?php echo $user['phone']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Edit Profile</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="mb-3">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" name="profile_photo" 
                                   accept="image/*" onchange="previewImage(this, 'profilePreview')">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="full_name" 
                                       value="<?php echo $user['full_name']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo $user['username']; ?>" disabled>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo $user['email']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" 
                                       value="<?php echo $user['phone']; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3"><?php echo $user['address']; ?></textarea>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Change Password</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="new_password">
                                <div class="form-text">Leave blank to keep current password</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-aku">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
