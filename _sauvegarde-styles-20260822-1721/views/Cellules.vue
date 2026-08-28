<template>
  <div class="cellules-page">
    <!-- Hero Section -->
    <section class="cellules-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        
        <!-- Motifs islamiques animés -->
        <div class="islamic-patterns">
          <div v-for="n in 8" :key="`star-${n}`" class="islamic-pattern star" :style="{ 
            left: `${Math.random() * 100}%`, 
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`
          }"></div>
          
          <div v-for="n in 5" :key="`crescent-${n}`" class="islamic-pattern crescent" :style="{ 
            left: `${Math.random() * 100}%`, 
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`
          }"></div>
          
          <div v-for="n in 10" :key="`geometric-${n}`" class="islamic-pattern geometric" :style="{ 
            left: `${Math.random() * 100}%`, 
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`
          }"></div>
        </div>
      </div>
      
      <div class="hero-content">
        <div class="hero-icon">🏛️</div>
        <h1 class="hero-title">
          <span class="highlight">C</span>ellules Spécialisées
        </h1>
        <p class="hero-subtitle">
          Des structures dédiées pour répondre aux besoins spécifiques de nos membres
        </p>
        <div class="hero-badge">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          <span>Expertise & Innovation</span>
        </div>
      </div>
    </section>

    <!-- Mission Statement -->
    <section class="mission-section">
      <div class="container">
        <div class="mission-card">
          <div class="mission-icon">🎯</div>
          <div class="mission-content">
            <h2 class="mission-title">Notre Approche</h2>
            <blockquote class="mission-quote">
              <div class="quote-mark-large">"</div>
              <p>
                Les Cellules Spécialisées de l'AEEMCI sont des structures techniques et consultatives 
                créées pour apporter une expertise pointue dans des domaines spécifiques. Elles assurent 
                l'accompagnement, le conseil et la formation nécessaires au développement de l'association 
                et à la protection des intérêts de ses membres.
              </p>
              <div class="quote-author">
                <div class="author-line"></div>
                <span>Cellules Spécialisées AEEMCI</span>
              </div>
            </blockquote>
          </div>
        </div>
      </div>
    </section>

    <!-- Navigation rapide -->
    <section class="quick-nav-section">
      <div class="container">
        <div class="quick-nav">
          <div 
            v-for="cellule in cellules" 
            :key="cellule.id"
            class="nav-item"
            :class="{ active: activeCellule === cellule.id }"
            @click="scrollToCellule(cellule.id)"
          >
            <div class="nav-icon">{{ cellule.icon }}</div>
            <div class="nav-title">{{ cellule.title }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Cellules Section -->
    <section class="cellules-content">
      <div class="container">
        <!-- Cellule Genre -->
        <div id="genre" class="cellule-card">
          <div class="cellule-header">
            <div class="cellule-icon-large">⚖️</div>
            <div class="cellule-title-group">
              <h2 class="cellule-title">Cellule Genre</h2>
              <div class="cellule-badge">Équité & Protection</div>
            </div>
          </div>
          
          <div class="cellule-content">
            <div class="content-main">
              <h3 class="content-subtitle">Mission</h3>
              <p class="content-text">
                La cellule Genre vise à répondre aux défis spécifiques liés à la promotion de l'équité 
                entre les sexes, à la lutte contre les discriminations et à la protection des droits 
                des élèves et étudiants, en particulier les jeunes filles.
              </p>
            </div>

            <div class="content-features">
              <h3 class="content-subtitle">Domaines d'intervention</h3>
              <div class="features-grid">
                <div class="feature-item">
                  <div class="feature-icon">🛡️</div>
                  <h4>Protection des droits</h4>
                  <p>Défense et promotion des droits des membres</p>
                </div>
                <div class="feature-item">
                  <div class="feature-icon">📊</div>
                  <h4>Équité de genre</h4>
                  <p>Promotion de l'égalité entre les sexes</p>
                </div>
                <div class="feature-item">
                  <div class="feature-icon">🎓</div>
                  <h4>Sensibilisation</h4>
                  <p>Formation et éducation sur les questions de genre</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CIMA -->
        <div id="cima" class="cellule-card">
          <div class="cellule-header">
            <div class="cellule-icon-large">🕌</div>
            <div class="cellule-title-group">
              <h2 class="cellule-title">CIMA</h2>
              <div class="cellule-badge">Conseil des Imams</div>
            </div>
          </div>
          
          <div class="cellule-content">
            <div class="content-main">
              <h3 class="content-subtitle">Présentation</h3>
              <p class="content-text">
                Le Conseil des Imams et formateurs de l'AEEMCI (CIMA) regroupe tous les imams, 
                les formateurs et tout autre acteur en charge de la formation de l'Association.
              </p>
            </div>

            <div class="info-cards">
              <div class="info-card">
                <div class="info-icon">👤</div>
                <div class="info-content">
                  <h4>Direction</h4>
                  <p>Présidé par l'Imam Central de l'AEEMCI ou l'un de ses adjoints</p>
                </div>
              </div>
              
              <div class="info-card">
                <div class="info-icon">📅</div>
                <div class="info-content">
                  <h4>Fréquence</h4>
                  <p>Se réunit au moins une fois par mandat</p>
                </div>
              </div>
              
              <div class="info-card">
                <div class="info-icon">🎯</div>
                <div class="info-content">
                  <h4>Mission</h4>
                  <p>Coordination et développement de la formation religieuse</p>
                </div>
              </div>
            </div>

            <div class="membres-section">
              <h3 class="content-subtitle">Composition</h3>
              <div class="membres-list">
                <div class="membre-item">
                  <div class="membre-icon">✓</div>
                  <p>Tous les imams de l'AEEMCI</p>
                </div>
                <div class="membre-item">
                  <div class="membre-icon">✓</div>
                  <p>Les formateurs religieux certifiés</p>
                </div>
                <div class="membre-item">
                  <div class="membre-icon">✓</div>
                  <p>Les acteurs en charge de la formation</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cellule Juridique -->
        <div id="juridique" class="cellule-card">
          <div class="cellule-header">
            <div class="cellule-icon-large">⚖️</div>
            <div class="cellule-title-group">
              <h2 class="cellule-title">Cellule Juridique</h2>
              <div class="cellule-badge">Conseil & Protection</div>
            </div>
          </div>
          
          <div class="cellule-content">
            <div class="content-main">
              <h3 class="content-subtitle">Mission</h3>
              <p class="content-text">
                La cellule juridique a pour mission de fournir des réponses concrètes et spécialisées 
                aux besoins juridiques des membres de l'AEEMCI, que ce soit en matière de protection 
                de leurs droits, de résolution de conflits ou de compréhension des lois régissant 
                leurs activités éducatives et associatives.
              </p>
            </div>

            <div class="services-grid">
              <div class="service-card">
                <div class="service-icon">📜</div>
                <h4>Conseil juridique</h4>
                <p>Assistance et conseil sur les questions de droit</p>
              </div>
              <div class="service-card">
                <div class="service-icon">🤝</div>
                <h4>Médiation</h4>
                <p>Résolution amiable des conflits</p>
              </div>
              <div class="service-card">
                <div class="service-icon">🛡️</div>
                <h4>Protection juridique</h4>
                <p>Défense des droits et intérêts des membres</p>
              </div>
              <div class="service-card">
                <div class="service-icon">📚</div>
                <h4>Formation</h4>
                <p>Sensibilisation aux droits et obligations</p>
              </div>
            </div>
          </div>
        </div>

        <!-- CNP -->
        <div id="cnp" class="cellule-card">
          <div class="cellule-header">
            <div class="cellule-icon-large">📚</div>
            <div class="cellule-title-group">
              <h2 class="cellule-title">Conseil National Pédagogique</h2>
              <div class="cellule-badge">CNP - Formation</div>
            </div>
          </div>
          
          <div class="cellule-content">
            <div class="content-main">
              <h3 class="content-subtitle">Rôle & Attributions</h3>
              <p class="content-text">
                Le Conseil National Pédagogique est chargé de la mise en œuvre de la politique 
                générale de formation de l'AEEMCI, du recrutement et de la formation des formateurs.
              </p>
            </div>

            <div class="cnp-structure">
              <div class="structure-item">
                <div class="structure-icon">👥</div>
                <div class="structure-details">
                  <h4>Composition</h4>
                  <p>7 membres dont un président, désignés par le CE parmi les acteurs de la formation avec expérience avérée</p>
                </div>
              </div>
              
              <div class="structure-item">
                <div class="structure-icon">⏱️</div>
                <div class="structure-details">
                  <h4>Mandat</h4>
                  <p>Deux (02) ans renouvelable</p>
                </div>
              </div>
              
              <div class="structure-item">
                <div class="structure-icon">📅</div>
                <div class="structure-details">
                  <h4>Réunions</h4>
                  <p>Au moins une fois par mois sur convocation du président</p>
                </div>
              </div>
            </div>

            <div class="missions-section">
              <h3 class="content-subtitle">Missions Principales</h3>
              <div class="missions-grid">
                <div class="mission-item">
                  <div class="mission-number">1</div>
                  <div class="mission-text">
                    <h4>Politique de formation</h4>
                    <p>Mise en œuvre de la stratégie générale de formation</p>
                  </div>
                </div>
                
                <div class="mission-item">
                  <div class="mission-number">2</div>
                  <div class="mission-text">
                    <h4>Recrutement</h4>
                    <p>Sélection et formation des formateurs qualifiés</p>
                  </div>
                </div>
                
                <div class="mission-item">
                  <div class="mission-number">3</div>
                  <div class="mission-text">
                    <h4>Rapportage</h4>
                    <p>Production de rapports d'activités semestriels au CE</p>
                  </div>
                </div>
                
                <div class="mission-item">
                  <div class="mission-number">4</div>
                  <div class="mission-text">
                    <h4>Participation active</h4>
                    <p>Implication dans les activités de formation (CIMA, SENAFOI, Concours Al Ilm)</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cellule Informatique -->
        <div id="informatique" class="cellule-card">
          <div class="cellule-header">
            <div class="cellule-icon-large">💻</div>
            <div class="cellule-title-group">
              <h2 class="cellule-title">Cellule Informatique et Multimédia</h2>
              <div class="cellule-badge">Digitalisation</div>
            </div>
          </div>
          
          <div class="cellule-content">
            <div class="content-main">
              <h3 class="content-subtitle">Objectif</h3>
              <p class="content-text">
                La Cellule informatique et multimédia a pour objectif de digitaliser les activités 
                de l'AEEMCI pour optimiser sa gestion, renforcer sa visibilité, sécuriser ses actions 
                et améliorer la communication, tant en interne qu'en externe.
              </p>
            </div>

            <div class="tech-areas">
              <h3 class="content-subtitle">Domaines d'Expertise</h3>
              <div class="tech-grid">
                <div class="tech-card">
                  <div class="tech-icon">🌐</div>
                  <h4>Plateforme digitale</h4>
                  <p>Développement et maintenance du site web et des applications</p>
                </div>
                
                <div class="tech-card">
                  <div class="tech-icon">📱</div>
                  <h4>Communication digitale</h4>
                  <p>Gestion des réseaux sociaux et de la présence en ligne</p>
                </div>
                
                <div class="tech-card">
                  <div class="tech-icon">🔒</div>
                  <h4>Sécurité</h4>
                  <p>Protection des données et sécurisation des systèmes</p>
                </div>
                
                <div class="tech-card">
                  <div class="tech-icon">📊</div>
                  <h4>Gestion de données</h4>
                  <p>Organisation et analyse des informations de l'association</p>
                </div>
                
                <div class="tech-card">
                  <div class="tech-icon">🎥</div>
                  <h4>Production multimédia</h4>
                  <p>Création de contenus audio-visuels de qualité</p>
                </div>
                
                <div class="tech-card">
                  <div class="tech-icon">🤖</div>
                  <h4>Innovation</h4>
                  <p>Veille technologique et solutions innovantes</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card">
          <div class="cta-content">
            <h2 class="cta-title">Besoin d'assistance ?</h2>
            <p class="cta-text">
              Nos cellules spécialisées sont à votre disposition pour vous accompagner 
              dans vos démarches et répondre à vos besoins spécifiques.
            </p>
            <div class="cta-buttons">
              <button class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Nous contacter</span>
              </button>
              <button class="btn btn-secondary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>En savoir plus</span>
              </button>
            </div>
          </div>
          <div class="cta-decoration">
            <div class="decoration-circle"></div>
            <div class="decoration-circle"></div>
            <div class="decoration-circle"></div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Cellules
const cellules = ref([
  { id: 'genre', title: 'Cellule Genre', icon: '⚖️' },
  { id: 'cima', title: 'CIMA', icon: '🕌' },
  { id: 'juridique', title: 'Cellule Juridique', icon: '⚖️' },
  { id: 'cnp', title: 'CNP', icon: '📚' },
  { id: 'informatique', title: 'Cellule Informatique', icon: '💻' }
])

const activeCellule = ref('genre')

// Scroll to cellule
const scrollToCellule = (id) => {
  const element = document.getElementById(id)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

// Intersection Observer
let observer = null

onMounted(() => {
  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        activeCellule.value = entry.target.id
        entry.target.classList.add('visible')
      }
    })
  }, { threshold: 0.2 })

  cellules.value.forEach(cellule => {
    const element = document.getElementById(cellule.id)
    if (element) observer.observe(element)
  })
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');

/* ===== VARIABLES ===== */
:root {
  --primary: #018404;
  --primary-dark: #016a03;
  --primary-light: #16a34a;
  --secondary: #fbbf24;
  --accent: #fb923c;
  --text-primary: #1a1a1a;
  --text-secondary: #475569;
  --background: #fafafa;
}

/* ===== BASE ===== */
.cellules-page {
  background: var(--background);
  min-height: 100vh;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* ===== HERO SECTION ===== */
.cellules-hero {
  position: relative;
  background: linear-gradient(135deg, #018404, #166534);
  color: white;
  padding: 6rem 2rem;
  text-align: center;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.hero-pattern {
  position: absolute;
  width: 100%;
  height: 100%;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.1;
}

.hero-glow-1,
.hero-glow-2 {
  position: absolute;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  filter: blur(120px);
  opacity: 0.15;
}

.hero-glow-1 {
  top: -300px;
  left: -200px;
  background: radial-gradient(circle, #fbbf24, transparent 70%);
  animation: float-glow 20s ease-in-out infinite;
}

.hero-glow-2 {
  bottom: -300px;
  right: -200px;
  background: radial-gradient(circle, #fb923c, transparent 70%);
  animation: float-glow 25s ease-in-out infinite reverse;
}

@keyframes float-glow {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(50px, -50px); }
}

/* Motifs islamiques */
.islamic-patterns {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.islamic-pattern {
  position: absolute;
  opacity: 0.15;
  animation: float-pattern 30s infinite linear;
}

.islamic-pattern.star {
  width: 30px;
  height: 30px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
  background-size: contain;
}

.islamic-pattern.crescent {
  width: 25px;
  height: 25px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
  background-size: contain;
}

.islamic-pattern.geometric {
  width: 40px;
  height: 40px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0 L100 50 L50 100 L0 50 Z'/%3E%3C/svg%3E");
  background-size: contain;
}

@keyframes float-pattern {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  100% {
    transform: translateY(100vh) rotate(360deg);
  }
}

.hero-content {
  position: relative;
  z-index: 1;
  max-width: 800px;
  margin: 0 auto;
}

.hero-icon {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
  animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-15px); }
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 1rem;
  line-height: 1.2;
  font-family: 'Zain', sans-serif;
}

.highlight {
  background: linear-gradient(135deg, #fbbf24, #fb923c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-subtitle {
  font-size: 1.25rem;
  opacity: 0.95;
  margin-bottom: 2rem;
  line-height: 1.6;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  background: rgba(255, 255, 255, 0.15);
  padding: 0.75rem 1.5rem;
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.hero-badge svg {
  color: #fbbf24;
}

/* ===== MISSION SECTION ===== */
.mission-section {
  padding: 6rem 0;
}

.mission-card {
  background: white;
  border-radius: 24px;
  padding: 4rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  border: 2px solid rgba(1, 132, 4, 0.1);
  position: relative;
  overflow: hidden;
}

.mission-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #018404, #fb923c, #fbbf24);
}

.mission-icon {
  font-size: 4rem;
  text-align: center;
  margin-bottom: 2rem;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

.mission-title {
  text-align: center;
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 2rem;
}

.mission-quote {
  position: relative;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 3rem;
  border-radius: 16px;
  border-left: 4px solid #018404;
}

.quote-mark-large {
  position: absolute;
  top: 1rem;
  left: 1rem;
  font-size: 6rem;
  color: rgba(1, 132, 4, 0.1);
  font-weight: 900;
  line-height: 1;
}

.mission-quote p {
  font-size: 1.3rem;
  line-height: 1.8;
  color: var(--text-secondary);
  font-style: italic;
  position: relative;
  z-index: 1;
  margin: 0;
}

.quote-author {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-top: 2rem;
  justify-content: flex-end;
}

.author-line {
  width: 60px;
  height: 2px;
  background: linear-gradient(90deg, #018404, #fb923c);
}

.quote-author span {
  font-size: 1.1rem;
  font-weight: 700;
  color: #018404;
  font-style: normal;
}

/* ===== QUICK NAV ===== */
.quick-nav-section {
  padding: 3rem 0;
  background: white;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.quick-nav {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  border: 2px solid rgba(1, 132, 4, 0.1);
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.nav-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(1, 132, 4, 0.15);
  border-color: #018404;
}

.nav-item.active {
  background: linear-gradient(135deg, #018404, #016a03);
  border-color: #018404;
  color: white;
}

.nav-icon {
  font-size: 1.5rem;
}

.nav-title {
  font-weight: 600;
  font-size: 0.95rem;
}

.nav-item.active .nav-title {
  color: white;
}

/* ===== CELLULES CONTENT ===== */
.cellules-content {
  padding: 4rem 0;
}

.cellule-card {
  background: white;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  border: 2px solid rgba(1, 132, 4, 0.1);
  margin-bottom: 4rem;
  opacity: 0;
  transform: translateY(50px);
  transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.cellule-card.visible {
  opacity: 1;
  transform: translateY(0);
}

.cellule-header {
  background: linear-gradient(135deg, #018404, #016a03);
  color: white;
  padding: 3rem;
  display: flex;
  align-items: center;
  gap: 2rem;
  position: relative;
  overflow: hidden;
}

.cellule-header::before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(251, 191, 36, 0.2), transparent 70%);
  border-radius: 50%;
}

.cellule-icon-large {
  font-size: 4rem;
  background: rgba(255, 255, 255, 0.15);
  width: 100px;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 20px;
  flex-shrink: 0;
  position: relative;
  z-index: 1;
}

.cellule-title-group {
  flex: 1;
  position: relative;
  z-index: 1;
}

.cellule-title {
  font-size: 2.5rem;
  font-weight: 900;
  margin-bottom: 0.75rem;
}

.cellule-badge {
  display: inline-block;
  background: rgba(255, 255, 255, 0.2);
  padding: 0.5rem 1.25rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
}

.cellule-content {
  padding: 3rem;
}

.content-main {
  margin-bottom: 3rem;
}

.content-subtitle {
  font-size: 1.8rem;
  font-weight: 800;
  color: #018404;
  margin-bottom: 1.5rem;
}

.content-text {
  font-size: 1.15rem;
  line-height: 1.8;
  color: var(--text-secondary);
  margin: 0;
}

/* Features Grid */
.content-features {
  margin-top: 3rem;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  margin-top: 2rem;
}

.feature-item {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  text-align: center;
  transition: all 0.3s ease;
}

.feature-item:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 30px rgba(1, 132, 4, 0.15);
  border-color: #018404;
}

.feature-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.feature-item h4 {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.75rem;
}

.feature-item p {
  font-size: 0.95rem;
  color: var(--text-secondary);
  margin: 0;
}

/* Info Cards */
.info-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  margin-top: 3rem;
}

.info-card {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  display: flex;
  gap: 1.5rem;
  transition: all 0.3s ease;
}

.info-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(1, 132, 4, 0.12);
}

.info-icon {
  font-size: 2.5rem;
  flex-shrink: 0;
}

.info-content h4 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #018404;
  margin-bottom: 0.5rem;
}

.info-content p {
  font-size: 0.95rem;
  color: var(--text-secondary);
  margin: 0;
}

/* Membres Section */
.membres-section {
  margin-top: 3rem;
}

.membres-list {
  display: grid;
  gap: 1rem;
  margin-top: 1.5rem;
}

.membre-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.02), rgba(251, 146, 60, 0.02));
  padding: 1.25rem 1.5rem;
  border-radius: 12px;
  border-left: 4px solid #018404;
}

.membre-icon {
  width: 30px;
  height: 30px;
  background: #018404;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  flex-shrink: 0;
}

.membre-item p {
  font-size: 1.05rem;
  color: var(--text-primary);
  margin: 0;
}

/* Services Grid */
.services-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
  margin-top: 3rem;
}

.service-card {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  transition: all 0.3s ease;
}

.service-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(1, 132, 4, 0.15);
  border-color: #018404;
}

.service-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.service-card h4 {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.75rem;
}

.service-card p {
  font-size: 1rem;
  color: var(--text-secondary);
  margin: 0;
}

/* CNP Structure */
.cnp-structure {
  margin-top: 3rem;
}

.structure-item {
  display: flex;
  align-items: flex-start;
  gap: 1.5rem;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.02), rgba(251, 146, 60, 0.02));
  padding: 2rem;
  border-radius: 16px;
  margin-bottom: 1.5rem;
  border-left: 4px solid #018404;
}

.structure-icon {
  font-size: 2.5rem;
  flex-shrink: 0;
}

.structure-details h4 {
  font-size: 1.3rem;
  font-weight: 700;
  color: #018404;
  margin-bottom: 0.75rem;
}

.structure-details p {
  font-size: 1.05rem;
  color: var(--text-secondary);
  margin: 0;
  line-height: 1.7;
}

/* Missions Grid */
.missions-section {
  margin-top: 3rem;
}

.missions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
  margin-top: 2rem;
}

.mission-item {
  display: flex;
  gap: 1.5rem;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  transition: all 0.3s ease;
}

.mission-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(1, 132, 4, 0.12);
  border-color: #018404;
}

.mission-number {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #018404, #016a03);
  color: white;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 900;
  flex-shrink: 0;
}

.mission-text h4 {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

.mission-text p {
  font-size: 0.95rem;
  color: var(--text-secondary);
  margin: 0;
}

/* Tech Areas */
.tech-areas {
  margin-top: 3rem;
}

.tech-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  margin-top: 2rem;
}

.tech-card {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  text-align: center;
  transition: all 0.3s ease;
}

.tech-card:hover {
  transform: translateY(-8px) rotate(-2deg);
  box-shadow: 0 15px 40px rgba(1, 132, 4, 0.15);
  border-color: #018404;
}

.tech-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.tech-card h4 {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.75rem;
}

.tech-card p {
  font-size: 0.95rem;
  color: var(--text-secondary);
  margin: 0;
  line-height: 1.6;
}

/* ===== CTA SECTION ===== */
.cta-section {
  padding: 6rem 0;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
}

.cta-card {
  background: white;
  border-radius: 24px;
  padding: 4rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  text-align: center;
  position: relative;
  overflow: hidden;
  border: 2px solid rgba(1, 132, 4, 0.1);
}

.cta-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #018404, #fb923c, #fbbf24);
}

.cta-content {
  position: relative;
  z-index: 1;
}

.cta-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 1.5rem;
}

.cta-text {
  font-size: 1.2rem;
  color: var(--text-secondary);
  max-width: 700px;
  margin: 0 auto 3rem;
  line-height: 1.8;
}

.cta-buttons {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, #018404, #016a03);
  color: white;
  box-shadow: 0 8px 25px rgba(1, 132, 4, 0.3);
}

.btn-primary:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 40px rgba(1, 132, 4, 0.4);
}

.btn-secondary {
  background: white;
  color: #018404;
  border: 2px solid #018404;
}

.btn-secondary:hover {
  background: #018404;
  color: white;
  transform: translateY(-4px);
  box-shadow: 0 15px 40px rgba(1, 132, 4, 0.2);
}

.cta-decoration {
  position: absolute;
  top: 50%;
  right: -100px;
  transform: translateY(-50%);
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.decoration-circle {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(1, 132, 4, 0.1), transparent 70%);
  animation: pulse-circle 3s ease-in-out infinite;
}

.decoration-circle:nth-child(2) {
  animation-delay: 1s;
}

.decoration-circle:nth-child(3) {
  animation-delay: 2s;
}

@keyframes pulse-circle {
  0%, 100% { transform: scale(1); opacity: 0.3; }
  50% { transform: scale(1.2); opacity: 0.5; }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .features-grid,
  .info-cards,
  .tech-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .services-grid,
  .missions-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .cellules-hero {
    padding: 4rem 1.5rem;
  }

  .hero-title {
    font-size: 2.5rem;
  }

  .hero-subtitle {
    font-size: 1rem;
  }

  .mission-card {
    padding: 2rem;
  }

  .mission-title {
    font-size: 2rem;
  }

  .mission-quote p {
    font-size: 1.1rem;
  }

  .quick-nav {
    overflow-x: auto;
    justify-content: flex-start;
    padding-bottom: 1rem;
  }

  .cellule-header {
    flex-direction: column;
    text-align: center;
    padding: 2rem;
  }

  .cellule-title {
    font-size: 2rem;
  }

  .cellule-content {
    padding: 2rem;
  }

  .features-grid,
  .info-cards,
  .tech-grid {
    grid-template-columns: 1fr;
  }

  .cta-title {
    font-size: 2rem;
  }

  .cta-card {
    padding: 2rem;
  }

  .cta-buttons {
    flex-direction: column;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .hero-icon {
    font-size: 3rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .cellule-icon-large {
    width: 80px;
    height: 80px;
    font-size: 3rem;
  }

  .content-subtitle {
    font-size: 1.5rem;
  }

  .nav-item {
    padding: 0.6rem 1rem;
  }

  .nav-title {
    font-size: 0.85rem;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>