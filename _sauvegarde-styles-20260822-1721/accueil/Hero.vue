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
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
    color: #16a085;
    margin-bottom: 20px;
    line-height: 1.2;
  }
  
  .hero-text p {
    font-size: 18px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
  }
  
  .hero-buttons {
    display: flex;
    gap: 20px;
  }
  
  .btn-primary {
    background: #16a085;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .btn-primary:hover {
    background: #138d75;
  }
  
  .btn-secondary {
    background: transparent;
    color: #16a085;
    border: 2px solid #16a085;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .btn-secondary:hover {
    background: #16a085;
    color: white;
  }
  
  .hero-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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