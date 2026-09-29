<?php
/**
 * Master Frontend Navbar & Top Bar
 * GiveLife Style - Bright, Humanitarian, Clean Layout
 */

declare(strict_types=1);

$currentPage = basename($_SERVER['PHP_SELF']);
$phone = getSetting('site_phone', '+91 92340 55507');
$email = getSetting('site_email', 'contact@herbalboxfoundation.org');
$siteName = getSetting('site_name', 'Herbalbox Foundation');
$tagline = getSetting('site_tagline', 'Health • Education • Better Tomorrow');
$cinNumber = getSetting('cin_number', 'U86901BR2026NPL087665');
?>

<!-- Top Announcement Bar (GiveLife Charity Amber/Orange Header) -->
<div class="top-bar-charity d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 d-flex align-items-center flex-wrap gap-4">
                <div class="top-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Near Birla Open Minds School, Hajipur (844101)</span>
                </div>
                <div class="top-info-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>CALL : <a href="tel:9234055507">+91 92340 55507</a></span>
                </div>
                <div class="top-info-item">
                    <i class="fas fa-envelope"></i>
                    <span>EMAIL : <a href="mailto:<?= e($email); ?>"><?= e($email); ?></a></span>
                </div>
            </div>
            <div class="col-lg-4 text-end d-flex justify-content-end align-items-center gap-3">
                <div class="top-social-links">
                    <a href="https://www.facebook.com/profile.php?id=61594374894081" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/herbalboxfoundation/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/919234055507" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://youtube.com/@herbalboxfoundation" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
                <span class="top-cin-badge">CIN: <?= e($cinNumber); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar -->
<nav class="navbar navbar-expand-xl navbar-give-life sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL; ?>/index.php">
            <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="brand-logo-img">
            <div class="brand-text">
                <h1 class="brand-title mb-0">HERBALBOX FOUNDATION</h1>
                <span class="brand-tagline">Health • Education • Better Tomorrow</span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNgoNavbar" aria-controls="mainNgoNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fas fa-bars fa-lg text-dark"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNgoNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-xl-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/about.php">ABOUT</a>
                </li>
                
                <!-- Causes / Programs Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['healthcare.php', 'education.php', 'ayush.php', 'yoga.php', 'medical-camps.php', 'blood-donation.php']) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        CAUSES
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/healthcare.php"><i class="fas fa-stethoscope text-primary"></i> Healthcare Services</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/education.php"><i class="fas fa-graduation-cap text-success"></i> School Education (1st - 12th)</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/ayush.php"><i class="fas fa-leaf text-warning"></i> AYUSH & Herbal Medicine</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/yoga.php"><i class="fas fa-spa text-info"></i> Daily Yoga & Meditation</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/medical-camps.php"><i class="fas fa-clinic-medical text-danger"></i> Free Medical Camps</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/blood-donation.php"><i class="fas fa-tint text-danger"></i> Blood Donation Drives</a></li>
                    </ul>
                </li>

                <!-- Partnerships & MOUs Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['schools.php', 'doctors.php', 'hospitals.php', 'partnerships.php', 'mou.php']) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        PARTNERS
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/schools.php"><i class="fas fa-school text-primary"></i> School Partners & MOUs</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/doctors.php"><i class="fas fa-user-md text-success"></i> Doctor Network</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/hospitals.php"><i class="fas fa-hospital text-info"></i> Hospital Collaborations</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>/mou.php"><i class="fas fa-file-contract text-warning"></i> Public MOU Archive</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item font-weight-bold text-primary" href="<?= BASE_URL; ?>/partnerships.php"><i class="fas fa-handshake"></i> Become a Partner (Apply)</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'events.php' || $currentPage === 'event-details.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/events.php">EVENTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'gallery.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/gallery.php">GALLERY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'blog.php' || $currentPage === 'blog-details.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/blog.php">BLOG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : ''; ?>" href="<?= BASE_URL; ?>/contact.php">CONTACT</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-give-donate">
                    DONATE NOW
                </a>
            </div>
        </div>
    </div>
</nav>
