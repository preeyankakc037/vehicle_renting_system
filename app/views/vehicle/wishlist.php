<?php
/**
 * Wishlist Page
 * Displays the user's saved vehicles for future reference.
 */
$page_title = 'My Wishlist - Pathek';
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Saved Vehicles</h2>

    <?php if (empty($wishlist_items)): ?>
        <div class="text-center py-5">
            <i class="far fa-heart fa-4x text-muted mb-3"></i>
            <h4>Your wishlist is empty</h4>
            <p class="text-muted">Save vehicles you like to view them later.</p>
            <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle" class="btn btn-primary mt-3">Browse Vehicles</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($wishlist_items as $vehicle): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm vehicle-card">
                        <div class="position-relative">
                            <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                                class="card-img-top vehicle-card-image">
                            <span class="badge bg-white text-dark position-absolute top-0 end-0 m-2 shadow-sm">
                                <?php echo htmlspecialchars($vehicle['vehicle_type']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">
                                    <?php echo htmlspecialchars($vehicle['brand']); ?>
                                </h5>
                                <h6 class="text-success fw-bold mb-0">NPR
                                    <?php echo number_format($vehicle['price_per_day'], 0); ?>/hour
                                </h6>
                            </div>
                            <p class="text-muted small mb-3">
                                <?php echo htmlspecialchars($vehicle['model']); ?>
                            </p>

                            <div class="d-grid gap-2">
                                <a href="<?php echo BASE_URL; ?>/index.php?page=booking&action=create&id=<?php echo $vehicle['vehicle_id']; ?>"
                                    class="btn btn-primary">Rent Now</a>

                                <form action="<?php echo BASE_URL; ?>/index.php?page=wishlist&action=remove" method="POST">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash-alt me-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>