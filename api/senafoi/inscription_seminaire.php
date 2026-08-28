<?php

// ================= HEADERS =================
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ================= INPUT =================
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['data'])) {
    http_response_code(400);
    echo json_encode(["error" => "Requête invalide"]);
    exit();
}

$input  = $data['data'];
$amount = intval($data['amount'] ?? $data['data']['montant'] ?? 0);

if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Montant invalide"]);
    exit();
}

// ================= VALIDATION =================
$requiredFields = ['nom', 'prenom', 'sexe', 'contact', 'transport'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Le champ '$field' est requis"]);
        exit();
    }
}

// ================= DB =================
require_once '../config/database.php';
require_once '../config/seminaire_config.php';

try {
    $db = new Database();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur connexion BDD"]);
    exit();
}

// ================= DATA =================
$anneeSeminaire = date('Y');

$prenom    = ucfirst(strtolower(trim($input['prenom'])));
$nom       = strtoupper(trim($input['nom']));
$sexe      = strtoupper(trim($input['sexe']));
$contact   = trim($input['contact']);
$transport = trim($input['transport']);

$numeroWave      = $input['numeroWave']      ?? null;
$niveauEtude     = trim($input['niveauEtude'] ?? '');
$age             = intval($input['age']       ?? 0);
$contactParent   = $input['contactParent']    ?? null;
$tailleTshirt    = $input['tailleTshirt']     ?? null;
$photo           = $input['photo']            ?? null;
$photoId         = $input['photoId']          ?? null;
$ville           = trim($input['ville'] ?? $input['secretariatRegional'] ?? '');

// Santé
$aUneMaladie  = ($input['aUneMaladie'] ?? 'non') === 'oui' ? 1 : 0;
$maladieDetail = $input['maladieDetail'] ?? null;

// Ancien participant
$aParticipeSenafoi  = !empty($input['a_participe_senafoi'])  ? 1 : 0;
$niveauAnnePassee   = $input['niveau_annee_passee']          ?? null;
$participantTrouve  = !empty($input['participant_trouve'])   ? 1 : 0;
$decisionParticipant = $input['decision_participant']        ?? null;

// ================= NIVEAU SÉMINAIRE =================
// Priorité : si ancien participant avec niveau défini → on le respecte
// La règle pépinière ne s'applique QUE aux nouveaux participants
$niveauSeminaire = $input['niveauSeminaire'] ?? SeminaireConfig::NIVEAU_DEFAUT_NOUVEAU;

if (!$aParticipeSenafoi) {
    // Nouveau participant seulement → appliquer règle pépinière
    if (
        $age < 9 ||
        preg_match('/^primaire$/i', $niveauEtude) ||
        preg_match('/^(cp|ce|cm)/i', $niveauEtude)
    ) {
        $niveauSeminaire = 'PEPINIERE';
    }
}

$dortoirInitial = strtoupper(trim((string)$niveauSeminaire)) === 'PEPINIERE'
    ? 'PEPINIERE'
    : null;

// ================= MATRICULE =================
function genererMatriculeUnique($db) {
    $annee = date('Y');
    $prefixe = "SEM" . $annee;
    $lockName = "senafoi_matricule_" . $annee;

    $lock = $db->query("SELECT GET_LOCK(?, 10) AS locked", [$lockName]);
    if (empty($lock) || intval($lock[0]['locked'] ?? 0) !== 1) {
        throw new Exception("Impossible de verrouiller la génération du matricule");
    }

    $rows = $db->query(
        "SELECT MAX(CAST(SUBSTRING(matricule_seminaire, 8) AS UNSIGNED)) AS dernier_numero
         FROM seminaristes
         WHERE matricule_seminaire LIKE ?",
        [$prefixe . '%']
    );

    $next = intval($rows[0]['dernier_numero'] ?? 0) + 1;
    while (true) {
        $matricule = $prefixe . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
        $check = $db->query(
            "SELECT id FROM seminaristes WHERE matricule_seminaire = ? LIMIT 1",
            [$matricule]
        );
        if (empty($check)) {
            return $matricule;
        }
        $next++;
    }
}

try {
    $matricule = genererMatriculeUnique($db);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur génération matricule"]);
    exit();
}

// ================= OPTIONAL =================
$secretariatRegional = $ville;
$sousComite          = $input['sousComite'] ?? null;
$qualite             = $input['qualite']    ?? null;

// ================= INSERT EN BDD =================
try {
    $sql = "
    INSERT INTO seminaristes (
        prenom, nom, sexe, contact,
        contact_parent,
        niveau_etude, niveau_seminaire,
        transport, numero_wave, dortoir,
        matricule_seminaire,
        annee_seminaire,
        secretariat_regional,
        sous_comite,
        qualite,
        taille_tshirt,
        photo,
        photo_id,
        malade,
        detail_malade,
        a_participe_senafoi,
        niveau_annee_passee,
        participant_trouve,
        decision_participant,
        statut_paiement,
        statut_inscription,
        somme_paye,
        devise_paiement,
        date_inscription,
        created_at,
        updated_at
    ) VALUES (
        ?, ?, ?, ?,
        ?,
        ?, ?, ?,
        ?, ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        'EN_ATTENTE', 'EN_COURS',
        ?,
        'XOF',
        NOW(), NOW(), NOW()
    )
    ";

    $db->query($sql, [
        $prenom,
        $nom,
        $sexe,
        $contact,
        $contactParent,
        $niveauEtude,
        $niveauSeminaire,
        $transport,
        $numeroWave,
        $dortoirInitial,
        $matricule,
        $anneeSeminaire,
        $secretariatRegional,
        $sousComite,
        $qualite,
        $tailleTshirt,
        $photo,
        $photoId,
        $aUneMaladie,
        $maladieDetail,
        $aParticipeSenafoi,
        $niveauAnnePassee,
        $participantTrouve,
        $decisionParticipant,
        $amount,
    ]);
    $db->query("SELECT RELEASE_LOCK(?) AS released", ["senafoi_matricule_" . date('Y')]);

} catch (Exception $e) {
    try {
        $db->query("SELECT RELEASE_LOCK(?) AS released", ["senafoi_matricule_" . date('Y')]);
    } catch (Exception $ignored) {}
    http_response_code(500);
    echo json_encode([
        "error"   => "Erreur enregistrement BDD",
        "details" => $e->getMessage()
    ]);
    exit();
}

// ================= APPEL API WAVE =================
$wavePayload = [
    "amount"      => (string) $amount,
    "currency"    => "XOF",
    "success_url" => "https://aeemci-ce.ci/seminaire_paye/$matricule",
    "error_url"   => "https://aeemci-ce.ci/seminaire_paye/$matricule"
];

$ch = curl_init("https://api.wave.com/v1/checkout/sessions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wavePayload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer wave_ci_prod_XA-f-CX_LCLs5HfwiUKbyrvPoo9-oc1qjxlIObDoHbIFs-yMePvAVG9yI0ezEGk4SlXZi1v8n_RjCIeX85sQXan_vmcBJMqiGA",
    "Content-Type: application/json"
]);

$waveResponse = curl_exec($ch);
$httpStatus   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$waveResult   = json_decode($waveResponse, true);
curl_close($ch);

// ================= RÉPONSE FINALE =================
if (($httpStatus === 200 || $httpStatus === 201) && isset($waveResult['wave_launch_url'])) {

    $transactionId = $waveResult['id'] ?? null;

    $db->query(
        "UPDATE seminaristes
         SET transaction_id = ?, updated_at = NOW()
         WHERE matricule_seminaire = ?",
        [$transactionId, $matricule]
    );

    echo json_encode([
        "success"         => true,
        "message"         => "Inscription enregistrée",
        "matricule"       => $matricule,
        "annee"           => $anneeSeminaire,
        "wave_launch_url" => $waveResult['wave_launch_url']
    ]);

} else {

    $db->query(
        "UPDATE seminaristes
         SET statut_paiement = 'ERREUR_WAVE', updated_at = NOW()
         WHERE matricule_seminaire = ?",
        [$matricule]
    );

    http_response_code(500);
    echo json_encode([
        "error"       => "Inscription enregistrée mais paiement Wave échoué",
        "matricule"   => $matricule,
        "details"     => $waveResult,
        "http_status" => $httpStatus
    ]);
}
