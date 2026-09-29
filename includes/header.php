<?php
/**
 * Master Frontend Header Template
 * SEO Optimized with Dynamic OpenGraph, Twitter Cards & Schema.org JSON-LD
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    require_once __DIR__ . '/../config/config.php';
}

$siteName = getSetting('site_name', 'Herbalbox Foundation');
$pageTitle = isset($pageTitle) ? "{$pageTitle} | {$siteName}" : getSetting('seo_meta_title', "{$siteName} - Health • Education • Better Tomorrow");
$pageDesc = $pageDesc ?? getSetting('seo_meta_description', 'Registered NGO empowering underprivileged communities through free healthcare camps, blood donation drives, NCERT school support, and AYUSH wellness.');
$pageKeywords = $pageKeywords ?? getSetting('seo_meta_keywords', 'NGO India, free medical camp, school mou, blood donation, AYUSH clinics, child education support, donate 80g');
$canonicalUrl = $canonicalUrl ?? (BASE_URL . '/' . basename($_SERVER['PHP_SELF']));
$ogImage = $ogImage ?? (BASE_URL . '/assets/images/og-banner.jpg');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?></title>
    <meta name="description" content="<?= e($pageDesc); ?>">
    <meta name="keywords" content="<?= e($pageKeywords); ?>">
    <link rel="canonical" href="<?= e($canonicalUrl); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonicalUrl); ?>">
    <meta property="og:title" content="<?= e($pageTitle); ?>">
    <meta property="og:description" content="<?= e($pageDesc); ?>">
    <meta property="og:image" content="<?= e($ogImage); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= e($canonicalUrl); ?>">
    <meta property="twitter:title" content="<?= e($pageTitle); ?>">
    <meta property="twitter:description" content="<?= e($pageDesc); ?>">
    <meta property="twitter:image" content="<?= e($ogImage); ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Custom Style with Cache-Buster -->
    <link href="<?= BASE_URL; ?>/assets/css/style.css?v=<?= time(); ?>" rel="stylesheet">

    <!-- Schema.org Organization JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NGO",
      "name": "<?= e($siteName); ?>",
      "url": "<?= BASE_URL; ?>",
      "logo": "<?= BASE_URL; ?>/assets/images/logo.png",
      "description": "<?= e($pageDesc); ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= e(getSetting('site_address', 'New Delhi, India')); ?>",
        "addressCountry": "IN"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "<?= e(getSetting('site_phone', '+91 98765 43210')); ?>",
        "contactType": "customer service"
      }
    }
    </script>
</head>
<body>
