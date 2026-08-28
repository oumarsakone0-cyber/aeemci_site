<template>
    <section id="accueil" class="hero-banner">
      <div class="container">
        <div class="hero-content animate-fade-in">
          <h1>بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</h1>
          <h2>Association des Élèves et Étudiants Musulmans de Côte d'Ivoire</h2>
          <p>Éducation, Excellence et Engagement pour la communauté musulmane</p>
          <div class="hero-buttons">
            <a href="#" class="btn btn-primary animate-pulse">Découvrir nos activités</a>
            <a href="#" class="btn btn-outline">Devenir membre</a>
          </div>
        </div>
      </div>
      
      <!-- Motifs islamiques animés -->
      <IslamicPatterns />
    </section>
  </template>
  
  <script setup>
  import IslamicPatterns from './IslamicPatterns.vue'
  </script>
  
  <style scoped>
  /* Hero Banner */
  .hero-banner {
    background: linear-gradient(rgba(26, 95, 63, 0.8), rgba(26, 95, 63, 0.8)), 
                url('https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 3rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  
  .hero-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 100%);
  }
  
  .hero-content {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
  }
  
  .hero-content h1 {
    font-size: 2.5rem;
    margin-bottom: 0.8rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    font-family: 'Times New Roman', serif;
  }
  
  .hero-content h2 {
    font-size: 1.8rem;
    margin-bottom: 0.8rem;
    font-weight: 700;
    color: var(--secondary);
  }
  
  .hero-content p {
    font-size: 1rem;
    margin-bottom: 1.5rem;
    opacity: 0.95;
  }
  
  .hero-buttons {
    display: flex;
    justify-content: center;
    gap: 0.8rem;
  }
  
  .btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 3px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.8rem;
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
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid white;
    color: white;
  }
  
  .btn-outline:hover {
    background-color: white;
    color: #16a34a;
    transform: translateY(-2px);
  }
  
  .container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 0.8rem;
  }
  
  .animate-fade-in {
    opacity: 0;
    transform: translateY(15px);
    animation: fadeIn 1s ease forwards;
  }
  
  .animate-pulse {
    animation: pulse 2s infinite;
  }
  
  @keyframes fadeIn {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4);
    }
    70% {
      box-shadow: 0 0 0 5px rgba(255, 215, 0, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
    }
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .hero-buttons {
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }
    
    .hero-buttons .btn {
      width: 100%;
      max-width: 250px;
    }
  
    .hero-content h1 {
      font-size: 2rem;
    }
  
    .hero-content h2 {
      font-size: 1.5rem;
    }
  }
  
  @media (max-width: 576px) {
    .hero-content h1 {
      font-size: 1.8rem;
    }
  
    .hero-content h2 {
      font-size: 1.3rem;
    }
    
    .hero-content p {
      font-size: 0.9rem;
    }
  }
  </style>
  