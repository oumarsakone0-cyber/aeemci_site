<template>
  <div class="cp-root">
    <header class="cp-topbar">
      <div class="cp-brand">
        <span class="cp-brand__dot"></span>
        <div>
          <strong>Concours Photo SENAFOI</strong>
          <small>Votre plus belle image du camp</small>
        </div>
      </div>
      <button v-if="participant" class="cp-logout" @click="deconnecter">Changer de matricule</button>
    </header>

    <!-- ══════════ 1. IDENTIFICATION ══════════ -->
    <section v-if="!participant" class="cp-screen">
      <div class="cp-card cp-card--auth">
        <div class="cp-hero-icon">📸</div>
        <h1>{{ concours.titre || 'Concours Photo SENAFOI 2026' }}</h1>
        <p class="cp-lead">{{ concours.description || 'Partagez votre plus belle photo prise sur le camp du séminaire. La meilleure image sera présentée et récompensée.' }}</p>

        <div class="cp-rules">
          <div class="cp-rule"><span>🖼️</span><div><strong>1 photo</strong><small>par participant</small></div></div>
          <div class="cp-rule"><span>🔒</span><div><strong>Galerie secrète</strong><small>visible après dépôt</small></div></div>
          <div class="cp-rule"><span>🏆</span><div><strong>Notée sur 20</strong><small>par le jury</small></div></div>
        </div>

        <div class="cp-state-pill" :class="etat.ouvert ? 'cp-state-pill--open' : 'cp-state-pill--closed'">
          {{ etat.libelle || '…' }}
        </div>

        <label class="cp-field">
          <span>Votre matricule</span>
          <input
            v-model="matriculeInput"
            :disabled="loadingAuth"
            placeholder="SEM2026… / SEM26SR… / SEM26CCR…"
            @keyup.enter="identifier"
            @input="matriculeInput = matriculeInput.toUpperCase().replace(/[^A-Z0-9]/g, '')"
          />
        </label>
        <p class="cp-hint">Séminaristes SENAFOI, SR et CCR peuvent participer.</p>

        <div v-if="authError" class="cp-alert cp-alert--error">{{ authError }}</div>

        <button class="cp-btn cp-btn--primary cp-btn--lg" :disabled="loadingAuth || !matriculeInput" @click="identifier">
          {{ loadingAuth ? 'Vérification…' : 'Participer au concours' }}
        </button>
      </div>
    </section>

    <!-- ══════════ 2. DÉPÔT / ESPACE PARTICIPANT ══════════ -->
    <section v-else class="cp-screen">
      <div class="cp-profile">
        <div class="cp-avatar">
          <img v-if="participant.photo" :src="participant.photo" alt="" />
          <span v-else>{{ initiales }}</span>
        </div>
        <div class="cp-profile__info">
          <strong>{{ participant.prenom }} {{ participant.nom }}</strong>
          <small>{{ participant.matricule }} · {{ typeLabel(participant.type) }}</small>
        </div>
      </div>

      <!-- Pas encore soumis -->
      <div v-if="!soumission" class="cp-card">
        <h2 class="cp-card__title">Déposez votre photo</h2>
        <p class="cp-card__sub">Une seule image par participant. Choisissez bien : le dépôt est définitif.</p>

        <div v-if="!etat.ouvert" class="cp-alert cp-alert--warn">{{ etat.libelle }}</div>

        <template v-else>
          <label class="cp-drop" :class="{ 'cp-drop--filled': form.image_url }">
            <input type="file" accept="image/*" :disabled="uploading" @change="uploadImage" />
            <img v-if="form.image_url" :src="form.image_url" alt="" class="cp-drop__preview" />
            <div v-else class="cp-drop__empty">
              <span class="cp-drop__icon">📷</span>
              <strong>{{ uploading ? `Envoi ${uploadProgress}%` : 'Choisir une image' }}</strong>
              <small>JPG ou PNG · 8 Mo maximum</small>
            </div>
          </label>
          <div v-if="uploading" class="cp-upbar"><div :style="{ width: uploadProgress + '%' }"></div></div>

          <label class="cp-field">
            <span>Titre de la photo</span>
            <input v-model="form.titre" maxlength="120" placeholder="Ex : Lever du jour sur le camp" />
          </label>
          <label class="cp-field">
            <span>Légende <em>(facultatif)</em></span>
            <textarea v-model="form.legende" rows="3" maxlength="500" placeholder="Racontez le moment capturé…"></textarea>
          </label>

          <div v-if="submitError" class="cp-alert cp-alert--error">{{ submitError }}</div>

          <button
            class="cp-btn cp-btn--primary cp-btn--lg"
            :disabled="submitting || uploading || !form.image_url"
            @click="confirmer = true"
          >
            {{ submitting ? 'Envoi…' : 'Soumettre ma photo' }}
          </button>
          <p class="cp-note">🔒 La galerie des autres participants se débloque dès que votre photo est déposée.</p>
        </template>
      </div>

      <!-- Déjà soumis -->
      <div v-else class="cp-card cp-card--done">
        <div class="cp-done-head">
          <span class="cp-check">✓</span>
          <div>
            <strong>Photo soumise</strong>
            <small>Déposée le {{ formatDate(soumission.created_at) }}</small>
          </div>
        </div>

        <figure class="cp-my-photo">
          <img :src="soumission.image_url" alt="" @click="zoom(soumission)" />
          <figcaption>
            <strong>{{ soumission.titre || 'Sans titre' }}</strong>
            <p v-if="soumission.legende">{{ soumission.legende }}</p>
          </figcaption>
        </figure>

        <div v-if="resultatsPublies && soumission.note !== null" class="cp-my-note">
          <span class="cp-my-note__val">{{ soumission.note }}<small>/20</small></span>
          <p v-if="soumission.commentaire_jury">« {{ soumission.commentaire_jury }} »</p>
        </div>
        <div v-else class="cp-alert cp-alert--wait">
          <strong>En attente de la proclamation</strong>
          <p>Les notes et le classement seront révélés après délibération du jury.</p>
        </div>

        <button class="cp-btn cp-btn--primary cp-btn--full" @click="ouvrirGalerie">
          {{ resultatsPublies ? 'Voir le classement' : 'Découvrir les autres photos' }}
        </button>
      </div>
    </section>

    <!-- ══════════ MODALE CONFIRMATION ══════════ -->
    <div v-if="confirmer" class="cp-modal" @click.self="confirmer = false">
      <div class="cp-modal__box">
        <h3>Confirmer le dépôt ?</h3>
        <p>Votre photo sera envoyée au jury. Vous ne pourrez plus la modifier ni en déposer une autre.</p>
        <div class="cp-modal__actions">
          <button class="cp-btn cp-btn--ghost" @click="confirmer = false">Annuler</button>
          <button class="cp-btn cp-btn--primary" :disabled="submitting" @click="soumettre">Je confirme</button>
        </div>
      </div>
    </div>

    <!-- ══════════ GALERIE ══════════ -->
    <div v-if="showGalerie" class="cp-modal" @click.self="showGalerie = false">
      <div class="cp-modal__box cp-modal__box--xl">
        <div class="cp-modal__head">
          <div>
            <h3>{{ resultatsPublies ? 'Classement du concours' : 'Galerie des participants' }}</h3>
            <p class="cp-modal__sub">{{ photos.length }} photo(s) · {{ resultatsPublies ? 'notes publiées' : 'notes non encore révélées' }}</p>
          </div>
          <button class="cp-modal__x" @click="showGalerie = false">✕</button>
        </div>

        <div v-if="loadingGalerie" class="cp-loading">Chargement…</div>
        <div v-else-if="galerieError" class="cp-alert cp-alert--warn">{{ galerieError }}</div>
        <div v-else-if="!photos.length" class="cp-empty">Aucune photo pour le moment.</div>
        <div v-else class="cp-grid">
          <figure
            v-for="(p, i) in photos"
            :key="p.id"
            class="cp-tile"
            :class="{ 'cp-tile--me': participant && p.matricule === participant.matricule }"
            @click="zoom(p)"
          >
            <span v-if="resultatsPublies && p.rang" class="cp-rank" :class="'cp-rank--' + (p.rang <= 3 ? p.rang : 'n')">
              {{ p.rang }}
            </span>
            <img :src="p.image_url" :alt="p.titre || 'Photo ' + (i + 1)" loading="lazy" />
            <figcaption>
              <strong>{{ p.titre || 'Sans titre' }}</strong>
              <small>{{ p.prenom }} {{ p.nom }}</small>
              <span v-if="resultatsPublies && p.note !== null && p.note !== undefined" class="cp-tile__note">{{ p.note }}/20</span>
            </figcaption>
          </figure>
        </div>
      </div>
    </div>

    <!-- ══════════ ZOOM ══════════ -->
    <div v-if="zoomed" class="cp-lightbox" @click.self="zoomed = null">
      <button class="cp-lightbox__x" @click="zoomed = null">✕</button>
      <img :src="zoomed.image_url" alt="" />
      <div class="cp-lightbox__cap">
        <strong>{{ zoomed.titre || 'Sans titre' }}</strong>
        <span v-if="zoomed.prenom">{{ zoomed.prenom }} {{ zoomed.nom }}</span>
        <p v-if="zoomed.legende">{{ zoomed.legende }}</p>
      </div>
    </div>
  </div>
</template>

<script>
const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_photo_api.php'
const UPLOAD_URL = 'https://api.aeemci-ce.ci/cloud/upload.php'
const CLOUD_API_KEY = 'SAKONE25_04_2026_medias'
const STORAGE = 'aeemci_concours_photo'

export default {
  name: 'ConcoursPhoto',
  data() {
    return {
      matriculeInput: '',
      loadingAuth: false,
      authError: '',
      participant: null,
      soumission: null,
      concours: {},
      etat: { ouvert: false, libelle: '' },
      resultatsPublies: false,

      form: { image_url: '', image_id: '', titre: '', legende: '' },
      uploading: false,
      uploadProgress: 0,
      submitting: false,
      submitError: '',
      confirmer: false,

      showGalerie: false,
      loadingGalerie: false,
      galerieError: '',
      photos: [],
      zoomed: null,
    }
  },
  computed: {
    initiales() {
      if (!this.participant) return '?'
      return ((this.participant.prenom?.[0] || '') + (this.participant.nom?.[0] || '')).toUpperCase() || '?'
    },
  },
  mounted() {
    this.chargerInfos()
    const saved = this.lireSession()
    if (saved?.matricule) {
      this.matriculeInput = saved.matricule
      this.identifier(true)
    }
  },
  methods: {
    typeLabel(t) {
      return { seminariste: 'Séminariste SENAFOI', sr: 'Séminariste SR', ccr: 'Séminariste CCR' }[t] || 'Participant'
    },
    formatDate(v) {
      if (!v) return '—'
      const d = new Date(String(v).replace(' ', 'T'))
      return Number.isNaN(d.getTime()) ? String(v) : d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })
    },
    lireSession() {
      try { return JSON.parse(localStorage.getItem(STORAGE) || 'null') } catch (e) { return null }
    },
    deconnecter() {
      try { localStorage.removeItem(STORAGE) } catch (e) { /* ignore */ }
      Object.assign(this.$data, this.$options.data.call(this))
      this.chargerInfos()
    },

    async chargerInfos() {
      try {
        const res = await fetch(`${API}?action=infos&rand=${Math.random()}`)
        const data = await res.json()
        if (data.success) {
          this.concours = data.concours || {}
          this.etat = data.etat || this.etat
          this.resultatsPublies = Number(data.concours?.resultats_publies) === 1
        }
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
        this.soumission = data.soumission
        this.etat = data.etat || this.etat
        this.resultatsPublies = Number(data.resultats_publies) === 1
        try { localStorage.setItem(STORAGE, JSON.stringify({ matricule: this.participant.matricule })) } catch (e) { /* ignore */ }
      } catch (e) {
        if (!silencieux) this.authError = e.message
        else { try { localStorage.removeItem(STORAGE) } catch (er) { /* ignore */ } }
      } finally {
        this.loadingAuth = false
      }
    },

    async uploadImage(event) {
      const file = event.target.files?.[0]
      if (!file) return
      if (!file.type.startsWith('image/')) {
        this.submitError = 'Veuillez choisir un fichier image.'
        event.target.value = ''
        return
      }
      if (file.size > 8 * 1024 * 1024) {
        this.submitError = 'Image trop lourde : 8 Mo maximum.'
        event.target.value = ''
        return
      }
      this.uploading = true
      this.uploadProgress = 0
      this.submitError = ''
      try {
        const body = new FormData()
        body.append('file', file)
        body.append('folder', 'senafoi')
        body.append('subfolder', 'concours_photo')
        const data = await new Promise((resolve, reject) => {
          const xhr = new XMLHttpRequest()
          xhr.upload.onprogress = (evt) => {
            if (evt.lengthComputable) this.uploadProgress = Math.round((evt.loaded * 100) / evt.total)
          }
          xhr.onload = () => {
            try {
              const json = JSON.parse(xhr.responseText || '{}')
              if (xhr.status >= 200 && xhr.status < 300 && json.success) resolve(json)
              else reject(new Error(json.error || json.message || 'Upload impossible'))
            } catch (e) { reject(new Error('Réponse upload invalide')) }
          }
          xhr.onerror = () => reject(new Error('Connexion upload impossible'))
          xhr.open('POST', UPLOAD_URL)
          xhr.setRequestHeader('X-API-Key', CLOUD_API_KEY)
          xhr.send(body)
        })
        this.form.image_url = data.url
        this.form.image_id = data.public_id || data.filename || ''
      } catch (e) {
        this.submitError = e.message || "Erreur lors de l'envoi de l'image."
      } finally {
        this.uploading = false
        event.target.value = ''
      }
    },

    async soumettre() {
      if (!this.form.image_url) return
      this.submitting = true
      this.confirmer = false
      this.submitError = ''
      try {
        const res = await fetch(`${API}?action=soumettre`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            matricule: this.participant.matricule,
            image_url: this.form.image_url,
            image_id: this.form.image_id,
            titre: this.form.titre,
            legende: this.form.legende,
          }),
        })
        const data = await res.json()
        if (!data.success) throw new Error(data.message)
        await this.identifier(true)
        this.ouvrirGalerie()
      } catch (e) {
        this.submitError = e.message
      } finally {
        this.submitting = false
      }
    },

    async ouvrirGalerie() {
      this.showGalerie = true
      this.loadingGalerie = true
      this.galerieError = ''
      try {
        const mat = this.participant?.matricule || ''
        const res = await fetch(`${API}?action=galerie&matricule=${encodeURIComponent(mat)}&rand=${Math.random()}`)
        const data = await res.json()
        if (!data.success) throw new Error(data.message)
        this.photos = data.photos || []
        this.resultatsPublies = Number(data.resultats_publies) === 1
      } catch (e) {
        this.galerieError = e.message
        this.photos = []
      } finally {
        this.loadingGalerie = false
      }
    },

    zoom(p) { this.zoomed = p },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.cp-root {
  --ink: var(--ink); --muted: var(--muted); --line: var(--line);
  --accent: #0891b2; --accent-dark: #0e7490; --accent-soft: #ecfeff;
  --gold: var(--gold);
  min-height: 100vh; background: linear-gradient(180deg, #ecfeff 0%, var(--surface) 320px);
  font-family: var(--display); color: var(--ink);
  padding-bottom: 40px;
}

.cp-topbar { max-width: 960px; margin: 0 auto; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.cp-brand { display: flex; align-items: center; gap: 11px; }
.cp-brand__dot { width: 12px; height: 12px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 5px rgba(8,145,178,.15); }
.cp-brand strong { display: block; font-size: 16px; }
.cp-brand small { color: var(--muted); font-size: 11.5px; }
.cp-logout { border: 1px solid var(--line); background: #fff; color: var(--muted); border-radius: 0; padding: 8px 12px; font-size: 12px; font-weight: 700; cursor: pointer; }

.cp-screen { max-width: 720px; margin: 0 auto; padding: 8px 20px; }
.cp-card { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 26px 22px; box-shadow: var(--shadow); }
.cp-card--auth { text-align: center; }
.cp-hero-icon { font-size: 46px; }
.cp-card--auth h1 { font-size: 25px; margin: 8px 0 10px; letter-spacing: -.02em; }
.cp-lead { color: var(--muted); font-size: 14px; line-height: 1.6; max-width: 460px; margin: 0 auto 20px; }
.cp-card__title { font-size: 19px; margin: 0 0 6px; }
.cp-card__sub { color: var(--muted); font-size: 13.5px; margin: 0 0 18px; }

.cp-rules { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
.cp-rule { display: flex; align-items: center; gap: 9px; background: var(--accent-soft); border-radius: 0; padding: 11px; text-align: left; }
.cp-rule span { font-size: 18px; }
.cp-rule strong { display: block; font-size: 12.5px; }
.cp-rule small { color: var(--muted); font-size: 11px; }

.cp-state-pill { display: inline-block; border-radius: 999px; padding: 7px 15px; font-size: 12.5px; font-weight: 800; margin-bottom: 18px; }
.cp-state-pill--open { background: var(--green-mist); color: var(--green); }
.cp-state-pill--closed { background: #fee2e2; color: #b91c1c; }

.cp-field { display: block; text-align: left; margin-bottom: 12px; }
.cp-field span { display: block; font-size: 12px; font-weight: 800; color: var(--ink-soft); margin-bottom: 6px; }
.cp-field em { color: var(--muted); font-style: normal; font-weight: 500; }
.cp-field input, .cp-field textarea { width: 100%; border: 1.5px solid var(--line); border-radius: 0; padding: 12px 14px; font-size: 14px; font-family: inherit; background: var(--surface); color: var(--ink); }
.cp-field input:focus, .cp-field textarea:focus { outline: none; border-color: var(--accent); background: #fff; box-shadow: 0 0 0 4px rgba(8,145,178,.12); }
.cp-hint { color: var(--muted); font-size: 11.5px; text-align: left; margin: 0 0 16px; }
.cp-note { color: var(--muted); font-size: 12px; text-align: center; margin: 12px 0 0; }

.cp-drop { display: block; border: 2px dashed #a5f3fc; border-radius: 0; background: var(--accent-soft); cursor: pointer; overflow: hidden; margin-bottom: 14px; transition: .18s; }
.cp-drop:hover { border-color: var(--accent); }
.cp-drop--filled { border-style: solid; padding: 0; }
.cp-drop input { display: none; }
.cp-drop__empty { padding: 36px 20px; text-align: center; }
.cp-drop__icon { font-size: 34px; display: block; margin-bottom: 8px; }
.cp-drop__empty strong { display: block; font-size: 14.5px; color: var(--accent-dark); }
.cp-drop__empty small { color: var(--muted); font-size: 12px; }
.cp-drop__preview { display: block; width: 100%; max-height: 380px; object-fit: cover; }
.cp-upbar { height: 6px; background: #e0f2fe; border-radius: 0; overflow: hidden; margin-bottom: 14px; }
.cp-upbar div { height: 100%; background: var(--accent); transition: width .2s; }

.cp-btn { border: 0; border-radius: 0; min-height: 48px; padding: 0 20px; font-size: 14px; font-weight: 800; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: transform .12s, opacity .12s; }
.cp-btn:disabled { opacity: .5; cursor: not-allowed; }
.cp-btn:not(:disabled):hover { transform: translateY(-1px); }
.cp-btn--primary { background: var(--accent); color: #fff; box-shadow: var(--shadow); }
.cp-btn--ghost { background: #fff; color: var(--accent-dark); border: 1.5px solid #a5f3fc; }
.cp-btn--lg { width: 100%; min-height: 54px; font-size: 15px; }
.cp-btn--full { width: 100%; margin-top: 14px; }

.cp-alert { border-radius: 0; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 14px; text-align: left; }
.cp-alert--error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.cp-alert--warn { background: var(--gold-mist); color: var(--gold); border: 1px solid #fed7aa; }
.cp-alert--wait { background: #f0f9ff; color: #075985; border: 1px solid #bae6fd; }
.cp-alert strong { display: block; margin-bottom: 3px; }
.cp-alert p { margin: 0; font-weight: 500; }

.cp-profile { display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 15px; margin-bottom: 16px; box-shadow: var(--shadow); }
.cp-avatar { width: 52px; height: 52px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: var(--accent-dark); color: #fff; display: grid; place-items: center; font-weight: 900; font-size: 16px; }
.cp-avatar img { width: 100%; height: 100%; object-fit: cover; }
.cp-profile__info strong { display: block; font-size: 15px; }
.cp-profile__info small { color: var(--muted); font-size: 12px; }

.cp-card--done { border-color: var(--green-mist); background: linear-gradient(180deg, var(--green-mist), #fff); }
.cp-done-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.cp-check { width: 40px; height: 40px; border-radius: 50%; background: var(--green); color: #fff; display: grid; place-items: center; font-size: 20px; font-weight: 900; flex-shrink: 0; }
.cp-done-head strong { display: block; font-size: 16px; }
.cp-done-head small { color: var(--muted); font-size: 12.5px; }
.cp-my-photo { margin: 0 0 14px; border-radius: 0; overflow: hidden; border: 1px solid var(--line); background: #fff; }
.cp-my-photo img { display: block; width: 100%; max-height: 340px; object-fit: cover; cursor: zoom-in; }
.cp-my-photo figcaption { padding: 12px 14px; }
.cp-my-photo strong { display: block; font-size: 14.5px; }
.cp-my-photo p { margin: 4px 0 0; color: var(--muted); font-size: 13px; line-height: 1.5; }
.cp-my-note { text-align: center; background: var(--gold-mist); border: 1px solid #fde68a; border-radius: 0; padding: 16px; margin-bottom: 6px; }
.cp-my-note__val { font-size: 40px; font-weight: 900; color: #b45309; line-height: 1; }
.cp-my-note__val small { font-size: 18px; }
.cp-my-note p { margin: 8px 0 0; color: #78350f; font-style: italic; font-size: 13px; }

/* Modales */
.cp-modal { position: fixed; inset: 0; background: rgba(15,23,42,.6); display: flex; align-items: center; justify-content: center; padding: 18px; z-index: 60; backdrop-filter: blur(4px); }
.cp-modal__box { background: #fff; border-radius: 0; padding: 22px; width: 440px; max-width: 100%; max-height: 90vh; overflow: auto; }
.cp-modal__box--xl { width: 940px; }
.cp-modal__box h3 { margin: 0 0 8px; font-size: 18px; }
.cp-modal__box > p { color: var(--muted); font-size: 13.5px; margin: 0 0 18px; }
.cp-modal__head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
.cp-modal__sub { color: var(--muted); font-size: 12.5px; margin: 4px 0 0; }
.cp-modal__x { border: 1px solid var(--line); background: #fff; border-radius: 0; width: 32px; height: 32px; cursor: pointer; color: var(--muted); flex-shrink: 0; }
.cp-modal__actions { display: flex; gap: 10px; }
.cp-modal__actions .cp-btn { flex: 1; }

.cp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
.cp-tile { position: relative; margin: 0; border: 1px solid var(--line); border-radius: 0; overflow: hidden; background: #fff; cursor: zoom-in; transition: .18s; }
.cp-tile:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.cp-tile--me { border-color: var(--accent); box-shadow: 0 0 0 2px rgba(8,145,178,.2); }
.cp-tile img { display: block; width: 100%; height: 160px; object-fit: cover; }
.cp-tile figcaption { padding: 10px 11px; }
.cp-tile figcaption strong { display: block; font-size: 13px; }
.cp-tile figcaption small { color: var(--muted); font-size: 11.5px; }
.cp-tile__note { display: inline-block; margin-top: 5px; background: var(--gold-mist); color: #b45309; border-radius: 999px; padding: 3px 9px; font-size: 11px; font-weight: 900; }
.cp-rank { position: absolute; top: 8px; left: 8px; z-index: 2; min-width: 28px; height: 28px; border-radius: 0; display: grid; place-items: center; font-weight: 900; font-size: 12.5px; background: rgba(255,255,255,.94); color: var(--muted); box-shadow: var(--shadow); }
.cp-rank--1 { background: var(--gold); color: #78350f; }
.cp-rank--2 { background: var(--line); color: var(--ink-soft); }
.cp-rank--3 { background: #fdba74; color: #7c2d12; }

.cp-lightbox { position: fixed; inset: 0; background: rgba(0,0,0,.92); z-index: 90; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }
.cp-lightbox img { max-width: 100%; max-height: 78vh; object-fit: contain; border-radius: 0; }
.cp-lightbox__x { position: absolute; top: 18px; right: 18px; width: 42px; height: 42px; border-radius: 0; border: 0; background: rgba(255,255,255,.16); color: #fff; font-size: 18px; cursor: pointer; }
.cp-lightbox__cap { color: #fff; text-align: center; margin-top: 16px; max-width: 640px; }
.cp-lightbox__cap strong { display: block; font-size: 16px; }
.cp-lightbox__cap span { color: var(--line); font-size: 13px; }
.cp-lightbox__cap p { color: var(--muted); font-size: 13px; margin: 8px 0 0; line-height: 1.5; }

.cp-loading, .cp-empty { text-align: center; padding: 34px; color: var(--muted); font-weight: 700; }

@media (max-width: 620px) {
  .cp-rules { grid-template-columns: 1fr; }
  .cp-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
  .cp-tile img { height: 130px; }
}
</style>
