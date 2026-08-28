<?php
declare(strict_types=1);

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

define('DB_HOST', getenv('AEEMCI_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('AEEMCI_DB_NAME') ?: 'capbvkkqah_aeemci');
define('DB_USER', getenv('AEEMCI_DB_USER') ?: 'capbvkkqah_aeemci');
define('DB_PASS', getenv('AEEMCI_DB_PASS') ?: '0Objectif-');
define('APP_TABLE', 'aeemci_concours_applications_islamiques_2026');

function respond(array $payload, int $status = 200): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 10,
        ]
    );
    return $pdo;
}

function inputJson(): array {
    $data = json_decode(file_get_contents('php://input') ?: '{}', true);
    return is_array($data) ? $data : [];
}

function clean(mixed $value): string {
    return trim((string)$value);
}

function ensureSchema(): void {
    db()->exec("
        CREATE TABLE IF NOT EXISTS " . APP_TABLE . " (
          id INT UNSIGNED NOT NULL AUTO_INCREMENT,
          reference VARCHAR(30) NOT NULL,
          responsable_nom VARCHAR(120) NOT NULL,
          responsable_prenom VARCHAR(160) NOT NULL,
          contact VARCHAR(40) NOT NULL,
          email VARCHAR(180) DEFAULT NULL,
          titre_projet VARCHAR(220) NOT NULL,
          type_projet VARCHAR(80) DEFAULT NULL,
          equipe TINYINT(1) NOT NULL DEFAULT 0,
          nom_equipe VARCHAR(160) DEFAULT NULL,
          membres_count TINYINT UNSIGNED NOT NULL DEFAULT 1,
          public_cible VARCHAR(180) DEFAULT NULL,
          technologies VARCHAR(240) DEFAULT NULL,
          lien_demo VARCHAR(500) DEFAULT NULL,
          resume TEXT NOT NULL,
          probleme_resolu TEXT DEFAULT NULL,
          presentation_url VARCHAR(700) NOT NULL,
          presentation_id VARCHAR(240) DEFAULT NULL,
          presentation_name VARCHAR(240) DEFAULT NULL,
          statut VARCHAR(30) NOT NULL DEFAULT 'SOUMIS',
          note DECIMAL(5,2) DEFAULT NULL,
          observation TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
          updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (id),
          UNIQUE KEY uq_app_reference (reference),
          KEY idx_app_statut (statut),
          KEY idx_app_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

function nextReference(): string {
    $stmt = db()->query("SELECT MAX(CAST(SUBSTRING(reference, 8) AS UNSIGNED)) FROM " . APP_TABLE . " WHERE reference LIKE 'APP26%'");
    $next = ((int)$stmt->fetchColumn()) + 1;
    return 'APP26' . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
}

function listRows(): void {
    $stmt = db()->query("SELECT * FROM " . APP_TABLE . " ORDER BY id DESC");
    $rows = $stmt->fetchAll();
    $stats = [
        'total' => count($rows),
        'equipes' => 0,
        'individuels' => 0,
        'soumis' => 0,
    ];
    foreach ($rows as $row) {
        if ((int)$row['equipe'] === 1) $stats['equipes']++;
        else $stats['individuels']++;
        if (($row['statut'] ?? '') === 'SOUMIS') $stats['soumis']++;
    }
    respond(['success' => true, 'data' => $rows, 'stats' => $stats]);
}

function createRow(): void {
    $data = inputJson();
    $required = ['responsable_nom', 'responsable_prenom', 'contact', 'titre_projet', 'resume', 'presentation_url'];
    foreach ($required as $field) {
        if (clean($data[$field] ?? '') === '') {
            respond(['success' => false, 'message' => "Le champ {$field} est requis."], 422);
        }
    }

    $reference = nextReference();
    $isTeam = !empty($data['equipe']) ? 1 : 0;
    $membersCount = max(1, min(20, (int)($data['membres_count'] ?? ($isTeam ? 2 : 1))));

    $stmt = db()->prepare("
        INSERT INTO " . APP_TABLE . "
          (reference, responsable_nom, responsable_prenom, contact, email, titre_projet, type_projet,
           equipe, nom_equipe, membres_count, public_cible, technologies, lien_demo, resume,
           probleme_resolu, presentation_url, presentation_id, presentation_name)
        VALUES
          (:reference, :responsable_nom, :responsable_prenom, :contact, :email, :titre_projet, :type_projet,
           :equipe, :nom_equipe, :membres_count, :public_cible, :technologies, :lien_demo, :resume,
           :probleme_resolu, :presentation_url, :presentation_id, :presentation_name)
    ");
    $stmt->execute([
        'reference' => $reference,
        'responsable_nom' => strtoupper(clean($data['responsable_nom'] ?? '')),
        'responsable_prenom' => clean($data['responsable_prenom'] ?? ''),
        'contact' => preg_replace('/\s+/', '', clean($data['contact'] ?? '')),
        'email' => clean($data['email'] ?? ''),
        'titre_projet' => clean($data['titre_projet'] ?? ''),
        'type_projet' => clean($data['type_projet'] ?? ''),
        'equipe' => $isTeam,
        'nom_equipe' => clean($data['nom_equipe'] ?? ''),
        'membres_count' => $membersCount,
        'public_cible' => clean($data['public_cible'] ?? ''),
        'technologies' => clean($data['technologies'] ?? ''),
        'lien_demo' => clean($data['lien_demo'] ?? ''),
        'resume' => clean($data['resume'] ?? ''),
        'probleme_resolu' => clean($data['probleme_resolu'] ?? ''),
        'presentation_url' => clean($data['presentation_url'] ?? ''),
        'presentation_id' => clean($data['presentation_id'] ?? ''),
        'presentation_name' => clean($data['presentation_name'] ?? ''),
    ]);

    $row = db()->prepare("SELECT * FROM " . APP_TABLE . " WHERE reference = ? LIMIT 1");
    $row->execute([$reference]);
    respond(['success' => true, 'message' => 'Candidature enregistrée avec succès.', 'data' => $row->fetch()]);
}

function updateStatus(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    $status = strtoupper(clean($data['statut'] ?? ''));
    $allowed = ['SOUMIS', 'PRESELECTIONNE', 'RETENU', 'REFUSE'];
    if ($id <= 0 || !in_array($status, $allowed, true)) {
        respond(['success' => false, 'message' => 'Statut invalide.'], 422);
    }
    $stmt = db()->prepare("UPDATE " . APP_TABLE . " SET statut = ?, note = ?, observation = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([
        $status,
        isset($data['note']) && $data['note'] !== '' ? (float)$data['note'] : null,
        clean($data['observation'] ?? ''),
        $id,
    ]);
    respond(['success' => true]);
}

try {
    ensureSchema();
    $action = clean($_GET['action'] ?? 'list');
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') listRows();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') createRow();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_status') updateStatus();
    respond(['success' => false, 'message' => 'Action non supportée.'], 404);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur.', 'details' => $e->getMessage()], 500);
}
