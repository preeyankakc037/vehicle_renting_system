<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">
    <div class="hero-left">
        <h1>Car Rental Service</h1>
        <p>Rent vehicles easily, safely, and affordably.</p>

        <div class="hero-buttons">
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=login" class="btn-primary">
            Book Now
        </a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=index" class="btn-primary">
            Book Now
        </a>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle&action=index" class="btn-outline">
        Explore Vehicles
    </a>
</div>
    </div>

    <div class="hero-right">
        <img src="/vehicle_rental_system/assets/images/car.png" alt="Car">
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
