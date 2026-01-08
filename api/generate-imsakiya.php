<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load configuration files
$configPath = __DIR__ . '/../config/config.php';
$dbPath = __DIR__ . '/../config/database.php';

if (!file_exists($configPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'ملف الإعدادات غير موجود. يرجى نسخ config.php.example إلى config.php'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once $configPath;

if (!file_exists($dbPath)) {
    // Try to continue without database (for testing)
    $pdo = null;
} else {
    require_once $dbPath;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Get form data
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$companyName = isset($_POST['companyName']) ? trim($_POST['companyName']) : '';
$companyPhone = isset($_POST['companyPhone']) ? trim($_POST['companyPhone']) : '';
$companyAddress = isset($_POST['companyAddress']) ? trim($_POST['companyAddress']) : '';
$template = isset($_POST['template']) ? intval($_POST['template']) : 1;
$customerName = isset($_POST['customerName']) ? trim($_POST['customerName']) : '';
$customerEmail = isset($_POST['customerEmail']) ? trim($_POST['customerEmail']) : '';
$customerPhone = isset($_POST['customerPhone']) ? trim($_POST['customerPhone']) : '';

// Validate required fields
if (empty($city) || empty($companyName) || empty($template) || empty($customerName) || empty($customerEmail)) {
    echo json_encode([
        'success' => false,
        'message' => 'جميع الحقول المطلوبة يجب ملؤها'
    ]);
    exit;
}

// Handle logo upload
$logoPath = null;
if (isset($_FILES['companyLogo']) && $_FILES['companyLogo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['companyLogo'];
    
    // Validate file size
    if ($file['size'] > MAX_FILE_SIZE) {
        echo json_encode([
            'success' => false,
            'message' => 'حجم الشعار كبير جداً. الحد الأقصى 2MB'
        ]);
        exit;
    }
    
    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        echo json_encode([
            'success' => false,
            'message' => 'نوع الملف غير مدعوم. يرجى رفع صورة PNG أو JPG'
        ]);
        exit;
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $logoFilename = uniqid('logo_') . '.' . $extension;
    $logoPath = STORAGE_UPLOADS . $logoFilename;
    
    if (!move_uploaded_file($file['tmp_name'], $logoPath)) {
        echo json_encode([
            'success' => false,
            'message' => 'فشل في رفع الشعار'
        ]);
        exit;
    }
}

// Fetch prayer times
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$prayerTimesUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $basePath . "/prayer-times.php?city=" . urlencode($city);

$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$prayerTimesResponse = @file_get_contents($prayerTimesUrl, false, $context);
$prayerTimesData = json_decode($prayerTimesResponse, true);

if (!$prayerTimesData || !$prayerTimesData['success']) {
    $errorMsg = 'فشل في جلب مواقيت الصلاة';
    if (isset($prayerTimesData['message'])) {
        $errorMsg .= ': ' . $prayerTimesData['message'];
    }
    echo json_encode([
        'success' => false,
        'message' => $errorMsg,
        'debug' => $prayerTimesResponse ? substr($prayerTimesResponse, 0, 200) : 'No response'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Generate order ID
$orderId = 'ORD' . date('Ymd') . strtoupper(uniqid());

// Save order to database (if available)
$orderDbId = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                order_id, city, company_name, company_phone, company_address,
                logo_path, template_id, customer_name, customer_email, customer_phone,
                created_at, status
            ) VALUES (
                :order_id, :city, :company_name, :company_phone, :company_address,
                :logo_path, :template_id, :customer_name, :customer_email, :customer_phone,
                NOW(), 'pending'
            )
        ");
        
        $stmt->execute([
            ':order_id' => $orderId,
            ':city' => $city,
            ':company_name' => $companyName,
            ':company_phone' => $companyPhone,
            ':company_address' => $companyAddress,
            ':logo_path' => $logoPath ? basename($logoPath) : null,
            ':template_id' => $template,
            ':customer_name' => $customerName,
            ':customer_email' => $customerEmail,
            ':customer_phone' => $customerPhone
        ]);
        
        $orderDbId = $pdo->lastInsertId();
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        // Continue without database for now
    }
}

// Generate PDF and Image
require_once __DIR__ . '/../includes/pdf-generator.php';
require_once __DIR__ . '/../includes/image-generator.php';

try {
    $pdfPath = generatePDF($orderId, $companyName, $logoPath, $template, $prayerTimesData['data'], $companyPhone, $companyAddress);
    $imagePath = generateImage($orderId, $companyName, $logoPath, $template, $prayerTimesData['data'], $companyPhone, $companyAddress);
} catch (Exception $e) {
    error_log("Generation error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'فشل في إنشاء الملفات: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Save generated files info (if database available)
if ($pdo && $orderDbId) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO generated_files (order_id, pdf_path, image_path, created_at)
            VALUES (:order_id, :pdf_path, :image_path, NOW())
        ");
        
        $stmt->execute([
            ':order_id' => $orderId,
            ':pdf_path' => basename($pdfPath),
            ':image_path' => basename($imagePath)
        ]);
        
        // Update order status
        $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = :id");
        $stmt->execute([':id' => $orderDbId]);
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

// Return success response
echo json_encode([
    'success' => true,
    'order_id' => $orderId,
    'pdf_url' => 'storage/exports/' . basename($pdfPath),
    'image_url' => 'storage/exports/' . basename($imagePath),
    'message' => 'تم إنشاء الإمساكية بنجاح'
], JSON_UNESCAPED_UNICODE);
