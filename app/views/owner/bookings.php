<?php
/**
 * Owner Bookings Management
 * List of all bookings for the owner's vehicles with options to approve or reject.
 */
$page_title = "Manage Bookings - Pathek Owner";
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-1">Incoming Bookings</h2>
            <p class="text-muted small mb-0">Manage reservations for your vehicles.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 small">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4 small">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Vehicle</th>
                            <th class="py-3">Renter</th>
                            <th class="py-3">Dates</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Status</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-2x opacity-25 mb-3"></i>
                                    <p class="mb-0">No bookings requests found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($b['vehicle_name']); ?>
                                        </div>
                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($b['plate_number']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark small">
                                            <?php echo htmlspecialchars($b['renter_name']); ?>
                                        </div>
                                        <div class="small text-muted contact-info">
                                            <i class="fas fa-phone-alt me-1"></i>
                                            <?php echo htmlspecialchars($b['renter_phone']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <?php echo date('M d', strtotime($b['pickup_date'])); ?> -
                                            <?php echo date('M d', strtotime($b['dropoff_date'])); ?>
                                        </div>
                                        <div class="text-muted duration-text">
                                            <?php echo ceil((strtotime($b['dropoff_date']) - strtotime($b['pickup_date'])) / 86400); ?>
                                            days
                                        </div>
                                    </td>
                                    <td class="fw-bold text-success small">
                                        NPR
                                        <?php echo number_format($b['total_price']); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeClass = match ($b['booking_status']) {
                                            'confirmed' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'cancelled' => 'bg-danger',
                                            'completed' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?> fw-normal rounded-pill px-3">
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
                                                method="POST" class="d-inline ms-1"
                                                onsubmit="return confirm('Reject this booking?');">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger rounded-circle action-btn"
                                                    title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light border disabled rounded-circle action-btn">
                                                <i class="fas fa-lock text-muted"></i>
                                            </button>
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