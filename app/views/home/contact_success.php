<?php
/**
 * Contact Success Page
 * Confirmation message displayed after a user successfully submits a contact query.
 */
require APP_PATH . '/views/layouts/navbar.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success success-icon"></i>
                    </div>
                    <h2 class="mb-3">Message Sent Successfully!</h2>
                    <p class="text-muted mb-4">Thank you for contacting us. We will get back to you as soon as possible.
                    </p>
                    <a href="<?= BASE_URL ?>" class="btn btn-primary">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>