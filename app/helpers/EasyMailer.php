<?php
/**
 * EasyMailer.php
 * Helper class for sending emails (OTPs, notifications) using PHPMailer.
 */
/**
 * Mailer Helper Class
 * 
 * This class handles all email functionalities for the system, mainly used for 
 * sending OTP verification codes during registration and password resets.
 * It uses a standalone version of PHPMailer to avoid environment-specific 
 * dependency issues.
 */

class EasyMailer
{
    private $mail;

    public function __construct()
    {
        // Load PHPMailer files (standalone, no Composer)
        require_once APP_PATH . '/helpers/PHPMailer/class.phpmailer.php';
        require_once APP_PATH . '/helpers/PHPMailer/class.smtp.php';

        $this->mail = new PHPMailer(true);
        $this->configure();
    }

    /**
     * Configure SMTP settings
     */
    private function configure()
    {
        // Load config if exists
        if (file_exists(CONFIG_PATH . '/smtp.php')) {
            require_once CONFIG_PATH . '/smtp.php';
        }

        try {
            $this->mail->IsSMTP();

            // Standard settings from config or defaults
            $this->mail->Host = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = defined('SMTP_USER') ? SMTP_USER : 'preeyankakc.07@gmail.com';
            $this->mail->Password = defined('SMTP_PASS') ? SMTP_PASS : 'jcvnlgnpsyqyvwhq';

            // FORCED SSL/465 (Better deliverability for scripts)
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;

            // Better Charset
            $this->mail->CharSet = 'UTF-8';

            // Performance optimizations
            $this->mail->Timeout = 15;
            $this->mail->SMTPKeepAlive = true;
            $this->mail->SMTPDebug = 0; // Disable for production

            // Email defaults
            $this->mail->From = defined('SMTP_FROM') ? SMTP_FROM : $this->mail->Username;
            $this->mail->FromName = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'Pathek';
            $this->mail->IsHTML(true);

        } catch (Exception $e) {
            $this->log("Config Error: " . $e->getMessage());
        }
    }

    /**
     * Log email events
     */
    public function log($message)
    {
        $logFile = BASE_PATH . '/email_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
    }

    /**
     * Sends OTP Email (Simple & Fast)
     */
    public function sendOTP($to, $otp)
    {
        $start = microtime(true);
        try {
            $this->mail->ClearAddresses();
            $this->mail->AddAddress($to);
            $this->mail->Subject = 'Your OTP Code - Pathek';
            $this->mail->Body = $this->getOTPTemplate($otp);

            $result = $this->mail->Send();
            $time = round(microtime(true) - $start, 2);

            $this->log("Sync OTP to $to | Status: " . ($result ? "SENT" : "FAILED") . " | Time: {$time}s");

            return [
                'success' => $result,
                'error' => $result ? '' : $this->mail->ErrorInfo
            ];

        } catch (Exception $e) {
            $time = round(microtime(true) - $start, 2);
            $this->log("Sync Exception for $to | Error: " . $e->getMessage() . " | Time: {$time}s");
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Asynchronous Email (Background Process)
     * This returns IMMEDIATELY to the user.
     */
    public function sendAsync($to, $otp)
    {
        // For portability 
        $this->log("Async call converted to Sync for portability: $to");
        return $this->sendOTP($to, $otp);
    }

    /**
     * OTP Email Template
     */
    private function getOTPTemplate($otp)
    {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px 10px 0 0;'>
                <h1 style='color: white; margin: 0; text-align: center;'>Pathek Rental</h1>
            </div>
            
            <div style='background: #f8f9fa; padding: 40px; border-radius: 0 0 10px 10px;'>
                <h2 style='color: #333; margin-top: 0;'>Verify Your Email</h2>
                <p style='color: #666; font-size: 16px;'>Your OTP verification code is:</p>
                
                <div style='background: white; padding: 20px; margin: 30px 0; border-radius: 8px; text-align: center; border: 2px dashed #667eea;'>
                    <h1 style='color: #667eea; margin: 0; font-size: 48px; letter-spacing: 10px;'>$otp</h1>
                </div>
                
                <p style='color: #999; font-size: 14px;'>⏱️ This code expires in <strong>5 minutes</strong></p>
                
                <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
                
                <p style='color: #999; font-size: 11px; text-align: center;'>
                    Ref ID: " . uniqid('PT-') . " | Time: " . date('H:i:s') . "
                </p>
                <p style='color: #999; font-size: 12px; margin: 0;'>
                    If you didn't request this code, please ignore this email.
                </p>
            </div>
        </div>
        ";
    }

    /**
     * Send custom email
     */
    public function send($to, $subject, $body)
    {
        try {
            $this->mail->ClearAddresses();
            $this->mail->AddAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            set_time_limit(15);
            $result = $this->mail->Send();

            return [
                'success' => $result,
                'error' => $result ? '' : $this->mail->ErrorInfo
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}