<template>
  <!-- Header -->
  <header class="header">
    <div class="container">
      <!-- Logo -->
      <div class="nav-brand">
        <img src="../../assets/aeemci.jpeg" width="75" alt="AEEMCI Logo">
        <div>
          <h1 class="logo">AEEMCI</h1>
          <p class="tagline">Association des Elèves et Etudiants Musulmans de Côte d'Ivoire</p>
        </div>
      </div>

      <!-- Mobile hamburger menu -->
      <button style="color: black;" class="mobile-menu-btn" @click="toggleMobileMenu" :class="{ active: isMobileMenuOpen }">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- Navigation -->
      <nav class="nav-menu" :class="{ 'mobile-open': isMobileMenuOpen }">
        <!-- Accueil -->
        <div class="nav-item">
          <a href="/" class="nav-link" @click="closeMobileMenu">Accueil</a>
        </div>

        <!-- A Propos -->
        <div class="nav-item has-dropdown">
          <a href="#" class="nav-link" @click="toggleDropdown('apropos', $event)">
            A Propos <span class="dropdown-arrow">▼</span>
          </a>
          <div class="dropdown" :class="{ 'mobile-open': activeDropdown === 'apropos' }">
            <a href="/charte" @click="closeMobileMenu">Charte</a>
            <a href="/historique" @click="closeMobileMenu">Historiques</a>
            <a href="/organigramme" @click="closeMobileMenu">Organigramme</a>
            <a href="/organes" @click="closeMobileMenu">Organes</a>
            <a href="/bureau" @click="closeMobileMenu">Comité Exécutif</a>
            <a href="/cellules" @click="closeMobileMenu">Cellules Spécialisées</a>
            <a href="/cartographie" @click="closeMobileMenu">Cartographie</a>
          </div>
        </div>

        <!-- Médiathèque -->
        <div class="nav-item has-dropdown">
          <a href="#" class="nav-link" @click="toggleDropdown('mediatheque', $event)">
            Médiathèque <span class="dropdown-arrow">▼</span>
          </a>
          <div class="dropdown" :class="{ 'mobile-open': activeDropdown === 'mediatheque' }">
            <a href="/tv" @click="closeMobileMenu">Aeemci TV</a>
            <a href="/images" @click="closeMobileMenu">Images</a>
            <a href="/videos" @click="closeMobileMenu">Vidéos</a>
          </div>
        </div>


        <div class="nav-item has-dropdown">
          <a href="#" class="nav-link" @click="toggleDropdown('activite', $event)">
            Inscription <span class="dropdown-arrow">▼</span>
          </a>
          <div class="dropdown" :class="{ 'mobile-open': activeDropdown === 'activite' }">
            <a target="_blank" href="https://senafoi.aeemci-ce.ci/" @click="closeMobileMenu">SENAFOI</a>
            <a href="/" @click="closeMobileMenu">CIMA</a>
            <a href="/carte_membre" @click="closeMobileMenu">Carte de Membre</a>
            <a href="/verification_carte" @click="closeMobileMenu">Vérification de Carte</a>
          </div>
        </div>

        <!-- Contact -->
        <div class="nav-item">
          <a href="/contact" class="nav-link" @click="closeMobileMenu">Contact</a>
        </div>

        <!-- Mobile actions -->
        <div class="header-actions mobile-actions">
          <button class="btn btn-primary" @click="closeMobileMenu" style="color: green;">Statut/Réglements</button>
          <a href="http://militant.aeemci-ce.ci/login"><button class="btn btn-primary" @click="closeMobileMenu">Espace Militant</button></a>
        </div>
      </nav>

      <!-- Desktop actions -->
      <div class="header-actions desktop-actions">
        <a href="/don"><button class="btn btn-outline clignote">Faire un don</button></a>
        <a href="http://militant.aeemci-ce.ci/"><button class="btn btn-primary">Espace Militant</button></a>
      </div>
    </div>
  </header>
</template> 
<script setup>
import { ref } from 'vue'

const isMobileMenuOpen = ref(false)
const activeDropdown = ref(null)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  if (!isMobileMenuOpen.value) {
    activeDropdown.value = null
  }
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
  activeDropdown.value = null
}

const toggleDropdown = (dropdown, event) => {
  if (window.innerWidth <= 768) {
    event.preventDefault()
    activeDropdown.value = activeDropdown.value === dropdown ? null : dropdown
  }
}
</script>

<style scoped>
/* Container */
.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Header */
.header {
  background: linear-gradient(135deg, white 0%, white 100%);
  color: black;
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  transition: all 0.3s ease;
  border-bottom: 3px solid #018404;
}

/* Logo */
.nav-brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}
.nav-brand .logo {
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0;
}
.nav-brand .tagline {
  font-size: 0.85rem;
  color: black;
  opacity: 0.9;
  margin: 0;
}

/* Hamburger */
.mobile-menu-btn {
  display: none;
  flex-direction: column;
  justify-content: space-around;
  width: 30px;
  height: 30px;
  background: #018404;
  border: none;
  cursor: pointer;
  padding: 5px;
  z-index: 1001;
}
.mobile-menu-btn span {
  width: 100%;
  height: 3px;
  background: white;
  border-radius: 2px;
  transition: all 0.3s ease;
}
.mobile-menu-btn.active span:nth-child(1) {
  transform: rotate(45deg) translate(7px, 7px);
}
.mobile-menu-btn.active span:nth-child(2) {
  opacity: 0;
}
.mobile-menu-btn.active span:nth-child(3) {
  transform: rotate(-45deg) translate(7px, -7px);
}

/* Clignotant */
.clignote {
  color: green;
  border: 2px solid green;
  font-weight: bold;
  animation: clignoterCouleur 1s infinite;
}
@keyframes clignoterCouleur {
  0%, 100% { color: green; border-color: green; }
  50% { color: white; border-color: white; }
}

/* Navigation */
.nav-menu {
  display: flex;
  gap: 1.5rem;
}
.nav-item {
  position: relative;
}
.nav-link {
  color: black;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 0;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}
.nav-link:hover {
  color: #ffd700;
}
.nav-link::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 2px;
  background: #ffd700;
  transition: width 0.3s ease;
}
.nav-link:hover::after {
  width: 100%;
}
.dropdown-arrow {
  font-size: 0.7rem;
  transition: transform 0.3s ease;
}
.nav-item.has-dropdown:hover .dropdown-arrow {
  transform: rotate(180deg);
}

/* Dropdown Desktop */
.dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  color: #018404;
  border-radius: 8px;
  min-width: 180px;
  overflow: hidden;
  opacity: 0;
  transform: translateY(10px);
  pointer-events: none;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  z-index: 200;
}
.nav-item.has-dropdown:hover .dropdown {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}
.dropdown a {
  display: block;
  padding: 0.75rem 1rem;
  color: #018404;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.3s ease, color 0.3s ease;
}
.dropdown a:hover {
  background: #018404;
  color: white;
}

/* Buttons */
.header-actions {
  display: flex;
  gap: 1rem;
}
.btn {
  padding: 0.65rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}
.btn-primary {
  background: linear-gradient(135deg, #f5841f 0%, #ff9c00 100%);
  color: white;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
}
.btn-outline {
  background: transparent;
  border: 2px solid white;
  color: white;
}
.btn-outline:hover {
  background: white;
  color: #16a34a;
}

/* Responsive */
.mobile-actions { display: none; }
.desktop-actions { display: flex; }

/* Tablet */
@media (max-width: 1024px) {
  .nav-menu { gap: 1rem; }
  .nav-brand .tagline { font-size: 0.75rem; }
}

/* Mobile */
@media (max-width: 768px) {
  .container { position: relative; }
  .nav-brand .logo { font-size: 1.5rem; }
  .nav-brand .tagline { display: none; }
  .mobile-menu-btn { display: flex; }
  .desktop-actions { display: none; }

  .nav-menu {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: linear-gradient(135deg, #018404 0%, #016a03 100%);
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    gap: 0;
    padding: 100px 20px 20px;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    overflow-y: auto;
  }
  .nav-menu.mobile-open { transform: translateX(0); }
  .nav-item {
    width: 100%;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }
  .nav-link {
    padding: 1rem;
    justify-content: center;
    font-size: 1.1rem;
    width: 100%;
  }
  .nav-link::after { display: none; }

  .dropdown {
    position: static;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0;
    min-width: auto;
    width: 100%;
    opacity: 0;
    max-height: 0;
    transform: none;
    pointer-events: none;
    transition: all 0.3s ease;
    box-shadow: none;
    overflow: hidden;
  }
  .dropdown.mobile-open {
    opacity: 1;
    max-height: none; /* ✅ plus de limite */
    pointer-events: auto;
  }
  .dropdown a {
    color: white;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  }
  .dropdown a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffd700;
  }

  .mobile-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
    width: 100%;
    max-width: 300px;
  }
  .mobile-actions .btn {
    width: 100%;
    padding: 1rem;
    font-size: 1rem;
  }
}

/* Small mobile */
@media (max-width: 480px) {
  .container { padding: 0 15px; }
  .nav-brand img { width: 60px; }
  .nav-brand .logo { font-size: 1.3rem; }
}
</style>
