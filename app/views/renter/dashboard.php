<?php
$page_title = "My Rentals - Vehicle Rental System";
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <h2>My Rentals</h2>

    <?php if(!empty($bookings)): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Owner</th>
                    <th>Pickup Date</th>
                    <th>Dropoff Date</th>
                    <th>Total Days</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['vehicle_name']); ?></td>
                        <td><?php echo htmlspecialchars($b['owner_name']); ?></td>
                        <td><?php echo htmlspecialchars($b['pickup_date']); ?></td>
                        <td><?php echo htmlspecialchars($b['dropoff_date']); ?></td>
                        <td><?php echo htmlspecialchars($b['total_days']); ?></td>
                        <td>$<?php echo htmlspecialchars($b['total_price']); ?></td>
                        <td><?php echo ucfirst($b['booking_status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no bookings yet.</p>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
