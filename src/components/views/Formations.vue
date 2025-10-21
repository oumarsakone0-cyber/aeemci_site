<template>
    <div class="app">
  
      <!-- Bannière élégante avec motif islamique -->
      <section class="page-banner">
        <div class="banner-pattern"></div>
        <div class="container">
          <div class="page-title">
            <h1>Nos <span class="highlight">Formations</span></h1>
            <p class="subtitle">Développement des compétences et renforcement des capacités</p>
            <div class="breadcrumbs">
              <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">Activités</a> <span class="separator">/</span> <span class="current">Formations</span>
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
                La formation continue est essentielle pour préparer nos membres à relever les défis du monde moderne tout en restant fidèles à leurs valeurs islamiques.
              </blockquote>
              <div class="quote-author">— Département Formation de l'AEEMCI</div>
            </div>
            
            <div class="intro-text">
              <p>L'AEEMCI accorde une importance capitale à la formation de ses membres. Notre programme de formations vise à développer les compétences techniques, managériales et spirituelles des élèves et étudiants musulmans, afin de les préparer à devenir des acteurs de changement dans la société.</p>
              <p>Nos formations sont conçues pour répondre aux besoins spécifiques de la jeunesse musulmane, en combinant excellence professionnelle et valeurs islamiques. Elles sont dispensées par des formateurs qualifiés, issus du monde académique, professionnel et religieux.</p>
            </div>
          </section>
          
          <!-- Types de formations -->
          <section class="formations-section">
            <h2 class="section-title"><span class="highlight">N</span>os Types de Formations</h2>
            
            <div class="formations-tabs">
              <div class="tabs-header">
                <button 
                  v-for="(tab, index) in formationTabs" 
                  :key="index"
                  :class="['tab-btn', { active: activeFormationTab === index }]"
                  @click="setFormationTab(index)"
                >
                  {{ tab.title }}
                </button>
              </div>
              
              <div class="tabs-content">
                <!-- Leadership et Management -->
                <div v-if="activeFormationTab === 0" class="tab-content">
                  <div class="tab-header">
                    <div class="tab-icon">👥</div>
                    <h3>Leadership et Management</h3>
                  </div>
                  
                  <p class="tab-description">
                    Ces formations visent à développer les compétences en leadership et en gestion d'équipe des membres de l'AEEMCI, particulièrement ceux qui occupent ou aspirent à occuper des postes de responsabilité au sein de l'association ou dans leur vie professionnelle.
                  </p>
                  
                  <div class="formations-grid">
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Leadership Islamique</h4>
                        <span class="formation-level">Niveau: Intermédiaire</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation sur les principes du leadership selon la tradition islamique, basée sur les exemples du Prophète Muhammad (SAW) et des grands leaders musulmans.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 3 jours</li>
                          <li><strong>Public cible:</strong> Responsables de sections et membres du bureau</li>
                          <li><strong>Prérequis:</strong> Connaissances de base en Islam</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Gestion de Projets</h4>
                        <span class="formation-level">Niveau: Avancé</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation aux méthodologies modernes de gestion de projets, adaptées au contexte associatif et aux spécificités des activités de l'AEEMCI.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 5 jours</li>
                          <li><strong>Public cible:</strong> Chefs de projets et coordinateurs d'activités</li>
                          <li><strong>Prérequis:</strong> Expérience en gestion d'activités</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Communication et Prise de Parole</h4>
                        <span class="formation-level">Niveau: Débutant à Avancé</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation aux techniques de communication efficace et de prise de parole en public, essentielle pour les représentants de l'association.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 2 jours</li>
                          <li><strong>Public cible:</strong> Tous les membres</li>
                          <li><strong>Prérequis:</strong> Aucun</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Développement Personnel -->
                <div v-if="activeFormationTab === 1" class="tab-content">
                  <div class="tab-header">
                    <div class="tab-icon">🌱</div>
                    <h3>Développement Personnel</h3>
                  </div>
                  
                  <p class="tab-description">
                    Ces formations visent à aider les membres à développer leurs compétences personnelles, à renforcer leur confiance en soi et à améliorer leur bien-être global, dans une perspective islamique.
                  </p>
                  
                  <div class="formations-grid">
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Gestion du Temps et Organisation</h4>
                        <span class="formation-level">Niveau: Débutant</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation aux techniques de gestion du temps et d'organisation personnelle, pour concilier études, activités associatives et pratique religieuse.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 2 jours</li>
                          <li><strong>Public cible:</strong> Tous les membres</li>
                          <li><strong>Prérequis:</strong> Aucun</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Intelligence Émotionnelle</h4>
                        <span class="formation-level">Niveau: Intermédiaire</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation à la gestion des émotions et au développement de l'intelligence émotionnelle, à la lumière des enseignements islamiques.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 3 jours</li>
                          <li><strong>Public cible:</strong> Tous les membres</li>
                          <li><strong>Prérequis:</strong> Aucun</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Préparation à la Vie Professionnelle</h4>
                        <span class="formation-level">Niveau: Avancé</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation pour préparer les étudiants à leur entrée dans la vie professionnelle : CV, entretiens, networking, éthique professionnelle islamique.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 4 jours</li>
                          <li><strong>Public cible:</strong> Étudiants en fin de cycle</li>
                          <li><strong>Prérequis:</strong> Être en dernière année d'études</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Compétences Techniques -->
                <div v-if="activeFormationTab === 2" class="tab-content">
                  <div class="tab-header">
                    <div class="tab-icon">💻</div>
                    <h3>Compétences Techniques</h3>
                  </div>
                  
                  <p class="tab-description">
                    Ces formations visent à développer des compétences techniques spécifiques, particulièrement dans le domaine du numérique, pour permettre aux membres de l'AEEMCI de s'adapter aux évolutions technologiques.
                  </p>
                  
                  <div class="formations-grid">
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Outils Numériques Collaboratifs</h4>
                        <span class="formation-level">Niveau: Débutant</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation à l'utilisation des outils numériques collaboratifs pour améliorer l'efficacité du travail en équipe au sein de l'association.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 2 jours</li>
                          <li><strong>Public cible:</strong> Tous les membres</li>
                          <li><strong>Prérequis:</strong> Connaissances de base en informatique</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Communication Digitale</h4>
                        <span class="formation-level">Niveau: Intermédiaire</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation aux techniques de communication digitale : gestion des réseaux sociaux, création de contenu, stratégie de communication en ligne.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 3 jours</li>
                          <li><strong>Public cible:</strong> Responsables communication</li>
                          <li><strong>Prérequis:</strong> Maîtrise des outils informatiques de base</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Initiation à la Programmation</h4>
                        <span class="formation-level">Niveau: Débutant à Intermédiaire</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation d'initiation à la programmation informatique, pour développer des compétences techniques recherchées sur le marché du travail.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 5 jours</li>
                          <li><strong>Public cible:</strong> Membres intéressés par l'informatique</li>
                          <li><strong>Prérequis:</strong> Connaissances de base en informatique</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Formation Islamique -->
                <div v-if="activeFormationTab === 3" class="tab-content">
                  <div class="tab-header">
                    <div class="tab-icon">☪️</div>
                    <h3>Formation Islamique</h3>
                  </div>
                  
                  <p class="tab-description">
                    Ces formations visent à approfondir les connaissances islamiques des membres, à renforcer leur pratique religieuse et à les aider à appliquer les principes islamiques dans leur vie quotidienne.
                  </p>
                  
                  <div class="formations-grid">
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Sciences du Coran</h4>
                        <span class="formation-level">Niveau: Débutant à Avancé</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation aux sciences du Coran : histoire de la révélation, méthodes d'interprétation, thématiques principales, application dans la vie quotidienne.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> Cycle de 10 séances</li>
                          <li><strong>Public cible:</strong> Tous les membres</li>
                          <li><strong>Prérequis:</strong> Variables selon le niveau</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Fiqh de l'Étudiant Musulman</h4>
                        <span class="formation-level">Niveau: Intermédiaire</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation sur les règles islamiques spécifiques à la vie étudiante : gestion du temps d'études, relations avec les non-musulmans, participation aux activités universitaires, etc.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 3 jours</li>
                          <li><strong>Public cible:</strong> Étudiants musulmans</li>
                          <li><strong>Prérequis:</strong> Connaissances de base en Islam</li>
                        </ul>
                      </div>
                    </div>
                    
                    <div class="formation-card">
                      <div class="formation-header">
                        <h4>Da'wa et Dialogue Interreligieux</h4>
                        <span class="formation-level">Niveau: Avancé</span>
                      </div>
                      <div class="formation-content">
                        <p>Formation aux méthodes de da'wa (invitation à l'Islam) et aux techniques de dialogue interreligieux, dans le respect mutuel et la compréhension.</p>
                        <ul class="formation-details">
                          <li><strong>Durée:</strong> 4 jours</li>
                          <li><strong>Public cible:</strong> Membres impliqués dans la da'wa</li>
                          <li><strong>Prérequis:</strong> Bonnes connaissances en Islam</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          
          <!-- Calendrier des formations -->
          <!-- Liste des formations avec statut -->
          <section class="formations-list-section">
            <h2 class="section-title"><span class="highlight">N</span>os Formations</h2>
            
            <div class="formations-filter">
              <button 
                v-for="(filter, index) in statusFilters" 
                :key="index"
                :class="['filter-btn', { active: activeStatusFilter === filter.value }]"
                @click="setStatusFilter(filter.value)"
              >
                {{ filter.label }}
              </button>
            </div>
            
            <div class="formations-list">
              <div 
                v-for="(formation, index) in filteredFormations" 
                :key="index"
                class="formation-list-item"
              >
                <div class="formation-status" :class="formation.status">
                  {{ getStatusLabel(formation.status) }}
                </div>
                
                <div class="formation-info">
                  <h3 class="formation-title">{{ formation.title }}</h3>
                  <div class="formation-meta">
                    <div class="meta-item">
                      <span class="meta-icon">📅</span>
                      <span>{{ formation.period }}</span>
                    </div>
                    <div class="meta-item">
                      <span class="meta-icon">📍</span>
                      <span>{{ formation.location }}</span>
                    </div>
                    <div class="meta-item">
                      <span class="meta-icon">👥</span>
                      <span>{{ formation.category }}</span>
                    </div>
                  </div>
                  <p class="formation-description">{{ formation.description }}</p>
                </div>
                
                <div class="formation-actions">
                  <!-- Bouton d'inscription pour les formations en cours -->
                  <button 
                    v-if="formation.status === 'in-progress'" 
                    class="btn btn-primary"
                    @click="openInscriptionModal(formation)"
                  >
                    S'inscrire
                  </button>
                  
                  <!-- Liens vers les ressources pour les formations terminées -->
                  <div v-if="formation.status === 'completed'" class="formation-resources">
                    <a v-if="formation.pdf" :href="formation.pdf" class="resource-link pdf" target="_blank">
                      <span class="resource-icon">📄</span>
                      <span>Résumé PDF</span>
                    </a>
                    <a v-if="formation.video" :href="formation.video" class="resource-link video" target="_blank">
                      <span class="resource-icon">🎬</span>
                      <span>Vidéo</span>
                    </a>
                  </div>
                  
                  <!-- Bouton pour les formations à venir -->
                  <button 
                    v-if="formation.status === 'upcoming'" 
                    class="btn btn-secondary"
                    @click="addToReminder(formation)"
                  >
                    Me rappeler
                  </button>
                </div>
              </div>
              
              <!-- Message si aucune formation ne correspond au filtre -->
              <div v-if="filteredFormations.length === 0" class="no-formations">
                <p>Aucune formation ne correspond à ce filtre pour le moment.</p>
              </div>
            </div>
            
            <!-- Modal d'inscription -->
            <div class="modal" v-if="showInscriptionModal" @click="closeInscriptionModal">
              <div class="modal-content" @click.stop>
                <button class="modal-close" @click="closeInscriptionModal">×</button>
                <h3 class="modal-title">Inscription à la formation</h3>
                <h4 class="modal-subtitle">{{ selectedFormation.title }}</h4>
                
                <form @submit.prevent="submitInscription" class="inscription-form-simple">
                  <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" v-model="inscriptionForm.name" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" v-model="inscriptionForm.email" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="phone">Contact téléphonique</label>
                    <input type="tel" id="phone" v-model="inscriptionForm.phone" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="section">Section / Sous-comité</label>
                    <input type="text" id="section" v-model="inscriptionForm.section" required>
                  </div>
                  
                  <button type="submit" class="btn btn-primary btn-block">Confirmer mon inscription</button>
                </form>
              </div>
            </div>
            
            <!-- Notification de confirmation -->
            <div class="notification" v-if="showNotification">
              <div class="notification-content">
                <span class="notification-icon">✅</span>
                <span class="notification-message">{{ notificationMessage }}</span>
              </div>
            </div>
          </section>
          
          <!-- Formateurs -->
          <section class="formateurs-section">
            <h2 class="section-title"><span class="highlight">N</span>os Formateurs</h2>
            
            <div class="formateurs-slider">
              <div class="slider-container" ref="sliderContainer">
                <div 
                  class="formateur-card" 
                  v-for="(formateur, index) in formateurs" 
                  :key="index"
                  :style="{ transform: `translateX(${-currentSlide * 100}%)` }"
                >
                  <div class="formateur-photo">
                    <img :src="formateur.photo" :alt="formateur.name" />
                  </div>
                  <div class="formateur-info">
                    <h3 class="formateur-name">{{ formateur.name }}</h3>
                    <div class="formateur-title">{{ formateur.title }}</div>
                    <p class="formateur-bio">{{ formateur.bio }}</p>
                    <div class="formateur-specialites">
                      <span class="specialite-tag" v-for="(specialite, i) in formateur.specialites" :key="i">
                        {{ specialite }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="slider-controls">
                <button class="slider-nav prev" @click="prevSlide" :disabled="currentSlide === 0">❮</button>
                <div class="slider-dots">
                  <button 
                    v-for="(_, index) in Math.ceil(formateurs.length / slidesPerView)" 
                    :key="index"
                    :class="['slider-dot', { active: Math.floor(currentSlide / slidesPerView) === index }]"
                    @click="goToSlide(index * slidesPerView)"
                  ></button>
                </div>
                <button class="slider-nav next" @click="nextSlide" :disabled="currentSlide >= formateurs.length - slidesPerView">❯</button>
              </div>
            </div>
          </section>
          
          <!-- Témoignages -->
          <section class="testimonials-section">
            <h2 class="section-title"><span class="highlight">T</span>émoignages</h2>
            
            <div class="testimonials-container">
              <div class="testimonials-slider">
                <div class="testimonial-slide" v-if="currentTestimonial === 0">
                  <div class="testimonial-content">
                    <div class="testimonial-quote">"</div>
                    <p>La formation en leadership islamique a complètement transformé ma vision du leadership. J'ai appris à diriger avec humilité, compassion et justice, tout en restant efficace. Ces principes m'ont aidé non seulement dans mes responsabilités au sein de l'AEEMCI, mais aussi dans ma vie professionnelle où je dirige maintenant une équipe de 15 personnes.</p>
                    <div class="testimonial-author">
                      <img src="https://i.ibb.co/YQnkf7L/islamic-conference.jpg" alt="Konaté Ibrahim" class="author-img" />
                      <div class="author-info">
                        <div class="author-name">Konaté Ibrahim</div>
                        <div class="author-title">Ancien Secrétaire Régional, Promotion 2018</div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="testimonial-slide" v-if="currentTestimonial === 1">
                  <div class="testimonial-content">
                    <div class="testimonial-quote">"</div>
                    <p>La formation en communication digitale m'a donné les compétences nécessaires pour gérer efficacement les réseaux sociaux de notre section. Grâce aux techniques apprises, nous avons pu augmenter notre visibilité en ligne et attirer plus de jeunes musulmans vers nos activités. Je recommande vivement cette formation à tous les responsables communication de l'AEEMCI.</p>
                    <div class="testimonial-author">
                      <img src="https://i.ibb.co/Qj9JNHW/quran-recitation.jpg" alt="Touré Aminata" class="author-img" />
                      <div class="author-info">
                        <div class="author-name">Touré Aminata</div>
                        <div class="author-title">Responsable Communication, Section Université FHB</div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="testimonial-slide" v-if="currentTestimonial === 2">
                  <div class="testimonial-content">
                    <div class="testimonial-quote">"</div>
                    <p>Le cycle de formation sur les sciences du Coran a profondément enrichi ma compréhension du Livre sacré. J'ai découvert des méthodes d'interprétation que j'ignorais et appris à contextualiser les versets. Cette formation m'a permis de mieux appliquer les enseignements coraniques dans ma vie quotidienne et de répondre aux questions de mes camarades non-musulmans avec plus d'assurance.</p>
                    <div class="testimonial-author">
                      <img src="https://i.ibb.co/Lk5M0bL/islamic-students.jpg" alt="Diallo Mamadou" class="author-img" />
                      <div class="author-info">
                        <div class="author-name">Diallo Mamadou</div>
                        <div class="author-title">Étudiant en Médecine, 3ème année</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="testimonial-controls">
                <button 
                  v-for="(_, index) in 3" 
                  :key="index" 
                  :class="['control-dot', { active: currentTestimonial === index }]"
                  @click="setTestimonial(index)"
                ></button>
              </div>
            </div>
          </section>
          
          <!-- Comment s'inscrire -->
          <section class="inscription-section">
            <h2 class="section-title"><span class="highlight">C</span>omment s'inscrire</h2>
            
            <div class="inscription-content">
              <div class="inscription-steps">
                <div class="step-item">
                  <div class="step-number">1</div>
                  <div class="step-content">
                    <h3>Consulter le calendrier</h3>
                    <p>Consultez notre calendrier des formations pour identifier celles qui correspondent à vos besoins et disponibilités.</p>
                  </div>
                </div>
                
                <div class="step-item">
                  <div class="step-number">2</div>
                  <div class="step-content">
                    <h3>Vérifier les prérequis</h3>
                    <p>Assurez-vous de remplir les conditions requises pour participer à la formation choisie (niveau, expérience, etc.).</p>
                  </div>
                </div>
                
                <div class="step-item">
                  <div class="step-number">3</div>
                  <div class="step-content">
                    <h3>Remplir le formulaire</h3>
                    <p>Complétez le formulaire d'inscription en ligne ou contactez le responsable des formations de votre section.</p>
                  </div>
                </div>
                
                <div class="step-item">
                  <div class="step-number">4</div>
                  <div class="step-content">
                    <h3>Confirmer votre participation</h3>
                    <p>Après validation de votre inscription, vous recevrez une confirmation par email avec tous les détails pratiques.</p>
                  </div>
                </div>
              </div>
              
              <div class="inscription-form">
                <h3>Demande d'information</h3>
                <form @submit.prevent="submitContactForm">
                  <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" v-model="contactForm.name" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" v-model="contactForm.email" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" v-model="contactForm.phone" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="formation">Formation d'intérêt</label>
                    <select id="formation" v-model="contactForm.formation" required>
                      <option value="">Sélectionnez une formation</option>
                      <option value="leadership">Leadership Islamique</option>
                      <option value="gestion">Gestion de Projets</option>
                      <option value="communication">Communication et Prise de Parole</option>
                      <option value="temps">Gestion du Temps</option>
                      <option value="emotionnelle">Intelligence Émotionnelle</option>
                      <option value="professionnelle">Préparation à la Vie Professionnelle</option>
                      <option value="numerique">Outils Numériques Collaboratifs</option>
                      <option value="digitale">Communication Digitale</option>
                      <option value="programmation">Initiation à la Programmation</option>
                      <option value="coran">Sciences du Coran</option>
                      <option value="fiqh">Fiqh de l'Étudiant Musulman</option>
                      <option value="dawa">Da'wa et Dialogue Interreligieux</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" v-model="contactForm.message" rows="4" required></textarea>
                  </div>
                  
                  <button type="submit" class="btn btn-primary">Envoyer ma demande</button>
                </form>
              </div>
            </div>
          </section>
          
          <!-- Galerie photos -->
          <section class="gallery-section">
            <h2 class="section-title"><span class="highlight">G</span>alerie Photos</h2>
            
            <div class="gallery-grid">
              <div class="gallery-item" v-for="(image, index) in galleryImages" :key="index" @click="openLightbox(index)">
                <div class="gallery-image">
                  <img :src="image.thumbnail" :alt="image.caption" />
                  <div class="image-overlay">
                    <div class="image-caption">{{ image.caption }}</div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Lightbox -->
            <div class="lightbox" v-if="lightboxOpen" @click="closeLightbox">
              <div class="lightbox-content" @click.stop>
                <button class="lightbox-close" @click="closeLightbox">×</button>
                <img :src="galleryImages[currentImage].full" :alt="galleryImages[currentImage].caption" class="lightbox-img" />
                <div class="lightbox-caption">{{ galleryImages[currentImage].caption }}</div>
                <button class="lightbox-nav prev" @click.stop="prevImage">❮</button>
                <button class="lightbox-nav next" @click.stop="nextImage">❯</button>
              </div>
            </div>
          </section>
        </div>
      </main>
  
      <!-- Footer -->
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  
  
  // Onglets de formations
  const formationTabs = [
    { title: 'Leadership et Management' },
    { title: 'Développement Personnel' },
    { title: 'Compétences Techniques' },
    { title: 'Formation Islamique' }
  ];
  
  const activeFormationTab = ref(0);
  
  const setFormationTab = (index) => {
    activeFormationTab.value = index;
  };
  
  // Filtres de statut
  const statusFilters = [
    { label: 'Toutes', value: 'all' },
    { label: 'En cours', value: 'in-progress' },
    { label: 'À venir', value: 'upcoming' },
    { label: 'Terminées', value: 'completed' }
  ];
  
  const activeStatusFilter = ref('all');
  
  const setStatusFilter = (filter) => {
    activeStatusFilter.value = filter;
  };
  
  // Liste des formations
  const formationsList = [
    {
      title: 'Leadership Islamique',
      period: '15 Mai - 17 Mai 2023',
      location: 'Siège de l\'AEEMCI, Abidjan',
      category: 'Leadership et Management',
      description: 'Formation sur les principes du leadership selon la tradition islamique, basée sur les exemples du Prophète Muhammad (SAW) et des grands leaders musulmans.',
      status: 'completed',
      pdf: 'https://example.com/leadership-islamique.pdf',
      video: 'https://example.com/leadership-islamique.mp4'
    },
    {
      title: 'Gestion de Projets',
      period: '10 Juin - 14 Juin 2023',
      location: 'INP-HB, Yamoussoukro',
      category: 'Leadership et Management',
      description: 'Formation aux méthodologies modernes de gestion de projets, adaptées au contexte associatif et aux spécificités des activités de l\'AEEMCI.',
      status: 'completed',
      pdf: 'https://example.com/gestion-projets.pdf',
      video: null
    },
    {
      title: 'Communication et Prise de Parole',
      period: '20 Juin - 21 Juin 2023',
      location: 'Université FHB, Abidjan',
      category: 'Leadership et Management',
      description: 'Formation aux techniques de communication efficace et de prise de parole en public, essentielle pour les représentants de l\'association.',
      status: 'in-progress',
      pdf: null,
      video: null
    },
    {
      title: 'Gestion du Temps et Organisation',
      period: '5 Juillet - 6 Juillet 2023',
      location: 'Section Cocody, Abidjan',
      category: 'Développement Personnel',
      description: 'Formation aux techniques de gestion du temps et d\'organisation personnelle, pour concilier études, activités associatives et pratique religieuse.',
      status: 'upcoming',
      pdf: null,
      video: null
    },
    {
      title: 'Intelligence Émotionnelle',
      period: '15 Juillet - 17 Juillet 2023',
      location: 'Siège de l\'AEEMCI, Abidjan',
      category: 'Développement Personnel',
      description: 'Formation à la gestion des émotions et au développement de l\'intelligence émotionnelle, à la lumière des enseignements islamiques.',
      status: 'upcoming',
      pdf: null,
      video: null
    },
    {
      title: 'Outils Numériques Collaboratifs',
      period: '1 Juin - 2 Juin 2023',
      location: 'Université Nangui Abrogoua, Abidjan',
      category: 'Compétences Techniques',
      description: 'Formation à l\'utilisation des outils numériques collaboratifs pour améliorer l\'efficacité du travail en équipe au sein de l\'association.',
      status: 'completed',
      pdf: 'https://example.com/outils-numeriques.pdf',
      video: 'https://example.com/outils-numeriques.mp4'
    },
    {
      title: 'Communication Digitale',
      period: '25 Juin - 27 Juin 2023',
      location: 'Siège de l\'AEEMCI, Abidjan',
      category: 'Compétences Techniques',
      description: 'Formation aux techniques de communication digitale : gestion des réseaux sociaux, création de contenu, stratégie de communication en ligne.',
      status: 'in-progress',
      pdf: null,
      video: null
    },
    {
      title: 'Sciences du Coran',
      period: 'Tous les samedis de Juin à Août 2023',
      location: 'Mosquée An Nour, Abidjan',
      category: 'Formation Islamique',
      description: 'Formation aux sciences du Coran : histoire de la révélation, méthodes d\'interprétation, thématiques principales, application dans la vie quotidienne.',
      status: 'in-progress',
      pdf: null,
      video: null
    }
  ];
  
  const filteredFormations = computed(() => {
    if (activeStatusFilter.value === 'all') {
      return formationsList;
    } else {
      return formationsList.filter(formation => formation.status === activeStatusFilter.value);
    }
  });
  
  const getStatusLabel = (status) => {
    switch (status) {
      case 'completed':
        return 'Terminée';
      case 'in-progress':
        return 'En cours';
      case 'upcoming':
        return 'À venir';
      default:
        return '';
    }
  };
  
  // Modal d'inscription
  const showInscriptionModal = ref(false);
  const selectedFormation = ref({});
  const inscriptionForm = ref({
    name: '',
    email: '',
    phone: '',
    section: ''
  });
  
  const openInscriptionModal = (formation) => {
    selectedFormation.value = formation;
    showInscriptionModal.value = true;
    document.body.style.overflow = 'hidden';
  };
  
  const closeInscriptionModal = () => {
    showInscriptionModal.value = false;
    document.body.style.overflow = 'auto';
  };
  
  // Notification
  const showNotification = ref(false);
  const notificationMessage = ref('');
  
  const submitInscription = () => {
    // Ici, vous pourriez implémenter l'envoi du formulaire à un backend
    notificationMessage.value = `Votre inscription à la formation "${selectedFormation.value.title}" a été enregistrée avec succès !`;
    showNotification.value = true;
    
    // Fermer le modal
    closeInscriptionModal();
    
    // Réinitialiser le formulaire
    inscriptionForm.value = {
      name: '',
      email: '',
      phone: '',
      section: ''
    };
    
    // Masquer la notification après 3 secondes
    setTimeout(() => {
      showNotification.value = false;
    }, 3500);
  };
  
  // Fonction pour ajouter un rappel
  const addToReminder = (formation) => {
    notificationMessage.value = `Un rappel a été configuré pour la formation "${formation.title}"`;
    showNotification.value = true;
    
    // Masquer la notification après 3 secondes
    setTimeout(() => {
      showNotification.value = false;
    }, 3500);
  };
  
  // Formateurs
  const formateurs = [
    {
      name: 'Dr. Ouattara Ibrahim',
      title: 'Spécialiste en Leadership et Management',
      bio: 'Docteur en Sciences de Gestion, expert en leadership islamique et management d\'équipe. Il a plus de 15 ans d\'expérience dans la formation des cadres associatifs et d\'entreprise.',
      photo: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      specialites: ['Leadership Islamique', 'Gestion de Projets', 'Management d\'Équipe']
    },
    {
      name: 'Mme Touré Aminata',
      title: 'Experte en Communication Digitale',
      bio: 'Spécialiste en communication digitale et réseaux sociaux, avec une expérience de 10 ans dans le domaine. Elle a travaillé pour plusieurs ONG internationales avant de rejoindre l\'équipe de formateurs de l\'AEEMCI.',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      specialites: ['Communication Digitale', 'Réseaux Sociaux', 'Marketing Digital']
    },
    {
      name: 'Imam Koné Mamadou',
      title: 'Spécialiste en Sciences Islamiques',
      bio: 'Imam et enseignant en sciences islamiques, diplômé de l\'Université Al-Azhar. Il est spécialisé dans les sciences du Coran et le fiqh, avec une approche pédagogique adaptée aux jeunes.',
      photo: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      specialites: ['Sciences du Coran', 'Fiqh', 'Da\'wa']
    },
    {
      name: 'M. Diallo Souleymane',
      title: 'Expert en Développement Personnel',
      bio: 'Coach certifié en développement personnel et intelligence émotionnelle. Il accompagne les jeunes dans leur développement personnel et professionnel depuis plus de 8 ans.',
      photo: 'https://i.ibb.co/Qf7mZwV/islamic-education.jpg',
      specialites: ['Intelligence Émotionnelle', 'Gestion du Temps', 'Préparation Professionnelle']
    },
    {
      name: 'Mme Bamba Mariam',
      title: 'Experte en Programmation',
      bio: 'Ingénieure en informatique et formatrice en programmation. Elle a travaillé pour plusieurs entreprises technologiques avant de se consacrer à la formation des jeunes.',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      specialites: ['Programmation', 'Outils Numériques', 'Technologies Web']
    }
  ];
  
  const currentSlide = ref(0);
  const slidesPerView = ref(3);
  
  const prevSlide = () => {
    if (currentSlide.value > 0) {
      currentSlide.value--;
    }
  };
  
  const nextSlide = () => {
    if (currentSlide.value < formateurs.length - slidesPerView.value) {
      currentSlide.value++;
    }
  };
  
  const goToSlide = (index) => {
    currentSlide.value = index;
  };
  
  // Ajuster le nombre de slides par vue en fonction de la largeur de l'écran
  const updateSlidesPerView = () => {
    if (window.innerWidth < 768) {
      slidesPerView.value = 1;
    } else if (window.innerWidth < 992) {
      slidesPerView.value = 2;
    } else {
      slidesPerView.value = 3;
    }
  };
  
  // Témoignages
  const currentTestimonial = ref(0);
  
  const setTestimonial = (index) => {
    currentTestimonial.value = index;
  };
  
  // Rotation automatique des témoignages
  let testimonialInterval;
  
  const startTestimonialRotation = () => {
    testimonialInterval = setInterval(() => {
      currentTestimonial.value = (currentTestimonial.value + 1) % 3;
    }, 8000);
  };
  
  // Galerie photos
  const galleryImages = [
    {
      thumbnail: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      full: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      caption: 'Formation Leadership Islamique - Session 2023'
    },
    {
      thumbnail: 'https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg',
      full: 'https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg',
      caption: 'Atelier Gestion de Projets à Yamoussoukro'
    },
    {
      thumbnail: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      full: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      caption: 'Formation Sciences du Coran - Travaux de groupe'
    },
    {
      thumbnail: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      full: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      caption: 'Séminaire Communication et Prise de Parole'
    },
    {
      thumbnail: 'https://i.ibb.co/Qf7mZwV/islamic-education.jpg',
      full: 'https://i.ibb.co/Qf7mZwV/islamic-education.jpg',
      caption: 'Formation Outils Numériques Collaboratifs'
    },
    {
      thumbnail: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      full: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      caption: 'Remise de certificats - Formation Da\'wa et Dialogue Interreligieux'
    }
  ];
  
  const lightboxOpen = ref(false);
  const currentImage = ref(0);
  
  const openLightbox = (index) => {
    currentImage.value = index;
    lightboxOpen.value = true;
    document.body.style.overflow = 'hidden';
  };
  
  const closeLightbox = () => {
    lightboxOpen.value = false;
    document.body.style.overflow = 'auto';
  };
  
  const nextImage = () => {
    currentImage.value = (currentImage.value + 1) % galleryImages.length;
  };
  
  const prevImage = () => {
    currentImage.value = (currentImage.value - 1 + galleryImages.length) % galleryImages.length;
  };
  
  // Formulaire de contact
  const contactForm = ref({
    name: '',
    email: '',
    phone: '',
    formation: '',
    message: ''
  });
  
  const submitContactForm = () => {
    // Ici, vous pourriez implémenter l'envoi du formulaire à un backend
    alert('Votre demande d\'information a été envoyée avec succès ! Nous vous contacterons prochainement.');
    // Réinitialiser le formulaire
    contactForm.value = {
      name: '',
      email: '',
      phone: '',
      formation: '',
      message: ''
    };
  };

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
  
  // Initialisation
  onMounted(() => {

    document.querySelectorAll('.period-card, .intro-section, .testimonials-section, .gallery-section, .stats-grid').forEach(el => {
      observer.observe(el);
    });
  
    // Démarrer la rotation des témoignages
    startTestimonialRotation();
  
    // Écouter les touches du clavier pour la galerie
    document.addEventListener('keydown', (e) => {
      if (!lightboxOpen.value) return;
      
      if (e.key === 'Escape') {
        closeLightbox();
      } else if (e.key === 'ArrowRight') {
        nextImage();
      } else if (e.key === 'ArrowLeft') {
        prevImage();
      }
    });
  
    // Ajuster le nombre de slides par vue
    updateSlidesPerView();
    window.addEventListener('resize', updateSlidesPerView);
  });
  
  onBeforeUnmount(() => {
    // Nettoyer les écouteurs d'événements
    document.removeEventListener('keydown', () => {});
    window.removeEventListener('resize', updateSlidesPerView);
    
    // Arrêter la rotation des témoignages
    clearInterval(testimonialInterval);
  });
  </script>
  <script>
  // Pour respecter la règle multi-word
  export default {
    name: 'FormaTions'
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
    --accent: #e67e22;
    --accent-dark: #d35400;
    --accent-light: #f39c12;
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
  
  /* Formations Tabs */
  .formations-section {
    margin-bottom: 4rem;
  }
  
  .formations-tabs {
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
  
  .tab-content {
    animation: fadeIn 0.5s ease;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .tab-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  
  .tab-icon {
    font-size: 2rem;
    margin-right: 1rem;
    color: var(--accent);
  }
  
  .tab-header h3 {
    font-size: 1.5rem;
    color: var(--accent);
    margin: 0;
  }
  
  .tab-description {
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 2rem;
  }
  
  .formations-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .formation-card {
    background-color: var(--background-alt);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }
  
  .formation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .formation-header {
    background-color: var(--accent);
    color: white;
    padding: 1rem;
    position: relative;
  }
  
  .formation-header h4 {
    font-size: 1.2rem;
    margin: 0 0 0.5rem 0;
  }
  
  .formation-level {
    font-size: 0.8rem;
    opacity: 0.9;
  }
  
  .formation-content {
    padding: 1.5rem;
  }
  
  .formation-content p {
    margin-bottom: 1rem;
    line-height: 1.6;
  }
  
  .formation-details {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }
  
  .formation-details li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
  }
  
  /* Calendar Section */
  .calendar-section {
    margin-bottom: 4rem;
  }
  
  .calendar-container {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  
  .calendar-title {
    font-size: 1.5rem;
    color: var(--primary);
    margin: 0;
  }
  
  .calendar-nav {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--primary);
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
  }
  
  .calendar-nav:hover {
    background-color: var(--primary-lighter);
  }
  
  .calendar-grid {
    margin-bottom: 2rem;
  }
  
  .calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
    margin-bottom: 0.5rem;
  }
  
  .day-header {
    text-align: center;
    font-weight: 600;
    color: var(--text-secondary);
    padding: 0.5rem;
  }
  
  .calendar-dates {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
  }
  
  .date-cell {
    height: 60px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
  }
  
  .date-cell:hover {
    background-color: var(--primary-lighter);
  }
  
  .date-number {
    font-size: 1.1rem;
    font-weight: 600;
  }
  
  .other-month .date-number {
    opacity: 0.3;
  }
  
  .date-indicator {
    width: 6px;
    height: 6px;
    background-color: var(--accent);
    border-radius: 50%;
    position: absolute;
    bottom: 8px;
  }
  
  .has-event {
    background-color: var(--primary-lighter);
  }
  
  .today {
    background-color: var(--primary);
    color: white;
  }
  
  .today .date-indicator {
    background-color: white;
  }
  
  .events-list {
    background-color: var(--background-alt);
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1.5rem;
  }
  
  .events-title {
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 1rem;
  }
  
  .event-item {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }
  
  .event-time {
    flex: 0 0 120px;
    font-weight: 600;
    color: var(--accent);
  }
  
  .event-details {
    flex: 1;
  }
  
  .event-title {
    font-weight: 600;
    margin-bottom: 0.3rem;
  }
  
  .event-location {
    font-size: 0.9rem;
    color: var(--text-secondary);
  }
  
  .event-action {
    padding: 0.5rem 1rem;
    background-color: var(--primary);
    color: white;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  
  .event-action:hover {
    background-color: var(--primary-dark);
    color: white;
  }
  
  .no-events {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
  }
  
  /* Formateurs Section */
  .formateurs-section {
    margin-bottom: 4rem;
  }
  
  .formateurs-slider {
    position: relative;
    overflow: hidden;
  }
  
  .slider-container {
    display: flex;
    transition: all 0.5s ease;
    overflow: hidden;
  }
  
  .formateur-card {
    flex: 0 0 calc(100% / 3);
    padding: 1rem;
    transition: transform 0.5s ease;
  }
  
  .formateur-photo {
    height: 200px;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
  }
  
  .formateur-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .formateur-info {
    padding: 1rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .formateur-name {
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 0.3rem;
  }
  
  .formateur-title {
    font-size: 0.9rem;
    color: var(--accent);
    margin-bottom: 1rem;
    font-weight: 600;
  }
  
  .formateur-bio {
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 1rem;
  }
  
  .formateur-specialites {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  
  .specialite-tag {
    background-color: var(--primary-lighter);
    color: var(--primary);
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
  }
  
  .slider-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 2rem;
  }
  
  .slider-nav {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--primary);
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
  }
  
  .slider-nav:hover:not(:disabled) {
    background-color: var(--primary-lighter);
  }
  
  .slider-nav:disabled {
    opacity: 0.3;
    cursor: not-allowed;
  }
  
  .slider-dots {
    display: flex;
    gap: 0.5rem;
    margin: 0 1rem;
  }
  
  .slider-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: var(--border);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .slider-dot.active {
    background-color: var(--primary);
    transform: scale(1.2);
  }
  
  /* Testimonials Section */
  .testimonials-section {
    margin-bottom: 4rem;
  }
  
  .testimonials-container {
    max-width: 800px;
    margin: 0 auto;
  }
  
  .testimonials-slider {
    position: relative;
    margin-bottom: 1.5rem;
  }
  
  .testimonial-slide {
    animation: fadeIn 0.5s ease;
  }
  
  .testimonial-content {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    position: relative;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .testimonial-quote {
    position: absolute;
    top: 1rem;
    left: 1rem;
    font-size: 4rem;
    color: var(--primary-lighter);
    opacity: 0.5;
    line-height: 1;
  }
  
  .testimonial-content p {
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 1;
  }
  
  .testimonial-author {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .author-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
  }
  
  .author-name {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.3rem;
  }
  
  .author-title {
    font-size: 0.9rem;
    color: var(--text-secondary);
  }
  
  .testimonial-controls {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
  }
  
  .control-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--border);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .control-dot.active {
    background-color: var(--primary);
    transform: scale(1.2);
  }
  
  /* Inscription Section */
  .inscription-section {
    margin-bottom: 4rem;
  }
  
  .inscription-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
  }
  
  .inscription-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .step-item {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
  }
  
  .step-number {
    width: 40px;
    height: 40px;
    background-color: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
  }
  
  .step-content h3 {
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .step-content p {
    font-size: 1rem;
    margin-bottom: 0;
  }
  
  .inscription-form {
    background-color: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .inscription-form h3 {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
  }
  
  .form-group input,
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-family: inherit;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }
  
  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(0, 100, 0, 0.1);
  }
  
  .btn {
    display: inline-block;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 1rem;
  }
  
  .btn-primary {
    background-color: var(--primary);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 100, 0, 0.1);
  }
  
  .btn-primary:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 100, 0, 0.15);
  }
  
  /* Gallery Section */
  .gallery-section {
    margin-bottom: 4rem;
  }
  
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .gallery-item {
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .gallery-image {
    position: relative;
    overflow: hidden;
  }
  
  .gallery-image img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  
  .gallery-item:hover .gallery-image img {
    transform: scale(1.05);
  }
  
  .image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
    padding: 1rem;
    color: white;
    transform: translateY(0);
    transition: transform 0.3s ease;
  }
  
  /* Lightbox */
  .lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
  }
  
  .lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
  }
  
  .lightbox-img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 4px;
  }
  
  .lightbox-caption {
    color: white;
    text-align: center;
    padding: 1rem 0;
    font-size: 1rem;
  }
  
  .lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
  }
  
  .lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .lightbox-nav:hover {
    background: rgba(0, 0, 0, 0.8);
  }
  
  .lightbox-nav.prev {
    left: -60px;
  }
  
  .lightbox-nav.next {
    right: -60px;
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
    
    .formations-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .formateur-card {
      flex: 0 0 50%;
    }
    
    .gallery-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .inscription-content {
      grid-template-columns: 1fr;
      gap: 3rem;
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
    
    .formations-grid {
      grid-template-columns: 1fr;
    }
    
    .formateur-card {
      flex: 0 0 100%;
    }
    
    .testimonial-author {
      flex-direction: column;
      text-align: center;
    }
    
    .gallery-grid {
      grid-template-columns: 1fr;
    }
    
    .lightbox-nav.prev {
      left: 10px;
    }
    
    .lightbox-nav.next {
      right: 10px;
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
  }
  
  @media (max-width: 576px) {
    .page-title h1 {
      font-size: 1.8rem;
    }
    
    .calendar-days, .calendar-dates {
      grid-template-columns: repeat(7, 1fr);
      font-size: 0.8rem;
    }
    
    .date-cell {
      height: 40px;
    }
    
    .event-item {
      flex-direction: column;
      align-items: flex-start;
    }
    
    .event-time {
      margin-bottom: 0.5rem;
    }
    
    .event-action {
      margin-top: 1rem;
      width: 100%;
      text-align: center;
    }
  }
  
  /* Liste des formations avec statut */
  .formations-list-section {
    margin-bottom: 4rem;
  }
  
  .formations-filter {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  
  .filter-btn {
    padding: 0.6rem 1.2rem;
    background-color: var(--background-alt);
    border: none;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .filter-btn.active {
    background-color: var(--primary);
    color: white;
  }
  
  .formations-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .formation-list-item {
    display: flex;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    position: relative;
  }
  
  .formation-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
  }
  
  .formation-status.completed {
    background-color: var(--success);
  }
  
  .formation-status.in-progress {
    background-color: var(--accent);
  }
  
  .formation-status.upcoming {
    background-color: var(--info);
  }
  
  .formation-info {
    flex: 1;
    padding: 2rem;
  }
  
  .formation-title {
    font-size: 1.3rem;
    color: var(--primary);
    margin-bottom: 1rem;
  }
  
  .formation-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 1rem;
  }
  
  .meta-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: var(--text-secondary);
  }
  
  .meta-icon {
    margin-right: 0.5rem;
  }
  
  .formation-description {
    line-height: 1.6;
    color: var(--text-primary);
  }
  
  .formation-actions {
    width: 200px;
    padding: 2rem;
    background-color: var(--background-alt);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  .btn-secondary {
    background-color: var(--info);
    color: white;
  }
  
  .btn-secondary:hover {
    background-color: #138496;
  }
  
  .btn-block {
    width: 100%;
  }
  
  .formation-resources {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
  }
  
  .resource-link {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    text-align: center;
    justify-content: center;
  }
  
  .resource-link.pdf {
    background-color: #f44336;
    color: white;
  }
  
  .resource-link.pdf:hover {
    background-color: #d32f2f;
    color: white;
  }
  
  .resource-link.video {
    background-color: #4caf50;
    color: white;
  }
  
  .resource-link.video:hover {
    background-color: #388e3c;
    color: white;
  }
  
  .resource-icon {
    margin-right: 0.5rem;
  }
  
  .no-formations {
    text-align: center;
    padding: 3rem;
    background-color: var(--background-alt);
    border-radius: 8px;
    color: var(--text-secondary);
  }
  
  /* Modal d'inscription */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
  }
  
  .modal-content {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    width: 90%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  }
  
  .modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
  }
  
  .modal-title {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
    text-align: center;
  }
  
  .modal-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  .inscription-form-simple {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  /* Notification */
  .notification {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 2001;
    animation: slideIn 0.3s ease, fadeOut 0.3s ease 3s forwards;
  }
  
  .notification-content {
    background-color: var(--success);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  .notification-icon {
    margin-right: 0.8rem;
    font-size: 1.2rem;
  }
  
  @keyframes slideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes fadeOut {
    from {
      opacity: 1;
    }
    to {
      opacity: 0;
      visibility: hidden;
    }
  }
  
  @media (max-width: 768px) {
    .formation-list-item {
      flex-direction: column;
    }
    
    .formation-actions {
      width: 100%;
      padding: 1.5rem;
    }
    
    .formation-status {
      position: static;
      align-self: flex-start;
      margin-bottom: 1rem;
    }
  }
  </style>