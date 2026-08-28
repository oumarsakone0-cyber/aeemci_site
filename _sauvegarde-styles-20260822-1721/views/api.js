import axios from "axios"

// Configuration de base
const API_BASE_URL = "https://sastock.com/api"

// Configuration Axios avec CORS
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 30000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
  withCredentials: false,
})

// Intercepteur de requête
apiClient.interceptors.request.use(
  (config) => {
    console.log("🔄 Requête API:", {
      method: config.method?.toUpperCase(),
      url: config.url,
      baseURL: config.baseURL,
      params: config.params,
      data: config.data ? "Données présentes" : "Pas de données",
    })
    return config
  },
  (error) => {
    console.error("❌ Erreur de configuration requête:", error)
    return Promise.reject(error)
  },
)

// Intercepteur de réponse
apiClient.interceptors.response.use(
  (response) => {
    console.log("✅ Réponse API reçue:", {
      status: response.status,
      statusText: response.statusText,
      data: response.data,
    })
    return response
  },
  (error) => {
    console.error("❌ Erreur de réponse:", error)

    if (error.code === "ERR_NETWORK") {
      console.log("🌐 Erreur réseau - Problème CORS ou serveur inaccessible")
      console.log("🔍 URL tentée:", error.config?.url)
      console.log("🔍 Méthode:", error.config?.method)
      throw new Error(
        "Impossible de se connecter au serveur. Vérifiez que le serveur est accessible et que CORS est configuré correctement.",
      )
    }

    if (error.response) {
      console.log("📊 Détails de l'erreur serveur:", {
        status: error.response.status,
        statusText: error.response.statusText,
        data: error.response.data,
      })
      throw new Error(`Erreur serveur ${error.response.status}: ${error.response.statusText}`)
    }

    throw error
  },
)

// Fonctions utilitaires pour les membres
export const membreUtils = {
  // Formatage du numéro de téléphone
  formatPhoneNumber: (phone) => {
    if (!phone) return ""
    const cleaned = phone.replace(/\D/g, "")
    const match = cleaned.match(/^(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/)
    if (match) {
      return `${match[1]} ${match[2]} ${match[3]} ${match[4]} ${match[5]}`
    }
    return phone
  },

  // Validation des données de membre
  validateMembreData: (data) => {
    const errors = []

    if (!data.prenom) errors.push("Le prénom est requis")
    if (!data.nom) errors.push("Le nom est requis")
    if (!data.dateNaissance) errors.push("La date de naissance est requise")
    if (!data.lieuNaissance) errors.push("Le lieu de naissance est requis")
    if (!data.contact) errors.push("Le contact est requis")
    if (!data.sexe) errors.push("Le sexe est requis")
    if (!data.srDebut) errors.push("Le secrétariat de début est requis")
    if (!data.sousComite) errors.push("Le sous-comité est requis")
    if (!data.anneeDebut) errors.push("L'année de début est requise")
    if (!data.statut) errors.push("Le statut est requis")

    return {
      isValid: errors.length === 0,
      errors,
    }
  },

  // Formatage des données pour l'API
  formatDataForAPI: (formData) => {
    return {
      prenom: formData.prenom?.trim(),
      nom: formData.nom?.trim().toUpperCase(),
      date_naissance: formData.dateNaissance,
      lieu_naissance: formData.lieuNaissance?.trim().toUpperCase(),
      contact: formData.contact?.replace(/\s/g, ""),
      sexe: formData.sexe,
      sr_debut: formData.srDebut,
      sous_comite: formData.sousComite?.trim().toUpperCase(),
      annee_debut: Number.parseInt(formData.anneeDebut),
      statut: formData.statut,
      secretariat_poste: formData.secretariatPoste,
      poste: formData.poste,
      commune_ville: formData.communeVille?.trim().toUpperCase(),
      section: formData.section?.trim().toUpperCase(),
      photo_url: formData.photoCloudinaryUrl,
      photo_public_id: formData.photoCloudinaryPublicId,
      numero_wave: formData.numeroWave?.replace(/\s/g, ""),
      created_at: new Date().toISOString(),
    }
  },
}

// API pour la gestion des membres
export const carteMembreApi = {
  // Créer un nouveau membre
  createMembre: async (membreData) => {
    try {
      console.log("🔄 Création d'un membre...")

      const response = await apiClient.post("/api-carte-membre-cors-fixed.php", membreData, {
        params: { action: "create_membre" },
      })

      if (response.data && response.data.success) {
        console.log("✅ Membre créé avec succès")
        return {
          success: true,
          data: response.data.data,
          message: response.data.message,
        }
      } else {
        console.error("❌ Erreur lors de la création:", response.data?.error || "Erreur inconnue")
        return {
          success: false,
          error: response.data?.error || "Erreur lors de la création du membre",
        }
      }
    } catch (error) {
      console.error("❌ Erreur lors de la création du membre:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Récupérer les statistiques
  getStats: async () => {
    try {
      console.log("🔄 Récupération des statistiques...")

      const response = await apiClient.get("/api-carte-membre-cors-fixed.php", {
        params: { action: "get_stats" },
      })

      if (response.data && response.data.success) {
        console.log("✅ Statistiques récupérées")
        return {
          success: true,
          data: response.data.data,
        }
      } else {
        return {
          success: false,
          error: response.data?.error || "Erreur lors de la récupération des statistiques",
        }
      }
    } catch (error) {
      console.error("❌ Erreur lors de la récupération des statistiques:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Récupérer tous les membres
  getMembres: async (page = 1, limit = 10) => {
    try {
      console.log("🔄 Récupération des membres...")

      const response = await apiClient.get("/api-carte-membre-cors-fixed.php", {
        params: {
          action: "get_membres",
          page: page,
          limit: limit,
        },
      })

      if (response.data && response.data.success) {
        console.log("✅ Membres récupérés")
        return {
          success: true,
          data: response.data.data,
          pagination: response.data.pagination,
        }
      } else {
        return {
          success: false,
          error: response.data?.error || "Erreur lors de la récupération des membres",
        }
      }
    } catch (error) {
      console.error("❌ Erreur lors de la récupération des membres:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Récupérer un membre par ID
  getMembreById: async (id) => {
    try {
      console.log("🔄 Récupération du membre ID:", id)

      const response = await apiClient.get("/api-carte-membre-cors-fixed.php", {
        params: {
          action: "get_membre",
          id: id,
        },
      })

      if (response.data && response.data.success) {
        console.log("✅ Membre récupéré")
        return {
          success: true,
          data: response.data.data,
        }
      } else {
        return {
          success: false,
          error: response.data?.error || "Membre non trouvé",
        }
      }
    } catch (error) {
      console.error("❌ Erreur lors de la récupération du membre:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Mettre à jour le statut de paiement
  updatePaiement: async (membreId, paiementData) => {
    try {
      console.log("🔄 Mise à jour du paiement pour le membre:", membreId)

      const response = await apiClient.post(
        "/api-carte-membre-cors-fixed.php",
        {
          membre_id: membreId,
          ...paiementData,
        },
        {
          params: { action: "update_paiement" },
        },
      )

      if (response.data && response.data.success) {
        console.log("✅ Paiement mis à jour")
        return {
          success: true,
          data: response.data.data,
        }
      } else {
        return {
          success: false,
          error: response.data?.error || "Erreur lors de la mise à jour du paiement",
        }
      }
    } catch (error) {
      console.error("❌ Erreur lors de la mise à jour du paiement:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Vérifier l'unicité du matricule
  verifyMatricule: async (matricule) => {
    try {
      console.log("🔄 Vérification du matricule:", matricule)

      const response = await apiClient.get("/api-carte-membre-cors-fixed.php", {
        params: {
          action: "verify_matricule",
          matricule: matricule,
        },
      })

      if (response.data && response.data.success !== undefined) {
        console.log("✅ Vérification du matricule terminée")
        return {
          success: true,
          available: response.data.available,
        }
      } else {
        return {
          success: false,
          error: response.data?.error || "Erreur lors de la vérification du matricule",
        }
      }
    } catch (error) {
      console.error("❌ Erreur lors de la vérification du matricule:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Générer une fiche PDF
  generateFiche: async (membreData) => {
    try {
      console.log("🔄 Génération de la fiche PDF...")

      const response = await apiClient.post("/api-carte-membre-cors-fixed.php", membreData, {
        params: { action: "generate_fiche" },
        responseType: "blob",
      })

      // Créer un lien de téléchargement
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement("a")
      link.href = url
      link.setAttribute("download", `fiche_membre_${membreData.matricule_gen || "nouveau"}.pdf`)
      document.body.appendChild(link)
      link.click()
      link.remove()
      window.URL.revokeObjectURL(url)

      console.log("✅ Fiche PDF générée et téléchargée")
      return {
        success: true,
        message: "Fiche PDF générée avec succès",
      }
    } catch (error) {
      console.error("❌ Erreur lors de la génération de la fiche:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },

  // Test de connexion
  testConnection: async () => {
    try {
      console.log("🔄 Test de connexion à l'API...")

      const response = await apiClient.get("/api-carte-membre-cors-fixed.php", {
        params: { action: "test" },
      })

      if (response.data) {
        console.log("✅ Connexion API réussie")
        return {
          success: true,
          data: response.data,
          message: "Connexion réussie",
        }
      } else {
        return {
          success: false,
          error: "Pas de réponse du serveur",
        }
      }
    } catch (error) {
      console.error("❌ Erreur de connexion:", error.message)
      return {
        success: false,
        error: error.message,
      }
    }
  },
}

// Upload direct vers Cloudinary
export const uploadPhoto = async (file, progressCallback = null, description = "") => {
  try {
    console.log("🔄 Upload vers Cloudinary...")

    // Validation du fichier
    if (!file) {
      throw new Error("Aucun fichier fourni")
    }

    if (!file.type.startsWith("image/")) {
      throw new Error("Le fichier doit être une image")
    }

    if (file.size > 5 * 1024 * 1024) {
      // 5MB
      throw new Error("Le fichier ne peut pas dépasser 5MB")
    }

    // Configuration Cloudinary
    const cloudName = "dqmhtibfm"
    const uploadPreset = "ml_default"

    // Créer FormData
    const formData = new FormData()
    formData.append("file", file)
    formData.append("upload_preset", uploadPreset)
    formData.append("folder", "membres_aeemci")

    // Nom unique avec timestamp
    const timestamp = Date.now()
    const fileName = `membre_${timestamp}_${file.name.replace(/[^a-zA-Z0-9.]/g, "_")}`
    formData.append("public_id", `membres_aeemci/${fileName}`)

    if (description) {
      formData.append("context", `description=${description}`)
    }

    // Upload avec suivi de progression
    const response = await axios.post(`https://api.cloudinary.com/v1_1/${cloudName}/image/upload`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      onUploadProgress: (progressEvent) => {
        if (progressCallback) {
          const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          progressCallback(percentCompleted)
        }
      },
    })

    if (response.data && response.data.secure_url) {
      console.log("✅ Upload Cloudinary réussi:", response.data.secure_url)
      return {
        success: true,
        data: {
          url: response.data.secure_url,
          public_id: response.data.public_id,
          width: response.data.width,
          height: response.data.height,
          format: response.data.format,
          bytes: response.data.bytes,
        },
      }
    } else {
      throw new Error("Réponse Cloudinary invalide")
    }
  } catch (error) {
    console.error("❌ Erreur upload Cloudinary:", error)

    if (error.response) {
      console.error("Détails erreur Cloudinary:", error.response.data)
      return {
        success: false,
        error: `Erreur Cloudinary: ${error.response.data.error?.message || "Erreur inconnue"}`,
      }
    }

    return {
      success: false,
      error: error.message || "Erreur lors de l'upload",
    }
  }
}

// Upload d'images génériques
export const uploadImage = async (file, folder = "general", progressCallback = null) => {
  try {
    console.log("🔄 Upload image vers Cloudinary...")

    // Validation
    if (!file || !file.type.startsWith("image/")) {
      throw new Error("Fichier image requis")
    }

    if (file.size > 5 * 1024 * 1024) {
      throw new Error("Fichier trop volumineux (max 5MB)")
    }

    // Configuration
    const cloudName = "dqmhtibfm"
    const uploadPreset = "ml_default"

    const formData = new FormData()
    formData.append("file", file)
    formData.append("upload_preset", uploadPreset)
    formData.append("folder", folder)

    // Nom unique
    const timestamp = Date.now()
    const fileName = `img_${timestamp}_${file.name.replace(/[^a-zA-Z0-9.]/g, "_")}`
    formData.append("public_id", `${folder}/${fileName}`)

    const response = await axios.post(`https://api.cloudinary.com/v1_1/${cloudName}/image/upload`, formData, {
      headers: { "Content-Type": "multipart/form-data" },
      onUploadProgress: (progressEvent) => {
        if (progressCallback) {
          const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          progressCallback(percent)
        }
      },
    })

    if (response.data?.secure_url) {
      console.log("✅ Upload image réussi")
      return {
        success: true,
        data: {
          url: response.data.secure_url,
          public_id: response.data.public_id,
          width: response.data.width,
          height: response.data.height,
        },
      }
    }

    throw new Error("Réponse Cloudinary invalide")
  } catch (error) {
    console.error("❌ Erreur upload image:", error)
    return {
      success: false,
      error: error.response?.data?.error?.message || error.message,
    }
  }
}

// Fonction utilitaire pour déboguer les problèmes CORS
export const debugCORSUtil = async () => {
  try {
    console.log("🔍 Debug CORS - Test des headers...")

    // Test simple GET avec fetch natif
    const response = await fetch(`${API_BASE_URL}/api-carte-membre-cors-fixed.php?action=test`, {
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
    const response = await fetch(`${API_BASE_URL}/api-carte-membre-cors-fixed.php?action=test`)
    results.fetch = { success: true, status: response.status }
  } catch (error) {
    results.fetch = { success: false, error: error.message }
  }

  // Test 2: Axios
  try {
    console.log("🔍 Test 2: Axios...")
    const response = await apiClient.get("/api-carte-membre-cors-fixed.php", {
      params: { action: "test" },
    })
    results.axios = { success: true, status: response.status }
  } catch (error) {
    results.axios = { success: false, error: error.message }
  }

  // Test 3: XMLHttpRequest
  try {
    console.log("🔍 Test 3: XMLHttpRequest...")
    await new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest()
      xhr.open("GET", `${API_BASE_URL}/api-carte-membre-cors-fixed.php?action=test`)
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

// Export par défaut
export default {
  carteMembreApi,
  membreUtils,
  uploadPhoto,
  uploadImage,
  apiClient,
}

export { testConnection }
