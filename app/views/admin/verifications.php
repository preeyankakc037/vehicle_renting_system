<?php
/**
 * Owner Verification Queue
 * List of pending owner applications requiring document review and approval.
 */
$page_title = "Verify Owners - Admin";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="assets/css/admin.css">
<?php ?>

<div class="container py-5">
    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Pending Owner Verifications</h2>
        </div>
        <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard"
            class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Action completed successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card admin-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Applicant</th>
                            <th>Business Name</th>
                            <th>ID Proof</th>
                            <th>Submitted On</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($verifications)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fa-2x mb-3 d-block text-success"></i>
                                    No pending verification requests.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($verifications as $v): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">
                                            <?php echo htmlspecialchars($v['full_name']); ?>
                                        </div>
                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($v['email']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($v['business_name'] ?: 'Individual'); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($v['id_proof_type']); ?>
                                        </span>
                                        <div class="small text-muted mt-1">
                                            <?php echo htmlspecialchars($v['id_proof_number']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($v['created_at'])); ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=approveOwner"
                                            method="POST" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?php echo $v['user_id']; ?>">
                                            <input type="hidden" name="verification_id"
                                                value="<?php echo $v['verification_id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm fw-bold"
                                                onclick="return confirm('Approve this owner?');">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=rejectOwner"
                                            method="POST" class="d-inline ms-2">
                                            <input type="hidden" name="user_id" value="<?php echo $v['user_id']; ?>">
                                            <input type="hidden" name="verification_id"
                                                value="<?php echo $v['verification_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Reject this owner?');">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/admin_footer.php'; ?>