<?php
/**
 * Executive Admin Dashboard
 * Metrics, Analytics & Activity Hub
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth_guard.php';

$pdo = Database::getConnection();

// Aggregate KPIs
$totalDonations = (float)$pdo->query("SELECT COALESCE(SUM(amount), 0) FROM donations WHERE payment_status = 'completed'")->fetchColumn();
$countDonations = (int)$pdo->query("SELECT COUNT(*) FROM donations WHERE payment_status = 'completed'")->fetchColumn();
$countVolunteers = (int)$pdo->query("SELECT COUNT(*) FROM volunteers")->fetchColumn();
$countPartnerships = (int)$pdo->query("SELECT COUNT(*) FROM partnership_enquiries WHERE status = 'pending'")->fetchColumn();
$countSchools = (int)$pdo->query("SELECT COUNT(*) FROM schools WHERE status = 'active'")->fetchColumn();
$countDoctors = (int)$pdo->query("SELECT COUNT(*) FROM doctors WHERE status = 'active'")->fetchColumn();
$countHospitals = (int)$pdo->query("SELECT COUNT(*) FROM hospitals WHERE status = 'active'")->fetchColumn();
$countEvents = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE status = 'upcoming'")->fetchColumn();
$countEnquiries = (int)$pdo->query("SELECT COUNT(*) FROM contact_enquiries WHERE status = 'unread'")->fetchColumn();
$countMous = (int)$pdo->query("SELECT COUNT(*) FROM mous WHERE status = 'active'")->fetchColumn();

// Latest Activity Feeds
$latestDonations = $pdo->query("SELECT * FROM donations ORDER BY id DESC LIMIT 5")->fetchAll();
$latestVolunteers = $pdo->query("SELECT * FROM volunteers ORDER BY id DESC LIMIT 5")->fetchAll();
$latestPartnerships = $pdo->query("SELECT * FROM partnership_enquiries ORDER BY id DESC LIMIT 5")->fetchAll();
$latestEnquiries = $pdo->query("SELECT * FROM contact_enquiries ORDER BY id DESC LIMIT 5")->fetchAll();

$adminTitle = 'Executive Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Executive Summary</h3>
                <p class="text-muted small mb-0">Overview of donations, volunteer applications, camp registrations, and partner activities.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= ADMIN_URL; ?>/events/index.php" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i> Add Event</a>
                <a href="<?= ADMIN_URL; ?>/donations/index.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download me-1"></i> Export Donations</a>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <!-- KPI Metric Cards Grid -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-label">Total Donations (80G)</span>
                            <div class="stat-val text-success"><?= formatCurrency($totalDonations); ?></div>
                            <small class="text-muted"><?= number_format($countDonations); ?> successful transactions</small>
                        </div>
                        <div class="stat-icon-wrapper bg-success-subtle text-success">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-label">Enrolled Volunteers</span>
                            <div class="stat-val text-primary"><?= number_format($countVolunteers); ?></div>
                            <a href="<?= ADMIN_URL; ?>/volunteers/index.php" class="small text-primary text-decoration-none">Manage volunteers &rarr;</a>
                        </div>
                        <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-label">Pending Partnerships</span>
                            <div class="stat-val text-warning"><?= number_format($countPartnerships); ?></div>
                            <a href="<?= ADMIN_URL; ?>/partners/index.php" class="small text-warning text-decoration-none">Review proposals &rarr;</a>
                        </div>
                        <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="stat-label">Unread Messages</span>
                            <div class="stat-val text-danger"><?= number_format($countEnquiries); ?></div>
                            <a href="<?= ADMIN_URL; ?>/enquiries/index.php" class="small text-danger text-decoration-none">Open inbox &rarr;</a>
                        </div>
                        <div class="stat-icon-wrapper bg-danger-subtle text-danger">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary KPI Strip -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border text-center">
                    <small class="text-muted d-block">School Partners</small>
                    <h5 class="fw-bold text-dark mb-0"><?= number_format($countSchools); ?> Active</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border text-center">
                    <small class="text-muted d-block">Doctor Network</small>
                    <h5 class="fw-bold text-dark mb-0"><?= number_format($countDoctors); ?> Doctors</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border text-center">
                    <small class="text-muted d-block">Hospitals & Labs</small>
                    <h5 class="fw-bold text-dark mb-0"><?= number_format($countHospitals); ?> Affiliated</h5>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border text-center">
                    <small class="text-muted d-block">Upcoming Events</small>
                    <h5 class="fw-bold text-dark mb-0"><?= number_format($countEvents); ?> Camps</h5>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card-admin">
                    <div class="card-header">
                        <span><i class="fas fa-chart-bar text-primary me-2"></i> Monthly Donation Trends (INR)</span>
                        <span class="badge bg-light text-muted border">Current Fiscal Year</span>
                    </div>
                    <div class="p-4">
                        <canvas id="donationsChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-admin">
                    <div class="card-header">
                        <span><i class="fas fa-chart-pie text-success me-2"></i> Program Impact Split</span>
                    </div>
                    <div class="p-4">
                        <canvas id="impactDonutChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Tables Row -->
        <div class="row g-4">
            <!-- Latest Donations -->
            <div class="col-lg-6">
                <div class="card-admin">
                    <div class="card-header">
                        <span><i class="fas fa-receipt text-success me-2"></i> Recent Donations</span>
                        <a href="<?= ADMIN_URL; ?>/donations/index.php" class="btn btn-sm btn-link text-primary p-0">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-admin table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Donor</th>
                                    <th>Amount</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($latestDonations)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-3">No donation records yet</td></tr>
                                <?php else: foreach ($latestDonations as $d): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($d['donor_name']); ?></strong>
                                        <small class="text-muted"><?= e($d['donation_code']); ?></small>
                                    </td>
                                    <td><strong class="text-success"><?= formatCurrency($d['amount']); ?></strong></td>
                                    <td><span class="badge bg-light text-dark border"><?= e(ucfirst($d['purpose'])); ?></span></td>
                                    <td><span class="badge bg-success-subtle text-success"><?= strtoupper($d['payment_status']); ?></span></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Latest Volunteer Applications -->
            <div class="col-lg-6">
                <div class="card-admin">
                    <div class="card-header">
                        <span><i class="fas fa-user-plus text-primary me-2"></i> New Volunteer Registrations</span>
                        <a href="<?= ADMIN_URL; ?>/volunteers/index.php" class="btn btn-sm btn-link text-primary p-0">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-admin table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Volunteer</th>
                                    <th>City</th>
                                    <th>Interest</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($latestVolunteers)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-3">No volunteer applications yet</td></tr>
                                <?php else: foreach ($latestVolunteers as $v): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($v['name']); ?></strong>
                                        <small class="text-muted"><?= e($v['phone']); ?></small>
                                    </td>
                                    <td><?= e($v['city']); ?></td>
                                    <td><span class="badge bg-light text-primary border"><?= e(truncateText($v['area_of_interest'], 20)); ?></span></td>
                                    <td><span class="badge bg-warning-subtle text-warning"><?= strtoupper($v['status']); ?></span></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Donations Bar Chart
    const ctx = document.getElementById('donationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
            datasets: [{
                label: 'Donations Received (₹)',
                data: [35000, 48000, 72000, 54000, 89000, 115000, 95000, 130000, 145000, 110000, 160000, 185000],
                backgroundColor: 'rgba(13, 148, 136, 0.85)',
                borderColor: '#0d9488',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Impact Donut Chart
    const ctx2 = document.getElementById('impactDonutChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Healthcare & Camps', 'NCERT Education', 'AYUSH & Yoga', 'Blood Donation'],
            datasets: [{
                data: [45, 25, 18, 12],
                backgroundColor: ['#0d9488', '#0284c7', '#10b981', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
