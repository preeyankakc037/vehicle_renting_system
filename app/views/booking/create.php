<?php
/**
 * Create Booking Page
 * Interface for renters to select dates and confirm a booking for a specific vehicle.
 */
$page_title = "Book Vehicle - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<!-- Booking CSS -->
<link rel="stylesheet" href="./assets/css/booking.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center position-relative">
                        <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                            class="img-fluid booking-hero-image" alt="Vehicle Image">
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-dark bg-opacity-75 text-white">
                            <h4 class="fw-bold mb-1">
                                <?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?>
                            </h4>
                            <div class="fs-5 text-warning fw-bold">NPR
                                <?php echo number_format($vehicle['price_per_day'], 0); ?> <span
                                    class="fs-6 text-white text-opacity-75">/ hour</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-5">
                            <h3 class="fw-bold mb-4">Complete Your Booking</h3>

                            <form action="<?php echo BASE_URL; ?>/index.php?page=booking&action=store" method="POST">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                <input type="hidden" name="price_per_day"
                                    value="<?php echo $vehicle['price_per_day']; ?>">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Pickup
                                            Date</label>
                                        <input type="date" name="pickup_date" class="form-control" required
                                            min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Dropoff
                                            Date</label>
                                        <input type="date" name="dropoff_date" class="form-control" required
                                            min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Pickup
                                        Location</label>
                                    <select name="pickup_location" class="form-select">
                                        <option value="General">General Pickup (Owner's Location)</option>
                                        <option value="Airport">Airport Terminal</option>
                                        <option value="City Center">City Center</option>
                                    </select>
                                </div>

                                <!-- Rental Policies Agreement -->
                                <div class="mb-4 bg-light p-3 rounded border">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms_accepted"
                                            id="termsCheck" required>
                                        <label class="form-check-label small" for="termsCheck">
                                            I have read and agree to the <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#policyModal">Rental Policies & Guidelines</a>.
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold py-3">
                                        Confirm Booking <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle"
                                        class="text-muted small text-decoration-none">Cancel and return</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rental Policies Modal -->
<div class="modal fade" id="policyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Vehicle Rental Policies & Guidelines</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold text-uppercase text-primary mb-3">1. Driver Requirements</h6>
                <ul class="small text-muted mb-4">
                    <li>Driver must be at least 21 years of age.</li>
                    <li>Must possess a valid driving license for the specific vehicle category.</li>
                    <li>The license must be at least 1 year old.</li>
                </ul>

                <h6 class="fw-bold text-uppercase text-primary mb-3">2. Fuel & Mileage</h6>
                <ul class="small text-muted mb-4">
                    <li>The vehicle is provided with a full tank of fuel and must be returned with a full tank.</li>
                    <li>Daily mileage limit is 300km. Excess mileage will be charged at NPR 15/km.</li>
                </ul>

                <h6 class="fw-bold text-uppercase text-primary mb-3">3. Security Deposit & Payments</h6>
                <ul class="small text-muted mb-4">
                    <li>A refundable security deposit of NPR 5,000 is required upon pickup.</li>
                    <li>Full rental payment must be cleared before the start of the journey.</li>
                    <li>Late returns will be charged an additional hourly rate.</li>
                </ul>

                <h6 class="fw-bold text-uppercase text-primary mb-3">4. Damage & Insurance</h6>
                <ul class="small text-muted mb-4">
                    <li>The renter is responsible for any minor damages (scratches, dents) incurred during the rental
                        period.</li>
                    <li>Major damages are covered by insurance, but the renter is liable for the insurance
                        deductible/excess.</li>
                    <li>Theft of personal belongings inside the vehicle is not covered.</li>
                </ul>

                <h6 class="fw-bold text-uppercase text-primary mb-3">5. Restrictions</h6>
                <ul class="small text-muted mb-0">
                    <li>No smoking inside the vehicle.</li>
                    <li>Pets are not allowed unless explicitly approved by the owner.</li>
                    <li>Off-roading is strictly prohibited for non-4WD vehicles.</li>
                </ul>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-primary fw-bold px-4" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>