<template>
    <div class="app">
      <!-- Bannière élégante avec motif islamique -->
      <section class="page-banner">
        <div class="banner-pattern"></div>
        
        <!-- Ajout des motifs islamiques animés -->
        <div class="islamic-patterns">
          <!-- Étoiles -->
          <div v-for="n in 8" :key="`star-${n}`" class="islamic-pattern star" :style="{
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`,
            animationDuration: `${15 + Math.random() * 20}s`
          }"></div>
          
          <!-- Croissants -->
          <div v-for="n in 5" :key="`crescent-${n}`" class="islamic-pattern crescent" :style="{
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`,
            animationDuration: `${15 + Math.random() * 20}s`
          }"></div>
          
          <!-- Motifs géométriques -->
          <div v-for="n in 10" :key="`geometric-${n}`" class="islamic-pattern geometric" :style="{
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`,
            animationDuration: `${15 + Math.random() * 20}s`,
            transform: `rotate(${Math.random() * 360}deg)`
          }"></div>
        </div>
        
        <div class="container">
          <div class="page-title">
            <h1>Actualité <span class="highlight">AEEMCI</span></h1>
            <p class="subtitle">Détails de l'actualité</p>
            <div class="breadcrumbs">
              <a href="#" @click.prevent="goBack">Accueil</a> 
              <span class="separator">/</span> 
              <a href="#" @click.prevent="goBack">Actualités</a>
              <span class="separator">/</span> 
              <span class="current">Détail</span>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Contenu principal -->
      <main class="main-content">
        <div class="container">
          <!-- Bouton retour -->
          <div class="back-button-section">
            <button @click="goBack" class="btn btn-outline btn-back">
              ← Retour aux actualités
            </button>
          </div>
  
          <!-- Loading state -->
          <div v-if="loading" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Chargement de l'article...</p>
          </div>
          
          <!-- Error state -->
          <div v-else-if="error" class="error-state">
            <p>❌ {{ error }}</p>
            <button @click="loadArticle" class="btn btn-sm">Réessayer</button>
          </div>
          
          <!-- Article principal -->
          <article v-else class="article-detail animate-slide-up">
            <!-- En-tête de l'article -->
            <header class="article-header">
              <h1 class="article-title">{{ article.title }}</h1>
              <div class="article-meta">
                <div class="meta-item">
                  <span class="icon-calendar">📅</span>
                  <span>{{ article.date }}</span>
                </div>
                <div class="meta-item" v-if="article.author">
                  <span class="icon-author">👤</span>
                  <span>{{ article.author }}</span>
                </div>
                <div class="meta-item" v-if="article.category">
                  <span class="icon-category">🏷️</span>
                  <span>{{ article.category }}</span>
                </div>
                <div class="meta-item" v-if="article.location">
                  <span class="icon-location">📍</span>
                  <span>{{ article.location }}</span>
                </div>
              </div>
            </header>

            <!-- Image principale -->
            <div class="article-image" v-if="article.image">
              <img :src="article.image" :alt="article.title" class="img-responsive" />
              <div class="image-caption" v-if="article.imageCaption">
                {{ article.imageCaption }}
              </div>
            </div>

            <!-- Détails de l'événement -->
            <div class="event-details" v-if="article.location || article.type">
              <h3>Détails de l'événement</h3>
              <div class="details-grid">
                <div class="detail-item" v-if="article.date">
                  <strong>Date :</strong> {{ article.date }}
                </div>
                <div class="detail-item" v-if="article.location">
                  <strong>Lieu :</strong> {{ article.location }}
                </div>
                <div class="detail-item" v-if="article.type">
                  <strong>Type :</strong> {{ article.type }}
                </div>
                <div class="detail-item" v-if="article.author">
                  <strong>Publié par :</strong> {{ article.author }}
                </div>
              </div>
            </div>

            <!-- Contenu de l'article -->
            <div class="article-content" v-if="article.content">
              <div class="content-section">
                <div v-html="article.content"></div>
              </div>
            </div>
  
            <!-- Galerie d'images -->
            <div class="article-gallery" v-if="article.gallery && article.gallery.length > 0">
              <h3>Galerie photos</h3>
              <div class="gallery-grid">
                <div 
                  v-for="(image, index) in article.gallery" 
                  :key="index" 
                  class="gallery-item"
                  @click="openLightbox(index)"
                >
                  <img :src="image.thumbnail || image.url" :alt="image.caption || 'Photo'" />
                  <div class="gallery-overlay">
                    <span class="gallery-icon">🔍</span>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Conclusion -->
            <div class="article-conclusion" v-if="article.conclusion">
              <div class="conclusion-content">
                <p class="conclusion-text">{{ article.conclusion }}</p>
              </div>
            </div>
  
            <!-- Tags -->
            <div class="article-tags" v-if="article.tags && article.tags.length > 0">
              <h4>Mots-clés :</h4>
              <div class="tags-list">
                <span v-for="tag in article.tags" :key="tag" class="tag">
                  {{ tag }}
                </span>
              </div>
            </div>
  
            <!-- Partage -->
            <div class="article-share">
              <h4>Partager cet article :</h4>
              <div class="share-buttons">
                <button class="share-btn facebook" @click="shareOnFacebook">
                  📘 Facebook
                </button>
                <button class="share-btn twitter" @click="shareOnTwitter">
                  🐦 Twitter
                </button>
                <button class="share-btn whatsapp" @click="shareOnWhatsApp">
                  💬 WhatsApp
                </button>
                <button class="share-btn copy" @click="copyLink">
                  🔗 Copier le lien
                </button>
              </div>
            </div>
          </article>
  
          <!-- Articles similaires -->
          <section class="related-articles">
            <div class="section-header">
              <h2><span class="highlight">A</span>utres actualités</h2>
              <span class="section-icon">📰</span>
            </div>
            
            <div class="related-grid">
              <div 
                v-for="relatedArticle in relatedArticles" 
                :key="relatedArticle.id" 
                class="related-item"
                @click="loadArticleById(relatedArticle.id)"
              >
                <div class="related-image">
                  <img :src="relatedArticle.image" :alt="relatedArticle.title" />
                </div>
                <div class="related-content">
                  <h4>{{ relatedArticle.title }}</h4>
                  <div class="related-meta">
                    <span class="icon-calendar">📅</span>
                    <span>{{ relatedArticle.date }}</span>
                  </div>
                  <p>{{ relatedArticle.excerpt }}</p>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>
  
      <!-- Lightbox pour la galerie -->
      <div class="lightbox" v-if="lightboxOpen" @click="closeLightbox">
        <div class="lightbox-content" @click.stop>
          <button class="lightbox-close" @click="closeLightbox">×</button>
          <img :src="currentGalleryImage.url" :alt="currentGalleryImage.caption" class="lightbox-img" />
          <div class="lightbox-caption" v-if="currentGalleryImage.caption">
            {{ currentGalleryImage.caption }}
          </div>
          <button class="lightbox-nav prev" @click.stop="prevGalleryImage">❮</button>
          <button class="lightbox-nav next" @click.stop="nextGalleryImage">❯</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import { actualitesApi, actualiteUtils } from '@/services/actualitesApi.js'
  
  export default {
    name: 'ActualiteDetail',
    setup() {
      const route = useRoute()
      
      // État pour la lightbox
      const lightboxOpen = ref(false)
      const currentGalleryIndex = ref(0)
      
      // États de chargement
      const loading = ref(true)
      const error = ref(null)
      
      // Données de l'article
      const article = ref({
        id: null,
        title: '',
        date: '',
        author: '',
        category: '',
        image: '',
        imageCaption: '',
        content: '',
        excerpt: '',
        location: '',
        type: ''
      })
  
      // Articles similaires
      const relatedArticles = ref([])
      
      // Charger l'article depuis l'API
      const loadArticle = async () => {
        try {
          loading.value = true
          error.value = null
          
          const articleId = route.query.id || route.params.id
          console.log('🔄 Chargement de l\'article ID:', articleId)
          
          if (!articleId) {
            throw new Error('ID de l\'article non fourni')
          }
          
          // Charger tous les articles et trouver celui qui correspond à l'ID
          const allArticles = await actualitesApi.getLatestActualites()
          console.log('✅ Articles chargés:', allArticles.length)
          
          const articleData = allArticles.find(a => a.id == articleId)
          
          if (!articleData) {
            throw new Error('Article non trouvé')
          }
          
          console.log('✅ Article trouvé:', articleData)
          
          // Formater les données de l'article
          article.value = {
            id: articleData.id,
            title: articleData.titre || articleData.title,
            date: actualiteUtils.formatDate(articleData.date_specifique || articleData.date_debut || articleData.date),
            author: articleData.author || 'AEEMCI Communication',
            category: articleData.type || articleData.category || 'Actualité',
            image: articleData.image || '',
            imageCaption: articleData.titre || articleData.title,
            content: articleData.texte_detaille || articleData.content || '',
            excerpt: articleData.texte_affichage || articleData.excerpt || '',
            location: articleData.lieu || articleData.location || '',
            type: articleData.type || 'Actualité',
            date_start: articleData.date_debut,
            date_end: articleData.date_fin
          }
          
          // Utiliser les autres articles comme articles similaires
          relatedArticles.value = allArticles
            .filter(a => a.id != articleId)
            .slice(0, 3)
            .map(a => actualiteUtils.formatActualite(a))
          
        } catch (err) {
          console.error('❌ Erreur lors du chargement de l\'article:', err)
          error.value = err.message || 'Erreur lors du chargement de l\'article'
        } finally {
          loading.value = false
        }
      }
  
      // Image actuelle de la galerie
      const currentGalleryImage = computed(() => {
        return article.value.gallery[currentGalleryIndex.value] || {}
      })
  
      // Fonctions pour la lightbox
      const openLightbox = (index) => {
        currentGalleryIndex.value = index
        lightboxOpen.value = true
        document.body.style.overflow = 'hidden'
      }
  
      const closeLightbox = () => {
        lightboxOpen.value = false
        document.body.style.overflow = 'auto'
      }
  
      const nextGalleryImage = () => {
        currentGalleryIndex.value = (currentGalleryIndex.value + 1) % article.value.gallery.length
      }
  
      const prevGalleryImage = () => {
        currentGalleryIndex.value = (currentGalleryIndex.value - 1 + article.value.gallery.length) % article.value.gallery.length
      }
  
      // Fonctions de navigation
      const goBack = () => {
        // Ici vous pouvez utiliser le router Vue pour naviguer
        window.history.back()
      }
  
      const loadArticleById = (articleId) => {
        // Navigation vers l'article avec l'ID spécifié
        window.location.href = `/detail_actualite?id=${articleId}`
      }
  
      // Fonctions de partage
      const shareOnFacebook = () => {
        const url = encodeURIComponent(window.location.href)
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank')
      }
  
      const shareOnTwitter = () => {
        const url = encodeURIComponent(window.location.href)
        const text = encodeURIComponent(article.value.title)
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
      }
  
      const shareOnWhatsApp = () => {
        const url = encodeURIComponent(window.location.href)
        const text = encodeURIComponent(`${article.value.title} - ${url}`)
        window.open(`https://wa.me/?text=${text}`, '_blank')
      }
  
      const copyLink = async () => {
        try {
          await navigator.clipboard.writeText(window.location.href)
          alert('Lien copié dans le presse-papiers!')
        } catch (err) {
          console.error('Erreur lors de la copie:', err)
        }
      }
  
      // Initialisation
      onMounted(() => {
        // Charger l'article au montage du composant
        loadArticle()
        
        // Écouter les touches du clavier pour la galerie
        document.addEventListener('keydown', (e) => {
          if (lightboxOpen.value) {
            if (e.key === 'Escape') {
              closeLightbox()
            } else if (e.key === 'ArrowRight') {
              nextGalleryImage()
            } else if (e.key === 'ArrowLeft') {
              prevGalleryImage()
            }
          }
        })
      })
  
      return {
        lightboxOpen,
        currentGalleryIndex,
        article,
        relatedArticles,
        currentGalleryImage,
        loading,
        error,
        openLightbox,
        closeLightbox,
        nextGalleryImage,
        prevGalleryImage,
        goBack,
        loadArticleById,
        shareOnFacebook,
        shareOnTwitter,
        shareOnWhatsApp,
        copyLink
      }
    }
  }
  </script>
  
  <style>
  :root {
    --primary: #006400;
    --primary-dark: #004d00;
    --primary-light: #008000;
    --primary-lighter: #e6f0e6;
    --secondary: #f8b400;
    --secondary-dark: #d99b00;
    --secondary-light: #ffc933;
    --accent: #e67e22;
    --text-primary: #333333;
    --text-secondary: #666666;
    --text-light: #999999;
    --background: #ffffff;
    --background-alt: #f5f5f5;
    --border: #e0e0e0;
    --success: #28a745;
    --danger: #dc3545;
    --warning: #ffc107;
    --info: #17a2b8;
  }
  
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text-primary);
    line-height: 1.6;
    background-color: var(--background);
    font-size: 14px;
  }
  
  .container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
  }
  
  a {
    color: var(--primary);
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  a:hover {
    color: var(--primary-dark);
  }
  
  img {
    max-width: 100%;
    height: auto;
  }
  
  .img-responsive {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 4px;
    transition: transform 0.5s ease;
  }
  
  .btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    border-radius: 4px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
    text-decoration: none;
  }
  
  .btn-primary {
    background-color: var(--primary);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 100, 0, 0.1);
  }
  
  .btn-primary:hover {
    background-color: var(--primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 100, 0, 0.15);
  }
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
  }
  
  .btn-outline:hover {
    background-color: var(--primary);
    color: white;
    transform: translateY(-2px);
  }
  
  .btn-sm {
    padding: 0.3rem 0.7rem;
    font-size: 0.75rem;
    border-radius: 2px;
  }
  
  /* Page Banner */
  .page-banner {
    background: linear-gradient(rgba(0, 100, 0, 0.9), rgba(0, 100, 0, 0.9));
    color: white;
    padding: 4rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  
  .banner-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.1;
  }
  
  .page-title {
    position: relative;
    z-index: 1;
  }
  
  .page-title h1 {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  }
  
  .subtitle {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    opacity: 0.9;
  }
  
  .breadcrumbs {
    font-size: 0.85rem;
    opacity: 0.8;
  }
  
  .breadcrumbs a {
    color: white;
    opacity: 0.8;
    transition: opacity 0.3s ease;
  }
  
  .breadcrumbs a:hover {
    opacity: 1;
  }
  
  .separator {
    margin: 0 0.5rem;
    opacity: 0.6;
  }
  
  .current {
    opacity: 1;
    font-weight: 600;
  }
  
  .highlight {
    color: var(--secondary);
    font-weight: 800;
  }
  
  /* Motifs islamiques animés */
  .islamic-patterns {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 1;
  }
  
  .islamic-pattern {
    position: absolute;
    opacity: 0.15;
  }
  
  .islamic-pattern.star {
    width: 30px;
    height: 30px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-diagonal 25s infinite linear;
  }
  
  .islamic-pattern.crescent {
    width: 25px;
    height: 25px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-horizontal 30s infinite linear;
  }
  
  .islamic-pattern.geometric {
    width: 40px;
    height: 40px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0 L100 50 L50 100 L0 50 Z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-vertical 20s infinite linear;
  }
  
  @keyframes float-diagonal {
    0% {
      transform: translate(0, 0) rotate(0deg);
    }
    25% {
      transform: translate(100px, 100px) rotate(90deg);
    }
    50% {
      transform: translate(200px, 200px) rotate(180deg);
    }
    75% {
      transform: translate(100px, 300px) rotate(270deg);
    }
    100% {
      transform: translate(0, 400px) rotate(360deg);
    }
  }
  
  @keyframes float-horizontal {
    0% {
      transform: translate(-100px, 0) rotate(0deg);
    }
    50% {
      transform: translate(calc(100vw + 100px), 0) rotate(180deg);
    }
    100% {
      transform: translate(-100px, 0) rotate(360deg);
    }
  }
  
  @keyframes float-vertical {
    0% {
      transform: translate(0, -100px) rotate(0deg);
    }
    50% {
      transform: translate(0, calc(100vh + 100px)) rotate(180deg);
    }
    100% {
      transform: translate(0, -100px) rotate(360deg);
    }
  }
  
  /* Main Content */
  .main-content {
    padding: 4rem 0;
    background-color: var(--background);
  }
  
  /* Back Button */
  .back-button-section {
    margin-bottom: 2rem;
  }
  
  .btn-back {
    font-size: 0.9rem;
  }
  
  /* Article Detail */
  .article-detail {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 3rem;
  }
  
  .article-header {
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 1rem;
  }
  
  .article-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    line-height: 1.3;
  }
  
  .article-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
  }
  
  .meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .article-image {
    margin-bottom: 2rem;
  }
  
  .image-caption {
    text-align: center;
    font-style: italic;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 0.5rem;
  }
  
  /* Event Details */
  .event-details {
    background-color: var(--primary-lighter);
    padding: 1.5rem;
    border-radius: 6px;
    margin-bottom: 2rem;
    border-left: 4px solid var(--primary);
  }
  
  .event-details h3 {
    color: var(--primary);
    margin-bottom: 1rem;
    font-size: 1.2rem;
  }
  
  .details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 0.8rem;
  }
  
  .detail-item {
    font-size: 0.9rem;
  }
  
  .detail-item strong {
    color: var(--primary);
  }
  
  /* Article Content */
  .article-content {
    margin-bottom: 2rem;
  }
  
  .content-section {
    margin-bottom: 2rem;
  }
  
  .content-section h3 {
    color: var(--primary);
    font-size: 1.3rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--border);
    padding-bottom: 0.5rem;
  }
  
  .content-section p {
    margin-bottom: 1rem;
    text-align: justify;
    line-height: 1.8;
  }
  
  /* Gallery */
  .article-gallery {
    margin-bottom: 2rem;
  }
  
  .article-gallery h3 {
    color: var(--primary);
    margin-bottom: 1rem;
    font-size: 1.2rem;
  }
  
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
  }
  
  .gallery-item {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease;
  }
  
  .gallery-item:hover {
    transform: scale(1.05);
  }
  
  .gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
  }
  
  .gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  
  .gallery-item:hover .gallery-overlay {
    opacity: 1;
  }
  
  .gallery-icon {
    font-size: 2rem;
    color: white;
  }
  
  /* Conclusion */
  .article-conclusion {
    margin-bottom: 2rem;
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, var(--primary-lighter), var(--background-alt));
    border-radius: 8px;
    border: 2px solid var(--primary);
    position: relative;
  }
  
  .article-conclusion::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, var(--primary), var(--secondary));
    border-radius: 8px;
    z-index: -1;
  }
  
  .conclusion-content {
    background: white;
    padding: 1rem;
    border-radius: 6px;
    position: relative;
    z-index: 1;
  }
  
  .conclusion-text {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  }
  
  /* Tags */
  .article-tags {
    margin-bottom: 2rem;
  }
  
  .article-tags h4 {
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  
  .tag {
    background-color: var(--primary-lighter);
    color: var(--primary);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
  }
  
  /* Share */
  .article-share {
    border-top: 1px solid var(--border);
    padding-top: 1.5rem;
  }
  
  .article-share h4 {
    color: var(--primary);
    margin-bottom: 1rem;
  }
  
  .share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
  }
  
  .share-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  .share-btn.facebook {
    background-color: #1877f2;
    color: white;
  }
  
  .share-btn.twitter {
    background-color: #1da1f2;
    color: white;
  }
  
  .share-btn.whatsapp {
    background-color: #25d366;
    color: white;
  }
  
  .share-btn.copy {
    background-color: var(--text-secondary);
    color: white;
  }
  
  .share-btn:hover {
    transform: translateY(-2px);
    opacity: 0.9;
  }
  
  /* Related Articles */
  .related-articles {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 0.5rem;
  }
  
  .section-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
  }
  
  .section-icon {
    color: var(--primary);
    font-size: 1.2rem;
  }
  
  .related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
  }
  
  .related-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 6px;
    background-color: var(--background-alt);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .related-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .related-image {
    flex-shrink: 0;
    width: 100px;
    height: 80px;
    border-radius: 4px;
    overflow: hidden;
  }
  
  .related-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .related-content h4 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
  }
  
  .related-meta {
    display: flex;
    align-items: center;
    color: var(--text-light);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
  }
  
  .related-meta .icon-calendar {
    margin-right: 0.3rem;
  }
  
  .related-content p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.4;
  }
  
  /* Lightbox */
  .lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }
  
  .lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
  }
  
  .lightbox-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
  }
  
  .lightbox-img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
  }
  
  .lightbox-caption {
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 1rem;
    margin-top: 1rem;
    border-radius: 4px;
    text-align: center;
  }
  
  .lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  
  .lightbox-nav.prev {
    left: 20px;
  }
  
  .lightbox-nav.next {
    right: 20px;
  }
  
  /* Loading and Error States */
  .loading-state, .error-state {
    text-align: center;
    padding: 3rem 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid var(--primary-lighter);
    border-top: 4px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .error-state p {
    color: var(--danger);
    margin-bottom: 1rem;
  }
  
  /* Animations */
  .animate-slide-up {
    opacity: 0;
    transform: translateY(20px);
    animation: slideUp 0.6s ease forwards;
  }
  
  @keyframes slideUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .page-title h1 {
      font-size: 2rem;
    }
  
    .article-title {
      font-size: 1.5rem;
    }
  
    .article-meta {
      flex-direction: column;
      gap: 0.8rem;
    }
  
    .details-grid {
      grid-template-columns: 1fr;
    }
  
    .share-buttons {
      justify-content: center;
    }
  
    .related-item {
      flex-direction: column;
    }
  
    .related-image {
      width: 100%;
      height: 150px;
    }
  
    .gallery-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 576px) {
    .article-detail {
      padding: 1rem;
    }
  
    .gallery-grid {
      grid-template-columns: 1fr;
    }
  
    .related-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>
  