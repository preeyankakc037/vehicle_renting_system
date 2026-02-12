<!--
  * Main Footer
  * Standard footer with links, contacts, and copyright info displayed on public pages.
-->
<footer class="footer-modern">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="footer-logo-container mb-4">
                    <a href="<?php echo BASE_URL; ?>/index.php" class="footer-logo-link">
                        <img src="<?php echo BASE_URL; ?>/assets/images/Pathek Logo.png" alt="Pathek Logo"
                            class="footer-logo-image">
                    </a>
                </div>
                <p class="footer-description">Your trusted partner for vehicle rentals. Find the perfect vehicle for
                    your needs. Established in 2018, Pathek stands as your best vehicle rental solution.</p>
            </div>

            <div class="col-md-2 mb-4">
                <h6 class="footer-heading">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/index.php?page=vehicle">Browse Vehicles</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=login">Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=register">Register</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=logout">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="footer-heading">Contact Info</h6>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> Budhanilkantha, Nepal</li>
                    <li><i class="fas fa-phone"></i> 01-5971616 / 9801101924</li>
                    <li><i class="fas fa-envelope"></i> info@pathek.com</li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="footer-heading">Social Network</h6>
                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Pathek. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="footer-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>