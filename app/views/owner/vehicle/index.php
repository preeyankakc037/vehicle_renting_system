<?php
/**
 * My Vehicles List
 * Displays all vehicles owned by the logged-in user with status and management options.
 */
$page_title = "My Vehicles - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Vehicles</h2>
        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=create" class="btn btn-success fw-bold">
            <i class="fas fa-plus me-2"></i>Add New Vehicle
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == 'created'): ?>
                Vehicle added successfully! Pending approval.
            <?php elseif ($_GET['success'] == 'updated'): ?>
                Vehicle updated successfully!
            <?php elseif ($_GET['success'] == 'status_updated'): ?>
                Vehicle status updated successfully!
            <?php elseif ($_GET['success'] == 'deleted'): ?>
                Vehicle deleted successfully!
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php if ($_GET['error'] == 'booked'): ?>
                Cannot change status: Vehicle is currently booked by a renter.
            <?php else: ?>
                An error occurred while updating the vehicle.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Vehicle</th>
                            <th>Type</th>
                            <th>Price/Hour</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vehicles)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-5">
                                        <div class="mb-4">
                                            <i class="fas fa-car-side fa-4x text-muted opacity-25"></i>
                                        </div>
                                        <h4 class="text-secondary fw-bold">No Vehicles Listed Yet</h4>
                                        <p class="text-muted mb-4">You haven't added any vehicles to your fleet. Start
                                            earning by listing your first vehicle!</p>
                                        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=create"
                                            class="btn btn-primary px-4 py-2 fw-semibold">
                                            <i class="fas fa-plus me-2"></i>Add Your First Vehicle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                                                class="rounded me-3 vehicle-thumbnail">
                                            <div>
                                                <div class="fw-bold">
                                                    <?php echo htmlspecialchars($vehicle['model'] . ' (' . $vehicle['brand'] . ')'); ?>
                                                </div>
                                                <div class="small text-muted">
                                                    <?php echo htmlspecialchars($vehicle['plate_number']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($vehicle['vehicle_type']); ?>
                                        </span></td>
                                    <td>NPR
                                        <?php echo number_format($vehicle['price_per_day'], 0); ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <?php if ($vehicle['availability_status'] === 'available'): ?>
                                                <button class="btn btn-sm btn-success dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-check-circle me-1"></i> Available
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-warning"
                                                            href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=updateStatus&id=<?php echo $vehicle['vehicle_id']; ?>&status=maintenance">
                                                            <i class="fas fa-tools me-2"></i> Mark as Busy / Maintenance
                                                        </a>
                                                    </li>
                                                </ul>
                                            <?php elseif ($vehicle['availability_status'] === 'maintenance'): ?>
                                                <button class="btn btn-sm btn-warning dropdown-toggle text-white" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-tools me-1"></i> Busy
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-success"
                                                            href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=updateStatus&id=<?php echo $vehicle['vehicle_id']; ?>&status=available">
                                                            <i class="fas fa-check-circle me-2"></i> Mark as Available
                                                        </a>
                                                    </li>
                                                </ul>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger">
                                                    <i class="fas fa-calendar-check me-1"></i> Booked
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($vehicle['approval_status'] === 'approved'): ?>
                                            <i class="fas fa-check-circle text-success" title="Approved"></i> Approved
                                        <?php elseif ($vehicle['approval_status'] === 'pending'): ?>
                                            <i class="fas fa-clock text-warning" title="Pending"></i> Pending
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-danger" title="Rejected"></i> Rejected
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=edit&id=<?php echo $vehicle['vehicle_id']; ?>"
                                            class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=delete&id=<?php echo $vehicle['vehicle_id']; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this vehicle?');">
                                            <i class="fas fa-trash"></i>
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

<?php require APP_PATH . '/views/layouts/footer.php'; ?>