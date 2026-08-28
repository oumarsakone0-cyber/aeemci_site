<template>
  <div class="histoire-page">
    <!-- Hero Section -->
    <section class="histoire-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        
        <!-- Motifs islamiques -->
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
        <div class="hero-icon">📖</div>
        <h1 class="hero-title">
          L'Histoire de l'<span class="highlight">AEEMCI</span>
        </h1>
        <p class="hero-subtitle">
          Un demi-siècle d'engagement pour la jeunesse musulmane ivoirienne
        </p>
        <div class="hero-stats">
          <div class="hero-stat">
            <div class="stat-value">50+</div>
            <div class="stat-label">Années</div>
          </div>
          <div class="hero-stat">
            <div class="stat-value">500K+</div>
            <div class="stat-label">Membres</div>
          </div>
          <div class="hero-stat">
            <div class="stat-value">1000+</div>
            <div class="stat-label">Sections</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Introduction -->
    <section class="intro-section">
      <div class="container">
        <div class="intro-card">
          <div class="intro-icon">✨</div>
          <blockquote class="intro-quote">
            <div class="quote-mark-large">"</div>
            <p>
              L'histoire de l'AEEMCI est celle d'une vision, d'un engagement et d'une détermination 
              sans faille pour préserver l'identité islamique tout en embrassant l'excellence académique.
            </p>
            <div class="quote-author">
              <div class="author-line"></div>
              <span>Fondateurs de l'AEEMCI</span>
            </div>
          </blockquote>
          
          <div class="intro-text">
            <p>
              Découvrez l'histoire fascinante de l'Association des Élèves et Étudiants Musulmans 
              de Côte d'Ivoire (AEEMCI), une organisation qui a façonné la vie de milliers de jeunes 
              musulmans ivoiriens depuis sa naissance en 1972.
            </p>
            <p>
              De ses modestes débuts dans une chambre universitaire à son statut actuel d'organisation 
              influente à l'échelle nationale, l'AEEMCI a traversé les décennies en restant fidèle à 
              sa mission fondamentale.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Timeline Navigation -->
    <section class="timeline-nav-section">
      <div class="container">
        <div class="timeline-nav">
          <div 
            v-for="(period, index) in periods" 
            :key="index"
            class="timeline-nav-item"
            :class="{ active: activePeriod === index }"
            @click="scrollToPeriod(index)"
          >
            <div class="nav-year">{{ period.year }}</div>
            <div class="nav-label">{{ period.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Périodes Historiques -->
    <section class="periods-section">
      <div class="container">
        <!-- 1972 - Naissance -->
        <div :id="`period-0`" class="period-block">
          <div class="period-header">
            <div class="period-year-badge">1972</div>
            <h2 class="period-title">Naissance de l'AEEMCI</h2>
          </div>

          <div class="period-gallery">
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758349618/aeemci_photo_1758349617992_Vassiriki_tourefd.png.png" alt="1er Président" />
              <div class="gallery-caption">1er Président - Vassiriki Touré</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350279/aeemci_photo_1758350277814_fffd.jpg.jpg" alt="Rencontre" />
              <div class="gallery-caption">Rencontre fondatrice</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758349497/aeemci_photo_1758349495440_WhatsApp_Image_2025-09-20_%C3%A0_06.23.37_0cd293c9.jpg.jpg" alt="SIFRAM" />
              <div class="gallery-caption">Activité SIFRAM</div>
            </div>
          </div>

          <div class="period-content">
            <div class="highlight-box">
              <div class="highlight-icon">🌟</div>
              <p>Comment continuer d'aller à l'école des blancs sans perdre son âme, sa foi et son identité islamique ?</p>
            </div>

            <p class="content-text">
              À la faveur de l'organisation du mois de Ramadan sur le campus de l'université de Cocody, 
              un groupe de jeunes étudiants musulmans réunis dans une chambre de cité universitaire font 
              lancer les bases d'une structure qui sera en quelques décennies, hissée au diapason des grands 
              mouvements musulmans crédibles de la sous-région Africaine.
            </p>

            <div class="founders-card">
              <h3>Les Précurseurs</h3>
              <p>
                <strong>Mory BAH</strong> et <strong>Vassiriki TOURÉ</strong> ont initié ce mouvement 
                face à une question de survie communautaire.
              </p>
            </div>
          </div>
        </div>

        <!-- 1975 - Reconnaissance -->
        <div :id="`period-1`" class="period-block period-alt">
          <div class="period-header">
            <div class="period-year-badge">1975</div>
            <h2 class="period-title">Reconnaissance Officielle</h2>
          </div>

          <div class="period-gallery">
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758349682/aeemci_photo_1758349682241_PR_KADER_KEITAjnk.png.png" alt="2ème Président" />
              <div class="gallery-caption">2ème Président - Abdoul Kader Keïta</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350614/aeemci_photo_1758350612316_reunion.jpg.jpg" alt="AG" />
              <div class="gallery-caption">Assemblée générale</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350685/aeemci_photo_1758350683723_conference_75.jpg.jpg" alt="Conférences" />
              <div class="gallery-caption">Conférences nationales</div>
            </div>
          </div>

          <div class="period-content">
            <div class="milestone-box">
              <div class="milestone-date">12 avril 1979</div>
              <h3>Reconnaissance de l'État</h3>
              <p>Par arrêté Ministériel N° 576/INT/AT/AG/1, l'AEEMCI obtient la reconnaissance officielle.</p>
            </div>

            <div class="assemblies-list">
              <div class="assembly-item">
                <div class="assembly-year">1972</div>
                <p>Première AG désignant <strong>Vassiriki TOURÉ</strong> comme président</p>
              </div>
              <div class="assembly-item">
                <div class="assembly-year">1975</div>
                <p>Seconde AG investissant <strong>Abdoul Kader KEÏTA</strong></p>
              </div>
            </div>
          </div>
        </div>

        <!-- 2015 - 40 ans -->
        <div :id="`period-2`" class="period-block">
          <div class="period-header">
            <div class="period-year-badge">2015</div>
            <h2 class="period-title">Quarante Ans d'Excellence</h2>
          </div>

          <div class="period-gallery">
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350841/aeemci_photo_1758350839817_amirbabam.jpg.jpg" alt="Amir" />
              <div class="gallery-caption">Rencontre avec l'Amir</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351163/aeemci_photo_1758351161265_400.jpg.jpg" alt="Logo" />
              <div class="gallery-caption">Logo 40ème anniversaire</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351364/aeemci_photo_1758351362800_dddd%C3%A0.jpg.jpg" alt="Célébration" />
              <div class="gallery-caption">Célébration des 40 ans</div>
            </div>
          </div>

          <div class="period-content">
            <div class="stats-showcase">
              <h3>Notre Réseau en chiffres</h3>
              <div class="stats-grid">
                <div class="stat-card">
                  <div class="stat-icon">🏢</div>
                  <div class="stat-number">31</div>
                  <div class="stat-label">Secrétariats Régionaux</div>
                </div>
                <div class="stat-card">
                  <div class="stat-icon">🏘️</div>
                  <div class="stat-number">200+</div>
                  <div class="stat-label">Sous-comités</div>
                </div>
                <div class="stat-card">
                  <div class="stat-icon">🏫</div>
                  <div class="stat-number">1000+</div>
                  <div class="stat-label">Sections</div>
                </div>
                <div class="stat-card">
                  <div class="stat-icon">👥</div>
                  <div class="stat-number">500K+</div>
                  <div class="stat-label">Membres</div>
                </div>
              </div>
            </div>

            <div class="achievements-grid">
              <div class="achievement-card">
                <div class="achievement-icon">🎓</div>
                <h4>Société Civile</h4>
                <p>Membre fondateur de la CSCI</p>
              </div>
              <div class="achievement-card">
                <div class="achievement-icon">🌍</div>
                <h4>Rayonnement</h4>
                <p>Modèle sous-régional</p>
              </div>
              <div class="achievement-card">
                <div class="achievement-icon">🤝</div>
                <h4>Partenariats</h4>
                <p>OJEMAO, BID, IIFSO</p>
              </div>
              <div class="achievement-card">
                <div class="achievement-icon">📚</div>
                <h4>Formation</h4>
                <p>3M de personnes touchées</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Aujourd'hui -->
        <div :id="`period-3`" class="period-block period-alt">
          <div class="period-header">
            <div class="period-year-badge">{{ currentYear }}</div>
            <h2 class="period-title">L'AEEMCI Aujourd'hui</h2>
          </div>

          <div class="period-gallery">
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351484/aeemci_photo_1758351483523_fpf.jpg.jpg" alt="Rentrée" />
              <div class="gallery-caption">Rentrée Solennelle</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351663/aeemci_photo_1758351660482_hgl.jpg.jpg" alt="Jeunesse" />
              <div class="gallery-caption">Jeunesse engagée</div>
            </div>
            <div class="gallery-item">
              <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351709/aeemci_photo_1758351709028_rupt.jpg.jpg" alt="Rupture" />
              <div class="gallery-caption">Rupture Collective</div>
            </div>
          </div>

          <div class="period-content">
            <div class="message-card">
              <div class="message-header">
                <div class="message-icon">💬</div>
                <h3>Message de l'Amir</h3>
              </div>
              <p>
                Cette année marque les 50 ans de l'AEEMCI, un demi-siècle d'engagement, de sacrifices 
                et de lutte au service de l'éducation et de la promotion des valeurs islamiques.
              </p>
              <div class="message-signature">
                <strong>El hadj Youssouf BAMBA</strong>
                <span>Amir (Président national) de l'AEEMCI</span>
              </div>
            </div>

            <div class="vision-grid">
              <div class="vision-card">
                <div class="vision-icon">🎯</div>
                <h3>Vision</h3>
                <p>AEEMCI : un esprit, des leaders !</p>
              </div>
              <div class="vision-card">
                <div class="vision-icon">💡</div>
                <h3>Slogan</h3>
                <p>Pour une identité islamique !</p>
              </div>
            </div>

            <div class="programs-section">
              <h3>Programmes Actuels</h3>
              <div class="programs-grid">
                <div class="program-card">
                  <div class="program-icon">📚</div>
                  <h4>SENAFOI</h4>
                  <p>Séminaire National de Formation</p>
                </div>
                <div class="program-card">
                  <div class="program-icon">🕌</div>
                  <h4>CIMA</h4>
                  <p>Conseil des Imams</p>
                </div>
                <div class="program-card">
                  <div class="program-icon">👩</div>
                  <h4>JMA</h4>
                  <p>Journée de la Militante</p>
                </div>
                <div class="program-card">
                  <div class="program-icon">❤️</div>
                  <h4>Actions Sociales</h4>
                  <p>Initiatives humanitaires</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card">
          <div class="cta-icon">🌟</div>
          <h2 class="cta-title">Rejoignez l'Histoire de l'AEEMCI</h2>
          <p class="cta-text">
            Faites partie de cette histoire et contribuez à façonner l'avenir de notre association !
          </p>
          <div class="cta-buttons">
            <button class="btn btn-primary">
              <span>Devenir membre</span>
            </button>
            <button class="btn btn-secondary">
              <span>En savoir plus</span>
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const currentYear = computed(() => new Date().getFullYear())

const periods = ref([
  { year: '1972', label: 'Naissance' },
  { year: '1975', label: 'Reconnaissance' },
  { year: '2015', label: '40 ans' },
  { year: currentYear.value, label: 'Aujourd\'hui' }
])

const activePeriod = ref(0)

const scrollToPeriod = (index) => {
  const element = document.getElementById(`period-${index}`)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

let observer = null

onMounted(() => {
  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.id
        const index = parseInt(id.split('-')[1])
        activePeriod.value = index
        entry.target.classList.add('visible')
      }
    })
  }, { threshold: 0.3 })

  periods.value.forEach((_, index) => {
    const element = document.getElementById(`period-${index}`)
    if (element) observer.observe(element)
  })
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');

:root {
  --primary: var(--green);
  --primary-dark: var(--green);
  --secondary: var(--gold);
  --accent: var(--gold);
  --text-primary: var(--ink);
  --text-secondary: var(--ink-soft);
  --background: var(--surface);
}

.histoire-page {
  background: var(--background);
  min-height: 100vh;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* Hero */
.histoire-hero {
  position: relative;
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  padding: 8rem 2rem;
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

.hero-glow-1, .hero-glow-2 {
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
  background: radial-gradient(circle, var(--gold), transparent 70%);
  animation: float-glow 20s ease-in-out infinite;
}

.hero-glow-2 {
  bottom: -300px;
  right: -200px;
  background: radial-gradient(circle, var(--gold), transparent 70%);
  animation: float-glow 25s ease-in-out infinite reverse;
}

@keyframes float-glow {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(50px, -50px); }
}

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
  0% { transform: translateY(0) rotate(0deg); }
  100% { transform: translateY(100vh) rotate(360deg); }
}

.hero-content {
  position: relative;
  z-index: 1;
  max-width: 900px;
  margin: 0 auto;
}

.hero-icon {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-15px); }
}

.hero-title {
  font-size: 4rem;
  font-weight: 900;
  margin-bottom: 1.5rem;
  font-family: var(--display);
}

.highlight {
  background: linear-gradient(135deg, var(--gold), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  font-size: 1.4rem;
  opacity: 0.95;
  margin-bottom: 3rem;
}

.hero-stats {
  display: flex;
  justify-content: center;
  gap: 3rem;
}

.hero-stat {
  text-align: center;
}

.stat-value {
  font-size: 3rem;
  font-weight: 900;
  margin-bottom: 0.5rem;
}

.stat-label {
  font-size: 1rem;
  opacity: 0.9;
  text-transform: uppercase;
}

/* Intro */
.intro-section {
  padding: 6rem 0;
}

.intro-card {
  background: white;
  border-radius: 0;
  padding: 4rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1,132,4,0.1);
  position: relative;
  overflow: hidden;
}

.intro-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--green), var(--gold), var(--gold));
}

.intro-icon {
  font-size: 4rem;
  text-align: center;
  margin-bottom: 2rem;
}

.intro-quote {
  position: relative;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 3rem;
  border-radius: 0;
  border-left: 4px solid var(--green);
  margin-bottom: 3rem;
}

.quote-mark-large {
  position: absolute;
  top: 1rem;
  left: 1rem;
  font-size: 6rem;
  color: rgba(1,132,4,0.1);
  font-weight: 900;
  line-height: 1;
}

.intro-quote p {
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
  background: linear-gradient(90deg, var(--green), var(--gold));
}

.quote-author span {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--green);
  font-style: normal;
}

.intro-text p {
  font-size: 1.15rem;
  line-height: 1.8;
  color: var(--text-secondary);
  margin-bottom: 1rem;
}

/* Timeline Nav */
.timeline-nav-section {
  padding: 3rem 0;
  background: white;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: var(--shadow);
}

.timeline-nav {
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.timeline-nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.nav-year {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border: 2px solid rgba(1,132,4,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 0.75rem;
  transition: all 0.3s ease;
}

.timeline-nav-item:hover .nav-year {
  transform: scale(1.1);
  box-shadow: var(--shadow);
}

.timeline-nav-item.active .nav-year {
  background: linear-gradient(135deg, var(--green), var(--green));
  border-color: var(--green);
  color: white;
  transform: scale(1.15);
}

.nav-label {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-secondary);
}

.timeline-nav-item.active .nav-label {
  color: var(--green);
}

/* Periods */
.periods-section {
  padding: 4rem 0;
}

.period-block {
  background: white;
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1,132,4,0.1);
  margin-bottom: 4rem;
  opacity: 0;
  transform: translateY(50px);
  transition: all 0.8s ease;
}

.period-block.visible {
  opacity: 1;
  transform: translateY(0);
}

.period-alt {
  background: linear-gradient(135deg, rgba(1,132,4,0.02), rgba(251,146,60,0.02));
}

.period-header {
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  padding: 3rem;
  display: flex;
  align-items: center;
  gap: 2rem;
}

.period-year-badge {
  width: 100px;
  height: 100px;
  background: rgba(255,255,255,0.15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 900;
  flex-shrink: 0;
}

.period-title {
  font-size: 2.5rem;
  font-weight: 900;
  margin: 0;
}

.period-gallery {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  padding: 2rem;
}

.gallery-item {
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
}

.gallery-item:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
}

.gallery-item img {
  width: 100%;
  height: 250px;
  object-fit: cover;
}

.gallery-caption {
  background: var(--green);
  color: white;
  padding: 0.75rem;
  text-align: center;
  font-weight: 600;
}

.period-content {
  padding: 3rem;
}

.content-text {
  font-size: 1.1rem;
  line-height: 1.8;
  color: var(--text-secondary);
  margin-bottom: 2rem;
}

/* Highlight Box */
.highlight-box {
  background: linear-gradient(135deg, rgba(251,191,36,0.1), rgba(251,146,60,0.1));
  padding: 2rem;
  border-radius: 0;
  border-left: 4px solid var(--gold);
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.highlight-icon {
  font-size: 3rem;
  flex-shrink: 0;
}

.highlight-box p {
  font-size: 1.3rem;
  font-weight: 600;
  color: var(--text-primary);
  font-style: italic;
  margin: 0;
}

/* Cards */
.founders-card, .milestone-box, .message-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 2rem;
  border-radius: 0;
  border-left: 4px solid var(--green);
  margin-bottom: 2rem;
}

.founders-card h3, .milestone-box h3, .message-card h3 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 1rem;
}

.milestone-date {
  font-size: 2rem;
  font-weight: 900;
  color: var(--gold);
  margin-bottom: 1rem;
}

.message-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.message-icon {
  font-size: 2.5rem;
}

.message-signature {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 2px solid rgba(1,132,4,0.2);
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.message-signature strong {
  font-size: 1.2rem;
  color: var(--green);
}

/* Assemblies */
.assemblies-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 2rem;
}

.assembly-item {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  background: rgba(1,132,4,0.05);
  padding: 1.5rem;
  border-radius: 0;
}

.assembly-year {
  width: 80px;
  height: 80px;
  background: var(--green);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 900;
  flex-shrink: 0;
}

/* Stats */
.stats-showcase {
  margin: 3rem 0;
}

.stats-showcase h3 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 2rem;
  text-align: center;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 2rem;
  border-radius: 0;
  text-align: center;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
}

.stat-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--green);
  margin-bottom: 0.5rem;
}

.stat-label {
  font-size: 0.95rem;
  color: var(--text-secondary);
  font-weight: 600;
}

/* Achievements */
.achievements-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
  margin-top: 2rem;
}

.achievement-card {
  background: white;
  padding: 2rem;
  border-radius: 0;
  border: 2px solid rgba(1,132,4,0.1);
  transition: all 0.3s ease;
}

.achievement-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.achievement-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.achievement-card h4 {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--green);
  margin-bottom: 0.75rem;
}

/* Vision */
.vision-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
  margin: 3rem 0;
}

.vision-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 2rem;
  border-radius: 0;
  text-align: center;
  border: 2px solid rgba(1,132,4,0.1);
}

.vision-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.vision-card h3 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 1rem;
}

/* Programs */
.programs-section {
  margin-top: 3rem;
}

.programs-section h3 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 2rem;
}

.programs-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.program-card {
  background: white;
  padding: 1.5rem;
  border-radius: 0;
  text-align: center;
  border: 2px solid rgba(1,132,4,0.1);
  transition: all 0.3s ease;
}

.program-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.program-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.program-card h4 {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--green);
  margin-bottom: 0.75rem;
}

.program-card p {
  font-size: 0.95rem;
  color: var(--text-secondary);
  margin: 0;
}

/* CTA */
.cta-section {
  padding: 6rem 0;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
}

.cta-card {
  background: white;
  border-radius: 0;
  padding: 4rem;
  text-align: center;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1,132,4,0.1);
  position: relative;
  overflow: hidden;
}

.cta-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--green), var(--gold), var(--gold));
}

.cta-icon {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
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
}

.cta-buttons {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  padding: 1rem 2rem;
  border-radius: 0;
  font-size: 1.1rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
}

.btn-primary {
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  box-shadow: var(--shadow);
}

.btn-primary:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}

.btn-secondary {
  background: white;
  color: var(--green);
  border: 2px solid var(--green);
}

.btn-secondary:hover {
  background: var(--green);
  color: white;
  transform: translateY(-4px);
}

/* Responsive */
@media (max-width: 1024px) {
  .stats-grid, .programs-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .achievements-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .histoire-hero {
    padding: 4rem 1.5rem;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-stats {
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .period-gallery {
    grid-template-columns: 1fr;
  }
  
  .stats-grid, .vision-grid, .programs-grid {
    grid-template-columns: 1fr;
  }
  
  .timeline-nav {
    overflow-x: auto;
    justify-content: flex-start;
    padding-bottom: 1rem;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .period-title {
    font-size: 1.8rem;
  }
  
  .cta-buttons {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>