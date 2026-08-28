<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

define('DB_HOST', getenv('AEEMCI_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('AEEMCI_DB_NAME') ?: 'capbvkkqah_aeemci');
define('DB_USER', getenv('AEEMCI_DB_USER') ?: 'capbvkkqah_aeemci');
define('DB_PASS', getenv('AEEMCI_DB_PASS') ?: '0Objectif-');

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
            PDO::ATTR_TIMEOUT => 8,
        ]
    );
    return $pdo;
}

function ensureTable(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_banco_inscriptions (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      reference VARCHAR(40) NOT NULL,
      nom VARCHAR(140) NOT NULL,
      prenom VARCHAR(180) NOT NULL,
      sous_comite VARCHAR(180) NOT NULL,
      contact VARCHAR(60) NOT NULL,
      statut VARCHAR(30) NOT NULL DEFAULT 'INSCRIT',
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_banco_reference (reference),
      KEY idx_banco_contact (contact),
      KEY idx_banco_sous_comite (sous_comite),
      KEY idx_banco_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function inputJson(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: '{}', true);
    return is_array($data) ? $data : [];
}

function cleanText($value): string {
    return trim(preg_replace('/\s+/u', ' ', (string)$value));
}

function normalizePhone($value): string {
    $raw = cleanText($value);
    $digits = preg_replace('/\D+/', '', $raw);
    return $digits ?: $raw;
}

function publicRow(array $row): array {
    return [
        'id' => (int)$row['id'],
        'reference' => $row['reference'],
        'nom' => $row['nom'],
        'prenom' => $row['prenom'],
        'nom_complet' => trim(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '')),
        'sous_comite' => $row['sous_comite'],
        'contact' => $row['contact'],
        'statut' => $row['statut'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at'],
    ];
}

function makeReference(): string {
    for ($i = 0; $i < 10; $i++) {
        $ref = 'BANCO' . date('Ymd') . str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        $stmt = db()->prepare('SELECT id FROM aeemci_banco_inscriptions WHERE reference = ? LIMIT 1');
        $stmt->execute([$ref]);
        if (!$stmt->fetch()) return $ref;
    }
    return 'BANCO' . date('YmdHis') . random_int(10, 99);
}

function stats(): array {
    $total = db()->query('SELECT COUNT(*) AS total FROM aeemci_banco_inscriptions')->fetch();
    $today = db()->query('SELECT COUNT(*) AS total FROM aeemci_banco_inscriptions WHERE DATE(created_at) = CURDATE()')->fetch();
    $committees = db()->query('SELECT COUNT(DISTINCT sous_comite) AS total FROM aeemci_banco_inscriptions')->fetch();
    $byCommittee = db()->query('SELECT sous_comite, COUNT(*) AS total FROM aeemci_banco_inscriptions GROUP BY sous_comite ORDER BY total DESC, sous_comite ASC LIMIT 12')->fetchAll();
    return [
        'total' => (int)($total['total'] ?? 0),
        'today' => (int)($today['total'] ?? 0),
        'sous_comites' => (int)($committees['total'] ?? 0),
        'by_sous_comite' => $byCommittee,
    ];
}

try {
    ensureTable();
    $action = $_GET['action'] ?? 'list';

    if ($action === 'inscrire') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        $data = inputJson();
        if (!$data) $data = $_POST;

        $nom = mb_strtoupper(cleanText($data['nom'] ?? ''), 'UTF-8');
        $prenom = cleanText($data['prenom'] ?? '');
        $sousComite = cleanText($data['sous_comite'] ?? $data['sousComite'] ?? '');
        $contact = normalizePhone($data['contact'] ?? '');

        if ($nom === '' || $prenom === '' || $sousComite === '' || $contact === '') {
            respond(['success' => false, 'message' => 'Nom, prénom, sous-comité et contact sont requis.'], 400);
        }

        $dup = db()->prepare('SELECT * FROM aeemci_banco_inscriptions WHERE contact = ? LIMIT 1');
        $dup->execute([$contact]);
        $existing = $dup->fetch();
        if ($existing) {
            respond([
                'success' => true,
                'already_registered' => true,
                'message' => 'Ce contact est déjà inscrit pour la sortie Banco.',
                'data' => publicRow($existing),
            ]);
        }

        $reference = makeReference();
        $stmt = db()->prepare('INSERT INTO aeemci_banco_inscriptions (reference, nom, prenom, sous_comite, contact, statut, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$reference, $nom, $prenom, $sousComite, $contact, 'INSCRIT']);
        $row = db()->prepare('SELECT * FROM aeemci_banco_inscriptions WHERE id = ? LIMIT 1');
        $row->execute([(int)db()->lastInsertId()]);
        respond(['success' => true, 'message' => 'Inscription Banco enregistrée.', 'data' => publicRow($row->fetch())]);
    }

    if ($action === 'stats') {
        respond(['success' => true, 'data' => stats()]);
    }

    if ($action === 'list') {
        $q = cleanText($_GET['search'] ?? $_GET['q'] ?? '');
        $limit = max(1, min(500, (int)($_GET['limit'] ?? 250)));
        $where = '';
        $params = [];
        if ($q !== '') {
            $where = 'WHERE nom LIKE ? OR prenom LIKE ? OR sous_comite LIKE ? OR contact LIKE ? OR reference LIKE ?';
            $like = '%' . $q . '%';
            $params = [$like, $like, $like, $like, $like];
        }
        $stmt = db()->prepare("SELECT * FROM aeemci_banco_inscriptions $where ORDER BY created_at DESC, id DESC LIMIT $limit");
        $stmt->execute($params);
        $rows = array_map('publicRow', $stmt->fetchAll());
        respond(['success' => true, 'data' => $rows, 'stats' => stats()]);
    }

    respond(['success' => false, 'message' => 'Action inconnue.'], 400);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()], 500);
}