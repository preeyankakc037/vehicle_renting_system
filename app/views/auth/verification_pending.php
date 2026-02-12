<?php
/**
 * Account Verification Pending
 * Info page displayed to owners awaiting admin approval of their documents.
 */
$page_title = 'Verification Pending - Pathek';
require APP_PATH . '/views/layouts/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="card border-0 shadow-sm py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Verification Pending</h2>
                    <p class="text-muted lead mb-4">
                        Your application to become an owner is currently under review.
                    </p>
                    <div class="alert alert-secondary d-inline-block text-start">
                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-warning text-dark">Pending
                                Review</span></p>
                        <p class="mb-0 small">Please check back later or contact support if it takes longer than 24
                            hours.</p>
                    </div>

                    <div class="mt-5">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-primary">Return to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>