<?php
/**
 * PDF Generator using HTML to PDF conversion
 * For Hostinger, we'll use a simple HTML to PDF approach
 */

function generatePDF($orderId, $companyName, $logoPath, $templateId, $prayerTimesData, $phone = '', $address = '') {
    require_once __DIR__ . '/../config/config.php';
    
    // Load template
    $templateFile = __DIR__ . "/../templates/template-{$templateId}.php";
    if (!file_exists($templateFile)) {
        $templateFile = __DIR__ . "/../templates/template-1.php";
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
        'prayer_times' => $prayerTimesData['prayer_times'],
        'city' => $prayerTimesData['city']
    ];
    
    // Generate HTML
    ob_start();
    include $templateFile;
    $html = ob_get_clean();
    
    // For now, save HTML file (can be converted to PDF using external service or library)
    $htmlPath = STORAGE_EXPORTS . $orderId . '.html';
    file_put_contents($htmlPath, $html);
    
    // Note: For actual PDF generation, you would use:
    // - Dompdf library (composer require dompdf/dompdf)
    // - Or headless Chrome via Browserhot
    // - Or external API service
    
    // For this implementation, we'll create a simple HTML file that can be printed as PDF
    // In production, integrate a proper PDF library
    
    return $htmlPath;
}
