<?php

class OwnerController {

    public function dashboard() {

        // Security check
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
            header("Location: index.php?action=login");
            exit;
        }

        require "../app/views/owner/dashboard.php";
    }
}
