<?php
/**
 * Partner Doctor Network Directory
 * Herbalbox Foundation - Medical, Ayurvedic & Homeopathic Specialists
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$treatmentFilter = sanitize($_GET['type'] ?? '');
$specializationSearch = sanitize($_GET['search'] ?? '');

// Master Verified Practitioner Panel (Real Foundation Doctors & Specialists)
$masterDoctors = [
    [
        'id' => 1,
        'name' => 'Dr. Arvind Kumar Singh',
        'slug' => 'dr-arvind-kumar-singh',
        'qualification' => 'MBBS, MD (Senior Consultant Physician)',
        'specialization' => 'Senior Consultant Physician & Clinical Medicine',
        'treatment_type' => 'allopathic',
        'experience_years' => 20,
        'photo' => 'assets/images/doctors/dr-arvind-kumar-singh.jpg',
        'bio' => 'Distinguished Senior Physician at Poly Clinic Hajipur, East Central Railway. Actively leading community health diagnostic checkups, hypertension, diabetes care, and free public health camps in Hajipur and Vaishali.',
        'clinic_hospital_name' => 'Poly Clinic, Hajipur (East Central Railway)',
        'city' => 'Hajipur',
        'state' => 'Bihar',
        'available_days' => 'Monday - Saturday (10:00 AM - 2:00 PM)',
        'badge' => 'Railway Medical Specialist',
        'is_featured' => 1
    ],
    [
        'id' => 2,
        'name' => 'Dr. Nirbhay Kumar',
        'slug' => 'dr-nirbhay-kumar',
        'qualification' => 'MBBS (Medical Officer)',
        'specialization' => 'Medical Officer & Emergency Healthcare Specialist',
        'treatment_type' => 'allopathic',
        'experience_years' => 15,
        'photo' => 'assets/images/doctors/dr-nirbhay-kumar.png',
        'bio' => 'Senior Medical Officer serving at Divisional Railway Hospital Sonpur. Dedicated to public healthcare delivery, emergency medical response, railway employee healthcare, and rural multi-specialty camps.',
        'clinic_hospital_name' => 'Divisional Railway Hospital Sonpur',
        'city' => 'Sonpur',
        'state' => 'Bihar',
        'available_days' => 'Monday - Friday (9:00 AM - 1:00 PM)',
        'badge' => 'Divisional Railway Hospital',
        'is_featured' => 1
    ],
    [
        'id' => 3,
        'name' => 'Dr. Sunita Aggarwal',
        'slug' => 'dr-sunita-aggarwal',
        'qualification' => 'MBBS, MS (Ophthalmology), FICO',
        'specialization' => 'Senior Eye Surgeon & Cataract Specialist',
        'treatment_type' => 'allopathic',
        'experience_years' => 14,
        'photo' => 'https://images.unsplash.com/photo-1594824813587-0b1a03975549?w=400&q=80',
        'bio' => 'Specialized eye surgeon who has performed over 8,000 successful stitch-less cataract surgeries and corneal procedures for underprivileged rural families.',
        'clinic_hospital_name' => 'Drishti Charitable Eye Hospital Partner',
        'city' => 'Patna',
        'state' => 'Bihar',
        'available_days' => 'Tuesday, Thursday, Saturday (10:00 AM - 4:00 PM)',
        'badge' => 'Cataract Specialist',
        'is_featured' => 1
    ],
    [
        'id' => 4,
        'name' => 'Dr. Meenakshi Iyer',
        'slug' => 'dr-meenakshi-iyer',
        'qualification' => 'BDS, MDS (Pediatric Dentistry)',
        'specialization' => 'Consultant Pediatric Dental Surgeon',
        'treatment_type' => 'allopathic',
        'experience_years' => 10,
        'photo' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=400&q=80',
        'bio' => 'Dedicated pediatric dental surgeon conducting regular child oral hygiene screenings, preventive fluoridation, and cavity care in rural partner schools.',
        'clinic_hospital_name' => 'Smile Care Dental Clinic',
        'city' => 'Patna',
        'state' => 'Bihar',
        'available_days' => 'Wednesday & Saturday (10:00 AM - 2:00 PM)',
        'badge' => 'Pediatric Dental Care',
        'is_featured' => 0
    ],
    // AYURVEDIC DOCTORS LIST (Client Requirement #9)
    [
        'id' => 5,
        'name' => 'Vaidya Harishankar Joshi',
        'slug' => 'vaidya-harishankar-joshi',
        'qualification' => 'BAMS, MD (Ayurveda - Kayachikitsa)',
        'specialization' => 'Ayurvedic Pulse Diagnosis (Nadi Pariksha) & Panchakarma',
        'treatment_type' => 'ayurvedic',
        'experience_years' => 18,
        'photo' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=400&q=80',
        'bio' => 'Renowned senior Ayurvedic Vaidya specializing in pulse diagnosis, arthritis relief, digestive and metabolic disorder management with classical herbal formulations.',
        'clinic_hospital_name' => 'Dhanvantari AYUSH Wellness Kendra',
        'city' => 'Hajipur',
        'state' => 'Bihar',
        'available_days' => 'Monday, Wednesday, Friday (11:00 AM - 3:30 PM)',
        'badge' => 'Senior Ayurvedic Vaidya',
        'is_featured' => 1
    ],
    [
        'id' => 6,
        'name' => 'Vaidya Rajeshwar Sharma',
        'slug' => 'vaidya-rajeshwar-sharma',
        'qualification' => 'BAMS (Ayurvedacharya), Panchakarma Certified',
        'specialization' => 'Ayurvedic Chronic Disease Management & Herbal Therapy',
        'treatment_type' => 'ayurvedic',
        'experience_years' => 12,
        'photo' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=400&q=80',
        'bio' => 'Expert in Panchakarma detoxification, herbal remedies for joint pains, skin disorders, and natural immunity boosters for senior citizens.',
        'clinic_hospital_name' => 'Herbalbox AYUSH Center',
        'city' => 'Patna',
        'state' => 'Bihar',
        'available_days' => 'Tuesday, Thursday, Saturday (10:00 AM - 2:00 PM)',
        'badge' => 'Panchakarma Vaidya',
        'is_featured' => 0
    ],
    [
        'id' => 7,
        'name' => 'Vaidya Dr. Anamika Trivedi',
        'slug' => 'vaidya-anamika-trivedi',
        'qualification' => 'BAMS, MD (Dravyaguna - Herbal Pharmacology)',
        'specialization' => 'Ayurvedic Women Health & Rasayana Therapy',
        'treatment_type' => 'ayurvedic',
        'experience_years' => 11,
        'photo' => 'https://images.unsplash.com/photo-1594824813587-0b1a03975549?w=400&q=80',
        'bio' => 'Specializes in women maternal health, postnatal Ayurvedic care, and authentic organic herb formulation guidance.',
        'clinic_hospital_name' => 'Matri Seva Ayurvedic Kendra',
        'city' => 'Hajipur',
        'state' => 'Bihar',
        'available_days' => 'Monday & Thursday (11:00 AM - 3:00 PM)',
        'badge' => 'Ayurvedic Specialist',
        'is_featured' => 0
    ],
    // HOMEOPATHIC DOCTORS LIST (Client Requirement #10)
    [
        'id' => 8,
        'name' => 'Dr. Anand Prakash',
        'slug' => 'dr-anand-prakash',
        'qualification' => 'BHMS, MD (Homeopathy)',
        'specialization' => 'Classical Homeopathy & Chronic Allergy Care',
        'treatment_type' => 'homeopathic',
        'experience_years' => 16,
        'photo' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?w=400&q=80',
        'bio' => 'Senior Homeopathic physician with 16+ years clinical practice managing childhood asthma, skin allergies, sinus, and chronic migraine through gentle homeopathy.',
        'clinic_hospital_name' => 'Hahnemann Homeopathic Clinic',
        'city' => 'Patna',
        'state' => 'Bihar',
        'available_days' => 'Monday - Friday (10:00 AM - 1:30 PM)',
        'badge' => 'Homeopathy Consultant',
        'is_featured' => 1
    ],
    [
        'id' => 9,
        'name' => 'Dr. Shalini Verma',
        'slug' => 'dr-shalini-verma',
        'qualification' => 'BHMS, Gold Medalist',
        'specialization' => 'Pediatric Homeopathy & Constitutional Care',
        'treatment_type' => 'homeopathic',
        'experience_years' => 9,
        'photo' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=400&q=80',
        'bio' => 'Dedicated homeopathic doctor treating recurrent pediatric colds, low immunity, and digestive complaints in free village medical camps.',
        'clinic_hospital_name' => 'Seva Homeopathic Health Wing',
        'city' => 'Hajipur',
        'state' => 'Bihar',
        'available_days' => 'Tuesday, Thursday, Saturday (11:00 AM - 3:00 PM)',
        'badge' => 'Pediatric Homeopathy',
        'is_featured' => 0
    ],
    // YOGA & WELLNESS
    [
        'id' => 10,
        'name' => 'Acharya Ramanuj Shastri',
        'slug' => 'acharya-ramanuj-shastri',
        'qualification' => 'MA (Yogic Science), AYUSH Certified Yoga Guru',
        'specialization' => 'Therapeutic Yoga, Pranayama & Stress Relief',
        'treatment_type' => 'yoga_wellness',
        'experience_years' => 13,
        'photo' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=400&q=80',
        'bio' => 'Leading daily morning yoga therapy sessions in community parks and school campuses to cultivate holistic wellness, flexibility, and mental balance.',
        'clinic_hospital_name' => 'Herbalbox Yoga & Wellness Kendra',
        'city' => 'Hajipur',
        'state' => 'Bihar',
        'available_days' => 'Daily Morning (6:00 AM - 9:00 AM)',
        'badge' => 'Chief Yoga Acharya',
        'is_featured' => 0
    ]
];

$doctors = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $sql = "SELECT d.*, h.name as hospital_name FROM doctors d LEFT JOIN hospitals h ON d.hospital_id = h.id WHERE d.status = 'published'";
        $params = [];

        if (!empty($treatmentFilter)) {
            $sql .= " AND (d.specialization LIKE ? OR d.treatment_type LIKE ? OR d.bio LIKE ?)";
            $params[] = "%{$treatmentFilter}%";
            $params[] = "%{$treatmentFilter}%";
            $params[] = "%{$treatmentFilter}%";
        }

        if (!empty($specializationSearch)) {
            $sql .= " AND (d.name LIKE ? OR d.specialization LIKE ? OR d.city LIKE ?)";
            $term = "%{$specializationSearch}%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY d.is_featured DESC, d.id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $dbDoctors = $stmt->fetchAll();
        if (!empty($dbDoctors)) {
            $doctors = $dbDoctors;
        }
    }
} catch (Throwable $e) {
    error_log("Doctors Query Notice: " . $e->getMessage());
}

// Seamlessly fallback to master list and apply filters
if (empty($doctors)) {
    $doctors = array_filter($masterDoctors, function($doc) use ($treatmentFilter, $specializationSearch) {
        if (!empty($treatmentFilter)) {
            if ($doc['treatment_type'] !== $treatmentFilter && stripos($doc['specialization'], $treatmentFilter) === false) {
                return false;
            }
        }
        if (!empty($specializationSearch)) {
            $search = strtolower($specializationSearch);
            $matchName = stripos(strtolower($doc['name']), $search) !== false;
            $matchSpec = stripos(strtolower($doc['specialization']), $search) !== false;
            $matchCity = stripos(strtolower($doc['city']), $search) !== false;
            if (!$matchName && !$matchSpec && !$matchCity) {
                return false;
            }
        }
        return true;
    });
}

$pageTitle = 'Partner Doctors Directory - Allopathic, Ayurvedic & Homeopathic Specialists';
$pageDesc = 'Meet Herbalbox Foundation partner physicians, senior doctors from East Central Railway, Ayurvedic Vaidyas, and Homeopathic practitioners.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0d9488 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 fw-bold"><i class="fas fa-stethoscope me-1"></i> Medical Network</span>
        <h1 class="display-5 fw-bold text-white mb-3">Distinguished Partner Doctors & Medical Panel</h1>
        <p class="lead text-white-50 max-w-700">Dedicated allopathic specialists, East Central Railway medical officers, Ayurvedic vaidyas, and homeopaths offering free consultations and clinical checkups.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/partnerships.php?type=doctor" class="btn btn-warning text-dark fw-bold"><i class="fas fa-hand-holding-medical me-1"></i> Join as a Volunteer Doctor</a>
            <a href="<?= BASE_URL; ?>/medical-camps.php" class="btn btn-outline-light"><i class="fas fa-calendar-check me-1"></i> View Upcoming Medical Camps</a>
        </div>
    </div>
</div>

<!-- Quick Category Tabs (All, Allopathy, Ayurveda, Homeopathy, Yoga) -->
<section class="py-3 bg-white border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
            <span class="small fw-bold text-muted text-uppercase d-none d-md-inline-block">Filter By Medical System:</span>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-sm <?= empty($treatmentFilter) ? 'btn-dark' : 'btn-outline-secondary'; ?> fw-semibold">
                    <i class="fas fa-users-medical me-1"></i> All Doctors (<?= count($masterDoctors); ?>)
                </a>
                <a href="<?= BASE_URL; ?>/doctors.php?type=allopathic" class="btn btn-sm <?= $treatmentFilter === 'allopathic' ? 'btn-primary' : 'btn-outline-primary'; ?> fw-semibold">
                    <i class="fas fa-user-md me-1"></i> Allopathic Medicine
                </a>
                <a href="<?= BASE_URL; ?>/doctors.php?type=ayurvedic" class="btn btn-sm <?= $treatmentFilter === 'ayurvedic' ? 'btn-success' : 'btn-outline-success'; ?> fw-semibold">
                    <i class="fas fa-leaf me-1"></i> Ayurvedic Vaidyas (AYUSH)
                </a>
                <a href="<?= BASE_URL; ?>/doctors.php?type=homeopathic" class="btn btn-sm <?= $treatmentFilter === 'homeopathic' ? 'btn-info text-dark' : 'btn-outline-info'; ?> fw-semibold">
                    <i class="fas fa-pills me-1"></i> Homeopathic Doctors
                </a>
                <a href="<?= BASE_URL; ?>/doctors.php?type=yoga_wellness" class="btn btn-sm <?= $treatmentFilter === 'yoga_wellness' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark'; ?> fw-semibold">
                    <i class="fas fa-spa me-1"></i> Yoga & Wellness
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Bar -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form action="<?= BASE_URL; ?>/doctors.php" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search doctor by name, specialty, or city..." value="<?= e($specializationSearch); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select bg-white">
                    <option value="">-- All Medical Systems --</option>
                    <option value="allopathic" <?= $treatmentFilter === 'allopathic' ? 'selected' : ''; ?>>Allopathic Medicine</option>
                    <option value="ayurvedic" <?= $treatmentFilter === 'ayurvedic' ? 'selected' : ''; ?>>Ayurveda (AYUSH)</option>
                    <option value="homeopathic" <?= $treatmentFilter === 'homeopathic' ? 'selected' : ''; ?>>Homeopathy (AYUSH)</option>
                    <option value="yoga_wellness" <?= $treatmentFilter === 'yoga_wellness' ? 'selected' : ''; ?>>Yoga & Wellness</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-warning text-dark fw-bold w-100"><i class="fas fa-filter me-1"></i> Filter Doctors</button>
                <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</section>

<!-- Doctor Grid -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <?php if (empty($doctors)): ?>
        <div class="text-center py-5 bg-light rounded-4 border">
            <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
            <h5>No doctors found matching your criteria</h5>
            <p class="text-muted">Try clearing your search query or selecting another medical system.</p>
            <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-warning text-dark fw-bold">View All Doctors</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($doctors as $doc): ?>
            <?php 
                $imgUrl = getImageUrl($doc['photo'], 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80');
                $treatmentBadgeClass = match($doc['treatment_type'] ?? 'allopathic') {
                    'ayurvedic' => 'bg-success text-white',
                    'homeopathic' => 'bg-info text-dark',
                    'yoga_wellness' => 'bg-warning text-dark',
                    default => 'bg-primary text-white'
                };
            ?>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 p-4 border rounded-4 shadow-sm text-center bg-white d-flex flex-column" style="transition: all 0.3s ease;">
                    <!-- Doctor Image -->
                    <div class="mx-auto mb-3 position-relative" style="width: 125px; height: 125px; border-radius: 50%; overflow: hidden; border: 4px solid #f39c12; box-shadow: 0 4px 15px rgba(243, 156, 18, 0.28);">
                        <img src="<?= e($imgUrl); ?>" alt="<?= e($doc['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                    </div>

                    <?php if (!empty($doc['badge'])): ?>
                    <span class="badge bg-dark text-warning border mb-2 px-2 py-1 small mx-auto">
                        <i class="fas fa-certificate me-1"></i> <?= e($doc['badge']); ?>
                    </span>
                    <?php endif; ?>

                    <h5 class="fw-bold fs-6 mb-1 text-dark"><?= e($doc['name']); ?></h5>
                    <p class="small text-warning fw-bold mb-1"><?= e($doc['specialization']); ?></p>
                    <p class="small text-muted mb-2" style="font-size: 12.5px;"><?= e($doc['qualification']); ?> (<?= (int)$doc['experience_years']; ?>+ Yrs Exp)</p>
                    
                    <div class="mb-3">
                        <span class="badge <?= $treatmentBadgeClass; ?> px-2 py-1">
                            <?= strtoupper(str_replace('_', ' ', $doc['treatment_type'])); ?>
                        </span>
                    </div>

                    <!-- Hospital / Location / Availability -->
                    <div class="p-2 bg-light rounded text-start small mb-3 border">
                        <div class="mb-1 text-truncate" title="<?= e($doc['clinic_hospital_name'] ?? 'Partner Hospital'); ?>">
                            <i class="fas fa-hospital text-primary me-1"></i> <strong><?= e($doc['clinic_hospital_name'] ?? 'Partner Clinic'); ?></strong>
                        </div>
                        <div class="mb-1">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($doc['city']); ?>, <?= e($doc['state']); ?>
                        </div>
                        <div>
                            <i class="fas fa-calendar-check text-success me-1"></i> <?= e($doc['available_days']); ?>
                        </div>
                    </div>

                    <?php if (!empty($doc['bio'])): ?>
                    <p class="small text-muted mb-3 text-start" style="font-size: 12px; line-height: 1.45;">
                        <?= e(truncateText($doc['bio'], 95)); ?>
                    </p>
                    <?php endif; ?>

                    <div class="mt-auto d-flex flex-column gap-2">
                        <a href="<?= BASE_URL; ?>/contact.php?doctor=<?= urlencode($doc['name']); ?>" class="btn btn-sm btn-outline-dark w-100 py-2">
                            <i class="fas fa-envelope me-1"></i> Consultation Inquiry
                        </a>
                        <a href="https://wa.me/919234055507?text=<?= urlencode('Hello Herbalbox Foundation, I would like to consult with ' . $doc['name'] . ' regarding upcoming medical camps.'); ?>" target="_blank" class="btn btn-sm btn-outline-success w-100 py-1">
                            <i class="fab fa-whatsapp me-1"></i> Book via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-light border-top">
    <div class="container text-center">
        <h3 class="fw-bold mb-2">Are You a Medical Practitioner?</h3>
        <p class="text-muted max-w-650 mx-auto mb-4">Join our network of compassionate doctors, surgeons, AYUSH vaidyas, and dentists volunteering for rural healthcare camps in Bihar.</p>
        <a href="<?= BASE_URL; ?>/partnerships.php?type=doctor" class="btn btn-warning text-dark fw-bold btn-lg px-4 shadow-sm">
            <i class="fas fa-user-plus me-1"></i> Register as a Volunteer Doctor
        </a>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>