<?php
/**
 * Medical & Legal Disclaimer Page
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pageTitle = 'Medical & Legal Disclaimer';
$pageDesc = 'Medical disclaimer regarding treatments, camp prescriptions, and healthcare information.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="bg-dark text-white py-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-3">
        <h1 class="h2 fw-bold text-white mb-1">Medical & Content Disclaimer</h1>
        <p class="text-white-50 mb-0">Important Healthcare Transparency Notice</p>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="content-legal leading-relaxed">
                    <div class="alert alert-warning p-4 rounded-4 mb-4">
                        <h5 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> No Medical Advice / Self-Prescription</h5>
                        <p class="mb-0 small">The articles, AYUSH guides, yoga tutorials, and health camp summaries published on this website are intended exclusively for general awareness and educational benefit. They should never be treated as personal medical diagnosis or self-treatment prescriptions.</p>
                    </div>

                    <h4>1. Professional Doctor Consultation</h4>
                    <p>Always seek the direct guidance of a licensed Allopathic Physician, BAMS Ayurvedic Vaidya, or BHMS Homeopath regarding any acute symptoms or chronic medical condition. Never ignore professional clinical advice or delay seeking it because of information read online.</p>

                    <h4>2. Camp Medications & Screenings</h4>
                    <p>Medications and eyeglasses dispensed during Seva Foundation camps are prescribed individually by licensed doctors present at the camp after physical examination. Treatment claims made by individual practitioners remain subject to medical evaluation.</p>

                    <h4>3. Institutional Partners</h4>
                    <p>Hospitals, diagnostic centres, and partner schools listed on this platform are independent entities operating under structured Memorandums of Understanding (MOUs). Specific clinical protocols remain the professional responsibility of the respective healthcare institution.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
