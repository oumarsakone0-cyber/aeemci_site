<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

define('DB_HOST', getenv('AEEMCI_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('AEEMCI_DB_NAME') ?: 'capbvkkqah_aeemci');
define('DB_USER', getenv('AEEMCI_DB_USER') ?: 'capbvkkqah_aeemci');
define('DB_PASS', getenv('AEEMCI_DB_PASS') ?: '0Objectif-');
define('WAVE_API_KEY', getenv('SENAFOI_WAVE_API_KEY') ?: 'wave_ci_prod_XA-f-CX_LCLs5HfwiUKbyrvPoo9-oc1qjxlIObDoHbIFs-yMePvAVG9yI0ezEGk4SlXZi1v8n_RjCIeX85sQXan_vmcBJMqiGA');

define('PRICE_KORHOGO', 50000);
define('PRICE_CONVOI', 100000);
define('PRICE_ACCOMPAGNATEUR', 80000);

function respond(array $payload, int $status = 200): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 10,
    ]);
    return $pdo;
}

function ensureTable(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS senafoi26_stands (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      reference_stand VARCHAR(40) NOT NULL,
      nom_stand VARCHAR(180) NOT NULL,
      nom_responsable VARCHAR(180) NOT NULL,
      contact_responsable VARCHAR(60) NOT NULL,
      formule VARCHAR(40) NOT NULL,
      accompagnateurs JSON DEFAULT NULL,
      nb_accompagnateurs INT UNSIGNED NOT NULL DEFAULT 0,
      montant_stand INT UNSIGNED NOT NULL DEFAULT 0,
      montant_accompagnateurs INT UNSIGNED NOT NULL DEFAULT 0,
      montant_total INT UNSIGNED NOT NULL DEFAULT 0,
      devise VARCHAR(10) NOT NULL DEFAULT 'XOF',
      transaction_id VARCHAR(120) DEFAULT NULL,
      wave_launch_url TEXT DEFAULT NULL,
      wave_transaction_id VARCHAR(120) DEFAULT NULL,
      payment_status_wave VARCHAR(60) DEFAULT NULL,
      statut_paiement VARCHAR(40) NOT NULL DEFAULT 'EN_ATTENTE',
      statut_inscription VARCHAR(40) NOT NULL DEFAULT 'EN_COURS',
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_senafoi26_stands_reference (reference_stand),
      KEY idx_senafoi26_stands_contact (contact_responsable),
      KEY idx_senafoi26_stands_statut (statut_paiement, statut_inscription),
      KEY idx_senafoi26_stands_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function inputJson(): array {
    $data = json_decode(file_get_contents('php://input') ?: '{}', true);
    return is_array($data) ? $data : [];
}

function clean($value): string { return trim(preg_replace('/\s+/u', ' ', (string)$value)); }
function money($amount): string { return number_format((float)$amount, 0, ',', ' ') . ' XOF'; }
function phone($value): string { $d = preg_replace('/\D+/', '', (string)$value); return $d ?: clean($value); }

function referenceStand(): string {
    $prefix = 'STD' . date('Y');
    $stmt = db()->prepare("SELECT MAX(CAST(SUBSTRING(reference_stand, 8) AS UNSIGNED)) AS last_num FROM senafoi26_stands WHERE reference_stand LIKE ?");
    $stmt->execute([$prefix . '%']);
    $next = (int)($stmt->fetch()['last_num'] ?? 0) + 1;
    while (true) {
        $ref = $prefix . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
        $check = db()->prepare('SELECT id FROM senafoi26_stands WHERE reference_stand = ? LIMIT 1');
        $check->execute([$ref]);
        if (!$check->fetch()) return $ref;
        $next++;
    }
}

function normalizeAccompagnateurs($value): array {
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        $value = is_array($decoded) ? $decoded : [];
    }
    if (!is_array($value)) return [];
    $out = [];
    foreach ($value as $item) {
        if (!is_array($item)) continue;
        $nom = clean($item['nom'] ?? '');
        $contact = phone($item['contact'] ?? '');
        if ($nom !== '') $out[] = ['nom' => $nom, 'contact' => $contact];
    }
    return array_slice($out, 0, 20);
}

function calculate(string $formule, array $accompagnateurs): array {
    $formule = strtolower(clean($formule));
    if (!in_array($formule, ['korhogo', 'convoi'], true)) $formule = 'korhogo';
    $stand = $formule === 'convoi' ? PRICE_CONVOI : PRICE_KORHOGO;
    $acc = count($accompagnateurs) * PRICE_ACCOMPAGNATEUR;
    return [$formule, $stand, $acc, $stand + $acc];
}

function publicRow(array $r): array {
    $acc = normalizeAccompagnateurs($r['accompagnateurs'] ?? []);
    return [
        'id' => (int)$r['id'],
        'reference_stand' => $r['reference_stand'],
        'nom_stand' => $r['nom_stand'],
        'nom_responsable' => $r['nom_responsable'],
        'contact_responsable' => $r['contact_responsable'],
        'formule' => $r['formule'],
        'formule_label' => $r['formule'] === 'convoi' ? 'Stand avec convoi AEEMCI' : 'Stand Korhogo',
        'accompagnateurs' => $acc,
        'nb_accompagnateurs' => (int)$r['nb_accompagnateurs'],
        'montant_stand' => (int)$r['montant_stand'],
        'montant_accompagnateurs' => (int)$r['montant_accompagnateurs'],
        'montant_total' => (int)$r['montant_total'],
        'montant_total_formate' => money($r['montant_total']),
        'devise' => $r['devise'] ?? 'XOF',
        'transaction_id' => $r['transaction_id'],
        'wave_transaction_id' => $r['wave_transaction_id'],
        'wave_launch_url' => $r['wave_launch_url'],
        'payment_status_wave' => $r['payment_status_wave'],
        'statut_paiement' => $r['statut_paiement'],
        'statut_inscription' => $r['statut_inscription'],
        'created_at' => $r['created_at'],
        'updated_at' => $r['updated_at'],
    ];
}

function waveCreate(int $amount, string $reference): array {
    $payload = [
        'amount' => (string)$amount,
        'currency' => 'XOF',
        'success_url' => 'https://www.aeemci-ce.ci/stand_paye/' . rawurlencode($reference),
        'error_url' => 'https://www.aeemci-ce.ci/stand_paye/' . rawurlencode($reference),
    ];
    $ch = curl_init('https://api.wave.com/v1/checkout/sessions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . WAVE_API_KEY, 'Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30,
    ]);
    $raw = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    $json = json_decode((string)$raw, true) ?: [];
    if ($err || !in_array($http, [200, 201], true) || empty($json['wave_launch_url'])) {
        throw new RuntimeException('Paiement Wave indisponible pour le moment. Réessaie dans quelques minutes.');
    }
    return $json;
}

function waveGet(string $sessionId): array {
    $ch = curl_init('https://api.wave.com/v1/checkout/sessions/' . rawurlencode($sessionId));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . WAVE_API_KEY, 'Accept: application/json'],
        CURLOPT_TIMEOUT => 20,
    ]);
    $raw = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err || $http !== 200) return ['success' => false, 'error' => $err ?: 'HTTP ' . $http];
    return ['success' => true, 'data' => json_decode((string)$raw, true) ?: []];
}

function getByReference(string $ref): ?array {
    $stmt = db()->prepare('SELECT * FROM senafoi26_stands WHERE reference_stand = ? LIMIT 1');
    $stmt->execute([$ref]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function confirmPayment(array $row): array {
    if (($row['statut_paiement'] ?? '') === 'PAYE') return publicRow($row);
    if (empty($row['transaction_id'])) return publicRow($row);
    $wave = waveGet($row['transaction_id']);
    if (empty($wave['success'])) return publicRow($row);
    $data = $wave['data'];
    $checkout = $data['checkout_status'] ?? '';
    $payment = $data['payment_status'] ?? '';
    $waveTx = $data['transaction_id'] ?? null;
    if ($checkout === 'complete' && $waveTx) {
        $stmt = db()->prepare("UPDATE senafoi26_stands SET statut_paiement='PAYE', statut_inscription='VALIDEE', payment_status_wave=?, wave_transaction_id=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$payment ?: 'succeeded', $waveTx, (int)$row['id']]);
        $row = getByReference($row['reference_stand']) ?: $row;
    }
    return publicRow($row);
}

try {
    ensureTable();
    $action = $_GET['action'] ?? 'list';

    if ($action === 'create') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
        $data = inputJson();
        $nomStand = clean($data['nom_stand'] ?? $data['nomStand'] ?? '');
        $nomResponsable = clean($data['nom_responsable'] ?? $data['nomResponsable'] ?? '');
        $contactResponsable = phone($data['contact_responsable'] ?? $data['contactResponsable'] ?? '');
        $accompagnateurs = normalizeAccompagnateurs($data['accompagnateurs'] ?? []);
        [$formule, $montantStand, $montantAcc, $total] = calculate((string)($data['formule'] ?? 'korhogo'), $accompagnateurs);
        if ($nomStand === '' || $nomResponsable === '' || $contactResponsable === '') {
            respond(['success' => false, 'message' => 'Nom du stand, responsable et contact sont requis.'], 400);
        }
        $ref = referenceStand();
        $wave = waveCreate($total, $ref);
        $stmt = db()->prepare("INSERT INTO senafoi26_stands
            (reference_stand, nom_stand, nom_responsable, contact_responsable, formule, accompagnateurs, nb_accompagnateurs, montant_stand, montant_accompagnateurs, montant_total, transaction_id, wave_launch_url, statut_paiement, statut_inscription, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'EN_ATTENTE', 'EN_COURS', NOW())");
        $stmt->execute([$ref, $nomStand, $nomResponsable, $contactResponsable, $formule, json_encode($accompagnateurs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), count($accompagnateurs), $montantStand, $montantAcc, $total, $wave['id'] ?? null, $wave['wave_launch_url']]);
        $row = getByReference($ref);
        respond(['success' => true, 'message' => 'Stand enregistré. Paiement Wave requis.', 'data' => publicRow($row), 'wave_launch_url' => $wave['wave_launch_url']]);
    }

    if ($action === 'get') {
        $ref = clean($_GET['reference'] ?? '');
        if ($ref === '') respond(['success' => false, 'message' => 'Référence requise.'], 400);
        $row = getByReference($ref);
        if (!$row) respond(['success' => false, 'message' => 'Stand introuvable.'], 404);
        respond(['success' => true, 'data' => confirmPayment($row)]);
    }

    if ($action === 'stats') {
        $stats = db()->query("SELECT COUNT(*) total, SUM(statut_paiement='PAYE') payes, SUM(statut_paiement<>'PAYE') attente, COALESCE(SUM(CASE WHEN statut_paiement='PAYE' THEN montant_total ELSE 0 END),0) montant FROM senafoi26_stands")->fetch();
        respond(['success' => true, 'data' => ['total'=>(int)$stats['total'], 'payes'=>(int)$stats['payes'], 'attente'=>(int)$stats['attente'], 'montant'=>(int)$stats['montant'], 'montant_formate'=>money($stats['montant'])]]);
    }

    if ($action === 'list') {
        $q = clean($_GET['q'] ?? $_GET['search'] ?? '');
        $status = clean($_GET['status'] ?? '');
        $where = [];
        $params = [];
        if ($q !== '') { $where[] = '(reference_stand LIKE ? OR nom_stand LIKE ? OR nom_responsable LIKE ? OR contact_responsable LIKE ?)'; $like = '%' . $q . '%'; array_push($params, $like, $like, $like, $like); }
        if ($status !== '') { $where[] = 'statut_paiement = ?'; $params[] = $status; }
        $sql = 'SELECT * FROM senafoi26_stands' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC, id DESC LIMIT 500';
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        respond(['success' => true, 'data' => array_map('publicRow', $stmt->fetchAll())]);
    }

    respond(['success' => false, 'message' => 'Action inconnue.'], 400);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()], 500);
}