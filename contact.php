<?php
/**
 * Contact Us & Interactive Location Portal
 * Seva Foundation Helpdesk
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

$presetSubject = sanitize($_GET['subject'] ?? '');
if (isset($_GET['doctor'])) {
    $presetSubject = 'Consultation Inquiry: Dr. ' . sanitize($_GET['doctor']);
}
if (isset($_GET['school'])) {
    $presetSubject = 'Program Inquiry: ' . sanitize($_GET['school']);
}

$successMsg = '';
$errorMsg = '';

// Handle Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();

    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? 'General Inquiry');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $errorMsg = 'Please complete all required fields (*).';
    } else {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $stmt = $pdo->prepare("INSERT INTO contact_enquiries (name, email, phone, subject, message, ip_address, status) VALUES (?, ?, ?, ?, ?, ?, 'unread')");
            $stmt->execute([$name, $email, $phone, $subject, $message, $ip]);

            logActivity('Contact Enquiry Received', 'contact_enquiries', null, "From: {$name} ({$email})");

            // Dispatch Email Notification
            $emailBody = "<h3>New Contact Message Received</h3>
            <table class='info-table'>
                <tr><th>From Name</th><td>" . htmlspecialchars($name) . "</td></tr>
                <tr><th>Email Address</th><td>" . htmlspecialchars($email) . "</td></tr>
                <tr><th>Phone</th><td>" . htmlspecialchars($phone) . "</td></tr>
                <tr><th>Subject</th><td>" . htmlspecialchars($subject) . "</td></tr>
                <tr><th>Message</th><td>" . nl2br(htmlspecialchars($message)) . "</td></tr>
            </table>";
            sendNgoEmail(getSetting('site_email', 'info@ngoseva.org'), "New Inquiry: {$subject}", $emailBody);

            $successMsg = "Thank you {$name}! Your message has been delivered to our helpdesk. We will reply to your email or call you shortly.";
        } catch (Exception $e) {
            $errorMsg = 'A server error occurred while sending your message. Please try again.';
        }
    }
}

$siteAddress = getSetting('site_address', 'Herbalbox Foundation, Near Birla Open Minds International School, Konhara Road, Hajipur, Vaishali, Bihar - 844101');
$regAddress = 'Patna, Bihar, India (CIN: U86901BR2026NPL087665)';
$sitePhone = getSetting('site_phone', '+91 92340 55507');
$siteEmail = getSetting('site_email', 'contact@herbalboxfoundation.org');
$officeHours = getSetting('office_hours', 'Mon - Sat: 9:00 AM - 6:30 PM (Emergency Support 24x7)');
$mapEmbed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3596.3776461942125!2d85.2132!3d25.6885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ed586c9945a00b%3A0x6a2c3a52e18d6e99!2sBirla%20Open%20Minds%20International%20School%2C%20Hajipur!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin';

$pageTitle = 'Contact Us - Herbalbox Foundation | Hajipur & Patna Office';
$pageDesc = 'Get in touch with Herbalbox Foundation. Campus: Near Birla Open Minds International School, Konhara Road, Hajipur, Bihar - 844101. Phone: +91 92340 55507.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative hero-gradient-dark">
    <div class="container py-4">
        <span class="badge bg-success text-white mb-2 px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-headset me-1"></i> 24x7 Foundation Helpdesk</span>
        <h1 class="display-5 fw-bold text-white mb-3">Get in Touch with Herbalbox Foundation</h1>
        <p class="lead text-white-50 max-w-700">Connect with us for free healthcare camps, NCERT digital school collaborations, blood donation drives, or volunteer opportunities across Bihar.</p>
    </div>
</div>

<!-- Contact Info & Form -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Left Info -->
            <div class="col-lg-5">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border h-100 hover-lift">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: contain;">
                        <div>
                            <h4 class="fw-bold mb-0 text-gradient-primary">Herbalbox Foundation</h4>
                            <small class="text-muted text-uppercase fw-semibold">Health • Education • Better Tomorrow</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle fs-5 shadow-sm">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Operational Campus</h6>
                            <p class="small text-muted mb-1"><?= e($siteAddress); ?></p>
                            <small class="text-secondary d-block"><strong>Registered Office:</strong> <?= e($regAddress); ?></small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-success-subtle text-success p-3 rounded-circle fs-5 shadow-sm">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Helpline & WhatsApp</h6>
                            <p class="small text-muted mb-0">
                                <a href="tel:9234055507" class="fw-bold text-dark text-decoration-none">+91 92340 55507</a><br>
                                <a href="https://wa.me/919234055507" target="_blank" class="text-success fw-bold text-decoration-none"><i class="fab fa-whatsapp me-1"></i> WhatsApp: +91 92340 55507</a>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-info-subtle text-info p-3 rounded-circle fs-5 shadow-sm">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Official Email</h6>
                            <p class="small text-muted mb-0">
                                <a href="mailto:contact@herbalboxfoundation.org" class="text-decoration-none"><?= e($siteEmail); ?></a><br>
                                <a href="mailto:info@herbalboxfoundation.org" class="text-decoration-none text-muted">info@herbalboxfoundation.org</a>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle fs-5 shadow-sm">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Working Hours</h6>
                            <p class="small text-muted mb-0"><?= e($officeHours); ?></p>
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <h6 class="fw-bold mb-2">Connect on Social Channels</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/profile.php?id=61594374894081" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/herbalboxfoundation/" target="_blank" class="btn btn-outline-danger btn-sm rounded-circle"><i class="fab fa-instagram"></i></a>
                            <a href="https://wa.me/919234055507" target="_blank" class="btn btn-outline-success btn-sm rounded-circle"><i class="fab fa-whatsapp"></i></a>
                            <a href="https://youtube.com/@herbalboxfoundation" target="_blank" class="btn btn-outline-danger btn-sm rounded-circle"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form -->
            <div class="col-lg-7">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border">
                    <h3 class="fw-bold mb-3">Send Us a Direct Message</h3>
                    <p class="text-muted small mb-4">Fill in your requirements below and our representative will respond within 24 hours.</p>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?= e($successMsg); ?></div>
                    <?php endif; ?>
                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger shadow-sm mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg); ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL; ?>/contact.php" method="POST" class="form-custom">
                        <?= getCsrfInput(); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Your Full Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Rahul Sharma">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control" required placeholder="name@domain.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Subject *</label>
                                <input type="text" name="subject" class="form-control" required value="<?= e($presetSubject); ?>" placeholder="Subject of your message">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Your Message / Query Details *</label>
                                <textarea name="message" rows="5" class="form-control" required placeholder="Type your message here..."></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-ngo-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Map Embed Section -->
<section class="bg-white py-4">
    <div class="container">
        <h5 class="fw-bold mb-3"><i class="fas fa-map-marked-alt text-primary me-2"></i> Find Us on Google Maps</h5>
        <div class="ratio ratio-21x9 rounded-4 overflow-hidden border shadow-sm" style="min-height: 380px;">
            <iframe src="<?= e($mapEmbed); ?>" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
