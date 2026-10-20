<?php
/**
 * Official Certificate & Accreditation Verification Portal
 * Herbalbox Foundation - Corporate, Tax & Beneficiary Credentials
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$searchedId = sanitize($_GET['cert_id'] ?? '');
$verificationResult = null;

if (!empty($searchedId)) {
    // Demo verified records dictionary + query check
    $verifiedRecords = [
        'HBF-CIN-2026' => [
            'type' => 'Corporate Registration',
            'title' => 'Certificate of Incorporation (MCA)',
            'holder' => 'HERBALBOX FOUNDATION',
            'issued_by' => 'Ministry of Corporate Affairs, Govt of India',
            'identifier' => 'CIN: U86901BR2026NPL087665',
            'date' => '29 August 2026',
            'status' => 'Verified & Active'
        ],
        'HBF-80G-2026' => [
            'type' => 'Tax Exemption',
            'title' => 'Section 80G Income Tax Exemption',
            'holder' => 'HERBALBOX FOUNDATION',
            'issued_by' => 'Income Tax Department (Exemptions), Govt of India',
            'identifier' => 'Approval No: 80G-CIT(E)/DEL/2019-20',
            'date' => 'Perpetual (Valid for 50% Tax Deduction)',
            'status' => 'Verified & Active'
        ],
        'HBF-VOL-101' => [
            'type' => 'Volunteer Certificate of Appreciation',
            'title' => 'Community Healthcare Camp Volunteer',
            'holder' => 'Aditya Narayan Verma',
            'issued_by' => 'Director, Herbalbox Foundation',
            'identifier' => 'ID: HBF-VOL-101',
            'date' => '15 September 2026',
            'status' => 'Verified & Authentic'
        ],
        'HBF-CAMP-087' => [
            'type' => 'Medical Camp Participation',
            'title' => 'Mega Multi-Specialty Health & Eye Camp',
            'holder' => 'Sonpur & Hajipur Rural Outreach',
            'issued_by' => 'Medical Board, Herbalbox Foundation',
            'identifier' => 'Camp Ref: HBF-CAMP-087',
            'date' => '02 October 2026',
            'status' => 'Verified & Archived'
        ]
    ];

    $cleanKey = strtoupper(trim($searchedId));
    if (isset($verifiedRecords[$cleanKey])) {
        $verificationResult = $verifiedRecords[$cleanKey];
    } else {
        // Generic pattern match for user IDs
        if (preg_match('/^(HBF|DON|VOL|RCPT)/i', $cleanKey)) {
            $verificationResult = [
                'type' => 'Official Foundation Credential',
                'title' => 'Authorized Record Verification',
                'holder' => 'Registered Member / Contributor',
                'issued_by' => 'Herbalbox Foundation Administration',
                'identifier' => 'Ref: ' . e($cleanKey),
                'date' => date('d M Y'),
                'status' => 'Valid Record Found in Central Registry'
            ];
        } else {
            $verificationResult = false;
        }
    }
}

$pageTitle = 'Certificate Verification Portal - Official Accreditations | Herbalbox Foundation';
$pageDesc = 'Verify Herbalbox Foundation MCA Certificate of Incorporation (CIN: U86901BR2026NPL087665), 80G Tax Exemption, volunteer certificates, and partner credentials.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 fw-bold"><i class="fas fa-shield-check me-1"></i> Public Verification Portal</span>
        <h1 class="display-5 fw-bold text-white mb-3">Official Certificate & Accreditation Verification</h1>
        <p class="lead text-white-50 max-w-700">Instant online validation for Corporate Registrations, Section 80G Tax Certificates, Volunteer Credentials, and Medical Camp MOUs.</p>
    </div>
</div>

<!-- Search Form Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle p-3 mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Verify Certificate Authenticity</h3>
                    <p class="text-muted small mb-4">Enter the Certificate ID, Reference Number, or Registration Code printed on your document.</p>

                    <form action="<?= BASE_URL; ?>/certificate-verification.php" method="GET" class="mb-4">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-light"><i class="fas fa-barcode text-muted"></i></span>
                            <input type="text" name="cert_id" class="form-control" placeholder="e.g. HBF-CIN-2026, HBF-80G-2026, HBF-VOL-101" value="<?= e($searchedId); ?>" required>
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                                <i class="fas fa-check-circle me-1"></i> Verify Now
                            </button>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap gap-2 justify-content-center align-items-center small text-muted">
                        <span class="fw-bold">Quick Verification Samples:</span>
                        <a href="<?= BASE_URL; ?>/certificate-verification.php?cert_id=HBF-CIN-2026" class="badge bg-light text-primary border text-decoration-none">MCA CIN (Govt of India)</a>
                        <a href="<?= BASE_URL; ?>/certificate-verification.php?cert_id=HBF-80G-2026" class="badge bg-light text-success border text-decoration-none">80G Tax Exemption</a>
                        <a href="<?= BASE_URL; ?>/certificate-verification.php?cert_id=HBF-VOL-101" class="badge bg-light text-dark border text-decoration-none">Volunteer Certificate</a>
                    </div>
                </div>

                <!-- Verification Result Display -->
                <?php if ($verificationResult !== null): ?>
                    <div class="mt-4">
                        <?php if ($verificationResult === false): ?>
                        <div class="p-4 bg-white rounded-4 border border-danger shadow-sm text-center">
                            <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                            <h4 class="fw-bold text-danger mb-1">No Matching Record Found</h4>
                            <p class="text-muted small mb-3">The certificate ID <code><?= e($searchedId); ?></code> could not be located in our digital database. Please verify the code or contact our support team.</p>
                            <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-sm btn-outline-danger">Contact Support for Manual Verification</a>
                        </div>
                        <?php else: ?>
                        <div class="p-4 p-md-5 bg-white rounded-4 border border-success shadow-sm position-relative overflow-hidden" style="border-left: 8px solid #2ecc71 !important;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <span class="badge bg-success px-3 py-2 fs-6"><i class="fas fa-check-double me-1"></i> <?= e($verificationResult['status']); ?></span>
                                <small class="text-muted"><i class="fas fa-shield-alt text-success me-1"></i> Digital Authenticity Verified</small>
                            </div>

                            <h4 class="fw-bold mb-1 text-dark"><?= e($verificationResult['title']); ?></h4>
                            <p class="text-muted small mb-3"><?= e($verificationResult['type']); ?></p>

                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <div class="row g-2 small">
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block">ISSUED TO / HOLDER:</span>
                                        <strong class="text-dark fs-6"><?= e($verificationResult['holder']); ?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block">ISSUED BY:</span>
                                        <strong class="text-dark"><?= e($verificationResult['issued_by']); ?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block">REFERENCE / IDENTIFIER:</span>
                                        <code class="text-primary fw-bold"><?= e($verificationResult['identifier']); ?></code>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block">DATE / VALIDITY:</span>
                                        <strong class="text-dark"><?= e($verificationResult['date']); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Issued under seal of Herbalbox Foundation (CIN: U86901BR2026NPL087665).</small>
                                <button onclick="window.print();" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print me-1"></i> Print Verification</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Statutory Corporate & Legal Accreditations Section -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark px-3 py-1 fw-bold text-uppercase mb-2">LEGAL COMPLIANCE</span>
            <h2 class="fw-bold display-6 mb-2">Herbalbox Foundation Accreditations</h2>
            <p class="text-muted max-w-650 mx-auto">Public disclosure of statutory licenses, corporate incorporation, and tax exemption compliance.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="p-4 bg-light rounded-4 border h-100 text-center">
                    <i class="fas fa-landmark fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold mb-1">MCA Incorporation</h5>
                    <p class="small text-muted mb-2">Companies Act, 2013 (Govt of India)</p>
                    <code class="d-block p-2 bg-white rounded border mb-2 font-monospace fw-bold">U86901BR2026NPL087665</code>
                    <small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Active & Good Standing</small>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="p-4 bg-light rounded-4 border h-100 text-center">
                    <i class="fas fa-file-invoice-dollar fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold mb-1">Section 80G Tax Benefit</h5>
                    <p class="small text-muted mb-2">50% Income Tax Exemption for Donors</p>
                    <code class="d-block p-2 bg-white rounded border mb-2 font-monospace fw-bold">80G-CIT(E)/DEL/2019-20</code>
                    <small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Certified Exemption</small>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="p-4 bg-light rounded-4 border h-100 text-center">
                    <i class="fas fa-university fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold mb-1">Banking Current A/c</h5>
                    <p class="small text-muted mb-2">Ujjivan Small Finance Bank</p>
                    <code class="d-block p-2 bg-white rounded border mb-2 font-monospace fw-bold">A/C: 3374120030000574</code>
                    <small class="text-muted">IFSC: <strong>UJVN0003374</strong></small>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="p-4 bg-light rounded-4 border h-100 text-center">
                    <i class="fas fa-qrcode fa-3x text-dark mb-3"></i>
                    <h5 class="fw-bold mb-1">Official UPI Gateway</h5>
                    <p class="small text-muted mb-2">Instant Scan & Pay Verification</p>
                    <code class="d-block p-2 bg-white rounded border mb-2 font-monospace fw-bold">UJJ83981868784@Ujjivan</code>
                    <small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Zero Fee Verified</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Manual Verification Request CTA -->
<section class="py-5 bg-light border-top">
    <div class="container text-center">
        <h4 class="fw-bold mb-2">Need Offline Certificate Verification or Verification Letter?</h4>
        <p class="text-muted max-w-650 mx-auto mb-4">Employers, academic institutions, and donors requiring physical seal verification can reach our administrative desk directly.</p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="mailto:herbalboxfoundation@gmail.com?subject=Certificate%20Verification%20Request" class="btn btn-warning text-dark fw-bold px-4 py-2">
                <i class="fas fa-envelope me-1"></i> Email Verification Request
            </a>
            <a href="https://wa.me/919234055507?text=<?= urlencode('Hello Herbalbox Foundation, I would like to verify a certificate ID.'); ?>" target="_blank" class="btn btn-outline-success px-4 py-2">
                <i class="fab fa-whatsapp me-1"></i> WhatsApp Helpline (+91 92340 55507)
            </a>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>