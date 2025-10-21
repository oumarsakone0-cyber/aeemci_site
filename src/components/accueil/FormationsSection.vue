<template>
    <section class="formations animate-slide-up" style="animation-delay: 0.3s;">
      <div class="section-header">
        <h2><span class="highlight">F</span>ormations</h2>
        <span class="section-icon">🎓</span>
      </div>
      
      <div class="formations-grid">
        <div class="formation-card animate-hover" v-for="formation in formations" :key="formation.id">
          <div class="formation-icon">{{ formation.icon }}</div>
          <h4>{{ formation.title }}</h4>
          <p>{{ formation.description }}</p>
          <div class="formation-details">
            <span class="formation-duration">{{ formation.duration }}</span>
            <span class="formation-level">{{ formation.level }}</span>
          </div>
          <a href="#" class="btn btn-sm">Détails</a>
        </div>
      </div>
      
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const formations = ref([
    {
      id: 1,
      icon: '📚',
      title: 'Formation en leadership islamique',
      description: 'Développez vos compétences en leadership selon les principes islamiques',
      duration: '3 mois',
      level: 'Tous niveaux'
    },
    {
      id: 2,
      icon: '🗣️',
      title: 'Cours d\'arabe et Mémorisation du Coran',
      description: 'Apprenez la langue arabe avec nos professeurs expérimentés',
      duration: '6 mois',
      level: 'Débutant à avancé'
    },
    {
      id: 3,
      icon: '📖',
      title: 'Sciences islamiques',
      description: 'Approfondissez vos connaissances en sciences islamiques',
      duration: '1 an',
      level: 'Intermédiaire'
    },
    {
  id: 4,
  icon: '💻', // Informatique
  title: 'Informatique et numérique',
  description: 'Formation aux outils informatiques et numériques modernes',
  duration: '2 mois',
  level: 'Tous niveaux'
},
{
  id: 5,
  icon: '🎤', // Microphone → Art oratoire
  title: 'Art Oratoire',
  description: 'Apprenez à mieux vous exprimer en public',
  duration: '2 mois',
  level: 'Tous niveaux'
},
{
  id: 6,
  icon: '🧑‍💼', // Manager → Management & leadership
  title: 'Management et leadership',
  description: 'Développez vos compétences en gestion et leadership',
  duration: '2 mois',
  level: 'Tous niveaux'
},
{
  id: 7,
  icon: '🫱🏽‍🫲🏾', // Poignée de mains → Civisme et citoyenneté
  title: 'Civisme et citoyenneté',
  description: 'Formation sur les valeurs civiques et citoyennes',
  duration: '2 mois',
  level: 'Tous niveaux'
},
{
  id: 8,
  icon: '🇬🇧', // Drapeau UK → Cours d’anglais
  title: 'Cours d\'anglais',
  description: 'Perfectionnez votre anglais écrit et oral',
  duration: '2 mois',
  level: 'Tous niveaux'
}

  ])
  </script>
  
  <style scoped>
  /* Formations Section */
  .formations {
    margin-bottom: 1.8rem;
    background-color: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.2rem;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 0.4rem;
  }
  
  .section-header h2 {
    font-size: 1.3rem;
    font-weight: 700;
  }
  
  .highlight {
    color: var(--primary);
    font-weight: 800;
  }
  
  .section-icon {
    color: var(--primary);
    font-size: 1rem;
  }
  
  .formations-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
  }
  
  .formation-card {
    background-color: white;
    border-radius: 6px;
    padding: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    text-align: center;
  }
  
  .formation-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .formation-icon {
    font-size: 1.5rem;
    margin-bottom: 0.6rem;
  }
  
  .formation-card h4 {
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    color: var(--primary);
  }
  
  .formation-card p {
    font-size: 0.8rem;
    margin-bottom: 0.6rem;
    color: var(--text-secondary);
  }
  
  .formation-details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.6rem;
    font-size: 0.75rem;
    color: var(--text-light);
  }
  
  .btn {
    display: inline-block;
    padding: 0.3rem 0.7rem;
    border-radius: 2px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
    text-decoration: none;
    background-color: var(--primary);
    color: white;
  }
  
  .btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
  }
  
  .btn-outline:hover {
    background-color: var(--primary);
    color: white;
  }
  
  .view-all {
    text-align: center;
    margin-top: 1.2rem;
  }
  
  .animate-slide-up {
    opacity: 0;
    transform: translateY(20px);
    animation: slideUp 0.6s ease forwards;
  }
  
  .animate-hover {
    transition: all 0.3s ease;
  }
  
  .animate-hover:hover {
    transform: translateY(-3px);
  }
  
  @keyframes slideUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Responsive */
  @media (max-width: 992px) {
    .formations-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 768px) {
    .formations-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>
  