<?php
/**
 * Institutional Partnerships & CSR Collaboration Page
 * Become a Partner Application Form
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

$preselectedType = sanitize($_GET['type'] ?? 'school');
$successMsg = '';
$errorMsg = '';

// Handle partnership proposal submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();

    $orgName = sanitize($_POST['organization_name'] ?? '');
    $contactPerson = sanitize($_POST['contact_person'] ?? '');
    $partnerType = sanitize($_POST['partner_type'] ?? 'school');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $website = sanitize($_POST['website'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $interest = sanitize($_POST['partnership_interest'] ?? '');
    $consent = isset($_POST['consent']);

    if (empty($orgName) || empty($contactPerson) || empty($phone) || empty($email) || empty($city) || empty($interest)) {
        $errorMsg = 'Please complete all required fields marked with an asterisk (*).';
    } elseif (!$consent) {
        $errorMsg = 'Please agree to the partnership terms and consent declaration.';
    } else {
        $docFile = null;
        if (!empty($_FILES['proposal_document']['name'])) {
            $upload = uploadFile($_FILES['proposal_document'], 'documents', 'document');
            if ($upload['success']) {
                $docFile = $upload['filename'];
            } else {
                $errorMsg = 'Document upload error: ' . $upload['error'];
            }
        }

        if (empty($errorMsg)) {
            try {
                $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $stmt = $pdo->prepare("INSERT INTO partnership_enquiries (organization_name, contact_person, partner_type, phone, email, website, address, city, state, partnership_interest, proposal_document, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$orgName, $contactPerson, $partnerType, $phone, $email, $website, $address, $city, $state, $interest, $docFile, $ip]);

                logActivity('Partnership Proposal Submitted', 'partnership_enquiries', null, "Organization: {$orgName} ({$partnerType})");

                // Dispatch Email Notification
                $emailBody = "<h3>New Partnership Proposal Received</h3>
                <table class='info-table'>
                    <tr><th>Organization</th><td>" . htmlspecialchars($orgName) . "</td></tr>
                    <tr><th>Contact Person</th><td>" . htmlspecialchars($contactPerson) . "</td></tr>
                    <tr><th>Partner Type</th><td>" . htmlspecialchars(ucfirst($partnerType)) . "</td></tr>
                    <tr><th>Phone</th><td>" . htmlspecialchars($phone) . "</td></tr>
                    <tr><th>Email</th><td>" . htmlspecialchars($email) . "</td></tr>
                    <tr><th>Location</th><td>" . htmlspecialchars("{$city}, {$state}") . "</td></tr>
                    <tr><th>Scope of Interest</th><td>" . nl2br(htmlspecialchars($interest)) . "</td></tr>
                </table>";
                sendNgoEmail(getSetting('site_email', 'info@ngoseva.org'), "New Partnership Application: {$orgName}", $emailBody);

                $successMsg = 'Thank you for reaching out! Your partnership application has been submitted successfully. Our Director of Partnerships will review your proposal and contact you within 2 business days.';
            } catch (Exception $e) {
                $errorMsg = 'A database error occurred while saving your application. Please try again.';
            }
        }
    }
}

$pageTitle = 'Become a Partner - School, Hospital, Doctor & CSR Collaboration';
$pageDesc = 'Collaborate with Seva Foundation for School MOUs, Doctor Volunteering, Hospital Affiliations, and Corporate Social Responsibility (CSR) impact programs.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0f766e 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><i class="fas fa-handshake me-1"></i> Institutional Alliances</span>
        <h1 class="display-5 fw-bold text-white mb-3">Partner With Us for Scalable Social Impact</h1>
        <p class="lead text-white-50 max-w-700">Whether you represent a school, hospital, medical practice, or CSR grant fund, we invite you to co-create transformative healthcare and educational programs.</p>
    </div>
</div>

<!-- Partner Categories -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">Collaboration Tracks</span>
            <h2 class="section-title">Who Can Partner with Seva Foundation?</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="p-4 border rounded-4 bg-light h-100 text-center">
                    <i class="fas fa-school fa-2x text-primary mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">School MOUs</h5>
                    <p class="small text-muted mb-0">Bi-annual student health screenings, eye tests, NCERT study aids, and campus yoga.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4 border rounded-4 bg-light h-100 text-center">
                    <i class="fas fa-user-md fa-2x text-success mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">Doctors & Clinicians</h5>
                    <p class="small text-muted mb-0">Volunteer your clinical expertise in weekly free camps or telemedicine consultations.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4 border rounded-4 bg-light h-100 text-center">
                    <i class="fas fa-hospital fa-2x text-info mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">Hospitals & Diagnostic Labs</h5>
                    <p class="small text-muted mb-0">Offer subsidized OPD slots, discounted diagnostics, and reserved surgery beds.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="p-4 border rounded-4 bg-light h-100 text-center">
                    <i class="fas fa-building fa-2x text-warning mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">CSR / Corporate Grants</h5>
                    <p class="small text-muted mb-0">Fund mobile clinics, smart classrooms, or girl child education with 80G tax deductions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partnership Application Form -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary text-white mb-2 px-3 py-1">Online Application</span>
                        <h3 class="fw-bold">Submit Institutional Partnership Proposal</h3>
                        <p class="text-muted small">Please fill out the form below. Our MOU coordinator will contact your representative promptly.</p>
                    </div>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?= e($successMsg); ?></div>
                    <?php endif; ?>
                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger shadow-sm mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg); ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL; ?>/partnerships.php" method="POST" enctype="multipart/form-data" class="form-custom">
                        <?= getCsrfInput(); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Organization / Institution Name *</label>
                                <input type="text" name="organization_name" class="form-control" required placeholder="e.g. DPS Senior Secondary School">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Contact Person & Designation *</label>
                                <input type="text" name="contact_person" class="form-control" required placeholder="e.g. Dr. Rajesh Verma (Principal)">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Partner Track / Category *</label>
                                <select name="partner_type" class="form-select" required>
                                    <option value="school" <?= $preselectedType === 'school' ? 'selected' : ''; ?>>School / College (MOU)</option>
                                    <option value="doctor" <?= $preselectedType === 'doctor' ? 'selected' : ''; ?>>Doctor / Specialist (Volunteer)</option>
                                    <option value="hospital" <?= $preselectedType === 'hospital' ? 'selected' : ''; ?>>Hospital / Clinic</option>
                                    <option value="diagnostic" <?= $preselectedType === 'diagnostic' ? 'selected' : ''; ?>>Diagnostic / Pathology Lab</option>
                                    <option value="csr_corporate" <?= $preselectedType === 'csr_corporate' ? 'selected' : ''; ?>>Corporate CSR / Donor Agency</option>
                                    <option value="community" <?= $preselectedType === 'community' ? 'selected' : ''; ?>>Community / NGO / RWA</option>
                                    <option value="other" <?= $preselectedType === 'other' ? 'selected' : ''; ?>>Other Strategic Partner</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="+91 98765 43210">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Official Email Address *</label>
                                <input type="email" name="email" class="form-control" required placeholder="contact@organization.org">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Website / Social URL</label>
                                <input type="url" name="website" class="form-control" placeholder="https://www.example.com">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">City *</label>
                                <input type="text" name="city" class="form-control" required placeholder="e.g. New Delhi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">State *</label>
                                <input type="text" name="state" class="form-control" required placeholder="e.g. Delhi NCR">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Complete Official Address</label>
                                <textarea name="address" rows="2" class="form-control" placeholder="Street, Building, Area, Pincode"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Proposed Scope of Partnership / Key Objectives *</label>
                                <textarea name="partnership_interest" rows="4" class="form-control" required placeholder="Describe what activities you would like to collaborate on (e.g. free health camps in school, CSR sponsorship of mobile clinic, doctor volunteering, etc.)"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Upload Proposal / Registration Profile (Optional PDF/DOCX - Max 15MB)</label>
                                <input type="file" name="proposal_document" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg">
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="consent" id="consent" required>
                                    <label class="form-check-label small text-muted" for="consent">
                                        I confirm that I am an authorized representative of the above organization and agree to initiate exploratory discussions for collaborative social welfare.
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-ngo-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Partnership Proposal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
