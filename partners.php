<?php
/**
 * Institutional Partners & Collaborations Overview Page
 * Herbalbox Foundation - GiveLife Style
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$schools = [];
$doctors = [];
$hospitals = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $schools = $pdo->query("SELECT * FROM schools WHERE status = 'published' ORDER BY id ASC LIMIT 6")->fetchAll() ?: [];
        $doctors = $pdo->query("SELECT d.*, h.name as hospital_name FROM doctors d LEFT JOIN hospitals h ON d.hospital_id = h.id WHERE d.status = 'published' ORDER BY d.id ASC LIMIT 4")->fetchAll() ?: [];
        $hospitals = $pdo->query("SELECT * FROM hospitals WHERE status = 'published' ORDER BY id ASC LIMIT 6")->fetchAll() ?: [];
    }
} catch (Throwable $e) {
    error_log("Partners Query Notice: " . $e->getMessage());
}

if (empty($schools)) {
    $schools = [
        [
            'name' => 'Birla Open Minds International School Partner',
            'city' => 'Hajipur',
            'state' => 'Bihar',
            'mou_date' => '2026-08-29',
            'description' => 'Collaborative partnership for student wellness checkups, digital education support, and environmental awareness.',
            'logo' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=200&q=80'
        ],
        [
            'name' => 'Adarsh Vidya Mandir High School',
            'city' => 'Patna',
            'state' => 'Bihar',
            'mou_date' => '2026-08-15',
            'description' => 'NCERT digital smart class installation and annual eye refraction clinics for over 800 students.',
            'logo' => 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=200&q=80'
        ],
        [
            'name' => 'Saraswati Gyan Mandir',
            'city' => 'Vaishali',
            'state' => 'Bihar',
            'mou_date' => '2026-07-20',
            'description' => 'Comprehensive school nutrition and regular pediatric health checkups for underprivileged children.',
            'logo' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=200&q=80'
        ]
    ];
}

if (empty($doctors)) {
    $doctors = [
        [
            'name' => 'Dr. Rajesh Kumar Sharma',
            'specialization' => 'Senior Ophthalmologist & Eye Surgeon',
            'qualification' => 'MBBS, MS (Ophthalmology)',
            'treatment_type' => 'allopathy',
            'photo' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80'
        ],
        [
            'name' => 'Dr. Ananya Verma',
            'specialization' => 'Consultant Pediatrician & Child Specialist',
            'qualification' => 'MBBS, MD (Pediatrics)',
            'treatment_type' => 'allopathy',
            'photo' => 'https://images.unsplash.com/photo-1594824813587-0b1a03975549?w=300&q=80'
        ],
        [
            'name' => 'Acharya Ved Prakash',
            'specialization' => 'Ayurveda & Panchakarma Specialist',
            'qualification' => 'BAMS, MD (Ayurveda)',
            'treatment_type' => 'ayush',
            'photo' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=300&q=80'
        ],
        [
            'name' => 'Dr. Sunita Mishra',
            'specialization' => 'General Physician & Community Health Expert',
            'qualification' => 'MBBS, DNB (Family Medicine)',
            'treatment_type' => 'allopathy',
            'photo' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=300&q=80'
        ]
    ];
}

$pageTitle = 'Institutional Partners & Collaborations | Herbalbox Foundation';
$pageDesc = 'Discover Herbalbox Foundation partner schools, specialist doctor network, and hospital collaborations.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Page Banner Header -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4 text-center">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 rounded-pill fw-bold"><i class="fas fa-handshake me-1"></i> Strategic Alliances</span>
        <h1 class="display-5 fw-bold text-white mb-2">Our Institutional Partners & Network</h1>
        <p class="lead text-white-50 max-w-700 mx-auto">Collaborating with schools, medical institutions, and volunteer doctors to maximize social impact.</p>
    </div>
</div>

<!-- 1. School Partners Section -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">EDUCATION ALLIANCES</span>
            <h2 class="fw-bold display-6 mb-2">Partner Schools & MOUs</h2>
            <p class="text-muted max-w-650 mx-auto">Providing student health screenings, eye checkups, NCERT study kits, and digital classrooms across Bihar.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($schools as $school): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center">
                    <img src="<?= e(getImageUrl($school['logo'] ?? null, 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=200&q=80')); ?>" alt="<?= e($school['name']); ?>" class="img-fluid rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5 class="fw-bold mb-1"><?= e($school['name']); ?></h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-1"></i> <?= e($school['city']); ?>, <?= e($school['state']); ?></p>
                    <div class="badge bg-success-subtle text-success mb-3">MOU Signed: <?= formatDate($school['mou_date']); ?></div>
                    <p class="small text-muted mb-3"><?= e(truncateText($school['description'], 110)); ?></p>
                    <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-sm btn-outline-dark mt-auto">View School Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 2. Doctor Network Section -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">MEDICAL EXPERTISE</span>
            <h2 class="fw-bold display-6 mb-2">Specialist Doctor Network</h2>
            <p class="text-muted max-w-650 mx-auto">Distinguished allopathic specialists and AYUSH doctors volunteering their services in our camps.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($doctors as $doc): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center">
                    <img src="<?= e(getImageUrl($doc['photo'] ?? null, 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80')); ?>" alt="<?= e($doc['name']); ?>" class="img-fluid rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5 class="fw-bold mb-1 fs-6"><?= e($doc['name']); ?></h5>
                    <p class="small text-warning fw-bold mb-1"><?= e($doc['specialization']); ?></p>
                    <p class="small text-muted mb-2"><?= e($doc['qualification']); ?></p>
                    <div class="badge bg-secondary-subtle text-secondary mb-3"><?= e(ucfirst($doc['treatment_type'] ?? 'Allopathy')); ?> Care</div>
                    <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-sm btn-outline-dark mt-auto">Consultation Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 3. Become a Partner CTA -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="p-5 rounded-4 shadow-lg text-white" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">Collaborate With Us</span>
                    <h2 class="text-white fw-bold mb-3 display-6">Sign an MOU with Herbalbox Foundation</h2>
                    <p class="text-white-50 lead mb-0">We invite schools, colleges, corporate CSR programs, and hospitals to collaborate for community health and education missions.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?= BASE_URL; ?>/partnerships.php" class="btn btn-givelife-orange btn-lg">
                        <i class="fas fa-file-contract me-1"></i> APPLY FOR PARTNERSHIP
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
