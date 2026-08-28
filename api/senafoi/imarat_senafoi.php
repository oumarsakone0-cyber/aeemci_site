<?php
/**
 * API IMARAT — SENAFOI 2026
 *
 * Inscription gratuite des membres de l'Imarat du séminaire :
 * Amir, 1er Vice-Amir, 2ème Vice-Amir et Vice-Amirate.
 *
 * Table dédiée : aucune table existante n'est modifiée.
 *
 * Chaque fonction a son propre préfixe de matricule, ce qui rend la fonction
 * lisible directement sur le badge :
 *   AMR26SNF001  Amir
 *   VA126SNF001  1er Vice-Amir
 *   VA226SNF001  2ème Vice-Amir
 *   VAM26SNF001  Vice-Amirate
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

/** Valeur canonique stockée en base => préfixe du matricule. */
$fonctions = [
    'AMIR' => 'AMR26SNF',
    '1ER VICE AMIR' => 'VA126SNF',
    '2EME VICE AMIR' => 'VA226SNF',
    'VICE AMIRATE' => 'VAM26SNF',
];

/**
 * Formes acceptées en entrée, réduites à leur squelette alphanumérique.
 * On tolère les tirets, les accents et les abréviations courantes : le
 * formulaire public, l'admin et un éventuel import ne les écrivent jamais
 * exactement de la même façon.
 */
$alias = [
    'AMIR' => 'AMIR',
    'LAMIR' => 'AMIR',
    '1ERVICEAMIR' => '1ER VICE AMIR',
    '1EREVICEAMIR' => '1ER VICE AMIR',
    '1REVICEAMIR' => '1ER VICE AMIR',
    '1VICEAMIR' => '1ER VICE AMIR',
    'PREMIERVICEAMIR' => '1ER VICE AMIR',
    '2EMEVICEAMIR' => '2EME VICE AMIR',
    '2EVICEAMIR' => '2EME VICE AMIR',
    '2NDVICEAMIR' => '2EME VICE AMIR',
    '2VICEAMIR' => '2EME VICE AMIR',
    'DEUXIEMEVICEAMIR' => '2EME VICE AMIR',
    'VICEAMIRATE' => 'VICE AMIRATE',
    'VICEAMIRAT' => 'VICE AMIRATE',
    'AMIRATE' => 'VICE AMIRATE',
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
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_imarat_senafoi (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      matricule VARCHAR(30) NOT NULL,
      fonction VARCHAR(40) NOT NULL,
      nom VARCHAR(120) NOT NULL,
      prenom VARCHAR(160) NOT NULL,
      contact VARCHAR(40) NOT NULL,
      photo VARCHAR(600) DEFAULT NULL,
      photo_id VARCHAR(220) DEFAULT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_imarat_matricule (matricule),
      KEY idx_imarat_fonction (fonction)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

/** Retire les accents et tout ce qui n'est ni lettre ni chiffre. */
function squelette(string $valeur): string {
    $v = str_replace(
        ['à','á','â','ã','ä','è','é','ê','ë','ì','í','î','ï','ò','ó','ô','õ','ö','ù','ú','û','ü','ç',
         'À','Á','Â','Ã','Ä','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ç'],
        ['a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','c',
         'A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C'],
        $valeur
    );
    $v = mb_strtoupper($v, 'UTF-8');
    return preg_replace('/[^A-Z0-9]/', '', $v) ?? '';
}

function normalizeFonction(string $fonction): string {
    global $fonctions, $alias;
    $brut = clean($fonction);
    if ($brut === '') {
        respond(['success' => false, 'message' => 'Fonction requise.'], 422);
    }

    $cle = squelette($brut);

    // Correspondance directe avec une valeur canonique
    foreach (array_keys($fonctions) as $canonique) {
        if (squelette($canonique) === $cle) return $canonique;
    }
    // Sinon, l'une des formes tolérées
    if (isset($alias[$cle])) return $alias[$cle];

    respond([
        'success' => false,
        'message' => 'Fonction invalide : choisissez Amir, 1er Vice-Amir, 2eme Vice-Amir ou Vice-Amirate.',
    ], 422);
}

function nextMatricule(string $fonction): string {
    global $fonctions;
    $prefix = $fonctions[$fonction];
    // La position vient d'une constante interne, jamais d'une saisie.
    $position = strlen($prefix) + 1;
    $stmt = db()->prepare("
        SELECT MAX(CAST(SUBSTRING(matricule, {$position}) AS UNSIGNED))
        FROM aeemci_imarat_senafoi
        WHERE matricule LIKE ?
    ");
    $stmt->execute([$prefix . '%']);
    $next = ((int)$stmt->fetchColumn()) + 1;
    return $prefix . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
}

function listRows(): void {
    $fonction = clean($_GET['fonction'] ?? '');
    $where = '';
    $params = [];
    if ($fonction !== '') {
        $where = 'WHERE fonction = ?';
        $params[] = normalizeFonction($fonction);
    }
    $stmt = db()->prepare("SELECT * FROM aeemci_imarat_senafoi {$where} ORDER BY fonction ASC, id DESC");
    $stmt->execute($params);
    respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

function stats(): void {
    global $fonctions;
    $rows = db()->query("
        SELECT fonction, COUNT(*) AS total
        FROM aeemci_imarat_senafoi
        GROUP BY fonction ORDER BY fonction ASC
    ")->fetchAll();
    $total = (int)db()->query("SELECT COUNT(*) FROM aeemci_imarat_senafoi")->fetchColumn();
    $avecPhoto = (int)db()->query("
        SELECT COUNT(*) FROM aeemci_imarat_senafoi
        WHERE photo IS NOT NULL AND photo <> ''
    ")->fetchColumn();

    $parFonction = [];
    foreach (array_keys($fonctions) as $f) $parFonction[$f] = 0;
    foreach ($rows as $r) $parFonction[$r['fonction']] = (int)$r['total'];

    respond([
        'success' => true,
        'data' => [
            'total' => $total,
            'avec_photo' => $avecPhoto,
            'par_fonction' => $parFonction,
            'fonctions' => $rows,
        ],
    ]);
}

function getByMatricule(string $matricule): array {
    $stmt = db()->prepare("SELECT * FROM aeemci_imarat_senafoi WHERE matricule = ?");
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
    $fonction = normalizeFonction($data['fonction'] ?? '');

    if ($nom === '' || $prenom === '' || $contact === '') {
        respond(['success' => false, 'message' => 'Nom, prenom et contact sont requis.'], 422);
    }

    // Garde-fou anti-doublon : même nom, même prénom, même contact
    $doublon = db()->prepare("
        SELECT matricule FROM aeemci_imarat_senafoi
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

    // L'Amir est unique : on refuse un second enregistrement.
    if ($fonction === 'AMIR') {
        $dejaAmir = db()->query("SELECT matricule FROM aeemci_imarat_senafoi WHERE fonction = 'AMIR' LIMIT 1")->fetchColumn();
        if ($dejaAmir) {
            respond([
                'success' => false,
                'message' => "Un Amir est deja enregistre sous le matricule {$dejaAmir}. Supprimez-le d'abord si vous devez le remplacer.",
            ], 409);
        }
    }

    $matricule = nextMatricule($fonction);
    $stmt = db()->prepare("INSERT INTO aeemci_imarat_senafoi
      (matricule, fonction, nom, prenom, contact, photo, photo_id)
      VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $matricule,
        $fonction,
        mb_strtoupper($nom, 'UTF-8'),
        $prenom,
        $contact,
        clean($data['photo'] ?? ''),
        clean($data['photo_id'] ?? ''),
    ]);

    respond([
        'success' => true,
        'message' => 'Membre de l\'Imarat enregistre gratuitement.',
        'data' => getByMatricule($matricule),
    ]);
}

function deleteRow(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) respond(['success' => false, 'message' => 'ID requis.'], 422);
    $stmt = db()->prepare("DELETE FROM aeemci_imarat_senafoi WHERE id = ?");
    $stmt->execute([$id]);
    respond(['success' => true, 'deleted' => $stmt->rowCount()]);
}

try {
    ensureSchema();
    $action = clean($_GET['action'] ?? 'list');
    if ($action === 'list') listRows();
    if ($action === 'stats') stats();
    if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') createRow();
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') deleteRow();
    respond(['success' => false, 'message' => 'Action inconnue.'], 404);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur.', 'detail' => $e->getMessage()], 500);
}
