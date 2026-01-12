<?php
/**
 * Feedback Model
 */
class Feedback {
    private $db;

    public function __construct() {
        // Correct way to call a Singleton Database class
        $this->db = Database::getInstance();
    }

    public function getFeedbackByVehicle($vehicle_id) {
        $this->db->query("SELECT f.*, u.full_name FROM feedback f 
                          JOIN users u ON f.user_id = u.id 
                          WHERE f.vehicle_id = :v_id ORDER BY f.created_at DESC");
        $this->db->bind(':v_id', $vehicle_id);
        return $this->db->resultSet();
    }

    public function getAverageRating($vehicle_id) {
        $this->db->query("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                          FROM feedback WHERE vehicle_id = :v_id");
        $this->db->bind(':v_id', $vehicle_id);
        return $this->db->single();
    }
}