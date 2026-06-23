<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$page_title = "Admin Dashboard";

$conn = getConnection();

// Get comprehensive statistics
$total_books = $conn->query("SELECT SUM(quantity) as total FROM books")->fetch_assoc()['total'] ?? 0;
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
$total_issued = $conn->query("SELECT COUNT(*) as total FROM issued_books WHERE return_date IS NULL")->fetch_assoc()['total'] ?? 0;
$total_categories = $conn->query("SELECT COUNT(DISTINCT category) as total FROM books")->fetch_assoc()['total'] ?? 0;

// Fix: Use PHP's current date to match what we store in return_date
$today_php = date('Y-m-d');
$total_returned_today = $conn->query("SELECT COUNT(*) as total FROM issued_books WHERE return_date = '$today_php'")->fetch_assoc()['total'] ?? 0;

// Get recent activities
$recent_issues = $conn->query("SELECT ib.*, b.title, b.author, u.full_name, u.username 
    FROM issued_books ib 
    JOIN books b ON ib.book_id = b.id 
    JOIN users u ON ib.user_id = u.id 
    WHERE ib.return_date IS NULL 
    ORDER BY ib.issue_date DESC LIMIT 5");

// Get popular books
$popular_books = $conn->query("SELECT b.title, b.author, COUNT(ib.id) as issue_count
    FROM books b
    LEFT JOIN issued_books ib ON b.id = ib.book_id
    GROUP BY b.id
    ORDER BY issue_count DESC
    LIMIT 5");

// Get monthly statistics for chart
$monthly_stats = $conn->query("SELECT 
    MONTH(issue_date) as month,
    YEAR(issue_date) as year,
    COUNT(*) as issues
    FROM issued_books 
    WHERE issue_date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)
    GROUP BY YEAR(issue_date), MONTH(issue_date)
    ORDER BY year, month");

$conn->close();
?>

<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>
                        <p class="lead mb-0">Here's what's happening in your library today</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="date-time">
                            <div class="current-date"><?php echo date('l, F j, Y'); ?></div>
                            <div class="current-time" id="currentTime"><?php echo date('g:i A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card-modern books">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number counter" data-target="<?php echo $total_books; ?>">0</h3>
                    <p class="stat-label">Total Books</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12% this month</span>
                    </div>
                </div>
                <a href="index.php?page=books" class="stat-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card-modern users">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number counter" data-target="<?php echo $total_users; ?>">0</h3>
                    <p class="stat-label">Total Users</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8% this month</span>
                    </div>
                </div>
                <a href="index.php?page=users" class="stat-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card-modern issued">
                <div class="stat-icon">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number counter" data-target="<?php echo $total_issued; ?>">0</h3>
                    <p class="stat-label">Books Issued</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-down"></i>
                        <span>-3% this week</span>
                    </div>
                </div>
                <a href="index.php?page=issued_books" class="stat-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card-modern categories">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number counter" data-target="<?php echo $total_categories; ?>">0</h3>
                    <p class="stat-label">Categories</p>
                    <div class="stat-trend">
                        <i class="fas fa-minus"></i>
                        <span>No change</span>
                    </div>
                </div>
                <a href="index.php?page=books" class="stat-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="stat-card-modern returned">
                <div class="stat-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number counter" data-target="<?php echo $total_returned_today; ?>">0</h3>
                    <p class="stat-label">Returned Today</p>
                    <div class="stat-trend text-success">
                        <i class="fas fa-check-circle"></i>
                        <span>Great!</span>
                    </div>
                </div>
                <a href="index.php?page=issued_books" class="stat-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <button class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#addBookModal">
                                <i class="fas fa-plus"></i>
                                <span>Add Book</span>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <button class="quick-action-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-user-plus"></i>
                                <span>Add User</span>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <a href="index.php?page=issued_books" class="quick-action-btn">
                                <i class="fas fa-book-reader"></i>
                                <span>Issue Book</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <a href="index.php?page=feedback" class="quick-action-btn">
                                <i class="fas fa-comments"></i>
                                <span>Feedback</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Issues -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-clock text-info me-2"></i>Recent Issues
                        </h5>
                        <a href="index.php?page=issued_books" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if ($recent_issues->num_rows > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php while($issue = $recent_issues->fetch_assoc()): ?>
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($issue['title']); ?></h6>
                                        <p class="mb-1 text-muted small">by <?php echo htmlspecialchars($issue['author']); ?></p>
                                        <p class="mb-0 text-muted small">
                                            Issued to <strong><?php echo htmlspecialchars($issue['full_name']); ?></strong>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <?php echo date('M d', strtotime($issue['issue_date'])); ?>
                                        </small>
                                        <br>
                                        <small class="text-success">
                                            Due: <?php echo date('M d', strtotime($issue['due_date'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent issues</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Books -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-fire text-warning me-2"></i>Popular Books
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php if ($popular_books->num_rows > 0): ?>
                            <?php while($book = $popular_books->fetch_assoc()): ?>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                <div class="popular-book-card">
                                    <div class="book-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <h6 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h6>
                                    <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>
                                    <div class="issue-count">
                                        <i class="fas fa-users me-1"></i>
                                        <?php echo $book['issue_count']; ?> issues
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-3">
                                <p class="text-muted">No data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Styles -->
<style>
.welcome-card {
    background: linear-gradient(135deg, var(--aku-primary) 0%, var(--aku-secondary) 100%);
    color: white;
    padding: 2rem;
    border-radius: var(--radius-xl);
    margin-bottom: 2rem;
}

.date-time {
    text-align: right;
}

.current-date {
    font-size: 1.1rem;
    font-weight: 600;
    opacity: 0.9;
}

.current-time {
    font-size: 2rem;
    font-weight: 700;
}

.stat-card-modern {
    background: white;
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stat-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-secondary);
}

.stat-card-modern.books::before { background: var(--gradient-primary); }
.stat-card-modern.users::before { background: var(--gradient-secondary); }
.stat-card-modern.issued::before { background: var(--gradient-accent); }
.stat-card-modern.categories::before { background: linear-gradient(135deg, #10b981, #34d399); }
.stat-card-modern.returned::before { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stat-card-modern.books .stat-icon { background: rgba(30, 41, 59, 0.1); color: var(--aku-primary); }
.stat-card-modern.users .stat-icon { background: rgba(59, 130, 246, 0.1); color: var(--aku-secondary); }
.stat-card-modern.issued .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--aku-accent); }
.stat-card-modern.categories .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.stat-card-modern.returned .stat-icon { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--aku-gray-900);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--aku-gray-600);
    margin-bottom: 0.5rem;
}

.stat-trend {
    font-size: 0.8rem;
    color: var(--aku-success);
}

.stat-trend i {
    margin-right: 0.25rem;
}

.stat-link {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--aku-gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--aku-gray-600);
    text-decoration: none;
    transition: all var(--transition-normal);
}

.stat-link:hover {
    background: var(--aku-secondary);
    color: white;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 80px;
    background: white;
    border: 2px solid var(--aku-gray-200);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--aku-gray-700);
    transition: all var(--transition-normal);
    cursor: pointer;
}

.quick-action-btn:hover {
    border-color: var(--aku-secondary);
    color: var(--aku-secondary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.quick-action-btn i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.quick-action-btn span {
    font-size: 0.9rem;
    font-weight: 500;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.popular-book-card {
    background: white;
    border: 1px solid var(--aku-gray-200);
    border-radius: var(--radius-lg);
    padding: 1rem;
    text-align: center;
    transition: all var(--transition-normal);
    height: 100%;
}

.popular-book-card:hover {
    border-color: var(--aku-secondary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.book-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--aku-gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: var(--aku-secondary);
}

.book-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.book-author {
    font-size: 0.8rem;
    color: var(--aku-gray-600);
    margin-bottom: 0.5rem;
}

.issue-count {
    font-size: 0.8rem;
    color: var(--aku-secondary);
    font-weight: 500;
}

@media (max-width: 768px) {
    .stat-card-modern {
        margin-bottom: 1rem;
    }
    
    .welcome-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    .current-time {
        font-size: 1.5rem;
    }
}
</style>

<!-- Real-time clock update -->
<script>
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
    document.getElementById('currentTime').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);

// Counter animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 50;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    };
    
    // Intersection Observer for counter animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        observer.observe(counter);
    });
});
</script>
