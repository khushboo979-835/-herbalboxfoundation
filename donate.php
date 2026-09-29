<?php
/**
 * Donation Portal & 80G Tax Exemption Receipt Processing
 * Seva Arogya & Shiksha Foundation
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

$defaultPurpose = sanitize($_GET['purpose'] ?? 'general');
$successDonation = null;
$errorMsg = '';

// Handle Payment Submission / Verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'process_donation') {
    requireCsrfToken();

    $name = sanitize($_POST['donor_name'] ?? '');
    $email = sanitize($_POST['donor_email'] ?? '');
    $phone = sanitize($_POST['donor_phone'] ?? '');
    $pan = sanitize($_POST['donor_pan'] ?? '');
    $address = sanitize($_POST['donor_address'] ?? '');
    $city = sanitize($_POST['donor_city'] ?? '');
    $state = sanitize($_POST['donor_state'] ?? '');
    $amount = (float)($_POST['amount'] ?? 0);
    $purpose = sanitize($_POST['purpose'] ?? 'general');
    $is80g = isset($_POST['is_80g_requested']) ? 1 : 0;

    if (empty($name) || empty($email) || empty($phone) || $amount < 100) {
        $errorMsg = 'Please enter a valid donor name, email, phone, and a minimum donation of ₹100.';
    } else {
        try {
            $donationCode = generateReferenceCode('DON');
            $receiptNo = 'RCPT-' . date('Y') . '-' . substr(bin2hex(random_bytes(3)), 0, 6);

            // Simulation / Payment Initiation Record
            $ins = $pdo->prepare("INSERT INTO donations (donation_code, donor_name, donor_email, donor_phone, donor_pan, donor_address, donor_city, donor_state, amount, purpose, payment_method, payment_status, receipt_number, is_80g_requested) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'razorpay_online', 'completed', ?, ?)");
            $ins->execute([$donationCode, $name, $email, $phone, $pan, $address, $city, $state, $amount, $purpose, $receiptNo, $is80g]);

            logActivity('New Donation Received', 'donations', null, "Donor: {$name}, Amount: ₹{$amount} ({$donationCode})");

            // Dispatch 80G Confirmation Email
            $emailBody = "<h3>Thank You for Your Generous Donation</h3>
            <p>Dear {$name}, your contribution of <strong>" . formatCurrency($amount) . "</strong> has been received with deep gratitude.</p>
            <table class='info-table'>
                <tr><th>Donation Code</th><td><strong>{$donationCode}</strong></td></tr>
                <tr><th>Receipt Number</th><td>{$receiptNo}</td></tr>
                <tr><th>Amount Contributed</th><td>" . formatCurrency($amount) . "</td></tr>
                <tr><th>Donation Purpose</th><td>" . htmlspecialchars(ucfirst($purpose)) . "</td></tr>
                <tr><th>80G Tax Status</th><td>Eligible for 50% Tax Exemption under Sec 80G</td></tr>
                <tr><th>NGO 80G Approval</th><td>" . htmlspecialchars(getSetting('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20')) . "</td></tr>
            </table>
            <p>Our finance team has enclosed this official tax receipt. Thank you for empowering underprivileged lives!</p>";
            sendNgoEmail($email, "Donation Receipt: {$donationCode} (80G Tax Exempt)", $emailBody, $name);

            $successDonation = [
                'code' => $donationCode,
                'name' => $name,
                'amount' => $amount,
                'receipt' => $receiptNo,
                'purpose' => $purpose
            ];
        } catch (Exception $e) {
            $errorMsg = 'A transaction processing error occurred. Please try again.';
        }
    }
}

$pageTitle = 'Donate Online (80G Tax Exemption) - Sponsor Healthcare & Education';
$pageDesc = 'Make an 80G tax-exempt charitable donation to sponsor free medical checkups, cataract surgeries, school supplies, and child education kits.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #78350f 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><i class="fas fa-hand-holding-heart me-1"></i> Transparent Giving</span>
        <h1 class="display-5 fw-bold text-white mb-3">Empower Lives with 100% Transparent Philanthropy</h1>
        <p class="lead text-white-50 max-w-700">Every single rupee is allocated directly toward free medicines, student NCERT kits, doctor camps, and blood donation logistics. All donations qualify for 80G Income Tax Exemption.</p>
    </div>
</div>

<!-- Main Donation Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <?php if ($successDonation): ?>
        <!-- Success Receipt Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-5 bg-white rounded-4 shadow border text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-4 mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-check fa-3x"></i>
                    </div>
                    <h2 class="fw-bold text-success mb-2">Thank You for Your Generosity!</h2>
                    <p class="text-muted mb-4">Your donation of <strong class="text-dark fs-4"><?= formatCurrency($successDonation['amount']); ?></strong> has been successfully processed.</p>

                    <div class="p-4 bg-light rounded-4 text-start mb-4 border">
                        <div class="row g-2">
                            <div class="col-6"><small class="text-muted">Donation Code:</small><br><strong><?= e($successDonation['code']); ?></strong></div>
                            <div class="col-6"><small class="text-muted">Receipt Number:</small><br><strong><?= e($successDonation['receipt']); ?></strong></div>
                            <div class="col-6"><small class="text-muted">Donor Name:</small><br><strong><?= e($successDonation['name']); ?></strong></div>
                            <div class="col-6"><small class="text-muted">Allocated Purpose:</small><br><strong><?= e(ucfirst($successDonation['purpose'])); ?></strong></div>
                            <div class="col-12 pt-2 border-top"><small class="text-muted">Tax Exemption:</small><br><span class="badge bg-success">Section 80G Certificate: <?= e(getSetting('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20')); ?></span></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button onclick="window.print();" class="btn btn-outline-secondary"><i class="fas fa-print me-1"></i> Print Tax Receipt</button>
                        <a href="<?= BASE_URL; ?>/index.php" class="btn btn-ngo-primary">Return to Homepage</a>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>

        <div class="row g-5">
            <!-- Form Left -->
            <div class="col-lg-7">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border">
                    <h3 class="fw-bold mb-3">Make a Secure Online Donation</h3>
                    <p class="text-muted small mb-4">Select or enter your desired contribution amount. Instant 80G tax receipt will be generated.</p>

                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger shadow-sm mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg); ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL; ?>/donate.php" method="POST" class="form-custom">
                        <?= getCsrfInput(); ?>
                        <input type="hidden" name="action" value="process_donation">
                        <input type="hidden" name="amount" id="finalDonationAmount" value="1000">

                        <!-- Preset Amount Selector -->
                        <label class="form-label small fw-bold">Select Donation Amount</label>
                        <div class="row g-2 mb-3">
                            <div class="col-3">
                                <button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold donation-amount-btn" data-amount="500">₹ 500</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-primary active w-100 py-3 fw-bold donation-amount-btn" data-amount="1000">₹ 1,000</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold donation-amount-btn" data-amount="2500">₹ 2,500</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold donation-amount-btn" data-amount="5000">₹ 5,000</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Or Enter Custom Amount (₹)</label>
                            <input type="number" id="customAmountInput" class="form-control form-control-lg" placeholder="e.g. 10000" min="100">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Donation Purpose / Sector *</label>
                            <select name="purpose" class="form-select">
                                <option value="general" <?= $defaultPurpose === 'general' ? 'selected' : ''; ?>>Where Needed Most (General Welfare)</option>
                                <option value="healthcare" <?= $defaultPurpose === 'healthcare' ? 'selected' : ''; ?>>Free Healthcare & Medical Camps</option>
                                <option value="education" <?= $defaultPurpose === 'education' ? 'selected' : ''; ?>>NCERT School Kits & Student Education</option>
                                <option value="medical_camp" <?= $defaultPurpose === 'medical_camp' ? 'selected' : ''; ?>>Sponsor a Rural Mega Medical Camp</option>
                                <option value="blood_donation" <?= $defaultPurpose === 'blood_donation' ? 'selected' : ''; ?>>Blood Donation Logistics & Drives</option>
                                <option value="ayush" <?= $defaultPurpose === 'ayush' ? 'selected' : ''; ?>>AYUSH & Ayurvedic Herbal Care</option>
                                <option value="yoga" <?= $defaultPurpose === 'yoga' ? 'selected' : ''; ?>>Community Yoga & Wellness</option>
                            </select>
                        </div>

                        <h5 class="fw-bold fs-6 mb-3 pt-3 border-top">Donor Details (for 80G Tax Exemption Certificate)</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name *</label>
                                <input type="text" name="donor_name" class="form-control" required placeholder="Name on PAN Card">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address *</label>
                                <input type="email" name="donor_email" class="form-control" required placeholder="For receipt delivery">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number *</label>
                                <input type="tel" name="donor_phone" class="form-control" required placeholder="10-digit mobile">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">PAN Number (Required for 80G Tax Benefit)</label>
                                <input type="text" name="donor_pan" class="form-control text-uppercase" placeholder="e.g. ABCDE1234F">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">City</label>
                                <input type="text" name="donor_city" class="form-control" placeholder="City">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">State</label>
                                <input type="text" name="donor_state" class="form-control" placeholder="State">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Postal Address</label>
                                <input type="text" name="donor_address" class="form-control" placeholder="Required for physical receipt record">
                            </div>
                        </div>

                        <div class="form-check my-4">
                            <input class="form-check-input" type="checkbox" name="is_80g_requested" id="taxCheck" checked>
                            <label class="form-check-label small text-muted" for="taxCheck">
                                I request an official Section 80G tax exemption certificate for this donation.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold text-dark shadow">
                            <i class="fas fa-lock me-2 text-danger"></i> Proceed to Secure Payment (Razorpay)
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tax Benefits & Trust Sidebar -->
            <div class="col-lg-5">
                <div class="p-4 bg-white rounded-4 shadow-sm border mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-shield-alt text-success me-2"></i> Tax Exemption Under Section 80G</h5>
                    <p class="small text-muted mb-3">All donations to Seva Arogya & Shiksha Foundation are 50% tax-deductible under Section 80G of the Indian Income Tax Act, 1961.</p>
                    <div class="p-3 bg-light rounded-3 small">
                        <strong>80G Unique Reg No:</strong><br>
                        <code class="text-primary"><?= e(getSetting('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20')); ?></code><br><br>
                        <strong>NGO PAN:</strong> <?= e(getSetting('pan_number', 'AAATS1234F')); ?><br>
                        <strong>NITI Aayog Darpan:</strong> <?= e(getSetting('niti_aayog_id', 'DL/2018/0192847')); ?>
                    </div>
                </div>

                <div class="p-4 bg-white rounded-4 shadow-sm border">
                    <h5 class="fw-bold mb-3"><i class="fas fa-calculator text-primary me-2"></i> What Your Contribution Achieves:</h5>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark px-2 py-1">₹ 500</span>
                            <span>Provides 15-day essential medicine supply to 5 elderly patients.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark px-2 py-1">₹ 1,000</span>
                            <span>Sponsors a complete NCERT textbook and bag kit for a needy school child.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark px-2 py-1">₹ 2,500</span>
                            <span>Funds diagnostic testing (ECG, sugar, Hb, vitals) for 25 camp visitors.</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark px-2 py-1">₹ 5,000</span>
                            <span>Sponsors a stitch-less cataract surgery and eye glasses for a senior citizen.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
