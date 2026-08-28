import axios from 'axios'

const API_BASE_URL = 'https://api.aeemci-ce.ci/api_mobile'
const API_FILE = '/aeemci_actualites_api.php'
const API_TIMEOUT = 20000

const actualitesApiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: API_TIMEOUT,
  headers: {
    Accept: 'application/json',
  },
  withCredentials: false,
})

const parsePhotos = (photos) => {
  if (!photos) return []
  if (Array.isArray(photos)) return photos
  if (typeof photos === 'string') {
    try {
      const parsed = JSON.parse(photos)
      return Array.isArray(parsed) ? parsed : []
    } catch {
      return photos ? [{ url: photos }] : []
    }
  }
  return []
}

const photoUrl = (photo) => {
  if (!photo) return ''
  if (typeof photo === 'string') return photo
  return photo.url || photo.preview || photo.image || ''
}

const normalizeActualite = (actualite = {}) => {
  const photos = parsePhotos(actualite.photos)
  const image = actualite.image || actualite.image_url || photoUrl(photos[0])
  const title = actualite.titre || actualite.title || ''
  const excerpt = actualite.texte_affichage || actualite.excerpt || actualite.resume || ''
  const content = actualite.texte_detaille || actualite.content || actualite.contenu || ''
  const type = actualite.type || actualite.category || 'Actualité'

  return {
    ...actualite,
    id: Number(actualite.id),
    titre: title,
    title,
    date: actualite.date_specifique || actualite.date_debut || actualite.published_at || actualite.created_at || actualite.date || '',
    excerpt,
    content,
    texte_affichage: excerpt,
    texte_detaille: content,
    image,
    image_url: image,
    auteur: actualite.auteur || actualite.author || 'AEEMCI Communication',
    author: actualite.author || actualite.auteur || 'AEEMCI Communication',
    type,
    category: type,
    lieu: actualite.lieu || actualite.location || '',
    location: actualite.location || actualite.lieu || '',
    photos,
    statut: actualite.statut || actualite.status || 'PUBLIÉ',
  }
}

export const actualitesApi = {
  async getLatestActualites(limit = 4) {
    try {
      const response = await actualitesApiClient.get(API_FILE, {
        params: { action: 'latest', limit },
      })
      if (response.data.success) {
        return (response.data.data || []).map(normalizeActualite)
      }
      throw new Error(response.data.error || response.data.message || 'Erreur lors de la récupération des actualités')
    } catch (error) {
      throw this.handleError(error, 'Erreur lors de la récupération des actualités')
    }
  },

  async getActualiteById(id) {
    try {
      const response = await actualitesApiClient.get(API_FILE, {
        params: { action: 'get', id },
      })
      if (response.data.success) return normalizeActualite(response.data.data)
      throw new Error(response.data.error || response.data.message || 'Actualité non trouvée')
    } catch (error) {
      throw this.handleError(error, "Erreur lors de la récupération de l'actualité")
    }
  },

  async getActualite(id) {
    return this.getActualiteById(id)
  },

  async getAllActualites(params = {}) {
    try {
      const response = await actualitesApiClient.get(API_FILE, {
        params: { action: 'list', ...params },
      })
      if (response.data.success) {
        return {
          ...response.data,
          data: (response.data.data || []).map(normalizeActualite),
        }
      }
      throw new Error(response.data.error || response.data.message || 'Erreur lors de la récupération des actualités')
    } catch (error) {
      throw this.handleError(error, 'Erreur lors de la récupération des actualités')
    }
  },

  handleError(error, defaultMessage) {
    const message = error.response?.data?.error || error.response?.data?.message || error.message || defaultMessage
    const formattedError = new Error(message)
    formattedError.statusCode = error.response?.status || 500
    formattedError.originalError = error
    return formattedError
  },
}

export const actualiteUtils = {
  formatActualite(actualite) {
    return normalizeActualite(actualite)
  },

  formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    if (Number.isNaN(date.getTime())) return String(dateString)
    return date.toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })
  },

  truncateText(text, maxLength = 150) {
    if (!text) return ''
    const clean = String(text).replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim()
    if (clean.length <= maxLength) return clean
    return clean.substring(0, maxLength).trim() + '...'
  },

  getImage(actualite) {
    return normalizeActualite(actualite).image
  },

  getPhotos(actualite) {
    return parsePhotos(actualite?.photos).map(photoUrl).filter(Boolean)
  },
}

export default actualitesApi
