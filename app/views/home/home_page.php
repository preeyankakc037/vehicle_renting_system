<?php
/**
 * Home Page
 * Main landing page featuring key services, featured vehicles, and system introduction.
 */
$page_title = 'Home - Pathek Vehicle Rental';
require_once APP_PATH . '/views/layouts/navbar.php';
?>

<!-- Home Page CSS -->
<link rel="stylesheet" href="./assets/css/home.css">

<!-- Hero Section of the homepage -->
<section class="hero-section-pathek">
    <div class="hero-container">
        <div class="hero-row">
            <!-- Left Side - Title and Description -->
            <div class="hero-col-left">
                <h1 class="hero-title-main">
                    Fair Fares <br><span class="text-success">Strong Journeys</span>
                </h1>
                <p class="hero-description">
                    Discover the perfect vehicle for your journey. From luxury cars to reliable bikes,
                    find everything you need for an unforgettable experience.
                </p>
                <div class="hero-buttons">
                    <a href="<?php echo BASE_URL; ?>/index.php?page=vehicle" class="btn-primary-custom">
                        <i class="fas fa-search"></i>Browse Vehicles
                    </a>
                    <?php
                    $list_link = BASE_URL . "/index.php?page=verification";
                    $button_text = "List Your Vehicle";
                    $button_icon = "fas fa-plus";
                    if (isset($_SESSION['user_role']) && strpos($_SESSION['user_role'], 'owner') !== false) {
                        $list_link = BASE_URL . "/index.php?page=owner&action=dashboard";
                        $button_text = "Owner Dashboard";
                        $button_icon = "fas fa-tachometer-alt";
                    }
                    ?>
                    <a href="<?php echo $list_link; ?>" class="btn-light-grey-custom btn-list-vehicle-border">
                        <i class="<?php echo $button_icon; ?>"></i><?php echo $button_text; ?>
                    </a>
                </div>
            </div>

            <!-- Right Side - Homepage Image -->
            <div class="hero-col-right">
                <div class="hero-image-container">
                    <img src="<?php echo BASE_URL; ?>/assets/images/front.png" alt="Vehicle Rental" class="hero-image">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works-section">
    <div class="how-it-works-container">
        <div class="how-it-works-row">
            <?php
            $steps = [
                'Choose Your Favorite Vehicle' => 'Select your preferred vehicle tailored to your journey.',
                'Make a Booking' => 'Easy booking via app or a simple phone call.',
                'Pick-Up Location & Date' => 'Choose location, date, and time.',
                'Sit Back & Relax' => 'Enjoy a safe and smooth journey with Pathek.'
            ];
            $i = 1;
            foreach ($steps as $title => $desc):
                ?>
                <div class="step-col">
                    <div class="step-item">
                        <div class="step-number-circle"><?= $i++ ?></div>
                        <h4 class="step-title"><?= $title ?></h4>
                        <p class="step-description"><?= $desc ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion faq-accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                                How can I rent a car from Pathek?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Renting a car is simple. Browse through our available vehicles, choose the one that
                                suits your needs, and click "Book Now". You can also call us directly for assistance.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq2">
                                What documents do I need to rent a vehicle?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You need a valid driver's license and a government-issued ID (Citizenship or Passport).
                                Some vehicles may require a security deposit.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq3">
                                Can I list my own vehicle for rent?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! Join our platform as an owner, complete the verification process, and once
                                approved, you can start listing your vehicles and earning.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq4">
                                Is there a cancellation fee?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Cancellation policies vary depending on the vehicle and the timing. Generally, if you
                                cancel 24 hours before the pickup, no fees apply.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->

<script>
    function selectService(el) {
        document.querySelectorAll('.service-option-card').forEach(function (card) {
            card.classList.remove('active');
        });
        el.classList.add('active');
    }
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>