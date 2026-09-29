<?php
/**
 * Master Frontend Navbar & Top Bar
 */

declare(strict_types=1);

$currentPage = basename($_SERVER['PHP_SELF']);
$phone = getSetting('site_phone', '+91 98765 43210');
$email = getSetting('site_email', 'info@ngoseva.org');
$siteName = getSetting('site_name', 'Seva Arogya & Shiksha Foundation');
$tagline = getSetting('site_tagline', 'Serving Humanity Through Healthcare & Education');
$nitiId = getSetting('niti_aayog_id', 'DL/2018/0192847');
$tax80g = getSetting('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20');
?>

<!-- Top Announcement Bar -->
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 d-flex align-items-center gap-3">
                <span class="badge-reg"><i class="fas fa-shield-alt me-1"></i> NITI Aayog: <?= e($nitiId); ?></span>
                <span class="badge-reg"><i class="fas fa-hand-holding-usd me-1"></i> 80G Tax Exempt</span>
                <span class="text-white-50">|</span>
                <a href="tel:<?= e(str_replace(' ', '', $phone)); ?>"><i class="fas fa-phone-alt me-1 text-primary"></i> <?= e($phone); ?></a>
                <a href="mailto:<?= e($email); ?>"><i class="fas fa-envelope me-1 text-primary"></i> <?= e($email); ?></a>
            </div>
            <div class="col-md-5 text-end d-flex justify-content-end align-items-center gap-3">
                <a href="<?= BASE_URL; ?>/volunteer.php" class="text-white"><i class="fas fa-user-plus me-1 text-warning"></i> Join as Volunteer</a>
                <span class="text-white-50">|</span>
                <div class="d-inline-flex gap-2">
                    <?php if ($fb = getSetting('facebook_url')): ?><a href="<?= e($fb); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if ($ig = getSetting('instagram_url')): ?><a href="<?= e($ig); ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if ($yt = getSetting('youtube_url')): ?><a href="<?= e($yt); ?>" target="_blank"><i class="fab fa-youtube"></i></a><?php endif; ?>
                    <?php if ($tw = getSetting('twitter_url')): ?><a href="<?= e($tw); ?>" target="_blank"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar -->
<nav class="navbar navbar-expand-xl navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL; ?>/index.php">
            <div class="logo-icon">
                <i class="fas fa-hands-holding-child"></i>
            </div>
            <div class="brand-text">
                <h1><?= e($siteName); ?></h1>
                <span><?= e($tagline); ?></span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNgoNavbar" aria-controls="mainNgoNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fas fa-bars fa-lg text-primary"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNgoNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-xl-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/about.php">About Us</a>
                </li>
                
                <!-- Programs Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['healthcare.php', 'education.php', 'ayush.php', 'yoga.php', 'medical-camps.php', 'blood-donation.php']) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        Programs
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/healthcare.php"><i class="fas fa-stethoscope"></i> Healthcare Services</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/education.php"><i class="fas fa-graduation-cap"></i> School Education (1st - 12th)</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/ayush.php"><i class="fas fa-leaf"></i> AYUSH & Medical Care</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/yoga.php"><i class="fas fa-spa"></i> Yoga & Meditation</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/medical-camps.php"><i class="fas fa-clinic-medical"></i> Free Medical Camps</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/blood-donation.php"><i class="fas fa-tint text-danger"></i> Blood Donation Camps</a></li>
                    </ul>
                </li>

                <!-- Partnerships & MOUs Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['schools.php', 'doctors.php', 'hospitals.php', 'partnerships.php', 'mou.php']) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        Partnerships
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/schools.php"><i class="fas fa-school"></i> School Partners & MOUs</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/doctors.php"><i class="fas fa-user-md"></i> Doctor Network</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/hospitals.php"><i class="fas fa-hospital"></i> Hospitals & Diagnostic Centers</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/mou.php"><i class="fas fa-file-contract"></i> Public MOU Archive</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item font-weight-bold text-primary" href="<?= BASE_URL; ?>/partnerships.php"><i class="fas fa-handshake"></i> Become a Partner (Apply)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'events.php' || $currentPage === 'event-details.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/events.php">Events & Camps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'gallery.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/gallery.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'blog.php' || $currentPage === 'blog-details.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/blog.php">News & Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'products.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/products.php">Ayurvedic Store</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/contact.php">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-ngo-donate">
                    <i class="fas fa-heart"></i> Donate Now
                </a>
            </div>
        </div>
    </div>
</nav>
