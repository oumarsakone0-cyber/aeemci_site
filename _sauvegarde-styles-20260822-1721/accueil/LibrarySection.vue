<template>
  <section class="band lib-band">
    <div class="khatam"></div>
    <div class="wrap">
      <div class="sec-head">
        <div>
          <div class="eyebrow rv">Bibliothèque</div>
          <h2 class="rv">1 200 ressources,<br>ouvertes à tous les militants.</h2>
          <p class="lede rv">
            Ouvrages, revues et supports numériques sur l'islam, l'éducation
            et le développement personnel, consultables sur place ou en ligne.
          </p>
        </div>
        <RouterLink class="link-more rv" to="/formations">
          Accéder au catalogue
          <svg width="15" height="10" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </RouterLink>
      </div>

      <div class="lib-grid">
        <!-- Chiffres + horaires -->
        <div class="lib-side">
          <div class="lstats" ref="statsEl">
            <div v-for="stat in stats" :key="stat.id" class="lstat tile rv" :class="stat.tone">
              <svg class="lic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" v-html="stat.icon"></svg>
              <b class="num" :data-to="stat.count" data-suf="+" :data-sep="stat.count >= 1000 ? '1' : ''">0</b>
              <small>{{ stat.label }}</small>
            </div>
          </div>

          <div class="hours rv">
            <h3>Horaires d'ouverture</h3>
            <div v-for="s in schedules" :key="s.day" class="hrow" :class="{ today: s.isToday }">
              <span>{{ s.day }}</span>
              <span>{{ s.time }}</span>
            </div>
            <p class="hnote">Siège du Comité exécutif — Abidjan</p>
          </div>
        </div>

        <!-- Sélection -->
        <div class="lib-main">
          <div class="eyebrow rv" style="margin-bottom:1.6rem">Sélection du moment</div>
          <div class="books">
            <article v-for="book in recommendedBooks" :key="book.id" class="book rv">
              <div class="bcover frame">
                <img :src="book.cover" :alt="book.title" loading="lazy" @error="onCoverError">
              </div>
              <div class="binfo">
                <span class="pill" :class="book.available ? 'tone-green' : 'tone-clay'">
                  {{ book.available ? 'Disponible' : 'Emprunté' }}
                </span>
                <h4>{{ book.title }}</h4>
                <span class="bauthor">{{ book.author }}</span>
                <p>{{ book.description }}</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { observeCounters } from '../../composables/useReveal'

const statsEl = ref(null)
let stopCounters = null

const stats = ref([
  {
    id: 1, count: 1000, label: 'Livres physiques', tone: 'tone-green',
    icon: '<path d="M4 4.5h6a2.5 2.5 0 0 1 2.5 2.5v12a2 2 0 0 0-2-2H4V4.5Z"/><path d="M20 4.5h-6A2.5 2.5 0 0 0 11.5 7v12a2 2 0 0 1 2-2H20V4.5Z"/>'
  },
  {
    id: 2, count: 100, label: 'E-books', tone: 'tone-teal',
    icon: '<rect x="2.5" y="4.5" width="19" height="12" rx="1"/><path d="M8 20.5h8M12 16.5v4"/>'
  },
  {
    id: 3, count: 100, label: 'Articles & revues', tone: 'tone-gold',
    icon: '<rect x="3" y="4" width="14" height="16" rx="1"/><path d="M17 8h4v10a2 2 0 0 1-4 0V8ZM6.5 8h7M6.5 11.5h7M6.5 15h4"/>'
  }
])

const schedules = ref([
  { day: 'Lundi — Vendredi', time: '09h00 – 20h00', isToday: false },
  { day: 'Samedi', time: '09h00 – 18h00', isToday: false },
  { day: 'Dimanche', time: 'Fermé', isToday: false }
])

const recommendedBooks = ref([
  {
    id: 1,
    title: 'Les Grands Péchés',
    author: 'Imam Adh-Dhahabi',
    description: 'Un guide essentiel sur les grands péchés en islam et la manière de les éviter.',
    cover: 'https://www.iqrashop.com/images/grands-peches-0910.jpg',
    available: true
  },
  {
    id: 2,
    title: 'Afrique et Développement',
    author: 'Collectif',
    description: 'Perspectives sur le développement économique et social du continent.',
    cover: 'https://reseau-mirabel.info/images/titres-couvertures/000001866.png',
    available: true
  },
  {
    id: 3,
    title: 'La Vie du Prophète',
    author: 'Ibn Hisham',
    description: 'Biographie de référence du Prophète Muhammad (paix et bénédictions sur lui).',
    cover: 'https://m.media-amazon.com/images/I/81b+OWVO92L._UF1000,1000_QL80_.jpg',
    available: false
  }
])

// Une couverture indisponible ne doit pas laisser un cadre cassé.
function onCoverError(event) {
  event.target.style.display = 'none'
}

function markToday() {
  const day = new Date().getDay() // 0 = dimanche
  if (day >= 1 && day <= 5) schedules.value[0].isToday = true
  else if (day === 6) schedules.value[1].isToday = true
  else schedules.value[2].isToday = true
}

onMounted(() => {
  markToday()
  if (statsEl.value) stopCounters = observeCounters(statsEl.value)
})
onUnmounted(() => { if (stopCounters) stopCounters() })
</script>

<style scoped>
.lib-band{ background:var(--surface); position:relative; overflow:hidden; }
.lib-band > .wrap{ position:relative; z-index:1; }
.sec-head .lede{ margin-top:1.1rem; }

.lib-grid{ display:grid; grid-template-columns:.8fr 1.2fr; gap:clamp(28px,4vw,64px); align-items:start; }

/* ---------- Chiffres ---------- */
.lstats{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.lstat{ padding:1.4rem 1.3rem 1.5rem; }
.lic{ width:24px; height:24px; color:var(--tone, var(--green)); }
.lstat b{
  display:block; margin-top:1.1rem; font-family:var(--display); font-weight:400;
  font-size:1.9rem; line-height:1; color:var(--ink); letter-spacing:-.02em;
}
.lstat b :deep(.suf){ color:var(--tone, var(--green)); }
.lstat small{
  display:block; margin-top:.55rem; font-size:.66rem; letter-spacing:.14em;
  text-transform:uppercase; color:var(--muted); font-weight:600;
}

/* ---------- Horaires ---------- */
.hours{ margin-top:1.6rem; padding:1.5rem 1.4rem; background:var(--white); border:1px solid var(--line); }
.hours h3{
  font-family:var(--sans); font-size:.66rem; letter-spacing:.2em; text-transform:uppercase;
  color:var(--ink); font-weight:800; margin-bottom:1.1rem;
}
.hrow{
  display:flex; justify-content:space-between; gap:1rem; padding:.62rem 0;
  border-bottom:1px solid var(--line); font-size:.83rem; color:var(--muted);
}
.hrow:last-of-type{ border-bottom:0; }
.hrow.today{ color:var(--green); font-weight:700; }
.hrow.today span:first-child::before{
  content:""; display:inline-block; width:5px; height:5px; background:var(--gold);
  transform:rotate(45deg); margin-right:.5rem; vertical-align:middle;
}
.hnote{ margin-top:1rem; font-size:.72rem; color:var(--muted); letter-spacing:.04em; }

/* ---------- Sélection ---------- */
.books{ display:flex; flex-direction:column; border-top:1px solid var(--line-strong); }
.book{
  display:grid; grid-template-columns:104px 1fr; gap:1.5rem; align-items:start;
  padding:1.6rem 0; border-bottom:1px solid var(--line);
  transition:padding-left .55s var(--ease);
}
.book:hover{ padding-left:1.1rem; }
.bcover{ aspect-ratio:3/4; background:var(--surface-2); }
.bcover img{ transform:scale(1); }
.book:hover .bcover img{ transform:scale(1.06); }

.binfo h4{ font-family:var(--display); font-weight:400; font-size:1.25rem; margin-top:.75rem; color:var(--ink); }
.bauthor{ display:block; margin-top:.3rem; font-size:.72rem; letter-spacing:.14em; text-transform:uppercase; color:var(--muted); font-weight:700; }
.binfo p{ margin-top:.7rem; font-size:.86rem; color:var(--ink-soft); line-height:1.7; max-width:52ch; }

@media (max-width:1180px){ .lib-grid{ grid-template-columns:1fr; } }
@media (max-width:640px){
  .lstats{ grid-template-columns:1fr; }
  .book{ grid-template-columns:80px 1fr; gap:1.1rem; }
  .lstat{ padding:1.1rem 1.2rem; }
}
</style>
