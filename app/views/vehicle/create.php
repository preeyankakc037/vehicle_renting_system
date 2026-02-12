<?php
/**
 * Add Vehicle Page
 * Form for owners to list a new vehicle with details and images.
 */
$page_title = "List Your Vehicle";
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">List Your Vehicle</h2>

    <form method="POST" action="<?= BASE_URL ?>/index.php?page=vehicle&action=store">

        <div class="mb-3">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="vehicle_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price Per Day (NPR)</label>
            <input type="number" name="price_per_day" class="form-control" required>
        </div>

        <button class="btn btn-success px-4">Submit Vehicle</button>
    </form>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>