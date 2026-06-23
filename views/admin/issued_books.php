<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'librarian')) {
    header('Location: index.php');
    exit();
}

$conn = getConnection();

// Get all issued books with book and user details
$issued_books = $conn->query("SELECT ib.*, b.title, b.author, b.isbn, u.full_name, u.username
    FROM issued_books ib 
    JOIN books b ON ib.book_id = b.id 
    JOIN users u ON ib.user_id = u.id 
    WHERE ib.return_date IS NULL 
    ORDER BY ib.issue_date DESC");

$conn->close();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Issued Books Management</h2>
            <p class="text-muted">Manage all book issues and returns</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Student</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($issue = $issued_books->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $issue['id']; ?></td>
                            <td><?php echo $issue['title']; ?></td>
                            <td><?php echo $issue['author']; ?></td>
                            <td><?php echo $issue['isbn']; ?></td>
                            <td>
                                <?php echo $issue['full_name']; ?><br>
                                <small class="text-muted"><?php echo $issue['username']; ?></small>
                            </td>
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
        </div>
    </div>
</div>
