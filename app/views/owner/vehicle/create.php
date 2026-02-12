<?php
/**
 * Add New Vehicle
 * Form interface for owners to submit a new vehicle listing for admin approval.
 */
$page_title = "Add Vehicle - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<!-- Vehicle CSS -->
<link rel="stylesheet" href="./assets/css/vehicle.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold">Add New Vehicle</h4>
                </div>
                <div class="card-body p-4">

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=store" method="POST"
                        enctype="multipart/form-data">

                        <!-- Step 1: Basic Info -->
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Basic Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                <select name="vehicle_type" class="form-select" required
                                    onchange="toggleFields(this.value)">
                                    <option value="">Select Type</option>
                                    <option value="Car">Car</option>
                                    <option value="Bike">Bike</option>
                                    <option value="Scooty">Scooty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand / Make <span class="text-danger">*</span></label>
                                <input type="text" name="brand" class="form-control" placeholder="e.g. Toyota, Honda"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control" placeholder="e.g. Corolla, Activa"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control" min="2000" max="2026" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">License Plate Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="plate_number" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price Per Hour (NPR) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="price_per_day" class="form-control" step="1" required>
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small">Vehicle Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6" id="seating-field">
                                <label class="form-label">Seating Capacity</label>
                                <input type="number" name="seating_capacity" class="form-control" placeholder="e.g. 5">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Utility Type <span class="text-danger">*</span></label>
                                <select name="utility_type" class="form-select" required>
                                    <option value="Personal">Personal</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="Tourism">Tourism</option>
                                    <option value="Marriage">Marriage / Events</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pickup Location Type <span
                                        class="text-danger">*</span></label>
                                <select name="pickup_type" class="form-select" required>
                                    <option value="General">General / Any</option>
                                    <option value="owner_location">Owner Location</option>
                                    <option value="doorstep_delivery">DoorStep Delivery</option>
                                    <option value="hub_station_pickup">Hub / Station Pickup</option>
                                    <option value="airport_pickup">Airport Pickup</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="driver_available"
                                        id="driverCheck">
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
                                <label class="form-label">Main Image (Outside) <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <div class="form-text small">Front/Main view</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Inside View (Optional)</label>
                                <input type="file" name="image2" class="form-control" accept="image/*">
                                <div class="form-text small">Dashboard/Interior</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Side View (Optional)</label>
                                <input type="file" name="image3" class="form-control" accept="image/*">
                                <div class="form-text small">Side/Rear view</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Describe your vehicle..."></textarea>
                        </div>

                        <div class="alert alert-info border-0 bg-info bg-opacity-10 small mb-4">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Note:</strong> A <strong>10% service fee</strong> is applied to every successful
                            booking.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=myVehicles"
                                class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Add Vehicle</button>
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