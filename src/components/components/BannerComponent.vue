<template>
  <transition name="popup-fade">
    <div v-if="showPopup" class="popup-overlay" @click="closePopup">
      <div class="popup-container" @click.stop>
        <button class="popup-close" @click="closePopup" aria-label="Fermer">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
        <div class="popup-content">
          <a href="https://agenceemploijeunes.ci/" target="_blank">
          <img 
            src="../../assets/pub1.jpg" 
            alt="Publicité AEEMCI" 
            class="popup-image"
          />
         </a>
      </div>
    </div>
    </div>
  </transition>

  <section class="hero">
    <div class="hero-background" :style="{ backgroundImage: `url(${currentSlide.backgroundImage})` }"></div>
    <div class="hero-overlay"></div>

    <div class="container">
      <transition name="fade" mode="out-in">
        <div class="hero-content" :key="currentSlideIndex">
          <div class="hero-left">
            <h1 class="hero-title" v-html="currentSlide.title"></h1>
            <p class="hero-subtitle" v-html="currentSlide.subtitle"></p>
            <div class="hero-actions" style="display: grid; max-width: 400px">
              <a href="/carte_membre"><button class="btn btn-large btn-primary">{{ currentSlide.primaryButton }}</button></a>
              <a href="/cv"><button class="btn btn-large btn-primary-white" style="color: black">{{ currentSlide.secondaryButton }}</button></a>
            </div>
          </div>
          
          <div class="hero-right">
            <div class="hero-stats">
              <div v-for="(stat, index) in currentSlide.stats" :key="index" 
                   :class="['stat', { 'stat-anniversary': stat.isSpecial }]">
                <span class="stat-number">{{ stat.number }}</span>
                <span class="stat-label">{{ stat.label }}</span>
              </div>
            </div>
            <h1 class="hero-title2" style="font-size: 26px; width: 100%; margin-top: 40px; color: white; text-align: center">
              {{ currentSlide.bottomTitle }}
            </h1>
          </div>
        </div>
      </transition>
    </div>
  </section>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'

export default {
  name: 'BannerCarousel',
  setup() {
    const currentSlideIndex = ref(0)
    const showPopup = ref(false)
    let intervalId = null

    const slides = ref([
      {
        backgroundImage: "https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758303617/aeemci_photo_1758303614009_ban3.jpg.jpg",
        title: "PLATEFORME DIGITALE<br/> DE L'AEEMCI",
        subtitle: `"L'AEEMCI est une structure islamique en Côte d'Ivoire, qui contribue à la formation religieuse et managériale des élèves et étudiants musulmans, pour l'émergence d'une élite musulmane consciente des valeurs républicaines et de sa responsabilité communautaire." <i style="font-size: 16px; color: orange">Charte de l'AEEMCI</i>`,
        primaryButton: "Demander une carte de membre",
        secondaryButton: "Déposer mon CV pour des opportunités",
        bottomTitle: "AEEMCI, pour une identité islamique !",
        stats: [
          { number: "50", label: "Ans d'existence", isSpecial: true },
          { number: "30,000+", label: "Membres actifs" },
          { number: "1000+", label: "Sections" },
          { number: "500+", label: "Sous-comités" },
          { number: "34", label: "Secrétariats Régionaux" },
          { number: "100+", label: "Activités sociales" }
        ]
      },
    ])

    const currentSlide = ref(slides.value[0])

    // Popup functions
    const closePopup = () => {
      showPopup.value = false
      localStorage.setItem('aeemci_popup_shown', 'true')
    }

    const checkFirstVisit = () => {
      const hasSeenPopup = localStorage.getItem('aeemci_popup_shown')
      if (!hasSeenPopup) {
        // Afficher la popup après un court délai pour une meilleure UX
        setTimeout(() => {
          showPopup.value = true
        }, 1000)
      }
    }

    const nextSlide = () => {
      currentSlideIndex.value = (currentSlideIndex.value + 1) % slides.value.length
      currentSlide.value = slides.value[currentSlideIndex.value]
    }

    const goToSlide = (index) => {
      currentSlideIndex.value = index
      currentSlide.value = slides.value[index]
      // Reset interval when manually changing slide
      if (intervalId) {
        clearInterval(intervalId)
        startAutoSlide()
      }
    }

    const startAutoSlide = () => {
      intervalId = setInterval(nextSlide, 15000) // Change slide every 15 seconds
    }

    onMounted(() => {
      startAutoSlide()
      checkFirstVisit()
    })

    onUnmounted(() => {
      if (intervalId) {
        clearInterval(intervalId)
      }
    })

    return {
      slides,
      currentSlide,
      currentSlideIndex,
      goToSlide,
      showPopup,
      closePopup
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:ital,wght@0,200;0,300;0,400;0,700;0,800;0,900;1,300;1,400&display=swap');

/* Popup Styles */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.75);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  backdrop-filter: blur(4px);
}

.popup-container {
  position: relative;
  max-width: 90%;
  max-height: 90vh;
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: popupSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.popup-content {
  position: relative;
  width: 100%;
  height: 100%;
}

.popup-image {
  display: block;
  width: 100%;
  height: auto;
  max-height: 90vh;
  object-fit: contain;
}

.popup-close {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 40px;
  height: 40px;
  background: #ff2525f2;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.popup-close:hover {
  background: white;
  transform: rotate(90deg) scale(1.1);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.popup-close svg {
  color: white;
}

/* Popup Animations */
.popup-fade-enter-active,
.popup-fade-leave-active {
  transition: opacity 0.3s ease;
}

.popup-fade-enter-from,
.popup-fade-leave-to {
  opacity: 0;
}

.popup-fade-enter-active .popup-container {
  animation: popupSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.popup-fade-leave-active .popup-container {
  animation: popupSlideOut 0.3s ease;
}

@keyframes popupSlideIn {
  from {
    opacity: 0;
    transform: scale(0.8) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes popupSlideOut {
  from {
    opacity: 1;
    transform: scale(1);
  }
  to {
    opacity: 0;
    transform: scale(0.9);
  }
}

/* Responsive Popup */
@media (max-width: 768px) {
  .popup-container {
    max-width: 95%;
    max-height: 85vh;
  }

  .popup-close {
    width: 36px;
    height: 36px;
    top: 12px;
    right: 12px;
  }
}

.container {
  max-width: 1300px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Buttons */
.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
}

.btn-primary {
  background: linear-gradient(135deg, #f5841f, #ff9c00);
  color: white;
  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(255, 215, 0, 0.4);
}

.btn-outline-white {
  background: transparent;
  border: 2px solid white;
  color: white;
}

.btn-outline-white:hover {
  background: white;
  color: #018404;
}

.btn-large {
  padding: 1rem 2rem;
  font-size: 1.1rem;
}

/* Hero Section */
.hero {
  position: relative;
  color: white;
  padding: 5rem 0;
  overflow: hidden;
}

.hero-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  z-index: -2;
  transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.hero-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.55);
  z-index: -1;
}

/* Content */
.hero-content {
  display: flex;
  align-items: center;
  gap: 4rem;
  max-width: 1200px;
  margin: 0 auto;
  animation: fadeIn 1s ease-out;
  transition: opacity 0.5s ease-in-out;
}

.hero-left {
  flex: 1;
}

.hero-title {
  font-size: 4rem;
  font-weight: 00;
  font-family: "Zain", sans-serif;
  font-weight: 900;
  font-style: normal;
  margin-bottom: 1.5rem;
  background: linear-gradient(135deg, #fd9806, #fd9806);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: slideUp 1.2s ease;
}

.hero-title2 {
  font-size: 4rem;
  font-weight: 00;
  font-family: "Zain", sans-serif;
  font-weight: 900;
  font-style: normal;
  margin-bottom: 1.5rem;
  background: linear-gradient(135deg, white, white);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: slideUp 1.2s ease;
}

.hero-subtitle {
  font-size: 1.3rem;
  margin-bottom: 2rem;
  opacity: 0.9;
  line-height: 1.6;
  animation: fadeIn 1.4s ease;
}

.hero-actions {
  display: flex;
  gap: 1rem;
}

/* Stats */
.hero-right {
  flex: 1;
  justify-content: center;
  align-items: center;
}

.hero-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 1.5rem;
}

.stat {
  text-align: center;
  padding: 1.5rem;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(6px);
  transition: transform 0.3s ease;
}

.stat:hover {
  transform: translateY(-5px);
}

.stat-number {
  display: block;
  font-size: 2.2rem;
  font-weight: 700;
  color: #c9e9d5;
}

.stat-label {
  font-size: 1rem;
  opacity: 0.85;
  color: white;
}

/* Style spécifique pour le 50 ans */
.stat-anniversary {
  background: linear-gradient(135deg, #ffd700, #ff9c00);
  color: #1a1a1a;
}

.stat-anniversary .stat-number {
  color: #1a1a1a;
}

.stat-anniversary .stat-label {
  color: #1a1a1a;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
  .hero-content {
    flex-direction: column;
    text-align: center;
    gap: 2rem;
  }

  .hero-title {
    font-size: 2.2rem;
  }

  .hero-actions {
    flex-direction: column;
  }

  .hero-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    max-width: 400px;
    margin: 0 auto;
  }

  .stat:nth-child(4) {
    grid-column: 1 / 2;
  }

  .stat:nth-child(5) {
    grid-column: 2 / 2;
  }

  .stat:nth-child(6) {
    grid-column: 3 / 4;
  }

  .stat {
    padding: 1rem;
  }

  .stat-number {
    font-size: 1.8rem;
  }
}

@media (max-width: 480px) {
  .hero {
    padding: 3rem 0;
  }

  .hero-title {
    font-size: 2.5rem;
  }

  .hero-subtitle {
    font-size: 1.1rem;
  }

  .btn-large {
    padding: 0.8rem 1.5rem;
    font-size: 1rem;
  }

  .hero-stats {
    max-width: 320px;
    gap: 0.8rem;
  }

  .stat {
    padding: 0.8rem;
  }

  .stat-number {
    font-size: 1.5rem;
  }

  .stat-label {
    font-size: 0.9rem;
  }
}

/* Added fade transition for content */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.fade-enter-to, .fade-leave-from {
  opacity: 1;
}
</style>