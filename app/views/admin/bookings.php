<?php
/**
 * Admin Booking Overview
 * View all system bookings with details on status, price, and involved parties.
 */
$page_title = "Manage Bookings - Pathek Admin";
require APP_PATH . '/views/layouts/admin_navbar.php';
?>
<link rel="stylesheet" href="./assets/css/admin.css">
<?php ?>

<div class="container-fluid px-4 py-5">

    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Bookings</h2>
            <p class="text-muted small mb-0">View and manage all vehicle reservations.</p>
        </div>
    </div>

    <div class="card admin-card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Booking ID</th>
                            <th>Vehicle</th>
                            <th>Renter</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-calendar-times fa-2x opacity-25"></i>
                                    </div>
                                    <p class="small text-muted mb-0">No bookings found in the system.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted small">
                                        #
                                        <?php echo $b['booking_id']; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">
                                            <?php echo htmlspecialchars($b['vehicle_name']); ?>
                                        </div>
                                        <div class="text-muted admin-text-xs">
                                            <?php echo htmlspecialchars($b['plate_number']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-dark">
                                            <?php echo htmlspecialchars($b['renter_name']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <?php echo date('M d', strtotime($b['pickup_date'])); ?> -
                                            <?php echo date('M d', strtotime($b['dropoff_date'])); ?>
                                        </div>
                                        <div class="text-muted admin-text-xs">
                                            <?php
                                            $days = (strtotime($b['dropoff_date']) - strtotime($b['pickup_date'])) / (60 * 60 * 24);
                                            echo ceil($days) . " days";
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match ($b['booking_status']) {
                                            'confirmed' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'cancelled' => 'bg-danger',
                                            'completed' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge badge-modern <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($b['booking_status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-dark">
                                        NPR
                                        <?php echo number_format($b['total_price']); ?>
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