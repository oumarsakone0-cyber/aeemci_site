<template>
  <header class="header" ref="headerRef">
    <!-- Top Bar -->
    <div class="top-bar">
      <div class="container">
        <div class="top-info">
          <span class="desktop-only">📧 aeemci@yahoo.fr</span>
          <div class="contact-line">
            <span>📞 +225 27 33 75 42 12</span>
          </div>
        </div>
        <div class="social-links desktop-only">
          <a href="https://www.facebook.com/aeemcice" target="_blank">Facebook</a>
          <a href="https://whatsapp.com/channel/0029VaF2TCY3mFY5DTSV1j1B" target="_blank">WhatSapp</a>
          <a href="https://www.youtube.com/@aeemcitv1488" target="_blank">Youtube</a>
        </div>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
      <div class="container">
        <div class="nav-brand">
          <img src="../../assets/aeemci.jpeg" alt="Aeemci" class="logo" />
          <div class="brand-text">
            <h1>AEEMCI</h1>
            <p>Association des Élèves et Étudiants Musulmans de Côte d'Ivoire</p>
          </div>
        </div>

        <!-- Menu -->
        <ul class="nav-menu" :class="{ active: isMenuOpen }" ref="menuRef">
          <li><router-link to="/" @click="closeMenu">Accueil</router-link></li>
          <li class="has-submenu">
            <a href="#" @click.prevent="toggleSubmenu('aeemci')" :class="{ active: activeSubmenu === 'aeemci' }">
              Aeemci <span class="arrow" :class="{ rotated: activeSubmenu === 'aeemci' }">▾</span>
            </a>
            <ul class="submenu" :class="{ active: activeSubmenu === 'aeemci' }">
              <li><router-link to="/historique" @click="closeMenu">Historique</router-link></li>
              <li><router-link to="/bureau" @click="closeMenu">Bureau</router-link></li>
              <li><router-link to="/organes" @click="closeMenu">Organes Centraux</router-link></li>
            </ul>
          </li>
          <li class="has-submenu">
            <a href="#" @click.prevent="toggleSubmenu('activites')" :class="{ active: activeSubmenu === 'activites' }">
              Activités <span class="arrow" :class="{ rotated: activeSubmenu === 'activites' }">▾</span>
            </a>
            <ul class="submenu" :class="{ active: activeSubmenu === 'activites' }">
              <li><router-link to="/formations" @click="closeMenu">Formations</router-link></li>
              <li><router-link to="/opportunites" @click="closeMenu">Opportunité</router-link></li>
              <li><router-link to="/carte_membre" @click="closeMenu">Carte Membre</router-link></li>
            </ul>
          </li>
          <li class="has-submenu">
            <a href="#" @click.prevent="toggleSubmenu('mediatheque')" :class="{ active: activeSubmenu === 'mediatheque' }">
              Médiathèque <span class="arrow" :class="{ rotated: activeSubmenu === 'mediatheque' }">▾</span>
            </a>
            <ul class="submenu" :class="{ active: activeSubmenu === 'mediatheque' }">
              <li><router-link to="/images" @click="closeMenu">Images</router-link></li>
              <li><router-link to="/videos" @click="closeMenu">Vidéos</router-link></li>
            </ul>
          </li>
          <li><router-link to="/contact" @click="closeMenu">Contact</router-link></li>
        </ul>

        <!-- Burger -->
        <div class="nav-toggle" @click="toggleMenu">
          <span :class="{ open: isMenuOpen }"></span>
          <span :class="{ open: isMenuOpen }"></span>
          <span :class="{ open: isMenuOpen }"></span>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const isMenuOpen = ref(false)
const activeSubmenu = ref(null)
const menuRef = ref(null)
const headerRef = ref(null)

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value
  document.body.style.overflow = isMenuOpen.value ? 'hidden' : 'auto'
  
  // Fermer tous les sous-menus quand on ferme le menu principal
  if (!isMenuOpen.value) {
    activeSubmenu.value = null
  }
}

function toggleSubmenu(submenuName) {
  console.log('Toggle submenu clicked:', submenuName)
  console.log('Current activeSubmenu:', activeSubmenu.value)
  
  if (activeSubmenu.value === submenuName) {
    activeSubmenu.value = null
    console.log('Closing submenu')
  } else {
    activeSubmenu.value = submenuName
    console.log('Opening submenu:', submenuName)
  }
  
  console.log('New activeSubmenu value:', activeSubmenu.value)
}

function closeMenu() {
  isMenuOpen.value = false
  activeSubmenu.value = null
  document.body.style.overflow = 'auto'
}

function handleClickOutside(event) {
  if (
    isMenuOpen.value &&
    menuRef.value &&
    !menuRef.value.contains(event.target) &&
    !headerRef.value.contains(event.target)
  ) {
    closeMenu()
  }
}

function handleResize() {
  // Fermer le menu mobile si on passe en desktop
  if (window.innerWidth > 768 && isMenuOpen.value) {
    closeMenu()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  window.removeEventListener('resize', handleResize)
  document.body.style.overflow = 'auto'
})
</script>

<style scoped>
.arrow {
  font-size: 0.9rem;
  margin-left: 6px;
  color: #666;
  transition: transform 0.3s ease;
}

.arrow.rotated {
  transform: rotate(180deg);
}

.container {
  max-width: 1200px;
  margin: auto;
  padding: 0 1rem;
}

.header {
  background: #fff;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.top-bar {
  background-color: var(--primary, #0e5a5a);
  color: white;
  font-size: 0.9rem;
  padding: 0.5rem 0;
}

.top-bar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.top-info {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.contact-line {
  display: flex;
  gap: 1rem;
}

.social-links {
  display: flex;
  gap: 1rem;
}

.social-links a {
  color: white;
  text-decoration: none;
  transition: opacity 0.3s ease;
}

.social-links a:hover {
  opacity: 0.8;
}

.navbar {
  padding: 1rem 0;
}

.navbar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-brand {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo {
  width: 80px;
  height: 60px;
  object-fit: contain;
}

.brand-text h1 {
  color: var(--primary, #0e5a5a);
  margin: 0;
  font-size: 1.6rem;
  font-weight: bold;
}

.brand-text p {
  color: #555;
  font-size: 0.85rem;
  margin: 0;
}

.nav-menu {
  display: flex;
  gap: 2rem;
  list-style: none;
  align-items: center;
  margin: 0;
  padding: 0;
}

.nav-menu a {
  color: #222;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 0.8rem;
  transition: all 0.3s ease;
  border-radius: 4px;
  display: block;
}

.nav-menu a:hover {
  color: var(--primary, #0e5a5a);
  background-color: rgba(14, 90, 90, 0.05);
}

.has-submenu {
  position: relative;
}

.submenu {
  list-style: none;
  background: white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 0.5rem 0;
  border-radius: 8px;
  position: absolute;
  top: 100%;
  left: 0;
  display: none;
  z-index: 1000;
  min-width: 200px;
  margin: 0;
}

.has-submenu:hover .submenu {
  display: block;
}

.submenu li {
  margin: 0;
}

.submenu a {
  font-size: 0.95rem;
  padding: 0.6rem 1rem;
  color: #333;
  transition: all 0.2s ease;
  border-radius: 0;
}

.submenu a:hover {
  background-color: rgba(14, 90, 90, 0.1);
  color: var(--primary, #0e5a5a);
}

.nav-toggle {
  display: none;
  flex-direction: column;
  gap: 4px;
  cursor: pointer;
  padding: 8px;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.nav-toggle:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

.nav-toggle span {
  width: 25px;
  height: 3px;
  background: #333;
  transition: all 0.3s ease;
  border-radius: 2px;
}

.nav-toggle span.open:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.nav-toggle span.open:nth-child(2) {
  opacity: 0;
}

.nav-toggle span.open:nth-child(3) {
  transform: rotate(-45deg) translate(6px, -6px);
}

/* Responsive */
@media (max-width: 768px) {
  .desktop-only {
    display: none !important;
  }

  .top-info {
    flex-direction: column;
    gap: 0.3rem;
    text-align: center;
    width: 100%;
  }

  .contact-line {
    justify-content: center;
    width: 100%;
    font-size: 0.9rem;
  }

  .brand-text h1 {
    font-size: 1.3rem;
  }

  .brand-text p {
    font-size: 0.75rem;
  }

  .logo {
    width: 60px;
    height: 45px;
  }

  .nav-toggle {
    display: flex;
    z-index: 1001;
  }

  .nav-menu {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    flex-direction: column;
    align-items: flex-start;
    background: white;
    padding: 120px 2rem 2rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateX(-100%);
    transition: all 0.3s ease;
    z-index: 1000;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch; /* Pour un scroll fluide sur iOS */
  }

  .nav-menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
  }

  .nav-menu li {
    width: 100%;
    margin-bottom: 0.5rem;
  }

  .nav-menu > li > a {
    font-size: 1.1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  /* Sous-menu en version mobile - Version simplifiée */
  .has-submenu .submenu {
    position: static;
    background: #f8f9fa;
    box-shadow: none;
    padding: 0;
    border-radius: 0;
    margin: 0;
    width: 100%;
    border-left: 3px solid var(--primary, #0e5a5a);
    margin-top: 0.5rem;
    height: 0;
    overflow: hidden;
    transition: height 0.3s ease;
  }

  .has-submenu .submenu.active {
    height: auto;
    min-height: 170px;
    padding: 0.5rem 0;
  }

  /* S'assurer que le contenu du menu peut défiler */
  .nav-menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* Correction pour les appareils avec petite hauteur */
  @media (max-height: 600px) {
    .nav-menu {
      padding: 100px 2rem 1rem;
    }
    
    .nav-menu > li > a {
      padding: 0.8rem 0;
    }
    
    .submenu a {
      padding: 0.6rem 1.5rem;
    }
  }

  .submenu a {
    padding: 0.8rem 1.5rem;
    font-size: 1rem;
    border-bottom: 1px solid #e9ecef;
  }

  .submenu a:hover {
    background-color: rgba(14, 90, 90, 0.1);
  }

  /* Rotation de la flèche en mobile */
  .has-submenu .submenu.active + a .arrow,
  .has-submenu:has(.submenu.active) > a .arrow {
    transform: rotate(180deg);
  }

  /* Désactiver complètement le hover sur mobile */
  .has-submenu:hover .submenu {
    height: 0 !important;
    padding: 0 !important;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 0 0.5rem;
  }

  .nav-menu {
    padding: 100px 1rem 2rem;
  }

  .brand-text h1 {
    font-size: 1.1rem;
  }

  .brand-text p {
    font-size: 0.7rem;
  }

  .logo {
    width: 50px;
    height: 40px;
  }
}
</style>
