<?php
/**
 * Manage Admins
 * List of all system administrators with options to add or manage access.
 */
$page_title = "Admin Management - Pathek";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="assets/css/admin.css">
<?php ?>

<div class="container py-5">
    <div class="admin-page-header d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0">Admin Management</h2>
        <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=createAdmin" class="btn btn-success btn-sm">
            <i class="fas fa-plus me-2"></i>Create New Admin
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card admin-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($admins)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No admins found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">
                                            <?php echo htmlspecialchars($admin['full_name']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($admin['email']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($admin['phone']); ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($admin['created_at'])); ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=editAdmin&id=<?php echo $admin['user_id']; ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($admin['user_id'] != $_SESSION['user_id']): ?>
                                                <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=deleteAdmin"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                                    <input type="hidden" name="admin_id" value="<?php echo $admin['user_id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">You</span>
                                            <?php endif; ?>
                                        </div>
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