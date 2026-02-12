<?php
/**
 * Verification Details
 * Review submitted documents (License, Bluebook) for a prospective vehicle owner.
 */
$page_title = "Verification Details - Admin";
require APP_PATH . '/views/layouts/navbar.php';
?>
<link rel="stylesheet" href="./assets/css/admin.css">
<?php ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard"
                class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>

            <div class="card admin-card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Verification Request #
                        <?php echo $verification['verification_id']; ?>
                    </h5>
                    <span
                        class="badge bg-<?php echo $verification['status'] == 'pending' ? 'warning' : 'secondary'; ?>">
                        <?php echo ucfirst($verification['status']); ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <!-- Applicant Info -->
                    <h6 class="text-uppercase text-muted fw-bold mb-3 small">Applicant Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="small text-muted">Full Name</label>
                            <p class="fw-bold">
                                <?php echo htmlspecialchars($verification['full_name']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Business Name</label>
                            <p class="fw-bold">
                                <?php echo htmlspecialchars($verification['business_name'] ?: 'N/A'); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Email</label>
                            <p class="fw-bold">
                                <?php echo htmlspecialchars($verification['email']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Phone</label>
                            <p class="fw-bold">
                                <?php echo htmlspecialchars($verification['phone']); ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- ID Proof -->
                    <h6 class="text-uppercase text-muted fw-bold mb-3 small">Identification Proof</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="small text-muted">ID Type</label>
                            <p class="fw-bold">
                                <?php echo htmlspecialchars($verification['id_proof_type']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">ID Number</label>
                            <p class="fw-bold">
                                <?php echo htmlspecialchars($verification['id_proof_number']); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <?php if ($verification['status'] == 'pending' || $verification['status'] == 'fixes_needed'): ?>
                        <hr>
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Actions</h6>
                        <div class="d-flex gap-2">
                            <!-- Approve -->
                            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=approveOwner" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $verification['user_id']; ?>">
                                <input type="hidden" name="verification_id"
                                    value="<?php echo $verification['verification_id']; ?>">
                                <button type="submit" class="btn btn-success fw-bold">
                                    <i class="fas fa-check me-2"></i>Approve Owner
                                </button>
                            </form>

                            <!-- Request Fixes -->
                            <button type="button" class="btn btn-warning fw-bold text-white" data-bs-toggle="modal"
                                data-bs-target="#fixesModal">
                                <i class="fas fa-wrench me-2"></i>Request Fixes
                            </button>

                            <!-- Reject -->
                            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=rejectOwner" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $verification['user_id']; ?>">
                                <input type="hidden" name="verification_id"
                                    value="<?php echo $verification['verification_id']; ?>">
                                <button type="submit" class="btn btn-outline-danger fw-bold">
                                    <i class="fas fa-times me-2"></i>Reject Application
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixes Modal -->
<div class="modal fade" id="fixesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=requestOwnerFixes" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Request Fixes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">What needs to be corrected?</p>
                    <input type="hidden" name="verification_id" value="<?php echo $verification['verification_id']; ?>">
                    <textarea name="admin_feedback" class="form-control" rows="4" required
                        placeholder="e.g. ID proof image is blurry, please re-upload."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>