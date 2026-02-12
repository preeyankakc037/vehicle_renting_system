<?php
/**
 * User Profile Page
 * Displays user details and allows for profile updates.
 */
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    require APP_PATH . '/views/layouts/admin_navbar.php';
} else {
    require APP_PATH . '/views/layouts/navbar.php';
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center">My Profile</h3>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/index.php?page=auth&action=updateProfile" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control"
                                value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control"
                                value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">Update Profile</button>
                            <a href="javascript:history.back()" class="btn btn-light text-muted">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    require APP_PATH . '/views/layouts/admin_footer.php';
} else {
    require APP_PATH . '/views/layouts/footer.php';
}
?>