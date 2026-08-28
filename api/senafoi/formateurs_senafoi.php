<?php
/**
 * API FORMATEURS & IMAMS — SENAFOI 2026
 *
 * Inscription gratuite des formateurs et des imams du séminaire.
 * Table dédiée : aucune table existante n'est modifiée.
 *
 * Matricules : FRM26SNF001 pour les formateurs, IMA26SNF001 pour les imams.
 * Les deux séries sont indépendantes, ce qui rend le matricule lisible d'un
 * coup d'œil sur un badge.
 *
 * Les photos sont déposées par le front sur le cloud AEEMCI dans
 * senafoi/photos, le même dossier que les séminaristes, SR et CCR.
 */

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

/** Les deux seules catégories acceptées, avec leur préfixe de matricule. */
$categories = [
    'FORMATEUR' => 'FRM26SNF',
    'IMAM' => 'IMA26SNF',
];

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
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_formateurs_senafoi (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      matricule VARCHAR(30) NOT NULL,
      categorie VARCHAR(20) NOT NULL,
      nom VARCHAR(120) NOT NULL,
      prenom VARCHAR(160) NOT NULL,
      contact VARCHAR(40) NOT NULL,
      photo VARCHAR(600) DEFAULT NULL,
      photo_id VARCHAR(220) DEFAULT NULL,
      specialite VARCHAR(190) DEFAULT NULL,
      convoi TINYINT(1) NOT NULL DEFAULT 0,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_formateur_matricule (matricule),
      KEY idx_formateur_categorie (categorie)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Migration pour les installations antérieures à la gestion du convoi
    try {
        $col = db()->query("SHOW COLUMNS FROM aeemci_formateurs_senafoi LIKE 'convoi'")->fetch();
        if (!$col) {
            db()->exec("ALTER TABLE aeemci_formateurs_senafoi ADD convoi TINYINT(1) NOT NULL DEFAULT 0 AFTER specialite");
        }
    } catch (Throwable $e) {
        // Non bloquant : la liste doit rester consultable
    }
}

/**
 * Normalise la catégorie. On retire les accents en majuscules comme en
 * minuscules avant de comparer : strtoupper() seul ne transforme pas les
 * caractères accentués et laisserait passer des valeurs non reconnues.
 */
function normalizeCategorie(string $categorie): string {
    global $categories;
    $value = clean($categorie);
    $value = str_replace(
        ['à','á','â','ã','ä','è','é','ê','ë','ì','í','î','ï','ò','ó','ô','õ','ö','ù','ú','û','ü','ç',
         'À','Á','Â','Ã','Ä','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ç'],
        ['a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','c',
         'A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C'],
        $value
    );
    $value = mb_strtoupper($value, 'UTF-8');

    if ($value === '') {
        respond(['success' => false, 'message' => 'Categorie requise.'], 422);
    }
    if (!array_key_exists($value, $categories)) {
        respond(['success' => false, 'message' => 'Categorie invalide : choisissez Formateur ou Imam.'], 422);
    }
    return $value;
}

function nextMatricule(string $categorie): string {
    global $categories;
    $prefix = $categories[$categorie];
    // La position vient d'une constante interne, jamais d'une saisie : on
    // l'injecte directement car MySQL n'accepte pas toujours un paramètre lié
    // comme second argument de SUBSTRING().
    $position = strlen($prefix) + 1;
    $stmt = db()->prepare("
        SELECT MAX(CAST(SUBSTRING(matricule, {$position}) AS UNSIGNED))
        FROM aeemci_formateurs_senafoi
        WHERE matricule LIKE ?
    ");
    $stmt->execute([$prefix . '%']);
    $next = ((int)$stmt->fetchColumn()) + 1;
    return $prefix . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
}

function listRows(): void {
    $categorie = clean($_GET['categorie'] ?? '');
    $where = '';
    $params = [];
    if ($categorie !== '') {
        $where = 'WHERE categorie = ?';
        $params[] = normalizeCategorie($categorie);
    }
    $stmt = db()->prepare("SELECT * FROM aeemci_formateurs_senafoi {$where} ORDER BY categorie ASC, id DESC");
    $stmt->execute($params);
    respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

function stats(): void {
    $rows = db()->query("
        SELECT categorie, COUNT(*) AS total
        FROM aeemci_formateurs_senafoi
        GROUP BY categorie ORDER BY categorie ASC
    ")->fetchAll();
    $total = (int)db()->query("SELECT COUNT(*) FROM aeemci_formateurs_senafoi")->fetchColumn();
    $avecPhoto = (int)db()->query("
        SELECT COUNT(*) FROM aeemci_formateurs_senafoi
        WHERE photo IS NOT NULL AND photo <> ''
    ")->fetchColumn();
    $convoi = (int)db()->query("SELECT COUNT(*) FROM aeemci_formateurs_senafoi WHERE convoi = 1")->fetchColumn();

    $parCategorie = ['FORMATEUR' => 0, 'IMAM' => 0];
    foreach ($rows as $r) $parCategorie[$r['categorie']] = (int)$r['total'];

    respond([
        'success' => true,
        'data' => [
            'total' => $total,
            'avec_photo' => $avecPhoto,
            'convoi' => $convoi,
            'formateurs' => $parCategorie['FORMATEUR'] ?? 0,
            'imams' => $parCategorie['IMAM'] ?? 0,
            'categories' => $rows,
        ],
    ]);
}

function getByMatricule(string $matricule): array {
    $stmt = db()->prepare("SELECT * FROM aeemci_formateurs_senafoi WHERE matricule = ?");
    $stmt->execute([$matricule]);
    $row = $stmt->fetch();
    if (!$row) respond(['success' => false, 'message' => 'Inscription introuvable.'], 404);
    return $row;
}

function createRow(): void {
    $data = inputJson();
    $nom = clean($data['nom'] ?? '');
    $prenom = clean($data['prenom'] ?? '');
    $contact = preg_replace('/\s+/', '', clean($data['contact'] ?? ''));
    $categorie = normalizeCategorie($data['categorie'] ?? '');

    if ($nom === '' || $prenom === '' || $contact === '') {
        respond(['success' => false, 'message' => 'Nom, prenom et contact sont requis.'], 422);
    }

    // Garde-fou anti-doublon : même nom, même prénom, même contact
    $doublon = db()->prepare("
        SELECT matricule FROM aeemci_formateurs_senafoi
        WHERE UPPER(nom) = ? AND UPPER(prenom) = ? AND contact = ?
        LIMIT 1
    ");
    $doublon->execute([mb_strtoupper($nom, 'UTF-8'), mb_strtoupper($prenom, 'UTF-8'), $contact]);
    if ($existant = $doublon->fetchColumn()) {
        respond([
            'success' => false,
            'message' => "Cette personne est deja inscrite sous le matricule {$existant}.",
        ], 409);
    }

    $matricule = nextMatricule($categorie);
    $stmt = db()->prepare("INSERT INTO aeemci_formateurs_senafoi
      (matricule, categorie, nom, prenom, contact, photo, photo_id, specialite)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $matricule,
        $categorie,
        mb_strtoupper($nom, 'UTF-8'),
        $prenom,
        $contact,
        clean($data['photo'] ?? ''),
        clean($data['photo_id'] ?? ''),
        clean($data['specialite'] ?? '') ?: null,
    ]);

    $libelle = $categorie === 'IMAM' ? 'Imam' : 'Formateur';
    respond([
        'success' => true,
        'message' => "{$libelle} enregistre gratuitement.",
        'data' => getByMatricule($matricule),
    ]);
}

/** Coche ou décoche un formateur pour le convoi au départ d'Abidjan. */
function setConvoi(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) respond(['success' => false, 'message' => 'ID requis.'], 422);
    $convoi = !empty($data['convoi']) ? 1 : 0;

    $stmt = db()->prepare("UPDATE aeemci_formateurs_senafoi SET convoi = ? WHERE id = ?");
    $stmt->execute([$convoi, $id]);

    respond(['success' => true, 'id' => $id, 'convoi' => $convoi]);
}

function deleteRow(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) respond(['success' => false, 'message' => 'ID requis.'], 422);
    $stmt = db()->prepare("DELETE FROM aeemci_formateurs_senafoi WHERE id = ?");
    $stmt->execute([$id]);
    respond(['success' => true, 'deleted' => $stmt->rowCount()]);
}

try {
    ensureSchema();
    $action = clean($_GET['action'] ?? 'list');
    if ($action === 'list') listRows();
    if ($action === 'stats') stats();
    if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') createRow();
    if ($action === 'set_convoi' && $_SERVER['REQUEST_METHOD'] === 'POST') setConvoi();
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') deleteRow();
    respond(['success' => false, 'message' => 'Action inconnue.'], 404);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur.', 'detail' => $e->getMessage()], 500);
}
