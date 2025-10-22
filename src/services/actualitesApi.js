import axios from "axios"

// Configuration de base pour l'API des actualités
const API_BASE_URL = "http://sogetrag.com/apistage"
const API_TIMEOUT = 20000 // 20 secondes

// Configuration de la base de données
const DB_CONFIG = {
  host: '91.216.107.162',
  dbname: 'soget2616263',
  username: 'soget2616263',
  password: '0Objectif-'
}

// Créer une instance Axios pour les actualités
const actualitesApiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: API_TIMEOUT,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
  withCredentials: false,
})

// Intercepteur pour les requêtes
actualitesApiClient.interceptors.request.use(
  (config) => {
    console.log(`🔄 Actualités API Request: ${config.method?.toUpperCase()} ${config.url}`)
    if (config.data) {
      console.log("📤 Données envoyées:", config.data)
    }
    if (config.params) {
      console.log("📤 Paramètres:", config.params)
    }
    return config
  },
  (error) => {
    console.error("❌ Erreur de requête actualités:", error)
    return Promise.reject(error)
  },
)

// Intercepteur pour les réponses
actualitesApiClient.interceptors.response.use(
  (response) => {
    console.log(`✅ Actualités API Response: ${response.status}`, response.data)
    return response
  },
  (error) => {
    console.error("❌ Erreur de réponse actualités:", error)
    return Promise.reject(error)
  },
)

// Service API pour les actualités
export const actualitesApi = {
  /**
   * Récupérer les 5 dernières actualités
   * @returns {Promise} Liste des actualités
   */
  async getLatestActualites() {
    try {
      console.log("📰 Récupération des dernières actualités...")
      const response = await actualitesApiClient.get("/api-actualites2.php", {
        params: {
          action: "get_latest",
          limit: 5
        }
      })

      if (response.data.success) {
        console.log("✅ Actualités récupérées:", response.data.data.length, "articles")
        return response.data.data
      } else {
        throw new Error(response.data.error || "Erreur lors de la récupération des actualités")
      }
    } catch (error) {
      console.error("❌ Erreur lors de la récupération des actualités:", error)
      throw error
    }
  },

  /**
   * Récupérer une actualité par ID
   * @param {number} id - ID de l'actualité
   * @returns {Promise} Données de l'actualité
   */
  async getActualiteById(id) {
    try {
      console.log(`📰 Récupération de l'actualité ID: ${id}`)
      const response = await actualitesApiClient.get("/api-actualites2.php", {
        params: {
          action: "get_by_id",
          id: id
        }
      })

      if (response.data.success) {
        console.log("✅ Actualité récupérée:", response.data.data)
        return response.data.data
      } else {
        throw new Error(response.data.error || "Actualité non trouvée")
      }
    } catch (error) {
      console.error("❌ Erreur lors de la récupération de l'actualité:", error)
      throw error
    }
  },

  /**
   * Récupérer toutes les actualités avec pagination
   * @param {Object} params - Paramètres de pagination
   * @returns {Promise} Liste paginée des actualités
   */
  async getAllActualites(params = {}) {
    try {
      console.log("📰 Récupération de toutes les actualités...")
      const response = await actualitesApiClient.get("/api-actualites2.php", {
        params: {
          action: "get_all",
          ...params
        }
      })

      if (response.data.success) {
        console.log("✅ Actualités récupérées:", response.data.data.length, "articles")
        return response.data
      } else {
        throw new Error(response.data.error || "Erreur lors de la récupération des actualités")
      }
    } catch (error) {
      console.error("❌ Erreur lors de la récupération des actualités:", error)
      throw error
    }
  },

  /**
   * Créer une nouvelle actualité
   * @param {Object} actualiteData - Données de l'actualité
   * @returns {Promise} Actualité créée
   */
  async createActualite(actualiteData) {
    try {
      console.log("📝 Création d'une nouvelle actualité...")
      console.log("📤 Données de l'actualité:", actualiteData)

      const response = await actualitesApiClient.post("/api-actualites2.php", actualiteData, {
        params: {
          action: "create"
        }
      })

      if (response.data.success) {
        console.log("✅ Actualité créée avec succès:", response.data.data)
        return response.data.data
      } else {
        throw new Error(response.data.error || "Erreur lors de la création de l'actualité")
      }
    } catch (error) {
      console.error("❌ Erreur lors de la création de l'actualité:", error)
      throw error
    }
  },

  /**
   * Mettre à jour une actualité
   * @param {number} id - ID de l'actualité
   * @param {Object} actualiteData - Nouvelles données
   * @returns {Promise} Actualité mise à jour
   */
  async updateActualite(id, actualiteData) {
    try {
      console.log(`📝 Mise à jour de l'actualité ID: ${id}`)
      const response = await actualitesApiClient.put("/api-actualites2.php", actualiteData, {
        params: {
          action: "update",
          id: id
        }
      })

      if (response.data.success) {
        console.log("✅ Actualité mise à jour:", response.data.data)
        return response.data.data
      } else {
        throw new Error(response.data.error || "Erreur lors de la mise à jour de l'actualité")
      }
    } catch (error) {
      console.error("❌ Erreur lors de la mise à jour de l'actualité:", error)
      throw error
    }
  },

  /**
   * Supprimer une actualité
   * @param {number} id - ID de l'actualité
   * @returns {Promise} Résultat de la suppression
   */
  async deleteActualite(id) {
    try {
      console.log(`🗑️ Suppression de l'actualité ID: ${id}`)
      const response = await actualitesApiClient.delete("/api-actualites2.php", {
        params: {
          action: "delete",
          id: id
        }
      })

      if (response.data.success) {
        console.log("✅ Actualité supprimée:", response.data.message)
        return response.data
      } else {
        throw new Error(response.data.error || "Erreur lors de la suppression de l'actualité")
      }
    } catch (error) {
      console.error("❌ Erreur lors de la suppression de l'actualité:", error)
      throw error
    }
  },

  /**
   * Upload d'une image pour une actualité
   * @param {File} file - Fichier image
   * @param {number} actualiteId - ID de l'actualité (optionnel)
   * @returns {Promise} URL de l'image uploadée
   */
  async uploadImage(file, actualiteId = null) {
    try {
      console.log("🔄 Upload d'image pour actualité...")
      
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

      const formData = new FormData()
      formData.append("image", file)
      if (actualiteId) {
        formData.append("actualite_id", actualiteId)
      }

      const response = await actualitesApiClient.post("/api-actualites2.php", formData, {
        params: {
          action: "upload_image"
        },
        headers: {
          "Content-Type": "multipart/form-data",
        },
        onUploadProgress: (progressEvent) => {
          const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          console.log(`📤 Upload Progress: ${percentCompleted}%`)
        },
      })

      if (response.data.success) {
        console.log("✅ Image uploadée avec succès:", response.data.data.url)
        return response.data.data
      } else {
        throw new Error(response.data.error || "Erreur lors de l'upload de l'image")
      }
    } catch (error) {
      console.error("❌ Erreur lors de l'upload de l'image:", error)
      throw error
    }
  },

  /**
   * Gestion des erreurs
   * @param {Error} error - Erreur capturée
   * @param {string} defaultMessage - Message par défaut
   * @returns {Error} Erreur formatée
   */
  handleError(error, defaultMessage) {
    const message = error.response?.data?.error || error.message || defaultMessage
    const statusCode = error.response?.status || 500

    const formattedError = new Error(message)
    formattedError.statusCode = statusCode
    formattedError.originalError = error

    return formattedError
  },
}

// Fonction utilitaire pour formater les données d'actualité
export const actualiteUtils = {
  /**
   * Formater une actualité pour l'affichage
   * @param {Object} actualite - Données brutes de l'actualité
   * @returns {Object} Données formatées
   */
  formatActualite(actualite) {
    return {
      id: actualite.id,
      title: actualite.titre || actualite.title,
      date: actualite.date_publication || actualite.date,
      excerpt: actualite.resume || actualite.excerpt || actualite.contenu?.substring(0, 150) + "...",
      content: actualite.contenu || actualite.content,
      image: actualite.image_url || actualite.image,
      author: actualite.auteur || actualite.author,
      category: actualite.categorie || actualite.category,
      status: actualite.statut || actualite.status,
      created_at: actualite.created_at,
      updated_at: actualite.updated_at
    }
  },

  /**
   * Formater la date pour l'affichage
   * @param {string} dateString - Date au format ISO
   * @returns {string} Date formatée
   */
  formatDate(dateString) {
    if (!dateString) return ""
    
    const date = new Date(dateString)
    const options = { 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    }
    
    return date.toLocaleDateString('fr-FR', options)
  },

  /**
   * Tronquer le texte
   * @param {string} text - Texte à tronquer
   * @param {number} maxLength - Longueur maximale
   * @returns {string} Texte tronqué
   */
  truncateText(text, maxLength = 150) {
    if (!text) return ""
    if (text.length <= maxLength) return text
    return text.substring(0, maxLength).trim() + "..."
  }
}

// Export par défaut
export default actualitesApi
