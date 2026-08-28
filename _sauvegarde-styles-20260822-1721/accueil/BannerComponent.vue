<template>
    <!--  Extracted rotating banner section -->
    <section class="hero-banner">
      <div class="banner-container">
        <div class="banner-slide" v-for="(banner, index) in banners" :key="index" 
             :class="{ active: currentBanner === index }">
          <div class="banner-content">
            <div class="banner-text">
              <h1 class="banner-title">{{ banner.title }}</h1>
              <p class="banner-description">{{ banner.description }}</p>
              <button class="banner-cta">{{ banner.cta }}</button>
            </div>
            <div class="banner-image">
              <img :src="banner.image" :alt="banner.title" />
            </div>
          </div>
        </div>
        
        <div class="banner-indicators">
          <button v-for="(banner, index) in banners" :key="index"
                  :class="{ active: currentBanner === index }"
                  @click="currentBanner = index">
          </button>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'BannerComponent',
    data() {
      return {
        currentBanner: 0,
        banners: [
          {
            title: "AEEMCI - Éducation Islamique d'Excellence",
            description: "Association des Élèves et Étudiants Musulmans de Côte d'Ivoire. Formant les leaders musulmans de demain à travers l'éducation et les valeurs islamiques.",
            cta: "Découvrir l'AEEMCI",
            image: "/placeholder.svg?height=400&width=600"
          },
          {
            title: "Formations Islamiques Certifiées",
            description: "Programmes complets d'études islamiques, du niveau débutant au niveau avancé. Cours de Coran, Hadith, Fiqh et langue arabe.",
            cta: "Nos Formations",
            image: "/placeholder.svg?height=400&width=600"
          },
          {
            title: "Bibliothèque Numérique Islamique",
            description: "Accédez à plus de 10,000 ouvrages islamiques, recherches académiques et ressources pédagogiques en ligne.",
            cta: "Explorer la Bibliothèque",
            image: "/placeholder.svg?height=400&width=600"
          },
          {
            title: "Communauté Étudiante Active",
            description: "Rejoignez une communauté de plus de 5,000 étudiants musulmans à travers 25 universités en Côte d'Ivoire.",
            cta: "Rejoindre la Communauté",
            image: "/placeholder.svg?height=400&width=600"
          }
        ]
      }
    },
    mounted() {
      this.startBannerRotation();
    },
    methods: {
      startBannerRotation() {
        setInterval(() => {
          this.currentBanner = (this.currentBanner + 1) % this.banners.length;
        }, 4000);
      }
    }
  }
  </script>
  
  <style scoped>
  .hero-banner {
    position: relative;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #138d7536, #cce0dc36);
  }
  
  .banner-container {
    position: relative;
    height: 100%;
  }
  
  .banner-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
  }
  
  .banner-slide.active {
    opacity: 1;
  }
  
  .banner-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 20px;
    height: 100%;
    gap: 40px;
  }
  
  .banner-text {
    flex: 1;
    color: white;
  }
  
  .banner-title {
    font-size: 48px;
    font-weight: bold;
    margin-bottom: 20px;
    line-height: 1.2;
  }
  
  .banner-description {
    font-size: 18px;
    margin-bottom: 30px;
    line-height: 1.6;
    opacity: 0.9;
  }
  
  .banner-cta {
    background: #e67e22;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .banner-cta:hover {
    background: #d35400;
  }
  
  .banner-image {
    flex: 1;
    text-align: center;
  }
  
  .banner-image img {
    max-width: 100%;
    height: 350px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  }
  
  .banner-indicators {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
  }
  
  .banner-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    background: transparent;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .banner-indicators button.active {
    background: white;
  }
  
  @media (max-width: 768px) {
    .banner-content {
      flex-direction: column;
      text-align: center;
      padding: 40px 20px;
    }
    
    .banner-title {
      font-size: 32px;
    }
    
    .banner-image img {
      height: 200px;
    }
  }
  </style>
  