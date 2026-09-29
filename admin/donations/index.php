<?php
/**
 * Donations Management, 80G Receipts & CSV Export
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $data = $pdo->query("SELECT donation_code, receipt_number, donor_name, donor_email, donor_phone, donor_pan, amount, purpose, payment_status, is_80g_requested, created_at FROM donations ORDER BY id DESC")->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=donations_80g_report_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Donation Code', 'Receipt No', 'Donor Name', 'Email', 'Phone', 'PAN Card', 'Amount (INR)', 'Purpose', 'Payment Status', '80G Requested', 'Transaction Date']);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

$donations = $pdo->query("SELECT * FROM donations ORDER BY id DESC")->fetchAll();

$adminTitle = 'Online Donations & 80G Receipts';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Donations & 80G Tax Certificates</h4>
                <p class="text-muted small mb-0">Track online philanthropic contributions, donor PAN details, and generate official 80G receipts.</p>
            </div>
            <a href="<?= ADMIN_URL; ?>/donations/index.php?export=csv" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export 80G Donations (CSV)
            </a>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>All Donations (<?= count($donations); ?>)</span>
                <input type="text" id="tableSearchInput" class="form-control form-control-sm w-auto" placeholder="Search donor or PAN...">
            </div>
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Donation Code</th>
                            <th>Donor Name</th>
                            <th>PAN Number</th>
                            <th>Amount</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($donations)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">No donations recorded yet</td></tr>
                        <?php else: foreach ($donations as $d): ?>
                        <tr>
                            <td><span class="badge bg-dark font-monospace"><?= e($d['donation_code']); ?></span></td>
                            <td>
                                <strong class="text-dark d-block"><?= e($d['donor_name']); ?></strong>
                                <small class="text-muted"><?= e($d['donor_email']); ?> • <?= e($d['donor_phone']); ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= e($d['donor_pan'] ?: 'N/A'); ?></span></td>
                            <td><strong class="text-success"><?= formatCurrency($d['amount']); ?></strong></td>
                            <td><span class="badge bg-light text-primary border"><?= strtoupper($d['purpose']); ?></span></td>
                            <td><span class="badge bg-success-subtle text-success"><?= strtoupper($d['payment_status']); ?></span></td>
                            <td><small class="text-muted"><?= formatDate($d['created_at']); ?></small></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptModal<?= $d['id']; ?>">
                                    <i class="fas fa-receipt me-1"></i> View Receipt
                                </button>
                            </td>
                        </tr>

                        <!-- Printable 80G Receipt Modal -->
                        <div class="modal fade" id="receiptModal<?= $d['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Section 80G Tax Exemption Receipt</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4" id="printArea<?= $d['id']; ?>">
                                        <div class="border rounded-4 p-4 text-center bg-light mb-3">
                                            <h4 class="fw-bold text-dark mb-1"><?= e(getSetting('site_name', 'Seva Arogya & Shiksha Foundation')); ?></h4>
                                            <p class="small text-muted mb-2"><?= e(getSetting('site_address')); ?></p>
                                            <span class="badge bg-success text-white">80G Reg No: <?= e(getSetting('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20')); ?></span>
                                            <span class="badge bg-dark text-white">PAN: <?= e(getSetting('pan_number', 'AAATS1234F')); ?></span>
                                        </div>

                                        <table class="table table-bordered mb-0">
                                            <tr><th>Receipt Number</th><td><?= e($d['receipt_number'] ?: 'RCPT-' . $d['id']); ?></td></tr>
                                            <tr><th>Donation Code</th><td><?= e($d['donation_code']); ?></td></tr>
                                            <tr><th>Donor Name</th><td><strong><?= e($d['donor_name']); ?></strong></td></tr>
                                            <tr><th>Donor PAN</th><td><?= e($d['donor_pan'] ?: 'Not Provided'); ?></td></tr>
                                            <tr><th>Amount Contributed</th><td><strong class="text-success fs-5"><?= formatCurrency($d['amount']); ?></strong></td></tr>
                                            <tr><th>Allocation Purpose</th><td><?= e(ucfirst($d['purpose'])); ?></td></tr>
                                            <tr><th>Payment Mode</th><td><?= e(strtoupper($d['payment_method'])); ?></td></tr>
                                            <tr><th>Date of Receipt</th><td><?= formatDateTime($d['created_at']); ?></td></tr>
                                        </table>

                                        <div class="mt-4 text-muted small text-center">
                                            This is a system-generated official donation receipt eligible for 50% deduction under Section 80G of the Indian Income Tax Act.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.print();"><i class="fas fa-print me-1"></i> Print Receipt</button>
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
