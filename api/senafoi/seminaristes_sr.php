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
define('WAVE_API_KEY', getenv('WAVE_API_KEY') ?: 'wave_ci_prod_XA-f-CX_LCLs5HfwiUKbyrvPoo9-oc1qjxlIObDoHbIFs-yMePvAVG9yI0ezEGk4SlXZi1v8n_RjCIeX85sQXan_vmcBJMqiGA');
define('PUBLIC_PAGE_URL', getenv('SEMINARISTES_SR_URL') ?: 'https://www.aeemci-ce.ci/seminaristes_sr');
define('API_URL', getenv('SEMINARISTES_SR_API_URL') ?: 'https://api.aeemci-ce.ci/senafoi/seminaristes_sr.php');
define('SR_TABLE', 'aeemci_seminaristes_sr_2026');
define('SR_OLD_TABLE', 'aeemci_seminaristes_sr');
define('SR_QUOTA', 10);

$secretariats = [
    'ABIDJAN NORD', 'ABIDJAN SUD', 'ABIDJAN EST', 'ABIDJAN OUEST',
    'AGNEBY-TIASSA ET ME', 'BAGOUE', 'BELIER', 'BERE', 'BAFING', 'BOUNKANI',
    'CAVALLY', 'FOLON', 'GBEKE', 'GBOKLE', 'GOH', 'GONTOUGO',
    'GRANDS-PONTS', "N'ZI", 'HAUT-SASSANDRA', 'IFFOU', 'INDENIE-DJUABLIN',
    'KABADOUGOU', 'LOH-DJIBOUA', 'NAWA', 'MORONOU', 'PORO', 'WORODOUGOU',
    'MARAHOUE', 'TCHOLOGO', 'TONPKI', 'SAN-PEDRO', 'SUD COMOE', 'GUEMON', 'HAMBOL'
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
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_seminaristes_sr_2026 (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      matricule VARCHAR(30) NOT NULL,
      secretariat VARCHAR(120) NOT NULL,
      nom VARCHAR(120) NOT NULL,
      prenom VARCHAR(160) NOT NULL,
      photo VARCHAR(600) DEFAULT NULL,
      photo_id VARCHAR(220) DEFAULT NULL,
      poste VARCHAR(160) NOT NULL,
      contact VARCHAR(40) NOT NULL,
      depart_abidjan TINYINT(1) NOT NULL DEFAULT 0,
      montant INT UNSIGNED NOT NULL DEFAULT 0,
      statut_paiement VARCHAR(30) NOT NULL DEFAULT 'GRATUIT',
      numero_wave VARCHAR(40) DEFAULT NULL,
      transaction_id VARCHAR(160) DEFAULT NULL,
      ref_paiement VARCHAR(160) DEFAULT NULL,
      wave_status VARCHAR(80) DEFAULT NULL,
      payment_status_wave VARCHAR(80) DEFAULT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY uq_sem_sr_matricule (matricule),
      KEY idx_sem_sr_secretariat (secretariat),
      KEY idx_sem_sr_payment (statut_paiement, transaction_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $stmt = db()->prepare("
        SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'aeemci_seminaristes_sr_2026'
          AND COLUMN_NAME = 'ref_paiement'
    ");
    $stmt->execute();
    if ((int)$stmt->fetchColumn() === 0) {
        db()->exec("ALTER TABLE aeemci_seminaristes_sr_2026 ADD COLUMN ref_paiement VARCHAR(160) DEFAULT NULL AFTER transaction_id");
    }
}

function tableExists(string $table): bool {
    $stmt = db()->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
    $stmt->execute([$table]);
    return (int)$stmt->fetchColumn() > 0;
}

function migratePaidAbidjanFromOld(): void {
    if (!tableExists(SR_OLD_TABLE)) return;
    db()->exec("INSERT IGNORE INTO aeemci_seminaristes_sr_2026
      (matricule, secretariat, nom, prenom, photo, photo_id, poste, contact, depart_abidjan, montant, statut_paiement, numero_wave, transaction_id, ref_paiement, wave_status, payment_status_wave, created_at, updated_at)
      SELECT matricule, secretariat, nom, prenom, photo, photo_id, poste, contact, depart_abidjan, montant, statut_paiement, numero_wave, transaction_id, ref_paiement, wave_status, payment_status_wave, created_at, updated_at
      FROM aeemci_seminaristes_sr old_sr
      WHERE old_sr.depart_abidjan = 1
        AND old_sr.statut_paiement = 'PAYE'
        AND NOT EXISTS (SELECT 1 FROM aeemci_seminaristes_sr_2026 new_sr WHERE new_sr.matricule = old_sr.matricule)");
}

function validateSecretariat(string $secretariat): string {
    global $secretariats;
    $value = strtoupper(clean($secretariat));
    if (!in_array($value, $secretariats, true)) {
        respond(['success' => false, 'message' => 'Secretariat invalide.'], 422);
    }
    return $value;
}

/**
 * Journal des matricules déjà attribués.
 *
 * Le numéro suivant était calculé avec MAX() sur les lignes vivantes :
 * supprimer le dernier inscrit libérait son numéro, qu'une autre personne
 * récupérait ensuite — alors que le badge du premier circulait déjà.
 * Ce journal garde la trace de tout matricule émis, même après suppression.
 */
function ensureJournalMatricules(): void {
    db()->exec("CREATE TABLE IF NOT EXISTS aeemci_matricules_emis (
      matricule VARCHAR(30) NOT NULL,
      famille VARCHAR(20) NOT NULL,
      emis_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (matricule),
      KEY idx_famille (famille)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Reprise de l'existant au premier passage
    db()->exec("INSERT IGNORE INTO aeemci_matricules_emis (matricule, famille)
                SELECT matricule, 'SR' FROM aeemci_seminaristes_sr_2026
                WHERE matricule LIKE 'SEM26SR%'");
}

function nextMatricule(): string {
    ensureJournalMatricules();

    // On prend le plus grand des deux : les inscriptions vivantes et tout ce
    // qui a déjà été émis. Un numéro n'est jamais servi deux fois.
    $vivants = (int)db()->query(
        "SELECT COALESCE(MAX(CAST(SUBSTRING(matricule, 8) AS UNSIGNED)), 0)
         FROM aeemci_seminaristes_sr_2026 WHERE matricule LIKE 'SEM26SR%'"
    )->fetchColumn();

    $emis = (int)db()->query(
        "SELECT COALESCE(MAX(CAST(SUBSTRING(matricule, 8) AS UNSIGNED)), 0)
         FROM aeemci_matricules_emis WHERE famille = 'SR' AND matricule LIKE 'SEM26SR%'"
    )->fetchColumn();

    $next = max($vivants, $emis) + 1;
    $matricule = 'SEM26SR' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);

    db()->prepare("INSERT IGNORE INTO aeemci_matricules_emis (matricule, famille) VALUES (?, 'SR')")
        ->execute([$matricule]);

    return $matricule;
}

function listRows(): void {
    $secretariat = clean($_GET['secretariat'] ?? '');
    $where = "WHERE statut_paiement IN ('PAYE', 'GRATUIT')";
    $params = [];
    if ($secretariat !== '') {
        $where .= ' AND secretariat = ?';
        $params[] = validateSecretariat($secretariat);
    }
    $stmt = db()->prepare("SELECT * FROM aeemci_seminaristes_sr_2026 {$where} ORDER BY secretariat ASC, id DESC");
    $stmt->execute($params);
    respond(['success' => true, 'data' => $stmt->fetchAll()]);
}

function stats(): void {
    $rows = db()->query("SELECT secretariat, COUNT(*) AS total, SUM(depart_abidjan = 1) AS depart_abidjan, COUNT(*) AS valides FROM aeemci_seminaristes_sr_2026 WHERE statut_paiement IN ('PAYE', 'GRATUIT') GROUP BY secretariat ORDER BY secretariat ASC")->fetchAll();
    $total = db()->query("SELECT COUNT(*) FROM aeemci_seminaristes_sr_2026 WHERE statut_paiement IN ('PAYE', 'GRATUIT')")->fetchColumn();
    respond(['success' => true, 'data' => ['total' => (int)$total, 'secretariats' => $rows]]);
}

function createRow(): void {
    $data = inputJson();
    $secretariat = validateSecretariat($data['secretariat'] ?? '');
    $count = db()->prepare("SELECT COUNT(*) FROM aeemci_seminaristes_sr_2026 WHERE secretariat = ? AND statut_paiement IN ('PAYE', 'GRATUIT')");
    $count->execute([$secretariat]);
    if ((int)$count->fetchColumn() >= SR_QUOTA) {
        respond(['success' => false, 'message' => 'Quota atteint: 10 membres maximum pour ce secretariat.'], 409);
    }

    $nom = clean($data['nom'] ?? '');
    $prenom = clean($data['prenom'] ?? '');
    $poste = clean($data['poste'] ?? '');
    $contact = preg_replace('/\s+/', '', clean($data['contact'] ?? ''));
    if ($nom === '' || $prenom === '' || $poste === '' || $contact === '') {
        respond(['success' => false, 'message' => 'Nom, prenom, poste et contact sont requis.'], 422);
    }

    $departAbidjan = !empty($data['depart_abidjan']);
    $montant = $departAbidjan ? 16000 : 0;
    $matricule = nextMatricule();
    $numeroWave = $departAbidjan ? clean($data['numero_wave'] ?? '') : clean($data['numero_wave'] ?? $contact);
    $statut = $departAbidjan ? 'EN_ATTENTE' : 'GRATUIT';
    if ($departAbidjan && $numeroWave === '') {
        respond(['success' => false, 'message' => 'Numero Wave requis pour le depart Abidjan.'], 422);
    }

    $stmt = db()->prepare("INSERT INTO aeemci_seminaristes_sr_2026
      (matricule, secretariat, nom, prenom, photo, photo_id, poste, contact, depart_abidjan, montant, statut_paiement, numero_wave)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $matricule,
        $secretariat,
        strtoupper($nom),
        $prenom,
        clean($data['photo'] ?? ''),
        clean($data['photo_id'] ?? ''),
        $poste,
        $contact,
        $departAbidjan ? 1 : 0,
        $montant,
        $statut,
        $numeroWave,
    ]);

    $row = getByMatricule($matricule);
    if (!$departAbidjan) {
        respond(['success' => true, 'message' => 'Membre enregistre gratuitement.', 'data' => $row]);
    }

    $wave = createWaveSession($row);
    if (!empty($wave['wave_launch_url'])) {
        $reference = $wave['id'] ?? null;
        $up = db()->prepare("UPDATE aeemci_seminaristes_sr_2026 SET transaction_id = ?, ref_paiement = ?, updated_at = NOW() WHERE matricule = ?");
        $up->execute([$reference, $reference, $matricule]);
        $row['transaction_id'] = $wave['id'] ?? null;
        $row['ref_paiement'] = $reference;
        respond(['success' => true, 'message' => 'Paiement Wave requis. Le membre sera valide uniquement apres confirmation du paiement.', 'data' => $row, 'wave_launch_url' => $wave['wave_launch_url']]);
    }

    db()->prepare("DELETE FROM aeemci_seminaristes_sr_2026 WHERE matricule = ? AND statut_paiement = 'EN_ATTENTE'")->execute([$matricule]);
    respond(['success' => false, 'message' => 'Paiement Wave indisponible. Le membre n a pas ete inscrit.', 'wave_details' => $wave], 502);
}

function getByMatricule(string $matricule): array {
    $stmt = db()->prepare("SELECT * FROM aeemci_seminaristes_sr_2026 WHERE matricule = ?");
    $stmt->execute([$matricule]);
    $row = $stmt->fetch();
    if (!$row) respond(['success' => false, 'message' => 'Inscription introuvable.'], 404);
    return $row;
}

function createWaveSession(array $row): array {
    $matricule = urlencode($row['matricule']);
    $secretariat = urlencode($row['secretariat']);
    $payload = [
        'amount' => (string)$row['montant'],
        'currency' => 'XOF',
        'success_url' => API_URL . "?action=payment_return&status=success&matricule={$matricule}&secretariat={$secretariat}",
        'error_url' => API_URL . "?action=payment_return&status=error&matricule={$matricule}&secretariat={$secretariat}",
    ];

    $ch = curl_init('https://api.wave.com/v1/checkout/sessions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . WAVE_API_KEY,
        'Content-Type: application/json',
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    $json = json_decode($body ?: '{}', true) ?: [];
    $json['_http_status'] = $status;
    if ($error) $json['_curl_error'] = $error;
    return $json;
}

function checkWaveSession(?string $sessionId): array {
    $sessionId = trim((string)$sessionId);
    if ($sessionId === '') return ['paid' => false, 'status' => '', 'payment_status' => '', 'data' => []];

    $ch = curl_init('https://api.wave.com/v1/checkout/sessions/' . rawurlencode($sessionId));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . WAVE_API_KEY,
        'Content-Type: application/json',
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $json = json_decode($body ?: '{}', true) ?: [];
    $waveStatus = clean($json['checkout_status'] ?? $json['status'] ?? '');
    $paymentStatus = clean($json['payment_status'] ?? '');
    $paid = in_array(strtolower($waveStatus), ['complete', 'completed', 'success', 'succeeded'], true)
        || in_array(strtolower($paymentStatus), ['succeeded', 'success', 'paid'], true);

    return [
        'paid' => $paid,
        'status' => $waveStatus,
        'payment_status' => $paymentStatus,
        'http_status' => $status,
        'error' => $error,
        'data' => $json,
    ];
}

function paymentReturn(): void {
    $matricule = clean($_GET['matricule'] ?? '');
    $status = clean($_GET['status'] ?? '');
    $secretariat = clean($_GET['secretariat'] ?? '');
    if ($matricule !== '') {
        $row = getByMatricule($matricule);
        $wave = $status === 'success' ? checkWaveSession($row['transaction_id'] ?? '') : ['paid' => false, 'status' => $status, 'payment_status' => $status];
        $newStatus = !empty($wave['paid']) ? 'PAYE' : ($status === 'error' ? 'ECHEC' : 'EN_ATTENTE');
        db()->prepare("UPDATE aeemci_seminaristes_sr_2026 SET statut_paiement = ?, wave_status = ?, payment_status_wave = ?, ref_paiement = COALESCE(ref_paiement, transaction_id), updated_at = NOW() WHERE matricule = ?")
            ->execute([$newStatus, $wave['status'] ?? $status, $wave['payment_status'] ?? $status, $matricule]);
        $status = !empty($wave['paid']) ? 'success' : 'pending';
    }
    $query = http_build_query(['payment' => $status, 'matricule' => $matricule, 'secretariat' => $secretariat]);
    header('Location: ' . PUBLIC_PAGE_URL . '?' . $query, true, 302);
    exit;
}

function paymentCallback(): void {
    $matricule = clean($_GET['matricule'] ?? '');
    $payload = inputJson();
    $waveStatus = clean($payload['checkout_status'] ?? $payload['status'] ?? '');
    $paymentStatus = clean($payload['payment_status'] ?? '');
    $transaction = clean($payload['transaction_id'] ?? $payload['id'] ?? '');
    if ($matricule !== '') {
        $paid = in_array($waveStatus, ['complete', 'success', 'succeeded'], true) || in_array($paymentStatus, ['succeeded', 'success'], true);
        db()->prepare("UPDATE aeemci_seminaristes_sr_2026 SET statut_paiement = ?, wave_status = ?, payment_status_wave = ?, transaction_id = COALESCE(NULLIF(?, ''), transaction_id), ref_paiement = COALESCE(NULLIF(?, ''), ref_paiement, transaction_id), updated_at = NOW() WHERE matricule = ?")
            ->execute([$paid ? 'PAYE' : 'EN_ATTENTE', $waveStatus, $paymentStatus, $transaction, $transaction, $matricule]);
    }
    respond(['success' => true]);
}

/**
 * Correction d'une fiche depuis l'administration.
 *
 * Le matricule et l'état du paiement ne sont pas modifiables ici : le
 * premier sert de clé sur les badges déjà imprimés, le second est écrit
 * par Wave. Le départ Abidjan reste ajustable, une inscription pouvant
 * avoir été saisie à tort.
 */
function updateRow(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) respond(['success' => false, 'message' => 'ID requis.'], 422);

    $lire = db()->prepare("SELECT * FROM aeemci_seminaristes_sr_2026 WHERE id = ? LIMIT 1");
    $lire->execute([$id]);
    $actuel = $lire->fetch();
    if (!$actuel) respond(['success' => false, 'message' => 'Inscription introuvable.'], 404);

    $nom = clean($data['nom'] ?? $actuel['nom']);
    $prenom = clean($data['prenom'] ?? $actuel['prenom']);
    $poste = clean($data['poste'] ?? $actuel['poste']);
    $contact = preg_replace('/\s+/', '', clean($data['contact'] ?? $actuel['contact']));
    if ($nom === '' || $prenom === '' || $poste === '' || $contact === '') {
        respond(['success' => false, 'message' => 'Nom, prenom, poste et contact sont requis.'], 422);
    }

    $secretariat = array_key_exists('secretariat', $data)
        ? validateSecretariat($data['secretariat'])
        : $actuel['secretariat'];

    // Changer de secrétariat ne doit pas faire dépasser le quota d'accueil.
    if ($secretariat !== $actuel['secretariat']) {
        $count = db()->prepare(
            "SELECT COUNT(*) FROM aeemci_seminaristes_sr_2026
             WHERE secretariat = ? AND statut_paiement IN ('PAYE', 'GRATUIT') AND id <> ?"
        );
        $count->execute([$secretariat, $id]);
        if ((int)$count->fetchColumn() >= SR_QUOTA) {
            respond(['success' => false, 'message' => "Quota atteint pour {$secretariat} : 10 membres maximum."], 409);
        }
    }

    $departAbidjan = array_key_exists('depart_abidjan', $data)
        ? (!empty($data['depart_abidjan']) ? 1 : 0)
        : (int)$actuel['depart_abidjan'];

    $stmt = db()->prepare("UPDATE aeemci_seminaristes_sr_2026
        SET nom = ?, prenom = ?, poste = ?, contact = ?, secretariat = ?,
            photo = ?, photo_id = ?, numero_wave = ?, depart_abidjan = ?,
            montant = ?, updated_at = NOW()
        WHERE id = ?");
    $stmt->execute([
        mb_strtoupper($nom),
        $prenom,
        $poste,
        $contact,
        $secretariat,
        clean($data['photo'] ?? $actuel['photo']),
        clean($data['photo_id'] ?? $actuel['photo_id']),
        clean($data['numero_wave'] ?? $actuel['numero_wave']),
        $departAbidjan,
        $departAbidjan ? 16000 : 0,
        $id,
    ]);

    $lire->execute([$id]);
    respond(['success' => true, 'message' => 'Fiche mise a jour.', 'data' => $lire->fetch()]);
}

function deleteRow(): void {
    $data = inputJson();
    $id = (int)($data['id'] ?? 0);
    $secretariat = validateSecretariat($data['secretariat'] ?? '');
    if ($id <= 0) respond(['success' => false, 'message' => 'ID requis.'], 422);
    $stmt = db()->prepare("DELETE FROM aeemci_seminaristes_sr_2026 WHERE id = ? AND secretariat = ?");
    $stmt->execute([$id, $secretariat]);
    respond(['success' => true, 'deleted' => $stmt->rowCount()]);
}

try {
    ensureSchema();
    migratePaidAbidjanFromOld();
    $action = clean($_GET['action'] ?? 'list');
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') listRows();
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'stats') stats();
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'payment_return') paymentReturn();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'payment_callback') paymentCallback();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') createRow();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') updateRow();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') deleteRow();
    respond(['success' => false, 'message' => 'Action non supportee.'], 404);
} catch (Throwable $e) {
    respond(['success' => false, 'message' => 'Erreur serveur.', 'details' => $e->getMessage()], 500);
}
