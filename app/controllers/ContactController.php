<?php
/**
 * ContactController.php
 * Receives and processes messages submitted through the contact form.
 */

class ContactController
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();

        // Initialize the contact_messages table
        // The message_id is utilized as the Primary Key for better normalization.
        $this->conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
            message_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('pending', 'read', 'replied') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Enforce the use of 'message_id' for consistency across the codebase.
        $cols = $this->conn->query("SHOW COLUMNS FROM contact_messages LIKE 'id'");
        if ($cols->num_rows > 0) {
            $this->conn->query("ALTER TABLE contact_messages CHANGE id message_id INT AUTO_INCREMENT");
        }

        // Maintains data integrity by ensuring the 'status' column is present for admin tracking.
        $cols = $this->conn->query("SHOW COLUMNS FROM contact_messages LIKE 'status'");
        if ($cols->num_rows == 0) {
            $this->conn->query("ALTER TABLE contact_messages ADD COLUMN status ENUM('pending', 'read', 'replied') DEFAULT 'pending' AFTER message");
        }
    }

    /**
     * Renders the primary Contact page interface.
     */
    public function index()
    {
        $page_title = "Contact Us";
        require APP_PATH . '/views/home/contact.php';
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->conn->real_escape_string($_POST['name']);
            $email = $this->conn->real_escape_string($_POST['email']);
            $subject = $this->conn->real_escape_string($_POST['subject']);
            $message = $this->conn->real_escape_string($_POST['message']);

            $query = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

            if ($this->conn->query($query)) {
                // Return to success confirmation view upon successful insertion.
                header("Location: " . BASE_URL . "/index.php?page=contact&action=success");
                exit;
            } else {
                $_SESSION['error'] = "Failed to send message. Please try again.";
                header("Location: " . BASE_URL . "/index.php?page=contact");
                exit;
            }
        }
    }

    public function success()
    {
        $page_title = "Message Sent";
        require APP_PATH . '/views/home/contact_success.php';
    }
}
