<?php
// =============================================================================
// sms_api.php â€” API autonome d'envoi de SMS via KYA SMS
// =============================================================================
// MÃ©thode  : POST
// Body JSON : { "phone": "0789977929", "message": "Votre texte ici" }
//             ou multi-numÃ©ros : { "phone": ["0789977929", "0708112233"], "message": "..." }
// RÃ©ponse  : { "success": true/false, "sms_log_id": int, "details": {...} }
// =============================================================================

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-SMS-Secret');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "MÃ©thode non autorisÃ©e"]);
    exit();
}

// =============================================================================
// CONFIGURATION
// =============================================================================
define('KYASMS_API_KEY',  'kyasmsd2ccf32b4aa62311eee9da3051b60bba18bb5236249abf9d1c5e5e873f');
define('KYASMS_BASE_URL', 'https://route.kyasms.net/api/v3/sms/send');
define('KYASMS_SENDER',   'AEEMCI');

// ClÃ© secrÃ¨te pour sÃ©curiser les appels internes (Ã  dÃ©finir aussi dans verify_payment.php)
define('SMS_INTERNAL_SECRET', 'aeemci_sms_secret_2026');

// =============================================================================
// VÃ‰RIFICATION CLÃ‰ SECRÃˆTE (appels internes uniquement)
// =============================================================================
$headerSecret = $_SERVER['HTTP_X_SMS_SECRET'] ?? '';
if ($headerSecret !== SMS_INTERNAL_SECRET) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "AccÃ¨s non autorisÃ©"]);
    exit();
}

// =============================================================================
// LECTURE & VALIDATION DU BODY
// =============================================================================
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['phone']) || empty($input['message'])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ParamÃ¨tres requis : phone (string|array) et message (string)"
    ]);
    exit();
}

$matricule = $input['matricule'] ?? null;  // optionnel, pour le log en base
$message   = trim($input['message']);

if (strlen($message) === 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Le message ne peut pas Ãªtre vide"]);
    exit();
}

// =============================================================================
// NORMALISATION NUMÃ‰ROS
// =============================================================================
$rawPhones        = is_array($input['phone']) ? $input['phone'] : [$input['phone']];
$normalizedPhones = [];

foreach ($rawPhones as $p) {
    $num = preg_replace('/\D/', '', $p);
    if (!str_starts_with($num, '225')) {
        $num = '225' . substr($num, -10);
    }
    $normalizedPhones[] = $num;
}

// =============================================================================
// LOG EN BASE (optionnel â€” si la table existe)
// =============================================================================
$insertedId = null;

try {
    require_once 'database.php';
    $db = new Database();

    $db->query("
        INSERT INTO aeemci_sms_messages
            (matricule, phone_numbers, message, status, created_at)
        VALUES (?, ?, ?, 'queued', NOW())
    ", [
        $matricule,
        json_encode($normalizedPhones),
        $message
    ]);

    $lastRow    = $db->query("SELECT LAST_INSERT_ID() AS lid");
    $insertedId = $lastRow[0]['lid'] ?? null;

} catch (Exception $e) {
    // La table n'existe pas encore ou la BDD est indisponible â†’ on continue quand mÃªme
    error_log("âš ï¸ SMS log BDD indisponible : " . $e->getMessage());
}

// =============================================================================
// ENVOI VIA KYA SMS (numÃ©ro par numÃ©ro)
// =============================================================================
$results = [];
$allOk   = true;

foreach ($normalizedPhones as $phone) {
    $payload = json_encode([
        "from"    => KYASMS_SENDER,
        "to"      => $phone,
        "type"    => "text",
        "message" => $message
    ]);

    $ch = curl_init(KYASMS_BASE_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "APIKEY: " . KYASMS_API_KEY
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);
    $success = ($response !== false && empty($curlError) && $httpCode < 400);

    if ($success) {
        error_log("âœ… SMS KYA envoyÃ© â†’ $phone (HTTP $httpCode)");
    } else {
        error_log("âŒ Ã‰chec SMS KYA â†’ $phone (HTTP $httpCode) : $response | cURL: $curlError");
        $allOk = false;
    }

    $results[$phone] = [
        "status"     => $success ? "sent" : "failed",
        "http_code"  => $httpCode,
        "response"   => $decoded ?? $response,
        "curl_error" => $curlError ?: null
    ];
}

// =============================================================================
// MISE Ã€ JOUR STATUT EN BASE
// =============================================================================
if ($insertedId && isset($db)) {
    $finalStatus = $allOk ? 'sent' : ($results ? 'partial_failure' : 'failed');
    try {
        $db->query(
            "UPDATE aeemci_sms_messages SET status = ?, updated_at = NOW() WHERE id = ?",
            [$finalStatus, $insertedId]
        );
    } catch (Exception $e) {
        error_log("âš ï¸ Impossible de mettre Ã  jour le statut SMS : " . $e->getMessage());
    }
}

// =============================================================================
// RÃ‰PONSE
// =============================================================================
http_response_code($allOk ? 200 : 207); // 207 = Multi-Status (certains ont Ã©chouÃ©)

echo json_encode([
    "success"        => $allOk,
    "sms_log_id"     => $insertedId,
    "phones_envoyes" => $normalizedPhones,
    "tous_envoyes"   => $allOk,
    "details"        => $results
]);
