<template>
  <div class="contact-page">
    <!-- Hero Banner -->
    <section class="hero-banner">
      <div class="hero-background">
        <div class="pattern pattern-1"></div>
        <div class="pattern pattern-2"></div>
        <div class="pattern pattern-3"></div>
      </div>
      
      <!-- Islamic Patterns -->
      <div class="islamic-patterns">
        <div v-for="n in 8" :key="`star-${n}`" class="pattern-star" :style="starStyle(n)"></div>
        <div v-for="n in 5" :key="`crescent-${n}`" class="pattern-crescent" :style="crescentStyle(n)"></div>
        <div v-for="n in 10" :key="`geo-${n}`" class="pattern-geometric" :style="geometricStyle(n)"></div>
      </div>
      
      <div class="hero-container">
        <div class="hero-content">
          <h1>Contactez <span class="gradient-text">AEEMCI</span></h1>
          <p class="hero-subtitle">Nous sommes à votre écoute</p>
          <div class="breadcrumbs">
            <a href="#">Accueil</a>
            <span class="separator">→</span>
            <span class="current">Contact</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
      <div class="content-container">
        <!-- Intro Section -->
        <section class="intro-section">
          <p class="intro-text">
            Vous avez des questions, des suggestions ou souhaitez simplement nous contacter ? 
            N'hésitez pas à utiliser le formulaire ci-dessous ou à nous joindre directement via nos coordonnées. 
            Notre équipe se fera un plaisir de vous répondre dans les plus brefs délais.
          </p>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
          <div class="contact-grid">
            <!-- Info Column -->
            <div class="info-column">
              <div class="info-card">
                <h2>
                  <span class="first-letter">N</span>os coordonnées
                </h2>
                <div class="title-line"></div>

                <div class="info-items">
                  <div class="info-item">
                    <div class="item-icon">📍</div>
                    <div class="item-content">
                      <h3>Adresse</h3>
                      <p>Siège: Cocody Riviera 2, Mosquée An Nour</p>
                      <p>Abidjan, Côte d'Ivoire</p>
                    </div>
                  </div>

                  <div class="info-item">
                    <div class="item-icon">📞</div>
                    <div class="item-content">
                      <h3>Téléphone</h3>
                      <p>+225 27 22 43 47 58</p>
                      <p>+225 07 88 70 41 36</p>
                    </div>
                  </div>

                  <div class="info-item">
                    <div class="item-icon">✉️</div>
                    <div class="item-content">
                      <h3>Email</h3>
                      <p>aeemci@yahoo.fr</p>
                      <p>info@aeemci-ce.ci</p>
                    </div>
                  </div>

                  <div class="info-item">
                    <div class="item-icon">🕒</div>
                    <div class="item-content">
                      <h3>Horaires</h3>
                      <p>Lundi - Vendredi: 08h00 - 20h00</p>
                      <p>Samedi: 09h00 - 16h00</p>
                      <p>Dimanche: Fermé</p>
                    </div>
                  </div>
                </div>

                <div class="social-section">
                  <h3>Suivez-nous</h3>
                  <div class="social-links">
                    <a href="https://www.facebook.com/aeemcice" target="_blank" class="social-link facebook">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3V2z"/>
                      </svg>
                    </a>
                    <a href="https://www.youtube.com/@aeemcitv1488" target="_blank" class="social-link youtube">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23 9.7s-.2-1.4-.8-2c-.7-.8-1.5-.8-1.9-.8-2.7-.2-6.8-.2-6.8-.2s-4.1 0-6.8.2c-.4 0-1.2 0-1.9.8-.6.6-.8 2-.8 2S4 11.1 4 12.5v1.3c0 1.4.2 2.8.2 2.8s.2 1.4.8 2c.7.8 1.6.7 2 .8 1.5.1 6.6.2 6.6.2s4.1 0 6.8-.2c.4 0 1.2 0 1.9-.8.6-.6.8-2 .8-2s.2-1.4.2-2.8v-1.3c0-1.4-.2-2.8-.2-2.8zM9.5 15.5v-7l6.5 3.5-6.5 3.5z"/>
                      </svg>
                    </a>
                    <a href="https://whatsapp.com/channel/0029VaF2TCY3mFY5DTSV1j1B" target="_blank" class="social-link whatsapp">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.5 14.4c-.3-.1-1.8-.9-2.1-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.2-.1.2-.3.2-.6.1-.3-.1-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.7-2.1-.2-.3 0-.5.1-.6.1-.1.3-.3.4-.5.1-.1.2-.3.2-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.4-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1 2.8 1.2 3c.1.2 2.1 3.2 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.8-.7 2.1-1.4.3-.7.3-1.3.2-1.4-.1-.1-.3-.2-.6-.3zm-5.5 7.5c-1.8 0-3.5-.5-5-1.4l-.4-.2-3.6.9.9-3.4-.3-.4c-1-1.5-1.5-3.3-1.5-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.5-9.5 9.5zm8-17.5c-2.2-2.2-5.1-3.4-8.2-3.4-6.4 0-11.6 5.2-11.6 11.6 0 2 .5 4 1.5 5.7L2 22l4.4-1.2c1.6.9 3.5 1.4 5.3 1.4h.1c6.4 0 11.6-5.2 11.6-11.6 0-3.1-1.2-6-3.4-8.2z"/>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Column -->
            <div class="form-column">
              <div class="form-card">
                <h2>
                  <span class="first-letter">E</span>nvoyez-nous un message
                </h2>
                <div class="title-line"></div>

                <form @submit.prevent="submitForm" class="contact-form">
                  <div class="form-row">
                    <div class="form-field">
                      <label>Nom complet <span class="required">*</span></label>
                      <input v-model="form.name" type="text" placeholder="Votre nom et prénom" required>
                    </div>
                  </div>

                  <div class="form-row two-cols">
                    <div class="form-field">
                      <label>Email <span class="required">*</span></label>
                      <input v-model="form.email" type="email" placeholder="votre@email.com" required>
                    </div>
                    <div class="form-field">
                      <label>Téléphone</label>
                      <input v-model="form.phone" type="tel" placeholder="07 XX XX XX XX">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-field">
                      <label>Lieu de résidence</label>
                      <input v-model="form.location" type="text" placeholder="Votre ville">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-field">
                      <label>Sujet <span class="required">*</span></label>
                      <select v-model="form.subject" required>
                        <option value="" disabled>Sélectionnez un sujet</option>
                        <option value="information">Demande d'information</option>
                        <option value="adhesion">Adhésion</option>
                        <option value="partenariat">Proposition de partenariat</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="autre">Autre</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-field">
                      <label>Message <span class="required">*</span></label>
                      <textarea v-model="form.message" rows="5" placeholder="Votre message..." required></textarea>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-checkbox">
                      <input v-model="form.consent" type="checkbox" id="consent" required>
                      <label for="consent">
                        J'accepte que mes données soient traitées conformément à la politique de confidentialité
                        <span class="required">*</span>
                      </label>
                    </div>
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                      <svg v-if="!isSubmitting" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                      </svg>
                      <span v-if="isSubmitting" class="spinner"></span>
                      {{ isSubmitting ? 'Envoi...' : 'Envoyer le message' }}
                    </button>
                  </div>
                </form>

                <!-- Success Message -->
                <div v-if="formSuccess" class="form-message success">
                  <div class="message-icon">✓</div>
                  <h3>Message envoyé !</h3>
                  <p>Nous vous répondrons dans les plus brefs délais.</p>
                </div>

                <!-- Error Message -->
                <div v-if="formError" class="form-message error">
                  <div class="message-icon">!</div>
                  <h3>Erreur d'envoi</h3>
                  <p>{{ formError }}</p>
                  <button @click="resetForm" class="btn btn-outline">Réessayer</button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Map Section -->
        <section class="map-section">
          <h2>
            <span class="first-letter">N</span>ous localiser
          </h2>
          <div class="title-line"></div>
          <div class="map-container">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.4479911783847!2d-3.9726892857675934!3d5.348889796120096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1eb5df24b1c51%3A0x7fe756eec1e2451d!2sMosqu%C3%A9e%20An-Nour%20de%20Riviera%202!5e0!3m2!1sfr!2sci!4v1650123456789!5m2!1sfr!2sci" 
              width="100%" 
              height="450" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy"
            ></iframe>
          </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
          <h2>
            <span class="first-letter">Q</span>uestions fréquentes
          </h2>
          <div class="title-line"></div>
          <div class="faq-container">
            <div v-for="(faq, idx) in faqs" :key="idx" class="faq-item" :class="{ active: activeFaq === idx }">
              <div class="faq-header" @click="toggleFaq(idx)">
                <h3>{{ faq.question }}</h3>
                <div class="faq-toggle">{{ activeFaq === idx ? '−' : '+' }}</div>
              </div>
              <div v-show="activeFaq === idx" class="faq-content">
                <p>{{ faq.answer }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Newsletter Section -->
        <section class="newsletter-section">
          <div class="newsletter-card">
            <div class="newsletter-content">
              <h2>Restez informé</h2>
              <p>Abonnez-vous à notre newsletter pour recevoir les dernières actualités et informations sur nos activités.</p>
              <form @submit.prevent="subscribeNewsletter" class="newsletter-form">
                <input v-model="newsletterEmail" type="email" placeholder="Votre email" required>
                <button type="submit" class="btn btn-secondary">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                  S'abonner
                </button>
              </form>
              <p class="newsletter-note">En vous abonnant, vous acceptez de recevoir nos emails.</p>
            </div>
            <div class="newsletter-image">
              <img src="https://scontent.fabj1-1.fna.fbcdn.net/v/t39.30808-6/471819768_2603533463370933_7363560428672793403_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFwH23iEBoD9SWErBAOpV3YI_MD2yDFMhUj8wPbIMUyFQdUk0HpBHLIf4nbB0dWJqUATJwGjPA2sou9z8TTqjic&_nc_ohc=euqbHiEWYlkQ7kNvwFu-NQ-&_nc_oc=Admvo_9AqFJWPrzvLFQXgAYhRlHLaZHNdP4LbQOTQ0E9wUcFAXRbtQF08mbrnox0cEI&_nc_zt=23&_nc_ht=scontent.fabj1-1.fna&_nc_gid=BBDAAb6-M85vR9G6Tykfgg&oh=00_AfG0K7xc-Ah20puIu6NXaTKErhca4pgBl6NstKNUo_WKKg&oe=680864CB" alt="Newsletter">
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const form = reactive({
  name: '',
  email: '',
  phone: '',
  location: '',
  subject: '',
  message: '',
  consent: false
})

const isSubmitting = ref(false)
const formSuccess = ref(false)
const formError = ref('')
const newsletterEmail = ref('')
const activeFaq = ref(null)

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
    answer: "Oui, l'AEEMCI propose des bourses d'études pour les élèves et étudiants méritants et nécessiteux. Les candidatures sont généralement ouvertes en début d'année académique. Consultez notre site web ou contactez-nous pour connaître les critères d'éligibilité."
  },
  {
    question: "Comment puis-je proposer un partenariat avec l'AEEMCI ?",
    answer: "Pour proposer un partenariat, veuillez nous contacter par email ou utiliser notre formulaire de contact en sélectionnant 'Proposition de partenariat' comme sujet. Notre équipe étudiera votre proposition et vous recontactera dans les meilleurs délais."
  }
]

const starStyle = (n) => ({
  left: `${Math.random() * 100}%`,
  top: `${Math.random() * 100}%`,
  animationDelay: `${Math.random() * 5}s`,
  animationDuration: `${15 + Math.random() * 20}s`
})

const crescentStyle = (n) => ({
  left: `${Math.random() * 100}%`,
  top: `${Math.random() * 100}%`,
  animationDelay: `${Math.random() * 5}s`,
  animationDuration: `${15 + Math.random() * 20}s`
})

const geometricStyle = (n) => ({
  left: `${Math.random() * 100}%`,
  top: `${Math.random() * 100}%`,
  animationDelay: `${Math.random() * 5}s`,
  animationDuration: `${15 + Math.random() * 20}s`,
  transform: `rotate(${Math.random() * 360}deg)`
})

const submitForm = async () => {
  isSubmitting.value = true
  formSuccess.value = false
  formError.value = ''
  
  try {
    await new Promise(resolve => setTimeout(resolve, 1500))
    Object.assign(form, {
      name: '',
      email: '',
      phone: '',
      location: '',
      subject: '',
      message: '',
      consent: false
    })
    formSuccess.value = true
  } catch (error) {
    formError.value = "Une erreur est survenue. Veuillez réessayer."
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  formError.value = ''
}

const toggleFaq = (idx) => {
  activeFaq.value = activeFaq.value === idx ? null : idx
}

const subscribeNewsletter = async () => {
  await new Promise(resolve => setTimeout(resolve, 1000))
  alert(`Merci de vous être abonné: ${newsletterEmail.value}`)
  newsletterEmail.value = ''
}
</script>

<script>
export default { name: 'ContactPage' }
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@300;400;600;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

.contact-page {
  font-family: 'Zain', sans-serif;
  background: linear-gradient(135deg, rgba(1,132,4,0.03), rgba(251,146,60,0.03));
}

/* Hero Banner */
.hero-banner {
  position: relative;
  background: linear-gradient(135deg, #018404, #16a34a);
  padding: 5rem 2rem 4rem;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.pattern {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation: float 20s ease-in-out infinite;
}

.pattern-1 {
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, #fbbf24, transparent);
  top: -200px;
  left: -150px;
}

.pattern-2 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, #fb923c, transparent);
  top: -100px;
  right: -200px;
  animation-delay: -5s;
}

.pattern-3 {
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, #fbbf24, transparent);
  bottom: -100px;
  left: 50%;
  animation-delay: -10s;
}

@keyframes float {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(30px, -30px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
}

/* Islamic Patterns */
.islamic-patterns {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  z-index: 1;
}

.pattern-star, .pattern-crescent, .pattern-geometric {
  position: absolute;
  opacity: 0.15;
  animation: float-pattern 25s infinite linear;
}

.pattern-star {
  width: 30px;
  height: 30px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
  background-size: contain;
}

.pattern-crescent {
  width: 25px;
  height: 25px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 100 18 9 9 0 000-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
  background-size: contain;
}

.pattern-geometric {
  width: 35px;
  height: 35px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0L100 50L50 100L0 50Z'/%3E%3C/svg%3E");
  background-size: contain;
}

@keyframes float-pattern {
  0% { transform: translate(0, -100px) rotate(0deg); }
  50% { transform: translate(0, calc(100vh + 100px)) rotate(180deg); }
  100% { transform: translate(0, -100px) rotate(360deg); }
}

.hero-container {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

.hero-content {
  text-align: center;
  color: white;
}

.hero-content h1 {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 1rem;
  line-height: 1.1;
}

.gradient-text {
  background: linear-gradient(135deg, #fbbf24, #fb923c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  font-size: 1.3rem;
  opacity: 0.95;
  margin-bottom: 1.5rem;
}

.breadcrumbs {
  font-size: 0.95rem;
  opacity: 0.9;
}

.breadcrumbs a {
  color: white;
  text-decoration: none;
  transition: opacity 0.3s;
}

.breadcrumbs a:hover { opacity: 1; }

.separator {
  margin: 0 0.75rem;
  opacity: 0.7;
}

.current {
  font-weight: 700;
}

/* Main Content */
.main-content {
  padding: 4rem 0;
}

.content-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* Intro */
.intro-section {
  text-align: center;
  max-width: 800px;
  margin: 0 auto 4rem;
}

.intro-text {
  font-size: 1.15rem;
  line-height: 1.8;
  color: #4b5563;
}

/* Contact Section */
.contact-section {
  margin-bottom: 5rem;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 3rem;
}

.info-card, .form-card {
  background: white;
  border-radius: 24px;
  padding: 3rem;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
}

h2 {
  font-size: 2rem;
  font-weight: 900;
  color: #1a1a1a;
  margin-bottom: 1rem;
}

.first-letter {
  color: #fbbf24;
  font-size: 2.5rem;
}

.title-line {
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, #018404, #fb923c);
  border-radius: 2px;
  margin-bottom: 2rem;
}

/* Info Items */
.info-items {
  margin-bottom: 3rem;
}

.info-item {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 2rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #e5e7eb;
}

.info-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
  margin-bottom: 0;
}

.item-icon {
  font-size: 2rem;
  flex-shrink: 0;
}

.item-content h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #018404;
  margin-bottom: 0.5rem;
}

.item-content p {
  color: #6b7280;
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Social Section */
.social-section h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 1rem;
}

.social-links {
  display: flex;
  gap: 1rem;
}

.social-link {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, rgba(1,132,4,0.1), rgba(251,146,60,0.1));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #018404;
  transition: all 0.3s ease;
}

.social-link:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(1,132,4,0.2);
}

.social-link.facebook:hover { color: #1877f2; }
.social-link.youtube:hover { color: #ff0000; }
.social-link.whatsapp:hover { color: #25d366; }

/* Form */
.contact-form {
  margin-bottom: 2rem;
}

.form-row {
  margin-bottom: 1.5rem;
}

.form-row.two-cols {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.form-field label {
  display: block;
  font-weight: 700;
  color: #374151;
  margin-bottom: 0.75rem;
  font-size: 0.95rem;
}

.required {
  color: #dc2626;
}

.form-field input,
.form-field select,
.form-field textarea {
  width: 100%;
  padding: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  font-family: 'Zain', sans-serif;
  transition: all 0.3s ease;
}

.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
  outline: none;
  border-color: #018404;
  box-shadow: 0 0 0 4px rgba(1,132,4,0.1);
}

.form-checkbox {
  display: flex;
  gap: 0.75rem;
  align-items: flex-start;
}

.form-checkbox input {
  margin-top: 0.25rem;
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.form-checkbox label {
  font-size: 0.9rem;
  color: #6b7280;
  line-height: 1.5;
  cursor: pointer;
}

.form-actions {
  margin-top: 2rem;
}

.btn {
  padding: 1.25rem 2.5rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-family: 'Zain', sans-serif;
  width: 100%;
  justify-content: center;
}

.btn-primary {
  background: linear-gradient(135deg, #018404, #16a34a);
  color: white;
  box-shadow: 0 10px 30px rgba(1,132,4,0.3);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-3px);
  box-shadow: 0 15px 40px rgba(1,132,4,0.4);
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.btn-secondary {
  background: linear-gradient(135deg, #fbbf24, #fb923c);
  color: white;
  box-shadow: 0 8px 25px rgba(251,191,36,0.3);
}

.btn-secondary:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(251,191,36,0.4);
}

.btn-outline {
  background: transparent;
  color: #018404;
  border: 2px solid #018404;
  padding: 0.875rem 2rem;
}

.btn-outline:hover {
  background: rgba(1,132,4,0.1);
}

.spinner {
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255,255,255,0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Form Messages */
.form-message {
  margin-top: 2rem;
  padding: 2rem;
  border-radius: 16px;
  text-align: center;
}

.form-message.success {
  background: rgba(22,163,74,0.1);
  border: 2px solid #16a34a;
}

.form-message.error {
  background: rgba(220,38,38,0.1);
  border: 2px solid #dc2626;
}

.message-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  margin-bottom: 1rem;
}

.form-message.success .message-icon {
  background: #16a34a;
  color: white;
}

.form-message.error .message-icon {
  background: #dc2626;
  color: white;
}

.form-message h3 {
  font-size: 1.5rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
}

.form-message p {
  color: #6b7280;
  margin-bottom: 1.5rem;
}

/* Map Section */
.map-section {
  margin-bottom: 5rem;
  text-align: center;
}

.map-section h2 {
  margin-bottom: 0.5rem;
}

.map-section .title-line {
  margin: 0 auto 2.5rem;
}

.map-container {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

/* FAQ Section */
.faq-section {
  margin-bottom: 5rem;
  text-align: center;
}

.faq-section h2 {
  margin-bottom: 0.5rem;
}

.faq-section .title-line {
  margin: 0 auto 2.5rem;
}

.faq-container {
  max-width: 800px;
  margin: 0 auto;
  text-align: left;
}

.faq-item {
  background: white;
  border-radius: 16px;
  margin-bottom: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

.faq-item:hover {
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.faq-item.active {
  box-shadow: 0 8px 20px rgba(1,132,4,0.15);
}

.faq-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

.faq-item.active .faq-header {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
}

.faq-header h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
}

.faq-toggle {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, rgba(1,132,4,0.1), rgba(251,146,60,0.1));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 700;
  color: #018404;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.faq-item.active .faq-toggle {
  background: #018404;
  color: white;
  transform: rotate(180deg);
}

.faq-content {
  padding: 0 2rem 1.5rem;
  color: #6b7280;
  line-height: 1.7;
}

/* Newsletter Section */
.newsletter-section {
  margin-bottom: 3rem;
}

.newsletter-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border-radius: 24px;
  overflow: hidden;
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  min-height: 400px;
}

.newsletter-content {
  padding: 4rem;
}

.newsletter-content h2 {
  font-size: 2.5rem;
  font-weight: 900;
  color: #018404;
  margin-bottom: 1rem;
}

.newsletter-content > p {
  color: #6b7280;
  font-size: 1.1rem;
  line-height: 1.7;
  margin-bottom: 2rem;
}

.newsletter-form {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.newsletter-form input {
  flex: 1;
  padding: 1rem 1.5rem;
  border: 2px solid #e5e7eb;
  border-radius: 16px;
  font-size: 1rem;
  font-family: 'Zain', sans-serif;
  transition: all 0.3s ease;
}

.newsletter-form input:focus {
  outline: none;
  border-color: #018404;
  box-shadow: 0 0 0 4px rgba(1,132,4,0.1);
}

.newsletter-form .btn {
  width: auto;
  padding: 1rem 2rem;
}

.newsletter-note {
  font-size: 0.85rem;
  color: #9ca3af;
  line-height: 1.5;
}

.newsletter-image {
  overflow: hidden;
  position: relative;
}

.newsletter-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Responsive */
@media (max-width: 1024px) {
  .contact-grid {
    grid-template-columns: 1fr;
  }
  
  .newsletter-card {
    grid-template-columns: 1fr;
  }
  
  .newsletter-image {
    min-height: 300px;
  }
}

@media (max-width: 768px) {
  .hero-content h1 {
    font-size: 2.5rem;
  }
  
  .form-row.two-cols {
    grid-template-columns: 1fr;
  }
  
  .info-card, .form-card {
    padding: 2rem 1.5rem;
  }
  
  .newsletter-content {
    padding: 2.5rem 2rem;
  }
  
  .newsletter-form {
    flex-direction: column;
  }
  
  .newsletter-form .btn {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .hero-content h1 {
    font-size: 2rem;
  }
  
  h2 {
    font-size: 1.75rem;
  }
  
  .first-letter {
    font-size: 2rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
</style>