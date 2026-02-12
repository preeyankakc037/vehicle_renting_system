<?php
/**
 * Search Results Page
 * Displays the list of available vehicles with filtering options (Type, Price, etc.).
 */
$page_title = "Browse Vehicles - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<!-- Vehicle CSS -->
<link rel="stylesheet" href="./assets/css/vehicle.css">

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-filter me-2"></i>Filter Vehicles</h5>
                    <form action="<?php echo BASE_URL; ?>/index.php" method="GET">
                        <input type="hidden" name="page" value="vehicle">

                        <!-- Vehicle Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Vehicle Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="Car" <?php echo ($filters['type'] ?? '') === 'Car' ? 'selected' : ''; ?>>
                                    Car</option>
                                <option value="Bike" <?php echo ($filters['type'] ?? '') === 'Bike' ? 'selected' : ''; ?>>
                                    Bike</option>
                                <option value="Scooty" <?php echo ($filters['type'] ?? '') === 'Scooty' ? 'selected' : ''; ?>>Scooty</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Price Range (per hour)</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" class="form-control" placeholder="Min"
                                    value="<?php echo htmlspecialchars($filters['min_price'] ?? ''); ?>">
                                <input type="number" name="max_price" class="form-control" placeholder="Max"
                                    value="<?php echo htmlspecialchars($filters['max_price'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Driver Availability -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Driver Option</label>
                            <select name="driver" class="form-select">
                                <option value="">Any</option>
                                <option value="1" <?php echo ($filters['driver'] ?? '') === '1' ? 'selected' : ''; ?>>With
                                    Driver</option>
                                <option value="0" <?php echo ($filters['driver'] ?? '') === '0' ? 'selected' : ''; ?>>Self
                                    Drive</option>
                            </select>
                        </div>


                        <!-- Pickup Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Pickup Location</label>
                            <select name="pickup" class="form-select">
                                <option value="">Any</option>
                                <option value="Airport" <?php echo ($filters['pickup'] ?? '') === 'Airport' ? 'selected' : ''; ?>>Airport</option>
                                <option value="Domestic Tour" <?php echo ($filters['pickup'] ?? '') === 'Domestic Tour' ? 'selected' : ''; ?>>Domestic Tour</option>
                                <option value="City Use" <?php echo ($filters['pickup'] ?? '') === 'City Use' ? 'selected' : ''; ?>>City Use</option>
                            </select>
                        </div>

                        <!-- Seating (Only specific to cars usually, but filter for all) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Min Seats</label>
                            <select name="seating" class="form-select">
                                <option value="">Any</option>
                                <option value="2" <?php echo ($filters['seating'] ?? '') === '2' ? 'selected' : ''; ?>>2+
                                </option>
                                <option value="4" <?php echo ($filters['seating'] ?? '') === '4' ? 'selected' : ''; ?>>4+
                                </option>
                                <option value="5" <?php echo ($filters['seating'] ?? '') === '5' ? 'selected' : ''; ?>>5+
                                </option>
                                <option value="7" <?php echo ($filters['seating'] ?? '') === '7' ? 'selected' : ''; ?>>7+
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-apply-filters w-100 fw-bold">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Vehicle Grid -->
        <div class="col-lg-9 ps-lg-5">
            <h4 class="mb-4 fw-bold">Available Vehicles</h4>

            <?php if (empty($vehicles)): ?>
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                    <h5>No vehicles available</h5>
                    <p class="mb-0">Try adjusting your filters to see more results.</p>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm vehicle-card vehicle-card-style">
                                <div class="position-relative">
                                    <a
                                        href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=details&id=<?php echo $vehicle['vehicle_id']; ?>">
                                        <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                                            class="card-img-top vehicle-card-image"
                                            alt="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>">
                                    </a>
                                    <span class="badge bg-white text-dark position-absolute top-0 end-0 m-2 shadow-sm">
                                        <?php echo htmlspecialchars($vehicle['vehicle_type']); ?>
                                    </span>

                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <form action="<?php echo BASE_URL; ?>/index.php?page=wishlist&action=add" method="POST"
                                            class="position-absolute top-0 start-0 m-2">
                                            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                            <button type="submit"
                                                class="btn btn-white btn-sm rounded-circle shadow-sm border-0 text-danger"
                                                title="Add to Wishlist">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title fw-bold mb-0">
                                            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=details&id=<?php echo $vehicle['vehicle_id']; ?>"
                                                class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($vehicle['model']); ?>
                                            </a>
                                        </h5>
                                        <h6 class="text-success fw-bold mb-0">
                                            NPR <?php echo number_format($vehicle['price_per_day'], 0); ?>
                                            <span class="text-muted small price-per-unit">/hour</span>
                                        </h6>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        <?php echo htmlspecialchars($vehicle['brand']); ?> •
                                        <?php echo htmlspecialchars($vehicle['year']); ?>
                                    </p>

                                    <!-- Features Pills -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <?php if ($vehicle['driver_available']): ?>
                                            <span class="badge bg-light text-dark border"><i
                                                    class="fas fa-user-tie me-1"></i>Driver</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border"><i
                                                    class="fas fa-steering-wheel me-1"></i>Self-Drive</span>
                                        <?php endif; ?>

                                        <?php if ($vehicle['seating_capacity']): ?>
                                            <span class="badge bg-light text-dark border d-flex align-items-center gap-1">
                                                <img src="<?php echo BASE_URL; ?>/assets/images/seat.png" class="seat-icon"
                                                    alt="Seats">
                                                <?php echo $vehicle['seating_capacity']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <div class="btn-group shadow-sm">
                                            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=details&id=<?php echo $vehicle['vehicle_id']; ?>"
                                                class="btn btn-outline-secondary fw-bold">
                                                View
                                            </a>
                                            <?php if ($vehicle['availability_status'] === 'available'): ?>
                                                <a href="<?php echo BASE_URL; ?>/index.php?page=booking&action=create&id=<?php echo $vehicle['vehicle_id']; ?>"
                                                    class="btn btn-success fw-bold">
                                                    Book Now
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary fw-bold" disabled>
                                                    <?php echo $vehicle['availability_status'] === 'maintenance' ? 'Busy' : 'Unavailable'; ?>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 text-muted small">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo htmlspecialchars($vehicle['pickup_type']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>