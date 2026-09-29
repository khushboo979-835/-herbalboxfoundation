<?php
/**
 * Master Frontend Footer Template
 */

declare(strict_types=1);

$siteName = getSetting('site_name', 'Seva Arogya & Shiksha Foundation');
$tagline = getSetting('site_tagline', 'Serving Humanity Through Healthcare & Education');
$phone = getSetting('site_phone', '+91 98765 43210');
$email = getSetting('site_email', 'info@ngoseva.org');
$address = getSetting('site_address', 'Seva Bhavan, Plot 42, Institutional Area, Sector 14, New Delhi - 110001');
$whatsappNum = preg_replace('/[^0-9]/', '', getSetting('whatsapp_number', '919876543210'));
$whatsappMsg = urlencode(getSetting('whatsapp_message', 'Hello Seva Foundation, I would like to inquire about your programs.'));
$regNo = getSetting('ngo_reg_number', 'REG/NGO/2018/88741/DELHI');
$panNo = getSetting('pan_number', 'AAATS1234F');
$nitiNo = getSetting('niti_aayog_id', 'DL/2018/0192847');
?>

<!-- Master Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: About NGO -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="logo-icon bg-primary text-white p-2 rounded-3">
                        <i class="fas fa-hands-holding-child fa-lg"></i>
                    </div>
                    <h4 class="text-white mb-0 fs-5"><?= e($siteName); ?></h4>
                </div>
                <p class="text-white-50 mb-3"><?= e($tagline); ?>. We provide free healthcare camps, medicine support, school MOUs, and AYUSH healing across India.</p>
                <div class="p-3 rounded-3 mb-3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                    <small class="d-block text-white-50"><strong class="text-white">Reg No:</strong> <?= e($regNo); ?></small>
                    <small class="d-block text-white-50"><strong class="text-white">NITI Aayog:</strong> <?= e($nitiNo); ?> | <strong class="text-white">PAN:</strong> <?= e($panNo); ?></small>
                    <small class="d-block text-warning"><i class="fas fa-certificate me-1"></i> 80G & 12A Tax Exemption Approved</small>
                </div>
                <div class="footer-social">
                    <?php if ($fb = getSetting('facebook_url')): ?><a href="<?= e($fb); ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if ($ig = getSetting('instagram_url')): ?><a href="<?= e($ig); ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if ($yt = getSetting('youtube_url')): ?><a href="<?= e($yt); ?>" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a><?php endif; ?>
                    <?php if ($tw = getSetting('twitter_url')): ?><a href="<?= e($tw); ?>" target="_blank" title="Twitter"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
                    <?php if ($li = getSetting('linkedin_url')): ?><a href="<?= e($li); ?>" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                </div>
            </div>

            <!-- Col 2: Healthcare & AYUSH -->
            <div class="col-lg-2 col-md-6">
                <h5>Our Programs</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/healthcare.php"><i class="fas fa-angle-right"></i> Healthcare Camps</a></li>
                    <li><a href="<?= BASE_URL; ?>/blood-donation.php"><i class="fas fa-angle-right"></i> Blood Donation</a></li>
                    <li><a href="<?= BASE_URL; ?>/education.php"><i class="fas fa-angle-right"></i> NCERT Education</a></li>
                    <li><a href="<?= BASE_URL; ?>/ayush.php"><i class="fas fa-angle-right"></i> AYUSH Treatments</a></li>
                    <li><a href="<?= BASE_URL; ?>/yoga.php"><i class="fas fa-angle-right"></i> Yoga & Meditation</a></li>
                    <li><a href="<?= BASE_URL; ?>/products.php"><i class="fas fa-angle-right"></i> Ayurvedic Store</a></li>
                </ul>
            </div>

            <!-- Col 3: Partnerships & Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h5>Partnerships & MOUs</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL; ?>/schools.php"><i class="fas fa-angle-right"></i> School Partners</a></li>
                    <li><a href="<?= BASE_URL; ?>/doctors.php"><i class="fas fa-angle-right"></i> Doctor Network</a></li>
                    <li><a href="<?= BASE_URL; ?>/hospitals.php"><i class="fas fa-angle-right"></i> Hospital Partners</a></li>
                    <li><a href="<?= BASE_URL; ?>/mou.php"><i class="fas fa-angle-right"></i> Public MOU Archive</a></li>
                    <li><a href="<?= BASE_URL; ?>/partnerships.php"><i class="fas fa-angle-right"></i> Become a Partner (CSR)</a></li>
                    <li><a href="<?= BASE_URL; ?>/volunteer.php"><i class="fas fa-angle-right"></i> Volunteer With Us</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact & Office -->
            <div class="col-lg-3 col-md-6">
                <h5>Headquarters</h5>
                <p class="text-white-50 mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?= e($address); ?></p>
                <p class="text-white-50 mb-2"><i class="fas fa-phone-alt text-primary me-2"></i> <?= e($phone); ?></p>
                <p class="text-white-50 mb-3"><i class="fas fa-envelope text-primary me-2"></i> <?= e($email); ?></p>
                <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-warning w-100 fw-bold text-dark"><i class="fas fa-heart me-1"></i> Make a Donation (80G)</a>
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

<!-- Floating WhatsApp Button -->
<?php if (!empty($whatsappNum)): ?>
<a href="https://wa.me/<?= e($whatsappNum); ?>?text=<?= $whatsappMsg; ?>" class="floating-whatsapp" target="_blank" title="Chat on WhatsApp" rel="noopener noreferrer">
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
