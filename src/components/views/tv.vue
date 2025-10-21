<template>
  <div class="app">
    <!-- Header -->


    <!-- Page Banner -->
    <section class="page-banner">
      <div class="banner-pattern">
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
          <h1>AEEMCI TV</h1>
          <p class="subtitle">La Télévision de l'Association des Élèves et Étudiants Musulmans de Côte d'Ivoire</p>
          <div class="breadcrumbs">
            <a href="#">Accueil</a> / <span>AEEMCI TV</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
      <div class="container">
        
        <!-- Programmes en vedette -->
        <section class="featured-programs section">
          <div class="section-header">
            <h2 class="section-title">Programmes en Vedette</h2>
            <p class="section-subtitle">Découvrez nos émissions phares</p>
          </div>
          
          <div class="programs-grid">
            <div v-for="program in featuredPrograms" :key="program.id" class="program-card">
              <div class="program-image">
                <img :src="program.image" :alt="program.title">
                <div class="program-overlay">
                  <button class="play-btn">▶</button>
                </div>
              </div>
              <div class="program-info">
                <h3 class="program-title">{{ program.title }}</h3>
                <p class="program-description">{{ program.description }}</p>
                <div class="program-meta">
                  <span class="program-time">{{ program.time }}</span>
                  <span class="program-category">{{ program.category }}</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Grille des programmes -->
        <section class="schedule-section section">
          <div class="section-header">
            <h2 class="section-title">Grille des Programmes</h2>
            <p class="section-subtitle">Programmation de la semaine</p>
          </div>
          
          <div class="schedule-tabs">
            <button 
              v-for="day in days" 
              :key="day"
              @click="selectedDay = day"
              :class="['schedule-tab', { active: selectedDay === day }]"
            >
              {{ day }}
            </button>
          </div>
          
          <div class="schedule-content">
            <div v-for="slot in getScheduleForDay(selectedDay)" :key="slot.id" class="schedule-item">
              <div class="schedule-time">{{ slot.time }}</div>
              <div class="schedule-program">
                <img :src="slot.image" :alt="slot.title" class="schedule-thumb">
                <div class="schedule-details">
                  <h4>{{ slot.title }}</h4>
                  <p>{{ slot.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Équipe AEEMCI TV -->
        <section class="team-section section">
          <div class="section-header">
            <h2 class="section-title">Notre Équipe</h2>
            <p class="section-subtitle">Les visages de AEEMCI TV</p>
          </div>
          
          <div class="team-grid">
            <div v-for="member in teamMembers" :key="member.id" class="member-card">
              <div class="member-photo">
                <img :src="member.photo" :alt="member.name">
              </div>
              <div class="member-info">
                <h3 class="member-name">{{ member.name }}</h3>
                <p class="member-title">{{ member.role }}</p>
                <p class="member-bio">{{ member.bio }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Contact et Diffusion -->
        <section class="contact-section section">
          <div class="section-header">
            <h2 class="section-title">Contact & Diffusion</h2>
            <p class="section-subtitle">Restez connectés avec AEEMCI TV</p>
          </div>
          
          <div class="contact-content">
            <div class="contact-info">
              <div class="contact-item">
                <div class="contact-icon">📺</div>
                <div>
                  <h4>Chaîne TV</h4>
                  <p>Canal 25 - TNT Côte d'Ivoire</p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">🌐</div>
                <div>
                  <h4>Streaming</h4>
                  <p>www.aeemci-tv.ci</p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">📱</div>
                <div>
                  <h4>Application Mobile</h4>
                  <p>Disponible sur iOS et Android</p>
                </div>
              </div>
            </div>
            
            <div class="broadcast-info">
              <h3>Horaires de Diffusion</h3>
              <div class="broadcast-schedule">
                <div class="broadcast-item">
                  <span class="broadcast-day">Lundi - Vendredi</span>
                  <span class="broadcast-time">06:00 - 23:00</span>
                </div>
                <div class="broadcast-item">
                  <span class="broadcast-day">Samedi</span>
                  <span class="broadcast-time">08:00 - 22:00</span>
                </div>
                <div class="broadcast-item">
                  <span class="broadcast-day">Dimanche</span>
                  <span class="broadcast-time">09:00 - 21:00</span>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-pattern"></div>
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <div class="footer-logo">
              <img src="https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg" alt="AEEMCI" class="logo-img">
              <span class="logo-text">AEEMCI TV</span>
            </div>
            <p class="footer-description">
              La télévision de référence de la communauté étudiante musulmane de Côte d'Ivoire.
            </p>
          </div>
          
          <div class="footer-section">
            <h4 class="footer-title">Programmes</h4>
            <ul class="footer-links">
              <li><a href="#">Émissions Religieuses</a></li>
              <li><a href="#">Actualités</a></li>
              <li><a href="#">Éducation</a></li>
              <li><a href="#">Culture</a></li>
            </ul>
          </div>
          
          <div class="footer-section">
            <h4 class="footer-title">Services</h4>
            <ul class="footer-links">
              <li><a href="#">Direct</a></li>
              <li><a href="#">Replay</a></li>
              <li><a href="#">Podcasts</a></li>
              <li><a href="#">Archives</a></li>
            </ul>
          </div>
          
          <div class="footer-section">
            <h4 class="footer-title">Contact</h4>
            <ul class="contact-info">
              <li>📧 contact@aeemci-tv.ci</li>
              <li>📞 +225 XX XX XX XX</li>
              <li>📍 Abidjan, Côte d'Ivoire</li>
            </ul>
          </div>
        </div>
        
        <div class="footer-bottom">
          <p>&copy; 2024 AEEMCI TV. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const selectedDay = ref('Lundi')

const days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']

const featuredPrograms = ref([
  {
    id: 1,
    title: 'Lumière de l\'Islam',
    description: 'Émission spirituelle quotidienne avec des enseignements islamiques',
    time: '18:00',
    category: 'Religieux',
    image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTFPgqoJsuHqNS1qazKPrdDqX4tDy0ekqcb5A&s'
  },
  {
    id: 2,
    title: 'Campus Info',
    description: 'L\'actualité des universités et grandes écoles de Côte d\'Ivoire',
    time: '20:00',
    category: 'Actualités',
    image: 'https://pbs.twimg.com/media/GvcdyA-XwAAOJfZ.jpg'
  },
  {
    id: 3,
    title: 'Jeunesse Engagée',
    description: 'Débats et discussions sur les enjeux de la jeunesse musulmane',
    time: '21:00',
    category: 'Débat',
    image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
  }
])

const teamMembers = ref([
  {
    id: 1,
    name: 'Amadou KONE',
    role: 'Directeur de la Chaîne',
    bio: 'Journaliste expérimenté, passionné par les médias islamiques',
    photo: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
  },
  {
    id: 2,
    name: 'Fatima TRAORE',
    role: 'Présentatrice Principale',
    bio: 'Spécialiste en communication, voix emblématique de AEEMCI TV',
    photo: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
  },
  {
    id: 3,
    name: 'Ibrahim OUATTARA',
    role: 'Chef de Production',
    bio: 'Expert en production audiovisuelle et nouvelles technologies',
    photo: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
  }
])

const scheduleData = ref({
  'Lundi': [
    { id: 1, time: '06:00', title: 'Réveil Spirituel', description: 'Récitation du Coran et invocations', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 2, time: '12:00', title: 'Journal de Midi', description: 'Actualités nationales et internationales', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 3, time: '18:00', title: 'Lumière de l\'Islam', description: 'Enseignements religieux', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ],
  'Mardi': [
    { id: 4, time: '06:00', title: 'Réveil Spirituel', description: 'Récitation du Coran et invocations', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 5, time: '20:00', title: 'Campus Info', description: 'Actualités universitaires', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ],
  'Mercredi': [
    { id: 6, time: '06:00', title: 'Réveil Spirituel', description: 'Récitation du Coran et invocations', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 7, time: '21:00', title: 'Jeunesse Engagée', description: 'Débats sur la jeunesse', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ],
  'Jeudi': [
    { id: 8, time: '06:00', title: 'Réveil Spirituel', description: 'Récitation du Coran et invocations', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 9, time: '19:00', title: 'Culture & Tradition', description: 'Patrimoine culturel islamique', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ],
  'Vendredi': [
    { id: 10, time: '06:00', title: 'Réveil Spirituel', description: 'Récitation du Coran et invocations', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 11, time: '13:00', title: 'Prière du Vendredi', description: 'Retransmission en direct', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ],
  'Samedi': [
    { id: 12, time: '08:00', title: 'Émission Jeunesse', description: 'Programmes pour les jeunes', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 13, time: '15:00', title: 'Documentaire', description: 'Films documentaires islamiques', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ],
  'Dimanche': [
    { id: 14, time: '09:00', title: 'Famille & Société', description: 'Émission familiale', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' },
    { id: 15, time: '16:00', title: 'Récital Coranique', description: 'Récitation et mémorisation', image: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg' }
  ]
})

const getScheduleForDay = (day) => {
  return scheduleData.value[day] || []
}
</script>

<style scoped>
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
  line-height: 1.6;
  color: var(--text-primary);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.section {
  margin-bottom: 4rem;
}

.section-header {
  text-align: center;
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2.5rem;
  color: var(--primary);
  margin-bottom: 0.5rem;
  font-weight: 700;
}

.section-subtitle {
  font-size: 1.1rem;
  color: var(--text-secondary);
  max-width: 600px;
  margin: 0 auto;
}

/* Header Styles */
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
  padding: 0.8rem 0;
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
  text-decoration: none;
}

.nav-link.active {
  color: var(--primary);
  font-weight: 600;
}

.nav-link:hover {
  color: var(--primary);
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
  text-decoration: none;
}

/* Islamic Patterns */
.islamic-pattern {
  position: absolute;
  opacity: 0.3;
  pointer-events: none;
}

.islamic-pattern.star {
  width: 30px;
  height: 30px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0 L60 35 L100 35 L70 57 L80 92 L50 70 L20 92 L30 57 L0 35 L40 35 Z'/%3E%3C/svg%3E");
  background-size: contain;
  animation: float-diagonal 25s infinite linear;
}

.islamic-pattern.crescent {
  width: 35px;
  height: 35px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 10 C30 10 15 25 15 50 C15 75 30 90 50 90 C40 90 30 75 30 50 C30 25 40 10 50 10 Z'/%3E%3C/svg%3E");
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

/* Programs Grid */
.programs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
}

.program-card {
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.program-card:hover {
  transform: translateY(-5px);
}

.program-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.program-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.program-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.program-card:hover .program-overlay {
  opacity: 1;
}

.play-btn {
  background: var(--primary);
  color: white;
  border: none;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.play-btn:hover {
  transform: scale(1.1);
}

.program-info {
  padding: 1.5rem;
}

.program-title {
  font-size: 1.3rem;
  color: var(--primary);
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.program-description {
  color: var(--text-secondary);
  margin-bottom: 1rem;
  line-height: 1.6;
}

.program-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.program-time {
  background: var(--primary-lighter);
  color: var(--primary);
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.program-category {
  color: var(--text-light);
  font-size: 0.85rem;
}

/* Schedule Section */
.schedule-tabs {
  display: flex;
  justify-content: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.schedule-tab {
  background: transparent;
  border: 2px solid var(--border);
  color: var(--text-secondary);
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
}

.schedule-tab.active {
  background: var(--primary);
  border-color: var(--primary);
  color: white;
}

.schedule-tab:hover {
  border-color: var(--primary);
  color: var(--primary);
}

.schedule-content {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.schedule-item {
  display: flex;
  align-items: center;
  padding: 1rem 0;
  border-bottom: 1px solid var(--border);
}

.schedule-item:last-child {
  border-bottom: none;
}

.schedule-time {
  background: var(--primary);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  min-width: 80px;
  text-align: center;
  margin-right: 1.5rem;
}

.schedule-program {
  display: flex;
  align-items: center;
  flex-grow: 1;
}

.schedule-thumb {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  margin-right: 1rem;
}

.schedule-details h4 {
  color: var(--primary);
  margin-bottom: 0.3rem;
  font-size: 1.1rem;
}

.schedule-details p {
  color: var(--text-secondary);
  font-size: 0.9rem;
}

/* Team Section */
.team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.member-card {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.member-card:hover {
  transform: translateY(-5px);
}

.member-photo {
  height: 200px;
  overflow: hidden;
}

.member-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.member-card:hover .member-photo img {
  transform: scale(1.05);
}

.member-info {
  padding: 1.5rem;
}

.member-name {
  font-size: 1.2rem;
  color: var(--primary);
  margin-bottom: 0.3rem;
}

.member-title {
  font-size: 0.9rem;
  color: var(--text-secondary);
  margin-bottom: 1rem;
  font-weight: 600;
}

.member-bio {
  font-size: 0.9rem;
  line-height: 1.6;
  margin-bottom: 1rem;
}

/* Contact Section */
.contact-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.contact-item {
  display: flex;
  align-items: flex-start;
  background-color: var(--background-alt);
  padding: 1.5rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.contact-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
}

.contact-icon {
  font-size: 2rem;
  margin-right: 1rem;
  color: var(--primary);
}

.broadcast-info {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.broadcast-info h3 {
  color: var(--primary);
  margin-bottom: 1.5rem;
  font-size: 1.3rem;
}

.broadcast-schedule {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.broadcast-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: var(--primary-lighter);
  border-radius: 8px;
}

.broadcast-day {
  font-weight: 600;
  color: var(--primary);
}

.broadcast-time {
  color: var(--text-secondary);
  font-weight: 500;
}

/* Footer */
.footer {
  background-color: #333;
  color: white;
  padding: 3rem 0 1rem;
  position: relative;
  overflow: hidden;
}

.footer-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.1;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
  margin-bottom: 2rem;
  position: relative;
  z-index: 1;
}

.footer-logo {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.footer-description {
  font-size: 0.9rem;
  opacity: 0.8;
  line-height: 1.6;
}

.footer-title {
  font-size: 1.1rem;
  margin-bottom: 1.2rem;
  position: relative;
  padding-bottom: 0.5rem;
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
  margin-bottom: 0.6rem;
}

.footer-links a {
  color: white;
  opacity: 0.8;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  text-decoration: none;
}

.footer-links a:hover {
  opacity: 1;
  padding-left: 3px;
}

.contact-info {
  list-style: none;
  padding: 0;
}

.contact-info li {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.footer-bottom {
  border-top: 1px solid #555;
  padding-top: 1rem;
  text-align: center;
  position: relative;
  z-index: 1;
}

/* Responsive Design */
@media (max-width: 768px) {
  .page-title h1 {
    font-size: 2.2rem;
  }
  
  .programs-grid {
    grid-template-columns: 1fr;
  }
  
  .contact-content {
    grid-template-columns: 1fr;
  }
  
  .team-grid {
    grid-template-columns: 1fr;
  }
  
  .footer-content {
    grid-template-columns: 1fr;
  }
  
  .schedule-tabs {
    justify-content: flex-start;
    overflow-x: auto;
    padding-bottom: 0.5rem;
  }
  
  .nav-list {
    flex-direction: column;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: none;
  }
}
</style>