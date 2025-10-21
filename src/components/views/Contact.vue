<template>
    <div class="app">
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
            <h1>Contactez <span class="highlight">AEEMCI</span></h1>
            <p class="subtitle">Nous sommes à votre écoute</p>
            <div class="breadcrumbs">
              <a href="#">Accueil</a> <span class="separator">/</span> <span class="current">Contact</span>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Contenu principal -->
      <main class="main-content">
        <div class="container">
          <!-- Introduction -->
          <section class="intro-section">
            <div class="intro-text">
              <p>Vous avez des questions, des suggestions ou souhaitez simplement nous contacter ? N'hésitez pas à utiliser le formulaire ci-dessous ou à nous joindre directement via nos coordonnées. Notre équipe se fera un plaisir de vous répondre dans les plus brefs délais.</p>
            </div>
          </section>
          
          <!-- Section Contact -->
          <section class="contact-section">
            <div class="contact-container">
              <!-- Informations de contact -->
              <div class="contact-info">
                <div class="info-header">
                  <h2><span class="highlight">N</span>os coordonnées</h2>
                  <div class="separator-line"></div>
                </div>
                
                <div class="info-content">
                  <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                      <h3>Adresse</h3>
                      <p>Siège: Cocody Riviera 2, Mosquée An Nour<br>Abidjan, Côte d'Ivoire</p>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-text">
                      <h3>Téléphone</h3>
                      <p>+225 27 22 43 47 58</p>
                      <p>+225 07 88 70 41 36 / 05 44 54 34 89 / 01 03 32 20 57</p>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">✉️</div>
                    <div class="info-text">
                      <h3>Email</h3>
                      <p>aeemci@yahoo.fr</p>
                      <p>info@aeemci-ce.ci</p>
                    </div>
                  </div>
                  
                  <div class="info-item">
                    <div class="info-icon">🕒</div>
                    <div class="info-text">
                      <h3>Horaires d'ouverture</h3>
                      <p>Lundi - Vendredi: 08h00 - 20h00</p>
                      <p>Samedi: 09h00 - 16h00</p>
                      <p>Dimanche: Fermé</p>
                    </div>
                  </div>
                </div>
                
                <div class="social-media">
                  <h3>Suivez-nous</h3>
                  <div class="social-icons">
                    <a href="https://www.facebook.com/aeemcice" target="_blank" class="social-icon">
                      <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDY0MDAiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS1mYWNlYm9vayI+PHBhdGggZD0iTTE4IDJoLTNhNSA1IDAgMCAwLTUgNXYzSDd2NGgzdjhoNHYtOGgzYTEgMSAwIDAgMCAxLTFWMTBoLTR2LTNoNFYyeiIvPjwvc3ZnPg==" alt="Facebook" />
                    </a>
                    <a href="https://www.youtube.com/@aeemcitv1488" target="_blank" class="social-icon">
                      <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDY0MDAiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS15b3V0dWJlIj48cGF0aCBkPSJNMi41IDEyYTkuNSA5LjUgMCAxIDAgMTkgMCA5LjUgOS41IDAgMSAwLTE5IDB6Ii8+PHBhdGggZD0ibTEwIDE1IDUuMTktM0wxMCA5djZ6Ii8+PC9zdmc+" alt="YouTube" />
                    </a>
                    <a href="https://whatsapp.com/channel/0029VaF2TCY3mFY5DTSV1j1B" target="_blank" class="social-icon">
                      <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDY0MDAiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBjbGFzcz0ibHVjaWRlIGx1Y2lkZS13aGF0c2FwcCI+PHBhdGggZD0iTTE3LjQgMTQuNGMtLjYgMC0xLjItLjEtMS44LS4yLS41LS4xLTEgLjEtMS4zLjVsLTEuMiAxLjJjLTIuNC0xLjItNC41LTMuMy01LjgtNS44bDEuMi0xLjJjLjQtLjQuNi0uOS41LTEuMy0uMS0uNi0uMi0xLjItLjItMS44QzguOCA1LjMgOC4xIDQuNSA3IDQuNUg0LjVDMy40IDQuNSAyLjUgNS40IDIuNSA2LjVjMCA4LjggNy4yIDE2IDE2IDE2IDEuMSAwIDItLjkgMi0yVjE3YzAtMS4xLS44LTEuOC0xLjgtMS44aC0xLjN6Ii8+PC9zdmc+" alt="WhatsApp" />
                    </a>
                  </div>
                </div>
              </div>
              
              <!-- Formulaire de contact -->
              <div class="contact-form">
                <div class="form-header">
                  <h2><span class="highlight">E</span>nvoyez-nous un message</h2>
                  <div class="separator-line"></div>
                </div>
                
                <form @submit.prevent="submitForm">
                  <div class="form-group">
                    <label for="name">Nom complet <span class="required">*</span></label>
                    <input 
                      type="text" 
                      id="name" 
                      v-model="formData.name" 
                      required 
                      class="form-control"
                      placeholder="Votre nom et prénom"
                    />
                  </div>
                  
                  <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input 
                      type="email" 
                      id="email" 
                      v-model="formData.email" 
                      required 
                      class="form-control"
                      placeholder="Votre adresse email"
                    />
                  </div>
                  
                  <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input 
                      type="tel" 
                      id="phone" 
                      v-model="formData.phone" 
                      class="form-control"
                      placeholder="Votre numéro de téléphone"
                    />
                  </div>

                  <div class="form-group">
                    <label for="phone">Lieu de résidence</label>
                    <input 
                      type="text" 
                      id="phone" 
                      v-model="formData.phone" 
                      class="form-control"
                      placeholder="Votre lieu de résidence"
                    />
                  </div>
                  
                  <div class="form-group">
                    <label for="subject">Sujet <span class="required">*</span></label>
                    <select 
                      id="subject" 
                      v-model="formData.subject" 
                      required 
                      class="form-control"
                    >
                      <option value="" disabled selected>Sélectionnez un sujet</option>
                      <option value="information">Demande d'information</option>
                      <option value="adhesion">Adhésion</option>
                      <option value="partenariat">Proposition de partenariat</option>
                      <option value="suggestion">Suggestion</option>
                      <option value="autre">Autre</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea 
                      id="message" 
                      v-model="formData.message" 
                      required 
                      class="form-control"
                      rows="5"
                      placeholder="Votre message"
                    ></textarea>
                  </div>
                  
                  <div class="form-group form-checkbox" >
                    <input 
                    style="width: 20px"
                      type="checkbox" 
                      id="consent" 
                      v-model="formData.consent" 
                      required
                    />
                    <label for="consent">J'accepte que mes données soient traitées conformément à la politique de confidentialité <span class="required">*</span></label>
                  </div>
                  
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                      <span v-if="isSubmitting">Envoi en cours...</span>
                      <span v-else>Envoyer le message</span>
                    </button>
                  </div>
                </form>
                
                <div v-if="formSuccess" class="form-success">
                  <div class="success-icon">✓</div>
                  <h3>Message envoyé avec succès!</h3>
                  <p>Nous vous répondrons dans les plus brefs délais.</p>
                </div>
                
                <div v-if="formError" class="form-error">
                  <div class="error-icon">!</div>
                  <h3>Une erreur est survenue</h3>
                  <p>{{ formError }}</p>
                  <button @click="resetForm" class="btn btn-outline">Réessayer</button>
                </div>
              </div>
            </div>
          </section>
          
          <!-- Section Carte -->
          <section class="map-section">
            <div class="section-header">
              <h2><span class="highlight">N</span>ous localiser</h2>
              <div class="separator-line"></div>
            </div>
            
            <div class="map-container">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.4479911783847!2d-3.9726892857675934!3d5.348889796120096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1eb5df24b1c51%3A0x7fe756eec1e2451d!2sMosqu%C3%A9e%20An-Nour%20de%20Riviera%202!5e0!3m2!1sfr!2sci!4v1650123456789!5m2!1sfr!2sci" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>
          </section>
          
          <!-- Section FAQ -->
          <section class="faq-section">
            <div class="section-header">
              <h2><span class="highlight">Q</span>uestions fréquentes</h2>
              <div class="separator-line"></div>
            </div>
            
            <div class="faq-container">
              <div 
                v-for="(faq, index) in faqs" 
                :key="index" 
                class="faq-item"
                :class="{ 'active': activeFaq === index }"
              >
                <div class="faq-question" @click="toggleFaq(index)">
                  <h3>{{ faq.question }}</h3>
                  <div class="faq-icon">{{ activeFaq === index ? '−' : '+' }}</div>
                </div>
                <div class="faq-answer" v-show="activeFaq === index">
                  <p>{{ faq.answer }}</p>
                </div>
              </div>
            </div>
          </section>
          
          
          <!-- Section Newsletter -->
          <section class="newsletter-section">
            <div class="newsletter-container">
              <div class="newsletter-content">
                <h2>Restez informé</h2>
                <p>Abonnez-vous à notre newsletter pour recevoir les dernières actualités et informations sur nos activités.</p>
                
                <form @submit.prevent="subscribeNewsletter" class="newsletter-form">
                  <input 
                    type="email" 
                    v-model="newsletterEmail" 
                    placeholder="Votre adresse email" 
                    required 
                    class="newsletter-input"
                  />
                  <button type="submit" class="btn btn-secondary">S'abonner</button>
                </form>
                
                <p class="newsletter-note">En vous abonnant, vous acceptez de recevoir nos emails et confirmez avoir lu notre politique de confidentialité.</p>
              </div>
              <div class="newsletter-image">
                <img src="https://scontent.fabj1-1.fna.fbcdn.net/v/t39.30808-6/471819768_2603533463370933_7363560428672793403_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFwH23iEBoD9SWErBAOpV3YI_MD2yDFMhUj8wPbIMUyFQdUk0HpBHLIf4nbB0dWJqUATJwGjPA2sou9z8TTqjic&_nc_ohc=euqbHiEWYlkQ7kNvwFu-NQ-&_nc_oc=Admvo_9AqFJWPrzvLFQXgAYhRlHLaZHNdP4LbQOTQ0E9wUcFAXRbtQF08mbrnox0cEI&_nc_zt=23&_nc_ht=scontent.fabj1-1.fna&_nc_gid=BBDAAb6-M85vR9G6Tykfgg&oh=00_AfG0K7xc-Ah20puIu6NXaTKErhca4pgBl6NstKNUo_WKKg&oe=680864CB" alt="Newsletter" class="img-responsive" />
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, onMounted } from 'vue';
  
  // Formulaire de contact
  const formData = reactive({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
    consent: false
  });
  
  const isSubmitting = ref(false);
  const formSuccess = ref(false);
  const formError = ref('');
  
  const submitForm = async () => {
    isSubmitting.value = true;
    formSuccess.value = false;
    formError.value = '';
    
    try {
      // Simulation d'envoi de formulaire
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      // Réinitialiser le formulaire après succès
      formData.name = '';
      formData.email = '';
      formData.phone = '';
      formData.subject = '';
      formData.message = '';
      formData.consent = false;
      
      formSuccess.value = true;
    } catch (error) {
      formError.value = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.";
    } finally {
      isSubmitting.value = false;
    }
  };
  
  const animateCounters = () => {
    const counters = document.querySelectorAll('.animate-count .stat-number');
    
    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-count'));
      const duration = 2000; // ms
      const step = target / (duration / 16); // 60fps
      let current = 0;
      
      const updateCounter = () => {
        current += step;
        if (current < target) {
          counter.textContent = Math.ceil(current);
          requestAnimationFrame(updateCounter);
        } else {
          counter.textContent = target;
          if (target >= 1000) {
            counter.textContent = target.toLocaleString('fr-FR');
          } else {
            counter.textContent = target;
          }
        }
      };
      
      updateCounter();
    });
  };

  const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-visible');
          
          // Si c'est la section des statistiques, démarrer l'animation des compteurs
          if (entry.target.classList.contains('stats-grid')) {
            animateCounters();
          }
        }
      });
    }, { threshold: 0.1 });

  const resetForm = () => {
    formError.value = '';
  };
  
  // FAQ
  const faqs = [
    {
      question: "Comment puis-je devenir membre de l'AEEMCI ?",
      answer: "Pour devenir membre de l'AEEMCI, vous devez être élève ou étudiant musulman en Côte d'Ivoire. Vous pouvez vous inscrire en remplissant le formulaire d'adhésion disponible sur notre site ou en vous rendant dans l'un de nos bureaux. Une cotisation annuelle est requise pour valider votre adhésion."
    },
    {
      question: "Quelles sont les activités organisées par l'AEEMCI ?",
      answer: "L'AEEMCI organise diverses activités tout au long de l'année, notamment des séminaires de formation (SENAFOI), des conférences, des actions sociales, des compétitions de récitation du Coran, des formations en leadership islamique, et bien d'autres événements éducatifs et spirituels."
    },
    {
      question: "Comment puis-je faire un don à l'AEEMCI ?",
      answer: "Vous pouvez faire un don à l'AEEMCI par virement bancaire, par mobile money, ou en vous rendant directement à notre siège. Pour plus d'informations sur les modalités de don, veuillez nous contacter par téléphone ou par email."
    },
    {
      question: "L'AEEMCI propose-t-elle des bourses d'études ?",
      answer: "Oui, l'AEEMCI propose des bourses d'études pour les élèves et étudiants méritants et nécessiteux. Les candidatures sont généralement ouvertes en début d'année académique. Consultez notre site web ou contactez-nous pour connaître les critères d'éligibilité et les dates de candidature."
    },
    {
      question: "Comment puis-je proposer un partenariat avec l'AEEMCI ?",
      answer: "Pour proposer un partenariat, veuillez nous contacter par email à partenariat@aeemci.org ou utiliser notre formulaire de contact en sélectionnant 'Proposition de partenariat' comme sujet. Notre équipe étudiera votre proposition et vous recontactera dans les meilleurs délais."
    }
  ];
  
  const activeFaq = ref(null);
  
  const toggleFaq = (index) => {
    if (activeFaq.value === index) {
      activeFaq.value = null;
    } else {
      activeFaq.value = index;
    }
  };
  
  // Bureaux régionaux
  const regionalOffices = [
    {
      region: "Abidjan Nord",
      address: "Abobo, Quartier Sogephia, près de la grande mosquée",
      phone: "+225 07 07 07 07 08",
      email: "abidjan-nord@aeemci.org"
    },
    {
      region: "Abidjan Sud",
      address: "Treichville, Avenue 16, Rue 21",
      phone: "+225 07 07 07 07 09",
      email: "abidjan-sud@aeemci.org"
    },
    {
      region: "Bouaké",
      address: "Quartier Air France, près de l'Université Alassane Ouattara",
      phone: "+225 07 07 07 07 10",
      email: "bouake@aeemci.org"
    },
    {
      region: "Daloa",
      address: "Quartier Tazibouo, près de l'INFPA",
      phone: "+225 07 07 07 07 11",
      email: "daloa@aeemci.org"
    },
    {
      region: "Korhogo",
      address: "Quartier Soba, près de l'Université Péléforo Gon Coulibaly",
      phone: "+225 07 07 07 07 12",
      email: "korhogo@aeemci.org"
    },
    {
      region: "San Pedro",
      address: "Quartier Séwéké, près du Lycée Moderne",
      phone: "+225 07 07 07 07 13",
      email: "sanpedro@aeemci.org"
    }
  ];
  
  // Newsletter
  const newsletterEmail = ref('');
  
  const subscribeNewsletter = async () => {
    // Simulation d'abonnement à la newsletter
    await new Promise(resolve => setTimeout(resolve, 1000));
    alert(`Merci de vous être abonné avec l'email: ${newsletterEmail.value}`);
    newsletterEmail.value = '';
  };
  onMounted(() => {
    document.querySelectorAll('.period-card, .intro-section, .testimonials-section, .gallery-section, .stats-grid').forEach(el => {
      observer.observe(el);
    });
  })
  </script>
    <script>
    // Pour respecter la règle multi-word
    export default {
      name: 'ConTact'
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
  
  .img-responsive {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 4px;
    transition: transform 0.5s ease;
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
  
  .btn-secondary {
    background-color: var(--secondary);
    color: var(--text-primary);
    box-shadow: 0 2px 4px rgba(248, 180, 0, 0.1);
  }
  
  .btn-secondary:hover {
    background-color: var(--secondary-dark);
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(248, 180, 0, 0.15);
  }
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
  }
  
  .btn-outline:hover {
    background-color: var(--primary);
    color: white;
    transform: translateY(-2px);
  }
  
  .btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
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
    margin-bottom: 3rem;
  }
  
  .intro-text {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 1.8;
  }
  
  /* Contact Section */
  .contact-section {
    margin-bottom: 4rem;
  }
  
  .contact-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
  }
  
  .contact-info {
    flex: 1;
    min-width: 300px;
    background-color: #f5f5f500;
    padding: 2rem;
    border-radius: 8px;
  }
  
  .contact-form {
    flex: 1.5;
    min-width: 300px;
    background-color: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .info-header, .form-header {
    margin-bottom: 1.5rem;
  }
  
  .info-header h2, .form-header h2, .section-header h2 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
  }
  
  .separator-line {
    width: 50px;
    height: 3px;
    background-color: var(--primary);
    margin-top: 0.5rem;
  }
  
  .info-content {
    margin-bottom: 2rem;
  }
  
  .info-item {
    display: flex;
    margin-bottom: 1.5rem;
  }
  
  .info-icon {
    font-size: 1.5rem;
    margin-right: 1rem;
    color: var(--primary);
  }
  
  .info-text h3 {
    font-size: 1.1rem;
    margin-bottom: 0.3rem;
    color: var(--primary);
  }
  
  .info-text p {
    color: var(--text-secondary);
    line-height: 1.5;
  }
  
  .social-media h3 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    color: var(--primary);
  }
  
  .social-icons {
    display: flex;
    gap: 1rem;
  }
  
  .social-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: white;
    border-radius: 50%;
    transition: all 0.3s ease;
  }
  
  .social-icon:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .social-icon img {
    width: 20px;
    height: 20px;
  }
  
  /* Form Styles */
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
  }
  
  .required {
    color: var(--danger);
  }
  
  .form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }
  
  .form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 100, 0, 0.1);
  }
  
  .form-checkbox {
    display: flex;
    align-items: flex-start;
  }
  
  .form-checkbox input {
    margin-top: 0.3rem;
    margin-right: 0.5rem;
  }
  
  .form-actions {
    margin-top: 2rem;
  }
  
  .form-success, .form-error {
    margin-top: 2rem;
    padding: 1.5rem;
    border-radius: 4px;
    text-align: center;
  }
  
  .form-success {
    background-color: rgba(40, 167, 69, 0.1);
    border: 1px solid var(--success);
  }
  
  .form-error {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid var(--danger);
  }
  
  .success-icon, .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }
  
  .success-icon {
    background-color: var(--success);
    color: white;
  }
  
  .error-icon {
    background-color: var(--danger);
    color: white;
  }
  
  /* Map Section */
  .map-section {
    margin-bottom: 4rem;
  }
  
  .section-header {
    margin-bottom: 2rem;
    text-align: center;
  }
  
  .map-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  /* FAQ Section */
  .faq-section {
    margin-bottom: 4rem;
  }
  
  .faq-container {
    max-width: 800px;
    margin: 0 auto;
  }
  
  .faq-item {
    margin-bottom: 1rem;
    border: 1px solid var(--border);
    border-radius: 4px;
    overflow: hidden;
  }
  
  .faq-item.active {
    border-color: var(--primary);
  }
  
  .faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: white;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .faq-item.active .faq-question {
    background-color: var(--primary-lighter);
  }
  
  .faq-question h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
  }
  
  .faq-icon {
    font-size: 1.5rem;
    color: var(--primary);
  }
  
  .faq-answer {
    padding: 0 1rem 1rem 1rem;
    color: var(--text-secondary);
    line-height: 1.6;
  }
  
  /* Regional Offices */
  .regional-offices {
    margin-bottom: 4rem;
  }
  
  .offices-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .office-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }
  
  .office-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .office-icon {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 1rem;
  }
  
  .office-card h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: var(--primary);
  }
  
  .office-address {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
  }
  
  .office-contact {
    color: var(--text-secondary);
    font-size: 0.9rem;
  }
  
  .contact-label {
    font-weight: 600;
    color: var(--text-primary);
  }
  
  /* Newsletter Section */
  .newsletter-section {
    margin-bottom: 2rem;
  }
  
  .newsletter-container {
    display: flex;
    background-color: var(--primary-lighter);
    border-radius: 8px;
    overflow: hidden;
  }
  
  .newsletter-content {
    flex: 1;
    padding: 3rem;
  }
  
  .newsletter-content h2 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: var(--primary);
  }
  
  .newsletter-content p {
    margin-bottom: 1.5rem;
    color: var(--text-secondary);
  }
  
  .newsletter-form {
    display: flex;
    margin-bottom: 1rem;
  }
  
  .newsletter-input {
    flex: 1;
    padding: 0.8rem;
    border: 1px solid var(--border);
    border-radius: 4px 0 0 4px;
    font-size: 0.9rem;
  }
  
  .newsletter-form .btn {
    border-radius: 0 4px 4px 0;
  }
  
  .newsletter-note {
    font-size: 0.8rem;
    color: var(--text-light);
  }
  
  .newsletter-image {
    flex: 1;
    display: none;
  }
  
  .newsletter-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  /* Responsive Styles */
  @media (min-width: 768px) {
    .newsletter-image {
      display: block;
    }
  }
  
  @media (max-width: 992px) {
    .offices-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 768px) {
    .page-title h1 {
      font-size: 2rem;
    }
    
    .contact-container {
      flex-direction: column;
    }
    
    .offices-grid {
      grid-template-columns: 1fr;
    }
    
    .newsletter-container {
      flex-direction: column;
    }
    
    .newsletter-content {
      padding: 2rem;
    }
  }
  
  @media (max-width: 576px) {
    .newsletter-form {
      flex-direction: column;
    }
    
    .newsletter-input {
      border-radius: 4px;
      margin-bottom: 0.5rem;
    }
    
    .newsletter-form .btn {
      border-radius: 4px;
      width: 100%;
    }
  }
  </style>