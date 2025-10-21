<template>
    <section id="about" class="about">
      <div class="container">
        <div class="section-header">
          <h2>À Propos de l'AEEMCI</h2>
          <p>Notre mission, nos valeurs et notre engagement envers la communauté étudiante musulmane</p>
        </div>
        
        <div class="about-content">
          <div class="about-text">
            <div class="mission">
              <h3>Notre Mission</h3>
              <p>L'AEEMCI œuvre pour l'épanouissement spirituel, intellectuel et social des élèves et étudiants musulmans de Côte d'Ivoire, en promouvant l'excellence académique dans le respect des valeurs islamiques.</p>
            </div>
            
            <div class="values">
              <h3>Nos Valeurs</h3>
              <ul>
                <li><strong>Foi :</strong> Renforcement de la spiritualité islamique</li>
                <li><strong>Excellence :</strong> Poursuite de l'excellence académique</li>
                <li><strong>Solidarité :</strong> Entraide et soutien mutuel</li>
                <li><strong>Intégrité :</strong> Respect des principes éthiques</li>
              </ul>
            </div>
          </div>
          
          <div class="stats">
            <div class="stat-item">
              <h4>500+</h4>
              <p>Membres Actifs</p>
            </div>
            <div class="stat-item">
              <h4>15</h4>
              <p>Universités Partenaires</p>
            </div>
            <div class="stat-item">
              <h4>25</h4>
              <p>Années d'Existence</p>
            </div>
            <div class="stat-item">
              <h4>100+</h4>
              <p>Événements Organisés</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'About'
  }
  </script>
  
  <style scoped>
  .about {
    padding: 80px 20px;
    background: white;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .section-header {
    text-align: center;
    margin-bottom: 60px;
  }
  
  .section-header h2 {
    font-size: 36px;
    color: #16a085;
    margin-bottom: 15px;
  }
  
  .section-header p {
    font-size: 18px;
    color: #666;
  }
  
  .about-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 60px;
    align-items: start;
  }
  
  .mission, .values {
    margin-bottom: 40px;
  }
  
  .mission h3, .values h3 {
    color: #16a085;
    font-size: 24px;
    margin-bottom: 15px;
  }
  
  .mission p, .values li {
    color: #666;
    line-height: 1.8;
    font-size: 16px;
  }
  
  .values ul {
    list-style: none;
    padding: 0;
  }
  
  .values li {
    margin-bottom: 10px;
    padding-left: 20px;
    position: relative;
  }
  
  .values li::before {
    content: "✓";
    color: #16a085;
    font-weight: bold;
    position: absolute;
    left: 0;
  }
  
  .stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
  }
  
  .stat-item {
    text-align: center;
    padding: 30px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #16a085;
  }
  
  .stat-item h4 {
    font-size: 36px;
    color: #16a085;
    margin-bottom: 10px;
  }
  
  .stat-item p {
    color: #666;
    font-weight: 500;
  }
  
  @media (max-width: 768px) {
    .about-content {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    
    .stats {
      grid-template-columns: 1fr;
    }
  }
  </style>