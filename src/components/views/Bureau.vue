<template>
  <div class="bureau-page">
    <!-- Hero Section -->
    <section class="bureau-hero">
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
        <div class="hero-icon">👥</div>
        <h1 class="hero-title">
          <span class="highlight">C</span>omité Exécutif
        </h1>
        <p class="hero-subtitle">
          L'équipe dirigeante de l'AEEMCI au service de la jeunesse musulmane
        </p>
        <div class="hero-badge">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          <span>Mandat 2025-2026</span>
        </div>
      </div>
    </section>

    <!-- Mission Statement -->
    <section class="mission-section">
      <div class="container">
        <div class="mission-card">
          <div class="mission-icon">🎯</div>
          <div class="mission-content">
            <h2 class="mission-title">Notre Engagement</h2>
            <blockquote class="mission-quote">
              <div class="quote-mark-large">"</div>
              <p>
                Notre engagement est de servir la communauté estudiantine musulmane avec dévouement, 
                intégrité et excellence, tout en préservant nos valeurs islamiques. Le Bureau Exécutif 
                National travaille sans relâche pour répondre aux besoins des élèves et étudiants musulmans 
                de Côte d'Ivoire.
              </p>
              <div class="quote-author">
                <div class="author-line"></div>
                <span>Bureau Exécutif AEEMCI</span>
              </div>
            </blockquote>
          </div>
        </div>
      </div>
    </section>

    <!-- Organigramme Section -->
    <section class="organigramme-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">📊</span>
            Structure du Comité Exécutif
          </h2>
          <p class="section-subtitle">
            L'organisation hiérarchique du Bureau Exécutif National
          </p>
        </div>

        <div class="org-visual">
          <!-- Niveau Amir -->
          <div class="org-tier tier-amir">
            <div class="org-position amir">
              <div class="position-badge">Amir</div>
              <div class="position-icon">👑</div>
            </div>
          </div>

          <div class="org-connector main-connector"></div>

          <!-- Niveau Vice-Amirates -->
          <div class="org-tier tier-vice">
            <div class="org-position vice">
              <div class="position-badge">1er Vice-Amir</div>
              <div class="position-subtitle">Affaires Académiques et Formation</div>
            </div>
            <div class="org-position vice">
              <div class="position-badge">2ème Vice-Amir</div>
              <div class="position-subtitle">Relations Extérieures</div>
            </div>
            <div class="org-position vice">
              <div class="position-badge">Vice-Amirate</div>
              <div class="position-subtitle">Projets et Développement</div>
            </div>
          </div>

          <div class="org-connector secondary-connector"></div>

          <!-- Niveau Secrétariats -->
          <div class="org-tier tier-secretariats">
            <div class="org-position secretariat">
              <div class="position-badge">SENAFAD</div>
              <div class="position-subtitle">Affaires Administratives</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENAFI</div>
              <div class="position-subtitle">Affaires Financières</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENAFOCI</div>
              <div class="position-subtitle">Formation, Culture & Idéologie</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENACEF</div>
              <div class="position-subtitle">Cellules Féminines</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENASIP</div>
              <div class="position-subtitle">Affaires Sociales & Insertion</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENAES</div>
              <div class="position-subtitle">Environnement & Sport</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENAMO</div>
              <div class="position-subtitle">Mobilisation & Organisation</div>
            </div>
            <div class="org-position secretariat">
              <div class="position-badge">SENACREX</div>
              <div class="position-subtitle">Communication & Relations Ext.</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bureau Exécutif Section -->
    <section class="bureau-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">⭐</span>
            Bureau Exécutif 2025-2026
          </h2>
        </div>

        <!-- Amir -->
        <div class="president-showcase">
          <div class="president-card">
            <div class="president-visual">
              <div class="president-photo">
                <img 
                  src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758277082/aeemci_photo_1758277078928_IMG_0537.jpeg.jpg.jpg" 
                  alt="Youssouf BAMBA - Amir"
                />
                <div class="photo-overlay"></div>
              </div>
              <div class="president-badge">
                <div class="badge-icon">👑</div>
                <div class="badge-text">Amir</div>
              </div>
            </div>
            
            <div class="president-details">
              <h3 class="president-name">Youssouf BAMBA</h3>
              <div class="president-title">Amir (Président National)</div>
              <div class="president-mandate">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Mandat 2025-2026</span>
              </div>
              
              <div class="president-bio">
                <p>
                  Diplômé en Masteur Professionnel en Informatique et Génie Logiciel de l'Institut 
                  Voltaire d'enseignement Technique et Professionnel de Marcory (IVESTP), Bamba Youssouf 
                  a rejoint l'AEEMCI dès ses années de lycée. Son parcours au sein de l'association est 
                  marqué par un engagement constant et une vision claire pour le développement de la 
                  jeunesse musulmane ivoirienne.
                </p>
              </div>
              
              <div class="president-objective">
                <div class="objective-header">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  <strong>Objectif Général</strong>
                </div>
                <p>
                  "Péréniser les acquis et renforcer les compétences afin d'assurer le positionnement 
                  stratégique de l'AEEMCI"
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Membres du Bureau -->
        <div class="bureau-grid">
          <!-- Vice-Amirs et Secrétaires -->
          <div 
            v-for="(member, index) in bureauMembers" 
            :key="index"
            class="bureau-member-card"
            :style="{ animationDelay: `${index * 0.05}s` }"
          >
            <div class="member-photo-container">
              <img 
                :src="member.photo" 
                :alt="member.name"
                class="member-photo"
              />
              <div class="photo-shine"></div>
            </div>
            <div class="member-info">
              <div class="member-position-badge">{{ member.position }}</div>
              <h4 class="member-name">{{ member.name }}</h4>
              <p class="member-role">{{ member.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Valeurs Section -->
    <section class="values-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">💎</span>
            Nos Valeurs
          </h2>
        </div>

        <div class="values-grid">
          <div 
            v-for="(value, index) in values" 
            :key="index"
            class="value-card"
            :style="{ animationDelay: `${index * 0.1}s` }"
          >
            <div class="value-icon">{{ value.icon }}</div>
            <h3 class="value-title">{{ value.title }}</h3>
            <p class="value-description">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
      <div class="container">
        <div class="contact-card">
          <div class="contact-icon">📞</div>
          <h2 class="contact-title">Contacter le Bureau Exécutif</h2>
          <p class="contact-subtitle">
            Notre équipe est à votre écoute pour répondre à toutes vos questions
          </p>
          <div class="contact-grid">
            <div class="contact-item">
              <div class="contact-item-icon">📱</div>
              <h3>Téléphone</h3>
              <p>+225 27 22 43 47 58</p>
              <p>+225 07 07 07 07 07</p>
            </div>
            <div class="contact-item">
              <div class="contact-item-icon">✉️</div>
              <h3>Email</h3>
              <p>bureau.executif@aeemci.org</p>
              <p>president@aeemci.org</p>
            </div>
            <div class="contact-item">
              <div class="contact-item-icon">📍</div>
              <h3>Adresse</h3>
              <p>Siège de l'AEEMCI</p>
              <p>Mosquée An Nour de l'AEEMCI</p>
              <p>Cocody Riviera 2, Abidjan</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Membres du bureau dans l'ordre hiérarchique exact
const bureauMembers = ref([
  // Vice-Amirs
  {
    name: 'Soro Souleymane',
    position: '1er Vice-Amir',
    role: 'Affaires Académiques et Formation',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758277228/aeemci_photo_1758277226378_soro.png.png'
  },
  {
    name: 'Chérif Mamadou',
    position: '2ème Vice-Amir',
    role: 'Relations Extérieures',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758277439/aeemci_photo_1758277438595_cherif.png.png'
  },
  {
    name: 'Vice-Amirate',
    position: 'Vice-Amirate',
    role: 'Projets et Développement',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758352913/aeemci_photo_1758352911506_amirate.png.png'
  },
  
  // Secrétariats Nationaux (dans l'ordre)
  {
    name: 'Ahmed',
    position: 'SENAFAD',
    role: 'Secrétariat National chargé des Affaires Administratives',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758278073/aeemci_photo_1758278070827_ahmed.png.png'
  },
  {
    name: 'Bema',
    position: 'SENAFI',
    role: 'Secrétariat National chargé des Affaires Financières',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758278118/aeemci_photo_1758278117340_bema.png.png'
  },
  {
    name: 'Thierno',
    position: 'SENAFOCI',
    role: 'Secrétariat National chargé de la Formation de la Culture et des questions Idéologiques',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758278454/aeemci_photo_1758278452523_thierno.png.png'
  },
  {
    name: 'Yel',
    position: 'SENACEF',
    role: 'Secrétariat National chargé des Cellules Féminines',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282658/aeemci_photo_1758282655078_yel.png.png'
  },
  {
    name: 'Tao',
    position: 'SENASIP',
    role: 'Secrétariat National chargé des Affaires Sociales et de l\'Insertion Professionnelle',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282736/aeemci_photo_1758282734186_tao.png.png'
  },
  {
    name: 'Bambara',
    position: 'SENAES',
    role: 'Secrétariat National chargé de l\'Environnement et du Sport',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282778/aeemci_photo_1758282776626_bambara.png.png'
  },
  {
    name: 'Muba',
    position: 'SENAMO',
    role: 'Secrétariat National chargé de la Mobilisation et de l\'Organisation',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282840/aeemci_photo_1758282837880_muba.png.png'
  },
  {
    name: 'Madous',
    position: 'SENACREX',
    role: 'Secrétariat National chargé de la Communication et des Relations Extérieures',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282884/aeemci_photo_1758282883177_madous.png.png'
  },
  
  // Adjoints
  {
    name: 'Baily',
    position: 'Adjoint SENAFAD',
    role: 'Affaires Administratives',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282914/aeemci_photo_1758282913482_baily.png.png'
  },
  {
    name: 'Jacob',
    position: 'Adjoint SENAFI',
    role: 'Affaires Financières',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283046/aeemci_photo_1758283043884_jacob.png.png'
  },
  {
    name: 'Mariam',
    position: 'Adjointe SENAFOCI',
    role: 'Formation, Culture et Idéologie',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283085/aeemci_photo_1758283084400_mariam.png.png'
  },
  {
    name: 'Cissé',
    position: 'Adjointe SENACEF',
    role: 'Cellules Féminines',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283450/aeemci_photo_1758283447885_cisse.png.png'
  },
  {
    name: 'Sanogo',
    position: 'Adjoint SENASIP',
    role: 'Affaires Sociales et Insertion',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283500/aeemci_photo_1758283499390_sanogo.png.png'
  },
  {
    name: 'Garanke',
    position: 'Adjoint SENAES',
    role: 'Environnement et Sport',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283589/aeemci_photo_1758283587775_garanke.png.png'
  },
  {
    name: 'Ibrahim',
    position: 'Adjoint SENAMO',
    role: 'Mobilisation et Organisation',
    photo: 'http://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283621/aeemci_photo_1758283619915_ibrahim.png.png'
  },
  {
    name: 'Fane',
    position: 'Adjoint SENACREX',
    role: 'Communication et Relations Extérieures',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283652/aeemci_photo_1758283651569_fane.png.png'
  },
  {
    name: 'Djim',
    position: 'Adjoint SENACREX',
    role: 'Communication et Relations Extérieures',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283679/aeemci_photo_1758283678012_djim.png.png'
  },
  {
    name: 'CREX',
    position: 'Adjoint SENACREX',
    role: 'Communication et Relations Extérieures',
    photo: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283725/aeemci_photo_1758283724443_crex.png.png'
  }
])

// Valeurs
const values = ref([
  {
    icon: '🤲',
    title: 'Dévouement',
    description: 'Un engagement total au service de la communauté estudiantine musulmane'
  },
  {
    icon: '⚖️',
    title: 'Intégrité',
    description: 'Transparence et honnêteté dans toutes nos actions et décisions'
  },
  {
    icon: '🎯',
    title: 'Excellence',
    description: 'Recherche constante de la qualité dans nos programmes et initiatives'
  },
  {
    icon: '🤝',
    title: 'Fraternité',
    description: 'Cultiver l\'esprit de solidarité et d\'entraide entre tous les membres'
  },
  {
    icon: '📚',
    title: 'Savoir',
    description: 'Promotion de l\'éducation et du développement des compétences'
  },
  {
    icon: '🌍',
    title: 'Ouverture',
    description: 'Dialogue et collaboration avec toutes les parties prenantes'
  }
])
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');

/* ===== VARIABLES ===== */
:root {
  --primary: var(--green);
  --primary-dark: var(--green);
  --primary-light: var(--green);
  --secondary: var(--gold);
  --accent: var(--gold);
  --text-primary: var(--ink);
  --text-secondary: var(--ink-soft);
  --background: var(--surface);
}

/* ===== BASE ===== */
.bureau-page {
  background: var(--background);
  min-height: 100vh;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* ===== HERO SECTION ===== */
.bureau-hero {
  position: relative;
  background: linear-gradient(135deg, var(--green), var(--green));
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
  font-family: var(--display);
}

.highlight {
  background: linear-gradient(135deg, var(--gold), var(--gold));
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
  border-radius: 0;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.hero-badge svg {
  color: var(--gold);
}

/* ===== MISSION SECTION ===== */
.mission-section {
  padding: 6rem 0;
}

.mission-card {
  background: white;
  border-radius: 0;
  padding: 4rem;
  box-shadow: var(--shadow);
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
  background: linear-gradient(90deg, var(--green), var(--gold), var(--gold));
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
  border-radius: 0;
  border-left: 4px solid var(--green);
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
  background: linear-gradient(90deg, var(--green), var(--gold));
}

.quote-author span {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--green);
  font-style: normal;
}

/* ===== SECTION HEADERS ===== */
.section-header {
  text-align: center;
  margin-bottom: 4rem;
}

.section-title {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.title-icon {
  font-size: 2.5rem;
}

.section-subtitle {
  font-size: 1.1rem;
  color: var(--text-secondary);
  max-width: 600px;
  margin: 0 auto;
}

/* ===== ORGANIGRAMME SECTION ===== */
.organigramme-section {
  padding: 6rem 0;
  background: white;
}

.org-visual {
  max-width: 1200px;
  margin: 0 auto;
}

.org-tier {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.org-position {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  border: 2px solid rgba(1, 132, 4, 0.2);
  border-radius: 0;
  padding: 1.5rem;
  text-align: center;
  min-width: 180px;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.org-position:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.org-position.amir {
  background: linear-gradient(135deg, var(--green), var(--green));
  border-color: var(--green);
  color: white;
  min-width: 250px;
}

.org-position.vice {
  flex: 1;
  min-width: 200px;
  max-width: 280px;
}

.org-position.secretariat {
  flex: 1;
  min-width: 160px;
  max-width: 200px;
}

.position-badge {
  font-size: 1.1rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  color: var(--green);
}

.org-position.amir .position-badge {
  color: white;
}

.position-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.position-subtitle {
  font-size: 0.85rem;
  color: var(--text-secondary);
  line-height: 1.4;
}

.org-position.amir .position-subtitle {
  color: rgba(255, 255, 255, 0.9);
}

.org-connector {
  height: 40px;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 2rem;
}

.org-connector::before {
  content: '';
  position: absolute;
  width: 2px;
  height: 100%;
  background: linear-gradient(to bottom, var(--green), var(--gold));
}

.org-connector::after {
  content: '';
  position: absolute;
  bottom: 0;
  width: 0;
  height: 0;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-top: 12px solid var(--gold);
}

/* ===== BUREAU SECTION ===== */
.bureau-section {
  padding: 6rem 0;
}

/* President Showcase */
.president-showcase {
  margin-bottom: 4rem;
}

.president-card {
  background: white;
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1, 132, 4, 0.1);
  display: grid;
  grid-template-columns: 400px 1fr;
  gap: 3rem;
  position: relative;
}

.president-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--green), var(--gold), var(--gold));
}

.president-visual {
  position: relative;
}

.president-photo {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}

.president-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.photo-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 50%;
  background: linear-gradient(to top, rgba(1, 132, 4, 0.9), transparent);
}

.president-badge {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  background: linear-gradient(135deg, var(--gold), var(--gold));
  padding: 1rem 2rem;
  border-radius: 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  box-shadow: var(--shadow);
}

.badge-icon {
  font-size: 1.5rem;
}

.badge-text {
  font-weight: 800;
  font-size: 1.1rem;
  color: white;
}

.president-details {
  padding: 3rem 3rem 3rem 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.president-name {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--green);
  margin-bottom: 0.5rem;
}

.president-title {
  font-size: 1.3rem;
  color: var(--text-secondary);
  font-weight: 600;
  margin-bottom: 1rem;
}

.president-mandate {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--gold);
  font-weight: 700;
  margin-bottom: 2rem;
}

.president-mandate svg {
  color: var(--gold);
}

.president-bio {
  margin-bottom: 2rem;
}

.president-bio p {
  font-size: 1.1rem;
  line-height: 1.8;
  color: var(--text-secondary);
  margin: 0;
}

.president-objective {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 1.5rem;
  border-radius: 0;
  border-left: 4px solid var(--green);
}

.objective-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
  color: var(--green);
}

.objective-header svg {
  color: var(--gold);
  flex-shrink: 0;
}

.president-objective p {
  font-size: 1.1rem;
  line-height: 1.7;
  color: var(--text-secondary);
  font-style: italic;
  margin: 0;
}

/* Bureau Grid */
.bureau-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
}

.bureau-member-card {
  background: white;
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1, 132, 4, 0.1);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  opacity: 0;
  animation: slideInUp 0.6s ease-out forwards;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.bureau-member-card:hover {
  transform: translateY(-12px);
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.member-photo-container {
  position: relative;
  height: 280px;
  overflow: hidden;
}

.member-photo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.bureau-member-card:hover .member-photo {
  transform: scale(1.1);
}

.photo-shine {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.6s;
}

.bureau-member-card:hover .photo-shine {
  left: 100%;
}

.member-info {
  padding: 1.5rem;
}

.member-position-badge {
  display: inline-block;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.1), rgba(251, 146, 60, 0.1));
  color: var(--green);
  padding: 0.4rem 0.9rem;
  border-radius: 0;
  font-size: 0.8rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.member-name {
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

.member-role {
  font-size: 0.9rem;
  color: var(--text-secondary);
  line-height: 1.5;
  margin: 0;
}

/* ===== VALUES SECTION ===== */
.values-section {
  padding: 6rem 0;
  background: white;
}

.values-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.value-card {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.02), rgba(251, 146, 60, 0.02));
  padding: 2.5rem;
  border-radius: 0;
  border: 2px solid rgba(1, 132, 4, 0.1);
  text-align: center;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  opacity: 0;
  animation: slideInUp 0.6s ease-out forwards;
}

.value-card:hover {
  transform: translateY(-12px) rotate(-2deg);
  border-color: var(--green);
  box-shadow: var(--shadow);
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
}

.value-icon {
  font-size: 4rem;
  margin-bottom: 1.5rem;
  filter: drop-shadow(0 4px 8px rgba(1, 132, 4, 0.2));
}

.value-title {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.value-description {
  font-size: 1rem;
  line-height: 1.7;
  color: var(--text-secondary);
  margin: 0;
}

/* ===== CONTACT SECTION ===== */
.contact-section {
  padding: 6rem 0;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
}

.contact-card {
  background: white;
  border-radius: 0;
  padding: 4rem;
  box-shadow: var(--shadow);
  text-align: center;
  border: 2px solid rgba(1, 132, 4, 0.1);
  position: relative;
  overflow: hidden;
}

.contact-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--green), var(--gold), var(--gold));
}

.contact-icon {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  animation: pulse 2s ease-in-out infinite;
}

.contact-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.contact-subtitle {
  font-size: 1.1rem;
  color: var(--text-secondary);
  margin-bottom: 3rem;
}

.contact-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.contact-item {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  padding: 2rem;
  border-radius: 0;
  border: 2px solid rgba(1, 132, 4, 0.1);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.contact-item:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.contact-item-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.contact-item h3 {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 1rem;
}

.contact-item p {
  font-size: 1rem;
  color: var(--text-secondary);
  margin: 0.5rem 0;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1200px) {
  .bureau-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 1024px) {
  .president-card {
    grid-template-columns: 1fr;
  }

  .president-visual {
    height: 400px;
  }

  .president-details {
    padding: 3rem;
  }

  .bureau-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .values-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .contact-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .bureau-hero {
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

  .section-title {
    font-size: 2rem;
  }

  .org-position {
    min-width: 140px;
  }

  .president-name {
    font-size: 2rem;
  }

  .bureau-grid {
    grid-template-columns: 1fr;
  }

  .values-grid {
    grid-template-columns: 1fr;
  }

  .contact-title {
    font-size: 2rem;
  }

  .contact-card {
    padding: 2rem;
  }
}

@media (max-width: 480px) {
  .hero-icon {
    font-size: 3rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .section-title {
    font-size: 1.75rem;
    flex-direction: column;
    gap: 0.5rem;
  }

  .org-position {
    min-width: 100%;
  }

  .president-visual {
    height: 300px;
  }

  .contact-icon {
    font-size: 3rem;
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