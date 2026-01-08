<?php
/**
 * API endpoint للحصول على بيانات المعاينة
 * يجلب مواقيت الصلاة من Aladhan API ديناميكياً
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Get city from request (default: رام الله)
$city = isset($_GET['city']) ? trim($_GET['city']) : 'رام الله';
$year = date('Y');
$month = 9; // رمضان (سيتم تحديثه تلقائياً حسب التاريخ)

// تحديد شهر رمضان ديناميكياً
$currentMonth = (int)date('n');
$currentYear = (int)date('Y');

// حساب شهر رمضان (تقريبي - يمكن تحسينه)
if ($currentMonth >= 3 && $currentMonth <= 5) {
    // رمضان قريب
    $month = 3; // مارس (تقريبي)
} else {
    $month = 9; // افتراضي
}

// City coordinates mapping
$cityCoordinates = [
    'رام الله' => ['lat' => 31.9073, 'lng' => 35.2043],
    'نابلس' => ['lat' => 32.2211, 'lng' => 35.2544],
    'القدس' => ['lat' => 31.7683, 'lng' => 35.2137],
    'بيت لحم' => ['lat' => 31.7054, 'lng' => 35.2024],
    'الخليل' => ['lat' => 31.5326, 'lng' => 35.0998],
    'جنين' => ['lat' => 32.4607, 'lng' => 35.3000],
    'طولكرم' => ['lat' => 32.3089, 'lng' => 35.0275],
    'قلقيلية' => ['lat' => 32.1892, 'lng' => 34.9706],
    'سلفيت' => ['lat' => 32.0819, 'lng' => 35.1697],
    'أريحا' => ['lat' => 31.8564, 'lng' => 35.4622],
    'يافا' => ['lat' => 32.0450, 'lng' => 34.7517],
    'حيفا' => ['lat' => 32.7940, 'lng' => 34.9896],
    'عكا' => ['lat' => 32.9283, 'lng' => 35.0767],
    'الناصرة' => ['lat' => 32.6996, 'lng' => 35.3035],
    'بئر السبع' => ['lat' => 31.2520, 'lng' => 34.7915],
    'غزة' => ['lat' => 31.3547, 'lng' => 34.3088],
    'خان يونس' => ['lat' => 31.3400, 'lng' => 34.3061],
    'رفح' => ['lat' => 31.2969, 'lng' => 34.2436],
    'دير البلح' => ['lat' => 31.4170, 'lng' => 34.3500],
    'بيت جالا' => ['lat' => 31.7100, 'lng' => 35.1867],
    'بيت ساحور' => ['lat' => 31.7000, 'lng' => 35.2000],
    'البيرة' => ['lat' => 31.9073, 'lng' => 35.2043],
    'بيت لاهيا' => ['lat' => 31.5500, 'lng' => 34.5000],
    'جبل النار' => ['lat' => 31.8500, 'lng' => 35.2167],
    'شعفاط' => ['lat' => 31.8167, 'lng' => 35.2333],
    'بيت حنينا' => ['lat' => 31.8167, 'lng' => 35.2167],
    'عناتا' => ['lat' => 31.8167, 'lng' => 35.2500],
    'أبو ديس' => ['lat' => 31.7667, 'lng' => 35.2667],
    'الزعيم' => ['lat' => 31.8000, 'lng' => 35.2500],
    'الرام' => ['lat' => 31.8500, 'lng' => 35.2333],
];

// Find city coordinates
$coords = null;
foreach ($cityCoordinates as $cityName => $coord) {
    if (stripos($city, $cityName) !== false || stripos($cityName, $city) !== false) {
        $coords = $coord;
        break;
    }
}

if (!$coords) {
    $coords = $cityCoordinates['رام الله']; // Default
}

// Fetch from Aladhan API
$url = "http://api.aladhan.com/v1/calendar/{$year}/{$month}";
$params = [
    'latitude' => $coords['lat'],
    'longitude' => $coords['lng'],
    'method' => 3, // MWL
    'school' => 0  // Shafi
];

$url .= '?' . http_build_query($params);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    echo json_encode([
        'success' => false,
        'message' => 'فشل في جلب مواقيت الصلاة'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$data = json_decode($response, true);

if (!$data || !isset($data['data'])) {
    echo json_encode([
        'success' => false,
        'message' => 'بيانات غير صحيحة من API'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Process prayer times - أول 30 يوم
$prayerTimes = [];
$daysToShow = min(30, count($data['data']));

for ($i = 0; $i < $daysToShow; $i++) {
    $day = $data['data'][$i];
    
    // Extract time from format like "04:30 (EET)"
    function extractTime($timeString) {
        preg_match('/(\d{2}:\d{2})/', $timeString, $matches);
        return $matches[1] ?? $timeString;
    }
    
    $prayerTimes[] = [
        'day' => $i + 1,
        'date' => $day['date']['gregorian']['date'],
        'hijri' => $day['date']['hijri']['date'],
        'hijri_day' => $day['date']['hijri']['day'],
        'hijri_month' => $day['date']['hijri']['month']['ar'],
        'imsak' => extractTime($day['timings']['Imsak'] ?? $day['timings']['Fajr']),
        'fajr' => extractTime($day['timings']['Fajr']),
        'sunrise' => extractTime($day['timings']['Sunrise']),
        'dhuhr' => extractTime($day['timings']['Dhuhr']),
        'asr' => extractTime($day['timings']['Asr']),
        'maghrib' => extractTime($day['timings']['Maghrib']),
        'isha' => extractTime($day['timings']['Isha'])
    ];
}

echo json_encode([
    'success' => true,
    'city' => $city,
    'prayer_times' => $prayerTimes
], JSON_UNESCAPED_UNICODE);
