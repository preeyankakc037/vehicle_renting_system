<?php
/**
 * Vehicle Controller
 */

class VehicleController {
    private $vehicleModel;
    private $feedbackModel;

    public function __construct() {
        $this->vehicleModel = new Vehicle();
        $this->feedbackModel = new Feedback();
    }

    /**
     * Homepage
     */
    public function home() {
        $featured_vehicles = $this->vehicleModel->getFeaturedVehicles(6);
        require_once APP_PATH . '/views/home/home.php';
    }

    /**
     * Browse all vehicles
     */
    public function index() {
        $vehicles = $this->vehicleModel->getAvailableVehicles();
        require_once APP_PATH . '/views/renter/browse_vehicles.php';
    }

    /**
     * View vehicle details
     */
    public function view() {
        if (!isset($_GET['id'])) {
            header('Location: ' . BASE_URL . '/index.php?page=vehicle');
            exit;
        }

        $vehicle_id = $_GET['id'];
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);
        
        if (!$vehicle) {
            $_SESSION['error'] = 'Vehicle not found';
            header('Location: ' . BASE_URL . '/index.php?page=vehicle');
            exit;
        }

        // Get feedback
        $feedback_list = $this->feedbackModel->getFeedbackByVehicle($vehicle_id);
        $rating_info = $this->feedbackModel->getAverageRating($vehicle_id);

        require_once APP_PATH . '/views/home/vehicle-details.php';
    }

    /**
     * Search vehicles
     */
    public function search() {
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
        $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 10000;

        $vehicles = $this->vehicleModel->searchVehicles($type, $min_price, $max_price);
        require_once APP_PATH . '/views/home/browse.php';
    }

    /**
     * Owner Dashboard
     */
    public function ownerDashboard() {
        AuthController::checkRole('owner');
        
        $owner_id = $_SESSION['user_id'];
        $vehicles = $this->vehicleModel->getVehiclesByOwner($owner_id);
        
        $bookingModel = new Booking();
        $bookings = $bookingModel->getBookingsByOwner($owner_id);

        require_once APP_PATH . '/views/owner/dashboard.php';
    }

    /**
     * Add vehicle form
     */
    public function add() {
        AuthController::checkRole('owner');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vehicle_name' => $_POST['vehicle_name'],
                'vehicle_type' => $_POST['vehicle_type'],
                'model' => $_POST['model'],
                'year' => $_POST['year'],
                'plate_number' => $_POST['plate_number'],
                'price_per_day' => $_POST['price_per_day'],
                'description' => $_POST['description'],
                'image_path' => $this->handleImageUpload()
            ];

            $result = $this->vehicleModel->addVehicle($data, $_SESSION['user_id']);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: ' . BASE_URL . '/index.php?page=vehicle&action=ownerDashboard');
            exit;
        }

        require_once APP_PATH . '/views/owner/add-vehicle.php';
    }

    /**
     * Edit vehicle
     */
    public function edit() {
        AuthController::checkRole('owner');

        if (!isset($_GET['id'])) {
            header('Location: ' . BASE_URL . '/index.php?page=vehicle&action=ownerDashboard');
            exit;
        }

        $vehicle_id = $_GET['id'];
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);

        // Check if owner owns this vehicle
        if ($vehicle['owner_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied';
            header('Location: ' . BASE_URL . '/index.php?page=vehicle&action=ownerDashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vehicle_name' => $_POST['vehicle_name'],
                'vehicle_type' => $_POST['vehicle_type'],
                'model' => $_POST['model'],
                'year' => $_POST['year'],
                'price_per_day' => $_POST['price_per_day'],
                'description' => $_POST['description']
            ];

            if ($this->vehicleModel->updateVehicle($vehicle_id, $data)) {
                $_SESSION['success'] = 'Vehicle updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update vehicle';
            }

            header('Location: ' . BASE_URL . '/index.php?page=vehicle&action=ownerDashboard');
            exit;
        }

        require_once APP_PATH . '/views/owner/edit-vehicle.php';
    }

    /**
     * Delete vehicle
     */
    public function delete() {
        AuthController::checkRole('owner');

        if (isset($_GET['id'])) {
            $vehicle_id = $_GET['id'];
            $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);

            // Check ownership
            if ($vehicle['owner_id'] == $_SESSION['user_id']) {
                if ($this->vehicleModel->deleteVehicle($vehicle_id)) {
                    $_SESSION['success'] = 'Vehicle deleted successfully';
                } else {
                    $_SESSION['error'] = 'Failed to delete vehicle';
                }
            }
        }

        header('Location: ' . BASE_URL . '/index.php?page=vehicle&action=ownerDashboard');
        exit;
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload() {
        if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === 0) {
            $upload_dir = ASSETS_PATH . '/images/vehicles/';
            
            // Create directory if not exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['vehicle_image']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('vehicle_') . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $upload_path)) {
                return 'assets/images/vehicles/' . $new_filename;
            }
        }
        return 'assets/images/default-vehicle.jpg';
    }
}