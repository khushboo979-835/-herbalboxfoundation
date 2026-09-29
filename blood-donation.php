<?php
/**
 * Blood Donation Drives & Emergency Donor Registry
 * Seva Raktdaan Mahadan Cell
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$bloodCamps = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT * FROM events WHERE status IN ('upcoming', 'published') ORDER BY event_date ASC");
        $bloodCamps = $stmt ? $stmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("Blood Donation Query Notice: " . $e->getMessage());
}

$successMsg = '';
$errorMsg = '';

// Handle quick donor pledge submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'donor_pledge') {
    requireCsrfToken();
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $bloodGroup = sanitize($_POST['blood_group'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? 'Delhi');
    $age = (int)($_POST['age'] ?? 0);

    if (empty($name) || empty($phone) || empty($bloodGroup) || empty($city)) {
        $errorMsg = 'Please fill in all mandatory fields.';
    } elseif ($age < 18 || $age > 65) {
        $errorMsg = 'Blood donor age must be between 18 and 65 years.';
    } else {
        try {
            $regCode = generateReferenceCode('DONOR');
            $insert = $pdo->prepare("INSERT INTO volunteers (volunteer_code, name, email, phone, gender, city, state, area_of_interest, message, status) VALUES (?, ?, ?, ?, 'Male', ?, ?, 'Blood Donation Registry', ?, 'approved')");
            $insert->execute([$regCode, $name, $email, $phone, $city, $state, "Registered Emergency Blood Donor. Group: {$bloodGroup}, Age: {$age}"]);

            logActivity('Blood Donor Registration', 'volunteers', null, "Donor {$name} ({$bloodGroup}) enrolled.");
            $successMsg = "Thank you {$name}! You have been registered in our Emergency Blood Donor Registry (ID: {$regCode}). We will contact you during urgent requirements or upcoming local camps.";
        } catch (Exception $e) {
            $errorMsg = 'Failed to submit registration. Please try again or call our helpline.';
        }
    }
}

$pageTitle = 'Blood Donation Camps & Emergency Donor Registry';
$pageDesc = 'Save lives with Seva Foundation and Red Cross Society. Register as a voluntary blood donor or join our upcoming blood donation drives.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #7f1d1d 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-danger text-white mb-2 px-3 py-1"><i class="fas fa-heartbeat me-1"></i> Give Blood, Save Life</span>
        <h1 class="display-5 fw-bold text-white mb-3">Voluntary Blood Donation Drives & Emergency Registry</h1>
        <p class="lead text-white-50 max-w-700">1 unit of donated blood can save up to 3 lives. Partnering with the Indian Red Cross Society and government blood banks to supply safe blood to thalassemic and critical patients.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="#register-donor" class="btn btn-warning text-dark fw-bold"><i class="fas fa-user-plus me-1"></i> Enroll in Donor Directory</a>
            <a href="#camps" class="btn btn-outline-light"><i class="fas fa-calendar-check me-1"></i> Upcoming Blood Drives</a>
        </div>
    </div>
</div>

<!-- Key Highlights -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <?php if ($successMsg): ?>
            <div class="alert alert-success shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?= e($successMsg); ?></div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="alert alert-danger shadow-sm mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg); ?></div>
        <?php endif; ?>

        <div class="row g-4 text-center">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border">
                    <h2 class="fw-bold text-danger mb-1">12,500+</h2>
                    <p class="small text-muted mb-0">Units of Blood Collected</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border">
                    <h2 class="fw-bold text-primary mb-1">100%</h2>
                    <p class="small text-muted mb-0">Certified Red Cross Vans</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border">
                    <h2 class="fw-bold text-success mb-1">35,000+</h2>
                    <p class="small text-muted mb-0">Patients & Mothers Helped</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border">
                    <h2 class="fw-bold text-warning mb-1">24/7</h2>
                    <p class="small text-muted mb-0">Emergency Donor Helpline</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donor Eligibility & Enrollment Form -->
<section class="py-5 bg-light" id="register-donor">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="section-tag">Donor Guidelines</span>
                <h2 class="section-title mb-4">Who Can Donate Blood?</h2>
                <ul class="list-unstyled mb-4">
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="fas fa-check-circle text-success mt-1"></i>
                        <div><strong>Age & Weight:</strong> Any healthy individual between 18 - 65 years weighing 45kg or more.</div>
                    </li>
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="fas fa-check-circle text-success mt-1"></i>
                        <div><strong>Hemoglobin Count:</strong> Minimum 12.5 g/dL (tested on spot for free).</div>
                    </li>
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="fas fa-check-circle text-success mt-1"></i>
                        <div><strong>Frequency:</strong> Men can donate every 3 months; women can donate every 4 months.</div>
                    </li>
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="fas fa-shield-alt text-primary mt-1"></i>
                        <div><strong>Safe & Sterile:</strong> 100% disposable, sealed single-use needles. No risk of infection.</div>
                    </li>
                </ul>

                <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
                    <div class="bg-danger text-white p-3 rounded-circle fs-4">
                        <i class="fas fa-phone-volume"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Urgent Blood Requirement Helpline</h6>
                        <span class="text-danger fw-bold fs-5"><?= e(getSetting('site_phone', '+91 98765 43210')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Enrollment Form -->
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border">
                    <h4 class="fw-bold mb-3"><i class="fas fa-tint text-danger me-2"></i> Enroll as an Emergency Donor</h4>
                    <p class="small text-muted mb-4">Join our volunteer registry to receive alerts when blood is urgently required in your city.</p>
                    
                    <form action="<?= BASE_URL; ?>/blood-donation.php#register-donor" method="POST" class="form-custom">
                        <?= getCsrfInput(); ?>
                        <input type="hidden" name="action" value="donor_pledge">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Rahul Sharma">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="10-digit mobile number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@domain.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Blood Group *</label>
                                <select name="blood_group" class="form-select" required>
                                    <option value="">-- Select Group --</option>
                                    <option value="A+">A Positive (A+)</option>
                                    <option value="A-">A Negative (A-)</option>
                                    <option value="B+">B Positive (B+)</option>
                                    <option value="B-">B Negative (B-)</option>
                                    <option value="O+">O Positive (O+)</option>
                                    <option value="O-">O Negative (O-)</option>
                                    <option value="AB+">AB Positive (AB+)</option>
                                    <option value="AB-">AB Negative (AB-)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Age (Years) *</label>
                                <input type="number" name="age" min="18" max="65" class="form-control" required placeholder="18 - 65">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">City *</label>
                                <input type="text" name="city" class="form-control" required placeholder="e.g. New Delhi">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                                    <i class="fas fa-heart me-1"></i> Register as Voluntary Donor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Drives List -->
<section class="py-5 bg-white" id="camps">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Drive Schedule</span>
            <h2 class="section-title">Upcoming Blood Donation Drives</h2>
        </div>

        <div class="row g-4">
            <?php if (empty($bloodCamps)): ?>
            <div class="col-12 text-center py-4">
                <p class="text-muted">No scheduled blood camps for this week. Register above to get notified of upcoming drives.</p>
            </div>
            <?php else: foreach ($bloodCamps as $camp): ?>
            <div class="col-lg-6">
                <div class="p-4 bg-light rounded-4 border d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-danger mb-2">Blood Donation Camp</span>
                        <h5 class="fw-bold mb-1"><?= e($camp['title']); ?></h5>
                        <p class="small text-muted mb-1"><i class="fas fa-calendar-alt text-primary me-1"></i> <?= date('d M Y', strtotime($camp['event_date'])); ?> | <?= date('h:i A', strtotime($camp['start_time'])); ?> - <?= date('h:i A', strtotime($camp['end_time'])); ?></p>
                        <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($camp['venue']); ?>, <?= e($camp['city']); ?></p>
                    </div>
                    <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $camp['id']; ?>" class="btn btn-sm btn-outline-danger">Book Slot</a>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
