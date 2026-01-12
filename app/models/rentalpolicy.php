<?php
/**
 * Rental Policy Model
 */

class RentalPolicy {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Get all active policies
     */
    public function getActivePolicies() {
        $query = "SELECT * FROM rental_policies WHERE is_active = TRUE ORDER BY policy_id ASC";
        $result = $this->conn->query($query);
        
        $policies = [];
        while ($row = $result->fetch_assoc()) {
            $policies[] = $row;
        }
        return $policies;
    }

    /**
     * Get all policies
     */
    public function getAllPolicies() {
        $query = "SELECT * FROM rental_policies ORDER BY policy_id ASC";
        $result = $this->conn->query($query);
        
        $policies = [];
        while ($row = $result->fetch_assoc()) {
            $policies[] = $row;
        }
        return $policies;
    }

    /**
     * Add policy
     */
    public function addPolicy($data) {
        $policy_name = $this->conn->real_escape_string($data['policy_name']);
        $policy_description = $this->conn->real_escape_string($data['policy_description']);
        $policy_value = $this->conn->real_escape_string($data['policy_value']);

        $query = "INSERT INTO rental_policies (policy_name, policy_description, policy_value) 
                  VALUES ('$policy_name', '$policy_description', '$policy_value')";

        return $this->conn->query($query);
    }

    /**
     * Update policy
     */
    public function updatePolicy($policy_id, $data) {
        $policy_id = (int)$policy_id;
        $policy_name = $this->conn->real_escape_string($data['policy_name']);
        $policy_description = $this->conn->real_escape_string($data['policy_description']);
        $policy_value = $this->conn->real_escape_string($data['policy_value']);

        $query = "UPDATE rental_policies SET 
                  policy_name = '$policy_name',
                  policy_description = '$policy_description',
                  policy_value = '$policy_value'
                  WHERE policy_id = $policy_id";

        return $this->conn->query($query);
    }

    /**
     * Toggle policy status
     */
    public function togglePolicyStatus($policy_id) {
        $policy_id = (int)$policy_id;
        $query = "UPDATE rental_policies SET is_active = NOT is_active WHERE policy_id = $policy_id";
        return $this->conn->query($query);
    }

    /**
     * Delete policy
     */
    public function deletePolicy($policy_id) {
        $policy_id = (int)$policy_id;
        $query = "DELETE FROM rental_policies WHERE policy_id = $policy_id";
        return $this->conn->query($query);
    }
}