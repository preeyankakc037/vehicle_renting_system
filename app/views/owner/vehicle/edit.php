<?php
/**
 * Edit Vehicle Details
 * Form to update existing vehicle information, images, and availability settings.
 */
$page_title = "Edit Vehicle - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold">Edit Vehicle</h4>
                </div>
                <div class="card-body p-4">

                    <?php if ($vehicle['approval_status'] === 'fixes_needed'): ?>
                        <div class="alert alert-warning border-warning">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Action Required</h5>
                            <p class="mb-1">The admin has requested changes for this vehicle:</p>
                            <hr>
                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($vehicle['admin_feedback']); ?></p>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=update" method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">

                        <!-- Step 1: Basic Info -->
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Basic Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                <select name="vehicle_type" class="form-select" required
                                    onchange="toggleFields(this.value)">
                                    <option value="Car" <?php echo $vehicle['vehicle_type'] == 'Car' ? 'selected' : ''; ?>>Car</option>
                                    <option value="Bike" <?php echo $vehicle['vehicle_type'] == 'Bike' ? 'selected' : ''; ?>>Bike</option>
                                    <option value="Scooty" <?php echo $vehicle['vehicle_type'] == 'Scooty' ? 'selected' : ''; ?>>Scooty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand / Make <span class="text-danger">*</span></label>
                                <input type="text" name="brand" class="form-control"
                                    value="<?php echo htmlspecialchars($vehicle['brand']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control"
                                    value="<?php echo htmlspecialchars($vehicle['model']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control"
                                    value="<?php echo htmlspecialchars($vehicle['year']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">License Plate Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="plate_number" class="form-control"
                                    value="<?php echo htmlspecialchars($vehicle['plate_number']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price Per Hour (NPR) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="price_per_day" class="form-control" step="1"
                                    value="<?php echo htmlspecialchars($vehicle['price_per_day']); ?>" required>
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Vehicle Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 <?php echo ($vehicle['vehicle_type'] == 'Bike' || $vehicle['vehicle_type'] == 'Scooty') ? 'd-none' : ''; ?>"
                                id="seating-field">
                                <label class="form-label">Seating Capacity</label>
                                <input type="number" name="seating_capacity" class="form-control"
                                    value="<?php echo htmlspecialchars($vehicle['seating_capacity']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Utility Type <span class="text-danger">*</span></label>
                                <select name="utility_type" class="form-select" required>
                                    <option value="Personal" <?php echo $vehicle['utility_type'] == 'Personal' ? 'selected' : ''; ?>>Personal</option>
                                    <option value="Commercial" <?php echo $vehicle['utility_type'] == 'Commercial' ? 'selected' : ''; ?>>Commercial</option>
                                    <option value="Tourism" <?php echo $vehicle['utility_type'] == 'Tourism' ? 'selected' : ''; ?>>Tourism</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pickup Location Type <span
                                        class="text-danger">*</span></label>
                                <select name="pickup_type" class="form-select" required>
                                    <option value="General" <?php echo $vehicle['pickup_type'] == 'General' ? 'selected' : ''; ?>>General / Any</option>
                                    <option value="owner_location" <?php echo $vehicle['pickup_type'] == 'owner_location' ? 'selected' : ''; ?>>Owner Location</option>
                                    <option value="doorstep_delivery" <?php echo $vehicle['pickup_type'] == 'doorstep_delivery' ? 'selected' : ''; ?>>DoorStep
                                        Delivery</option>
                                    <option value="hub_station_pickup" <?php echo $vehicle['pickup_type'] == 'hub_station_pickup' ? 'selected' : ''; ?>>Hub /
                                        Station Pickup</option>
                                    <option value="airport_pickup" <?php echo $vehicle['pickup_type'] == 'airport_pickup' ? 'selected' : ''; ?>>Airport Pickup</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="driver_available"
                                        id="driverCheck" <?php echo $vehicle['driver_available'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="driverCheck">
                                        Driver Available?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Media & Description -->
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Media & Description</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Main Image (Outside)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <?php if (!empty($vehicle['image_path'])): ?>
                                    <div class="mt-2 text-center">
                                        <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                                            class="img-thumbnail rounded shadow-sm admin-table-img">
                                        <div class="small text-muted mt-1">Current Image</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Inside View</label>
                                <input type="file" name="image2" class="form-control" accept="image/*">
                                <?php if (!empty($vehicle['image_path_2'])): ?>
                                    <div class="mt-2 text-center">
                                        <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=2"
                                            class="img-thumbnail rounded shadow-sm admin-table-img">
                                        <div class="small text-muted mt-1">Current Image</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Side View</label>
                                <input type="file" name="image3" class="form-control" accept="image/*">
                                <?php if (!empty($vehicle['image_path_3'])): ?>
                                    <div class="mt-2 text-center">
                                        <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=3"
                                            class="img-thumbnail rounded shadow-sm admin-table-img">
                                        <div class="small text-muted mt-1">Current Image</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control"
                                rows="3"><?php echo htmlspecialchars($vehicle['description']); ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=myVehicles"
                                class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Update Vehicle</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFields(type) {
        var seatingField = document.getElementById('seating-field');
        if (type === 'Bike' || type === 'Scooty') {
            seatingField.style.display = 'none';
        } else {
            seatingField.style.display = 'block';
        }
    }
</script>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>