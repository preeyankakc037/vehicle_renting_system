<?php
/**
 * AuthController.php
 * Manages user registration, login, logout, and profile updates.
 * Also handles OTP generation and verification.
 */
/**
 * Authentication Controller 
 */

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Renders the user registration interface.
     */
    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role_input = $_POST['user_role'] ?? 'renter';
            $user_role = ($role_input === 'owner_both' || $role_input === 'owner' || $role_input === 'both') ? 'owner_pending' : 'renter';

            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'phone' => $_POST['phone'],
                'user_role' => $user_role
            ];

            // Enforce RFC-compliant email validation for platform security.
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Please enter a valid email address.';
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=register');
                exit;
            }

            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'Passwords do not match';
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=register');
                exit;
            }

            $_SESSION['pending_registration'] = $data;

            // Security: Generate a 6-digit numeric verification code for email authentication.
            $otp = rand(100000, 999999);
            $result = $this->userModel->register($data);

            if ($result['success']) {
                $user_id = $result['user_id'];
                $this->userModel->setOtp($user_id, $otp);

                $_SESSION['verify_user_id'] = $user_id;
                $_SESSION['verify_email'] = $_POST['email'];

                // Dispatch the verification token via the pre-configured SMTP helper.
                require_once APP_PATH . '/helpers/EasyMailer.php';
                $mailer = new EasyMailer();
                $mail_result = $mailer->sendOTP($_POST['email'], $otp);

                if ($mail_result['success']) {
                    $_SESSION['show_verify_modal'] = true;
                    $_SESSION['success'] = "Confirmation: OTP is on its way to your email!";
                } else {
                    $_SESSION['show_verify_modal'] = true;
                    $_SESSION['warning'] = "OTP: $otp (Email error: " . $mail_result['error'] . ")";
                }

                header('Location: ' . BASE_URL . '/index.php?page=auth&action=login&verify=true');
                exit;
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=register');
                exit;
            }
        } else {
            $page_title = 'Register - Pathek';
            require_once APP_PATH . '/views/auth/register.php';
        }
    }

    public function verifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entered_otp = $_POST['otp_code'];

            if (isset($_SESSION['pending_registration'])) {
                // Cleanup stale registration data and force re-registration
                unset($_SESSION['pending_registration']);
                $_SESSION['show_verify_modal'] = true;
                $_SESSION['error'] = "Session expired. Please register again.";
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=register');
                exit;
            }

            if (!isset($_SESSION['verify_user_id'])) {
                $_SESSION['show_verify_modal'] = true;
                $_SESSION['error'] = "OTP expired or invalid. Request a new one.";
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=login&verify=true');
                exit;
            }

            $user_id = $_SESSION['verify_user_id'];

            if ($this->userModel->verifyOtp($user_id, $entered_otp)) {
                $this->userModel->markEmailVerified($user_id);

                unset($_SESSION['verify_email']);
                unset($_SESSION['verify_user_id']);
                unset($_SESSION['show_verify_modal']);

                $_SESSION['success'] = "Email verified successfully. You can now log in!";
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
                exit;
            } else {
                $_SESSION['show_verify_modal'] = true;
                $_SESSION['error'] = "Incorrect OTP.";
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=login&verify=true');
                exit;
            }
        }
    }

    public function resendOtp()
    {
        if (isset($_SESSION['verify_user_id'])) {
            $user_id = $_SESSION['verify_user_id'];

            $otp = rand(100000, 999999);
            $this->userModel->setOtp($user_id, $otp);

            // ASYNC SENDING
            require_once APP_PATH . '/helpers/EasyMailer.php';
            $mailer = new EasyMailer();

            // Retrieve the user profile to obtain the recipient's email address.
            $user = $this->userModel->getUserById($user_id);
            $email = $user['email'];

            $result = $mailer->sendOTP($email, $otp);

            $_SESSION['show_verify_modal'] = true;
            if ($result['success']) {
                $_SESSION['success'] = "Confirmation: A new OTP is being sent!";
            } else {
                $_SESSION['warning'] = "New OTP: $otp";
            }

            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login&verify=true');
        } else {
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
        }
        exit;
    }


    /**
     * Renders the secure portal for user authentication.
     */
    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $result = $this->userModel->login($email, $password);

            if ($result['success']) {
                if ($result['user']['is_email_verified'] == 0) {
                    // Validate email format before sending OTP
                    if (!filter_var($result['user']['email'], FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['error'] = 'Your email address is invalid. Please contact support.';
                        header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
                        exit;
                    }

                    $_SESSION['verify_email'] = $result['user']['email'];
                    $_SESSION['verify_user_id'] = $result['user']['user_id'];

                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['otp_expiry'] = time() + 600;

                    // ASYNC SENDING
                    require_once APP_PATH . '/helpers/EasyMailer.php';
                    $mailer = new EasyMailer();
                    $mailer->sendOTP($result['user']['email'], $otp);

                    $_SESSION['show_verify_modal'] = true;
                    $_SESSION['error'] = "Please verify your email to continue.";
                    header('Location: ' . BASE_URL . '/index.php?page=auth&action=login&verify=true');
                    exit;
                }

                $_SESSION['user_id'] = $result['user']['user_id'];
                $_SESSION['user_role'] = $result['user']['user_role'];
                $_SESSION['user_name'] = $result['user']['full_name'];
                $_SESSION['user_email'] = $result['user']['email'];
                $_SESSION['is_host'] = $result['user']['is_host'];

                $_SESSION['success'] = "Welcome back, " . $result['user']['full_name'];

                $this->redirectToDashboard();
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
                exit;
            }
        } else {
            $page_title = 'Login - Pathek';
            require_once APP_PATH . '/views/auth/login.php';
        }
    }

    public function profile()
    {
        self::requireLogin();
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];

        if ($role === 'admin') {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=editAdmin&id=" . $user_id);
            exit;
        }

        // Both owners and renters can see the profile page
        $userModel = new User();
        $user = $userModel->getUserById($user_id);
        $page_title = "My Profile - Pathek";
        require APP_PATH . '/views/auth/profile.php';
        exit;
    }

    public function changePassword()
    {
        self::requireLogin();
        $page_title = "Change Password - Pathek";
        require APP_PATH . '/views/auth/change_password.php';
    }

    public function updatePassword()
    {
        self::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = "New passwords do not match.";
                header("Location: " . BASE_URL . "/index.php?page=auth&action=profile");
                exit;
            }

            if (!$this->userModel->verifyPassword($user_id, $current_password)) {
                $_SESSION['error'] = "Incorrect current password.";
                header("Location: " . BASE_URL . "/index.php?page=auth&action=profile");
                exit;
            }

            if ($this->userModel->updatePassword($user_id, $new_password)) {
                $_SESSION['success'] = "Password updated successfully. Please login again.";
                session_destroy();
                header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            } else {
                $_SESSION['error'] = "Failed to update password.";
                header("Location: " . BASE_URL . "/index.php?page=auth&action=profile");
            }
        }
    }

    public function updateProfile()
    {
        self::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $data = [
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'] ?? ''
            ];

            if ($this->userModel->updateProfile($user_id, $data)) {
                $_SESSION['success'] = "Profile updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }

            header("Location: " . BASE_URL . "/index.php?page=auth&action=profile");
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    private function redirectToDashboard()
    {
        $role = $_SESSION['user_role'] ?? '';
        $base = BASE_URL . '/index.php';

        switch ($role) {
            case 'admin':
                header('Location: ' . $base . '?page=admin&action=dashboard');
                break;
            case 'owner_verified':
                header('Location: ' . $base . '?page=owner&action=dashboard');
                break;
            case 'owner_pending':
                header('Location: ' . $base . '?page=verification');
                break;
            case 'renter':
                header('Location: ' . $base . '?page=booking&action=myBookings');
                break;
            default:
                header('Location: ' . $base);
                break;
        }
        exit;
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function checkRole($required_role)
    {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = 'You are not logged in. Please log in first.';
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
            exit;
        }

        if ($_SESSION['user_role'] !== $required_role) {
            $_SESSION['error'] = 'Access denied';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    public function checkEmail()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
            $conn = $this->userModel->getConnection();
            $email = $conn->real_escape_string($_POST['email']);
            $query = "SELECT user_id FROM users WHERE email = '$email'";
            $result = $conn->query($query);

            echo json_encode(['exists' => $result->num_rows > 0]);
        } else {
            echo json_encode(['exists' => false]);
        }
        exit;
    }

    public function checkPhone()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['phone'])) {
            $conn = $this->userModel->getConnection();
            $phone = $conn->real_escape_string($_POST['phone']);
            $query = "SELECT user_id FROM users WHERE phone = '$phone'";
            $result = $conn->query($query);

            echo json_encode(['exists' => $result->num_rows > 0]);
        } else {
            echo json_encode(['exists' => false]);
        }
        exit;
    }

    public static function requireLogin($message = 'You are not logged in. Please log in first.')
    {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = $message;
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
            exit;
        }
    }
}