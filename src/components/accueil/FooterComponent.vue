<template>
    <!-- Extracted footer section -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <h3>AEEMCI</h3>
            <p>Association des Élèves et Étudiants Musulmans de Côte d'Ivoire</p>
            <div class="social-links">
              <a href="#" class="social-link">📘 Facebook</a>
              <a href="#" class="social-link">📷 Instagram</a>
              <a href="#" class="social-link">🐦 Twitter</a>
              <a href="#" class="social-link">📺 YouTube</a>
            </div>
          </div>
          
          <div class="footer-section">
            <h4>Liens Rapides</h4>
            <ul>
              <li><a href="#about">À propos</a></li>
              <li><a href="#activities">Activités</a></li>
              <li><a href="#library">Bibliothèque</a></li>
              <li><a href="#formations">Formations</a></li>
              <li><a href="#events">Événements</a></li>
            </ul>
          </div>
          
          <div class="footer-section">
            <h4>Services</h4>
            <ul>
              <li><a href="#">Adhésion</a></li>
              <li><a href="#">Formations en ligne</a></li>
              <li><a href="#">Ressources islamiques</a></li>
              <li><a href="#">Soutien académique</a></li>
              <li><a href="#">Conseil spirituel</a></li>
            </ul>
          </div>
          
          <div class="footer-section">
            <h4>Contact</h4>
            <div class="contact-info">
              <p>📍 Cocody, Abidjan, Côte d'Ivoire</p>
              <p>📞 +225 07 27 33 75 42 12</p>
              <p>✉️ info@aeemci-ce.ci</p>
              <p>🌐 www.aeemci.org</p>
            </div>
          </div>
        </div>
        
        <div class="footer-bottom">
          <div class="footer-bottom-content">
            <p>&copy; 2025 AEEMCI. Tous droits réservés.</p>
            <div class="footer-links">
              <a href="#">Politique de confidentialité</a>
              <a href="#">Conditions d'utilisation</a>
              <a href="#">Mentions légales</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'FooterComponent'
  }
  </script>
  
  <style scoped>
  .footer {
    background: #2c3e50;
    color: white;
    padding: 60px 20px 0;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
  }
  
  .footer-section h3 {
    color: #16a085;
    font-size: 24px;
    margin-bottom: 20px;
  }
  
  .footer-section h4 {
    color: #16a085;
    font-size: 18px;
    margin-bottom: 20px;
  }
  
  .footer-section p {
    color: #bdc3c7;
    line-height: 1.6;
    margin-bottom: 20px;
  }
  
  .social-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .social-link {
    color: #bdc3c7;
    text-decoration: none;
    transition: color 0.3s;
  }
  
  .social-link:hover {
    color: #16a085;
  }
  
  .footer-section ul {
    list-style: none;
    padding: 0;
  }
  
  .footer-section li {
    margin-bottom: 10px;
  }
  
  .footer-section a {
    color: #bdc3c7;
    text-decoration: none;
    transition: color 0.3s;
  }
  
  .footer-section a:hover {
    color: #16a085;
  }
  
  .contact-info p {
    margin-bottom: 10px;
    color: #bdc3c7;
  }
  
  .footer-bottom {
    border-top: 1px solid #34495e;
    padding: 20px 0;
  }
  
  .footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .footer-bottom p {
    color: #bdc3c7;
    margin: 0;
  }
  
  .footer-links {
    display: flex;
    gap: 20px;
  }
  
  .footer-links a {
    color: #bdc3c7;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
  }
  
  .footer-links a:hover {
    color: #16a085;
  }
  
  @media (max-width: 768px) {
    .footer-content {
      grid-template-columns: 1fr;
      gap: 30px;
    }
    
    .footer-bottom-content {
      flex-direction: column;
      text-align: center;
    }
    
    .footer-links {
      flex-direction: column;
      gap: 10px;
    }
  }
  </style>
  