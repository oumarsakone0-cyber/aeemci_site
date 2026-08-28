<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_programmes (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      titre VARCHAR(255) NOT NULL,
      slug VARCHAR(280) DEFAULT NULL,
      type VARCHAR(80) NOT NULL DEFAULT 'EVENEMENT',
      lieu VARCHAR(220) DEFAULT NULL,
      ville VARCHAR(120) DEFAULT NULL,
      resume TEXT DEFAULT NULL,
      description MEDIUMTEXT DEFAULT NULL,
      photos JSON DEFAULT NULL,
      statut VARCHAR(30) NOT NULL DEFAULT 'BROUILLON',
      date_debut DATE DEFAULT NULL,
      date_fin DATE DEFAULT NULL,
      heure_debut TIME DEFAULT NULL,
      heure_fin TIME DEFAULT NULL,
      organisateur VARCHAR(180) DEFAULT NULL,
      action_label VARCHAR(80) DEFAULT NULL,
      action_url VARCHAR(500) DEFAULT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      published_at DATETIME DEFAULT NULL,
      PRIMARY KEY (id),
      UNIQUE KEY uq_aeemci_programmes_slug (slug),
      KEY idx_aeemci_programmes_statut_date (statut, date_debut, created_at),
      KEY idx_aeemci_programmes_type (type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function inputJson(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: '{}', true);
    return is_array($data) ? $data : [];
}

function slugify(string $text): string {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $text));
    $text = trim($text, '-');
    return $text ?: 'programme';
}

function uniqueSlug(string $title, ?int $ignoreId = null): string {
    $base = slugify($title);
    $slug = $base;
    $i = 2;
    while (true) {
        $sql = 'SELECT id FROM aeemci_programmes WHERE slug = ?';
        $params = [$slug];
        if ($ignoreId) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $stmt = db()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        if (!$stmt->fetch()) return $slug;
        $slug = $base . '-' . $i++;
    }
}

function normalizeStatus(?string $status): string {
    $s = strtoupper(trim($status ?: 'BROUILLON'));
    $map = [
        'PUBLIÉ' => 'PUBLIE',
        'PUBLIÃ‰' => 'PUBLIE',
        'PUBLIÉE' => 'PUBLIE',
        'PUBLIÃ‰E' => 'PUBLIE',
        'PUBLIEE' => 'PUBLIE',
        'ACTIF' => 'PUBLIE',
        'ACTIVE' => 'PUBLIE',
        'ARCHIVÉ' => 'ARCHIVE',
        'ARCHIVÃ‰' => 'ARCHIVE',
        'ARCHIVÉE' => 'ARCHIVE',
        'ARCHIVÃ‰E' => 'ARCHIVE',
        'ARCHIVEE' => 'ARCHIVE',
    ];
    $s = $map[$s] ?? $s;
    return in_array($s, ['BROUILLON', 'PUBLIE', 'ARCHIVE'], true) ? $s : 'BROUILLON';
}

function cleanPhotos($photos): array {
    if (is_string($photos)) {
        $decoded = json_decode($photos, true);
        $photos = is_array($decoded) ? $decoded : ($photos ? [$photos] : []);
    }
    if (!is_array($photos)) return [];
    $out = [];
    foreach ($photos as $photo) {
        if (is_string($photo) && trim($photo) !== '') {
            $out[] = ['url' => trim($photo), 'preview' => trim($photo)];
        } elseif (is_array($photo)) {
            $url = trim((string)($photo['url'] ?? $photo['preview'] ?? ''));
            if ($url !== '') {
                $out[] = [
                    'url' => $url,
                    'preview' => $photo['preview'] ?? $url,
                    'filename' => $photo['filename'] ?? null,
                    'alt' => $photo['alt'] ?? null,
                ];
            }
        }
    }
    return array_slice($out, 0, 8);
}

function publicRow(array $row, bool $compact = false): array {
    $photos = json_decode($row['photos'] ?? '[]', true);
    if (!is_array($photos)) $photos = [];
    $image = '';
    if (!empty($photos[0])) $image = is_array($photos[0]) ? ($photos[0]['url'] ?? $photos[0]['preview'] ?? '') : $photos[0];
    $date = $row['date_debut'] ?: $row['created_at'];
    $payload = [
        'id' => (int)$row['id'],
        'titre' => $row['titre'],
        'title' => $row['titre'],
        'slug' => $row['slug'],
        'type' => $row['type'],
        'category' => $row['type'],
        'lieu' => $row['lieu'],
        'location' => $row['lieu'],
        'ville' => $row['ville'],
        'resume' => $row['resume'],
        'excerpt' => $row['resume'],
        'description' => $row['description'],
        'content' => $row['description'],
        'photos' => $photos,
        'image' => $image,
        'statut' => $row['statut'],
        'date_debut' => $row['date_debut'],
        'date_fin' => $row['date_fin'],
        'heure_debut' => $row['heure_debut'],
        'heure_fin' => $row['heure_fin'],
        'date' => $date,
        'organisateur' => $row['organisateur'],
        'action_label' => $row['action_label'],
        'action_url' => $row['action_url'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at'],
        'published_at' => $row['published_at'],
    ];
    if ($compact) {
        $payload['description'] = '';
        $payload['content'] = '';
    }
    return $payload;
}

function requireTitle(array $data): void {
    if (trim((string)($data['titre'] ?? $data['title'] ?? '')) === '') {
        respond(['success' => false, 'message' => 'Le titre est requis.'], 400);
    }
}

function cleanTime($value): ?string {
    $value = trim((string)($value ?? ''));
    if ($value === '') return null;
    return preg_match('/^\d{2}:\d{2}/', $value) ? substr($value, 0, 5) . ':00' : null;
}

function buildPayload(array $data, ?array $existing = null): array {
    $titre = trim((string)($data['titre'] ?? $data['title'] ?? $existing['titre'] ?? ''));
    $statut = normalizeStatus($data['statut'] ?? $existing['statut'] ?? 'BROUILLON');
    return [
        'titre' => $titre,
        'slug' => !empty($data['slug']) ? slugify((string)$data['slug']) : uniqueSlug($titre, $existing ? (int)$existing['id'] : null),
        'type' => strtoupper(trim((string)($data['type'] ?? $existing['type'] ?? 'EVENEMENT'))) ?: 'EVENEMENT',
        'lieu' => trim((string)($data['lieu'] ?? $data['location'] ?? $existing['lieu'] ?? '')),
        'ville' => trim((string)($data['ville'] ?? $existing['ville'] ?? '')),
        'resume' => trim((string)($data['resume'] ?? $data['excerpt'] ?? $existing['resume'] ?? '')),
        'description' => (string)($data['description'] ?? $data['content'] ?? $existing['description'] ?? ''),
        'photos' => json_encode(cleanPhotos($data['photos'] ?? $existing['photos'] ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'statut' => $statut,
        'date_debut' => ($data['date_debut'] ?? $existing['date_debut'] ?? null) ?: null,
        'date_fin' => ($data['date_fin'] ?? $existing['date_fin'] ?? null) ?: null,
        'heure_debut' => cleanTime($data['heure_debut'] ?? $existing['heure_debut'] ?? null),
        'heure_fin' => cleanTime($data['heure_fin'] ?? $existing['heure_fin'] ?? null),
        'organisateur' => trim((string)($data['organisateur'] ?? $existing['organisateur'] ?? 'AEEMCI')),
        'action_label' => trim((string)($data['action_label'] ?? $existing['action_label'] ?? 'Voir le programme')),
        'action_url' => trim((string)($data['action_url'] ?? $existing['action_url'] ?? '')),
        'published_at' => $statut === 'PUBLIE' ? ($existing['published_at'] ?? date('Y-m-d H:i:s')) : null,
    ];
}

try {
    ensureTable();
    $action = $_GET['action'] ?? 'latest';

    if ($action === 'latest' || $action === 'list') {
        $publicOnly = $action === 'latest' || !empty($_GET['public']);
        $limit = max(1, min(100, (int)($_GET['limit'] ?? ($action === 'latest' ? 4 : 100))));
        $offset = max(0, (int)($_GET['offset'] ?? 0));
        $where = [];
        $params = [];
        if ($publicOnly) {
            $where[] = "statut = 'PUBLIE'";
            if (empty($_GET['include_past'])) {
                $where[] = "(date_fin IS NULL OR date_fin >= CURDATE())";
            }
        } elseif (!empty($_GET['statut']) && $_GET['statut'] !== 'all') {
            $where[] = 'statut = :statut';
            $params[':statut'] = normalizeStatus($_GET['statut']);
        }
        if (!empty($_GET['search'])) {
            $where[] = '(titre LIKE :q OR resume LIKE :q OR description LIKE :q OR lieu LIKE :q OR ville LIKE :q)';
            $params[':q'] = '%' . $_GET['search'] . '%';
        }
        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = db()->prepare("SELECT * FROM aeemci_programmes {$sqlWhere} ORDER BY COALESCE(date_debut, DATE(created_at)) ASC, id DESC LIMIT {$limit} OFFSET {$offset}");
        $stmt->execute($params);
        $rows = array_map(fn($row) => publicRow($row, $action === 'latest'), $stmt->fetchAll());
        respond(['success' => true, 'data' => $rows, 'programmes' => $rows]);
    }

    if ($action === 'get' || $action === 'detail') {
        $id = (int)($_GET['id'] ?? 0);
        $slug = trim((string)($_GET['slug'] ?? ''));
        if (!$id && $slug === '') respond(['success' => false, 'message' => 'ID ou slug requis.'], 400);
        $stmt = $id ? db()->prepare('SELECT * FROM aeemci_programmes WHERE id = ? LIMIT 1') : db()->prepare('SELECT * FROM aeemci_programmes WHERE slug = ? LIMIT 1');
        $stmt->execute([$id ?: $slug]);
        $row = $stmt->fetch();
        if (!$row) respond(['success' => false, 'message' => 'Programme introuvable.'], 404);
        respond(['success' => true, 'data' => publicRow($row), 'programme' => publicRow($row)]);
    }

    if ($action === 'stats') {
        $row = db()->query("SELECT COUNT(*) total, SUM(statut='PUBLIE') publies, SUM(statut='BROUILLON') brouillons, SUM(statut='ARCHIVE') archives FROM aeemci_programmes")->fetch();
        respond(['success' => true, 'data' => array_map('intval', $row ?: [])]);
    }

    if ($action === 'create') {
        $data = inputJson();
        requireTitle($data);
        $p = buildPayload($data);
        $stmt = db()->prepare("INSERT INTO aeemci_programmes (titre, slug, type, lieu, ville, resume, description, photos, statut, date_debut, date_fin, heure_debut, heure_fin, organisateur, action_label, action_url, published_at, created_at) VALUES (:titre, :slug, :type, :lieu, :ville, :resume, :description, :photos, :statut, :date_debut, :date_fin, :heure_debut, :heure_fin, :organisateur, :action_label, :action_url, :published_at, NOW())");
        $stmt->execute($p);
        $id = (int)db()->lastInsertId();
        $stmt = db()->prepare('SELECT * FROM aeemci_programmes WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        respond(['success' => true, 'message' => 'Programme créé avec succès.', 'id' => $id, 'data' => publicRow($stmt->fetch())]);
    }

    if ($action === 'update') {
        $data = inputJson();
        $id = (int)($_GET['id'] ?? $data['id'] ?? 0);
        if (!$id) respond(['success' => false, 'message' => 'ID requis.'], 400);
        $stmt = db()->prepare('SELECT * FROM aeemci_programmes WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $existing = $stmt->fetch();
        if (!$existing) respond(['success' => false, 'message' => 'Programme introuvable.'], 404);
        $p = buildPayload($data, $existing);
        $p['id'] = $id;
        $stmt = db()->prepare("UPDATE aeemci_programmes SET titre=:titre, slug=:slug, type=:type, lieu=:lieu, ville=:ville, resume=:resume, description=:description, photos=:photos, statut=:statut, date_debut=:date_debut, date_fin=:date_fin, heure_debut=:heure_debut, heure_fin=:heure_fin, organisateur=:organisateur, action_label=:action_label, action_url=:action_url, published_at=:published_at, updated_at=NOW() WHERE id=:id");
        $stmt->execute($p);
        $stmt = db()->prepare('SELECT * FROM aeemci_programmes WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        respond(['success' => true, 'message' => 'Programme mis à jour avec succès.', 'data' => publicRow($stmt->fetch())]);
    }

    if ($action === 'delete') {
        $data = inputJson();
        $id = (int)($_GET['id'] ?? $data['id'] ?? 0);
        if (!$id) respond(['success' => false, 'message' => 'ID requis.'], 400);
        $stmt = db()->prepare('DELETE FROM aeemci_programmes WHERE id = ?');
        $stmt->execute([$id]);
        respond(['success' => true, 'message' => 'Programme supprimé avec succès.']);
    }

    respond(['success' => false, 'message' => 'Action inconnue.'], 404);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()], 500);
}
