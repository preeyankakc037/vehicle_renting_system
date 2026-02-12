<?php
/**
 * Login Page
 * Authenticates users and initiates a session. Includes OTP verification logic.
 */
$page_title = 'Login - Pathek';
require_once APP_PATH . '/views/layouts/navbar.php';
?>
<link rel="stylesheet" href="assets/css/auth.css">
<?php
$show_verify = isset($_GET['verify']) || (isset($_SESSION['verify_email']) && isset($_SESSION['show_verify_modal']));
// Clear the session trigger to prevent it sticking forever, but keep email
if (isset($_SESSION['show_verify_modal']))
    unset($_SESSION['show_verify_modal']);
?>

<!-- Login Modal -->
<div class="modal fade <?php echo $show_verify ? '' : 'show modal-overlay'; ?>" id="loginModal" tabindex="-1" <?php echo $show_verify ? '' : 'aria-modal="true" role="dialog"'; ?>>
    <div class="modal-dialog modal-dialog-centered modal-max-480">
        <div class="modal-content auth-modal-content">

            <!-- Modal Header -->
            <div class="modal-header border-0 pb-1 pt-3">
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn-close ms-auto"></a>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 pb-4 pt-1">
                <?php if (!$show_verify && isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger py-2 small mb-3 rounded-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (!$show_verify && isset($_SESSION['success'])): ?>
                    <div class="alert alert-success py-2 small mb-3 rounded-2">
                        <i class="fas fa-check-circle me-1"></i>
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=auth&action=login">

                    <div class="mb-2">
                        <label class="form-label text-brand-green auth-form-label">Email</label>
                        <input type="email" class="form-control form-control-sm" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-brand-green auth-form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form-control-sm pe-5" name="password"
                                id="loginPassword" required>
                            <button type="button" class="btn position-absolute end-0 top-0 h-100 auth-password-toggle"
                                onclick="togglePassword('loginPassword', this)">
                                <i class="fas fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand w-100 fw-bold py-2 mb-2 rounded-6">
                        SIGN IN
                    </button>

                    <div class="text-center mb-2">
                        <span class="auth-link-secondary" style="cursor: default; text-decoration: none;">
                            Forgot password?
                        </span>
                    </div>

                    <div class="text-center">
                        <small class="auth-footer-text">
                            Don't have an account?
                            <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=register"
                                class="fw-semibold text-decoration-none text-brand-light">
                                Create One
                            </a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Verify OTP Modal -->
<div class="modal fade <?php echo $show_verify ? 'show modal-overlay' : ''; ?>" id="verifyModal" tabindex="-1" <?php echo $show_verify ? 'aria-modal="true" role="dialog"' : ''; ?>>
    <div class="modal-dialog modal-dialog-centered modal-max-400">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header verify-modal-header border-0 justify-content-center position-relative">
                <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=login"
                    class="btn-close position-absolute end-0 top-0 m-3"></a>
                <div class="text-center">
                    <div class="verify-icon-container shadow-sm mb-2">
                        <i class="fas fa-envelope-open-text fa-lg text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Verify Email</h5>
                    <p class="text-muted small mb-0">
                        Code sent to
                        <strong><?php echo htmlspecialchars($_SESSION['verify_email'] ?? 'your email'); ?></strong>
                    </p>
                </div>
            </div>

            <div class="modal-body p-4">
                <?php if ($show_verify && isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger py-1 px-2 small rounded-2 mb-3">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['error']);
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if ($show_verify && isset($_SESSION['success'])): ?>
                    <div class="alert alert-success py-1 px-2 small rounded-2 mb-3">
                        <i class="fas fa-check-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['success']);
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/index.php?page=auth&action=verifyOtp" method="POST">
                    <div class="mb-3">
                        <input type="text" name="otp_code" class="form-control text-center fw-bold otp-input"
                            placeholder="0 0 0 0 0 0" maxlength="6" required pattern="[0-9]{6}">
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-3 mb-3">
                        Verify Now
                    </button>
                </form>

                <div class="text-center">
                    <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=resendOtp"
                        class="text-decoration-none small text-muted hover-text-success">
                        <i class="fas fa-redo-alt me-1"></i> Resend Code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, button) {
        var input = document.getElementById(inputId);
        var icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>