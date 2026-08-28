<template>
  <div class="gq-root" :class="{ 'gq-root--plateau': plateau }">
    <!-- ══════════ EN-TÊTE ══════════ -->
    <header v-if="!plateau" class="gq-topbar">
      <div class="gq-brand">
        <span class="gq-brand__dot"></span>
        <div>
          <strong>Grand Quiz AEEMCI</strong>
          <small>4 jours · 4 manches · 20 points par manche</small>
        </div>
      </div>
      <button v-if="participant" class="gq-logout" @click="deconnecter">Changer de matricule</button>
    </header>

    <!-- ══════════ 1. IDENTIFICATION ══════════ -->
    <section v-if="!participant" class="gq-screen">
      <div class="gq-card gq-card--auth">
        <div class="gq-hero-icon">🏆</div>
        <h1>Grand Quiz AEEMCI 2026</h1>
        <p class="gq-lead">
          Quatre manches, une par jour. Obtenez la moyenne de {{ seuil }}/20 à chaque manche
          pour rester en course jusqu'à la finale.
        </p>

        <div class="gq-rules">
          <div class="gq-rule"><span>⏱</span><div><strong>10 minutes</strong><small>par manche</small></div></div>
          <div class="gq-rule"><span>📋</span><div><strong>15 questions</strong><small>1 ou 2 points</small></div></div>
          <div class="gq-rule"><span>🎯</span><div><strong>{{ seuil }}/20 minimum</strong><small>pour se qualifier</small></div></div>
        </div>

        <label class="gq-field">
          <span>Votre matricule</span>
          <input
            v-model="matriculeInput"
            :disabled="loadingAuth"
            placeholder="SEM2026… / SEM26SR… / SEM26CCR…"
            @keyup.enter="identifier"
            @input="matriculeInput = matriculeInput.toUpperCase().replace(/[^A-Z0-9]/g, '')"
          />
        </label>
        <p class="gq-hint">Séminaristes SENAFOI, SR et CCR peuvent participer.</p>

        <div v-if="authError" class="gq-alert gq-alert--error">{{ authError }}</div>

        <button class="gq-btn gq-btn--primary gq-btn--lg" :disabled="loadingAuth || !matriculeInput" @click="identifier">
          {{ loadingAuth ? 'Vérification…' : 'Entrer dans le quiz' }}
        </button>

        <button class="gq-link" @click="ouvrirClassement">Voir le classement des qualifiés</button>
      </div>
    </section>

    <!-- ══════════ 2. TABLEAU DE BORD ══════════ -->
    <section v-else-if="!enCours && !resultat" class="gq-screen">
      <div class="gq-profile">
        <div class="gq-avatar">
          <img v-if="participant.photo" :src="participant.photo" alt="" />
          <span v-else>{{ initiales }}</span>
        </div>
        <div class="gq-profile__info">
          <strong>{{ participant.prenom }} {{ participant.nom }}</strong>
          <small>{{ participant.matricule }} · {{ typeLabel(participant.type) }}</small>
        </div>
        <div class="gq-profile__score">
          <span class="gq-profile__pts">{{ participant.total_points }}</span>
          <small>points cumulés</small>
        </div>
      </div>

      <div v-if="participant.statut === 'elimine'" class="gq-alert gq-alert--out">
        <strong>Parcours terminé</strong>
        <p>Vous n'avez pas atteint la moyenne lors d'une manche. Vous pouvez continuer à suivre le classement.</p>
      </div>

      <h2 class="gq-section-title">Les 4 manches</h2>
      <div class="gq-tours">
        <article
          v-for="t in tours"
          :key="t.numero"
          class="gq-tour"
          :class="[`gq-tour--${t.statut.etat}`, { 'gq-tour--done': tourJoue(t.numero) }]"
        >
          <div class="gq-tour__head">
            <span class="gq-tour__num">J{{ t.numero }}</span>
            <div>
              <strong>{{ t.titre }}</strong>
              <small>{{ t.statut.libelle }}</small>
            </div>
          </div>

          <div v-if="tourJoue(t.numero)" class="gq-tour__result">
            <span class="gq-badge" :class="histo(t.numero).admis ? 'gq-badge--ok' : 'gq-badge--ko'">
              {{ histo(t.numero).score }}/{{ histo(t.numero).total_points }}
              · {{ histo(t.numero).admis ? 'Qualifié' : 'Éliminé' }}
            </span>
          </div>

          <button
            v-else-if="t.statut.etat === 'ouvert' && peutComposer(t.numero)"
            class="gq-btn gq-btn--primary"
            :disabled="starting"
            @click="ouvrirManche(t)"
          >
            {{ starting ? 'Ouverture…' : 'Composer maintenant' }}
          </button>

          <p v-else class="gq-tour__lock">{{ raisonBlocage(t) }}</p>
        </article>
      </div>

      <button class="gq-btn gq-btn--ghost gq-btn--full" @click="ouvrirClassement">
        Voir le classement des qualifiés
      </button>
    </section>


    <!-- ══════════ 3a. PLATEAU DE JEU — une question à la fois ══════════
         Écran autonome : pas de durée globale, pas de navigation, pas de
         retour en arrière. La question passe seule quand le temps tombe. -->
    <section v-if="plateau" class="gq-show">
      <div class="gq-show__halo"></div>

      <div class="gq-show__bandeau">
        <span class="gq-show__manche">{{ tourActif.titre }}</span>
        <span class="gq-show__compte">
          <b>{{ indexCourant + 1 }}</b><i>/{{ questions.length }}</i>
        </span>
        <span class="gq-show__pts">
          {{ questions[indexCourant]?.points }} pt{{ questions[indexCourant]?.points > 1 ? 's' : '' }}
        </span>
      </div>

      <!-- La longue bande : elle se vide du vert au rouge, 15 s par question -->
      <div class="gq-show__barre" :class="'gq-show__barre--' + couleurChrono">
        <div class="gq-show__barre-fill" :style="{ width: (tempsQuestion / parQuestion * 100) + '%' }">
          <span class="gq-show__tete"></span>
        </div>
        <span class="gq-show__secondes">{{ tempsQuestion }}<i>s</i></span>
      </div>

      <main class="gq-show__body" :key="indexCourant">
        <span v-if="questions[indexCourant]?.categorie" class="gq-show__cat">
          {{ questions[indexCourant].categorie }}
        </span>

        <div class="gq-show__scene">
          <h2 class="gq-show__question">{{ questions[indexCourant]?.question }}</h2>
        </div>

        <div class="gq-show__options">
          <button
            v-for="(opt, i) in ['A', 'B', 'C', 'D']"
            :key="opt"
            class="gq-show__option"
            :class="['gq-show__option--' + i, {
              'is-choisi': reponses[indexCourant] === opt,
              'is-eteint': figee && reponses[indexCourant] !== opt,
            }]"
            :style="{ animationDelay: (i * 70) + 'ms' }"
            :disabled="figee"
            @click="repondre(opt)"
          >
            <span class="gq-show__lettre">{{ opt }}</span>
            <span class="gq-show__texte">{{ questions[indexCourant]?.['reponse_' + opt.toLowerCase()] }}</span>
          </button>
        </div>
      </main>

      <footer class="gq-show__pied">
        <div class="gq-show__fil">
          <i
            v-for="(q, i) in questions" :key="i"
            :class="{ on: i === indexCourant, fait: i < indexCourant, rep: reponses[i] }"
          ></i>
        </div>
        <p class="gq-show__note">
          <template v-if="indexCourant < questions.length - 1">
            Réponse définitive · la question suivante s'affiche toute seule
          </template>
          <template v-else>Dernière question · la copie partira automatiquement</template>
        </p>
      </footer>

      <!-- Voile de transition entre deux questions -->
      <transition name="gq-flash">
        <div v-if="verrouille" class="gq-show__flash">
          <span>{{ reponses[indexCourant] ? 'Réponse enregistrée' : 'Temps écoulé' }}</span>
        </div>
      </transition>
    </section>

    <!-- ══════════ 3b. QUIZ EN COURS — manche classique ══════════ -->
    <section v-else-if="enCours" class="gq-quiz">
        <header class="gq-quiz__bar">
          <div class="gq-quiz__meta">
            <span class="gq-quiz__title">{{ tourActif.titre }}</span>
            <div class="gq-timer" :class="{ 'gq-timer--urgent': tempsRestant < 60 }">
              ⏱ {{ formatTemps(tempsRestant) }}
            </div>
          </div>
          <div class="gq-progress">
            <div class="gq-progress__fill" :style="{ width: ((indexCourant + 1) / questions.length * 100) + '%' }"></div>
          </div>
          <div class="gq-progress__label">
            Question {{ indexCourant + 1 }} / {{ questions.length }}
            <span class="gq-pts">{{ questions[indexCourant]?.points }} pt{{ questions[indexCourant]?.points > 1 ? 's' : '' }}</span>
          </div>
        </header>

        <main class="gq-quiz__body">
          <span v-if="questions[indexCourant]?.categorie" class="gq-cat">{{ questions[indexCourant].categorie }}</span>
          <h2 class="gq-question">{{ questions[indexCourant]?.question }}</h2>

          <div class="gq-options">
            <button
              v-for="opt in ['A', 'B', 'C', 'D']"
              :key="opt"
              class="gq-option"
              :class="{ 'gq-option--selected': reponses[indexCourant] === opt }"
              @click="repondre(opt)"
            >
              <span class="gq-option__letter">{{ opt }}</span>
              <span class="gq-option__text">{{ questions[indexCourant]?.['reponse_' + opt.toLowerCase()] }}</span>
            </button>
          </div>

          <div class="gq-dots">
            <button
              v-for="(q, i) in questions"
              :key="i"
              class="gq-dot"
              :class="{ 'gq-dot--current': i === indexCourant, 'gq-dot--answered': reponses[i] }"
              @click="indexCourant = i"
            >{{ i + 1 }}</button>
          </div>
        </main>

      <footer class="gq-quiz__nav">
        <button class="gq-btn gq-btn--ghost" :disabled="indexCourant === 0" @click="indexCourant--">Précédent</button>
        <button v-if="indexCourant < questions.length - 1" class="gq-btn gq-btn--primary" @click="indexCourant++">Suivant</button>
        <button v-else class="gq-btn gq-btn--send" :disabled="submitting" @click="confirmer = true">Terminer</button>
      </footer>

      <div v-if="confirmer" class="gq-modal" @click.self="confirmer = false">
        <div class="gq-modal__box">
          <h3>Terminer la manche ?</h3>
          <p v-if="nonRepondues">{{ nonRepondues }} question(s) sans réponse seront comptées fausses.</p>
          <p v-else>Toutes les questions sont répondues.</p>
          <div class="gq-modal__actions">
            <button class="gq-btn gq-btn--ghost" @click="confirmer = false">Continuer</button>
            <button class="gq-btn gq-btn--send" :disabled="submitting" @click="soumettre()">
              {{ submitting ? 'Envoi…' : 'Valider' }}
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ 4. RÉSULTAT ══════════ -->
    <section v-else-if="resultat" class="gq-screen">
      <div class="gq-card gq-card--result" :class="resultat.admis ? 'gq-card--win' : 'gq-card--lose'">
        <div class="gq-result-icon">{{ resultat.admis ? '🎉' : '💪' }}</div>
        <div class="gq-score-big">{{ resultat.score }}<small>/{{ resultat.total_points }}</small></div>
        <p class="gq-result-sub">{{ resultat.correctes }} bonne(s) réponse(s) sur {{ resultat.nb_questions }}</p>

        <div class="gq-verdict" :class="resultat.admis ? 'gq-verdict--ok' : 'gq-verdict--ko'">
          {{ resultat.admis ? 'QUALIFIÉ' : 'ÉLIMINÉ' }}
        </div>
        <p class="gq-result-msg">{{ resultat.message_suite }}</p>

        <div class="gq-result-stats">
          <div><strong>{{ resultat.total_cumule }}</strong><small>points cumulés</small></div>
          <div v-if="resultat.rang"><strong>{{ resultat.rang }}<sup>e</sup></strong><small>au classement</small></div>
        </div>

        <button class="gq-btn gq-btn--primary gq-btn--full" @click="ouvrirClassement">Voir le classement</button>
        <button class="gq-btn gq-btn--ghost gq-btn--full" @click="retourAccueil">Retour au tableau de bord</button>
      </div>
    </section>

    <!-- ══════════ AVERTISSEMENT — nouveau format ══════════
         Rendu hors de la chaîne v-if/v-else-if des écrans : une branche
         antérieure était toujours vraie et l'avalait. -->
    <div v-if="avertissement" class="gq-modal gq-modal--alerte">
      <div class="gq-alerte">
        <div class="gq-alerte__tete">
          <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" />
            <line x1="12" y1="9" x2="12" y2="13" /><line x1="12" y1="17" x2="12.01" y2="17" />
          </svg>
          <strong>Attention — du nouveau dans cette manche</strong>
        </div>

        <ul class="gq-alerte__liste">
          <li>Chaque question doit être répondue en
            <b>{{ avertissement.secondes_par_question }} secondes</b>.</li>
          <li>Une longue barre se vide en passant du <b>vert</b> à l'<b>orange</b>
            puis au <b>rouge</b>.</li>
          <li>Dès que vous touchez une réponse, elle est <b>validée</b> et la question
            suivante s'affiche. Pas de bouton « Suivant ».</li>
          <li>Si le temps s'épuise, la question est comptée <b>non répondue</b> et
            <b>on passe simplement à la suivante</b> — le quiz continue.</li>
          <li>La réponse choisie est <b>définitive</b> : ni changement d'avis,
            ni retour en arrière.</li>
          <li>Il n'y a <b>plus de durée globale</b> pour la manche : seul le
            chrono de chaque question compte.</li>
          <li>Après la dernière question, la copie part <b>automatiquement</b>.</li>
        </ul>

        <p class="gq-alerte__duree">
          {{ avertissement.nb_questions || '—' }} questions ·
          {{ avertissement.secondes_par_question }} s chacune
        </p>

        <div class="gq-alerte__actions">
          <button class="gq-btn gq-btn--ghost" @click="avertissement = null">Revenir</button>
          <button class="gq-btn gq-btn--send" :disabled="starting" @click="lancerApresAvertissement">
            {{ starting ? 'Ouverture…' : "J'ai compris, commencer" }}
          </button>
        </div>
      </div>
    </div>

    <!-- ══════════ CLASSEMENT ══════════ -->
    <div v-if="showClassement" class="gq-modal" @click.self="showClassement = false">
      <div class="gq-modal__box gq-modal__box--lg">
        <div class="gq-modal__head">
          <h3>Classement des qualifiés</h3>
          <button class="gq-modal__x" @click="showClassement = false">✕</button>
        </div>
        <p class="gq-modal__sub">Classement par points cumulés, du meilleur au dernier.</p>

        <div v-if="loadingClassement" class="gq-loading">Chargement…</div>
        <div v-else-if="!classement.length" class="gq-empty">Aucun participant classé pour le moment.</div>
        <ol v-else class="gq-rank">
          <li
            v-for="r in classement"
            :key="r.matricule"
            class="gq-rank__row"
            :class="{ 'gq-rank__row--me': participant && r.matricule === participant.matricule, 'gq-rank__row--out': r.statut === 'elimine' }"
          >
            <span class="gq-rank__pos" :class="'gq-rank__pos--' + (r.rang <= 3 ? r.rang : 'n')">{{ r.rang }}</span>
            <div class="gq-rank__who">
              <strong>{{ r.prenom }} {{ r.nom }}</strong>
              <small>{{ r.matricule }} · manche {{ r.dernier_tour }}{{ r.statut === 'elimine' ? ' · éliminé' : '' }}</small>
            </div>
            <span class="gq-rank__pts">{{ r.total_points }} pts</span>
          </li>
        </ol>
      </div>
    </div>
  </div>
</template>

<script>
const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_quiz_api.php'
const STORAGE = 'aeemci_grand_quiz'

export default {
  name: 'GrandQuizJeu',
  data() {
    return {
      matriculeInput: '',
      loadingAuth: false,
      authError: '',
      participant: null,
      historique: [],
      tours: [],
      seuil: 10,

      starting: false,
      enCours: false,
      tourActif: null,
      questions: [],
      reponses: {},
      indexCourant: 0,
      tempsRestant: 0,
      timer: null,
      confirmer: false,
      submitting: false,

      // Mode « une question à la fois »
      avertissement: null,     // manche en attente de confirmation
      tempsQuestion: 0,        // secondes restantes sur la question affichée
      timerQuestion: null,
      verrouille: false,       // pendant la bascule vers la question suivante
      debutManche: 0,          // horodatage du départ, sert au temps écoulé
      resultat: null,

      showClassement: false,
      classement: [],
      loadingClassement: false,
    }
  },
  computed: {
    initiales() {
      if (!this.participant) return '?'
      return ((this.participant.prenom?.[0] || '') + (this.participant.nom?.[0] || '')).toUpperCase() || '?'
    },
    nonRepondues() {
      return this.questions.length - Object.values(this.reponses).filter(Boolean).length
    },
    /** Secondes accordées par question ; 0 = manche classique. */
    parQuestion() {
      return Number(this.tourActif?.secondes_par_question || 0)
    },
    /** Vrai quand on doit afficher le plateau chronométré plein écran. */
    plateau() {
      return this.enCours && this.parQuestion > 0
    },
    /** Réponse déjà donnée : le choix est définitif, plus rien n'est cliquable. */
    figee() {
      return this.verrouille || Boolean(this.reponses[this.indexCourant])
    },
    /** Vert au départ, orange à la moitié, rouge dans le dernier tiers. */
    couleurChrono() {
      if (!this.parQuestion) return 'vert'
      const part = this.tempsQuestion / this.parQuestion
      if (part > 0.5) return 'vert'
      if (part > 0.25) return 'orange'
      return 'rouge'
    },
  },
  mounted() {
    this.chargerTours()
    const saved = this.lireSession()
    if (saved?.matricule) {
      this.matriculeInput = saved.matricule
      this.identifier(true)
    }
    window.addEventListener('beforeunload', this.sauverEtat)
    document.addEventListener('visibilitychange', this.sauverEtat)
  },
  beforeUnmount() {
    this.sauverEtat()
    if (this.timer) clearInterval(this.timer)
    if (this.timerQuestion) clearInterval(this.timerQuestion)
    window.removeEventListener('beforeunload', this.sauverEtat)
    document.removeEventListener('visibilitychange', this.sauverEtat)
  },
  watch: {
    reponses: { deep: true, handler() { this.sauverEtat() } },
  },
  methods: {
    typeLabel(t) {
      return { seminariste: 'Séminariste SENAFOI', sr: 'Séminariste SR', ccr: 'Séminariste CCR' }[t] || 'Participant'
    },
    // ── Persistance locale ──────────────────────────────────────────────
    cleSession() { return `${STORAGE}_${this.participant?.matricule || this.matriculeInput || 'anon'}` },
    sauverEtat() {
      try {
        if (this.participant) {
          localStorage.setItem(STORAGE, JSON.stringify({ matricule: this.participant.matricule }))
        }
        if (this.enCours && this.tourActif) {
          localStorage.setItem(this.cleSession(), JSON.stringify({
            numero: this.tourActif.numero,
            reponses: this.reponses,
            indexCourant: this.indexCourant,
            tempsRestant: this.tempsRestant,
          }))
        }
      } catch (e) { /* mode prive */ }
    },
    lireSession() {
      try { return JSON.parse(localStorage.getItem(STORAGE) || 'null') } catch (e) { return null }
    },
    lireEtatQuiz() {
      try { return JSON.parse(localStorage.getItem(this.cleSession()) || 'null') } catch (e) { return null }
    },
    effacerEtatQuiz() {
      try { localStorage.removeItem(this.cleSession()) } catch (e) { /* ignore */ }
    },
    deconnecter() {
      try { localStorage.removeItem(STORAGE) } catch (e) { /* ignore */ }
      if (this.timer) clearInterval(this.timer)
      Object.assign(this.$data, this.$options.data.call(this))
      this.chargerTours()
    },

    // ── Chargement ──────────────────────────────────────────────────────
    async chargerTours() {
      try {
        const res = await fetch(`${API}?action=tours&rand=${Math.random()}`)
        const data = await res.json()
        if (data.success) { this.tours = data.tours || []; this.seuil = data.seuil || 10 }
      } catch (e) { /* silencieux */ }
    },
    async identifier(silencieux = false) {
      if (!this.matriculeInput) return
      this.loadingAuth = true
      this.authError = ''
      try {
        const res = await fetch(`${API}?action=identify`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: this.matriculeInput }),
        })
        const data = await res.json()
        if (!data.success) throw new Error(data.message || 'Matricule introuvable')
        this.participant = data.participant
        this.historique = data.historique || []
        this.tours = data.tours || this.tours
        this.sauverEtat()
        this.reprendreSiBesoin()
      } catch (e) {
        if (!silencieux) this.authError = e.message
        else { try { localStorage.removeItem(STORAGE) } catch (er) { /* ignore */ } }
      } finally {
        this.loadingAuth = false
      }
    },
    // Reprend une manche interrompue si le créneau est encore ouvert
    async reprendreSiBesoin() {
      const etat = this.lireEtatQuiz()
      if (!etat?.numero) return
      const tour = this.tours.find(t => Number(t.numero) === Number(etat.numero))
      if (!tour || tour.statut.etat !== 'ouvert') { this.effacerEtatQuiz(); return }
      if (this.tourJoue(etat.numero)) { this.effacerEtatQuiz(); return }
      await this.demarrer(etat.numero, etat)
    },

    histo(numero) {
      return this.historique.find(h => Number(h.numero_tour) === Number(numero)) || {}
    },
    tourJoue(numero) {
      return this.historique.some(h => Number(h.numero_tour) === Number(numero))
    },
    peutComposer(numero) {
      if (!this.participant) return false
      if (this.participant.statut === 'elimine') return false
      if (this.tourJoue(numero)) return false
      return numero === 1 || this.participant.dernier_tour >= numero - 1
    },
    raisonBlocage(t) {
      if (this.tourJoue(t.numero)) return 'Manche déjà composée'
      if (this.participant?.statut === 'elimine') return 'Vous n\'êtes plus en course'
      if (t.numero > 1 && this.participant?.dernier_tour < t.numero - 1) {
        return `Réservé aux qualifiés de la manche ${t.numero - 1}`
      }
      return t.statut.libelle
    },

    // ── Déroulé du quiz ─────────────────────────────────────────────────
    async demarrer(numero, etatSauve = null) {
      this.starting = true
      try {
        const res = await fetch(`${API}?action=start`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: this.participant.matricule, numero }),
        })
        const data = await res.json()
        if (!data.success) throw new Error(data.message)

        // La réponse de « start » n'a pas toujours porté le format chronométré.
        // La liste des manches, elle, l'expose depuis toujours : on la garde
        // comme référence pour ne dépendre d'aucune version du serveur.
        const fiche = this.tours.find(t => Number(t.numero) === Number(numero)) || {}
        this.tourActif = {
          ...data.tour,
          secondes_par_question: Number(
            data.tour?.secondes_par_question ?? fiche.secondes_par_question ?? 0
          ),
        }
        this.questions = data.questions || []
        this.reponses = etatSauve?.reponses || {}
        this.indexCourant = etatSauve?.indexCourant || 0
        // Le serveur fait foi sur le temps restant (anti-triche)
        this.tempsRestant = data.temps_restant
        this.enCours = true
        this.resultat = null
        this.verrouille = false
        this.debutManche = Date.now()

        if (this.parQuestion > 0) {
          // Une question à la fois : la durée en minutes de la manche ne
          // s'applique plus du tout. Le seul chronomètre est celui de la
          // question affichée ; aucun compte à rebours global ne tourne.
          this.lancerTimerQuestion()
        } else {
          this.lancerTimer()
        }
      } catch (e) {
        alert(e.message)
        await this.chargerTours()
      } finally {
        this.starting = false
      }
    },
    lancerTimer() {
      if (this.timer) clearInterval(this.timer)
      this.timer = setInterval(() => {
        this.tempsRestant--
        if (this.tempsRestant % 3 === 0) this.sauverEtat()
        if (this.tempsRestant <= 0) {
          clearInterval(this.timer)
          this.soumettre(true)
        }
      }, 1000)
    },

    /* ── Mode « une question à la fois » ──────────────────────────── */

    /**
     * Le format ayant changé en cours de compétition, on prévient avant
     * d'ouvrir la manche : personne ne doit découvrir le chronomètre
     * une fois la première question affichée.
     */
    ouvrirManche(tour) {
      const parQuestion = Number(tour.secondes_par_question || 0)
      if (parQuestion > 0) {
        this.avertissement = {
          numero: tour.numero,
          secondes_par_question: parQuestion,
          nb_questions: tour.nb_questions || 0,
        }
        return
      }
      this.demarrer(tour.numero)
    },

    lancerApresAvertissement() {
      const numero = this.avertissement?.numero
      this.avertissement = null
      if (numero) this.demarrer(numero)
    },

    /** Démarre le compte à rebours de la question affichée. */
    lancerTimerQuestion() {
      if (this.timerQuestion) clearInterval(this.timerQuestion)
      if (!this.parQuestion) return

      this.tempsQuestion = this.parQuestion
      this.timerQuestion = setInterval(() => {
        this.tempsQuestion--
        if (this.tempsQuestion <= 0) {
          clearInterval(this.timerQuestion)
          // Temps écoulé : la question reste sans réponse et l'on avance.
          this.avancer()
        }
      }, 1000)
    },

    /**
     * Passe à la question suivante, ou soumet si c'était la dernière.
     * Un court verrou évite le double appui pendant la bascule.
     */
    avancer() {
      if (this.verrouille) return
      this.verrouille = true
      if (this.timerQuestion) clearInterval(this.timerQuestion)

      const derniere = this.indexCourant >= this.questions.length - 1
      setTimeout(() => {
        if (derniere) {
          this.soumettre(true)
          return
        }
        this.indexCourant++
        this.verrouille = false
        this.sauverEtat()
        this.lancerTimerQuestion()
      }, 700)
    },

    repondre(opt) {
      if (this.parQuestion) {
        // Un seul choix, définitif : ni changement d'avis, ni retour.
        if (this.figee) return
        this.reponses = { ...this.reponses, [this.indexCourant]: opt }
        this.avancer()
        return
      }
      if (this.verrouille) return
      this.reponses = { ...this.reponses, [this.indexCourant]: opt }
      if (this.indexCourant < this.questions.length - 1) {
        setTimeout(() => { this.indexCourant++ }, 180)
      }
    },
    formatTemps(s) {
      const v = Math.max(0, s)
      return `${String(Math.floor(v / 60)).padStart(2, '0')}:${String(v % 60).padStart(2, '0')}`
    },
    async soumettre(auto = false) {
      if (this.submitting) return
      this.submitting = true
      this.confirmer = false
      if (this.timer) clearInterval(this.timer)
      if (this.timerQuestion) clearInterval(this.timerQuestion)
      try {
        // En mode chronométré, la durée en minutes n'a plus cours : le temps
        // écoulé se mesure simplement depuis l'ouverture de la manche.
        const ecoule = this.parQuestion
          ? Math.round((Date.now() - this.debutManche) / 1000)
          : (this.tourActif.duree_minutes || 10) * 60 - Math.max(0, this.tempsRestant)
        const res = await fetch(`${API}?action=submit`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            matricule: this.participant.matricule,
            numero: this.tourActif.numero,
            reponses: this.reponses,
            temps_ecoule: Math.max(0, ecoule),
          }),
        })
        const data = await res.json()
        if (!data.success) throw new Error(data.message)
        this.resultat = data.resultats
        this.enCours = false
        this.effacerEtatQuiz()
        if (auto && !this.parQuestion) {
          setTimeout(() => alert('Temps écoulé — vos réponses ont été enregistrées.'), 200)
        }
        this.verrouille = false
        await this.identifier(true)
        this.resultat = data.resultats
      } catch (e) {
        alert(e.message)
        this.enCours = true
        this.verrouille = false
      } finally {
        this.submitting = false
      }
    },
    retourAccueil() {
      this.resultat = null
      this.chargerTours()
    },

    async ouvrirClassement() {
      this.showClassement = true
      this.loadingClassement = true
      try {
        const res = await fetch(`${API}?action=classement&rand=${Math.random()}`)
        const data = await res.json()
        if (data.success) this.classement = data.classement || []
      } catch (e) {
        this.classement = []
      } finally {
        this.loadingClassement = false
      }
    },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.gq-root {
  --ink: var(--ink); --muted: var(--muted); --line: var(--line);
  --accent: #7c3aed; --accent-dark: #5b21b6; --accent-soft: #f5f3ff;
  --ok: var(--green); --ko: #dc2626; --gold: var(--gold);
  min-height: 100vh; background: linear-gradient(180deg, #faf5ff 0%, var(--surface) 320px);
  font-family: var(--display); color: var(--ink);
  padding-bottom: 40px;
}

/* Topbar */
.gq-topbar { max-width: 960px; margin: 0 auto; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.gq-brand { display: flex; align-items: center; gap: 11px; }
.gq-brand__dot { width: 12px; height: 12px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 5px rgba(124,58,237,.15); }
.gq-brand strong { display: block; font-size: 16px; }
.gq-brand small { color: var(--muted); font-size: 11.5px; }
.gq-logout { border: 1px solid var(--line); background: #fff; color: var(--muted); border-radius: 0; padding: 8px 12px; font-size: 12px; font-weight: 700; cursor: pointer; }

/* Écrans */
.gq-screen { max-width: 760px; margin: 0 auto; padding: 8px 20px; }
.gq-card { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 28px 24px; box-shadow: var(--shadow); }
.gq-card--auth { text-align: center; }
.gq-hero-icon { font-size: 46px; }
.gq-card--auth h1 { font-size: 26px; margin: 8px 0 10px; letter-spacing: -.02em; }
.gq-lead { color: var(--muted); font-size: 14px; line-height: 1.6; max-width: 460px; margin: 0 auto 20px; }

.gq-rules { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 22px; }
.gq-rule { display: flex; align-items: center; gap: 9px; background: var(--accent-soft); border-radius: 0; padding: 11px; text-align: left; }
.gq-rule span { font-size: 18px; }
.gq-rule strong { display: block; font-size: 12.5px; }
.gq-rule small { color: var(--muted); font-size: 11px; }

.gq-field { display: block; text-align: left; margin-bottom: 8px; }
.gq-field span { display: block; font-size: 12px; font-weight: 800; color: var(--ink-soft); margin-bottom: 6px; }
.gq-field input { width: 100%; height: 52px; border: 1.5px solid var(--line); border-radius: 0; padding: 0 15px; font-size: 16px; font-weight: 700; letter-spacing: .05em; background: var(--surface); color: var(--ink); }
.gq-field input:focus { outline: none; border-color: var(--accent); background: #fff; box-shadow: 0 0 0 4px rgba(124,58,237,.12); }
.gq-hint { color: var(--muted); font-size: 11.5px; text-align: left; margin: 0 0 16px; }

.gq-btn { border: 0; border-radius: 0; min-height: 48px; padding: 0 20px; font-size: 14px; font-weight: 800; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: transform .12s, opacity .12s; }
.gq-btn:disabled { opacity: .55; cursor: not-allowed; }
.gq-btn:not(:disabled):hover { transform: translateY(-1px); }
.gq-btn--primary { background: var(--accent); color: #fff; box-shadow: var(--shadow); }
.gq-btn--send { background: var(--ok); color: #fff; box-shadow: var(--shadow); }
.gq-btn--ghost { background: #fff; color: var(--accent); border: 1.5px solid #ddd6fe; }
.gq-btn--lg { width: 100%; min-height: 54px; font-size: 15px; }
.gq-btn--full { width: 100%; margin-top: 10px; }
.gq-link { display: block; width: 100%; margin-top: 14px; background: none; border: 0; color: var(--accent); font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: underline; }

.gq-alert { border-radius: 0; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 14px; text-align: left; }
.gq-alert--error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.gq-alert--out { background: var(--gold-mist); color: var(--gold); border: 1px solid #fed7aa; }
.gq-alert--out strong { display: block; margin-bottom: 3px; }
.gq-alert--out p { margin: 0; font-weight: 500; }

/* Profil */
.gq-profile { display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 16px; margin-bottom: 20px; box-shadow: var(--shadow); }
.gq-avatar { width: 54px; height: 54px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: var(--accent-dark); color: #fff; display: grid; place-items: center; font-weight: 900; font-size: 17px; }
.gq-avatar img { width: 100%; height: 100%; object-fit: cover; }
.gq-profile__info { flex: 1; min-width: 0; }
.gq-profile__info strong { display: block; font-size: 15px; }
.gq-profile__info small { color: var(--muted); font-size: 12px; }
.gq-profile__score { text-align: right; }
.gq-profile__pts { display: block; font-size: 26px; font-weight: 900; color: var(--accent); line-height: 1; }
.gq-profile__score small { color: var(--muted); font-size: 11px; }

.gq-section-title { font-size: 13px; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); margin: 0 0 12px; }
.gq-tours { display: grid; gap: 12px; }
.gq-tour { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 15px; box-shadow: var(--shadow); }
.gq-tour--ouvert { border-color: var(--green-mist); background: linear-gradient(135deg, var(--green-mist), #fff); }
.gq-tour--done { opacity: .92; }
.gq-tour__head { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
.gq-tour__num { width: 42px; height: 42px; border-radius: 0; background: var(--accent-soft); color: var(--accent); display: grid; place-items: center; font-weight: 900; font-size: 14px; flex-shrink: 0; }
.gq-tour--ouvert .gq-tour__num { background: var(--green-mist); color: var(--green); }
.gq-tour__head strong { display: block; font-size: 14.5px; }
.gq-tour__head small { color: var(--muted); font-size: 12px; }
.gq-tour__lock { margin: 0; color: var(--muted); font-size: 12.5px; font-weight: 600; background: var(--surface); border-radius: 0; padding: 9px 11px; }
.gq-tour__result { margin-top: 4px; }
.gq-badge { display: inline-block; border-radius: 999px; padding: 6px 12px; font-size: 12px; font-weight: 900; }
.gq-badge--ok { background: var(--green-mist); color: var(--green); }
.gq-badge--ko { background: #fee2e2; color: #b91c1c; }

/* Quiz */
.gq-quiz { max-width: 760px; margin: 0 auto; padding: 8px 20px; }
.gq-quiz__bar { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 14px 16px; margin-bottom: 14px; position: sticky; top: 8px; z-index: 5; box-shadow: var(--shadow); }
.gq-quiz__meta { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 10px; }
.gq-quiz__title { font-weight: 800; font-size: 14px; }
.gq-timer { background: var(--accent-soft); color: var(--accent-dark); border-radius: 999px; padding: 6px 13px; font-weight: 900; font-size: 14px; font-variant-numeric: tabular-nums; }
.gq-timer--urgent { background: #fee2e2; color: var(--ko); animation: gqpulse 1s infinite; }

/* ══════════════════════════════════════════════════════════════════════
   PLATEAU DE JEU — mode « une question à la fois »
   Écran entier, sans en-tête ni pied de page : on est sur un plateau,
   plus sur une page web.
   ══════════════════════════════════════════════════════════════════════ */
.gq-root--plateau { min-height: 100vh; min-height: 100dvh; padding: 0; background: #0b0722; }

.gq-show {
  position: fixed; inset: 0; z-index: 60;
  display: flex; flex-direction: column;
  padding: max(14px, env(safe-area-inset-top)) 14px max(14px, env(safe-area-inset-bottom));
  background:
    radial-gradient(120% 80% at 12% 0%, #4c1d95 0%, transparent 60%),
    radial-gradient(120% 80% at 88% 100%, #0e7490 0%, transparent 58%),
    linear-gradient(165deg, #170c3d 0%, #241154 48%, #0f0a2e 100%);
  color: #fff; overflow: hidden;
}
/* Halo qui respire : donne la profondeur d'un plateau éclairé */
.gq-show__halo { position: absolute; left: 50%; top: -28vh; width: 130vw; height: 70vh;
                 transform: translateX(-50%); pointer-events: none;
                 background: radial-gradient(closest-side, rgba(250,204,21,.20), transparent 72%);
                 animation: gqHalo 5s ease-in-out infinite; }
@keyframes gqHalo { 50% { opacity: .45; transform: translateX(-50%) scale(1.12); } }

.gq-show__bandeau { position: relative; display: flex; align-items: center;
                    justify-content: space-between; gap: 10px; margin-bottom: 10px; }
.gq-show__manche { font-size: 11.5px; font-weight: 900; letter-spacing: .14em;
                   text-transform: uppercase; color: #c4b5fd;
                   overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.gq-show__compte { font-weight: 900; font-variant-numeric: tabular-nums; }
.gq-show__compte b { font-size: 26px; color: #fde047; text-shadow: 0 0 22px rgba(250,204,21,.5); }
.gq-show__compte i { font-style: normal; font-size: 14px; color: rgba(255,255,255,.6); }
.gq-show__pts { flex-shrink: 0; background: linear-gradient(135deg, var(--gold), var(--gold));
                color: #3b1d00; border-radius: 999px; padding: 5px 13px;
                font-size: 11.5px; font-weight: 900;
                box-shadow: var(--shadow); }

/* ── La longue bande qui se vide ─────────────────────────────────────── */
.gq-show__barre { position: relative; height: 30px; border-radius: 999px;
                  background: rgba(255,255,255,.09); overflow: hidden;
                  border: 1px solid rgba(255,255,255,.14);
                  box-shadow: inset 0 3px 10px rgba(0,0,0,.45); }
.gq-show__barre-fill { position: relative; height: 100%; border-radius: 999px;
                       transition: width 1s linear, background .6s; }
.gq-show__tete { position: absolute; right: 0; top: 0; bottom: 0; width: 22px;
                 border-radius: 999px; background: rgba(255,255,255,.55);
                 filter: blur(5px); }
.gq-show__barre--vert   .gq-show__barre-fill { background: linear-gradient(90deg, var(--green), var(--green-2) 60%, var(--green-mist)); }
.gq-show__barre--orange .gq-show__barre-fill { background: linear-gradient(90deg, #b45309, var(--gold) 60%, var(--gold)); }
.gq-show__barre--rouge  .gq-show__barre-fill { background: linear-gradient(90deg, #991b1b, #dc2626 60%, #fca5a5); }
.gq-show__barre--rouge { animation: gqTremble .55s ease-in-out infinite;
                         border-color: rgba(248,113,113,.65); }
@keyframes gqTremble { 25% { transform: translateX(-1.5px); } 75% { transform: translateX(1.5px); } }
.gq-show__secondes { position: absolute; inset: 0; display: grid; place-items: center;
                     font-size: 14px; font-weight: 900; color: #fff;
                     text-shadow: 0 1px 5px rgba(0,0,0,.7);
                     font-variant-numeric: tabular-nums; letter-spacing: .03em; }
.gq-show__secondes i { font-style: normal; font-size: 10px; opacity: .75; margin-left: 1px; }

/* ── Question ────────────────────────────────────────────────────────── */
.gq-show__body { position: relative; flex: 1; min-height: 0; display: flex;
                 flex-direction: column; justify-content: center; gap: 14px;
                 padding: 14px 2px; overflow-y: auto; }
.gq-show__cat { align-self: flex-start; padding: 5px 13px; border-radius: 999px;
                background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
                color: #e9d5ff; font-size: 10.5px; font-weight: 900;
                letter-spacing: .1em; text-transform: uppercase;
                animation: gqEntree .35s both; }
.gq-show__scene { border-radius: 0; padding: 20px 18px;
                  background: linear-gradient(135deg, rgba(255,255,255,.13), rgba(255,255,255,.05));
                  border: 1px solid rgba(255,255,255,.18);
                  box-shadow: var(--shadow);
                  animation: gqEntree .4s .05s both; }
.gq-show__question { margin: 0; font-size: clamp(19px, 5vw, 30px); line-height: 1.32;
                     font-weight: 800; text-shadow: 0 2px 14px rgba(0,0,0,.35); }
@keyframes gqEntree { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }

/* ── Réponses ────────────────────────────────────────────────────────── */
.gq-show__options { display: grid; gap: 10px; }
.gq-show__option { display: flex; align-items: center; gap: 13px; width: 100%;
                   border: 2px solid rgba(255,255,255,.15); border-radius: 0;
                   padding: 13px 15px; text-align: left; cursor: pointer;
                   font-family: inherit; color: #fff;
                   background: linear-gradient(135deg, rgba(255,255,255,.11), rgba(255,255,255,.04));
                   transition: transform .14s, border-color .2s, background .2s, opacity .3s;
                   animation: gqEntree .38s both; }
.gq-show__option:hover:not(:disabled) { transform: translateY(-2px) scale(1.01);
                                        border-color: rgba(255,255,255,.45); }
.gq-show__option:active:not(:disabled) { transform: scale(.985); }
.gq-show__option:disabled { cursor: default; }
.gq-show__option.is-eteint { opacity: .28; filter: saturate(.4); }
.gq-show__lettre { flex-shrink: 0; width: 42px; height: 42px; border-radius: 0;
                   display: grid; place-items: center; font-size: 18px; font-weight: 900;
                   color: #180d3d; box-shadow: var(--shadow); }
.gq-show__option--0 .gq-show__lettre { background: linear-gradient(140deg, #fde047, var(--gold)); }
.gq-show__option--1 .gq-show__lettre { background: linear-gradient(140deg, var(--green-mist), var(--green)); }
.gq-show__option--2 .gq-show__lettre { background: linear-gradient(140deg, #93c5fd, #2563eb); }
.gq-show__option--3 .gq-show__lettre { background: linear-gradient(140deg, #f9a8d4, #db2777); }
.gq-show__option--0:hover:not(:disabled) { border-color: var(--gold); }
.gq-show__option--1:hover:not(:disabled) { border-color: var(--green-2); }
.gq-show__option--2:hover:not(:disabled) { border-color: #60a5fa; }
.gq-show__option--3:hover:not(:disabled) { border-color: #f472b6; }
.gq-show__texte { font-size: 15.5px; line-height: 1.38; font-weight: 700; }
.gq-show__option.is-choisi { background: #fff; color: #1b0f45; border-color: #fff;
                             box-shadow: 0 0 0 4px rgba(255,255,255,.22),
                                         0 14px 34px rgba(255,255,255,.2);
                             animation: gqChoix .45s ease-out; opacity: 1; }
@keyframes gqChoix { 0% { transform: scale(1); } 35% { transform: scale(1.045); } 100% { transform: scale(1); } }

/* ── Pied de plateau ─────────────────────────────────────────────────── */
.gq-show__pied { position: relative; padding-top: 10px; }
.gq-show__fil { display: flex; gap: 4px; justify-content: center; flex-wrap: wrap; }
.gq-show__fil i { width: 8px; height: 8px; border-radius: 50%;
                  background: rgba(255,255,255,.18); transition: background .3s, transform .3s; }
.gq-show__fil i.fait { background: rgba(255,255,255,.4); }
.gq-show__fil i.rep { background: var(--green-2); }
.gq-show__fil i.on { background: #fde047; transform: scale(1.7);
                     box-shadow: var(--shadow); }
.gq-show__note { margin: 9px 0 0; text-align: center; font-size: 11.5px;
                 color: rgba(255,255,255,.55); font-style: italic; }

/* ── Voile de bascule entre deux questions ───────────────────────────── */
.gq-show__flash { position: absolute; inset: 0; z-index: 5; display: grid; place-items: center;
                  background: rgba(11,7,34,.72); backdrop-filter: blur(3px); }
.gq-show__flash span { padding: 12px 26px; border-radius: 999px;
                       background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.3);
                       font-weight: 900; font-size: 15px; letter-spacing: .02em; }
.gq-flash-enter-active, .gq-flash-leave-active { transition: opacity .2s; }
.gq-flash-enter-from, .gq-flash-leave-to { opacity: 0; }

@media (min-width: 700px) {
  .gq-show { padding: 22px max(22px, calc((100vw - 820px) / 2)); }
  .gq-show__options { grid-template-columns: 1fr 1fr; gap: 12px; }
  .gq-show__question { text-align: center; }
  .gq-show__cat { align-self: center; }
}

/* ── Avertissement de changement de format ───────────────────────────── */
.gq-modal--alerte { padding: 16px; }

/* ── Avertissement de changement de format ───────────────────────────── */
.gq-alerte { max-width: 620px; margin: 0 auto; background: #fff; border: 2px solid #dc2626;
             border-radius: 0; padding: 22px; box-shadow: var(--shadow); }
.gq-alerte__tete { display: flex; align-items: center; gap: 11px; color: #b91c1c; margin-bottom: 14px; }
.gq-alerte__tete strong { font-size: 18px; line-height: 1.25; }
.gq-alerte__liste { margin: 0; padding-left: 20px; display: grid; gap: 9px;
                    font-size: 14.5px; line-height: 1.55; color: var(--ink); }
.gq-alerte__liste b { color: #b91c1c; }
.gq-alerte__duree { margin: 16px 0 0; padding: 10px 14px; border-radius: 0;
                    background: #fef2f2; color: #991b1b; font-weight: 800;
                    font-size: 13.5px; text-align: center; }
.gq-alerte__actions { display: flex; gap: 10px; margin-top: 18px; }
.gq-alerte__actions .gq-btn { flex: 1; }
@keyframes gqpulse { 50% { opacity: .6; } }
.gq-progress { height: 7px; background: #eef2f7; border-radius: 0; overflow: hidden; }
.gq-progress__fill { height: 100%; background: linear-gradient(90deg, var(--accent), #a78bfa); transition: width .3s; }
.gq-progress__label { display: flex; justify-content: space-between; margin-top: 7px; font-size: 11.5px; color: var(--muted); font-weight: 700; }
.gq-pts { color: var(--gold); }

.gq-quiz__body { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 20px; box-shadow: var(--shadow); }
.gq-cat { display: inline-block; background: var(--accent-soft); color: var(--accent); border-radius: 999px; padding: 4px 11px; font-size: 11px; font-weight: 800; margin-bottom: 10px; }
.gq-question { font-size: 18px; line-height: 1.45; margin: 0 0 18px; }
.gq-options { display: grid; gap: 9px; }
.gq-option { display: flex; align-items: center; gap: 12px; width: 100%; text-align: left; background: var(--surface); border: 1.5px solid var(--line); border-radius: 0; padding: 13px 14px; cursor: pointer; font-family: inherit; font-size: 14px; color: var(--ink); transition: .15s; }
.gq-option:hover { border-color: #c4b5fd; background: #fff; }
.gq-option--selected { border-color: var(--accent); background: var(--accent-soft); }
.gq-option__letter { width: 30px; height: 30px; border-radius: 0; background: #fff; border: 1.5px solid var(--line); display: grid; place-items: center; font-weight: 900; font-size: 13px; flex-shrink: 0; }
.gq-option--selected .gq-option__letter { background: var(--accent); border-color: var(--accent); color: #fff; }
.gq-option__text { flex: 1; }

.gq-dots { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--line); }
.gq-dot { width: 30px; height: 30px; border-radius: 0; border: 1.5px solid var(--line); background: #fff; color: var(--muted); font-size: 11.5px; font-weight: 800; cursor: pointer; }
.gq-dot--answered { background: var(--green-mist); border-color: var(--green-mist); color: var(--green); }
.gq-dot--current { border-color: var(--accent); color: var(--accent); box-shadow: 0 0 0 3px rgba(124,58,237,.14); }

.gq-quiz__nav { display: flex; gap: 10px; margin-top: 14px; }
.gq-quiz__nav .gq-btn { flex: 1; }

/* Résultat */
.gq-card--result { text-align: center; }
.gq-card--win { border-color: var(--green-mist); background: linear-gradient(180deg, var(--green-mist), #fff); }
.gq-card--lose { border-color: #fecaca; background: linear-gradient(180deg, #fef2f2, #fff); }
.gq-result-icon { font-size: 50px; }
.gq-score-big { font-size: 56px; font-weight: 900; letter-spacing: -.04em; line-height: 1; margin: 6px 0; }
.gq-score-big small { font-size: 22px; color: var(--muted); font-weight: 700; }
.gq-result-sub { color: var(--muted); font-size: 13.5px; margin: 0 0 16px; }
.gq-verdict { display: inline-block; border-radius: 999px; padding: 9px 22px; font-weight: 900; font-size: 14px; letter-spacing: .08em; margin-bottom: 12px; }
.gq-verdict--ok { background: var(--green-mist); color: var(--green); }
.gq-verdict--ko { background: #fee2e2; color: #b91c1c; }
.gq-result-msg { font-size: 14px; line-height: 1.6; color: var(--ink-soft); margin: 0 0 18px; }
.gq-result-stats { display: flex; justify-content: center; gap: 28px; padding: 14px 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); margin-bottom: 16px; }
.gq-result-stats strong { display: block; font-size: 24px; font-weight: 900; color: var(--accent); }
.gq-result-stats small { color: var(--muted); font-size: 11.5px; }

/* Modales */
.gq-modal { position: fixed; inset: 0; background: rgba(15,23,42,.55); display: flex; align-items: center; justify-content: center; padding: 18px; z-index: 60; backdrop-filter: blur(4px); }
.gq-modal__box { background: #fff; border-radius: 0; padding: 22px; width: 420px; max-width: 100%; max-height: 88vh; overflow: auto; }
.gq-modal__box--lg { width: 620px; }
.gq-modal__box h3 { margin: 0 0 8px; font-size: 18px; }
.gq-modal__box p { color: var(--muted); font-size: 13.5px; margin: 0 0 16px; }
.gq-modal__head { display: flex; align-items: center; justify-content: space-between; }
.gq-modal__sub { margin-bottom: 14px !important; }
.gq-modal__x { border: 1px solid var(--line); background: #fff; border-radius: 0; width: 32px; height: 32px; cursor: pointer; color: var(--muted); }
.gq-modal__actions { display: flex; gap: 10px; }
.gq-modal__actions .gq-btn { flex: 1; }

.gq-rank { list-style: none; margin: 0; padding: 0; display: grid; gap: 7px; }
.gq-rank__row { display: flex; align-items: center; gap: 11px; padding: 10px 12px; border: 1px solid var(--line); border-radius: 0; background: #fff; }
.gq-rank__row--me { border-color: var(--accent); background: var(--accent-soft); }
.gq-rank__row--out { opacity: .55; }
.gq-rank__pos { width: 30px; height: 30px; border-radius: 0; display: grid; place-items: center; font-weight: 900; font-size: 12.5px; background: var(--surface); color: var(--muted); flex-shrink: 0; }
.gq-rank__pos--1 { background: var(--gold-mist); color: #b45309; }
.gq-rank__pos--2 { background: var(--line); color: var(--ink-soft); }
.gq-rank__pos--3 { background: #fed7aa; color: var(--gold); }
.gq-rank__who { flex: 1; min-width: 0; }
.gq-rank__who strong { display: block; font-size: 13.5px; }
.gq-rank__who small { color: var(--muted); font-size: 11.5px; }
.gq-rank__pts { font-weight: 900; color: var(--accent); font-size: 14px; white-space: nowrap; }

.gq-loading, .gq-empty { text-align: center; padding: 30px; color: var(--muted); font-weight: 700; }

@media (max-width: 620px) {
  .gq-rules { grid-template-columns: 1fr; }
  .gq-result-stats { gap: 18px; }
  .gq-score-big { font-size: 46px; }
}
</style>
