<?php
/**
 * Front Controller - Entry Point
 * All requests start here
 */
// die(); // Temporary check

// 1. Start session FIRST
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Define path constants
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH);
define('RESOURCES_PATH', BASE_PATH . '/resources');

// 3. Define BASE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . "://" . $host . $script);


// 4. Load database configuration
require_once CONFIG_PATH . '/db.php'; 

// 5. Autoloader for controllers & models
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// 6. Routing parameters
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// 7. Routing logic
try {
    // Exclusive Redirection for Admins: Prevent access to public pages
    if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
        $adminAllowedPages = ['admin', 'auth', 'image'];
        if (!in_array($page, $adminAllowedPages)) {
            header('Location: ' . BASE_URL . '/index.php?page=admin&action=dashboard');
            exit;
        }
    }

    switch ($page) {

        case 'home':
            $controller = new HomeController();
            $controller->index();
            break;

        case 'auth':
            $controller = new AuthController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->login();
            break;

        case 'booking':
            $controller = new BookingController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->renterDashboard();
            break;

        case 'admin':
            $controller = new AdminController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->dashboard();
            break;

        case 'contact':
            $controller = new ContactController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->index();
            break;

        case 'vehicle':
            $ownerActions = ['myVehicles', 'create', 'store', 'edit', 'update', 'delete', 'updateStatus'];

            if (in_array($action, $ownerActions)) {
                require_once APP_PATH . '/controllers/OwnerVehicleController.php';
                $controller = new OwnerVehicleController();
            } else {
                $controller = new BrowseController();
            }

            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->index();
            break;

        case 'verification':
            require_once APP_PATH . '/controllers/VerificationController.php';
            $controller = new VerificationController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->index();
            break;

        case 'owner':
            $controller = new OwnerController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->dashboard();
            break;

        case 'wishlist':
            require_once APP_PATH . '/controllers/WishlistController.php';
            $controller = new WishlistController();
            method_exists($controller, $action)
                ? $controller->$action()
                : $controller->index();
            break;



        case 'image':
            $controller = new ImageController();
            $controller->serve();
            break;

        default:
            http_response_code(404);
            require APP_PATH . '/views/errors/404.php';
            break;
    }

} catch (Throwable $e) {
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Error - Vehicle Rental System</title>
        <link rel="stylesheet" href="./assets/css/base.css">
    </head>

    <body class="error-page-body">
        <div class="error-container">
            <h1 class="error-title">⚠️ Application Error</h1>
            <p>Something unexpected happened.</p>
            <div class="error-details">
                <strong>Error:</strong> <?= htmlspecialchars($e->getMessage()) ?><br>
                <strong>File:</strong> <?= htmlspecialchars($e->getFile()) ?><br>
                <strong>Line:</strong> <?= $e->getLine() ?>
            </div>

            <a href="<?= BASE_URL ?>/index.php" class="error-btn">Go to Homepage</a>
        </div>
    </body>

    </html>
    <?php
    exit;
}