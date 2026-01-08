<?php
/**
 * Image Generator
 * Creates social media ready images (WhatsApp/Instagram)
 */

function generateImage($orderId, $companyName, $logoPath, $templateId, $prayerTimesData, $phone = '', $address = '') {
    require_once __DIR__ . '/../config/config.php';
    
    // For image generation, we can:
    // 1. Use GD library (built-in PHP)
    // 2. Use headless Chrome to screenshot HTML
    // 3. Use external service
    
    // For now, we'll create a simple HTML file optimized for image conversion
    $templateFile = __DIR__ . "/../templates/template-{$templateId}-image.php";
    if (!file_exists($templateFile)) {
        $templateFile = __DIR__ . "/../templates/template-1-image.php";
    }
    
    // Prepare data - convert logo path to web-accessible URL if needed
    $logoUrl = null;
    if ($logoPath && file_exists($logoPath)) {
        // Get relative path from storage/uploads
        $logoUrl = 'storage/uploads/' . basename($logoPath);
    }
    
    $data = [
        'order_id' => $orderId,
        'company_name' => $companyName,
        'logo_path' => $logoUrl,
        'phone' => $phone,
        'address' => $address,
        'prayer_times' => array_slice($prayerTimesData['prayer_times'], 0, 7), // First 7 days for image
        'city' => $prayerTimesData['city']
    ];
    
    ob_start();
    include $templateFile;
    $html = ob_get_clean();
    
    $htmlPath = STORAGE_EXPORTS . $orderId . '-image.html';
    file_put_contents($htmlPath, $html);
    
    // Note: Convert HTML to image using:
    // - Headless Chrome (puppeteer/playwright)
    // - wkhtmltoimage
    // - Or external service
    
    return $htmlPath;
}
