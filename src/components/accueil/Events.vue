<template>
    <section id="events" class="events">
      <div class="container">
        <div class="section-header">
          <h2>Événements à Venir</h2>
          <p>Participez à nos prochaines activités et rencontres</p>
        </div>
        
        <div class="events-content">
          <div class="upcoming-events">
            <h3>Prochains Événements</h3>
            <div class="event-list">
              <div class="event-card featured">
                <div class="event-date">
                  <span class="day">15</span>
                  <span class="month">FÉV</span>
                </div>
                <div class="event-info">
                  <h4>Conférence : "L'Islam et la Science Moderne"</h4>
                  <p>Conférence magistrale avec Dr. Ahmed Kouassi sur la compatibilité entre foi et science</p>
                  <div class="event-details">
                    <span>📍 Université Félix Houphouët-Boigny</span>
                    <span>⏰ 14h00 - 17h00</span>
                    <span>👥 200 places</span>
                  </div>
                </div>
                <div class="event-status">
                  <span class="status featured">Événement Phare</span>
                </div>
              </div>
              
              <div class="event-card">
                <div class="event-date">
                  <span class="day">22</span>
                  <span class="month">FÉV</span>
                </div>
                <div class="event-info">
                  <h4>Journée Portes Ouvertes</h4>
                  <p>Découvrez l'AEEMCI, ses activités et rencontrez nos membres</p>
                  <div class="event-details">
                    <span>📍 Siège AEEMCI Cocody</span>
                    <span>⏰ 09h00 - 16h00</span>
                    <span>👥 Accès libre</span>
                  </div>
                </div>
                <div class="event-status">
                  <span class="status open">Inscription Ouverte</span>
                </div>
              </div>
              
              <div class="event-card">
                <div class="event-date">
                  <span class="day">01</span>
                  <span class="month">MAR</span>
                </div>
                <div class="event-info">
                  <h4>Tournoi Sportif Inter-Universités</h4>
                  <p>Compétition sportive entre les différentes universités membres</p>
                  <div class="event-details">
                    <span>📍 Stade Université de Cocody</span>
                    <span>⏰ 08h00 - 18h00</span>
                    <span>👥 16 équipes</span>
                  </div>
                </div>
                <div class="event-status">
                  <span class="status soon">Bientôt</span>
                </div>
              </div>
              
              <div class="event-card">
                <div class="event-date">
                  <span class="day">08</span>
                  <span class="month">MAR</span>
                </div>
                <div class="event-info">
                  <h4>Soirée Culturelle Islamique</h4>
                  <p>Soirée dédiée à la culture islamique avec récitations et nasheed</p>
                  <div class="event-details">
                    <span>📍 Centre Culturel AEEMCI</span>
                    <span>⏰ 19h00 - 22h00</span>
                    <span>👥 150 places</span>
                  </div>
                </div>
                <div class="event-status">
                  <span class="status soon">Bientôt</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="event-calendar">
            <h3>Calendrier Islamique</h3>
            <div class="calendar-events">
              <div class="calendar-item">
                <div class="calendar-date">
                  <span>12 Rabi' al-Awwal</span>
                  <span class="hijri-date">1446 H</span>
                </div>
                <div class="calendar-info">
                  <h4>Mawlid an-Nabi</h4>
                  <p>Célébration de la naissance du Prophète (PSL)</p>
                </div>
              </div>
              
              <div class="calendar-item">
                <div class="calendar-date">
                  <span>27 Rajab</span>
                  <span class="hijri-date">1446 H</span>
                </div>
                <div class="calendar-info">
                  <h4>Isra et Mi'raj</h4>
                  <p>Commémoration du voyage nocturne</p>
                </div>
              </div>
              
              <div class="calendar-item">
                <div class="calendar-date">
                  <span>1 Ramadan</span>
                  <span class="hijri-date">1446 H</span>
                </div>
                <div class="calendar-info">
                  <h4>Début du Ramadan</h4>
                  <p>Commencement du mois de jeûne</p>
                </div>
              </div>
              
              <div class="calendar-item">
                <div class="calendar-date">
                  <span>1 Shawwal</span>
                  <span class="hijri-date">1446 H</span>
                </div>
                <div class="calendar-info">
                  <h4>Aïd al-Fitr</h4>
                  <p>Fête de la rupture du jeûne</p>
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
    name: 'Events'
  }
  </script>
  
  <style scoped>
  .events {
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
  
  .events-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 60px;
  }
  
  .upcoming-events h3, .event-calendar h3 {
    color: #16a085;
    font-size: 28px;
    margin-bottom: 30px;
  }
  
  .event-list {
    display: flex;
    flex-direction: column;
    gap: 25px;
  }
  
  .event-card {
    display: flex;
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    gap: 25px;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .event-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }
  
  .event-card.featured {
    background: linear-gradient(135deg, #16a085, #27ae60);
    color: white;
  }
  
  .event-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 10px;
    padding: 15px;
    min-width: 80px;
    color: #16a085;
  }
  
  .event-card.featured .event-date {
    background: rgba(255,255,255,0.9);
  }
  
  .event-date .day {
    font-size: 24px;
    font-weight: bold;
  }
  
  .event-date .month {
    font-size: 12px;
    font-weight: 500;
  }
  
  .event-info {
    flex: 1;
  }
  
  .event-info h4 {
    color: #16a085;
    font-size: 20px;
    margin-bottom: 10px;
  }
  
  .event-card.featured .event-info h4 {
    color: white;
  }
  
  .event-info p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
  }
  
  .event-card.featured .event-info p {
    color: rgba(255,255,255,0.9);
  }
  
  .event-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 14px;
    color: #888;
  }
  
  .event-card.featured .event-details {
    color: rgba(255,255,255,0.8);
  }
  
  .event-status {
    display: flex;
    align-items: flex-start;
  }
  
  .status {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
  }
  
  .status.featured {
    background: rgba(255,255,255,0.2);
    color: white;
  }
  
  .status.open {
    background: #27ae60;
    color: white;
  }
  
  .status.soon {
    background: #f39c12;
    color: white;
  }
  
  .calendar-events {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .calendar-item {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #16a085;
  }
  
  .calendar-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 100px;
    color: #16a085;
    font-weight: bold;
  }
  
  .hijri-date {
    font-size: 12px;
    color: #888;
  }
  
  .calendar-info h4 {
    color: #16a085;
    margin-bottom: 5px;
  }
  
  .calendar-info p {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
  }
  
  @media (max-width: 768px) {
    .events-content {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    
    .event-card {
      flex-direction: column;
      gap: 15px;
    }
    
    .event-date {
      align-self: flex-start;
    }
    
    .calendar-item {
      flex-direction: column;
      gap: 10px;
    }
  }
  </style>