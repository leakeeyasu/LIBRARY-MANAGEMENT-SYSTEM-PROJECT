<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$conn = getConnection();
$books = $conn->query("SELECT b.*, 
    (SELECT COUNT(*) FROM issued_books ib WHERE ib.book_id = b.id) as issue_count
    FROM books b 
    ORDER BY b.created_at DESC");
$conn->close();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Manage Books</h2>
            <p class="text-muted">Add, edit, or remove books from the library</p>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-aku" data-bs-toggle="modal" data-bs-target="#addBookModal">
                <i class="fas fa-plus me-2"></i>Add New Book
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
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Category</th>
                            <th>Total Qty</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = $books->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $book['id']; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php echo $book['isbn']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $book['category']; ?></span></td>
                            <td><?php echo $book['quantity']; ?></td>
                            <td>
                                <?php if ($book['available'] > 0): ?>
                                    <span class="badge bg-success"><?php echo $book['available']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-book-btn"
                                    data-book-id="<?php echo $book['id']; ?>"
                                    data-book-title="<?php echo htmlspecialchars($book['title']); ?>"
                                    data-book-author="<?php echo htmlspecialchars($book['author']); ?>"
                                    data-book-isbn="<?php echo $book['isbn']; ?>"
                                    data-book-category="<?php echo $book['category']; ?>"
                                    data-book-publisher="<?php echo htmlspecialchars($book['publisher']); ?>"
                                    data-book-year="<?php echo $book['publication_year']; ?>"
                                    data-book-quantity="<?php echo $book['quantity']; ?>"
                                    data-book-description="<?php echo htmlspecialchars($book['description']); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <?php if ($book['available'] > 0): ?>
                                <button class="btn btn-sm btn-success issue-btn" 
                                    data-book-id="<?php echo $book['id']; ?>">
                                    <i class="fas fa-hand-holding"></i>
                                </button>
                                <?php endif; ?>
                                
                                <?php if ($book['issue_count'] > 0): ?>
                                <!-- Book has issue history - disable delete -->
                                <button class="btn btn-sm btn-secondary" disabled title="Cannot delete: Book has issue history">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php else: ?>
                                <!-- Book has no issue history - allow delete -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_book">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger delete-book-btn">
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
