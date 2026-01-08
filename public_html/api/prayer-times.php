<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../config/config.php';

// Get city from request
$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : 9; // Ramadan month

if (empty($city)) {
    echo json_encode([
        'success' => false,
        'message' => 'اسم المدينة مطلوب'
    ]);
    exit;
}

// Check cache first
$cacheKey = "prayer_times_{$city}_{$year}_{$month}";
$cacheFile = "../storage/cache/{$cacheKey}.json";

if (file_exists($cacheFile)) {
    $cacheData = json_decode(file_get_contents($cacheFile), true);
    if ($cacheData && isset($cacheData['timestamp'])) {
        // Cache valid for 24 hours
        if (time() - $cacheData['timestamp'] < 86400) {
            echo json_encode([
                'success' => true,
                'data' => $cacheData['data'],
                'cached' => true
            ]);
            exit;
        }
    }
}

// City coordinates mapping (Palestinian cities)
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
    // Default to Ramallah if city not found
    $coords = $cityCoordinates['رام الله'];
}

// Fetch from Aladhan API
$url = "http://api.aladhan.com/v1/calendar/{$year}/{$month}";
$params = [
    'latitude' => $coords['lat'],
    'longitude' => $coords['lng'],
    'method' => 3, // MWL (Muslim World League)
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
    ]);
    exit;
}

$data = json_decode($response, true);

if (!$data || !isset($data['data'])) {
    echo json_encode([
        'success' => false,
        'message' => 'بيانات غير صحيحة من API'
    ]);
    exit;
}

// Process and format prayer times
$prayerTimes = [];
foreach ($data['data'] as $day) {
    $prayerTimes[] = [
        'date' => $day['date']['gregorian']['date'],
        'hijri' => $day['date']['hijri']['date'],
        'day' => $day['date']['hijri']['day'],
        'month' => $day['date']['hijri']['month']['ar'],
        'year' => $day['date']['hijri']['year'],
        'fajr' => $day['timings']['Fajr'],
        'sunrise' => $day['timings']['Sunrise'],
        'dhuhr' => $day['timings']['Dhuhr'],
        'asr' => $day['timings']['Asr'],
        'maghrib' => $day['timings']['Maghrib'],
        'isha' => $day['timings']['Isha'],
        'imsak' => $day['timings']['Imsak'] ?? $day['timings']['Fajr']
    ];
}

$result = [
    'success' => true,
    'data' => [
        'city' => $city,
        'coordinates' => $coords,
        'year' => $year,
        'month' => $month,
        'prayer_times' => $prayerTimes
    ],
    'cached' => false
];

// Save to cache
if (!is_dir('../storage/cache')) {
    mkdir('../storage/cache', 0755, true);
}
file_put_contents($cacheFile, json_encode([
    'timestamp' => time(),
    'data' => $result['data']
], JSON_UNESCAPED_UNICODE));

echo json_encode($result, JSON_UNESCAPED_UNICODE);
