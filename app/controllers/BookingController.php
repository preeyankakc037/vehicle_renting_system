<?php
/**
 * Booking Controller
 */

class BookingController {
    private $bookingModel;
    private $vehicleModel;
    private $feedbackModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->vehicleModel = new Vehicle();
        $this->feedbackModel = new Feedback();
    }

    /**
     * Renter Dashboard
     */
    public function renterDashboard() {
        AuthController::checkRole('renter');
        
        $renter_id = $_SESSION['user_id'];
        $bookings = $this->bookingModel->getBookingsByRenter($renter_id);

        require_once APP_PATH . '/views/renter/dashboard.php';
    }

    /**
     * Create booking
     */
    public function create() {
        AuthController::requireLogin('Please login to book a vehicle');

        if ($_SESSION['user_role'] !== 'renter') {
            $_SESSION['error'] = 'Only renters can book vehicles';
            header('Location: /public/index.php');
            exit;
        }

        if (!isset($_GET['vehicle_id'])) {
            header('Location: /public/index.php?page=vehicle');
            exit;
        }

        $vehicle_id = $_GET['vehicle_id'];
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);

        if (!$vehicle || $vehicle['approval_status'] !== 'approved') {
            $_SESSION['error'] = 'Vehicle not available';
            header('Location: /public/index.php?page=vehicle');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pickup_date = $_POST['pickup_date'];
            $dropoff_date = $_POST['dropoff_date'];

            // Validate dates
            $today = date('Y-m-d');
            if ($pickup_date < $today) {
                $_SESSION['error'] = 'Pickup date cannot be in the past';
                header('Location: /public/index.php?page=booking&action=create&vehicle_id=' . $vehicle_id);
                exit;
            }

            if ($dropoff_date <= $pickup_date) {
                $_SESSION['error'] = 'Drop-off date must be after pickup date';
                header('Location: /public/index.php?page=booking&action=create&vehicle_id=' . $vehicle_id);
                exit;
            }

            // Check availability
            if (!$this->vehicleModel->isAvailableForDates($vehicle_id, $pickup_date, $dropoff_date)) {
                $_SESSION['error'] = 'Vehicle is not available for selected dates';
                header('Location: /public/index.php?page=booking&action=create&vehicle_id=' . $vehicle_id);
                exit;
            }

            // Calculate total
            $datetime1 = new DateTime($pickup_date);
            $datetime2 = new DateTime($dropoff_date);
            $interval = $datetime1->diff($datetime2);
            $total_days = $interval->days;
            $total_price = $total_days * $vehicle['price_per_day'];

            $booking_data = [
                'vehicle_id' => $vehicle_id,
                'renter_id' => $_SESSION['user_id'],
                'owner_id' => $vehicle['owner_id'],
                'pickup_date' => $pickup_date,
                'dropoff_date' => $dropoff_date,
                'total_days' => $total_days,
                'total_price' => $total_price
            ];

            $result = $this->bookingModel->createBooking($booking_data);

            if ($result['success']) {
                // Update vehicle status
                $this->vehicleModel->updateAvailabilityStatus($vehicle_id, 'booked');
                $_SESSION['success'] = 'Booking created successfully!';
                header('Location: /public/index.php?page=booking&action=renterDashboard');
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: /public/index.php?page=booking&action=create&vehicle_id=' . $vehicle_id);
            }
            exit;
        }

        require_once APP_PATH . '/views/renter/create-booking.php';
    }

    /**
     * View booking details
     */
    public function view() {
        AuthController::requireLogin();

        if (!isset($_GET['id'])) {
            header('Location: /public/index.php');
            exit;
        }

        $booking_id = $_GET['id'];
        $booking = $this->bookingModel->getBookingById($booking_id);

        if (!$booking) {
            $_SESSION['error'] = 'Booking not found';
            header('Location: /public/index.php');
            exit;
        }

        // Check access
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];

        if ($role !== 'admin' && $booking['renter_id'] != $user_id && $booking['owner_id'] != $user_id) {
            $_SESSION['error'] = 'Access denied';
            header('Location: /public/index.php');
            exit;
        }

        require_once APP_PATH . '/views/renter/booking-details.php';
    }

    /**
     * Cancel booking
     */
    public function cancel() {
        AuthController::requireLogin();

        if (isset($_GET['id'])) {
            $booking_id = $_GET['id'];
            $booking = $this->bookingModel->getBookingById($booking_id);

            if ($booking && $booking['renter_id'] == $_SESSION['user_id']) {
                if ($booking['booking_status'] === 'pending') {
                    $this->bookingModel->cancelBooking($booking_id);
                    
                    // Update vehicle status back to available
                    $this->vehicleModel->updateAvailabilityStatus($booking['vehicle_id'], 'available');
                    
                    $_SESSION['success'] = 'Booking cancelled successfully';
                } else {
                    $_SESSION['error'] = 'Cannot cancel this booking';
                }
            }
        }

        header('Location: /public/index.php?page=booking&action=renterDashboard');
        exit;
    }

    /**
     * Confirm booking (Owner)
     */
    public function confirm() {
        AuthController::checkRole('owner');

        if (isset($_GET['id'])) {
            $booking_id = $_GET['id'];
            $booking = $this->bookingModel->getBookingById($booking_id);

            if ($booking && $booking['owner_id'] == $_SESSION['user_id']) {
                $this->bookingModel->updateBookingStatus($booking_id, 'confirmed');
                $_SESSION['success'] = 'Booking confirmed';
            }
        }

        header('Location: /public/index.php?page=vehicle&action=ownerDashboard');
        exit;
    }

    /**
     * Complete booking (Owner)
     */
    public function complete() {
        AuthController::checkRole('owner');

        if (isset($_GET['id'])) {
            $booking_id = $_GET['id'];
            $booking = $this->bookingModel->getBookingById($booking_id);

            if ($booking && $booking['owner_id'] == $_SESSION['user_id']) {
                $this->bookingModel->completeBooking($booking_id);
                
                // Update vehicle status back to available
                $this->vehicleModel->updateAvailabilityStatus($booking['vehicle_id'], 'available');
                
                $_SESSION['success'] = 'Booking marked as completed';
            }
        }

        header('Location: /public/index.php?page=vehicle&action=ownerDashboard');
        exit;
    }

    /**
     * Submit feedback
     */
    public function feedback() {
        AuthController::checkRole('renter');

        if (!isset($_GET['booking_id'])) {
            header('Location: /public/index.php?page=booking&action=renterDashboard');
            exit;
        }

        $booking_id = $_GET['booking_id'];
        $booking = $this->bookingModel->getBookingById($booking_id);

        if (!$booking || $booking['renter_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Invalid booking';
            header('Location: /public/index.php?page=booking&action=renterDashboard');
            exit;
        }

        if (!$this->feedbackModel->canGiveFeedback($booking_id, $_SESSION['user_id'])) {
            $_SESSION['error'] = 'You cannot give feedback for this booking';
            header('Location: /public/index.php?page=booking&action=renterDashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'booking_id' => $booking_id,
                'renter_id' => $_SESSION['user_id'],
                'vehicle_id' => $booking['vehicle_id'],
                'rating' => $_POST['rating'],
                'comment' => $_POST['comment']
            ];

            $result = $this->feedbackModel->addFeedback($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: /public/index.php?page=booking&action=renterDashboard');
            exit;
        }

        require_once APP_PATH . '/views/renter/feedback.php';
    }
}