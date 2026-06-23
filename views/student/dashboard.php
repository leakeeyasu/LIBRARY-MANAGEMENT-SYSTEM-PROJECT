<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: index.php');
    exit();
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];

// Get student's issued books
$issued_books = $conn->query("SELECT ib.*, b.title, b.author, b.isbn, b.category 
    FROM issued_books ib 
    JOIN books b ON ib.book_id = b.id 
    WHERE ib.user_id = $user_id AND ib.return_date IS NULL 
    ORDER BY ib.issue_date DESC");

// Get student's reading history
$reading_history = $conn->query("SELECT ib.*, b.title, b.author 
    FROM issued_books ib 
    JOIN books b ON ib.book_id = b.id 
    WHERE ib.user_id = $user_id AND ib.return_date IS NOT NULL 
    ORDER BY ib.return_date DESC LIMIT 5");

// Get available books
$available_books = $conn->query("SELECT * FROM books WHERE available > 0 ORDER BY created_at DESC LIMIT 6");

$total_borrowed = $conn->query("SELECT COUNT(*) as total FROM issued_books WHERE user_id = $user_id")->fetch_assoc()['total'];
$currently_borrowed = $conn->query("SELECT COUNT(*) as total FROM issued_books WHERE user_id = $user_id AND return_date IS NULL")->fetch_assoc()['total'];

$conn->close();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Student Dashboard</h2>
            <p class="text-muted">Welcome back, <?php echo $_SESSION['full_name']; ?>!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="stat-card bg-primary text-white">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo $currently_borrowed; ?></h3>
                            <p class="mb-0">Currently Borrowed</p>
                        </div>
                        <i class="fas fa-book-reader fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card bg-success text-white">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo $total_borrowed; ?></h3>
                            <p class="mb-0">Total Books Read</p>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Borrowed Books -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Currently Borrowed Books</h5>
                </div>
                <div class="card-body">
                    <?php if ($issued_books->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($book = $issued_books->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $book['title']; ?></td>
                                        <td><?php echo $book['author']; ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $book['category']; ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($book['issue_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($book['due_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                            <p class="text-muted">You haven't borrowed any books yet.</p>
                            <p class="text-muted">Contact the librarian to borrow books from our collection.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Books -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Available Books</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <?php if ($available_books->num_rows > 0): ?>
                            <?php while($book = $available_books->fetch_assoc()): ?>
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $book['title']; ?></h6>
                                        <p class="text-muted mb-2"><small><?php echo $book['author']; ?></small></p>
                                        <p class="mb-2"><span class="badge bg-secondary"><?php echo $book['category']; ?></span></p>
                                        <p class="text-muted mb-0"><small><i class="fas fa-book me-1"></i><?php echo $book['available']; ?> available</small></p>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-muted text-center">No books available at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading History -->
    <?php if ($reading_history->num_rows > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Reading History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Borrowed Date</th>
                                    <th>Returned Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($history = $reading_history->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $history['title']; ?></td>
                                    <td><?php echo $history['author']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($history['issue_date'])); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($history['return_date'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
