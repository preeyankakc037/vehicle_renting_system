if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
require APP_PATH . '/views/layouts/admin_navbar.php';
} else {
require APP_PATH . '/views/layouts/navbar.php';
}

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center">Change Password</h3>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/index.php?page=auth&action=updatePassword" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="6">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">Update Password</button>
                            <a href="javascript:history.back()" class="btn btn-light text-muted">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Password Change Utility
 * Secure form to update the user's account password.
 */
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    require APP_PATH . '/views/layouts/admin_footer.php';
} else {
    require APP_PATH . '/views/layouts/footer.php';
}
?>