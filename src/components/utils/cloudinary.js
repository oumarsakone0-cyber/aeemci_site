import axios from 'axios'

const cloudinaryConfig = {
  cloudName: "dqk65objc",
  uploadPreset: "sadeal",
  apiKey: "784574272958761",
  uploadUrl: "https://api.cloudinary.com/v1_1/dqk65objc/image/upload",
}

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
