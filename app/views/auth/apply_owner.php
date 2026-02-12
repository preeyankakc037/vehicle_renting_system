<?php
/**
 * Owner Application Form
 * Allows existing renters to apply for an upgrade to "Vehicle Owner" status.
 */
$page_title = "Become a Partner - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                        <h2 class="fw-bold">Become an Owner</h2>
                        <p class="text-muted">Start earning by listing your vehicles on Pathek.</p>
                    </div>

                    <?php if (isset($user['status']) && $user['status'] === 'fixes_needed'): ?>
                        <div class="alert alert-warning border-warning mb-4">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Action Required</h5>
                            <p class="mb-1">The admin has requested changes to your application:</p>
                            <hr>
                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($user['admin_feedback']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/index.php?page=verification&action=store" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Business Name (Optional)</label>
                            <input type="text" name="business_name" class="form-control"
                                placeholder="e.g. John's Rentals">
                            <div class="form-text">Leave blank if you are an individual owner.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ID Proof Type <span class="text-danger">*</span></label>
                            <select name="id_proof_type" class="form-select" required>
                                <option value="Citizenship">Citizenship Card</option>
                                <option value="License">Driving License</option>
                                <option value="Passport">Passport</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Phone Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required
                                    placeholder="Contact Number"
                                    value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">ID Proof Number <span class="text-danger">*</span></label>
                            <input type="text" name="id_proof_number" class="form-control" required
                                placeholder="Enter ID Number">
                        </div>

                        <div class="alert alert-light border small text-muted mb-4">
                            <i class="fas fa-info-circle me-1 text-primary"></i>
                            <strong>Note:</strong> We charge a flat <strong>10% service fee</strong> on every successful
                            booking.
                            Your application will be reviewed by our admin team within 24 hours.
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>