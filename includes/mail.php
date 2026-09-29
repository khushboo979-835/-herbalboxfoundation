<?php
/**
 * Email Helper Service
 * Dispatches HTML transactional emails using PHP mail() or configured SMTP settings
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    exit('Direct access not permitted');
}

/**
 * Send a templated HTML email notification
 */
function sendNgoEmail(string $toEmail, string $subject, string $htmlContent, ?string $toName = null): bool {
    $fromEmail = getSetting('smtp_from_email', 'no-reply@ngoseva.org');
    $fromName = getSetting('smtp_from_name', getSetting('site_name', 'Seva Foundation'));

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$fromEmail}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $template = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.6; color: #2d3748; background-color: #f7fafc; margin: 0; padding: 20px; }
            .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
            .email-header { background: #0d9488; color: #ffffff; padding: 28px 32px; text-align: center; }
            .email-header h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
            .email-header p { margin: 6px 0 0 0; opacity: 0.9; font-size: 13px; }
            .email-body { padding: 32px; font-size: 15px; }
            .email-footer { background: #f8fafc; padding: 20px 32px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #edf2f7; }
            .badge { display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 4px; background: #e6fffa; color: #047481; }
            .info-table { width: 100%; border-collapse: collapse; margin: 18px 0; }
            .info-table th, .info-table td { padding: 10px 14px; border-bottom: 1px solid #edf2f7; text-align: left; }
            .info-table th { background: #f8fafc; color: #4a5568; font-weight: 600; width: 35%; }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h1>' . htmlspecialchars($fromName) . '</h1>
                <p>' . htmlspecialchars(getSetting('site_tagline', 'Serving Humanity')) . '</p>
            </div>
            <div class="email-body">' . $htmlContent . '</div>
            <div class="email-footer">
                <p>This is an automated notification from ' . htmlspecialchars($fromName) . '.</p>
                <p>' . htmlspecialchars(getSetting('site_address', 'New Delhi, India')) . ' | Ph: ' . htmlspecialchars(getSetting('site_phone', '')) . '</p>
            </div>
        </div>
    </body>
    </html>';

    // Dispatches via standard mail function (or SMTP hook if integrated)
    try {
        return @mail($toEmail, $subject, $template, $headers);
    } catch (Exception $e) {
        error_log("Email sending exception: " . $e->getMessage());
        return false;
    }
}
