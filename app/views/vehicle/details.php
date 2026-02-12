<?php
/**
 * Vehicle Details Page
 * Shows comprehensive information about a specific vehicle including images, features, and reviews.
 * Also handles the booking initiation.
 */
$page_title = $vehicle['model'] . ' (' . $vehicle['brand'] . ") - Pathek";
require APP_PATH . '/views/layouts/navbar.php';
?>

<!-- Vehicle CSS -->
<link rel="stylesheet" href="./assets/css/vehicle.css">

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/index.php?page=vehicle"
                    class="text-decoration-none">Browse</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo htmlspecialchars($vehicle['model'] . ' [' . $vehicle['brand'] . ']'); ?>
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Left Column: Images -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=1"
                                class="d-block w-100 carousel-image" alt="Main View">
                        </div>
                        <?php if (!empty($vehicle['image_path_2'])): ?>
                            <div class="carousel-item">
                                <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=2"
                                    class="d-block w-100 carousel-image" alt="Inside View">
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($vehicle['image_path_3'])): ?>
                            <div class="carousel-item">
                                <img src="<?php echo BASE_URL; ?>/index.php?page=image&id=<?php echo $vehicle['vehicle_id']; ?>&slot=3"
                                    class="d-block w-100 carousel-image" alt="Side View">
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($vehicle['image_path_2']) || !empty($vehicle['image_path_3'])): ?>
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
                    <?php endif; ?>
                </div>
            </div>

            <!-- Description & Specs -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">About this Vehicle</h4>
                    <p class="text-muted">
                        <?php echo nl2br(htmlspecialchars($vehicle['description'])); ?>
                    </p>

                    <hr>

                    <h5 class="fw-bold mb-3">Specifications</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-car fa-lg me-2"></i>
                                <div>
                                    <small class="d-block text-uppercase spec-label">Model</small>
                                    <span class="fw-bold text-dark">
                                        <?php echo htmlspecialchars($vehicle['model']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-calendar-alt fa-lg me-2"></i>
                                <div>
                                    <small class="d-block text-uppercase spec-label">Year</small>
                                    <span class="fw-bold text-dark">
                                        <?php echo htmlspecialchars($vehicle['year']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center text-muted">
                                <img src="<?php echo BASE_URL; ?>/assets/images/seat.png" class="seat-icon-large me-2"
                                    alt="Seats">
                                <div>
                                    <small class="d-block text-uppercase spec-label">Seats</small>
                                    <span class="fw-bold text-dark">
                                        <?php echo htmlspecialchars($vehicle['seating_capacity'] ?? 'N/A'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-steering-wheel fa-lg me-2"></i>
                                <div>
                                    <small class="d-block text-uppercase spec-label">Driver</small>
                                    <span class="fw-bold text-dark">
                                        <?php echo $vehicle['driver_available'] ? 'Included' : 'Self-Drive'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Customer Reviews</h4>
                        <span class="badge bg-light text-dark border"><?php echo count($reviews); ?> Reviews</span>
                    </div>

                    <!-- Review Form -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="mb-4 p-3 bg-light rounded border">
                            <h6 class="fw-bold mb-3">Write a Review</h6>
                            <form action="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=submitReview" method="POST">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Rating</label>
                                    <div class="rating-input">
                                        <select name="rating" class="form-select w-auto rating-select" required>
                                            <option value="5">★★★★★ (5/5)</option>
                                            <option value="4">★★★★☆ (4/5)</option>
                                            <option value="3">★★★☆☆ (3/5)</option>
                                            <option value="2">★★☆☆☆ (2/5)</option>
                                            <option value="1">★☆☆☆☆ (1/5)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="2"
                                        placeholder="Share your experience..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Post Review</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Reviews List -->
                    <?php if (empty($reviews)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="far fa-comment-dots fa-2x mb-2"></i>
                            <p>No reviews yet. Be the first to rent and review!</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($reviews as $review): ?>
                                <div class="d-flex border-bottom pb-3">
                                    <div class="bg-light rounded-circle p-2 text-center me-3 reviewer-avatar">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($review['renter_name']); ?>
                                                </h6>
                                                <div class="text-warning small">
                                                    <?php for ($i = 0; $i < $review['rating']; $i++)
                                                        echo '★'; ?>
                                                    <?php for ($i = $review['rating']; $i < 5; $i++)
                                                        echo '☆'; ?>
                                                </div>
                                            </div>
                                            <small class="text-muted review-date">
                                                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                            </small>
                                        </div>
                                        <p class="mt-2 mb-0 text-muted small">
                                            <?php echo htmlspecialchars($review['comment']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Booking & Owner Info -->
        <div class="col-lg-4">
            <!-- Price Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="fw-bold text-success mb-0">NPR
                        <?php echo number_format($vehicle['price_per_day'], 0); ?>
                    </h2>
                    <small class="text-muted">per hour</small>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>/index.php?page=booking&action=create&id=<?php echo $vehicle['vehicle_id']; ?>"
                            class="btn btn-success btn-lg fw-bold shadow-sm">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- Owner Info Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Owner Contact</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-3 me-3 text-secondary">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div>
                            <div class="fw-bold">
                                <?php echo htmlspecialchars($vehicle['owner_name']); ?>
                            </div>
                            <small class="text-success"><i class="fas fa-certificate me-1"></i>Verified Owner</small>
                        </div>
                    </div>

                    <div class="alert alert-light border small">
                        <i class="fas fa-phone-alt me-2 text-primary"></i>
                        <strong>
                            <?php echo htmlspecialchars($vehicle['owner_phone']); ?>
                        </strong>
                        <div class="text-muted mt-1">Call for queries before booking.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>