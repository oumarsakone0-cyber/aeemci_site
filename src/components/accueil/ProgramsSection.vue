<template>
  <section class="programmes animate-slide-up">
    <div class="section-header">
      <h2><span class="highlight">P</span>rogrammes à venir</h2>
      <span class="section-icon">📆</span>
    </div>

    <div class="programme-timeline">
      <div
        class="timeline-item animate-slide-right"
        v-for="(programme, index) in programmes"
        :key="programme.id"
        :style="{ animationDelay: `${index * 0.2}s` }"
      >
        <div class="timeline-date">
          <span class="month">{{ programme.month }}</span>
          <span class="day">{{ programme.day }}</span>
          <span class="year">{{ programme.year }}</span>
        </div>

        <div class="timeline-content">
          <div class="timeline-image" v-if="programme.image">
            <img :src="programme.image" :alt="programme.title" />
          </div>
          <div class="timeline-text">
            <h4>{{ programme.title }}</h4>
            <p>{{ programme.description }}</p>
            <a href="#" class="btn btn-sm">{{ programme.action }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
  
  <script setup>
  import { ref } from 'vue'
  
  const programmes = ref([
  {
    id: 1,
    month: 'DEC',
    day: '26-28',
    year: '2025',
    title: 'CIMA 2025',
    description: 'Conseil des Imams et Formateurs de l\'Aeemci',
    action: 'En savoir plus',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758212841/aeemci_photo_1758212838756_cima.jpeg.jpg'
  },
  {
    id: 2,
    month: 'AG',
    day: '27-28',
    year: '2025',
    title: 'Assemblée Générale 2025',
    description: 'Réunion annuelle des membres de l’association.',
    action: 'Voir les détails',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758212759/aeemci_photo_1758212756670_ag.jpeg.jpg'
  }
])
  </script>
  
  <style scoped>
  /* Programme Timeline */
  .programmes {
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
    border-bottom: 2px solid #16a34a;
    padding-bottom: 0.4rem;
  }
  
  .section-header h2 {
    font-size: 1.3rem;
    font-weight: 700;
  }
  
  .highlight {
    color: #16a34a;
    font-weight: 800;
  }
  
  .section-icon {
    color: #16a34a;
    font-size: 1rem;
  }
  
  .programme-timeline {
    position: relative;
    padding-left: 1.2rem;
  }
  
  .programme-timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 70px;
    width: 2px;
    background-color: #16a34a;
    border-radius: 2px;
  }
  
  .timeline-item {
    display: flex;
    margin-bottom: 1.2rem;
    position: relative;
    opacity: 0;
    transform: translateX(20px);
  }
  
  .timeline-date {
    width: 70px;
    text-align: center;
    background-color: #16a34a;
    color: white;
    padding: 0.4rem;
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(26, 95, 63, 0.2);
    z-index: 2;
  }
  
  .timeline-date .month {
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 600;
  }
  
  .timeline-date .day {
    font-size: 1rem;
    font-weight: 700;
    margin: 0.1rem 0;
  }
  
  .timeline-date .year {
    font-size: 0.7rem;
  }
  
  .timeline-content {
    flex: 1;
    background-color: white;
    padding: 0.8rem;
    border-radius: 4px;
    margin-left: 1.2rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
  }
  
  .timeline-content:hover {
    transform: translateY(-3px);
  }
  
  .timeline-content h4 {
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
    color: #16a34a;
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
    background-color: #16a34a;
    color: white;
  }
  
  .btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }
  
  .animate-slide-up {
    opacity: 0;
    transform: translateY(20px);
    animation: slideUp 0.6s ease forwards;
  }
  
  .animate-slide-right {
    opacity: 0;
    transform: translateX(-20px);
    animation: slideRight 0.6s ease forwards;
  }
  
  @keyframes slideUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  @keyframes slideRight {
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .timeline-item {
      flex-direction: column;
      align-items: flex-start;
      padding-left: 0;
    }
    
    .timeline-date {
      margin-bottom: 0.5rem;
      width: 100%;
      max-width: 120px;
    }
    
    .timeline-content {
      margin-left: 0;
      width: 100%;
    }
    
    .programme-timeline::before {
      display: none;
    }
  }
  .timeline-content {
  display: flex;
  gap: 1rem;
  background-color: white;
  padding: 1rem;
  border-radius: 6px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  margin-left: 1.2rem;
  flex: 1;
  transition: transform 0.3s ease;
}

.timeline-image img {
  width: 100px;
  height: 100px;
  border-radius: 8px;
  object-fit: cover;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

.timeline-text {
  flex: 1;
}

.timeline-content:hover {
  transform: translateY(-3px);
}

/* Mobile */
@media (max-width: 768px) {
  .timeline-content {
    flex-direction: column;
    margin-left: 0;
    width: 100%;
  }

  .timeline-image img {
    width: 100%;
    height: auto;
    max-height: 200px;
  }
}

  </style>
  