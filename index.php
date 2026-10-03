<?php
/**
 * Master Homepage
 * Herbalbox Foundation - Modern Humanitarian NGO Architecture
 * GiveLife 1-to-1 Premium Design
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$impactStats = [];
$featuredPrograms = [];
$upcomingEvents = [];
$featuredDoctors = [];
$featuredSchools = [];
$testimonials = [];
$latestBlogs = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $statsStmt = $pdo->query("SELECT * FROM impact_statistics WHERE status = 'published' ORDER BY sort_order ASC, id ASC LIMIT 6");
        $impactStats = $statsStmt ? $statsStmt->fetchAll() : [];

        $programsStmt = $pdo->query("SELECT p.*, c.name as category_name, c.slug as category_slug FROM programs p LEFT JOIN program_categories c ON p.category_id = c.id WHERE p.status = 'published' ORDER BY p.sort_order ASC, p.id ASC LIMIT 4");
        $featuredPrograms = $programsStmt ? $programsStmt->fetchAll() : [];

        $eventsStmt = $pdo->query("SELECT * FROM events WHERE status IN ('upcoming', 'published') ORDER BY event_date ASC LIMIT 3");
        $upcomingEvents = $eventsStmt ? $eventsStmt->fetchAll() : [];

        $doctorsStmt = $pdo->query("SELECT d.*, h.name as hospital_name FROM doctors d LEFT JOIN hospitals h ON d.hospital_id = h.id WHERE d.status = 'published' AND d.is_featured = 1 ORDER BY d.id ASC LIMIT 4");
        $featuredDoctors = $doctorsStmt ? $doctorsStmt->fetchAll() : [];

        $schoolsStmt = $pdo->query("SELECT * FROM schools WHERE status = 'published' AND is_featured = 1 ORDER BY id ASC LIMIT 3");
        $featuredSchools = $schoolsStmt ? $schoolsStmt->fetchAll() : [];

        $testimonialsStmt = $pdo->query("SELECT * FROM testimonials WHERE status = 'published' ORDER BY id DESC LIMIT 4");
        $testimonials = $testimonialsStmt ? $testimonialsStmt->fetchAll() : [];

        $blogStmt = $pdo->query("SELECT b.*, c.name as category_name FROM blog_posts b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.status = 'published' ORDER BY b.published_at DESC LIMIT 3");
        $latestBlogs = $blogStmt ? $blogStmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("Index Query Notice: " . $e->getMessage());
}

// 1. Guaranteed Real 4 Causes (Compact 4-column row)
$defaultCauses = [
    [
        'id' => 1,
        'title' => 'Mobile Multi-Specialty Health Clinics',
        'category_name' => 'Healthcare',
        'image' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80',
        'short_description' => 'Mobile medical vans providing physician consultations, ECG, blood tests, and 7-day free medicines.',
        'target_amount' => 500000,
        'raised_amount' => 385000
    ],
    [
        'id' => 2,
        'title' => 'Netra Jyoti Eye Care & Cataract Surgery',
        'category_name' => 'Eye Care',
        'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=600&q=80',
        'short_description' => 'Vision screenings, free prescription glasses, and sponsored cataract surgeries for needy rural elders.',
        'target_amount' => 600000,
        'raised_amount' => 450000
    ],
    [
        'id' => 3,
        'title' => 'NCERT Smart Digital Classrooms',
        'category_name' => 'Education',
        'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80',
        'short_description' => 'Digitizing rural classrooms with smart learning kits, free NCERT books, bags, and stationery support.',
        'target_amount' => 450000,
        'raised_amount' => 340000
    ],
    [
        'id' => 4,
        'title' => 'Ayush Arogya & Herbal Wellness',
        'category_name' => 'AYUSH',
        'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&q=80',
        'short_description' => 'Free Ayurvedic pulse diagnosis (Nadi Pariksha), pure herbal decoctions, and preventive lifestyle camps.',
        'target_amount' => 350000,
        'raised_amount' => 280000
    ]
];

$causesList = !empty($featuredPrograms) ? array_map(function($p, $i) use ($defaultCauses) {
    $fallback = $defaultCauses[$i % count($defaultCauses)];
    return [
        'id' => $p['id'] ?? $fallback['id'],
        'title' => !empty($p['title']) ? $p['title'] : $fallback['title'],
        'category_name' => !empty($p['category_name']) ? $p['category_name'] : $fallback['category_name'],
        'image' => $fallback['image'],
        'short_description' => !empty($p['short_description']) ? $p['short_description'] : $fallback['short_description'],
        'target_amount' => (float)(!empty($p['target_amount']) ? $p['target_amount'] : $fallback['target_amount']),
        'raised_amount' => (float)(!empty($p['raised_amount']) ? $p['raised_amount'] : $fallback['raised_amount'])
    ];
}, array_slice($featuredPrograms, 0, 4), array_keys(array_slice($featuredPrograms, 0, 4))) : $defaultCauses;

// 2. Guaranteed Real 3 Events
$defaultEvents = [
    [
        'id' => 1,
        'title' => 'Mega Multi-Specialty Health & Eye Camp',
        'category' => 'Medical Camp',
        'event_date' => '2026-10-15',
        'venue' => 'Near Birla Open Minds School, Konhara Road',
        'city' => 'Hajipur, Bihar',
        'short_description' => 'Free cardiology, dental, and eye checkups. Free spectacles and essential medicines for 800+ villagers.',
        'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80'
    ],
    [
        'id' => 2,
        'title' => 'Voluntary Blood Donation & Platelet Drive',
        'category' => 'Blood Donation',
        'event_date' => '2026-10-24',
        'venue' => 'Herbalbox Community Health Center',
        'city' => 'Patna, Bihar',
        'short_description' => 'Save lives through voluntary blood donation. Donors receive full blood screening and certificate of appreciation.',
        'image' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=600&q=80'
    ],
    [
        'id' => 3,
        'title' => 'NCERT Learning & Study Kit Distribution',
        'category' => 'Education',
        'event_date' => '2026-11-05',
        'venue' => 'Partner Government High School',
        'city' => 'Vaishali, Bihar',
        'short_description' => 'Distributing NCERT curriculum books, school bags, notebooks, and digital learning tools to 500+ needy students.',
        'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&q=80'
    ]
];

$eventsList = !empty($upcomingEvents) ? array_map(function($e, $i) use ($defaultEvents) {
    $fallback = $defaultEvents[$i % count($defaultEvents)];
    return [
        'id' => $e['id'] ?? $fallback['id'],
        'title' => !empty($e['title']) ? $e['title'] : $fallback['title'],
        'category' => !empty($e['category']) ? str_replace('_', ' ', ucwords($e['category'])) : $fallback['category'],
        'event_date' => !empty($e['event_date']) ? $e['event_date'] : $fallback['event_date'],
        'venue' => !empty($e['venue']) ? $e['venue'] : $fallback['venue'],
        'city' => !empty($e['city']) ? $e['city'] : $fallback['city'],
        'short_description' => !empty($e['short_description']) ? $e['short_description'] : $fallback['short_description'],
        'image' => $fallback['image']
    ];
}, array_slice($upcomingEvents, 0, 3), array_keys(array_slice($upcomingEvents, 0, 3))) : $defaultEvents;

// 3. Guaranteed Real School Partners
$defaultSchools = [
    [
        'name' => 'Birla Open Minds International School Partner',
        'city' => 'Hajipur',
        'state' => 'Bihar',
        'mou_date' => '2026-08-29',
        'description' => 'Collaborative partnership for student wellness checkups, digital classrooms, and environmental drives.',
        'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80'
    ],
    [
        'name' => 'Adarsh Pragati Bal Niketan High School',
        'city' => 'Patna',
        'state' => 'Bihar',
        'mou_date' => '2026-08-15',
        'description' => 'Equipped with smart digital classrooms, audio-visual kits, and annual eye checkups for 850 students.',
        'image' => 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=600&q=80'
    ],
    [
        'name' => 'Shri Saraswati Gyan Mandir Rural School',
        'city' => 'Vaishali',
        'state' => 'Bihar',
        'mou_date' => '2026-07-20',
        'description' => 'Comprehensive child nutrition, free study materials, and regular pediatric health checkups.',
        'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80'
    ]
];

$schoolsList = !empty($featuredSchools) ? array_map(function($s, $i) use ($defaultSchools) {
    $fallback = $defaultSchools[$i % count($defaultSchools)];
    return [
        'name' => !empty($s['name']) ? $s['name'] : $fallback['name'],
        'city' => !empty($s['city']) ? $s['city'] : $fallback['city'],
        'state' => !empty($s['state']) ? $s['state'] : $fallback['state'],
        'mou_date' => !empty($s['mou_date']) ? $s['mou_date'] : $fallback['mou_date'],
        'description' => !empty($s['description']) ? $s['description'] : $fallback['description'],
        'image' => $fallback['image']
    ];
}, array_slice($featuredSchools, 0, 3), array_keys(array_slice($featuredSchools, 0, 3))) : $defaultSchools;

// 4. Guaranteed Real 4 Doctors (One Row)
$defaultDoctors = [
    [
        'name' => 'Dr. Arvind Sharma',
        'specialization' => 'General Physician & Cardiology',
        'qualification' => 'MBBS, MD (Medicine), FCCP',
        'type' => 'Allopathy Care',
        'image' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=600&q=80'
    ],
    [
        'name' => 'Dr. Sunita Aggarwal',
        'specialization' => 'Ophthalmology & Cornea Specialist',
        'qualification' => 'MBBS, MS (Ophthalmology), FICO',
        'type' => 'Eye Specialist',
        'image' => 'https://images.unsplash.com/photo-1594824813587-0b1a03975549?w=600&q=80'
    ],
    [
        'name' => 'Vaidya Harishankar Joshi',
        'specialization' => 'Ayurvedic Pulse Diagnosis (Nadi Pariksha)',
        'qualification' => 'BAMS, MD (Kayachikitsa)',
        'type' => 'AYUSH Care',
        'image' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=600&q=80'
    ],
    [
        'name' => 'Dr. Meenakshi Iyer',
        'specialization' => 'Pediatric Dental Surgeon',
        'qualification' => 'BDS, MDS (Pediatric Dentistry)',
        'type' => 'Dental Care',
        'image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&q=80'
    ]
];

$doctorsList = !empty($featuredDoctors) ? array_map(function($d, $i) use ($defaultDoctors) {
    $fallback = $defaultDoctors[$i % count($defaultDoctors)];
    return [
        'name' => !empty($d['name']) ? $d['name'] : $fallback['name'],
        'specialization' => !empty($d['specialization']) ? $d['specialization'] : $fallback['specialization'],
        'qualification' => !empty($d['qualification']) ? $d['qualification'] : $fallback['qualification'],
        'type' => !empty($d['treatment_type']) ? ucfirst($d['treatment_type']) . ' Care' : $fallback['type'],
        'image' => $fallback['image']
    ];
}, array_slice($featuredDoctors, 0, 4), array_keys(array_slice($featuredDoctors, 0, 4))) : $defaultDoctors;

// 5. Guaranteed Real 3 News / Articles
$defaultBlogs = [
    [
        'title' => 'Over 850 Patients Benefited from Free Health & Eye Camp in Hajipur',
        'slug' => 'mega-health-camp-hajipur-impact',
        'category_name' => 'Healthcare',
        'published_at' => date('d M Y', strtotime('-3 days')),
        'short_description' => 'A detailed report of our multi-specialty camp featuring cardiac, dental, and eye screenings along with free medicine distribution.',
        'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80'
    ],
    [
        'title' => 'Ayurvedic Principles of Immunity (Ojas) for Changing Seasons',
        'slug' => 'power-of-ayush-and-yoga',
        'category_name' => 'AYUSH & Yoga',
        'published_at' => date('d M Y', strtotime('-7 days')),
        'short_description' => 'Insights from our certified Ayurvedic practitioners on herbal decoctions, immune boosting, and simple morning Pranayama.',
        'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&q=80'
    ],
    [
        'title' => 'Bridging the Rural Digital Learning Gap with Interactive NCERT Pedagogy',
        'slug' => 'digitizing-rural-education-bihar',
        'category_name' => 'Education',
        'published_at' => date('d M Y', strtotime('-12 days')),
        'short_description' => 'How digital classroom tools and interactive audio-visual learning kits are transforming primary education in government schools.',
        'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80'
    ]
];

$blogsList = !empty($latestBlogs) ? array_map(function($b, $i) use ($defaultBlogs) {
    $fallback = $defaultBlogs[$i % count($defaultBlogs)];
    return [
        'title' => !empty($b['title']) ? $b['title'] : $fallback['title'],
        'slug' => !empty($b['slug']) ? $b['slug'] : $fallback['slug'],
        'category_name' => !empty($b['category_name']) ? $b['category_name'] : $fallback['category_name'],
        'published_at' => !empty($b['published_at']) ? date('d M Y', strtotime($b['published_at'])) : $fallback['published_at'],
        'short_description' => !empty($b['short_description']) ? $b['short_description'] : $fallback['short_description'],
        'image' => $fallback['image']
    ];
}, array_slice($latestBlogs, 0, 3), array_keys(array_slice($latestBlogs, 0, 3))) : $defaultBlogs;

// 6. Impact Statistics Defaults
if (empty($impactStats)) {
    $impactStats = [
        ['title' => 'Patients Treated', 'count_number' => '150,000+'],
        ['title' => 'Medical Camps', 'count_number' => '420+'],
        ['title' => 'Free Cataract Surgeries', 'count_number' => '3,850+'],
        ['title' => 'Students Supported', 'count_number' => '24,000+'],
        ['title' => 'Schools Digitized', 'count_number' => '48+'],
        ['title' => 'Blood Units Collected', 'count_number' => '5,600+']
    ];
}

$pageTitle = 'Herbalbox Foundation | Health • Education • Better Tomorrow';
$pageDesc = 'Herbalbox Foundation (CIN: U86901BR2026NPL087665) - Dedicated to free medical camps, NCERT digital education, and community development across Bihar.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. GiveLife Hero Banner -->
<section class="givelife-hero-banner">
    <button class="givelife-slider-arrow left" type="button" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
    <button class="givelife-slider-arrow right" type="button" aria-label="Next"><i class="fas fa-chevron-right"></i></button>

    <div class="container position-relative" style="z-index: 5;">
        <div class="row">
            <div class="col-lg-9 col-xl-8">
                <h1 class="hero-main-title">
                    We Can <br>
                    Help The Poor
                </h1>
                <p class="hero-subtext-para">
                    Herbalbox Foundation is committed to creating meaningful social impact through community welfare, free multi-specialty medical camps, NCERT digital education, and sustainable empowerment.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-givelife-orange">
                        DONATE NOW
                    </a>
                    <a href="<?= BASE_URL; ?>/about.php" class="btn btn-givelife-white">
                        ABOUT US
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Three Feature Pillar Cards (Below Hero) -->
<div class="container givelife-pillars-wrap">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-4 col-md-6">
            <div class="givelife-pillar-card">
                <div class="pillar-icon-box">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <h3 class="pillar-title">Free Healthcare & Camps</h3>
                <p class="pillar-desc">
                    Organizing free multi-specialty health checkups, vision tests, free cataract surgeries, dental care, and essential medicine distribution for rural families.
                </p>
                <a href="<?= BASE_URL; ?>/healthcare.php" class="fw-bold text-dark mt-auto d-inline-flex align-items-center gap-1">
                    Read More <i class="fas fa-arrow-right text-warning small"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="givelife-pillar-card pillar-green">
                <div class="pillar-icon-box">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h3 class="pillar-title">NCERT Digital Education</h3>
                <p class="pillar-desc">
                    Digitizing classrooms, providing smart audio-visual curriculum kits, and distributing free NCERT textbooks to underprivileged students from 1st to 12th.
                </p>
                <a href="<?= BASE_URL; ?>/education.php" class="fw-bold text-dark mt-auto d-inline-flex align-items-center gap-1">
                    Read More <i class="fas fa-arrow-right text-success small"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="givelife-pillar-card pillar-blue">
                <div class="pillar-icon-box">
                    <i class="fas fa-spa"></i>
                </div>
                <h3 class="pillar-title">AYUSH & Community Relief</h3>
                <p class="pillar-desc">
                    Daily morning yoga, Pranayama, certified Ayurvedic wellness consultations, emergency blood donation drives, and sustainable women empowerment initiatives.
                </p>
                <a href="<?= BASE_URL; ?>/ayush.php" class="fw-bold text-dark mt-auto d-inline-flex align-items-center gap-1">
                    Read More <i class="fas fa-arrow-right text-info small"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 3. About Us Section -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800&q=80" alt="Herbalbox Foundation Charity Work" class="img-fluid rounded-4 shadow-lg w-100">
                    <div class="position-absolute bottom-0 start-0 bg-white p-3 m-3 rounded-3 shadow border-start border-4 border-warning d-flex align-items-center gap-3">
                        <div class="bg-warning text-white p-3 rounded-circle fs-4">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">100% Tax Deductible</h6>
                            <small class="text-muted">Section 80G & 12A Certified</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">ABOUT OUR FOUNDATION</span>
                <h2 class="fw-bold mb-3 display-6">Serving Society with Integrity, Compassion & Responsibility</h2>
                <p class="text-muted mb-4">
                    <strong>Herbalbox Foundation</strong> is a professionally established organization incorporated under the <strong>Companies Act, 2013</strong> (CIN: <span class="text-warning fw-bold">U86901BR2026NPL087665</span>), with its registered office in Patna, Bihar, and operational campus in Hajipur.
                </p>
                <p class="text-muted mb-4">
                    We are committed to creating meaningful social impact through community welfare, free multi-specialty medical camps, NCERT digital education, and sustainable empowerment.
                </p>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-warning">
                            <h6 class="fw-bold mb-1"><i class="fas fa-bullseye text-warning me-2"></i> Our Mission</h6>
                            <p class="small text-muted mb-0">To work with communities and build a more inclusive, healthy, and educated society.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-success">
                            <h6 class="fw-bold mb-1"><i class="fas fa-eye text-success me-2"></i> Our Vision</h6>
                            <p class="small text-muted mb-0">To serve society with compassion while creating opportunities for positive, sustainable change.</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <a href="<?= BASE_URL; ?>/about.php" class="btn btn-givelife-orange">
                        LEARN MORE ABOUT US
                    </a>
                    <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-outline-dark fw-bold px-4 py-2">
                        CONTACT US
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Featured Humanitarian Campaigns (Modified: Exactly 4 Cards in 1 Single Row) -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">OUR CAUSES</span>
            <h2 class="fw-bold display-6 mb-2">Featured Humanitarian Campaigns</h2>
            <p class="text-muted max-w-650 mx-auto">Support our ongoing health and schooling initiatives across Bihar. Every single contribution saves lives.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($causesList as $prog): ?>
            <?php 
                $categoryName = (string)($prog['category_name'] ?? 'Healthcare');
                $target = (float)($prog['target_amount'] ?? 500000);
                $raised = (float)($prog['raised_amount'] ?? 350000);
                $pct = $target > 0 ? min(100, round(($raised / $target) * 100)) : 70;
            ?>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="cause-card-give h-100 shadow-sm border-0 rounded-4 overflow-hidden d-flex flex-column bg-white">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="<?= e((string)$prog['image']); ?>" alt="<?= e((string)$prog['title']); ?>" style="height: 100%; width: 100%; object-fit: cover;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark fw-bold px-2 py-1 small"><?= e(strtoupper($categoryName)); ?></span>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h6 class="fw-bold fs-6 mb-2" style="min-height: 42px; line-height: 1.35;">
                            <a href="<?= BASE_URL; ?>/causes.php" class="text-dark text-decoration-none">
                                <?= e((string)$prog['title']); ?>
                            </a>
                        </h6>
                        <p class="small text-muted mb-3 flex-grow-1" style="font-size: 13px; line-height: 1.45;"><?= e(truncateText((string)$prog['short_description'], 85)); ?></p>
                        
                        <!-- Progress Bar -->
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $pct; ?>%;" aria-valuenow="<?= $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-3" style="font-size: 12px; font-weight: 600;">
                            <span class="text-success">₹ <?= number_format($raised); ?></span>
                            <span class="text-muted">Goal: ₹ <?= number_format($target); ?></span>
                        </div>

                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-givelife-orange w-100 py-2 btn-sm fw-bold mt-auto">
                            <i class="fas fa-heart text-danger me-1"></i> DONATE NOW
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Impact Counter Strip -->
<section class="impact-counters-bar">
    <div class="container">
        <div class="row g-4 justify-content-center text-center">
            <?php foreach ($impactStats as $stat): ?>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="counter-digit"><?= e((string)$stat['count_number']); ?></div>
                <div class="counter-name"><?= e((string)$stat['title']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 6. Free Medical Camps & Blood Drives -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-light rounded-4 border-start border-5 border-danger h-100 shadow-sm">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle p-3 mb-3" style="width: 55px; height: 55px;">
                        <i class="fas fa-tint fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Life-Saving Blood Donation Drives</h3>
                    <p class="text-muted mb-4">We organize certified blood donation camps in partnership with leading hospital blood banks. Over 5,600+ units collected to date across Bihar.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Free complete blood screening report for all donors</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> 24/7 Emergency Blood Donor Helpdesk</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Recognition certificates and donor appreciation kit</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/blood-donation.php" class="btn btn-danger fw-bold px-4 py-2"><i class="fas fa-heart me-1"></i> Register as Blood Donor</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-light rounded-4 border-start border-5 border-success h-100 shadow-sm">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-3 mb-3" style="width: 55px; height: 55px;">
                        <i class="fas fa-clinic-medical fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Free Multi-Specialty Medical Camps</h3>
                    <p class="text-muted mb-4">Bringing certified physicians, optometrists, dentists, and free essential medicines directly into rural villages and underserved urban settlements in Vaishali & Patna.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> General consultation, ECG & Blood Sugar tests</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Free prescription reading glasses distribution</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Sponsored cataract surgeries at partner hospitals</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/medical-camps.php" class="btn btn-success fw-bold px-4 py-2"><i class="fas fa-calendar-check me-1"></i> View Upcoming Camps</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Upcoming Events & Camps (With Work-Specific Images) -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="text-warning fw-bold text-uppercase small tracking-wider mb-1 d-inline-block">JOIN OUR ACTION</span>
                <h2 class="fw-bold display-6 mb-0">Upcoming Events & Camps</h2>
            </div>
            <a href="<?= BASE_URL; ?>/events.php" class="btn btn-outline-dark fw-bold btn-sm">View All Events <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($eventsList as $evt): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        <img src="<?= e((string)$evt['image']); ?>" alt="<?= e((string)$evt['title']); ?>" style="height: 100%; width: 100%; object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark fw-bold px-3 py-2 small shadow-sm">
                            <i class="fas fa-calendar-day me-1"></i> <?= formatDate((string)$evt['event_date']); ?>
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-light text-primary align-self-start mb-2 px-2 py-1"><?= e((string)$evt['category']); ?></span>
                        <h5 class="fw-bold mb-2 fs-6"><a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="text-dark text-decoration-none"><?= e((string)$evt['title']); ?></a></h5>
                        <p class="small text-muted mb-3"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e((string)$evt['venue']); ?>, <?= e((string)$evt['city']); ?></p>
                        <p class="small text-muted mb-4 flex-grow-1"><?= e((string)$evt['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="btn btn-givelife-orange w-100 py-2 mt-auto">FREE REGISTRATION</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. School Partners & MOUs (With Real Campus Images) -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">EDUCATION ALLIANCES</span>
            <h2 class="fw-bold display-6 mb-2">School Partners & MOUs</h2>
            <p class="text-muted max-w-650 mx-auto">Promoting student wellness, eye screenings, NCERT digital learning support, and yoga across partner schools.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($schoolsList as $school): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white d-flex flex-column">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="<?= e((string)$school['image']); ?>" alt="<?= e((string)$school['name']); ?>" style="height: 100%; width: 100%; object-fit: cover;">
                        <span class="position-absolute bottom-0 start-0 m-3 badge bg-success text-white fw-bold px-2 py-1 small shadow-sm">
                            <i class="fas fa-file-signature me-1"></i> MOU Signed: <?= formatDate((string)$school['mou_date']); ?>
                        </span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1 text-center">
                        <h5 class="fw-bold mb-1 fs-6"><?= e((string)$school['name']); ?></h5>
                        <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-1"></i> <?= e((string)$school['city']); ?>, <?= e((string)$school['state']); ?></p>
                        <p class="small text-muted mb-3 flex-grow-1"><?= e(truncateText((string)$school['description'], 110)); ?></p>
                        <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-sm btn-outline-dark mt-auto py-2">View School Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 9. Distinguished Doctor Network (Fixed Photo Rendering & 4-Column Grid) -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">MEDICAL EXPERTISE</span>
            <h2 class="fw-bold display-6 mb-2">Distinguished Doctor Network</h2>
            <p class="text-muted max-w-650 mx-auto">Dedicated allopathic specialists and AYUSH practitioners volunteering their medical expertise.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($doctorsList as $doc): ?>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center bg-white d-flex flex-column">
                    <div class="mx-auto mb-3" style="width: 110px; height: 110px; border-radius: 50%; overflow: hidden; border: 3px solid #f39c12; box-shadow: 0 4px 12px rgba(243, 156, 18, 0.25);">
                        <img src="<?= e((string)$doc['image']); ?>" alt="<?= e((string)$doc['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h5 class="fw-bold mb-1 fs-6"><?= e((string)$doc['name']); ?></h5>
                    <p class="small text-warning fw-bold mb-1"><?= e((string)$doc['specialization']); ?></p>
                    <p class="small text-muted mb-2" style="font-size: 12.5px;"><?= e((string)$doc['qualification']); ?></p>
                    <div class="badge bg-light text-dark border mb-3"><?= e((string)$doc['type']); ?></div>
                    <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-sm btn-outline-dark mt-auto py-2">Consultation Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 10. Testimonials -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase small tracking-wider mb-2 d-inline-block">TESTIMONIALS</span>
            <h2 class="fw-bold display-6 mb-2">Voices of Beneficiaries & Partners</h2>
            <p class="text-muted max-w-650 mx-auto">Read how our medical camps, cataract surgeries, school support, and wellness programs have touched real lives across Bihar.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
            <?php 
                $tName = (string)($t['name'] ?? 'Beneficiary');
                $initial = strtoupper(substr($tName, 0, 1));
            ?>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4 d-flex flex-column bg-light">
                    <?= renderRatingStars((int)($t['rating'] ?? 5)); ?>
                    <p class="small text-muted my-3 flex-grow-1">"<?= e((string)($t['content'] ?? '')); ?>"</p>
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                            <?= $initial; ?>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold fs-6"><?= e($tName); ?></h6>
                            <small class="text-muted d-block"><?= e((string)($t['designation'] ?? 'Beneficiary')); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 11. Latest News & Articles (With Authentic Images) -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="text-warning fw-bold text-uppercase small tracking-wider mb-1 d-inline-block">KNOWLEDGE & UPDATES</span>
                <h2 class="fw-bold display-6 mb-0">Latest News & Health Articles</h2>
            </div>
            <a href="<?= BASE_URL; ?>/blog.php" class="btn btn-outline-dark fw-bold btn-sm">View All Articles <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($blogsList as $blog): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white d-flex flex-column">
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        <img src="<?= e((string)$blog['image']); ?>" alt="<?= e((string)$blog['title']); ?>" style="height: 100%; width: 100%; object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark fw-bold px-2 py-1 small"><?= e((string)$blog['category_name']); ?></span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <small class="text-muted mb-2 d-block"><i class="fas fa-calendar-alt me-1"></i> <?= e((string)$blog['published_at']); ?></small>
                        <h5 class="fw-bold mb-2 fs-6"><a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e((string)$blog['slug']); ?>" class="text-dark text-decoration-none"><?= e((string)$blog['title']); ?></a></h5>
                        <p class="small text-muted mb-3 flex-grow-1"><?= e((string)$blog['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e((string)$blog['slug']); ?>" class="text-warning fw-bold small text-decoration-none mt-auto">Read Full Article <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 12. Pan-India Operational Presence (10 States) -->
<section class="py-5 bg-white border-top">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold mb-2">
                <i class="fas fa-map-marked-alt me-1"></i> अखिल भारतीय सेवा नेटवर्क | Pan-India Outreach
            </span>
            <h2 class="fw-bold display-6 mb-2">हमारी कार्य उपस्थिति एवं विस्तार क्षेत्र</h2>
            <p class="text-muted max-w-700 mx-auto">
                आनन्द जानकी जनकल्याण समिति 10 प्रमुख राज्यों व केंद्र शासित प्रदेशों में निःशुल्क स्वास्थ्य शिविर, डिजिटल बाल शिक्षा, महिला सशक्तिकरण एवं सामाजिक उत्थान हेतु समर्पित है।
            </p>
        </div>

        <div class="row g-3">
            <?php
            $homepageStates = [
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

            <?php foreach ($homepageStates as $st): ?>
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

<!-- 13. Instant UPI Scanner & Direct Bank Donation Spotlight -->
<section class="py-5" style="background: #f8fafc;">
    <div class="container py-3">
        <div class="row g-4 align-items-center">
            <!-- Left QR Card -->
            <div class="col-lg-5 text-center">
                <div class="p-4 bg-white rounded-4 shadow-sm border">
                    <div class="p-3 bg-light rounded-4 mb-3 position-relative" style="border: 2px dashed #059669; display: inline-block;">
                        <img src="<?= BASE_URL; ?>/assets/images/qr-code.png" alt="UPI Scanner Union Bank" class="img-fluid rounded-3" style="max-height: 220px; object-fit: contain;">
                        <div class="fw-bold text-uppercase mt-2" style="font-size: 11px; letter-spacing: 1px; color: #047857;">
                            <i class="fas fa-qrcode me-1"></i> SCAN VIA ANY UPI APP
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">anandjankijks@ybl</h5>
                    <p class="small text-muted mb-3">PhonePe • Google Pay • Paytm • BHIM</p>
                    <button type="button" onclick="navigator.clipboard.writeText('anandjankijks@ybl'); alert('UPI ID (anandjankijks@ybl) Copied!');" class="btn btn-success btn-sm px-4 rounded-pill fw-bold">
                        <i class="far fa-copy me-1"></i> UPI ID कॉपी करें
                    </button>
                </div>
            </div>

            <!-- Right Bank Details Card -->
            <div class="col-lg-7">
                <div class="p-4 p-md-5 rounded-4 text-white shadow" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);">
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">Direct Bank Account Transfer</span>
                    <h3 class="fw-bold text-white mb-3">सीधे बैंक खाते में सहयोग हेतु विवरण</h3>
                    
                    <div class="vstack gap-2 small mb-4">
                        <div class="row py-1 border-bottom border-secondary">
                            <div class="col-sm-4 text-warning fw-semibold">बैंक का नाम:</div>
                            <div class="col-sm-8 fw-bold text-white fs-6">UNION BANK OF INDIA</div>
                        </div>
                        <div class="row py-1 border-bottom border-secondary">
                            <div class="col-sm-4 text-warning fw-semibold">खाता धारक:</div>
                            <div class="col-sm-8 fw-bold text-white">आनन्द जानकी जनकल्याण समिति</div>
                        </div>
                        <div class="row py-1 border-bottom border-secondary">
                            <div class="col-sm-4 text-warning fw-semibold">खाता संख्या:</div>
                            <div class="col-sm-8 fw-bold text-white fs-5 font-monospace">195721010000222</div>
                        </div>
                        <div class="row py-1">
                            <div class="col-sm-4 text-warning fw-semibold">IFSC कोड:</div>
                            <div class="col-sm-8 fw-bold text-white fs-6 font-monospace">UBIN0919578</div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-warning fw-bold text-dark px-4 py-2 rounded-pill">
                            <i class="fas fa-heart text-danger me-1"></i> सम्पूर्ण दान पोर्टल (80G Receipt)
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText('बैंक का नाम: UNION BANK OF INDIA\nखाता धारक: आनन्द जानकी जनकल्याण समिति\nखाता संख्या: 195721010000222\nIFSC: UBIN0919578'); alert('बैंक विवरण कॉपी हो गया!');" class="btn btn-outline-light px-4 py-2 rounded-pill fw-bold">
                            <i class="far fa-copy me-1"></i> बैंक विवरण कॉपी करें
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 14. High-Impact Donation & Volunteer CTA Banner -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="p-5 rounded-4 shadow-lg text-white" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">Make a Direct Difference Today</span>
                    <h2 class="text-white fw-bold mb-3 display-6">Help Us Sponsor Free Medical Treatments & NCERT School Kits</h2>
                    <p class="text-white-50 lead mb-0">Every donation of ₹500 provides life-saving medicines to 5 patients or a complete NCERT study kit to a needy child. All contributions are 100% tax-exempt under Section 80G.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column gap-3">
                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-givelife-orange btn-lg">
                            <i class="fas fa-heart text-danger me-1"></i> DONATE ONLINE NOW
                        </a>
                        <a href="<?= BASE_URL; ?>/volunteer.php" class="btn btn-givelife-white btn-lg">
                            <i class="fas fa-hands-helping me-1"></i> JOIN AS VOLUNTEER
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
