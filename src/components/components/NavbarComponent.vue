<template>
  <!-- Barre utilitaire -->
  <div class="util">
    <div class="wrap">
      <div class="util-l"><span class="dot-live"></span> Abidjan · Cinquante ans au service de l'élite musulmane</div>
      <div class="util-r">
        <a href="https://email.aeemci-ce.ci/" target="_blank" rel="noopener">Webmail</a>
        <a href="http://militant.aeemci-ce.ci/">Espace militant</a>
        <RouterLink to="/verification_carte">Vérifier une carte</RouterLink>
      </div>
    </div>
  </div>

  <!-- En-tête -->
  <header class="nav" :class="{ 'on-hero': onHero, stuck: isStuck }">
    <div class="wrap">
      <RouterLink class="brand" to="/">
        <span class="brand-mark"><img :src="logo" alt="Logo AEEMCI"></span>
        <span class="brand-txt">
          <strong>AEEMCI</strong>
          <span>Élèves &amp; Étudiants Musulmans de Côte d'Ivoire</span>
        </span>
      </RouterLink>

      <ul class="menu" :class="{ open: mobileOpen }" id="menu">
        <li><RouterLink class="top" to="/" @click="closeMobile">Accueil</RouterLink></li>

        <li v-for="group in groups" :key="group.id" :class="{ expand: expanded === group.id }">
          <a class="top" href="#" @click="toggleGroup(group.id, $event)">
            {{ group.label }}
            <svg class="chev" width="10" height="10" viewBox="0 0 12 12" fill="none" aria-hidden="true">
              <path d="M2 4.5 6 8.5 10 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
          </a>
          <div class="drop">
            <component
              v-for="(item, i) in group.items"
              :key="item.label"
              :is="item.external ? 'a' : RouterLink"
              v-bind="item.external ? { href: item.to, target: '_blank', rel: 'noopener' } : { to: item.to }"
              @click="closeMobile"
            >
              <i>{{ String(i + 1).padStart(2, '0') }}</i><span>{{ item.label }}</span>
            </component>
          </div>
        </li>

        <li><RouterLink class="top" to="/contact" @click="closeMobile">Contact</RouterLink></li>

        <li class="menu-actions">
          <RouterLink class="btn btn--gold btn--lg" to="/don" @click="closeMobile"><span>Faire un don</span></RouterLink>
          <a class="btn btn--solid btn--lg" href="http://militant.aeemci-ce.ci/"><span>Espace militant</span></a>
        </li>
      </ul>

      <div class="nav-cta">
        <RouterLink class="btn" :class="onHero ? 'btn--ghost-light' : 'btn--gold'" to="/don"><span>Faire un don</span></RouterLink>
        <a class="btn" :class="onHero ? 'btn--light' : 'btn--solid'" href="http://militant.aeemci-ce.ci/"><span>Espace militant</span></a>
        <button
          class="burger" :class="{ on: mobileOpen }" @click="toggleMobile"
          :aria-expanded="mobileOpen ? 'true' : 'false'" aria-label="Ouvrir le menu"
        ><i></i><i></i><i></i></button>
      </div>
    </div>
    <div class="progress" :style="{ width: progress + '%' }"></div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import logo from '../../assets/logos/aeemci.jpg'

const route = useRoute()

const mobileOpen = ref(false)
const expanded = ref(null)
const isStuck = ref(false)
const onHero = ref(true)
const progress = ref(0)

const groups = [
  {
    id: 'assoc',
    label: "L'Association",
    items: [
      { label: 'Charte de l\'association', to: '/charte' },
      { label: 'Historique', to: '/historique' },
      { label: 'Organigramme', to: '/organigramme' },
      { label: 'Comité exécutif', to: '/bureau' },
      { label: 'Cellules spécialisées', to: '/cellules' },
      { label: 'Formations', to: '/formations' }
    ]
  },
  {
    id: 'media',
    label: 'Médiathèque',
    items: [
      { label: 'AEEMCI TV', to: '/tv' },
      { label: 'Photothèque', to: '/images' },
      { label: 'Vidéos', to: '/senafoi_video' }
    ]
  },
  {
    id: 'inscriptions',
    label: 'Inscriptions',
    items: [
      { label: 'SENAFOI', to: 'https://senafoi.aeemci-ce.ci/', external: true },
      { label: 'CIMA', to: 'http://militant.aeemci-ce.ci/cima', external: true },
      { label: 'Carte de membre', to: '/carte_membre' },
      { label: 'Vérification de carte', to: '/verification_carte' },
      { label: 'Webmail AEEMCI', to: 'https://email.aeemci-ce.ci/', external: true }
    ]
  }
]

function toggleMobile() {
  mobileOpen.value = !mobileOpen.value
  document.body.style.overflow = mobileOpen.value ? 'hidden' : ''
  if (!mobileOpen.value) expanded.value = null
}

function closeMobile() {
  mobileOpen.value = false
  expanded.value = null
  document.body.style.overflow = ''
}

function toggleGroup(id, event) {
  // Sur mobile, le premier niveau déplie le sous-menu au lieu de naviguer.
  if (window.innerWidth <= 900) {
    event.preventDefault()
    expanded.value = expanded.value === id ? null : id
  } else {
    event.preventDefault()
  }
}

let ticking = false
function onScroll() {
  if (ticking) return
  ticking = true
  requestAnimationFrame(() => {
    const y = window.scrollY || window.pageYOffset
    const hero = document.querySelector('.hero--home')
    isStuck.value = y > 40
    onHero.value = Boolean(hero) && y < hero.offsetHeight - 90
    const h = document.documentElement.scrollHeight - window.innerHeight
    progress.value = h > 0 ? (y / h) * 100 : 0
    ticking = false
  })
}

// Une page sans héros doit afficher l'en-tête clair dès le départ.
watch(() => route.fullPath, () => {
  closeMobile()
  requestAnimationFrame(onScroll)
})

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
})
onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  document.body.style.overflow = ''
})
</script>

<style scoped>
/* ---------- Barre utilitaire ---------- */
.util{ background:var(--deep); color:var(--deep-fg); font-size:.72rem; letter-spacing:.06em; position:relative; z-index:60; }
.util .wrap{ display:flex; justify-content:space-between; align-items:center; gap:1rem; height:38px; white-space:nowrap; }
.util-l{ display:flex; align-items:center; gap:.6rem; opacity:.9; overflow:hidden; text-overflow:ellipsis; }
.util-r{ display:flex; align-items:center; gap:1.7rem; }
.util-r a{ opacity:.9; transition:opacity .3s, color .3s; }
.util-r a:hover{ opacity:1; color:var(--deep-gold); }
.dot-live{ width:6px; height:6px; border-radius:50%; background:#5FD98F; flex:none; animation:ping 2.8s var(--ease) infinite; }
@keyframes ping{
  0%{ box-shadow:0 0 0 0 rgba(95,217,143,.5); }
  70%{ box-shadow:0 0 0 9px rgba(95,217,143,0); }
  100%{ box-shadow:0 0 0 0 rgba(95,217,143,0); }
}

/* ---------- En-tête ---------- */
.nav{
  position:fixed; top:38px; left:0; right:0; z-index:70;
  border-bottom:1px solid transparent;
  transition:background .55s var(--ease-soft), border-color .55s, top .4s var(--ease);
}
.nav .wrap{ display:flex; align-items:center; justify-content:space-between; gap:1.4rem; height:92px; transition:height .5s var(--ease); }

/* Au-dessus du héros : transparent, texte clair */
.nav.on-hero .brand-txt strong,
.nav.on-hero .menu a.top{ color:#fff; }
.nav.on-hero .brand-txt span{ color:rgba(255,255,255,.62); }
.nav.on-hero .brand-mark{ border-color:rgba(255,255,255,.25); background:rgba(255,255,255,.92); }
.nav.on-hero .burger{ border-color:rgba(255,255,255,.35); }
.nav.on-hero .burger i{ background:#fff; }

/* Décollée : fond blanc translucide */
.nav.stuck{
  top:0; background:color-mix(in srgb, var(--white) 90%, transparent);
  backdrop-filter:saturate(180%) blur(20px); -webkit-backdrop-filter:saturate(180%) blur(20px);
  border-bottom-color:var(--line);
}
.nav.stuck .wrap{ height:70px; }

.brand{ display:flex; align-items:center; gap:.85rem; }
.brand-mark{ width:46px; height:46px; overflow:hidden; flex:none; border:1px solid var(--line); background:var(--white); transition:all .5s var(--ease); }
.nav.stuck .brand-mark{ width:38px; height:38px; }
.brand-mark img{ width:100%; height:100%; object-fit:contain; }
.brand-txt strong{ display:block; font-family:var(--display); font-size:1.3rem; letter-spacing:.13em; line-height:1; color:var(--ink); font-weight:400; transition:color .4s; }
.brand-txt span{ display:block; font-size:.58rem; letter-spacing:.15em; text-transform:uppercase; color:var(--muted); margin-top:.34rem; font-weight:600; white-space:nowrap; transition:color .4s; }
@media (max-width:1280px){ .brand-txt span{ display:none; } }

.menu{ display:flex; align-items:center; gap:.1rem; margin:0; padding:0; list-style:none; }
.menu > li{ position:relative; }
.menu a.top{ display:flex; align-items:center; gap:.38rem; padding:.7rem .95rem; font-size:.86rem; font-weight:600; color:var(--ink-soft); position:relative; transition:color .3s; }
.menu a.top .chev{ opacity:.6; transition:transform .38s var(--ease); }
.menu > li:hover a.top .chev{ transform:rotate(180deg); }
.menu a.top::after{
  content:""; position:absolute; left:.95rem; right:.95rem; bottom:.44rem; height:1px;
  background:currentColor; opacity:.55; transform:scaleX(0); transform-origin:right;
  transition:transform .45s var(--ease);
}
.menu a.top:hover::after{ transform:scaleX(1); transform-origin:left; }

.drop{
  position:absolute; top:calc(100% + 10px); left:-.4rem; min-width:262px;
  background:var(--white); border:1px solid var(--line); color:var(--ink);
  box-shadow: var(--shadow);
  padding:.5rem; opacity:0; visibility:hidden; transform:translateY(-10px);
  transition:all .4s var(--ease);
}
.menu > li:hover .drop, .menu > li:focus-within .drop{ opacity:1; visibility:visible; transform:none; }
.drop :deep(a){ display:flex; align-items:baseline; gap:.75rem; padding:.65rem .85rem; font-size:.85rem; color:var(--ink-soft); font-weight:500; transition:all .3s var(--ease); }
.drop :deep(a i){ font-style:normal; font-size:.6rem; color:var(--gold); font-weight:700; min-width:20px; }
.drop :deep(a:hover){ background:var(--surface); color:var(--green); padding-left:1.1rem; }

.nav-cta{ display:flex; align-items:center; gap:.6rem; }
.menu-actions{ display:none; }

.burger{ display:none; width:44px; height:44px; border:1px solid var(--line); background:transparent; flex-direction:column; gap:5px; align-items:center; justify-content:center; }
.burger i{ display:block; width:19px; height:1.5px; background:var(--ink); transition:transform .4s var(--ease), opacity .3s; }
.burger.on i:nth-child(1){ transform:translateY(6.5px) rotate(45deg); }
.burger.on i:nth-child(2){ opacity:0; }
.burger.on i:nth-child(3){ transform:translateY(-6.5px) rotate(-45deg); }

.progress{ position:absolute; left:0; bottom:-1px; height:1.5px; background:var(--gold); z-index:2; }

@media (max-width:900px){
  .menu, .nav-cta .btn{ display:none; }
  .burger{ display:flex; }
  .menu.open{
    display:flex; position:fixed; inset:calc(38px + 70px) 0 0; background:var(--white); color:var(--ink);
    flex-direction:column; align-items:stretch; gap:0; padding:1rem var(--gut) 3rem; overflow-y:auto; z-index:90;
  }
  .menu.open > li{ border-bottom:1px solid var(--line); }
  .menu.open a.top{ padding:1.2rem .2rem; font-size:1rem; justify-content:space-between; color:var(--ink-soft) !important; }
  .menu.open .drop{
    position:static; opacity:1; visibility:visible; transform:none; border:0; box-shadow:none;
    background:var(--surface); display:none; min-width:0; margin-bottom:.8rem;
  }
  .menu.open > li.expand .drop{ display:block; }
  .menu.open .menu-actions{ display:flex; flex-direction:column; gap:.8rem; border:0; margin-top:1.8rem; }
  .menu.open .menu-actions .btn{ width:100%; }
}

@media (max-width:640px){
  .util-l{ display:none; }
  .util .wrap{ justify-content:flex-end; }
  .util-r{ gap:1.1rem; }
}
</style>
