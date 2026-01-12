<?php
/**
 * Authentication Controller
 */

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $result = $this->userModel->login($email, $password);

            if ($result['success']) {
                // ✅ Set all session variables including is_host
                $_SESSION['user_id']    = $result['user']['user_id'];
                $_SESSION['user_role']  = $result['user']['user_role']; // renter / owner / admin
                $_SESSION['user_name']  = $result['user']['full_name'];
                $_SESSION['user_email'] = $result['user']['email'];
                $_SESSION['is_host']    = $result['user']['is_host'];

                $_SESSION['success'] = "Welcome back, " . $result['user']['full_name'];

                $this->redirectToDashboard();
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
                exit;
            }
        } else {
            $page_title = 'Login - Vehicle Rental System';
            require_once APP_PATH . '/views/auth/login.php';
        }
    }

    /**
     * Show registration form
     */
    public function register() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'user_role' => $_POST['user_role']
            ];

            // Validate password confirmation
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'Passwords do not match';
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=register');
                exit;
            }

            $result = $this->userModel->register($data);

            if ($result['success']) {
                // ✅ Automatically log in the user after registration
                $_SESSION['user_id']    = $result['user']['user_id'];
                $_SESSION['user_role']  = $result['user']['user_role'];
                $_SESSION['user_name']  = $result['user']['full_name'];
                $_SESSION['user_email'] = $result['user']['email'];
                $_SESSION['is_host']    = $result['user']['is_host'];

                $_SESSION['success'] = "Account created successfully. Welcome, " . $result['user']['full_name'];

                $this->redirectToDashboard();
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=auth&action=register');
                exit;
            }
        } else {
            $page_title = 'Register - Vehicle Rental System';
            require_once APP_PATH . '/views/auth/register.php';
        }
    }

    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    /**
     * Redirect to appropriate dashboard
     */
    private function redirectToDashboard() {
        $role = $_SESSION['user_role'];
        $base = BASE_URL . '/index.php';
        
        switch ($role) {
            case 'admin':
                header('Location: ' . $base . '?page=admin&action=dashboard');
                break;
            case 'owner':
                header('Location: ' . $base . '?page=vehicle&action=ownerDashboard');
                break;
            case 'renter':
                header('Location: ' . $base . '?page=booking&action=renterDashboard');
                break;
            default:
                header('Location: ' . $base);
                break;
        }
        exit;
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check user role
     */
    public static function checkRole($required_role) {
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

    /**
     * Require login
     */
    public static function requireLogin($message = 'You are not logged in. Please log in first.') {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = $message;
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
            exit;
        }
    }
}
