/**
 * AKU Digital Library - Simple JavaScript
 * Using only standard JavaScript, HTML, CSS, PHP, MySQL
 */

// Simple Library Management System
function AKULibrary() {
    this.init();
}

AKULibrary.prototype.init = function() {
    var self = this;
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            self.setupEventListeners();
            self.initializeComponents();
        });
    } else {
        self.setupEventListeners();
        self.initializeComponents();
    }
};

AKULibrary.prototype.setupEventListeners = function() {
    var self = this;
    
    // Window scroll events
    window.addEventListener('scroll', function() {
        self.handleScroll();
    });
    
    // Window resize events
    window.addEventListener('resize', function() {
        self.handleResize();
    });
    
    // Form submissions
    var forms = document.querySelectorAll('form');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function(e) {
            self.handleFormSubmit(e, this);
        });
    }
};

AKULibrary.prototype.initializeComponents = function() {
    this.createScrollToTopButton();
    this.initImagePreview();
    this.initRatingSystem();
    this.initModals();
    this.initAlerts();
};

AKULibrary.prototype.handleScroll = function() {
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Show/hide scroll to top button
    var scrollBtn = document.querySelector('.scroll-to-top');
    if (scrollBtn) {
        if (scrollTop > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    }
    
    // Navbar scroll effect
    var navbar = document.querySelector('.navbar');
    if (navbar) {
        if (scrollTop > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
};

AKULibrary.prototype.handleResize = function() {
    // Handle responsive adjustments
    if (window.innerWidth < 768) {
        this.adjustMobileLayout();
    }
};

AKULibrary.prototype.adjustMobileLayout = function() {
    var cards = document.querySelectorAll('.card');
    for (var i = 0; i < cards.length; i++) {
        cards[i].style.marginBottom = '1rem';
    }
};

AKULibrary.prototype.handleFormSubmit = function(event, form) {
    if (!this.validateForm(form)) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    form.classList.add('was-validated');
    
    // Add loading state to submit button
    var submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        var originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Reset after 3 seconds (fallback)
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 3000);
    }
};

AKULibrary.prototype.validateForm = function(form) {
    var isValid = true;
    var inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    for (var i = 0; i < inputs.length; i++) {
        if (!this.validateField(inputs[i])) {
            isValid = false;
        }
    }
    
    return isValid;
};

AKULibrary.prototype.validateField = function(field) {
    var value = field.value.trim();
    var type = field.type;
    var isValid = true;
    var message = '';
    
    // Remove existing feedback
    this.removeFieldFeedback(field);
    
    // Required validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = 'This field is required.';
    }
    
    // Email validation
    if (type === 'email' && value) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            message = 'Please enter a valid email address.';
        }
    }
    
    // Password validation
    if (type === 'password' && value) {
        if (value.length < 6) {
            isValid = false;
            message = 'Password must be at least 6 characters long.';
        }
    }
    
    // Phone validation
    if (field.name === 'phone' && value) {
        var phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(value.replace(/\s/g, ''))) {
            isValid = false;
            message = 'Please enter a valid phone number.';
        }
    }
    
    // Show feedback
    this.showFieldFeedback(field, isValid, message);
    
    return isValid;
};

AKULibrary.prototype.showFieldFeedback = function(field, isValid, message) {
    field.classList.remove('is-valid', 'is-invalid');
    field.classList.add(isValid ? 'is-valid' : 'is-invalid');
    
    if (!isValid && message) {
        var feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        field.parentNode.appendChild(feedback);
    }
};

AKULibrary.prototype.removeFieldFeedback = function(field) {
    field.classList.remove('is-valid', 'is-invalid');
    var feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.remove();
    }
};

AKULibrary.prototype.createScrollToTopButton = function() {
    var scrollBtn = document.createElement('button');
    scrollBtn.className = 'scroll-to-top';
    scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollBtn.setAttribute('aria-label', 'Scroll to top');
    document.body.appendChild(scrollBtn);
    
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
};

AKULibrary.prototype.initImagePreview = function() {
    var fileInputs = document.querySelectorAll('input[type="file"]');
    for (var i = 0; i < fileInputs.length; i++) {
        fileInputs[i].addEventListener('change', function(e) {
            previewImage(this);
        });
    }
};

AKULibrary.prototype.initRatingSystem = function() {
    var ratingContainers = document.querySelectorAll('.rating-container');
    for (var i = 0; i < ratingContainers.length; i++) {
        this.setupRatingContainer(ratingContainers[i]);
    }
};

AKULibrary.prototype.setupRatingContainer = function(container) {
    var self = this;
    var stars = container.querySelectorAll('.star-icon');
    var currentRating = 0;
    
    for (var i = 0; i < stars.length; i++) {
        (function(index) {
            stars[index].addEventListener('click', function() {
                currentRating = index + 1;
                self.updateStars(stars, currentRating);
                self.submitRating(container.dataset.bookId, currentRating);
            });
            
            stars[index].addEventListener('mouseover', function() {
                self.updateStars(stars, index + 1);
            });
        })(i);
    }
    
    container.addEventListener('mouseleave', function() {
        self.updateStars(stars, currentRating);
    });
};

AKULibrary.prototype.updateStars = function(stars, rating) {
    for (var i = 0; i < stars.length; i++) {
        if (i < rating) {
            stars[i].classList.add('selected');
        } else {
            stars[i].classList.remove('selected');
        }
    }
};

AKULibrary.prototype.submitRating = function(bookId, rating) {
    var self = this;
    
    // Simple AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?action=rate_book', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        self.showAlert('Rating submitted successfully!', 'success');
                    } else {
                        self.showAlert('Failed to submit rating.', 'danger');
                    }
                } catch (e) {
                    self.showAlert('Failed to submit rating.', 'danger');
                }
            } else {
                self.showAlert('Failed to submit rating.', 'danger');
            }
        }
    };
    
    xhr.send('book_id=' + encodeURIComponent(bookId) + '&rating=' + encodeURIComponent(rating));
};

AKULibrary.prototype.initModals = function() {
    var modals = document.querySelectorAll('.modal');
    for (var i = 0; i < modals.length; i++) {
        this.setupModal(modals[i]);
    }
};

AKULibrary.prototype.setupModal = function(modal) {
    var self = this;
    
    modal.addEventListener('show.bs.modal', function() {
        // Focus first input when modal opens
        setTimeout(function() {
            var firstInput = modal.querySelector('input, select, textarea');
            if (firstInput) {
                firstInput.focus();
            }
        }, 100);
    });
    
    modal.addEventListener('hidden.bs.modal', function() {
        // Reset form when modal closes
        var form = modal.querySelector('form');
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
            self.clearFormFeedback(form);
        }
    });
};

AKULibrary.prototype.clearFormFeedback = function(form) {
    var feedbacks = form.querySelectorAll('.invalid-feedback, .valid-feedback');
    for (var i = 0; i < feedbacks.length; i++) {
        feedbacks[i].remove();
    }
    
    var inputs = form.querySelectorAll('.is-valid, .is-invalid');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('is-valid', 'is-invalid');
    }
};

AKULibrary.prototype.initAlerts = function() {
    var self = this;
    var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    for (var i = 0; i < alerts.length; i++) {
        (function(alert) {
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }
            }, 5000);
        })(alerts[i]);
    }
};

AKULibrary.prototype.showAlert = function(message, type) {
    type = type || 'info';
    var alertContainer = document.querySelector('.alert-container') || this.createAlertContainer();
    
    var alert = document.createElement('div');
    alert.className = 'alert alert-' + type + ' alert-dismissible fade show';
    
    var iconClass = type === 'success' ? 'check-circle' : 
                   type === 'danger' ? 'exclamation-circle' : 
                   'info-circle';
    
    alert.innerHTML = '<i class="fas fa-' + iconClass + ' me-2"></i>' + 
                     message + 
                     '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    
    alertContainer.appendChild(alert);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
};

AKULibrary.prototype.createAlertContainer = function() {
    var container = document.createElement('div');
    container.className = 'alert-container';
    document.body.appendChild(container);
    return container;
};

// Utility functions (global scope for backward compatibility)
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        // Validate file type
        var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        var isValidType = false;
        for (var i = 0; i < allowedTypes.length; i++) {
            if (file.type === allowedTypes[i]) {
                isValidType = true;
                break;
            }
        }
        
        if (!isValidType) {
            alert('Please select a valid image file (JPEG, PNG, or GIF).');
            input.value = '';
            return;
        }
        
        // Validate file size (5MB max)
        var maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File size must be less than 5MB.');
            input.value = '';
            return;
        }
        
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById(previewId) || 
                         input.parentNode.querySelector('.image-preview img') ||
                         createImagePreview(input);
            
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
}

function createImagePreview(input) {
    var container = document.createElement('div');
    container.className = 'image-preview mt-2';
    
    var img = document.createElement('img');
    img.style.maxWidth = '200px';
    img.style.maxHeight = '200px';
    img.style.borderRadius = '8px';
    img.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
    
    container.appendChild(img);
    input.parentNode.appendChild(container);
    
    return img;
}

function showIssueModal(bookId, bookTitle) {
    bookTitle = bookTitle || '';
    var bookIdInput = document.getElementById('issue_book_id');
    if (bookIdInput) {
        bookIdInput.value = bookId;
    }
    
    if (bookTitle) {
        var modalTitle = document.querySelector('#issueBookModal .modal-title');
        if (modalTitle) {
            modalTitle.textContent = 'Issue: ' + bookTitle;
        }
    }
    
    // Show modal using Bootstrap
    if (typeof bootstrap !== 'undefined') {
        var modal = new bootstrap.Modal(document.getElementById('issueBookModal'));
        modal.show();
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Counter animation for statistics
function animateCounter(element) {
    var target = parseInt(element.getAttribute('data-target'));
    var increment = target / 100;
    var current = 0;
    
    var updateCounter = function() {
        if (current < target) {
            current += increment;
            element.textContent = Math.floor(current).toLocaleString();
            setTimeout(updateCounter, 20);
        } else {
            element.textContent = target.toLocaleString();
        }
    };
    
    updateCounter();
}

// Initialize counters when they come into view
function initCounters() {
    var counters = document.querySelectorAll('.counter');
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    for (var i = 0; i < counters.length; i++) {
        observer.observe(counters[i]);
    }
}

// Initialize the application
var akuLibrary = new AKULibrary();

// Initialize counters when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCounters);
} else {
    initCounters();
}

// Export for use in other scripts
window.AKULibrary = AKULibrary;