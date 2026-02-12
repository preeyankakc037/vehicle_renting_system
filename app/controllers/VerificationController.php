<?php
/**
 * VerificationController.php
 * Handles the submission and status checking of owner verification documents.
 */

class VerificationController
{
    private $db;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Renders the administrative interface for processing owner verification applications.
     */
    public function index()
    {
        // Execute a real-time database query to synchronize account privileges.
        $user_id = $_SESSION['user_id'];
        $stmt = $this->db->prepare("SELECT user_role FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Update session state if administrative approval was granted during the current lifecycle.
        if ($user['user_role'] === 'owner_verified') {
            $_SESSION['user_role'] = 'owner_verified'; // Update Session
            header("Location: " . BASE_URL . "/index.php?page=vehicle&action=myVehicles");
            exit;
        }

        // Shows verification form if user is unverified or previously rejected.

        if ($user['user_role'] === 'owner_pending') {
            // Check verification status
            $stmt = $this->db->prepare("SELECT status, admin_feedback, business_name, id_proof_type, id_proof_number FROM owner_verifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $verification = $stmt->get_result()->fetch_assoc();

            // Conditional logic: Render the application form if no record exists or if revisions are requested.
            if (!$verification || $verification['status'] === 'fixes_needed') {
                if ($verification) {
                    $user = array_merge($user, $verification);
                }
                require APP_PATH . '/views/auth/apply_owner.php';
                return;
            }

            // If status is pending, dispatch the verification queue notification view.
            require APP_PATH . '/views/auth/verification_pending.php';
            return;
        }

        require APP_PATH . '/views/auth/apply_owner.php';
    }

    /**
     * Processes and persists sensitive verification data to the secure system repository.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $business_name = $_POST['business_name'] ?? '';
            $id_proof_type = $_POST['id_proof_type'];
            $id_proof_number = $_POST['id_proof_number'];

            // Check for existing "fixes_needed" application
            $check = $this->db->prepare("SELECT verification_id FROM owner_verifications WHERE user_id = ? AND status = 'fixes_needed' ORDER BY created_at DESC LIMIT 1");
            $check->bind_param("i", $user_id);
            $check->execute();
            $existing = $check->get_result()->fetch_assoc();

            if ($existing) {
                // UPDATES existing
                $stmt = $this->db->prepare("UPDATE owner_verifications SET business_name = ?, id_proof_type = ?, id_proof_number = ?, status = 'pending', admin_feedback = NULL WHERE verification_id = ?");
                $stmt->bind_param("sssi", $business_name, $id_proof_type, $id_proof_number, $existing['verification_id']);
            } else {
                // INSERTS new
                $stmt = $this->db->prepare("INSERT INTO owner_verifications (user_id, business_name, id_proof_type, id_proof_number) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $business_name, $id_proof_type, $id_proof_number);
            }

            if ($stmt->execute()) {
                // Ensures user role is owner_pending
                $update = $this->db->prepare("UPDATE users SET user_role = 'owner_pending' WHERE user_id = ?");
                $update->bind_param("i", $user_id);
                $update->execute();

                // Updates Session
                $_SESSION['user_role'] = 'owner_pending';

                header("Location: " . BASE_URL . "/index.php?page=verification");
            } else {
                $error = "Failed to submit application. Please try again.";
                require APP_PATH . '/views/auth/apply_owner.php';
            }
        }
    }
}
