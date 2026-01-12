<footer class="footer-modern">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="footer-title"><i class="fas fa-car"></i> <span class="text-green">Spark</span>Rent</h5>
                <p class="footer-description">Your trusted partner for vehicle rentals. Find the perfect vehicle for your needs. Established in 1996, SparkRent stands as your best vehicle rental solution.</p>
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
                    <li><i class="fas fa-map-marker-alt"></i> 123 Main St, City, Country</li>
                    <li><i class="fas fa-phone"></i> 01-5971616 / 9801101924</li>
                    <li><i class="fas fa-envelope"></i> info@sparkrent.com</li>
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
                <h6 class="footer-heading mt-4">Download</h6>
                <div class="app-download">
                    <a href="#" class="app-btn">
                        <i class="fab fa-apple"></i> App Store
                    </a>
                    <a href="#" class="app-btn">
                        <i class="fab fa-google-play"></i> Google Play
                    </a>
                </div>
            </div>
        </div>
        
        <hr class="footer-divider">
        
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright">Copyright <?php echo date('Y'); ?> - SparkRent. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="footer-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer-modern {
    background-color: #1a1a1a;
    color: rgba(255,255,255,0.8);
    padding: 60px 0 20px;
    margin-top: 80px;
}

.footer-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

.footer-description {
    line-height: 1.8;
    font-size: 0.95rem;
}

.footer-heading {
    font-weight: 700;
    color: white;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 1px;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.footer-links a:hover {
    color: #00d563;
    padding-left: 5px;
}

.footer-contact {
    list-style: none;
    padding: 0;
}

.footer-contact li {
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 0.95rem;
}

.footer-contact i {
    color: #00d563;
    margin-top: 3px;
}

.social-links {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.social-icon {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s;
}

.social-icon:hover {
    background: #00d563;
    transform: translateY(-3px);
}

.app-download {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.app-btn {
    background: rgba(255,255,255,0.1);
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.app-btn:hover {
    background: #00d563;
    color: white;
    transform: translateX(5px);
}

.app-btn i {
    font-size: 1.25rem;
}

.footer-divider {
    border-color: rgba(255,255,255,0.1);
    margin: 2rem 0 1.5rem;
}

.footer-bottom {
    padding-top: 1rem;
}

.copyright {
    margin: 0;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.6);
}

.footer-link {
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    font-size: 0.9rem;
}

.footer-link:hover {
    color: #00d563;
}

.text-green {
    color: #00d563;
}
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>