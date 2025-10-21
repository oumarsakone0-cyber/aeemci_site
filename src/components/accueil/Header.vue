<template>
    <header class="header">
      <!-- Bannière d'annonce -->
      <div class="announcement-banner" v-if="showBanner">
        <div class="banner-content">
          <span>🎓 Nouvelle session de formation islamique - Inscriptions ouvertes jusqu'au 30 janvier 2025</span>
          <button @click="closeBanner" class="close-btn">×</button>
        </div>
      </div>
  
      <!-- Navigation principale -->
      <nav class="main-nav">
        <div class="nav-container">
          <div class="logo">
            <h2>AEEMCI</h2>
            <span>Association des Élèves et Étudiants Musulmans de CI</span>
          </div>
          
          <ul class="nav-menu">
            <li><a href="#accueil">Accueil</a></li>
            <li><a href="#about">À propos</a></li>
            <li><a href="#activities">Activités</a></li>
            <li><a href="#library">Bibliothèque</a></li>
            <li><a href="#formations">Formations</a></li>
            <li><a href="#events">Événements</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
          
          <button class="donate-btn">Adhérer</button>
        </div>
      </nav>
    </header>
  </template>
  
  <script>
  export default {
    name: 'Header',
    data() {
      return {
        showBanner: true
      }
    },
    methods: {
      closeBanner() {
        this.showBanner = false
      }
    }
  }
  </script>
  
  <style scoped>
  .announcement-banner {
    background: linear-gradient(135deg, #16a085, #27ae60);
    color: white;
    padding: 8px 0;
    text-align: center;
    position: relative;
  }
  
  .banner-content {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    font-size: 14px;
  }
  
  .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0 10px;
  }
  
  .main-nav {
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
  }
  
  .nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
  }
  
  .logo h2 {
    color: #16a085;
    font-size: 24px;
    font-weight: bold;
  }
  
  .logo span {
    color: #666;
    font-size: 12px;
    display: block;
  }
  
  .nav-menu {
    display: flex;
    list-style: none;
    gap: 30px;
  }
  
  .nav-menu a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: color 0.3s;
  }
  
  .nav-menu a:hover {
    color: #16a085;
  }
  
  .donate-btn {
    background: #e67e22;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .donate-btn:hover {
    background: #d35400;
  }
  </style>