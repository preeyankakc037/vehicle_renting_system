<?php
/**
 * BookingController.php
 * Controls the booking process, allowing renters to create new bookings
 * and view their booking history.
 */

class BookingController
{

    private $db;
    private $bookingModel;
    private $vehicleModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
        require_once APP_PATH . '/models/Booking.php';
        require_once APP_PATH . '/models/Vehicle.php';
        $this->bookingModel = new Booking();
        $this->vehicleModel = new Vehicle();
    }

    /**
     * Shows Booking Form
     */
    public function create()
    {
        $vehicle_id = $_GET['id'] ?? null;
        if (!$vehicle_id) {
            header("Location: " . BASE_URL . "/index.php?page=vehicle");
            exit;
        }

        // Fetch vehicle details
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        $vehicle = $stmt->get_result()->fetch_assoc();

        if (!$vehicle || $vehicle['availability_status'] !== 'available') {
            // Handle unavailable
            header("Location: " . BASE_URL . "/index.php?page=vehicle");
            exit;
        }

        require APP_PATH . '/views/booking/create.php';
    }

    /**
     * Store Booking
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Preparing booking data for insertion. 
            // Note: owner_id is handled by the model via vehicle relationship to maintain normalization.
            $data = [
                'vehicle_id' => $_POST['vehicle_id'],
                'renter_id' => $_SESSION['user_id'],
                'pickup_date' => $_POST['pickup_date'],
                'dropoff_date' => $_POST['dropoff_date'],
                'pickup_location' => $_POST['pickup_location'],
            ];

            // 1. Strict Availability Check
            if (!$this->vehicleModel->isAvailableForDates($data['vehicle_id'], $data['pickup_date'], $data['dropoff_date'])) {
                $error = "Sorry, this vehicle is not available for the selected dates. Please choose different dates.";
                // Re-render the form with error (need to fetch vehicle again)
                $vehicle = $this->vehicleModel->getVehicleById($data['vehicle_id']);
                require APP_PATH . '/views/booking/create.php';
                return;
            }

            // 2. Terms Acceptance Check
            if (!isset($_POST['terms_accepted'])) {
                $error = "You must accept the rental policies and guidelines to proceed.";
                $vehicle = $this->vehicleModel->getVehicleById($data['vehicle_id']);
                require APP_PATH . '/views/booking/create.php';
                return;
            }

            // Calculate days and price

            // Calculate actual days
            $start = new DateTime($data['pickup_date']);
            $end = new DateTime($data['dropoff_date']);
            $days = $end->diff($start)->days + 1;
            $data['total_days'] = $days;
            $data['total_price'] = $days * $_POST['price_per_day'];

            // Perform actual database insertion via Booking Model

            $result = $this->bookingModel->createBooking($data);

            if ($result['success']) {
                header("Location: " . BASE_URL . "/index.php?page=booking&action=myBookings&success=created");
            } else {
                // Error handling
                header("Location: " . BASE_URL . "/index.php?page=vehicle");
            }
        }
    }

    /**
     * Renter's "My Bookings" Page
     */
    public function myBookings()
    {
        $renter_id = $_SESSION['user_id'];
        $bookings = $this->bookingModel->getBookingsByRenter($renter_id);

        require APP_PATH . '/views/booking/my_bookings.php';
    }

    /**
     * Cancel Booking
     */
    public function cancel()
    {
        $booking_id = $_GET['id'] ?? null;
        if (!$booking_id) {
            header("Location: " . BASE_URL . "/index.php?page=booking&action=myBookings");
            exit;
        }

        $booking = $this->bookingModel->getBookingById($booking_id);

        // Security check: Only renter can cancel their own pending booking
        if (!$booking || $booking['renter_id'] != $_SESSION['user_id']) {
            header("Location: " . BASE_URL . "/index.php?page=booking&action=myBookings&error=unauthorized");
            exit;
        }

        if ($booking['booking_status'] === 'pending') {
            if ($this->bookingModel->cancelBooking($booking_id)) {
                header("Location: " . BASE_URL . "/index.php?page=booking&action=myBookings&success=cancelled");
            } else {
                header("Location: " . BASE_URL . "/index.php?page=booking&action=myBookings&error=failed");
            }
        } else {
            header("Location: " . BASE_URL . "/index.php?page=booking&action=myBookings&error=cannot_cancel");
        }
    }

    /**
     * Alias for default routing
     */
    public function renterDashboard()
    {
        $this->myBookings();
    }
}