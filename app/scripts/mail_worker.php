<?php
/**
 * Background Mail Worker
 * Executed via CLI to send emails without blocking the user
 */

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

// 1. Setup paths (same as public/index.php)
define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');

// 2. Load dependencies
require_once APP_PATH . '/helpers/EasyMailer.php';

// 3. Get arguments from CLI
$to = $argv[1] ?? null;
$otp = $argv[2] ?? null;

if (!$to || !$otp) {
    file_put_contents(BASE_PATH . '/email_log.txt', "[" . date('Y-m-d H:i:s') . "] Worker Error: Missing parameters $to, $otp" . PHP_EOL, FILE_APPEND);
    exit(1);
}

// 4. Send the email
file_put_contents(BASE_PATH . '/email_log.txt', "[" . date('Y-m-d H:i:s') . "] WORKER START: Sending to $to" . PHP_EOL, FILE_APPEND);
$mailer = new EasyMailer();
$result = $mailer->sendOTP($to, $otp);

if ($result['success']) {
    file_put_contents(BASE_PATH . '/email_log.txt', "[" . date('Y-m-d H:i:s') . "] WORKER SUCCESS: Delivered to $to" . PHP_EOL, FILE_APPEND);
} else {
    file_put_contents(BASE_PATH . '/email_log.txt', "[" . date('Y-m-d H:i:s') . "] WORKER FAILED: $to | Error: " . $result['error'] . PHP_EOL, FILE_APPEND);
}

exit($result['success'] ? 0 : 1);
