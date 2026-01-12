<?php
$page_title = 'Login - Vehicle Rental System';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <div class="form-container">
        <h2 class="text-center mb-4">Login</h2>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=auth&action=login">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="/public/index.php?page=auth&action=register">Register here</a></p>
        </div>

        <div class="mt-4 p-3 bg-light rounded">
            <h6>Demo Accounts:</h6>
            <small>
                <strong>Admin:</strong> admin@vehiclerental.com / admin123<br>
                <strong>Owner:</strong> owner1@test.com / admin123<br>
                <strong>Renter:</strong> renter1@test.com / admin123
            </small>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>