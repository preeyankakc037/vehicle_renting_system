<?php
/**
 * Front Controller - Entry Point
 * All requests start here
 */

// 1. Start session FIRST (before anything else)
session_start();

// 2. Define ALL path constants
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('ASSETS_PATH', BASE_PATH . '/assets');

// 3. Define BASE_URL for dynamic routing
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . "://" . $host . $script);

// 4. Load database configuration
require_once CONFIG_PATH . '/db.php';

// 5. Autoloader for controllers and models
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

// 6. Get routing parameters
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// 7. Route to appropriate controller
try {
    switch ($page) {
        case 'home':
            $controller = new VehicleController();
            $controller->home();
            break;
            
        case 'auth':
            $controller = new AuthController();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $controller->login();
            }
            break;
            
        case 'vehicle':
            $controller = new VehicleController();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $controller->index();
            }
            break;
            
        case 'booking':
            $controller = new BookingController();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $controller->renterDashboard();
            }
            break;
            
        case 'admin':
            $controller = new AdminController();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $controller->dashboard();
            }
            break;
            
        default:
            $controller = new VehicleController();
            $controller->home();
            break;
    }
    
} catch (Exception $e) {
    // Display friendly error page
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error - Vehicle Rental System</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                background: #f5f5f5; 
                padding: 50px; 
                text-align: center; 
            }
            .error-container {
                background: white;
                max-width: 600px;
                margin: 0 auto;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 { color: #dc3545; }
            .error-details {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
                text-align: left;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #00d563;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
            .btn:hover { background: #00b851; }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>⚠️ Application Error</h1>
            <p>Sorry, something went wrong.</p>
            
            <div class="error-details">
                <strong>Error:</strong> <?php echo htmlspecialchars($e->getMessage()); ?><br>
                <strong>File:</strong> <?php echo htmlspecialchars($e->getFile()); ?><br>
                <strong>Line:</strong> <?php echo $e->getLine(); ?>
            </div>
            
            <a href="<?php echo BASE_URL; ?>/index.php" class="btn">← Go to Homepage</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}