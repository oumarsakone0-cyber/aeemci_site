<?php
// 1. Configuration erreurs + CORS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Assurez-vous que l'encodage par défaut est UTF-8 pour une meilleure gestion des caractères accentués
ini_set('default_charset', 'UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Connexion BDD
require_once '../config/database.php';

try {
    $db = new Database();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Erreur BDD: " . $e->getMessage()]);
    exit();
}

// 3. Lecture des données
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch($action) {
    case 'start_session':
        startSession($db, $input);
        break;
            case 'submit_answers':
        submitAnswers($db, $input);
        break;
    case 'sync_niveaux':
        syncNiveaux($db, $input);
        break;
            default:
        echo json_encode(['success' => false, 'error' => 'Action non reconnue']);
}

// 4. Fonction démarrage session
function startSession($db, $input) {
    try {
        $evaluation_id = $input['evaluation_id'] ?? 0;
        $matricule = $input['matricule'] ?? '';
                
        if ($evaluation_id <= 0 || empty($matricule)) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            return;
        }
        // Vérifier si une session existe déjà
        $query = "
            SELECT id, statut FROM aeemci_evaluation_sessions 
            WHERE evaluation_id = ? AND matricule = ?
            ORDER BY id DESC
            LIMIT 1
        ";
        $result = $db->query($query, [$evaluation_id, $matricule]);
        if (!empty($result)) {
            $session_id = $result[0]['id'];
            $statut = $result[0]['statut'];
            if ($statut === 'en_cours') {
                echo json_encode([
                    'success' => true,
                    'session_id' => $session_id,
                    'message' => 'Session en cours reprise'
                ]);
                return;
            } elseif ($statut === 'termine') {
                echo json_encode([
                    'success' => false,
                    'error' => 'Cette évaluation a déjà été complétée'
                ]);
                return;
            }
        }
        // Créer une nouvelle session
        $query = "
            INSERT INTO aeemci_evaluation_sessions 
            (evaluation_id, matricule, statut, started_at, updated_at) 
            VALUES (?, ?, 'en_cours', NOW(), NOW())
        ";
        $db->query($query, [$evaluation_id, $matricule]);
                $sessionId = $db->lastInsertId();
                
        echo json_encode([
            'success' => true,
            'session_id' => $sessionId,
            'message' => 'Nouvelle session démarrée'
        ]);
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// 5. Fonction soumission des réponses
function submitAnswers($db, $input) {
    try {
        $evaluation_id = $input['evaluation_id'] ?? 0;
        $matricule = $input['matricule'] ?? '';
        $reponses = $input['reponses'] ?? [];
        $temps_ecoule = $input['temps_ecoule'] ?? 0;
                
        if ($evaluation_id <= 0 || empty($matricule)) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            return;
        }
                
        // Récupérer les questions avec les bonnes réponses
        $query = "
            SELECT id, bonne_reponse, points 
            FROM aeemci_evaluation_questions 
            WHERE evaluation_id = ? 
            ORDER BY ordre ASC
        ";
        $questions = $db->query($query, [$evaluation_id]);
                
        $score_obtenu = 0;
        $score_total = 0;
                
        foreach ($questions as $index => $question) {
            $score_total += $question['points'];
            $reponse_donnee = $reponses[$index] ?? null;
            $est_correcte = ($reponse_donnee === $question['bonne_reponse']) ? 1 : 0;
            $points_obtenus = $est_correcte ? $question['points'] : 0;
            if ($est_correcte) {
                $score_obtenu += $question['points'];
            }
            // Enregistrement ou mise à jour de la réponse
            $query_reponse = "
                INSERT INTO aeemci_evaluation_reponses 
                (evaluation_id, question_id, matricule, reponse_choisie, est_correcte, temps_reponse, points_obtenus, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                    reponse_choisie = VALUES(reponse_choisie),
                    est_correcte = VALUES(est_correcte),
                    temps_reponse = VALUES(temps_reponse),
                    points_obtenus = VALUES(points_obtenus),
                    created_at = NOW()
            ";
                        
            $db->query($query_reponse, [
                $evaluation_id,
                $question['id'],
                $matricule,
                $reponse_donnee,
                $est_correcte,
                $temps_ecoule,
                $points_obtenus
            ]);
        }
                
        // Calculer le pourcentage
        $score_pourcentage = $score_total > 0 ? round(($score_obtenu / $score_total) * 100, 2) : 0;

        // --- Récupérer le niveau d'étude, le sexe et l'âge du séminariste ---
        // La colonne d'âge n'existe pas dans tous les schémas : on la cherche
        // avant de l'ajouter au SELECT, sinon la requête échouerait.
        $colonne_age = colonneAgeSeminariste($db);
        $select_age = '';
        if ($colonne_age !== null) {
            $select_age = $colonne_age['mode'] === 'date'
                ? ", TIMESTAMPDIFF(YEAR, `{$colonne_age['nom']}`, CURDATE()) AS age_calcule"
                : ", `{$colonne_age['nom']}` AS age_calcule";
        }

        $query_seminariste = "
            SELECT niveau_etude, sexe{$select_age}
            FROM seminaristes
            WHERE matricule_seminaire = ?
        ";
        $seminariste_info = $db->query($query_seminariste, [$matricule]);

        if (empty($seminariste_info)) {
            echo json_encode(['success' => false, 'error' => 'Informations du séminariste non trouvées. Assurez-vous que le matricule existe dans la table seminaristes.']);
            return;
        }

        $niveau_etude = $seminariste_info[0]['niveau_etude'];
        $sexe = $seminariste_info[0]['sexe'];
        $age = isset($seminariste_info[0]['age_calcule']) && $seminariste_info[0]['age_calcule'] !== null
            ? (int)$seminariste_info[0]['age_calcule']
            : null;

        // --- Déterminer le niveau d'affectation ---
        // La note est ramenee sur 20 : les baremes sont exprimes sur 20.
        $note_sur_20 = $score_total > 0 ? round(($score_obtenu / $score_total) * 20, 2) : (float)$score_obtenu;
        $niveau_affecte = determineNiveauAffecte($note_sur_20, $niveau_etude, $sexe, $age);
                
        // Mettre à jour la session
        $query_update = "
            UPDATE aeemci_evaluation_sessions 
            SET statut = 'termine', 
                score_obtenu = ?, 
                sexe = ?,             
                niveau_etude = ?,     
                score_total = ?, 
                score_pourcentage = ?, 
                temps_ecoule = ?, 
                completed_at  = NOW(),
                niveau_affecte = ? 
            WHERE evaluation_id = ? AND matricule = ? AND statut = 'en_cours'
        ";
                
        $db->query($query_update, [
            $score_obtenu,
            $sexe,             
            $niveau_etude,     
            $score_total,      
            $score_pourcentage,
            $temps_ecoule,
            $niveau_affecte, 
            $evaluation_id,
            $matricule
        ]);
                
        // --- Appliquer le niveau au dossier du seminariste ---
        // Jusqu'ici le niveau n'etait ecrit que dans aeemci_evaluation_sessions :
        // la table seminaristes restait sur TEST_ENTREE. On la met a jour ici.
        // Garde-fou : on n'ecrase que les niveaux encore indetermines
        // (vide, TEST_ENTREE ou NON AFFECTE). Un seminariste deja classe
        // manuellement garde son niveau.
        $niveau_applique = false;
        $niveau_seminaire = null;
        $niveau_message = '';

        if ($niveau_affecte !== '' && $niveau_affecte !== 'Non Affecté') {
            $query_niveau = "
                UPDATE seminaristes
                SET niveau_seminaire = ?
                WHERE matricule_seminaire = ?
                  AND (
                        niveau_seminaire IS NULL
                     OR TRIM(niveau_seminaire) = ''
                     OR REPLACE(REPLACE(UPPER(TRIM(niveau_seminaire)), '_', ' '), 'É', 'E')
                        IN ('TEST ENTREE', 'TEST D ENTREE', 'NON AFFECTE')
                  )
            ";
            $db->query($query_niveau, [$niveau_affecte, $matricule]);

            // Le wrapper Database n'expose pas rowCount() : on relit la ligne
            // pour savoir si l'ecriture a bien eu lieu.
            $verif = $db->query(
                "SELECT niveau_seminaire FROM seminaristes WHERE matricule_seminaire = ?",
                [$matricule]
            );
            $niveau_seminaire = $verif[0]['niveau_seminaire'] ?? null;
            $niveau_applique = (trim((string)$niveau_seminaire) === $niveau_affecte);
            if (!$niveau_applique) {
                $niveau_message = "Le seminariste possede deja le niveau '"
                    . trim((string)$niveau_seminaire) . "' : il n'a pas ete remplace.";
            }
            error_log("DEBUG: niveau_seminaire de $matricule = '"
                . (string)$niveau_seminaire . "' (niveau affecte: '$niveau_affecte')");
        } else {
            $niveau_message = "Aucun niveau n'a pu etre determine (niveau d'etude ou sexe non reconnu).";
            error_log("DEBUG: niveau non applique pour $matricule (niveau_affecte = '$niveau_affecte')");
        }

        echo json_encode([
            'success' => true,
            'resultats' => [
                'score_obtenu' => $score_obtenu,
                'score_total' => $score_total,
                'score_pourcentage' => $score_pourcentage,
                'temps_ecoule' => $temps_ecoule,
                'niveau_affecte' => $niveau_affecte,
                'niveau_seminaire' => $niveau_seminaire,
                'niveau_applique' => $niveau_applique,
                'niveau_message' => $niveau_message
            ]
        ]);
            } catch(Exception $e) {
        // Pour un débogage plus facile, vous pouvez loguer l'erreur complète
        error_log("Erreur dans submitAnswers: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
        http_response_code(500); 
        echo json_encode(['success' => false, 'error' => 'Erreur SQL ou interne : ' . $e->getMessage()]);
    }
}

// 6. Rattrapage : appliquer aux seminaristes les niveaux deja calcules
// par les tests d'entree passes AVANT la correction (leur niveau etait
// enregistre dans aeemci_evaluation_sessions mais pas dans seminaristes).
// Appel : POST { "action": "sync_niveaux", "token": "senafoi26_niveaux", "apply": true }
// Sans "apply", la reponse est une simple simulation.
function syncNiveaux($db, $input) {
    try {
        if (($input['token'] ?? '') !== 'senafoi26_niveaux') {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Token invalide.']);
            return;
        }

        $apply = !empty($input['apply']);
        // Filtre optionnel : limiter le rattrapage a l'evaluation du test d'entree.
        $evaluation_id = (int)($input['evaluation_id'] ?? 0);
        $filtreEval = $evaluation_id > 0 ? ' AND evaluation_id = ' . $evaluation_id : '';

        $query = "
            SELECT s.matricule_seminaire, s.niveau_seminaire, sess.niveau_affecte
            FROM seminaristes s
            JOIN (
                SELECT matricule, niveau_affecte
                FROM aeemci_evaluation_sessions
                WHERE statut = 'termine'
                  AND niveau_affecte IS NOT NULL
                  AND TRIM(niveau_affecte) <> ''
                  AND TRIM(niveau_affecte) <> 'Non Affecté'
                  {$filtreEval}
                  AND id IN (SELECT MAX(id) FROM aeemci_evaluation_sessions GROUP BY matricule)
            ) sess ON sess.matricule = s.matricule_seminaire
            WHERE s.niveau_seminaire IS NULL
               OR TRIM(s.niveau_seminaire) = ''
               OR REPLACE(REPLACE(UPPER(TRIM(s.niveau_seminaire)), '_', ' '), 'É', 'E')
                  IN ('TEST ENTREE', 'TEST D ENTREE', 'NON AFFECTE')
            ORDER BY s.matricule_seminaire ASC
        ";
        $candidats = $db->query($query);
        $candidats = is_array($candidats) ? $candidats : [];

        $appliques = 0;
        if ($apply) {
            foreach ($candidats as $ligne) {
                $db->query(
                    "UPDATE seminaristes SET niveau_seminaire = ? WHERE matricule_seminaire = ?",
                    [trim((string)$ligne['niveau_affecte']), $ligne['matricule_seminaire']]
                );
                $appliques++;
            }
        }

        echo json_encode([
            'success' => true,
            'mode' => $apply ? 'applique' : 'simulation',
            'total' => count($candidats),
            'appliques' => $appliques,
            'details' => $candidats
        ]);
    } catch(Exception $e) {
        error_log("Erreur dans syncNiveaux: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// --- Fonction pour déterminer le niveau d'affectation ---
/**
 * Retire les accents et met en majuscules.
 */
function sansAccentsMaj($valeur) {
    $valeur = trim((string)$valeur);
    $map = [
        'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','à'=>'a','á'=>'a','â'=>'a','ä'=>'a',
        'È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
        'Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
        'Ò'=>'O','Ó'=>'O','Ô'=>'O','Ö'=>'O','ò'=>'o','ó'=>'o','ô'=>'o','ö'=>'o',
        'Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
        'Ç'=>'C','ç'=>'c',
    ];
    $valeur = strtr($valeur, $map);
    return function_exists('mb_strtoupper') ? mb_strtoupper($valeur, 'UTF-8') : strtoupper($valeur);
}

/**
 * Rang du niveau d'etude :
 *   1 = primaire, 6e, 5e | 2 = 4e, 3e | 3 = seconde | 4 = 1re, terminale, superieur
 *   0 = non reconnu
 */
function rangNiveauEtude($brut) {
    $v = preg_replace('/[^A-Z0-9]/', '', sansAccentsMaj($brut));
    if ($v === '') return 0;

    foreach (['UNIVERSITE','UNIV','SUPERIEUR','SUP','FAC','LICENCE','MASTER','DOCTORAT','BTS','DUT','ETUDIANT','PREPA'] as $mot) {
        if (strpos($v, $mot) !== false) return 4;
    }
    foreach (['TERMINALE','TERMINAL','TLE','TERM'] as $mot) {
        if (strpos($v, $mot) !== false) return 4;
    }
    foreach (['1ERE','1ER','PREMIERE','1E'] as $mot) {
        if (strpos($v, $mot) === 0) return 4;
    }
    foreach (['SECONDE','SECOND','2NDE','2ND','2DE'] as $mot) {
        if (strpos($v, $mot) === 0) return 3;
    }
    foreach (['4EME','4E','QUATRIEME','3EME','3E','TROISIEME'] as $mot) {
        if (strpos($v, $mot) === 0) return 2;
    }
    foreach (['PRIMAIRE','CP','CE1','CE2','CM1','CM2','ECOLE','6EME','6E','SIXIEME','5EME','5E','CINQUIEME'] as $mot) {
        if (strpos($v, $mot) === 0) return 1;
    }
    return 0;
}

/**
 * Determine le niveau du seminariste (barème arrete par la commission) :
 *
 *   - age < 9 ans          -> PEPINIERE
 *   - note <= 9            -> niveau 1
 *   - note de 10 a 16      -> niveau 2
 *   - note > 16            -> niveau 3
 *
 *   Lettre, niveaux 1 et 2 : PRIMAIRE/6e/5e -> A | 4e/3e/2nde -> B | au-dela -> C
 *   Lettre, niveau 3       : PRIMAIRE..3e -> A | 2nde et au-dela -> B (pas de C)
 *
 *   Sexe : garcon -> F (frere), fille -> S (soeur).
 *
 * Le resultat ne contient pas le mot « niveau » : « 1 AF », « 2 BS », « 3 AF »...
 *
 * @param float    $note        note ramenee sur 20
 * @param string   $niveauEtude niveau d'etude declare a l'inscription
 * @param string   $sexe        sexe declare a l'inscription
 * @param int|null $age         age en annees, null si inconnu
 */
function determineNiveauAffecte($note, $niveauEtude, $sexe, $age = null) {
    if ($age !== null && $age < 9) {
        error_log("DEBUG: age $age < 9 -> PEPINIERE");
        return 'PEPINIERE';
    }

    $sexeNorm = sansAccentsMaj($sexe);
    if ($sexeNorm !== '' && ($sexeNorm[0] === 'M' || strpos($sexeNorm, 'GARCON') !== false || strpos($sexeNorm, 'HOMME') !== false)) {
        $suffixe = 'F'; // frere
    } elseif ($sexeNorm !== '' && ($sexeNorm[0] === 'F' || strpos($sexeNorm, 'FILLE') !== false || strpos($sexeNorm, 'FEMME') !== false)) {
        $suffixe = 'S'; // soeur
    } else {
        error_log("DEBUG: sexe non reconnu ('$sexe') -> Non Affecte");
        return 'Non Affecté';
    }

    $rang = rangNiveauEtude($niveauEtude);
    if ($rang === 0) {
        error_log("DEBUG: niveau d'etude non reconnu ('$niveauEtude') -> Non Affecte");
        return 'Non Affecté';
    }

    if ($note < 10) {
        $groupe = '1';
        $lettre = $rang === 1 ? 'A' : ($rang <= 3 ? 'B' : 'C');
    } elseif ($note <= 16) {
        $groupe = '2';
        $lettre = $rang === 1 ? 'A' : ($rang <= 3 ? 'B' : 'C');
    } else {
        // Niveau 3 : pas de C, la seconde bascule en B
        $groupe = '3';
        $lettre = $rang <= 2 ? 'A' : 'B';
    }

    $niveau = $groupe . ' ' . $lettre . $suffixe;
    error_log("DEBUG: note $note, rang etude $rang, sexe $sexeNorm -> $niveau");
    return $niveau;
}

/**
 * Repere la colonne d'age dans la table seminaristes.
 * @return array{nom: string, mode: string}|null
 */
function colonneAgeSeminariste($db) {
    static $trouve = false, $res = null;
    if ($trouve) return $res;
    $trouve = true;
    try {
        $lignes = $db->query("
            SELECT LOWER(COLUMN_NAME) AS nom, LOWER(DATA_TYPE) AS type
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'seminaristes'
        ");
        $cols = [];
        foreach ((array)$lignes as $l) { $cols[$l['nom']] = $l['type']; }

        foreach (['age', 'age_seminariste', 'annees'] as $c) {
            if (isset($cols[$c])) { $res = ['nom' => $c, 'mode' => 'entier']; return $res; }
        }
        foreach (['date_naissance', 'datenaissance', 'date_de_naissance', 'naissance', 'date_naiss'] as $c) {
            if (isset($cols[$c])) { $res = ['nom' => $c, 'mode' => 'date']; return $res; }
        }
    } catch (Exception $e) {
        error_log("DEBUG: detection colonne age impossible : " . $e->getMessage());
    }
    return $res;
}
?>
