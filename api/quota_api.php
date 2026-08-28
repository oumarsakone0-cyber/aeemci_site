<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$host = 'localhost';
$dbname = 'capbvkkqah_aeemci';
$username = 'capbvkkqah_aeemci';
$password = '0Objectif-';

function respond(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function getRequestData(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) {
        return $_POST;
    }

    $json = json_decode($raw, true);
    return is_array($json) ? $json : $_POST;
}


function tableExists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = :table
    ");
    $stmt->execute(['table' => $table]);
    return (int)$stmt->fetchColumn() > 0;
}

function countValidatedRegistrations(PDO $pdo, string $table): int
{
    if (!tableExists($pdo, $table)) {
        return 0;
    }

    $stmt = $pdo->query("
        SELECT COUNT(*) AS total
        FROM {$table}
        WHERE statut_paiement IN ('PAYE', 'GRATUIT')
    ");
    return (int)($stmt->fetch()['total'] ?? 0);
}

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS seminaire_quotas (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            annee_seminaire INT NOT NULL,
            quota_total INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_annee_seminaire (annee_seminaire)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $action = $_GET['action'] ?? 'get_quota';
    $data = getRequestData();
    $annee = (int)($_GET['annee'] ?? $data['annee'] ?? 2026);

    if ($annee < 2000 || $annee > 2100) {
        respond(['success' => false, 'message' => 'Année invalide.'], 400);
    }

    if ($action === 'set_quota' || $action === 'increment_quota') {
        if ($action === 'increment_quota') {
            $increment = max(0, (int)($data['increment'] ?? 0));
            if ($increment < 1) {
                respond(['success' => false, 'message' => 'Increment invalide.'], 400);
            }

            $stmt = $pdo->prepare("
                INSERT INTO seminaire_quotas (annee_seminaire, quota_total)
                VALUES (:annee, :increment)
                ON DUPLICATE KEY UPDATE quota_total = quota_total + VALUES(quota_total)
            ");
            $stmt->execute(['annee' => $annee, 'increment' => $increment]);
        } else {
            $quotaTotal = max(0, (int)($data['quota_total'] ?? 0));
            $stmt = $pdo->prepare("
                INSERT INTO seminaire_quotas (annee_seminaire, quota_total)
                VALUES (:annee, :quota_total)
                ON DUPLICATE KEY UPDATE quota_total = VALUES(quota_total)
            ");
            $stmt->execute(['annee' => $annee, 'quota_total' => $quotaTotal]);
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO seminaire_quotas (annee_seminaire, quota_total)
        VALUES (:annee, 0)
        ON DUPLICATE KEY UPDATE annee_seminaire = annee_seminaire
    ");
    $stmt->execute(['annee' => $annee]);

    $quotaStmt = $pdo->prepare("SELECT quota_total FROM seminaire_quotas WHERE annee_seminaire = :annee LIMIT 1");
    $quotaStmt->execute(['annee' => $annee]);
    $quotaTotal = (int)($quotaStmt->fetch()['quota_total'] ?? 0);

    $countStmt = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM seminaristes
        WHERE annee_seminaire = :annee
          AND payment_status_wave = 'succeeded'
    ");
    $countStmt->execute(['annee' => $annee]);
    $inscriptionsCount = (int)($countStmt->fetch()['total'] ?? 0)
        + countValidatedRegistrations($pdo, 'aeemci_seminaristes_sr_2026')
        + countValidatedRegistrations($pdo, 'aeemci_seminaristes_ccr_2026');
    $placesDisponibles = max(0, $quotaTotal - $inscriptionsCount);

    respond([
        'success' => true,
        'data' => [
            'annee_seminaire' => $annee,
            'quota_total' => $quotaTotal,
            'inscriptions_count' => $inscriptionsCount,
            'places_disponibles' => $placesDisponibles,
            'is_full' => $placesDisponibles <= 0,
            'refreshed_at' => date(DATE_ATOM),
        ],
    ]);
} catch (Throwable $e) {
    respond([
        'success' => false,
        'message' => 'Erreur serveur lors du chargement du quota.',
    ], 500);
}
