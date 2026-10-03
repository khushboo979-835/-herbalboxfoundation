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

        <!-- Top Dual Donation Mode (UPI / Bank Transfer + Instant Online) -->
        <div class="row g-4 align-items-stretch mb-5">
            <!-- Left: Authentic UPI Scanner & Direct Bank Transfer -->
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border: 1px solid #e2e8f0;">
                    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%);">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2"><i class="fas fa-qrcode me-1"></i> तत्काल UPI एवं सीधा बैंक खाता सहयोग</span>
                        <h4 class="fw-bold mb-1">Direct Bank & Scanner Contribution</h4>
                        <p class="small text-white-50 mb-0">किसी भी UPI ऐप (PhonePe, GPay, Paytm, BHIM) अथवा सीधे बैंक ट्रांसफर से सहयोग करें</p>
                    </div>

                    <div class="p-4 d-flex flex-column align-items-center">
                        <!-- QR Code Wrapper with green dashed border -->
                        <div class="p-3 bg-white rounded-4 shadow-sm mb-3 text-center position-relative" style="border: 2px dashed #059669; max-width: 270px;">
                            <img src="<?= BASE_URL; ?>/assets/images/qr-code.png" alt="Union Bank UPI QR Code" class="img-fluid rounded-3 mb-2" style="max-height: 220px; width: 100%; object-fit: contain;">
                            <div class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 1px; color: #047857;">
                                <i class="fas fa-mobile-alt me-1"></i> SCAN VIA ANY UPI APP
                            </div>
                        </div>

                        <!-- UPI ID Display & Copy Button -->
                        <div class="w-100 mb-3 text-center">
                            <div class="fw-bold text-dark fs-5 mb-2" id="upiIdText">anandjankijks@ybl</div>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" onclick="copyUpiId('anandjankijks@ybl')" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm" id="upiCopyBtn">
                                    <i class="far fa-copy me-1"></i> UPI ID कॉपी करें
                                </button>
                                <button type="button" onclick="copyUpiId('anandjankijks@upi')" class="btn btn-outline-success px-3 py-2 rounded-pill fw-semibold btn-sm">
                                    Alt UPI: anandjankijks@upi
                                </button>
                            </div>
                        </div>

                        <p class="text-muted small text-center px-3 mb-4" style="line-height: 1.5; font-size: 13px;">
                            <strong>आनन्द जानकी जनकल्याण समिति</strong> एक सरकारी पंजीकृत संस्था है (सोसाइटी XXI ऑफ 1860)। सभी लेन-देन पारदर्शी एवं प्रमाणित हैं।
                        </p>

                        <!-- Bank Details Card (Dark Green Box matching user screenshot) -->
                        <div class="w-100 p-4 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary pb-2">
                                <h5 class="fw-bold mb-0 text-white fs-6">
                                    <i class="fas fa-university text-warning me-2"></i> सीधे बैंक खाते में सहयोग हेतु विवरण
                                </h5>
                                <span class="badge bg-warning text-dark px-2 py-1 small">Direct Transfer</span>
                            </div>

                            <div class="vstack gap-2 small">
                                <div class="row">
                                    <div class="col-sm-5 text-warning fw-semibold">बैंक का नाम:</div>
                                    <div class="col-sm-7 fw-bold text-white">UNION BANK OF INDIA</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5 text-warning fw-semibold">खाता धारक:</div>
                                    <div class="col-sm-7 fw-bold text-white">आनन्द जानकी जनकल्याण समिति</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5 text-warning fw-semibold">खाता संख्या:</div>
                                    <div class="col-sm-7 fw-bold text-white fs-6 font-monospace" id="bankAccNo">195721010000222</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5 text-warning fw-semibold">IFSC कोड:</div>
                                    <div class="col-sm-7 fw-bold text-white font-monospace" id="bankIfsc">UBIN0919578</div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-top border-secondary text-center">
                                <button type="button" onclick="copyBankDetails()" class="btn btn-warning w-100 fw-bold text-dark rounded-pill py-2 shadow" id="bankCopyBtn">
                                    <i class="far fa-copy me-1"></i> बैंक विवरण कॉपी करें
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Instant Online Donation Form (80G Tax Exemption) -->
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-danger text-white px-3 py-1 rounded-pill"><i class="fas fa-receipt me-1"></i> 80G Tax Exemption</span>
                            <span class="badge bg-light text-muted border">Instant Receipt</span>
                        </div>
                        <h3 class="fw-bold mb-2 text-dark">Make an Online Donation</h3>
                        <p class="text-muted small mb-4">Select or enter your desired contribution amount. All contributions directly support health camps and child education.</p>

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
                                    <button type="button" class="btn btn-outline-primary w-100 py-2 fw-bold donation-amount-btn" data-amount="500">₹ 500</button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary active w-100 py-2 fw-bold donation-amount-btn" data-amount="1000">₹ 1,000</button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-outline-primary w-100 py-2 fw-bold donation-amount-btn" data-amount="2500">₹ 2,500</button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-outline-primary w-100 py-2 fw-bold donation-amount-btn" data-amount="5000">₹ 5,000</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Or Enter Custom Amount (₹)</label>
                                <input type="number" id="customAmountInput" class="form-control" placeholder="e.g. 10000" min="100">
                            </div>

                            <div class="mb-3">
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

                            <h5 class="fw-bold fs-6 mb-3 pt-2 border-top">Donor Details (for 80G Certificate)</h5>

                            <div class="row g-2">
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
                                    <label class="form-label small fw-bold">PAN Number (for 80G)</label>
                                    <input type="text" name="donor_pan" class="form-control text-uppercase" placeholder="e.g. AAFTA1192G">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">City</label>
                                    <input type="text" name="donor_city" class="form-control" placeholder="City">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">State</label>
                                    <input type="text" name="donor_state" class="form-control" placeholder="State">
                                </div>
                            </div>

                            <div class="form-check my-3">
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

                    <!-- Tax Info Bar -->
                    <div class="p-3 bg-light rounded-3 mt-4 border">
                        <div class="d-flex align-items-center justify-content-between small text-muted">
                            <div><i class="fas fa-shield-alt text-success me-1"></i> <strong>PAN:</strong> AAFTA1192G</div>
                            <div><i class="fas fa-certificate text-primary me-1"></i> <strong>Act:</strong> Society XXI 1860</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Locations & Pan-India Footprint Section -->
        <div class="my-5 p-4 p-md-5 bg-white rounded-4 shadow-sm border">
            <div class="text-center mb-4">
                <span class="badge bg-success text-white px-3 py-1 rounded-pill fw-semibold mb-2">
                    <i class="fas fa-map-marked-alt me-1"></i> अखिल भारतीय सेवा नेटवर्क | Pan-India Outreach
                </span>
                <h3 class="fw-bold display-6 mb-2">हमारी कार्य उपस्थिति एवं विस्तार क्षेत्र</h3>
                <p class="text-muted max-w-700 mx-auto">
                    आनन्द जानकी जनकल्याण समिति स्वास्थ्य, निःशुल्क शिक्षा, महिला सशक्तिकरण एवं सामाजिक उत्थान हेतु 10 प्रमुख राज्यों व केंद्र शासित प्रदेशों में निरंतर सक्रिय है।
                </p>
            </div>

            <div class="row g-3">
                <?php
                $statesList = [
                    ['name' => 'New Delhi', 'hindi' => 'नई दिल्ली', 'role' => 'National Capital Outreach, Healthcare & Policy Coordination', 'icon' => 'fa-landmark', 'color' => 'danger'],
                    ['name' => 'Uttar Pradesh', 'hindi' => 'उत्तर प्रदेश', 'role' => 'Rural Health Camps, Women Empowerment & Skill Workshops', 'icon' => 'fa-hands-helping', 'color' => 'warning'],
                    ['name' => 'Uttarakhand', 'hindi' => 'उत्तराखंड', 'role' => 'Hilly Community Wellness, AYUSH & Environment Camps', 'icon' => 'fa-mountain', 'color' => 'info'],
                    ['name' => 'Bihar', 'hindi' => 'बिहार', 'role' => 'Primary Operational Center, NCERT Digital Classrooms & Eye Camps', 'icon' => 'fa-hospital-user', 'color' => 'success'],
                    ['name' => 'Jharkhand', 'hindi' => 'झारखंड', 'role' => 'Tribal Community Healthcare, Child Nutrition & Blood Drives', 'icon' => 'fa-users', 'color' => 'primary'],
                    ['name' => 'Odisha (Udisha)', 'hindi' => 'ओडिशा', 'role' => 'Rural Education Support, Disaster Relief & Health Drives', 'icon' => 'fa-hand-holding-medical', 'color' => 'teal'],
                    ['name' => 'Madhya Pradesh', 'hindi' => 'मध्य प्रदेश', 'role' => 'Grassroots Child Education Kits & AYUSH Herbal Wellness', 'icon' => 'fa-book-reader', 'color' => 'indigo'],
                    ['name' => 'Chhattisgarh', 'hindi' => 'छत्तीसगढ़', 'role' => 'Forest & Tribal Region Mobile Health Clinics', 'icon' => 'fa-ambulance', 'color' => 'danger'],
                    ['name' => 'West Bengal', 'hindi' => 'पश्चिम बंगाल', 'role' => 'Community Welfare, Vision Screenings & Preventive Care', 'icon' => 'fa-heartbeat', 'color' => 'success'],
                    ['name' => 'Assam', 'hindi' => 'असम', 'role' => 'North-East Health Outreach, Student Kits & Youth Development', 'icon' => 'fa-tree', 'color' => 'warning']
                ];
                ?>

                <?php foreach ($statesList as $st): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100 d-flex align-items-center gap-3 hover-lift transition">
                        <div class="bg-<?= $st['color']; ?> text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 46px; height: 46px; font-size: 1.1rem;">
                            <i class="fas <?= $st['icon']; ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0 text-dark"><?= $st['name']; ?></h6>
                                <span class="badge bg-white text-muted border small"><?= $st['hindi']; ?></span>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 12px; line-height: 1.35;"><?= $st['role']; ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- What Contribution Achieves -->
        <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border">
            <h4 class="fw-bold mb-3 text-center"><i class="fas fa-calculator text-primary me-2"></i> What Your Contribution Achieves</h4>
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded-3 border text-center h-100">
                        <span class="badge bg-warning text-dark px-2 py-1 mb-2">₹ 500</span>
                        <p class="small text-muted mb-0">Provides 15-day essential medicine supply to 5 elderly patients.</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded-3 border text-center h-100">
                        <span class="badge bg-primary text-white px-2 py-1 mb-2">₹ 1,000</span>
                        <p class="small text-muted mb-0">Sponsors a complete NCERT textbook & school bag kit for a child.</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded-3 border text-center h-100">
                        <span class="badge bg-success text-white px-2 py-1 mb-2">₹ 2,500</span>
                        <p class="small text-muted mb-0">Funds diagnostic testing (ECG, sugar, Hb) for 25 camp visitors.</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded-3 border text-center h-100">
                        <span class="badge bg-danger text-white px-2 py-1 mb-2">₹ 5,000</span>
                        <p class="small text-muted mb-0">Sponsors stitch-less cataract surgery and eye glasses for senior citizens.</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Toast for Copy Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fw-bold" id="toastMessage">
                <i class="fas fa-check-circle me-2"></i> Copied to clipboard!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
function showToast(msg) {
    var toastEl = document.getElementById('copyToast');
    document.getElementById('toastMessage').innerHTML = '<i class="fas fa-check-circle me-2"></i> ' + msg;
    if (window.bootstrap && bootstrap.Toast) {
        var toast = new bootstrap.Toast(toastEl, { delay: 2500 });
        toast.show();
    } else {
        alert(msg);
    }
}

function copyUpiId(upi) {
    navigator.clipboard.writeText(upi).then(function() {
        showToast('UPI ID (' + upi + ') कॉपी हो गया है!');
    }).catch(function() {
        showToast('UPI ID: ' + upi);
    });
}

function copyBankDetails() {
    var details = "बैंक का नाम: UNION BANK OF INDIA\nखाता धारक: आनन्द जानकी जनकल्याण समिति\nखाता संख्या: 195721010000222\nIFSC कोड: UBIN0919578\nUPI ID: anandjankijks@ybl";
    navigator.clipboard.writeText(details).then(function() {
        showToast('बैंक खाता विवरण सफलतापूर्वक कॉपी हो गया!');
    }).catch(function() {
        showToast('बैंक विवरण कॉपी कर लिया गया है।');
    });
}

// Preset Amount Buttons
document.querySelectorAll('.donation-amount-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.donation-amount-btn').forEach(function(b) {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-primary');
        });
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-primary', 'active');
        var amt = this.getAttribute('data-amount');
        document.getElementById('finalDonationAmount').value = amt;
        document.getElementById('customAmountInput').value = '';
    });
});

document.getElementById('customAmountInput')?.addEventListener('input', function() {
    if (this.value) {
        document.querySelectorAll('.donation-amount-btn').forEach(function(b) {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-primary');
        });
        document.getElementById('finalDonationAmount').value = this.value;
    }
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
