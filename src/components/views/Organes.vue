<template>
  <div class="organes-page">
    <!-- Hero Section -->
    <section class="organes-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
      </div>
      
      <div class="hero-content">
        <div class="hero-icon">🏛️</div>
        <h1 class="hero-title">
          <span class="highlight">O</span>rganes de l'AEEMCI
        </h1>
        <p class="hero-subtitle">
          Les structures organisationnelles qui assurent la gouvernance et le bon fonctionnement de l'association
        </p>
        <div class="hero-badge">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          <span>Structure organisationnelle</span>
        </div>
      </div>
    </section>

    <!-- Mission Statement -->
    <section class="mission-section">
      <div class="container">
        <div class="mission-card">
          <div class="mission-icon">🎯</div>
          <div class="mission-content">
            <h2 class="mission-title">Notre Structure</h2>
            <blockquote class="mission-quote">
              <div class="quote-mark-large">"</div>
              <p>
                Les Organes de l'AEEMCI constituent l'ossature administrative et décisionnelle de l'organisation. 
                Ils assurent la gouvernance, la coordination et le bon fonctionnement de l'association à tous les niveaux, 
                depuis le niveau national jusqu'aux sections locales.
              </p>
              <div class="quote-author">
                <div class="author-line"></div>
                <span>Structure AEEMCI</span>
              </div>
            </blockquote>
          </div>
        </div>
      </div>
    </section>

    <!-- Organes Délibérants -->
    <section class="organes-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">📋</span>
            Organes Délibérants
          </h2>
          <p class="section-subtitle">
            Les instances de décision et d'orientation stratégique de l'association
          </p>
        </div>

        <div class="organes-layout">
          <div class="organes-visual">
            <div class="org-hierarchy">
              <div 
                v-for="(organ, index) in organesDeliberants" 
                :key="organ.id"
                class="org-level-item"
                :style="{ animationDelay: `${index * 0.1}s` }"
              >
                <div 
                  class="org-card"
                  :class="{ active: activeOrganDeliberant === organ.id }"
                  @click="setActiveOrganDeliberant(organ.id)"
                >
                  <div class="org-icon">{{ organ.icon }}</div>
                  <h3 class="org-name">{{ organ.name }}</h3>
                  <div class="org-badge">{{ organ.badge }}</div>
                </div>
                <div v-if="index < organesDeliberants.length - 1" class="org-connector">
                  <div class="connector-line"></div>
                  <div class="connector-arrow"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="organes-details">
            <transition name="slide-fade" mode="out-in">
              <div v-if="activeOrganDeliberant" class="detail-card" :key="activeOrganDeliberant">
                <div class="detail-header">
                  <div class="detail-icon">{{ getActiveOrgan('deliberant').icon }}</div>
                  <h3 class="detail-title">{{ getActiveOrgan('deliberant').name }}</h3>
                </div>
                <div class="detail-content">
                  <p>{{ getActiveOrgan('deliberant').description }}</p>
                </div>
                <div class="detail-footer">
                  <div class="detail-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>{{ getActiveOrgan('deliberant').badge }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="detail-placeholder">
                <div class="placeholder-icon">🔍</div>
                <p>Cliquez sur un organe pour afficher sa description détaillée</p>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </section>

    <!-- Organes Dirigeants -->
    <section class="organes-section organes-alternate">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">👥</span>
            Organes Dirigeants
          </h2>
          <p class="section-subtitle">
            Les instances d'exécution et de coordination des activités
          </p>
        </div>

        <div class="organes-layout">
          <div class="organes-visual">
            <div class="org-hierarchy">
              <div 
                v-for="(organ, index) in organesDirigeants" 
                :key="organ.id"
                class="org-level-item"
                :style="{ animationDelay: `${index * 0.1}s` }"
              >
                <div 
                  class="org-card"
                  :class="{ active: activeOrganDirigeant === organ.id }"
                  @click="setActiveOrganDirigeant(organ.id)"
                >
                  <div class="org-icon">{{ organ.icon }}</div>
                  <h3 class="org-name">{{ organ.name }}</h3>
                  <div class="org-badge">{{ organ.badge }}</div>
                </div>
                <div v-if="index < organesDirigeants.length - 1" class="org-connector">
                  <div class="connector-line"></div>
                  <div class="connector-arrow"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="organes-details">
            <transition name="slide-fade" mode="out-in">
              <div v-if="activeOrganDirigeant" class="detail-card" :key="activeOrganDirigeant">
                <div class="detail-header">
                  <div class="detail-icon">{{ getActiveOrgan('dirigeant').icon }}</div>
                  <h3 class="detail-title">{{ getActiveOrgan('dirigeant').name }}</h3>
                </div>
                <div class="detail-content">
                  <p>{{ getActiveOrgan('dirigeant').description }}</p>
                </div>
                <div class="detail-footer">
                  <div class="detail-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>{{ getActiveOrgan('dirigeant').badge }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="detail-placeholder">
                <div class="placeholder-icon">🔍</div>
                <p>Cliquez sur un organe pour afficher sa description détaillée</p>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </section>

    <!-- Organes de Contrôle -->
    <section class="organes-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">🔍</span>
            Organes de Contrôle
          </h2>
          <p class="section-subtitle">
            Les instances de vérification et de contrôle financier
          </p>
        </div>

        <div class="organes-layout">
          <div class="organes-visual">
            <div class="org-hierarchy">
              <div 
                v-for="(organ, index) in organesControle" 
                :key="organ.id"
                class="org-level-item"
                :style="{ animationDelay: `${index * 0.1}s` }"
              >
                <div 
                  class="org-card"
                  :class="{ active: activeOrganControle === organ.id }"
                  @click="setActiveOrganControle(organ.id)"
                >
                  <div class="org-icon">{{ organ.icon }}</div>
                  <h3 class="org-name">{{ organ.name }}</h3>
                  <div class="org-badge">{{ organ.badge }}</div>
                </div>
                <div v-if="index < organesControle.length - 1" class="org-connector">
                  <div class="connector-line"></div>
                  <div class="connector-arrow"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="organes-details">
            <transition name="slide-fade" mode="out-in">
              <div v-if="activeOrganControle" class="detail-card" :key="activeOrganControle">
                <div class="detail-header">
                  <div class="detail-icon">{{ getActiveOrgan('controle').icon }}</div>
                  <h3 class="detail-title">{{ getActiveOrgan('controle').name }}</h3>
                </div>
                <div class="detail-content">
                  <p>{{ getActiveOrgan('controle').description }}</p>
                </div>
                <div class="detail-footer">
                  <div class="detail-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>{{ getActiveOrgan('controle').badge }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="detail-placeholder">
                <div class="placeholder-icon">🔍</div>
                <p>Cliquez sur un organe pour afficher sa description détaillée</p>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </section>

    <!-- Organigramme Visuel -->
    <section class="organigramme-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">📊</span>
            Organigramme Complet
          </h2>
        </div>
        <div class="organigramme-visual">
          <img 
            src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758226031/aeemci_photo_1758226028664_organigramme.jpg.jpg" 
            alt="Organigramme AEEMCI"
            class="organigramme-image"
          />
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <span class="title-icon">❓</span>
            Questions Fréquentes
          </h2>
        </div>

        <div class="faq-container">
          <div 
            v-for="(faq, index) in faqs" 
            :key="index"
            class="faq-item"
            :class="{ active: activeFaq === index }"
          >
            <div class="faq-question" @click="toggleFaq(index)">
              <h3>{{ faq.question }}</h3>
              <div class="faq-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path :d="activeFaq === index ? 'M5 12h14' : 'M12 5v14M5 12h14'"/>
                </svg>
              </div>
            </div>
            <transition name="expand">
              <div v-if="activeFaq === index" class="faq-answer">
                <p>{{ faq.answer }}</p>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
      <div class="container">
        <div class="contact-card">
          <div class="contact-icon">📞</div>
          <h2 class="contact-title">Contacter les Organes Centraux</h2>
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
              <p>organes.centraux@aeemci.org</p>
              <p>secretariat@aeemci.org</p>
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
import { ref, computed } from 'vue'

// États actifs
const activeOrganDeliberant = ref(null)
const activeOrganDirigeant = ref(null)
const activeOrganControle = ref(null)
const activeFaq = ref(null)

// Organes Délibérants
const organesDeliberants = ref([
  {
    id: 'congres',
    name: 'Congrès',
    icon: '🏛️',
    badge: 'Organe suprême',
    description: "Le Congrès est l'organe suprême de l'association. Il détermine l'orientation de celle-ci. Il est composé des représentants des Sections, des Sous-Comités, des Secrétariats Régionaux, des membres du CA, du CE, des CCC, des CCR, du Président de l'Amicale des Anciens de l'AEEMCI ou son représentant, de l'Imamat Central de l'AEEMCI, du Conseil Consultatif."
  },
  {
    id: 'ag',
    name: 'Assemblée Générale',
    icon: '👥',
    badge: 'Organe de synthèse',
    description: "L'Assemblée Générale est l'organe de synthèse des activités de l'association entre deux Congrès. Elle est composée des membres du Conseil d'Administration, du Comité Exécutif, du Commissariat Central aux Comptes, de l'Imamat Central, du Conseil Consultatif, des Secrétaires Régionaux, des Commissaires aux Comptes Régionaux, des représentants des Sous-Comités et du Président de l'Amicale des Anciens de l'AEEMCI."
  },
  {
    id: 'cr',
    name: 'Conseil Régional',
    icon: '🗺️',
    badge: 'Assemblée régionale',
    description: "Le Conseil Régional est l'assemblée délibérative des Sous-Comités d'une région. Il est présidé par l'Amir ou son représentant."
  },
  {
    id: 'ago',
    name: 'AGO',
    icon: '📋',
    badge: 'Assemblée locale',
    description: "L'AGO est l'assemblée délibérative des Sections d'un Sous-Comité, ou des membres d'une section. Elle est présidée par l'Amir ou son représentant."
  }
])

// Organes Dirigeants
const organesDirigeants = ref([
  {
    id: 'ca',
    name: "Conseil d'Administration",
    icon: '🏢',
    badge: 'Organe de contrôle',
    description: "Le Conseil d'Administration est l'organe de contrôle et d'orientation stratégique de l'association. Il est élu pour 2 ans par le Congrès et veille à l'application des décisions prises."
  },
  {
    id: 'ce',
    name: 'Comité Exécutif',
    icon: '⚙️',
    badge: "Organe d'exécution",
    description: "Le Comité Exécutif est l'organe d'exécution des décisions du Congrès. Il a la responsabilité de la mise en œuvre du Plan d'Action Stratégique et des Grandes Activités du Mandat. Il est chargé de la gestion quotidienne de l'association."
  },
  {
    id: 'sr',
    name: 'Secrétariat Régional',
    icon: '🌍',
    badge: 'Coordination régionale',
    description: "Le Secrétariat Régional est l'organe d'exécution des décisions émanant du CE au niveau régional. Il est composé d'un Secrétaire Régional, un Vice-Secrétaire Régional et de seize Secrétaires Régionaux adjoints. Le Secrétaire Régional coordonne et gère les activités de l'association dans la région."
  },
  {
    id: 'sc',
    name: 'Sous-Comité',
    icon: '🏘️',
    badge: 'Coordination locale',
    description: "Le Sous-Comité est le regroupement de Sections dans une localité. Le bureau du Sous-Comité est l'organe de coordination des activités des Sections entre elles d'une part et avec le SR d'autre part."
  },
  {
    id: 'section',
    name: 'Section',
    icon: '🏫',
    badge: 'Cellule de base',
    description: "La Section est la plus petite entité de l'association. Elle est créée dans les écoles primaires et secondaires, les UFR, les grandes écoles, les résidences universitaires et les écoles confessionnelles sur toute l'étendue du territoire national."
  }
])

// Organes de Contrôle
const organesControle = ref([
  {
    id: 'ccc',
    name: 'Commissariat Central aux Comptes',
    icon: '💼',
    badge: 'Contrôle central',
    description: "Le Commissariat Central aux Comptes est l'organe de contrôle du patrimoine de l'association. Il est composé de trois membres élus au Congrès pour un mandat renouvelable une fois. Il contrôle régulièrement la gestion du patrimoine, centralise la gestion des CCR, CC des Sections et Sous-Comités, et veille au respect des normes financières et comptables."
  },
  {
    id: 'ccr',
    name: 'Commissariat aux Comptes Régional',
    icon: '📊',
    badge: 'Contrôle régional',
    description: "Le Commissariat aux Comptes Régional est l'organe de contrôle du patrimoine matériel et financier des Secrétariats Régionaux. Il est composé d'un Commissaire aux Comptes Régional Principal et un Adjoint nommés par les CCC. Leur mandat est de deux ans renouvelables une fois."
  },
  {
    id: 'ccsc',
    name: 'CC de Sous-Comité',
    icon: '📈',
    badge: 'Contrôle S/C',
    description: "Les Commissaires aux Comptes des Sous-Comités exercent leurs fonctions à l'image des CCR et dressent un rapport financier et matériel trimestriel de contrôle du patrimoine adressé aux CCR."
  },
  {
    id: 'ccsection',
    name: 'CC de Section',
    icon: '📝',
    badge: 'Contrôle section',
    description: "Les Commissaires aux Comptes de la Section exercent leurs fonctions à l'image des CC des Sous-Comités. Ils dressent un rapport trimestriel de contrôle du patrimoine qu'ils adressent aux CC de S/C."
  }
])

// FAQs
const faqs = ref([
  {
    question: "Quelle est la différence entre le Comité Exécutif et le Conseil d'Administration ?",
    answer: "Le Conseil d'Administration (CA) est l'organe de contrôle et d'orientation stratégique, élu pour 2 ans par le Congrès. Le Comité Exécutif est l'organe d'exécution des résolutions du congrès, nommé par l'Amir pour 2 ans, chargé de la gestion quotidienne de l'association."
  },
  {
    question: "Comment les décisions sont-elles prises au sein des organes centraux ?",
    answer: "Les décisions sont généralement prises par consensus. En cas de désaccord, un vote est organisé. Pour les questions importantes, une majorité qualifiée (2/3 des voix) est requise, tandis que pour les questions courantes, une majorité simple suffit."
  },
  {
    question: "Quel est le rôle du Commissaire Central aux Comptes ?",
    answer: "Le Commissaire Central aux Comptes est chargé de vérifier la régularité des comptes de l'association, de contrôler la gestion financière du Comité Exécutif et de présenter des rapports financiers au CA et à l'AGO. Il agit en toute indépendance vis-à-vis du Comité Exécutif."
  },
  {
    question: "Comment sont financés les organes centraux de l'AEEMCI ?",
    answer: "Les organes centraux sont financés par les cotisations des membres, les subventions, les dons et legs, les revenus des activités génératrices de revenus, et les partenariats avec diverses institutions nationales et internationales."
  }
])

// Méthodes
const setActiveOrganDeliberant = (id) => {
  activeOrganDeliberant.value = activeOrganDeliberant.value === id ? null : id
}

const setActiveOrganDirigeant = (id) => {
  activeOrganDirigeant.value = activeOrganDirigeant.value === id ? null : id
}

const setActiveOrganControle = (id) => {
  activeOrganControle.value = activeOrganControle.value === id ? null : id
}

const toggleFaq = (index) => {
  activeFaq.value = activeFaq.value === index ? null : index
}

const getActiveOrgan = (type) => {
  if (type === 'deliberant') {
    return organesDeliberants.value.find(o => o.id === activeOrganDeliberant.value) || {}
  } else if (type === 'dirigeant') {
    return organesDirigeants.value.find(o => o.id === activeOrganDirigeant.value) || {}
  } else if (type === 'controle') {
    return organesControle.value.find(o => o.id === activeOrganControle.value) || {}
  }
  return {}
}
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
.organes-page {
  background: var(--background);
  min-height: 100vh;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* ===== HERO SECTION ===== */
.organes-hero {
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

/* ===== ORGANES SECTION ===== */
.organes-section {
  padding: 6rem 0;
}

.organes-alternate {
  background: white;
}

.organes-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: start;
}

/* ===== ORGANES VISUAL ===== */
.organes-visual {
  background: white;
  border-radius: 0;
  padding: 2rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1, 132, 4, 0.1);
}

.organes-alternate .organes-visual {
  background: var(--background);
}

.org-hierarchy {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.org-level-item {
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

.org-card {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
  border: 2px solid rgba(1, 132, 4, 0.2);
  border-radius: 0;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.org-card:hover {
  transform: translateX(10px);
  border-color: var(--green);
  box-shadow: var(--shadow);
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.1), rgba(251, 146, 60, 0.1));
}

.org-card.active {
  background: linear-gradient(135deg, var(--green), var(--green));
  border-color: var(--green);
  box-shadow: var(--shadow);
  transform: translateX(10px) scale(1.05);
}

.org-card.active .org-icon,
.org-card.active .org-name,
.org-card.active .org-badge {
  color: white;
}

.org-icon {
  font-size: 2.5rem;
  flex-shrink: 0;
}

.org-name {
  flex: 1;
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
}

.org-badge {
  background: rgba(251, 146, 60, 0.2);
  color: var(--gold);
  padding: 0.25rem 0.75rem;
  border-radius: 0;
  font-size: 0.85rem;
  font-weight: 600;
  flex-shrink: 0;
}

.org-card.active .org-badge {
  background: rgba(255, 255, 255, 0.2);
  color: white;
}

.org-connector {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0.5rem 0;
}

.connector-line {
  width: 2px;
  height: 30px;
  background: linear-gradient(to bottom, var(--green), var(--gold));
}

.connector-arrow {
  width: 0;
  height: 0;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-top: 12px solid var(--gold);
}

/* ===== ORGANES DETAILS ===== */
.organes-details {
  position: sticky;
  top: 100px;
}

.detail-card {
  background: white;
  border-radius: 0;
  padding: 2.5rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1, 132, 4, 0.1);
  min-height: 300px;
}

.organes-alternate .detail-card {
  background: var(--background);
}

.detail-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 2px solid rgba(1, 132, 4, 0.1);
}

.detail-icon {
  font-size: 3rem;
  background: linear-gradient(135deg, var(--green), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.detail-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text-primary);
  margin: 0;
}

.detail-content p {
  font-size: 1.1rem;
  line-height: 1.8;
  color: var(--text-secondary);
  margin: 0;
}

.detail-footer {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 2px solid rgba(1, 132, 4, 0.1);
}

.detail-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.1), rgba(251, 146, 60, 0.1));
  padding: 0.5rem 1rem;
  border-radius: 0;
  font-weight: 600;
  color: var(--green);
}

.detail-badge svg {
  color: var(--gold);
}

.detail-placeholder {
  background: white;
  border-radius: 0;
  padding: 3rem;
  text-align: center;
  box-shadow: var(--shadow);
  border: 2px dashed rgba(1, 132, 4, 0.2);
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.organes-alternate .detail-placeholder {
  background: var(--background);
}

.placeholder-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
  opacity: 0.5;
  animation: pulse 2s ease-in-out infinite;
}

.detail-placeholder p {
  font-size: 1.1rem;
  color: var(--text-secondary);
  max-width: 300px;
}

/* ===== TRANSITIONS ===== */
.slide-fade-enter-active {
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.slide-fade-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

/* ===== ORGANIGRAMME SECTION ===== */
.organigramme-section {
  padding: 6rem 0;
  background: white;
}

.organigramme-visual {
  background: var(--background);
  border-radius: 0;
  padding: 2rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1, 132, 4, 0.1);
}

.organigramme-image {
  width: 100%;
  height: auto;
  border-radius: 0;
  display: block;
}

/* ===== FAQ SECTION ===== */
.faq-section {
  padding: 6rem 0;
}

.faq-container {
  max-width: 900px;
  margin: 0 auto;
}

.faq-item {
  background: white;
  border-radius: 0;
  margin-bottom: 1.5rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1, 132, 4, 0.1);
  overflow: hidden;
  transition: all 0.3s ease;
}

.faq-item:hover {
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.faq-question {
  padding: 1.5rem 2rem;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s ease;
}

.faq-question h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
  flex: 1;
}

.faq-icon {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(1, 132, 4, 0.1);
  border-radius: 50%;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.faq-icon svg {
  color: var(--green);
  transition: all 0.3s ease;
}

.faq-item.active .faq-question {
  background: linear-gradient(135deg, rgba(1, 132, 4, 0.05), rgba(251, 146, 60, 0.05));
}

.faq-item.active .faq-icon {
  background: var(--green);
}

.faq-item.active .faq-icon svg {
  color: white;
}

.faq-answer {
  padding: 0 2rem 1.5rem;
  border-top: 1px solid rgba(1, 132, 4, 0.1);
}

.faq-answer p {
  font-size: 1.05rem;
  line-height: 1.7;
  color: var(--text-secondary);
  margin: 1rem 0 0;
}

.expand-enter-active,
.expand-leave-active {
  transition: all 0.3s ease;
  overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
  max-height: 0;
  opacity: 0;
  padding-top: 0;
  padding-bottom: 0;
}

.expand-enter-to,
.expand-leave-from {
  max-height: 500px;
  opacity: 1;
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
@media (max-width: 1024px) {
  .organes-layout {
    grid-template-columns: 1fr;
  }

  .organes-details {
    position: static;
  }

  .contact-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .organes-hero {
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

  .org-card {
    flex-direction: column;
    text-align: center;
  }

  .org-name {
    font-size: 1rem;
  }

  .detail-card {
    padding: 1.5rem;
  }

  .detail-title {
    font-size: 1.5rem;
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

  .org-icon {
    font-size: 2rem;
  }

  .faq-question h3 {
    font-size: 1rem;
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