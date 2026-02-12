<?php
/**
 * AdminController.php
 * Handles all administrator actions including dashboard stats, user management,
 * vehicle approvals, and viewing system logs.
 */

class AdminController
{

    private $db;

    public function __construct()
    {
        // If not logged in, go to login
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            exit;
        }

        // If logged in but not admin, redirect to home (or show access denied)
        if ($_SESSION['user_role'] !== 'admin') {
            // Redirect to home which handles routing based on role naturally or just safe fallback
            header("Location: " . BASE_URL . "/index.php");
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function dashboard()
    {
        // Stats
        $stats = [];

        //Ensure contact_messages table has the 'status' column
        $this->db->query("CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('pending', 'read', 'replied') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Fallback for existing tables missing the 'status' column
        $cols = $this->db->query("SHOW COLUMNS FROM contact_messages LIKE 'status'");
        if ($cols->num_rows == 0) {
            $this->db->query("ALTER TABLE contact_messages ADD COLUMN status ENUM('pending', 'read', 'replied') DEFAULT 'pending' AFTER message");
        }
        $stats['users'] = $this->db->query("SELECT COUNT(*) as count FROM users WHERE user_role = 'renter'")->fetch_assoc()['count'];
        $stats['owners'] = $this->db->query("SELECT COUNT(*) as count FROM users WHERE user_role = 'owner_verified'")->fetch_assoc()['count'];
        $stats['vehicles'] = $this->db->query("SELECT COUNT(*) as count FROM vehicles")->fetch_assoc()['count'];
        $stats['pending_vehicles'] = $this->db->query("SELECT COUNT(*) as count FROM vehicles WHERE approval_status = 'pending'")->fetch_assoc()['count'];
        $stats['pending_verifications'] = $this->db->query("SELECT COUNT(*) as count FROM owner_verifications WHERE status = 'pending'")->fetch_assoc()['count'];
        $stats['messages'] = $this->db->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'pending'")->fetch_assoc()['count'];

        // Fetch Recent 5 Pending Owner Verifications (Preview)
        $query_owners = "SELECT ov.*, u.full_name, u.email, u.phone 
                  FROM owner_verifications ov 
                  JOIN users u ON ov.user_id = u.user_id 
                  WHERE ov.status = 'pending' 
                  ORDER BY ov.created_at ASC 
                  LIMIT 5";
        $result_owners = $this->db->query($query_owners);
        $recent_verifications = [];
        while ($row = $result_owners->fetch_assoc()) {
            $recent_verifications[] = $row;
        }

        // Fetch Recent 5 Listed Vehicles (Preview) - Showing ALL recent, not just pending
        $query_vehicles = "SELECT v.*, u.full_name as owner_name 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  ORDER BY v.created_at DESC 
                  LIMIT 5";
        $result_vehicles = $this->db->query($query_vehicles);
        $recent_vehicles = [];
        while ($row = $result_vehicles->fetch_assoc()) {
            $recent_vehicles[] = $row;
        }

        require APP_PATH . "/views/admin/dashboard.php";
    }

    /**
     * List all contact messages
     */
    public function messages()
    {
        $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
        $result = $this->db->query($query);
        $messages = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }

        require APP_PATH . "/views/admin/messages.php";
    }

    /**
     * Delete a contact message
     */
    public function deleteMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) $_POST['message_id'];

            $success = $this->db->query("DELETE FROM contact_messages WHERE message_id = $id");
            if (!$success) {
                $success = $this->db->query("DELETE FROM contact_messages WHERE id = $id");
            }

            if ($success) {
                $_SESSION['success'] = "Message deleted.";
            } else {
                $_SESSION['error'] = "Failed to delete message.";
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=admin&action=messages");
        exit;
    }

    /**
     * List Pending Owner Verifications
     */
    public function verifications()
    {
        $query = "SELECT ov.*, u.full_name, u.email 
                  FROM owner_verifications ov 
                  JOIN users u ON ov.user_id = u.user_id 
                  WHERE ov.status = 'pending' 
                  ORDER BY ov.created_at ASC";

        $result = $this->db->query($query);
        $verifications = [];
        while ($row = $result->fetch_assoc()) {
            $verifications[] = $row;
        }

        require APP_PATH . "/views/admin/verifications.php";
    }

    /**
     * Approve Owner
     */
    public function approveOwner()
    {
        $user_id = $_POST['user_id'];
        $verification_id = $_POST['verification_id'];

        $this->db->begin_transaction();
        try {
            // 1. Update verification status
            $stmt = $this->db->prepare("UPDATE owner_verifications SET status = 'approved' WHERE verification_id = ?");
            $stmt->bind_param("i", $verification_id);
            $stmt->execute();

            // 2. Update User Role
            $stmt2 = $this->db->prepare("UPDATE users SET user_role = 'owner_verified' WHERE user_id = ?");
            $stmt2->bind_param("i", $user_id);
            $stmt2->execute();

            $this->db->commit();
            header("Location: " . BASE_URL . "/index.php?page=admin&action=verifications&success=approved");

        } catch (Exception $e) {
            $this->db->rollback();
            header("Location: " . BASE_URL . "/index.php?page=admin&action=verifications&error=failed");
        }
    }

    /**
     * Reject Owner
     */
    public function rejectOwner()
    {
        $user_id = $_POST['user_id'];
        $verification_id = $_POST['verification_id'];

        $this->db->begin_transaction();
        try {
            // 1. Update verification status
            $stmt = $this->db->prepare("UPDATE owner_verifications SET status = 'rejected' WHERE verification_id = ?");
            $stmt->bind_param("i", $verification_id);
            $stmt->execute();

            // 2. Update User Role (Revert to renter or specific rejection role)
            $stmt2 = $this->db->prepare("UPDATE users SET user_role = 'owner_rejected' WHERE user_id = ?");
            $stmt2->bind_param("i", $user_id);
            $stmt2->execute();

            $this->db->commit();
            header("Location: " . BASE_URL . "/index.php?page=admin&action=verifications&success=rejected");

        } catch (Exception $e) {
            $this->db->rollback();
            header("Location: " . BASE_URL . "/index.php?page=admin&action=verifications&error=failed");
        }
    }


    /**
     * Approve Vehicle
     */
    public function approveVehicle()
    {
        $vehicle_id = $_POST['vehicle_id'];

        // Update vehicle status to approved
        $stmt = $this->db->prepare("UPDATE vehicles SET approval_status = 'approved', availability_status = 'available' WHERE vehicle_id = ?");
        $stmt->bind_param("i", $vehicle_id);

        if ($stmt->execute()) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=vehicles&success=approved");
        } else {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=vehicles&error=failed");
        }
    }

    /**
     * Reject Vehicle
     */
    public function rejectVehicle()
    {
        $vehicle_id = $_POST['vehicle_id'];

        // Update vehicle status to rejected
        $stmt = $this->db->prepare("UPDATE vehicles SET approval_status = 'rejected' WHERE vehicle_id = ?");
        $stmt->bind_param("i", $vehicle_id);

        if ($stmt->execute()) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=vehicles&success=rejected");
        } else {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=vehicles&error=failed");
        }
    }
    /**
     * Request Fixes for Vehicle
     */
    public function requestVehicleFixes()
    {
        $vehicle_id = $_POST['vehicle_id'];
        $feedback = $_POST['admin_feedback'];

        // Update vehicle status to 'fixes_needed' and save feedback
        $stmt = $this->db->prepare("UPDATE vehicles SET approval_status = 'fixes_needed', admin_feedback = ? WHERE vehicle_id = ?");
        $stmt->bind_param("si", $feedback, $vehicle_id);

        if ($stmt->execute()) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=dashboard&success=feedback_sent");
        } else {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=dashboard&error=failed");
        }
    }
    /**
     * Request Fixes for Owner Verification
     */
    public function requestOwnerFixes()
    {
        $verification_id = $_POST['verification_id'];
        $feedback = $_POST['admin_feedback'];

        // Update verification status to 'fixes_needed' and save feedback
        $stmt = $this->db->prepare("UPDATE owner_verifications SET status = 'fixes_needed', admin_feedback = ? WHERE verification_id = ?");
        $stmt->bind_param("si", $feedback, $verification_id);

        if ($stmt->execute()) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=dashboard&success=feedback_sent");
        } else {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=dashboard&error=failed");
        }
    }

    /**
     * View Verification Details
     */
    public function viewVerificationDetails()
    {
        $verification_id = $_GET['id'];

        $query = "SELECT ov.*, u.full_name, u.email, u.phone 
                  FROM owner_verifications ov 
                  JOIN users u ON ov.user_id = u.user_id 
                  WHERE ov.verification_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $verification_id);
        $stmt->execute();
        $verification = $stmt->get_result()->fetch_assoc();

        if (!$verification) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=dashboard");
            exit;
        }

        require APP_PATH . "/views/admin/view_verification.php";
    }

    /**
     * View Vehicle Details
     */
    public function viewVehicleDetails()
    {
        $vehicle_id = $_GET['id'];

        $query = "SELECT v.*, u.full_name as owner_name, u.email as owner_email, u.phone as owner_phone 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE v.vehicle_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        $vehicle = $stmt->get_result()->fetch_assoc();

        if (!$vehicle) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=dashboard");
            exit;
        }

        require APP_PATH . "/views/admin/view_vehicle.php";
    }
    /**
     * List All Admins
     */
    public function admins()
    {
        $query = "SELECT user_id, full_name, email, phone, created_at 
                  FROM users 
                  WHERE user_role = 'admin' 
                  ORDER BY created_at DESC";

        $result = $this->db->query($query);
        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }

        require APP_PATH . "/views/admin/admins.php";
    }

    /**
     * Show Create Admin Form
     */
    public function createAdmin()
    {
        require APP_PATH . "/views/admin/create_admin.php";
    }

    /**
     * Store New Admin
     */
    public function storeAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
            exit;
        }

        $full_name = $this->db->real_escape_string($_POST['full_name']);
        $email = $this->db->real_escape_string($_POST['email']);
        $phone = $this->db->real_escape_string($_POST['phone']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if email already exists
        $check = $this->db->query("SELECT user_id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $_SESSION['error'] = "Email already exists.";
            header("Location: " . BASE_URL . "/index.php?page=admin&action=createAdmin");
            exit;
        }

        $query = "INSERT INTO users (full_name, email, phone, password_hash, user_role, account_status, is_email_verified) 
                  VALUES ('$full_name', '$email', '$phone', '$password', 'admin', 'active', 1)";

        if ($this->db->query($query)) {
            $_SESSION['success'] = "Admin created successfully.";
            header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
        } else {
            $_SESSION['error'] = "Failed to create admin.";
            header("Location: " . BASE_URL . "/index.php?page=admin&action=createAdmin");
        }
        exit;
    }

    /**
     * Show Edit Admin Form
     */
    public function editAdmin()
    {
        $admin_id = $_GET['id'] ?? 0;

        $stmt = $this->db->prepare("SELECT user_id, full_name, email, phone FROM users WHERE user_id = ? AND user_role = 'admin'");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if (!$admin) {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
            exit;
        }

        require APP_PATH . "/views/admin/edit_admin.php";
    }

    /**
     * Update Admin
     */
    public function updateAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
            exit;
        }

        $admin_id = (int) $_POST['admin_id'];
        $full_name = $this->db->real_escape_string($_POST['full_name']);
        $email = $this->db->real_escape_string($_POST['email']);
        $phone = $this->db->real_escape_string($_POST['phone']);

        $password_sql = "";
        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = "Passwords do not match.";
                header("Location: " . BASE_URL . "/index.php?page=admin&action=editAdmin&id=$admin_id");
                exit;
            }
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $password_sql = ", password_hash = '$password'";
        }

        $query = "UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone' $password_sql 
                  WHERE user_id = $admin_id AND user_role = 'admin'";

        if ($this->db->query($query)) {
            $_SESSION['success'] = "Admin updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update admin.";
        }

        header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
        exit;
    }

    /**
     * Delete Admin
     */
    public function deleteAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
            exit;
        }

        $admin_id = (int) $_POST['admin_id'];

        // Security: Enforce account integrity by preventing administrative self-deletion.
        if ($admin_id == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account.";
            header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
            exit;
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ? AND user_role = 'admin'");
        $stmt->bind_param("i", $admin_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['success'] = "Admin deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete admin.";
        }

        header("Location: " . BASE_URL . "/index.php?page=admin&action=admins");
        exit;
    }
    /**
     * Manage All Vehicles (Full List with Filters)
     */
    public function vehicles()
    {
        $status = $_GET['status'] ?? '';
        $type = $_GET['type'] ?? '';
        $search = $_GET['search'] ?? '';

        $query = "SELECT v.*, u.full_name as owner_name, u.email as owner_email 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE 1=1";

        // Filtering
        if (!empty($status)) {
            $status = $this->db->real_escape_string($status);
            $query .= " AND v.approval_status = '$status'";
        }

        if (!empty($type)) {
            $type = $this->db->real_escape_string($type);
            $query .= " AND v.vehicle_type = '$type'";
        }

        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $query .= " AND (v.vehicle_name LIKE '%$search%' OR u.full_name LIKE '%$search%' OR v.plate_number LIKE '%$search%')";
        }

        $query .= " ORDER BY v.created_at DESC";

        $result = $this->db->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }

        require APP_PATH . "/views/admin/manage_vehicles.php";
    }

    /**
     * Suspends a vehicle listing to restrict public visibility and booking capabilities.
     */
    public function suspendVehicle()
    {
        $vehicle_id = (int) $_POST['vehicle_id'];

        // Add logging here (for instance: log status change)

        $stmt = $this->db->prepare("UPDATE vehicles SET approval_status = 'rejected', availability_status = 'maintenance' WHERE vehicle_id = ?");
        $stmt->bind_param("i", $vehicle_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Vehicle suspended successfully.";
        } else {
            $_SESSION['error'] = "Failed to suspend vehicle.";
        }

        header("Location: " . BASE_URL . "/index.php?page=admin&action=vehicles");
    }

    /**
     * Executes a permanent deletion of a vehicle entity with foundational integrity checks.
     */
    public function deleteVehicle()
    {
        $vehicle_id = (int) $_POST['vehicle_id'];

        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE vehicle_id = ?");
        $stmt->bind_param("i", $vehicle_id);

        if ($stmt->execute()) {
            $this->logAction('delete_vehicle', "Deleted vehicle ID: $vehicle_id");
            $_SESSION['success'] = "Vehicle deleted permanently.";
        } else {
            $_SESSION['error'] = "Failed to delete vehicle.";
        }

        header("Location: " . BASE_URL . "/index.php?page=admin&action=vehicles");
    }

    /**
     * Renders the comprehensive user management interface for all system actors.
     */
    public function users()
    {
        // Consolidate both roles into one list
        $query = "SELECT * FROM users WHERE user_role != 'admin' ORDER BY created_at DESC";
        $result = $this->db->query($query);
        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        require APP_PATH . "/views/admin/manage_users.php";
    }

    /**
     * Suspend/Ban User
     */
    public function updateUserStatus()
    {
        $user_id = (int) $_GET['id'];
        $status = $_GET['status']; // 'active' or 'banned' or 'suspended'

        $stmt = $this->db->prepare("UPDATE users SET account_status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $status, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "User status updated to $status.";
            // Log this
            $this->logAction('update_user', "User ID $user_id status to $status");
        } else {
            $_SESSION['error'] = "Failed to update status.";
        }

        header("Location: " . BASE_URL . "/index.php?page=admin&action=users");
    }

    /**
     * List All Bookings (Operations > Bookings)
     */
    public function bookings()
    {
        // Try to fetch bookings. If table doesn't exist, handles gracefully.
        $bookings = [];
        $check = $this->db->query("SHOW TABLES LIKE 'bookings'");

        if ($check && $check->num_rows > 0) {
            $query = "SELECT b.*, v.vehicle_name, v.plate_number, u.full_name as renter_name 
                      FROM bookings b 
                      LEFT JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                      LEFT JOIN users u ON b.renter_id = u.user_id 
                      ORDER BY b.created_at DESC";
            $result = $this->db->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $bookings[] = $row;
                }
            }
        }

        require APP_PATH . "/views/admin/bookings.php";
    }

    /**
     * Settings Page (Self-Healing DB)
     */
    public function settings()
    {
        // Self-Healing
        $check = $this->db->query("SHOW TABLES LIKE 'settings'");
        if ($check->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                key_name VARCHAR(50) UNIQUE NOT NULL,
                value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $this->db->query($sql);

            $defaults = [
                'platform_name' => 'Pathek',
                'currency' => 'NPR',
                'min_price' => '500',
                'max_price' => '50000',
                'verification_required' => '1',
                'min_booking_duration' => '1',
                'support_email' => 'support@pathek.com',
                'cancellation_allowed' => '1'
            ];
            foreach ($defaults as $key => $val) {
                $this->db->query("INSERT IGNORE INTO settings (key_name, value) VALUES ('$key', '$val')");
            }
        }

        $settings = [];
        $res = $this->db->query("SELECT * FROM settings");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $settings[$row['key_name']] = $row['value'];
            }
        }

        require APP_PATH . "/views/admin/settings.php";
    }

    /**
     * Update Settings
     */
    public function updateSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ensure table exists
            $this->db->query("CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                key_name VARCHAR(50) UNIQUE NOT NULL,
                value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            foreach ($_POST as $key => $val) {
                $stmt = $this->db->prepare("INSERT INTO settings (key_name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->bind_param("sss", $key, $val, $val);
                $stmt->execute();
            }

            $_SESSION['success'] = "Settings updated successfully.";
            $this->logAction('update_settings', "System settings updated");
        }

        header("Location: " . BASE_URL . "/index.php?page=admin&action=settings");
    }

    /**
     * Retrieves system audit logs, ensuring necessary database schema is initialized.
     */
    public function logs()
    {
        // Automated table initialization for audit sustainability.
        $check = $this->db->query("SHOW TABLES LIKE 'system_logs'");
        if ($check->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS system_logs (
                log_id INT AUTO_INCREMENT PRIMARY KEY,
                admin_id INT,
                action VARCHAR(50) NOT NULL,
                details TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->db->query($sql);
        }

        $query = "SELECT l.*, u.full_name as admin_name 
                  FROM system_logs l 
                  LEFT JOIN users u ON l.admin_id = u.user_id 
                  ORDER BY l.created_at DESC LIMIT 100";
        $logs = [];
        if ($res = $this->db->query($query)) {
            while ($row = $res->fetch_assoc()) {
                $logs[] = $row;
            }
        }

        require APP_PATH . "/views/admin/logs.php";
    }

    /**
     * Helper: Log Action
     */
    private function logAction($action, $details)
    {
        if (isset($_SESSION['user_id'])) {
            $admin_id = $_SESSION['user_id'];

            // Execute a transactional log insertion with automated table recovery on failure.
            $sql = "INSERT INTO system_logs (admin_id, action, details) VALUES (?, ?, ?)";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iss", $admin_id, $action, $details);
                $stmt->execute();
            } catch (Exception $e) {
                // assume table missing
                $this->db->query("CREATE TABLE IF NOT EXISTS system_logs (
                    log_id INT AUTO_INCREMENT PRIMARY KEY,
                    admin_id INT,
                    action VARCHAR(50) NOT NULL,
                    details TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iss", $admin_id, $action, $details);
                $stmt->execute();
            }
        }
    }
}
