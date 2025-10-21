<template>
  <div class="charter-cards">
    <!-- Section Charte de l'AEEMCI -->
    <section class="period-card">
      <div class="period-header">
        <div class="period-year">📜</div>
        <h2 class="period-title">Charte de l'AEEMCI</h2>
      </div>
      <div class="period-content">
        <div class="intro-section">
          <div class="quote-box">
            <div class="quote-mark">"</div>
            <blockquote>
              L'AEEMCI est une structure islamique en Côte d'Ivoire, qui contribue à la formation religieuse et managériale des élèves et étudiants musulmans, pour l'émergence d'une élite musulmane consciente des valeurs républicaines et de sa responsabilité communautaire.
            </blockquote>
            <div class="quote-author">- Mission de l'AEEMCI</div>
          </div>
        </div>

        <div class="articles-section">
          <h3>Articles de la Charte</h3>
          <div class="milestone-list">
            <ul>
              <li><strong>Article 1 :</strong> Allah est notre Seigneur et le prophète Mouhammad (saw) est son Messager et notre Prophète</li>
              <li><strong>Article 2 :</strong> Le Coran et la Sunnah du Prophète Muhammad (SAW) restent nos textes de référence et de recours</li>
              <li><strong>Article 3 :</strong> Toute position ou pratique qui trouverait une justification dans le Coran, la Sunnah, les Ecoles Juridiques est acceptée</li>
              <li><strong>Article 4 :</strong> L'AEEMCI ne sera aucunement utilisée comme vecteur d'une idéologie particulière quelle qu'elle soit</li>
              <li><strong>Article 5 :</strong> L'habillement islamique est celui que l'AEEMCI adopte</li>
              <li><strong>Article 6 :</strong> La tolérance et le respect des valeurs d'autrui, la sincérité de l'engagement militant, l'exemplarité dans l'adoration, dans la fraternité et dans la solidarité sont notre credo</li>
              <li><strong>Article 7 :</strong> L'Aeemciste respecte les valeurs et symboles islamiques et doit les défendre partout où besoin se fera</li>
              <li><strong>Article 8 :</strong> La tempérance, le pardon, la discipline et la modération sont des valeurs qui l'habitent</li>
              <li><strong>Article 9 :</strong> L'Aeemciste n'abandonne jamais pour cause de difficulté, il doit persévérer par la prière et le travail</li>
              <li><strong>Article 10 :</strong> L'Aeemciste a droit à une formation islamique et académique. Au respect de son intégrité physique et morale, de son honneur et de sa dignité. La structure s'engage à défendre tout membre atteint dans l'un de ces domaines</li>
              <li><strong>Article 11 :</strong> L'Aeemciste a à l'égard de sa structure un devoir de loyauté et de sincérité. Les imams et les responsables de l'AEEMCI sont des symboles et valeurs pour la structure. Leurs avis sont à respecter</li>
              <li><strong>Article 12 :</strong> Les membres du GROUPE AEEMCI (AEEMCI et l'Amicale des Anciens Aeemcistes (3A) forment une famille unie. Aînés et cadets, à travers une collaboration harmonieuse, contribuent à l'édification d'une société nouvelle. Une société mue par les nobles principes islamiques</li>
              <li><strong>Article 13 :</strong> L'AEEMCI reste un édifice à bâtir où chaque génération doit poser sa pierre au-dessus de la précédente. Elle constitue notre fierté et c'est en elle que nous réalisons notre identité islamique</li>
            </ul>
          </div>
        </div>

        <div class="signature-section">
          <p class="signature-text">Fait à Abidjan le 31 décembre 2024</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  // Animation d'entrée
  setTimeout(() => {
    const elements = document.querySelectorAll('.period-card, .intro-section')
    elements.forEach((el, index) => {
      setTimeout(() => {
        el.style.opacity = '1'
        el.style.transform = 'translateY(0)'
      }, index * 200)
    })
  }, 100)
})
</script>

<style scoped>
/* Variables CSS */
:root {
  --primary: #006400;
  --primary-dark: #004d00;
  --primary-light: #228B22;
  --primary-lighter: #f0f8f0;
  --secondary: #FFD700;
  --background: #fafafa;
  --background-alt: #ffffff;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --border-color: #e1e8ed;
  --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Reset et base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.charter-cards {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: var(--text-primary);
  line-height: 1.6;
  background-color: var(--background);
  font-size: 14px;
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

/* Introduction Section */
.intro-section {
  display: block;
  gap: 2rem;
  margin-bottom: 4rem;
  align-items: center;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.quote-box {
  flex: 0 0 40%;
  background-color: var(--primary-lighter);
  padding: 2rem;
  border-radius: 8px;
  position: relative;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.quote-mark {
  position: absolute;
  top: 1rem;
  left: 1rem;
  font-size: 4rem;
  color: #16a34a;
  opacity: 0.2;
  line-height: 1;
}

blockquote {
  font-size: 1.2rem;
  font-style: italic;
  line-height: 1.6;
  margin-bottom: 1rem;
  position: relative;
  z-index: 1;
  color: var(--primary-dark);
}

.quote-author {
  text-align: right;
  font-weight: 600;
  font-size: 0.9rem;
  color: #16a34a;
}

.intro-text {
  flex: 1;
}

.intro-text p {
  margin-bottom: 1rem;
  font-size: 1rem;
  line-height: 1.7;
}

/* Period Cards */
.period-card {
  margin-bottom: 4rem;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.period-header {
  background-color: #16a34a;
  color: white;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.period-year {
  font-size: 2rem;
  font-weight: 700;
  line-height: 1;
  padding: 0.8rem 1.2rem;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
}

.period-title {
  font-size: 1.8rem;
  margin: 0;
}

.period-content {
  padding: 2rem;
}

.period-content h3 {
  color: #16a34a;
  margin: 2rem 0 1rem 0;
  font-size: 1.3rem;
}

/* Milestone List */
.milestone-list {
  margin: 2rem 0;
}

.milestone-list ul {
  list-style: none;
}

.milestone-list li {
  display: flex;
  margin-bottom: 1rem;
  padding-left: 2rem;
  position: relative;
  line-height: 1.6;
}

.milestone-list li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: #16a34a;
  font-weight: bold;
  font-size: 1.1rem;
}

/* Articles Section */
.articles-section {
  margin-top: 2rem;
}

.articles-section h3 {
  color: #16a34a;
  margin: 2rem 0 1rem 0;
  font-size: 1.3rem;
  text-align: center;
  border-bottom: 2px solid var(--primary-light);
  padding-bottom: 1rem;
}

/* Signature Section */
.signature-section {
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 2px solid var(--primary-light);
  text-align: center;
}

.signature-text {
  font-style: italic;
  color: var(--primary);
  font-weight: 600;
  font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .intro-section {
    flex-direction: column;
  }

  .quote-box {
    flex: none;
  }

  .period-header {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }

  .period-year {
    font-size: 1.5rem;
  }

  .period-title {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .charter-cards {
    padding: 1rem;
  }

  .period-content {
    padding: 1rem;
  }
}
</style>
