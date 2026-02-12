<?php
/**
 * User.php
 * Model class for managing User accounts, authentication, and profile data.
 */
/**
 * User Model
 */

class User
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Get database connection
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Register a new user
     */
    /**
     * Set OTP
     */
    public function setOtp($user_id, $code, $expiry_minutes = 5)
    {
        $expiry = date('Y-m-d H:i:s', strtotime("+$expiry_minutes minutes"));
        $hashed_otp = password_hash($code, PASSWORD_DEFAULT);

        try {
            $stmt = $this->conn->prepare("UPDATE users SET otp_hash = ?, otp_expires_at = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $hashed_otp, $expiry, $user_id);
            return $stmt->execute();
        } catch (Exception $e) {
            // If error, try seeking if columns are missing
            $this->ensureOtpColumns();
            // Retry
            $stmt = $this->conn->prepare("UPDATE users SET otp_hash = ?, otp_expires_at = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $hashed_otp, $expiry, $user_id);
            return $stmt->execute();
        }
    }

    /**
     * Self-Heal: Ensure OTP columns exist
     */
    private function ensureOtpColumns()
    {
        $this->conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_email_verified TINYINT(1) DEFAULT 0");
        $this->conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS otp_hash VARCHAR(255) NULL");
        $this->conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS otp_expires_at DATETIME NULL");
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($user_id, $code)
    {
        $stmt = $this->conn->prepare("SELECT otp_hash, otp_expires_at FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0)
            return false;

        $row = $res->fetch_assoc();

        // Verify hashed OTP and check expiry
        if (password_verify($code, $row['otp_hash']) && strtotime($row['otp_expires_at']) > time()) {
            return true;
        }
        return false;
    }

    /**
     * Mark Email as Verified
     */
    public function markEmailVerified($user_id)
    {
        // Verified and clear OTP
        $stmt = $this->conn->prepare("UPDATE users SET is_email_verified = 1, otp_hash = NULL, otp_expires_at = NULL WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    /**
     * Register a new user
     */
    public function register($data)
    {
        $full_name = $this->conn->real_escape_string($data['full_name']);
        $email = $this->conn->real_escape_string($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $phone = $this->conn->real_escape_string($data['phone']);
        $user_role = $this->conn->real_escape_string($data['user_role']);

        // Check if email already exists
        $check_query = "SELECT user_id FROM users WHERE email = '$email'";
        $result = $this->conn->query($check_query);

        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        $is_email_verified = 0; // NOT Verified by default
        $account_status = 'active';

        $query = "INSERT INTO users (full_name, email, password_hash, phone, user_role, is_email_verified, account_status) 
                  VALUES ('$full_name', '$email', '$password', '$phone', '$user_role', $is_email_verified, '$account_status')";

        if ($this->conn->query($query)) {
            $user_id = $this->conn->insert_id;
            return [
                'success' => true,
                'message' => 'Registration successful. Verification required.',
                'user_id' => $user_id
            ];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $this->conn->error];
        }
    }

    /**
     * Login user
     */
    public function login($email, $password)
    {
        $email = $this->conn->real_escape_string($email);

        $query = "SELECT * FROM users WHERE email = '$email' AND account_status = 'active'";
        $result = $this->conn->query($query);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                return ['success' => true, 'user' => $user];
            }
        }

        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    /**
     * Get user by ID
     * @param int $user_id
     * @return array|null
     */
    public function getUserById($user_id): ?array
    {
        $user_id = (int) $user_id;
        $query = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get all users by role
     */
    public function getUsersByRole($role)
    {
        $role = $this->conn->real_escape_string($role);
        $query = "SELECT * FROM users WHERE user_role = '$role' ORDER BY created_at DESC";
        $result = $this->conn->query($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    /**
     * Get all users
     */
    public function getAllUsers()
    {
        $query = "SELECT * FROM users WHERE user_role != 'admin' ORDER BY created_at DESC";
        $result = $this->conn->query($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    /**
     * Update user status
     */
    public function updateUserStatus($user_id, $status)
    {
        $user_id = (int) $user_id;
        $status = $this->conn->real_escape_string($status);

        $query = "UPDATE users SET account_status = '$status' WHERE user_id = $user_id";
        return $this->conn->query($query);
    }

    /**
     * Update user profile
     */
    public function updateProfile($user_id, $data)
    {
        $user_id = (int) $user_id;
        $full_name = $this->conn->real_escape_string($data['full_name']);
        $phone = $this->conn->real_escape_string($data['phone']);

        $query = "UPDATE users SET full_name = '$full_name', phone = '$phone' WHERE user_id = $user_id";

        return $this->conn->query($query);
    }

    /**
     * Update password
     */
    public function updatePassword($user_id, $new_password)
    {
        $user_id = (int) $user_id;
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password_hash = '$hashed_password' WHERE user_id = $user_id";
        return $this->conn->query($query);
    }

    /**
     * Verify password (helper)
     */
    public function verifyPassword($user_id, $password)
    {
        $user = $this->getUserById($user_id);
        if ($user && password_verify($password, $user['password_hash'])) {
            return true;
        }
        return false;
    }

    /**
     * Get total count by role
     */
    public function countByRole($role)
    {
        $role = $this->conn->real_escape_string($role);
        $query = "SELECT COUNT(*) as total FROM users WHERE user_role = '$role'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}