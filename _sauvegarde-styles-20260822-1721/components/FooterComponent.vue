<template>
  <footer class="ft">
    <div class="khatam"></div>
    <div class="wrap">
      <div class="ft-grid">
        <div class="ft-about">
          <RouterLink class="ft-brand" to="/">
            <span class="brand-mark"><img :src="logo" alt="Logo AEEMCI"></span>
            <span class="brand-txt">
              <strong>AEEMCI</strong>
              <span>Depuis 1976</span>
            </span>
          </RouterLink>
          <p>
            Association des Élèves et Étudiants Musulmans de Côte d'Ivoire.
            Siège du Comité exécutif — Abidjan, Côte d'Ivoire.
          </p>
          <div class="socials">
            <a href="https://www.facebook.com/aeemci" target="_blank" rel="noopener" aria-label="Facebook">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M14 8.5V6.8c0-.8.2-1.2 1.4-1.2H17V2.6h-2.6c-3 0-4.1 1.5-4.1 4v1.9H8.3V11.5h2V21.4H14V11.5h2.5l.4-3H14Z"/></svg>
            </a>
            <a href="https://www.youtube.com/@aeemci" target="_blank" rel="noopener" aria-label="YouTube">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12s0-3.3-.4-4.9a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4a2.5 2.5 0 0 0-1.8 1.8C2 8.7 2 12 2 12s0 3.3.4 4.9c.2.9.9 1.6 1.8 1.8 1.6.3 7.8.3 7.8.3s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8C22 15.3 22 12 22 12ZM10 15.2V8.8l5.2 3.2L10 15.2Z"/></svg>
            </a>
            <a href="https://wa.me/2250700000000" target="_blank" rel="noopener" aria-label="WhatsApp">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a9.9 9.9 0 0 0-8.5 15L2 22l5.2-1.4A9.9 9.9 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2A5.2 5.2 0 0 0 8 11c.1.2 1.5 2.4 3.7 3.3 2.2.9 2.2.6 2.6.6a2.5 2.5 0 0 0 1.7-1.2c.2-.4.2-.8.1-.9l-.4-.2Z"/></svg>
            </a>
          </div>
        </div>

        <div v-for="col in columns" :key="col.title">
          <h5>{{ col.title }}</h5>
          <ul>
            <li v-for="item in col.items" :key="item.label">
              <a v-if="item.external" :href="item.to" target="_blank" rel="noopener">{{ item.label }}</a>
              <RouterLink v-else :to="item.to">{{ item.label }}</RouterLink>
            </li>
          </ul>
        </div>
      </div>

      <div class="ft-bot">
        <p>© {{ year }} AEEMCI — Association des Élèves et Étudiants Musulmans de Côte d'Ivoire.</p>
        <nav>
          <RouterLink to="/politique_confidentialite">Politique de confidentialité</RouterLink>
          <RouterLink to="/contact">Nous contacter</RouterLink>
        </nav>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import logo from '../../assets/logos/aeemci.jpg'

const year = new Date().getFullYear()

const columns = [
  {
    title: "L'association",
    items: [
      { label: 'Charte', to: '/charte' },
      { label: 'Historique', to: '/historique' },
      { label: 'Organigramme', to: '/organigramme' },
      { label: 'Comité exécutif', to: '/bureau' },
      { label: 'Cellules spécialisées', to: '/cellules' }
    ]
  },
  {
    title: 'Services',
    items: [
      { label: 'Carte de membre', to: '/carte_membre' },
      { label: 'Vérification de carte', to: '/verification_carte' },
      { label: 'SENAFOI', to: 'https://senafoi.aeemci-ce.ci/', external: true },
      { label: 'CIMA', to: 'http://militant.aeemci-ce.ci/cima', external: true },
      { label: 'AEEMCI TV', to: '/tv' }
    ]
  },
  {
    title: 'Contact',
    items: [
      { label: 'Nous écrire', to: '/contact' },
      { label: 'contact@aeemci-ce.ci', to: 'mailto:contact@aeemci-ce.ci', external: true },
      { label: 'Webmail AEEMCI', to: 'https://email.aeemci-ce.ci/', external: true },
      { label: 'Espace militant', to: 'http://militant.aeemci-ce.ci/', external: true },
      { label: 'Faire un don', to: '/don' }
    ]
  }
]
</script>

<style scoped>
.ft{
  background:var(--deep); color:var(--deep-fg);
  padding:clamp(60px,6vw,92px) 0 0; position:relative; overflow:hidden;
}
.ft .khatam{ opacity:.22; }

.ft-grid{
  position:relative; z-index:1; display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr;
  gap:clamp(28px,4vw,56px); padding-bottom:3.6rem;
}

h5{
  font-family:var(--sans); font-size:.66rem; letter-spacing:.2em; text-transform:uppercase;
  color:#fff; font-weight:800; margin:0 0 1.4rem;
}
ul{ list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:.75rem; }
ul a{ font-size:.85rem; color:var(--deep-fg-2); transition:color .35s, padding-left .35s var(--ease); display:inline-block; }
ul a:hover{ color:var(--deep-gold); padding-left:.45rem; }

.ft-about p{ font-size:.87rem; color:var(--deep-fg-2); line-height:1.75; max-width:38ch; margin:1.3rem 0 0; }
.ft-brand{ display:flex; align-items:center; gap:.85rem; }
.brand-mark{ width:46px; height:46px; overflow:hidden; flex:none; border:1px solid rgba(255,255,255,.2); background:#fff; }
.brand-mark img{ width:100%; height:100%; object-fit:contain; }
.brand-txt strong{ display:block; font-family:var(--display); font-size:1.3rem; letter-spacing:.13em; line-height:1; color:#fff; font-weight:400; }
.brand-txt span{ display:block; font-size:.58rem; letter-spacing:.15em; text-transform:uppercase; color:var(--deep-fg-2); margin-top:.34rem; font-weight:600; }

.socials{ display:flex; gap:.5rem; margin-top:1.7rem; }
.socials a{
  width:40px; height:40px; border:1px solid rgba(255,255,255,.18);
  display:grid; place-items:center; color:var(--deep-fg-2); transition:all .45s var(--ease);
}
.socials a:hover{ border-color:var(--deep-gold); color:var(--deep-gold); transform:translateY(-3px); }

.ft-bot{
  position:relative; z-index:1; border-top:1px solid rgba(255,255,255,.14);
  padding:1.7rem 0 2.3rem; display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap;
}
.ft-bot p{ margin:0; }
.ft-bot p, .ft-bot a{ font-size:.74rem; color:var(--deep-fg-2); }
.ft-bot a:hover{ color:var(--deep-gold); }
.ft-bot nav{ display:flex; gap:1.7rem; flex-wrap:wrap; }

@media (max-width:1180px){ .ft-grid{ grid-template-columns:1fr 1fr; } }
@media (max-width:640px){ .ft-grid{ grid-template-columns:1fr; } }
</style>
