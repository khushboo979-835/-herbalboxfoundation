<?php
/**
 * Master Frontend Footer Template
 */

declare(strict_types=1);

$siteName = getSetting('site_name', 'Herbalbox Foundation');
$tagline = getSetting('site_tagline', 'Health • Education • Better Tomorrow');
$phone = getSetting('site_phone', '+91 92340 55507');
$email = getSetting('site_email', 'contact@herbalboxfoundation.org');
$address = getSetting('site_address', 'Herbalbox Foundation, Near Birla Open Minds International School, Konhara Road, Hajipur, Vaishali, Bihar - 844101');
$whatsappNum = '919234055507';
$whatsappMsg = urlencode('Hello Herbalbox Foundation, I would like to know more about your health and education programs.');
$cinNo = getSetting('cin_number', 'U86901BR2026NPL087665');
?>

<!-- Master Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: About NGO -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="brand-logo-img rounded-circle bg-white p-1 shadow-sm" style="width: 52px; height: 52px; object-fit: contain;">
                    <div>
                        <h4 class="text-white mb-0 fs-5 fw-bold"><?= e($siteName); ?></h4>
                        <small class="text-warning text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.75rem;"><?= e($tagline); ?></small>
                    </div>
                </div>
                <p class="text-white-50 mb-3">Herbalbox Foundation is a professionally established organization incorporated under the Companies Act, 2013. We are committed to creating meaningful social impact through community welfare, free medical camps, NCERT digital education, and holistic AYUSH wellness.</p>
                <div class="p-3 rounded-3 mb-3 legal-box" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);">
                    <small class="d-block text-white-50"><strong class="text-white">CIN:</strong> <span class="text-warning fw-bold"><?= e($cinNo); ?></span></small>
                    <small class="d-block text-white-50"><strong class="text-white">Legal Status:</strong> Company Limited by Guarantee</small>
                    <small class="d-block text-white-50"><strong class="text-white">Regd Office:</strong> Patna, Bihar, India</small>
                    <small class="d-block text-white-50"><strong class="text-white">Incorporated:</strong> 29 August 2026</small>
                </div>
                <div class="footer-social">
                    <a href="https://www.facebook.com/profile.php?id=61594374894081" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/herbalboxfoundation/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/919234055507" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://youtube.com/@herbalboxfoundation" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="https://linkedin.com/company/herbalboxfoundation" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Col 2: Healthcare & AYUSH -->
            <div class="col-lg-2 col-md-6">
                <h5>Our Initiatives</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/healthcare.php"><i class="fas fa-angle-right"></i> Healthcare Services</a></li>
                    <li><a href="<?= BASE_URL; ?>/medical-camps.php"><i class="fas fa-angle-right"></i> Free Medical Camps</a></li>
                    <li><a href="<?= BASE_URL; ?>/blood-donation.php"><i class="fas fa-angle-right"></i> Blood Donation Camps</a></li>
                    <li><a href="<?= BASE_URL; ?>/education.php"><i class="fas fa-angle-right"></i> NCERT School Education</a></li>
                    <li><a href="<?= BASE_URL; ?>/ayush.php"><i class="fas fa-angle-right"></i> AYUSH & Ayurveda</a></li>
                    <li><a href="<?= BASE_URL; ?>/yoga.php"><i class="fas fa-angle-right"></i> Yoga & Meditation</a></li>
                </ul>
            </div>

            <!-- Col 3: Partnerships & Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h5>Partnerships & MOUs</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/schools.php"><i class="fas fa-angle-right"></i> School Partners & MOUs</a></li>
                    <li><a href="<?= BASE_URL; ?>/doctors.php"><i class="fas fa-angle-right"></i> Doctor Network</a></li>
                    <li><a href="<?= BASE_URL; ?>/hospitals.php"><i class="fas fa-angle-right"></i> Hospital Collaborations</a></li>
                    <li><a href="<?= BASE_URL; ?>/mou.php"><i class="fas fa-angle-right"></i> Public MOU Archive</a></li>
                    <li><a href="<?= BASE_URL; ?>/partnerships.php"><i class="fas fa-angle-right"></i> CSR Partnership Application</a></li>
                    <li><a href="<?= BASE_URL; ?>/volunteer.php"><i class="fas fa-angle-right"></i> Join as Volunteer</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact & Office -->
            <div class="col-lg-3 col-md-6">
                <h5>Operational Campus</h5>
                <p class="text-white-50 mb-2 small"><i class="fas fa-map-marker-alt text-warning me-2"></i> Near Birla Open Minds International School, Konhara Road, Hajipur, Vaishali, Bihar - 844101</p>
                <p class="text-white-50 mb-2 small"><i class="fas fa-phone-alt text-success me-2"></i> <a href="tel:9234055507" class="text-white text-decoration-none fw-bold">+91 92340 55507</a></p>
                <p class="text-white-50 mb-2 small"><i class="fab fa-whatsapp text-success me-2"></i> <a href="https://wa.me/919234055507" target="_blank" class="text-white text-decoration-none">+91 92340 55507</a></p>
                <p class="text-white-50 mb-3 small"><i class="fas fa-envelope text-info me-2"></i> <a href="mailto:contact@herbalboxfoundation.org" class="text-white text-decoration-none">contact@herbalboxfoundation.org</a></p>
                <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-warning w-100 fw-bold text-dark shadow-sm"><i class="fas fa-heart text-danger me-1"></i> Support Our Mission (Donate)</a>
            </div>
        </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0 text-white-50">&copy; <?= date('Y'); ?> <?= e($siteName); ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="<?= BASE_URL; ?>/privacy-policy.php" class="text-white-50 me-3 text-decoration-none">Privacy Policy</a>
                    <a href="<?= BASE_URL; ?>/terms.php" class="text-white-50 me-3 text-decoration-none">Terms of Service</a>
                    <a href="<?= BASE_URL; ?>/disclaimer.php" class="text-white-50 text-decoration-none">Disclaimer</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Floating Call & WhatsApp Widgets -->
<a href="tel:9234055507" class="floating-call" title="Call Helpline: +91 92340 55507">
    <i class="fas fa-phone-alt"></i>
</a>
<?php if (!empty($whatsappNum)): ?>
<a href="https://wa.me/<?= e($whatsappNum); ?>?text=<?= $whatsappMsg; ?>" class="floating-whatsapp" target="_blank" title="Chat on WhatsApp (+91 92340 55507)" rel="noopener noreferrer">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Floating Back to Top Button -->
<button type="button" class="back-to-top" id="backToTop" title="Back to Top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Master Custom JS -->
<script src="<?= BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
