<?php
/**
 * Vehicle Condition Model
 */

class VehicleCondition {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Record vehicle condition
     */
    public function recordCondition($data) {
        $booking_id = (int)$data['booking_id'];
        $vehicle_id = (int)$data['vehicle_id'];
        $condition_type = $this->conn->real_escape_string($data['condition_type']);
        $fuel_level = $this->conn->real_escape_string($data['fuel_level']);
        $mileage = (int)$data['mileage'];
        $condition_notes = $this->conn->real_escape_string($data['condition_notes']);
        $recorded_by = (int)$data['recorded_by'];

        $query = "INSERT INTO vehicle_conditions (booking_id, vehicle_id, condition_type, 
                  fuel_level, mileage, condition_notes, recorded_by) 
                  VALUES ($booking_id, $vehicle_id, '$condition_type', '$fuel_level', 
                  $mileage, '$condition_notes', $recorded_by)";

        if ($this->conn->query($query)) {
            return ['success' => true, 'message' => 'Condition recorded successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to record condition'];
        }
    }

    /**
     * Get conditions for booking
     */
    public function getConditionsByBooking($booking_id) {
        $booking_id = (int)$booking_id;
        $query = "SELECT vc.*, u.full_name as recorded_by_name
                  FROM vehicle_conditions vc
                  JOIN users u ON vc.recorded_by = u.user_id
                  WHERE vc.booking_id = $booking_id
                  ORDER BY vc.created_at ASC";
        
        $result = $this->conn->query($query);
        $conditions = [];
        while ($row = $result->fetch_assoc()) {
            $conditions[] = $row;
        }
        return $conditions;
    }
}