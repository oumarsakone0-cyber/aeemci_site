import axios from "axios"

// Configuration de base pour l'API - HTTPS pour sastock.com
const API_BASE_URL = "https://sastock.com/api"
const API_TIMEOUT = 20000 // 20 secondes

// Configuration Cloudinary
const cloudinaryConfig = {
  cloudName: "dqk65objc",
  uploadPreset: "sadeal",
  apiKey: "784574272958761",
  uploadUrl: "https://api.cloudinary.com/v1_1/dqk65objc/image/upload",
}

// Configuration Axios avec gestion CORS optimisée
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: API_TIMEOUT,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
  withCredentials: false, // Important pour CORS cross-origin
})

// Intercepteur pour les requêtes
apiClient.interceptors.request.use(
  (config) => {
    console.log(`🔄 Requête API: ${config.method?.toUpperCase()} ${config.url}`)
    if (config.data) {
      console.log("📤 Données envoyées:", config.data)
    }
    if (config.params) {
      console.log("📤 Paramètres:", config.params)
    }
    return config
  },
  (error) => {
    console.error("❌ Erreur de requête:", error)
    return Promise.reject(error)
  },
)

// Intercepteur pour les réponses avec gestion d'erreurs détaillée
apiClient.interceptors.response.use(
  (response) => {
    console.log(`✅ Réponse API: ${response.status}`, response.data)
    return response
  },
  (error) => {
    console.error("❌ Erreur de réponse:", error)

    // Gestion spécifique des erreurs CORS
    if (error.code === "ERR_NETWORK") {
      console.error("🌐 Erreur réseau - Problème CORS ou serveur inaccessible")
      console.error("🔍 URL tentée:", error.config?.url)
      console.error("🔍 Méthode:", error.config?.method)
      throw new Error(
        "Impossible de se connecter au serveur. Vérifiez que le serveur est accessible et que CORS est configuré correctement.",
      )
    }

    // Gestion des timeouts
    if (error.code === "ECONNABORTED") {
      console.error("⏱️ Timeout de la requête")
      throw new Error("La requête a pris trop de temps. Le serveur met du temps à répondre, veuillez réessayer.")
    }

    // Gestion des erreurs de certificat SSL
    if (error.code === "ERR_CERT_COMMON_NAME_INVALID") {
      console.error("🔒 Erreur de certificat SSL")
      throw new Error("Problème de certificat SSL. Contactez l'administrateur.")
    }

    return Promise.reject(error)
  },
)

// Fonction utilitaire pour tester la connexion
export const testConnection = async () => {
  try {
    console.log("🔍 Test de connexion à l'API...")
    const response = await apiClient.get("/api-carte-membre.php?action=test")
    console.log("✅ Test de connexion réussi:", response.data)
    return response.data
  } catch (error) {
    console.error("❌ Test de connexion échoué:", error)
    throw new Error(`Test de connexion échoué: ${error.message}`)
  }
}

// Fonction pour uploader une photo vers Cloudinary (modifiée selon votre modèle)
export const uploadPhoto = async (file, membreId = null, altText = "") => {
  try {
    console.log("🔄 Upload de la photo vers Cloudinary...")
    console.log("📤 Fichier:", file.name, file.size, "bytes")

    // Validation du fichier
    if (!file) {
      throw new Error("Aucun fichier fourni")
    }

    // Vérifier le type de fichier
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp"]
    if (!allowedTypes.includes(file.type)) {
      throw new Error("Type de fichier non supporté. Utilisez JPG, PNG, GIF ou WebP.")
    }

    // Vérifier la taille (max 5MB)
    const maxSize = 5 * 1024 * 1024 // 5MB
    if (file.size > maxSize) {
      throw new Error("Le fichier est trop volumineux. Taille maximum: 5MB")
    }

    // Créer un nom de fichier unique selon votre modèle
    const timestamp = Date.now()
    const fileName = `membre_${timestamp}_${file.name.replace(/\s+/g, "_")}`

    // Préparer les données pour Cloudinary selon votre configuration
    const formData = new FormData()
    formData.append("file", file)
    formData.append("upload_preset", cloudinaryConfig.uploadPreset)
    formData.append("api_key", cloudinaryConfig.apiKey)
    formData.append("public_id", fileName)
    formData.append("folder", "membres_aeemci") // Organiser dans un dossier

    console.log("📤 Upload vers Cloudinary...")

    // Upload vers Cloudinary avec suivi de progression
    const response = await axios.post(cloudinaryConfig.uploadUrl, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      timeout: 30000, // 30 secondes pour l'upload
      onUploadProgress: (progressEvent) => {
        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        console.log(`📤 Progression upload: ${percentCompleted}%`)
      },
    })

    if (response.data && response.data.secure_url) {
      console.log("✅ Photo uploadée avec succès:", response.data.secure_url)

      return {
        success: true,
        data: {
          url: response.data.secure_url,
          public_id: response.data.public_id,
          width: response.data.width,
          height: response.data.height,
          format: response.data.format,
          bytes: response.data.bytes,
          created_at: response.data.created_at,
        },
        message: "Photo uploadée avec succès",
      }
    } else {
      throw new Error("Réponse Cloudinary invalide")
    }
  } catch (error) {
    console.error("❌ Erreur lors de l'upload de la photo:", error)

    if (error.response) {
      // Erreur de Cloudinary
      const cloudinaryError = error.response.data?.error?.message || error.response.statusText
      throw new Error(`Erreur Cloudinary: ${cloudinaryError}`)
    } else if (error.code === "ECONNABORTED") {
      throw new Error("Timeout lors de l'upload. Le fichier est peut-être trop volumineux.")
    } else {
      throw new Error(`Erreur lors de l'upload: ${error.message}`)
    }
  }
}

// Fonction pour uploader une image avec progression (compatible avec votre code Vue)
export const uploadImage = async (file, productId = null, altText = "") => {
  try {
    console.log("🔄 Upload d'image avec progression...")
    
    // Validation du fichier
    if (!file) {
      throw new Error("Aucun fichier fourni")
    }

    // Vérifier le type de fichier
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp"]
    if (!allowedTypes.includes(file.type)) {
      throw new Error("Type de fichier non supporté. Utilisez JPG, PNG, GIF ou WebP.")
    }

    // Vérifier la taille (max 5MB)
    const maxSize = 5 * 1024 * 1024 // 5MB
    if (file.size > maxSize) {
      throw new Error("Le fichier est trop volumineux. Taille maximum: 5MB")
    }

    // Créer un nom de fichier unique
    const timestamp = Date.now()
    const fileName = `product_${timestamp}_${file.name.replace(/\s+/g, "_")}`

    // Préparer les données pour Cloudinary
    const formData = new FormData()
    formData.append("file", file)
    formData.append("upload_preset", cloudinaryConfig.uploadPreset)
    formData.append("api_key", cloudinaryConfig.apiKey)
    formData.append("public_id", fileName)
    
    // Ajouter des métadonnées si fournies
    if (productId) {
      formData.append("context", `product_id=${productId}`)
    }
    if (altText) {
      formData.append("context", `alt_text=${altText}`)
    }

    console.log("📤 Upload vers Cloudinary...")

    // Upload vers Cloudinary avec suivi de progression
    const response = await axios.post(cloudinaryConfig.uploadUrl, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      timeout: 30000, // 30 secondes pour l'upload
      onUploadProgress: (progressEvent) => {
        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        console.log(`📤 Upload Progress: ${percentCompleted}%`)
      },
    })

    if (response.data && response.data.secure_url) {
      console.log("✅ Image uploadée avec succès:", response.data.secure_url)

      return {
        success: true,
        data: {
          url: response.data.secure_url,
          public_id: response.data.public_id,
          width: response.data.width,
          height: response.data.height,
          format: response.data.format,
          bytes: response.data.bytes,
          created_at: response.data.created_at,
        },
        message: "Image uploadée avec succès",
      }
    } else {
      throw new Error("Réponse Cloudinary invalide")
    }
  } catch (error) {
    console.error("❌ Erreur lors de l'upload de l'image:", error)

    if (error.response) {
      // Erreur de Cloudinary
      const cloudinaryError = error.response.data?.error?.message || error.response.statusText
      throw new Error(`Erreur Cloudinary: ${cloudinaryError}`)
    } else if (error.code === "ECONNABORTED") {
      throw new Error("Timeout lors de l'upload. Le fichier est peut-être trop volumineux.")
    } else {
      throw new Error(`Erreur lors de l'upload: ${error.message}`)
    }
  }
}

// Fonction pour créer un membre avec validation complète
export const createMembre = async (membreData) => {
  try {
    console.log("📝 Création d'un nouveau membre...")
    console.log("📤 Données du membre:", membreData)

    // Validation côté client
    const requiredFields = [
      "prenom",
      "nom",
      "date_naiss",
      "lieu_naiss",
      "contact",
      "sexe",
      "secretariat_debut",
      "souscomite_debut",
      "annee_debut",
      "je_suis",
    ]

    const missingFields = []
    for (const field of requiredFields) {
      if (!membreData[field] && membreData[field] !== 0 && membreData[field] !== "0") {
        missingFields.push(field)
      }
    }

    if (missingFields.length > 0) {
      throw new Error(`Champs obligatoires manquants: ${missingFields.join(", ")}`)
    }

    // Validation du sexe
    if (!["M", "F"].includes(membreData.sexe)) {
      throw new Error("Le sexe doit être M (Masculin) ou F (Féminin)")
    }

    // Validation de l'année
    const currentYear = new Date().getFullYear()
    const annee = Number.parseInt(membreData.annee_debut)
    if (annee < 1972 || annee > currentYear) {
      throw new Error(`L'année de début doit être entre 1972 et ${currentYear}`)
    }

    // Validation conditionnelle selon le statut
    const statut = membreData.je_suis
    switch (statut) {
      case "SR":
        if (!membreData.region || !membreData.qualite_membre) {
          throw new Error("Pour un SR, les champs 'région' et 'qualité membre' sont requis")
        }
        break
      case "SOUS_COMITE":
        if (!membreData.region || !membreData.ville_ou_commune || !membreData.qualite_membre) {
          throw new Error(
            "Pour un membre de sous-comité, les champs 'région', 'ville/commune' et 'qualité membre' sont requis",
          )
        }
        break
      case "BUREAU_SECTION":
        if (!membreData.region || !membreData.ville_ou_commune || !membreData.section || !membreData.qualite_membre) {
          throw new Error("Pour un membre de bureau de section, tous les champs de localisation sont requis")
        }
        break
      case "MEMBRE_SIMPLE":
        if (!membreData.region || !membreData.ville_ou_commune || !membreData.section) {
          throw new Error("Pour un membre simple, les champs 'région', 'ville/commune' et 'section' sont requis")
        }
        break
    }

    console.log("✅ Validation côté client réussie")

    const response = await apiClient.post("/api-carte-membre.php?action=create_membre", membreData)

    if (response.data.success) {
      console.log("✅ Membre créé avec succès:", response.data)
      return response.data
    } else {
      console.error("❌ Erreur lors de la création:", response.data.error)
      throw new Error(response.data.error || "Erreur lors de la création du membre")
    }
  } catch (error) {
    console.error("❌ Erreur lors de la création du membre:", error)

    if (error.response) {
      // Erreur de réponse du serveur
      const errorData = error.response.data
      const errorMessage = errorData?.error || error.response.statusText || "Erreur serveur"

      console.error("❌ Détails de l'erreur serveur:", errorData)

      // Si c'est une erreur de validation, on affiche les détails
      if (errorData?.missing_fields) {
        throw new Error(`Champs manquants: ${errorData.missing_fields.join(", ")}`)
      }

      if (errorData?.missing) {
        const missingDetails = Object.entries(errorData.missing)
          .filter(([key, value]) => value)
          .map(([key]) => key)
        throw new Error(`Champs requis manquants pour ${errorData.statut}: ${missingDetails.join(", ")}`)
      }

      throw new Error(`Erreur serveur (${error.response.status}): ${errorMessage}`)
    } else if (error.request) {
      // Erreur de réseau
      console.error("🌐 Erreur réseau - Vérifiez votre connexion internet")
      console.error("🔍 Détails de la requête:", error.request)
      throw new Error(
        `Impossible de se connecter au serveur. Vérifiez que le serveur est accessible à l'adresse: ${API_BASE_URL}`,
      )
    } else {
      // Autre erreur
      console.error("❌ Erreur:", error.message)
      throw error
    }
  }
}

// Fonction pour récupérer les statistiques
export const getStats = async () => {
  try {
    console.log("📊 Récupération des statistiques...")
    const response = await apiClient.get("/api-carte-membre.php?action=stats")

    if (response.data.success) {
      console.log("✅ Statistiques récupérées:", response.data.data)
      return response.data.data
    } else {
      throw new Error(response.data.error || "Erreur lors de la récupération des statistiques")
    }
  } catch (error) {
    console.error("❌ Erreur lors de la récupération des statistiques:", error)
    throw error
  }
}

// Fonction pour récupérer la liste des membres
export const getMembres = async (params = {}) => {
  try {
    console.log("👥 Récupération de la liste des membres...")
    const queryParams = new URLSearchParams({
      action: "membres",
      ...params,
    }).toString()

    const response = await apiClient.get(`/api-carte-membre.php?${queryParams}`)

    if (response.data.success) {
      console.log("✅ Membres récupérés:", response.data.data.length, "membres")
      return response.data
    } else {
      throw new Error(response.data.error || "Erreur lors de la récupération des membres")
    }
  } catch (error) {
    console.error("❌ Erreur lors de la récupération des membres:", error)
    throw error
  }
}

// Fonction pour récupérer un membre par ID
export const getMembreById = async (id) => {
  try {
    console.log(`👤 Récupération du membre ID: ${id}`)
    const response = await apiClient.get(`/api-carte-membre.php?action=membre&id=${id}`)

    if (response.data.success) {
      console.log("✅ Membre récupéré:", response.data.data)
      return response.data.data
    } else {
      throw new Error(response.data.error || "Membre non trouvé")
    }
  } catch (error) {
    console.error("❌ Erreur lors de la récupération du membre:", error)
    throw error
  }
}

// Fonction pour mettre à jour le statut de paiement
export const updatePaiement = async (paiementData) => {
  try {
    console.log("💳 Mise à jour du paiement...")
    const response = await apiClient.post("/api-carte-membre.php?action=update_paiement", paiementData)

    if (response.data.success) {
      console.log("✅ Paiement mis à jour:", response.data)
      return response.data
    } else {
      throw new Error(response.data.error || "Erreur lors de la mise à jour du paiement")
    }
  } catch (error) {
    console.error("❌ Erreur lors de la mise à jour du paiement:", error)
    throw error
  }
}

// Fonction pour vérifier l'unicité d'un matricule
export const verifyMatricule = async (matricule) => {
  try {
    console.log(`🔍 Vérification du matricule: ${matricule}`)
    const response = await apiClient.get(
      `/api-carte-membre.php?action=verify_matricule&matricule=${encodeURIComponent(matricule)}`,
    )

    if (response.data.success) {
      console.log("✅ Vérification matricule:", response.data)
      return response.data
    } else {
      throw new Error(response.data.error || "Erreur lors de la vérification du matricule")
    }
  } catch (error) {
    console.error("❌ Erreur lors de la vérification du matricule:", error)
    throw error
  }
}

// Fonction utilitaire pour déboguer les problèmes CORS
export const debugCORSUtil = async () => {
  try {
    console.log("🔍 Debug CORS - Test des headers...")

    // Test simple GET avec fetch natif
    const response = await fetch(`${API_BASE_URL}/api-carte-membre.php?action=test`, {
      method: "GET",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    })

    console.log("✅ Status:", response.status)
    console.log("✅ Headers:", [...response.headers.entries()])

    const text = await response.text()
    console.log("✅ Response:", text.substring(0, 500))

    return {
      success: true,
      status: response.status,
      headers: [...response.headers.entries()],
      preview: text.substring(0, 200),
    }
  } catch (error) {
    console.error("❌ Debug CORS échoué:", error)
    return { success: false, error: error.message }
  }
}

// Fonction pour tester différentes méthodes de connexion
export const testMultipleMethodsUtil = async () => {
  const results = {}

  // Test 1: Fetch natif
  try {
    console.log("🔍 Test 1: Fetch natif...")
    const response = await fetch(`${API_BASE_URL}/api-carte-membre.php?action=test`)
    results.fetch = { success: true, status: response.status }
  } catch (error) {
    results.fetch = { success: false, error: error.message }
  }

  // Test 2: Axios
  try {
    console.log("🔍 Test 2: Axios...")
    const response = await apiClient.get("/api-carte-membre.php?action=test")
    results.axios = { success: true, status: response.status }
  } catch (error) {
    results.axios = { success: false, error: error.message }
  }

  // Test 3: XMLHttpRequest
  try {
    console.log("🔍 Test 3: XMLHttpRequest...")
    await new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest()
      xhr.open("GET", `${API_BASE_URL}/api-carte-membre.php?action=test`)
      xhr.onload = () => {
        if (xhr.status === 200) {
          results.xhr = { success: true, status: xhr.status }
          resolve()
        } else {
          results.xhr = { success: false, error: `Status ${xhr.status}` }
          reject()
        }
      }
      xhr.onerror = () => {
        results.xhr = { success: false, error: "Network error" }
        reject()
      }
      xhr.send()
    })
  } catch (error) {
    if (!results.xhr) {
      results.xhr = { success: false, error: error.message }
    }
  }

  console.log("📊 Résultats des tests:", results)
  return results
}

// Export de l'objet carteMembreApi pour compatibilité
export const carteMembreApi = {
  createMembre,
  getMembres,
  getMembreById,
  getStats,
  updatePaiement,
  verifyMatricule,
  uploadPhoto, // ✅ Fonction uploadPhoto
  uploadImage, // ✅ Fonction uploadImage pour compatibilité
  testConnection,
  debugCORSUtil,
  testMultipleMethodsUtil,
}

// Export par défaut avec toutes les fonctions
export default {
  testConnection,
  createMembre,
  getStats,
  getMembres,
  getMembreById,
  updatePaiement,
  verifyMatricule,
  uploadPhoto, // ✅ Fonction uploadPhoto
  uploadImage, // ✅ Fonction uploadImage pour compatibilité
  debugCORSUtil,
  testMultipleMethodsUtil,
  carteMembreApi,

  // Configuration
  API_BASE_URL,
  API_TIMEOUT,
  cloudinaryConfig,
}

// Utilitaires pour les cartes de membres
export const membreUtils = {
  formatPhoneNumber(numero) {
    if (!numero) return ""

    let value = numero.replace(/\D/g, "")
    if (value.length > 10) value = value.substring(0, 10)

    let formatted = ""
    for (let i = 0; i < value.length; i++) {
      if (i > 0 && i % 2 === 0) formatted += " "
      formatted += value[i]
    }

    return formatted
  },

  validateMembreData(membreData) {
    const errors = []

    const requiredFields = [
      "prenom",
      "nom",
      "date_naiss",
      "lieu_naiss",
      "contact",
      "sexe",
      "secretariat_debut",
      "souscomite_debut",
      "annee_debut",
      "je_suis",
    ]

    requiredFields.forEach((field) => {
      if (!membreData[field] && membreData[field] !== 0) {
        errors.push(`Le champ ${field} est requis`)
      }
    })

    const currentYear = new Date().getFullYear()
    if (membreData.annee_debut) {
      const annee = Number.parseInt(membreData.annee_debut)
      if (annee < 1972 || annee > currentYear) {
        errors.push(`L'année de début doit être entre 1972 et ${currentYear}`)
      }
    }

    if (membreData.contact && membreData.contact.replace(/\D/g, "").length !== 10) {
      errors.push("Le numéro de contact doit contenir 10 chiffres")
    }

    if (membreData.sexe && !["M", "F"].includes(membreData.sexe)) {
      errors.push("Le sexe doit être M ou F")
    }

    return {
      isValid: errors.length === 0,
      errors: errors,
    }
  },

  formatDataForAPI(formData) {
    return {
      prenom: formData.prenom?.toUpperCase() || "",
      nom: formData.nom?.toUpperCase() || "",
      date_naiss: formData.dateNaissance || "",
      lieu_naiss: formData.lieuNaissance?.toUpperCase() || "",
      contact: formData.contact || "",
      sexe: formData.sexe || "",
      secretariat_debut: formData.srDebut || "",
      souscomite_debut: formData.sousComite || "",
      annee_debut: formData.anneeDebut || "",
      je_suis: formData.statut || "",
      region: formData.secretariatPoste || null,
      ville_ou_commune: formData.communeVille || null,
      section: formData.section || null,
      qualite_membre: formData.poste || null,
      poste_definition: formData.posteDefinition || null,
      photo_membre: formData.photo || null,
      photo_id: formData.photoId || null,
      ref_paiement: formData.refPaiement || null,
      numero_wave: formData.numeroWave || null,
    }
  },
}
