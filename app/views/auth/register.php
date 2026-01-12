<?php
$page_title = 'Register - Vehicle Rental System';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <div class="form-container">
        <h2 class="text-center mb-4">Register</h2>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=auth&action=register">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="user_role" class="form-label">I want to</label>
                <select class="form-select" id="user_role" name="user_role" required>
                    <option value="">Select an option</option>
                    <option value="renter">Rent Vehicles</option>
                    <option value="owner">List My Vehicles for Rent</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>
        
        <div class="text-center mt-3">
            <p>Already have an account? <a href="/public/index.php?page=auth&action=login">Login here</a></p>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>