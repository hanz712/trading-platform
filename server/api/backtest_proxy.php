<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/api_config.php';

$symbol = 'XAU/USD';
$interval = $_GET['interval'] ?? '1h';

$allowed_intervals = ['5min', '15min', '30min', '1h', '4h', '1day'];
$tf = in_array($interval, $allowed_intervals) ? $interval : '1h';

if (!defined('XAUUSD_API_KEY') || empty(XAUUSD_API_KEY)) {
    echo json_encode(['status' => 'error', 'message' => 'API Key belum dikonfigurasi.']);
    exit;
}

$url = "https://api.twelvedata.com/time_series?symbol=" . urlencode($symbol) . "&interval=" . $tf . "&outputsize=120&apikey=" . XAUUSD_API_KEY;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error || !$response || $http_code !== 200) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal koneksi ke server luar (HTTP Code: ' . $http_code . ').']);
    exit;
}

// Kirim langsung data JSON dari Twelve Data ke frontend
echo $response;
exit;
?>