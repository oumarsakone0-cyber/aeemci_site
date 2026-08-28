<template>
  <div class="ma-root">
    <header class="ma-topbar">
      <div class="ma-brand">
        <span class="ma-brand__dot"></span>
        <div>
          <strong>Mon affiche SENAFOI 2026</strong>
          <small>Génère et partage ton affiche personnalisée</small>
        </div>
      </div>
    </header>

    <!-- ══════ SAISIE DU MATRICULE ══════ -->
    <section v-if="!participant" class="ma-screen">
      <div class="ma-card ma-card--auth">
        <div class="ma-hero-icon">🖼️</div>
        <h1>Génère ton affiche personnalisée</h1>
        <p class="ma-lead">
          Entre ton matricule pour créer ton affiche avec ta photo,
          et invite tes proches à te rejoindre au SENAFOI 2026.
        </p>

        <div class="ma-cats">
          <span>Séminaristes</span>
          <span>SR</span>
          <span>CCR</span>
          <span>Commission</span>
          <span>Stands</span>
        </div>

        <label class="ma-field">
          <span>Votre matricule</span>
          <input
            v-model="matriculeInput"
            :disabled="chargement"
            placeholder="SEM2026… / SEM26SR… / COM… / STD2026…"
            @keyup.enter="rechercher"
            @input="matriculeInput = matriculeInput.toUpperCase().replace(/[^A-Z0-9-]/g, '')"
          />
        </label>

        <div v-if="erreur" class="ma-alert">{{ erreur }}</div>

        <button class="ma-btn ma-btn--primary" :disabled="chargement || !matriculeInput" @click="rechercher">
          {{ chargement ? 'Recherche…' : 'Générer mon affiche' }}
        </button>
      </div>
    </section>

    <!-- ══════ APERÇU ET TÉLÉCHARGEMENT ══════ -->
    <section v-else class="ma-screen">
      <div class="ma-card">
        <div class="ma-who">
          <div>
            <strong>{{ participant.nom }}</strong>
            <small>{{ participant.matricule }} · {{ libelleCategorie(participant.categorie) }}</small>
          </div>
          <button class="ma-link" @click="recommencer">Changer</button>
        </div>

        <!--
          Seuls les séminaristes, SR et CCR ont un sexe enregistré. Pour les
          autres catégories on demande, afin d'écrire « Prête » au féminin.
        -->
        <div class="ma-genre">
          <span>Vous êtes&nbsp;:</span>
          <div class="ma-genre__choix">
            <button :class="{ on: !feminin }" @click="choisirGenre(false)">Frère</button>
            <button :class="{ on: feminin }" @click="choisirGenre(true)">Sœur</button>
          </div>
        </div>

        <div class="ma-preview">
          <div v-if="generation" class="ma-preview__load">Création de votre affiche…</div>
          <img v-else-if="apercu" :src="apercu" alt="Aperçu de l'affiche" />
        </div>

        <button class="ma-btn ma-btn--primary" :disabled="generation || telechargement || !apercu" @click="telecharger">
          {{ telechargement ? 'Préparation…' : (iphone ? 'Enregistrer mon affiche' : 'Télécharger mon affiche') }}
        </button>

        <p v-if="iphone" class="ma-astuce">
          Sur iPhone, touchez « Enregistrer mon affiche » puis
          <strong>Enregistrer l'image</strong> dans le menu de partage.
          Vous pouvez aussi rester appuyé sur l'aperçu ci-dessus.
        </p>

        <div class="ma-share">
          <p>Partage-la sur&nbsp;:</p>
          <div class="ma-share__row">
            <a class="ma-share__btn wa" :href="lienWhatsApp" target="_blank" rel="noopener">WhatsApp</a>
            <button class="ma-share__btn cp" @click="copierTexte">
              {{ copie ? 'Texte copié ✓' : 'Copier le texte' }}
            </button>
          </div>
          <p class="ma-share__hint">
            Télécharge d'abord l'image, puis joins-la à ta publication.
          </p>
        </div>
      </div>
    </section>

    <footer class="ma-footer">
      <p>02-09 AOÛT 2026 · Lycée Houphouët Boigny, Korhogo</p>
      <p class="ma-slogan">« AEEMCI, pour une identité islamique ! »</p>
    </footer>
  </div>
</template>

<script>
import { genererAffiche, telechargerAffiche, CATEGORIES, estFeminin, estIOS } from './affiche-generateur.js'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_affiche_api.php'

export default {
  name: 'MonAffiche',
  data() {
    return {
      matriculeInput: '',
      chargement: false,
      erreur: '',
      participant: null,
      apercu: '',
      generation: false,
      copie: false,
      feminin: false,
      telechargement: false,
      iphone: estIOS(),
    }
  },
  computed: {
    textePartage() {
      if (!this.participant) return ''
      return (
        `Je serai au SENAFOI 2026 à Korhogo, du 02 au 09 août ! ` +
        `8 jours de formation islamique, de spiritualité et de fraternité. ` +
        `Rejoins-nous : https://www.aeemci-ce.ci/seminaristes`
      )
    },
    lienWhatsApp() {
      return 'https://wa.me/?text=' + encodeURIComponent(this.textePartage)
    },
  },
  methods: {
    libelleCategorie(c) {
      return (CATEGORIES[c] || CATEGORIES.seminariste).label
    },
    async rechercher() {
      if (!this.matriculeInput) return
      this.chargement = true
      this.erreur = ''
      try {
        const res = await fetch(`${API}?action=resoudre`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: this.matriculeInput }),
        })
        const data = await res.json()
        if (!data.success) throw new Error(data.message || 'Matricule introuvable')
        this.participant = data.participant
        // Pré-sélection quand le sexe est connu (séminaristes, SR, CCR)
        this.feminin = estFeminin(data.participant)
        await this.construireApercu()
      } catch (e) {
        this.erreur = e.message
      } finally {
        this.chargement = false
      }
    },
    donneesAffiche() {
      return { ...this.participant, feminin: this.feminin }
    },
    async choisirGenre(valeur) {
      if (this.feminin === valeur) return
      this.feminin = valeur
      await this.construireApercu()
    },
    async construireApercu() {
      this.generation = true
      try {
        const cv = await genererAffiche(this.donneesAffiche())
        this.apercu = cv.toDataURL('image/jpeg', 0.92)
      } catch (e) {
        this.erreur = "Impossible de générer l'affiche : " + e.message
      } finally {
        this.generation = false
      }
    },
    async telecharger() {
      this.telechargement = true
      this.erreur = ''
      try {
        const r = await telechargerAffiche(this.donneesAffiche())
        if (r.methode === 'onglet') {
          this.erreur = "L'image s'est ouverte dans un nouvel onglet : restez appuyé dessus pour l'enregistrer."
        }
      } catch (e) {
        this.erreur = e.message
      } finally {
        this.telechargement = false
      }
    },
    copierTexte() {
      navigator.clipboard?.writeText(this.textePartage).then(() => {
        this.copie = true
        setTimeout(() => { this.copie = false }, 2500)
      })
    },
    recommencer() {
      this.participant = null
      this.apercu = ''
      this.matriculeInput = ''
      this.erreur = ''
    },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.ma-root {
  --vert: var(--green); --vert-fonce: #0f5132; --vert-clair: #eaf7ee;
  --orange: var(--gold); --encre: var(--ink); --gris: var(--muted); --ligne: var(--line);
  min-height: 100vh;
  background: linear-gradient(180deg, #eaf7ee 0%, var(--surface) 340px);
  font-family: var(--display);
  color: var(--encre); padding-bottom: 30px;
}

.ma-topbar { max-width: 640px; margin: 0 auto; padding: 18px 20px; }
.ma-brand { display: flex; align-items: center; gap: 11px; }
.ma-brand__dot { width: 12px; height: 12px; border-radius: 50%; background: var(--vert); box-shadow: 0 0 0 5px rgba(21,128,61,.15); }
.ma-brand strong { display: block; font-size: 16px; }
.ma-brand small { color: var(--gris); font-size: 11.5px; }

.ma-screen { max-width: 640px; margin: 0 auto; padding: 4px 20px; }
.ma-card { background: #fff; border: 1px solid var(--ligne); border-radius: 0; padding: 26px 22px; box-shadow: var(--shadow); }
.ma-card--auth { text-align: center; }
.ma-hero-icon { font-size: 44px; }
.ma-card--auth h1 { font-size: 24px; margin: 8px 0 10px; letter-spacing: -.02em; }
.ma-lead { color: var(--gris); font-size: 14px; line-height: 1.65; margin-bottom: 18px; }

.ma-cats { display: flex; gap: 6px; justify-content: center; flex-wrap: wrap; margin-bottom: 20px; }
.ma-cats span { background: var(--vert-clair); color: var(--vert-fonce); border-radius: 999px; padding: 6px 13px; font-size: 12px; font-weight: 800; }

.ma-field { display: block; text-align: left; margin-bottom: 14px; }
.ma-field span { display: block; font-size: 12px; font-weight: 800; color: var(--ink-soft); margin-bottom: 6px; }
.ma-field input { width: 100%; height: 52px; border: 1.5px solid var(--ligne); border-radius: 0; padding: 0 15px; font-size: 16px; font-weight: 700; letter-spacing: .04em; background: var(--surface); color: var(--encre); }
.ma-field input:focus { outline: none; border-color: var(--vert); background: #fff; box-shadow: 0 0 0 4px rgba(21,128,61,.12); }

.ma-alert { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; border-radius: 0; padding: 12px 14px; font-size: 13px; font-weight: 600; margin-bottom: 14px; text-align: left; }

.ma-btn { width: 100%; border: 0; border-radius: 0; min-height: 54px; font-size: 15px; font-weight: 800; font-family: inherit; cursor: pointer; transition: transform .12s, opacity .12s; }
.ma-btn--primary { background: var(--vert); color: #fff; box-shadow: var(--shadow); }
.ma-btn:not(:disabled):hover { transform: translateY(-1px); }
.ma-btn:disabled { opacity: .5; cursor: not-allowed; }

.ma-who { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding-bottom: 14px; border-bottom: 1px solid var(--ligne); margin-bottom: 16px; }
.ma-who strong { display: block; font-size: 16px; }
.ma-who small { color: var(--gris); font-size: 12.5px; }
.ma-link { background: none; border: 0; color: var(--vert); font-size: 13px; font-weight: 800; cursor: pointer; text-decoration: underline; }

.ma-astuce { margin: 10px 0 0; font-size: 12px; line-height: 1.55; color: var(--gris); background: var(--green-mist); border: 1px solid var(--green-mist); border-radius: 0; padding: 10px 12px; }
.ma-astuce strong { color: var(--vert-fonce); }

.ma-genre { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.ma-genre > span { font-size: 12.5px; font-weight: 800; color: var(--ink-soft); }
.ma-genre__choix { display: flex; background: var(--surface); border-radius: 0; padding: 3px; gap: 3px; }
.ma-genre__choix button { border: 0; background: none; padding: 9px 20px; border-radius: 0; font-size: 13px; font-weight: 800; color: var(--gris); cursor: pointer; font-family: inherit; }
.ma-genre__choix button.on { background: var(--vert); color: #fff; }

.ma-preview { border-radius: 0; overflow: hidden; border: 1px solid var(--ligne); background: var(--surface); margin-bottom: 16px; min-height: 220px; display: grid; place-items: center; }
.ma-preview img { display: block; width: 100%; }
.ma-preview__load { padding: 60px 20px; color: var(--gris); font-weight: 700; }

.ma-share { margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--ligne); }
.ma-share > p { font-size: 13px; font-weight: 800; color: var(--ink-soft); margin: 0 0 10px; }
.ma-share__row { display: flex; gap: 9px; }
.ma-share__btn { flex: 1; border-radius: 0; min-height: 46px; font-size: 13.5px; font-weight: 800; font-family: inherit; cursor: pointer; border: 0; display: flex; align-items: center; justify-content: center; text-decoration: none; }
.ma-share__btn.wa { background: #25d366; color: #fff; }
.ma-share__btn.cp { background: #fff; color: var(--vert-fonce); border: 1.5px solid var(--green-mist); }
.ma-share__hint { margin: 10px 0 0; font-size: 11.5px; color: var(--gris); text-align: center; }

.ma-footer { max-width: 640px; margin: 24px auto 0; padding: 0 20px; text-align: center; color: var(--gris); font-size: 12px; line-height: 1.7; }
.ma-slogan { font-weight: 800; color: var(--vert-fonce); }
</style>
