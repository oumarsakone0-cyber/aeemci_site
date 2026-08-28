<template>
  <section class="band ident">
    <div class="khatam"></div>
    <div class="wrap">
      <div class="ident-grid">
        <div class="ident-media">
          <div class="frame wipe rv">
            <img :src="imgDouas" alt="Doua collectif lors du SENAFOI">
          </div>
          <div class="badge rv">
            <b>50</b>
            <span>Ans · 1976–2026</span>
          </div>
        </div>

        <div>
          <div class="eyebrow rv">Notre identité</div>
          <h2 class="rv">Une identité islamique,<br>une conscience citoyenne.</h2>

          <p class="arabic rv">« Et cramponnez-vous tous ensemble au câble d'Allah et ne soyez pas divisés. »</p>

          <p class="lede rv">
            Depuis 1976, l'association accompagne les élèves et étudiants musulmans de Côte d'Ivoire
            sur trois terrains inséparables : la foi, le savoir et le service rendu à la communauté.
          </p>

          <div class="pillars rv">
            <div v-for="pillar in pillars" :key="pillar.roman" class="pillar">
              <i>{{ pillar.roman }}</i>
              <span>
                <strong>{{ pillar.title }}</strong>
                <span>{{ pillar.text }}</span>
              </span>
            </div>
          </div>

          <div class="ident-actions rv">
            <RouterLink class="btn btn--lg" to="/charte"><span>Lire la charte</span></RouterLink>
            <RouterLink class="btn btn--ghost btn--lg" to="/historique"><span>Notre histoire</span></RouterLink>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import imgDouas from '../../assets/douas.jpg'

const pillars = [
  {
    roman: 'I',
    title: 'Formation religieuse',
    text: 'Enseignement doctrinal rigoureux, transmis par des formateurs et imams agréés par le Comité exécutif.'
  },
  {
    roman: 'II',
    title: 'Excellence académique',
    text: "Soutien scolaire, bourses au mérite et accompagnement des bacheliers vers l'enseignement supérieur."
  },
  {
    roman: 'III',
    title: 'Leadership managérial',
    text: 'Gouvernance associative, gestion de projet et prise de responsabilité dès la section locale.'
  },
  {
    roman: 'IV',
    title: 'Solidarité communautaire',
    text: 'Actions sociales, dons, santé et entraide déployés dans les 34 secrétariats régionaux.'
  }
]
</script>

<style scoped>
.ident{ background:var(--surface); position:relative; overflow:hidden; }
.ident-grid{
  position:relative; z-index:1; display:grid; grid-template-columns:.92fr 1.08fr;
  gap:clamp(32px,5vw,84px); align-items:center;
}

.ident-media{ position:relative; }
.ident-media .frame{ aspect-ratio:4/5; }
.ident-media .badge{
  position:absolute; right:-18px; bottom:-18px; width:138px; height:138px; padding:.5rem;
  background:var(--deep); color:#fff; display:grid; place-content:center;
  text-align:center; gap:.2rem; z-index:2;
}
.ident-media .badge b{ font-family:var(--display); font-size:2.3rem; line-height:1; color:var(--deep-gold); font-weight:400; }
.ident-media .badge span{ font-size:.56rem; letter-spacing:.13em; text-transform:uppercase; color:rgba(255,255,255,.72); font-weight:700; }

.ident h2{ font-size:clamp(1.95rem,3.6vw,3.1rem); margin-top:1.15rem; letter-spacing:-.018em; }
.arabic{
  font-family:var(--display); font-size:clamp(1.25rem,2.1vw,1.75rem);
  color:var(--green); line-height:1.55; margin-top:1.8rem;
}
.lede{ margin-top:1.2rem; }

.pillars{ display:grid; border-top:1px solid var(--line-strong); margin-top:2.2rem; }
.pillar{
  display:grid; grid-template-columns:auto 1fr; gap:1.4rem; padding:1.4rem 0;
  border-bottom:1px solid var(--line); align-items:start; transition:padding-left .5s var(--ease);
}
.pillar:hover{ padding-left:.9rem; }
.pillar i{ font-style:normal; font-size:.66rem; letter-spacing:.14em; color:var(--gold); font-weight:700; padding-top:.4rem; min-width:22px; }
.pillar strong{ display:block; font-family:var(--display); font-weight:400; font-size:1.2rem; color:var(--ink); }
.pillar span span{ display:block; font-size:.85rem; color:var(--muted); margin-top:.35rem; line-height:1.65; }

.ident-actions{ display:flex; flex-wrap:wrap; gap:.8rem; margin-top:2.2rem; }

@media (max-width:1180px){ .ident-grid{ grid-template-columns:1fr; } }
@media (max-width:900px){ .ident-media .badge{ width:118px; height:118px; right:0; bottom:0; } }
@media (max-width:640px){
  .ident-actions{ width:100%; }
  .ident-actions .btn{ width:100%; white-space:normal; text-align:center; line-height:1.35; }
}
</style>
