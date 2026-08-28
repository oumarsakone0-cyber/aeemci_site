<template>
  <div class="cd-root">
    <header class="cd-tete">
      <div class="cd-brand">
        <span class="cd-brand__point"></span>
        <div>
          <strong>Note de conduite</strong>
          <small>SENAFOI 2026 · par défaut 16/20</small>
        </div>
      </div>
      <label class="cd-agent">
        <span>Agent</span>
        <input v-model="agent" placeholder="Votre nom" @input="memoriserAgent" />
      </label>
    </header>

    <main class="cd-corps">
      <!-- ── Scanner ─────────────────────────────────────────────────── -->
      <section class="cd-carte cd-scan">
        <div class="cd-video-cadre">
          <video ref="video" class="cd-video" playsinline autoplay></video>
          <div class="cd-viseur">
            <span class="c tl"></span><span class="c tr"></span>
            <span class="c bl"></span><span class="c br"></span>
            <span class="cd-ligne" :class="{ active: scanEnCours }"></span>
          </div>
        </div>

        <div class="cd-scan-actions">
          <button v-if="!scanEnCours" class="btn primary" type="button" @click="demarrer">
            📷 Activer la caméra
          </button>
          <button v-else class="btn danger" type="button" @click="arreter">⏹ Arrêter</button>
        </div>

        <div class="cd-manuel">
          <input
            v-model="saisieManuelle" placeholder="Ou saisir le matricule…"
            @keyup.enter="chercher(saisieManuelle)"
          />
          <button class="btn soft" type="button" :disabled="!saisieManuelle.trim()" @click="chercher(saisieManuelle)">
            Valider
          </button>
        </div>

        <div v-if="erreur" class="cd-alerte">{{ erreur }}</div>
      </section>

      <!-- ── Profil et notation ──────────────────────────────────────── -->
      <section class="cd-carte cd-profil" v-if="seminariste">
        <div class="cd-profil-tete">
          <img v-if="seminariste.photo" :src="seminariste.photo" alt="" />
          <span v-else class="cd-init">{{ initiales }}</span>
          <div>
            <strong>{{ seminariste.prenom }} {{ seminariste.nom }}</strong>
            <small>{{ seminariste.matricule }} · {{ seminariste.niveau_seminaire || 'Niveau non défini' }}</small>
          </div>
        </div>

        <div class="cd-actuelle" :class="couleurNote(seminariste.note_conduite)">
          <span>Conduite actuelle</span>
          <strong>{{ seminariste.note_conduite }}/20</strong>
        </div>

        <div class="cd-choix">
          <span class="cd-choix__label">Nouvelle conduite</span>
          <div class="cd-choix__grille">
            <button
              v-for="n in [16, 15, 14, 13, 12]" :key="n"
              class="cd-note" :class="[couleurNote(n), { on: Number(seminariste.note_conduite) === n }]"
              :disabled="enregistrement"
              @click="noter(n)"
            >{{ n }}</button>
          </div>
        </div>

        <Transition name="cd-fade">
          <div v-if="confirmation" class="cd-confirmation">✓ Conduite enregistrée : {{ confirmation }}/20</div>
        </Transition>

        <div v-if="seminariste.historique?.length" class="cd-historique">
          <span class="cd-historique__label">Derniers changements</span>
          <ul>
            <li v-for="(h, i) in seminariste.historique" :key="i">
              {{ h.ancienne_note ?? '—' }} → <b>{{ h.nouvelle_note }}</b>
              <small>{{ h.agent || 'agent inconnu' }} · {{ formatDate(h.created_at) }}</small>
            </li>
          </ul>
        </div>

        <button class="btn ghost full" type="button" @click="reinitialiser">← Séminariste suivant</button>
      </section>
    </main>

    <!-- ── Historique de session ──────────────────────────────────────── -->
    <aside v-if="session.length" class="cd-session">
      <span class="cd-session__label">Cette session ({{ session.length }})</span>
      <ul>
        <li v-for="(s, i) in session" :key="i">
          <strong>{{ s.nom }}</strong>
          <span :class="couleurNote(s.note)">{{ s.note }}/20</span>
        </li>
      </ul>
    </aside>
  </div>
</template>

<script>
import { BrowserMultiFormatReader } from '@zxing/library'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_conduite_api.php'

export default {
  name: 'ConduiteSeminaristes',
  data() {
    return {
      agent: '',
      scanEnCours: false,
      lecteur: null,
      saisieManuelle: '',
      erreur: '',
      chargement: false,
      seminariste: null,
      enregistrement: false,
      confirmation: null,
      session: [],
      dernierCode: '',
      dernierMoment: 0,
    }
  },
  computed: {
    initiales() {
      const p = this.seminariste;
      if (!p) return '?';
      return `${(p.prenom || '')[0] || ''}${(p.nom || '')[0] || ''}`.toUpperCase() || '?';
    },
  },
  mounted() {
    try { this.agent = localStorage.getItem('conduite_seminaristes_agent') || ''; } catch (e) { /* ignore */ }
  },
  beforeUnmount() {
    this.arreter();
  },
  methods: {
    memoriserAgent() {
      try { localStorage.setItem('conduite_seminaristes_agent', this.agent); } catch (e) { /* ignore */ }
    },
    async demarrer() {
      this.erreur = '';
      try {
        this.lecteur = new BrowserMultiFormatReader();
        this.scanEnCours = true;
        await this.lecteur.decodeFromConstraints(
          { video: { facingMode: 'environment' } },
          this.$refs.video,
          (resultat) => {
            if (!resultat) return;
            const texte = String(resultat.getText() || '').trim();
            const maintenant = Date.now();
            if (texte === this.dernierCode && maintenant - this.dernierMoment < 3000) return;
            this.dernierCode = texte;
            this.dernierMoment = maintenant;
            this.bip();
            this.chercher(this.extraireMatricule(texte));
          }
        );
      } catch (e) {
        this.scanEnCours = false;
        this.erreur = "Caméra indisponible : " + e.message + ". Saisissez le matricule à la main.";
      }
    },
    arreter() {
      try { this.lecteur?.reset(); } catch (e) { /* déjà arrêté */ }
      this.lecteur = null;
      this.scanEnCours = false;
    },
    extraireMatricule(texte) {
      const brut = String(texte || '').trim();
      const parametre = brut.match(/matricule=([A-Za-z0-9_-]+)/i);
      if (parametre) return parametre[1].toUpperCase();
      const dernier = brut.split(/[/?#\s]+/).filter(Boolean).pop() || brut;
      return dernier.toUpperCase();
    },
    bip(frequence = 880) {
      try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.frequency.value = frequence;
        osc.connect(gain); gain.connect(ctx.destination);
        gain.gain.setValueAtTime(0.08, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.18);
        osc.start(); osc.stop(ctx.currentTime + 0.18);
      } catch (e) { /* audio bloqué */ }
    },
    async chercher(matricule) {
      const m = String(matricule || '').trim().toUpperCase();
      if (!m) return;
      this.chargement = true;
      this.erreur = '';
      this.confirmation = null;
      this.seminariste = null;
      try {
        const res = await fetch(`${API}?action=rechercher`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: m }),
        });
        const data = await res.json();
        if (!data.success) { this.erreur = data.message || 'Matricule introuvable.'; this.bip(220); return; }
        this.seminariste = { ...data.seminariste, historique: data.historique || [] };
        this.saisieManuelle = '';
      } catch (e) {
        this.erreur = 'Connexion impossible : ' + e.message;
      } finally {
        this.chargement = false;
      }
    },
    async noter(note) {
      if (!this.seminariste || this.enregistrement) return;
      this.enregistrement = true;
      this.erreur = '';
      try {
        const res = await fetch(`${API}?action=noter`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: this.seminariste.matricule, note, agent: this.agent }),
        });
        const data = await res.json();
        if (!data.success) { this.erreur = data.message; return; }

        this.seminariste.historique.unshift({
          ancienne_note: this.seminariste.note_conduite,
          nouvelle_note: note, agent: this.agent, created_at: new Date().toISOString(),
        });
        this.seminariste.note_conduite = note;
        this.confirmation = note;
        this.session.unshift({ nom: `${this.seminariste.prenom} ${this.seminariste.nom}`.trim(), note });
        this.session = this.session.slice(0, 15);
        setTimeout(() => { this.confirmation = null; }, 2200);
      } catch (e) {
        this.erreur = 'Connexion impossible : ' + e.message;
      } finally {
        this.enregistrement = false;
      }
    },
    couleurNote(n) {
      const v = Number(n);
      if (v >= 16) return 'cd-vert';
      if (v >= 14) return 'cd-jaune';
      return 'cd-rouge';
    },
    formatDate(v) {
      if (!v) return '';
      const d = new Date(String(v).replace(' ', 'T'));
      return Number.isNaN(d.getTime()) ? v : d.toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'short' });
    },
    reinitialiser() {
      this.seminariste = null;
      this.erreur = '';
      this.confirmation = null;
      this.saisieManuelle = '';
    },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.cd-root {
  --vert: #0f5132; --vert-clair: var(--green-mist); --vert-fonce: #0b3d26;
  --jaune: var(--gold); --jaune-clair: var(--gold-mist);
  --rouge: #dc2626; --rouge-clair: #fee2e2;
  --ligne: #e4e6f0; --muted: #8a8fa8; --surface: #fff; --bg: #f6f8fb;
  font-family: var(--display);
  background: var(--bg); min-height: 100dvh; color: #0f1117;
  display: flex; flex-direction: column;
}

.cd-tete { display: flex; align-items: center; justify-content: space-between; gap: 12px;
           padding: 14px 18px; background: var(--surface); border-bottom: 1px solid var(--ligne);
           position: sticky; top: 0; z-index: 5; }
.cd-brand { display: flex; align-items: center; gap: 9px; }
.cd-brand__point { width: 10px; height: 10px; border-radius: 50%; background: var(--vert); }
.cd-brand strong { display: block; font-size: 14px; }
.cd-brand small { color: var(--muted); font-size: 11.5px; }
.cd-agent { display: flex; align-items: center; gap: 7px; }
.cd-agent span { font-size: 11px; color: var(--muted); font-weight: 700; }
.cd-agent input { border: 1px solid var(--ligne); border-radius: 0; padding: 7px 10px; font-size: 12.5px; width: 130px; }

.cd-corps { flex: 1; display: flex; flex-wrap: wrap; gap: 16px; padding: 18px; max-width: 920px;
            margin: 0 auto; width: 100%; align-items: flex-start; }
.cd-carte { background: var(--surface); border-radius: 0; padding: 18px; box-shadow: var(--shadow);
            flex: 1 1 340px; }

/* Scanner */
.cd-video-cadre { position: relative; aspect-ratio: 1; border-radius: 0; overflow: hidden;
                  background: #0a0c0e; margin-bottom: 14px; }
.cd-video { width: 100%; height: 100%; object-fit: cover; }
.cd-viseur { position: absolute; inset: 14%; pointer-events: none; }
.c { position: absolute; width: 26px; height: 26px; border: 3px solid var(--vert); }
.c.tl { top: 0; left: 0; border-right: 0; border-bottom: 0; border-top-left-radius: 8px; }
.c.tr { top: 0; right: 0; border-left: 0; border-bottom: 0; border-top-right-radius: 8px; }
.c.bl { bottom: 0; left: 0; border-right: 0; border-top: 0; border-bottom-left-radius: 8px; }
.c.br { bottom: 0; right: 0; border-left: 0; border-top: 0; border-bottom-right-radius: 8px; }
.cd-ligne { position: absolute; left: 0; right: 0; top: 0; height: 2px; background: var(--vert);
            opacity: 0; box-shadow: var(--shadow); }
.cd-ligne.active { opacity: 1; animation: cd-scan 1.8s ease-in-out infinite; }
@keyframes cd-scan { 0% { top: 0; } 50% { top: 100%; } 100% { top: 0; } }

.cd-scan-actions { margin-bottom: 12px; }
.cd-manuel { display: flex; gap: 8px; margin-bottom: 10px; }
.cd-manuel input { flex: 1; border: 1px solid var(--ligne); border-radius: 0; padding: 10px 12px; font-size: 13px; }
.cd-alerte { background: var(--rouge-clair); color: var(--rouge); border-radius: 0; padding: 9px 12px; font-size: 12.5px; font-weight: 700; }

.btn { border: 0; border-radius: 999px; padding: 12px 20px; font-weight: 800; font-size: 13.5px;
       cursor: pointer; font-family: inherit; width: 100%; }
.btn:disabled { opacity: .5; cursor: default; }
.btn.primary { background: var(--vert); color: #fff; }
.btn.danger { background: var(--rouge); color: #fff; }
.btn.soft { background: var(--bg); color: #0f1117; width: auto; padding: 10px 16px; }
.btn.ghost { background: transparent; color: var(--vert); border: 2px solid var(--vert-clair); }
.btn.full { margin-top: 14px; }

/* Profil */
.cd-profil-tete { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.cd-profil-tete img { width: 58px; height: 58px; border-radius: 50%; object-fit: cover; }
.cd-init { width: 58px; height: 58px; border-radius: 50%; background: var(--vert-clair); color: var(--vert);
           display: grid; place-items: center; font-weight: 900; font-size: 19px; }
.cd-profil-tete strong { display: block; font-size: 15.5px; }
.cd-profil-tete small { color: var(--muted); font-size: 12px; }

.cd-actuelle { display: flex; align-items: center; justify-content: space-between; border-radius: 0;
               padding: 12px 15px; margin-bottom: 18px; }
.cd-actuelle span { font-size: 11px; font-weight: 800; text-transform: uppercase; opacity: .75; }
.cd-actuelle strong { font-size: 22px; font-weight: 900; }

.cd-choix__label { display: block; font-size: 11px; font-weight: 800; color: var(--muted);
                    text-transform: uppercase; margin-bottom: 10px; }
.cd-choix__grille { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
.cd-note { aspect-ratio: 1; border-radius: 0; border: 2px solid transparent; font-size: 19px;
           font-weight: 900; cursor: pointer; background: var(--bg); color: var(--ink-soft); }
.cd-note.on { border-color: currentColor; box-shadow: 0 0 0 3px rgba(0,0,0,.04) inset; }
.cd-note:disabled { opacity: .6; cursor: default; }

.cd-vert { background: var(--vert-clair); color: var(--vert-fonce); }
.cd-jaune { background: var(--jaune-clair); color: #92400e; }
.cd-rouge { background: var(--rouge-clair); color: var(--rouge); }

.cd-confirmation { margin-top: 14px; background: var(--vert-clair); color: var(--vert-fonce);
                    border-radius: 0; padding: 11px 14px; font-weight: 800; font-size: 13px; text-align: center; }
.cd-fade-enter-active, .cd-fade-leave-active { transition: opacity .25s; }
.cd-fade-enter-from, .cd-fade-leave-to { opacity: 0; }

.cd-historique { margin-top: 16px; border-top: 1px solid var(--ligne); padding-top: 12px; }
.cd-historique__label { font-size: 10.5px; font-weight: 800; color: var(--muted); text-transform: uppercase; }
.cd-historique ul { list-style: none; margin-top: 8px; display: flex; flex-direction: column; gap: 6px; }
.cd-historique li { font-size: 12.5px; display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; }
.cd-historique small { color: var(--muted); font-size: 10.5px; margin-left: auto; }

/* Session */
.cd-session { max-width: 920px; margin: 0 auto 20px; width: 100%; padding: 0 18px; }
.cd-session__label { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; }
.cd-session ul { list-style: none; margin-top: 8px; display: flex; flex-direction: column; gap: 6px;
                 background: var(--surface); border-radius: 0; padding: 10px 14px; }
.cd-session li { display: flex; align-items: center; justify-content: space-between; font-size: 13px;
                  padding: 5px 0; border-bottom: 1px solid var(--surface); }
.cd-session li:last-child { border-bottom: 0; }
.cd-session li span { border-radius: 999px; padding: 2px 9px; font-size: 11.5px; font-weight: 800; }

@media (max-width: 640px) {
  .cd-corps { padding: 14px; }
}
</style>
