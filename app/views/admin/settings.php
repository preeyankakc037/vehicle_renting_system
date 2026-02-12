<?php
/**
 * System Settings
 * Configuration panel for global system variables (e.g., Commission Rate).
 */
$page_title = "System Settings - Pathek Admin";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="./assets/css/admin.css">
<?php ?>

<div class="container py-5 admin-container-sm">

    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">System Settings</h2>
            <p class="text-muted small mb-0">Configure platform rules and defaults.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show card-modern border-0 border-start border-4 border-success mb-4"
            role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=updateSettings" method="POST">

        <!-- Platform Settings -->
        <div class="card admin-card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-globe me-2 text-primary"></i> Platform Settings</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Platform Name</label>
                    <input type="text" class="form-control" name="platform_name"
                        value="<?php echo htmlspecialchars($settings['platform_name'] ?? 'Pathek'); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Support Email</label>
                    <input type="email" class="form-control" name="support_email"
                        value="<?php echo htmlspecialchars($settings['support_email'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Currency (Default)</label>
                    <input type="text" class="form-control bg-light" name="currency"
                        value="<?php echo htmlspecialchars($settings['currency'] ?? 'NPR'); ?>" readonly>
                </div>
            </div>
        </div>

        <!-- Vehicle Rules -->
        <div class="card admin-card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-car me-2 text-success"></i> Vehicle Rules</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Min Price / Day</label>
                        <input type="number" class="form-control" name="min_price"
                            value="<?php echo htmlspecialchars($settings['min_price'] ?? '500'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Max Price / Day</label>
                        <input type="number" class="form-control" name="max_price"
                            value="<?php echo htmlspecialchars($settings['max_price'] ?? '50000'); ?>">
                    </div>
                </div>
                <div class="mt-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="verificationToggle" name="verification_required"
                        value="1" <?php echo ($settings['verification_required'] ?? '0') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label small fw-bold" for="verificationToggle">Partner Verification
                        Required</label>
                    <div class="form-text small">If enabled, owners must be verified before listing vehicles.</div>
                </div>
            </div>
        </div>

        <!-- Booking Rules -->
        <div class="card admin-card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-info"></i> Booking Rules</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Min Booking Duration (Days)</label>
                    <input type="number" class="form-control" name="min_booking_duration"
                        value="<?php echo htmlspecialchars($settings['min_booking_duration'] ?? '1'); ?>">
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cancelToggle" name="cancellation_allowed"
                        value="1" <?php echo ($settings['cancellation_allowed'] ?? '0') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label small fw-bold" for="cancelToggle">Allow User Cancellations</label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5">
                <i class="fas fa-save me-2"></i> Save Changes
            </button>
        </div>

    </form>
</div>

<?php require APP_PATH . '/views/layouts/admin_footer.php'; ?>