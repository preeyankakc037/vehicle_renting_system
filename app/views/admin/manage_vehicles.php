<?php
/**
 * Manage Vehicles
 * Administration interface to oversee all listed vehicles and manage their approval status.
 */
$page_title = "Manage Vehicles - Pathek Admin";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="./assets/css/admin.css">
<?php
$status_filter = $_GET['status'] ?? '';
$type_filter = $_GET['type'] ?? '';
$search_query = $_GET['search'] ?? '';
?>

<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Vehicle Management</h2>
            <p class="text-muted small mb-0">Monitor and manage all listed vehicles.</p>
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

    <!-- Filters & Search -->
    <div class="card admin-card border-0 mb-4">
        <div class="card-body p-3">
            <form action="<?php echo BASE_URL; ?>/index.php" method="GET" class="row g-3 align-items-center">
                <input type="hidden" name="page" value="admin">
                <input type="hidden" name="action" value="vehicles">

                <!-- Search -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="search"
                            placeholder="Search vehicle, owner, plate..."
                            value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending
                            Approval</option>
                        <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Active
                            (Approved)</option>
                        <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected
                            / Suspended</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div class="col-md-3">
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="Car" <?php echo $type_filter === 'Car' ? 'selected' : ''; ?>>Car</option>
                        <option value="Bike" <?php echo $type_filter === 'Bike' ? 'selected' : ''; ?>>Bike</option>
                        <option value="Scooty" <?php echo $type_filter === 'Scooty' ? 'selected' : ''; ?>>Scooty
                        </option>
                    </select>
                </div>

                <!-- Action -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Filter</button>
                    <?php if (!empty($status_filter) || !empty($type_filter) || !empty($search_query)): ?>
                        <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=vehicles"
                            class="btn btn-outline-secondary" title="Clear Filters"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Vehicles List -->
    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Vehicle</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th>Price / Hour</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vehicles)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-3"><i class="fas fa-filter fa-3x opacity-25"></i></div>
                                    <h6 class="fw-bold text-dark">No vehicles matches your filters.</h6>
                                    <p class="small text-muted mb-3">Try adjusting your search or filter criteria.</p>
                                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=vehicles"
                                        class="btn btn-outline-primary btn-sm">Clear All Filters</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vehicles as $v): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="bg-light d-flex align-items-center justify-content-center admin-btn-action me-4">
                                                <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $v['vehicle_id']; ?>&slot=1"
                                                    alt="Vehicle" class="admin-table-img"
                                                    onerror="this.src='<?php echo BASE_URL; ?>/assets/images/default_car.jpg'">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    <?php echo htmlspecialchars($v['model'] . ' (' . $v['brand'] . ')'); ?>
                                                </div>
                                                <div class="text-muted small">
                                                    <?php echo htmlspecialchars($v['plate_number']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">
                                            <?php echo htmlspecialchars($v['owner_name']); ?>
                                        </div>
                                        <div class="text-muted admin-text-xs">
                                            <?php echo htmlspecialchars($v['owner_email']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $icon = match ($v['vehicle_type']) {
                                            'Car' => 'fa-car',
                                            'Bike' => 'fa-motorcycle',
                                            'Scooter' => 'fa-motorcycle',
                                            default => 'fa-car'
                                        };
                                        ?>
                                        <span class="badge bg-light text-dark fw-normal border">
                                            <i class="fas <?php echo $icon; ?> me-1 text-secondary"></i>
                                            <?php echo htmlspecialchars($v['vehicle_type']); ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        NPR
                                        <?php echo number_format($v['price_per_day']); ?>
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
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?php echo BASE_URL; ?>/index.php?page=admin&action=viewVehicleDetails&id=<?php echo $v['vehicle_id']; ?>">
                                                        <i class="fas fa-eye me-2 text-primary"></i> View Details
                                                    </a>
                                                </li>

                                                <?php if ($v['approval_status'] === 'pending'): ?>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="<?php echo BASE_URL; ?>/index.php?page=admin&action=approveVehicle"
                                                            method="POST" class="d-inline">
                                                            <input type="hidden" name="vehicle_id"
                                                                value="<?php echo $v['vehicle_id']; ?>">
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle me-2"></i> Approve
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($v['approval_status'] === 'approved'): ?>
                                                    <li>
                                                        <form
                                                            action="<?php echo BASE_URL; ?>/index.php?page=admin&action=suspendVehicle"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to suspend this vehicle?');">
                                                            <input type="hidden" name="vehicle_id"
                                                                value="<?php echo $v['vehicle_id']; ?>">
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="fas fa-ban me-2"></i> Suspend
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form
                                                        action="<?php echo BASE_URL; ?>/index.php?page=admin&action=deleteVehicle"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('CAUTION: This will permanently delete the vehicle. Continue?');">
                                                        <input type="hidden" name="vehicle_id"
                                                            value="<?php echo $v['vehicle_id']; ?>">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash-alt me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls (Static only) -->
            <?php if (!empty($vehicles) && count($vehicles) >= 20): ?>
                <div class="d-flex justify-content-center py-4 border-top">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/admin_footer.php'; ?>