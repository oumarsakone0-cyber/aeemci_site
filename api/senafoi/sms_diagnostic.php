<?php
/**
 * DIAGNOSTIC SMS — SENAFOI 2026
 *
 * Outil de dépannage pour comprendre pourquoi les SMS de validation
 * d'inscription ne partent plus.
 *
 * Utilisation (dans le navigateur) :
 *   1) Etat general      : https://api.aeemci-ce.ci/senafoi/sms_diagnostic.php
 *   2) Envoi de test     : ...sms_diagnostic.php?test_to=0700000000
 *      (envoie un vrai SMS — consomme 1 credit — et affiche la reponse brute de KYA)
 *   3) Historique d'un   : ...sms_diagnostic.php?matricule=SEM20261275
 *      matricule
 *
 * A SUPPRIMER du serveur une fois le probleme resolu.
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

// Memes constantes que update_payment.php
define('KYA_SMS_URL', 'https://route.kyasms.net/api/v3/sms/send');
define('KYA_SMS_API_KEY', 'kyasmsd2ccf32b4aa62311eee9da3051b60bba18bb5236249abf9d1c5e5e873f');
define('KYA_SMS_SENDER', 'AEEMCI');

$rapport = [
    'date' => date('Y-m-d H:i:s'),
    'etapes' => [],
];

function etape(string $nom, bool $ok, $detail = null, ?string $conseil = null): array {
    return array_filter([
        'test' => $nom,
        'resultat' => $ok ? 'OK' : 'PROBLEME',
        'detail' => $detail,
        'conseil' => $conseil,
    ], static fn($v) => $v !== null);
}

function normaliserNumeroSms($phone): string {
    $digits = preg_replace('/\D+/', '', (string)$phone);
    if ($digits === '') return '';
    if (strpos($digits, '00225') === 0) $digits = substr($digits, 2);
    if (strpos($digits, '225') === 0 && strlen($digits) === 13) return $digits;
    if (strlen($digits) === 10) return '225' . $digits;
    if (strlen($digits) === 8) return '2250' . $digits;
    return $digits;
}

// ── 1. Environnement PHP ───────────────────────────────────────────────────
$rapport['etapes'][] = etape(
    '1. Extension cURL disponible',
    function_exists('curl_init'),
    function_exists('curl_init') ? ('version ' . (curl_version()['version'] ?? '?')) : 'cURL absent',
    function_exists('curl_init') ? null : 'Activez l\'extension cURL dans PHP (panel n0c > PHP > extensions).'
);

$rapport['etapes'][] = etape(
    '2. allow_url_fopen (secours)',
    (bool)ini_get('allow_url_fopen'),
    ini_get('allow_url_fopen') ? 'active' : 'desactive',
    ini_get('allow_url_fopen') ? null : 'Non bloquant si cURL fonctionne.'
);

// ── 2. Connexion sortante vers KYA ─────────────────────────────────────────
$connexionOk = false;
$detailConnexion = 'cURL indisponible';
if (function_exists('curl_init')) {
    $ch = curl_init('https://route.kyasms.net');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_NOBODY => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    $connexionOk = ($err === '' && $code > 0);
    $detailConnexion = $connexionOk
        ? "route.kyasms.net joignable (HTTP $code)"
        : "Echec : " . ($err ?: "code $code");
}
$rapport['etapes'][] = etape(
    '3. Serveur joignable (route.kyasms.net)',
    $connexionOk,
    $detailConnexion,
    $connexionOk ? null : "L'hebergeur bloque les connexions sortantes. Demandez a n0c d'autoriser les appels HTTPS sortants vers route.kyasms.net."
);

// ── 3. Base de données et journal SMS ──────────────────────────────────────
$db = null;
try {
    require_once '../config/database.php';
    $db = new Database();
    $rapport['etapes'][] = etape('4. Connexion base de donnees', true, 'OK');
} catch (Throwable $e) {
    $rapport['etapes'][] = etape('4. Connexion base de donnees', false, $e->getMessage(),
        'Verifiez ../config/database.php');
}

if ($db) {
    // Journal global : combien de SMS envoyes / echoues et quand ?
    try {
        $stats = $db->query("
            SELECT status, COUNT(*) AS total, MAX(created_at) AS dernier
            FROM aeemci_sms_messages
            GROUP BY status
            ORDER BY total DESC
        ");
        $rapport['etapes'][] = etape(
            '5. Journal aeemci_sms_messages',
            !empty($stats),
            $stats ?: 'Table vide : aucun SMS n\'a jamais ete journalise.',
            empty($stats) ? 'Si la table est vide alors que des inscriptions ont eu lieu, la fonction d\'envoi n\'est jamais atteinte.' : null
        );

        $derniers = $db->query("
            SELECT id, matricule, status, created_at, LEFT(message, 60) AS extrait
            FROM aeemci_sms_messages
            ORDER BY id DESC LIMIT 15
        ");
        $rapport['derniers_sms'] = $derniers;

        // Diagnostic automatique du journal
        $sent = 0; $failed = 0; $dernierSent = null;
        foreach (($stats ?: []) as $s) {
            if ($s['status'] === 'sent') { $sent = (int)$s['total']; $dernierSent = $s['dernier']; }
            if ($s['status'] === 'failed') { $failed = (int)$s['total']; }
        }
        if ($failed > 0 && $sent === 0) {
            $rapport['diagnostic_journal'] = "Tous les envois echouent ($failed echecs, 0 succes) : le probleme vient de KYA (cle API, credit) ou du reseau sortant.";
        } elseif ($dernierSent) {
            $rapport['diagnostic_journal'] = "Dernier SMS reussi le $dernierSent. $failed echec(s) enregistre(s) au total.";
        }
    } catch (Throwable $e) {
        $rapport['etapes'][] = etape('5. Journal aeemci_sms_messages', false, $e->getMessage(),
            "La table n'existe pas. Creez-la (voir sql_creation ci-dessous) : sans elle l'anti-doublon et le suivi ne fonctionnent pas.");
        $rapport['sql_creation'] = "CREATE TABLE aeemci_sms_messages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  matricule VARCHAR(60) DEFAULT NULL,
  phone_numbers TEXT DEFAULT NULL,
  message TEXT DEFAULT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'queued',
  provider_response TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_sms_matricule (matricule),
  KEY idx_sms_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    }

    // Historique d'un matricule precis
    $matricule = strtoupper(trim((string)($_GET['matricule'] ?? '')));
    if ($matricule !== '') {
        try {
            $sem = $db->query("
                SELECT matricule_seminaire, prenom, nom, contact, numero_wave,
                       statut_paiement, statut_inscription, payment_status_wave, date_inscription
                FROM seminaristes WHERE matricule_seminaire = ? LIMIT 1
            ", [$matricule]);
            $rapport['seminariste'] = $sem[0] ?? 'Introuvable';

            if (!empty($sem[0])) {
                $tel = !empty($sem[0]['numero_wave']) ? $sem[0]['numero_wave'] : ($sem[0]['contact'] ?? '');
                $rapport['seminariste_telephone_utilise'] = [
                    'brut' => $tel,
                    'normalise' => normaliserNumeroSms($tel),
                    'valide' => strlen(normaliserNumeroSms($tel)) >= 12 ? 'oui' : 'NON — numero trop court',
                ];
            }

            $rapport['sms_du_matricule'] = $db->query(
                "SELECT id, status, created_at, message FROM aeemci_sms_messages WHERE matricule = ? ORDER BY id DESC",
                [$matricule]
            );
        } catch (Throwable $e) {
            $rapport['seminariste'] = 'Erreur : ' . $e->getMessage();
        }
    }
}

// ── 4. Envoi de test (uniquement si demande explicitement) ─────────────────
$testTo = trim((string)($_GET['test_to'] ?? ''));
if ($testTo !== '') {
    $numero = normaliserNumeroSms($testTo);
    if (strlen($numero) < 12) {
        $rapport['envoi_test'] = ['erreur' => "Numero invalide apres normalisation : '$numero'"];
    } else {
        $message = 'Test SENAFOI 2026 - verification du service SMS. ' . date('H:i');
        $payload = json_encode([
            'from' => KYA_SMS_SENDER,
            'to' => $numero,
            'type' => 'text',
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $ch = curl_init(KYA_SMS_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'APIKEY: ' . KYA_SMS_API_KEY],
            CURLOPT_TIMEOUT => 35,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        $reponse = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $decoded = json_decode((string)$reponse, true);

        // Condition de succes utilisee par update_payment.php
        $successSelonCode = !$curlError && $httpCode >= 200 && $httpCode < 400
            && ((($decoded['reason'] ?? '') === 'success') || !empty($decoded['data']));

        $rapport['envoi_test'] = [
            'numero_envoye' => $numero,
            'http_code' => $httpCode,
            'curl_error' => $curlError ?: null,
            'reponse_brute' => $reponse,
            'reponse_decodee' => $decoded,
            'considere_comme_reussi_par_le_code' => $successSelonCode ? 'OUI' : 'NON',
        ];

        // Interpretation
        if ($curlError) {
            $rapport['envoi_test']['cause_probable'] = "Reseau sortant bloque ou SSL : $curlError";
        } elseif ($httpCode === 401 || $httpCode === 403) {
            $rapport['envoi_test']['cause_probable'] = "Cle API KYA refusee (HTTP $httpCode) : cle expiree, revoquee ou compte suspendu.";
        } elseif ($httpCode === 402 || stripos((string)$reponse, 'credit') !== false || stripos((string)$reponse, 'balance') !== false) {
            $rapport['envoi_test']['cause_probable'] = "Credit SMS epuise sur le compte KYA.";
        } elseif ($httpCode >= 400) {
            $rapport['envoi_test']['cause_probable'] = "KYA renvoie une erreur HTTP $httpCode — voir reponse_brute.";
        } elseif (!$successSelonCode) {
            $rapport['envoi_test']['cause_probable'] = "KYA repond OK (HTTP $httpCode) mais dans un format que le code ne reconnait pas comme un succes. Le SMS est peut-etre parti alors qu'il est journalise 'failed'.";
        } else {
            $rapport['envoi_test']['cause_probable'] = "Envoi accepte par KYA. Si le SMS n'arrive pas, verifiez l'enregistrement du sender 'AEEMCI' aupres de l'operateur.";
        }
    }
} else {
    $rapport['envoi_test'] = "Non execute. Ajoutez ?test_to=0700000000 a l'URL pour envoyer un SMS de test (consomme 1 credit).";
}

// ── 5. Synthese ────────────────────────────────────────────────────────────
$problemes = array_values(array_filter($rapport['etapes'], static fn($e) => $e['resultat'] === 'PROBLEME'));
$rapport['synthese'] = $problemes
    ? count($problemes) . ' probleme(s) detecte(s) — voir les etapes marquees PROBLEME.'
    : 'Environnement correct. Lancez un envoi de test avec ?test_to=VotreNumero pour voir la reponse de KYA.';

echo json_encode($rapport, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
