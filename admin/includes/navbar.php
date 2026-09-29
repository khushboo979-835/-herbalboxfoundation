<?php
declare(strict_types=1);

$admin = getCurrentAdmin();
?>

<header class="admin-topbar">
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-light d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h5 class="mb-0 fw-bold d-none d-md-block text-dark"><?= e($adminTitle ?? 'Administration Control Center'); ?></h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>/index.php" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-external-link-alt me-1"></i> View Live Website
        </a>

        <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center gap-2 dropdown-toggle py-1 px-2 border" type="button" data-bs-toggle="dropdown">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 13px;">
                    <?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)); ?>
                </div>
                <div class="text-start d-none d-sm-block">
                    <span class="d-block fw-bold small text-dark" style="line-height: 1.2;"><?= e($admin['name'] ?? 'Administrator'); ?></span>
                    <small class="text-muted" style="font-size: 10px;"><?= strtoupper(e($admin['role'] ?? 'Admin')); ?></small>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="<?= ADMIN_URL; ?>/users/index.php"><i class="fas fa-user-circle me-2"></i> Manage Admins</a></li>
                <li><a class="dropdown-item" href="<?= ADMIN_URL; ?>/settings/index.php"><i class="fas fa-sliders-h me-2"></i> System Settings</a></li>
                <li><a class="dropdown-item" href="<?= ADMIN_URL; ?>/activity-logs/index.php"><i class="fas fa-list-alt me-2"></i> Security Logs</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= ADMIN_URL; ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sign Out</a></li>
            </ul>
        </div>
    </div>
</header>
