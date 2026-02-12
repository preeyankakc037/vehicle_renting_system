<?php
/**
 * My Bookings List
 * Displays a history of all bookings made by the logged-in renter with their current status.
 */
$page_title = "My Bookings - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">My Rentals</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>
            <?php
            if ($_GET['success'] == 'cancelled')
                echo "Booking cancelled successfully.";
            else
                echo "Booking request submitted successfully!";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php
            if ($_GET['error'] == 'unauthorized')
                echo "You are not authorized to cancel this booking.";
            elseif ($_GET['error'] == 'cannot_cancel')
                echo "Only pending bookings can be cancelled.";
            else
                echo "An error occurred while processing your request.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <div class="d-inline-block p-4 rounded-circle bg-light text-primary">
                    <i class="fas fa-calendar-alt fa-3x"></i>
                </div>
            </div>
            <h3 class="fw-bold text-dark">No Active Bookings</h3>
            <p class="text-muted w-50 mx-auto mb-4">
                You haven't booked any vehicles yet. Start your journey by exploring our premium fleet of cars, bikes, and
                scooties.
            </p>
            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle" class="btn btn-primary btn-lg px-5 pill-btn shadow-sm">
                <i class="fas fa-car-side me-2"></i> Browse Vehicles
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Vehicle</th>
                        <th>Dates</th>
                        <th>Total Price</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-light rounded p-2 text-center text-muted vehicle-icon">
                                            <i class="fas fa-car"></i>
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">
                                        <?php echo htmlspecialchars($b['vehicle_name']); ?>
                                    </div>
                                    <div class="small text-muted">
                                        <?php echo htmlspecialchars($b['vehicle_type']); ?>
                                    </div>
                                </div>
            </div>
            </td>
            <td>
                <div class="small">
                    <div class="fw-bold">
                        <?php echo date('M d', strtotime($b['pickup_date'])); ?>
                    </div>
                    <div class="text-muted">to
                        <?php echo date('M d, Y', strtotime($b['dropoff_date'])); ?>
                    </div>
                </div>
            </td>
            <td class="fw-bold text-success">NPR
                <?php echo number_format($b['total_price'], 0); ?>
            </td>
            <td>
                <div class="small">
                    <?php echo htmlspecialchars($b['owner_name']); ?>
                </div>
                <div class="small text-muted">
                    <?php echo htmlspecialchars($b['owner_phone']); ?>
                </div>
            </td>
            <td>
                <?php
                $statusClass = match ($b['booking_status']) {
                    'confirmed' => 'success',
                    'pending' => 'warning',
                    'cancelled' => 'danger',
                    'completed' => 'primary',
                    default => 'secondary'
                };
                ?>
                <span
                    class="badge bg-<?php echo $statusClass; ?> bg-opacity-10 text-<?php echo $statusClass; ?> px-3 py-2 rounded-pill">
                    <?php echo ucfirst($b['booking_status']); ?>
                </span>
            </td>
            <td class="text-end pe-4">
                <?php if ($b['booking_status'] === 'pending'): ?>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=booking&action=cancel&id=<?php echo $b['booking_id']; ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Are you sure you want to cancel this booking?');">
                        Cancel
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-light text-muted" disabled>Receipt</button>
                <?php endif; ?>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </div>
<?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>