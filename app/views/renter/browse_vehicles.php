<?php
$page_title = 'Browse Vehicles - Vehicle Rental System';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <div class="section-title">
        <h2>Available Vehicles</h2>
        <p>Find the perfect vehicle for your journey</p>
    </div>

    <!-- Filters -->
    <div class="mb-4">
        <form action="/public/index.php" method="GET" class="row g-3">
            <input type="hidden" name="page" value="vehicle">
            <input type="hidden" name="action" value="search">
            
            <div class="col-md-3">
                <select class="form-select" name="type">
                    <option value="">All Types</option>
                    <option value="car" <?php echo (isset($_GET['type']) && $_GET['type'] === 'car') ? 'selected' : ''; ?>>Car</option>
                    <option value="suv" <?php echo (isset($_GET['type']) && $_GET['type'] === 'suv') ? 'selected' : ''; ?>>SUV</option>
                    <option value="van" <?php echo (isset($_GET['type']) && $_GET['type'] === 'van') ? 'selected' : ''; ?>>Van</option>
                    <option value="bike" <?php echo (isset($_GET['type']) && $_GET['type'] === 'bike') ? 'selected' : ''; ?>>Bike</option>
                    <option value="truck" <?php echo (isset($_GET['type']) && $_GET['type'] === 'truck') ? 'selected' : ''; ?>>Truck</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="min_price" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : '0'; ?>">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : '10000'; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Vehicles Grid -->
    <div class="row">
        <?php if (!empty($vehicles)): ?>
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="vehicle-card">
                        <img src="/<?php echo !empty($vehicle['image_path']) ? $vehicle['image_path'] : 'assets/images/default-vehicle.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>">
                        <div class="vehicle-card-body">
                            <h5><?php echo htmlspecialchars($vehicle['vehicle_name']); ?></h5>
                            <span class="vehicle-type"><?php echo ucfirst($vehicle['vehicle_type']); ?></span>
                            <p class="text-muted mb-2">
                                <i class="fas fa-car"></i> <?php echo htmlspecialchars($vehicle['model']); ?> 
                                • <?php echo $vehicle['year']; ?>
                            </p>
                            <p class="text-muted mb-3">
                                <i class="fas fa-user"></i> Owner: <?php echo htmlspecialchars($vehicle['owner_name']); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">$<?php echo number_format($vehicle['price_per_day'], 2); ?><small class="text-muted">/day</small></span>
                                <a href="/public/index.php?page=vehicle&action=view&id=<?php echo $vehicle['vehicle_id']; ?>" 
                                   class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No vehicles found matching your criteria.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>