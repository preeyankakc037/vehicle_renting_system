<?php
/**
 * Vehicle Model
 */

class Vehicle {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Add new vehicle
     */
    public function addVehicle($data, $owner_id) {
        $owner_id = (int)$owner_id;
        $vehicle_name = $this->conn->real_escape_string($data['vehicle_name']);
        $vehicle_type = $this->conn->real_escape_string($data['vehicle_type']);
        $model = $this->conn->real_escape_string($data['model']);
        $year = (int)$data['year'];
        $plate_number = $this->conn->real_escape_string($data['plate_number']);
        $price_per_day = (float)$data['price_per_day'];
        $description = $this->conn->real_escape_string($data['description']);
        $image_path = isset($data['image_path']) ? $this->conn->real_escape_string($data['image_path']) : '';

        $query = "INSERT INTO vehicles (owner_id, vehicle_name, vehicle_type, model, year, 
                  plate_number, price_per_day, description, image_path) 
                  VALUES ($owner_id, '$vehicle_name', '$vehicle_type', '$model', $year, 
                  '$plate_number', $price_per_day, '$description', '$image_path')";

        if ($this->conn->query($query)) {
            return ['success' => true, 'message' => 'Vehicle added successfully. Pending admin approval.'];
        } else {
            return ['success' => false, 'message' => 'Failed to add vehicle'];
        }
    }

    /**
     * Get all approved and available vehicles
     */
    public function getAvailableVehicles() {
        $query = "SELECT v.*, u.full_name as owner_name, u.phone as owner_phone 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE v.approval_status = 'approved' 
                  AND v.availability_status = 'available'
                  ORDER BY v.created_at DESC";
        
        $result = $this->conn->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        return $vehicles;
    }

    /**
     * Get featured vehicles (limit 6)
     */
    public function getFeaturedVehicles($limit = 6) {
        $query = "SELECT v.*, u.full_name as owner_name 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE v.approval_status = 'approved' 
                  AND v.availability_status = 'available'
                  ORDER BY v.created_at DESC 
                  LIMIT $limit";
        
        $result = $this->conn->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        return $vehicles;
    }

    /**
     * Get vehicle by ID
     */
    public function getVehicleById($vehicle_id) {
        $vehicle_id = (int)$vehicle_id;
        $query = "SELECT v.*, u.full_name as owner_name, u.phone as owner_phone, u.email as owner_email 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE v.vehicle_id = $vehicle_id";
        
        $result = $this->conn->query($query);
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get vehicles by owner
     */
    public function getVehiclesByOwner($owner_id) {
        $owner_id = (int)$owner_id;
        $query = "SELECT * FROM vehicles WHERE owner_id = $owner_id ORDER BY created_at DESC";
        
        $result = $this->conn->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        return $vehicles;
    }

    /**
     * Get all vehicles (admin)
     */
    public function getAllVehicles() {
        $query = "SELECT v.*, u.full_name as owner_name 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  ORDER BY v.created_at DESC";
        
        $result = $this->conn->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        return $vehicles;
    }

    /**
     * Update vehicle
     */
    public function updateVehicle($vehicle_id, $data) {
        $vehicle_id = (int)$vehicle_id;
        $vehicle_name = $this->conn->real_escape_string($data['vehicle_name']);
        $vehicle_type = $this->conn->real_escape_string($data['vehicle_type']);
        $model = $this->conn->real_escape_string($data['model']);
        $year = (int)$data['year'];
        $price_per_day = (float)$data['price_per_day'];
        $description = $this->conn->real_escape_string($data['description']);

        $query = "UPDATE vehicles SET 
                  vehicle_name = '$vehicle_name',
                  vehicle_type = '$vehicle_type',
                  model = '$model',
                  year = $year,
                  price_per_day = $price_per_day,
                  description = '$description'
                  WHERE vehicle_id = $vehicle_id";

        return $this->conn->query($query);
    }

    /**
     * Update approval status
     */
    public function updateApprovalStatus($vehicle_id, $status) {
        $vehicle_id = (int)$vehicle_id;
        $status = $this->conn->real_escape_string($status);
        
        $query = "UPDATE vehicles SET approval_status = '$status' WHERE vehicle_id = $vehicle_id";
        return $this->conn->query($query);
    }

    /**
     * Update availability status
     */
    public function updateAvailabilityStatus($vehicle_id, $status) {
        $vehicle_id = (int)$vehicle_id;
        $status = $this->conn->real_escape_string($status);
        
        $query = "UPDATE vehicles SET availability_status = '$status' WHERE vehicle_id = $vehicle_id";
        return $this->conn->query($query);
    }

    /**
     * Delete vehicle
     */
    public function deleteVehicle($vehicle_id) {
        $vehicle_id = (int)$vehicle_id;
        $query = "DELETE FROM vehicles WHERE vehicle_id = $vehicle_id";
        return $this->conn->query($query);
    }

    /**
     * Check if vehicle is available for dates
     */
    public function isAvailableForDates($vehicle_id, $pickup_date, $dropoff_date) {
        $vehicle_id = (int)$vehicle_id;
        $pickup_date = $this->conn->real_escape_string($pickup_date);
        $dropoff_date = $this->conn->real_escape_string($dropoff_date);

        $query = "SELECT COUNT(*) as count FROM bookings 
                  WHERE vehicle_id = $vehicle_id 
                  AND booking_status IN ('pending', 'confirmed')
                  AND (
                      (pickup_date <= '$pickup_date' AND dropoff_date >= '$pickup_date')
                      OR (pickup_date <= '$dropoff_date' AND dropoff_date >= '$dropoff_date')
                      OR (pickup_date >= '$pickup_date' AND dropoff_date <= '$dropoff_date')
                  )";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['count'] == 0;
    }

    /**
     * Get total vehicles count
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM vehicles";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Search vehicles
     */
    public function searchVehicles($type = '', $min_price = 0, $max_price = 10000) {
        $conditions = ["v.approval_status = 'approved'", "v.availability_status = 'available'"];
        
        if (!empty($type)) {
            $type = $this->conn->real_escape_string($type);
            $conditions[] = "v.vehicle_type = '$type'";
        }
        
        $conditions[] = "v.price_per_day BETWEEN $min_price AND $max_price";
        
        $where = implode(' AND ', $conditions);
        
        $query = "SELECT v.*, u.full_name as owner_name 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE $where
                  ORDER BY v.created_at DESC";
        
        $result = $this->conn->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        return $vehicles;
    }
}