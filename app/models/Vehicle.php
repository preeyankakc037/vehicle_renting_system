<?php
/**
 * Vehicle.php
 * Model class for Vehicle data, including search, details retrieval, and
 * owner-specific vehicle management.
 */
/**
 * Vehicle Model
 */

class Vehicle
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Adds new vehicle
     * @return array
     */
    public function addVehicle($data, $owner_id): array
    {
        $owner_id = (int) $owner_id;

        $vehicle_type = $data['vehicle_type'];
        $brand = $data['brand'];
        $model = $data['model'];
        $year = (int) $data['year'];
        $plate_number = $data['plate_number'];

        // CHECK DUPLICATE PLATE
        $checkQuery = "SELECT COUNT(*) as count FROM vehicles WHERE plate_number = '$plate_number'";
        $checkResult = $this->conn->query($checkQuery);
        if ($checkResult && $checkResult->fetch_assoc()['count'] > 0) {
            return ['success' => false, 'message' => 'The license plate number you entered (' . $plate_number . ') already exists in our system. Please check the number and try again.'];
        }

        $price_per_day = (float) ($data['price_per_day'] ?? 0);
        $seating_capacity = !empty($data['seating_capacity']) ? (int) $data['seating_capacity'] : NULL;
        $driver_available = (int) ($data['driver_available'] ?? 0);
        $utility_type = $data['utility_type'] ?? 'Personal';
        $pickup_type = $data['pickup_type'] ?? 'General';
        $description = $data['description'];
        $image_path = $data['image_path'] ?? '';
        $image_path_2 = $data['image_path_2'] ?? '';
        $image_path_3 = $data['image_path_3'] ?? '';
        $approval_status = $data['approval_status'] ?? 'approved';

        // Construct vehicle_name from Brand + Model
        $vehicle_name = "$brand $model";

        $query = "INSERT INTO vehicles (
            owner_id, vehicle_name, vehicle_type, brand, model, year, 
            plate_number, price_per_day, seating_capacity, driver_available,
            utility_type, pickup_type, description, image_path, image_path_2, image_path_3, approval_status
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?
        )";

        $stmt = $this->conn->prepare($query);
        $null = NULL;
        $stmt->bind_param(
            "issssisdiisssssss",
            $owner_id,
            $vehicle_name,
            $vehicle_type,
            $brand,
            $model,
            $year,
            $plate_number,
            $price_per_day,
            $seating_capacity,
            $driver_available,
            $utility_type,
            $pickup_type,
            $description,
            $image_path,
            $image_path_2,
            $image_path_3,
            $approval_status
        );


        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Vehicle added successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to add vehicle: ' . $stmt->error];
        }

        return ['success' => false, 'message' => 'Failed to add vehicle due to unexpected error.'];
    }

    /**
     * Get all approved and available vehicles from VERIFIED owners
     * @return array
     */
    public function getAvailableVehicles(): array
    {
        $query = "SELECT v.*, u.full_name as owner_name, u.phone as owner_phone 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE v.approval_status = 'approved' 
                  AND v.availability_status = 'available'
                  AND u.user_role IN ('owner', 'owner_verified', 'admin') 
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
     * @param int $limit
     * @return array
     */
    public function getFeaturedVehicles($limit = 6): array
    {
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
     * @param int $vehicle_id
     * @return array|null
     */
    public function getVehicleById($vehicle_id): ?array
    {
        $vehicle_id = (int) $vehicle_id;
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
     * @param int $owner_id
     * @return array
     */
    public function getVehiclesByOwner($owner_id): array
    {
        $owner_id = (int) $owner_id;
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
     * @return array
     */
    public function getAllVehicles(): array
    {
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
    public function updateVehicle($vehicle_id, $data)
    {
        $vehicle_id = (int) $vehicle_id;
        $brand = $data['brand'];
        $model = $data['model'];
        $vehicle_name = "$brand $model";
        $vehicle_type = $data['vehicle_type'];
        $year = (int) $data['year'];
        $price_per_day = (float) $data['price_per_day'];
        $plate_number = $data['plate_number'];
        $seating_capacity = !empty($data['seating_capacity']) ? (int) $data['seating_capacity'] : NULL;
        $driver_available = (int) ($data['driver_available'] ?? 0);
        $utility_type = $data['utility_type'];
        $pickup_type = $data['pickup_type'];
        $description = $data['description'];
        $image_sql = "";
        $params = [
            $vehicle_name,
            $brand,
            $model,
            $vehicle_type,
            $year,
            $plate_number,
            $price_per_day,
            $seating_capacity,
            $driver_available,
            $utility_type,
            $pickup_type,
            $description
        ];
        $types = "ssssisdiisss";

        $image_blobs = [];

        if (isset($data['image_path']) && $data['image_path'] !== null) {
            $image_sql .= ", image_path = ?";
            $params[] = $data['image_path'];
            $types .= "s";
        }
        if (isset($data['image_path_2']) && $data['image_path_2'] !== null) {
            $image_sql .= ", image_path_2 = ?";
            $params[] = $data['image_path_2'];
            $types .= "s";
        }
        if (isset($data['image_path_3']) && $data['image_path_3'] !== null) {
            $image_sql .= ", image_path_3 = ?";
            $params[] = $data['image_path_3'];
            $types .= "s";
        }

        $query = "UPDATE vehicles SET 
                  vehicle_name = ?, brand = ?, model = ?, vehicle_type = ?, 
                  year = ?, plate_number = ?, price_per_day = ?, seating_capacity = ?, 
                  driver_available = ?, utility_type = ?, pickup_type = ?, 
                  description = ? $image_sql
                  WHERE vehicle_id = ?";

        $params[] = $vehicle_id;
        $types .= "i";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);



        return $stmt->execute();
    }

    /**
     * Update approval status
     * @param int $vehicle_id
     * @param string $status
     * @return bool
     */
    public function updateApprovalStatus($vehicle_id, $status): bool
    {
        $vehicle_id = (int) $vehicle_id;
        $status = $this->conn->real_escape_string($status);

        $query = "UPDATE vehicles SET approval_status = '$status' WHERE vehicle_id = $vehicle_id";
        return $this->conn->query($query);
    }

    /**
     * Update availability status
     * @param int $vehicle_id
     * @param string $status
     * @return bool
     */
    public function updateAvailabilityStatus($vehicle_id, $status): bool
    {
        $vehicle_id = (int) $vehicle_id;
        $status = $this->conn->real_escape_string($status);

        $query = "UPDATE vehicles SET availability_status = '$status' WHERE vehicle_id = $vehicle_id";
        return $this->conn->query($query);
    }

    /**
     * Delete vehicle
     * @param int $vehicle_id
     * @return bool
     */
    public function deleteVehicle($vehicle_id): bool
    {
        $vehicle_id = (int) $vehicle_id;
        $query = "DELETE FROM vehicles WHERE vehicle_id = $vehicle_id";
        return $this->conn->query($query);
    }

    /**
     * Check if vehicle is available for dates
     * @param int $vehicle_id
     * @param string $pickup_date
     * @param string $dropoff_date
     * @return bool
     */
    public function isAvailableForDates($vehicle_id, $pickup_date, $dropoff_date): bool
    {
        $vehicle_id = (int) $vehicle_id;
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
     * @return int
     */
    public function getTotalCount(): int
    {
        $query = "SELECT COUNT(*) as total FROM vehicles";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Search vehicles with filters (VERIFIED OWNERS ONLY)
     * @return array
     */
    public function searchVehicles($filters = []): array
    {
        // Base conditions: Approved Vehicle + (Available OR Maintenance) + Verified Owner
        $conditions = [
            "v.approval_status = 'approved'",
            "v.availability_status IN ('available', 'maintenance')",
            "u.user_role IN ('owner', 'owner_verified', 'admin')"
        ];

        if (!empty($filters['type'])) {
            $type = $this->conn->real_escape_string($filters['type']);
            $conditions[] = "v.vehicle_type = '$type'";
        }

        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $min = (float) $filters['min_price'];
            $conditions[] = "v.price_per_day >= $min";
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $max = (float) $filters['max_price'];
            $conditions[] = "v.price_per_day <= $max";
        }

        // Driver option
        if (isset($filters['driver']) && $filters['driver'] !== '') {
            $driver = (int) $filters['driver']; // 1 or 0
            $conditions[] = "v.driver_available = $driver";
        }

        // Seating Capacity (for cars/SUVs)
        if (!empty($filters['seating'])) {
            $seating = (int) $filters['seating'];
            $conditions[] = "v.seating_capacity >= $seating";
        }

        // Utility Type
        if (!empty($filters['utility'])) {
            $utility = $this->conn->real_escape_string($filters['utility']);
            $conditions[] = "v.utility_type = '$utility'";
        }

        // Pickup Type
        if (!empty($filters['pickup'])) {
            $pickup = $this->conn->real_escape_string($filters['pickup']);
            $conditions[] = "v.pickup_type = '$pickup'";
        }

        $where = implode(' AND ', $conditions);

        $query = "SELECT DISTINCT v.*, u.full_name as owner_name 
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

    /**
     * Get Reviews for a Vehicle
     * @param int $vehicle_id
     * @return array
     */
    public function getReviews($vehicle_id): array
    {
        $vehicle_id = (int) $vehicle_id;
        $query = "SELECT r.*, u.full_name as renter_name 
                  FROM reviews r 
                  JOIN users u ON r.renter_id = u.user_id 
                  WHERE r.vehicle_id = $vehicle_id 
                  ORDER BY r.created_at DESC";

        $result = $this->conn->query($query);
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    /**
     * Add Review
     * @return bool
     */
    public function addReview($vehicle_id, $renter_id, $rating, $comment): bool
    {
        $vehicle_id = (int) $vehicle_id;
        $renter_id = (int) $renter_id;
        $rating = (int) $rating;
        $comment = $this->conn->real_escape_string($comment);

        $query = "INSERT INTO reviews (vehicle_id, renter_id, rating, comment) VALUES ($vehicle_id, $renter_id, $rating, '$comment')";
        return $this->conn->query($query);
    }
}
