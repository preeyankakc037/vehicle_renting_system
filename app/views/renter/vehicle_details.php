<?php
$page_title = $vehicle['vehicle_name'] . ' - Vehicle Rental System';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="/<?php echo !empty($vehicle['image_path']) ? $vehicle['image_path'] : 'assets/images/default-vehicle.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>"
                 class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h1><?php echo htmlspecialchars($vehicle['vehicle_name']); ?></h1>
            <span class="vehicle-type"><?php echo ucfirst($vehicle['vehicle_type']); ?></span>
            
            <div class="mt-3">
                <h3 class="price-tag">$<?php echo number_format($vehicle['price_per_day'], 2); ?><small class="text-muted">/day</small></h3>
            </div>

            <div class="mt-4">
                <h5>Vehicle Details</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-car text-primary"></i> <strong>Model:</strong> <?php echo htmlspecialchars($vehicle['model']); ?></li>
                    <li><i class="fas fa-calendar text-primary"></i> <strong>Year:</strong> <?php echo $vehicle['year']; ?></li>
                    <li><i class="fas fa-id-card text-primary"></i> <strong>Plate Number:</strong> <?php echo htmlspecialchars($vehicle['plate_number']); ?></li>
                    <li><i class="fas fa-user text-primary"></i> <strong>Owner:</strong> <?php echo htmlspecialchars($vehicle['owner_name']); ?></li>
                    <li><i class="fas fa-phone text-primary"></i> <strong>Contact:</strong> <?php echo htmlspecialchars($vehicle['owner_phone']); ?></li>
                </ul>
            </div>

            <div class="mt-4">
                <h5>Description</h5>
                <p><?php echo nl2br(htmlspecialchars($vehicle['description'])); ?></p>
            </div>

            <?php if ($vehicle['availability_status'] === 'available'): ?>
                <div class="mt-4">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'renter'): ?>
                        <a href="/public/index.php?page=booking&action=create&vehicle_id=<?php echo $vehicle['vehicle_id']; ?>" 
                           class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-calendar-check"></i> Book This Vehicle
                        </a>
                    <?php else: ?>
                        <a href="/public/index.php?page=auth&action=login" 
                           class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt"></i> Login to Book
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle"></i> This vehicle is currently not available
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reviews Section -->
    <?php if (!empty($feedback_list)): ?>
        <div class="mt-5">
            <h3>Customer Reviews</h3>
            <?php if ($rating_info['total_reviews'] > 0): ?>
                <div class="mb-3">
                    <span class="h4">
                        <?php 
                        $avg_rating = round($rating_info['avg_rating'], 1);
                        for ($i = 1; $i <= 5; $i++): 
                            echo $i <= $avg_rating ? '⭐' : '☆';
                        endfor;
                        ?>
                    </span>
                    <span class="ms-2 text-muted"><?php echo $avg_rating; ?> out of 5 (<?php echo $rating_info['total_reviews']; ?> reviews)</span>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php foreach ($feedback_list as $feedback): ?>
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6><?php echo htmlspecialchars($feedback['renter_name']); ?></h6>
                                <div class="text-warning mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): 
                                        echo $i <= $feedback['rating'] ? '⭐' : '☆';
                                    endfor; ?>
                                </div>
                                <p class="mb-1"><?php echo htmlspecialchars($feedback['comment']); ?></p>
                                <small class="text-muted">
                                    Rented: <?php echo date('M d, Y', strtotime($feedback['pickup_date'])); ?> - 
                                    <?php echo date('M d, Y', strtotime($feedback['dropoff_date'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>