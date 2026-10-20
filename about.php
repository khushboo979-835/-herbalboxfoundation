<?php
/**
 * About Us Page
 * Herbalbox Foundation - GiveLife Style
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$teamMembers = [];
$reports = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $reportsStmt = $pdo->query("SELECT * FROM about_documents WHERE status = 'published' ORDER BY id ASC");
        $reports = $reportsStmt ? $reportsStmt->fetchAll() : [];

        $teamStmt = $pdo->query("SELECT * FROM team_members WHERE status = 'published' ORDER BY sort_order ASC, id ASC");
        $teamMembers = $teamStmt ? $teamStmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("About Query Notice: " . $e->getMessage());
}

if (empty($teamMembers)) {
    $teamMembers = [
        [
            'name' => 'Syed Mehdi Raza',
            'designation' => 'Director & CEO / General Secretary',
            'bio' => 'Directing Herbalbox Foundation with grassroots dedication. Spearheading free healthcare camps, digital education drives, and AYUSH wellness initiatives across Bihar.',
            'photo' => 'assets/images/team/syed-mehdi-raza-director.jpg',
            'phone' => '+91 99340 43877',
            'email' => 'herbalboxfoundation@gmail.com'
        ],
        [
            'name' => 'Priti Kumari',
            'designation' => 'Director & General Secretary',
            'bio' => '“Leading with Purpose, Building a Healthier, Educated & Brighter Future.” Championing women welfare, child nutrition, smart school kits, and community development.',
            'photo' => 'assets/images/team/director-herbalbox.jpg',
            'phone' => '+91 92340 55507',
            'email' => 'herbalboxfoundation@gmail.com'
        ],
        [
            'name' => 'Dr. Arvind Kumar Singh',
            'designation' => 'Medical Advisor & Senior Consultant Physician',
            'bio' => 'Senior Consultant Physician at Poly Clinic Hajipur, East Central Railway. Guiding the foundation\'s free multi-specialty clinical camps and diagnostics.',
            'photo' => 'assets/images/doctors/dr-arvind-kumar-singh.jpg',
            'phone' => '+91 92340 55507',
            'email' => 'herbalboxfoundation@gmail.com'
        ],
        [
            'name' => 'Dr. Nirbhay Kumar',
            'designation' => 'Healthcare Advisory & Medical Officer',
            'bio' => 'Medical Officer at Divisional Railway Hospital Sonpur. Overseeing clinical emergency response, mobile healthcare clinics, and rural health checkups.',
            'photo' => 'assets/images/doctors/dr-nirbhay-kumar.png',
            'phone' => '+91 92340 55507',
            'email' => 'herbalboxfoundation@gmail.com'
        ]
    ];
}

$pageTitle = 'About Us - History, Mission, Vision & Legal Accreditations | Herbalbox Foundation';
$pageDesc = 'Discover Herbalbox Foundation journey, legal status under Companies Act 2013, CIN: U86901BR2026NPL087665, and community impact.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Page Banner Header -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 rounded-pill fw-bold"><i class="fas fa-certificate me-1"></i> CIN: U86901BR2026NPL087665</span>
        <h1 class="display-5 fw-bold text-white mb-2">About Herbalbox Foundation</h1>
        <p class="lead text-white-50 max-w-700">Working Together for a Better Tomorrow through Community Healthcare, NCERT Digital Education, and Sustainable Social Empowerment.</p>
    </div>
</div>

<!-- History & Core Philosophy -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">ABOUT OUR ORGANIZATION</span>
                <h2 class="fw-bold mb-4 display-6">Dedicated to Sustainable Social Development & Community Welfare</h2>
                <p class="text-muted mb-3">
                    <strong>Herbalbox Foundation</strong> is a professionally established organization incorporated under the <strong>Companies Act, 2013</strong>, with its registered office in <strong>Patna, Bihar</strong>.
                </p>
                <p class="text-muted mb-3">
                    We are committed to creating meaningful social impact through community welfare, awareness, social development, empowerment and sustainable initiatives. Our mission is to work with communities and contribute towards building a more inclusive, responsible and empowered society.
                </p>
                <p class="text-muted mb-4">
                    With an operational campus in <strong>Hajipur (Near Birla Open Minds International School, Konhara Road, Pin: 844101)</strong>, we organize regular free health camps, eye & dental checkups, NCERT smart digital education drives, and holistic AYUSH wellness programs.
                </p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-success mb-1"><i class="fas fa-calendar-check me-2"></i>29 August 2026</h5>
                            <small class="text-muted fw-semibold">Incorporation Date</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-primary mb-1"><i class="fas fa-landmark me-2"></i>Patna, Bihar</h5>
                            <small class="text-muted fw-semibold">Registered Office</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4 bg-white rounded-4 shadow-lg border">
                    <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="img-fluid rounded-4 mb-3" style="max-height: 280px; width: auto; object-fit: contain;">
                    <h4 class="fw-bold mb-1">HERBALBOX FOUNDATION</h4>
                    <p class="text-success fw-bold text-uppercase small mb-2">Health • Education • Better Tomorrow</p>
                    <p class="small text-muted mb-0">CIN: <strong>U86901BR2026NPL087665</strong> | Company Limited by Guarantee</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission, Vision & Core Values -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">OUR GUIDING LIGHT</span>
            <h2 class="fw-bold display-6 mb-2">Mission, Vision & Principles</h2>
            <p class="text-muted max-w-650 mx-auto">Guiding our humanitarian action across healthcare, education, and rural development.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="h-100 p-4 bg-white rounded-4 shadow-sm border border-top border-4 border-warning">
                    <div class="mb-3 text-warning fs-3"><i class="fas fa-bullseye"></i></div>
                    <h4 class="fw-bold mb-3">Our Mission</h4>
                    <p class="text-muted mb-0">To work with communities and contribute towards building a more inclusive, responsible and empowered society through accessible healthcare, quality education, and sustainable initiatives.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="h-100 p-4 bg-white rounded-4 shadow-sm border border-top border-4 border-success">
                    <div class="mb-3 text-success fs-3"><i class="fas fa-eye"></i></div>
                    <h4 class="fw-bold mb-3">Our Vision</h4>
                    <p class="text-muted mb-0">To serve society with integrity, compassion and responsibility while creating opportunities for positive and sustainable change.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="h-100 p-4 bg-white rounded-4 shadow-sm border border-top border-4 border-primary">
                    <div class="mb-3 text-primary fs-3"><i class="fas fa-shield-heart"></i></div>
                    <h4 class="fw-bold mb-3">Core Values</h4>
                    <ul class="text-muted small ps-3 mb-0">
                        <li class="mb-2"><strong>Integrity & Compassion:</strong> Respect and care for every beneficiary.</li>
                        <li class="mb-2"><strong>Responsibility:</strong> Transparent, accountable community service.</li>
                        <li class="mb-2"><strong>Inclusiveness:</strong> Equal access to health and education for all.</li>
                        <li class="mb-0"><strong>Holistic Wellness:</strong> Modern healthcare paired with AYUSH care.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statutory Registrations & Legal Information -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">GOVERNANCE & COMPLIANCE</span>
            <h2 class="fw-bold display-6 mb-2">Corporate & Statutory Registrations</h2>
            <p class="text-muted max-w-650 mx-auto">Incorporated under the Companies Act, 2013 (Ministry of Corporate Affairs, Govt of India).</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-id-card fa-2x text-primary mb-3"></i>
                    <h6 class="fw-bold mb-1">Corporate CIN</h6>
                    <span class="badge bg-dark px-3 py-2 fs-6">U86901BR2026NPL087665</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-building-columns fa-2x text-success mb-3"></i>
                    <h6 class="fw-bold mb-1">Legal Status</h6>
                    <span class="badge bg-success px-3 py-2">Company Limited by Guarantee</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-map-location-dot fa-2x text-info mb-3"></i>
                    <h6 class="fw-bold mb-1">Registered Office</h6>
                    <span class="badge bg-info text-dark px-3 py-2">Patna, Bihar, India</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-calendar-check fa-2x text-warning mb-3"></i>
                    <h6 class="fw-bold mb-1">Incorporated On</h6>
                    <span class="badge bg-warning text-dark px-3 py-2">29 August 2026</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership & Changemakers -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">MEET THE CHANGEMAKERS</span>
            <h2 class="fw-bold display-6 mb-2">Our Dedicated Leadership & Advisory</h2>
            <p class="text-muted max-w-650 mx-auto">A team of dedicated physicians, senior academicians, and social welfare leaders.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($teamMembers as $member): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 p-4 border rounded-4 shadow-sm text-center bg-white d-flex flex-column">
                    <div class="mx-auto mb-3" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid #f39c12; box-shadow: 0 4px 15px rgba(243, 156, 18, 0.25);">
                        <img src="<?= e(getImageUrl($member['photo'] ?? null, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&q=80')); ?>" alt="<?= e($member['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                    </div>
                    <h5 class="fw-bold mb-1 fs-6 text-dark"><?= e($member['name']); ?></h5>
                    <p class="small text-warning fw-bold mb-2"><?= e($member['designation']); ?></p>
                    <p class="small text-muted mb-3 flex-grow-1" style="font-size: 13px;"><?= e(truncateText($member['bio'] ?? '', 120)); ?></p>

                    <div class="p-2 bg-light rounded text-start small border mt-auto">
                        <?php if (!empty($member['phone'])): ?>
                            <div class="text-truncate mb-1"><i class="fas fa-phone-alt text-success me-1"></i> <a href="tel:<?= preg_replace('/[^0-9+]/', '', $member['phone']); ?>" class="text-muted text-decoration-none"><?= e($member['phone']); ?></a></div>
                        <?php endif; ?>
                        <?php if (!empty($member['email'])): ?>
                            <div class="text-truncate"><i class="fas fa-envelope text-primary me-1"></i> <a href="mailto:<?= e($member['email']); ?>" class="text-muted text-decoration-none"><?= e($member['email']); ?></a></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Official Visiting Cards & Website QR Section -->
        <div class="mt-5 p-4 p-lg-5 bg-white rounded-4 border shadow-sm">
            <div class="row align-items-center g-4">
                <div class="col-lg-7 text-center">
                    <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-1 text-uppercase">OFFICIAL IDENTITY CARDS</span>
                    <h4 class="fw-bold mb-3 text-dark">Director Credentials & Visiting Cards</h4>
                    <p class="text-muted small mb-4">Official visiting cards of Director Syed Mehdi Raza and Director Priti Kumari representing Herbalbox Foundation.</p>
                    <div class="p-2 bg-light rounded-4 border shadow-sm d-inline-block">
                        <a href="<?= BASE_URL; ?>/assets/images/team/visiting-cards.png" target="_blank" title="Click to view high-resolution visiting cards">
                            <img src="<?= BASE_URL; ?>/assets/images/team/visiting-cards.png" alt="Herbalbox Foundation Director Visiting Cards" class="img-fluid rounded-3" style="max-height: 280px; width: auto; object-fit: contain;">
                        </a>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted"><i class="fas fa-search-plus me-1"></i> Click on the cards to view full resolution</small>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="p-4 bg-light rounded-4 border text-center">
                        <span class="badge bg-success text-white mb-2 px-3 py-1 fw-bold"><i class="fas fa-qrcode me-1"></i> OFFICIAL WEBSITE QR CODE</span>
                        <h5 class="fw-bold mb-2">Scan & Visit Portal</h5>
                        <p class="small text-muted mb-3">Scan this official QR code with any smartphone camera or QR scanner to open and share Herbalbox Foundation.</p>
                        
                        <div class="p-3 bg-white rounded-3 border d-inline-block shadow-sm mb-3">
                            <img src="<?= BASE_URL; ?>/assets/images/qr/website-qr.png" alt="Herbalbox Foundation Website QR Code" class="img-fluid" style="width: 160px; height: 160px;">
                        </div>

                        <div class="d-flex flex-column gap-2 text-start small">
                            <div class="p-2 bg-white rounded border">
                                <i class="fas fa-globe text-primary me-2"></i> <strong>Website:</strong> <a href="https://herbalboxfoundation.com" target="_blank" class="text-decoration-none">www.herbalboxfoundation.com</a>
                            </div>
                            <div class="p-2 bg-white rounded border">
                                <i class="fas fa-envelope text-info me-2"></i> <strong>Email:</strong> herbalboxfoundation@gmail.com
                            </div>
                            <div class="p-2 bg-white rounded border">
                                <i class="fas fa-phone-alt text-success me-2"></i> <strong>Helpline:</strong> +91 92340 55507 / +91 99340 43877
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Financial Transparency & 80G Certificate -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">FINANCIAL TRANSPARENCY & VERIFICATION</span>
            <h2 class="fw-bold display-6 mb-2">Statutory Accreditations & Verification</h2>
            <p class="text-muted max-w-650 mx-auto">Verify corporate registration, 80G tax exemption, and voluntary participation certificates.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light d-flex flex-column h-100 shadow-sm text-center">
                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                    <h5 class="mb-1 fw-bold">80G Income Tax Exemption</h5>
                    <p class="small text-muted mb-3">Active & Certified | 50% Tax Deduction under Sec 80G for all donors.</p>
                    <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-sm btn-outline-danger mt-auto fw-bold py-2">
                        <i class="fas fa-hand-holding-heart me-1"></i> Donate (80G Receipt)
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light d-flex flex-column h-100 shadow-sm text-center">
                    <i class="fas fa-file-shield fa-3x text-success mb-3"></i>
                    <h5 class="mb-1 fw-bold">MCA Certificate of Incorporation</h5>
                    <p class="small text-muted mb-3">CIN: <strong>U86901BR2026NPL087665</strong> | Ministry of Corporate Affairs, Govt of India.</p>
                    <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-sm btn-outline-success mt-auto fw-bold py-2">
                        <i class="fas fa-check-circle me-1"></i> Verified Organization
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light d-flex flex-column h-100 shadow-sm text-center">
                    <i class="fas fa-award fa-3x text-warning mb-3"></i>
                    <h5 class="mb-1 fw-bold">Certificate Verification Portal</h5>
                    <p class="small text-muted mb-3">Verify volunteer certificates, donor 80G receipts, and partnership accreditations online.</p>
                    <a href="<?= BASE_URL; ?>/certificate-verification.php" class="btn btn-sm btn-warning text-dark mt-auto fw-bold py-2">
                        <i class="fas fa-search me-1"></i> Verify Certificate Online
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="p-5 rounded-4 shadow-lg text-white" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">Join Our Mission</span>
                    <h2 class="text-white fw-bold mb-3 display-6">Become a Volunteer or Partner with Herbalbox Foundation</h2>
                    <p class="text-white-50 lead mb-0">Help us deliver life-saving medical camps, eye refraction screenings, and NCERT digital education to rural communities.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column gap-3">
                        <a href="<?= BASE_URL; ?>/volunteer.php" class="btn btn-givelife-orange btn-lg">
                            <i class="fas fa-user-plus me-1"></i> JOIN AS VOLUNTEER
                        </a>
                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-givelife-white btn-lg">
                            <i class="fas fa-heart text-danger me-1"></i> DONATE (80G EXEMPT)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
