<?php
/**
 * WishlistController.php
 * Manages adding and removing vehicles from a user's wishlist.
 */

class WishlistController
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
     * Retrieves and displays the authenticated user's curated wishlist of vehicle assets.
     */
    public function index()
    {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT w.*, v.*, u.full_name as owner_name 
                  FROM wishlists w 
                  JOIN vehicles v ON w.vehicle_id = v.vehicle_id 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE w.user_id = ? 
                  ORDER BY w.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $wishlist_items = [];
        while ($row = $result->fetch_assoc()) {
            $wishlist_items[] = $row;
        }

        require APP_PATH . '/views/vehicle/wishlist.php';
    }

    /**
     * Persists a vehicle identifier to the user's personal wishlist, ensuring data uniqueness.
     */
    public function add()
    {
        $user_id = $_SESSION['user_id'];
        $vehicle_id = $_POST['vehicle_id'];

        // Ensure referential integrity by validating that the asset isn't already present in the wishlist.
        $check = $this->db->prepare("SELECT wishlist_id FROM wishlists WHERE user_id = ? AND vehicle_id = ?");
        $check->bind_param("ii", $user_id, $vehicle_id);
        $check->execute();

        if ($check->get_result()->num_rows == 0) {
            $stmt = $this->db->prepare("INSERT INTO wishlists (user_id, vehicle_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $vehicle_id);
            $stmt->execute();
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Executes the removal of a specific vehicle asset from the authenticated user's wishlist.
     */
    public function remove()
    {
        $user_id = $_SESSION['user_id'];
        $vehicle_id = $_POST['vehicle_id'];

        $stmt = $this->db->prepare("DELETE FROM wishlists WHERE user_id = ? AND vehicle_id = ?");
        $stmt->bind_param("ii", $user_id, $vehicle_id);
        $stmt->execute();

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}
