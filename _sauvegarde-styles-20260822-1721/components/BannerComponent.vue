<template>
  <!-- Fenêtre d'annonce -->
  <transition name="popup">
    <div v-if="showPopup" class="popup-overlay" @click="closePopup">
      <div class="popup-container" @click.stop>
        <button class="popup-close" @click="closePopup" aria-label="Fermer">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
        <RouterLink to="/seminaristes" @click="closePopup">
          <img :src="senafoiPopupImage" alt="Inscription SENAFOI 2026" class="popup-image">
        </RouterLink>
      </div>
    </div>
  </transition>

  <!-- Héros -->
  <section class="hero hero--home" ref="heroEl">
    <div class="parallax" ref="parallaxEl">
      <div class="slides">
        <div
          v-for="(slide, i) in slides"
          :key="slide.label"
          class="slide"
          :class="{ on: i === current }"
          :style="{ backgroundImage: `url(${slide.image})` }"
        ></div>
      </div>
    </div>
    <div class="veil"></div>
    <div class="khatam"></div>
    <div class="grain"></div>

    <div class="hero-body">
      <div class="wrap">
        <div class="hero-grid">
          <div>
            <div class="eyebrow rv">Plateforme digitale officielle · Depuis 1976</div>

            <h1>
              <span class="mask"><span>Former une élite</span></span>
              <span class="mask"><span><em>musulmane</em> consciente,</span></span>
              <span class="mask"><span class="out">génération après</span></span>
              <span class="mask"><span class="out">génération.</span></span>
            </h1>

            <blockquote class="hero-quote rv">
              <p>L'AEEMCI contribue à la formation religieuse et managériale des élèves et étudiants musulmans, pour l'émergence d'une élite consciente des valeurs républicaines et de sa responsabilité communautaire.</p>
              <cite>Charte de l'AEEMCI</cite>
            </blockquote>

            <div class="hero-actions rv">
              <RouterLink class="btn btn--light btn--lg" to="/carte_membre"><span>Demander une carte de membre</span></RouterLink>
              <RouterLink class="btn btn--ghost-light btn--lg" to="/mes_documents"><span>Diplômes &amp; bulletins SENAFOI</span></RouterLink>
            </div>
          </div>

          <div class="slidenav rv">
            <button
              v-for="(slide, i) in slides" :key="slide.label" type="button"
              :aria-current="i === current ? 'true' : 'false'"
              @click="select(i)"
            >
              <span class="bar"></span> {{ slide.label }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Chiffres de l'association -->
    <div class="figures" ref="figuresEl">
      <div class="wrap">
        <div class="figures-grid">
          <div v-for="fig in figures" :key="fig.label" class="fig" :class="{ key: fig.key }">
            <b class="num" :data-to="fig.value" :data-suf="fig.suffix || ''" :data-sep="fig.sep ? '1' : ''">0</b>
            <small>{{ fig.label }}</small>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink } from 'vue-router'
import { observeCounters, prefersReducedMotion } from '../../composables/useReveal'

import senafoiPopupImage from '../../assets/senafoi-popup-2026.jpeg'
import imgDouas from '../../assets/douas.jpg'
import imgConference from '../../assets/conference.jpg'
import imgMarche from '../../assets/marche_aeemci.jpg'
import imgJardin from '../../assets/jardin.jpg'

const POPUP_KEY = 'aeemci_senafoi_popup_2026_shown'
const SLIDE_MS = 7000

const slides = [
  { label: 'Doua collectif', image: imgDouas },
  { label: 'SENAFOI', image: imgConference },
  { label: 'Marche citoyenne', image: imgMarche },
  { label: 'Écoles de vacances', image: imgJardin }
]

const figures = [
  { value: 50, label: "Ans d'existence", key: true },
  { value: 30000, suffix: '+', sep: true, label: 'Membres actifs' },
  { value: 1000, suffix: '+', sep: true, label: 'Sections' },
  { value: 500, suffix: '+', label: 'Sous-comités' },
  { value: 34, label: 'Secrétariats régionaux' },
  { value: 100, suffix: '+', label: 'Actions sociales' }
]

const current = ref(0)
const showPopup = ref(false)
const heroEl = ref(null)
const parallaxEl = ref(null)
const figuresEl = ref(null)

let timer = null
let stopCounters = null

function play() {
  clearInterval(timer)
  if (prefersReducedMotion()) return
  timer = setInterval(() => { current.value = (current.value + 1) % slides.length }, SLIDE_MS)
}

function select(i) {
  current.value = i
  play()
}

function closePopup() {
  showPopup.value = false
  try { localStorage.setItem(POPUP_KEY, 'true') } catch (e) { /* stockage indisponible */ }
}

function onMouseMove(e) {
  if (!heroEl.value || !parallaxEl.value) return
  const r = heroEl.value.getBoundingClientRect()
  const x = (e.clientX - r.left) / r.width - 0.5
  const y = (e.clientY - r.top) / r.height - 0.5
  parallaxEl.value.style.transform = `translate3d(${-x * 26}px, ${-y * 20}px, 0)`
}
function onMouseLeave() {
  if (parallaxEl.value) parallaxEl.value.style.transform = ''
}

onMounted(() => {
  play()

  let seen = true
  try { seen = Boolean(localStorage.getItem(POPUP_KEY)) } catch (e) { seen = true }
  if (!seen) setTimeout(() => { showPopup.value = true }, 1200)

  if (figuresEl.value) stopCounters = observeCounters(figuresEl.value)

  const fine = window.matchMedia && window.matchMedia('(pointer:fine)').matches
  if (heroEl.value && fine && !prefersReducedMotion()) {
    heroEl.value.addEventListener('mousemove', onMouseMove)
    heroEl.value.addEventListener('mouseleave', onMouseLeave)
  }
})

onUnmounted(() => {
  clearInterval(timer)
  if (stopCounters) stopCounters()
  if (heroEl.value) {
    heroEl.value.removeEventListener('mousemove', onMouseMove)
    heroEl.value.removeEventListener('mouseleave', onMouseLeave)
  }
})
</script>

<style scoped>
/* ---------- Fenêtre d'annonce ---------- */
.popup-overlay{
  position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center;
  background:rgba(5,30,20,.78); backdrop-filter:blur(10px); padding:1.5rem;
}
.popup-container{ position:relative; max-width:min(92vw, 640px); max-height:90vh; background:var(--white); overflow:hidden; box-shadow:0 40px 90px -40px rgba(0,0,0,.6); }
.popup-image{ width:100%; height:auto; max-height:90vh; object-fit:contain; }
.popup-close{
  position:absolute; top:14px; right:14px; width:42px; height:42px; z-index:10;
  background:rgba(255,255,255,.92); border:1px solid var(--line); color:var(--ink);
  display:grid; place-items:center; transition:all .4s var(--ease);
}
.popup-close:hover{ background:var(--deep); color:#fff; border-color:var(--deep); transform:rotate(90deg); }
.popup-enter-active,.popup-leave-active{ transition:opacity .35s var(--ease); }
.popup-enter-from,.popup-leave-to{ opacity:0; }
.popup-enter-active .popup-container{ animation:popIn .5s var(--ease); }
@keyframes popIn{ from{ opacity:0; transform:translateY(-24px) scale(.96); } to{ opacity:1; transform:none; } }

/* ---------- Héros ---------- */
.hero{
  position:relative; min-height:100svh; display:flex; flex-direction:column; justify-content:flex-end;
  overflow:hidden; background:var(--deep); color:#fff; padding-top:150px;
}
.parallax{ position:absolute; inset:-4%; z-index:0; transition:transform 1.2s var(--ease-soft); }
.slides{ position:absolute; inset:0; }
.slide{
  position:absolute; inset:0; background-size:cover; background-position:center;
  opacity:0; transform:scale(1.05); transition:opacity 2s var(--ease-soft); will-change:opacity, transform;
}
.slide.on{ opacity:1; animation:kenburns 11s linear forwards; }
@keyframes kenburns{
  from{ transform:scale(1.04) translate3d(0,0,0); }
  to{ transform:scale(1.17) translate3d(-1.4%,-1.2%,0); }
}
.veil{
  position:absolute; inset:0; z-index:2;
  background:
    linear-gradient(100deg, rgba(5,30,20,.95) 0%, rgba(5,30,20,.88) 42%, rgba(5,30,20,.5) 74%, rgba(5,30,20,.6) 100%),
    linear-gradient(to top, rgba(5,30,20,.96) 0%, rgba(5,30,20,0) 42%);
}
.hero .khatam{ z-index:3; opacity:.35; }

.hero-body{ position:relative; z-index:6; padding-bottom:clamp(30px,4vw,56px); }
.hero-grid{ display:grid; grid-template-columns:minmax(0,1fr) auto; gap:2rem; align-items:end; }
.hero .eyebrow{ color:rgba(255,255,255,.7); }
.hero .eyebrow::before{ background:var(--deep-gold); }

.hero h1{ font-size:clamp(2.6rem,6.4vw,5.5rem); line-height:1.02; letter-spacing:-.022em; margin:1.7rem 0 0; color:#fff; }
.hero h1 em{ font-style:normal; color:var(--deep-gold); }
.hero h1 .out{ color:transparent; -webkit-text-stroke:1.1px rgba(255,255,255,.62); }

.hero-quote{ margin-top:2rem; padding-left:1.6rem; border-left:1px solid rgba(231,206,147,.55); max-width:50ch; }
.hero-quote p{ font-size:1rem; line-height:1.8; color:rgba(255,255,255,.86); margin:0; }
.hero-quote cite{ display:block; margin-top:.85rem; font-style:normal; font-size:.66rem; letter-spacing:.22em; text-transform:uppercase; color:var(--deep-gold); font-weight:700; }
.hero-actions{ display:flex; flex-wrap:wrap; gap:.8rem; margin-top:2.3rem; }

.slidenav{ display:flex; flex-direction:column; gap:.9rem; align-items:flex-end; }
.slidenav button{
  display:flex; align-items:center; gap:.9rem; background:none; border:0; padding:0;
  color:rgba(255,255,255,.55); font-size:.64rem; letter-spacing:.18em; text-transform:uppercase; font-weight:700;
  transition:color .4s;
}
.slidenav button:hover,
.slidenav button[aria-current="true"]{ color:#fff; }
.slidenav .bar{ width:56px; height:1px; background:rgba(255,255,255,.28); position:relative; overflow:hidden; flex:none; }
.slidenav .bar::after{ content:""; position:absolute; inset:0; background:var(--deep-gold); transform:scaleX(0); transform-origin:left; }
.slidenav button[aria-current="true"] .bar::after{ animation:fill 7s linear forwards; }
@keyframes fill{ from{ transform:scaleX(0); } to{ transform:scaleX(1); } }

/* ---------- Chiffres ---------- */
.figures{ position:relative; z-index:6; border-top:1px solid rgba(255,255,255,.16); }
.figures-grid{ display:grid; grid-template-columns:repeat(6,1fr); }
.fig{ padding:1.7rem .6rem 2rem; border-right:1px solid rgba(255,255,255,.13); position:relative; overflow:hidden; }
.fig:last-child{ border-right:0; }
.fig::before{
  content:""; position:absolute; top:0; left:0; right:0; height:1px; background:var(--deep-gold);
  transform:scaleX(0); transform-origin:left; transition:transform .8s var(--ease);
}
.fig:hover::before{ transform:scaleX(1); }
.fig b{ display:block; font-family:var(--display); font-weight:400; font-size:clamp(1.75rem,2.7vw,2.5rem); line-height:1; color:#fff; letter-spacing:-.02em; }
.fig b :deep(.suf){ color:var(--deep-gold); }
.fig small{ display:block; margin-top:.65rem; font-size:.66rem; letter-spacing:.14em; text-transform:uppercase; color:rgba(255,255,255,.62); font-weight:600; }
.fig.key b{ color:var(--deep-gold); }

/* ---------- Responsive ---------- */
@media (max-width:1180px){
  .hero-grid{ grid-template-columns:1fr; }
  .slidenav{ flex-direction:row; align-items:center; gap:1.4rem; margin-top:2.2rem; flex-wrap:wrap; }
  .slidenav .bar{ width:40px; }
  .figures-grid{ grid-template-columns:repeat(3,1fr); }
  .fig:nth-child(3n){ border-right:0; }
  .fig:nth-child(-n+3){ border-bottom:1px solid rgba(255,255,255,.13); }
}
@media (max-width:640px){
  .hero{ padding-top:116px; }
  .hero h1{ font-size:clamp(2.25rem,9vw,3rem); margin-top:1.3rem; }
  .hero-quote{ margin-top:1.5rem; padding-left:1.1rem; }
  .hero-quote p{ font-size:.9rem; line-height:1.7; }
  .hero-actions{ margin-top:1.7rem; width:100%; }
  .hero-actions .btn{ width:100%; white-space:normal; text-align:center; line-height:1.35; }
  .slidenav{ margin-top:1.8rem; gap:1rem .9rem; }
  .slidenav button{ font-size:.58rem; letter-spacing:.14em; }
  .slidenav .bar{ width:26px; }
  .figures-grid{ grid-template-columns:repeat(2,1fr); }
  .fig{ padding:1.3rem .5rem 1.5rem; border-right:1px solid rgba(255,255,255,.13); border-bottom:1px solid rgba(255,255,255,.13); }
  .fig:nth-child(2n){ border-right:0; }
}
</style>
