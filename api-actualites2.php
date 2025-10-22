<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration de la base de données
$host = '91.216.107.162';
$dbname = 'soget2616263';
$username = 'soget2616263';
$password = '0Objectif-';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer l'action demandée
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    // Debug: afficher l'action reçue
    error_log("Action reçue: " . $action);
    error_log("GET params: " . print_r($_GET, true));
    error_log("POST params: " . print_r($_POST, true));
    error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
    error_log("QUERY_STRING: " . $_SERVER['QUERY_STRING']);
    
    // Si l'action est vide, essayer de la récupérer différemment
    if (empty($action)) {
        $action = $_REQUEST['action'] ?? '';
        error_log("Action après REQUEST: " . $action);
    }
    
    // Si toujours vide, retourner une erreur détaillée
    if (empty($action)) {
        sendResponse(false, 'Action non reconnue: "' . $action . '" - GET: ' . print_r($_GET, true) . ' - POST: ' . print_r($_POST, true) . ' - REQUEST: ' . print_r($_REQUEST, true), null, 400);
        return;
    }
    
    switch ($action) {
        case 'get_latest':
            getLatestActualites($pdo);
            break;
            
        case 'get_by_id':
            getActualiteById($pdo);
            break;
            
        case 'get_all':
            getAllActualites($pdo);
            break;
            
        case 'create':
            createActualite($pdo);
            break;
            
        case 'update':
            updateActualite($pdo);
            break;
            
        case 'delete':
            deleteActualite($pdo);
            break;
            
        case 'upload_image':
            uploadImage($pdo);
            break;
            
        case 'test':
            sendResponse(true, 'API fonctionne correctement', ['timestamp' => date('Y-m-d H:i:s')]);
            break;
            
        default:
            sendResponse(false, 'Action non reconnue: ' . $action, null, 400);
    }
    
} catch (PDOException $e) {
    sendResponse(false, 'Erreur de connexion à la base de données: ' . $e->getMessage(), null, 500);
} catch (Exception $e) {
    sendResponse(false, 'Erreur: ' . $e->getMessage(), null, 500);
}

/**
 * Récupérer les dernières actualités
 */
function getLatestActualites($pdo) {
    $limit = $_GET['limit'] ?? 5;
    $limit = min($limit, 10); // Maximum 10 articles
    
    try {
        $sql = "SELECT 
                    a.id,
                    a.titre,
                    a.type,
                    a.date_debut,
                    a.date_fin,
                    a.date_specifique,
                    a.lieu,
                    a.texte_affichage,
                    a.texte_detaille,
                    a.statut,
                    a.photos,
                    a.created_at,
                    a.updated_at
                FROM aeemciste_actualites a
                WHERE a.statut = 'publie' OR a.statut = 'BROUILLON'
                ORDER BY a.date_debut DESC, a.created_at DESC
                LIMIT :limit";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formater les données
        foreach ($actualites as &$actualite) {
            // Mapper vers le format attendu par le frontend
            $actualite['title'] = $actualite['titre'];
            $actualite['content'] = $actualite['texte_detaille'];
            $actualite['excerpt'] = $actualite['texte_affichage'] ?: substr(strip_tags($actualite['texte_detaille']), 0, 150) . '...';
            $actualite['date'] = $actualite['date_specifique'] ?: $actualite['date_debut'];
            $actualite['image'] = '';
            $actualite['author'] = '';
            $actualite['category'] = $actualite['type'];
            $actualite['location'] = $actualite['lieu'];
            $actualite['date_start'] = $actualite['date_debut'];
            $actualite['date_end'] = $actualite['date_fin'];
            
            // Traiter les photos JSON
            if ($actualite['photos'] && !empty($actualite['photos'])) {
                try {
                    $photosData = json_decode($actualite['photos'], true);
                    if (is_array($photosData) && !empty($photosData)) {
                        $actualite['image'] = $photosData[0]['preview'] ?? '';
                    }
                } catch (Exception $e) {
                    // En cas d'erreur de décodage JSON, ignorer
                    $actualite['image'] = '';
                }
            }
            
            // Formater la date
            if ($actualite['date']) {
                $actualite['date'] = formatDate($actualite['date']);
            } else {
                $actualite['date'] = 'Date non spécifiée';
            }
        }
        
        sendResponse(true, 'Actualités récupérées avec succès', $actualites);
        
    } catch (Exception $e) {
        sendResponse(false, 'Erreur lors de la récupération des actualités: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Récupérer une actualité par ID
 */
function getActualiteById($pdo) {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        sendResponse(false, 'ID de l\'actualité requis', null, 400);
        return;
    }
    
    try {
        // Requête très simple pour tester
        $sql = "SELECT id, titre, texte_detaille FROM aeemciste_actualites WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $actualite = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$actualite) {
            sendResponse(false, 'Actualité non trouvée', null, 404);
            return;
        }

        // Format simple
        $result = [
            'id' => $actualite['id'],
            'title' => $actualite['titre'],
            'content' => $actualite['texte_detaille'],
            'date' => 'Date test',
            'image' => '',
            'author' => 'AEEMCI Communication'
        ];

        sendResponse(true, 'Actualité récupérée avec succès', $result);

    } catch (Exception $e) {
        sendResponse(false, 'Erreur: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Récupérer toutes les actualités avec pagination
 */
function getAllActualites($pdo) {
    $page = max(1, $_GET['page'] ?? 1);
    $limit = min(20, max(1, $_GET['limit'] ?? 10));
    $offset = ($page - 1) * $limit;
    
    try {
        // Compter le total
        $countSql = "SELECT COUNT(*) as total FROM aeemciste_actualites WHERE statut = 'publie' OR statut = 'BROUILLON'";
        $countStmt = $pdo->query($countSql);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Récupérer les actualités
        $sql = "SELECT 
                    a.id,
                    a.titre,
                    a.type,
                    a.date_debut,
                    a.date_fin,
                    a.date_specifique,
                    a.lieu,
                    a.texte_affichage,
                    a.texte_detaille,
                    a.statut,
                    a.photos,
                    a.created_at,
                    a.updated_at
                FROM aeemciste_actualites a
                WHERE a.statut = 'publie' OR a.statut = 'BROUILLON'
                ORDER BY a.date_debut DESC, a.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formater les données
        foreach ($actualites as &$actualite) {
            // Mapper vers le format attendu par le frontend
            $actualite['title'] = $actualite['titre'];
            $actualite['content'] = $actualite['texte_detaille'];
            $actualite['excerpt'] = $actualite['texte_affichage'] ?: substr(strip_tags($actualite['texte_detaille']), 0, 150) . '...';
            $actualite['date'] = $actualite['date_specifique'] ?: $actualite['date_debut'];
            $actualite['image'] = '';
            $actualite['author'] = '';
            $actualite['category'] = $actualite['type'];
            $actualite['location'] = $actualite['lieu'];
            $actualite['date_start'] = $actualite['date_debut'];
            $actualite['date_end'] = $actualite['date_fin'];

            // Traiter les photos JSON
            if ($actualite['photos'] && !empty($actualite['photos'])) {
                try {
                    $photosData = json_decode($actualite['photos'], true);
                    if (is_array($photosData) && !empty($photosData)) {
                        $actualite['image'] = $photosData[0]['preview'] ?? '';
                    }
                } catch (Exception $e) {
                    // En cas d'erreur de décodage JSON, ignorer
                    $actualite['image'] = '';
                }
            }

            // Formater la date
            if ($actualite['date']) {
                $actualite['date'] = formatDate($actualite['date']);
            } else {
                $actualite['date'] = 'Date non spécifiée';
            }
        }
        
        $response = [
            'data' => $actualites,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ];
        
        sendResponse(true, 'Actualités récupérées avec succès', $response);
        
    } catch (Exception $e) {
        sendResponse(false, 'Erreur lors de la récupération des actualités: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Créer une nouvelle actualité
 */
function createActualite($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        sendResponse(false, 'Données JSON invalides', null, 400);
        return;
    }
    
    $requiredFields = ['titre', 'contenu'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            sendResponse(false, "Le champ '$field' est requis", null, 400);
            return;
        }
    }
    
    try {
        $sql = "INSERT INTO aeemciste_actualites 
                (titre, contenu, resume, date_publication, image_url, auteur, categorie, statut, created_at, updated_at)
                VALUES (:titre, :contenu, :resume, :date_publication, :image_url, :auteur, :categorie, :statut, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':contenu', $data['contenu']);
        $stmt->bindParam(':resume', $data['resume'] ?? null);
        $stmt->bindParam(':date_publication', $data['date_publication'] ?? date('Y-m-d'));
        $stmt->bindParam(':image_url', $data['image_url'] ?? null);
        $stmt->bindParam(':auteur', $data['auteur'] ?? null);
        $stmt->bindParam(':categorie', $data['categorie'] ?? null);
        $stmt->bindParam(':statut', $data['statut'] ?? 'brouillon');
        
        $stmt->execute();
        
        $actualiteId = $pdo->lastInsertId();
        
        sendResponse(true, 'Actualité créée avec succès', ['id' => $actualiteId]);
        
    } catch (Exception $e) {
        sendResponse(false, 'Erreur lors de la création de l\'actualité: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Mettre à jour une actualité
 */
function updateActualite($pdo) {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$id) {
        sendResponse(false, 'ID de l\'actualité requis', null, 400);
        return;
    }
    
    if (!$data) {
        sendResponse(false, 'Données JSON invalides', null, 400);
        return;
    }
    
    try {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['titre'])) {
            $fields[] = 'titre = :titre';
            $params[':titre'] = $data['titre'];
        }
        if (isset($data['contenu'])) {
            $fields[] = 'contenu = :contenu';
            $params[':contenu'] = $data['contenu'];
        }
        if (isset($data['resume'])) {
            $fields[] = 'resume = :resume';
            $params[':resume'] = $data['resume'];
        }
        if (isset($data['date_publication'])) {
            $fields[] = 'date_publication = :date_publication';
            $params[':date_publication'] = $data['date_publication'];
        }
        if (isset($data['image_url'])) {
            $fields[] = 'image_url = :image_url';
            $params[':image_url'] = $data['image_url'];
        }
        if (isset($data['auteur'])) {
            $fields[] = 'auteur = :auteur';
            $params[':auteur'] = $data['auteur'];
        }
        if (isset($data['categorie'])) {
            $fields[] = 'categorie = :categorie';
            $params[':categorie'] = $data['categorie'];
        }
        if (isset($data['statut'])) {
            $fields[] = 'statut = :statut';
            $params[':statut'] = $data['statut'];
        }
        
        if (empty($fields)) {
            sendResponse(false, 'Aucune donnée à mettre à jour', null, 400);
            return;
        }
        
        $fields[] = 'updated_at = NOW()';
        
        $sql = "UPDATE aeemciste_actualites SET " . implode(', ', $fields) . " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        if ($stmt->rowCount() === 0) {
            sendResponse(false, 'Actualité non trouvée', null, 404);
            return;
        }
        
        sendResponse(true, 'Actualité mise à jour avec succès');
        
    } catch (Exception $e) {
        sendResponse(false, 'Erreur lors de la mise à jour de l\'actualité: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Supprimer une actualité
 */
function deleteActualite($pdo) {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        sendResponse(false, 'ID de l\'actualité requis', null, 400);
        return;
    }
    
    try {
        // Supprimer d'abord les photos associées
        $deletePhotosSql = "DELETE FROM aeemciste_actualites_photos WHERE actualite_id = :id";
        $deletePhotosStmt = $pdo->prepare($deletePhotosSql);
        $deletePhotosStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $deletePhotosStmt->execute();
        
        // Supprimer l'actualité
        $sql = "DELETE FROM aeemciste_actualites WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            sendResponse(false, 'Actualité non trouvée', null, 404);
            return;
        }
        
        sendResponse(true, 'Actualité supprimée avec succès');
        
    } catch (Exception $e) {
        sendResponse(false, 'Erreur lors de la suppression de l\'actualité: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Upload d'une image
 */
function uploadImage($pdo) {
    if (!isset($_FILES['image'])) {
        sendResponse(false, 'Aucun fichier image fourni', null, 400);
        return;
    }
    
    $file = $_FILES['image'];
    $actualiteId = $_POST['actualite_id'] ?? null;
    
    // Validation du fichier
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        sendResponse(false, 'Type de fichier non supporté', null, 400);
        return;
    }
    
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        sendResponse(false, 'Fichier trop volumineux (max 5MB)', null, 400);
        return;
    }
    
    try {
        // Créer le dossier d'upload s'il n'existe pas
        $uploadDir = 'uploads/actualites/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'actualite_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $url = 'http://sogetrag.com/apistage/' . $filepath;
            
            // Si un ID d'actualité est fourni, associer l'image
            if ($actualiteId) {
                $sql = "INSERT INTO aeemciste_actualites_photos (actualite_id, url_photo, created_at) VALUES (:actualite_id, :url_photo, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':actualite_id', $actualiteId, PDO::PARAM_INT);
                $stmt->bindParam(':url_photo', $url);
                $stmt->execute();
            }
            
            sendResponse(true, 'Image uploadée avec succès', [
                'url' => $url,
                'filename' => $filename,
                'size' => $file['size'],
                'type' => $file['type']
            ]);
        } else {
            sendResponse(false, 'Erreur lors de l\'upload du fichier', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Erreur lors de l\'upload: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Formater une date
 */
function formatDate($date) {
    if (!$date) return null;
    
    $timestamp = strtotime($date);
    if ($timestamp === false) return $date;
    
    return date('d/m/Y', $timestamp);
}

/**
 * Envoyer une réponse JSON
 */
function sendResponse($success, $message, $data = null, $httpCode = 200) {
    http_response_code($httpCode);
    
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}
?>
