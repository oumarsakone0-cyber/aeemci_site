<template>
  <div class="charter-page">
    <!-- Hero Section -->
    <section class="charter-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
      </div>
      
      <div class="hero-content">
        <div class="hero-icon">📜</div>
        <h1 class="hero-title">
          <span class="highlight">C</span>harte de l'AEEMCI
        </h1>
        <p class="hero-subtitle">
          Les valeurs et principes qui guident notre association depuis sa création
        </p>
        <div class="hero-badge">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          <span>Document fondateur</span>
        </div>
      </div>
    </section>

    <!-- Mission Statement -->
    <section class="mission-section">
      <div class="container">
        <div class="mission-card">
          <div class="mission-icon">🎯</div>
          <div class="mission-content">
            <h2 class="mission-title">Notre Mission</h2>
            <blockquote class="mission-quote">
              <div class="quote-mark-large">"</div>
              <p>
                L'AEEMCI est une structure islamique en Côte d'Ivoire, qui contribue à la 
                formation religieuse et managériale des élèves et étudiants musulmans, pour 
                l'émergence d'une élite musulmane consciente des valeurs républicaines et de 
                sa responsabilité communautaire.
              </p>
              <div class="quote-author">
                <div class="author-line"></div>
                <span>Mission de l'AEEMCI</span>
              </div>
            </blockquote>
          </div>
        </div>
      </div>
    </section>

    <!-- Articles Section -->
    <section class="articles-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">📖</span>
            Articles de la Charte
          </h2>
          <p class="section-subtitle">
            13 articles fondamentaux qui définissent notre identité et nos engagements
          </p>
        </div>

        <div class="articles-grid">
          <div 
            v-for="(article, index) in articles" 
            :key="article.id"
            class="article-card"
            :style="{ animationDelay: `${index * 0.1}s` }"
          >
            <div class="article-number">
              <span class="number-text">{{ index + 1 }}</span>
              <div class="number-glow"></div>
            </div>
            
            <div class="article-content">
              <h3 class="article-title">{{ article.title }}</h3>
              <p class="article-text">{{ article.content }}</p>
            </div>

            <div class="article-decoration"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">⭐</span>
            Nos Valeurs Fondamentales
          </h2>
        </div>

        <div class="values-grid">
          <div 
            v-for="(value, index) in values" 
            :key="value.id"
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

    <!-- Commitment Section -->
    <section class="commitment-section">
      <div class="container">
        <div class="commitment-card">
          <div class="commitment-icon">🤝</div>
          <h2 class="commitment-title">Notre Engagement</h2>
          <p class="commitment-text">
            L'AEEMCI reste un édifice à bâtir où chaque génération doit poser sa pierre 
            au-dessus de la précédente. Elle constitue notre fierté et c'est en elle que 
            nous réalisons notre identité islamique.
          </p>
          <div class="commitment-stats">
            <div class="stat-item">
              <div class="stat-number">50+</div>
              <div class="stat-label">Années d'histoire</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
              <div class="stat-number">30k+</div>
              <div class="stat-label">Membres actifs</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
              <div class="stat-number">1000+</div>
              <div class="stat-label">Sections</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Signature Section -->
    <section class="signature-section">
      <div class="container">
        <div class="signature-card">
          <div class="signature-seal">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <div class="seal-glow"></div>
          </div>
          <div class="signature-content">
            <p class="signature-text">Document établi et adopté par l'Assemblée Générale</p>
            <p class="signature-date">Fait à Abidjan, le 31 décembre 2024</p>
            <div class="signature-line"></div>
            <p class="signature-authority">Le Comité Exécutif de l'AEEMCI</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Articles de la charte
const articles = ref([
  {
    id: 1,
    title: 'Article 1 : Notre foi',
    content: 'Allah est notre Seigneur et le prophète Mouhammad (SAW) est son Messager et notre Prophète.'
  },
  {
    id: 2,
    title: 'Article 2 : Nos références',
    content: 'Le Coran et la Sunnah du Prophète Muhammad (SAW) restent nos textes de référence et de recours.'
  },
  {
    id: 3,
    title: 'Article 3 : Ouverture jurisprudentielle',
    content: 'Toute position ou pratique qui trouverait une justification dans le Coran, la Sunnah, les Écoles Juridiques est acceptée.'
  },
  {
    id: 4,
    title: 'Article 4 : Neutralité idéologique',
    content: 'L\'AEEMCI ne sera aucunement utilisée comme vecteur d\'une idéologie particulière quelle qu\'elle soit.'
  },
  {
    id: 5,
    title: 'Article 5 : Habillement',
    content: 'L\'habillement islamique est celui que l\'AEEMCI adopte.'
  },
  {
    id: 6,
    title: 'Article 6 : Notre credo',
    content: 'La tolérance et le respect des valeurs d\'autrui, la sincérité de l\'engagement militant, l\'exemplarité dans l\'adoration, dans la fraternité et dans la solidarité sont notre credo.'
  },
  {
    id: 7,
    title: 'Article 7 : Défense des valeurs',
    content: 'L\'Aeemciste respecte les valeurs et symboles islamiques et doit les défendre partout où besoin se fera.'
  },
  {
    id: 8,
    title: 'Article 8 : Vertus cardinales',
    content: 'La tempérance, le pardon, la discipline et la modération sont des valeurs qui l\'habitent.'
  },
  {
    id: 9,
    title: 'Article 9 : Persévérance',
    content: 'L\'Aeemciste n\'abandonne jamais pour cause de difficulté, il doit persévérer par la prière et le travail.'
  },
  {
    id: 10,
    title: 'Article 10 : Droits des membres',
    content: 'L\'Aeemciste a droit à une formation islamique et académique. Au respect de son intégrité physique et morale, de son honneur et de sa dignité. La structure s\'engage à défendre tout membre atteint dans l\'un de ces domaines.'
  },
  {
    id: 11,
    title: 'Article 11 : Devoirs des membres',
    content: 'L\'Aeemciste a à l\'égard de sa structure un devoir de loyauté et de sincérité. Les imams et les responsables de l\'AEEMCI sont des symboles et valeurs pour la structure. Leurs avis sont à respecter.'
  },
  {
    id: 12,
    title: 'Article 12 : Une famille unie',
    content: 'Les membres du GROUPE AEEMCI (AEEMCI et l\'Amicale des Anciens Aeemcistes (3A)) forment une famille unie. Aînés et cadets, à travers une collaboration harmonieuse, contribuent à l\'édification d\'une société nouvelle. Une société mue par les nobles principes islamiques.'
  },
  {
    id: 13,
    title: 'Article 13 : Notre héritage',
    content: 'L\'AEEMCI reste un édifice à bâtir où chaque génération doit poser sa pierre au-dessus de la précédente. Elle constitue notre fierté et c\'est en elle que nous réalisons notre identité islamique.'
  }
])

// Valeurs fondamentales
const values = ref([
  {
    id: 1,
    icon: '🤲',
    title: 'Foi et Spiritualité',
    description: 'Enracinement dans les valeurs islamiques et pratique sincère de la religion'
  },
  {
    id: 2,
    icon: '📚',
    title: 'Excellence Académique',
    description: 'Recherche du savoir et de l\'excellence dans tous les domaines'
  },
  {
    id: 3,
    icon: '🤝',
    title: 'Fraternité et Solidarité',
    description: 'Entraide entre membres et engagement communautaire fort'
  },
  {
    id: 4,
    icon: '⚖️',
    title: 'Tolérance et Respect',
    description: 'Respect des différences et dialogue constructif avec tous'
  },
  {
    id: 5,
    icon: '💪',
    title: 'Persévérance',
    description: 'Détermination et engagement continu malgré les difficultés'
  },
  {
    id: 6,
    icon: '🎯',
    title: 'Responsabilité',
    description: 'Conscience de notre rôle dans la société et la communauté'
  }
])

// Animations au montage
onMounted(() => {
  // Observer pour les animations au scroll
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-visible')
          observer.unobserve(entry.target)
        }
      })
    }, { threshold: 0.1 })
    
    const elements = document.querySelectorAll('.article-card, .value-card, .mission-card')
    elements.forEach(el => observer.observe(el))
  }
})
</script>

<style scoped>
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
.charter-page {
  background: var(--background);
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* ===== HERO SECTION ===== */
.charter-hero {
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

/* ===== ARTICLES SECTION ===== */
.articles-section {
  padding: 6rem 0;
  background: white;
}

.articles-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.article-card {
  display: grid;
  grid-template-columns: 100px 1fr;
  gap: 2rem;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.02), rgba(251, 146, 60, 0.02));
  padding: 2rem;
  border-radius: 20px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  position: relative;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  opacity: 0;
  transform: translateY(30px);
  animation: slideInUp 0.8s ease-out forwards;
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

.article-card.animate-visible {
  opacity: 1;
  transform: translateY(0);
}

.article-card:hover {
  transform: translateY(-8px);
  border-color: #018404;
  box-shadow: 0 16px 40px rgba(1, 132, 4, 0.15);
}

.article-number {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100px;
  height: 100px;
  background: linear-gradient(135deg, #018404, #016a03);
  border-radius: 50%;
  flex-shrink: 0;
}

.number-text {
  font-size: 2.5rem;
  font-weight: 900;
  color: white;
  position: relative;
  z-index: 2;
}

.number-glow {
  position: absolute;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(251, 191, 36, 0.4), transparent 70%);
  border-radius: 50%;
  animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.2); opacity: 0.8; }
}

.article-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  justify-content: center;
}

.article-title {
  font-size: 1.3rem;
  font-weight: 800;
  color: #018404;
  margin: 0;
}

.article-text {
  font-size: 1.05rem;
  line-height: 1.7;
  color: var(--text-secondary);
  margin: 0;
}

.article-decoration {
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(251, 146, 60, 0.05), transparent 70%);
  pointer-events: none;
}

/* ===== VALUES SECTION ===== */
.values-section {
  padding: 6rem 0;
}

.values-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.value-card {
  background: white;
  padding: 2.5rem;
  border-radius: 20px;
  border: 2px solid rgba(1, 132, 4, 0.1);
  text-align: center;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  overflow: hidden;
  opacity: 0;
  transform: translateY(30px);
  animation: slideInUp 0.8s ease-out forwards;
}

.value-card:hover {
  transform: translateY(-12px) rotate(-2deg);
  border-color: #018404;
  box-shadow: 0 20px 50px rgba(1, 132, 4, 0.2);
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

/* ===== COMMITMENT SECTION ===== */
.commitment-section {
  padding: 6rem 0;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
}

.commitment-card {
  background: white;
  padding: 4rem;
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  text-align: center;
  border: 2px solid rgba(1, 132, 4, 0.1);
}

.commitment-icon {
  font-size: 5rem;
  margin-bottom: 2rem;
  animation: pulse 2s ease-in-out infinite;
}

.commitment-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 1.5rem;
}

.commitment-text {
  font-size: 1.2rem;
  line-height: 1.8;
  color: var(--text-secondary);
  max-width: 800px;
  margin: 0 auto 3rem;
}

.commitment-stats {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 3rem;
}

.stat-item {
  text-align: center;
}

.stat-number {
  font-size: 3rem;
  font-weight: 900;
  background: linear-gradient(135deg, #018404, #fb923c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1;
  margin-bottom: 0.5rem;
}

.stat-label {
  font-size: 1rem;
  font-weight: 600;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-divider {
  width: 2px;
  height: 60px;
  background: linear-gradient(to bottom, transparent, #018404, transparent);
}

/* ===== SIGNATURE SECTION ===== */
.signature-section {
  padding: 6rem 0;
}

.signature-card {
  background: white;
  padding: 4rem;
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  display: flex;
  gap: 3rem;
  align-items: center;
  border: 2px solid rgba(1, 132, 4, 0.1);
  position: relative;
  overflow: hidden;
}

.signature-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #018404, #fb923c, #fbbf24);
}

.signature-seal {
  position: relative;
  width: 120px;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.signature-seal svg {
  width: 100%;
  height: 100%;
  color: #018404;
  position: relative;
  z-index: 2;
  animation: rotate 20s linear infinite;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.seal-glow {
  position: absolute;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(1, 132, 4, 0.2), transparent 70%);
  border-radius: 50%;
  animation: pulse-glow 2s ease-in-out infinite;
}

.signature-content {
  flex: 1;
  text-align: left;
}

.signature-text {
  font-size: 1.1rem;
  color: var(--text-secondary);
  margin-bottom: 0.5rem;
}

.signature-date {
  font-size: 1.3rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 2rem;
}

.signature-line {
  width: 200px;
  height: 2px;
  background: linear-gradient(90deg, #018404, transparent);
  margin-bottom: 1rem;
}

.signature-authority {
  font-size: 1.2rem;
  font-weight: 700;
  color: #018404;
  font-style: italic;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .hero-title {
    font-size: 2.5rem;
  }

  .article-card {
    grid-template-columns: 80px 1fr;
    gap: 1.5rem;
  }

  .article-number {
    width: 80px;
    height: 80px;
  }

  .number-text {
    font-size: 2rem;
  }

  .values-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .charter-hero {
    padding: 4rem 1.5rem;
  }

  .hero-title {
    font-size: 2rem;
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

  .article-card {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .article-number {
    margin: 0 auto;
  }

  .values-grid {
    grid-template-columns: 1fr;
  }

  .commitment-stats {
    flex-direction: column;
    gap: 2rem;
  }

  .stat-divider {
    width: 60px;
    height: 2px;
    background: linear-gradient(to right, transparent, #018404, transparent);
  }

  .signature-card {
    flex-direction: column;
    text-align: center;
  }

  .signature-content {
    text-align: center;
  }

  .signature-line {
    margin: 2rem auto 1rem;
  }
}

@media (max-width: 480px) {
  .hero-icon {
    font-size: 3rem;
  }

  .hero-title {
    font-size: 1.75rem;
  }

  .section-title {
    font-size: 1.75rem;
    flex-direction: column;
    gap: 0.5rem;
  }

  .container {
    padding: 0 1rem;
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