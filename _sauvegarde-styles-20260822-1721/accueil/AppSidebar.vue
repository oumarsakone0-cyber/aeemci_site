<template>
  <aside class="sidebar">
    <!-- Opportunités -->
    <section class="widget rv tone-clay">
      <h3><span class="wdot"></span> Opportunités</h3>

      <a class="promo" href="https://agenceemploijeunes.ci/" target="_blank" rel="noopener">
        <img :src="pub" alt="Agence Emploi Jeunes">
        <span class="promo-cap">Agence Emploi Jeunes <b>↗</b></span>
      </a>

      <div class="olist">
        <RouterLink v-for="op in opportunities" :key="op.id" class="orow" :to="op.to">
          <svg class="oic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" v-html="op.icon"></svg>
          <span>
            <strong>{{ op.title }}</strong>
            <small>{{ op.description }}</small>
          </span>
          <svg class="oarw" width="13" height="9" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </RouterLink>
      </div>
    </section>

    <!-- Hadith du jour -->
    <section class="widget widget--dark rv">
      <h3><span class="wdot"></span> Hadith du jour</h3>
      <blockquote>
        <p>« {{ currentHadith.text }} »</p>
        <cite>{{ currentHadith.source }}</cite>
      </blockquote>
      <button type="button" class="hnext" @click="nextHadith">
        Suivant
        <svg width="13" height="9" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </section>

    <!-- Calendrier -->
    <section class="widget rv tone-teal">
      <h3><span class="wdot"></span> Agenda</h3>
      <div class="cal-head">
        <button type="button" @click="changeMonth(-1)" aria-label="Mois précédent">‹</button>
        <strong>{{ currentMonthName }} {{ currentYear }}</strong>
        <button type="button" @click="changeMonth(1)" aria-label="Mois suivant">›</button>
      </div>
      <div class="cal-days">
        <span v-for="d in ['L','M','M','J','V','S','D']" :key="d">{{ d }}</span>
      </div>
      <div class="cal-grid">
        <button
          v-for="(date, i) in calendarDates" :key="i" type="button"
          class="cal-cell"
          :class="{ out: !date.currentMonth, today: date.isToday, event: date.hasEvent }"
          @click="showEvent(date)"
        >{{ date.day }}</button>
      </div>
      <div v-if="selectedEvent" class="cal-event">
        <strong>{{ selectedEvent.title }}</strong>
        <span>{{ selectedEvent.time }}</span>
      </div>
    </section>

    <!-- Témoignages -->
    <section class="widget rv tone-plum">
      <h3><span class="wdot"></span> Ils en parlent</h3>
      <transition name="fade" mode="out-in">
        <figure class="testi" :key="currentTestimonial">
          <p>« {{ testimonials[currentTestimonial].text }} »</p>
          <figcaption>
            <span class="avatar">{{ initials(testimonials[currentTestimonial].name) }}</span>
            <span>
              <strong>{{ testimonials[currentTestimonial].name }}</strong>
              <small>{{ testimonials[currentTestimonial].role }}</small>
            </span>
          </figcaption>
        </figure>
      </transition>
      <div class="dots">
        <button
          v-for="(t, i) in testimonials" :key="i" type="button"
          :class="{ on: i === currentTestimonial }" :aria-label="`Témoignage ${i + 1}`"
          @click="currentTestimonial = i"
        ></button>
      </div>
    </section>

    <!-- Liens utiles -->
    <section class="widget rv tone-gold">
      <h3><span class="wdot"></span> Liens utiles</h3>
      <ul class="links">
        <li v-for="link in usefulLinks" :key="link.id">
          <a :href="link.url" target="_blank" rel="noopener">
            {{ link.title }}
            <span>↗</span>
          </a>
        </li>
      </ul>
    </section>

    <!-- Suivez-nous -->
    <section class="widget rv tone-green">
      <h3><span class="wdot"></span> Suivez-nous</h3>
      <div class="socials">
        <a v-for="s in socialLinks" :key="s.name" :href="s.url" target="_blank" rel="noopener" :aria-label="s.name">
          <svg viewBox="0 0 24 24" fill="currentColor" v-html="s.icon"></svg>
        </a>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="widget widget--dark rv">
      <h3><span class="wdot"></span> Lettre d'information</h3>
      <p class="nl-text">Les annonces, les inscriptions et l'agenda, une fois par mois.</p>
      <form class="nl-form" @submit.prevent="subscribeNewsletter">
        <input v-model="newsletterEmail" type="email" required placeholder="Votre adresse e-mail" aria-label="Adresse e-mail">
        <button type="submit">S'abonner</button>
      </form>
      <p v-if="newsletterSuccess" class="nl-ok">Merci, votre inscription est enregistrée.</p>
    </section>
  </aside>
</template>

<script setup>
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import pub from '../../assets/pub1.jpg'

/* ---------- Opportunités ---------- */
const opportunities = ref([
  {
    id: 1, title: 'Stages et emplois', to: '/cv',
    description: 'Déposez votre CV pour recevoir les offres adaptées à votre profil.',
    icon: '<rect x="2.5" y="7" width="19" height="13" rx="1"/><path d="M8.5 7V5.5a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2V7M2.5 12.5h19"/>'
  },
  {
    id: 2, title: "Bourses d'étude", to: '/opportunites',
    description: 'Les bourses nationales et internationales ouvertes aux militants.',
    icon: '<path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M5.5 10v5.2c0 1.6 2.9 3.3 6.5 3.3s6.5-1.7 6.5-3.3V10"/>'
  },
  {
    id: 3, title: 'Rendez-vous sociaux', to: '/programmes',
    description: 'Participez aux actions humanitaires et sociales de l\'association.',
    icon: '<path d="M12 20.5s-7.5-4.6-7.5-9.6a4.3 4.3 0 0 1 7.5-2.8 4.3 4.3 0 0 1 7.5 2.8c0 5-7.5 9.6-7.5 9.6Z"/>'
  }
])

/* ---------- Liens utiles ---------- */
const usefulLinks = ref([
  { id: 1, title: 'Ministère de la Promotion de la Jeunesse', url: 'http://jeunesse.gouv.ci' },
  { id: 2, title: "Ministère de l'Enseignement Supérieur", url: 'http://www.enseignement.gouv.ci' },
  { id: 3, title: "Ministère de l'Enseignement Technique", url: 'http://www.gouv.ci' }
])

/* ---------- Hadiths ---------- */
const hadiths = ref([
  { text: "Les actes ne valent que par leurs intentions, et la personne obtient ce qu'elle a eu comme intention.", source: 'Rapporté par Al-Bukhari et Muslim' },
  { text: "Nul d'entre vous n'est véritablement croyant tant qu'il n'aime pas pour son frère ce qu'il aime pour lui-même.", source: 'Rapporté par Al-Bukhari et Muslim' },
  { text: "Quiconque croit en Allah et au Jour dernier, qu'il dise du bien ou qu'il se taise.", source: 'Rapporté par Al-Bukhari et Muslim' },
  { text: "La bonté est le bon caractère, et le péché est ce qui agite ton âme et que tu n'aimerais pas que les gens découvrent.", source: 'Rapporté par Muslim' }
])
const currentHadithIndex = ref(0)
const currentHadith = computed(() => hadiths.value[currentHadithIndex.value])
const nextHadith = () => {
  currentHadithIndex.value = (currentHadithIndex.value + 1) % hadiths.value.length
}

/* ---------- Réseaux ---------- */
const socialLinks = ref([
  { name: 'Facebook', url: 'https://facebook.com/aeemci', icon: '<path d="M14 8.5V6.8c0-.8.2-1.2 1.4-1.2H17V2.6h-2.6c-3 0-4.1 1.5-4.1 4v1.9H8.3V11.5h2V21.4H14V11.5h2.5l.4-3H14Z"/>' },
  { name: 'YouTube', url: 'https://youtube.com/@aeemci', icon: '<path d="M22 12s0-3.3-.4-4.9a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4a2.5 2.5 0 0 0-1.8 1.8C2 8.7 2 12 2 12s0 3.3.4 4.9c.2.9.9 1.6 1.8 1.8 1.6.3 7.8.3 7.8.3s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8C22 15.3 22 12 22 12ZM10 15.2V8.8l5.2 3.2L10 15.2Z"/>' },
  { name: 'WhatsApp', url: 'https://wa.me/2250700000000', icon: '<path d="M12 2a9.9 9.9 0 0 0-8.5 15L2 22l5.2-1.4A9.9 9.9 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2A5.2 5.2 0 0 0 8 11c.1.2 1.5 2.4 3.7 3.3 2.2.9 2.2.6 2.6.6a2.5 2.5 0 0 0 1.7-1.2c.2-.4.2-.8.1-.9l-.4-.2Z"/>' },
  { name: 'TikTok', url: 'https://www.tiktok.com/@aeemci', icon: '<path d="M16.2 2.5h-2.9v12.2a2.6 2.6 0 1 1-2-2.5V9.2a5.6 5.6 0 1 0 5 5.5V8.6a6.6 6.6 0 0 0 3.7 1.2V6.9a3.8 3.8 0 0 1-3.8-3.8v-.6Z"/>' }
])

/* ---------- Témoignages ---------- */
const currentTestimonial = ref(0)
const testimonials = ref([
  { text: "L'AEEMCI a changé ma vie. J'ai développé mes compétences en leadership tout en renforçant ma foi.", name: 'Aminata Koné', role: 'Ancienne membre, promotion 2018' },
  { text: "Grâce aux formations, j'ai pu concilier mes études universitaires et une solide éducation islamique.", name: 'Ibrahim Touré', role: 'Étudiant en médecine' },
  { text: "Les activités sociales m'ont permis de contribuer au bien-être de ma communauté tout en m'épanouissant.", name: 'Fatima Diallo', role: 'Bénévole active' }
])
const initials = (name) =>
  name.split(' ').filter(Boolean).slice(0, 2).map((w) => w[0]).join('').toUpperCase()

/* ---------- Agenda ---------- */
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())
const selectedEvent = ref(null)

const MONTHS = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
const currentMonthName = computed(() => MONTHS[currentMonth.value])

const calendarDates = computed(() => {
  const today = new Date()
  const firstDay = new Date(currentYear.value, currentMonth.value, 1)
  const daysInMonth = new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
  // La grille commence le lundi.
  const start = (firstDay.getDay() + 6) % 7

  const events = [
    { day: 10, title: 'Réunion du bureau', time: '14h00' },
    { day: 15, title: 'Conférence', time: '18h30' },
    { day: 22, title: 'Formation', time: '09h00' }
  ]

  const dates = []
  const prevLast = new Date(currentYear.value, currentMonth.value, 0).getDate()
  for (let i = start - 1; i >= 0; i--) {
    dates.push({ day: prevLast - i, currentMonth: false, isToday: false, hasEvent: false })
  }
  for (let i = 1; i <= daysInMonth; i++) {
    const isToday =
      i === today.getDate() &&
      currentMonth.value === today.getMonth() &&
      currentYear.value === today.getFullYear()
    const event = events.find((e) => e.day === i)
    dates.push({ day: i, currentMonth: true, isToday, hasEvent: Boolean(event), event })
  }
  const remaining = 42 - dates.length
  for (let i = 1; i <= remaining; i++) {
    dates.push({ day: i, currentMonth: false, isToday: false, hasEvent: false })
  }
  return dates
})

function changeMonth(direction) {
  let month = currentMonth.value + direction
  if (month < 0) { month = 11; currentYear.value-- }
  else if (month > 11) { month = 0; currentYear.value++ }
  currentMonth.value = month
  selectedEvent.value = null
}

function showEvent(date) {
  selectedEvent.value = date.event || null
}

/* ---------- Lettre d'information ---------- */
const newsletterEmail = ref('')
const newsletterSuccess = ref(false)
function subscribeNewsletter() {
  newsletterSuccess.value = true
  setTimeout(() => {
    newsletterEmail.value = ''
    newsletterSuccess.value = false
  }, 4000)
}
</script>

<style scoped>
.sidebar{ display:flex; flex-direction:column; gap:18px; position:sticky; top:110px; }

/* ---------- Encart générique ---------- */
.widget{ background:var(--white); border:1px solid var(--line); padding:1.4rem 1.4rem 1.5rem; }
.widget h3{
  font-family:var(--sans); font-size:.64rem; letter-spacing:.2em; text-transform:uppercase;
  color:var(--ink); font-weight:800; display:flex; align-items:center; gap:.6rem; margin-bottom:1.2rem;
}
.wdot{ width:6px; height:6px; background:var(--tone, var(--green)); transform:rotate(45deg); flex:none; }

.widget--dark{ background:var(--deep); border-color:var(--deep); color:var(--deep-fg); }
.widget--dark h3{ color:#fff; }
.widget--dark .wdot{ background:var(--deep-gold); }

/* ---------- Opportunités ---------- */
.promo{ position:relative; display:block; overflow:hidden; margin-bottom:1.2rem; }
.promo img{ width:100%; height:118px; object-fit:cover; transition:transform 1.2s var(--ease); }
.promo:hover img{ transform:scale(1.06); }
.promo-cap{
  position:absolute; left:0; right:0; bottom:0; padding:.6rem .8rem;
  background:linear-gradient(to top, rgba(5,30,20,.85), transparent);
  color:#fff; font-size:.68rem; letter-spacing:.12em; text-transform:uppercase; font-weight:700;
  display:flex; justify-content:space-between; align-items:center;
}
.promo-cap b{ color:var(--deep-gold); }

.olist{ display:flex; flex-direction:column; border-top:1px solid var(--line); }
.orow{
  display:grid; grid-template-columns:auto 1fr auto; gap:.85rem; align-items:center;
  padding:.95rem 0; border-bottom:1px solid var(--line); transition:padding-left .45s var(--ease);
}
.orow:last-child{ border-bottom:0; }
.orow:hover{ padding-left:.6rem; }
.oic{ width:20px; height:20px; color:var(--tone, var(--green)); }
.orow strong{ display:block; font-size:.85rem; font-weight:700; color:var(--ink); }
.orow small{ display:block; font-size:.75rem; color:var(--muted); line-height:1.5; margin-top:.2rem; }
.oarw{ color:var(--muted); transition:transform .45s var(--ease), color .4s; }
.orow:hover .oarw{ transform:translateX(3px); color:var(--tone, var(--green)); }

/* ---------- Hadith ---------- */
.widget--dark blockquote{ margin:0; padding-left:1rem; border-left:1px solid rgba(231,206,147,.5); }
.widget--dark blockquote p{ font-family:var(--display); font-size:1.02rem; line-height:1.65; color:#fff; }
.widget--dark blockquote cite{
  display:block; margin-top:.8rem; font-style:normal; font-size:.62rem;
  letter-spacing:.16em; text-transform:uppercase; color:var(--deep-gold); font-weight:700;
}
.hnext{
  display:inline-flex; align-items:center; gap:.5rem; margin-top:1.2rem; padding:0; border:0; background:none;
  font-size:.64rem; letter-spacing:.17em; text-transform:uppercase; font-weight:700;
  color:var(--deep-fg-2); transition:color .35s, gap .4s var(--ease);
}
.hnext:hover{ color:var(--deep-gold); gap:.95rem; }

/* ---------- Agenda ---------- */
.cal-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:.9rem; }
.cal-head strong{ font-family:var(--display); font-weight:400; font-size:1.05rem; color:var(--ink); }
.cal-head button{
  width:28px; height:28px; border:1px solid var(--line); background:transparent;
  color:var(--muted); line-height:1; transition:all .35s var(--ease);
}
.cal-head button:hover{ border-color:var(--tone, var(--green)); color:var(--tone, var(--green)); }

.cal-days,.cal-grid{ display:grid; grid-template-columns:repeat(7,1fr); gap:2px; }
.cal-days span{ text-align:center; font-size:.6rem; letter-spacing:.08em; color:var(--muted); font-weight:700; padding-bottom:.4rem; }
.cal-cell{
  aspect-ratio:1; border:0; background:transparent; font-size:.76rem; color:var(--ink-soft);
  font-variant-numeric:tabular-nums; position:relative; transition:background .3s, color .3s;
}
.cal-cell:hover{ background:var(--surface); }
.cal-cell.out{ color:var(--line-strong); }
.cal-cell.today{ background:var(--green); color:#fff; font-weight:700; }
.cal-cell.event::after{
  content:""; position:absolute; left:50%; bottom:4px; transform:translateX(-50%);
  width:4px; height:4px; background:var(--gold); border-radius:50%;
}
.cal-cell.today.event::after{ background:#fff; }
.cal-event{
  margin-top:.9rem; padding:.75rem .85rem; background:var(--surface);
  border-left:2px solid var(--tone, var(--green)); display:flex; justify-content:space-between; gap:.8rem;
}
.cal-event strong{ font-size:.82rem; color:var(--ink); }
.cal-event span{ font-size:.75rem; color:var(--muted); font-weight:600; }

/* ---------- Témoignages ---------- */
.testi{ margin:0; }
.testi p{ font-size:.88rem; line-height:1.7; color:var(--ink-soft); }
.testi figcaption{ display:flex; align-items:center; gap:.75rem; margin-top:1rem; }
.avatar{
  width:38px; height:38px; flex:none; display:grid; place-items:center;
  background:var(--tone-m, var(--green-mist)); color:var(--tone, var(--green));
  font-size:.72rem; font-weight:800; letter-spacing:.04em;
}
.testi figcaption strong{ display:block; font-size:.82rem; color:var(--ink); }
.testi figcaption small{ display:block; font-size:.72rem; color:var(--muted); }
.dots{ display:flex; gap:.4rem; margin-top:1.1rem; }
.dots button{
  width:18px; height:2px; border:0; background:var(--line-strong); padding:0;
  transition:background .35s, width .35s var(--ease);
}
.dots button.on{ background:var(--tone, var(--green)); width:28px; }
.fade-enter-active,.fade-leave-active{ transition:opacity .35s var(--ease); }
.fade-enter-from,.fade-leave-to{ opacity:0; }

/* ---------- Liens utiles ---------- */
.links{ list-style:none; margin:0; padding:0; border-top:1px solid var(--line); }
.links a{
  display:flex; justify-content:space-between; gap:.8rem; align-items:center;
  padding:.85rem 0; border-bottom:1px solid var(--line); font-size:.83rem; color:var(--ink-soft);
  transition:color .35s, padding-left .4s var(--ease);
}
.links a span{ color:var(--muted); transition:color .35s; }
.links a:hover{ color:var(--tone, var(--green)); padding-left:.5rem; }
.links a:hover span{ color:var(--tone, var(--green)); }

/* ---------- Réseaux ---------- */
.socials{ display:flex; gap:.5rem; }
.socials a{
  width:42px; height:42px; border:1px solid var(--line); display:grid; place-items:center;
  color:var(--muted); transition:all .45s var(--ease);
}
.socials a svg{ width:16px; height:16px; }
.socials a:hover{ border-color:var(--tone, var(--green)); color:var(--tone, var(--green)); transform:translateY(-3px); }

/* ---------- Lettre d'information ---------- */
.nl-text{ font-size:.84rem; color:var(--deep-fg-2); line-height:1.65; }
.nl-form{ display:flex; flex-direction:column; gap:.6rem; margin-top:1.1rem; }
.nl-form input{
  padding:.8rem .9rem; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.2);
  color:#fff; font-size:.85rem; font-family:inherit;
}
.nl-form input::placeholder{ color:rgba(255,255,255,.45); }
.nl-form input:focus{ outline:none; border-color:var(--deep-gold); }
.nl-form button{
  padding:.8rem 1rem; border:1px solid var(--deep-gold); background:var(--deep-gold); color:var(--deep);
  font-size:.74rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
  transition:background .4s, color .4s;
}
.nl-form button:hover{ background:transparent; color:var(--deep-gold); }
.nl-ok{ margin-top:.8rem; font-size:.78rem; color:var(--deep-gold); }

@media (max-width:1180px){ .sidebar{ position:static; } }
</style>
