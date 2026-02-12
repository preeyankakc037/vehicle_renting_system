<?php
/**
 * System Logs
 * Chronological record of critical system events and user activities.
 */
$page_title = "System Logs - Pathek Admin";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="assets/css/admin.css">
<?php ?>

<div class="container-fluid px-4 py-5">

    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Audit Logs</h2>
            <p class="text-muted small mb-0">Recent system activities and admin actions.</p>
        </div>
    </div>

    <div class="card admin-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Time</th>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-clipboard-list fa-2x opacity-25"></i>
                                    </div>
                                    <p class="small text-muted mb-0">No logs found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="ps-4 text-nowrap text-muted small">
                                        <?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">
                                            <?php echo htmlspecialchars($log['admin_name'] ?? 'System/Deleted Admin'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal text-uppercase admin-badge-sm">
                                            <?php echo htmlspecialchars($log['action']); ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo htmlspecialchars($log['details']); ?>
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