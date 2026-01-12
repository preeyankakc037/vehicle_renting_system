<?php
/**
 * Admin Controller
 */

class AdminController {
    private $userModel;
    private $vehicleModel;
    private $bookingModel;
    private $policyModel;

    public function __construct() {
        $this->userModel = new User();
        $this->vehicleModel = new Vehicle();
        $this->bookingModel = new Booking();
        $this->policyModel = new RentalPolicy();
    }

    /**
     * Admin Dashboard
     */
    public function dashboard() {
        AuthController::checkRole('admin');

        // Get statistics
        $total_users = $this->userModel->countByRole('owner') + $this->userModel->countByRole('renter');
        $total_owners = $this->userModel->countByRole('owner');
        $total_renters = $this->userModel->countByRole('renter');
        $total_vehicles = $this->vehicleModel->getTotalCount();
        $total_bookings = $this->bookingModel->getTotalCount();

        // Get recent data
        $recent_bookings = $this->bookingModel->getRecentBookings(5);
        $pending_vehicles = $this->getPendingVehicles();

        require_once APP_PATH . '/views/admin/dashboard.php';
    }

    /**
     * Manage users
     */
    public function users() {
        AuthController::checkRole('admin');

        $users = $this->userModel->getAllUsers();
        require_once APP_PATH . '/views/admin/users.php';
    }

    /**
     * Block/Unblock user
     */
    public function toggleUserStatus() {
        AuthController::checkRole('admin');

        if (isset($_GET['id'])) {
            $user_id = $_GET['id'];
            $user = $this->userModel->getUserById($user_id);

            if ($user) {
                $new_status = ($user['status'] === 'active') ? 'blocked' : 'active';
                $this->userModel->updateUserStatus($user_id, $new_status);
                $_SESSION['success'] = 'User status updated';
            }
        }

        header('Location: /public/index.php?page=admin&action=users');
        exit;
    }

    /**
     * Manage vehicles
     */
    public function vehicles() {
        AuthController::checkRole('admin');

        $vehicles = $this->vehicleModel->getAllVehicles();
        require_once APP_PATH . '/views/admin/vehicles.php';
    }

    /**
     * Approve vehicle
     */
    public function approveVehicle() {
        AuthController::checkRole('admin');

        if (isset($_GET['id'])) {
            $vehicle_id = $_GET['id'];
            $this->vehicleModel->updateApprovalStatus($vehicle_id, 'approved');
            $_SESSION['success'] = 'Vehicle approved';
        }

        header('Location: /public/index.php?page=admin&action=vehicles');
        exit;
    }

    /**
     * Reject vehicle
     */
    public function rejectVehicle() {
        AuthController::checkRole('admin');

        if (isset($_GET['id'])) {
            $vehicle_id = $_GET['id'];
            $this->vehicleModel->updateApprovalStatus($vehicle_id, 'rejected');
            $_SESSION['success'] = 'Vehicle rejected';
        }

        header('Location: /public/index.php?page=admin&action=vehicles');
        exit;
    }

    /**
     * Manage bookings
     */
    public function bookings() {
        AuthController::checkRole('admin');

        $bookings = $this->bookingModel->getAllBookings();
        require_once APP_PATH . '/views/admin/bookings.php';
    }

    /**
     * Manage rental policies
     */
    public function policies() {
        AuthController::checkRole('admin');

        $policies = $this->policyModel->getAllPolicies();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action_type = $_POST['action_type'];

            if ($action_type === 'add') {
                $data = [
                    'policy_name' => $_POST['policy_name'],
                    'policy_description' => $_POST['policy_description'],
                    'policy_value' => $_POST['policy_value']
                ];
                $this->policyModel->addPolicy($data);
                $_SESSION['success'] = 'Policy added successfully';
            } elseif ($action_type === 'edit') {
                $data = [
                    'policy_name' => $_POST['policy_name'],
                    'policy_description' => $_POST['policy_description'],
                    'policy_value' => $_POST['policy_value']
                ];
                $this->policyModel->updatePolicy($_POST['policy_id'], $data);
                $_SESSION['success'] = 'Policy updated successfully';
            }

            header('Location: /public/index.php?page=admin&action=policies');
            exit;
        }

        require_once APP_PATH . '/views/admin/policies.php';
    }

    /**
     * Toggle policy status
     */
    public function togglePolicy() {
        AuthController::checkRole('admin');

        if (isset($_GET['id'])) {
            $this->policyModel->togglePolicyStatus($_GET['id']);
            $_SESSION['success'] = 'Policy status updated';
        }

        header('Location: /public/index.php?page=admin&action=policies');
        exit;
    }

    /**
     * Delete policy
     */
    public function deletePolicy() {
        AuthController::checkRole('admin');

        if (isset($_GET['id'])) {
            $this->policyModel->deletePolicy($_GET['id']);
            $_SESSION['success'] = 'Policy deleted';
        }

        header('Location: /public/index.php?page=admin&action=policies');
        exit;
    }

    /**
     * Get pending vehicles
     */
    private function getPendingVehicles() {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT v.*, u.full_name as owner_name 
                  FROM vehicles v 
                  JOIN users u ON v.owner_id = u.user_id 
                  WHERE v.approval_status = 'pending'
                  ORDER BY v.created_at DESC
                  LIMIT 5";
        
        $result = $db->query($query);
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        return $vehicles;
    }
}