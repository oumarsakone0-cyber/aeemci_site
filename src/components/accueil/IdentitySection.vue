<template>
    <section class="identity animate-slide-up" style="animation-delay: 0.5s;">
      <div class="section-header">
        <h2><span class="highlight">J</span>e suis AEEMCIste</h2>
        <span class="section-icon">👤</span>
      </div>
      
      <div class="identity-content">
        <div class="identity-text">
          <p>Rejoignez la communauté des AEEMCIstes et participez à la construction d'un avenir meilleur pour la jeunesse musulmane de Côte d'Ivoire.</p>
          <div class="identity-stats">
            <div class="stat-item animate-count">
              <div class="stat-number" data-count="50">0</div>
              <div class="stat-label">Années d'existence</div>
            </div>
            <div class="stat-item animate-count">
              <div class="stat-number" data-count="30000">0</div>
              <div class="stat-label">Membres actifs</div>
            </div>
            <div class="stat-item animate-count">
              <div class="stat-number" data-count="1000">0</div>
              <div class="stat-label">Sections locales</div>
            </div>
          </div>
          <a href="#" class="btn btn-primary btn-sm" style="color: white">Devenir membre</a>
        </div>
        <div class="identity-image">
          <img src="../../assets/aeemciste.jpeg" alt="Membres AEEMCI" class="img-responsive" />
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { onMounted } from 'vue'
  
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
          if (entry.isIntersecting && entry.target.classList.contains('identity-stats')) {
            animateCounters()
            observer.unobserve(entry.target)
          }
        })
      }, { threshold: 0.1 })
      
      const statsElement = document.querySelector('.identity-stats')
      if (statsElement) {
        observer.observe(statsElement)
      }
    } else {
      animateCounters()
    }
  })
  </script>
  
  <style scoped>
  /* Identity Section */
  .identity {
    margin-bottom: 1.2rem;
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
  
  .identity-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem;
    align-items: center;
  }
  
  .identity-stats {
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
  }
  
  .btn-primary {
    background-color: var(--secondary);
    color: #16a34a;
    box-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
  }
  
  .btn-primary:hover {
    background-color: #ffed4e;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(255, 215, 0, 0.4);
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
    .identity-content {
      grid-template-columns: 1fr;
    }
  }
  
  @media (max-width: 576px) {
    .identity-stats {
      flex-direction: column;
      gap: 0.8rem;
    }
    
    .stat-item {
      width: 100%;
    }
  }
  </style>
  