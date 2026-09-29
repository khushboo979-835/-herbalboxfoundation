<?php
/**
 * Single Event Details & Attendee Registration
 * Seva Foundation Outreach
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

$eventId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: ' . BASE_URL . '/events.php');
    exit;
}

$successMsg = '';
$errorMsg = '';

// Handle Attendee Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'event_register') {
    requireCsrfToken();

    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $gender = sanitize($_POST['gender'] ?? 'Male');
    $bloodGroup = sanitize($_POST['blood_group'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $requirements = sanitize($_POST['special_requirements'] ?? '');

    if (empty($name) || empty($phone) || empty($city)) {
        $errorMsg = 'Please complete all required fields.';
    } else {
        try {
            $regCode = generateReferenceCode('EVT-REG');
            $ins = $pdo->prepare("INSERT INTO event_registrations (event_id, registration_number, name, phone, email, age, gender, blood_group, city, special_requirements, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')");
            $ins->execute([$eventId, $regCode, $name, $phone, $email, $age, $gender, $bloodGroup, $city, $requirements]);

            // Increment registered count
            $pdo->prepare("UPDATE events SET registered_count = registered_count + 1 WHERE id = ?")->execute([$eventId]);

            logActivity('Event Registration', 'events', $eventId, "Attendee: {$name} (Reg: {$regCode})");

            // Dispatch Confirmation Email if email provided
            if (!empty($email)) {
                $emailBody = "<h3>Event Registration Confirmed</h3>
                <p>Dear {$name}, your registration for <strong>" . htmlspecialchars($event['title']) . "</strong> has been confirmed.</p>
                <table class='info-table'>
                    <tr><th>Registration No</th><td><strong class='badge'>{$regCode}</strong></td></tr>
                    <tr><th>Event Date</th><td>" . formatDate($event['event_date']) . " (" . date('h:i A', strtotime($event['start_time'])) . ")</td></tr>
                    <tr><th>Venue</th><td>" . htmlspecialchars($event['venue']) . ", " . htmlspecialchars($event['city']) . "</td></tr>
                </table>
                <p>Please show this confirmation email or registration code upon arrival at the venue.</p>";
                sendNgoEmail($email, "Registration Confirmed: {$event['title']}", $emailBody, $name);
            }

            $successMsg = "Registration Successful! Your Pass Number is: {$regCode}. A confirmation has been generated.";
        } catch (Exception $e) {
            $errorMsg = 'A registration error occurred. Please try again.';
        }
    }
}

$pageTitle = $event['title'] . ' - Free Health & Welfare Camp';
$pageDesc = $event['short_description'];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #042f2e 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><?= strtoupper(str_replace('_', ' ', $event['category'])); ?></span>
        <h1 class="display-5 fw-bold text-white mb-3"><?= e($event['title']); ?></h1>
        <p class="lead text-white-50 max-w-700"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?= e($event['venue']); ?>, <?= e($event['city']); ?>, <?= e($event['state']); ?></p>
    </div>
</div>

<!-- Details & Registration Form -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Left Column: Details -->
            <div class="col-lg-7">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border mb-4">
                    <img src="<?= e(getImageUrl($event['featured_image'], 'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=800&q=80')); ?>" alt="<?= e($event['title']); ?>" class="img-fluid rounded-4 mb-4 w-100" style="max-height: 380px; object-fit: cover;">

                    <h3 class="fw-bold mb-3">About This Event / Camp</h3>
                    <div class="text-muted mb-4 leading-relaxed">
                        <?= nl2br(e($event['description'] ?: $event['short_description'])); ?>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block"><i class="fas fa-calendar-day text-primary me-1"></i> Date & Timing</small>
                                <strong class="text-dark"><?= formatDate($event['event_date']); ?><br><?= date('h:i A', strtotime($event['start_time'])); ?> - <?= date('h:i A', strtotime($event['end_time'])); ?></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block"><i class="fas fa-user-md text-success me-1"></i> Specialist Doctor Panel</small>
                                <strong class="text-dark"><?= e($event['doctor_partner_info'] ?? 'Seva Specialist Physicians'); ?></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block"><i class="fas fa-map-marked-alt text-danger me-1"></i> Complete Venue Address</small>
                                <span class="text-muted small"><?= e($event['address'] ?: $event['venue']); ?>, <?= e($event['city']); ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block"><i class="fas fa-users text-warning me-1"></i> Capacity & Registrations</small>
                                <strong class="text-dark"><?= (int)$event['registered_count']; ?> Registered / Max <?= (int)$event['max_participants']; ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                        <div>
                            <strong>Need directions to venue?</strong>
                            <small class="d-block text-muted">Open in Google Maps for turn-by-turn navigation</small>
                        </div>
                        <a href="https://maps.google.com/?q=<?= urlencode($event['venue'] . ' ' . $event['city']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-directions me-1"></i> Open Maps
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Registration Form -->
            <div class="col-lg-5">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border position-sticky" style="top: 100px;">
                    <h4 class="fw-bold mb-2"><i class="fas fa-ticket-alt text-primary me-2"></i> Free Camp Registration</h4>
                    <p class="small text-muted mb-4">Register in advance to get priority token for doctor consultation and free medicine.</p>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?= e($successMsg); ?></div>
                    <?php endif; ?>
                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger shadow-sm mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg); ?></div>
                    <?php endif; ?>

                    <?php if ($event['status'] === 'completed'): ?>
                        <div class="alert alert-secondary text-center">This event has already concluded. Please explore our upcoming events.</div>
                    <?php else: ?>
                    <form action="<?= BASE_URL; ?>/event-details.php?id=<?= $eventId; ?>" method="POST" class="form-custom">
                        <?= getCsrfInput(); ?>
                        <input type="hidden" name="action" value="event_register">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Full Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Aarti Sharma">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-7">
                                <label class="form-label small fw-bold">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="10-digit mobile">
                            </div>
                            <div class="col-5">
                                <label class="form-label small fw-bold">Age *</label>
                                <input type="number" name="age" min="1" max="110" class="form-control" required placeholder="Age">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email Address (Optional)</label>
                            <input type="email" name="email" class="form-control" placeholder="To receive pass copy">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">City / Area *</label>
                                <input type="text" name="city" class="form-control" required placeholder="e.g. Noida">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Health Symptoms / Special Requirements</label>
                            <textarea name="special_requirements" rows="2" class="form-control" placeholder="e.g. Eye vision test required, diabetes checkup"></textarea>
                        </div>

                        <button type="submit" class="btn btn-ngo-primary w-100 py-2 fw-bold">
                            <i class="fas fa-check-circle me-1"></i> Confirm Free Pass
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
