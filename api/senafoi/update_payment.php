<?php
// =============================================================================
// verify_payment.php â€” VÃ©rification paiement Wave + SMS KYA SMS
// =============================================================================

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "MÃ©thode non autorisÃ©e"]);
    exit();
}

$rawInput = file_get_contents("php://input");
$data     = json_decode($rawInput, true);

if (!$data || !isset($data['matricule'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Matricule requis"]);
    exit();
}

$matricule    = trim($data['matricule']);
$force_update = isset($data['force_update']) ? $data['force_update'] : false;

require_once '../config/database.php';
require_once '../config/seminaire_config.php';

define('KYA_SMS_URL', 'https://route.kyasms.net/api/v3/sms/send');
define('KYA_SMS_API_KEY', 'kyasmsd2ccf32b4aa62311eee9da3051b60bba18bb5236249abf9d1c5e5e873f');
define('KYA_SMS_SENDER', 'AEEMCI');
define('KYA_SMS_CALLBACK_URL', 'https://api.aeemci-ce.ci/senafoi/sms_dlr.php');

function normaliserNumeroSms($phone) {
    $digits = preg_replace('/\D+/', '', (string)$phone);
    if ($digits === '') return '';

    if (strpos($digits, '00225') === 0) {
        $digits = substr($digits, 2);
    }
    if (strpos($digits, '225') === 0 && strlen($digits) === 13) {
        return $digits;
    }
    if (strlen($digits) === 10) {
        return '225' . $digits;
    }
    if (strlen($digits) === 8) {
        return '2250' . $digits;
    }

    return $digits;
}

function envoyerSMSValidation($phone, $message, $matricule = null) {
    $normalizedPhone = normaliserNumeroSms($phone);
    if ($normalizedPhone === '') {
        return [
            'success' => false,
            'tous_envoyes' => false,
            'skipped' => true,
            'reason' => 'Numero SMS invalide',
        ];
    }

    // ATTENTION : ne pas ajouter 'callback_url' ici.
    // La passerelle KYA rejette la requete lorsque ce parametre est present
    // (constate le 26/07/2026 : payload identique SANS callback_url = succes,
    //  AVEC callback_url = echec systematique depuis le 05/07/2026).
    // Les accuses de reception se configurent dans le tableau de bord KYA,
    // pas dans le corps de la requete d'envoi.
    $payload = json_encode([
        'from' => KYA_SMS_SENDER,
        'to' => $normalizedPhone,
        'type' => 'text',
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $ch = curl_init(KYA_SMS_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'APIKEY: ' . KYA_SMS_API_KEY,
        ],
        CURLOPT_TIMEOUT => 35,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    $decoded = json_decode((string)$response, true);

    // Detection de succes tolerante : KYA a plusieurs formats de reponse selon
    // les versions. On considere l'envoi accepte des lors que la requete HTTP
    // aboutit et qu'aucune erreur explicite n'est renvoyee.
    $httpOk = !$curlError && $httpCode >= 200 && $httpCode < 400;
    $marqueurSucces = (($decoded['reason'] ?? '') === 'success')
        || !empty($decoded['data'])
        || in_array(strtolower((string)($decoded['status'] ?? '')), ['success', 'ok', 'sent', 'queued', 'accepted'], true)
        || (isset($decoded['error']) && $decoded['error'] === false)
        || (isset($decoded['success']) && $decoded['success'] === true);
    $erreurExplicite = !empty($decoded['error']) && $decoded['error'] !== false;

    $success = $httpOk && ($marqueurSucces || (!$erreurExplicite && $decoded === null && trim((string)$response) !== ''));

    if (!$success) {
        error_log('ECHEC SMS KYA -> ' . $normalizedPhone
            . ' | HTTP ' . $httpCode
            . ' | cURL: ' . ($curlError ?: 'aucune')
            . ' | Reponse: ' . substr((string)$response, 0, 400));
    }

    return [
        'success' => $success,
        'tous_envoyes' => $success,
        'phone' => $normalizedPhone,
        'http_code' => $httpCode,
        'curl_error' => $curlError ?: null,
        'provider_response' => $decoded ?? $response,
        'raw_response' => substr((string)$response, 0, 500),
    ];
}

function envoyerSMSValidationSiNecessaire($db, $phone, $prenom, $matricule) {
    if (empty($phone)) {
        return [
            'success' => false,
            'tous_envoyes' => false,
            'skipped' => true,
            'reason' => 'Aucun numÃ©ro Wave ou contact disponible',
        ];
    }

    try {
        $existing = $db->query(
            "SELECT id FROM aeemci_sms_messages WHERE matricule = ? AND status = 'sent' LIMIT 1",
            [$matricule]
        );
        if (!empty($existing)) {
            return [
                'success' => true,
                'tous_envoyes' => true,
                'skipped' => true,
                'already_sent' => true,
            ];
        }
    } catch (Throwable $e) {
        error_log('VÃ©rification anti-doublon SMS indisponible: ' . $e->getMessage());
    }

    $message = "Salam $prenom, votre inscription SENAFOI 26 est validee. Matricule: $matricule. AEEMCI";
    $result = envoyerSMSValidation($phone, $message, $matricule);

    // Journalisation : on tente d'abord d'enregistrer la reponse du fournisseur
    // (colonne provider_response). Si la colonne n'existe pas, on retombe sur
    // l'insertion simple pour ne jamais bloquer l'envoi.
    $trace = json_encode([
        'http_code' => $result['http_code'] ?? null,
        'curl_error' => $result['curl_error'] ?? null,
        'reponse' => $result['raw_response'] ?? ($result['provider_response'] ?? null),
    ], JSON_UNESCAPED_UNICODE);

    try {
        $db->query(
            "INSERT INTO aeemci_sms_messages (matricule, phone_numbers, message, status, provider_response, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            [
                $matricule,
                json_encode([$result['phone'] ?? normaliserNumeroSms($phone)], JSON_UNESCAPED_UNICODE),
                $message,
                !empty($result['success']) ? 'sent' : 'failed',
                $trace,
            ]
        );
    } catch (Throwable $e) {
        try {
            $db->query(
                "INSERT INTO aeemci_sms_messages (matricule, phone_numbers, message, status, created_at) VALUES (?, ?, ?, ?, NOW())",
                [
                    $matricule,
                    json_encode([$result['phone'] ?? normaliserNumeroSms($phone)], JSON_UNESCAPED_UNICODE),
                    $message,
                    !empty($result['success']) ? 'sent' : 'failed',
                ]
            );
        } catch (Throwable $e2) {
            error_log('Journalisation SMS indisponible: ' . $e2->getMessage());
        }
    }

    return $result;
}

function getMontantsAutorises($db) {
    static $montants = null;
    if ($montants === null) {
        $rows     = $db->query("SELECT montant FROM seminaire_montants_autorises WHERE actif = 1");
        $montants = array_column($rows, 'montant');
    }
    return $montants;
}

function verifierMontantConforme($somme_paye, $db) {
    $montants_autorises = getMontantsAutorises($db);
    return in_array(floatval($somme_paye), array_map('floatval', $montants_autorises));
}

function appelWave($url) {
    $api_key = "wave_ci_prod_XA-f-CX_LCLs5HfwiUKbyrvPoo9-oc1qjxlIObDoHbIFs-yMePvAVG9yI0ezEGk4SlXZi1v8n_RjCIeX85sQXan_vmcBJMqiGA";
    $curl    = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer {$api_key}",
            "Accept: application/json"
        ]
    ]);
    $response  = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err       = curl_error($curl);
    curl_close($curl);
    return [json_decode($response, true), $http_code, $err];
}

function extraireMontantWave($waveData) {
    $amount   = 0;
    $currency = 'XOF';

    if (isset($waveData['amount']))                   $amount = floatval($waveData['amount']);
    elseif (isset($waveData['total_amount']))         $amount = floatval($waveData['total_amount']);
    elseif (isset($waveData['payment']['amount']))    $amount = floatval($waveData['payment']['amount']);
    elseif (isset($waveData['transaction']['amount'])) $amount = floatval($waveData['transaction']['amount']);

    if (isset($waveData['currency'])) $currency = $waveData['currency'];

    error_log("Montant extrait Wave: $amount $currency");
    return [$amount, $currency];
}

function logAttribution($db, $id, $type, $ancienne, $nouvelle, $raison) {
    $db->query("
        INSERT INTO seminaire_attributions_log
            (id_seminaire, type_attribution, ancienne_valeur, nouvelle_valeur, raison, attribue_par, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ", [$id, $type, $ancienne, $nouvelle, $raison, 'System']);
}

function assignerCarAutomatique($db, $id_seminaire, $transport_choisi, $niveau_seminaire, $ancien_participant) {
    $transport = strtolower(trim((string)$transport_choisi));

    if (in_array($transport, ['personnel', 'transport_personnel', 'transport personnel', 'sans_transport', 'sans transport', '0'], true)) {
        return 'TRANSPORT_PERSONNEL';
    }

    $annee = defined('SENAFOI_YEAR') ? (string)SENAFOI_YEAR : date('Y');
    $niveau = strtoupper(trim((string)$niveau_seminaire));
    $estNouveauSansNiveau = !$ancien_participant
        && ($niveau === '' || $niveau === 'TEST_ENTREE' || $niveau === 'NON AFFECTE' || $niveau === 'NON AFFECTÃ‰');

    $carsReserves = ['CAR_10', 'CAR_11', 'CAR_12'];
    $placeholders = implode(',', array_fill(0, count($carsReserves), '?'));
    $filtreCars = $estNouveauSansNiveau
        ? "AND code IN ($placeholders)"
        : "AND code NOT IN ($placeholders)";

    $paramsCars = array_merge([$annee], $carsReserves);
    $cars = $db->query("
        SELECT code, nom, capacite
        FROM seminaire_cars
        WHERE CAST(annee AS CHAR) = ?
          $filtreCars
        ORDER BY code ASC, id ASC
    ", $paramsCars);

    foreach ($cars as $car) {
        $code = trim((string)($car['code'] ?? ''));
        $capacite = (int)($car['capacite'] ?? 0);
        if ($code === '' || $capacite <= 0) {
            continue;
        }

        $usedRows = $db->query("
            SELECT COUNT(*) AS total
            FROM seminaristes
            WHERE car_transport = ?
              AND id <> ?
              AND CAST(annee_seminaire AS CHAR) = ?
              AND statut_paiement = 'PAYE'
              AND statut_inscription = 'VALIDEE'
        ", [$code, $id_seminaire, $annee]);

        $used = (int)($usedRows[0]['total'] ?? 0);
        if ($used < $capacite) {
            return $code;
        }
    }

    return 'Car suivant';
}

// ── AFFECTATION AUTOMATIQUE DES DORTOIRS ────────────────────────────────────
// Pilotee depuis l'admin SENAFOI26 (page Dortoirs > bouton "Affectation
// automatique"). Tant que le reglage est a 0, aucun dortoir n'est attribue
// (comportement historique : notification par SMS ulterieure).
function affectationDortoirActivee($db) {
    try {
        $rows = $db->query("SELECT valeur FROM senafoi26_settings WHERE cle = 'dortoir_auto_assign' LIMIT 1");
        return (string)($rows[0]['valeur'] ?? '0') === '1';
    } catch (Throwable $e) {
        return false; // table absente => fonctionnalite non activee
    }
}

function assignerDortoirAutomatique($db, $id_seminaire, $sexe, $niveau_seminaire) {
    // La pepiniere garde son dortoir dedie.
    if (strtoupper(trim((string)$niveau_seminaire)) === 'PEPINIERE') {
        return 'PEPINIERE';
    }
    if (!affectationDortoirActivee($db)) {
        return null;
    }

    $annee = defined('SENAFOI_YEAR') ? (string)SENAFOI_YEAR : date('Y');
    $sexeCible = strtoupper(trim((string)$sexe)) === 'F' ? 'F' : 'M';

    try {
        $dortoirs = $db->query("
            SELECT code, capacite FROM seminaire_dortoirs
            WHERE CAST(annee AS CHAR) = ? AND sexe = ? AND capacite > 0
            ORDER BY code ASC, id ASC
        ", [$annee, $sexeCible]);
    } catch (Throwable $e) {
        return null;
    }

    foreach ($dortoirs as $d) {
        $code = trim((string)($d['code'] ?? ''));
        $capacite = (int)($d['capacite'] ?? 0);
        if ($code === '' || $capacite <= 0) continue;

        $usedRows = $db->query("
            SELECT COUNT(*) AS total FROM seminaristes
            WHERE dortoir = ? AND id <> ? AND CAST(annee_seminaire AS CHAR) = ?
              AND statut_paiement = 'PAYE' AND statut_inscription = 'VALIDEE'
        ", [$code, $id_seminaire, $annee]);

        if ((int)($usedRows[0]['total'] ?? 0) < $capacite) {
            return $code;
        }
    }
    return null;
}

function enrichirSeminariste($db, $id, $amount_paid = null, $currency = null) {
    $infos = $db->query("SELECT * FROM seminaristes WHERE id = ?", [$id]);
    $s     = $infos[0] ?? [];

    $s['niveau_seminaire_label'] = getNiveauLabel($s['niveau_seminaire'] ?? '');
    $s['dortoir_label']          = getDortoirLabel($s['dortoir'] ?? '');
    $s['car_label']              = getCarLabel($s['car_transport'] ?? '');
    $s['sexe_label']             = ($s['sexe'] ?? '') === 'M' ? 'Masculin' : 'FÃ©minin';

    $montant = $amount_paid ?? ($s['somme_paye'] ?? 0);
    $devise  = $currency    ?? ($s['devise_paiement'] ?? 'XOF');
    $s['montant_paye_formate'] = formatMontant($montant, $devise);
    $s['montant_paye_brut']    = $montant;
    return $s;
}

function sortirMontantNonConforme($amount, $currency, $waveData = null, $transaction_id = null) {
    http_response_code(400);
    $payload = [
        "success"         => false,
        "message"         => "DÃ©solÃ© vous avez payÃ© une somme pas conforme",
        "montant_paye"    => floatval($amount),
        "devise"          => $currency,
        "montant_formate" => formatMontant($amount, $currency),
        "error_type"      => "MONTANT_NON_CONFORME"
    ];
    if ($transaction_id) $payload['transaction_id'] = $transaction_id;
    if ($waveData) $payload['wave_response_complete'] = $waveData;
    echo json_encode($payload);
    exit();
}

function formatMontant($montant, $devise = 'XOF') {
    return number_format($montant, 0, ',', ' ') . ' ' . $devise;
}

function getNiveauLabel($niveau) {
    $labels = [
        '1AS' => '1Ã¨re AnnÃ©e Secondaire', '2AS' => '2Ã¨me AnnÃ©e Secondaire',
        '3AS' => '3Ã¨me AnnÃ©e Secondaire', '1AF' => '1Ã¨re AnnÃ©e Formation',
        '2AF' => '2Ã¨me AnnÃ©e Formation', '3AF' => '3Ã¨me AnnÃ©e Formation',
        '1BS' => '1Ã¨re AnnÃ©e SupÃ©rieure', '2BS' => '2Ã¨me AnnÃ©e SupÃ©rieure',
        '3BS' => '3Ã¨me AnnÃ©e SupÃ©rieure', '1BF' => '1Ã¨re AnnÃ©e Formation AvancÃ©e',
        '2BF' => '2Ã¨me AnnÃ©e Formation AvancÃ©e', '3BF' => '3Ã¨me AnnÃ©e Formation AvancÃ©e',
        '4' => 'Niveau 4', 'TEST_ENTREE' => "Test d'entrÃ©e requis"
    ];
    return $labels[$niveau] ?? $niveau;
}

function getDortoirLabel($dortoir) {
    $labels = [
        'DORTOIR_A' => 'Dortoir Alpha - Hommes', 'DORTOIR_B' => 'Dortoir Beta - Hommes',
        'DORTOIR_C' => 'Dortoir Gamma - Hommes', 'DORTOIR_D' => 'Dortoir Delta - Hommes',
        'DORTOIR_E' => 'Dortoir Epsilon - Hommes', 'DORTOIR_F' => 'Dortoir Zeta - Hommes',
        'DORTOIR_G' => 'Dortoir Eta - Hommes', 'DORTOIR_H' => 'Dortoir Theta - Hommes',
        'DORTOIR_I' => 'Dortoir Iota - Femmes', 'DORTOIR_J' => 'Dortoir Kappa - Femmes',
        'DORTOIR_K' => 'Dortoir Lambda - Femmes', 'DORTOIR_L' => 'Dortoir Mu - Femmes',
        'DORTOIR_M' => 'Dortoir Nu - Femmes', 'DORTOIR_N' => 'Dortoir Xi - Femmes',
        'DORTOIR_O' => 'Dortoir Omicron - Femmes', 'DORTOIR_P' => 'Dortoir Pi - Femmes',
        'EXTERNE' => 'HÃ©bergement Externe',
        'PEPINIERE' => 'PÃ©piniÃ¨re'
    ];
    return $labels[$dortoir] ?? ($dortoir ?: 'Non assignÃ©');
}

function getCarLabel($car) {
    if (!$car) return 'Non assigné';
    if ($car === 'TRANSPORT_PERSONNEL') return 'Transport personnel';
    if (preg_match('/^CAR_\d+$/i', $car)) return strtoupper($car);
    return $car;
}

try {
    $db = new Database();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur BDD", "details" => $e->getMessage()]);
    exit();
}

try {
    $query = "
        SELECT id, transaction_id, statut_paiement, statut_inscription, payment_status_wave, sexe,
               dortoir, car_transport, somme_paye, devise_paiement,
               transport, contact, numero_wave, created_at,
               niveau_seminaire, a_participe_senafoi
        FROM seminaristes
        WHERE matricule_seminaire = ?
        LIMIT 1
    ";
    $result = $db->query($query, [$matricule]);

    if (empty($result)) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "SÃ©minariste introuvable"]);
        exit();
    }

    $row              = $result[0];
    $id_seminaire     = $row['id'];
    $session_id       = $row['transaction_id'];
    $current_status   = $row['statut_paiement'];
    $current_inscription_status = $row['statut_inscription'] ?? '';
    $current_wave_status = $row['payment_status_wave'] ?? '';
    $sexe             = $row['sexe'];
    $transport_choisi = $row['transport'] ?? 'convoi';
    $ancien_dortoir   = $row['dortoir'];
    $ancien_car       = $row['car_transport'];
    $somme_actuelle   = $row['somme_paye'];
    $devise_actuelle  = $row['devise_paiement'];
    $numero_wave      = $row['numero_wave'];
    $created_at       = $row['created_at'];
    $niveauSeminaire  = $row['niveau_seminaire'] ?? '';
    $ancienParticipant = !empty($row['a_participe_senafoi']);
    $smsPhone = !empty($numero_wave) ? $numero_wave : ($row['contact'] ?? null);
    $smsResult = null;

    if ($current_status === 'PAYE') {
        $normalizedDortoir = $ancien_dortoir;
        if (empty($normalizedDortoir)) {
            $normalizedDortoir = assignerDortoirAutomatique($db, $id_seminaire, $sexe, $niveauSeminaire);
        }

        $normalizedCar = $ancien_car;
        if (empty($normalizedCar)) {
            $normalizedCar = assignerCarAutomatique(
                $db,
                $id_seminaire,
                $transport_choisi,
                $niveauSeminaire,
                $ancienParticipant
            );
        }

        if ($current_inscription_status !== 'VALIDEE' || empty($current_wave_status) || empty($ancien_car) || $normalizedDortoir !== $ancien_dortoir) {
            $db->query(
                "UPDATE seminaristes
                 SET statut_inscription = 'VALIDEE',
                     payment_status_wave = COALESCE(NULLIF(payment_status_wave, ''), 'succeeded'),
                     car_transport = ?,
                     dortoir = ?,
                     updated_at = NOW()
                 WHERE id = ?",
                [$normalizedCar, $normalizedDortoir, $id_seminaire]
            );

            logAttribution(
                $db,
                $id_seminaire,
                'NORMALISATION_PAIEMENT_DEJA_PAYE',
                "STATUT: $current_inscription_status, WAVE: $current_wave_status, DORTOIR: $ancien_dortoir, CAR: $ancien_car",
                "STATUT: VALIDEE, WAVE: succeeded, DORTOIR: " . ($normalizedDortoir ?: 'SMS ULTERIEUR') . ", CAR: $normalizedCar",
                "Normalisation automatique d'une inscription deja marquee PAYE"
            );
        }

        $seminaristeSms = enrichirSeminariste($db, $id_seminaire);
        $smsResult = envoyerSMSValidationSiNecessaire(
            $db,
            $smsPhone,
            $seminaristeSms['prenom'] ?? 'participant',
            $matricule
        );
        error_log('SMS validation inscription dÃ©jÃ  payÃ©e ' . $matricule . ': ' . json_encode($smsResult, JSON_UNESCAPED_UNICODE));
    }

    error_log("=== INFORMATIONS SÃ‰MINARISTE ===");
    error_log("ID: $id_seminaire | Sexe: $sexe | Transport: $transport_choisi");
    error_log("Statut actuel: $current_status | Session Wave: $session_id");

    if ($current_status === 'PAYE' && ($somme_actuelle == 0 || $force_update)) {
        if (empty($session_id)) {
            $seminariste = enrichirSeminariste($db, $id_seminaire);
            echo json_encode([
                "success" => true,
                "message" => "Paiement dÃ©jÃ  confirmÃ© mais montant non rÃ©cupÃ©rable (pas de session Wave)",
                "already_paid" => true,
                "montant_issue" => true,
                "seminariste" => $seminariste
            ]);
            exit();
        }

        $wave_url = "https://api.wave.com/v1/checkout/sessions/{$session_id}";
        list($waveData, $http_code, $err) = appelWave($wave_url);

        if (!$err && $http_code === 200 && $waveData) {
            list($amount_paid, $currency) = extraireMontantWave($waveData);

            if ($amount_paid > 0 && !verifierMontantConforme($amount_paid, $db)) {
                sortirMontantNonConforme($amount_paid, $currency, $waveData);
            }

            if ($amount_paid > 0) {
                $db->query(
                    "UPDATE seminaristes SET somme_paye = ?, devise_paiement = ?, updated_at = NOW() WHERE id = ?",
                    [$amount_paid, $currency, $id_seminaire]
                );
            }

            $seminariste = enrichirSeminariste($db, $id_seminaire, $amount_paid, $currency);
            echo json_encode([
                "success" => true,
                "message" => "Paiement dÃ©jÃ  confirmÃ© - Montant rÃ©cupÃ©rÃ© depuis Wave",
                "already_paid" => true,
                "montant_updated" => ($amount_paid > 0),
                "seminariste" => $seminariste
            ]);
            exit();
        }

        $seminariste = enrichirSeminariste($db, $id_seminaire);
        echo json_encode([
            "success" => true,
            "message" => "Paiement dÃ©jÃ  confirmÃ© mais impossible de rÃ©cupÃ©rer le montant depuis Wave",
            "already_paid" => true,
            "montant_issue" => true,
            "wave_error" => $err ?: "HTTP $http_code",
            "seminariste" => $seminariste
        ]);
        exit();
    }

    if ($current_status === 'PAYE') {
        if (!verifierMontantConforme($somme_actuelle, $db)) {
            sortirMontantNonConforme($somme_actuelle, $devise_actuelle, null);
        }

        $seminariste = enrichirSeminariste($db, $id_seminaire);
        echo json_encode([
            "success" => true,
            "message" => "Paiement dÃ©jÃ  confirmÃ©",
            "already_paid" => true,
            "seminariste" => $seminariste
        ]);
        exit();
    }

    if (empty($session_id)) {
        echo json_encode([
            "success" => false,
            "message" => "Session de paiement non trouvÃ©e",
            "debug_info" => [
                "matricule" => $matricule,
                "id_seminaire" => $id_seminaire,
                "transaction_id" => $session_id,
                "created_at" => $created_at
            ]
        ]);
        exit();
    }

    $wave_url = "https://api.wave.com/v1/checkout/sessions/{$session_id}";
    list($waveData, $http_code, $err) = appelWave($wave_url);

    if ($err) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Erreur API Wave",
            "error" => $err,
            "session_id" => $session_id
        ]);
        exit();
    }

    if ($http_code !== 200) {
        echo json_encode([
            "success" => false,
            "message" => "Erreur Wave - Session invalide ou expirÃ©e",
            "debug_info" => [
                "http_code" => $http_code,
                "session_id" => $session_id,
                "created_at" => $created_at
            ],
            "wave_response_complete" => $waveData
        ]);
        exit();
    }

    $wave_status         = $waveData['checkout_status'] ?? 'indÃ©fini';
    $payment_status_wave = $waveData['payment_status'] ?? 'non spÃ©cifiÃ©';
    $transaction_id      = $waveData['transaction_id'] ?? null;

    list($amount_paid, $currency) = extraireMontantWave($waveData);

    if (!verifierMontantConforme($amount_paid, $db)) {
        $db->query("
            UPDATE seminaristes
            SET dortoir = NULL, car_transport = NULL,
                statut_paiement = 'MONTANT_NON_CONFORME',
                statut_inscription = 'ANNULEE',
                updated_at = NOW()
            WHERE id = ?
        ", [$id_seminaire]);

        sortirMontantNonConforme($amount_paid, $currency, $waveData, $transaction_id);
    }

    if ($wave_status !== 'complete' || !$transaction_id) {
        $db->query("
            UPDATE seminaristes
            SET dortoir = NULL, car_transport = NULL,
                statut_paiement = 'EXPIRE',
                statut_inscription = 'ANNULEE',
                updated_at = NOW()
            WHERE id = ?
        ", [$id_seminaire]);

        echo json_encode([
            "success" => false,
            "message" => "Le paiement n'a pas Ã©tÃ© effectuÃ© ou a expirÃ©. Vos affectations ont Ã©tÃ© annulÃ©es.",
            "wave_status" => $wave_status,
            "payment_status_wave" => $payment_status_wave,
            "transaction_id" => $transaction_id,
            "seminariste" => [
                "matricule_seminaire" => $matricule,
                "id" => $id_seminaire,
                "statut_paiement" => "EXPIRE",
                "statut_inscription" => "ANNULEE"
            ]
        ]);
        exit();
    }

    $nouveauDortoir = assignerDortoirAutomatique($db, $id_seminaire, $sexe, $niveauSeminaire);
    $nouveauCar = assignerCarAutomatique(
        $db,
        $id_seminaire,
        $transport_choisi,
        $niveauSeminaire,
        $ancienParticipant
    );

    $db->query("
        UPDATE seminaristes
        SET statut_paiement = 'PAYE',
            transaction_id = ?,
            payment_status_wave = ?,
            statut_inscription = 'VALIDEE',
            dortoir = ?,
            car_transport = ?,
            somme_paye = ?,
            devise_paiement = ?,
            updated_at = NOW()
        WHERE id = ?
    ", [
        $transaction_id,
        $payment_status_wave,
        $nouveauDortoir,
        $nouveauCar,
        $amount_paid,
        $currency,
        $id_seminaire
    ]);

    logAttribution(
        $db,
        $id_seminaire,
        'VALIDATION_PAIEMENT_AVEC_CAR_AUTO',
        "ANCIEN - DORTOIR: $ancien_dortoir, CAR: $ancien_car, MONTANT: $somme_actuelle",
        "NOUVEAU - DORTOIR: " . ($nouveauDortoir ?: 'SMS ULTERIEUR') . ", CAR: $nouveauCar, MONTANT: $amount_paid $currency",
        "Paiement valide avec attribution automatique. Transaction: $transaction_id, Montant: $amount_paid $currency, Transport: $transport_choisi"
    );

    $seminariste = enrichirSeminariste($db, $id_seminaire, $amount_paid, $currency);

    $smsResult = envoyerSMSValidationSiNecessaire(
        $db,
        $smsPhone,
        $seminariste['prenom'] ?? 'participant',
        $matricule
    );
    error_log('SMS validation ' . $matricule . ': ' . json_encode($smsResult, JSON_UNESCAPED_UNICODE));

    echo json_encode([
        "success" => true,
        "message" => "Paiement confirme et inscription validee. Car attribue automatiquement.",
        "wave_status" => $wave_status,
        "payment_status_wave" => $payment_status_wave,
        "transaction_id" => $transaction_id,
        "payment_info" => [
            "montant_paye" => $amount_paid,
            "devise" => $currency,
            "montant_formate" => formatMontant($amount_paid, $currency)
        ],
        "attributions_effectuees" => [
            "transport_choisi" => $transport_choisi,
            "dortoir_attribue" => $nouveauDortoir,
            "car_attribue" => $nouveauCar,
            "attribution_apres_paiement" => true,
            "message" => $nouveauDortoir
                ? "Votre car et votre dortoir PEPINIERE ont ete attribues automatiquement."
                : "Votre car a ete attribue automatiquement. Le dortoir sera notifie par SMS.",
            "sexe_participant" => $sexe
        ],
        "sms_info" => $smsResult ?? ["skipped" => true, "tous_envoyes" => false],
        "seminariste" => $seminariste
    ]);

} catch (Exception $e) {
    error_log("=== ERREUR EXCEPTION === " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur",
        "details" => $e->getMessage()
    ]);
    exit();
}
