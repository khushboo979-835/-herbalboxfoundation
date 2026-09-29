<?php
/**
 * Privacy Policy Page
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pageTitle = 'Privacy Policy';
$pageDesc = 'Privacy policy and data protection terms of Seva Arogya & Shiksha Foundation.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="bg-dark text-white py-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-3">
        <h1 class="h2 fw-bold text-white mb-1">Privacy Policy</h1>
        <p class="text-white-50 mb-0">Effective Date: <?= date('F d, Y'); ?></p>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="content-legal leading-relaxed">
                    <h4>1. Introduction</h4>
                    <p>Seva Arogya & Shiksha Foundation ("NGO", "we", "our", or "us") is dedicated to protecting the privacy of our website visitors, donors, volunteers, and partner institutions.</p>

                    <h4>2. Information We Collect</h4>
                    <p>We may collect personal details such as your name, email address, phone number, PAN card number (for 80G tax receipt purposes), postal address, and voluntary health/educational survey responses when submitted via our forms.</p>

                    <h4>3. Utilization of Information</h4>
                    <p>Your data is used strictly to process donations, issue Section 80G tax exemption certificates, coordinate free health checkup camps, coordinate emergency blood donation drives, and send official newsletter communications.</p>

                    <h4>4. Data Security & Payment Information</h4>
                    <p>We do not store your credit card, debit card, or net banking credentials on our servers. All financial transactions are securely processed via certified RBI-compliant payment gateways (such as Razorpay) using 256-bit SSL encryption.</p>

                    <h4>5. Contact Us</h4>
                    <p>If you have any questions regarding our privacy practices, please write to us at: <code><?= e(getSetting('site_email', 'info@ngoseva.org')); ?></code>.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
