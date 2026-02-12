<?php
/**
 * Vehicle Details (Admin View)
 * Detailed inspection view for a specific vehicle to aid in approval decisions.
 */
$page_title = "Vehicle Details - Admin";
require APP_PATH . '/views/layouts/navbar.php';
?>
<link rel="stylesheet" href="./assets/css/admin.css">
<?php ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard"
                class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Vehicle Approval #
                        <?php echo $vehicle['vehicle_id']; ?>
                    </h5>
                    <span
                        class="badge bg-<?php echo $vehicle['approval_status'] == 'pending' ? 'warning' : 'secondary'; ?>">
                        <?php echo ucfirst($vehicle['approval_status']); ?>
                    </span>
                </div>
                <div class="card-body p-4">

                    <div class="row g-4">
                        <!-- Vehicle Images Carousel -->
                        <div class="col-md-6">
                            <div id="vehicleCarousel" class="carousel slide rounded overflow-hidden shadow-sm"
                                data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                                            class="d-block w-100 admin-details-img" alt="Main View">
                                        <div
                                            class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 py-1 rounded">
                                            Main View
                                        </div>
                                    </div>
                                    <?php if (!empty($vehicle['image_path_2'])): ?>
                                        <div class="carousel-item">
                                            <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=2"
                                                class="d-block w-100 admin-details-img" alt="Inside View">
                                            <div
                                                class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 py-1 rounded">
                                                Inside View
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($vehicle['image_path_3'])): ?>
                                        <div class="carousel-item">
                                            <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=3"
                                                class="d-block w-100 admin-details-img" alt="Side View">
                                            <div
                                                class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 py-1 rounded">
                                                Side View
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small">Vehicle Information</h6>
                            <h3 class="fw-bold mb-2">
                                <?php echo htmlspecialchars($vehicle['model'] . ' (' . $vehicle['brand'] . ')'); ?>
                            </h3>
                            <p class="text-success fw-bold fs-4 mb-3">NPR
                                <?php echo number_format($vehicle['price_per_day'], 0); ?> <span
                                    class="fs-6 text-muted">/ hour</span>
                            </p>

                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted small">Type</dt>
                                <dd class="col-sm-8">
                                    <?php echo $vehicle['vehicle_type']; ?> /
                                    <?php echo $vehicle['utility_type']; ?>
                                </dd>

                                <dt class="col-sm-4 text-muted small">Plate No.</dt>
                                <dd class="col-sm-8">
                                    <?php echo htmlspecialchars($vehicle['plate_number']); ?>
                                </dd>

                                <dt class="col-sm-4 text-muted small">Year</dt>
                                <dd class="col-sm-8">
                                    <?php echo $vehicle['year']; ?>
                                </dd>

                                <dt class="col-sm-4 text-muted small">Capacity</dt>
                                <dd class="col-sm-8">
                                    <?php echo $vehicle['seating_capacity'] ?? 'N/A'; ?> Seats
                                </dd>

                                <dt class="col-sm-4 text-muted small">Driver</dt>
                                <dd
                                    class="col-sm-8 text-<?php echo $vehicle['driver_available'] ? 'success' : 'secondary'; ?>">
                                    <i
                                        class="fas fa-<?php echo $vehicle['driver_available'] ? 'check' : 'times'; ?> me-1"></i>
                                    <?php echo $vehicle['driver_available'] ? 'Available' : 'Not Available'; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small">Owner Details</h6>
                            <p class="mb-1 fw-bold">
                                <?php echo htmlspecialchars($vehicle['owner_name']); ?>
                            </p>
                            <p class="mb-1 small"><i class="fas fa-envelope me-2 text-muted"></i>
                                <?php echo htmlspecialchars($vehicle['owner_email']); ?>
                            </p>
                            <p class="mb-0 small"><i class="fas fa-phone me-2 text-muted"></i>
                                <?php echo htmlspecialchars($vehicle['owner_phone']); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small">Description</h6>
                            <p class="text-muted small">
                                <?php echo nl2br(htmlspecialchars($vehicle['description'])); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <?php if ($vehicle['approval_status'] == 'pending' || $vehicle['approval_status'] == 'fixes_needed'): ?>
                        <div class="bg-light p-3 rounded mt-4 border d-flex gap-2 justify-content-end align-items-center">
                            <span class="fw-bold me-auto text-muted small text-uppercase">Admin Actions:</span>

                            <!-- Approve -->
                            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=approveVehicle" method="POST">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                <button type="submit" class="btn btn-success fw-bold">
                                    <i class="fas fa-check me-2"></i>Approve
                                </button>
                            </form>

                            <!-- Request Fixes -->
                            <button type="button" class="btn btn-warning fw-bold text-white" data-bs-toggle="modal"
                                data-bs-target="#vehicleFixesModal">
                                <i class="fas fa-wrench me-2"></i>Request Fixes
                            </button>

                            <!-- Reject -->
                            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=rejectVehicle" method="POST">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                <button type="submit" class="btn btn-outline-danger fw-bold">
                                    <i class="fas fa-times me-2"></i>Reject
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Fixes Modal -->
<div class="modal fade" id="vehicleFixesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>/index.php?page=admin&action=requestVehicleFixes" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Request Fixes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">What changes are required for this vehicle?</p>
                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                    <textarea name="admin_feedback" class="form-control" rows="4" required
                        placeholder="e.g. Images are too dark, please upload verified plate number photo."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>