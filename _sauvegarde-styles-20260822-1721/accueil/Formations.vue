<template>
    <section id="formations" class="formations">
      <div class="container">
        <div class="section-header">
          <h2>Formations Islamiques</h2>
          <p>Programmes de formation pour approfondir vos connaissances religieuses</p>
        </div>
        
        <div class="formations-content">
          <div class="formation-categories">
            <div class="category active" @click="setActiveCategory('debutant')">
              <h3>Niveau Débutant</h3>
              <p>Bases de l'Islam</p>
            </div>
            <div class="category" @click="setActiveCategory('intermediaire')">
              <h3>Niveau Intermédiaire</h3>
              <p>Approfondissement</p>
            </div>
            <div class="category" @click="setActiveCategory('avance')">
              <h3>Niveau Avancé</h3>
              <p>Spécialisation</p>
            </div>
          </div>
          
          <div class="formation-details">
            <div v-if="activeCategory === 'debutant'" class="formation-level">
              <h3>Formations Niveau Débutant</h3>
              <div class="course-grid">
                <div class="course-card">
                  <h4>Les Piliers de l'Islam</h4>
                  <p>Apprentissage des 5 piliers fondamentaux de l'Islam</p>
                  <div class="course-info">
                    <span>⏱️ 8 semaines</span>
                    <span>👥 20 places</span>
                  </div>
                </div>
                <div class="course-card">
                  <h4>Purification et Prière</h4>
                  <p>Apprentissage correct des ablutions et de la prière</p>
                  <div class="course-info">
                    <span>⏱️ 6 semaines</span>
                    <span>👥 25 places</span>
                  </div>
                </div>
                <div class="course-card">
                  <h4>Lecture du Coran</h4>
                  <p>Initiation à la lecture de l'arabe et du Coran</p>
                  <div class="course-info">
                    <span>⏱️ 12 semaines</span>
                    <span>👥 15 places</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="activeCategory === 'intermediaire'" class="formation-level">
              <h3>Formations Niveau Intermédiaire</h3>
              <div class="course-grid">
                <div class="course-card">
                  <h4>Tafsir du Coran</h4>
                  <p>Étude et interprétation des versets coraniques</p>
                  <div class="course-info">
                    <span>⏱️ 16 semaines</span>
                    <span>👥 15 places</span>
                  </div>
                </div>
                <div class="course-card">
                  <h4>Sciences du Hadith</h4>
                  <p>Méthodologie d'authentification des Hadiths</p>
                  <div class="course-info">
                    <span>⏱️ 12 semaines</span>
                    <span>👥 12 places</span>
                  </div>
                </div>
                <div class="course-card">
                  <h4>Fiqh Pratique</h4>
                  <p>Jurisprudence islamique pour la vie quotidienne</p>
                  <div class="course-info">
                    <span>⏱️ 14 semaines</span>
                    <span>👥 18 places</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="activeCategory === 'avance'" class="formation-level">
              <h3>Formations Niveau Avancé</h3>
              <div class="course-grid">
                <div class="course-card">
                  <h4>Théologie Islamique</h4>
                  <p>Étude approfondie de la croyance islamique</p>
                  <div class="course-info">
                    <span>⏱️ 20 semaines</span>
                    <span>👥 10 places</span>
                  </div>
                </div>
                <div class="course-card">
                  <h4>Langue Arabe Avancée</h4>
                  <p>Maîtrise de la grammaire et littérature arabe</p>
                  <div class="course-info">
                    <span>⏱️ 24 semaines</span>
                    <span>👥 8 places</span>
                  </div>
                </div>
                <div class="course-card">
                  <h4>Formation de Formateurs</h4>
                  <p>Devenir formateur en sciences islamiques</p>
                  <div class="course-info">
                    <span>⏱️ 18 semaines</span>
                    <span>👥 6 places</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="inscription-info">
            <h3>Modalités d'Inscription</h3>
            <div class="info-grid">
              <div class="info-item">
                <h4>📅 Calendrier</h4>
                <p>Nouvelles sessions chaque trimestre</p>
              </div>
              <div class="info-item">
                <h4>💰 Tarifs</h4>
                <p>Formations gratuites pour les membres</p>
              </div>
              <div class="info-item">
                <h4>📍 Lieux</h4>
                <p>Campus universitaires et centres AEEMCI</p>
              </div>
              <div class="info-item">
                <h4>🎓 Certification</h4>
                <p>Certificat de participation délivré</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'Formations',
    data() {
      return {
        activeCategory: 'debutant'
      }
    },
    methods: {
      setActiveCategory(category) {
        this.activeCategory = category
      }
    }
  }
  </script>
  
  <style scoped>
  .formations {
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
  
  .formation-categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
  }
  
  .category {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
  }
  
  .category:hover, .category.active {
    border-color: #16a085;
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }
  
  .category h3 {
    color: #16a085;
    margin-bottom: 10px;
  }
  
  .category p {
    color: #666;
  }
  
  .formation-details {
    margin-bottom: 60px;
  }
  
  .formation-level h3 {
    color: #16a085;
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
  }
  
  .course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
  }
  
  .course-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
  }
  
  .course-card:hover {
    transform: translateY(-3px);
  }
  
  .course-card h4 {
    color: #16a085;
    margin-bottom: 15px;
    font-size: 20px;
  }
  
  .course-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
  }
  
  .course-info {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #888;
  }
  
  .inscription-info h3 {
    color: #16a085;
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
  }
  
  .info-item {
    background: white;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  
  .info-item h4 {
    color: #16a085;
    margin-bottom: 10px;
    font-size: 18px;
  }
  
  .info-item p {
    color: #666;
    line-height: 1.5;
  }
  
  @media (max-width: 768px) {
    .formation-categories {
      grid-template-columns: 1fr;
    }
    
    .course-grid {
      grid-template-columns: 1fr;
    }
    
    .info-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>