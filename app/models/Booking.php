<?php
/**
 * Booking.php
 * Model class representing a Booking. Handles database operations for
 * creating, updating, and retrieving booking records.
 */
/**
 * Booking Model
 */

class Booking
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Create new booking
     * @param array $data
     * @return array
     */
    public function createBooking($data): array
    {
        $vehicle_id = (int) $data['vehicle_id'];
        $renter_id = (int) $data['renter_id'];
        $pickup_date = $this->conn->real_escape_string($data['pickup_date']);
        $dropoff_date = $this->conn->real_escape_string($data['dropoff_date']);
        $total_price = (float) $data['total_price'];

        $query = "INSERT INTO bookings (vehicle_id, renter_id, pickup_date, 
                  dropoff_date, total_price) 
                  VALUES ($vehicle_id, $renter_id, '$pickup_date', 
                  '$dropoff_date', $total_price)";

        if ($this->conn->query($query)) {
            return ['success' => true, 'message' => 'Booking created successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to create booking'];
        }
    }

    /**
     * Get booking by ID
     * @param int $booking_id
     * @return array|null
     */
    public function getBookingById($booking_id): ?array
    {
        $booking_id = (int) $booking_id;
        $query = "SELECT b.*, v.vehicle_name, v.vehicle_type, v.model, 
                  r.full_name as renter_name, r.phone as renter_phone, r.email as renter_email,
                  o.full_name as owner_name, o.phone as owner_phone
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users r ON b.renter_id = r.user_id
                  JOIN users o ON v.owner_id = o.user_id
                  WHERE b.booking_id = $booking_id";

        $result = $this->conn->query($query);
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Get bookings by renter
     * @param int $renter_id
     * @return array
     */
    public function getBookingsByRenter($renter_id): array
    {
        $renter_id = (int) $renter_id;
        $query = "SELECT b.*, v.vehicle_name, v.vehicle_type, v.model, v.image_path,
                  o.full_name as owner_name, o.phone as owner_phone
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users o ON v.owner_id = o.user_id
                  WHERE b.renter_id = $renter_id
                  ORDER BY b.created_at DESC";

        $result = $this->conn->query($query);
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    /**
     * Get bookings by owner
     * @param int $owner_id
     * @return array
     */
    public function getBookingsByOwner($owner_id): array
    {
        $owner_id = (int) $owner_id;
        $query = "SELECT b.*, v.vehicle_name, v.vehicle_type, v.plate_number,
                  r.full_name as renter_name, r.phone as renter_phone, r.email as renter_email
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users r ON b.renter_id = r.user_id
                  WHERE b.owner_id = $owner_id
                  ORDER BY b.created_at DESC";

        $result = $this->conn->query($query);
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    /**
     * Get all bookings (admin)
     * @return array
     */
    public function getAllBookings(): array
    {
        $query = "SELECT b.*, v.vehicle_name, v.plate_number,
                  r.full_name as renter_name, o.full_name as owner_name
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users r ON b.renter_id = r.user_id
                  JOIN users o ON v.owner_id = o.user_id
                  ORDER BY b.created_at DESC";

        $result = $this->conn->query($query);
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    /**
     * Update booking status
     * @param int $booking_id
     * @param string $status
     * @return bool
     */
    public function updateBookingStatus($booking_id, $status): bool
    {
        $booking_id = (int) $booking_id;
        $status = $this->conn->real_escape_string($status);

        $query = "UPDATE bookings SET booking_status = '$status' WHERE booking_id = $booking_id";
        return $this->conn->query($query);
    }

    /**
     * Cancel booking
     * @param int $booking_id
     * @return bool
     */
    public function cancelBooking($booking_id): bool
    {
        return $this->updateBookingStatus($booking_id, 'cancelled');
    }

    /**
     * Complete booking
     * @param int $booking_id
     * @return bool
     */
    public function completeBooking($booking_id): bool
    {
        return $this->updateBookingStatus($booking_id, 'completed');
    }

    /**
     * Get total bookings count
     * @return int
     */
    public function getTotalCount(): int
    {
        $query = "SELECT COUNT(*) as total FROM bookings";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Get recent bookings
     * @param int $limit
     * @return array
     */
    public function getRecentBookings($limit = 5): array
    {
        $query = "SELECT b.*, v.vehicle_name, r.full_name as renter_name
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  JOIN users r ON b.renter_id = r.user_id
                  ORDER BY b.created_at DESC
                  LIMIT $limit";

        $result = $this->conn->query($query);
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }
}