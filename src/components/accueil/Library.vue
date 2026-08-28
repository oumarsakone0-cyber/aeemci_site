<template>
    <section id="library" class="library">
      <div class="container">
        <div class="section-header">
          <h2>Bibliothèque Islamique</h2>
          <p>Ressources numériques et physiques pour approfondir vos connaissances islamiques</p>
        </div>
        
        <div class="library-content">
          <div class="library-stats">
            <div class="stat">
              <h3>1,200+</h3>
              <p>Livres Islamiques</p>
            </div>
            <div class="stat">
              <h3>500+</h3>
              <p>Articles Numériques</p>
            </div>
            <div class="stat">
              <h3>200+</h3>
              <p>Conférences Audio</p>
            </div>
          </div>
          
          <div class="collections">
            <h3>Collections Disponibles</h3>
            <div class="collection-grid">
              <div class="collection-item">
                <h4>📖 Coran et Tafsir</h4>
                <p>Différentes traductions du Coran et commentaires des grands savants</p>
              </div>
              <div class="collection-item">
                <h4>📚 Hadith</h4>
                <p>Collections authentiques de Hadiths avec explications</p>
              </div>
              <div class="collection-item">
                <h4>⚖️ Fiqh</h4>
                <p>Jurisprudence islamique selon les différentes écoles</p>
              </div>
              <div class="collection-item">
                <h4>🏛️ Histoire Islamique</h4>
                <p>Histoire des prophètes, des califes et de la civilisation islamique</p>
              </div>
              <div class="collection-item">
                <h4>💝 Spiritualité</h4>
                <p>Ouvrages sur la purification de l'âme et le développement spirituel</p>
              </div>
              <div class="collection-item">
                <h4>🎓 Sciences Islamiques</h4>
                <p>Études approfondies des sciences religieuses</p>
              </div>
            </div>
          </div>
          
          <div class="services">
            <h3>Services de la Bibliothèque</h3>
            <div class="service-list">
              <div class="service-item">
                <span class="service-icon">📱</span>
                <div>
                  <h4>Accès Numérique</h4>
                  <p>Plateforme en ligne accessible 24h/24</p>
                </div>
              </div>
              <div class="service-item">
                <span class="service-icon">📍</span>
                <div>
                  <h4>Consultation sur Place</h4>
                  <p>Espace de lecture dans nos locaux</p>
                </div>
              </div>
              <div class="service-item">
                <span class="service-icon">🎧</span>
                <div>
                  <h4>Ressources Audio</h4>
                  <p>Conférences et récitations disponibles</p>
                </div>
              </div>
              <div class="service-item">
                <span class="service-icon">👨‍🏫</span>
                <div>
                  <h4>Aide à la Recherche</h4>
                  <p>Assistance pour vos travaux académiques</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'Library'
  }
  </script>
  
  <style scoped>
  .library {
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
    color: var(--green);
    margin-bottom: 15px;
  }
  
  .section-header p {
    font-size: 18px;
    color: var(--muted);
  }
  
  .library-content {
    display: grid;
    gap: 60px;
  }
  
  .library-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    text-align: center;
  }
  
  .stat {
    padding: 30px;
    background: var(--surface);
    border-radius: 0;
    border-top: 4px solid var(--green);
  }
  
  .stat h3 {
    font-size: 36px;
    color: var(--green);
    margin-bottom: 10px;
  }
  
  .stat p {
    color: var(--muted);
    font-weight: 500;
  }
  
  .collections h3, .services h3 {
    color: var(--green);
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
  }
  
  .collection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
  }
  
  .collection-item {
    padding: 25px;
    background: var(--surface);
    border-radius: 0;
    border-left: 4px solid var(--green);
  }
  
  .collection-item h4 {
    color: var(--green);
    margin-bottom: 10px;
    font-size: 18px;
  }
  
  .collection-item p {
    color: var(--muted);
    line-height: 1.6;
  }
  
  .service-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
  }
  
  .service-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: var(--surface);
    border-radius: 0;
  }
  
  .service-icon {
    font-size: 32px;
    flex-shrink: 0;
  }
  
  .service-item h4 {
    color: var(--green);
    margin-bottom: 5px;
  }
  
  .service-item p {
    color: var(--muted);
    line-height: 1.5;
  }
  
  @media (max-width: 768px) {
    .library-stats {
      grid-template-columns: 1fr;
    }
    
    .collection-grid {
      grid-template-columns: 1fr;
    }
    
    .service-list {
      grid-template-columns: 1fr;
    }
  }
  </style>