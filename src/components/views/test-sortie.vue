<template>
  <div class="ts-root">

    <!-- ══════════════ IDENTIFICATION ══════════════ -->
    <div v-if="etape === 'identification'" class="ts-screen ts-screen--scanner">
      <div class="ts-wrap">
        <div class="ts-brand">
          <span class="ts-brand__icon">◈</span>
          <span class="ts-brand__label">SÉNAFOI</span>
        </div>
        <h1 class="ts-title">Test de sortie</h1>
        <p class="ts-sub">Scannez votre badge ou saisissez votre matricule</p>

        <div class="ts-video-frame">
          <video ref="video" class="ts-video" playsinline autoplay></video>
          <div class="ts-scan-overlay">
            <div class="ts-corner tl"></div>
            <div class="ts-corner tr"></div>
            <div class="ts-corner bl"></div>
            <div class="ts-corner br"></div>
            <div class="ts-scan-line" :class="{ active: scanEnCours }"></div>
          </div>
        </div>

        <div v-if="erreur" class="ts-alert ts-alert--danger">⚠ {{ erreur }}</div>

        <div class="ts-actions">
          <button v-if="!scanEnCours" class="ts-btn ts-btn--primary" @click="demarrerScanner">
            📷 Activer la caméra
          </button>
          <button v-else class="ts-btn ts-btn--danger" @click="arreterScanner">⏹ Arrêter</button>
          <button class="ts-btn ts-btn--outline" @click="showManuel = true">✏ Saisir mon matricule</button>
        </div>
      </div>

      <div v-if="showManuel" class="ts-modal-backdrop" @click.self="showManuel = false">
        <div class="ts-modal">
          <div class="ts-modal__head">
            <h3>Saisie manuelle</h3>
            <button class="ts-modal__x" @click="showManuel = false">✕</button>
          </div>
          <input
            v-model="matriculeSaisi"
            @input="matriculeSaisi = matriculeSaisi.toUpperCase()"
            @keyup.enter="validerManuel"
            type="text" placeholder="VOTRE MATRICULE" class="ts-input" autofocus
          />
          <div class="ts-modal__actions">
            <button class="ts-btn ts-btn--ghost" @click="showManuel = false">Annuler</button>
            <button class="ts-btn ts-btn--primary" :disabled="!matriculeSaisi.trim() || chargement" @click="validerManuel">
              {{ chargement ? 'Vérification…' : 'Valider' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════ CHARGEMENT ══════════════ -->
    <div v-else-if="etape === 'chargement'" class="ts-screen ts-screen--center">
      <div class="ts-spinner"></div>
      <p class="ts-loading">Vérification de votre profil…</p>
    </div>

    <!-- ══════════════ REFUS — pas de niveau 1 à 5 ══════════════ -->
    <div v-else-if="etape === 'refus'" class="ts-screen ts-screen--center">
      <div class="ts-status-icon ts-status-icon--warn">!</div>
      <h2 class="ts-status-title">Niveau non déterminé</h2>
      <p class="ts-status-sub">{{ messageEcran }}</p>
      <button class="ts-btn ts-btn--outline" @click="reinitialiser">← Recommencer</button>
    </div>

    <!-- ══════════════ TEST INDISPONIBLE ══════════════ -->
    <div v-else-if="etape === 'indisponible'" class="ts-screen ts-screen--center">
      <div class="ts-status-icon ts-status-icon--warn">⏳</div>
      <h2 class="ts-status-title">Test pas encore disponible</h2>
      <p class="ts-status-sub">{{ messageEcran }}</p>
      <button class="ts-btn ts-btn--outline" @click="reinitialiser">← Recommencer</button>
    </div>

    <!-- ══════════════ DÉJÀ COMPOSÉ ══════════════ -->
    <div v-else-if="etape === 'deja_compose'" class="ts-screen ts-screen--center">
      <div class="ts-status-icon ts-status-icon--warn">✓</div>
      <h2 class="ts-status-title">Déjà composé</h2>
      <p class="ts-status-sub">Vous avez déjà composé le test de sortie de votre niveau. Une seule tentative est autorisée.</p>
      <button class="ts-btn ts-btn--outline" @click="reinitialiser">← Recommencer</button>
    </div>

    <!-- ══════════════ ERREUR TECHNIQUE ══════════════ -->
    <div v-else-if="etape === 'erreur'" class="ts-screen ts-screen--center">
      <div class="ts-status-icon ts-status-icon--danger">✕</div>
      <h2 class="ts-status-title">Une erreur est survenue</h2>
      <p class="ts-status-sub">{{ messageEcran }}</p>
      <button class="ts-btn ts-btn--outline" @click="reinitialiser">← Recommencer</button>
    </div>

    <!-- ══════════════ PROFIL + NIVEAU DÉTECTÉ ══════════════ -->
    <div v-else-if="etape === 'infos'" class="ts-screen ts-screen--info">
      <div class="ts-wrap">
        <div class="ts-brand">
          <span class="ts-brand__icon">◈</span>
          <span class="ts-brand__label">SÉNAFOI</span>
        </div>
        <h2 class="ts-title">Bienvenue</h2>

        <div class="ts-profile-card">
          <div class="ts-avatar">
            <img v-if="participant.photo" :src="participant.photo" alt="" class="ts-avatar__img" />
            <span v-else class="ts-avatar__initials">{{ initiales }}</span>
          </div>
          <div class="ts-profile-name">{{ participant.prenom }} {{ participant.nom }}</div>
          <div class="ts-profile-matricule">{{ participant.matricule }}</div>
        </div>

        <div class="ts-niveau-hero">
          <div class="ts-niveau-hero__label">Votre niveau</div>
          <div class="ts-niveau-hero__value">{{ participant.niveau_seminaire }}</div>
        </div>

        <div class="ts-testcard">
          <div class="ts-testcard__title">{{ test.titre }}</div>
          <div class="ts-stats-row">
            <div class="ts-stat-pill">
              <span class="ts-stat-pill__value">{{ test.nb_questions }}</span>
              <span class="ts-stat-pill__label">Questions</span>
            </div>
            <div class="ts-stat-pill">
              <span class="ts-stat-pill__value">{{ test.total_points }}</span>
              <span class="ts-stat-pill__label">Points</span>
            </div>
            <div class="ts-stat-pill">
              <span class="ts-stat-pill__value">{{ test.duree_minutes }}</span>
              <span class="ts-stat-pill__label">Minutes</span>
            </div>
          </div>
        </div>

        <div class="ts-rules">
          <div class="ts-rules__title">Instructions</div>
          <ul class="ts-rules__list">
            <li>Vous composez le test propre à votre niveau : {{ participant.niveau_seminaire }}</li>
            <li>Une seule tentative est autorisée</li>
            <li>Chaque question vaut 1 point, soit {{ test.total_points }} points au total</li>
            <li>Soumission automatique en fin de temps</li>
          </ul>
        </div>

        <div class="ts-info-actions">
          <button class="ts-btn ts-btn--ghost" @click="reinitialiser">← Rescanner</button>
          <button class="ts-btn ts-btn--primary" :disabled="demarrage" @click="lancerTest">
            {{ demarrage ? 'Démarrage…' : 'Commencer le test →' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ══════════════ QUESTIONNAIRE ══════════════ -->
    <div v-else-if="etape === 'quiz'" class="ts-quiz">
      <header class="ts-quiz__header">
        <div class="ts-quiz__meta">
          <span class="ts-quiz__name">{{ test.titre }}</span>
          <div class="ts-timer" :class="{ 'ts-timer--urgent': tempsRestant < 300 }">
            ⏱ {{ formatTemps(tempsRestant) }}
          </div>
        </div>
        <div class="ts-progress-wrap">
          <div class="ts-progress-track">
            <div class="ts-progress-fill" :style="`width: ${((indexCourant + 1) / questions.length) * 100}%`"></div>
          </div>
          <span class="ts-progress-label">{{ indexCourant + 1 }} / {{ questions.length }}</span>
        </div>
      </header>

      <div v-if="reprise" class="ts-resume-banner">
        <span>↻</span>
        <div><strong>Session reprise</strong><p>Vos réponses et le temps restant ont été restaurés.</p></div>
        <button class="ts-resume-banner__x" @click="reprise = false">✕</button>
      </div>

      <main class="ts-quiz__body">
        <div class="ts-question-num">Question {{ indexCourant + 1 }}</div>
        <div class="ts-question-text">{{ questions[indexCourant]?.question }}</div>

        <div class="ts-options">
          <label
            v-for="option in ['A', 'B', 'C', 'D']" :key="option" class="ts-option"
            :class="{
              'ts-option--selected': reponses[indexCourant] === option,
              'ts-option--filled': reponses[indexCourant] !== null && reponses[indexCourant] !== option,
            }"
          >
            <input
              type="radio" :name="`q_${indexCourant}`" :value="option"
              v-model="reponses[indexCourant]" class="ts-option__radio"
            />
            <span class="ts-option__letter">{{ option }}</span>
            <span class="ts-option__text">{{ questions[indexCourant]?.[`reponse_${option.toLowerCase()}`] }}</span>
            <span class="ts-option__check">✓</span>
          </label>
        </div>

        <div class="ts-qnav">
          <div class="ts-qnav__label">Navigation rapide</div>
          <div class="ts-qnav__grid">
            <button
              v-for="(_, idx) in questions" :key="idx" class="ts-qnav__dot"
              :class="{ 'ts-qnav__dot--current': idx === indexCourant, 'ts-qnav__dot--answered': reponses[idx] !== null && idx !== indexCourant }"
              @click="allerA(idx)"
            >{{ idx + 1 }}</button>
          </div>
        </div>
      </main>

      <nav class="ts-quiz__footer">
        <button class="ts-nav-btn ts-nav-btn--prev" :disabled="indexCourant === 0" @click="precedent">
          ← Précédent
        </button>
        <button v-if="indexCourant < questions.length - 1" class="ts-nav-btn ts-nav-btn--next" @click="suivant">
          Suivant →
        </button>
        <button v-else class="ts-nav-btn ts-nav-btn--finish" @click="confirmer = true">
          ✓ Terminer
        </button>
      </nav>
    </div>

    <!-- ══════════════ FIN — pas de score affiché ══════════════ -->
    <div v-else-if="etape === 'termine'" class="ts-screen ts-screen--center">
      <div class="ts-status-icon ts-status-icon--ok">✓</div>
      <h2 class="ts-status-title">Résultat soumis</h2>
      <p class="ts-status-sub">Vous serez informé de votre résultat.</p>
      <p class="ts-status-thanks">Merci pour votre participation. Qu'Allah vous récompense.</p>
      <button class="ts-btn ts-btn--outline" @click="reinitialiser">← Retour à l'accueil</button>
    </div>

    <!-- ══════════════ MODAL CONFIRMATION ══════════════ -->
    <div v-if="confirmer" class="ts-modal-backdrop" @click.self="confirmer = false">
      <div class="ts-modal ts-modal--confirm">
        <div class="ts-modal__icon">⚡</div>
        <h3>Terminer le test ?</h3>
        <p>{{ nonRepondues > 0 ? `${nonRepondues} question(s) sans réponse. ` : '' }}Cette action est irréversible.</p>
        <div class="ts-modal__actions">
          <button class="ts-btn ts-btn--ghost" @click="confirmer = false">Continuer</button>
          <button class="ts-btn ts-btn--primary" :disabled="soumission" @click="soumettre">
            {{ soumission ? 'Envoi…' : 'Confirmer' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { BrowserMultiFormatReader } from '@zxing/library'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_test_sortie_api.php'
const STORAGE = 'senafoi_test_sortie'

export default {
  name: 'TestSortie',
  data() {
    return {
      etape: 'identification',
      erreur: '',
      messageEcran: '',

      scanEnCours: false,
      lecteur: null,
      showManuel: false,
      matriculeSaisi: '',
      chargement: false,

      participant: {},
      test: {},
      matricule: '',

      questions: [],
      reponses: {},
      indexCourant: 0,
      tempsRestant: 0,
      timer: null,
      reprise: false,
      saveTick: 0,

      demarrage: false,
      confirmer: false,
      soumission: false,
    }
  },
  computed: {
    initiales() {
      const n = (this.participant.prenom || '').charAt(0);
      const m = (this.participant.nom || '').charAt(0);
      return (n + m).toUpperCase() || '?';
    },
    nonRepondues() {
      return Object.values(this.reponses).filter((r) => r === null).length;
    },
  },
  mounted() {
    window.addEventListener('beforeunload', this.sauverEtat);
    document.addEventListener('visibilitychange', this.surVisibilite);
  },
  beforeUnmount() {
    this.sauverEtat();
    if (this.timer) clearInterval(this.timer);
    this.arreterScanner();
    window.removeEventListener('beforeunload', this.sauverEtat);
    document.removeEventListener('visibilitychange', this.surVisibilite);
  },
  methods: {
    /* ─── Scan QR (identique au poste de présence, fonctionnel) ────────── */
    async demarrerScanner() {
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
            this.arreterScanner();
            this.identifier(this.extraireMatricule(texte));
          }
        );
      } catch (e) {
        this.scanEnCours = false;
        this.erreur = "Caméra indisponible : " + e.message + ". Saisissez votre matricule à la main.";
      }
    },
    arreterScanner() {
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
    validerManuel() {
      if (!this.matriculeSaisi.trim()) return;
      const m = this.matriculeSaisi.trim().toUpperCase();
      this.showManuel = false;
      this.matriculeSaisi = '';
      this.identifier(m);
    },

    /* ─── Identification et routage vers le bon test ────────────────── */
    async identifier(matricule) {
      if (!matricule) return;
      this.matricule = matricule;
      this.etape = 'chargement';
      this.chargement = true;
      try {
        const res = await fetch(`${API}?action=identifier`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule }),
        });
        const data = await res.json();

        if (data.refus) { this.messageEcran = data.message; this.etape = 'refus'; return; }
        if (data.indisponible) { this.messageEcran = data.message; this.etape = 'indisponible'; return; }
        if (!data.success) { this.messageEcran = data.message || 'Matricule introuvable.'; this.etape = 'erreur'; return; }

        this.participant = data.participant;
        this.test = data.test;

        if (data.deja_compose) { this.etape = 'deja_compose'; return; }
        this.etape = 'infos';
      } catch (e) {
        this.messageEcran = 'Connexion impossible : ' + e.message;
        this.etape = 'erreur';
      } finally {
        this.chargement = false;
      }
    },

    /* ─── Persistance locale (reprise après fermeture) ──────────────── */
    cleSession() { return `${STORAGE}_${this.matricule || 'anonyme'}`; },
    sauverEtat() {
      if (this.etape !== 'quiz') return;
      try {
        localStorage.setItem(this.cleSession(), JSON.stringify({
          matricule: this.matricule, reponses: this.reponses,
          indexCourant: this.indexCourant, tempsRestant: this.tempsRestant, savedAt: Date.now(),
        }));
      } catch (e) { /* mode privé ou quota dépassé */ }
    },
    effacerEtat() {
      try { localStorage.removeItem(this.cleSession()); } catch (e) { /* ignore */ }
    },
    surVisibilite() { if (document.hidden) this.sauverEtat(); },

    /* ─── Déroulé du test ─────────────────────────────────────────── */
    async lancerTest() {
      this.demarrage = true;
      try {
        const res = await fetch(`${API}?action=demarrer`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: this.matricule }),
        });
        const data = await res.json();
        if (!data.success) { this.messageEcran = data.message; this.etape = 'erreur'; return; }

        this.questions = data.questions || [];
        this.tempsRestant = data.temps_restant;

        // Reprise d'une copie interrompue si elle existe et reste valide
        let restaure = null;
        try {
          const brut = localStorage.getItem(this.cleSession());
          restaure = brut ? JSON.parse(brut) : null;
        } catch (e) { restaure = null; }

        const initiales = {};
        for (let i = 0; i < this.questions.length; i++) initiales[i] = null;

        if (data.reponses_sauvees) {
          this.questions.forEach((q, i) => {
            initiales[i] = data.reponses_sauvees[i] ?? null;
          });
        } else if (restaure && restaure.reponses) {
          for (let i = 0; i < this.questions.length; i++) {
            initiales[i] = restaure.reponses[i] ?? null;
          }
          this.reprise = true;
        }
        this.reponses = initiales;

        this.etape = 'quiz';
        this.demarrerChrono();
      } catch (e) {
        this.messageEcran = 'Connexion impossible : ' + e.message;
        this.etape = 'erreur';
      } finally {
        this.demarrage = false;
      }
    },
    demarrerChrono() {
      if (this.timer) clearInterval(this.timer);
      this.timer = setInterval(() => {
        this.tempsRestant--;
        if (++this.saveTick % 3 === 0) this.sauverEtat();
        if (this.tempsRestant <= 0) this.tempsEcoule();
      }, 1000);
    },
    tempsEcoule() {
      clearInterval(this.timer);
      alert('Temps écoulé ! Soumission automatique.');
      setTimeout(() => this.soumettre(), 1200);
    },
    scrollHaut() { window.scrollTo({ top: 0, behavior: 'smooth' }); },
    suivant() { if (this.indexCourant < this.questions.length - 1) { this.indexCourant++; this.scrollHaut(); } },
    precedent() { if (this.indexCourant > 0) { this.indexCourant--; this.scrollHaut(); } },
    allerA(i) { this.indexCourant = i; this.scrollHaut(); this.sauverEtat(); },

    async soumettre() {
      this.confirmer = false;
      this.soumission = true;
      if (this.timer) clearInterval(this.timer);
      const dureeSecondes = (this.test.duree_minutes || 20) * 60;
      const tempsEcoule = Math.max(0, dureeSecondes - Math.max(0, this.tempsRestant));
      try {
        const res = await fetch(`${API}?action=soumettre`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            matricule: this.matricule,
            reponses: Object.values(this.reponses),
            temps_ecoule: tempsEcoule,
          }),
        });
        const data = await res.json();
        if (!data.success) { this.messageEcran = data.message; this.etape = 'erreur'; return; }
        this.effacerEtat();
        this.etape = 'termine';
      } catch (e) {
        this.messageEcran = 'Connexion impossible : ' + e.message;
        this.etape = 'erreur';
      } finally {
        this.soumission = false;
      }
    },

    formatTemps(s) {
      const v = Math.max(0, s);
      return `${String(Math.floor(v / 60)).padStart(2, '0')}:${String(v % 60).padStart(2, '0')}`;
    },
    reinitialiser() {
      if (this.timer) clearInterval(this.timer);
      this.effacerEtat();
      Object.assign(this.$data, this.$options.data.call(this));
    },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.ts-root {
  --c-ink: #0f1117; --c-ink-soft: #3a3d4a; --c-muted: #8a8fa8; --c-line: #e4e6f0;
  --c-bg: #f7f8fc; --c-surface: #ffffff;
  --c-accent: #0f5132; --c-accent-light: var(--green-mist); --c-accent-dark: #0b3d26;
  --c-success: #0e9f6e; --c-success-light: var(--green-mist);
  --c-warn: var(--gold); --c-warn-light: var(--gold-mist);
  --c-danger: #dc2626; --c-danger-light: #fee2e2;
  --r: 14px; --r-sm: 8px; --r-xl: 24px;
  --shadow: 0 2px 16px rgba(15,17,35,0.08); --shadow-lg: 0 8px 40px rgba(15,17,35,0.14);
  font-family: var(--display);
  background: var(--c-bg); color: var(--c-ink); min-height: 100dvh;
  -webkit-font-smoothing: antialiased;
}

.ts-brand { display: flex; align-items: center; gap: 8px; justify-content: center; margin-bottom: 28px; }
.ts-brand__icon { font-size: 22px; color: var(--c-accent); }
.ts-brand__label { font-weight: 900; letter-spacing: .1em; font-size: 13px; color: var(--c-ink-soft); }

.ts-screen { min-height: 100dvh; padding: 40px 20px; display: flex; flex-direction: column;
             align-items: center; justify-content: center; }
.ts-screen--center { text-align: center; gap: 10px; }
.ts-wrap { width: 100%; max-width: 460px; }

.ts-title { font-size: 24px; font-weight: 900; text-align: center; margin-bottom: 6px; }
.ts-sub { text-align: center; color: var(--c-muted); font-size: 13.5px; margin-bottom: 22px; }

/* Scanner */
.ts-video-frame { position: relative; width: 100%; aspect-ratio: 1; border-radius: var(--r-xl);
                  overflow: hidden; background: #0a0c0e; margin-bottom: 18px; box-shadow: var(--shadow-lg); }
.ts-video { width: 100%; height: 100%; object-fit: cover; }
.ts-scan-overlay { position: absolute; inset: 14%; pointer-events: none; }
.ts-corner { position: absolute; width: 28px; height: 28px; border: 3px solid var(--c-accent); }
.ts-corner.tl { top: 0; left: 0; border-right: 0; border-bottom: 0; border-top-left-radius: 8px; }
.ts-corner.tr { top: 0; right: 0; border-left: 0; border-bottom: 0; border-top-right-radius: 8px; }
.ts-corner.bl { bottom: 0; left: 0; border-right: 0; border-top: 0; border-bottom-left-radius: 8px; }
.ts-corner.br { bottom: 0; right: 0; border-left: 0; border-top: 0; border-bottom-right-radius: 8px; }
.ts-scan-line { position: absolute; left: 0; right: 0; top: 0; height: 2px; background: var(--c-accent);
                opacity: 0; box-shadow: var(--shadow); }
.ts-scan-line.active { opacity: 1; animation: ts-scan 1.8s ease-in-out infinite; }
@keyframes ts-scan { 0% { top: 0; } 50% { top: 100%; } 100% { top: 0; } }

.ts-alert { border-radius: var(--r-sm); padding: 10px 13px; font-size: 13px; font-weight: 600; margin-bottom: 14px; }
.ts-alert--danger { background: var(--c-danger-light); color: var(--c-danger); }

.ts-actions { display: flex; flex-direction: column; gap: 10px; }

.ts-btn { border: 0; border-radius: 999px; padding: 13px 22px; font-size: 14px; font-weight: 800;
          cursor: pointer; font-family: inherit; display: inline-flex; align-items: center;
          justify-content: center; gap: 7px; transition: transform .12s, opacity .2s; }
.ts-btn:active { transform: scale(.97); }
.ts-btn:disabled { opacity: .55; cursor: default; }
.ts-btn--primary { background: var(--c-accent); color: #fff; }
.ts-btn--danger { background: var(--c-danger); color: #fff; }
.ts-btn--outline { background: transparent; color: var(--c-accent); border: 2px solid var(--c-accent-light); }
.ts-btn--ghost { background: var(--c-bg); color: var(--c-ink-soft); }

/* Modales */
.ts-modal-backdrop { position: fixed; inset: 0; background: rgba(15,17,35,.55); backdrop-filter: blur(3px);
                      display: grid; place-items: center; z-index: 50; padding: 16px; }
.ts-modal { background: #fff; border-radius: var(--r-xl); padding: 22px; width: 100%; max-width: 380px;
            box-shadow: var(--shadow-lg); }
.ts-modal__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.ts-modal__x { border: 0; background: var(--c-bg); border-radius: 50%; width: 30px; height: 30px; cursor: pointer; }
.ts-input { width: 100%; padding: 13px 14px; border-radius: var(--r-sm); border: 1.5px solid var(--c-line);
            font-size: 15px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; margin-bottom: 16px; }
.ts-modal__actions { display: flex; gap: 10px; }
.ts-modal__actions .ts-btn { flex: 1; }
.ts-modal--confirm { text-align: center; }
.ts-modal--confirm h3 { font-size: 18px; margin: 8px 0 6px; }
.ts-modal--confirm p { color: var(--c-muted); font-size: 13.5px; margin-bottom: 18px; }
.ts-modal__icon { font-size: 30px; }

/* Chargement / statuts */
.ts-spinner { width: 42px; height: 42px; border-radius: 50%; border: 4px solid var(--c-line);
              border-top-color: var(--c-accent); animation: ts-spin 0.8s linear infinite; }
@keyframes ts-spin { to { transform: rotate(360deg); } }
.ts-loading { color: var(--c-muted); font-size: 14px; }
.ts-status-icon { width: 64px; height: 64px; border-radius: 50%; display: grid; place-items: center;
                  font-size: 28px; font-weight: 900; margin-bottom: 6px; }
.ts-status-icon--danger { background: var(--c-danger-light); color: var(--c-danger); }
.ts-status-icon--warn { background: var(--c-warn-light); color: var(--c-warn); }
.ts-status-icon--ok { background: var(--c-success-light); color: var(--c-success); }
.ts-status-title { font-size: 20px; font-weight: 900; }
.ts-status-sub { color: var(--c-ink-soft); font-size: 14px; max-width: 380px; line-height: 1.5; }
.ts-status-thanks { color: var(--c-muted); font-size: 12.5px; font-style: italic; }

/* Profil */
.ts-profile-card { background: var(--c-surface); border-radius: var(--r-xl); padding: 22px;
                    text-align: center; box-shadow: var(--shadow); margin-bottom: 16px; }
.ts-avatar { width: 76px; height: 76px; border-radius: 50%; margin: 0 auto 12px; overflow: hidden;
             background: var(--c-accent-light); display: grid; place-items: center; }
.ts-avatar__img { width: 100%; height: 100%; object-fit: cover; }
.ts-avatar__initials { font-size: 26px; font-weight: 900; color: var(--c-accent); }
.ts-profile-name { font-size: 17px; font-weight: 800; }
.ts-profile-matricule { color: var(--c-muted); font-size: 12.5px; font-weight: 700; letter-spacing: .05em; }

.ts-niveau-hero { background: linear-gradient(135deg, var(--c-accent), var(--c-accent-dark));
                  border-radius: var(--r-xl); padding: 18px; text-align: center; color: #fff; margin-bottom: 16px; }
.ts-niveau-hero__label { font-size: 11px; text-transform: uppercase; letter-spacing: .1em; opacity: .8; }
.ts-niveau-hero__value { font-size: 30px; font-weight: 900; margin-top: 4px; }

.ts-testcard { background: var(--c-surface); border-radius: var(--r-xl); padding: 18px; box-shadow: var(--shadow); margin-bottom: 16px; }
.ts-testcard__title { font-weight: 800; font-size: 15px; margin-bottom: 12px; text-align: center; }
.ts-stats-row { display: flex; gap: 10px; }
.ts-stat-pill { flex: 1; background: var(--c-bg); border-radius: var(--r-sm); padding: 12px 6px; text-align: center; }
.ts-stat-pill__value { display: block; font-size: 20px; font-weight: 900; color: var(--c-accent); }
.ts-stat-pill__label { font-size: 10.5px; color: var(--c-muted); font-weight: 700; text-transform: uppercase; }

.ts-rules { background: var(--c-surface); border-radius: var(--r-xl); padding: 18px; box-shadow: var(--shadow); margin-bottom: 22px; }
.ts-rules__title { font-weight: 800; font-size: 13px; margin-bottom: 8px; }
.ts-rules__list { list-style: none; display: flex; flex-direction: column; gap: 7px; }
.ts-rules__list li { font-size: 13px; color: var(--c-ink-soft); padding-left: 18px; position: relative; }
.ts-rules__list li::before { content: '•'; position: absolute; left: 4px; color: var(--c-accent); font-weight: 900; }

.ts-info-actions { display: flex; gap: 10px; }
.ts-info-actions .ts-btn { flex: 1; }

/* Quiz */
.ts-quiz { max-width: 640px; margin: 0 auto; padding: 12px 16px 100px; }
.ts-quiz__header { position: sticky; top: 0; z-index: 5; background: var(--c-bg); padding: 10px 0 14px; }
.ts-quiz__meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.ts-quiz__name { font-weight: 800; font-size: 14px; }
.ts-timer { background: var(--c-accent-light); color: var(--c-accent-dark); border-radius: 999px;
            padding: 6px 13px; font-weight: 900; font-size: 13.5px; font-variant-numeric: tabular-nums; }
.ts-timer--urgent { background: var(--c-danger-light); color: var(--c-danger); animation: ts-pulse 1s infinite; }
@keyframes ts-pulse { 50% { opacity: .55; } }
.ts-progress-wrap { display: flex; align-items: center; gap: 10px; }
.ts-progress-track { flex: 1; height: 6px; border-radius: 999px; background: var(--c-line); overflow: hidden; }
.ts-progress-fill { height: 100%; background: var(--c-accent); border-radius: 999px; transition: width .3s; }
.ts-progress-label { font-size: 11.5px; color: var(--c-muted); font-weight: 700; white-space: nowrap; }

.ts-resume-banner { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding: 12px 14px;
                     border-radius: 0; background: var(--green-mist); border: 1px solid var(--green-mist); color: var(--green); font-size: 20px; }
.ts-resume-banner strong { display: block; font-size: 14px; }
.ts-resume-banner p { margin: 2px 0 0; font-size: 12.5px; opacity: .85; }
.ts-resume-banner__x { margin-left: auto; border: 0; background: transparent; color: var(--green);
                        font-size: 15px; cursor: pointer; padding: 4px 8px; }

.ts-quiz__body { background: var(--c-surface); border-radius: var(--r-xl); padding: 20px; box-shadow: var(--shadow); }
.ts-question-num { font-size: 11.5px; color: var(--c-accent); font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.ts-question-text { font-size: 17px; font-weight: 700; line-height: 1.4; margin: 8px 0 18px; }

.ts-options { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.ts-option { display: flex; align-items: center; gap: 12px; border: 2px solid var(--c-line); border-radius: var(--r);
             padding: 12px 14px; cursor: pointer; position: relative; transition: border-color .15s, background .15s; }
.ts-option:hover { border-color: var(--c-accent-light); }
.ts-option--selected { border-color: var(--c-accent); background: var(--c-accent-light); }
.ts-option__radio { position: absolute; opacity: 0; width: 0; height: 0; }
.ts-option__letter { flex-shrink: 0; width: 30px; height: 30px; border-radius: 50%; background: var(--c-bg);
                      display: grid; place-items: center; font-weight: 900; font-size: 13px; }
.ts-option--selected .ts-option__letter { background: var(--c-accent); color: #fff; }
.ts-option__text { flex: 1; font-size: 14px; line-height: 1.4; }
.ts-option__check { opacity: 0; color: var(--c-accent); font-weight: 900; }
.ts-option--selected .ts-option__check { opacity: 1; }

.ts-qnav__label { font-size: 11px; color: var(--c-muted); font-weight: 800; text-transform: uppercase; margin-bottom: 8px; }
.ts-qnav__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(34px, 1fr)); gap: 6px; }
.ts-qnav__dot { border: 1.5px solid var(--c-line); background: var(--c-surface); border-radius: 0;
                padding: 7px 0; font-size: 12px; font-weight: 700; cursor: pointer; color: var(--c-ink-soft); }
.ts-qnav__dot--answered { background: var(--c-accent-light); border-color: var(--c-accent-light); color: var(--c-accent-dark); }
.ts-qnav__dot--current { border-color: var(--c-accent); color: var(--c-accent); }

.ts-quiz__footer { position: fixed; left: 0; right: 0; bottom: 0; display: flex; gap: 10px; padding: 14px 16px;
                    background: var(--c-surface); border-top: 1px solid var(--c-line); box-shadow: var(--shadow); }
.ts-nav-btn { flex: 1; border: 0; border-radius: 999px; padding: 13px; font-weight: 800; font-size: 14px; cursor: pointer; }
.ts-nav-btn:disabled { opacity: .4; cursor: default; }
.ts-nav-btn--prev { background: var(--c-bg); color: var(--c-ink-soft); }
.ts-nav-btn--next { background: var(--c-accent); color: #fff; }
.ts-nav-btn--finish { background: var(--c-success); color: #fff; }

@media (max-width: 480px) {
  .ts-screen { padding: 28px 14px; }
}
</style>
