<?php
/**
 * Manage Users
 * Administration interface to view, edit, or delete registered users (Renters and Owners).
 */
$page_title = "Manage Users - Pathek Admin";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="assets/css/admin.css">
<?php ?>

<div class="container-fluid px-4 py-5">

    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">User Management</h2>
            <p class="text-muted admin-text-xs">Manage all Renters and Owners in one place.</p>
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

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show card-modern border-0 border-start border-4 border-danger mb-4"
            role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card admin-card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Role</th>
                            <th>Contact</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-users fa-2x opacity-25"></i></div>
                                    <p class="small text-muted mb-0">No users found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="bg-light d-flex align-items-center justify-content-center admin-btn-action me-3">
                                                <span class="fw-bold text-primary">
                                                    <?php echo strtoupper(substr($u['full_name'], 0, 1)); ?>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    <?php echo htmlspecialchars($u['full_name']); ?>
                                                </div>
                                                <div class="text-muted admin-text-xs">
                                                    <?php echo htmlspecialchars($u['email']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $role = str_replace('_', ' ', $u['user_role']);
                                        $roleClass = match ($u['user_role']) {
                                            'renter' => 'bg-info',
                                            'owner_verified' => 'bg-success',
                                            'owner_pending' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge badge-modern <?php echo $roleClass; ?>">
                                            <?php echo ucfirst($role); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small text-dark">
                                            <?php echo htmlspecialchars($u['phone']); ?>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                    </td>
                                    <td>
                                        <?php if ($u['account_status'] === 'active'): ?>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 admin-badge-sm">Active</span>
                                        <?php else: ?>
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 admin-badge-sm">Banned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <?php if ($u['account_status'] === 'active'): ?>
                                                    <li>
                                                        <a class="dropdown-item text-danger"
                                                            href="<?php echo BASE_URL; ?>/index.php?page=admin&action=updateUserStatus&id=<?php echo $u['user_id']; ?>&status=banned"
                                                            onclick="return confirm('Are you sure you want to ban this user?');">
                                                            <i class="fas fa-ban me-2"></i> Ban User
                                                        </a>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <a class="dropdown-item text-success"
                                                            href="<?php echo BASE_URL; ?>/index.php?page=admin&action=updateUserStatus&id=<?php echo $u['user_id']; ?>&status=active">
                                                            <i class="fas fa-check-circle me-2"></i> Activate User
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
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