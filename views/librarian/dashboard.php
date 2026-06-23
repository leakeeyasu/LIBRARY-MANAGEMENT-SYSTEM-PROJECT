<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header('Location: index.php');
    exit();
}

$conn = getConnection();

// Get statistics
$total_books = $conn->query("SELECT SUM(quantity) as total FROM books")->fetch_assoc()['total'] ?? 0;
$available_books_count = $conn->query("SELECT SUM(available) as total FROM books")->fetch_assoc()['total'] ?? 0;
$total_issued = $conn->query("SELECT COUNT(*) as total FROM issued_books WHERE return_date IS NULL")->fetch_assoc()['total'] ?? 0;

// Get recent issued books
$recent_issues = $conn->query("SELECT ib.*, b.title, b.author, u.full_name, u.username 
    FROM issued_books ib 
    JOIN books b ON ib.book_id = b.id 
    JOIN users u ON ib.user_id = u.id 
    WHERE ib.return_date IS NULL 
    ORDER BY ib.issue_date DESC LIMIT 10");

// Get available books for issuing
$available_books = $conn->query("SELECT * FROM books WHERE available > 0 ORDER BY title ASC");

$conn->close();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Librarian Dashboard</h2>
            <p class="text-muted">Welcome back, <?php echo $_SESSION['full_name']; ?>!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo number_format($total_books); ?></h3>
                            <p class="mb-0">Total Books</p>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card bg-success text-white">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo number_format($available_books_count); ?></h3>
                            <p class="mb-0">Available Books</p>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card bg-warning text-white">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo number_format($total_issued); ?></h3>
                            <p class="mb-0">Currently Issued</p>
                        </div>
                        <i class="fas fa-hand-holding fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="index.php?page=issued_books" class="stat-card-footer">
                    Manage Issues <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Quick Actions</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-aku" data-bs-toggle="modal" data-bs-target="#issueBookModal">
                            <i class="fas fa-book me-2"></i>Issue Book
                        </button>
                        <a href="index.php?page=issued_books" class="btn btn-outline-primary">
                            <i class="fas fa-book-reader me-2"></i>Manage Issued Books
                        </a>
                        <a href="index.php?page=feedback" class="btn btn-outline-secondary">
                            <i class="fas fa-comment me-2"></i>Submit Feedback
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Books for Issuing -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Available Books</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table data-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>ISBN</th>
                                    <th>Category</th>
                                    <th>Available</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($book = $available_books->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $book['title']; ?></td>
                                    <td><?php echo $book['author']; ?></td>
                                    <td><?php echo $book['isbn']; ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $book['category']; ?></span></td>
                                    <td><span class="badge bg-success"><?php echo $book['available']; ?> / <?php echo $book['quantity']; ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-aku issue-book-btn" 
                                                data-book-id="<?php echo $book['id']; ?>" 
                                                data-book-title="<?php echo htmlspecialchars($book['title']); ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#issueBookModal">
                                            <i class="fas fa-hand-holding me-1"></i>Issue
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Issued Books -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Currently Issued Books</h5>
                </div>
                <div class="card-body">
                    <?php if ($recent_issues->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table data-table">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Student</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($issue = $recent_issues->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $issue['title']; ?></td>
                                        <td><?php echo $issue['author']; ?></td>
                                        <td><?php echo $issue['full_name']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($issue['issue_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($issue['due_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td>
                                            <a href="index.php?action=return_book&issue_id=<?php echo $issue['id']; ?>" 
                                               class="btn btn-sm btn-success return-btn" 
                                               data-book-title="<?php echo htmlspecialchars($issue['title']); ?>">
                                                <i class="fas fa-check me-1"></i>Return
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No currently issued books</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for issuing a book -->
<div class="modal fade" id="issueBookModal" tabindex="-1" aria-labelledby="issueBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueBookModalLabel">Issue Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="issue_book">
                    <input type="hidden" id="issue_book_id" name="book_id">
                    <div class="mb-3">
                        <label class="form-label">Select Student *</label>
                        <select class="form-select" name="student_id" required>
                            <option value="">Select a student</option>
                            <?php
                            $conn = getConnection();
                            $sql = "SELECT id, username, full_name FROM users WHERE role='student' AND is_active=1 ORDER BY full_name";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['full_name']} ({$row['username']})</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date *</label>
                        <input type="date" class="form-control" name="due_date" required 
                               min="<?php echo date('Y-m-d'); ?>"
                               value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>">
                        <div class="form-text">Books are issued for 14 days by default</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-aku">Issue Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.issue-book-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            const bookTitle = this.getAttribute('data-book-title');
            document.getElementById('issue_book_id').value = bookId;
            
            // Update modal title
            document.getElementById('issueBookModalLabel').textContent = 'Issue: ' + bookTitle;
        });
    });
</script>
