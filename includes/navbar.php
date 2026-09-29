<?php
/**
 * Master Frontend Navbar & Top Bar
 */

declare(strict_types=1);

$currentPage = basename($_SERVER['PHP_SELF']);
$phone = getSetting('site_phone', '+91 92340 55507');
$email = getSetting('site_email', 'contact@herbalboxfoundation.org');
$siteName = getSetting('site_name', 'Herbalbox Foundation');
$tagline = getSetting('site_tagline', 'Health • Education • Better Tomorrow');
$cinNumber = getSetting('cin_number', 'U86901BR2026NPL087665');
?>

<!-- Top Announcement Bar -->
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 d-flex align-items-center gap-3">
                <span class="badge-reg"><i class="fas fa-certificate text-warning me-1"></i> CIN: <?= e($cinNumber); ?></span>
                <span class="badge-reg"><i class="fas fa-building text-info me-1"></i> Patna, Bihar</span>
                <span class="text-white-50">|</span>
                <a href="tel:9234055507"><i class="fas fa-phone-alt me-1 text-success"></i> +91 92340 55507</a>
                <a href="mailto:<?= e($email); ?>"><i class="fas fa-envelope me-1 text-info"></i> <?= e($email); ?></a>
            </div>
            <div class="col-md-5 text-end d-flex justify-content-end align-items-center gap-3">
                <a href="https://wa.me/919234055507" target="_blank" class="text-success fw-bold"><i class="fab fa-whatsapp me-1"></i> +91 92340 55507</a>
                <span class="text-white-50">|</span>
                <div class="d-inline-flex gap-2">
                    <a href="https://www.facebook.com/profile.php?id=61594374894081" target="_blank" title="Facebook"><i class="fab fa-facebook-f text-primary"></i></a>
                    <a href="https://www.instagram.com/herbalboxfoundation/" target="_blank" title="Instagram"><i class="fab fa-instagram text-danger"></i></a>
                    <a href="https://wa.me/919234055507" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp text-success"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar -->
<nav class="navbar navbar-expand-xl navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL; ?>/index.php">
            <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="brand-logo-img shadow-sm rounded-circle">
            <div class="brand-text">
                <h1 class="mb-0 fw-bold fs-4 text-gradient-primary">HERBALBOX FOUNDATION</h1>
                <span class="brand-tagline fw-semibold text-uppercase tracking-wider">Health • Education • Better Tomorrow</span>
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
