<!--
  * Main Navigation Bar
  * Top navigation available to all users. Shows different links based on role (Renter/Owner/Guest).
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Pathek'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./assets/css/base.css">

</head>

<body>

    <nav class="navbar navbar-expand-lg shadow-sm py-3">
        <?php $current_page = $_GET['page'] ?? 'home'; ?>
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand logo-container" href="<?php echo BASE_URL; ?>/index.php">
                <img src="<?php echo BASE_URL; ?>/assets/images/Pathek Logo.png" alt="Pathek Logo" class="logo-image">
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">

                    <!-- Public Links -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php echo ($_GET['page'] ?? 'home') === 'home' ? 'active' : ''; ?>"
                            href="<?php echo BASE_URL; ?>/index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php echo ($_GET['page'] ?? '') === 'vehicle' ? 'active' : ''; ?>"
                            href="<?php echo BASE_URL; ?>/index.php?page=vehicle">
                            Browse Vehicles
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id']) && strpos($_SESSION['user_role'] ?? '', 'owner') !== false): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-success <?php echo ($_GET['page'] ?? '') === 'owner' ? 'active' : ''; ?>"
                                href="<?php echo BASE_URL; ?>/index.php?page=owner&action=dashboard">
                                Owner Dashboard
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php echo ($_GET['page'] ?? '') === 'contact' ? 'active' : ''; ?>"
                            href="<?php echo BASE_URL; ?>/index.php?page=contact">
                            Contact Us
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <!-- Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1 user-icon"></i>
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow">

                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <li>
                                        <a class="dropdown-item"
                                            href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard">
                                            <i class="fas fa-chart-line me-2"></i> Admin Dashboard
                                        </a>
                                    </li>

                                <?php else: ?>

                                    <!-- Common User Links (Available for both the  Renters AND Owners) -->
                                    <li>
                                        <a class="dropdown-item"
                                            href="<?php echo BASE_URL; ?>/index.php?page=booking&action=myBookings">
                                            <i class="fas fa-calendar-alt me-2"></i> My Bookings
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/index.php?page=wishlist">
                                            <i class="fas fa-heart me-2"></i> My Wishlist
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <a class="dropdown-item"
                                        href="<?php echo BASE_URL; ?>/index.php?page=auth&action=profile">
                                        <i class="fas fa-user me-2"></i> My Profile
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger"
                                        href="<?php echo BASE_URL; ?>/index.php?page=auth&action=logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php else: ?>

                        <!-- Sign In Button -->
                        <li class="nav-item">
                            <a class="btn btn-signin text-white px-4"
                                href="<?php echo BASE_URL; ?>/index.php?page=auth&action=login">
                                Sign In
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>