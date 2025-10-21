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
            <h1>Grand Groupe <span class="highlight">AEEMCI</span></h1>
            <p class="subtitle">Structure organisationnelle</p>
            <div class="breadcrumbs">
              <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">AEEMCI</a> <span class="separator">/</span> <span class="current">Organigramme</span>
            </div>
          </div>
        </div>
      </section>

    <!-- Contenu principal -->
    <main class="main-content">
      <div class="container">
        
        <!-- Introduction -->
        <section id="introduction" class="intro-section">
          <div class="quote-box">
            <div class="quote-mark">"</div>
            <blockquote>
              Une organisation structurée au service de la jeunesse musulmane estudiantine de Côte d'Ivoire.
            </blockquote>
            <div class="quote-author">- Vision Organisationnelle AEEMCI</div>
          </div>
          <div class="intro-text">
            <p><strong>L'AEEMCI</strong> s'appuie sur une structure organisationnelle solide et hiérarchisée pour mener à bien sa mission. Notre organigramme reflète notre engagement envers la transparence, l'efficacité et la participation démocratique.</p>
            <p>Chaque niveau de notre organisation joue un rôle crucial dans la réalisation de nos objectifs communs et le service de nos membres.</p>
          </div>
        </section>

        <!-- Assemblée Générale -->
        <section id="assemblee-generale" class="period-card">
          <div class="period-header">
            <div class="period-year">🏛️</div>
            <h2 class="period-title">Le Grand Groupe</h2>
          </div>
          <div class="period-content">
            <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758310090/aeemci_photo_1758310011938_organigramme.jpg.jpg" alt="Organigramme AEEMCI" style="width: 100%; border-radius: 8px; box-shadow: var(--shadow);" />
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
  { id: 'introduction', title: 'Introduction', icon: '📋' },
  { id: 'bureau-executif', title: 'Bureau Exécutif', icon: '👑' },
  { id: 'commissions', title: 'Commissions', icon: '🏛️' },
  { id: 'sections-regionales', title: 'Sections Régionales', icon: '🗺️' },
  { id: 'organes-controle', title: 'Organes de Contrôle', icon: '⚖️' },
  { id: 'assemblee-generale', title: 'Assemblée Générale', icon: '🏛️' }
])

const activeSection = ref('introduction')
const navTrack = ref(null)

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
    const elements = document.querySelectorAll('.period-card, .intro-section')
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
  --primary-light: #228B22;
  --primary-lighter: #f0f8f0;
  --secondary: #FFD700;
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

/* Page Banner */
.page-banner {
  position: relative;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
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
  content: '❋';
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
  font-weight: 600;
}

/* Timeline Navigation */
.timeline-nav {
  background-color: white;
  border-bottom: 1px solid var(--border-color);
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-track {
  display: flex;
  gap: 0.5rem;
  padding: 1rem 0;
  overflow-x: auto;
  scrollbar-width: none;
}

.nav-track::-webkit-scrollbar {
  display: none;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.8rem 1.2rem;
  border: none;
  background-color: transparent;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
  white-space: nowrap;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.nav-item:hover {
  background-color: var(--primary-lighter);
  color: var(--primary);
}

.nav-item.active {
  background-color: var(--primary);
  color: white;
  box-shadow: 0 2px 8px rgba(0, 100, 0, 0.3);
}

.nav-icon {
  font-size: 1.1rem;
}

/* Main Content */
.main-content {
  padding: 2rem 0;
}

/* Introduction Section */
.intro-section {
  display: flex;
  gap: 2rem;
  margin-bottom: 4rem;
  align-items: center;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.quote-box {
  flex: 0 0 40%;
  background-color: var(--primary-lighter);
  padding: 2rem;
  border-radius: 8px;
  position: relative;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.quote-mark {
  position: absolute;
  top: 1rem;
  left: 1rem;
  font-size: 4rem;
  color: var(--primary);
  opacity: 0.2;
  line-height: 1;
}

blockquote {
  font-size: 1.2rem;
  font-style: italic;
  line-height: 1.6;
  margin-bottom: 1rem;
  position: relative;
  z-index: 1;
  color: var(--primary-dark);
}

.quote-author {
  text-align: right;
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--primary);
}

.intro-text {
  flex: 1;
}

.intro-text p {
  margin-bottom: 1rem;
  font-size: 1rem;
  line-height: 1.7;
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
  background-color: var(--primary);
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

/* Leadership Grid */
.leadership-grid {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.leader-card {
  display: flex;
  align-items: flex-start;
  background-color: var(--background-alt);
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  transition: all 0.3s ease;
}

.leader-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.leader-card.president {
  background: linear-gradient(135deg, var(--primary-lighter) 0%, white 100%);
  border: 2px solid var(--primary);
}

.leader-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 1.5rem;
  border: 3px solid var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--primary-lighter);
}

.avatar-placeholder {
  font-size: 2rem;
  color: var(--primary);
}

.leader-info h3,
.leader-info h4 {
  color: var(--primary);
  margin-bottom: 0.5rem;
}

.leader-info h3 {
  font-size: 1.3rem;
}

.leader-info h4 {
  font-size: 1.1rem;
}

.leader-role {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin-bottom: 1rem;
}

.leader-responsibilities ul {
  list-style: none;
  padding-left: 0;
}

.leader-responsibilities li {
  padding: 0.3rem 0;
  padding-left: 1.5rem;
  position: relative;
  font-size: 0.9rem;
}

.leader-responsibilities li::before {
  content: '▸';
  position: absolute;
  left: 0;
  color: var(--primary);
  font-weight: bold;
}

.executive-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

/* Commissions Grid */
.commissions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

.commission-card {
  display: flex;
  align-items: center;
  background-color: var(--background-alt);
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  transition: all 0.3s ease;
}

.commission-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.commission-icon {
  font-size: 2.5rem;
  margin-right: 1rem;
  color: var(--primary);
}

.commission-info h4 {
  color: var(--primary);
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

.commission-info p {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin-bottom: 0.8rem;
}

.member-count {
  background-color: var(--primary-lighter);
  color: var(--primary);
  padding: 0.3rem 0.8rem;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

/* Regions Grid */
.regions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

.region-card {
  background-color: var(--background-alt);
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  transition: all 0.3s ease;
}

.region-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.region-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.region-header h4 {
  color: var(--primary);
  font-size: 1.2rem;
}

.region-status {
  padding: 0.3rem 0.8rem;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.region-status.active {
  background-color: #d4edda;
  color: #155724;
}

.region-status.development {
  background-color: #fff3cd;
  color: #856404;
}

.region-info p {
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

/* Control Organs */
.control-organs {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

.organ-card {
  display: flex;
  align-items: center;
  background-color: var(--background-alt);
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  transition: all 0.3s ease;
}

.organ-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.organ-icon {
  font-size: 2.5rem;
  margin-right: 1rem;
  color: var(--primary);
}

.organ-info h4 {
  color: var(--primary);
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

.organ-info p {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin-bottom: 0.8rem;
}

.organ-composition {
  background-color: var(--primary-lighter);
  color: var(--primary);
  padding: 0.3rem 0.8rem;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
  display: inline-block;
}

/* Assembly Info */
.assembly-info {
  display: grid;
  gap: 2rem;
}

.assembly-description h3 {
  color: var(--primary);
  margin-bottom: 1rem;
  font-size: 1.3rem;
}

.assembly-description p {
  font-size: 1rem;
  line-height: 1.7;
}

.assembly-powers h4,
.assembly-schedule h4 {
  color: var(--primary);
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.schedule-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.schedule-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--background-alt);
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid var(--border-color);
}

.schedule-type {
  font-weight: 600;
  color: var(--primary);
}

.schedule-frequency {
  color: var(--text-secondary);
  font-size: 0.9rem;
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
  color: var(--primary);
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
  
  .intro-section {
    flex-direction: column;
  }
  
  .quote-box {
    flex: none;
  }
  
  .executive-row {
    grid-template-columns: 1fr;
  }
  
  .commissions-grid,
  .regions-grid,
  .control-organs {
    grid-template-columns: 1fr;
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
  
  .leader-card {
    flex-direction: column;
    text-align: center;
  }
  
  .leader-avatar {
    margin-right: 0;
    margin-bottom: 1rem;
  }
}

@media (max-width: 480px) {
  .period-content {
    padding: 1rem;
  }
  
  .leader-card,
  .commission-card,
  .region-card,
  .organ-card {
    padding: 1rem;
  }
  
  .nav-item {
    padding: 0.6rem 1rem;
    font-size: 0.8rem;
  }
  
  .commission-card,
  .organ-card {
    flex-direction: column;
    text-align: center;
  }
  
  .commission-icon,
  .organ-icon {
    margin-right: 0;
    margin-bottom: 1rem;
  }
}
</style>
