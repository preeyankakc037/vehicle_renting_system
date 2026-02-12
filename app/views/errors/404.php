<?php
http_response_code(404);
$page_title = '404 - Page Not Found';
require APP_PATH . '/views/layouts/navbar.php';
?>

<section class="text-center py-5">
    <h1 class="display-1 mb-3">404</h1>
    <p class="lead text-muted mb-4">
        The page you're looking for doesn't exist.
    </p>

    <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary btn-lg">
        <i class="fas fa-home me-2"></i>Go Home
    </a>
</section>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>