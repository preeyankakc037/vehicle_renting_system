<?php
/**
 * OwnerController.php
 * Manages the owner dashboard, booking requests, and vehicle listings overview.
 */

class OwnerController
{
    private $db;

    public function __construct()
    {
        // Enforce session-based authentication for vehicle owners.
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            exit;
        }

        // Validate that the authenticated user possesses the required owner or administrative privileges.
        if (strpos($_SESSION['user_role'] ?? '', 'owner') === false && $_SESSION['user_role'] !== 'admin') {
            // Strict authorization check for owner-specific assets.
        }

        $this->db = Database::getInstance()->getConnection();
    }

    public function dashboard()
    {
        $owner_id = $_SESSION['user_id'];

        // Initialize Key Performance Indicators (KPIs) for the owner dashboard.
        $stats = [
            'total_vehicles' => 0,
            'active_vehicles' => 0,
            'pending_bookings' => 0
        ];

        // Total Vehicles
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM vehicles WHERE owner_id = ?");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $stats['total_vehicles'] = $stmt->get_result()->fetch_assoc()['count'];

        // Active Vehicles
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM vehicles WHERE owner_id = ? AND approval_status = 'approved' AND availability_status = 'available'");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $stats['active_vehicles'] = $stmt->get_result()->fetch_assoc()['count'];

        // Calculate the count of pending booking requests specifically for vehicles owned by this user.
        $query_pending = "SELECT COUNT(*) as count 
                          FROM bookings b 
                          JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                          WHERE v.owner_id = ? AND b.booking_status = 'pending'";
        $stmt = $this->db->prepare($query_pending);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $stats['pending_bookings'] = $stmt->get_result()->fetch_assoc()['count'];


        // Populate the inventory list with all vehicles registered under the authenticated user.
        $vehicles = [];
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE owner_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $res_v = $stmt->get_result();
        while ($row = $res_v->fetch_assoc()) {
            $vehicles[] = $row;
        }

        // Extract recent booking activities and associated renter information for managed vehicles.
        $bookings = [];
        $query_b = "SELECT b.*, v.vehicle_name, v.plate_number, u.full_name as renter_name, u.phone as renter_phone
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users u ON b.renter_id = u.user_id
                  WHERE v.owner_id = ?
                  ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($query_b);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $res_b = $stmt->get_result();
        while ($row = $res_b->fetch_assoc()) {
            $bookings[] = $row;
        }

        require APP_PATH . "/views/owner/dashboard.php";
    }

    /**
     * Manage Bookings for Owner's Vehicles
     */
    public function bookings()
    {
        $owner_id = $_SESSION['user_id'];

        $query = "SELECT b.*, v.vehicle_name, v.plate_number, u.full_name as renter_name, u.phone as renter_phone
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users u ON b.renter_id = u.user_id
                  WHERE v.owner_id = ?
                  ORDER BY b.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        require APP_PATH . "/views/owner/bookings.php";
    }

    /**
     * Delete Vehicle (Owner Action)
     */
    public function deleteVehicle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicle_id = $_POST['vehicle_id'];
            $owner_id = $_SESSION['user_id'];

            // Perform security verification to ensure the vehicle belongs to the authenticated owner.
            $check = $this->db->prepare("SELECT vehicle_id FROM vehicles WHERE vehicle_id = ? AND owner_id = ?");
            $check->bind_param("ii", $vehicle_id, $owner_id);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $del = $this->db->prepare("DELETE FROM vehicles WHERE vehicle_id = ?");
                $del->bind_param("i", $vehicle_id);
                if ($del->execute()) {
                    $_SESSION['success'] = "Vehicle deleted.";
                } else {
                    $_SESSION['error'] = "Failed to delete.";
                }
            } else {
                $_SESSION['error'] = "Unauthorized.";
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=owner&action=dashboard");
    }

    /**
     * Approve Booking
     */
    public function approveBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = $_POST['booking_id'];

            // Verify ownership first
            if ($this->verifyBookingOwnership($booking_id)) {
                $stmt = $this->db->prepare("UPDATE bookings SET booking_status = 'confirmed' WHERE booking_id = ?");
                $stmt->bind_param("i", $booking_id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Booking confirmed successfully.";
                } else {
                    $_SESSION['error'] = "Failed to confirm booking.";
                }
            } else {
                $_SESSION['error'] = "Unauthorized action.";
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=owner&action=dashboard");
    }

    /**
     * Reject Booking
     */
    public function rejectBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = $_POST['booking_id'];

            if ($this->verifyBookingOwnership($booking_id)) {
                $stmt = $this->db->prepare("UPDATE bookings SET booking_status = 'cancelled' WHERE booking_id = ?");
                $stmt->bind_param("i", $booking_id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Booking rejected/cancelled.";
                } else {
                    $_SESSION['error'] = "Failed to reject booking.";
                }
            } else {
                $_SESSION['error'] = "Unauthorized action.";
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=owner&action=dashboard");
    }

    /**
     * Helper: Verify Owner owns the vehicle for this booking
     */
    private function verifyBookingOwnership($booking_id)
    {
        $owner_id = $_SESSION['user_id'];
        $query = "SELECT b.booking_id 
                  FROM bookings b 
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                  WHERE b.booking_id = ? AND v.owner_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $booking_id, $owner_id);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }
}
?>