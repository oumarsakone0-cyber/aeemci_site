<template>
    <section id="activities" class="activities">
      <div class="container">
        <div class="section-header">
          <h2>Nos Activités</h2>
          <p>Découvrez nos programmes et initiatives pour la communauté étudiante</p>
        </div>
        
        <div class="activities-grid">
          <div class="activity-card">
            <div class="activity-icon">📚</div>
            <h3>Cercles d'Étude</h3>
            <p>Sessions d'étude du Coran et des Hadiths organisées chaque semaine dans différentes universités.</p>
            <ul>
              <li>Tafsir du Coran</li>
              <li>Étude des Hadiths</li>
              <li>Fiqh pratique</li>
            </ul>
          </div>
          
          <div class="activity-card">
            <div class="activity-icon">🎓</div>
            <h3>Soutien Académique</h3>
            <p>Programme de tutorat et d'accompagnement pour la réussite scolaire et universitaire.</p>
            <ul>
              <li>Cours de soutien</li>
              <li>Mentorat étudiant</li>
              <li>Préparation aux examens</li>
            </ul>
          </div>
          
          <div class="activity-card">
            <div class="activity-icon">🤝</div>
            <h3>Actions Sociales</h3>
            <p>Initiatives caritatives et projets communautaires pour aider les plus démunis.</p>
            <ul>
              <li>Distribution de vivres</li>
              <li>Aide aux orphelins</li>
              <li>Projets de développement</li>
            </ul>
          </div>
          
          <div class="activity-card">
            <div class="activity-icon">🕌</div>
            <h3>Activités Spirituelles</h3>
            <p>Programmes pour renforcer la foi et la pratique religieuse des membres.</p>
            <ul>
              <li>Prières collectives</li>
              <li>Retraites spirituelles</li>
              <li>Célébrations religieuses</li>
            </ul>
          </div>
          
          <div class="activity-card">
            <div class="activity-icon">🎤</div>
            <h3>Conférences</h3>
            <p>Conférences et séminaires sur des thèmes islamiques et contemporains.</p>
            <ul>
              <li>Conférenciers invités</li>
              <li>Débats thématiques</li>
              <li>Ateliers pratiques</li>
            </ul>
          </div>
          
          <div class="activity-card">
            <div class="activity-icon">⚽</div>
            <h3>Sport et Loisirs</h3>
            <p>Activités sportives et récréatives pour favoriser la cohésion sociale.</p>
            <ul>
              <li>Tournois sportifs</li>
              <li>Sorties récréatives</li>
              <li>Activités culturelles</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'Activities'
  }
  </script>
  
  <style scoped>
  .activities {
    padding: 80px 20px;
    background: #f8f9fa;
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
  
  .activities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
  }
  
  .activity-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .activity-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }
  
  .activity-icon {
    font-size: 48px;
    margin-bottom: 20px;
  }
  
  .activity-card h3 {
    color: #16a085;
    font-size: 24px;
    margin-bottom: 15px;
  }
  
  .activity-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
  }
  
  .activity-card ul {
    list-style: none;
    padding: 0;
  }
  
  .activity-card li {
    color: #666;
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
  }
  
  .activity-card li::before {
    content: "•";
    color: #16a085;
    font-weight: bold;
    position: absolute;
    left: 0;
  }
  
  @media (max-width: 768px) {
    .activities-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>