<?php
/**
 * User Model
 */

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Register a new user
     */
    public function register($data) {
        $full_name = $this->conn->real_escape_string($data['full_name']);
        $email = $this->conn->real_escape_string($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $phone = $this->conn->real_escape_string($data['phone']);
        $address = isset($data['address']) ? $this->conn->real_escape_string($data['address']) : '';
        $user_role = $this->conn->real_escape_string($data['user_role']);

        // Check if email already exists
        $check_query = "SELECT user_id FROM users WHERE email = '$email'";
        $result = $this->conn->query($check_query);
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        $query = "INSERT INTO users (full_name, email, password, phone, address, user_role) 
                  VALUES ('$full_name', '$email', '$password', '$phone', '$address', '$user_role')";

        if ($this->conn->query($query)) {
            return ['success' => true, 'message' => 'Registration successful'];
        } else {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    /**
     * Login user
     */
    public function login($email, $password) {
        $email = $this->conn->real_escape_string($email);
        
        $query = "SELECT * FROM users WHERE email = '$email' AND status = 'active'";
        $result = $this->conn->query($query);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                return ['success' => true, 'user' => $user];
            }
        }
        
        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    /**
     * Get user by ID
     */
    public function getUserById($user_id) {
        $user_id = (int)$user_id;
        $query = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $this->conn->query($query);
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get all users by role
     */
    public function getUsersByRole($role) {
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
    public function getAllUsers() {
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
    public function updateUserStatus($user_id, $status) {
        $user_id = (int)$user_id;
        $status = $this->conn->real_escape_string($status);
        
        $query = "UPDATE users SET status = '$status' WHERE user_id = $user_id";
        return $this->conn->query($query);
    }

    /**
     * Update user profile
     */
    public function updateProfile($user_id, $data) {
        $user_id = (int)$user_id;
        $full_name = $this->conn->real_escape_string($data['full_name']);
        $phone = $this->conn->real_escape_string($data['phone']);
        $address = $this->conn->real_escape_string($data['address']);

        $query = "UPDATE users SET full_name = '$full_name', phone = '$phone', 
                  address = '$address' WHERE user_id = $user_id";
        
        return $this->conn->query($query);
    }

    /**
     * Get total count by role
     */
    public function countByRole($role) {
        $role = $this->conn->real_escape_string($role);
        $query = "SELECT COUNT(*) as total FROM users WHERE user_role = '$role'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}