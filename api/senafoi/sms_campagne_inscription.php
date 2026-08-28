<?php
/**
 * CAMPAGNE SMS — Confirmation d'inscription SENAFOI 2026
 *
 * Envoie a chaque seminariste inscrit et valide AVANT la date de coupure
 * un SMS contenant : confirmation, matricule, dortoir et lien du recu.
 *
 * Le rythme est de 1 SMS toutes les 2 secondes : c'est le NAVIGATEUR qui
 * pilote la cadence (un appel par destinataire), ce qui evite les coupures
 * liees au max_execution_time de PHP et permet de reprendre a tout moment.
 *
 * UTILISATION
 *   1) Apercu (aucun envoi)  : sms_campagne_inscription.php?token=VOTRE_TOKEN
 *   2) Cliquer sur "Demarrer l'envoi" dans l'interface.
 *
 * Chaque destinataire est journalise : relancer la page ne renvoie jamais
 * deux fois le meme SMS.
 *
 * A SUPPRIMER du serveur une fois la campagne terminee.
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Abidjan');
set_time_limit(60);

// ── Reglages de la campagne ────────────────────────────────────────────────
define('CAMPAGNE', 'inscription_2026_07_26_parents');  // cible : numero du parent
define('CAMPAGNE_PRECEDENTE', 'inscription_2026_07_26');  // ancienne campagne (numero de paiement)
define('DATE_COUPURE', '2026-07-26 17:43:00');   // seules les inscriptions AVANT cette date
define('CAMPAGNE_TOKEN', 'senafoi26_campagne_sms');  // a changer avant usage
define('SMS_MAX_CARACTERES', 155);
define('LIEN_RECU', 'aeemci-ce.ci/seminaire_paye/');

define('KYA_SMS_URL', 'https://route.kyasms.net/api/v3/sms/send');
define('KYA_SMS_API_KEY', 'kyasmsd2ccf32b4aa62311eee9da3051b60bba18bb5236249abf9d1c5e5e873f');
define('KYA_SMS_SENDER', 'AEEMCI');

// ── Securite ───────────────────────────────────────────────────────────────
$token = $_GET['token'] ?? '';
if (!hash_equals(CAMPAGNE_TOKEN, (string)$token)) {
    http_response_code(403);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['success' => false, 'message' => 'Token requis.'], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once '../config/database.php';
$db = new Database();

// ── Outils ─────────────────────────────────────────────────────────────────
function sansAccents(string $t): string {
    $map = [
        'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A',
        'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E',
        'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I',
        'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O',
        'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U',
        'ç'=>'c','Ç'=>'C','ñ'=>'n','Ñ'=>'N','œ'=>'oe','Œ'=>'OE','æ'=>'ae','Æ'=>'AE',
        '’'=>"'",'‘'=>"'",'“'=>'"','”'=>'"','–'=>'-','—'=>'-','…'=>'...','·'=>'-',
    ];
    $t = strtr($t, $map);
    // Tout caractere non ASCII restant est retire (GSM-7 uniquement)
    return preg_replace('/[^\x20-\x7E]/', '', $t);
}

/**
 * Numero destinataire : on vise le PARENT en priorite.
 * Repli sur le contact du seminariste puis sur le numero Wave uniquement
 * si aucun numero de parent n'est renseigne.
 */
function choisirDestinataire(array $s): array {
    $parent = normaliserNumeroSms($s['contact_parent'] ?? '');
    if (strlen($parent) >= 12) {
        return ['numero' => $parent, 'source' => 'parent'];
    }
    $perso = normaliserNumeroSms($s['contact'] ?? '');
    if (strlen($perso) >= 12) {
        return ['numero' => $perso, 'source' => 'contact_seminariste'];
    }
    $wave = normaliserNumeroSms($s['numero_wave'] ?? '');
    if (strlen($wave) >= 12) {
        return ['numero' => $wave, 'source' => 'numero_wave'];
    }
    return ['numero' => '', 'source' => 'aucun'];
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

/** Construit le message, sans accents et borne a 155 caracteres. */
function construireMessage(string $matricule, ?string $dortoir): string {
    $mat = sansAccents(trim($matricule));
    $d = sansAccents(trim((string)$dortoir));
    if ($d === '') $d = 'a venir';

    $faire = static fn(string $dort) =>
        "Inscription SENAFOI 26 validee. Matricule: {$mat}. Dortoir: {$dort}. Recu: "
        . LIEN_RECU . "{$mat} AEEMCI";

    $msg = $faire($d);

    // Si le nom du dortoir fait deborder, on le raccourcit progressivement
    if (strlen($msg) > SMS_MAX_CARACTERES) {
        $marge = strlen($msg) - SMS_MAX_CARACTERES;
        $nouvelleLongueur = max(3, strlen($d) - $marge);
        $d = rtrim(substr($d, 0, $nouvelleLongueur));
        $msg = $faire($d);
    }
    // Filet de securite absolu
    if (strlen($msg) > SMS_MAX_CARACTERES) {
        $msg = substr($msg, 0, SMS_MAX_CARACTERES);
    }
    return $msg;
}

function envoyerSms(string $numero, string $message): array {
    // IMPORTANT : ne pas ajouter 'callback_url', KYA rejette la requete.
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
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    $reponse = curl_exec($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    $decoded = json_decode((string)$reponse, true);
    $success = !$curlError && $httpCode >= 200 && $httpCode < 400
        && ((($decoded['reason'] ?? '') === 'success') || !empty($decoded['data']));

    return [
        'success' => $success,
        'http_code' => $httpCode,
        'curl_error' => $curlError ?: null,
        'reponse' => substr((string)$reponse, 0, 300),
    ];
}

// ── Table de suivi de campagne (dediee, n'altere rien d'existant) ──────────
try {
    $db->query("
        CREATE TABLE IF NOT EXISTS aeemci_sms_campagne_envois (
          id INT UNSIGNED NOT NULL AUTO_INCREMENT,
          campagne VARCHAR(60) NOT NULL,
          matricule VARCHAR(60) NOT NULL,
          telephone VARCHAR(30) DEFAULT NULL,
          message TEXT DEFAULT NULL,
          statut VARCHAR(20) NOT NULL DEFAULT 'envoye',
          detail TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (id),
          UNIQUE KEY uq_campagne_matricule (campagne, matricule)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
} catch (Throwable $e) {
    // On continue : sans la table, l'anti-doublon sera simplement inactif.
}

/**
 * Seminaristes dont le NUMERO DU PARENT a deja recu un SMS.
 *
 * Deux origines possibles :
 *   - la campagne precedente, si le numero utilise etait aussi celui du parent ;
 *   - les SMS de validation automatiques envoyes lors de l'inscription.
 *
 * Ces personnes sont exclues de la nouvelle campagne pour ne pas payer
 * ni envoyer deux fois le meme message au meme telephone.
 */
function parentsDejaJoints($db): array {
    $out = [];

    // a) Campagne precedente : le numero reellement utilise est stocke
    try {
        $rows = $db->query("
            SELECT c.matricule, c.telephone, s.contact_parent
            FROM aeemci_sms_campagne_envois c
            JOIN seminaristes s ON s.matricule_seminaire = c.matricule
            WHERE c.campagne = ? AND c.statut = 'envoye'
        ", [CAMPAGNE_PRECEDENTE]);
        foreach ($rows as $r) {
            $parent = normaliserNumeroSms($r['contact_parent'] ?? '');
            $utilise = normaliserNumeroSms($r['telephone'] ?? '');
            if ($parent !== '' && $parent === $utilise) {
                $out[$r['matricule']] = ['numero' => $parent, 'origine' => 'campagne_precedente'];
            }
        }
    } catch (Throwable $e) { /* table absente : on continue */ }

    // b) SMS de validation automatiques (update_payment.php)
    try {
        $rows = $db->query("
            SELECT m.matricule, m.phone_numbers, s.contact_parent
            FROM aeemci_sms_messages m
            JOIN seminaristes s ON s.matricule_seminaire = m.matricule
            WHERE m.status = 'sent' AND m.matricule IS NOT NULL
        ");
        foreach ($rows as $r) {
            $mat = $r['matricule'];
            if (isset($out[$mat])) continue;
            $parent = normaliserNumeroSms($r['contact_parent'] ?? '');
            if ($parent === '') continue;
            $nums = json_decode((string)($r['phone_numbers'] ?? '[]'), true);
            if (!is_array($nums)) continue;
            foreach ($nums as $n) {
                if (normaliserNumeroSms($n) === $parent) {
                    $out[$mat] = ['numero' => $parent, 'origine' => 'sms_validation'];
                    break;
                }
            }
        }
    } catch (Throwable $e) { /* on continue */ }

    return $out;
}

/** Liste des destinataires restants (non encore traites avec succes). */
function destinataires($db, bool $seulementRestants = true): array {
    $sql = "
        SELECT s.id, s.matricule_seminaire, s.prenom, s.nom, s.contact, s.contact_parent, s.numero_wave,
               s.dortoir, s.date_inscription
        FROM seminaristes s
        WHERE CAST(s.annee_seminaire AS CHAR) = '2026'
          AND s.statut_paiement = 'PAYE'
          AND s.statut_inscription = 'VALIDEE'
          AND s.date_inscription <= ?
        ORDER BY s.date_inscription ASC, s.id ASC
    ";
    $rows = $db->query($sql, [DATE_COUPURE]);

    if (!$seulementRestants) return $rows;

    try {
        $faits = $db->query(
            "SELECT matricule FROM aeemci_sms_campagne_envois WHERE campagne = ? AND statut = 'envoye'",
            [CAMPAGNE]
        );
        $dejaFaits = array_flip(array_column($faits, 'matricule'));
    } catch (Throwable $e) {
        $dejaFaits = [];
    }

    // On retire aussi ceux dont le parent a deja recu le message
    $parentsJoints = parentsDejaJoints($db);

    return array_values(array_filter(
        $rows,
        static fn($r) => !isset($dejaFaits[$r['matricule_seminaire']])
            && !isset($parentsJoints[$r['matricule_seminaire']])
    ));
}

// ══════════════════════════════════════════════════════════════════════════
// API : verification des doublons parent
// ══════════════════════════════════════════════════════════════════════════
$action = $_GET['action'] ?? '';

if ($action === 'analyse') {
    header('Content-Type: application/json; charset=UTF-8');

    $tous = destinataires($db, false);
    $parentsJoints = parentsDejaJoints($db);

    // Repartition par origine
    $parCampagne = 0; $parValidation = 0;
    foreach ($parentsJoints as $p) {
        if ($p['origine'] === 'campagne_precedente') $parCampagne++;
        else $parValidation++;
    }

    // Detail lisible (limite a 50 lignes pour l'affichage)
    $detail = [];
    $index = [];
    foreach ($tous as $r) $index[$r['matricule_seminaire']] = $r;
    $n = 0;
    foreach ($parentsJoints as $mat => $info) {
        if ($n++ >= 50) break;
        $r = $index[$mat] ?? null;
        $detail[] = [
            'matricule' => $mat,
            'nom' => $r ? trim(($r['prenom'] ?? '') . ' ' . ($r['nom'] ?? '')) : '',
            'numero_parent' => $info['numero'],
            'origine' => $info['origine'],
        ];
    }

    // Envois de la campagne precedente, tous confondus
    $totalPrecedente = 0;
    try {
        $rows = $db->query(
            "SELECT COUNT(*) AS n FROM aeemci_sms_campagne_envois WHERE campagne = ? AND statut = 'envoye'",
            [CAMPAGNE_PRECEDENTE]
        );
        $totalPrecedente = (int)($rows[0]['n'] ?? 0);
    } catch (Throwable $e) { /* ignore */ }

    echo json_encode([
        'success' => true,
        'total_eligibles' => count($tous),
        'envois_campagne_precedente' => $totalPrecedente,
        'parents_deja_joints' => count($parentsJoints),
        'dont_campagne_precedente' => $parCampagne,
        'dont_sms_validation' => $parValidation,
        'economie_fcfa' => count($parentsJoints) * 15,
        'detail' => $detail,
        'explication' => 'Ces seminaristes sont exclus automatiquement de la nouvelle campagne : '
            . 'le numero de leur parent a deja recu le message (numero de paiement identique au numero du parent, '
            . 'ou SMS de validation deja parti sur ce numero).',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'liste') {
    header('Content-Type: application/json; charset=UTF-8');
    $tous = destinataires($db, false);
    $restants = destinataires($db, true);

    $apercu = [];
    $sansNumero = 0;
    foreach (array_slice($restants, 0, 5) as $r) {
        $msg = construireMessage($r['matricule_seminaire'], $r['dortoir']);
        $dest = choisirDestinataire($r);
        $apercu[] = [
            'nom' => trim(($r['prenom'] ?? '') . ' ' . ($r['nom'] ?? '')),
            'matricule' => $r['matricule_seminaire'],
            'dortoir' => $r['dortoir'] ?: '(aucun)',
            'telephone' => $dest['numero'],
            'source' => $dest['source'],
            'message' => $msg,
            'longueur' => strlen($msg),
        ];
    }

    $sansParent = 0;
    foreach ($restants as $r) {
        $d = choisirDestinataire($r);
        if ($d['numero'] === '') $sansNumero++;
        elseif ($d['source'] !== 'parent') $sansParent++;
    }

    echo json_encode([
        'success' => true,
        'campagne' => CAMPAGNE,
        'date_coupure' => DATE_COUPURE,
        'total_eligibles' => count($tous),
        'restants' => count($restants),
        'deja_envoyes' => count($tous) - count($restants),
        'parents_deja_joints' => count(parentsDejaJoints($db)),
        'sans_numero' => $sansNumero,
        'sans_parent_repli' => $sansParent,
        'cout_estime_fcfa' => count($restants) * 15,
        'apercu' => $apercu,
        'file' => array_map(static fn($r) => [
            'matricule' => $r['matricule_seminaire'],
            'nom' => trim(($r['prenom'] ?? '') . ' ' . ($r['nom'] ?? '')),
        ], $restants),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ══════════════════════════════════════════════════════════════════════════
// API : envoi d'UN destinataire (appelee toutes les 2 s par le navigateur)
// ══════════════════════════════════════════════════════════════════════════
if ($action === 'envoyer') {
    header('Content-Type: application/json; charset=UTF-8');
    $matricule = strtoupper(trim((string)($_GET['matricule'] ?? '')));
    if ($matricule === '') {
        echo json_encode(['success' => false, 'message' => 'Matricule requis.']);
        exit;
    }

    // Anti-doublon
    try {
        $deja = $db->query(
            "SELECT id FROM aeemci_sms_campagne_envois WHERE campagne = ? AND matricule = ? AND statut = 'envoye' LIMIT 1",
            [CAMPAGNE, $matricule]
        );
        if (!empty($deja)) {
            echo json_encode(['success' => true, 'ignore' => true, 'matricule' => $matricule, 'message' => 'Deja envoye']);
            exit;
        }
    } catch (Throwable $e) { /* on continue */ }

    $rows = $db->query("
        SELECT matricule_seminaire, prenom, nom, contact, contact_parent, numero_wave, dortoir, date_inscription,
               statut_paiement, statut_inscription
        FROM seminaristes
        WHERE matricule_seminaire = ? AND CAST(annee_seminaire AS CHAR) = '2026' LIMIT 1
    ", [$matricule]);
    $s = $rows[0] ?? null;

    if (!$s) {
        echo json_encode(['success' => false, 'matricule' => $matricule, 'message' => 'Introuvable']);
        exit;
    }
    if ($s['statut_paiement'] !== 'PAYE' || $s['statut_inscription'] !== 'VALIDEE') {
        echo json_encode(['success' => false, 'matricule' => $matricule, 'message' => 'Inscription non validee']);
        exit;
    }
    if ($s['date_inscription'] > DATE_COUPURE) {
        echo json_encode(['success' => false, 'matricule' => $matricule, 'message' => 'Inscrit apres la date de coupure']);
        exit;
    }

    $dest = choisirDestinataire($s);
    $numero = $dest['numero'];
    $nom = trim(($s['prenom'] ?? '') . ' ' . ($s['nom'] ?? ''));

    if (strlen($numero) < 12) {
        try {
            $db->query("
                INSERT INTO aeemci_sms_campagne_envois (campagne, matricule, telephone, message, statut, detail, created_at)
                VALUES (?, ?, ?, ?, 'sans_numero', 'Aucun numero parent ni contact exploitable', NOW())
                ON DUPLICATE KEY UPDATE statut = VALUES(statut), detail = VALUES(detail)
            ", [CAMPAGNE, $matricule, $numero, '']);
        } catch (Throwable $e) { /* ignore */ }
        echo json_encode(['success' => false, 'matricule' => $matricule, 'nom' => $nom, 'message' => 'Aucun numero parent exploitable']);
        exit;
    }

    $message = construireMessage($matricule, $s['dortoir']);
    $res = envoyerSms($numero, $message);

    try {
        $db->query("
            INSERT INTO aeemci_sms_campagne_envois (campagne, matricule, telephone, message, statut, detail, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE statut = VALUES(statut), detail = VALUES(detail), created_at = NOW()
        ", [
            CAMPAGNE, $matricule, $numero, $message,
            $res['success'] ? 'envoye' : 'echec',
            json_encode(['source_numero' => $dest['source'], 'http' => $res['http_code'], 'curl' => $res['curl_error'], 'rep' => $res['reponse']], JSON_UNESCAPED_UNICODE),
        ]);
    } catch (Throwable $e) { /* ignore */ }

    // Journal general
    try {
        $db->query(
            "INSERT INTO aeemci_sms_messages (matricule, phone_numbers, message, status, created_at) VALUES (?, ?, ?, ?, NOW())",
            [$matricule, json_encode([$numero]), $message, $res['success'] ? 'sent' : 'failed']
        );
    } catch (Throwable $e) { /* ignore */ }

    echo json_encode([
        'success' => $res['success'],
        'matricule' => $matricule,
        'nom' => $nom,
        'telephone' => $numero,
        'source' => $dest['source'],
        'longueur' => strlen($message),
        'message' => $res['success'] ? 'Envoye' : ('Echec HTTP ' . $res['http_code']),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ══════════════════════════════════════════════════════════════════════════
// INTERFACE
// ══════════════════════════════════════════════════════════════════════════
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Campagne SMS — Inscriptions SENAFOI 2026</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f1f5f9;color:#0f172a;padding:24px}
  .wrap{max-width:900px;margin:0 auto;display:grid;gap:16px}
  .card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;box-shadow:0 10px 30px rgba(15,23,42,.06)}
  h1{font-size:22px;margin-bottom:6px}
  .sub{color:#64748b;font-size:13.5px}
  .kpis{display:grid;grid-template-columns:repeat(5,1fr);gap:10px}
  .kpi{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px;text-align:center}
  .kpi b{display:block;font-size:24px;color:#15803d}
  .kpi.warn b{color:#c2410c}
  .kpi span{font-size:11.5px;color:#64748b;font-weight:700}
  .preview{background:#0f172a;color:#e2e8f0;border-radius:12px;padding:14px;font-family:ui-monospace,Menlo,monospace;font-size:12.5px;line-height:1.7;overflow:auto}
  .len{color:#4ade80}
  .bar{height:12px;background:#e2e8f0;border-radius:8px;overflow:hidden;margin:12px 0}
  .bar i{display:block;height:100%;background:linear-gradient(90deg,#16a34a,#4ade80);width:0;transition:width .3s}
  button{border:0;border-radius:11px;padding:13px 20px;font-weight:800;font-size:14px;cursor:pointer;font-family:inherit}
  .go{background:#16a34a;color:#fff}.stop{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
  button:disabled{opacity:.5;cursor:not-allowed}
  .actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
  .log{max-height:340px;overflow:auto;font-family:ui-monospace,Menlo,monospace;font-size:12px;line-height:1.8}
  .log div{padding:3px 8px;border-radius:6px}
  .ok{background:#f0fdf4;color:#15803d}.ko{background:#fef2f2;color:#b91c1c}.sk{background:#f8fafc;color:#64748b}
  .alert{background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;border-radius:12px;padding:12px 14px;font-size:13px}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h1>Campagne SMS — Confirmation d'inscription</h1>
    <p class="sub">
      Cible : seminaristes payes et valides inscrits avant le <b><?= DATE_COUPURE ?></b>.
      Les inscriptions posterieures ne sont pas concernees.<br>
      <b style="color:#0891b2">Destinataire : le numero du PARENT</b> (contact_parent).
      Repli sur le contact du seminariste uniquement si aucun numero de parent n'est renseigne.
    </p>
  </div>

  <div class="card">
    <div class="kpis" id="kpis">
      <div class="kpi"><b id="k-total">…</b><span>ELIGIBLES</span></div>
      <div class="kpi"><b id="k-restants">…</b><span>A ENVOYER</span></div>
      <div class="kpi"><b id="k-faits">…</b><span>DEJA ENVOYES</span></div>
      <div class="kpi warn"><b id="k-cout">…</b><span>COUT ESTIME (F)</span></div>
      <div class="kpi warn"><b id="k-repli">…</b><span>SANS N° PARENT</span></div>
    </div>
    <div id="warn-numero"></div>
  </div>

  <div class="card">
    <h2 style="font-size:15px;margin-bottom:10px">Verification des doublons</h2>
    <p class="sub" style="margin-bottom:10px">
      Seminaristes dont le numero du parent a <b>deja recu</b> le message
      (numero de paiement identique a celui du parent, ou SMS de validation deja parti).
      Ils sont retires automatiquement de la file.
    </p>
    <div id="analyse">Analyse en cours…</div>
  </div>

  <div class="card">
    <h2 style="font-size:15px;margin-bottom:10px">Apercu du message</h2>
    <div class="preview" id="apercu">Chargement…</div>
  </div>

  <div class="card">
    <div class="actions">
      <button class="go" id="btn-start">Demarrer l'envoi (1 SMS / 2 s)</button>
      <button class="stop" id="btn-stop" disabled>Arreter</button>
      <span class="sub" id="etat">En attente</span>
    </div>
    <div class="bar"><i id="bar"></i></div>
    <div class="log" id="log"></div>
  </div>
</div>

<script>
const TOKEN = <?= json_encode($_GET['token'] ?? '') ?>;
const BASE = location.pathname + '?token=' + encodeURIComponent(TOKEN);
let file = [], index = 0, timer = null, actif = false, ok = 0, ko = 0;

const $ = id => document.getElementById(id);

async function charger() {
  const r = await fetch(BASE + '&action=liste');
  const d = await r.json();
  if (!d.success) { $('etat').textContent = 'Erreur de chargement'; return; }
  file = d.file || [];
  $('k-total').textContent = d.total_eligibles;
  $('k-restants').textContent = d.restants;
  $('k-faits').textContent = d.deja_envoyes;
  $('k-cout').textContent = d.cout_estime_fcfa.toLocaleString('fr-FR');
  $('k-repli').textContent = d.sans_parent_repli ?? 0;
  let avert = '';
  if (d.sans_numero > 0) {
    avert += '<div class="alert" style="margin-top:12px">' + d.sans_numero +
      ' seminariste(s) sans aucun numero exploitable seront ignores automatiquement.</div>';
  }
  if (d.sans_parent_repli > 0) {
    avert += '<div class="alert" style="margin-top:12px">' + d.sans_parent_repli +
      ' seminariste(s) n\'ont pas de numero de parent : le SMS partira sur leur propre contact.</div>';
  }
  $('warn-numero').innerHTML = avert;
  $('apercu').innerHTML = (d.apercu || []).map(a =>
    `<div>${a.nom} — ${a.matricule}<br>Destinataire : ${a.telephone} <b style="color:${a.source==='parent'?'#4ade80':'#fbbf24'}">[${a.source}]</b><br>${a.message}<br><span class="len">${a.longueur} caracteres</span></div><br>`
  ).join('') || 'Aucun destinataire restant.';
  $('etat').textContent = file.length ? file.length + ' destinataire(s) en file' : 'Rien a envoyer';
  $('btn-start').disabled = file.length === 0;
}

function log(cls, txt) {
  const d = document.createElement('div');
  d.className = cls;
  d.textContent = txt;
  $('log').prepend(d);
}

async function envoyerSuivant() {
  if (!actif || index >= file.length) { arreter(true); return; }
  const cible = file[index];
  try {
    const r = await fetch(BASE + '&action=envoyer&matricule=' + encodeURIComponent(cible.matricule));
    const d = await r.json();
    if (d.ignore) { log('sk', `↷ ${cible.matricule} — deja envoye`); }
    else if (d.success) { ok++; log('ok', `✓ ${d.nom || cible.nom} — ${d.matricule} — ${d.telephone} [${d.source}] (${d.longueur} car.)`); }
    else { ko++; log('ko', `✗ ${cible.matricule} — ${d.message || 'echec'}`); }
  } catch (e) {
    ko++; log('ko', `✗ ${cible.matricule} — erreur reseau`);
  }
  index++;
  $('bar').style.width = (index / file.length * 100) + '%';
  $('etat').textContent = `${index}/${file.length} traites — ${ok} envoyes, ${ko} echecs`;
  if (actif) timer = setTimeout(envoyerSuivant, 2000);   // cadence : 2 secondes
}

function demarrer() {
  if (!file.length) return;
  if (!confirm(`Envoyer ${file.length} SMS ?\n\nCadence : 1 toutes les 2 secondes.\nDuree estimee : ${Math.ceil(file.length * 2 / 60)} minutes.\nCout estime : ${(file.length * 15).toLocaleString('fr-FR')} F.\n\nNe fermez pas cet onglet pendant l'envoi.`)) return;
  actif = true;
  $('btn-start').disabled = true;
  $('btn-stop').disabled = false;
  envoyerSuivant();
}

function arreter(fini) {
  actif = false;
  clearTimeout(timer);
  $('btn-start').disabled = false;
  $('btn-stop').disabled = true;
  $('etat').textContent = fini
    ? `Termine — ${ok} envoyes, ${ko} echecs sur ${index}`
    : `Interrompu a ${index}/${file.length} — relancez pour reprendre`;
}

async function analyser() {
  try {
    const r = await fetch(BASE + '&action=analyse');
    const d = await r.json();
    if (!d.success) { $('analyse').textContent = 'Analyse indisponible'; return; }
    let html = '<div class="kpis" style="grid-template-columns:repeat(3,1fr)">' +
      '<div class="kpi"><b style="color:#0891b2">' + d.parents_deja_joints + '</b><span>PARENTS DEJA JOINTS</span></div>' +
      '<div class="kpi"><b style="color:#7c3aed">' + d.dont_campagne_precedente + '</b><span>VIA CAMPAGNE PRECEDENTE</span></div>' +
      '<div class="kpi"><b style="color:#7c3aed">' + d.dont_sms_validation + '</b><span>VIA SMS INSCRIPTION</span></div>' +
      '</div>';
    if (d.parents_deja_joints > 0) {
      html += '<div class="alert" style="margin-top:12px;background:#f0fdf4;border-color:#86efac;color:#15803d">' +
        d.parents_deja_joints + ' SMS evites, soit <b>' + d.economie_fcfa.toLocaleString('fr-FR') +
        ' F economises</b>. Ces personnes ne sont pas dans la file.</div>';
      html += '<div class="log" style="margin-top:12px;max-height:200px">' +
        d.detail.map(x => '<div class="sk">' + x.matricule + ' — ' + (x.nom || '') +
          ' — ' + x.numero_parent + ' [' + x.origine + ']</div>').join('') +
        (d.parents_deja_joints > d.detail.length ? '<div class="sk">… et ' + (d.parents_deja_joints - d.detail.length) + ' autre(s)</div>' : '') +
        '</div>';
    } else {
      html += '<div class="alert" style="margin-top:12px">Aucun doublon detecte : aucun numero de parent n\'a encore ete contacte.</div>';
    }
    $('analyse').innerHTML = html;
  } catch (e) {
    $('analyse').textContent = 'Analyse impossible';
  }
}

$('btn-start').onclick = demarrer;
$('btn-stop').onclick = () => arreter(false);
window.addEventListener('beforeunload', e => { if (actif) { e.preventDefault(); e.returnValue = ''; } });
charger();
analyser();
</script>
</body>
</html>
