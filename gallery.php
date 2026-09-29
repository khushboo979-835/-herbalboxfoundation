<?php
/**
 * Media Gallery & Visual Showcase
 * Herbalbox Foundation - GiveLife Style with Unique Real Photos
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$cats = [];
try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $cats = $pdo->query("SELECT * FROM gallery_categories WHERE status = 'published' ORDER BY sort_order ASC")->fetchAll() ?: [];
    }
} catch (Throwable $e) {
    error_log("Gallery Query Notice: " . $e->getMessage());
}

if (empty($cats)) {
    $cats = [
        ['name' => 'Health & Medical Camps', 'slug' => 'health-medical-camps'],
        ['name' => 'Eye Screening & Surgeries', 'slug' => 'eye-screening-surgeries'],
        ['name' => 'NCERT Smart Schools & Labs', 'slug' => 'ncert-smart-schools-labs'],
        ['name' => 'Blood Donation Drives', 'slug' => 'blood-donation-drives'],
        ['name' => 'Yoga & AYUSH Wellness', 'slug' => 'yoga-ayush-wellness']
    ];
}

$galleryImages = [
    [
        'cat_slug' => 'health-medical-camps',
        'title' => 'Doctors Consulting Elderly Beneficiaries',
        'desc' => 'Free general physician consultation, blood pressure and glucose testing in Hajipur rural village.',
        'image' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=800&q=80'
    ],
    [
        'cat_slug' => 'health-medical-camps',
        'title' => 'Free Essential Medicine Distribution Counter',
        'desc' => 'Dispensing 7-day essential antibiotics, analgesics, and nutritional herbal syrups to patients.',
        'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=800&q=80'
    ],
    [
        'cat_slug' => 'eye-screening-surgeries',
        'title' => 'Digital Eye Refraction & Vision Testing',
        'desc' => 'Computerized vision checkups and free high-quality prescription spectacles distribution.',
        'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800&q=80'
    ],
    [
        'cat_slug' => 'eye-screening-surgeries',
        'title' => 'Free Cataract Surgeries & Lens Implants',
        'desc' => 'Sponsored modern sutureless cataract surgeries restoring clear eyesight for senior villagers.',
        'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&q=80'
    ],
    [
        'cat_slug' => 'ncert-smart-schools-labs',
        'title' => 'Smart Digital Classroom Session in Partner School',
        'desc' => 'Students engaging in interactive audio-visual NCERT curriculum learning via digital boards.',
        'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&q=80'
    ],
    [
        'cat_slug' => 'ncert-smart-schools-labs',
        'title' => 'NCERT Study Kit & Book Handover Drive',
        'desc' => 'Distributing school bags, notebooks, textbooks, and drawing kits to primary school children.',
        'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&q=80'
    ],
    [
        'cat_slug' => 'blood-donation-drives',
        'title' => 'Voluntary Blood Donation Drive in Patna',
        'desc' => 'Youth and community donors stepping forward to support emergency blood banks.',
        'image' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=800&q=80'
    ],
    [
        'cat_slug' => 'yoga-ayush-wellness',
        'title' => 'Community Morning Yoga & Pranayama in Hajipur',
        'desc' => 'Certified Yoga Acharyas guiding villagers in immunity-boosting breathing and meditation exercises.',
        'image' => 'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=800&q=80'
    ],
    [
        'cat_slug' => 'yoga-ayush-wellness',
        'title' => 'Ayurvedic Pulse Diagnosis (Nadi Pariksha) Clinic',
        'desc' => 'Traditional Ayurvedic doctors offering free holistic health assessments and herbal tonics.',
        'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&q=80'
    ]
];

$pageTitle = 'Photo & Video Gallery | Herbalbox Foundation';
$pageDesc = 'Authentic visual archives from Herbalbox Foundation free medical camps, eye checkups, NCERT digital classrooms, and yoga drives.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Page Banner Header -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4 text-center">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 rounded-pill fw-bold"><i class="fas fa-camera text-danger me-1"></i> Visual Archive</span>
        <h1 class="display-5 fw-bold text-white mb-2">Our Impact in Action: Photo Gallery</h1>
        <p class="lead text-white-50 max-w-700 mx-auto">Real smiling faces, active medical camps, and moments of compassion captured across Bihar.</p>
    </div>
</div>

<!-- Filter Bar -->
<section class="py-4 bg-white border-bottom">
    <div class="container d-flex flex-wrap justify-content-center gap-2">
        <button type="button" class="btn btn-sm btn-dark active gallery-filter-btn" data-filter="all">All Photos</button>
        <?php foreach ($cats as $cat): ?>
        <button type="button" class="btn btn-sm btn-outline-secondary gallery-filter-btn" data-filter="<?= e($cat['slug']); ?>"><?= e($cat['name']); ?></button>
        <?php endforeach; ?>
    </div>
</section>

<!-- Photo Gallery Grid -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4" id="galleryContainer">
            <?php foreach ($galleryImages as $item): ?>
            <div class="col-lg-4 col-md-6 gallery-item" data-category="<?= e($item['cat_slug']); ?>">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="<?= e($item['image']); ?>" alt="<?= e($item['title']); ?>" style="height: 240px; width: 100%; object-fit: cover;">
                    <div class="card-body p-4 bg-white d-flex flex-column">
                        <h5 class="fw-bold mb-2 fs-6"><?= e($item['title']); ?></h5>
                        <p class="small text-muted mb-0"><?= e($item['desc']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- JavaScript for Gallery Filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('btn-dark', 'active');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-dark', 'active');

            const filterValue = this.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
