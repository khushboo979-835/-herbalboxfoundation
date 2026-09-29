<?php
/**
 * Volunteer Program & Application Form
 * Seva Volunteer Network
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

$successMsg = '';
$errorMsg = '';

// Handle Volunteer Application
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();

    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $dob = sanitize($_POST['dob'] ?? null);
    $gender = sanitize($_POST['gender'] ?? 'Male');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $pincode = sanitize($_POST['pincode'] ?? '');
    $qualification = sanitize($_POST['qualification'] ?? '');
    $occupation = sanitize($_POST['occupation'] ?? '');
    $interests = isset($_POST['interests']) && is_array($_POST['interests']) ? implode(', ', array_map('sanitize', $_POST['interests'])) : 'Healthcare';
    $experience = sanitize($_POST['experience'] ?? '');
    $availability = sanitize($_POST['available_days'] ?? 'Weekends');
    $hours = (int)($_POST['available_hours_per_week'] ?? 5);
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($city)) {
        $errorMsg = 'Please complete all required fields (*).';
    } else {
        $resumePath = null;
        if (!empty($_FILES['resume_file']['name'])) {
            $upload = uploadFile($_FILES['resume_file'], 'volunteers', 'document');
            if ($upload['success']) {
                $resumePath = $upload['filename'];
            } else {
                $errorMsg = 'Resume upload error: ' . $upload['error'];
            }
        }

        if (empty($errorMsg)) {
            try {
                $volCode = generateReferenceCode('VOL');
                $stmt = $pdo->prepare("INSERT INTO volunteers (volunteer_code, name, email, phone, dob, gender, city, state, pincode, qualification, occupation, area_of_interest, experience, available_days, available_hours_per_week, message, resume_file, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->execute([$volCode, $name, $email, $phone, $dob ?: null, $gender, $city, $state, $pincode, $qualification, $occupation, $interests, $experience, $availability, $hours, $message, $resumePath]);

                logActivity('New Volunteer Registered', 'volunteers', null, "Volunteer: {$name} ({$volCode})");

                // Dispatch Email Notification
                $emailBody = "<h3>New Volunteer Application</h3>
                <p>A new volunteer application has been submitted by <strong>" . htmlspecialchars($name) . "</strong>.</p>
                <table class='info-table'>
                    <tr><th>Volunteer Code</th><td>{$volCode}</td></tr>
                    <tr><th>Email</th><td>" . htmlspecialchars($email) . "</td></tr>
                    <tr><th>Phone</th><td>" . htmlspecialchars($phone) . "</td></tr>
                    <tr><th>Location</th><td>" . htmlspecialchars("{$city}, {$state}") . "</td></tr>
                    <tr><th>Interests</th><td>" . htmlspecialchars($interests) . "</td></tr>
                    <tr><th>Availability</th><td>" . htmlspecialchars($availability) . " ({$hours} hrs/week)</td></tr>
                </table>";
                sendNgoEmail(getSetting('site_email', 'info@ngoseva.org'), "New Volunteer Application: {$name}", $emailBody);

                $successMsg = "Thank you {$name}! Your volunteer application (ID: {$volCode}) has been received. Our volunteer coordinator will reach out for an orientation call.";
            } catch (Exception $e) {
                $errorMsg = 'A database error occurred. Please try again.';
            }
        }
    }
}

$pageTitle = 'Become a Volunteer - Join the Humanitarian Movement';
$pageDesc = 'Volunteer with Seva Foundation across healthcare camps, blood donation drives, NCERT student teaching, and community welfare programs.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #065f46 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><i class="fas fa-hands-helping me-1"></i> Grassroots Action</span>
        <h1 class="display-5 fw-bold text-white mb-3">Join 3,400+ Changemakers as a Volunteer</h1>
        <p class="lead text-white-50 max-w-700">Contribute your time, clinical skills, or passion for education to uplift thousands of underprivileged families across India.</p>
    </div>
</div>

<!-- Volunteer Registration Form -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary text-white mb-2 px-3 py-1">Application Form</span>
                        <h3 class="fw-bold">Volunteer Application & Interests</h3>
                        <p class="text-muted small">Select your preferred fields of contribution and availability.</p>
                    </div>

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?= e($successMsg); ?></div>
                    <?php endif; ?>
                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger shadow-sm mb-4"><i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg); ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL; ?>/volunteer.php" method="POST" enctype="multipart/form-data" class="form-custom">
                        <?= getCsrfInput(); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Priya Sharma">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control" required placeholder="priya@example.com">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="10-digit mobile">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Date of Birth</label>
                                <input type="date" name="dob" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Occupation / Profession</label>
                                <input type="text" name="occupation" class="form-control" placeholder="e.g. Student / Doctor / Software Engineer">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">City *</label>
                                <input type="text" name="city" class="form-control" required placeholder="City">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">State *</label>
                                <input type="text" name="state" class="form-control" required placeholder="State">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Pincode</label>
                                <input type="text" name="pincode" class="form-control" placeholder="Pincode">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold mb-2">Areas of Interest (Select all that apply) *</label>
                                <div class="row g-2">
                                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="Medical Camps" id="i1" checked><label class="form-check-label small" for="i1">Medical & Health Camps</label></div></div>
                                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="Blood Donation" id="i2"><label class="form-check-label small" for="i2">Blood Donation Drives</label></div></div>
                                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="School Education" id="i3"><label class="form-check-label small" for="i3">NCERT School Teaching</label></div></div>
                                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="Yoga & Wellness" id="i4"><label class="form-check-label small" for="i4">Yoga & Meditation Sessions</label></div></div>
                                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="Event Logistics" id="i5"><label class="form-check-label small" for="i5">Event Management & Logistics</label></div></div>
                                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="Digital Media" id="i6"><label class="form-check-label small" for="i6">Digital Media & Photography</label></div></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Availability</label>
                                <select name="available_days" class="form-select">
                                    <option value="Weekends">Weekends Only</option>
                                    <option value="Weekdays">Weekdays</option>
                                    <option value="Anyday">Flexible (Anyday)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Hours Available Per Week</label>
                                <input type="number" name="available_hours_per_week" class="form-control" value="5" min="1" max="40">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Why do you want to volunteer with Seva Foundation?</label>
                                <textarea name="message" rows="3" class="form-control" placeholder="Tell us about your background, motivation, or previous social experience..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Upload Resume / Profile Document (Optional PDF - Max 5MB)</label>
                                <input type="file" name="resume_file" class="form-control" accept=".pdf,.doc,.docx">
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-ngo-primary btn-lg px-5">
                                    <i class="fas fa-heart me-2"></i> Join as Volunteer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
