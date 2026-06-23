<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$conn = getConnection();
$feedbacks = $conn->query("SELECT f.*, u.full_name, u.username, u.profile_photo 
    FROM feedback f 
    JOIN users u ON f.user_id = u.id 
    ORDER BY f.created_at DESC");
$conn->close();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Feedback Management</h2>
            <p class="text-muted">View and respond to user feedback</p>
        </div>
    </div>

    <div class="row">
        <?php if ($feedbacks->num_rows > 0): ?>
            <?php while($feedback = $feedbacks->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <img src="uploads/<?php echo $feedback['profile_photo']; ?>" 
                                 class="profile-img-sm me-3" 
                                 onerror="this.src='uploads/default.png'">
                            <div>
                                <h6 class="mb-0"><?php echo $feedback['full_name']; ?></h6>
                                <small class="text-muted">@<?php echo $feedback['username']; ?></small>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Rating:</strong>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $feedback['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Message:</strong>
                            <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
                        </div>
                        
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo date('M d, Y h:i A', strtotime($feedback['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                        <p class="text-muted">No feedback submitted yet.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
