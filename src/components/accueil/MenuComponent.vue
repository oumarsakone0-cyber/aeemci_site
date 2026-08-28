<template>
    <!--  Extracted menu/header section -->
    <div>
      <!-- Announcement Banner -->
      <div class="announcement-banner">
        <div class="banner-content">
          <div class="banner-text">
            <span class="banner-icon">📢</span>
            <strong>Annonce Spéciale:</strong> Rejoignez-nous pour le 35ème Séminaire Annuel d'Éducation Islamique - 10-18 Août 2024
          </div>
          <button class="banner-close" @click="closeBanner">×</button>
        </div>
      </div>
  
      <!-- Header -->
      <header class="header">
        <div class="container">
          <div class="nav-brand">
            <div class="logo">
              <span class="logo-icon">🎓</span>
              <span class="logo-text">AEEMCI</span>
            </div>
          </div>
          
          <nav class="nav-menu">
            <a href="#" class="nav-link active">Accueil</a>
            <a href="#" class="nav-link">À propos</a>
            <a href="#" class="nav-link">Activités</a>
            <a href="#" class="nav-link">Formations</a>
            <a href="#" class="nav-link">Bibliothèque</a>
            <a href="#" class="nav-link">Événements</a>
            <a href="#" class="nav-link">Contact</a>
          </nav>
          
          <button class="join-btn">Adhérer</button>
        </div>
      </header>
    </div>
  </template>
  
  <script>
  export default {
    name: 'MenuComponent',
    methods: {
      closeBanner() {
        document.querySelector('.announcement-banner').style.display = 'none';
      }
    }
  }
  </script>
  
  <style scoped>
  .announcement-banner {
    background: linear-gradient(135deg, var(--green), var(--green));
    color: white;
    padding: 12px 0;
    text-align: center;
    position: relative;
  }
  
  .banner-content {
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }
  
  .banner-text {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .banner-icon {
    font-size: 18px;
  }
  
  .banner-close {
    position: absolute;
    right: 20px;
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .banner-close:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
  }
  
  .header {
    background: white;
    box-shadow: var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 80px;
  }
  
  .nav-brand .logo {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .logo-icon {
    font-size: 32px;
    color: var(--green);
  }
  
  .logo-text {
    font-size: 28px;
    font-weight: bold;
    color: var(--ink);
  }
  
  .nav-menu {
    display: flex;
    gap: 30px;
  }
  
  .nav-link {
    text-decoration: none;
    color: var(--ink);
    font-weight: 500;
    padding: 10px 0;
    position: relative;
    transition: color 0.3s;
  }
  
  .nav-link:hover,
  .nav-link.active {
    color: var(--green);
  }
  
  .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--green);
  }
  
  .join-btn {
    background: var(--green);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 0;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .join-btn:hover {
    background: var(--green);
  }
  
  @media (max-width: 768px) {
    .nav-menu {
      display: none;
    }
    
    .container {
      padding: 0 15px;
    }
  }
  </style>
  