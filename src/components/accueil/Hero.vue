<template>
    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1>Association des Élèves et Étudiants Musulmans de Côte d'Ivoire</h1>
          <p>Unir, Former et Accompagner la jeunesse musulmane estudiantine dans la foi et l'excellence académique</p>
          <div class="hero-buttons">
            <button class="btn-primary">Nous Rejoindre</button>
            <button class="btn-secondary">Découvrir nos Activités</button>
          </div>
        </div>
        <div class="hero-image">
          <img src="https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg" alt="Étudiants AEEMCI" />
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'Hero'
  }
  </script>
  
  <style scoped>
  .hero {
    background: linear-gradient(135deg, var(--surface) 0%, var(--surface) 100%);
    padding: 80px 20px;
  }
  
  .hero-content {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }
  
  .hero-text h1 {
    font-size: 48px;
    color: var(--green);
    margin-bottom: 20px;
    line-height: 1.2;
  }
  
  .hero-text p {
    font-size: 18px;
    color: var(--muted);
    margin-bottom: 30px;
    line-height: 1.6;
  }
  
  .hero-buttons {
    display: flex;
    gap: 20px;
  }
  
  .btn-primary {
    background: var(--green);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 0;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .btn-primary:hover {
    background: var(--green);
  }
  
  .btn-secondary {
    background: transparent;
    color: var(--green);
    border: 2px solid var(--green);
    padding: 15px 30px;
    border-radius: 0;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .btn-secondary:hover {
    background: var(--green);
    color: white;
  }
  
  .hero-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 0;
    box-shadow: var(--shadow);
  }
  
  @media (max-width: 768px) {
    .hero-content {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    
    .hero-text h1 {
      font-size: 36px;
    }
    
    .hero-buttons {
      flex-direction: column;
    }
  }
  </style>