<template>
  <div class="cr-root">
    <header class="cr-topbar">
      <div class="cr-brand">
        <span class="cr-brand__dot"></span>
        <div>
          <strong>Boite a idees SENAFOI 2026</strong>
          <small>Votre avis, en toute confidentialite</small>
        </div>
      </div>
    </header>

    <!-- ══════ FORMULAIRE ══════ -->
    <section v-if="!envoye" class="cr-screen">
      <div class="cr-card">
        <div class="cr-hero">
          <span class="cr-hero__icon">🔒</span>
          <h1>Donnez votre avis anonymement</h1>
          <p>
            Aidez-nous a ameliorer le seminaire. Votre message est <b>totalement anonyme</b> :
            ni votre nom, ni votre matricule, ni aucune donnee permettant de vous identifier
            n'est enregistre.
          </p>
        </div>

        <div class="cr-date">
          <span>Date</span>
          <strong>{{ dateAffichee }}</strong>
        </div>

        <label class="cr-field">
          <span>Votre avis s'adresse a <b class="req">*</b></span>
          <div class="cr-themes">
            <button
              v-for="t in themes"
              :key="t"
              type="button"
              class="cr-theme"
              :class="{ 'cr-theme--on': form.theme === t }"
              @click="form.theme = t"
            >
              <span class="cr-theme__ico">{{ icone(t) }}</span>
              {{ libelle(t) }}
            </button>
          </div>
        </label>

        <label class="cr-field">
          <span>Votre critique ou suggestion <b class="req">*</b></span>
          <textarea
            v-model="form.critique"
            rows="7"
            maxlength="2000"
            placeholder="Decrivez ce qui pourrait etre ameliore, ou ce que vous avez apprecie…"
          ></textarea>
          <small class="cr-count" :class="{ 'cr-count--low': form.critique.length < 10 }">
            {{ form.critique.length }} / 2000 caracteres
            <template v-if="form.critique.length < 10"> — minimum 10</template>
          </small>
        </label>

        <div v-if="erreur" class="cr-alert cr-alert--err">{{ erreur }}</div>

        <button
          class="cr-btn"
          :disabled="envoi || !form.theme || form.critique.trim().length < 10"
          @click="soumettre"
        >
          {{ envoi ? 'Envoi en cours…' : 'Envoyer mon avis' }}
        </button>

        <p class="cr-privacy">
          🛡️ Aucune adresse IP, aucun identifiant, aucun cookie de suivi.
          Personne ne peut savoir qui a ecrit ce message.
        </p>
      </div>
    </section>

    <!-- ══════ CONFIRMATION ══════ -->
    <section v-else class="cr-screen">
      <div class="cr-card cr-card--done">
        <div class="cr-done-icon">✓</div>
        <h1>Merci pour votre contribution</h1>
        <p class="cr-done-msg">
          Votre avis a bien ete transmis au comite d'organisation,
          de facon totalement anonyme.
        </p>
        <div class="cr-done-recap">
          <div><span>Theme</span><strong>{{ libelle(dernierTheme) }}</strong></div>
          <div><span>Date</span><strong>{{ dateAffichee }}</strong></div>
        </div>
        <p class="cr-done-note">
          Les avis ne sont pas publics : seul le comite d'organisation y a acces.
        </p>
        <button class="cr-btn cr-btn--ghost" @click="nouveau">Envoyer un autre avis</button>
      </div>
    </section>

    <footer class="cr-footer">
      <p>Association des Eleves et Etudiants Musulmans de Cote d'Ivoire</p>
      <p class="cr-slogan">« Pour une identite islamique ! »</p>
    </footer>
  </div>
</template>

<script>
const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_avis_api.php'

export default {
  name: 'CritiqueAnonyme',
  data() {
    return {
      // Ordre impose par le comite d'organisation
      themes: ['FORMATION', 'RESTAURATION', 'SECURITE', 'INNOVATION', 'PROPRETE', "COMITE D'ORGANISATION", 'AUTRE'],
      form: { theme: '', critique: '' },
      envoi: false,
      envoye: false,
      erreur: '',
      dernierTheme: '',
    }
  },
  computed: {
    dateAffichee() {
      return new Date().toLocaleDateString('fr-FR', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
      })
    },
  },
  methods: {
    libelle(t) {
      return {
        FORMATION: 'Formation',
        RESTAURATION: 'Restauration',
        SECURITE: 'Sécurité',
        INNOVATION: 'Innovation',
        PROPRETE: 'Propreté',
        "COMITE D'ORGANISATION": "Comité d'organisation",
        AUTRE: 'Autre',
      }[t] || t
    },
    icone(t) {
      return {
        FORMATION: '📚',
        RESTAURATION: '🍽️',
        SECURITE: '🛡️',
        INNOVATION: '💡',
        PROPRETE: '🧹',
        "COMITE D'ORGANISATION": '👥',
        AUTRE: '💬',
      }[t] || '💬'
    },
    async soumettre() {
      this.envoi = true
      this.erreur = ''
      try {
        const res = await fetch(`${API}?action=soumettre`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ theme: this.form.theme, critique: this.form.critique.trim() }),
        })
        const data = await res.json()
        if (!data.success) throw new Error(data.message || 'Envoi impossible')
        this.dernierTheme = this.form.theme
        this.envoye = true
      } catch (e) {
        this.erreur = e.message
      } finally {
        this.envoi = false
      }
    },
    nouveau() {
      this.form = { theme: '', critique: '' }
      this.envoye = false
      this.erreur = ''
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.cr-root {
  --ink: var(--ink); --muted: var(--muted); --line: var(--line);
  --accent: var(--green); --accent-soft: #f0fdfa; --accent-dark: #115e59;
  min-height: 100vh;
  background: linear-gradient(180deg, #f0fdfa 0%, var(--surface) 340px);
  font-family: var(--display);
  color: var(--ink); padding-bottom: 30px;
}

.cr-topbar { max-width: 720px; margin: 0 auto; padding: 18px 20px; }
.cr-brand { display: flex; align-items: center; gap: 11px; }
.cr-brand__dot { width: 12px; height: 12px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 5px rgba(15,118,110,.15); }
.cr-brand strong { display: block; font-size: 16px; }
.cr-brand small { color: var(--muted); font-size: 11.5px; }

.cr-screen { max-width: 720px; margin: 0 auto; padding: 4px 20px; }
.cr-card { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 26px 22px; box-shadow: var(--shadow); }

.cr-hero { text-align: center; margin-bottom: 22px; }
.cr-hero__icon { font-size: 42px; }
.cr-hero h1 { font-size: 24px; margin: 8px 0 10px; letter-spacing: -.02em; }
.cr-hero p { color: var(--muted); font-size: 14px; line-height: 1.65; }

.cr-date { display: flex; justify-content: space-between; align-items: center; background: var(--accent-soft); border: 1px solid #99f6e4; border-radius: 0; padding: 12px 15px; margin-bottom: 20px; }
.cr-date span { font-size: 12px; font-weight: 800; color: var(--accent-dark); text-transform: uppercase; letter-spacing: .06em; }
.cr-date strong { font-size: 13.5px; text-transform: capitalize; }

.cr-field { display: block; margin-bottom: 20px; }
.cr-field > span { display: block; font-size: 13px; font-weight: 800; color: var(--ink-soft); margin-bottom: 10px; }
.req { color: #dc2626; }

.cr-themes { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 9px; }
.cr-theme { display: flex; align-items: center; gap: 9px; border: 1.5px solid var(--line); background: var(--surface); border-radius: 0; padding: 13px 12px; font-size: 13.5px; font-weight: 700; font-family: inherit; color: var(--ink-soft); cursor: pointer; text-align: left; transition: .15s; }
.cr-theme:hover { border-color: #5eead4; background: #fff; }
.cr-theme--on { border-color: var(--accent); background: var(--accent-soft); color: var(--accent-dark); box-shadow: 0 0 0 3px rgba(15,118,110,.12); }
.cr-theme__ico { font-size: 17px; }

.cr-field textarea { width: 100%; border: 1.5px solid var(--line); border-radius: 0; padding: 14px; font-size: 14.5px; font-family: inherit; line-height: 1.6; background: var(--surface); color: var(--ink); resize: vertical; }
.cr-field textarea:focus { outline: none; border-color: var(--accent); background: #fff; box-shadow: 0 0 0 4px rgba(15,118,110,.1); }
.cr-count { display: block; margin-top: 7px; font-size: 11.5px; color: var(--muted); font-weight: 600; text-align: right; }
.cr-count--low { color: var(--gold); }

.cr-alert { border-radius: 0; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 14px; }
.cr-alert--err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

.cr-btn { width: 100%; border: 0; border-radius: 0; min-height: 54px; font-size: 15px; font-weight: 800; font-family: inherit; cursor: pointer; background: var(--accent); color: #fff; box-shadow: var(--shadow); transition: transform .12s, opacity .12s; }
.cr-btn:not(:disabled):hover { transform: translateY(-1px); }
.cr-btn:disabled { opacity: .5; cursor: not-allowed; }
.cr-btn--ghost { background: #fff; color: var(--accent-dark); border: 1.5px solid #99f6e4; box-shadow: none; margin-top: 14px; }

.cr-privacy { margin-top: 16px; text-align: center; font-size: 12px; color: var(--muted); line-height: 1.6; background: var(--surface); border-radius: 0; padding: 12px; }

.cr-card--done { text-align: center; border-color: var(--green-mist); background: linear-gradient(180deg, var(--green-mist), #fff); }
.cr-done-icon { width: 66px; height: 66px; margin: 0 auto 14px; border-radius: 50%; background: var(--green); color: #fff; display: grid; place-items: center; font-size: 32px; font-weight: 900; }
.cr-card--done h1 { font-size: 23px; margin-bottom: 10px; }
.cr-done-msg { color: var(--ink-soft); font-size: 14.5px; line-height: 1.65; margin-bottom: 20px; }
.cr-done-recap { display: flex; gap: 12px; justify-content: center; margin-bottom: 16px; flex-wrap: wrap; }
.cr-done-recap div { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 11px 16px; min-width: 140px; }
.cr-done-recap span { display: block; font-size: 10.5px; color: var(--muted); font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.cr-done-recap strong { font-size: 13.5px; text-transform: capitalize; }
.cr-done-note { color: var(--muted); font-size: 12.5px; font-style: italic; }

.cr-footer { max-width: 720px; margin: 26px auto 0; padding: 0 20px; text-align: center; color: var(--muted); font-size: 11.5px; line-height: 1.7; }
.cr-slogan { font-weight: 800; color: var(--accent-dark); }

@media (max-width: 560px) {
  .cr-themes { grid-template-columns: 1fr 1fr; }
  .cr-hero h1 { font-size: 21px; }
}
</style>
