<template>
  <div class="body-wrapper">
    <!-- Bandeau d'annonces -->
    <div class="ticker">
      <div class="ticker-in">
        <div class="ticker-tag"><span class="dot"></span> Annonces</div>
        <div class="ticker-view">
          <div class="ticker-track" :class="{ paused: isPaused }">
            <em v-for="(msg, i) in loopMessages" :key="`${i}-${msg}`">{{ msg }}</em>
          </div>
        </div>
        <button class="ticker-toggle" @click="isPaused = !isPaused" :aria-label="isPaused ? 'Reprendre' : 'Mettre en pause'">
          <svg v-if="isPaused" width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
          <svg v-else width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
        </button>
        <div class="ticker-time">{{ currentTime }}</div>
      </div>
    </div>

    <!-- Accès rapide -->
    <section class="band">
      <div class="wrap">
        <div class="sec-head">
          <div>
            <div class="eyebrow rv">Services en ligne</div>
            <h2 class="rv">Tout ce dont <em>un militant</em><br>a besoin, en un endroit.</h2>
          </div>
          <RouterLink class="link-more rv" to="/opportunites">
            Voir tous les services
            <svg width="15" height="10" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </RouterLink>
        </div>

        <div class="quick rv">
          <RouterLink v-for="(item, i) in navigationItems" :key="item.id" class="qcard" :class="item.tone" :to="item.link">
            <span class="idx">{{ String(i + 1).padStart(2, '0') }}</span>
            <span class="chip">
              <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" v-html="item.icon"></svg>
            </span>
            <h3>{{ item.name }}</h3>
            <p>{{ item.desc }}</p>
            <span class="arw">
              Accéder
              <svg width="13" height="9" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Actualités -->
    <NewsSection />

    <!-- Programmes -->
    <ProgramsSection />

    <!-- Formations, bibliothèque et colonne latérale -->
    <section class="band band--tight resources">
      <div class="wrap">
        <div class="rule rv"></div>
        <div class="content-grid">
          <div class="main-col">
            <FormationsSection />
            <LibrarySection />
          </div>
          <AppSidebar class="side-col" />
        </div>
      </div>
    </section>

    <!-- Identité -->
    <IdentitySection />

    <!-- Bandeau typographique -->
    <div class="wordband">
      <div class="wordband-track" ref="wordbandEl">
        <span>AEEMCI</span><span>Pour une identité islamique</span><span>AEEMCI</span><span>Depuis 1976</span>
        <span>AEEMCI</span><span>Pour une identité islamique</span><span>AEEMCI</span><span>Depuis 1976</span>
      </div>
    </div>

    <!-- Appel final -->
    <section class="cta">
      <div class="khatam"></div>
      <div class="wrap">
        <div class="cta-in">
          <span class="orn rv">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1">
              <rect x="5" y="5" width="14" height="14"/>
              <rect x="5" y="5" width="14" height="14" transform="rotate(45 12 12)"/>
            </svg>
          </span>
          <div class="eyebrow rv">Rejoindre l'association</div>
          <h2 class="rv">Votre place est déjà prête.</h2>
          <p class="lede rv">Adhérez en quelques minutes, recevez votre carte de membre et accédez à l'ensemble des formations, ressources et services de l'AEEMCI.</p>
          <div class="cta-actions rv">
            <RouterLink class="btn btn--solid btn--lg" to="/carte_membre"><span>Devenir membre</span></RouterLink>
            <RouterLink class="btn btn--ghost btn--lg" to="/contact"><span>Nous contacter</span></RouterLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { RouterLink } from 'vue-router'

import NewsSection from '../accueil/NewsSection.vue'
import ProgramsSection from '../accueil/ProgramsSection.vue'
import FormationsSection from '../accueil/FormationsSection.vue'
import LibrarySection from '../accueil/LibrarySection.vue'
import AppSidebar from '../accueil/AppSidebar.vue'
import IdentitySection from '../accueil/IdentitySection.vue'
import { prefersReducedMotion } from '../../composables/useReveal'

/* ---------- Bandeau d'annonces ---------- */
const isPaused = ref(false)
const messages = ref([
  'Inscriptions SENAFOI 2026 ouvertes',
  'AEEMCI, pour une identité islamique',
  "50 ans d'excellence et d'engagement",
  'Retrait des cartes de membre dans tous les secrétariats régionaux'
])
const loopMessages = computed(() => [...messages.value, ...messages.value])

/* ---------- Heure ---------- */
const currentTime = ref('')
let timeInterval = null
function updateTime() {
  currentTime.value = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

/* ---------- Accès rapide ---------- */
const navigationItems = ref([
  {
    id: 'carte_membre', name: 'Carte de membre', link: '/carte_membre', tone: 'tone-green',
    desc: "Demander, renouveler ou vérifier l'authenticité d'une carte.",
    icon: '<rect x="2" y="5" width="20" height="14" rx="1"/><circle cx="8" cy="11" r="2.2"/><path d="M4.5 16.5c.7-1.6 2-2.4 3.5-2.4s2.8.8 3.5 2.4M14.5 10h5M14.5 13.5h5"/>'
  },
  {
    id: 'espace_organe', name: 'Espace organes', link: '/organes', tone: 'tone-teal',
    desc: 'Secrétariats régionaux, sections et sous-comités.',
    icon: '<path d="M3 20.5h18M5 20.5V9.5l7-5 7 5v11"/><path d="M9.5 20.5v-6h5v6"/>'
  },
  {
    id: 'bachelier', name: 'Nouveaux bacheliers', link: '/bachelier', tone: 'tone-gold',
    desc: "Accompagnement vers l'enseignement supérieur.",
    icon: '<path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M5.5 10v5.2c0 1.6 2.9 3.3 6.5 3.3s6.5-1.7 6.5-3.3V10"/><path d="M22 8v6"/>'
  },
  {
    id: 'stages', name: 'Stage · Emploi · Bourse', link: '/opportunites', tone: 'tone-clay',
    desc: 'Les opportunités relayées par le réseau des anciens.',
    icon: '<rect x="2.5" y="7" width="19" height="13" rx="1"/><path d="M8.5 7V5.5a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2V7M2.5 12.5h19"/>'
  }
])

/* ---------- Bandeau typographique piloté par le défilement ---------- */
const wordbandEl = ref(null)
let raf = null
let offset = 0
let lastY = 0

function wordbandTick() {
  const el = wordbandEl.value
  if (el) {
    const y = window.scrollY || window.pageYOffset
    offset -= (y - lastY) * 0.55 + 0.35
    lastY = y
    const half = el.scrollWidth / 2
    if (half > 0) {
      while (offset <= -half) offset += half
      while (offset > 0) offset -= half
    }
    el.style.transform = `translateX(${offset}px)`
  }
  raf = requestAnimationFrame(wordbandTick)
}

onMounted(() => {
  updateTime()
  timeInterval = setInterval(updateTime, 30000)
  if (!prefersReducedMotion()) {
    lastY = window.scrollY || 0
    raf = requestAnimationFrame(wordbandTick)
  }
})

onUnmounted(() => {
  if (timeInterval) clearInterval(timeInterval)
  if (raf) cancelAnimationFrame(raf)
})
</script>

<style scoped>
.body-wrapper{ background:var(--white); }

/* ---------- Bandeau d'annonces ---------- */
.ticker{ border-bottom:1px solid var(--line); background:var(--white); overflow:hidden; }
.ticker-in{ display:flex; align-items:stretch; }
.ticker-tag{
  flex:none; display:flex; align-items:center; gap:.6rem; padding:0 1.6rem;
  background:var(--green); color:#fff; font-size:.64rem; letter-spacing:.2em; text-transform:uppercase; font-weight:700;
}
.ticker-tag .dot{ width:6px; height:6px; border-radius:50%; background:var(--deep-gold); }
.ticker-view{ flex:1; overflow:hidden; position:relative; }
.ticker-view::before,.ticker-view::after{ content:""; position:absolute; top:0; bottom:0; width:70px; z-index:2; pointer-events:none; }
.ticker-view::before{ left:0; background:linear-gradient(to right,var(--white),transparent); }
.ticker-view::after{ right:0; background:linear-gradient(to left,var(--white),transparent); }
.ticker-track{ display:flex; width:max-content; animation:slide 52s linear infinite; }
.ticker-track.paused,
.ticker-view:hover .ticker-track{ animation-play-state:paused; }
@keyframes slide{ from{ transform:translateX(0); } to{ transform:translateX(-50%); } }
.ticker-track em{
  font-style:normal; display:flex; align-items:center; gap:1.1rem; padding:1rem 2.1rem;
  font-size:.82rem; color:var(--ink-soft); white-space:nowrap; font-weight:500;
}
.ticker-track em::before{ content:""; width:4px; height:4px; background:var(--gold); flex:none; transform:rotate(45deg); }
.ticker-toggle{
  flex:none; width:46px; border:0; border-left:1px solid var(--line); background:transparent;
  color:var(--muted); display:grid; place-items:center; transition:color .3s, background .3s;
}
.ticker-toggle:hover{ color:var(--green); background:var(--surface); }
.ticker-time{
  flex:none; display:flex; align-items:center; padding:0 1.2rem; border-left:1px solid var(--line);
  font-size:.72rem; letter-spacing:.1em; color:var(--muted); font-weight:700; font-variant-numeric:tabular-nums;
}

/* ---------- Accès rapide ---------- */
.quick{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
.qcard{
  position:relative; padding:2rem 1.8rem 1.9rem; overflow:hidden; display:block;
  background:var(--white); border:1px solid var(--line);
  transition:border-color .5s var(--ease), transform .5s var(--ease);
}
/* Barre de teinte permanente : la carte est vivante au repos, pas seulement au survol. */
.qcard::before{
  content:""; position:absolute; left:0; right:0; top:0; height:3px;
  background:var(--tone, var(--green)); opacity:.85; z-index:2;
}
/* Lavis teinté qui monte au survol */
.qcard::after{
  content:""; position:absolute; inset:0; background:var(--tone-m, var(--green-mist));
  transform:translateY(100%); transition:transform .6s var(--ease); z-index:0;
}
.qcard:hover{ border-color:var(--tone, var(--green)); transform:translateY(-4px); }
.qcard:hover::after{ transform:none; }
.qcard > *{ position:relative; z-index:1; }

.chip{
  display:grid; place-items:center; width:46px; height:46px;
  background:var(--tone-m, var(--green-mist)); color:var(--tone, var(--green));
  transition:background .5s var(--ease), transform .6s var(--ease);
}
.qcard:hover .chip{ background:var(--tone, var(--green)); color:#fff; transform:translateY(-3px); }
.chip .ic{ width:23px; height:23px; }

.qcard h3{ font-family:var(--sans); font-size:1.02rem; font-weight:700; margin-top:1.3rem; color:var(--ink); }
.qcard p{ font-size:.82rem; color:var(--muted); margin-top:.45rem; line-height:1.6; }
.qcard .arw{
  margin-top:1.5rem; display:flex; align-items:center; gap:.5rem; font-size:.66rem; letter-spacing:.17em;
  text-transform:uppercase; font-weight:700; color:var(--tone, var(--green)); transition:gap .4s var(--ease);
}
.qcard:hover .arw{ gap:1rem; }
.qcard .idx{
  position:absolute; top:1.5rem; right:1.6rem; font-family:var(--display); font-size:1.5rem;
  color:var(--tone, var(--green)); opacity:.28; z-index:1; line-height:1;
  transition:opacity .5s var(--ease);
}
.qcard:hover .idx{ opacity:.6; }

.sec-head h2 em{ font-style:normal; color:var(--green); }

/* ---------- Contenu + colonne latérale ---------- */
.resources{ padding-top:0; }
.resources > .wrap > .rule{ margin-bottom:var(--section); }
.content-grid{
  display:grid; grid-template-columns:minmax(0,1fr) 340px;
  gap:clamp(28px,3.4vw,56px); align-items:start;
}
.main-col{ min-width:0; }
/* Les sections imbriquées perdent leur respiration propre : le parent la porte. */
.content-grid :deep(.band){ padding:0; }
.content-grid :deep(.rule){ display:none; }
.content-grid :deep(.fgrid){ grid-template-columns:repeat(3,1fr); }
.content-grid :deep(.lib-band){
  margin-top:var(--section); padding:clamp(26px,3vw,44px);
  border:1px solid var(--line); background:var(--surface);
}
.content-grid :deep(.lib-grid){ grid-template-columns:1fr; }
.content-grid :deep(.sec-head){ margin-bottom:2.6rem; }

/* ---------- Bandeau typographique ---------- */
.wordband{ padding:clamp(40px,5vw,72px) 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line); overflow:hidden; }
.wordband-track{ display:flex; width:max-content; will-change:transform; }
.wordband span{
  font-family:var(--display); font-size:clamp(2.4rem,6vw,5rem); line-height:1; white-space:nowrap;
  padding-right:.6em; color:transparent; -webkit-text-stroke:1px var(--line-strong); letter-spacing:.01em;
}
.wordband span:nth-child(even){ color:var(--green); -webkit-text-stroke:0; }

/* ---------- Appel final ---------- */
.cta{ padding:var(--section) 0; background:var(--white); position:relative; overflow:hidden; }
.cta .khatam{ opacity:.45; }
.cta-in{ position:relative; z-index:1; text-align:center; display:flex; flex-direction:column; align-items:center; }
.cta .orn{ color:var(--gold); }
.cta .eyebrow{ justify-content:center; margin-top:1.7rem; }
.cta h2{ font-size:clamp(2.1rem,4.8vw,3.8rem); margin-top:1.4rem; letter-spacing:-.02em; }
.cta .lede{ margin-top:1.3rem; text-align:center; }
.cta-actions{ display:flex; gap:.9rem; margin-top:2.5rem; flex-wrap:wrap; justify-content:center; }

@media (max-width:1180px){
  .quick{ grid-template-columns:repeat(2,1fr); }
  .content-grid{ grid-template-columns:1fr; }
  .content-grid :deep(.fgrid){ grid-template-columns:repeat(2,1fr); }
  .side-col{ margin-top:var(--section); }
}
@media (max-width:900px){ .ticker-tag{ padding:0 1rem; } .ticker-time{ display:none; } }
@media (max-width:640px){
  .quick{ grid-template-columns:1fr; }
  .content-grid :deep(.fgrid){ grid-template-columns:1fr; }
  .cta-actions{ width:100%; }
  .cta-actions .btn{ width:100%; white-space:normal; text-align:center; line-height:1.35; }
}
</style>
