<?php
// =============================================================================
// verify_payment.php — Vérification paiement Wave + SMS KYA SMS
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
    echo json_encode(["error" => "Méthode non autorisée"]);
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

define('SMS_API_URL',         'https://api.aeemci-ce.ci/config/sms_api.php');
define('SMS_INTERNAL_SECRET', 'aeemci_sms_secret_2026');

function envoyerSMSKya($phones, $message, $matricule = null) {
    $apiKey  = "kyasmsd2ccf32b4aa62311eee9da3051b60bba18bb5236249abf9d1c5e5e873f";
    $baseUrl = "https://route.kyasms.com/api/v3/sms/send";

    // Normalisation numéros
    $rawPhones = is_array($phones) ? $phones : [$phones];
    $normalizedPhones = [];
    foreach ($rawPhones as $p) {
        $num = preg_replace('/\D/', '', $p);
        if (!str_starts_with($num, '225')) {
            $num = '225' . substr($num, -10);
        }
        $normalizedPhones[] = $num;
    }

    $allOk   = true;
    $results = [];

    foreach ($normalizedPhones as $phone) {
        $payload = json_encode([
            "from"    => "AEEMCI CE",
            "to"      => $phone,
            "type"    => "text",
            "message" => $message
        ]);

        $ch = curl_init($baseUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "APIKEY: {$apiKey}"
            ],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $success = ($response !== false && empty($curlError) && $httpCode < 400);

        if ($success) {
            error_log("✅ SMS KYA envoyé → $phone (HTTP $httpCode)");
        } else {
            error_log("❌ Échec SMS KYA → $phone (HTTP $httpCode) : $response | cURL: $curlError");
            $allOk = false;
        }

        $results[$phone] = [
            "status"    => $success ? "sent" : "failed",
            "http_code" => $httpCode,
            "response"  => json_decode($response, true) ?? $response
        ];
    }

    return [
        "success"      => $allOk,
        "tous_envoyes" => $allOk,
        "details"      => $results
    ];
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

function enrichirSeminariste($db, $id, $amount_paid = null, $currency = null) {
    $infos = $db->query("SELECT * FROM seminaristes WHERE id = ?", [$id]);
    $s     = $infos[0] ?? [];

    $s['niveau_seminaire_label'] = getNiveauLabel($s['niveau_seminaire'] ?? '');
    $s['dortoir_label']          = getDortoirLabel($s['dortoir'] ?? '');
    $s['car_label']              = getCarLabel($s['car_transport'] ?? '');
    $s['sexe_label']             = ($s['sexe'] ?? '') === 'M' ? 'Masculin' : 'Féminin';

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
        "message"         => "Désolé vous avez payé une somme pas conforme",
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
        '1AS' => '1ère Année Secondaire', '2AS' => '2ème Année Secondaire',
        '3AS' => '3ème Année Secondaire', '1AF' => '1ère Année Formation',
        '2AF' => '2ème Année Formation', '3AF' => '3ème Année Formation',
        '1BS' => '1ère Année Supérieure', '2BS' => '2ème Année Supérieure',
        '3BS' => '3ème Année Supérieure', '1BF' => '1ère Année Formation Avancée',
        '2BF' => '2ème Année Formation Avancée', '3BF' => '3ème Année Formation Avancée',
        '4' => 'Niveau 4', 'TEST_ENTREE' => "Test d'entrée requis"
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
        'EXTERNE' => 'Hébergement Externe'
    ];
    return $labels[$dortoir] ?? ($dortoir ?: 'Non assigné');
}

function getCarLabel($car) {
    $labels = [
        'CAR_01' => 'Car Alpha', 'CAR_02' => 'Car Beta',
        'CAR_03' => 'Car Gamma', 'CAR_04' => 'Car Delta',
        'CAR_05' => 'Car Epsilon', 'CAR_06' => 'Car Zeta',
        'CAR_07' => 'Car Eta', 'CAR_08' => 'Car Theta',
        'CAR_09' => 'Car Iota', 'CAR_10' => 'Car Kappa',
        'CAR_11' => 'Car Lambda', 'TRANSPORT_PERSONNEL' => 'Transport Personnel'
    ];
    return $labels[$car] ?? ($car ?: 'Non assigné');
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
        SELECT id, transaction_id, statut_paiement, sexe,
               dortoir, car_transport, somme_paye, devise_paiement,
               transport, contact, numero_wave, created_at
        FROM seminaristes
        WHERE matricule_seminaire = ?
        LIMIT 1
    ";
    $result = $db->query($query, [$matricule]);

    if (empty($result)) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Séminariste introuvable"]);
        exit();
    }

    $row              = $result[0];
    $id_seminaire     = $row['id'];
    $session_id       = $row['transaction_id'];
    $current_status   = $row['statut_paiement'];
    $sexe             = $row['sexe'];
    $transport_choisi = $row['transport'] ?? 'convoi';
    $ancien_dortoir   = $row['dortoir'];
    $ancien_car       = $row['car_transport'];
    $somme_actuelle   = $row['somme_paye'];
    $devise_actuelle  = $row['devise_paiement'];
    $numero_wave      = $row['numero_wave'];
    $created_at       = $row['created_at'];

    error_log("=== INFORMATIONS SÉMINARISTE ===");
    error_log("ID: $id_seminaire | Sexe: $sexe | Transport: $transport_choisi");
    error_log("Statut actuel: $current_status | Session Wave: $session_id");

    if ($current_status === 'PAYE' && ($somme_actuelle == 0 || $force_update)) {
        if (empty($session_id)) {
            $seminariste = enrichirSeminariste($db, $id_seminaire);
            echo json_encode([
                "success" => true,
                "message" => "Paiement déjà confirmé mais montant non récupérable (pas de session Wave)",
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
                "message" => "Paiement déjà confirmé - Montant récupéré depuis Wave",
                "already_paid" => true,
                "montant_updated" => ($amount_paid > 0),
                "seminariste" => $seminariste
            ]);
            exit();
        }

        $seminariste = enrichirSeminariste($db, $id_seminaire);
        echo json_encode([
            "success" => true,
            "message" => "Paiement déjà confirmé mais impossible de récupérer le montant depuis Wave",
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
            "message" => "Paiement déjà confirmé",
            "already_paid" => true,
            "seminariste" => $seminariste
        ]);
        exit();
    }

    if (empty($session_id)) {
        echo json_encode([
            "success" => false,
            "message" => "Session de paiement non trouvée",
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
            "message" => "Erreur Wave - Session invalide ou expirée",
            "debug_info" => [
                "http_code" => $http_code,
                "session_id" => $session_id,
                "created_at" => $created_at
            ],
            "wave_response_complete" => $waveData
        ]);
        exit();
    }

    $wave_status         = $waveData['checkout_status'] ?? 'indéfini';
    $payment_status_wave = $waveData['payment_status'] ?? 'non spécifié';
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
            "message" => "Le paiement n'a pas été effectué ou a expiré. Vos affectations ont été annulées.",
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

    $smsResult = null;
    if (!empty($numero_wave)) {
        $seminaristeTmp  = enrichirSeminariste($db, $id_seminaire, $amount_paid, $currency);
        $montant_formate = formatMontant($amount_paid, $currency);
        $prenom          = $seminaristeTmp['prenom'] ?? 'participant';

        $message_sms = "Salam $prenom! Bienvenue au SENAFOI 26! Paiement de $montant_formate confirme. Matricule: $matricule. Recu: urlr.me/eJrqQR RDV 03 Aout. - AEEMCI";

        $smsResult = envoyerSMSKya($numero_wave, $message_sms, $matricule);

        if (!empty($smsResult['tous_envoyes'])) {
            error_log("✅ SMS KYA envoyé avant validation BDD vers numero_wave");
        } else {
            error_log("⚠️ SMS KYA partiellement échoué — on continue quand même la validation");
        }
    } else {
        error_log("⚠️ Pas de numéro Wave renseigné pour $matricule — SMS ignoré");
    }

    $nouveauDortoir = SeminaireConfig::attribuerDortoir($db, $sexe);
    $nouveauCar     = SeminaireConfig::attribuerCar($db, $transport_choisi);

    if ($sexe === 'F' && empty($nouveauDortoir)) {
        $nouveauDortoir = 'EXTERNE';
    }

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
        'VALIDATION_PAIEMENT_ET_ATTRIBUTION',
        "ANCIEN - DORTOIR: $ancien_dortoir, CAR: $ancien_car, MONTANT: $somme_actuelle",
        "NOUVEAU - DORTOIR: $nouveauDortoir, CAR: $nouveauCar, MONTANT: $amount_paid $currency",
        "Paiement validé. Transaction: $transaction_id, Montant: $amount_paid $currency, Transport: $transport_choisi"
    );

    $seminariste = enrichirSeminariste($db, $id_seminaire, $amount_paid, $currency);

    echo json_encode([
        "success" => true,
        "message" => "Paiement confirmé et inscription validée avec attribution automatique",
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