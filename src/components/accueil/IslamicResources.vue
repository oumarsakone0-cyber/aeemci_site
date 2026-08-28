<template>
    <section class="islamic-resources">
      <div class="container">
        <div class="section-header">
          <h2>Ressources Islamiques</h2>
          <p>Outils et ressources pour enrichir votre pratique religieuse</p>
        </div>
        
        <div class="resources-grid">
          <div class="resource-card">
            <div class="resource-icon">🕐</div>
            <h3>Horaires de Prières</h3>
            <div class="prayer-times">
              <div class="prayer-time">
                <span class="prayer-name">Fajr</span>
                <span class="time">05:30</span>
              </div>
              <div class="prayer-time">
                <span class="prayer-name">Dhuhr</span>
                <span class="time">12:15</span>
              </div>
              <div class="prayer-time">
                <span class="prayer-name">Asr</span>
                <span class="time">15:45</span>
              </div>
              <div class="prayer-time">
                <span class="prayer-name">Maghrib</span>
                <span class="time">18:20</span>
              </div>
              <div class="prayer-time">
                <span class="prayer-name">Isha</span>
                <span class="time">19:35</span>
              </div>
            </div>
            <p class="location">📍 Abidjan, Côte d'Ivoire</p>
          </div>
          
          <div class="resource-card">
            <div class="resource-icon">📖</div>
            <h3>Verset du Jour</h3>
            <div class="daily-verse">
              <p class="arabic-text">وَمَن يَتَّقِ اللَّهَ يَجْعَل لَّهُ مَخْرَجًا</p>
              <p class="translation">"Et quiconque craint Allah, Il lui donnera une issue favorable"</p>
              <p class="reference">Sourate At-Talaq, Verset 2</p>
            </div>
          </div>
          
          <div class="resource-card">
            <div class="resource-icon">🤲</div>
            <h3>Invocations Quotidiennes</h3>
            <div class="duas">
              <div class="dua-item">
                <h4>Au réveil</h4>
                <p class="arabic">الحمد لله الذي أحيانا بعد ما أماتنا وإليه النشور</p>
              </div>
              <div class="dua-item">
                <h4>Avant de manger</h4>
                <p class="arabic">بسم الله</p>
              </div>
              <div class="dua-item">
                <h4>Après avoir mangé</h4>
                <p class="arabic">الحمد لله الذي أطعمنا وسقانا وجعلنا مسلمين</p>
              </div>
            </div>
          </div>
          
          <div class="resource-card">
            <div class="resource-icon">🌙</div>
            <h3>Calendrier Hijri</h3>
            <div class="hijri-calendar">
              <div class="current-date">
                <span class="hijri-date">15 Rajab 1446</span>
                <span class="gregorian-date">16 Janvier 2025</span>
              </div>
              <div class="upcoming-events">
                <h4>Prochains événements religieux :</h4>
                <ul>
                  <li>27 Rajab - Isra et Mi'raj</li>
                  <li>15 Sha'ban - Laylat al-Bara'at</li>
                  <li>1 Ramadan - Début du Ramadan</li>
                </ul>
              </div>
            </div>
          </div>
          
          <div class="resource-card">
            <div class="resource-icon">🎧</div>
            <h3>Récitations Audio</h3>
            <div class="audio-resources">
              <div class="audio-item">
                <h4>Sourate Al-Fatiha</h4>
                <p>Récitation par Sheikh Abdul Rahman Al-Sudais</p>
                <button class="play-btn">▶️ Écouter</button>
              </div>
              <div class="audio-item">
                <h4>Sourate Al-Baqarah</h4>
                <p>Récitation par Sheikh Mishary Rashid</p>
                <button class="play-btn">▶️ Écouter</button>
              </div>
            </div>
          </div>
          
          <div class="resource-card">
            <div class="resource-icon">📚</div>
            <h3>Hadith du Jour</h3>
            <div class="daily-hadith">
              <p class="hadith-text">"Le croyant n'est pas celui qui mange à sa faim tandis que son voisin a faim."</p>
              <p class="hadith-source">Rapporté par Al-Bukhari</p>
              <div class="hadith-lesson">
                <h4>Enseignement :</h4>
                <p>L'importance de la solidarité et du partage dans la communauté musulmane.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'IslamicResources'
  }
  </script>
  
  <style scoped>
  .islamic-resources {
    padding: 80px 20px;
    background: var(--surface);
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
  
  .resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
  }
  
  .resource-card {
    background: white;
    padding: 30px;
    border-radius: 0;
    box-shadow: var(--shadow);
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .resource-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow);
  }
  
  .resource-icon {
    font-size: 48px;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .resource-card h3 {
    color: var(--green);
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .prayer-times {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
  }
  
  .prayer-time {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background: var(--surface);
    border-radius: 0;
  }
  
  .prayer-name {
    font-weight: 500;
    color: var(--ink);
  }
  
  .time {
    font-weight: bold;
    color: var(--green);
  }
  
  .location {
    text-align: center;
    color: var(--muted);
    font-size: 14px;
  }
  
  .daily-verse {
    text-align: center;
  }
  
  .arabic-text {
    font-size: 24px;
    color: var(--green);
    margin-bottom: 15px;
    font-family: var(--display);
    line-height: 1.8;
  }
  
  .translation {
    font-style: italic;
    color: var(--muted);
    margin-bottom: 10px;
    line-height: 1.6;
  }
  
  .reference {
    font-size: 14px;
    color: var(--muted);
    font-weight: 500;
  }
  
  .duas {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .dua-item h4 {
    color: var(--green);
    margin-bottom: 8px;
    font-size: 16px;
  }
  
  .arabic {
    font-family: var(--display);
    font-size: 18px;
    color: var(--ink);
    line-height: 1.8;
    text-align: right;
  }
  
  .hijri-calendar {
    text-align: center;
  }
  
  .current-date {
    margin-bottom: 25px;
  }
  
  .hijri-date {
    display: block;
    font-size: 24px;
    color: var(--green);
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  .gregorian-date {
    color: var(--muted);
    font-size: 16px;
  }
  
  .upcoming-events h4 {
    color: var(--green);
    margin-bottom: 15px;
  }
  
  .upcoming-events ul {
    list-style: none;
    padding: 0;
  }
  
  .upcoming-events li {
    padding: 8px 0;
    color: var(--muted);
    border-bottom: 1px solid var(--line);
  }
  
  .upcoming-events li:last-child {
    border-bottom: none;
  }
  
  .audio-resources {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .audio-item {
    padding: 15px;
    background: var(--surface);
    border-radius: 0;
  }
  
  .audio-item h4 {
    color: var(--green);
    margin-bottom: 5px;
  }
  
  .audio-item p {
    color: var(--muted);
    font-size: 14px;
    margin-bottom: 10px;
  }
  
  .play-btn {
    background: var(--green);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 0;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
  }
  
  .play-btn:hover {
    background: var(--green);
  }
  
  .daily-hadith {
    text-align: center;
  }
  
  .hadith-text {
    font-size: 18px;
    color: var(--ink);
    font-style: italic;
    margin-bottom: 15px;
    line-height: 1.6;
  }
  
  .hadith-source {
    color: var(--green);
    font-weight: 500;
    margin-bottom: 20px;
  }
  
  .hadith-lesson {
    text-align: left;
    padding: 15px;
    background: var(--surface);
    border-radius: 0;
  }
  
  .hadith-lesson h4 {
    color: var(--green);
    margin-bottom: 8px;
  }
  
  .hadith-lesson p {
    color: var(--muted);
    line-height: 1.6;
  }
  
  @media (max-width: 768px) {
    .resources-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>