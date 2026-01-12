<?php
/**
 * DIAGNOSTIC SCRIPT - Place in /public/ folder
 * Access via: http://localhost/vehicle_rental_system/public/test.php
 * This will help identify any issues
 */

echo "<!DOCTYPE html><html><head><title>Diagnostic Test</title>";
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
.error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
.info { background: #d1ecf1; border: 2px solid #0dcaf0; padding: 15px; margin: 10px 0; border-radius: 5px; }
h1 { color: #333; }
code { background: #fff; padding: 2px 8px; border-radius: 3px; border: 1px solid #ddd; }
</style></head><body>";

echo "<h1>🔍 Vehicle Rental System - Diagnostic Test</h1>";

// Test 1: PHP Version
echo "<div class='info'><strong>PHP Version:</strong> " . phpversion() . "</div>";

// Test 2: Session
session_start();
if (isset($_SESSION)) {
    echo "<div class='success'>✓ Session is working</div>";
} else {
    echo "<div class='error'>✗ Session not working</div>";
}

// Test 3: Path Constants
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');

echo "<div class='info'><strong>BASE_PATH:</strong> <code>" . BASE_PATH . "</code></div>";
echo "<div class='info'><strong>APP_PATH:</strong> <code>" . APP_PATH . "</code></div>";

// Test 4: Check if folders exist
$folders = [
    'app' => APP_PATH,
    'config' => CONFIG_PATH,
    'assets' => BASE_PATH . '/assets',
    'sql' => BASE_PATH . '/sql'
];

foreach ($folders as $name => $path) {
    if (is_dir($path)) {
        echo "<div class='success'>✓ Folder exists: <code>$name</code> at $path</div>";
    } else {
        echo "<div class='error'>✗ Folder missing: <code>$name</code> at $path</div>";
    }
}

// Test 5: Check important files
$files = [
    'Database Config' => CONFIG_PATH . '/db.php',
    'User Model' => APP_PATH . '/models/User.php',
    'Auth Controller' => APP_PATH . '/controllers/AuthController.php',
    'Vehicle Controller' => APP_PATH . '/controllers/VehicleController.php',
    'Header View' => APP_PATH . '/views/layouts/header.php',
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<div class='success'>✓ File exists: <strong>$name</strong></div>";
    } else {
        echo "<div class='error'>✗ File missing: <strong>$name</strong><br><code>$path</code></div>";
    }
}

// Test 6: Database Connection
try {
    require_once CONFIG_PATH . '/db.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "<div class='success'>✓ Database connection successful</div>";
    
    // Check if tables exist
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        echo "<div class='info'><strong>Database Tables Found:</strong> " . count($tables) . "<br>";
        echo implode(', ', $tables) . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Database connection failed: " . $e->getMessage() . "</div>";
}

// Test 7: BASE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = str_replace('/test.php', '', $_SERVER['SCRIPT_NAME']);
$base_url = $protocol . "://" . $host . $script;

echo "<div class='info'><strong>Your BASE_URL should be:</strong> <code>$base_url</code></div>";
echo "<div class='info'><strong>Access your app at:</strong> <a href='$base_url/index.php'>$base_url/index.php</a></div>";

// Test 8: Write permissions
$upload_dir = BASE_PATH . '/assets/images/vehicles';
if (!is_dir($upload_dir)) {
    echo "<div class='error'>✗ Upload directory doesn't exist: <code>$upload_dir</code><br>Please create it!</div>";
} else {
    if (is_writable($upload_dir)) {
        echo "<div class='success'>✓ Upload directory is writable</div>";
    } else {
        echo "<div class='error'>✗ Upload directory is NOT writable. Please set permissions.</div>";
    }
}

echo "<hr>";
echo "<h2>🎯 Next Steps</h2>";
echo "<ol>";
echo "<li>If all tests pass, go to: <a href='index.php'>index.php</a></li>";
echo "<li>If database connection failed, check <code>config/db.php</code></li>";
echo "<li>If folders are missing, verify file extraction</li>";
echo "<li>Delete this test.php file when done</li>";
echo "</ol>";

echo "</body></html>";