<?php
/**
 * Media Gallery & Video Showcase
 * Seva Foundation In-Action
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch categories
$cats = $pdo->query("SELECT * FROM gallery_categories WHERE status = 'active' ORDER BY sort_order ASC")->fetchAll();

// Fetch images
$images = $pdo->query("SELECT gi.*, gc.slug as cat_slug FROM gallery_images gi LEFT JOIN gallery_categories gc ON gi.category_id = gc.id WHERE gi.status = 'active' ORDER BY gi.sort_order ASC, gi.id DESC")->fetchAll();

// Fetch videos
$videos = $pdo->query("SELECT * FROM videos WHERE status = 'active' ORDER BY sort_order ASC, id DESC")->fetchAll();

$pageTitle = 'Photo & Video Gallery - Medical Camps, School Programs & Yoga';
$pageDesc = 'Explore authentic photographic and video archives from our free health checkup camps, blood donation drives, school MOUs, and youth empowerment workshops.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1"><i class="fas fa-camera me-1"></i> Visual Highlights</span>
        <h1 class="display-5 fw-bold text-white mb-3">Our Impact in Action: Photo & Video Gallery</h1>
        <p class="lead text-white-50 max-w-700">Real stories, real smiling faces, and moments of compassion captured from our health camps and school classrooms.</p>
    </div>
</div>

<!-- Filter Bar -->
<section class="py-4 bg-white border-bottom">
    <div class="container d-flex flex-wrap justify-content-center gap-2">
        <button type="button" class="btn btn-sm btn-ngo-primary active gallery-filter-btn" data-filter="all">All Photos</button>
        <?php foreach ($cats as $cat): ?>
        <button type="button" class="btn btn-sm btn-outline-secondary gallery-filter-btn" data-filter="<?= e($cat['slug']); ?>"><?= e($cat['name']); ?></button>
        <?php endforeach; ?>
    </div>
</section>

<!-- Photo Gallery Grid -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4">
            <!-- Sample fallback pictures if database is fresh -->
            <?php
            $sampleImgs = [
                ['cat' => 'medical-camps', 'title' => 'Mega Health Camp Delhi', 'img' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80', 'desc' => 'Doctor consultations and vital checks.'],
                ['cat' => 'blood-donation', 'title' => 'Red Cross Blood Drive', 'img' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=600&q=80', 'desc' => 'Voluntary donor giving blood.'],
                ['cat' => 'education-schools', 'title' => 'NCERT Book Distribution', 'img' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80', 'desc' => 'Students receiving learning study kits.'],
                ['cat' => 'yoga-ayush', 'title' => 'Morning Yoga in Park', 'img' => 'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=600&q=80', 'desc' => 'Community wellness meditation session.'],
                ['cat' => 'medical-camps', 'title' => 'Free Eye Refraction & Glasses', 'img' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80', 'desc' => 'Optometrist checking visual acuity.'],
                ['cat' => 'education-schools', 'title' => 'School Career Guidance Workshop', 'img' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&q=80', 'desc' => 'Counselors mentoring 12th board students.']
            ];

            if (empty($images)) {
                foreach ($sampleImgs as $imgItem): ?>
                <div class="col-lg-4 col-md-6 gallery-grid-item" data-category="<?= e($imgItem['cat']); ?>">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100">
                        <img src="<?= e($imgItem['img']); ?>" alt="<?= e($imgItem['title']); ?>" style="height: 240px; object-fit: cover;">
                        <div class="card-body p-3 bg-white">
                            <h6 class="fw-bold mb-1"><?= e($imgItem['title']); ?></h6>
                            <small class="text-muted"><?= e($imgItem['desc']); ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach;
            } else {
                foreach ($images as $img): ?>
                <div class="col-lg-4 col-md-6 gallery-grid-item" data-category="<?= e($img['cat_slug'] ?? 'all'); ?>">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100">
                        <img src="<?= e(getImageUrl($img['image_path'])); ?>" alt="<?= e($img['title']); ?>" style="height: 240px; object-fit: cover;">
                        <div class="card-body p-3 bg-white">
                            <h6 class="fw-bold mb-1"><?= e($img['title']); ?></h6>
                            <small class="text-muted"><?= e($img['description']); ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach;
            } ?>
        </div>
    </div>
</section>

<!-- Video Gallery Section -->
<section class="py-5 bg-white border-top">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">Video Archives</span>
            <h2 class="section-title">Documentary & Event Videos</h2>
            <p class="section-subtitle">Watch on-ground video coverage of our medical camps and school programs.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="p-3 bg-light rounded-4 border">
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden mb-3">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Seva Foundation Mega Health Camp" allowfullscreen></iframe>
                    </div>
                    <h5 class="fw-bold mb-1">Glimpses of Mega Health & Eye Camp</h5>
                    <p class="small text-muted mb-0">Providing free healthcare and cataract operations to 500+ rural families in Delhi NCR.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-3 bg-light rounded-4 border">
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden mb-3">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="School NCERT Education Drive" allowfullscreen></iframe>
                    </div>
                    <h5 class="fw-bold mb-1">School NCERT Learning Kits & Yoga Demonstration</h5>
                    <p class="small text-muted mb-0">Transforming classroom learning and student vitality across government schools.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
