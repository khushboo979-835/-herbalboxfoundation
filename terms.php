<?php
/**
 * Terms of Service Page
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pageTitle = 'Terms & Conditions of Use';
$pageDesc = 'Terms of service and legal agreement governing Seva Foundation digital services.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="bg-dark text-white py-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-3">
        <h1 class="h2 fw-bold text-white mb-1">Terms of Service</h1>
        <p class="text-white-50 mb-0">Governing Use of Seva Foundation Digital Platforms</p>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="content-legal leading-relaxed">
                    <h4>1. Acceptance of Terms</h4>
                    <p>By accessing or utilizing any page, form, or donation service on this website, you agree to be bound by these Terms and Conditions and our Privacy Policy.</p>

                    <h4>2. Donations & Tax Exemptions</h4>
                    <p>All contributions made to Seva Arogya & Shiksha Foundation are non-refundable voluntary donations utilized exclusively for charitable healthcare and educational objectives. Receipts for Section 80G tax deductions are issued in the name of the PAN card holder provided during donation submission.</p>

                    <h4>3. Code of Conduct for Volunteers & Camp Registrants</h4>
                    <p>Participants registering for free health camps or volunteer drives agree to provide accurate and truthful contact information. The NGO reserves the right to cancel or reschedule events due to logistical, medical, or administrative factors.</p>

                    <h4>4. Intellectual Property</h4>
                    <p>All logos, trademarks, research publications, and educational NCERT support guides published on this platform remain the intellectual property of Seva Foundation and partner institutions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
