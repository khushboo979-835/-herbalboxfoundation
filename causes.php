<?php
/**
 * Causes & Humanitarian Programs
 * Herbalbox Foundation - GiveLife Style
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$programs = [];
try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT * FROM programs WHERE status = 'published' ORDER BY sort_order ASC, id ASC");
        $programs = $stmt ? $stmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("Causes Query Notice: " . $e->getMessage());
}

if (empty($programs)) {
    $programs = [
        [
            'id' => 1,
            'title' => 'Free Mega Medical & Eye Refraction Camps',
            'type' => 'healthcare',
            'icon' => 'fa-stethoscope',
            'featured_image' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80',
            'short_description' => 'Providing free multi-specialty medical checkups, free prescription glasses, and cataract surgeries across rural Bihar.',
            'target_amount' => 500000,
            'raised_amount' => 385000
        ],
        [
            'id' => 2,
            'title' => 'NCERT Digital Classrooms & School Support',
            'type' => 'education',
            'icon' => 'fa-graduation-cap',
            'featured_image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80',
            'short_description' => 'Empowering underprivileged government and partner schools with smart digital classes and free NCERT books.',
            'target_amount' => 400000,
            'raised_amount' => 295000
        ],
        [
            'id' => 3,
            'title' => 'AYUSH Herbal Wellness & Yoga Camps',
            'type' => 'ayush',
            'icon' => 'fa-leaf',
            'featured_image' => 'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=600&q=80',
            'short_description' => 'Promoting holistic wellness through daily morning yoga, Ayurvedic consultations, and pure herbal remedies.',
            'target_amount' => 300000,
            'raised_amount' => 240000
        ],
        [
            'id' => 4,
            'title' => 'Emergency Blood Donation & Donor Drives',
            'type' => 'healthcare',
            'icon' => 'fa-tint',
            'featured_image' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=600&q=80',
            'short_description' => 'Organizing voluntary blood donation camps with certified hospital blood banks to save critical lives across Bihar.',
            'target_amount' => 250000,
            'raised_amount' => 195000
        ],
        [
            'id' => 5,
            'title' => 'Free Cataract Surgeries & Vision Restoration',
            'type' => 'healthcare',
            'icon' => 'fa-eye',
            'featured_image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80',
            'short_description' => 'Sponsoring complete cataract surgeries, lens implants, and post-operative medications for senior citizens in rural villages.',
            'target_amount' => 450000,
            'raised_amount' => 360000
        ],
        [
            'id' => 6,
            'title' => 'Child Health, Nutrition & School Wellness',
            'type' => 'education',
            'icon' => 'fa-apple-alt',
            'featured_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&q=80',
            'short_description' => 'Quarterly pediatric health screening, deworming, vitamin distribution, and nutrition kits for school students.',
            'target_amount' => 350000,
            'raised_amount' => 280000
        ]
    ];
}

$pageTitle = 'Our Causes & Humanitarian Programs | Herbalbox Foundation';
$pageDesc = 'Explore our core humanitarian causes in healthcare, NCERT digital education, AYUSH wellness, and free cataract surgeries.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Page Banner Header -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4 text-center">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 rounded-pill fw-bold"><i class="fas fa-heart text-danger me-1"></i> Make A Difference</span>
        <h1 class="display-5 fw-bold text-white mb-2">Our Key Causes & Campaigns</h1>
        <p class="lead text-white-50 max-w-700 mx-auto">Every contribution directly transforms the life of an underprivileged child or patient across Bihar.</p>
    </div>
</div>

<!-- Causes Grid Section -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4">
            <?php foreach ($programs as $prog): ?>
            <div class="col-lg-4 col-md-6">
                <div class="cause-card-give">
                    <div class="cause-img-box">
                        <img src="<?= e(getImageUrl($prog['featured_image'] ?? $prog['image'] ?? null, 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=600&q=80')); ?>" alt="<?= e($prog['title']); ?>">
                        <span class="cause-tag-badge"><?= e(strtoupper($prog['type'] ?? 'General')); ?></span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h4 class="fw-bold fs-5 mb-2">
                            <a href="<?= BASE_URL; ?>/<?= ($prog['type'] ?? '') === 'education' ? 'education.php' : (($prog['type'] ?? '') === 'ayush' ? 'ayush.php' : 'healthcare.php'); ?>" class="text-dark text-decoration-none">
                                <?= e($prog['title']); ?>
                            </a>
                        </h4>
                        <p class="small text-muted mb-3 flex-grow-1"><?= e($prog['short_description']); ?></p>
                        
                        <!-- Progress Bar -->
                        <?php 
                            $target = (float)($prog['target_amount'] ?? 500000);
                            $raised = (float)($prog['raised_amount'] ?? 350000);
                            $pct = $target > 0 ? min(100, round(($raised / $target) * 100)) : 70;
                        ?>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $pct; ?>%;" aria-valuenow="<?= $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-3">
                            <span>Raised: <strong><?= formatCurrency($raised); ?></strong></span>
                            <span>Goal: <strong><?= formatCurrency($target); ?></strong></span>
                        </div>

                        <a href="<?= BASE_URL; ?>/donate.php" class="btn btn-givelife-orange w-100 py-2 mt-auto">
                            <i class="fas fa-heart text-danger me-1"></i> DONATE TO THIS CAUSE
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="p-5 rounded-4 shadow-lg text-white" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2">100% Tax Deductible</span>
                    <h2 class="text-white fw-bold mb-3 display-6">Empower Lives Through 80G Tax-Exempt Donations</h2>
                    <p class="text-white-50 lead mb-0">Your donations help us provide free cataract surgeries, life-saving medicines, and digital education kits. All donations are exempt under Section 80G.</p>
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
