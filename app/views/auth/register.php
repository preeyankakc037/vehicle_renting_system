<?php
/**
 * User Registration Page
 * Form to create a new user account (Renter or Owner).
 */
$page_title = 'Sign Up - Pathek';
require_once APP_PATH . '/views/layouts/navbar.php';
?>
<link rel="stylesheet" href="assets/css/auth.css">
<?php ?>

<!-- Register Modal -->
<div class="modal fade show modal-overlay" id="registerModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-max-480">
        <div class="modal-content auth-modal-content">

            <!-- Modal Header -->
            <div class="modal-header border-0 pb-1 pt-3">
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn-close ms-auto"></a>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 pb-4 pt-1">
                <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=auth&action=register"
                    id="registerForm">

                    <!-- Full Name (Split) -->
                    <div class="mb-2">
                        <label class="form-label text-brand-green auth-form-label">Full Name</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" name="first_name"
                                    placeholder="first name" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" name="last_name"
                                    placeholder="last name" required>
                            </div>
                        </div>
                        <input type="hidden" name="full_name" id="full_name">
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-2">
                        <label class="form-label text-brand-green auth-form-label">Phone Number</label>
                        <input type="tel" class="form-control form-control-sm" name="phone" id="phoneInput" required>
                        <small id="phoneError" class="text-danger d-none">Phone number already registered</small>
                    </div>

                    <!-- Email -->
                    <div class="mb-2">
                        <label class="form-label text-brand-green auth-form-label">Email</label>
                        <input type="email" class="form-control form-control-sm" name="email" id="emailInput" required>
                        <small id="emailError" class="text-danger d-none">Email already registered</small>
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label class="form-label text-brand-green auth-form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form-control-sm pe-5" name="password"
                                id="registerPassword" required>
                            <button type="button" class="btn position-absolute end-0 top-0 h-100 auth-password-toggle"
                                onclick="togglePassword('registerPassword', this)">
                                <i class="fas fa-eye text-muted"></i>
                            </button>
                        </div>
                        <small id="passwordError" class="text-danger d-none">Password must be at least 8 characters with
                            uppercase, lowercase, number, and special character</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label text-brand-green auth-form-label">Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form-control-sm pe-5" name="confirm_password"
                                id="confirmPassword" required>
                            <button type="button" class="btn position-absolute end-0 top-0 h-100 auth-password-toggle"
                                onclick="togglePassword('confirmPassword', this)">
                                <i class="fas fa-eye text-muted"></i>
                            </button>
                        </div>
                        <small id="passwordMatch" class="text-danger d-none">Passwords do not match</small>
                    </div>

                    <!-- Signup As (Role Selection) -->
                    <div class="mb-3">
                        <label class="form-label text-brand-green auth-form-label">Signup As</label>
                        <select class="form-select form-select-sm" name="user_role" id="userRole" required>
                            <option value="renter">Renter</option>
                            <option value="owner_both">Owner/Both</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-brand w-100 fw-bold py-2 mb-2 rounded-6">
                        REGISTER
                    </button>

                    <div class="text-center">
                        <small class="auth-footer-text">
                            Already have an account?
                            <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=login"
                                class="fw-semibold text-decoration-none text-brand-light">
                                Sign In
                            </a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</style>

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

    // Checks if email already exists
    var emailCheckTimeout;
    document.getElementById('emailInput').addEventListener('blur', function () {
        var email = this.value;
        if (!email) return;

        clearTimeout(emailCheckTimeout);
        emailCheckTimeout = setTimeout(function () {
            fetch('<?php echo BASE_URL; ?>/index.php?page=auth&action=checkEmail', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    var errorElement = document.getElementById('emailError');
                    if (data.exists) {
                        errorElement.classList.remove('d-none');
                    } else {
                        errorElement.classList.add('d-none');
                    }
                });
        }, 500);
    });

    // Checks if phone already exists
    var phoneCheckTimeout;
    document.getElementById('phoneInput').addEventListener('blur', function () {
        var phone = this.value;
        if (!phone) return;

        clearTimeout(phoneCheckTimeout);
        phoneCheckTimeout = setTimeout(function () {
            fetch('<?php echo BASE_URL; ?>/index.php?page=auth&action=checkPhone', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'phone=' + encodeURIComponent(phone)
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    var errorElement = document.getElementById('phoneError');
                    if (data.exists) {
                        errorElement.classList.remove('d-none');
                    } else {
                        errorElement.classList.add('d-none');
                    }
                });
        }, 500);
    });

    // Password match validation
    document.getElementById('confirmPassword').addEventListener('input', function () {
        var password = document.getElementById('registerPassword').value;
        var confirmPassword = this.value;
        var matchElement = document.getElementById('passwordMatch');

        if (confirmPassword && password !== confirmPassword) {
            matchElement.classList.remove('d-none');
        } else {
            matchElement.classList.add('d-none');
        }
    });

    // Form validation on submit
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        var firstName = document.querySelector('input[name="first_name"]').value;
        var lastName = document.querySelector('input[name="last_name"]').value;
        document.getElementById('full_name').value = firstName + ' ' + lastName;

        var password = document.getElementById('registerPassword').value;
        var confirmPassword = document.getElementById('confirmPassword').value;
        var passwordError = document.getElementById('passwordError');

        // Check password match
        if (password !== confirmPassword) {
            e.preventDefault();
            document.getElementById('passwordMatch').classList.remove('d-none');
            return false;
        }

        // Validate password strength (hidden validation)
        var isStrong = password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[a-z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[!@#$%^&*]/.test(password);

        if (!isStrong) {
            e.preventDefault();
            passwordError.classList.remove('d-none');
            return false;
        } else {
            passwordError.classList.add('d-none');
        }

        // Check for duplicate email/phone errors
        if (!document.getElementById('emailError').classList.contains('d-none') ||
            !document.getElementById('phoneError').classList.contains('d-none')) {
            e.preventDefault();
            return false;
        }
    });
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>