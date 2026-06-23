<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = getConnection();

// Get user's previous feedback
$previous_feedback = $conn->query("SELECT * FROM feedback WHERE user_id = $user_id ORDER BY created_at DESC");

$conn->close();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="mb-4">Submit Feedback</h2>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Share Your Experience</h5>
                    <form method="POST">
                        <input type="hidden" name="action" value="submit_feedback">
                        
                        <div class="mb-3">
                            <label class="form-label">Rating *</label>
                            <div class="rating-stars">
                                <input type="hidden" name="rating" id="ratingValue" value="5">
                                <i class="fas fa-star star-icon selected" data-rating="1" style="cursor: pointer; font-size: 2rem;"></i>
                                <i class="fas fa-star star-icon selected" data-rating="2" style="cursor: pointer; font-size: 2rem;"></i>
                                <i class="fas fa-star star-icon selected" data-rating="3" style="cursor: pointer; font-size: 2rem;"></i>
                                <i class="fas fa-star star-icon selected" data-rating="4" style="cursor: pointer; font-size: 2rem;"></i>
                                <i class="fas fa-star star-icon selected" data-rating="5" style="cursor: pointer; font-size: 2rem;"></i>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Your Message *</label>
                            <textarea class="form-control" name="message" rows="5" required 
                                      placeholder="Tell us about your experience..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-aku">
                            <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                    </form>
                </div>
            </div>

            <!-- Previous Feedback -->
            <?php if ($previous_feedback->num_rows > 0): ?>
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Your Previous Feedback</h5>
                </div>
                <div class="card-body">
                    <?php while($feedback = $previous_feedback->fetch_assoc()): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="mt-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $feedback['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">
                                <?php echo date('M d, Y', strtotime($feedback['created_at'])); ?>
                            </small>
                        </div>
                        <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
