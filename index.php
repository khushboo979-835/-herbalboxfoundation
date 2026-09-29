<?php
/**
 * Master Homepage
 * Seva Arogya & Shiksha Foundation
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

// Fallback defaults for impact stats if database is initializing
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

$pageTitle = 'Herbalbox Foundation | Health • Education • Better Tomorrow';
$pageDesc = 'Herbalbox Foundation (CIN: U86901BR2026NPL087665) - Dedicated to free medical camps, NCERT digital education, and community development across Bihar.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. Hero Section -->
<section class="hero-slider-section position-relative overflow-hidden">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 hero-content">
                <div class="d-inline-flex align-items-center gap-2 mb-3 bg-white bg-opacity-10 px-3 py-2 rounded-pill border border-white border-opacity-20 shadow-sm backdrop-blur">
                    <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-shield-alt me-1"></i> CIN: U86901BR2026NPL087665</span>
                    <span class="text-white small fw-semibold">Incorporated under Companies Act, 2013</span>
                </div>
                <h1 class="hero-title fw-bold text-white mb-3">
                    Herbalbox Foundation <br>
                    <span class="text-gradient-amber">Working Together for a Better Tomorrow</span>
                </h1>
                <p class="hero-subtitle text-white-50 lead mb-4">
                    Committed to creating meaningful social impact through community welfare, free multi-specialty medical camps, NCERT digital education, and sustainable empowerment across Bihar & Eastern India.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-warning btn-lg fw-bold text-dark px-4 shadow-sm hover-lift">
                        <i class="fas fa-heart text-danger me-1"></i> Donate (80G Tax-Exempt)
                    </a>
                    <a href="<?= BASE_URL; ?>/volunteer.php" class="btn btn-success btn-lg fw-bold px-4 shadow-sm hover-lift">
                        <i class="fas fa-user-plus me-1"></i> Join as Volunteer
                    </a>
                    <a href="tel:9234055507" class="btn btn-outline-light btn-lg px-4 hover-lift">
                        <i class="fas fa-phone-alt me-1 text-warning"></i> +91 92340 55507
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="hero-card-img p-4 bg-white bg-opacity-95 rounded-4 shadow-lg border border-2 border-white hover-lift animate-float" style="backdrop-filter: blur(10px);">
                    <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="img-fluid rounded-4 mb-3" style="max-height: 280px; width: auto; object-fit: contain;">
                    <h5 class="fw-bold text-dark mb-1">HERBALBOX FOUNDATION</h5>
                    <p class="text-success fw-bold text-uppercase small mb-2 tracking-wider">Health • Education • Better Tomorrow</p>
                    <div class="d-flex justify-content-center gap-2 small text-muted">
                        <span><i class="fas fa-map-marker-alt text-primary me-1"></i> Hajipur & Patna, Bihar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Dynamic Impact Statistics Section -->
<section class="impact-section">
    <div class="container">
        <div class="row g-3 justify-content-center">
            <?php foreach ($impactStats as $stat): ?>
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                <div class="impact-card">
                    <div class="icon-box">
                        <i class="fas <?= e($stat['icon'] ?? 'fa-heartbeat'); ?>"></i>
                    </div>
                    <div class="counter-value" data-target="<?= e(preg_replace('/[^0-9]/', '', $stat['count_number'])); ?>">
                        <?= e($stat['count_number']); ?>
                    </div>
                    <div class="counter-title"><?= e($stat['title']); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 3. NGO Introduction & Mission/Vision Section -->
<section class="py-5 my-4">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-tag">About Our Foundation</span>
                <h2 class="section-title mb-3">Empowering Underserved Lives with Compassion and Integrity</h2>
                <p class="lead text-primary fw-semibold mb-3">
                    <?= e(getSetting('about_hero_headline', 'Dedicated to Uplifting Lives Through Accessible Healthcare & Holistic Education')); ?>
                </p>
                <p class="text-muted mb-4">
                    <?= e(getSetting('about_history', 'Established in 2018 by a consortium of philanthropic doctors and educationists, Seva Foundation runs 150+ free healthcare camps annually, partners with 45+ premier schools, and empowers thousands with life-saving medical treatments.')); ?>
                </p>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-primary">
                            <h5 class="fs-6 fw-bold mb-1"><i class="fas fa-bullseye text-primary me-2"></i> Our Mission</h5>
                            <p class="small text-muted mb-0"><?= e(truncateText(getSetting('about_mission', 'To provide free quality healthcare and NCERT education to the needy.'), 120)); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-success">
                            <h5 class="fs-6 fw-bold mb-1"><i class="fas fa-eye text-success me-2"></i> Our Vision</h5>
                            <p class="small text-muted mb-0"><?= e(truncateText(getSetting('about_vision', 'A compassionate India where no child is deprived of schooling and health.'), 120)); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <a href="<?= BASE_URL; ?>/about.php" class="btn btn-ngo-primary">
                        Read Full Story <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                    <a href="<?= BASE_URL; ?>/mou.php" class="btn btn-ngo-outline">
                        View Accreditations & MOUs
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800&q=80" alt="Children and Community Education" class="img-fluid rounded-4 shadow-lg">
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
        </div>
    </div>
</section>

<!-- 4. Key Healthcare & Education Programs Showcase -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">What We Do</span>
            <h2 class="section-title">Comprehensive Impact Programs</h2>
            <p class="section-subtitle">Delivering high-impact humanitarian initiatives in primary healthcare, NCERT-based schooling, AYUSH healing, and emergency relief.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredPrograms as $prog): ?>
            <div class="col-lg-4 col-md-6">
                <div class="program-card">
                    <div class="card-thumb">
                        <img src="<?= e(getImageUrl($prog['image'], 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80')); ?>" alt="<?= e($prog['title']); ?>">
                        <span class="badge-cat"><?= e(strtoupper($prog['type'])); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="program-icon">
                            <i class="fas <?= e($prog['icon'] ?? 'fa-heartbeat'); ?>"></i>
                        </div>
                        <h4 class="card-title"><?= e($prog['title']); ?></h4>
                        <p class="card-desc"><?= e($prog['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/<?= $prog['type'] === 'education' ? 'education.php' : ($prog['type'] === 'ayush' ? 'ayush.php' : 'healthcare.php'); ?>" class="text-primary fw-bold text-decoration-none mt-auto">
                            Learn More <i class="fas fa-chevron-right ms-1 small"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?= BASE_URL; ?>/healthcare.php" class="btn btn-ngo-primary me-2">Explore All Healthcare Programs</a>
            <a href="<?= BASE_URL; ?>/education.php" class="btn btn-ngo-outline">Explore School Initiatives</a>
        </div>
    </div>
</section>

<!-- 5. Free Medical Camps & Blood Donation Highlight -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border border-primary border-opacity-25 h-100">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle p-3 mb-3" style="width: 55px; height: 55px;">
                        <i class="fas fa-tint fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Life-Saving Blood Donation Drives</h3>
                    <p class="text-muted mb-4">We organize certified blood donation camps in partnership with the Indian Red Cross Society and leading government hospital blood banks. Over 12,500 units collected to date.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Free complete blood screening report for all donors</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> 24/7 Emergency Blood Donor Helpdesk</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Recognition certificates and donor appreciation kit</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/blood-donation.php" class="btn btn-danger"><i class="fas fa-heart me-1"></i> Register as Blood Donor</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border border-success border-opacity-25 h-100">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-3 mb-3" style="width: 55px; height: 55px;">
                        <i class="fas fa-clinic-medical fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Free Multi-Specialty Medical Camps</h3>
                    <p class="text-muted mb-4">Bringing certified physicians, optometrists, dentists, and free essential medicines directly into rural villages and underserved urban settlements across the country.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> General consultation, ECG & Blood Sugar tests</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Free prescription reading glasses distribution</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Sponsored cataract surgeries at partner hospitals</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/medical-camps.php" class="btn btn-success"><i class="fas fa-calendar-check me-1"></i> View Upcoming Camps</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. School Partnerships & MOU Showcase -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">Institutional Collaborations</span>
            <h2 class="section-title">Our School Partners & MOUs</h2>
            <p class="section-subtitle">Promoting student wellness, eye screenings, NCERT learning support, and yoga across government and private institutions.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredSchools as $school): ?>
            <div class="col-lg-4 col-md-6">
                <div class="partner-card">
                    <div class="school-logo-box">
                        <img src="<?= e(getImageUrl($school['logo'], 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=200&q=80')); ?>" alt="<?= e($school['name']); ?>">
                    </div>
                    <h5 class="fw-bold mb-2"><?= e($school['name']); ?></h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-1"></i> <?= e($school['city']); ?>, <?= e($school['state']); ?></p>
                    <div class="badge bg-primary-subtle text-primary mb-3">MOU Signed: <?= formatDate($school['mou_date']); ?></div>
                    <p class="small text-muted mb-3"><?= e(truncateText($school['description'], 110)); ?></p>
                    <div class="p-2 bg-light rounded text-start small mb-3">
                        <strong>Active Programs:</strong><br>
                        <span class="text-muted"><?= e(truncateText($school['active_programs'], 80)); ?></span>
                    </div>
                    <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-sm btn-outline-primary w-100">View School Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-ngo-primary me-2">Browse All 65+ School Partners</a>
            <a href="<?= BASE_URL; ?>/partnerships.php" class="btn btn-ngo-outline">Sign an MOU with Your School</a>
        </div>
    </div>
</section>

<!-- 7. Doctor & Hospital Network -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">Medical Expertise</span>
            <h2 class="section-title">Distinguished Doctor Network</h2>
            <p class="section-subtitle">Dedicated allopathic specialists and AYUSH practitioners volunteering their expertise to serve the community.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredDoctors as $doc): ?>
            <div class="col-lg-3 col-md-6">
                <div class="partner-card">
                    <img src="<?= e(getImageUrl($doc['photo'], 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80')); ?>" alt="<?= e($doc['name']); ?>" class="doctor-photo">
                    <h5 class="fw-bold mb-1 fs-6"><?= e($doc['name']); ?></h5>
                    <p class="small text-primary fw-semibold mb-1"><?= e($doc['specialization']); ?></p>
                    <p class="small text-muted mb-2"><?= e($doc['qualification']); ?></p>
                    <div class="badge bg-secondary-subtle text-secondary mb-3"><?= e(ucfirst($doc['treatment_type'])); ?> Care</div>
                    <p class="small text-muted mb-3"><i class="fas fa-hospital me-1"></i> <?= e($doc['clinic_hospital_name'] ?? $doc['hospital_name'] ?? 'Partner Clinic'); ?></p>
                    <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-sm btn-ngo-outline w-100">Consultation Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. Upcoming Events & Camps -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="section-tag">Join Our Action</span>
                <h2 class="section-title mb-0">Upcoming Events & Camps</h2>
            </div>
            <a href="<?= BASE_URL; ?>/events.php" class="btn btn-ngo-outline">View All Events <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($upcomingEvents as $evt): ?>
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="position-relative">
                        <img src="<?= e(getImageUrl($evt['featured_image'], 'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=600&q=80')); ?>" alt="<?= e($evt['title']); ?>" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                        <div class="event-date-badge">
                            <div class="day"><?= date('d', strtotime($evt['event_date'])); ?></div>
                            <div class="month"><?= date('M', strtotime($evt['event_date'])); ?></div>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-primary-subtle text-primary align-self-start mb-2"><?= e(str_replace('_', ' ', strtoupper($evt['category']))); ?></span>
                        <h5 class="fw-bold mb-2"><a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="text-dark text-decoration-none"><?= e($evt['title']); ?></a></h5>
                        <p class="small text-muted mb-3"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($evt['venue']); ?>, <?= e($evt['city']); ?></p>
                        <p class="small text-muted mb-4 flex-grow-1"><?= e($evt['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="btn btn-sm btn-ngo-primary w-100 mt-auto">Free Registration</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 9. Testimonials & Community Voices -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">Impact Stories</span>
            <h2 class="section-title">Voices of Beneficiaries & Partners</h2>
            <p class="section-subtitle">Read how our medical camps, cataract surgeries, school support, and wellness programs have touched real lives.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-lg-3 col-md-6">
                <div class="testimonial-card">
                    <?= renderRatingStars((int)$t['rating']); ?>
                    <p class="small text-muted my-3">"<?= e($t['content']); ?>"</p>
                    <div class="d-flex align-items-center gap-3 pt-2 border-top">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
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

<!-- 10. Latest News & Blog Articles -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="section-tag">Knowledge & Updates</span>
                <h2 class="section-title mb-0">Latest Articles & Health Insights</h2>
            </div>
            <a href="<?= BASE_URL; ?>/blog.php" class="btn btn-ngo-outline">View All Articles <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($latestBlogs as $blog): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="<?= e(getImageUrl($blog['featured_image'], 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=600&q=80')); ?>" alt="<?= e($blog['title']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2 small text-muted">
                            <span class="badge bg-primary-subtle text-primary"><?= e($blog['category_name'] ?? 'Healthcare'); ?></span>
                            <span>•</span>
                            <span><?= formatDate($blog['published_at']); ?></span>
                        </div>
                        <h5 class="fw-bold mb-2"><a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($blog['slug']); ?>" class="text-dark text-decoration-none"><?= e($blog['title']); ?></a></h5>
                        <p class="small text-muted mb-3 flex-grow-1"><?= e($blog['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($blog['slug']); ?>" class="text-primary fw-bold small text-decoration-none mt-auto">Read Full Article <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 11. High-Impact Donation & Volunteer CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="cta-banner">
            <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">Make a Direct Difference Today</span>
                    <h2 class="text-white fw-bold mb-3 display-6">Help Us Sponsor Free Medical Treatments & NCERT School Kits</h2>
                    <p class="text-white-50 lead mb-0">Every donation of ₹500 provides life-saving medicines to 5 patients or a complete NCERT study kit to a needy child. All contributions are 100% tax-exempt under Section 80G.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column gap-3">
                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-warning btn-lg fw-bold text-dark shadow">
                            <i class="fas fa-heart me-1 text-danger"></i> Donate Online Now
                        </a>
                        <a href="<?= BASE_URL; ?>/volunteer.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-hands-helping me-1"></i> Join Our Volunteer Team
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
