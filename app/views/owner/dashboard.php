<?php
/**
 * Owner Dashboard
 * Central hub for vehicle owners to see statistics, recent activities, and quick actions.
 */
$page_title = "Owner Dashboard - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<!-- Vehicle CSS -->
<link rel="stylesheet" href="./assets/css/vehicle.css">

<div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Owner Dashboard</h2>
            <p class="text-muted small mb-0">Control center for your vehicle rental business.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=create"
            class="btn btn-success fw-bold rounded-3 shadow-sm">
            <i class="fas fa-plus me-2"></i> List New Vehicle
        </a>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div
            class="alert alert-success alert-dismissible fade show mb-4 rounded-3 small border-0 border-start border-4 border-success shadow-sm">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div
            class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 small border-0 border-start border-4 border-danger shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <!-- Section A: Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 bg-gradient-card">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?php echo $stats['total_vehicles']; ?></h3>
                        <div class="text-muted small fw-semibold">Total Listed</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 owner-stats-card">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?php echo $stats['active_vehicles']; ?></h3>
                        <div class="text-muted small fw-semibold">Active Vehicles</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 owner-stats-card">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?php echo $stats['pending_bookings']; ?></h3>
                        <div class="text-muted small fw-semibold">Pending Requests</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section B: Vehicle Management -->
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <div
                class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3 icon-32">
                <i class="fas fa-tasks small"></i>
            </div>
            <h4 class="fw-bold mb-0">My Vehicles</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Vehicle</th>
                            <th class="py-3">Price/Hour</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Approval</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vehicles)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">No vehicles listed yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vehicles as $v): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($v['model'] . ' (' . $v['brand'] . ')'); ?>
                                        </div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($v['plate_number']); ?></div>
                                    </td>
                                    <td class="fw-bold text-success small">NPR <?php echo number_format($v['price_per_day']); ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <?php if ($v['availability_status'] === 'available'): ?>
                                                <button
                                                    class="btn btn-sm btn-outline-success dropdown-toggle px-3 border-0 bg-success bg-opacity-10"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-check-circle me-1"></i> Available
                                                </button>
                                                <ul class="dropdown-menu shadow border-0">
                                                    <li>
                                                        <a class="dropdown-item text-warning"
                                                            href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=updateStatus&id=<?php echo $v['vehicle_id']; ?>&status=maintenance&redirect=dashboard">
                                                            <i class="fas fa-tools me-2"></i> Mark as Busy
                                                        </a>
                                                    </li>
                                                </ul>
                                            <?php elseif ($v['availability_status'] === 'maintenance'): ?>
                                                <button
                                                    class="btn btn-sm btn-outline-warning dropdown-toggle px-3 border-0 bg-warning bg-opacity-10"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-tools me-1"></i> Busy
                                                </button>
                                                <ul class="dropdown-menu shadow border-0">
                                                    <li>
                                                        <a class="dropdown-item text-success"
                                                            href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=updateStatus&id=<?php echo $v['vehicle_id']; ?>&status=available&redirect=dashboard">
                                                            <i class="fas fa-check-circle me-2"></i> Mark as Available
                                                        </a>
                                                    </li>
                                                </ul>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-3 fw-normal">
                                                    <i class="fas fa-calendar-check me-1"></i> Booked
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $appBadge = match ($v['approval_status']) {
                                            'approved' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'rejected' => 'bg-danger',
                                            'fixes_needed' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?php echo $appBadge; ?> rounded-pill fw-normal">
                                            <?php echo ucfirst(str_replace('_', ' ', $v['approval_status'])); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=edit&id=<?php echo $v['vehicle_id']; ?>"
                                            class="btn btn-sm btn-light border rounded-circle me-1" title="Edit">
                                            <i class="fas fa-edit text-muted"></i>
                                        </a>
                                        <form action="<?php echo BASE_URL; ?>/index.php?page=owner&action=deleteVehicle"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this vehicle permanently?');">
                                            <input type="hidden" name="vehicle_id" value="<?php echo $v['vehicle_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-light border rounded-circle text-danger"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
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

    <!-- Section C: Booking Management -->
    <div>
        <div class="d-flex align-items-center mb-3">
            <div
                class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 section-icon">
                <i class="fas fa-calendar-check small"></i>
            </div>
            <i class="fas fa-calendar-check small"></i>
        </div>
        <h4 class="fw-bold mb-0">Bookings for My Vehicles</h4>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Vehicle</th>
                        <th class="py-3">Renter</th>
                        <th class="py-3">Dates</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted small">No booking requests found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($b['vehicle_name']); ?></div>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?php echo htmlspecialchars($b['renter_name']); ?></div>
                                    <div class="text-muted contact-info"><i
                                            class="fas fa-phone small me-1"></i><?php echo htmlspecialchars($b['renter_phone']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-dark">
                                        <?php echo date('M d', strtotime($b['pickup_date'])) . ' - ' . date('M d', strtotime($b['dropoff_date'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $bBadge = match ($b['booking_status']) {
                                        'confirmed' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'cancelled' => 'bg-danger',
                                        'completed' => 'bg-primary',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $bBadge; ?> rounded-pill fw-normal">
                                        <?php echo ucfirst($b['booking_status']); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($b['booking_status'] === 'pending'): ?>
                                        <form action="<?php echo BASE_URL; ?>/index.php?page=owner&action=approveBooking"
                                            method="POST" class="d-inline">
                                            <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success rounded-circle action-btn"
                                                title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo BASE_URL; ?>/index.php?page=owner&action=rejectBooking"
                                            method="POST" class="d-inline ms-1" onsubmit="return confirm('Reject booking?');">
                                            <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger rounded-circle action-btn"
                                                title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small"><i class="fas fa-lock"></i></span>
                                    <?php endif; ?>
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

<?php require APP_PATH . '/views/layouts/footer.php'; ?>