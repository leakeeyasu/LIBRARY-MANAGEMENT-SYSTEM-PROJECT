<?php
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <?php if (file_exists('uploads/aku_logo.png')): ?>
                            <img src="uploads/aku.jpg" alt="AKU Logo" class="aku-logo-img mb-3">
                        <?php else: ?>
                            <i class="fas fa-book-open fa-3x text-aku mb-3"></i>
                        <?php endif; ?>
                        <h3 class="fw-bold">Welcome Back!</h3>
                        <p class="text-muted">Login to access your account</p>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="username" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        
                        <button type="submit" class="btn btn-aku w-100 mb-3">Login</button>
                        
                        <div class="text-center">
                            <p class="text-muted">Don't have an account? 
                                <a href="index.php?page=register" class="text-aku">Register here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
