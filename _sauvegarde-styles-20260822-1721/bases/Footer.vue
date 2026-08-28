<template>
  <div class="app">

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-column">
            <div class="footer-logo">
              <img src="https://i.ibb.co/Jd3tVbN/islamic-logo.png" alt="Logo AEEMCI" class="logo-img" />
              <span class="logo-text">AEEMCI</span>
            </div>
            <p class="footer-description">
              Association des Élèves et Étudiants Musulmans de Côte d'Ivoire, œuvrant pour l'éducation et l'épanouissement de la jeunesse musulmane depuis plus de 35 ans.
            </p>
          </div>
          
          <div class="footer-column">
            <h4 class="footer-title">Liens rapides</h4>
            <ul class="footer-links">
              <li><a href="#">Accueil</a></li>
              <li><a href="#">À propos</a></li>
              <li><a href="#">Activités</a></li>
              <li><a href="#">Médiathèque</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>
          
          <div class="footer-column">
            <h4 class="footer-title">Contactez-nous</h4>
            <ul class="contact-info">
              <li>
                <span class="icon-location">📍</span>
                <span>Siège: Cocody Riviera 2, Mosquée An Nour, Abidjan</span>
              </li>
              <li>
                <span class="icon-phone">📞</span>
                <span>+225 07 07 07 07 07</span>
              </li>
              <li>
                <span class="icon-mail">✉️</span>
                <span>contact@aeemci.org</span>
              </li>
            </ul>
          </div>
          
          <div class="footer-column">
            <h4 class="footer-title">Horaires d'ouverture</h4>
            <ul class="opening-hours">
              <li>
                <span class="day">Lundi - Vendredi:</span>
                <span class="hours">09h00 - 17h00</span>
              </li>
              <li>
                <span class="day">Samedi:</span>
                <span class="hours">09h00 - 13h00</span>
              </li>
              <li>
                <span class="day">Dimanche:</span>
                <span class="hours">Fermé</span>
              </li>
            </ul>
          </div>
        </div>
        
        <div class="footer-bottom">
          <div class="copyright">
            &copy; {{ currentYear }} AEEMCI. Tous droits réservés.
          </div>
          <div class="footer-nav">
            <a href="#">Politique de confidentialité</a>
            <a href="#">Conditions d'utilisation</a>
            <a href="#">Plan du site</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

// État pour les dropdowns
const dropdowns = ref({
  aeemci: false,
  activites: false,
  mediatheque: false
});

// Année actuelle pour le copyright
const currentYear = computed(() => {
  return new Date().getFullYear();
});
// Animation des compteurs
const animateCounters = () => {
  const counters = document.querySelectorAll('.animate-count .stat-number');
  
  counters.forEach(counter => {
    const target = parseInt(counter.getAttribute('data-count'));
    const duration = 2000; // ms
    const step = target / (duration / 16); // 60fps
    let current = 0;
    
    const updateCounter = () => {
      current += step;
      if (current < target) {
        counter.textContent = Math.ceil(current);
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = target;
        if (target >= 1000) {
          counter.textContent = (target / 1000) + 'k+';
        } else {
          counter.textContent = target + '+';
        }
      }
    };
    
    updateCounter();
  });
};

// Témoignages
const testimonials = [
  {
    text: "L'AEEMCI a changé ma vie. J'ai pu développer mes compétences en leadership tout en renforçant ma foi.",
    name: "Aminata Koné",
    role: "Ancienne membre, Promotion 2018",
    avatar: "https://i.ibb.co/YQnkf7L/islamic-conference.jpg"
  },
  {
    text: "Grâce aux formations de l'AEEMCI, j'ai pu concilier mes études universitaires avec une solide éducation islamique.",
    name: "Ibrahim Touré",
    role: "Étudiant en médecine",
    avatar: "https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg"
  },
  {
    text: "Les activités sociales de l'AEEMCI m'ont permis de contribuer au bien-être de ma communauté tout en m'épanouissant.",
    name: "Fatima Diallo",
    role: "Bénévole active",
    avatar: "https://i.ibb.co/Qj9JNHW/quran-recitation.jpg"
  }
];

const currentTestimonial = ref(0);
let testimonialInterval;

const updateTestimonialSlider = () => {
  const slider = document.querySelector('.testimonial-slider');
  if (slider) {
    slider.style.transform = `translateX(-${currentTestimonial.value * 100}%)`;
  }
};

const startTestimonialSlider = () => {
  testimonialInterval = setInterval(() => {
    currentTestimonial.value = (currentTestimonial.value + 1) % testimonials.length;
    updateTestimonialSlider();
  }, 5000);
};

// Initialisation des animations au chargement de la page
onMounted(() => {
  // Fermer les dropdowns quand on clique en dehors
  document.addEventListener('click', (event) => {
    const isDropdownButton = event.target.closest('.dropdown');
    if (!isDropdownButton) {
      Object.keys(dropdowns.value).forEach(key => {
        dropdowns.value[key] = false;
      });
    }
  });
  
  // Observer pour déclencher les animations au scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-visible');
        
        // Si c'est la section des statistiques, démarrer l'animation des compteurs
        if (entry.target.classList.contains('identity-stats') || entry.target.classList.contains('bibliotheque-stats')) {
          animateCounters();
        }
      }
    });
  }, { threshold: 0.1 });
  
  // Observer les éléments avec animation
  document.querySelectorAll('.animate-slide-up, .animate-slide-right, .animate-fade-in, .identity-stats, .bibliotheque-stats').forEach(el => {
    observer.observe(el);
  });
  
  // Démarrer le slider de témoignages
  startTestimonialSlider();
  
  // Initialiser le slider de témoignages
  updateTestimonialSlider();
});

onBeforeUnmount(() => {
  // Nettoyer les intervalles
  if (testimonialInterval) {
    clearInterval(testimonialInterval);
  }
});
</script>
<script>
    // Pour respecter la règle multi-word
    export default {
      name: 'FooTer'
    }
    </script>

<style>
:root {
  --primary: #006400;
  --primary-dark: #004d00;
  --primary-light: #008000;
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
  line-height: 1.4;
  background-color: var(--background);
  font-size: 13px;
}

.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 0.8rem;
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
  padding: 0.5rem 1rem;
  border-radius: 3px;
  font-weight: 600;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  font-size: 0.8rem;
  letter-spacing: 0.3px;
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

.form-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid var(--border);
  border-radius: 3px;
  font-size: 0.8rem;
  margin-bottom: 0.6rem;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 2px rgba(0, 100, 0, 0.1);
}

/* Header Styles - Menu à droite */
.header {
  background-color: white;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.6rem 0;
}

.logo {
  display: flex;
  align-items: center;
}

.logo-img {
  width: 35px;
  height: 35px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.logo-text {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--primary);
  margin-left: 0.3rem;
}

.main-nav {
  flex-grow: 1;
  display: flex;
  justify-content: flex-end;
}

.nav-list {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  align-items: center;
}

.nav-item {
  position: relative;
  margin-left: 1.2rem;
}

.nav-link {
  color: var(--text-primary);
  font-weight: 500;
  padding: 0.4rem 0;
  display: flex;
  align-items: center;
  font-size: 0.85rem;
  transition: all 0.2s ease;
}

.nav-link.active {
  color: var(--primary);
  font-weight: 600;
}

.nav-link:hover {
  color: var(--primary);
}

.dropdown-arrow {
  font-size: 0.5rem;
  margin-left: 0.2rem;
  opacity: 0.7;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background-color: white;
  min-width: 160px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
  border-radius: 3px;
  padding: 0.4rem 0;
  z-index: 100;
  list-style: none;
  transform-origin: top center;
  animation: dropdown-anim 0.2s ease;
  border: 1px solid var(--border);
}

@keyframes dropdown-anim {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-menu a {
  display: block;
  padding: 0.4rem 0.8rem;
  color: var(--text-primary);
  font-size: 0.8rem;
  transition: all 0.2s ease;
}

.dropdown-menu a:hover {
  background-color: var(--background-alt);
  color: var(--primary);
  padding-left: 1rem;
}

.nav-buttons {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.btn-donate, .btn-espace {
  padding: 0.3rem 0.7rem;
  border-radius: 2px;
  font-size: 0.75rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-donate {
  background-color: var(--secondary);
  color: var(--text-primary);
}

.btn-donate:hover {
  background-color: var(--secondary-dark);
  transform: translateY(-2px);
}

.btn-espace {
  background-color: var(--accent);
  color: white;
}

.btn-espace:hover {
  background-color: #d35400;
  transform: translateY(-2px);
}

.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
}

/* Hero Banner avec motifs islamiques animés */
.hero-banner {
  background: linear-gradient(rgba(0, 100, 0, 0.8), rgba(0, 100, 0, 0.8)), url('https://i.ibb.co/Lk5M0bL/islamic-students.jpg');
  background-size: cover;
  background-position: center;
  color: white;
  padding: 3rem 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.hero-banner::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 100%);
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  z-index: 1;
}

.hero-content h1 {
  font-size: 1.8rem;
  margin-bottom: 0.8rem;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-content p {
  font-size: 1rem;
  margin-bottom: 1.5rem;
  opacity: 0.95;
}

.hero-buttons {
  display: flex;
  justify-content: center;
  gap: 0.8rem;
}

.hero-buttons .btn-outline {
  border-color: white;
  color: white;
}

.hero-buttons .btn-outline:hover {
  background-color: white;
  color: var(--primary);
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
  padding: 2rem 0;
  background-color: var(--background-alt);
}

.content-wrapper {
  display: flex !important;
  flex-wrap: wrap !important;
  gap: 1.2rem;
}

.main-column {
  flex: 0 0 calc(66.666% - 0.6rem) !important; /* 2/3 moins la moitié de l'espace de gap */
  width: calc(66.666% - 0.6rem) !important;
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.2rem;
  border-bottom: 2px solid var(--primary);
  padding-bottom: 0.4rem;
}

.section-header h2 {
  font-size: 1.3rem;
  font-weight: 700;
}

.highlight {
  color: var(--primary);
  font-weight: 800;
}

.section-icon {
  color: var(--primary);
  font-size: 1rem;
}

/* News Section */
.actualites {
  margin-bottom: 1.8rem;
}

.news-item {
  margin-bottom: 1.2rem;
}

.news-item.featured {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
  background-color: var(--background-alt);
  border-radius: 6px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.news-item.featured:hover {
  transform: translateY(-3px);
}

.news-meta {
  display: flex;
  align-items: center;
  color: var(--text-light);
  font-size: 0.75rem;
  margin-bottom: 0.6rem;
}

.icon-calendar {
  margin-right: 0.3rem;
}

.event-details {
  background-color: rgba(0, 100, 0, 0.1);
  padding: 0.6rem;
  border-radius: 4px;
  margin: 0.8rem 0;
}

.detail-item {
  margin-bottom: 0.4rem;
  font-size: 0.8rem;
}

/* News Carousel */
.news-carousel {
  position: relative;
  margin-bottom: 1.2rem;
}

.carousel-container {
  display: flex;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  scrollbar-width: none;
  -ms-overflow-style: none;
  gap: 1rem;
  padding: 0.5rem 0;
}

.carousel-container::-webkit-scrollbar {
  display: none;
}

.carousel-slide {
  flex: 0 0 calc(33.333% - 0.67rem);
  scroll-snap-align: start;
}

.carousel-controls {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 0.8rem;
}

.carousel-control {
  background-color: var(--primary);
  color: white;
  border: none;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 0.8rem;
  transition: all 0.3s ease;
}

.carousel-control:hover {
  background-color: var(--primary-dark);
  transform: scale(1.1);
}

.news-card {
  border: none;
  border-radius: 6px;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  background-color: white;
  height: 100%;
}

.news-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.news-card-img img {
  width: 100%;
  height: 120px;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.news-card:hover .news-card-img img {
  transform: scale(1.05);
}

.news-card-content {
  padding: 0.8rem;
}

.news-card-content h4 {
  margin-bottom: 0.4rem;
  font-size: 0.9rem;
  color: var(--primary);
}

.read-more {
  display: flex;
  align-items: center;
  margin-top: 0.6rem;
  font-weight: 600;
  font-size: 0.75rem;
  color: var(--primary);
}

.read-more:hover {
  color: var(--primary-dark);
  text-decoration: underline;
}

.view-all-news, .view-all {
  text-align: center;
  margin-top: 1.2rem;
}

/* Programme Timeline */
.programmes {
  margin-bottom: 1.8rem;
}

.programme-timeline {
  position: relative;
  padding-left: 1.2rem;
}

.programme-timeline::before {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  left: 70px;
  width: 2px;
  background-color: var(--primary);
  border-radius: 2px;
}

.timeline-item {
  display: flex;
  margin-bottom: 1.2rem;
  position: relative;
  opacity: 0;
  transform: translateX(20px);
}

.timeline-date {
  width: 70px;
  text-align: center;
  background-color: var(--primary);
  color: white;
  padding: 0.4rem;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0, 100, 0, 0.2);
  z-index: 2;
}

.timeline-date .month {
  font-size: 0.7rem;
  text-transform: uppercase;
  font-weight: 600;
}

.timeline-date .day {
  font-size: 1rem;
  font-weight: 700;
  margin: 0.1rem 0;
}

.timeline-date .year {
  font-size: 0.7rem;
}

.timeline-content {
  flex: 1;
  background-color: white;
  padding: 0.8rem;
  border-radius: 4px;
  margin-left: 1.2rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.timeline-content:hover {
  transform: translateY(-3px);
}

.timeline-content h4 {
  margin-bottom: 0.4rem;
  font-size: 0.9rem;
  color: var(--primary);
}

/* Formations Section */
.formations {
  margin-bottom: 1.8rem;
}

.formations-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.formation-card {
  background-color: white;
  border-radius: 6px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  text-align: center;
}

.formation-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.formation-icon {
  font-size: 1.5rem;
  margin-bottom: 0.6rem;
}

.formation-card h4 {
  font-size: 0.9rem;
  margin-bottom: 0.4rem;
  color: var(--primary);
}

.formation-card p {
  font-size: 0.8rem;
  margin-bottom: 0.6rem;
  color: var(--text-secondary);
}

.formation-details {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.6rem;
  font-size: 0.75rem;
  color: var(--text-light);
}

/* Bibliothèque Section */
.bibliotheque {
  margin-bottom: 1.8rem;
}

.bibliotheque-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
}

.bibliotheque-info h3 {
  font-size: 1.1rem;
  margin-bottom: 0.6rem;
  color: var(--primary);
}

.bibliotheque-info p {
  font-size: 0.85rem;
  margin-bottom: 1rem;
}

.bibliotheque-stats {
  display: flex;
  justify-content: space-between;
  margin: 1.2rem 0;
}

.bibliotheque-hours {
  margin: 1rem 0;
}

.bibliotheque-hours h4 {
  font-size: 0.9rem;
  margin-bottom: 0.4rem;
  color: var(--primary);
}

.bibliotheque-hours ul {
  list-style: none;
}

.bibliotheque-hours li {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
  margin-bottom: 0.3rem;
}

.book-carousel {
  display: flex;
  gap: 0.8rem;
  margin-top: 0.8rem;
}

.book-item {
  flex: 1;
  background-color: var(--background-alt);
  border-radius: 6px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.book-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.book-cover img {
  width: 100%;
  height: 120px;
  object-fit: cover;
}

.book-info {
  padding: 0.6rem;
}

.book-info h5 {
  font-size: 0.85rem;
  margin-bottom: 0.3rem;
  color: var(--primary);
}

.book-info p {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

/* Identity Section */
.identity {
  margin-bottom: 1.2rem;
}

.identity-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
  align-items: center;
}

.identity-stats {
  display: flex;
  justify-content: space-between;
  margin: 1.2rem 0;
}

.stat-item {
  text-align: center;
  padding: 0.8rem;
  background-color: var(--background-alt);
  border-radius: 4px;
  transition: transform 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.stat-number {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 0.2rem;
}

.stat-label {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

/* Sidebar */
.sidebar {
  flex: 0 0 calc(33.333% - 0.6rem) !important; /* 1/3 moins la moitié de l'espace de gap */
  width: calc(33.333% - 0.6rem) !important;
}

.sidebar-widget {
  background-color: white;
  border-radius: 6px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
  opacity: 0;
  transform: translateY(15px);
}

.sidebar-widget:hover {
  transform: translateY(-3px);
}

.widget-header {
  background-color: var(--primary);
  color: white;
  padding: 0.6rem;
}

.widget-header h3 {
  margin: 0;
  font-size: 1rem;
}

.widget-content {
  padding: 0.8rem;
}

/* Opportunities */
.opportunity-item {
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--border);
}

.opportunity-item:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}

.opportunity-item h4 {
  margin-bottom: 0.4rem;
  color: var(--primary);
  font-size: 0.9rem;
}

/* Prayer Times */
.prayer-date {
  text-align: center;
  font-weight: 600;
  margin-bottom: 0.8rem;
  font-size: 0.8rem;
}

.prayer-list {
  margin-bottom: 0.8rem;
}

.prayer-item {
  display: flex;
  justify-content: space-between;
  padding: 0.4rem 0;
  border-bottom: 1px dashed var(--border);
}

.prayer-item:last-child {
  border-bottom: none;
}

.prayer-name {
  font-weight: 600;
  font-size: 0.8rem;
}

.prayer-time {
  font-size: 0.8rem;
}

.prayer-location {
  text-align: center;
  font-size: 0.75rem;
  color: var(--text-light);
  margin-top: 0.6rem;
}

.icon-location {
  margin-right: 0.25rem;
}

/* Mini Calendar */
.mini-calendar {
  font-size: 0.8rem;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.6rem;
}

.calendar-nav {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 0.8rem;
  color: var(--primary);
}

.current-month {
  font-weight: 600;
}

.calendar-days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  text-align: center;
  margin-bottom: 0.4rem;
}

.day-name {
  font-weight: 600;
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.calendar-dates {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.2rem;
}

.calendar-date {
  text-align: center;
  padding: 0.3rem 0;
  font-size: 0.75rem;
  border-radius: 3px;
  cursor: pointer;
}

.calendar-date.other-month {
  color: var(--text-light);
}

.calendar-date.today {
  background-color: var(--primary);
  color: white;
  font-weight: 600;
}

.calendar-date.has-event {
  background-color: var(--secondary-light);
  font-weight: 600;
  position: relative;
}

.calendar-date.has-event::after {
  content: '';
  position: absolute;
  bottom: 2px;
  left: 50%;
  transform: translateX(-50%);
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background-color: var(--primary);
}

.calendar-event {
  margin-top: 0.8rem;
  padding: 0.6rem;
  background-color: var(--background-alt);
  border-radius: 4px;
}

.calendar-event h5 {
  font-size: 0.85rem;
  margin-bottom: 0.3rem;
  color: var(--primary);
}

.calendar-event p {
  font-size: 0.75rem;
  margin-bottom: 0.4rem;
}

.event-link {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--primary);
}

/* Hadith Widget */
.hadith-container {
  text-align: center;
}

.hadith-text {
  font-style: italic;
  margin-bottom: 0.6rem;
  font-size: 0.85rem;
}

.hadith-source {
  font-size: 0.75rem;
  color: var(--text-secondary);
  margin-bottom: 0.8rem;
}

.hadith-btn {
  margin: 0 auto;
  display: block;
}

/* Social Links */
.social-links {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.8rem;
}

.social-link {
  display: flex;
  align-items: center;
  padding: 0.5rem;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.social-link:hover {
  background-color: var(--background-alt);
  transform: translateY(-2px);
}

.social-icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.4rem;
  font-size: 0.9rem;
}

.social-icon.facebook {
  background-color: #3b5998;
  color: white;
}

.social-icon.twitter {
  background-color: #1da1f2;
  color: white;
}

.social-icon.instagram {
  background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
  color: white;
}

.social-icon.youtube {
  background-color: #ff0000;
  color: white;
}

/* Testimonial Slider */
.testimonial-slider {
  display: flex;
  transition: transform 0.5s ease;
  margin-bottom: 0.8rem;
}

.testimonial-slide {
  flex: 0 0 100%;
}

.testimonial-content {
  position: relative;
  padding: 1rem;
  background-color: var(--background-alt);
  border-radius: 6px;
}

.testimonial-quote {
  position: absolute;
  top: 0.5rem;
  left: 0.5rem;
  font-size: 1.5rem;
  color: var(--primary);
  opacity: 0.2;
}

.testimonial-content p {
  font-size: 0.85rem;
  margin-bottom: 0.8rem;
  font-style: italic;
}

.testimonial-author {
  display: flex;
  align-items: center;
}

.testimonial-avatar {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 0.6rem;
}

.testimonial-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.testimonial-name {
  font-weight: 600;
  font-size: 0.8rem;
}

.testimonial-role {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.testimonial-dots {
  display: flex;
  justify-content: center;
  gap: 0.4rem;
}

.testimonial-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: var(--border);
  cursor: pointer;
  transition: all 0.3s ease;
}

.testimonial-dot.active {
  background-color: var(--primary);
  transform: scale(1.2);
}

/* Newsletter */
.newsletter p {
  margin-bottom: 0.8rem;
  font-size: 0.85rem;
}

/* Footer */
.footer {
  background-color: #333;
  color: white;
  padding: 2rem 0 0.8rem;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.footer-logo {
  display: flex;
  align-items: center;
  margin-bottom: 0.8rem;
}

.footer-description {
  font-size: 0.8rem;
  opacity: 0.8;
  line-height: 1.5;
}

.footer-title {
  font-size: 1rem;
  margin-bottom: 1rem;
  position: relative;
  padding-bottom: 0.4rem;
}

.footer-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 2px;
  background-color: var(--primary);
  border-radius: 2px;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin-bottom: 0.4rem;
}

.footer-links a {
  color: white;
  opacity: 0.8;
  transition: all 0.3s ease;
  font-size: 0.8rem;
}

.footer-links a:hover {
  opacity: 1;
  padding-left: 3px;
}

.contact-info {
  list-style: none;
  padding: 0;
  background-color: #f5f5f500;
}

.contact-info li {
  display: flex;
  align-items: flex-start;
  margin-bottom: 0.8rem;
  font-size: 0.8rem;
}

.opening-hours {
  list-style: none;
  padding: 0;
}

.opening-hours li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.4rem;
  font-size: 0.8rem;
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  font-size: 0.75rem;
}

.footer-nav {
  display: flex;
  gap: 1rem;
}

.footer-nav a {
  color: white;
  opacity: 0.8;
  transition: opacity 0.3s ease;
}

.footer-nav a:hover {
  opacity: 1;
}

/* Animations */
.animate-fade-in {
  opacity: 0;
  transform: translateY(15px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.animate-slide-up {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.animate-slide-right {
  opacity: 0;
  transform: translateX(-20px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.animate-visible {
  opacity: 1;
  transform: translate(0);
}

.animate-pulse {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(0, 100, 0, 0.4);
  }
  70% {
    box-shadow: 0 0 0 5px rgba(0, 100, 0, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(0, 100, 0, 0);
  }
}

.animate-hover {
  transition: all 0.3s ease;
}

.animate-hover:hover {
  transform: translateY(-3px);
}

/* Responsive Styles */
@media (max-width: 992px) {
  .content-wrapper {
    grid-template-columns: 1fr;
  }

  .main-column, .sidebar {
    flex: 0 0 100% !important;
    width: 100% !important;
  }
  
  .footer-content {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .news-item.featured {
    grid-template-columns: 1fr;
  }
  
  .identity-content, .bibliotheque-content {
    grid-template-columns: 1fr;
  }
  
  .formations-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .hero-content h1 {
    font-size: 1.5rem;
  }
}

@media (max-width: 768px) {
  .mobile-menu-btn {
    display: block;
  }
  
  .main-nav {
    position: fixed;
    top: 60px;
    left: 0;
    right: 0;
    background-color: white;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    padding: 0.8rem;
    display: none;
    flex-direction: column;
  }
  
  .main-nav.mobile-open {
    display: flex;
    animation: slide-down 0.3s ease;
  }
  
  @keyframes slide-down {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .nav-list {
    flex-direction: column;
    width: 100%;
    margin-bottom: 0.8rem;
  }
  
  .nav-item {
    margin: 0;
    margin-bottom: 0.6rem;
  }
  
  .dropdown-menu {
    position: static;
    box-shadow: none;
    padding-left: 0.8rem;
    animation: none;
    border: none;
    border-left: 2px solid var(--primary);
  }
  
  .nav-buttons {
    width: 100%;
    justify-content: space-between;
  }
  
  .btn-donate, .btn-espace {
    width: 48%;
    text-align: center;
  }
  
  .carousel-slide {
    flex: 0 0 calc(100% - 1rem);
  }
  
  .formations-grid {
    grid-template-columns: 1fr;
  }
  
  .hero-buttons {
    flex-direction: column;
  }
  
  .footer-content {
    grid-template-columns: 1fr;
  }
  
  .footer-bottom {
    flex-direction: column;
    gap: 0.8rem;
    text-align: center;
  }
  
  .footer-nav {
    flex-direction: column;
    gap: 0.4rem;
  }
  
  .section-header h2 {
    font-size: 1.1rem;
  }
}

@media (max-width: 576px) {
  .hero-content h1 {
    font-size: 1.3rem;
  }
  
  .hero-content p {
    font-size: 0.9rem;
  }
  
  .section-header h2 {
    font-size: 1rem;
  }
  
  .social-links {
    grid-template-columns: 1fr;
  }
  
  .identity-stats {
    flex-direction: column;
    gap: 0.8rem;
  }
  
  .bibliotheque-stats {
    flex-direction: column;
    gap: 0.8rem;
  }
}
</style>