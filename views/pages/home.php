<?php $page_title = "Home"; ?>

<!-- Hero Section -->
<section class="hero-section" aria-label="Welcome to AKU Digital Library">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6 slide-in-left">
                <h1 class="display-3 fw-bold mb-4">
                    Welcome to <span class="text-gradient">AKU Digital Library</span>
                </h1>
                <p class="lead mb-4 fs-5">
                    Discover a world of knowledge with thousands of books, journals, and digital resources at your fingertips. 
                    Experience the future of library management with our modern, intuitive platform.
                </p>
                <div class="hero-actions">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=register" class="btn btn-aku btn-lg me-3 mb-2">
                            <i class="fas fa-rocket me-2"></i>Get Started
                        </a>
                        <a href="index.php?page=login" class="btn btn-outline-light btn-lg mb-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=<?php echo $_SESSION['role']; ?>_dashboard" class="btn btn-aku btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-illustration">
                    <div class="floating-books">
                        <div class="book book-1"><i class="fas fa-book"></i></div>
                        <div class="book book-2"><i class="fas fa-book-open"></i></div>
                        <div class="book book-3"><i class="fas fa-graduation-cap"></i></div>
                    </div>
                    <div class="hero-icon">
                        <i class="fas fa-book-reader fa-10x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        <div class="scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white" id="features">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold mb-3">Why Choose Our Library?</h2>
                <p class="lead text-muted">Experience modern library management at its finest with cutting-edge features</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-book-open fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Vast Collection</h5>
                    <p class="card-text text-muted">
                        Access thousands of books across multiple categories and subjects. From academic textbooks to recreational reading.
                    </p>
                    <div class="feature-stats">
                        <small class="text-primary fw-semibold"><?php echo number_format($total_books ?? 1000); ?>+ Books Available</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-clock fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title fw-bold">24/7 Access</h5>
                    <p class="card-text text-muted">
                        Browse and reserve books anytime, anywhere with our digital platform. Never limited by library hours.
                    </p>
                    <div class="feature-stats">
                        <small class="text-success fw-semibold">Always Available</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-user-graduate fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title fw-bold">Student Friendly</h5>
                    <p class="card-text text-muted">
                        Easy borrowing system designed specifically for student needs with extended loan periods and renewals.
                    </p>
                    <div class="feature-stats">
                        <small class="text-info fw-semibold"><?php echo number_format($total_users ?? 500); ?>+ Active Students</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-search fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title fw-bold">Smart Search</h5>
                    <p class="card-text text-muted">
                        Advanced search functionality to find exactly what you need quickly and efficiently.
                    </p>
                    <div class="feature-stats">
                        <small class="text-warning fw-semibold">Instant Results</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title fw-bold">Mobile Responsive</h5>
                    <p class="card-text text-muted">
                        Fully responsive design that works perfectly on all devices - desktop, tablet, and mobile.
                    </p>
                    <div class="feature-stats">
                        <small class="text-danger fw-semibold">All Devices Supported</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-secondary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Secure & Reliable</h5>
                    <p class="card-text text-muted">
                        Your data is protected with modern security measures and reliable backup systems.
                    </p>
                    <div class="feature-stats">
                        <small class="text-secondary fw-semibold">Bank-Level Security</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light" id="how-it-works">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold mb-3">How It Works</h2>
                <p class="lead text-muted">Get started with our library in just a few simple steps</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-user-plus fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Register</h5>
                    <p class="text-muted">Create your account with basic information</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-search fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Browse</h5>
                    <p class="text-muted">Search and explore our vast collection</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-bookmark fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Reserve</h5>
                    <p class="text-muted">Request books you want to borrow</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-book-reader fa-2x text-info"></i>
                    </div>
                    <h5 class="fw-bold">Enjoy</h5>
                    <p class="text-muted">Pick up and enjoy your selected books</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-white" id="testimonials">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold mb-3">What Students Say</h2>
                <p class="lead text-muted">Hear from our satisfied library users</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"The digital library system is amazing! I can easily find and reserve books from anywhere on campus."</p>
                        <div class="testimonial-author">
                            <strong>helen desta</strong>
                            <small class="text-muted d-block">Computer Science Student</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"User-friendly interface and quick book search. The mobile app works perfectly on my phone!"</p>
                        <div class="testimonial-author">
                            <strong>tekeste belay</strong>
                            <small class="text-muted d-block">Business Administration</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"Great collection of books and very helpful librarian staff. The online system saves so much time!"</p>
                        <div class="testimonial-author">
                            <strong>hogos g/bremariam</strong>
                            <small class="text-muted d-block">Medical Student</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<?php if (!isset($_SESSION['user_id'])): ?>
<section class="py-5 bg-gradient-secondary text-white" id="cta">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-3">Ready to Start Your Learning Journey?</h2>
                <p class="lead mb-4 opacity-90">
                    Join thousands of students who are already using our digital library to enhance their academic experience
                </p>
                <div class="cta-actions">
                    <a href="index.php?page=register" class="btn btn-light btn-lg me-3 mb-2">
                        <i class="fas fa-rocket me-2"></i>Register Now
                    </a>
                    <a href="index.php?page=about" class="btn btn-outline-light btn-lg mb-2">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
                <div class="cta-features mt-4">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-check-circle me-2"></i>
                            <small>Free Registration</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-check-circle me-2"></i>
                            <small>Instant Access</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-check-circle me-2"></i>
                            <small>24/7 Support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Counter Animation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString();
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
