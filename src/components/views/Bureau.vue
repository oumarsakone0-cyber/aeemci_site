<template>
    <div class="app">
  
      <!-- Bannière élégante avec motif islamique -->
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
            <h1>Comité <span class="highlight">Exécutif</span></h1>
            <p class="subtitle">L'équipe dirigeante de l'AEEMCI au service de la jeunesse musulmane</p>
            <div class="breadcrumbs">
              <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">AEEMCI</a> <span class="separator">/</span> <span class="current">Bureau Exécutif</span>
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
                Notre engagement est de servir la communauté estudiantine musulmane avec dévouement, intégrité et excellence, tout en préservant nos valeurs islamiques.
              </blockquote>
              <div class="quote-author">— Président du Bureau Exécutif</div>
            </div>
            
            <div class="intro-text">
              <p>Le Bureau Exécutif National est l'organe dirigeant de l'Association des Élèves et Étudiants Musulmans de Côte d'Ivoire. Élu pour un mandat de deux ans, il est chargé de la mise en œuvre des orientations définies par l'Assemblée Générale et de la gestion quotidienne de l'association.</p>
              <p>Composé de membres dévoués et compétents, le Bureau Exécutif travaille sans relâche pour répondre aux besoins des élèves et étudiants musulmans de Côte d'Ivoire, tout en promouvant les valeurs d'excellence académique, d'éthique islamique et d'engagement citoyen.</p>
            </div>
          </section>

          <section class="org-structure-section">
            <h2 class="section-title"><span class="highlight">Comité</span> Exécutif</h2>
            
            <div class="org-chart">
              <div class="org-level president-level">
                <div class="org-box president-box">
                  <div class="org-title">Amir</div>
                </div>
              </div>
              
              <div class="org-level vp-level">
                <div class="org-box vp-box">
                  <div class="org-title">1er Vice-Amir</div>
                  <div class="org-subtitle">Affaires Académiques et Formation</div>
                </div>
                <div class="org-box vp-box">
                  <div class="org-title">2ème Vice-Amir</div>
                  <div class="org-subtitle">Relations Extérieures</div>
                </div>
                <div class="org-box vp-box">
                  <div class="org-title">Vice-Amirate</div>
                  <div class="org-subtitle">Projets et Développement</div>
                </div>
              </div>
              
              <div class="org-level secretary-level">
                <div class="org-box secretary-box">
                  <div class="org-title">SENAFAD</div>
                  <div class="org-subtitle">Secrétariat National chargé des Affaires Administratives</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENAFI</div>
                  <div class="org-subtitle">Secrétariat National chargé des Affaires Financières</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENAFOCI</div>
                  <div class="org-subtitle">Secrétariat National chargé de la Formation de la Culture et des questions Idéologiques</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENACEF</div>
                  <div class="org-subtitle">Secrétariat National chargé des Cellules Féminines</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENASIP</div>
                  <div class="org-subtitle">Secrétariat National chargé des Affaires Sociales et de l'Insertion Professionnel</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENAES</div>
                  <div class="org-subtitle">Secrétariat National chargé de l'Environnment et du Sport</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENAMO</div>
                  <div class="org-subtitle">Secrétariat National chargé de la Mobilisation et de l'Organisation</div>
                </div>
                <div class="org-box secretary-box">
                  <div class="org-title">SENACREX</div>
                  <div class="org-subtitle">Secrétariat National chargé de la Communication et des Rélations Extérieures</div>
                </div>
              </div>
            </div>
            
           
          </section>
          
          <!-- Président et Vice-Présidents -->
          <section class="leadership-section">
            <h2 class="section-title"><span class="highlight">Bureau</span> Exécutif ( 2025 - 2026)</h2>
            
            <div class="president-card">
              <div class="president-photo">
                <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758277082/aeemci_photo_1758277078928_IMG_0537.jpeg.jpg.jpg" alt="Président de l'AEEMCI" />
              </div>
              <div class="president-info">
                <h3 class="president-name">Youssouf BAMBA</h3>
                <div class="president-title">Amir (Président National)</div>
                <div class="president-mandate">Mandat 2025-2026</div>
                <p class="president-bio">
                  Diplômé en Masteur Professionnel en Informatique et Génie Logiciel de l'Institut Voltaire d'enseignement Technique et Professionnel de Marcory (IVESTP), Bamba Youssouf a rejoint l'AEEMCI dès ses années de lycée. Son parcours au sein de l'association est marqué par un engagement constant et une vision claire pour le développement de la jeunesse musulmane ivoirienne.
                </p>
                <div class="president-quote">
                  <b>Objectif Général</b> : "Péréniser les acquis et renforcer les compétences afin d'assurer le positionnement stratégique de l'AEEMCI"
                </div>
              </div>
            </div>
            
            <div class="vice-presidents">
              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758277228/aeemci_photo_1758277226378_soro.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758277439/aeemci_photo_1758277438595_cherif.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758352913/aeemci_photo_1758352911506_amirate.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758278073/aeemci_photo_1758278070827_ahmed.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758278118/aeemci_photo_1758278117340_bema.png.png" alt="2ème Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758278454/aeemci_photo_1758278452523_thierno.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282658/aeemci_photo_1758282655078_yel.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282736/aeemci_photo_1758282734186_tao.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282778/aeemci_photo_1758282776626_bambara.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282840/aeemci_photo_1758282837880_muba.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282884/aeemci_photo_1758282883177_madous.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758282914/aeemci_photo_1758282913482_baily.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283046/aeemci_photo_1758283043884_jacob.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283085/aeemci_photo_1758283084400_mariam.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283450/aeemci_photo_1758283447885_cisse.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283500/aeemci_photo_1758283499390_sanogo.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283589/aeemci_photo_1758283587775_garanke.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="http://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283621/aeemci_photo_1758283619915_ibrahim.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283652/aeemci_photo_1758283651569_fane.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283679/aeemci_photo_1758283678012_djim.png.png" alt="1er Vice-Président" />
                </div>
              </div>

              <div class="vp-card">
                <div class="vp-photo">
                  <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758283725/aeemci_photo_1758283724443_crex.png.png" alt="1er Vice-Président" />
                </div>
              </div>
              
              
              
              
            </div>
          </section>
          
          
        </div>
      </main>
  
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  
  // État pour les dropdowns
  const dropdowns = ref({
    aeemci: true, // Ouvert par défaut car nous sommes sur la page Bureau Exécutif
    activites: false,
    mediatheque: false
  });
  
  
  // Catégories de membres
  const memberCategories = [
    'Tous',
    'Présidence',
    'Secrétariat',
    'Trésorerie',
    'Départements'
  ];
  
  const activeCategory = ref(0);
  
  const setActiveCategory = (index) => {
    activeCategory.value = index;
  };
  
  // Membres du bureau
  const members = [
    // Présidence
    {
      name: 'Ouattara Ibrahim',
      title: 'Président National',
      bio: 'Diplômé en Génie Civil et titulaire d\'un Master en Management de Projets.',
      email: 'president@aeemci.org',
      photo: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      category: 1
    },
    {
      name: 'Koné Mamadou',
      title: '1er Vice-Président',
      bio: 'Étudiant en Médecine, en charge des affaires académiques et de la formation.',
      email: 'vp1@aeemci.org',
      photo: 'https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg',
      category: 1
    },
    {
      name: 'Touré Aminata',
      title: '2ème Vice-Présidente',
      bio: 'Diplômée en Sciences Économiques, responsable des relations extérieures.',
      email: 'vp2@aeemci.org',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      category: 1
    },
    {
      name: 'Diallo Souleymane',
      title: '3ème Vice-Président',
      bio: 'Ingénieur en Informatique, coordonne les projets de développement.',
      email: 'vp3@aeemci.org',
      photo: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      category: 1
    },
    
    // Secrétariat
    {
      name: 'Bamba Karim',
      title: 'Secrétaire Général',
      bio: 'Diplômé en Droit, responsable de l\'administration et de la coordination des activités.',
      email: 'sg@aeemci.org',
      photo: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      category: 2
    },
    {
      name: 'Cissé Mariam',
      title: 'Secrétaire Générale Adjointe',
      bio: 'Étudiante en Sciences Politiques, assiste le Secrétaire Général dans ses fonctions.',
      email: 'sga@aeemci.org',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      category: 2
    },
    
    // Trésorerie
    {
      name: 'Coulibaly Issouf',
      title: 'Trésorier Général',
      bio: 'Expert-comptable, gère les finances et le budget de l\'association.',
      email: 'tresorier@aeemci.org',
      photo: 'https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg',
      category: 3
    },
    {
      name: 'Sylla Fatoumata',
      title: 'Trésorière Générale Adjointe',
      bio: 'Étudiante en Finance, assiste le Trésorier Général dans la gestion financière.',
      email: 'tresoriere.adjointe@aeemci.org',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      category: 3
    },
    
    // Départements
    {
      name: 'Konaté Moussa',
      title: 'Directeur de l\'Éducation et Formation',
      bio: 'Enseignant, coordonne les programmes éducatifs et les formations islamiques.',
      email: 'education@aeemci.org',
      photo: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      category: 4
    },
    {
      name: 'Diabaté Aïcha',
      title: 'Directrice des Affaires Féminines',
      bio: 'Sociologue, responsable des questions spécifiques aux élèves et étudiantes musulmanes.',
      email: 'affaires.feminines@aeemci.org',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      category: 4
    },
    {
      name: 'Traoré Amadou',
      title: 'Directeur de la Communication',
      bio: 'Journaliste, gère la communication et les relations avec les médias.',
      email: 'communication@aeemci.org',
      photo: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      category: 4
    },
    {
      name: 'Sanogo Fatou',
      title: 'Directrice des Relations Extérieures',
      bio: 'Diplomate, développe les partenariats nationaux et internationaux.',
      email: 'relations.exterieures@aeemci.org',
      photo: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      category: 4
    },
    {
      name: 'Koné Bakary',
      title: 'Directeur des Projets et Développement',
      bio: 'Ingénieur en développement, coordonne les projets sociaux et économiques.',
      email: 'projets@aeemci.org',
      photo: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      category: 4
    }
  ];
  
  // Filtrer les membres selon la catégorie sélectionnée
  const filteredMembers = computed(() => {
    if (activeCategory.value === 0) {
      return members;
    } else {
      return members.filter(member => member.category === activeCategory.value);
    }
  });
  
  // Galerie photos
  const galleryImages = [
    {
      thumbnail: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      full: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      caption: 'Réunion du Bureau Exécutif'
    },
    {
      thumbnail: 'https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg',
      full: 'https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg',
      caption: 'Séminaire de formation des cadres'
    },
    {
      thumbnail: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      full: 'https://i.ibb.co/Qj9JNHW/quran-recitation.jpg',
      caption: 'Cérémonie d\'ouverture du SENAFOI'
    },
    {
      thumbnail: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      full: 'https://i.ibb.co/Lk5M0bL/islamic-students.jpg',
      caption: 'Visite dans une section régionale'
    },
    {
      thumbnail: 'https://i.ibb.co/Qf7mZwV/islamic-education.jpg',
      full: 'https://i.ibb.co/Qf7mZwV/islamic-education.jpg',
      caption: 'Remise de prix aux lauréats'
    },
    {
      thumbnail: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      full: 'https://i.ibb.co/YQnkf7L/islamic-conference.jpg',
      caption: 'Conférence sur le leadership islamique'
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
    subject: '',
    message: ''
  });
  
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
  
  const submitContactForm = () => {
    // Ici, vous pourriez implémenter l'envoi du formulaire à un backend
    alert('Votre message a été envoyé avec succès !');
    // Réinitialiser le formulaire
    contactForm.value = {
      name: '',
      email: '',
      subject: '',
      message: ''
    };
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
  });
  
  onBeforeUnmount(() => {
    // Nettoyer les écouteurs d'événements
    document.removeEventListener('keydown', () => {});
  });
  </script>
  <script>
  // Pour respecter la règle multi-word
  export default {
    name: 'BuReau'
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
  
  .btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    border-radius: 4px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
  }
  
  .btn-primary {
    background-color: var(--primary);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 100, 0, 0.1);
  }
  
  .btn-primary:hover {
    background-color: var(--primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 100, 0, 0.15);
  }
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
  }
  
  .btn-outline:hover {
    background-color: var(--primary);
    color: white;
    transform: translateY(-2px);
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
    background-color: #d35400;
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
  
  /* Motifs islamiques animés */
  .islamic-patterns {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 1;
  }
  
  .islamic-pattern {
    position: absolute;
    opacity: 0.15;
  }
  
  .islamic-pattern.star {
    width: 30px;
    height: 30px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-diagonal 25s infinite linear;
  }
  
  .islamic-pattern.crescent {
    width: 25px;
    height: 25px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
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
  
  /* Leadership Section */
  .leadership-section {
    margin-bottom: 4rem;
  }
  
  .president-card {
    display: flex;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
  }
  
  .president-photo {
    flex: 0 0 300px;
    position: relative;
  }
  
  .president-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .president-info {
    flex: 1;
    padding: 2rem;
  }
  
  .president-name {
    font-size: 1.8rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .president-title {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 0.3rem;
  }
  
  .president-mandate {
    font-size: 1rem;
    color: var(--secondary);
    font-weight: 600;
    margin-bottom: 1.5rem;
  }
  
  .president-bio {
    margin-bottom: 1rem;
    line-height: 1.8;
  }
  
  .president-quote {
    background-color: var(--primary-lighter);
    padding: 1.5rem;
    border-left: 4px solid var(--primary);
    font-style: italic;
    margin-top: 1.5rem;
    border-radius: 0 8px 8px 0;
  }
  
  .vice-presidents {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .vp-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
  }
  
  .vp-card:hover {
    transform: translateY(-5px);
  }
  
  .vp-photo {
    height: 200px;
    overflow: hidden;
  }
  
  .vp-photo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
  }
  
  .vp-card:hover .vp-photo img {
    transform: scale(1.05);
  }
  
  .vp-info {
    padding: 1.5rem;
  }
  
  .vp-name {
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .vp-title {
    font-size: 1rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
    font-weight: 600;
  }
  
  .vp-bio {
    font-size: 0.9rem;
    line-height: 1.6;
  }
  
  /* Organization Structure */
  .org-structure-section {
    margin-bottom: 4rem;
  }
  
  .org-chart {
    margin-bottom: 3rem;
  }
  
  .org-level {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
  }
  
  .org-level::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    width: 2px;
    height: 1.5rem;
    background-color: var(--primary);
    transform: translateX(-50%);
  }
  
  .org-level:last-child::after {
    display: none;
  }
  
  .org-box {
    background-color: var(--primary);
    color: white;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    min-width: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .president-box {
    background-color: var(--primary-dark);
  }
  
  .vp-box {
    background-color: var(--primary);
  }
  
  .secretary-box {
    background-color: var(--primary-light);
  }
  
  .org-title {
    font-weight: 700;
    margin-bottom: 0.3rem;
  }
  
  .org-subtitle {
    font-size: 0.8rem;
    opacity: 0.9;
  }
  
  .departments-title {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  .departments-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .department-card {
    display: flex;
    align-items: flex-start;
    background-color: var(--background-alt);
    padding: 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .department-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .department-icon {
    font-size: 2rem;
    margin-right: 1rem;
    color: var(--primary);
  }
  
  .department-name {
    font-size: 1.1rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .department-desc {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0;
  }
  
  /* Members Section */
  .members-section {
    margin-bottom: 4rem;
  }
  
  .members-filter {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  
  .filter-btn {
    background-color: var(--background-alt);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
  }
  
  .filter-btn:hover {
    background-color: var(--primary-lighter);
  }
  
  .filter-btn.active {
    background-color: var(--primary);
    color: white;
  }
  
  .members-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
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
  
  .member-contact {
    margin-top: 1rem;
  }
  
  .contact-link {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
  }
  
  .contact-icon {
    margin-right: 0.5rem;
  }
  
  /* Contact Section */
  .contact-section {
    margin-bottom: 4rem;
  }
  
  .contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
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
  
  .contact-text h3 {
    font-size: 1.2rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .contact-text p {
    font-size: 0.9rem;
    margin-bottom: 0;
  }
  
  .social-links {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  .social-link {
    font-size: 0.9rem;
    color: var(--primary);
    transition: all 0.3s ease;
  }
  
  .social-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
  }
  
  .contact-form {
    background-color: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .contact-form h3 {
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
  .form-group textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-family: inherit;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }
  
  .form-group input:focus,
  .form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(0, 100, 0, 0.1);
  }
  
  /* Gallery Section */
  .gallery-section {
    margin-bottom: 2rem;
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
    
    .president-card {
      flex-direction: column;
    }
    
    .president-photo {
      flex: 0 0 auto;
      height: 300px;
    }
    
    .vice-presidents {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .departments-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .members-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .gallery-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .contact-content {
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
    
    .vice-presidents {
      grid-template-columns: 1fr;
    }
    
    .org-level {
      flex-direction: column;
      align-items: center;
    }
    
    .departments-grid {
      grid-template-columns: 1fr;
    }
    
    .members-grid {
      grid-template-columns: 1fr;
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
    
    .members-filter {
      flex-direction: column;
      align-items: center;
    }
    
    .filter-btn {
      width: 100%;
      text-align: center;
    }
  }
  </style>