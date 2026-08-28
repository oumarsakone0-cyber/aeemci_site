<template>
  <section class="band band--tight form-band">
    <div class="wrap">
      <div class="rule rv"></div>

      <div class="sec-head">
        <div>
          <div class="eyebrow rv">Formations · {{ formations.length }} programmes</div>
          <h2 class="rv">Apprendre la foi,<br>apprendre le métier.</h2>
          <p class="lede rv">
            Des cycles courts qui associent enseignement islamique et compétences
            recherchées sur le marché du travail ivoirien.
          </p>
        </div>
        <RouterLink class="link-more rv" to="/formations">
          Toutes les formations
          <svg width="15" height="10" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </RouterLink>
      </div>

      <!-- Filtres de niveau -->
      <div class="filters rv">
        <button
          v-for="level in levels" :key="level" type="button"
          class="filter" :class="{ active: selectedLevel === level }"
          @click="selectedLevel = level"
        >{{ level }}</button>
      </div>

      <!-- Grille -->
      <div class="fgrid">
        <article
          v-for="formation in filteredFormations" :key="formation.id"
          class="fcard tile rv" :class="toneOf(formation.level)"
        >
          <span class="pill" :class="toneOf(formation.level)">{{ formation.level }}</span>

          <svg class="fic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" v-html="formation.icon"></svg>

          <h3>{{ formation.title }}</h3>
          <p>{{ formation.description }}</p>

          <div class="fmeta">
            <span>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
              {{ formation.duration }}
            </span>
            <span>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 19.5c.9-3 3.4-4.6 6.5-4.6s5.6 1.6 6.5 4.6M17 8.2a3.2 3.2 0 0 1 0 6.3"/></svg>
              {{ formation.participants }}
            </span>
          </div>

          <RouterLink class="fgo" to="/formations">
            S'inscrire
            <svg width="13" height="9" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </RouterLink>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'

const levels = ref(['Tous', 'Tous niveaux', 'Débutant', 'Intermédiaire', 'Avancé'])
const selectedLevel = ref('Tous')

const formations = ref([
  {
    id: 1,
    title: 'Leadership islamique',
    description: 'Développez vos compétences en leadership selon les principes islamiques.',
    duration: '3 mois', level: 'Tous niveaux', participants: '25 max',
    icon: '<path d="M12 3.2 4 7v5.5c0 4.4 3.3 7.4 8 8.3 4.7-.9 8-3.9 8-8.3V7l-8-3.8Z"/><path d="M9 12.2l2.2 2.2L15.3 10"/>'
  },
  {
    id: 2,
    title: 'Arabe & Coran',
    description: 'Apprenez la langue arabe et mémorisez le Coran avec nos professeurs.',
    duration: '6 mois', level: 'Débutant', participants: '20 max',
    icon: '<path d="M4 4.5h6a2.5 2.5 0 0 1 2.5 2.5v12a2 2 0 0 0-2-2H4V4.5Z"/><path d="M20 4.5h-6A2.5 2.5 0 0 0 11.5 7v12a2 2 0 0 1 2-2H20V4.5Z"/>'
  },
  {
    id: 3,
    title: 'Sciences islamiques',
    description: 'Approfondissez vos connaissances en jurisprudence, hadith et exégèse.',
    duration: '1 an', level: 'Intermédiaire', participants: '30 max',
    icon: '<path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M5.5 10v5.2c0 1.6 2.9 3.3 6.5 3.3s6.5-1.7 6.5-3.3V10"/><path d="M22 8v6"/>'
  },
  {
    id: 4,
    title: 'Informatique & numérique',
    description: 'Maîtrisez les outils bureautiques, la sécurité et la création de contenu.',
    duration: '2 mois', level: 'Tous niveaux', participants: '15 max',
    icon: '<rect x="2.5" y="4.5" width="19" height="12" rx="1"/><path d="M8 20.5h8M12 16.5v4"/>'
  },
  {
    id: 5,
    title: 'Art oratoire',
    description: 'Développez votre éloquence et votre aisance devant un public.',
    duration: '2 mois', level: 'Tous niveaux', participants: '20 max',
    icon: '<rect x="9" y="2.5" width="6" height="11" rx="3"/><path d="M5.5 11.5a6.5 6.5 0 0 0 13 0M12 18v3.5"/>'
  },
  {
    id: 6,
    title: 'Management',
    description: 'Perfectionnez vos compétences en gestion de projet et d\'équipe.',
    duration: '3 mois', level: 'Intermédiaire', participants: '25 max',
    icon: '<rect x="2.5" y="7" width="19" height="13" rx="1"/><path d="M8.5 7V5.5a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2V7M2.5 12.5h19"/>'
  },
  {
    id: 7,
    title: 'Civisme & citoyenneté',
    description: 'Renforcez vos valeurs civiques et votre engagement communautaire.',
    duration: '2 mois', level: 'Tous niveaux', participants: '30 max',
    icon: '<path d="M12 20.5s-7.5-4.6-7.5-9.6a4.3 4.3 0 0 1 7.5-2.8 4.3 4.3 0 0 1 7.5 2.8c0 5-7.5 9.6-7.5 9.6Z"/>'
  },
  {
    id: 8,
    title: "Cours d'anglais",
    description: 'Perfectionnez votre anglais écrit et oral en petits groupes.',
    duration: '4 mois', level: 'Débutant', participants: '15 max',
    icon: '<circle cx="12" cy="12" r="9"/><path d="M3.2 9.5h17.6M3.2 14.5h17.6M12 3a16 16 0 0 1 0 18A16 16 0 0 1 12 3Z"/>'
  }
])

const filteredFormations = computed(() =>
  selectedLevel.value === 'Tous'
    ? formations.value
    : formations.value.filter((f) => f.level === selectedLevel.value)
)

// Une teinte par niveau — la couleur porte une information, elle ne décore pas.
const tones = {
  'Tous niveaux': 'tone-green',
  'Débutant': 'tone-teal',
  'Intermédiaire': 'tone-gold',
  'Avancé': 'tone-clay'
}
const toneOf = (level) => tones[level] || 'tone-green'
</script>

<style scoped>
.form-band{ padding-top:0; }
.rule{ margin-bottom:var(--section); }
.sec-head .lede{ margin-top:1.1rem; }

/* ---------- Filtres ---------- */
.filters{ display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:2.4rem; }
.filter{
  padding:.55rem 1.1rem; border:1px solid var(--line); background:transparent;
  font-size:.73rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
  color:var(--muted); transition:all .4s var(--ease);
}
.filter:hover{ border-color:var(--line-strong); color:var(--ink-soft); }
.filter.active{ background:var(--green); border-color:var(--green); color:#fff; }

/* ---------- Grille ---------- */
.fgrid{ display:grid; grid-template-columns:repeat(4,1fr); gap:clamp(14px,1.6vw,22px); }
.fcard{ padding:1.7rem 1.6rem 1.5rem; display:flex; flex-direction:column; gap:0; }
.fcard > .pill{ align-self:flex-start; }
.fcard > .fgo{ align-self:flex-start; }

.fic{
  width:26px; height:26px; margin-top:1.4rem; color:var(--tone, var(--green));
  transition:transform .55s var(--ease);
}
.fcard:hover .fic{ transform:translateY(-3px); }

.fcard h3{ font-family:var(--sans); font-size:1rem; font-weight:700; margin-top:1rem; color:var(--ink); line-height:1.35; }
.fcard p{ font-size:.83rem; color:var(--muted); line-height:1.6; margin-top:.5rem; flex:1; }

.fmeta{ display:flex; flex-wrap:wrap; gap:1rem; margin-top:1.2rem; padding-top:1rem; border-top:1px solid var(--line); }
.fmeta span{ display:inline-flex; align-items:center; gap:.4rem; font-size:.73rem; color:var(--muted); font-weight:600; }

.fgo{
  display:inline-flex; align-items:center; gap:.5rem; margin-top:1.1rem;
  font-size:.66rem; letter-spacing:.17em; text-transform:uppercase; font-weight:700;
  color:var(--tone, var(--green)); transition:gap .4s var(--ease);
}
.fcard:hover .fgo{ gap:1rem; }

@media (max-width:1180px){ .fgrid{ grid-template-columns:repeat(2,1fr); } }
@media (max-width:560px){ .fgrid{ grid-template-columns:1fr; } }
</style>
