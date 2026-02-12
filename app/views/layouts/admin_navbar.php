<!--
  * Admin Navigation Bar
  * Secure top menu exclusively for administrators with links to management modules.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $page_title ?? 'Admin Panel - Pathek'; ?>
    </title>
    <!-- Google Fonts  -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./assets/css/base.css">
    <link rel="stylesheet" href="./assets/css/admin.css">

</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top admin-navbar">
        <div class="container-fluid px-4">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2"
                href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard">
                <span class="fs-4 fw-bold text-dark">Admin<span class="text-success">Panel</span></span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Admin Menu -->
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1 gap-lg-3">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_GET['action'] ?? 'dashboard') === 'dashboard' ? 'active text-success fw-bold' : ''; ?>"
                            href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>

                    <!-- Operations Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($_GET['action'] ?? '', ['vehicles', 'verifications', 'bookings']) ? 'active text-success fw-bold' : ''; ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-layer-group me-1"></i> Operations
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 animate slideIn">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=vehicles">
                                    <i class="fas fa-car text-muted"></i> All Vehicles
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=verifications">
                                    <i class="fas fa-user-check text-muted"></i> Verifications
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=bookings">
                                    <i class="fas fa-calendar-alt text-muted"></i> Bookings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=messages">
                                    <i class="fas fa-envelope text-muted"></i> User Messages
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Management Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($_GET['action'] ?? '', ['admins', 'users']) ? 'active text-success fw-bold' : ''; ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users-cog me-1"></i> Management
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 animate slideIn">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=admins">
                                    <i class="fas fa-user-shield text-muted"></i> Admins
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=users">
                                    <i class="fas fa-users text-muted"></i> Users & Owners
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- System Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($_GET['action'] ?? '', ['settings', 'logs']) ? 'active text-success fw-bold' : ''; ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cogs me-1"></i> System
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 animate slideIn">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=settings">
                                    <i class="fas fa-sliders-h text-muted"></i> Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=logs">
                                    <i class="fas fa-list-ul text-muted"></i> Logs
                                </a>
                            </li>
                        </ul>
                    </li>

                    <div class="vr d-none d-lg-block mx-2 h-50 my-auto text-muted"></div>

                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <div
                                class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center admin-avatar">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <span class="fw-semibold small d-none d-md-inline">
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                            <li>
                                <a class="dropdown-item rounded-2 mb-1"
                                    href="<?php echo BASE_URL; ?>/index.php?page=admin&action=editAdmin&id=<?php echo $_SESSION['user_id']; ?>">
                                    <i class="fas fa-id-card me-2 text-muted"></i> Edit Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger rounded-2"
                                    href="<?php echo BASE_URL; ?>/index.php?page=auth&action=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>