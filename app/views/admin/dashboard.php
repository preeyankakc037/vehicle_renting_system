<?php
/**
 * Admin Dashboard
 * High-level overview of system metrics, recent activities, and quick access to management modules.
 */
$page_title = "Admin Dashboard - Pathek";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="assets/css/admin.css">
<?php ?>

<div class="container-fluid px-4 py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Dashboard Overview</h2>
            <p class="text-muted small mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=vehicles" class="btn btn-primary btn-sm">
                <i class="fas fa-car me-2"></i> Manage Vehicles
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-5">

        <!-- Total Vehicles -->
        <div class="col-xl col-md-4">
            <div class="card card-modern h-100 border-0 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Vehicles</div>
                        <div class="bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center admin-stat-icon"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-car small"></i>
                        </div>
                    </div>
                    <div class="h3 fw-bold mb-0 text-dark"><?php echo $stats['vehicles']; ?></div>
                </div>
            </div>
        </div>

        <!-- Verified Owners -->
        <div class="col-xl col-md-4">
            <div class="card card-modern h-100 border-0 border-start border-4 border-success">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Owners</div>
                        <div class="bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center admin-stat-icon"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-user-check small"></i>
                        </div>
                    </div>
                    <div class="h3 fw-bold mb-0 text-dark"><?php echo $stats['owners']; ?></div>
                </div>
            </div>
        </div>

        <!-- Total Renters -->
        <div class="col-xl col-md-4">
            <div class="card card-modern h-100 border-0 border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Renters</div>
                        <div class="bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center admin-stat-icon"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-users small"></i>
                        </div>
                    </div>
                    <div class="h3 fw-bold mb-0 text-dark"><?php echo $stats['users']; ?></div>
                </div>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="col-xl-2 col-md-6">
            <div class="card card-modern h-100 border-0 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small fw-bold text-uppercase">Pending Requests</div>
                        <div
                            class="bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center admin-stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="h2 fw-bold mb-0 text-dark"><?php echo $stats['pending_verifications']; ?></div>
                    <div class="small text-muted mt-2">
                        Verifications
                    </div>
                </div>
            </div>
        </div>

        <!-- User Messages -->
        <div class="col-xl-2 col-md-6">
            <div class="card card-modern h-100 border-0 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small fw-bold text-uppercase">New Messages</div>
                        <div
                            class="bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center admin-stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <div class="h2 fw-bold mb-0 text-dark"><?php echo $stats['messages']; ?></div>
                    <div class="small text-muted mt-2">
                        User inquiries
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row g-4">

        <!-- Recent Listed Vehicles -->
        <div class="col-lg-7">
            <div class="card card-modern shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold fs-6 text-dark">Recent Vehicles</h5>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=vehicles"
                        class="btn btn-light btn-sm text-muted">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Vehicle Details</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_vehicles)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted mb-2"><i class="fas fa-car fa-2x opacity-25"></i></div>
                                            <p class="small text-muted mb-0">No recent vehicles found.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_vehicles as $v): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="rounded bg-light d-flex align-items-center justify-content-center admin-stat-icon me-4">
                                                        <i class="fas fa-car text-secondary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark small">
                                                            <?php echo htmlspecialchars($v['vehicle_name']); ?>
                                                        </div>
                                                        <div class="text-muted admin-text-xs">
                                                            <i
                                                                class="fas fa-user-tag me-1"></i><?php echo htmlspecialchars($v['owner_name']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-success small">
                                                NPR <?php echo number_format($v['price_per_day']); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match ($v['approval_status']) {
                                                    'approved' => 'bg-success',
                                                    'pending' => 'bg-warning',
                                                    'rejected' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge badge-modern <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($v['approval_status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=viewVehicleDetails&id=<?php echo $v['vehicle_id']; ?>"
                                                    class="btn btn-light btn-sm text-secondary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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

        <!-- Recent Verifications -->
        <div class="col-lg-5">
            <div class="card card-modern shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold fs-6 text-dark">Pending Verifications</h5>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=verifications"
                        class="btn btn-light btn-sm text-muted">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Applicant</th>
                                    <th>Submitted</th>
                                    <th class="text-end pe-4">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_verifications)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="text-muted mb-2"><i
                                                    class="fas fa-check-circle fa-2x opacity-25"></i></div>
                                            <p class="small text-muted mb-0">No pending verifications.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_verifications as $rv): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="rounded-circle bg-light d-flex align-items-center justify-content-center admin-btn-action me-3">
                                                        <span
                                                            class="small fw-bold text-secondary"><?php echo strtoupper(substr($rv['full_name'], 0, 1)); ?></span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark small">
                                                            <?php echo htmlspecialchars($rv['full_name']); ?>
                                                        </div>
                                                        <div class="text-muted admin-text-xs">
                                                            <?php echo htmlspecialchars($rv['business_name']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted small">
                                                <?php echo date('M d', strtotime($rv['created_at'])); ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=viewVerificationDetails&id=<?php echo $rv['verification_id']; ?>"
                                                    class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1">
                                                    Review
                                                </a>
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

    </div>
</div>

<?php require APP_PATH . '/views/layouts/admin_footer.php'; ?>