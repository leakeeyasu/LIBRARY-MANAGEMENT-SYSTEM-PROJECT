<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$conn = getConnection();
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$conn->close();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Manage Users</h2>
            <p class="text-muted">Add, edit, or manage user accounts</p>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-aku" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus me-2"></i>Add New User
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profile</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td>
                                <img src="uploads/<?php echo $user['profile_photo']; ?>" 
                                     class="profile-img-sm" 
                                     onerror="this.src='uploads/default.png'">
                            </td>
                            <td><?php echo $user['full_name']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $user['role'] == 'admin' ? 'danger' : 
                                        ($user['role'] == 'librarian' ? 'warning' : 'info'); 
                                ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-user-btn"
                                    data-user-id="<?php echo $user['id']; ?>"
                                    data-user-name="<?php echo htmlspecialchars($user['full_name']); ?>"
                                    data-user-email="<?php echo $user['email']; ?>"
                                    data-user-phone="<?php echo $user['phone']; ?>"
                                    data-user-address="<?php echo htmlspecialchars($user['address']); ?>"
                                    data-user-active="<?php echo $user['is_active']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button class="btn btn-sm btn-warning role-btn"
                                    data-user-id="<?php echo $user['id']; ?>"
                                    data-user-role="<?php echo $user['role']; ?>">
                                    <i class="fas fa-user-tag"></i>
                                </button>
                                
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <!-- Fixed: Changed from GET to POST method -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger delete-user-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
