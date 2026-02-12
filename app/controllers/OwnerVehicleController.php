<?php
/**
 * OwnerVehicleController.php
 * Handles CRUD operations for vehicles owned by the logged-in user.
 */

class OwnerVehicleController
{
    private $vehicleModel;

    public function __construct()
    {
        // Enforce session-based authentication for vehicle owners.
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/index.php?page=auth&action=login");
            exit;
        }

        $role = $_SESSION['user_role'] ?? '';

        // Execute strict role-based access control (RBAC) to ensure resource authorization.
        if ($role === 'owner_pending') {
            header("Location: " . BASE_URL . "/index.php?page=verification"); // Redirect to "Pending" view
            exit;
        }

        if ($role === 'renter') {
            header("Location: " . BASE_URL . "/index.php?page=verification"); // Redirect to "Apply" view
            exit;
        }

        // Grant access exclusively to verified owners and platform administrators.
        if ($role !== 'owner_verified' && $role !== 'admin') {
            header("Location: " . BASE_URL . "/index.php");
            exit;
        }

        $this->vehicleModel = new Vehicle();
    }

    /**
     * Routing alias for the primary vehicle management interface.
     */
    public function index()
    {
        $this->myVehicles();
    }

    /**
     * List Owner's Vehicles
     */
    public function myVehicles()
    {
        $owner_id = $_SESSION['user_id'];
        // Retrieve all inventory associated with the authenticated user ID.
        require APP_PATH . '/views/owner/vehicle/index.php';
    }

    /**
     * Renders the user interface for vehicle registration.
     */
    public function create()
    {
        require APP_PATH . '/views/owner/vehicle/create.php';
    }

    /**
     * Store New Vehicle
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $owner_id = $_SESSION['user_id'];

            // Process multi-part file uploads for primary and secondary vehicle imagery.
            $image_path = $this->handleFileUpload('image', null);
            $image_path_2 = $this->handleFileUpload('image2', null);
            $image_path_3 = $this->handleFileUpload('image3', null);

            $data = [
                'vehicle_type' => $_POST['vehicle_type'],
                'brand' => $_POST['brand'],
                'model' => $_POST['model'],
                'year' => $_POST['year'],
                'plate_number' => $_POST['plate_number'],
                'price_per_day' => $_POST['price_per_day'],
                'seating_capacity' => $_POST['seating_capacity'] ?? NULL,
                'driver_available' => isset($_POST['driver_available']) ? 1 : 0,
                'utility_type' => $_POST['utility_type'] ?? 'Personal',
                'pickup_type' => $_POST['pickup_type'] ?? 'General',
                'description' => $_POST['description'],
                'image_path' => $image_path,
                'image_path_2' => $image_path_2,
                'image_path_3' => $image_path_3,
                'approval_status' => 'approved' // Automated approval workflow for streamlined vehicle onboarding.
            ];

            $result = $this->vehicleModel->addVehicle($data, $owner_id);

            if ($result['success']) {
                header("Location: " . BASE_URL . "/index.php?page=owner&action=dashboard&success=created");
                exit;
            } else {
                $error = $result['message'];
                require APP_PATH . '/views/owner/vehicle/create.php';
            }
        }
    }

    /**
     * Show Edit Form
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "/index.php?page=vehicle&action=myVehicles");
            exit;
        }

        $vehicle = $this->vehicleModel->getVehicleById($id);

        // Security: Verify asset ownership prior to granting edit privileges.
        if (!$vehicle || $vehicle['owner_id'] != $_SESSION['user_id']) {
            header("Location: " . BASE_URL . "/index.php?page=vehicle&action=myVehicles&error=unauthorized");
            exit;
        }

        require APP_PATH . '/views/owner/vehicle/edit.php';
    }

    /**
     * Update Vehicle
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['vehicle_id'];
            $owner_id = $_SESSION['user_id'];

            // Security: Re-validate ownership to maintain data integrity during the update transaction.
            $vehicle = $this->vehicleModel->getVehicleById($id);
            if (!$vehicle || $vehicle['owner_id'] != $owner_id) {
                header("Location: " . BASE_URL . "/index.php?page=vehicle&action=myVehicles");
                exit;
            }

            $data = [
                'vehicle_type' => $_POST['vehicle_type'],
                'brand' => $_POST['brand'],
                'model' => $_POST['model'],
                'year' => $_POST['year'],
                'plate_number' => $_POST['plate_number'],
                'price_per_day' => $_POST['price_per_day'],
                'seating_capacity' => $_POST['seating_capacity'] ?? NULL,
                'driver_available' => isset($_POST['driver_available']) ? 1 : 0,
                'utility_type' => $_POST['utility_type'] ?? 'Personal',
                'pickup_type' => $_POST['pickup_type'] ?? 'General',
                'description' => $_POST['description']
            ];

            // Manage conditional image asset updates, preserving existing data if no new files are provided.
            // If new image uploaded, update it, otherwise keep old

            if ($this->vehicleModel->updateVehicle($id, $data)) {
                header("Location: " . BASE_URL . "/index.php?page=owner&action=dashboard&success=updated");
            } else {
                header("Location: " . BASE_URL . "/index.php?page=owner&action=dashboard&error=update_failed");
            }
            exit;
        }
    }

    /**
     * Delete Vehicle
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $vehicle = $this->vehicleModel->getVehicleById($id);
            if ($vehicle && $vehicle['owner_id'] == $_SESSION['user_id']) {
                $this->vehicleModel->deleteVehicle($id);
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=vehicle&action=myVehicles&success=deleted");
        exit;
    }

    /**
     * Update the operational availability status of the vehicle asset.
     */
    public function updateStatus()
    {
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;

        if ($id && $status && in_array($status, ['available', 'maintenance'])) {
            $vehicle = $this->vehicleModel->getVehicleById($id);

            // Security: Validate administrative privileges over the target vehicle instance.
            if ($vehicle && $vehicle['owner_id'] == $_SESSION['user_id']) {
                // Prevent changing status if vehicle is booked
                if ($vehicle['availability_status'] === 'booked') {
                    header("Location: " . BASE_URL . "/index.php?page=vehicle&action=myVehicles&error=booked");
                    exit;
                }

                $this->vehicleModel->updateAvailabilityStatus($id, $status);
                $redirect = ($_GET['redirect'] ?? '') === 'dashboard' ? 'owner&action=dashboard' : 'vehicle&action=myVehicles';
                header("Location: " . BASE_URL . "/index.php?page=$redirect&success=status_updated");
                exit;
            }
        }

        $redirect = ($_GET['redirect'] ?? '') === 'dashboard' ? 'owner&action=dashboard' : 'owner&action=dashboard';
        header("Location: " . BASE_URL . "/index.php?page=$redirect&error=failed");
        exit;
    }

    private function handleFileUpload($inputName, $default = null)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/uploads/vehicles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES[$inputName]['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
                return 'uploads/vehicles/' . $fileName;
            }
        }
        return $default;
    }
}
