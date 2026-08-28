<template>
  <section class="prayer-times">
    <div class="container">
      <div class="prayer-grid">
        <button class="prayer-item" @click="handleCard('carte')">
          <div class="icon">🪪</div>
          <span class="prayer-name">Carte Aeemciste</span>
          <span class="prayer-time">Demander sa carte</span>
        </button>

        <button class="prayer-item blinking-button" @click="handleCard('senafoi')">
          <div class="icon blinking-icon">🕌</div>
          <span class="prayer-name blinking-text">Inscription SENAFOI 25</span>
          <span class="prayer-time">Participer au séminaire</span>
        </button>

        <button class="prayer-item" @click="handleCard('autre')">
          <div class="icon">💳</div>
          <span class="prayer-name">Statut de la carte</span>
          <span class="prayer-time">Vérifier la disponibilité</span>
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
const handleCard = (type) => {
  if (type === 'carte') {
    window.location.href = '/carte_membre'
  } else if (type === 'senafoi') {
    window.location.href = '/seminaristes'
  } else {
    window.location.href = '/verification_carte'
  }
}
</script>

<style scoped>
.prayer-times {
  background: #16a34a;
  color: white;
  padding: 2.5rem 0;
}

.prayer-times h3 {
  text-align: center;
  margin-bottom: 2rem;
  color: var(--secondary);
  font-size: 1.8rem;
}

.prayer-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  justify-content: center;
  max-width: 800px;
  margin: 0 auto;
}

/* BOUTON = CARTE */
.prayer-item {
  background: rgba(255, 255, 255, 0.1);
  padding: 1.5rem;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.2s ease, background-color 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  text-align: center;
  border: none;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.prayer-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-3px);
}

/* ANIMATION DE CLIGNOTEMENT INTENSE POUR LE BOUTON INSCRIPTION */
.blinking-button {
  animation: intenseBlink 0.8s infinite, glow 2s infinite alternate;
  border: 3px solid #facc15;
  background: rgba(250, 204, 21, 0.3) !important;
  position: relative;
  overflow: hidden;
}

.blinking-button::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  animation: shine 1.5s infinite;
}

.blinking-button:hover {
  animation-play-state: paused;
  background: rgba(250, 204, 21, 0.5) !important;
  transform: translateY(-5px) scale(1.08);
  box-shadow: 0 8px 25px rgba(250, 204, 21, 0.6);
}

/* Animation principale de clignotement */
@keyframes intenseBlink {
  0% {
    background: rgba(250, 204, 21, 0.1);
    border-color: #facc15;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  25% {
    background: rgba(250, 204, 21, 0.6);
    border-color: #fbbf24;
    box-shadow: 0 4px 20px rgba(250, 204, 21, 0.4), 0 0 30px rgba(250, 204, 21, 0.3);
  }
  50% {
    background: rgba(250, 204, 21, 0.8);
    border-color: #f59e0b;
    box-shadow: 0 6px 25px rgba(250, 204, 21, 0.6), 0 0 40px rgba(250, 204, 21, 0.5);
    transform: scale(1.02);
  }
  75% {
    background: rgba(250, 204, 21, 0.6);
    border-color: #fbbf24;
    box-shadow: 0 4px 20px rgba(250, 204, 21, 0.4), 0 0 30px rgba(250, 204, 21, 0.3);
  }
  100% {
    background: rgba(250, 204, 21, 0.1);
    border-color: #facc15;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
}

/* Animation de lueur */
@keyframes glow {
  0% {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 20px rgba(250, 204, 21, 0.3);
  }
  100% {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 40px rgba(250, 204, 21, 0.7);
  }
}

/* Animation de brillance */
@keyframes shine {
  0% {
    transform: translateX(-100%) translateY(-100%) rotate(45deg);
  }
  100% {
    transform: translateX(100%) translateY(100%) rotate(45deg);
  }
}

/* Animation pour l'icône */
.blinking-icon {
  animation: iconPulse 1s infinite;
}

@keyframes iconPulse {
  0%, 100% {
    transform: scale(1);
    filter: brightness(1);
  }
  50% {
    transform: scale(1.2);
    filter: brightness(1.5) drop-shadow(0 0 10px #facc15);
  }
}

/* Animation pour le texte */
.blinking-text {
  animation: textGlow 1.2s infinite;
}

@keyframes textGlow {
  0%, 100% {
    text-shadow: none;
    color: white;
  }
  50% {
    text-shadow: 0 0 10px #facc15, 0 0 20px #facc15;
    color: #facc15;
  }
}

.icon {
  font-size: 2.2rem;
  margin-bottom: 0.6rem;
  color: #facc15;
}

.prayer-name {
  display: block;
  font-weight: bold;
  margin-bottom: 0.4rem;
  font-size: 1.1rem;
}

.prayer-time {
  font-size: 1rem;
  color: var(--secondary);
}

.container {
  width: 100%;
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 0.8rem;
}
</style>