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
            'name' => 'Managing Trustee & Founder',
            'designation' => 'Executive Director',
            'bio' => 'Dedicated to grassroots public health reforms, rural education access, and AYUSH wellness expansion across Bihar.',
            'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&q=80'
        ],
        [
            'name' => 'Chief Medical Officer',
            'designation' => 'Director of Healthcare Operations',
            'bio' => 'Senior physician coordinating free multi-specialty camps, mobile clinics, and hospital surgical partnerships.',
            'photo' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80'
        ],
        [
            'name' => 'Head of Education Initiatives',
            'designation' => 'Director of School Partnerships',
            'bio' => 'Academics coordinator driving NCERT smart classrooms, teacher training, and child nutrition programs.',
            'photo' => 'https://images.unsplash.com/photo-1594824813587-0b1a03975549?w=300&q=80'
        ],
        [
            'name' => 'AYUSH & Yoga Director',
            'designation' => 'Chief Yoga Acharya',
            'bio' => 'Certified Ayurvedic and Panchakarma practitioner heading community wellness centers and morning yoga clinics.',
            'photo' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=300&q=80'
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
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center">
                    <img src="<?= e(getImageUrl($member['photo'] ?? null, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&q=80')); ?>" alt="<?= e($member['name']); ?>" class="img-fluid rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5 class="fw-bold mb-1 fs-6"><?= e($member['name']); ?></h5>
                    <p class="small text-warning fw-bold mb-2"><?= e($member['designation']); ?></p>
                    <p class="small text-muted mb-0"><?= e(truncateText($member['bio'] ?? '', 100)); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Financial Transparency & 80G Certificate -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">FINANCIAL TRANSPARENCY</span>
            <h2 class="fw-bold display-6 mb-2">Annual Reports & Audited Financials</h2>
            <p class="text-muted max-w-650 mx-auto">Download our transparent annual audits and utilization statements.</p>
        </div>

        <div class="row g-3 justify-content-center">
            <div class="col-md-6">
                <div class="p-4 border rounded-4 bg-light d-flex align-items-center justify-content-between shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                        <div>
                            <h5 class="mb-1 fw-bold">80G Income Tax Exemption Certificate</h5>
                            <small class="text-muted">Status: Active & Certified | 50% Tax Deduction</small>
                        </div>
                    </div>
                    <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-sm btn-outline-danger px-3 py-2 fw-bold">
                        <i class="fas fa-download me-1"></i> Verify
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 border rounded-4 bg-light d-flex align-items-center justify-content-between shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-file-shield fa-3x text-success"></i>
                        <div>
                            <h5 class="mb-1 fw-bold">MCA Certificate of Incorporation</h5>
                            <small class="text-muted">CIN: U86901BR2026NPL087665 | Govt of India</small>
                        </div>
                    </div>
                    <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-sm btn-outline-success px-3 py-2 fw-bold">
                        <i class="fas fa-check-circle me-1"></i> Verified
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pan-India Operational Presence (10 States) -->
<section class="py-5 bg-white border-top">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold mb-2">
                <i class="fas fa-globe-asia me-1"></i> अखिल भारतीय सेवा नेटवर्क | Pan-India Outreach
            </span>
            <h2 class="fw-bold display-6 mb-2">हमारी कार्य उपस्थिति एवं विस्तार क्षेत्र (10 राज्य)</h2>
            <p class="text-muted max-w-700 mx-auto">
                आनन्द जानकी जनकल्याण समिति देश के 10 प्रमुख राज्यों व केंद्र शासित प्रदेशों में निःशुल्क स्वास्थ्य शिविर, डिजिटल बाल शिक्षा, महिला सशक्तिकरण एवं सामाजिक उत्थान के लिए निरंतर कार्य कर रही है।
            </p>
        </div>

        <div class="row g-3">
            <?php
            $aboutStates = [
                ['name' => 'New Delhi', 'hindi' => 'नई दिल्ली', 'role' => 'National Capital Outreach, Healthcare & Policy Coordination', 'icon' => 'fa-landmark', 'color' => 'danger'],
                ['name' => 'Uttar Pradesh', 'hindi' => 'उत्तर प्रदेश', 'role' => 'Rural Health Camps, Women Empowerment & Skill Centers', 'icon' => 'fa-hands-helping', 'color' => 'warning'],
                ['name' => 'Uttarakhand', 'hindi' => 'उत्तराखंड', 'role' => 'Hilly Community Wellness, AYUSH & Environment Camps', 'icon' => 'fa-mountain', 'color' => 'info'],
                ['name' => 'Bihar', 'hindi' => 'बिहार', 'role' => 'Primary Operational Center, NCERT Classrooms & Eye Surgeries', 'icon' => 'fa-hospital-user', 'color' => 'success'],
                ['name' => 'Jharkhand', 'hindi' => 'झारखंड', 'role' => 'Tribal Community Healthcare, Child Nutrition & Blood Drives', 'icon' => 'fa-users', 'color' => 'primary'],
                ['name' => 'Odisha (Udisha)', 'hindi' => 'ओडिशा', 'role' => 'Rural Education Support, Disaster Relief & Health Drives', 'icon' => 'fa-hand-holding-medical', 'color' => 'teal'],
                ['name' => 'Madhya Pradesh', 'hindi' => 'मध्य प्रदेश', 'role' => 'Grassroots Child Education Kits & AYUSH Herbal Wellness', 'icon' => 'fa-book-reader', 'color' => 'indigo'],
                ['name' => 'Chhattisgarh', 'hindi' => 'छत्तीसगढ़', 'role' => 'Forest & Tribal Region Mobile Health Clinics', 'icon' => 'fa-ambulance', 'color' => 'danger'],
                ['name' => 'West Bengal', 'hindi' => 'पश्चिम बंगाल', 'role' => 'Community Welfare, Vision Screenings & Preventive Care', 'icon' => 'fa-heartbeat', 'color' => 'success'],
                ['name' => 'Assam', 'hindi' => 'असम', 'role' => 'North-East Health Outreach, Student Kits & Youth Development', 'icon' => 'fa-tree', 'color' => 'warning']
            ];
            ?>

            <?php foreach ($aboutStates as $st): ?>
            <div class="col-lg-4 col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100 d-flex align-items-center gap-3 hover-lift transition">
                    <div class="bg-<?= $st['color']; ?> text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
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
