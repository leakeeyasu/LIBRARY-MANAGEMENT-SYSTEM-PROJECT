    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/app.js"></script>
    
    <!-- Page-specific JavaScript -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert:not(.alert-permanent)').each(function() {
                    $(this).fadeOut('slow', function() {
                        $(this).remove();
                    });
                });
            }, 5000);
            
            // Form validation
            $('.needs-validation').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                $(this).addClass('was-validated');
                
                // Add loading state to submit button
                var submitBtn = $(this).find('button[type="submit"]');
                if (submitBtn.length) {
                    var originalText = submitBtn.html();
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
                    
                    // Reset after 3 seconds (fallback)
                    setTimeout(function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalText);
                    }, 3000);
                }
            });
            
            // Edit book modal
            $('.edit-book-btn').click(function() {
                var bookData = {
                    id: $(this).data('book-id'),
                    title: $(this).data('book-title'),
                    author: $(this).data('book-author'),
                    isbn: $(this).data('book-isbn'),
                    category: $(this).data('book-category'),
                    publisher: $(this).data('book-publisher'),
                    year: $(this).data('book-year'),
                    quantity: $(this).data('book-quantity'),
                    description: $(this).data('book-description')
                };
                
                $('#edit_book_id').val(bookData.id);
                $('#edit_title').val(bookData.title);
                $('#edit_author').val(bookData.author);
                $('#edit_isbn').val(bookData.isbn);
                $('#edit_category').val(bookData.category);
                $('#edit_publisher').val(bookData.publisher);
                $('#edit_publication_year').val(bookData.year);
                $('#edit_quantity').val(bookData.quantity);
                $('#edit_description').val(bookData.description);
                
                $('#editBookModal').modal('show');
            });
            
            // Issue book modal
            $('.issue-btn, .issue-book-btn').click(function() {
                var bookId = $(this).data('book-id');
                var bookTitle = $(this).data('book-title') || 'Selected Book';
                
                $('#issue_book_id').val(bookId);
                $('#issueBookModal .modal-title').text('Issue: ' + bookTitle);
                $('#issueBookModal').modal('show');
            });
            
            // Edit user modal
            $('.edit-user-btn').click(function() {
                var userData = {
                    id: $(this).data('user-id'),
                    name: $(this).data('user-name'),
                    email: $(this).data('user-email'),
                    phone: $(this).data('user-phone'),
                    address: $(this).data('user-address'),
                    active: $(this).data('user-active')
                };
                
                $('#edit_user_id').val(userData.id);
                $('#edit_full_name').val(userData.name);
                $('#edit_email').val(userData.email);
                $('#edit_phone').val(userData.phone);
                $('#edit_address').val(userData.address);
                $('#edit_is_active').prop('checked', userData.active == 1);
                
                $('#editUserModal').modal('show');
            });
            
            // Update role modal
            $('.role-btn').click(function() {
                var userId = $(this).data('user-id');
                var userRole = $(this).data('user-role');
                var userName = $(this).data('user-name') || 'User';
                
                $('#role_user_id').val(userId);
                $('#role_select').val(userRole);
                $('#updateRoleModal .modal-title').text('Update Role: ' + userName);
                $('#updateRoleModal').modal('show');
            });
            
            // Delete confirmations
            $('.delete-book-btn').click(function(e) {
                e.preventDefault();
                var bookTitle = $(this).closest('tr').find('td:first').text() || 'this book';
                
                if (confirm('Are you sure you want to delete "' + bookTitle + '"? This action cannot be undone.')) {
                    $(this).closest('form').submit();
                }
            });
            
            $('.delete-user-btn').click(function(e) {
                e.preventDefault();
                var userName = $(this).closest('tr').find('td:nth-child(2)').text() || 'this user';
                
                if (confirm('Are you sure you want to delete "' + userName + '"? This action cannot be undone.')) {
                    $(this).closest('form').submit();
                }
            });
            
            $('.return-btn').click(function(e) {
                e.preventDefault();
                var bookTitle = $(this).data('book-title') || 'this book';
                var returnUrl = $(this).attr('href');
                
                if (confirm('Mark "' + bookTitle + '" as returned?')) {
                    // Show loading state
                    var btn = $(this);
                    var originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
                    
                    // Navigate to the return URL
                    window.location.href = returnUrl;
                }
            });
            
            // Rating system
            $('.star-icon').click(function() {
                var rating = $(this).data('rating');
                var bookId = $(this).closest('.rating-stars').data('book-id');
                
                $('#ratingValue').val(rating);
                
                // Update star display
                $(this).parent().find('.star-icon').each(function(index) {
                    var star = $(this);
                    setTimeout(function() {
                        if (star.data('rating') <= rating) {
                            star.addClass('selected').css('color', '#ffc107');
                        } else {
                            star.removeClass('selected').css('color', '#ddd');
                        }
                    }, index * 50);
                });
                
                // Submit rating if book ID is available
                if (bookId) {
                    submitRating(bookId, rating);
                }
            });
            
            // Initialize existing rating stars
            $('.star-icon').each(function() {
                if ($(this).hasClass('selected')) {
                    $(this).css('color', '#ffc107');
                }
            });
            
            // Search functionality
            var searchTimeout;
            $('.search-input').on('input', function() {
                var query = $(this).val();
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(function() {
                    if (query.length >= 2) {
                        console.log('Searching for:', query);
                        // Add your search logic here
                    }
                }, 300);
            });
            
            // Modal reset functionality
            $('.modal').on('hidden.bs.modal', function() {
                var form = $(this).find('form');
                if (form.length) {
                    form[0].reset();
                    form.removeClass('was-validated');
                    form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                    form.find('.invalid-feedback, .valid-feedback').remove();
                }
            });
            
            // Image preview functionality
            $('input[type="file"]').change(function() {
                previewImage(this);
            });
            
            // Smooth scrolling for anchor links
            $('a[href^="#"]').click(function(e) {
                e.preventDefault();
                var target = $($(this).attr('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 500);
                }
            });
        });
        
        function submitRating(bookId, rating) {
            $.ajax({
                url: 'index.php?action=rate_book',
                method: 'POST',
                data: {
                    book_id: bookId,
                    rating: rating
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('Rating submitted successfully!', 'success');
                    } else {
                        showAlert(response.message || 'Failed to submit rating.', 'danger');
                    }
                },
                error: function() {
                    showAlert('An error occurred while submitting rating.', 'danger');
                }
            });
        }
        
        function showAlert(message, type) {
            type = type || 'info';
            var alertContainer = $('.alert-container');
            if (alertContainer.length === 0) {
                $('body').append('<div class="alert-container"></div>');
                alertContainer = $('.alert-container');
            }
            
            var iconClass = type === 'success' ? 'check-circle' : 
                           type === 'danger' ? 'exclamation-circle' : 
                           'info-circle';
            
            var alert = $('<div class="alert alert-' + type + ' alert-dismissible fade show">' +
                         '<i class="fas fa-' + iconClass + ' me-2"></i>' + message +
                         '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                         '</div>');
            
            alertContainer.append(alert);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                alert.fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        // Scroll effects
        $(window).scroll(function() {
            var scrollTop = $(this).scrollTop();
            
            // Show/hide scroll to top button
            if (scrollTop > 300) {
                $('.scroll-to-top').addClass('show');
            } else {
                $('.scroll-to-top').removeClass('show');
            }
            
            // Navbar scroll effect
            if (scrollTop > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
        });
        
        // Performance monitoring
        $(window).on('load', function() {
            var loadTime = performance.now();
            console.log('Page loaded in ' + Math.round(loadTime) + 'ms');
            
            // Check for slow loading images
            $('img').each(function() {
                if (!this.complete) {
                    $(this).on('load', function() {
                        console.log('Image loaded: ' + this.src);
                    }).on('error', function() {
                        console.warn('Failed to load image: ' + this.src);
                        this.src = 'uploads/default.png';
                    });
                }
            });
        });
    </script>

</body>
</html>
