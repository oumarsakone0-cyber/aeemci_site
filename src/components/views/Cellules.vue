<template>
  <div class="history-page">
    <!-- Header avec bannière -->
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
            <h1>Cellules <span class="highlight">Spécialisées</span></h1>
            <div class="breadcrumbs">
              <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">AEEMCI</a> <span class="separator">/</span> <span class="current">Cellules Spécialisées</span>
            </div>
          </div>
        </div>
      </section>

    <!-- Contenu principal -->
    <main class="main-content">
      <div class="container">

        <!-- Section Cellule Genre -->
        <section id="genre" class="period-card">
          <div class="period-header">
            <div class="period-year">⚖️</div>
            <h2 class="period-title">Cellule Genre</h2>
          </div>
          <div class="period-content">
            <p>La cellule Genre vise à répondre aux défis spécifiques liés à la promotion de l'équité entre les sexes, à la lutte contre les discriminations et à la protection des droits des élèves et étudiants, en particulier les jeunes filles.</p>
          </div>
        </section>

        <!-- Section CIMA -->
        <section id="cima" class="period-card">
          <div class="period-header">
            <div class="period-year">🕌</div>
            <h2 class="period-title">CIMA</h2>
          </div>
          <div class="period-content">
            <div class="milestone-list">
              <ul>
                <li>Il est créé un Conseil des Imams et formateurs de l'AEEMCI (CIMA) qui regroupe tous les imams, les formateurs et tout autre acteur en charge de la formation de l'Association.</li>
                <li>Le CIMA est présidé par l'Imam Central de l'AEEMCI qui peut se faire représenter par un de ses adjoints.</li>
                <li>Le Conseil des Imams et formateurs de l'AEEMCI se réunit au moins une fois par mandat.</li>
              </ul>
            </div>
          </div>
        </section>

        <!-- Section Cellule Juridique -->
        <section id="juridique" class="period-card">
          <div class="period-header">
            <div class="period-year">⚖️</div>
            <h2 class="period-title">Cellule Juridique</h2>
          </div>
          <div class="period-content">
            <p>La cellule juridique a pour mission de fournir des réponses concrètes et spécialisées aux besoins juridiques des membres de l'AEEMCI, que ce soit en matière de protection de leurs droits, de résolution de conflits ou de compréhension des lois régissant leurs activités éducatives et associatives.</p>
          </div>
        </section>

        <!-- Section CNP -->
        <section id="cnp" class="period-card">
          <div class="period-header">
            <div class="period-year">📚</div>
            <h2 class="period-title">Conseil National Pédagogique (CNP)</h2>
          </div>
          <div class="period-content">
            <h3>Art 37 : Le Conseil National Pédagogique (CNP)</h3>
            <div class="milestone-list">
              <ul>
                <li>Le Conseil National Pédagogique est chargé de la mise en œuvre de la politique générale de formation de l'AEEMCI, du recrutement et de la formation des formateurs.</li>
                <li>Le CNP se compose de 7 membres, dont un président, désignés par le CE parmi les acteurs de la formation disposant d'une expérience avérée.</li>
                <li>Leur mandat est de deux (02) ans renouvelable.</li>
                <li>Le CE définit les orientations et missions du CNP au cours de sa mandature.</li>
                <li>Le CNP se réunit au moins une fois par mois sur convocation de son président afin de mener des travaux suivant son cahier de charges.</li>
                <li>Le CNP produit, chaque six mois, un rapport d'activités qu'il soumet au CE.</li>
                <li>Le CNP prend une part active aux activités de formation organisée par le CE (CIMA, SENAFOI, Concours Al Ilm, etc.).</li>
              </ul>
            </div>
          </div>
        </section>

        <!-- Section Cellule Informatique -->
        <section id="informatique" class="period-card">
          <div class="period-header">
            <div class="period-year">💻</div>
            <h2 class="period-title">Cellule Informatique et Multimédia</h2>
          </div>
          <div class="period-content">
            <p>La Cellule informatique et multimédia a pour objectif de digitaliser les activités de l'AEEMCI pour optimiser sa gestion, renforcer sa visibilité, sécuriser ses actions et améliorer la communication, tant en interne qu'en externe.</p>
          </div>
        </section>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Sections de navigation
const sections = ref([
  { id: 'genre', title: 'Cellule Genre', icon: '⚖️' },
  { id: 'cima', title: 'CIMA', icon: '🕌' },
  { id: 'juridique', title: 'Cellule Juridique', icon: '⚖️' },
  { id: 'cnp', title: 'CNP', icon: '📚' },
  { id: 'informatique', title: 'Cellule Informatique', icon: '💻' }
])

const activeSection = ref('genre')

// Navigation
const scrollToSection = (sectionId) => {
  activeSection.value = sectionId
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

// Intersection Observer pour la navigation active
let observer = null

onMounted(() => {
  // Animation d'entrée
  setTimeout(() => {
    const elements = document.querySelectorAll('.period-card')
    elements.forEach((el, index) => {
      setTimeout(() => {
        el.style.opacity = '1'
        el.style.transform = 'translateY(0)'
      }, index * 200)
    })
  }, 100)

  // Observer pour la navigation
  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        activeSection.value = entry.target.id
      }
    })
  }, { threshold: 0.3 })

  sections.value.forEach(section => {
    const element = document.getElementById(section.id)
    if (element) observer.observe(element)
  })
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})
</script>

<style scoped>
/* Variables CSS */
:root {
  --primary: #006400;
  --primary-dark: #004d00;
  --primary-light: #16a34a;
  --primary-lighter: #f0f8f0;
  --secondary: #16a34a;
  --background: #fafafa;
  --background-alt: #ffffff;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --border-color: #e1e8ed;
  --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Reset et base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #16a34a;
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

/* Page Banner */
.page-banner {
  position: relative;
  background: linear-gradient(135deg, #16a34a 0%, var(--primary-dark) 100%);
  color: white;
  padding: 4rem 0;
  overflow: hidden;
}

.banner-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
    radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 2px, transparent 2px);
  background-size: 40px 40px;
  animation: patternMove 20s linear infinite;
}

@keyframes patternMove {
  0% { transform: translate(0, 0); }
  100% { transform: translate(40px, 40px); }
}

.islamic-patterns {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden;
}

.islamic-pattern {
  position: absolute;
  opacity: 0.1;
}

.islamic-pattern.star::before {
  content: '✦';
  font-size: 20px;
  color: white;
  animation: float 6s ease-in-out infinite;
}

.islamic-pattern.crescent::before {
  content: '☪';
  font-size: 24px;
  color: white;
  animation: float 8s ease-in-out infinite;
}

.islamic-pattern.geometric::before {
  content: '◆';
  font-size: 16px;
  color: white;
  animation: rotate 10s linear infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

@keyframes rotate {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.page-title {
  position: relative;
  z-index: 2;
  text-align: center;
}

.page-title h1 {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
}

.highlight {
  color: var(--secondary);
}

.subtitle {
  font-size: 1.2rem;
  opacity: 0.9;
  margin-bottom: 2rem;
}

.breadcrumbs {
  font-size: 0.9rem;
  opacity: 0.8;
}

.breadcrumbs a {
  color: white;
  text-decoration: none;
}

.breadcrumbs a:hover {
  text-decoration: underline;
}

.separator {
  margin: 0 0.5rem;
}

.current {
  color: var(--secondary);
}

/* Main Content */
.main-content {
  padding: 2rem 0;
}

/* Period Cards */
.period-card {
  margin-bottom: 4rem;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.period-header {
  background-color: #16a34a;
  color: white;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.period-year {
  font-size: 2rem;
  font-weight: 700;
  line-height: 1;
  padding: 0.8rem 1.2rem;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
}

.period-title {
  font-size: 1.8rem;
  margin: 0;
}

.period-content {
  padding: 2rem;
}

.period-content h3 {
  color: #16a34a;
  margin: 2rem 0 1rem 0;
  font-size: 1.3rem;
}

.period-content p {
  font-size: 1rem;
  line-height: 1.7;
  margin-bottom: 1rem;
}

/* Milestone List */
.milestone-list {
  margin: 2rem 0;
}

.milestone-list ul {
  list-style: none;
}

.milestone-list li {
  display: flex;
  margin-bottom: 1rem;
  padding-left: 2rem;
  position: relative;
  line-height: 1.6;
}

.milestone-list li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: #16a34a;
  font-weight: bold;
  font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .page-title h1 {
    font-size: 2rem;
  }
  
  .subtitle {
    font-size: 1rem;
  }
  
  .period-header {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }
  
  .period-year {
    font-size: 1.5rem;
  }
  
  .period-title {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .period-content {
    padding: 1rem;
  }
}
</style>
