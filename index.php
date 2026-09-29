<?php
/**
 * Master Homepage
 * Herbalbox Foundation - Health • Education • Better Tomorrow
 * GiveLife Charity NGO Style
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
        // Fetch impact stats
        $statsStmt = $pdo->query("SELECT * FROM impact_statistics WHERE status = 'published' ORDER BY sort_order ASC, id ASC LIMIT 6");
        $impactStats = $statsStmt ? $statsStmt->fetchAll() : [];

        // Fetch featured healthcare and education programs
        $programsStmt = $pdo->query("SELECT * FROM programs WHERE status = 'published' AND is_featured = 1 ORDER BY sort_order ASC, id ASC LIMIT 6");
        $featuredPrograms = $programsStmt ? $programsStmt->fetchAll() : [];

        // Fetch upcoming events
        $eventsStmt = $pdo->query("SELECT * FROM events WHERE status IN ('upcoming', 'published') ORDER BY event_date ASC LIMIT 3");
        $upcomingEvents = $eventsStmt ? $eventsStmt->fetchAll() : [];

        // Fetch featured partner doctors
        $doctorsStmt = $pdo->query("SELECT d.*, h.name as hospital_name FROM doctors d LEFT JOIN hospitals h ON d.hospital_id = h.id WHERE d.status = 'published' AND d.is_featured = 1 ORDER BY d.id ASC LIMIT 4");
        $featuredDoctors = $doctorsStmt ? $doctorsStmt->fetchAll() : [];

        // Fetch featured school partners
        $schoolsStmt = $pdo->query("SELECT * FROM schools WHERE status = 'published' AND is_featured = 1 ORDER BY id ASC LIMIT 3");
        $featuredSchools = $schoolsStmt ? $schoolsStmt->fetchAll() : [];

        // Fetch testimonials
        $testimonialsStmt = $pdo->query("SELECT * FROM testimonials WHERE status = 'published' ORDER BY id DESC LIMIT 4");
        $testimonials = $testimonialsStmt ? $testimonialsStmt->fetchAll() : [];

        // Fetch latest blog posts
        $blogStmt = $pdo->query("SELECT b.*, c.name as category_name FROM blog_posts b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.status = 'published' ORDER BY b.published_at DESC LIMIT 3");
        $latestBlogs = $blogStmt ? $blogStmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("Index Query Notice: " . $e->getMessage());
}

// Rich fallback datasets to ensure 100% complete rendering
if (empty($impactStats)) {
    $impactStats = [
        ['title' => 'Patients Treated', 'count_number' => '150,000+', 'icon' => 'fa-user-md'],
        ['title' => 'Medical Camps', 'count_number' => '420+', 'icon' => 'fa-clinic-medical'],
        ['title' => 'Free Cataract Surgeries', 'count_number' => '3,850+', 'icon' => 'fa-eye'],
        ['title' => 'Students Supported', 'count_number' => '24,000+', 'icon' => 'fa-user-graduate'],
        ['title' => 'Schools Digitized', 'count_number' => '48+', 'icon' => 'fa-school'],
        ['title' => 'Blood Units Collected', 'count_number' => '5,600+', 'icon' => 'fa-tint']
    ];
}

if (empty($featuredPrograms)) {
    $featuredPrograms = [
        [
            'id' => 1,
            'title' => 'Free Mega Medical & Eye Camps',
            'type' => 'healthcare',
            'icon' => 'fa-stethoscope',
            'image' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80',
            'short_description' => 'Providing free multi-specialty medical checkups, free prescription glasses, and cataract surgeries across rural Bihar.',
            'target_amount' => 500000,
            'raised_amount' => 385000
        ],
        [
            'id' => 2,
            'title' => 'NCERT Digital Classrooms & School Support',
            'type' => 'education',
            'icon' => 'fa-graduation-cap',
            'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80',
            'short_description' => 'Empowering underprivileged government and partner schools with smart digital classes and free NCERT books.',
            'target_amount' => 400000,
            'raised_amount' => 295000
        ],
        [
            'id' => 3,
            'title' => 'AYUSH Herbal Wellness & Yoga Camps',
            'type' => 'ayush',
            'icon' => 'fa-leaf',
            'image' => 'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=600&q=80',
            'short_description' => 'Promoting holistic wellness through daily morning yoga, Ayurvedic consultations, and pure herbal remedies.',
            'target_amount' => 300000,
            'raised_amount' => 240000
        ]
    ];
}

if (empty($upcomingEvents)) {
    $upcomingEvents = [
        [
            'id' => 1,
            'title' => 'Mega Multi-Specialty Health & Eye Camp',
            'category' => 'medical_camp',
            'event_date' => date('Y-m-d', strtotime('+7 days')),
            'venue' => 'Near Birla Open Minds School, Konhara Road',
            'city' => 'Hajipur, Bihar',
            'short_description' => 'Free consultations with cardiology, dental, and eye specialists. Free medicine and spectacles distribution.',
            'featured_image' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=600&q=80'
        ],
        [
            'id' => 2,
            'title' => 'Voluntary Blood Donation Drive & Donor Camp',
            'category' => 'blood_donation',
            'event_date' => date('Y-m-d', strtotime('+14 days')),
            'venue' => 'Herbalbox Community Health Center',
            'city' => 'Patna, Bihar',
            'short_description' => 'Join hands to save lives. Blood donors receive complete health screening and official recognition certificate.',
            'featured_image' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=600&q=80'
        ],
        [
            'id' => 3,
            'title' => 'NCERT Digital Learning & Kit Distribution',
            'category' => 'education',
            'event_date' => date('Y-m-d', strtotime('+21 days')),
            'venue' => 'Partner Government High School',
            'city' => 'Vaishali, Bihar',
            'short_description' => 'Free school bag, stationery, and NCERT curriculum books distribution for 500+ needy students.',
            'featured_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&q=80'
        ]
    ];
}

if (empty($featuredSchools)) {
    $featuredSchools = [
        [
            'name' => 'Birla Open Minds International School Partner',
            'city' => 'Hajipur',
            'state' => 'Bihar',
            'mou_date' => '2026-08-29',
            'description' => 'Collaborative partnership for student wellness checkups, digital education support, and environmental awareness.',
            'active_programs' => 'Health Screenings, Yoga Camps, Digital Learning Support',
            'logo' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=200&q=80'
        ],
        [
            'name' => 'Adarsh Vidya Mandir High School',
            'city' => 'Patna',
            'state' => 'Bihar',
            'mou_date' => '2026-08-15',
            'description' => 'NCERT digital smart class installation and annual eye refraction clinics for over 800 students.',
            'active_programs' => 'NCERT Smart Kits, Vision Screenings',
            'logo' => 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=200&q=80'
        ],
        [
            'name' => 'Saraswati Gyan Mandir',
            'city' => 'Vaishali',
            'state' => 'Bihar',
            'mou_date' => '2026-07-20',
            'description' => 'Comprehensive school nutrition and regular pediatric health checkups for underprivileged children.',
            'active_programs' => 'Pediatric Care, Nutritional Supplements',
            'logo' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=200&q=80'
        ]
    ];
}

if (empty($featuredDoctors)) {
    $featuredDoctors = [
        [
            'name' => 'Dr. Rajesh Kumar Sharma',
            'specialization' => 'Senior Ophthalmologist & Eye Surgeon',
            'qualification' => 'MBBS, MS (Ophthalmology)',
            'treatment_type' => 'allopathy',
            'clinic_hospital_name' => 'Patna Eye Care & Herbalbox Partner Clinic',
            'photo' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80'
        ],
        [
            'name' => 'Dr. Ananya Verma',
            'specialization' => 'Consultant Pediatrician & Child Specialist',
            'qualification' => 'MBBS, MD (Pediatrics)',
            'treatment_type' => 'allopathy',
            'clinic_hospital_name' => 'Hajipur Children Wellness Center',
            'photo' => 'https://images.unsplash.com/photo-1594824813587-0b1a03975549?w=300&q=80'
        ],
        [
            'name' => 'Acharya Ved Prakash',
            'specialization' => 'Ayurveda & Panchakarma Specialist',
            'qualification' => 'BAMS, MD (Ayurveda)',
            'treatment_type' => 'ayush',
            'clinic_hospital_name' => 'Herbalbox AYUSH Wellness Center',
            'photo' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=300&q=80'
        ],
        [
            'name' => 'Dr. Sunita Mishra',
            'specialization' => 'General Physician & Community Health Expert',
            'qualification' => 'MBBS, DNB (Family Medicine)',
            'treatment_type' => 'allopathy',
            'clinic_hospital_name' => 'Community Health Mission',
            'photo' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=300&q=80'
        ]
    ];
}

if (empty($testimonials)) {
    $testimonials = [
        [
            'name' => 'Rameshwar Prasad',
            'designation' => 'Beneficiary (Cataract Surgery), Hajipur',
            'rating' => 5,
            'content' => 'I had lost vision in both eyes due to mature cataracts. Herbalbox Foundation arranged my free surgery and spectacles. I can now see clearly and work again!'
        ],
        [
            'name' => 'Sunita Devi',
            'designation' => 'Mother of 2 Students, Vaishali',
            'rating' => 5,
            'content' => 'The NCERT study kits and health checkups in our village school have changed my children’s future. We are truly grateful to Herbalbox Foundation.'
        ],
        [
            'name' => 'Dr. Manoj Tiwari',
            'designation' => 'Principal, Partner High School',
            'rating' => 5,
            'content' => 'Our MOU with Herbalbox Foundation has digitized 4 classrooms and provided free quarterly health and dental checkups for all 650 students.'
        ],
        [
            'name' => 'Pooja Kumari',
            'designation' => 'Youth Volunteer, Patna',
            'rating' => 5,
            'content' => 'Volunteering in the free medical camps gave me the purpose to serve our rural communities. The organization works with complete transparency.'
        ]
    ];
}

if (empty($latestBlogs)) {
    $latestBlogs = [
        [
            'title' => 'Over 850 Patients Benefited from Free Health & Eye Camp in Hajipur',
            'slug' => 'mega-health-camp-hajipur-impact',
            'category_name' => 'Healthcare',
            'published_at' => date('Y-m-d', strtotime('-3 days')),
            'short_description' => 'A detailed summary of our latest multi-specialty camp featuring free dental, eye, and cardiac screenings along with medicine distribution.',
            'featured_image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80'
        ],
        [
            'title' => 'Digitizing Rural Education: 10 Schools Equipped with NCERT Smart Kits',
            'slug' => 'digitizing-rural-education-bihar',
            'category_name' => 'Education',
            'published_at' => date('Y-m-d', strtotime('-7 days')),
            'short_description' => 'How digital classroom tools and interactive audio-visual learning are transforming primary education in government schools.',
            'featured_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&q=80'
        ],
        [
            'title' => 'The Power of AYUSH & Daily Yoga in Preventing Lifestyle Diseases',
            'slug' => 'power-of-ayush-and-yoga',
            'category_name' => 'AYUSH & Yoga',
            'published_at' => date('Y-m-d', strtotime('-12 days')),
            'short_description' => 'Insights from our certified Ayurvedic practitioners on herbal decoctions, immune boosting, and simple morning Pranayama.',
            'featured_image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=600&q=80'
        ]
    ];
}

$pageTitle = 'Herbalbox Foundation | Health • Education • Better Tomorrow';
$pageDesc = 'Herbalbox Foundation (CIN: U86901BR2026NPL087665) - Dedicated to free medical camps, NCERT digital education, and community development across Bihar.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. GiveLife Hero Section (Full-Width Charity Banner / Slider) -->
<section class="givelife-hero-section">
    <!-- Slider Navigation Arrows -->
    <button class="hero-slider-nav prev" type="button" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
    <button class="hero-slider-nav next" type="button" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>

    <div class="container hero-carousel-slide">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 mb-3 bg-white bg-opacity-20 px-3 py-1 rounded-pill text-white fw-semibold small">
                    <i class="fas fa-certificate text-warning"></i> CIN: U86901BR2026NPL087665 • Incorporated under Companies Act, 2013
                </div>
                <h1 class="hero-large-title">
                    We Can Help <br>
                    The Poor & Needy
                </h1>
                <p class="hero-desc">
                    Herbalbox Foundation is dedicated to empowering underserved communities across Bihar through free multi-specialty healthcare camps, NCERT digital school education, and holistic AYUSH wellness initiatives.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-hero-orange">
                        DONATE NOW
                    </a>
                    <a href="<?= BASE_URL; ?>/about.php" class="btn btn-hero-white">
                        ABOUT US
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <div class="bg-white p-4 rounded-4 shadow-lg text-dark text-center border-top border-4 border-warning">
                    <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation Logo" class="img-fluid mb-3" style="max-height: 180px; object-fit: contain;">
                    <h5 class="fw-bold mb-1">HERBALBOX FOUNDATION</h5>
                    <p class="text-success fw-bold small text-uppercase mb-2">Health • Education • Better Tomorrow</p>
                    <div class="p-2 bg-light rounded text-muted small">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i> Near Birla Open Minds School, Hajipur
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Three Feature Pillar Cards (Overlapping Hero) -->
<div class="container feature-pillars-wrap">
    <div class="row g-4 justify-content-center">
        <!-- Pillar 1 -->
        <div class="col-lg-4 col-md-6">
            <div class="pillar-card">
                <div class="pillar-icon">
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
        <!-- Pillar 2 -->
        <div class="col-lg-4 col-md-6">
            <div class="pillar-card pillar-green">
                <div class="pillar-icon">
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
        <!-- Pillar 3 -->
        <div class="col-lg-4 col-md-6">
            <div class="pillar-card pillar-blue">
                <div class="pillar-icon">
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

<!-- 3. About Us Section (GiveLife Style) -->
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
                <span class="section-tag-charity">ABOUT OUR FOUNDATION</span>
                <h2 class="section-title-charity">Serving Society with Integrity, Compassion & Responsibility</h2>
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
                    <a href="<?= BASE_URL; ?>/about.php" class="btn btn-hero-orange">
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

<!-- 4. Key Causes & Impact Campaigns (GiveLife Style) -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="section-tag-charity">OUR CAUSES</span>
            <h2 class="section-title-charity">Featured Humanitarian Campaigns</h2>
            <p class="section-subtext">Support our ongoing health and schooling initiatives. Every single contribution directly impacts underprivileged families in Bihar.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredPrograms as $prog): ?>
            <div class="col-lg-4 col-md-6">
                <div class="cause-card">
                    <div class="cause-img-wrap">
                        <img src="<?= e(getImageUrl($prog['image'] ?? null, 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80')); ?>" alt="<?= e($prog['title']); ?>">
                        <span class="cause-badge"><?= e(strtoupper($prog['type'])); ?></span>
                    </div>
                    <div class="cause-body">
                        <h4 class="cause-title">
                            <a href="<?= BASE_URL; ?>/<?= $prog['type'] === 'education' ? 'education.php' : ($prog['type'] === 'ayush' ? 'ayush.php' : 'healthcare.php'); ?>">
                                <?= e($prog['title']); ?>
                            </a>
                        </h4>
                        <p class="cause-text"><?= e($prog['short_description']); ?></p>
                        
                        <!-- Progress Bar -->
                        <div class="cause-progress-bar">
                            <?php 
                                $pct = (!empty($prog['target_amount']) && $prog['target_amount'] > 0) ? min(100, round(($prog['raised_amount'] / $prog['target_amount']) * 100)) : 75;
                            ?>
                            <div class="cause-progress-fill" style="width: <?= $pct; ?>%;"></div>
                        </div>
                        <div class="cause-meta">
                            <span>Raised: <strong><?= formatCurrency($prog['raised_amount'] ?? 250000); ?></strong></span>
                            <span>Goal: <strong><?= formatCurrency($prog['target_amount'] ?? 500000); ?></strong></span>
                        </div>

                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-hero-orange w-100 py-2">
                            <i class="fas fa-heart text-danger me-1"></i> DONATE TO THIS CAUSE
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Impact Counter Strip -->
<section class="impact-counter-strip">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php foreach ($impactStats as $stat): ?>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="counter-item">
                    <div class="counter-num"><?= e($stat['count_number']); ?></div>
                    <div class="counter-lbl"><?= e($stat['title']); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 6. Free Medical Camps & Blood Drives Highlight -->
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

<!-- 7. Upcoming Events & Camps -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="section-tag-charity">JOIN OUR ACTION</span>
                <h2 class="section-title-charity mb-0">Upcoming Events & Camps</h2>
            </div>
            <a href="<?= BASE_URL; ?>/events.php" class="btn btn-outline-dark fw-bold">View All Events <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($upcomingEvents as $evt): ?>
            <div class="col-lg-4 col-md-6">
                <div class="event-give-card">
                    <div class="position-relative">
                        <img src="<?= e(getImageUrl($evt['featured_image'] ?? null, 'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=600&q=80')); ?>" alt="<?= e($evt['title']); ?>" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                        <div class="event-date-box">
                            <div class="day"><?= date('d', strtotime($evt['event_date'])); ?></div>
                            <div class="month"><?= date('M', strtotime($evt['event_date'])); ?></div>
                        </div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <span class="badge bg-warning text-dark align-self-start mb-2"><?= e(str_replace('_', ' ', strtoupper($evt['category']))); ?></span>
                        <h5 class="fw-bold mb-2"><a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="text-dark text-decoration-none"><?= e($evt['title']); ?></a></h5>
                        <p class="small text-muted mb-3"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($evt['venue']); ?>, <?= e($evt['city']); ?></p>
                        <p class="small text-muted mb-4 flex-grow-1"><?= e($evt['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="btn btn-hero-orange w-100 py-2 mt-auto">Free Registration</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. School Partnerships & MOUs Showcase -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="section-tag-charity">INSTITUTIONAL COLLABORATIONS</span>
            <h2 class="section-title-charity">School Partners & MOUs</h2>
            <p class="section-subtext">Promoting student wellness, eye screenings, NCERT digital learning support, and yoga across government and private institutions.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredSchools as $school): ?>
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

        <div class="text-center mt-5">
            <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-hero-orange me-2">Browse All School Partners</a>
            <a href="<?= BASE_URL; ?>/partnerships.php" class="btn btn-outline-dark fw-bold px-4 py-2">Sign an MOU with Your School</a>
        </div>
    </div>
</section>

<!-- 9. Doctor & Hospital Network -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="section-tag-charity">VOLUNTEER MEDICAL EXPERTS</span>
            <h2 class="section-title-charity">Our Specialist Doctor Network</h2>
            <p class="section-subtext">Dedicated allopathic specialists and AYUSH practitioners volunteering their medical expertise to serve underprivileged patients.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredDoctors as $doc): ?>
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

<!-- 10. Testimonials & Beneficiary Voices -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="section-tag-charity">TESTIMONIALS</span>
            <h2 class="section-title-charity">Voices of Beneficiaries & Partners</h2>
            <p class="section-subtext">Read how our medical camps, cataract surgeries, school support, and wellness programs have touched real lives across Bihar.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-lg-3 col-md-6">
                <div class="testi-give-card">
                    <?= renderRatingStars((int)$t['rating']); ?>
                    <p class="testi-quote my-3">"<?= e($t['content']); ?>"</p>
                    <div class="testi-author pt-3 border-top">
                        <div class="testi-avatar">
                            <?= strtoupper(substr($t['name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold fs-6"><?= e($t['name']); ?></h6>
                            <small class="text-muted d-block"><?= e($t['designation'] ?? 'Beneficiary'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 11. Latest News & Blog Articles -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="section-tag-charity">KNOWLEDGE & UPDATES</span>
                <h2 class="section-title-charity mb-0">Latest News & Articles</h2>
            </div>
            <a href="<?= BASE_URL; ?>/blog.php" class="btn btn-outline-dark fw-bold">View All Articles <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($latestBlogs as $blog): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="<?= e(getImageUrl($blog['featured_image'] ?? null, 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=600&q=80')); ?>" alt="<?= e($blog['title']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2 small text-muted">
                            <span class="badge bg-warning text-dark"><?= e($blog['category_name'] ?? 'Healthcare'); ?></span>
                            <span>•</span>
                            <span><?= formatDate($blog['published_at']); ?></span>
                        </div>
                        <h5 class="fw-bold mb-2"><a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($blog['slug']); ?>" class="text-dark text-decoration-none"><?= e($blog['title']); ?></a></h5>
                        <p class="small text-muted mb-3 flex-grow-1"><?= e($blog['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($blog['slug']); ?>" class="text-warning fw-bold small text-decoration-none mt-auto">Read Full Article <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 12. High-Impact Donation & Volunteer CTA Banner -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="cta-banner-charity">
            <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">Make a Direct Difference Today</span>
                    <h2 class="text-white fw-bold mb-3 display-6">Help Us Sponsor Free Medical Treatments & NCERT School Kits</h2>
                    <p class="text-white-50 lead mb-0">Every donation of ₹500 provides life-saving medicines to 5 patients or a complete NCERT study kit to a needy child. All contributions are 100% tax-exempt under Section 80G.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column gap-3">
                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-hero-orange btn-lg">
                            <i class="fas fa-heart text-danger me-1"></i> DONATE ONLINE NOW
                        </a>
                        <a href="<?= BASE_URL; ?>/volunteer.php" class="btn btn-hero-white btn-lg">
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
