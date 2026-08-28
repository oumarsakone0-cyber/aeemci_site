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

// Liste alignee sur le formulaire public (CommissionSenafoiMembres.vue).
// Toute valeur ajoutee au <select> doit egalement figurer ici, sinon
// l'inscription est rejetee avec "Commission invalide".
$commissions = [
    // Amirat
    'AMIR', '1ER VICE-AMIR', '2EME VICE-AMIR', 'VICE-AMIRATE',
    // Commissions operationnelles
    'ADMINISTRATION', 'CADRE DE VIE', 'COMMUNICATION', 'FINANCE',
    'FORMATION', 'INFORMATIQUE', 'PEPINIERE', 'PROTOCOLE',
    'RESTAURATION', 'SANTE', 'SECURITE', 'TRANSPORT',
    // Management / organisation
    'MANAGER GENERAL', 'MANAGER GENERAL ADJOINT', 'PCO', 'PCO-A',
    // Conseil d'administration et conseil consultatif
    'PCA', 'MEMBRE DU CA', 'CCC', 'CCC1', 'CCC2',
    'MEMBRE DU CONSEIL CONSULTATIF',
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
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_commissions_senafoi (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      matricule VARCHAR(30) NOT NULL,
      nom VARCHAR(120) NOT NULL,
      prenom VARCHAR(160) NOT NULL,
      photo VARCHAR(600) DEFAULT NULL,
      photo_id VARCHAR(220) DEFAULT NULL,
      contact VARCHAR(40) NOT NULL,
      commission VARCHAR(120) NOT NULL,
      depart_abidjan TINYINT(1) NOT NULL DEFAULT 0,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_comm_senafoi_matricule (matricule),
      KEY idx_comm_senafoi_commission (commission)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $cols = db()->query("SHOW COLUMNS FROM aeemci_commissions_senafoi LIKE 'depart_abidjan'")->fetchAll();
    if (!$cols) {
        db()->exec("ALTER TABLE aeemci_commissions_senafoi ADD depart_abidjan TINYINT(1) NOT NULL DEFAULT 0 AFTER commission");
    }
}

function normalizeCommission(string $commission): string {
    global $commissions;
    $value = clean($commission);

    // On retire d'abord les accents (minuscules ET majuscules) : strtoupper()
    // est octet par octet et ne transforme pas "e" en "E", ce qui faisait
    // echouer des valeurs comme "2eme Vice-Amir".
    $accents = [
        'á','à','â','ä','ã','å','Á','À','Â','Ä','Ã','Å',
        'é','è','ê','ë','É','È','Ê','Ë',
        'í','ì','î','ï','Í','Ì','Î','Ï',
        'ó','ò','ô','ö','õ','Ó','Ò','Ô','Ö','Õ',
        'ú','ù','û','ü','Ú','Ù','Û','Ü',
        'ç','Ç','ñ','Ñ',
    ];
    $sans = [
        'A','A','A','A','A','A','A','A','A','A','A','A',
        'E','E','E','E','E','E','E','E',
        'I','I','I','I','I','I','I','I',
        'O','O','O','O','O','O','O','O','O','O',
        'U','U','U','U','U','U','U','U',
        'C','C','N','N',
    ];
    $value = str_replace($accents, $sans, $value);
    $value = function_exists('mb_strtoupper') ? mb_strtoupper($value, 'UTF-8') : strtoupper($value);

    // Espaces multiples et tirets entoures d'espaces normalises
    $value = preg_replace('/\s+/', ' ', $value);
    $value = preg_replace('/\s*-\s*/', '-', $value);
    $value = trim((string)$value);

    if ($value === '') {
        respond(['success' => false, 'message' => 'Commission requise.'], 422);
    }
    if (!in_array($value, $commissions, true)) {
        respond([
            'success' => false,
            'message' => 'Commission invalide : ' . $commission,
            'commissions_autorisees' => $commissions,
        ], 422);
    }
    return $value;
}

function nextMatricule(): string {
    $stmt = db()->query("SELECT MAX(CAST(SUBSTRING(matricule, 9) AS UNSIGNED)) FROM aeemci_commissions_senafoi WHERE matricule LIKE 'COM26SNF%'");
    $next = ((int)$stmt->fetchColumn()) + 1;
    return 'COM26SNF' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
}

function listRows(): void {
    $commission = clean($_GET['commission'] ?? '');
    $where = '';
    $params = [];
    if ($commission !== '') {
        $where = 'WHERE commission = ?';
        $params[] = normalizeCommission($commission);
    }
    $stmt = db()->prepare("SELECT * FROM aeemci_commissions_senafoi {$where} ORDER BY commission ASC, id DESC");
    $stmt->execute($params);
    respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

function stats(): void {
    $rows = db()->query("SELECT commission, COUNT(*) AS total, SUM(depart_abidjan = 1) AS depart_abidjan FROM aeemci_commissions_senafoi GROUP BY commission ORDER BY commission ASC")->fetchAll();
    $total = db()->query("SELECT COUNT(*) FROM aeemci_commissions_senafoi")->fetchColumn();
    $depart = db()->query("SELECT COUNT(*) FROM aeemci_commissions_senafoi WHERE depart_abidjan = 1")->fetchColumn();
    respond(['success' => true, 'data' => ['total' => (int)$total, 'depart_abidjan' => (int)$depart, 'commissions' => $rows]]);
}

function createRow(): void {
    $data = inputJson();
    $nom = clean($data['nom'] ?? '');
    $prenom = clean($data['prenom'] ?? '');
    $contact = preg_replace('/\s+/', '', clean($data['contact'] ?? ''));
    $commission = normalizeCommission($data['commission'] ?? '');

    if ($nom === '' || $prenom === '' || $contact === '') {
        respond(['success' => false, 'message' => 'Nom, prenom et contact sont requis.'], 422);
    }

    $matricule = nextMatricule();
    $departAbidjan = !empty($data['depart_abidjan']);
    $stmt = db()->prepare("INSERT INTO aeemci_commissions_senafoi
      (matricule, nom, prenom, photo, photo_id, contact, commission, depart_abidjan)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $matricule,
        strtoupper($nom),
        $prenom,
        clean($data['photo'] ?? ''),
        clean($data['photo_id'] ?? ''),
        $contact,
        $commission,
        $departAbidjan ? 1 : 0,
    ]);

    $row = getByMatricule($matricule);
    respond(['success' => true, 'message' => 'Membre de commission enregistre gratuitement.', 'data' => $row]);
}

function getByMatricule(string $matricule): array {
    $stmt = db()->prepare("SELECT * FROM aeemci_commissions_senafoi WHERE matricule = ?");
    $stmt->execute([$matricule]);
    $row = $stmt->fetch();
    if (!$row) respond(['success' => false, 'message' => 'Inscription introuvable.'], 404);
    return $row;
}

function deleteRow(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) respond(['success' => false, 'message' => 'ID requis.'], 422);
    $stmt = db()->prepare("DELETE FROM aeemci_commissions_senafoi WHERE id = ?");
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
