<?php
/**
 * 404 Not Found Page
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

http_response_code(404);
$pageTitle = '404 - Page Not Found';
$pageDesc = 'The requested page could not be found on Seva Foundation website.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5 my-5 text-center">
    <div class="container py-5">
        <div class="p-5 bg-white rounded-4 shadow-sm border max-w-600 mx-auto">
            <div class="text-primary mb-3">
                <i class="fas fa-exclamation-triangle fa-4x"></i>
            </div>
            <h1 class="display-4 fw-bold text-dark mb-2">404</h1>
            <h4 class="fw-bold mb-3">Oops! Page Not Found</h4>
            <p class="text-muted mb-4">The page you are looking for might have been moved, renamed, or is temporarily unavailable.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?= BASE_URL; ?>/index.php" class="btn btn-ngo-primary"><i class="fas fa-home me-1"></i> Back to Homepage</a>
                <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-outline-secondary">Contact Support</a>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
