<template>
  <div class="app">
    <!-- Header -->

    <!-- Bannière élégante avec motif islamique -->
    <section class="page-banner">
      <div class="banner-pattern"></div>
      
      <!-- Motifs islamiques animés -->
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
          <h1>Notre <span class="highlight">Boutique</span></h1>
          <p class="subtitle">Découvrez notre sélection de produits islamiques authentiques</p>
          <div class="breadcrumbs">
            <a href="#">Accueil</a> <span class="separator">/</span> <span class="current">Boutique</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Contenu principal -->
    <main class="main-content">
      <div class="container">
        <!-- Section des produits -->
        <section class="products-section">
          <div class="section-header">
            <h2 class="section-title">Nos Produits</h2>
            <p class="section-description">Une sélection soigneusement choisie de produits islamiques de qualité</p>
          </div>

          <div class="products-grid">
            <div v-for="product in products" :key="product.id" class="product-card">
              <div class="product-image">
                <img :src="product.image" :alt="product.name" />
              </div>
              <div class="product-info">
                <h3 class="product-name">{{ product.name }}</h3>
                <p class="product-price">{{ product.price }} Fcfa</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Section contact -->
        <section class="contact-section">
          <div class="section-header">
            <h2 class="section-title">Pour Commander</h2>
            <p class="section-description">Contactez-nous pour passer votre commande</p>
          </div>

          <div class="contact-grid">
            <div class="contact-card">
              <div class="contact-icon">📞</div>
              <h3 class="contact-title">Téléphone</h3>
              <p class="contact-info">+33 1 23 45 67 89</p>
              <p class="contact-hours">Lun-Ven: 9h-18h</p>
            </div>

            <div class="contact-card">
              <div class="contact-icon">📧</div>
              <h3 class="contact-title">Email</h3>
              <p class="contact-info">commande@boutique-islamique.fr</p>
              <p class="contact-hours">Réponse sous 24h</p>
            </div>

            <div class="contact-card">
              <div class="contact-icon">💬</div>
              <h3 class="contact-title">WhatsApp</h3>
              <p class="contact-info">+33 6 12 34 56 78</p>
              <p class="contact-hours">Disponible 7j/7</p>
            </div>
          </div>
        </section>
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-pattern"></div>
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <div class="footer-logo">
              <span class="logo-icon">🕌</span>
              <span class="logo-text">Boutique Islamique</span>
            </div>
            <p class="footer-description">
              Votre boutique de confiance pour tous vos besoins islamiques. 
              Produits authentiques et service de qualité.
            </p>
          </div>

          <div class="footer-section">
            <h3 class="footer-title">Navigation</h3>
            <ul class="footer-links">
              <li><a href="#">Accueil</a></li>
              <li><a href="#">Produits</a></li>
              <li><a href="#">À propos</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h3 class="footer-title">Contact</h3>
            <ul class="contact-info">
              <li>📍 123 Rue de la Paix, 75001 Paris</li>
              <li>📞 +33 1 23 45 67 89</li>
              <li>📧 contact@boutique-islamique.fr</li>
            </ul>
          </div>

          <div class="footer-section">
            <h3 class="footer-title">Horaires</h3>
            <ul class="opening-hours">
              <li>Lundi - Vendredi: 9h - 18h</li>
              <li>Samedi: 10h - 16h</li>
              <li>Dimanche: Fermé</li>
            </ul>
          </div>
        </div>

        <div class="footer-bottom">
          <p>&copy; 2024 Boutique Islamique. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const products = ref([
  {
    id: 1,
    name: "Coran avec traduction française",
    price: "25",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 2,
    name: "Tapis de prière en soie",
    price: "45",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 3,
    name: "Chapelet en bois d'olivier",
    price: "15",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 4,
    name: "Calligraphie Allah dorée",
    price: "35",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 5,
    name: "Parfum musc blanc",
    price: "20",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 6,
    name: "Hijab en soie premium",
    price: "30",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 7,
    name: "Livre des invocations",
    price: "18",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 8,
    name: "Lanterne ramadan décorative",
    price: "40",
    image: "/placeholder.svg?height=300&width=300"
  },
  {
    id: 9,
    name: "Encens bakhour premium",
    price: "12",
    image: "/placeholder.svg?height=300&width=300"
  }
])
</script>

<style scoped>
:root {
  --primary: #2d5a27;
  --secondary: #4a7c59;
  --accent: #8fbc8f;
  --background: #f8fdf8;
  --background-alt: #f0f8f0;
  --text-primary: #2c3e50;
  --text-secondary: #7f8c8d;
  --white: #ffffff;
  --shadow: rgba(0, 0, 0, 0.1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: var(--text-primary);
  background-color: var(--background);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

/* Header */
.header {
  background-color: var(--white);
  box-shadow: 0 2px 10px var(--shadow);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--primary);
}

.logo-icon {
  font-size: 2rem;
}

.nav {
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: var(--text-primary);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: var(--primary);
}

/* Bannière */
.page-banner {
  background: linear-gradient(135deg, #16a34a 0%, #16a34a 100%);
  color: white;
  padding: 4rem 0;
  position: relative;
  overflow: hidden;
}

.banner-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.islamic-patterns {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden;
}

.islamic-pattern {
  position: absolute;
  opacity: 0.3;
  animation: float 20s infinite linear;
}

.islamic-pattern.star::before {
  content: '✦';
  font-size: 1.5rem;
  color: white;
}

.islamic-pattern.crescent::before {
  content: '☪';
  font-size: 2rem;
  color: white;
}

.islamic-pattern.geometric::before {
  content: '❋';
  font-size: 1.2rem;
  color: white;
}

@keyframes float {
  0% { transform: translateY(100vh) rotate(0deg); }
  100% { transform: translateY(-100px) rotate(360deg); }
}

.page-title {
  text-align: center;
  position: relative;
  z-index: 2;
}

.page-title h1 {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.highlight {
  color: var(--accent);
}

.subtitle {
  font-size: 1.2rem;
  margin-bottom: 1.5rem;
  opacity: 0.9;
}

.breadcrumbs {
  font-size: 0.9rem;
  opacity: 0.8;
}

.breadcrumbs a {
  color: white;
  text-decoration: none;
}

.separator {
  margin: 0 0.5rem;
}

/* Contenu principal */
.main-content {
  padding: 4rem 0;
}

.section-header {
  text-align: center;
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2.5rem;
  color: var(--primary);
  margin-bottom: 1rem;
}

.section-description {
  font-size: 1.1rem;
  color: var(--text-secondary);
  max-width: 600px;
  margin: 0 auto;
}

/* Grille des produits */
.products-section {
  margin-bottom: 4rem;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.product-card {
  background-color: var(--white);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 20px var(--shadow);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.product-image {
  width: 100%;
  height: 250px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.product-info {
  padding: 1.5rem;
  text-align: center;
}

.product-name {
  font-size: 1.2rem;
  color: var(--primary);
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.product-price {
  font-size: 1.4rem;
  color: var(--secondary);
  font-weight: bold;
}

/* Section contact */
.contact-section {
  background-color: var(--background-alt);
  padding: 3rem 0;
  border-radius: 20px;
}

.contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
}

.contact-card {
  background-color: var(--white);
  border-radius: 12px;
  padding: 2rem;
  text-align: center;
  box-shadow: 0 4px 20px var(--shadow);
  transition: transform 0.3s ease;
}

.contact-card:hover {
  transform: translateY(-5px);
}

.contact-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.contact-title {
  font-size: 1.3rem;
  color: var(--primary);
  margin-bottom: 1rem;
}

.contact-info {
  font-size: 1.1rem;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.contact-hours {
  color: var(--text-secondary);
  font-size: 0.9rem;
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
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
  position: relative;
  z-index: 1;
}

.footer-logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  font-size: 1.3rem;
  font-weight: bold;
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
  text-decoration: none;
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
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.opening-hours {
  list-style: none;
  padding: 0;
}

.opening-hours li {
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.footer-bottom {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  opacity: 0.7;
}

/* Responsive */
@media (max-width: 768px) {
  .page-title h1 {
    font-size: 2rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .products-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }
  
  .nav {
    display: none;
  }
}
</style>