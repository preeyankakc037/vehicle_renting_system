<?php
$page_title = 'Admin Dashboard - Vehicle Rental System';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-stat">
                <h3><?php echo $total_users; ?></h3>
                <p>Total Users</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-stat" style="background: linear-gradient(135deg, #10b981, #059669);">
                <h3><?php echo $total_vehicles; ?></h3>
                <p>Total Vehicles</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-stat" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h3><?php echo $total_bookings; ?></h3>
                <p>Total Bookings</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-stat" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <h3><?php echo count($pending_vehicles); ?></h3>
                <p>Pending Approvals</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <a href="/public/index.php?page=admin&action=users" class="btn btn-primary w-100">Manage Users</a>
        </div>
        <div class="col-md-4">
            <a href="/public/index.php?page=admin&action=vehicles" class="btn btn-primary w-100">Manage Vehicles</a>
        </div>
        <div class="col-md-4">
            <a href="/public/index.php?page=admin&action=bookings" class="btn btn-primary w-100">View All Bookings</a>
        </div>
    </div>

    <?php if (!empty($pending_vehicles)): ?>
    <div class="dashboard-card">
        <h4 class="mb-3">Pending Vehicle Approvals</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Price/Day</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_vehicles as $vehicle): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vehicle['vehicle_name']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['owner_name']); ?></td>
                            <td><?php echo ucfirst($vehicle['vehicle_type']); ?></td>
                            <td>$<?php echo number_format($vehicle['price_per_day'], 2); ?></td>
                            <td>
                                <a href="/public/index.php?page=admin&action=approveVehicle&id=<?php echo $vehicle['vehicle_id']; ?>" 
                                   class="btn btn-sm btn-success">Approve</a>
                                <a href="/public/index.php?page=admin&action=rejectVehicle&id=<?php echo $vehicle['vehicle_id']; ?>" 
                                   class="btn btn-sm btn-danger">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-card mt-4">
        <h4 class="mb-3">Recent Bookings</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Renter</th>
                        <th>Dates</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_bookings)): ?>
                        <?php foreach ($recent_bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['vehicle_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['renter_name']); ?></td>
                                <td><?php echo date('M d', strtotime($booking['pickup_date'])); ?> - <?php echo date('M d', strtotime($booking['dropoff_date'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo $booking['booking_status'] === 'confirmed' ? 'success' : 
                                             ($booking['booking_status'] === 'pending' ? 'warning' : 'primary'); 
                                    ?>">
                                        <?php echo ucfirst($booking['booking_status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No bookings yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>