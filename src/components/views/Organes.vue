<template>
    <div class="app">
  
      <!-- Bannière élégante avec motif islamique -->
      <section class="page-banner">
        <div class="banner-pattern"></div>
        <div class="container">
          <div class="page-title">
            <h1>Organes <span class="highlight"></span></h1>
            <p class="subtitle">Les structures organisationnelles de l'AEEMCI</p>
            <div class="breadcrumbs">
              <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">AEEMCI</a> <span class="separator">/</span> <span class="current">Organes</span>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Contenu principal -->
      <main class="main-content">
        <div class="container">
          <!-- Introduction -->
          <section class="intro-section">
            <div class="quote-box">
              <div class="quote-mark">"</div>
              <blockquote>
                L'AEEMCI est une structure islamique en Côte d'Ivoire, qui contribue à la formation religieuse et managériale des élèves et étudiants musulmans, pour l'émergence d'une élite musulmane consciente des valeurs républicaines et de sa responsabilité communautaire.
              </blockquote>
              <div class="quote-author">— Statuts de l'AEEMCI</div>
            </div>
            
            <div class="intro-text">
              <p>Les Organes Centraux de l'Association des Élèves et Étudiants Musulmans de Côte d'Ivoire (AEEMCI) constituent l'ossature administrative et décisionnelle de l'organisation. Ils assurent la gouvernance, la coordination et le bon fonctionnement de l'association à tous les niveaux.</p>
              <p>Cette structure hiérarchique permet une répartition claire des responsabilités et garantit une représentation démocratique des membres, depuis le niveau national jusqu'aux sections locales.</p>
            </div>
          </section>
          
          <!-- Organigramme interactif -->
          <section class="organigramme-section">
            <h2 class="section-title"><span class="highlight">Organes</span> Délibérants</h2>
            
            <!-- Modified container to have organs on left and details on right -->
            <div class="organigramme-container">
              <!-- Left side: Organigramme -->
              <div class="organigramme-left">
                <div class="organigramme">
                  <!-- Congrès -->
                  <div class="org-level">
                    <div 
                      class="org-box congres" 
                      :class="{ active: activeOrganDeliberant === 'congres' }"
                      @click="setActiveOrganDeliberant('congres')"
                    >
                      <div class="org-title">Congrès</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- AGO -->
                  <div class="org-level">
                    <div 
                      class="org-box ago" 
                      :class="{ active: activeOrganDeliberant === 'ag' }"
                      @click="setActiveOrganDeliberant('ag')"
                    >
                      <div class="org-title">Assemblée Générale (AG)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- Conseil Régional -->
                  <div class="org-level">
                    <div 
                      class="org-box cr" 
                      :class="{ active: activeOrganDeliberant === 'cr' }"
                      @click="setActiveOrganDeliberant('cr')"
                    >
                      <div class="org-title">Conseil Régional</div>
                    </div>
                  </div>
                  
                  <!-- Flèches divergentes -->
                  <div class="org-arrows-split">
                    <div class="arrow-split left">
                      <div class="arrow-line"></div>
                      <div class="arrow-head"></div>
                    </div>
                    <div class="arrow-split right">
                      <div class="arrow-line"></div>
                      <div class="arrow-head"></div>
                    </div>
                  </div>
                  
                  <!-- AGO -->
                  <div class="org-level">
                    <div 
                      class="org-box ago" 
                      :class="{ active: activeOrganDeliberant === 'ago' }"
                      @click="setActiveOrganDeliberant('ago')"
                    >
                      <div class="org-title">Assemblée Générale Ordinaire (AGO)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <div class="org-level">
                    <div 
                      class="org-box ago" 
                      :class="{ active: activeOrganDeliberant === 'age' }"
                      @click="setActiveOrganDeliberant('age')"
                    >
                      <div class="org-title">Assemblée Générale extraordinaires (AGE)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                </div>
              </div>
              
              <!-- Right side: Description -->
              <div class="organigramme-right">
                <div class="organ-description">
                  <div v-if="!activeOrganDeliberant" class="description-placeholder">
                    <div class="placeholder-icon">
                      <span>🔍</span>
                    </div>
                    <p>Cliquez sur un organe dans l'organigramme pour afficher sa description détaillée.</p>
                  </div>
                  <div v-else class="description-content">
                    <h3>{{ activeOrganDeliberant.toUpperCase() }}</h3>
                    <p>{{ organDetails[activeOrganDeliberant] }}</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="organigramme-section">
            <h2 class="section-title"><span class="highlight">Organes</span> dirigeants</h2>
            
            <!-- Modified container structure for left-right layout -->
            <div class="organigramme-container">
              <!-- Left side: Organigramme -->
              <div class="organigramme-left">
                <div class="organigramme">

                  <div class="org-level">
                    <div 
                      class="org-box ce" 
                      :class="{ active: activeOrganDirigeant === 'ce' }"
                      @click="setActiveOrganDirigeant('ce')"
                    >
                      <div class="org-title">Conseil d'Administration (CA)</div>
                    </div>
                  </div>
                  <!-- CE -->
                  <div class="org-level">
                    <div 
                      class="org-box ce" 
                      :class="{ active: activeOrganDirigeant === 'ce' }"
                      @click="setActiveOrganDirigeant('ce')"
                    >
                      <div class="org-title">Comité Exécutif (CE)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- SR -->
                  <div class="org-level">
                    <div 
                      class="org-box sr" 
                      :class="{ active: activeOrganDirigeant === 'sr' }"
                      @click="setActiveOrganDirigeant('sr')"
                    >
                      <div class="org-title">Secrétariat Régional (SR)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- S/C -->
                  <div class="org-level">
                    <div 
                      class="org-box sc" 
                      :class="{ active: activeOrganDirigeant === 'sc' }"
                      @click="setActiveOrganDirigeant('sc')"
                    >
                      <div class="org-title">Sous-Comité (S/C)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- Section -->
                  <div class="org-level">
                    <div 
                      class="org-box section" 
                      :class="{ active: activeOrganDirigeant === 'section' }"
                      @click="setActiveOrganDirigeant('section')"
                    >
                      <div class="org-title">Section</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- Imamat Central -->
                  
                </div>
              </div>
              
              <!-- Right side: Description -->
              <div class="organigramme-right">
                <div class="organ-description">
                  <div v-if="!activeOrganDirigeant" class="description-placeholder">
                    <div class="placeholder-icon">
                      <span>🔍</span>
                    </div>
                    <p>Cliquez sur un organe pour afficher sa description détaillée.</p>
                  </div>
                  <div v-else class="description-content">
                    <h3>{{ activeOrganDirigeant.toUpperCase() }}</h3>
                    <p>{{ organDetailsDirigeant[activeOrganDirigeant] }}</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="organigramme-section">
            <h2 class="section-title"><span class="highlight">Organes</span> de Contrôle</h2>
            
            <!-- Modified container structure for left-right layout -->
            <div class="organigramme-container">
              <!-- Left side: Organigramme -->
              <div class="organigramme-left">
                <div class="organigramme">
                  <!-- CCC -->
                  <div class="org-level">
                    <div 
                      class="org-box ccc" 
                      :class="{ active: activeOrganControle === 'ccc' }"
                      @click="setActiveOrganControle('ccc')"
                    >
                      <div class="org-title">Commissariat Central aux Comptes (CCC)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- CCR -->
                  <div class="org-level">
                    <div 
                      class="org-box ccr" 
                      :class="{ active: activeOrganControle === 'ccr' }"
                      @click="setActiveOrganControle('ccr')"
                    >
                      <div class="org-title">Commissariat aux Comptes Régional (CCR)</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- CC de S/C -->
                  <div class="org-level">
                    <div 
                      class="org-box cc-sc" 
                      :class="{ active: activeOrganControle === 'cc-sc' }"
                      @click="setActiveOrganControle('cc-sc')"
                    >
                      <div class="org-title">CC de S/C</div>
                    </div>
                  </div>
                  
                  <!-- Flèche -->
                  <div class="org-arrow">
                    <div class="arrow-line"></div>
                    <div class="arrow-head"></div>
                  </div>
                  
                  <!-- CC de Section -->
                  <div class="org-level">
                    <div 
                      class="org-box cc-section" 
                      :class="{ active: activeOrganControle === 'cc-section' }"
                      @click="setActiveOrganControle('cc-section')"
                    >
                      <div class="org-title">CC de Section</div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Right side: Description -->
              <div class="organigramme-right">
                <div class="organ-description">
                  <div v-if="!activeOrganControle" class="description-placeholder">
                    <div class="placeholder-icon">
                      <span>🔍</span>
                    </div>
                    <p>Cliquez sur un organe pour afficher sa description détaillée.</p>
                  </div>
                  <div v-else class="description-content">
                    <h3>{{ activeOrganControle.toUpperCase() }}</h3>
                    <p>{{ organDetailsControle[activeOrganControle] }}</p>
                  </div>
                </div>
              </div>
            </div>
          </section>
          
          <!-- Fonctionnement des organes -->
          <section class="fonctionnement-section">
            <h2 class="section-title"><span class="highlight">Groupe </span>AEEMCI</h2>
            <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758226031/aeemci_photo_1758226028664_organigramme.jpg.jpg">
          </section>
          
          <!-- FAQ -->
          <section class="faq-section">
            <h2 class="section-title"><span class="highlight">Q</span>uestions Fréquentes</h2>
            
            <div class="faq-container">
              <div 
                v-for="(faq, index) in faqs" 
                :key="index" 
                class="faq-item"
                :class="{ 'active': activeFaq === index }"
              >
                <div class="faq-question" @click="toggleFaq(index)">
                  <h3>{{ faq.question }}</h3>
                  <span class="faq-icon">{{ activeFaq === index ? '−' : '+' }}</span>
                </div>
                <div class="faq-answer" v-show="activeFaq === index">
                  <p>{{ faq.answer }}</p>
                </div>
              </div>
            </div>
          </section>
          
          <!-- Contact -->
          <section class="contact-section">
            <h2 class="section-title"><span class="highlight">C</span>ontacter les Organes Centraux</h2>
            
            <div class="contact-cards">
              <div class="contact-card">
                <div class="contact-icon">
                  <span>📞</span>
                </div>
                <h3>Téléphone</h3>
                <p>+225 27 22 43 47 58</p>
                <p>+225 07 07 07 07 07</p>
              </div>
              
              <div class="contact-card">
                <div class="contact-icon">
                  <span>✉️</span>
                </div>
                <h3>Email</h3>
                <p>organes.centraux@aeemci.org</p>
                <p>secretariat@aeemci.org</p>
              </div>
              
              <div class="contact-card">
                <div class="contact-icon">
                  <span>📍</span>
                </div>
                <h3>Adresse</h3>
                <p>Siège de l'AEEMCI</p>
                <p>Mosquée An Nour de l'AEEMCI</p>
                <p>Cocody Riviera 2, Abidjan</p>
              </div>
            </div>
          </section>
        </div>
      </main>
  
      <!-- Footer -->

    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  // État pour les dropdowns
  const dropdowns = ref({
    aeemci: true, // Ouvert par défaut car nous sommes sur la page Organes Centraux
    activites: false,
    mediatheque: false
  });
  
  const activeOrganDirigeants = ref(null);
  const activeOrganDeliberant = ref(null);
  const activeOrganControle = ref(null);
  
  // New refs for the updated structure
  const activeOrganDirigeant = ref(null); // For Organes Dirigeants
  
  const setActiveOrganDirigeants = (organ) => {
    if (activeOrganDirigeants.value === organ) {
      activeOrganDirigeants.value = null;
    } else {
      activeOrganDirigeants.value = organ;
    }
  };
  
  const setActiveOrganDeliberant = (organ) => {
    if (activeOrganDeliberant.value === organ) {
      activeOrganDeliberant.value = null;
    } else {
      activeOrganDeliberant.value = organ;
    }
  };
  
  const setActiveOrganControle = (organ) => {
    if (activeOrganControle.value === organ) {
      activeOrganControle.value = null;
    } else {
      activeOrganControle.value = organ;
    }
  };
  
  // New handler for Organes Dirigeants
  const setActiveOrganDirigeant = (organ) => {
    if (activeOrganDirigeant.value === organ) {
      activeOrganDirigeant.value = null;
    } else {
      activeOrganDirigeant.value = organ;
    }
  };
  
  const organDetails = {
    ce: "C'est l'organe d'exécution des décisions du Congrès. Il a la responsabilité de la mise en œuvre du Plan d'Action Stratégique et des Grandes Activités du Mandat. A ce titre, il est chargé de la gestion quotidienne de l'association.",
    
    ccc: "Le Commissariat Central aux Comptes est l'organe de contrôle du patrimoine de l'association. Il est composé de trois membres élus au Congrès pour un mandat renouvelable une fois pour l'un des trois (3). Il est chargé de contrôler régulièrement la gestion du patrimoine de l'association. Il centralise la gestion des CCR, CC des Sections et des Sous-Comités de l'association. Il établit un rapport qu'il présente soit au Congrès ou à l'Assemblée Générale. Il veille au respect des normes financières et comptables du manuel de procédure de l'AEEMCI.",
    
    sr: "1- C'est l'organe d'exécution des décisions émanant du CE, ou du conseil régional à la base (S/C). 2- Il est composé d'un (1) Secrétaire Régional, un (1) Vice-Secrétaire Régional et de seize (16) Secrétaires Régionaux adjoints. 3- Le Secrétaire Régional est le 1er responsable de l'association dans la région. 4- Il coordonne et gère les activités de l'association dans la région.",
    
    sc: "Le Sous-Comité est le regroupement de Sections dans une localité. En cas de nécessité, le CE peut créer plusieurs Sous-Comités dans la même localité. Le bureau du Sous-Comité est l'organe de coordination des activités des Sections entre elle-même d'une part et d'autre part avec le SR.",
    
    section: "1- La Section est la plus petite entité de l'association. 2- Elle est créée dans les écoles primaires et secondaires, les UFR, les grandes écoles, les résidences universitaires et les écoles confessionnelles sur toute l'étendue du territoire national.",
    
    congres: "1- Le Congrès est l'organe suprême de l'association. Il détermine l'orientation de celle-ci. 2- Il est composé des représentants des Sections, des Sous-Comités, des Secrétariats Régionaux (SR), des membres du CA, du CE, des CCC, des CCR ; des CC), du Président de l'Amicale des Anciens de l'AEEMCI (3A) ou son représentant, de l'Imamat Central de l'AEEMCI, du Conseil Consultatif.",
    
    ag: "1- L'Assemblée Générale est l'organe de synthèse des activités de l'association entre deux Congrès. 2- Elle est composée des membres du Conseil d'Administration, du Comité Exécutif, du Commissariat Central aux Comptes, de l'Imamat Central, du Conseil Consultatif, des Secrétaires Régionaux, des Commissaires aux Comptes Régionaux, des représentants des Sous-Comités et du Président de l'Amicale des Anciens de l'AEEMCI ou son représentant.",
    
    cr: "1- Le Conseil Régional est l'assemblée délibérative des Sous-Comités d'une région. 2- Il est présidé par l'Amir ou son représentant.",
    
    ago: "1- L'AGO est l'assemblée délibérative des Sections d'un Sous-Comité, ou des membres d'une section. 2- Elle est présidée par l'Amir ou son représentant.",
    
    ccr: "1- Le Commissariat aux Comptes Régional est l'organe de contrôle du patrimoine matériel et financier des Secrétariats Régionaux. 2- Il est composé d'un (1) Commissaire aux Comptes Régional Principal et un (1) Adjoint nommés par les CCC sur proposition conjointe des Présidents et des CC des Sous-Comités lors du Conseil Régional. 3- Leur mandat est de deux (2) ans renouvelables une (01) fois.",
    
    ccsc: "4- Les Commissaires aux Comptes des S/C exercent leurs fonctions à l'image des CCR et dressent un rapport financier et matériel trimestriel de contrôle du patrimoine adressé à ceux-ci.",
    
    ccsection: "4- Les Commissaires aux Comptes de la Section exercent leurs fonctions à l'image des CC des Sous-Comités. Ils dressent un rapport trimestriel de contrôle du patrimoine qu'ils adressent à ceux-ci.",
    
    imamat: "1- L'Imamat Central est un organe consultatif de l'AEEMCI. Il est dirigé par un Imam Central et trois Adjoints.",
    
    conseil: "1- Le Conseil Consultatif est le conseiller technique de l'Amir et du Comité Exécutif. 2- Il apporte l'assistance technique pour la mise en œuvre du cahier de charges du Comité Exécutif. 3- Il peut être consulté par le CE pour toutes les questions touchant la vie de l'AEEMCI. 4- Le Conseil Consultatif donne son avis sur les projets et questions à lui soumis par tout autre organe de l'AEEMCI. 5- Le Conseil Consultatif se réunit au moins une fois par mois sur convocation de son Président. 6- L'Amir peut et selon un ordre du jour bien déterminé, demander au Président, la ténue d'une réunion dudit Conseil. 7- Le mandat du Conseil Consultatif expire avec celui de l'Amir."
  };

  // Details for Organes Dirigeants
  const organDetailsDirigeant = {
    ce: "Le Comité Exécutif (CE) est l'organe d'exécution des décisions du Congrès et de l'Assemblée Générale. Il est responsable de la gestion quotidienne de l'association, de la mise en œuvre du plan d'action stratégique et de la coordination des activités.",
    sr: "Le Secrétariat Régional (SR) est l'organe d'exécution des décisions du CE au niveau régional. Il est responsable de la coordination et de la gestion des activités de l'association dans sa région, sous la supervision du CE.",
    sc: "Le Sous-Comité (S/C) est l'organe de coordination des Sections dans une localité ou une zone géographique définie. Il assure la liaison entre les Sections et le Secrétariat Régional.",
    section: "La Section est la cellule de base de l'association, implantée dans les établissements scolaires, universitaires ou les résidences. Elle est responsable de la mobilisation des membres et de la mise en œuvre des activités au niveau local.",
    imamat: "L'Imamat Central est un organe consultatif chargé des affaires religieuses au sein de l'AEEMCI. Il veille à la conformité des activités de l'association avec les préceptes de l'Islam.",
    conseil: "Le Conseil Consultatif est un organe technique et de conseil auprès de l'Amir et du Comité Exécutif. Il apporte son expertise pour la prise de décision et l'orientation stratégique de l'association."
  };

  // Details for Organes de Contrôle
  const organDetailsControle = {
    ccc: "Le Commissariat Central aux Comptes (CCC) est l'organe suprême de contrôle financier de l'AEEMCI. Il supervise les Commissariats aux Comptes Régionaux et s'assure de la bonne gestion du patrimoine de l'association.",
    ccr: "Le Commissariat aux Comptes Régional (CCR) est chargé du contrôle financier des Secrétariats Régionaux et des Sous-Comités de sa région. Il rend compte de ses activités au CCC.",
    'cc-sc': "Le Commissaire aux Comptes de Sous-Comité (CC de S/C) contrôle la gestion financière et matérielle des Sections relevant de son Sous-Comité. Il rapporte au CCR.",
    'cc-section': "Le Commissaire aux Comptes de Section (CC de Section) est responsable du contrôle financier et matériel au niveau de la Section. Il rapporte au CC de S/C."
  };
  
  // Onglets de fonctionnement
  const fonctionnementTabs = [
    { title: 'Processus Décisionnel' },
    { title: 'Élections et Mandats' },
    { title: 'Réunions et Rapports' }
  ];
  
  const activeTab = ref(0);
  
  const setActiveTab = (index) => {
    activeTab.value = index;
  };
  
  // FAQ
  const faqs = [
    {
      question: "Quelle est la différence entre le Comité Exécutif et le Conseil Administratif ?",
      answer: "Le Conseil d'Administration (CA) est l'organe de contrôle et d'orientation stratégique, élu pour 2 ans par le Congrès. Le Comité Exécutif est l'organe d'exécution des résolutions du congrès, nommé par l'Amir pour 2 ans, chargé de la gestion de l'association."
    },
    {
      question: "Comment les décisions sont-elles prises au sein des organes centraux ?",
      answer: "Les décisions sont généralement prises par consensus. En cas de désaccord, un vote est organisé. Pour les questions importantes, une majorité qualifiée (2/3 des voix) est souvent requise, tandis que pour les questions courantes, une majorité simple suffit."
    },
    {
      question: "Quel est le rôle du Commissaire Central aux comptes ?",
      answer: "Le Commissaire Central aux comptes est chargé de vérifier la régularité des comptes de l'association, de contrôler la gestion financière du Comité Exécutif et de présenter des rapports financiers au CA et à l'AGO. Il agit en toute indépendance vis-à-vis du Comité Exécutif."
    },
    {
      question: "Comment sont financés les organes centraux de l'AEEMCI ?",
      answer: "Les organes centraux sont financés par les cotisations des membres, les subventions, les dons et legs, les revenus des activités génératrices de revenus, et les partenariats avec diverses institutions nationales et internationales."
    }
  ];

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
            counter.textContent = target.toLocaleString('fr-FR');
          } else {
            counter.textContent = target;
          }
        }
      };
      
      updateCounter();
    });
  };

  const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-visible');
          
          // Si c'est la section des statistiques, démarrer l'animation des compteurs
          if (entry.target.classList.contains('stats-grid')) {
            animateCounters();
          }
        }
      });
    }, { threshold: 0.1 });
  
  const activeFaq = ref(null);
  
  const toggleFaq = (index) => {
    if (activeFaq.value === index) {
      activeFaq.value = null;
    } else {
      activeFaq.value = index;
    }
  };
  
  // Initialisation
  onMounted(() => {

    document.querySelectorAll('.period-card, .intro-section, .testimonials-section, .gallery-section, .stats-grid').forEach(el => {
      observer.observe(el);
    });
    // Fermer les dropdowns quand on clique en dehors
    document.addEventListener('click', (event) => {
      const isDropdownButton = event.target.closest('.dropdown');
      if (!isDropdownButton) {
        Object.keys(dropdowns.value).forEach(key => {
          dropdowns.value[key] = false;
        });
      }
    });
  });
  </script>
  <script>
  // Pour respecter la règle multi-word
  export default {
    name: 'OrGanes'
  }
  </script>
  
  <style>
  :root {
    --primary: #006400;
    --primary-dark: #004d00;
    --primary-light: #008000;
    --primary-lighter: #e6f0e6;
    --secondary: #f8b400;
    --secondary-dark: #d99b00;
    --secondary-light: #ffc933;
    --accent: #ff6600;
    --accent-dark: #e65c00;
    --accent-light: #ff8533;
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
  
  ul {
    list-style-position: inside;
    margin-bottom: 1rem;
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
  
  .dropdown-menu a.active {
    background-color: var(--background-alt);
    color: var(--primary);
    font-weight: 600;
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
    background-color: var(--accent-dark);
    transform: translateY(-2px);
  }
  
  .mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
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
  }
  
  .breadcrumbs a:hover {
    opacity: 1;
  }
  
  .separator {
    margin: 0 0.5rem;
    opacity: 0.6;
  }
  
  .current {
    opacity: 1;
    font-weight: 600;
  }
  
  .highlight {
    color: var(--secondary);
    font-weight: 800;
  }
  
  /* Main Content */
  .main-content {
    padding: 4rem 0;
    background-color: var(--background);
  }
  
  /* Introduction Section */
  .intro-section {
    display: flex;
    gap: 2rem;
    margin-bottom: 4rem;
    align-items: center;
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
    line-height: 1.8;
  }
  
  /* Section Titles */
  .section-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
  }
  
  .section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: var(--primary);
    border-radius: 3px;
  }
  
  /* Organigramme Section */
  .organigramme-section {
    margin-bottom: 4rem;
  }
  
  /* Updated container layout for left-right structure */
  .organigramme-container {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    min-height: 400px;
  }
  
  .organigramme-left {
    flex: 0 0 50%;
    display: flex;
    justify-content: center;
  }
  
  .organigramme-right {
    flex: 1;
    display: flex;
    align-items: flex-start;
  }
  
  .organigramme {
    position: relative;
    width: 100%;
    max-width: 500px;
  }
  
  .org-level {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
  }
  
  .org-level.split {
    justify-content: space-between;
  }
  
  .org-level.left {
    justify-content: flex-start;
    padding-left: 20%;
  }
  
  .org-box {
    background-color: #f8b400;
    color: white;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    min-width: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .org-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }
  
  .org-box.active {
    background-color: var(--accent-dark);
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }
  
  .org-title {
    font-weight: 700;
    font-size: 1rem;
  }
  
  .org-arrow {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 40px;
    position: relative;
    margin-bottom: 1rem;
  }
  
  .org-arrow-left {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    height: 40px;
    position: relative;
    margin-bottom: 1rem;
    padding-left: 20%;
  }
  
  .arrow-line {
    width: 2px;
    height: 30px;
    background-color: var(--accent);
  }
  
  .arrow-head {
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 10px solid var(--accent);
  }
  
  .org-arrows-split {
    display: flex;
    justify-content: space-between;
    height: 40px;
    position: relative;
    margin-bottom: 1rem;
  }
  
  .arrow-split {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 50%;
    position: relative;
  }
  
  .arrow-split.left {
    align-items: flex-end;
    padding-right: 25%;
  }
  
  .arrow-split.right {
    align-items: flex-start;
    padding-left: 25%;
  }
  
  .arrow-split .arrow-line {
    width: 2px;
    height: 30px;
    background-color: var(--accent);
  }
  
  .arrow-split .arrow-head {
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 10px solid var(--accent);
  }
  
  .organ-description {
    flex: 1;
    background-color: var(--background-alt);
    border-radius: 8px;
    padding: 2rem;
    min-height: 400px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .description-content h3 {
    color: var(--accent);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--accent-light);
  }
  
  .description-content p {
    margin-bottom: 1rem;
    line-height: 1.8;
  }
  
  .description-content ul {
    margin-bottom: 1.5rem;
    padding-left: 1rem;
  }
  
  .description-content li {
    margin-bottom: 0.5rem;
  }
  
  .description-meta {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1.5rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .meta-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
  }
  
  .meta-label {
    font-weight: 600;
    color: var(--text-secondary);
  }
  
  .meta-value {
    color: var(--accent);
  }
  
  .description-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 300px;
    text-align: center;
  }
  
  .placeholder-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--text-light);
  }
  
  .placeholder-icon span {
    display: inline-block;
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 0.7;
    }
    50% {
      transform: scale(1.1);
      opacity: 1;
    }
    100% {
      transform: scale(1);
      opacity: 0.7;
    }
  }
  
  /* Organigramme Section - New Structure */
  .organigramme-section .organigramme-container {
    gap: 1.5rem;
  }
  
  .organigramme-section .organigramme {
    flex: none; /* Reset flex basis */
    width: 100%;
    max-width: 600px; /* Limit width for better readability */
    margin: 0 auto; /* Center the organigramme */
  }
  
  .organigramme-section .organ-description {
    flex: none; /* Reset flex basis */
    width: 100%;
  }
  
  .organigramme-section .organs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Responsive grid */
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  
  .organigramme-section .organ-box {
    min-width: auto; /* Override min-width for grid items */
    padding: 0.8rem 0.5rem; /* Adjust padding */
    font-size: 0.9rem; /* Adjust font size */
  }
  
  .organigramme-section .organ-box h3 {
    font-size: 1rem; /* Adjust title font size */
    margin-bottom: 0.3rem;
  }
  
  .organigramme-section .organ-box p {
    font-size: 0.75rem; /* Adjust subtitle font size */
    margin: 0;
  }
  
  .organigramme-section .organ-box.active {
    background-color: var(--accent-dark);
  }
  
  .organigramme-section .details-section {
    background-color: var(--background-alt);
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .organigramme-section .details-section h3 {
    color: var(--accent);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--accent-light);
  }
  
  .organigramme-section .organ-details p {
    line-height: 1.8;
  }
  
  /* Specific organ box colors */
  .organigramme-section .organ-box.ce { background-color: #f8b400; }
  .organigramme-section .organ-box.sr { background-color: #ff6600; }
  .organigramme-section .organ-box.sc { background-color: #006400; }
  .organigramme-section .organ-box.section { background-color: #17a2b8; }
  
  .organigramme-section .organ-box.congres { background-color: #f8b400; }
  .organigramme-section .organ-box.ag { background-color: #ff6600; }
  .organigramme-section .organ-box.cr { background-color: #006400; }
  .organigramme-section .organ-box.ago { background-color: #17a2b8; }
  
  .organigramme-section .organ-box.ccc { background-color: #f8b400; }
  .organigramme-section .organ-box.ccr { background-color: #ff6600; }
  .organigramme-section .organ-box.ccsc { background-color: #006400; }
  .organigramme-section .organ-box.ccsection { background-color: #17a2b8; }
  
  .organigramme-section .organ-box.imamat { background-color: #f8b400; }
  .organigramme-section .organ-box.conseil { background-color: #ff6600; }
  
  /* Functionnement Section */
  .fonctionnement-section {
    margin-bottom: 4rem;
  }
  
  .fonctionnement-tabs {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .tabs-header {
    display: flex;
    background-color: var(--background-alt);
    border-bottom: 1px solid var(--border);
  }
  
  .tab-btn {
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-secondary);
    transition: all 0.3s ease;
    flex: 1;
    text-align: center;
  }
  
  .tab-btn:hover {
    background-color: rgba(0, 0, 0, 0.05);
  }
  
  .tab-btn.active {
    color: var(--accent);
    border-bottom: 3px solid var(--accent);
    background-color: white;
  }
  
  .tabs-content {
    padding: 2rem;
  }
  
  .tab-content h3 {
    color: #f8b400;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  /* Process Flow */
  .process-flow {
    margin-top: 2rem;
  }
  
  .process-step {
    display: flex;
    margin-bottom: 1.5rem;
    position: relative;
  }
  
  .process-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 40px;
    left: 20px;
    width: 2px;
    height: calc(100% + 1.5rem);
    background-color: var(--accent-light);
  }
  
  .step-number {
    width: 40px;
    height: 40px;
    background-color: #f8b400;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 1.5rem;
    position: relative;
    z-index: 1;
  }
  
  .step-content {
    flex: 1;
  }
  
  .step-content h4 {
    font-size: 1.2rem;
    color: #f8b400;
    margin-bottom: 0.5rem;
  }
  
  /* Elections Grid */
  .elections-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
  }
  
  .election-card {
    background-color: var(--background-alt);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .election-header {
    background-color: #f8b400;
    color: white;
    padding: 1rem;
    text-align: center;
  }
  
  .election-header h4 {
    font-size: 1.2rem;
    margin: 0;
  }
  
  .election-content {
    padding: 1.5rem;
  }
  
  .election-item {
    margin-bottom: 0.8rem;
  }
  
  .item-label {
    font-weight: 600;
    color: var(--text-secondary);
    display: block;
    margin-bottom: 0.3rem;
  }
  
  .item-value {
    color: var(--text-primary);
  }
  
  /* Meetings Table */
  .meetings-table {
    margin-top: 1.5rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .table-header {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    background-color: #f8b400;
    color: white;
    font-weight: 600;
  }
  
  .header-cell {
    padding: 1rem;
    text-align: center;
    border-right: 1px solid rgba(255, 255, 255, 0.2);
  }
  
  .header-cell:last-child {
    border-right: none;
  }
  
  .table-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    border-bottom: 1px solid var(--border);
  }
  
  .table-row:nth-child(even) {
    background-color: var(--background-alt);
  }
  
  .table-row:last-child {
    border-bottom: none;
  }
  
  .row-cell {
    padding: 1rem;
    text-align: center;
    border-right: 1px solid var(--border);
  }
  
  .row-cell:last-child {
    border-right: none;
  }
  
  .reporting-cycle {
    margin-top: 2rem;
    background-color: var(--primary-lighter);
    padding: 1.5rem;
    border-radius: 8px;
  }
  
  .reporting-cycle h4 {
    color: var(--primary);
    margin-bottom: 1rem;
    font-size: 1.2rem;
  }
  
  /* FAQ Section */
  .faq-section {
    margin-bottom: 4rem;
  }
  
  .faq-container {
    max-width: 800px;
    margin: 0 auto;
  }
  
  .faq-item {
    background-color: white;
    border-radius: 8px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }
  
  .faq-question {
    padding: 1.5rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
  }
  
  .faq-question h3 {
    font-size: 1.1rem;
    margin: 0;
    color: var(--text-primary);
  }
  
  .faq-icon {
    font-size: 1.5rem;
    color: #f8b400;
  }
  
  .faq-item.active .faq-question {
    background-color: #f8b400;
  }
  
  .faq-item.active .faq-question h3 {
    color: white;
  }
  
  .faq-item.active .faq-icon {
    color: white;
  }
  
  .faq-answer {
    padding: 0 1.5rem 1.5rem;
    border-top: 1px solid var(--border);
  }
  
  /* Contact Section */
  .contact-section {
    margin-bottom: 2rem;
  }
  
  .contact-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .contact-card {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }
  
  .contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  }
  
  .contact-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #f8b400;
  }
  
  .contact-card h3 {
    font-size: 1.3rem;
    margin-bottom: 1rem;
    color: #f8b400;
  }
  
  .contact-card p {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
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
  
  .opening-hours {
    list-style: none;
    padding: 0;
  }
  
  .opening-hours li {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.6rem;
    font-size: 0.9rem;
  }
  
  .footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.85rem;
    position: relative;
    z-index: 1;
  }
  
  .footer-nav {
    display: flex;
    gap: 1.5rem;
  }
  
  .footer-nav a {
    color: white;
    opacity: 0.8;
    transition: opacity 0.3s ease;
  }
  
  .footer-nav a:hover {
    opacity: 1;
  }
  
  /* Responsive Styles */
  @media (max-width: 992px) {
    .intro-section {
      flex-direction: column;
    }
    
    .quote-box {
      width: 100%;
    }
    
    .organigramme-container {
      flex-direction: column;
    }
    
    .organigramme {
      flex: 0 0 auto;
      width: 100%;
    }
    
    .elections-grid {
      grid-template-columns: 1fr;
    }
    
    .contact-cards {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .footer-content {
      grid-template-columns: repeat(2, 1fr);
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
      padding: 1rem;
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
      margin-bottom: 1rem;
    }
    
    .nav-item {
      margin: 0;
      margin-bottom: 0.8rem;
    }
    
    .dropdown-menu {
      position: static;
      box-shadow: none;
      padding-left: 1rem;
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
    
    .page-title h1 {
      font-size: 2.2rem;
    }
    
    .tabs-header {
      flex-direction: column;
    }
    
    .table-header, .table-row {
      grid-template-columns: 1fr;
    }
    
    .header-cell, .row-cell {
      border-right: none;
      border-bottom: 1px solid var(--border);
    }
    
    .header-cell:last-child, .row-cell:last-child {
      border-bottom: none;
    }
    
    .contact-cards {
      grid-template-columns: 1fr;
    }
    
    .footer-content {
      grid-template-columns: 1fr;
    }
    
    .footer-bottom {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }
    
    .footer-nav {
      flex-direction: column;
      gap: 0.6rem;
    }

    /* Updated mobile responsive styles */
    .organigramme-section .organigramme-container {
      flex-direction: column;
      gap: 1.5rem;
      min-height: auto;
    }
    
    .organigramme-section .organigramme-left {
      flex: none;
      width: 100%;
    }
    
    .organigramme-section .organigramme-right {
      flex: none;
      width: 100%;
    }
    
    .organigramme-section .organigramme {
      max-width: 100%;
    }
    
    .organigramme-section .organs-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
    }
    
    .organigramme-section .organ-box {
      min-width: auto;
      padding: 0.8rem 0.5rem;
      font-size: 0.9rem;
    }
    
    .organigramme-section .organ-box h3 {
      font-size: 1rem;
      margin-bottom: 0.3rem;
    }
  }
  
  @media (max-width: 576px) {
    .page-title h1 {
      font-size: 1.8rem;
    }
    
    .org-level.split {
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }
    
    .org-arrows-split {
      display: none;
    }
    
    .faq-question h3 {
      font-size: 1rem;
    }
  }
  </style>
