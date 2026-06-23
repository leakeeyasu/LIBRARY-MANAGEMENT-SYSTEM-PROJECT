    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-book-open me-2"></i>
                            AKU Digital Library
                        </h5>
                        <p class="mb-3">
                            Empowering students with access to knowledge through modern library management technology. 
                            Your gateway to academic excellence.
                        </p>
                        <div class="social-links">
                            <a href="https://facebook.com/AksumUniversity" target="_blank" class="social-link me-3">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/AksumUniversity" target="_blank" class="social-link me-3">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link me-3">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://linkedin.com/school/aksum-university" target="_blank" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h6 class="fw-bold mb-3">Quick Links</h6>
                        <ul class="footer-links">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php?page=about">About Us</a></li>
                            <li><a href="index.php?page=contact">Contact</a></li>
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <li><a href="index.php?page=login">Login</a></li>
                                <li><a href="index.php?page=register">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="footer-section">
                        <h6 class="fw-bold mb-3">Services</h6>
                        <ul class="footer-links">
                            <li><a href="#">Book Borrowing</a></li>
                            <li><a href="#">Digital Resources</a></li>
                            <li><a href="#">Research Support</a></li>
                            <li><a href="#">Study Spaces</a></li>
                            <li><a href="#">Academic Help</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="footer-section">
                        <h6 class="fw-bold mb-3">Contact Info</h6>
                        <div class="contact-info">
                            <div class="contact-item mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span>AKU Campus, Library Building</span>
                            </div>
                            <div class="contact-item mb-2">
                                <i class="fas fa-phone me-2"></i>
                                <a href="tel:0934234556">0934234556</a>
                            </div>
                            <div class="contact-item mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <a href="mailto:library@aku.edu">library@aku.edu</a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-clock me-2"></i>
                                <span>24/7 Digital Access</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 opacity-25">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 opacity-75">
                        &copy; <?php echo date('Y'); ?> AKU Digital Library. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links-inline">
                        <a href="#" class="me-3">Privacy Policy</a>
                        <a href="#" class="me-3">Terms of Service</a>
                        <a href="#">Help</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
    .footer {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: white;
        padding: 3rem 0 1.5rem;
        margin-top: 4rem;
    }

    .footer h5, .footer h6 {
        color: white;
        font-weight: 600;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        font-size: 0.9rem;
    }

    .footer-links a:hover {
        color: #f59e0b;
        transform: translateX(5px);
    }

    .footer-links-inline a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease-in-out;
    }

    .footer-links-inline a:hover {
        color: #f59e0b;
    }

    .social-links {
        margin-top: 1rem;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
    }

    .social-link:hover {
        background: #f59e0b;
        color: white;
        transform: translateY(-3px);
    }

    .contact-info {
        font-size: 0.9rem;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        color: rgba(255, 255, 255, 0.9);
    }

    .contact-item i {
        margin-top: 0.1rem;
        color: #f59e0b;
    }

    .contact-item a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s ease-in-out;
    }

    .contact-item a:hover {
        color: #f59e0b;
    }
    </style>
