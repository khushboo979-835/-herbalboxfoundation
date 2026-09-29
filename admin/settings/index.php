<?php
/**
 * Master Website Settings Manager
 * NGO Seva Foundation
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();

    $settings = $_POST['settings'] ?? [];
    if (is_array($settings)) {
        $updateStmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($settings as $key => $val) {
            $updateStmt->execute([sanitize($key), sanitize($val)]);
        }
        logActivity('Updated Site Settings', 'settings', null, 'Modified site configuration parameters.');
        setFlashMessage('success', 'Website settings have been successfully updated.');
        header('Location: ' . ADMIN_URL . '/settings/index.php');
        exit;
    }
}

$adminTitle = 'System & Website Settings';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Website & System Settings</h4>
                <p class="text-muted small mb-0">Control organization branding, phone numbers, WhatsApp widgets, tax exemption IDs, and payment keys.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <form action="<?= ADMIN_URL; ?>/settings/index.php" method="POST" class="form-custom">
            <?= getCsrfInput(); ?>

            <div class="row g-4">
                <!-- 1. NGO Identity & Legal -->
                <div class="col-lg-6">
                    <div class="card-admin h-100">
                        <div class="card-header"><i class="fas fa-id-card text-primary me-2"></i> Organization Identity & Registrations</div>
                        <div class="p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">NGO Legal Name</label>
                                <input type="text" name="settings[site_name]" class="form-control" value="<?= e(getSetting('site_name')); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Tagline / Mission Slogan</label>
                                <input type="text" name="settings[site_tagline]" class="form-control" value="<?= e(getSetting('site_tagline')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Trust / Society Registration Number</label>
                                <input type="text" name="settings[ngo_reg_number]" class="form-control" value="<?= e(getSetting('ngo_reg_number')); ?>">
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">PAN Number</label>
                                    <input type="text" name="settings[pan_number]" class="form-control text-uppercase" value="<?= e(getSetting('pan_number')); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">NITI Aayog Darpan ID</label>
                                    <input type="text" name="settings[niti_aayog_id]" class="form-control" value="<?= e(getSetting('niti_aayog_id')); ?>">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Section 80G Approval No</label>
                                    <input type="text" name="settings[tax_exemption_80g]" class="form-control" value="<?= e(getSetting('tax_exemption_80g')); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Section 12A Registration</label>
                                    <input type="text" name="settings[tax_exemption_12a]" class="form-control" value="<?= e(getSetting('tax_exemption_12a')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Contact & Headquarters -->
                <div class="col-lg-6">
                    <div class="card-admin h-100">
                        <div class="card-header"><i class="fas fa-phone-alt text-success me-2"></i> Contact & Office Details</div>
                        <div class="p-4">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Primary Helpline Phone</label>
                                    <input type="text" name="settings[site_phone]" class="form-control" value="<?= e(getSetting('site_phone')); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Alternate Landline</label>
                                    <input type="text" name="settings[site_alt_phone]" class="form-control" value="<?= e(getSetting('site_alt_phone')); ?>">
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Primary Email Address</label>
                                    <input type="email" name="settings[site_email]" class="form-control" value="<?= e(getSetting('site_email')); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Donation Inquiry Email</label>
                                    <input type="email" name="settings[donation_email]" class="form-control" value="<?= e(getSetting('donation_email')); ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Floating WhatsApp Number (with country code)</label>
                                <input type="text" name="settings[whatsapp_number]" class="form-control" value="<?= e(getSetting('whatsapp_number')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">WhatsApp Pre-filled Message</label>
                                <input type="text" name="settings[whatsapp_message]" class="form-control" value="<?= e(getSetting('whatsapp_message')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Official Headquarters Address</label>
                                <textarea name="settings[site_address]" rows="2" class="form-control"><?= e(getSetting('site_address')); ?></textarea>
                            </div>
                            <div>
                                <label class="form-label small fw-bold">Office Working Hours</label>
                                <input type="text" name="settings[office_hours]" class="form-control" value="<?= e(getSetting('office_hours')); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Social Media URLs -->
                <div class="col-lg-6">
                    <div class="card-admin h-100">
                        <div class="card-header"><i class="fas fa-share-alt text-info me-2"></i> Social Media Profiles</div>
                        <div class="p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold"><i class="fab fa-facebook text-primary me-1"></i> Facebook URL</label>
                                <input type="url" name="settings[facebook_url]" class="form-control" value="<?= e(getSetting('facebook_url')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold"><i class="fab fa-instagram text-danger me-1"></i> Instagram URL</label>
                                <input type="url" name="settings[instagram_url]" class="form-control" value="<?= e(getSetting('instagram_url')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold"><i class="fab fa-youtube text-danger me-1"></i> YouTube Channel URL</label>
                                <input type="url" name="settings[youtube_url]" class="form-control" value="<?= e(getSetting('youtube_url')); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold"><i class="fab fa-x-twitter text-dark me-1"></i> X / Twitter URL</label>
                                <input type="url" name="settings[twitter_url]" class="form-control" value="<?= e(getSetting('twitter_url')); ?>">
                            </div>
                            <div>
                                <label class="form-label small fw-bold"><i class="fab fa-linkedin text-primary me-1"></i> LinkedIn Company Page</label>
                                <input type="url" name="settings[linkedin_url]" class="form-control" value="<?= e(getSetting('linkedin_url')); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Payment Gateway & Maps -->
                <div class="col-lg-6">
                    <div class="card-admin h-100">
                        <div class="card-header"><i class="fas fa-credit-card text-warning me-2"></i> Payment Gateway & Map Integration</div>
                        <div class="p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Razorpay Key ID</label>
                                <input type="text" name="settings[razorpay_key_id]" class="form-control" value="<?= e(getSetting('razorpay_key_id')); ?>">
                                <small class="text-muted">Place your Razorpay Key ID here (e.g. rzp_test_... or rzp_live_...)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Razorpay Key Secret</label>
                                <input type="password" name="settings[razorpay_key_secret]" class="form-control" value="<?= e(getSetting('razorpay_key_secret')); ?>">
                            </div>
                            <div>
                                <label class="form-label small fw-bold">Google Maps Embed URL</label>
                                <textarea name="settings[google_map_embed]" rows="3" class="form-control"><?= e(getSetting('google_map_embed')); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save me-2"></i> Save All Settings
                </button>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
