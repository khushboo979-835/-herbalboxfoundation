<?php
declare(strict_types=1);
?>

<footer class="py-3 px-4 bg-white border-top text-muted small text-center mt-auto">
    <div class="container-fluid">
        &copy; <?= date('Y'); ?> <?= e(getSetting('site_name', 'Seva Foundation')); ?> • Admin Management Console v2.0 • Secured with Core PHP 8 & MySQL 8
    </div>
</footer>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Admin Custom JS -->
<script src="<?= BASE_URL; ?>/assets/js/admin.js"></script>
</body>
</html>
