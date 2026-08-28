<?php
declare(strict_types=1);

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function respond(array $payload, int $status = 200): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
}

if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
    respond(['success' => false, 'message' => 'Aucun fichier reçu.'], 422);
}

$file = $_FILES['file'];
if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
    respond(['success' => false, 'message' => 'Erreur pendant le transfert du fichier.'], 422);
}

$maxBytes = 12 * 1024 * 1024;
if ((int)$file['size'] > $maxBytes) {
    respond(['success' => false, 'message' => 'Le fichier ne doit pas dépasser 12 Mo.'], 422);
}

$originalName = (string)($file['name'] ?? 'presentation.pdf');
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$allowed = ['pdf', 'ppt', 'pptx'];
if (!in_array($extension, $allowed, true)) {
    respond(['success' => false, 'message' => 'Format non autorisé. Utilise PDF, PPT ou PPTX.'], 422);
}

$safeBase = preg_replace('/[^a-z0-9_-]+/i', '_', pathinfo($originalName, PATHINFO_FILENAME));
$safeBase = trim((string)$safeBase, '_') ?: 'presentation';
$fileName = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '_' . $safeBase . '.' . $extension;

$targetDir = dirname(__DIR__) . '/cloud/medias/concours/applications_islamiques';
if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true)) {
    respond(['success' => false, 'message' => 'Impossible de créer le dossier de stockage.'], 500);
}

$targetPath = $targetDir . '/' . $fileName;
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    respond(['success' => false, 'message' => 'Impossible d’enregistrer le fichier.'], 500);
}

$url = 'https://api.aeemci-ce.ci/cloud/medias/concours/applications_islamiques/' . rawurlencode($fileName);
respond([
    'success' => true,
    'url' => $url,
    'public_id' => 'concours/applications_islamiques/' . $fileName,
    'name' => $originalName,
]);
