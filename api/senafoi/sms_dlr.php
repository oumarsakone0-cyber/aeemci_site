<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    $payload = $_REQUEST;
}

$line = json_encode([
    'received_at' => date('c'),
    'method' => $_SERVER['REQUEST_METHOD'] ?? '',
    'query' => $_GET,
    'payload' => $payload,
    'raw' => $raw,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

file_put_contents(__DIR__ . '/sms_dlr_log.txt', $line . PHP_EOL, FILE_APPEND);

echo json_encode(['success' => true]);
