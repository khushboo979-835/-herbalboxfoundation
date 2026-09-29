<?php
declare(strict_types=1);

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
function isMenuActive(string $path, string $currentUri): string {
    return str_contains($currentUri, $path) ? 'active' : '';
}
?>

<aside class="admin-sidebar">
    <a href="<?= ADMIN_URL; ?>/dashboard.php" class="sidebar-brand">
        <i class="fas fa-hands-holding-child text-primary fs-4"></i>
        <span>NGO Admin Console</span>
    </a>

    <ul class="sidebar-menu">
        <li class="menu-header">Overview</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/dashboard.php" class="<?= isMenuActive('dashboard.php', $currentUri); ?>">
                <i class="fas fa-th-large"></i> <span>Dashboard</span>
            </a>
        </li>

        <li class="menu-header">Programs & Clinical</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/programs/index.php" class="<?= isMenuActive('/programs/', $currentUri); ?>">
                <i class="fas fa-heartbeat"></i> <span>Programs & Verticals</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/doctors/index.php" class="<?= isMenuActive('/doctors/', $currentUri); ?>">
                <i class="fas fa-user-md"></i> <span>Doctors Network</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/hospitals/index.php" class="<?= isMenuActive('/hospitals/', $currentUri); ?>">
                <i class="fas fa-hospital"></i> <span>Hospitals & Labs</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/schools/index.php" class="<?= isMenuActive('/schools/', $currentUri); ?>">
                <i class="fas fa-school"></i> <span>School Partners (MOUs)</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/mou/index.php" class="<?= isMenuActive('/mou/', $currentUri); ?>">
                <i class="fas fa-file-contract"></i> <span>MOU Documents</span>
            </a>
        </li>

        <li class="menu-header">Events & Outreach</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/events/index.php" class="<?= isMenuActive('/events/', $currentUri); ?>">
                <i class="fas fa-calendar-alt"></i> <span>Events & Camps</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/event-registrations/index.php" class="<?= isMenuActive('/event-registrations/', $currentUri); ?>">
                <i class="fas fa-id-badge"></i> <span>Camp Registrations</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/donations/index.php" class="<?= isMenuActive('/donations/', $currentUri); ?>">
                <i class="fas fa-hand-holding-usd"></i> <span>Donations (80G)</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/volunteers/index.php" class="<?= isMenuActive('/volunteers/', $currentUri); ?>">
                <i class="fas fa-hands-helping"></i> <span>Volunteers</span>
            </a>
        </li>

        <li class="menu-header">Media & Content</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/blog/index.php" class="<?= isMenuActive('/blog/', $currentUri); ?>">
                <i class="fas fa-newspaper"></i> <span>News & Blog Posts</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/gallery/index.php" class="<?= isMenuActive('/gallery/', $currentUri); ?>">
                <i class="fas fa-images"></i> <span>Photo Gallery</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/videos/index.php" class="<?= isMenuActive('/videos/', $currentUri); ?>">
                <i class="fas fa-video"></i> <span>Video Links</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/testimonials/index.php" class="<?= isMenuActive('/testimonials/', $currentUri); ?>">
                <i class="fas fa-comment-dots"></i> <span>Testimonials</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/products/index.php" class="<?= isMenuActive('/products/', $currentUri); ?>">
                <i class="fas fa-leaf"></i> <span>Ayurvedic Store</span>
            </a>
        </li>

        <li class="menu-header">Enquiries & Leads</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/enquiries/index.php" class="<?= isMenuActive('/enquiries/', $currentUri); ?>">
                <i class="fas fa-inbox"></i> <span>Contact Messages</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/partners/index.php" class="<?= isMenuActive('/partners/', $currentUri); ?>">
                <i class="fas fa-handshake"></i> <span>Partnership Proposals</span>
            </a>
        </li>

        <li class="menu-header">Appearance & Configuration</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/home/banners.php" class="<?= isMenuActive('/home/', $currentUri); ?>">
                <i class="fas fa-sliders-h"></i> <span>Home Banners</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/impact/index.php" class="<?= isMenuActive('/impact/', $currentUri); ?>">
                <i class="fas fa-chart-line"></i> <span>Impact Stats</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/about/index.php" class="<?= isMenuActive('/about/', $currentUri); ?>">
                <i class="fas fa-users-cog"></i> <span>About & Team</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/seo/index.php" class="<?= isMenuActive('/seo/', $currentUri); ?>">
                <i class="fas fa-search-dollar"></i> <span>SEO Settings</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/settings/index.php" class="<?= isMenuActive('/settings/', $currentUri); ?>">
                <i class="fas fa-cog"></i> <span>Site Settings</span>
            </a>
        </li>

        <li class="menu-header">System & Security</li>
        <li>
            <a href="<?= ADMIN_URL; ?>/users/index.php" class="<?= isMenuActive('/users/', $currentUri); ?>">
                <i class="fas fa-user-shield"></i> <span>Admin Users</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/activity-logs/index.php" class="<?= isMenuActive('/activity-logs/', $currentUri); ?>">
                <i class="fas fa-history"></i> <span>Activity Logs</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/backup/index.php" class="<?= isMenuActive('/backup/', $currentUri); ?>">
                <i class="fas fa-database"></i> <span>Database Backup</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL; ?>/logout.php" class="text-danger">
                <i class="fas fa-sign-out-alt"></i> <span>Sign Out</span>
            </a>
        </li>
    </ul>
</aside>
