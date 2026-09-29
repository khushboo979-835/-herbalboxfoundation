<?php
/**
 * Donation & Refund Policy
 * Herbalbox Foundation
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pageTitle = 'Donation & Refund Policy | Herbalbox Foundation';
$pageDesc = 'Official Donation and Refund Policy for Herbalbox Foundation registered under Companies Act, 2013.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4">
        <h1 class="display-6 fw-bold text-white mb-2">Donation & Refund Policy</h1>
        <p class="lead text-white-50">Herbalbox Foundation • CIN: U86901BR2026NPL087665</p>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="max-w-850 mx-auto">
            <h4 class="fw-bold mb-3">1. General Donation Terms</h4>
            <p class="text-muted mb-4">Herbalbox Foundation is a non-profit entity incorporated under the Companies Act, 2013. All donations made through our online portal, bank transfers, or payment gateways are voluntary and utilized strictly towards free medical camps, NCERT digital school support, and community welfare programs across Bihar.</p>

            <h4 class="fw-bold mb-3">2. Section 80G Tax Exemption</h4>
            <p class="text-muted mb-4">All eligible donations receive an official electronic receipt and tax deduction certificate under Section 80G of the Income Tax Act, 1961. Donors must provide a valid Permanent Account Number (PAN) during donation to receive tax exemption benefits.</p>

            <h4 class="fw-bold mb-3">3. Refund & Cancellation Policy</h4>
            <p class="text-muted mb-3">As donations are immediately assigned to ongoing humanitarian missions and procurement of medicines/school kits, donations are normally non-refundable. However, refunds may be considered under the following circumstances:</p>
            <ul class="text-muted mb-4 ps-3">
                <li class="mb-2"><strong>Technical Error / Duplicate Debit:</strong> If an online payment transaction resulted in duplicate deductions due to a gateway error.</li>
                <li class="mb-2"><strong>Erroneous Amount:</strong> If an unintended amount was entered and reported to us within <strong>48 hours</strong> of the transaction.</li>
            </ul>

            <h4 class="fw-bold mb-3">4. Refund Request Process</h4>
            <p class="text-muted mb-4">To request a refund for a technical error, email us at <a href="mailto:contact@herbalboxfoundation.org" class="text-warning fw-bold">contact@herbalboxfoundation.org</a> or call <a href="tel:9234055507" class="text-warning fw-bold">+91 92340 55507</a> within 48 hours with your Donation Reference ID, Transaction Proof, and Bank Details. Approved refunds will be processed via original payment method within 7-10 business days.</p>

            <div class="p-4 bg-light rounded-3 border-start border-4 border-warning mt-4">
                <h6 class="fw-bold mb-1">Official Contact Helpline</h6>
                <p class="small text-muted mb-0">Herbalbox Foundation • Operational Campus: Near Birla Open Minds School, Hajipur (844101) • Phone: +91 92340 55507</p>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
