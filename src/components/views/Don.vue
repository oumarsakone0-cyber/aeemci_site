<template>
  <div class="app">
    <!-- Header -->

    <!-- Bannière élégante avec motif islamique -->
    <section class="page-banner">
      <div class="banner-pattern"></div>
      
      <!-- Ajout des motifs islamiques animés -->
      <div class="islamic-patterns">
        <!-- Étoiles -->
        <div v-for="n in 8" :key="`star-${n}`" class="islamic-pattern star" :style="{ 
          left: `${Math.random() * 100}%`, 
          top: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 5}s`,
          animationDuration: `${15 + Math.random() * 20}s`
        }"></div>
        
        <!-- Croissants -->
        <div v-for="n in 5" :key="`crescent-${n}`" class="islamic-pattern crescent" :style="{ 
          left: `${Math.random() * 100}%`, 
          top: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 5}s`,
          animationDuration: `${15 + Math.random() * 20}s`
        }"></div>
        
        <!-- Motifs géométriques -->
        <div v-for="n in 10" :key="`geometric-${n}`" class="islamic-pattern geometric" :style="{ 
          left: `${Math.random() * 100}%`, 
          top: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 5}s`,
          animationDuration: `${15 + Math.random() * 20}s`,
          transform: `rotate(${Math.random() * 360}deg)`
        }"></div>
      </div>
      
      <div class="container">
        <div class="page-title">
          <h1>Faire un <span class="highlight">Don</span></h1>
          <p class="subtitle">Soutenez l'AEEMCI dans sa mission d'accompagnement de la jeunesse musulmane</p>
          <div class="breadcrumbs">
            <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">AEEMCI</a> <span class="separator">/</span> <span class="current">Faire un don</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Contenu principal -->
    <main class="main-content">
      <div class="container">

        <!-- Formulaire de don -->
        <section class="donation-section">
          <h2 class="section-title">Effectuer un <span class="highlight">Don</span></h2>
          
          <div class="donation-content">
            <div class="donation-form">
              <h3>Informations du don</h3>
              
              <!-- Montants prédéfinis -->
              <div class="amount-selection">
                <h4>Choisir un montant</h4>
                <div class="amount-buttons">
                  <button 
                    v-for="amount in predefinedAmounts" 
                    :key="amount"
                    class="amount-btn"
                    :class="{ active: selectedAmount === amount }"
                    @click="selectAmount(amount)"
                  >
                    {{ formatAmount(amount) }} FCFA
                  </button>
                </div>
                
                <div class="custom-amount">
                  <label for="customAmount">Montant personnalisé</label>
                  <input 
                    type="number" 
                    id="customAmount"
                    v-model="customAmount"
                    placeholder="Entrez votre montant"
                    @input="selectCustomAmount"
                  >
                </div>
              </div>

              <!-- Informations personnelles -->
              <div class="donor-info">
                <h4>Vos informations</h4>
                <div class="form-row">
                  <div class="form-group">
                    <label for="firstName">Prénom *</label>
                    <input type="text" id="firstName" v-model="donorInfo.firstName" required>
                  </div>
                  <div class="form-group">
                    <label for="lastName">Nom *</label>
                    <input type="text" id="lastName" v-model="donorInfo.lastName" required>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" v-model="donorInfo.email" required>
                  </div>
                  <div class="form-group">
                    <label for="phone">Téléphone *</label>
                    <input type="tel" id="phone" v-model="donorInfo.phone" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="cause">Cause à soutenir</label>
                  <select id="cause" v-model="donorInfo.cause">
                    <option value="">Toutes les causes</option>
                    <option value="bourses">Bourses d'études</option>
                    <option value="infrastructure">Infrastructure</option>
                    <option value="social">Actions sociales</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="message">Message (optionnel)</label>
                  <textarea id="message" v-model="donorInfo.message" rows="3" placeholder="Votre message de soutien..."></textarea>
                </div>

                <div class="form-group">
                  <label class="checkbox-label">
                    <input type="checkbox" v-model="donorInfo.anonymous">
                    Don anonyme
                  </label>
                </div>
              </div>

              <!-- Méthodes de paiement -->
              <div class="payment-methods">
                <h4>Méthode de paiement</h4>
                <div class="payment-options">
                  <div 
                    class="payment-option"
                    :class="{ active: selectedPayment === 'wave' }"
                    @click="selectPayment('wave')"
                  >
                    <div class="payment-logo">
                      <img src="https://play-lh.googleusercontent.com/-Mp3XW7uhwn3KGQxUKGPoc4MbA5ti-3-q23TgoVi9ujBgHWW5n4IySvlG5Exwrxsjw" alt="Wave">
                    </div>
                    <div class="payment-info">
                      <h5>Wave</h5>
                      <p>Paiement sécurisé via Wave</p>
                    </div>
                  </div>

                  <div 
                    class="payment-option"
                    :class="{ active: selectedPayment === 'orange' }"
                    @click="selectPayment('orange')"
                  >
                    <div class="payment-logo">
                      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSHPY0h-BIX7jd7u7SxbCJXvg01GlYnT-mgWg&s" alt="Orange Money">
                    </div>
                    <div class="payment-info">
                      <h5>Orange Money</h5>
                      <p>Paiement via Orange Money</p>
                    </div>
                  </div>

                  <div 
                    class="payment-option"
                    :class="{ active: selectedPayment === 'mtn' }"
                    @click="selectPayment('mtn')"
                  >
                    <div class="payment-logo">
                      <img src="https://www.cie.ci/images/mtn.png" alt="MTN Money">
                    </div>
                    <div class="payment-info">
                      <h5>MTN Money</h5>
                      <p>Paiement via MTN Mobile Money</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="btn btn-primary donation-btn" @click="processDonation">
                Faire un don de {{ formatAmount(finalAmount) }} FCFA
              </button>
            </div>

            <!-- Résumé du don -->
            <div class="donation-summary">
              <h3>Résumé de votre don</h3>
              
              <div class="summary-item">
                <span class="summary-label">Montant :</span>
                <span class="summary-value">{{ formatAmount(finalAmount) }} FCFA</span>
              </div>
              
              <div class="summary-item">
                <span class="summary-label">Cause :</span>
                <span class="summary-value">{{ getCauseName(donorInfo.cause) }}</span>
              </div>
              
              <div class="summary-item">
                <span class="summary-label">Méthode :</span>
                <span class="summary-value">{{ getPaymentName(selectedPayment) }}</span>
              </div>

              <div class="impact-info">
                <h4>Impact de votre don</h4>
                <div class="impact-item" v-for="impact in getImpact(finalAmount)" :key="impact.text">
                  <div class="impact-icon">{{ impact.icon }}</div>
                  <p>{{ impact.text }}</p>
                </div>
              </div>

              <div class="security-info">
                <div class="security-icon">🔒</div>
                <p>Paiement 100% sécurisé et crypté</p>
              </div>
            </div>
          </div>
        </section>

      </div>
    </main>


  </div>
</template>

<script>
export default {
  name: 'DonationPage',
  data() {
    return {
      mobileMenuOpen: false,
      predefinedAmounts: [5000, 10000, 25000, 50000, 100000, 250000],
      selectedAmount: 10000,
      customAmount: '',
      selectedPayment: 'wave',
      donorInfo: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        cause: '',
        message: '',
        anonymous: false
      },
      recentDonors: [
        {
          id: 1,
          name: 'Aminata K.',
          amount: 50000,
          cause: 'Bourses d\'études',
          date: 'Il y a 2 heures',
          message: 'Pour l\'éducation de nos jeunes',
          avatar: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
        },
        {
          id: 2,
          name: 'Ibrahim S.',
          amount: 25000,
          cause: 'Actions sociales',
          date: 'Il y a 5 heures',
          message: 'Qu\'Allah vous bénisse',
          avatar: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
        },
        {
          id: 3,
          name: 'Fatou D.',
          amount: 100000,
          cause: 'Infrastructure',
          date: 'Il y a 1 jour',
          message: 'Pour un avenir meilleur',
          avatar: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
        },
        {
          id: 4,
          name: 'Moussa T.',
          amount: 15000,
          cause: 'Bourses d\'études',
          date: 'Il y a 2 jours',
          message: '',
          avatar: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
        },
        {
          id: 5,
          name: 'Aïcha B.',
          amount: 75000,
          cause: 'Actions sociales',
          date: 'Il y a 3 jours',
          message: 'Barakallahu fikoum',
          avatar: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
        },
        {
          id: 6,
          name: 'Ousmane L.',
          amount: 30000,
          cause: 'Infrastructure',
          date: 'Il y a 4 jours',
          message: 'Continuez le bon travail',
          avatar: 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
        }
      ]
    }
  },
  computed: {
    finalAmount() {
      return this.customAmount || this.selectedAmount || 0;
    }
  },
  methods: {
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
    },
    selectAmount(amount) {
      this.selectedAmount = amount;
      this.customAmount = '';
    },
    selectCustomAmount() {
      this.selectedAmount = null;
    },
    selectPayment(method) {
      this.selectedPayment = method;
    },
    formatAmount(amount) {
      return new Intl.NumberFormat('fr-FR').format(amount);
    },
    getCauseName(cause) {
      const causes = {
        'bourses': 'Bourses d\'études',
        'infrastructure': 'Infrastructure',
        'social': 'Actions sociales',
        '': 'Toutes les causes'
      };
      return causes[cause] || 'Toutes les causes';
    },
    getPaymentName(method) {
      const methods = {
        'wave': 'Wave',
        'orange': 'Orange Money',
        'mtn': 'MTN Money'
      };
      return methods[method] || 'Non sélectionné';
    },
    getImpact(amount) {
      const impacts = [];
      if (amount >= 5000) {
        impacts.push({ icon: '📚', text: 'Fournitures scolaires pour 1 étudiant' });
      }
      if (amount >= 15000) {
        impacts.push({ icon: '🍽️', text: 'Repas pour 10 étudiants nécessiteux' });
      }
      if (amount >= 50000) {
        impacts.push({ icon: '🎓', text: 'Contribution à une bourse d\'études' });
      }
      if (amount >= 100000) {
        impacts.push({ icon: '🏫', text: 'Amélioration des infrastructures' });
      }
      return impacts.length ? impacts : [{ icon: '❤️', text: 'Votre générosité fait la différence' }];
    },
    processDonation() {
      if (!this.donorInfo.firstName || !this.donorInfo.lastName || !this.donorInfo.email || !this.donorInfo.phone) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
      }
      
      if (!this.finalAmount || this.finalAmount < 1000) {
        alert('Le montant minimum est de 1 000 FCFA');
        return;
      }
      
      if (!this.selectedPayment) {
        alert('Veuillez sélectionner une méthode de paiement');
        return;
      }
      
      // Simulation du processus de paiement
      alert(`Redirection vers ${this.getPaymentName(this.selectedPayment)} pour un don de ${this.formatAmount(this.finalAmount)} FCFA`);
    }
  }
}
</script>

<style>
:root {
  --primary: #006400;
  --primary-dark: #004d00;
  --primary-light: #008000;
  --primary-lighter: #e6f0e6;
  --secondary: #f8b400;
  --secondary-dark: #d99b00;
  --secondary-light: #ffc933;
  --accent: #e67e22;
  --text-primary: #333333;
  --text-secondary: #666666;
  --text-light: #999999;
  --background: #ffffff;
  --background-alt: #f5f5f5;
  --border: #e0e0e0;
  --success: #28a745;
  --danger: #dc3545;
  --warning: #ffc107;
  --info: #17a2b8;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: var(--text-primary);
  line-height: 1.6;
  background-color: var(--background);
  font-size: 14px;
}

.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

a {
  color: var(--primary);
  text-decoration: none;
  transition: all 0.3s ease;
}

a:hover {
  color: var(--primary-dark);
}

img {
  max-width: 100%;
  height: auto;
}

.btn {
  display: inline-block;
  padding: 0.6rem 1.2rem;
  border-radius: 4px;
  font-weight: 600;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  font-size: 0.9rem;
  letter-spacing: 0.3px;
}

.btn-primary {
  background-color: var(--primary);
  color: white;
  box-shadow: 0 2px 4px rgba(0, 100, 0, 0.1);
}

.btn-primary:hover {
  background-color: var(--primary-dark);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 3px 6px rgba(0, 100, 0, 0.15);
}

/* Header Styles */
.header {
  background-color: white;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.8rem 0;
}

.logo {
  display: flex;
  align-items: center;
}

.logo-img {
  width: 35px;
  height: 35px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.logo-text {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--primary);
  margin-left: 0.3rem;
}

.main-nav {
  flex-grow: 1;
  display: flex;
  justify-content: flex-end;
}

.nav-list {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  align-items: center;
}

.nav-item {
  position: relative;
  margin-left: 1.2rem;
}

.nav-link {
  color: var(--text-primary);
  font-weight: 500;
  padding: 0.4rem 0;
  display: flex;
  align-items: center;
  font-size: 0.85rem;
  transition: all 0.2s ease;
}

.nav-link.active {
  color: var(--primary);
  font-weight: 600;
}

.nav-link:hover {
  color: var(--primary);
}

.nav-buttons {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.btn-donate, .btn-espace {
  padding: 0.3rem 0.7rem;
  border-radius: 2px;
  font-size: 0.75rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-donate {
  background-color: var(--secondary);
  color: var(--text-primary);
}

.btn-donate:hover {
  background-color: var(--secondary-dark);
  transform: translateY(-2px);
}

.btn-espace {
  background-color: var(--accent);
  color: white;
}

.btn-espace:hover {
  background-color: #d35400;
  transform: translateY(-2px);
}

.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
}

/* Page Banner */
.page-banner {
  background: linear-gradient(rgba(0, 100, 0, 0.9), rgba(0, 100, 0, 0.9));
  color: white;
  padding: 4rem 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.banner-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.1;
}

.page-title {
  position: relative;
  z-index: 1;
}

.page-title h1 {
  font-size: 3rem;
  margin-bottom: 0.5rem;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.subtitle {
  font-size: 1.2rem;
  margin-bottom: 1rem;
  opacity: 0.9;
}

.breadcrumbs {
  font-size: 0.85rem;
  opacity: 0.8;
}

.breadcrumbs a {
  color: white;
  opacity: 0.8;
  transition: opacity 0.3s ease;
}

.breadcrumbs a:hover {
  opacity: 1;
}

.separator {
  margin: 0 0.5rem;
  opacity: 0.6;
}

.current {
  opacity: 1;
  font-weight: 600;
}

.highlight {
  color: var(--secondary);
  font-weight: 800;
}

/* Motifs islamiques animés */
.islamic-patterns {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  overflow: hidden;
  z-index: 1;
}

.islamic-pattern {
  position: absolute;
  opacity: 0.15;
}

.islamic-pattern.star {
  width: 30px;
  height: 30px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
  background-size: contain;
  animation: float-diagonal 25s infinite linear;
}

.islamic-pattern.crescent {
  width: 25px;
  height: 25px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
  background-size: contain;
  animation: float-horizontal 30s infinite linear;
}

.islamic-pattern.geometric {
  width: 40px;
  height: 40px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0 L100 50 L50 100 L0 50 Z'/%3E%3C/svg%3E");
  background-size: contain;
  animation: float-vertical 20s infinite linear;
}

@keyframes float-diagonal {
  0% {
    transform: translate(0, 0) rotate(0deg);
  }
  25% {
    transform: translate(100px, 100px) rotate(90deg);
  }
  50% {
    transform: translate(200px, 200px) rotate(180deg);
  }
  75% {
    transform: translate(100px, 300px) rotate(270deg);
  }
  100% {
    transform: translate(0, 400px) rotate(360deg);
  }
}

@keyframes float-horizontal {
  0% {
    transform: translate(-100px, 0) rotate(0deg);
  }
  50% {
    transform: translate(calc(100vw + 100px), 0) rotate(180deg);
  }
  100% {
    transform: translate(-100px, 0) rotate(360deg);
  }
}

@keyframes float-vertical {
  0% {
    transform: translate(0, -100px) rotate(0deg);
  }
  50% {
    transform: translate(0, calc(100vh + 100px)) rotate(180deg);
  }
  100% {
    transform: translate(0, -100px) rotate(360deg);
  }
}

/* Main Content */
.main-content {
  padding: 4rem 0;
  background-color: var(--background);
}

/* Introduction Section */
.intro-section {
  display: flex;
  gap: 2rem;
  margin-bottom: 4rem;
  align-items: center;
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
  color: var(--primary);
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
  color: var(--primary);
}

.intro-text {
  flex: 1;
}

.intro-text p {
  margin-bottom: 1rem;
  font-size: 1rem;
  line-height: 1.8;
}

/* Section Titles */
.section-title {
  text-align: center;
  font-size: 2rem;
  margin-bottom: 2rem;
  position: relative;
  padding-bottom: 1rem;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: var(--primary);
  border-radius: 3px;
}

/* Causes Section */
.causes-section {
  margin-bottom: 4rem;
}

.causes-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.cause-card {
  background-color: white;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.cause-card:hover {
  transform: translateY(-5px);
}

.cause-icon {
  font-size: 3rem;
  text-align: center;
  margin-bottom: 1rem;
}

.cause-title {
  font-size: 1.3rem;
  color: var(--primary);
  margin-bottom: 0.5rem;
  text-align: center;
}

.cause-description {
  text-align: center;
  margin-bottom: 1.5rem;
  color: var(--text-secondary);
}

.cause-progress {
  margin-bottom: 1rem;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background-color: var(--background-alt);
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.progress-fill {
  height: 100%;
  background-color: var(--primary);
  transition: width 0.3s ease;
}

.progress-text {
  font-size: 0.9rem;
  color: var(--primary);
  font-weight: 600;
}

.cause-amount {
  text-align: center;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

/* Donation Section */
.donation-section {
  margin-bottom: 4rem;
}

.donation-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 3rem;
}

.donation-form {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.donation-form h3 {
  font-size: 1.5rem;
  color: var(--primary);
  margin-bottom: 2rem;
  text-align: center;
}

.donation-form h4 {
  font-size: 1.2rem;
  color: var(--primary);
  margin-bottom: 1rem;
}

.amount-selection {
  margin-bottom: 2rem;
}

.amount-buttons {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 1rem;
}

.amount-btn {
  padding: 1rem;
  border: 2px solid var(--border);
  background-color: white;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 600;
}

.amount-btn:hover {
  border-color: var(--primary);
  background-color: var(--primary-lighter);
}

.amount-btn.active {
  border-color: var(--primary);
  background-color: var(--primary);
  color: white;
}

.custom-amount {
  margin-top: 1rem;
}

.custom-amount label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.custom-amount input {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid var(--border);
  border-radius: 4px;
  font-size: 1rem;
}

.donor-info {
  margin-bottom: 2rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid var(--border);
  border-radius: 4px;
  font-family: inherit;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 2px rgba(0, 100, 0, 0.1);
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.checkbox-label input {
  width: auto;
  margin-right: 0.5rem;
}

.payment-methods {
  margin-bottom: 2rem;
}

.payment-options {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.payment-option {
  display: flex;
  align-items: center;
  padding: 1rem;
  border: 2px solid var(--border);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.payment-option:hover {
  border-color: var(--primary);
  background-color: var(--primary-lighter);
}

.payment-option.active {
  border-color: var(--primary);
  background-color: var(--primary-lighter);
}

.payment-logo {
  width: 50px;
  height: 50px;
  margin-right: 1rem;
}

.payment-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.payment-info h5 {
  font-size: 1.1rem;
  color: var(--primary);
  margin-bottom: 0.3rem;
}

.payment-info p {
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.donation-btn {
  width: 100%;
  padding: 1rem;
  font-size: 1.1rem;
  font-weight: 700;
}

.donation-summary {
  background-color: var(--primary-lighter);
  padding: 2rem;
  border-radius: 8px;
  height: fit-content;
}

.donation-summary h3 {
  font-size: 1.3rem;
  color: var(--primary);
  margin-bottom: 1.5rem;
  text-align: center;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--border);
}

.summary-label {
  font-weight: 600;
}

.summary-value {
  color: var(--primary);
  font-weight: 600;
}

.impact-info {
  margin: 2rem 0;
}

.impact-info h4 {
  font-size: 1.1rem;
  color: var(--primary);
  margin-bottom: 1rem;
}

.impact-item {
  display: flex;
  align-items: center;
  margin-bottom: 0.8rem;
}

.impact-icon {
  font-size: 1.5rem;
  margin-right: 0.8rem;
}

.security-info {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background-color: white;
  border-radius: 8px;
  margin-top: 1rem;
}

.security-icon {
  font-size: 1.5rem;
  margin-right: 0.5rem;
}

/* Recent Donors Section */
.recent-donors-section {
  margin-bottom: 4rem;
}

.donors-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.donor-card {
  background-color: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.donor-card:hover {
  transform: translateY(-5px);
}

.donor-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 auto 1rem;
}

.donor-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.donor-name {
  font-size: 1.1rem;
  color: var(--primary);
  margin-bottom: 0.3rem;
  text-align: center;
}

.donor-amount {
  font-size: 1rem;
  font-weight: 600;
  color: var(--secondary);
  margin-bottom: 0.3rem;
  text-align: center;
}

.donor-cause {
  font-size: 0.9rem;
  color: var(--text-secondary);
  margin-bottom: 0.3rem;
  text-align: center;
}

.donor-date {
  font-size: 0.8rem;
  color: var(--text-light);
  margin-bottom: 0.5rem;
  text-align: center;
}

.donor-message {
  font-size: 0.9rem;
  font-style: italic;
  color: var(--text-secondary);
  text-align: center;
  background-color: var(--background-alt);
  padding: 0.8rem;
  border-radius: 4px;
}

/* Footer */
.footer {
  background-color: #333;
  color: white;
  padding: 3rem 0 1rem;
  position: relative;
  overflow: hidden;
}

.footer-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.1;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
  margin-bottom: 2rem;
  position: relative;
  z-index: 1;
}

.footer-logo {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.footer-description {
  font-size: 0.9rem;
  opacity: 0.8;
  line-height: 1.6;
}

.footer-title {
  font-size: 1.1rem;
  margin-bottom: 1.2rem;
  position: relative;
  padding-bottom: 0.5rem;
}

.footer-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 2px;
  background-color: var(--primary);
  border-radius: 2px;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin-bottom: 0.6rem;
}

.footer-links a {
  color: white;
  opacity: 0.8;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.footer-links a:hover {
  opacity: 1;
  padding-left: 3px;
}

.contact-info {
  list-style: none;
  padding: 0;
}

.contact-info li {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.opening-hours {
  list-style: none;
  padding: 0;
}

.opening-hours li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.6rem;
  font-size: 0.9rem;
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  font-size: 0.85rem;
  position: relative;
  z-index: 1;
}

.footer-nav {
  display: flex;
  gap: 1.5rem;
}

.footer-nav a {
  color: white;
  opacity: 0.8;
  transition: opacity 0.3s ease;
}

.footer-nav a:hover {
  opacity: 1;
}

/* Responsive Styles */
@media (max-width: 992px) {
  .intro-section {
    flex-direction: column;
  }
  
  .quote-box {
    width: 100%;
  }
  
  .causes-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .donation-content {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
  
  .donors-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .footer-content {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .mobile-menu-btn {
    display: block;
  }
  
  .main-nav {
    position: fixed;
    top: 60px;
    left: 0;
    right: 0;
    background-color: white;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    display: none;
    flex-direction: column;
  }
  
  .main-nav.mobile-open {
    display: flex;
    animation: slide-down 0.3s ease;
  }
  
  @keyframes slide-down {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .nav-list {
    flex-direction: column;
    width: 100%;
    margin-bottom: 1rem;
  }
  
  .nav-item {
    margin: 0;
    margin-bottom: 0.8rem;
  }
  
  .nav-buttons {
    width: 100%;
    justify-content: space-between;
  }
  
  .btn-donate, .btn-espace {
    width: 48%;
    text-align: center;
  }
  
  .page-title h1 {
    font-size: 2.2rem;
  }
  
  .causes-grid {
    grid-template-columns: 1fr;
  }
  
  .amount-buttons {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .donors-grid {
    grid-template-columns: 1fr;
  }
  
  .footer-content {
    grid-template-columns: 1fr;
  }
  
  .footer-bottom {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .footer-nav {
    flex-direction: column;
    gap: 0.6rem;
  }
}

@media (max-width: 576px) {
  .page-title h1 {
    font-size: 1.8rem;
  }
  
  .amount-buttons {
    grid-template-columns: 1fr;
  }
}
</style>
