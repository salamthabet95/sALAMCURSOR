<?php
/**
 * Test Connection Script
 * Use this to test if the API is working
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$results = [
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    ],
    'checks' => []
];

// Check config file
$configPath = __DIR__ . '/../config/config.php';
if (file_exists($configPath)) {
    require_once $configPath;
    $results['checks']['config_file'] = [
        'status' => 'OK',
        'message' => 'Config file exists'
    ];
} else {
    $results['checks']['config_file'] = [
        'status' => 'ERROR',
        'message' => 'Config file not found. Copy config.php.example to config.php'
    ];
}

// Check database file
$dbPath = __DIR__ . '/../config/database.php';
if (file_exists($dbPath)) {
    $results['checks']['database_file'] = [
        'status' => 'OK',
        'message' => 'Database config file exists'
    ];
    
    // Try to connect
    try {
        require_once $dbPath;
        if (isset($pdo)) {
            $results['checks']['database_connection'] = [
                'status' => 'OK',
                'message' => 'Database connection successful'
            ];
        }
    } catch (Exception $e) {
        $results['checks']['database_connection'] = [
            'status' => 'ERROR',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ];
    }
} else {
    $results['checks']['database_file'] = [
        'status' => 'WARNING',
        'message' => 'Database config file not found. Copy database.php.example to database.php'
    ];
}

// Check storage directories
$directories = [
    'uploads' => __DIR__ . '/../storage/uploads/',
    'exports' => __DIR__ . '/../storage/exports/',
    'cache' => __DIR__ . '/../storage/cache/'
];

foreach ($directories as $name => $path) {
    if (is_dir($path)) {
        $writable = is_writable($path);
        $results['checks']["storage_{$name}"] = [
            'status' => $writable ? 'OK' : 'ERROR',
            'message' => $writable ? 'Directory exists and is writable' : 'Directory exists but is not writable',
            'path' => $path
        ];
    } else {
        $results['checks']["storage_{$name}"] = [
            'status' => 'ERROR',
            'message' => 'Directory does not exist',
            'path' => $path
        ];
    }
}

// Check prayer-times API
$prayerTimesUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
                  '://' . $_SERVER['HTTP_HOST'] . 
                  dirname($_SERVER['SCRIPT_NAME']) . 
                  '/prayer-times.php?city=رام الله';

$context = stream_context_create(['http' => ['timeout' => 5]]);
$response = @file_get_contents($prayerTimesUrl, false, $context);
if ($response) {
    $data = json_decode($response, true);
    $results['checks']['prayer_times_api'] = [
        'status' => ($data && isset($data['success'])) ? 'OK' : 'ERROR',
        'message' => ($data && isset($data['success'])) ? 'Prayer times API is working' : 'Prayer times API returned error',
        'url' => $prayerTimesUrl
    ];
} else {
    $results['checks']['prayer_times_api'] = [
        'status' => 'ERROR',
        'message' => 'Could not reach prayer times API',
        'url' => $prayerTimesUrl
    ];
}

echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
