<template>
    <section class="bibliotheque animate-slide-up" style="animation-delay: 0.4s;">
      <div class="section-header">
        <h2><span class="highlight">B</span>ibliothèque</h2>
        <span class="section-icon">📚</span>
      </div>
      
      <div class="bibliotheque-content">
        <div class="bibliotheque-info">
          <h3>Ressources islamiques</h3>
          <p>Notre bibliothèque offre une vaste collection de livres, articles et ressources numériques sur l'islam, l'éducation et le développement personnel.</p>
          
          <div class="bibliotheque-stats">
            <div class="stat-item animate-count">
              <div class="stat-number" data-count="1000">1000</div>
              <div class="stat-label">Livres</div>
            </div>
            <div class="stat-item animate-count">
              <div class="stat-number" data-count="100">100</div>
              <div class="stat-label">E-books</div>
            </div>
            <div class="stat-item animate-count">
              <div class="stat-number" data-count="100">100</div>
              <div class="stat-label">Articles</div>
            </div>
          </div>
          
          <div class="bibliotheque-hours">
            <h4>Horaires d'ouverture</h4>
            <ul>
              <li><span>Lundi - Samedi:</span> <span>09h00 - 20h00</span></li>
              <li><span>Dimanche:</span> <span>Fermé</span></li>
            </ul>
          </div>
          
          <a href="#" class="btn btn-sm">Catalogue en ligne</a>
        </div>
        
        <div class="bibliotheque-featured">
          <h4>Livres recommandés</h4>
          <div class="book-carousel">
            <div class="book-item animate-hover" v-for="book in recommendedBooks" :key="book.id">
              <div class="book-cover">
                <img :src="book.cover" :alt="book.title" />
              </div>
              <div class="book-info">
                <h5>{{ book.title }}</h5>
                <p>{{ book.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  
  const recommendedBooks = ref([
    {
      id: 1,
      title: 'Les Grands Péchés',
      description: 'Les Grands Péchés en islam',
      cover: 'https://www.iqrashop.com/images/grands-peches-0910.jpg'
    },
    {
      id: 2,
      title: 'Afrique et Développement',
      description: 'Livre sur le développement en Afrique',
      cover: 'https://reseau-mirabel.info/images/titres-couvertures/000001866.png'
    },
    {
      id: 3,
      title: 'La vie du prophete',
      description: 'Vie du Prophète Muhammad (paix et bénédictions sur lui)',
      cover: 'https://m.media-amazon.com/images/I/81b+OWVO92L._UF1000,1000_QL80_.jpg'
    }
  ])
  
  const animateCounters = () => {
    const counters = document.querySelectorAll('.animate-count .stat-number')
    
    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-count'))
      const duration = 2000
      const step = target / (duration / 16)
      let current = 0
      
      const updateCounter = () => {
        current += step
        if (current < target) {
          counter.textContent = Math.ceil(current)
          requestAnimationFrame(updateCounter)
        } else {
          counter.textContent = target
          if (target >= 1000) {
            counter.textContent = (target / 1000) + 'k+'
          } else {
            counter.textContent = target + '+'
          }
        }
      }
      
      updateCounter()
    })
  }
  
  onMounted(() => {
    // Observer pour déclencher l'animation des compteurs
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting && entry.target.classList.contains('bibliotheque-stats')) {
            animateCounters()
            observer.unobserve(entry.target)
          }
        })
      }, { threshold: 0.1 })
      
      const statsElement = document.querySelector('.bibliotheque-stats')
      if (statsElement) {
        observer.observe(statsElement)
      }
    } else {
      animateCounters()
    }
  })
  </script>
  
  <style scoped>
  /* Bibliothèque Section */
  .bibliotheque {
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
  
  .bibliotheque-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem;
  }
  
  .bibliotheque-info h3 {
    font-size: 1.1rem;
    margin-bottom: 0.6rem;
    color: #16a34a;
  }
  
  .bibliotheque-info p {
    font-size: 0.85rem;
    margin-bottom: 1rem;
  }
  
  .bibliotheque-stats {
    display: flex;
    justify-content: space-between;
    margin: 1.2rem 0;
  }
  
  .stat-item {
    text-align: center;
    padding: 0.8rem;
    background-color: var(--background-alt);
    border-radius: 4px;
    transition: transform 0.3s ease;
  }
  
  .stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  }
  
  .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #16a34a;
    margin-bottom: 0.2rem;
  }
  
  .stat-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
  }
  
  .bibliotheque-hours {
    margin: 1rem 0;
  }
  
  .bibliotheque-hours h4 {
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    color: #16a34a;
  }
  
  .bibliotheque-hours ul {
    list-style: none;
  }
  
  .bibliotheque-hours li {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    margin-bottom: 0.3rem;
  }
  
  .book-carousel {
    display: flex;
    gap: 0.8rem;
    margin-top: 0.8rem;
  }
  
  .book-item {
    flex: 1;
    background-color: var(--background-alt);
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s ease;
  }
  
  .book-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .book-cover img {
    width: 100%;
    height: 120px;
    object-fit: cover;
  }
  
  .book-info {
    padding: 0.6rem;
  }
  
  .book-info h5 {
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
    color: #16a34a;
  }
  
  .book-info p {
    font-size: 0.75rem;
    color: var(--text-secondary);
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
    .bibliotheque-content {
      grid-template-columns: 1fr;
    }
  }
  
  @media (max-width: 768px) {
    .book-carousel {
      flex-direction: column;
      gap: 1rem;
    }
    
    .bibliotheque-stats {
      flex-direction: column;
      gap: 0.8rem;
    }
    
    .stat-item {
      width: 100%;
    }
  }
  </style>
  