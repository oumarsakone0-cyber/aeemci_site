<template>
  <section class="actualites animate-slide-up">
    <div class="section-header">
      <h2><span class="highlight">A</span>ctualités</h2>
      <span class="section-icon">📰</span>
    </div>
    
    <div class="news-item featured">
      <div class="news-content">
        <h3 style="font-weight: bold; font-size: 17px">l'Amir à Dakar.</h3>
        <div class="news-meta">
          <span class="icon-calendar">📅</span>
          <span>11 - 14 Septembre 2025</span>
        </div>
        <p>
          Cette rencontre a rassemblé les présidents des différentes AEEM, qui ont partagé leurs expériences et leurs réflexions afin de renforcer la coopération entre elles.
          À l’issue des assises, les instances de l’AEEMS ont été renouvelées pour un mandat de deux ans, et le frère Issa Baboucar DIEDHIOU a été désigné comme nouveau président de l’association.
          Quant à l’Amir de l’AEEMCI, il a regagné Abidjan sain et sauf, porté par vos invocations.
        </p>
        <div class="event-details">
          <div class="detail-item">
            <strong>Date :</strong> 11 - 14 Septembre 2025
          </div>
          <div class="detail-item">
            <strong>Lieu :</strong> Dakar, Sénégal
          </div>
        </div>
        <a href="/detail_actualite" class="btn btn-sm">Lire plus</a>
      </div>
      <div class="news-image">
        <img src="../../assets/senegalag.jpeg" alt="SENAFOI MAN 2024" class="img-responsive" />
      </div>
    </div>
    
    <NewsCarousel :articles="newsArticles" />
    
    <div class="view-all-news">
      <a href="#" class="btn btn-outline btn-sm">Voir toutes les actualités</a>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import NewsCarousel from './NewsCarousel.vue'

const newsArticles = ref([
  {
    id: 1,
    title: 'AEEMCI partenaire au FACFI',
    date: '23 Avril 2025',
    excerpt: 'Le samedi 23 août, l\’Amir de l\’AEEMCI, Youssouf BAMBA, a participé au FACFI (Forum des Acteurs de la Finance Islamique), un cadre d\’échanges riche...',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758208910/aeemci_photo_1758208905564_facfi.jpeg.jpg'
  },
  {
    id: 2,
    title: 'SENAFOI Yakro 2025',
    date: '03 - 10 Août 2025',
    excerpt: 'Séminaire National de Formation Islamique et Manageriale dans l\'optique de permetre aux jeunes musulmans de Côte d\'Ivoire de se former durant les vacances scolaires...',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758208953/aeemci_photo_1758208952364_senafoia.jpeg.jpg'
  },
  {
    id: 3,
    title: 'Séminaire Régionaux',
    date: '10 - 17 Avril 2025',
    excerpt: 'Dans le but de contribuer à la formation des jeunes musulmans pendant les congés de paques, plusieurs séminaires régionaux ont été organisés sur le territoir national...',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758209236/aeemci_photo_1758209228825_regionaux.jpeg.jpg'
  },
  {
    id: 4,
    title: 'Rupture Collective du Jeûne',
    date: '28/06/2024',
    excerpt: 'Le Comité Exécutif de l\'AEEMCI a organisé une grande rupture collective du jeûne à l\'occasion du mois béni de Ramadan 1447H / 2025...',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758209305/aeemci_photo_1758209303650_rupture.jpeg.jpg'
  },
  
])
</script>

<style scoped>
/* News Section */
.actualites {
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

.news-item.featured {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
  background-color: var(--background-alt);
  border-radius: 6px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
  margin-bottom: 1.2rem;
}

.news-item.featured:hover {
  transform: translateY(-3px);
}

.news-meta {
  display: flex;
  align-items: center;
  color: var(--text-light);
  font-size: 0.75rem;
  margin-bottom: 0.6rem;
}

.icon-calendar {
  margin-right: 0.3rem;
}

.event-details {
  background-color: rgba(26, 95, 63, 0.1);
  padding: 0.6rem;
  border-radius: 4px;
  margin: 0.8rem 0;
}

.detail-item {
  margin-bottom: 0.4rem;
  font-size: 0.8rem;
}

.img-responsive {
  display: block;
  width: 100%;
  height: auto;
  border-radius: 4px;
  transition: transform 0.5s ease;
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

.btn-outline {
  background-color: transparent;
  border: 1px solid #16a34a;
  color: #16a34a;
}

.btn-outline:hover {
  background-color: #16a34a;
  color: white;
}

.view-all-news {
  text-align: center;
  margin-top: 1.2rem;
}

.animate-slide-up {
  opacity: 0;
  transform: translateY(20px);
  animation: slideUp 0.6s ease forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 992px) {
  .news-item.featured {
    grid-template-columns: 1fr;
  }
}
</style>
