<?php
/**
 * HomeController.php
 * Manages the default landing page and redirects users based on their role/session.
 */
/**
 * Home Controller
 * Orchestrates the application's primary landing page experience.
 */
class HomeController
{
    /**
     * Renders the landing page with dynamic featured vehicle data.
     */
    public function index()
    {
        $vehicleModel = new Vehicle();
        $featured_vehicles = $vehicleModel->getFeaturedVehicles(3);
        require APP_PATH . '/views/home/home_page.php';
    }
}
