<?php
/**
 * BrowseController.php
 * Handles the logic for searching and filtering vehicles based on user input.
 */

class BrowseController
{
    private $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new Vehicle();
    }

    /**
     * Renders the comprehensive vehicle search and browsing interface.
     */
    public function index()
    {
        // Sanitize and extract filtering criteria from the global request.
        $filters = [
            'type' => $_GET['type'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'driver' => $_GET['driver'] ?? '',
            'seating' => $_GET['seating'] ?? '',
            'utility' => $_GET['utility'] ?? '',
            'pickup' => $_GET['pickup'] ?? '',
        ];

        // Retrieve available inventory matching the specified search parameters.
        $vehicles = $this->vehicleModel->searchVehicles($filters);

        // Dispatch the operational dataset to the presentation layer.
        require APP_PATH . '/views/vehicle/search_results.php';
    }

    /**
     * Retrieves and displays granular specifications for a unique vehicle entity.
     */
    public function details()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "/index.php?page=vehicle");
            exit;
        }

        $vehicle = $this->vehicleModel->getVehicleById($id);

        if (!$vehicle || $vehicle['approval_status'] !== 'approved') {
            // Security: Enforce business rules by restricting access to unapproved listings.
            header("Location: " . BASE_URL . "/index.php?page=vehicle");
            exit;
        }

        $reviews = $this->vehicleModel->getReviews($id);

        require APP_PATH . '/views/vehicle/details.php';
    }

    /**
     * Processes and persists user-submitted ratings and feedback.
     */
    public function submitReview()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicle_id = $_POST['vehicle_id'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];
            $renter_id = $_SESSION['user_id'];

            if ($this->vehicleModel->addReview($vehicle_id, $renter_id, $rating, $comment)) {
                header("Location: " . BASE_URL . "/index.php?page=vehicle&action=details&id=$vehicle_id&success=review_added");
            } else {
                header("Location: " . BASE_URL . "/index.php?page=vehicle&action=details&id=$vehicle_id&error=review_failed");
            }
        }
    }


}
