<?php
/**
 * Contact Us Page
 * Displays contact information, location map, and a query submission form.
 */
$page_title = 'Contact Us - Pathek Vehicle Rental';
require_once APP_PATH . '/views/layouts/navbar.php';
?>


<link rel="stylesheet" href="./assets/css/home.css">

<!-- Contact Hero -->
<section class="contact-hero-section text-center text-white position-relative">
    <div class="contact-hero-bg">
        <div class="contact-hero-overlay"></div>
        <div class="container py-5 position-relative">
            <h1 class="display-5 fw-bold text-white">Get In Touch</h1>
            <p class="lead text-white">Have any questions? We'd love to hear from you - reach out to us anytime!</p>
        </div>
    </div>
</section>

<!-- Contact Info & Form -->
<section class="contact-section py-5 bg-light">
    <div class="container">
        <div class="row g-4">

            <!-- Contact Details -->
            <div class="col-lg-5">
                <div class="contact-info p-4 shadow-sm rounded bg-white h-100">
                    <h3 class="fw-bold mb-3">Contact Information</h3>

                    <p>
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        Budhanilkantha, Kathmandu, Nepal
                    </p>

                    <p>
                        <i class="fas fa-phone text-success me-2"></i>
                        +977 9801101924 / 01-5971616
                    </p>

                    <p>
                        <i class="fas fa-envelope text-success me-2"></i>
                        info@pathek.org
                    </p>

                    <hr>

                    <h5 class="fw-semibold mb-3">Follow Us</h5>
                    <a href="#" class="text-success me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-success me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-success"><i class="fab fa-twitter fa-lg"></i></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-box p-4 shadow-sm rounded bg-white">
                    <h3 class="fw-bold mb-3">Send Us a Message</h3>

                    <form action="<?= BASE_URL ?>/index.php?page=contact&action=submit" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject</label>
                            <input type="text" name="subject" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea name="message" rows="5" class="form-control form-control-lg" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="map-wrapper shadow rounded overflow-hidden">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14124.777051912444!2d85.3561944!3d27.7730303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19183416e9f1%3A0x6b872e61c33c3917!2sBudhanilkantha%2C%20Nepal!5e0!3m2!1sen!2snp!4v1704285821000!5m2!1sen!2snp"
                        width="100%" height="400" class="map-iframe" allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </div>
    </div>
</section>


<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>