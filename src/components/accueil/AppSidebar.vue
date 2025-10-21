<template>
    <div class="sidebar">
      <!-- Widget Opportunités -->
      <div class="sidebar-widget animate-fade-in">
        <div class="widget-header">
          <h3><span class="highlight">O</span>pportunités</h3>
        </div>
        <div class="widget-content">
          <div class="opportunities">
            <a href="https://agenceemploijeunes.ci/" target="_blank">
              <img 
              style="border-bottom: 2px solid gray; padding-bottom: 20px"
                src="../../assets/pub1.jpg" 
                alt="Publicité AEEMCI" 
                class="popup-image"
              />
            </a>
            <div class="opportunity-item" v-for="opportunity in opportunities" :key="opportunity.id">
              <h4>{{ opportunity.title }}</h4>
              <p>{{ opportunity.description }}</p>
              <a href="/cv" class="btn btn-sm" v-if="opportunity.id == 1" style="background-color: #f69405;">{{ opportunity.action }}</a>
              <a href="#" class="btn btn-sm" v-else>{{ opportunity.action }}</a>
            </div>
          </div>
        </div>
      </div>

      <div class="sidebar-widget animate-fade-in">
        <div class="widget-header">
          <h3><span class="highlight">Liens</span> Utiles </h3>
        </div>
        <div class="widget-content">
          <div class="opportunities">
            <div class="opportunity-item" v-for="opportunity in opportunities2" :key="opportunity.id">
              <h4>{{ opportunity.title }}</h4>
              <a target="_blank" :href="opportunity.description" class="btn btn-sm">{{ opportunity.action }}</a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Widget Calendrier -->
      <div class="sidebar-widget animate-fade-in" style="animation-delay: 0.3s;">
        <div class="widget-header">
          <h3><span class="highlight">C</span>alendrier</h3>
        </div>
        <div class="widget-content">
          <div class="mini-calendar">
            <div class="calendar-header">
              <button @click="changeMonth(-1)" class="calendar-nav">❮</button>
              <div class="current-month">{{ currentMonthName }} {{ currentYear }}</div>
              <button @click="changeMonth(1)" class="calendar-nav">❯</button>
            </div>
            <div class="calendar-days">
              <div v-for="day in ['D', 'L', 'M', 'M', 'J', 'V', 'S']" :key="day" class="day-name">{{ day }}</div>
            </div>
            <div class="calendar-dates">
              <div 
                v-for="date in calendarDates" 
                :key="date.day" 
                :class="[
                  'calendar-date', 
                  { 'other-month': !date.currentMonth },
                  { 'today': date.isToday },
                ]"
                @click="date.hasEvent ? showEvent(date) : null"
              >
                {{ date.day }}
              </div>
            </div>
            <div v-if="selectedEvent" class="calendar-event">
              <h5>{{ selectedEvent.title }}</h5>
              <p>{{ selectedEvent.time }}</p>
              <a href="#" class="event-link">Détails →</a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Widget Hadith du jour -->
      <div class="sidebar-widget animate-fade-in" style="animation-delay: 0.4s;">
        <div class="widget-header">
          <h3><span class="highlight">H</span>adith du jour</h3>
        </div>
        <div class="widget-content">
          <div class="hadith-container">
            <div class="hadith-text">
              <p>{{ currentHadith.text }}</p>
            </div>
            <div class="hadith-source">
              {{ currentHadith.source }}
            </div>
          </div>
        </div>
      </div>
      
      <!-- Widget Suivez-nous -->
      <div class="sidebar-widget animate-fade-in" style="animation-delay: 0.5s;">
        <div class="widget-header">
          <h3><span class="highlight">S</span>uivez-nous</h3>
        </div>
        <div class="widget-content">
          <div class="social-links">
            <a href="#" class="social-link animate-hover" v-for="social in socialLinks" :key="social.name">
              <div class="social-icon" :class="social.name.toLowerCase()">
                <span><img :src="social.icon" style="width: 100px;"></span>
              </div>
              <span>{{ social.name }}</span>
            </a>
          </div>
        </div>
      </div>
      
      <!-- Widget Témoignages -->
      <div class="sidebar-widget animate-fade-in" style="animation-delay: 0.6s;">
        <div class="widget-header">
          <h3><span class="highlight">T</span>émoignages</h3>
        </div>
        <div class="widget-content">
          <div class="testimonial-slider" ref="testimonialSlider">
            <div class="testimonial-slide" v-for="(testimonial, index) in testimonials" :key="index">
              <div class="testimonial-content">
                <div class="testimonial-quote">❝</div>
                <p>{{ testimonial.text }}</p>
                <div class="testimonial-author">
                  <div class="testimonial-avatar">
                    <img :src="testimonial.avatar" alt="Avatar" />
                  </div>
                  <div class="testimonial-info">
                    <div class="testimonial-name">{{ testimonial.name }}</div>
                    <div class="testimonial-role">{{ testimonial.role }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="testimonial-dots">
            <span 
              v-for="(_, index) in testimonials" 
              :key="index" 
              :class="{ active: currentTestimonial === index }"
              @click="setTestimonial(index)"
              class="testimonial-dot"
            ></span>
          </div>
        </div>
      </div>
      
      <!-- Widget Newsletter -->
      <div class="sidebar-widget animate-fade-in" style="animation-delay: 0.7s;">
        <div class="widget-header">
          <h3><span class="highlight">N</span>ewsletter</h3>
        </div>
        <div class="widget-content">
          <div class="newsletter">
            <p>Abonnez-vous à notre newsletter pour recevoir nos actualités</p>
            <form @submit.prevent="subscribeNewsletter" class="newsletter-form">
              <input type="email" v-model="newsletterEmail" placeholder="Votre email" required class="form-input" />
              <button type="submit" class="btn btn-primary btn-sm">S'abonner</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
  
  const newsletterEmail = ref('')
  const currentTestimonial = ref(0)
  const currentMonth = ref(new Date().getMonth())
  const currentYear = ref(new Date().getFullYear())
  const selectedEvent = ref(null)
  const currentHadithIndex = ref(0)
  
  // Opportunities
  const opportunities = ref([
    {
      id: 1,
      title: 'Stages et emplois',
      description: 'Déposez dès maintenant votre CV pour profiter des offres de stages et d’emplois adaptées à votre profil.',
      action: 'Déposer mon CV'
    },
    {
      id: 2,
      title: 'Bourses d\'étude',
      description: 'Voir les opportunités de bourses disponibles',
      action: 'Consulter'
    },
    {
      id: 3,
      title: 'Rendez-vous sociaux éducatifs',
      description: 'Devenez volontaire pour nos actions humanitaires',
      action: 'Rejoindre'
    }
  ])

  const opportunities2 = ref([
    {
      id: 1,
      title: '•	Ministre de la Promotion de la Jeunesse, de l’Insertion professionnelle et du Service civique',
      description: 'http://jeunesse.gouv.ci',
      action: 'Consulter'
    },
    {
      id: 2,
      title: '•	Ministre de l’Enseignement Supérieur et de la Recherche Scientifique',
      description: 'http://www.enseignement.gouv.ci',
      action: 'Consulter'
    },
    {
      id: 3,
      title: '•	Ministre de l’Enseignement technique, de la Formation professionnelle et de l’Apprentissage',
      description: 'http://www.gouv.ci ',
      action: 'Consulter'
    }
  ])
  
  // Hadiths
  const hadiths = ref([
    {
      text: "Les actes ne valent que par leurs intentions, et la personne obtient ce qu'elle à eu comme intention.",
      source: "Rapporté par Al-Bukhari et Muslim"
    },
    {
      text: "Nul d'entre vous n'est véritablement croyant tant qu'il n'aime pas pour son frère ce qu'il aime pour lui-même.",
      source: "Rapporté par Al-Bukhari et Muslim"
    },
    {
      text: "Quiconque croit en Allah et au Jour dernier, qu'il dise du bien ou qu'il se taise.",
      source: "Rapporté par Al-Bukhari et Muslim"
    },
    {
      text: "La bonté est le bon caractère, et le péché est ce qui agite ton âme et que tu n'aimerais pas que les gens découvrent.",
      source: "Rapporté par Muslim"
    }
  ])
  
  const currentHadith = computed(() => hadiths.value[currentHadithIndex.value])
  
  // Social links
  const socialLinks = ref([
    { name: 'Facebook', icon: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758210636/aeemci_photo_1758210635353_facebook-scalable-vector-graphics-icon-facebook-logo-png-b2bd60751e410d1a8e8c60a1a74d6f99.png.png' },
    { name: 'Twitter', icon: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758210771/aeemci_photo_1758210770186_6d8746f8-2192-44c6-81cc-c311b644dd5e.jpeg.jpg' },
    { name: 'Whatsapp', icon: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758210806/aeemci_photo_1758210804708_whatsapp-application-software-message-icon-whatsapp-logo-png-b5e77839959b29a157e7c7ff89f46dd1.png.png' },
    { name: 'YouTube', icon: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758210741/aeemci_photo_1758210739729_youtube-logo-youtube-2ff53c4ad1e285d1c6c2e11e6dbfe171.png.png' }
  ])
  
  // Testimonials
  const testimonials = ref([
    {
      text: "L'AEEMCI a changé ma vie. J'ai pu développer mes compétences en leadership tout en renforçant ma foi.",
      name: "Aminata Koné",
      role: "Ancienne membre, Promotion 2018",
      avatar: "https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80"
    },
    {
      text: "Grâce aux formations de l'AEEMCI, j'ai pu concilier mes études universitaires avec une solide éducation islamique.",
      name: "Ibrahim Touré",
      role: "Étudiant en médecine",
      avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80"
    },
    {
      text: "Les activités sociales de l'AEEMCI m'ont permis de contribuer au bien-être de ma communauté tout en m'épanouissant.",
      name: "Fatima Diallo",
      role: "Bénévole active",
      avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80"
    }
  ])
  
  // Calendar
  const currentMonthName = computed(() => {
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
    return months[currentMonth.value]
  })
  
  const calendarDates = computed(() => {
    const today = new Date()
    const firstDay = new Date(currentYear.value, currentMonth.value, 1)
    const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0)
    const daysInMonth = lastDay.getDate()
    const startingDay = firstDay.getDay()
    
    const events = [
      { day: 10, title: 'Réunion du bureau', time: '14h00' },
      { day: 15, title: 'Conférence', time: '18h30' },
      { day: 22, title: 'Formation', time: '09h00' }
    ]
    
    const dates = []
    
    // Previous month days
    const prevMonthLastDay = new Date(currentYear.value, currentMonth.value, 0).getDate()
    for (let i = startingDay - 1; i >= 0; i--) {
      dates.push({
        day: prevMonthLastDay - i,
        currentMonth: false,
        isToday: false,
        hasEvent: false
      })
    }
    
    // Current month days
    for (let i = 1; i <= daysInMonth; i++) {
      const isToday = i === today.getDate() && currentMonth.value === today.getMonth() && currentYear.value === today.getFullYear()
      const event = events.find(e => e.day === i)
      
      dates.push({
        day: i,
        currentMonth: true,
        isToday,
        hasEvent: !!event,
        event: event
      })
    }
    
    // Next month days
    const remainingDays = 42 - dates.length
    for (let i = 1; i <= remainingDays; i++) {
      dates.push({
        day: i,
        currentMonth: false,
        isToday: false,
        hasEvent: false
      })
    }
    
    return dates
  })
  
  // Methods
  const subscribeNewsletter = () => {
    alert(`Merci de vous être abonné avec l'email: ${newsletterEmail.value}`)
    newsletterEmail.value = ''
  }
  
  const changeMonth = (direction) => {
    let newMonth = currentMonth.value + direction
    
    if (newMonth < 0) {
      newMonth = 11
      currentYear.value--
    } else if (newMonth > 11) {
      newMonth = 0
      currentYear.value++
    }
    
    currentMonth.value = newMonth
  }
  
  const showEvent = (date) => {
    if (date.event) {
      selectedEvent.value = date.event
    }
  }
  
  const changeHadith = () => {
    currentHadithIndex.value = (currentHadithIndex.value + 1) % hadiths.value.length
  }
  
  const setTestimonial = (index) => {
    currentTestimonial.value = index
    updateTestimonialSlider()
  }
  
  const updateTestimonialSlider = () => {
    const slider = document.querySelector('.testimonial-slider')
    if (slider) {
      slider.style.transform = `translateX(-${currentTestimonial.value * 100}%)`
    }
  }
  
  let testimonialInterval
  
  const startTestimonialSlider = () => {
    testimonialInterval = setInterval(() => {
      currentTestimonial.value = (currentTestimonial.value + 1) % testimonials.value.length
      updateTestimonialSlider()
    }, 5000)
  }
  
  onMounted(() => {
    startTestimonialSlider()
    updateTestimonialSlider()
    
    setTimeout(() => {
      document.querySelectorAll('.sidebar-widget').forEach(el => {
        el.classList.add('animate-visible')
      })
    }, 300)
  })
  
  onBeforeUnmount(() => {
    if (testimonialInterval) {
      clearInterval(testimonialInterval)
    }
  })
  </script>
  
  <style scoped>
  /* Sidebar */
  .sidebar {
    flex: 0 0 calc(33.333% - 0.6rem);
    width: calc(33.333% - 0.6rem);
  }
  
  .sidebar-widget {
    background-color: white;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
    opacity: 0;
    transform: translateY(15px);
    margin-bottom: 1.2rem;
  }
  
  .sidebar-widget:hover {
    transform: translateY(-3px);
  }
  
  .sidebar-widget.animate-visible {
    opacity: 1;
    transform: translateY(0);
  }
  
  .widget-header {
    background-color: #16a34a;
    color: white;
    padding: 0.6rem;
  }
  
  .widget-header h3 {
    margin: 0;
    font-size: 1rem;
  }
  
  .highlight {
    color: var(--secondary);
    font-weight: 800;
  }
  
  .widget-content {
    padding: 0.8rem;
  }
  
  /* Opportunities */
  .opportunity-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
  }
  
  .opportunity-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
  }
  
  .opportunity-item h4 {
    margin-bottom: 0.4rem;
    color: #16a34a;
    font-size: 0.9rem;
  }
  
  /* Mini Calendar */
  .mini-calendar {
    font-size: 0.8rem;
  }
  
  .calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.6rem;
  }
  
  .calendar-nav {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    color: #16a34a;
  }
  
  .current-month {
    font-weight: 600;
  }
  
  .calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    margin-bottom: 0.4rem;
  }
  
  .day-name {
    font-weight: 600;
    font-size: 0.75rem;
    color: var(--text-secondary);
  }
  
  .calendar-dates {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.2rem;
  }
  
  .calendar-date {
    text-align: center;
    padding: 0.3rem 0;
    font-size: 0.75rem;
    border-radius: 3px;
    cursor: pointer;
  }
  
  .calendar-date.other-month {
    color: var(--text-light);
  }
  
  .calendar-date.today {
    background-color: #16a34a;
    color: white;
    font-weight: 600;
  }
  
  .calendar-date.has-event {
    background-color: var(--secondary-light);
    font-weight: 600;
    position: relative;
  }
  
  .calendar-date.has-event::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background-color: #16a34a;
  }
  
  .calendar-event {
    margin-top: 0.8rem;
    padding: 0.6rem;
    background-color: var(--background-alt);
    border-radius: 4px;
  }
  
  .calendar-event h5 {
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
    color: #16a34a;
  }
  
  .calendar-event p {
    font-size: 0.75rem;
    margin-bottom: 0.4rem;
  }
  
  .event-link {
    font-size: 0.75rem;
    font-weight: 600;
    color: #16a34a;
    text-decoration: none;
  }
  
  /* Hadith Widget */
  .hadith-container {
    text-align: center;
  }
  
  .hadith-text {
    font-style: italic;
    margin-bottom: 0.6rem;
    font-size: 0.85rem;
  }
  
  .hadith-source {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 0.8rem;
  }
  
  .hadith-btn {
    margin: 0 auto;
    display: block;
  }
  
  /* Social Links */
  .social-links {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
  }
  
  .social-link {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: var(--text-primary);
  }
  
  .social-link:hover {
    background-color: var(--background-alt);
    transform: translateY(-2px);
  }
  
  .social-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.4rem;
    font-size: 0.9rem;
  }
  
  .social-icon.facebook {
    background-color: #3b5998;
    color: white;
  }
  
  .social-icon.twitter {
    background-color: #1da1f2;
    color: white;
  }
  
  .social-icon.instagram {
    background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    color: white;
  }
  
  .social-icon.youtube {
    background-color: #ff0000;
    color: white;
  }
  
  /* Testimonial Slider */
  .testimonial-slider {
    display: flex;
    transition: transform 0.5s ease;
    margin-bottom: 0.8rem;
    overflow: hidden;
  }
  
  .testimonial-slide {
    flex: 0 0 100%;
  }
  
  .testimonial-content {
    position: relative;
    padding: 1rem;
    background-color: var(--background-alt);
    border-radius: 6px;
  }
  
  .testimonial-quote {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    font-size: 1.5rem;
    color: #16a34a;
    opacity: 0.2;
  }
  
  .testimonial-content p {
    font-size: 0.85rem;
    margin-bottom: 0.8rem;
    font-style: italic;
  }
  
  .testimonial-author {
    display: flex;
    align-items: center;
  }
  
  .testimonial-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 0.6rem;
  }
  
  .testimonial-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .testimonial-name {
    font-weight: 600;
    font-size: 0.8rem;
  }
  
  .testimonial-role {
    font-size: 0.75rem;
    color: var(--text-secondary);
  }
  
  .testimonial-dots {
    display: flex;
    justify-content: center;
    gap: 0.4rem;
  }
  
  .testimonial-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--border);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .testimonial-dot.active {
    background-color: #16a34a;
    transform: scale(1.2);
  }
  
  /* Newsletter */
  .newsletter p {
    margin-bottom: 0.8rem;
    font-size: 0.85rem;
  }
  
  .newsletter-form {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .form-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--border);
    border-radius: 3px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
  }
  
  .form-input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 2px rgba(26, 95, 63, 0.1);
  }
  
  .btn {
    display: inline-block;
    padding: 0.3rem 0.7rem;
    border-radius: 2px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
    text-decoration: none;
    background-color: #16a34a;
    color: white;
  }
  
  .btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }
  
  .btn-primary {
    background-color: var(--secondary);
    color: #16a34a;
    box-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
  }
  
  .btn-primary:hover {
    background-color: #ffed4e;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(255, 215, 0, 0.4);
  }
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid #16a34a;
    color: #16a34a;
  }
  
  .btn-outline:hover {
    background-color: #16a34a;
    color: white;
  }
  
  .animate-fade-in {
    opacity: 0;
    transform: translateY(15px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  
  .animate-hover {
    transition: all 0.3s ease;
  }
  
  .animate-hover:hover {
    transform: translateY(-2px);
  }
  
  /* Responsive */
  @media (max-width: 992px) {
    .sidebar {
      flex: 0 0 100% !important;
      width: 100% !important;
    }
  }
  
  @media (max-width: 576px) {
    .social-links {
      grid-template-columns: 1fr;
    }
  }
  </style>
  