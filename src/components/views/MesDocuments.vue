<template>
  <div class="app-container">
    <!-- Header, repris de la page d'inscription des séminaristes -->
    <header class="app-header">
      <div class="header-content">
        <div class="header-left">
          <div class="logo">
            <div class="logo-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
              </svg>
            </div>
            <div class="logo-text">
              <h1>AEEMCI</h1>
              <p>Séminaire National de Formation Islamique et Managériale</p>
            </div>
          </div>
        </div>
        <div class="header-right">
          <div class="year-badge">SENAFOI 26</div>
        </div>
      </div>
    </header>

    <main class="main-content">
      <div class="form-wrapper">
        <div class="welcome-card">
          <!-- Bannière du séminaire -->
          <div class="senafoi-banner-hero">
            <img
              :src="senafoiBanner"
              alt="SENAFOI Korhogo 2026 - 37e edition"
              class="senafoi-banner-img"
            />
          </div>

          <div class="card-body">
            <!-- ══════════ SAISIE DU MATRICULE ══════════ -->
            <template v-if="etape === 'saisie'">
              <div class="section-intro">
                <h2>Retrait de vos documents</h2>
                <p>
                  Saisissez votre matricule pour obtenir votre diplôme du SENAFOI 2026.
                  Si vous avez composé le test de sortie, votre bulletin de note vous est
                  remis en même temps.
                </p>
              </div>

              <label class="md-champ">
                <span>Votre matricule</span>
                <input
                  ref="champ"
                  v-model="matricule"
                  :disabled="chargement"
                  placeholder="SEM2026… / SEM26SR… / SEM26CCR…"
                  autocomplete="off"
                  autocapitalize="characters"
                  spellcheck="false"
                  @keyup.enter="chercher"
                  @input="normaliser"
                />
              </label>
              <p class="md-aide">
                Séminaristes, SR, CCR, comité d'organisation, formateurs et imams.
              </p>

              <div v-if="erreur" class="md-alerte">{{ erreur }}</div>

              <button class="md-btn md-btn--principal" :disabled="chargement || !matricule" @click="chercher">
                {{ chargement ? 'Recherche…' : 'Obtenir mes documents' }}
              </button>
            </template>

            <!-- ══════════ RÉSULTAT ══════════ -->
            <template v-else>
              <div class="md-identite">
                <div class="md-photo">
                  <img v-if="personne.photo" :src="personne.photo" alt="" />
                  <span v-else>{{ initiales }}</span>
                </div>
                <div class="md-identite__txt">
                  <strong>{{ personne.prenom }} {{ personne.nom }}</strong>
                  <small>{{ personne.matricule }}</small>
                  <span class="md-pastille">{{ personne.population }}</span>
                </div>
              </div>

              <p v-if="personne.detail" class="md-detail">{{ personne.detail }}</p>

              <h3 class="md-titre-docs">Vos documents</h3>

              <div class="md-docs">
                <article class="md-doc">
                  <div class="md-doc__icone">📜</div>
                  <div class="md-doc__txt">
                    <strong>{{ document.libelle }}</strong>
                    <small>Document officiel signé</small>
                  </div>
                  <button class="md-btn md-btn--doc" :disabled="enCours === 'diplome'" @click="telechargerDiplome">
                    {{ enCours === 'diplome' ? '…' : 'Télécharger' }}
                  </button>
                </article>

                <article v-if="bulletin" class="md-doc md-doc--bulletin">
                  <div class="md-doc__icone">📊</div>
                  <div class="md-doc__txt">
                    <strong>Bulletin de note</strong>
                    <small>
                      Moyenne {{ bulletin.moyenne.toFixed(2) }}/20 ·
                      <b :class="bulletin.admis ? 'ok' : 'ko'">{{ bulletin.admis ? 'Admis' : 'Refusé' }}</b>
                    </small>
                  </div>
                  <button class="md-btn md-btn--doc" :disabled="enCours === 'bulletin'" @click="telechargerBulletinPdf">
                    {{ enCours === 'bulletin' ? '…' : 'Télécharger' }}
                  </button>
                </article>

                <p v-else class="md-sans-bulletin">
                  Aucun bulletin ne vous concerne : seuls les séminaristes ayant composé
                  le test de sortie en reçoivent un.
                </p>
              </div>

              <div v-if="avertissement" class="md-avis">{{ avertissement }}</div>
              <div v-if="erreur" class="md-alerte">{{ erreur }}</div>

              <button class="md-btn md-btn--fantome" @click="recommencer">
                ← Un autre matricule
              </button>
            </template>
          </div>
        </div>

        <p class="md-pied">AEEMCI · Pour une identité islamique</p>
      </div>
    </main>
  </div>
</template>

<script>
import senafoiBanner from '../../assets/senafoi-2026-banner.jpeg'
import { telechargerDocument, prechargerFond } from '../../utils/diplomes'
import { telechargerBulletin } from '../../utils/bulletins'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_documents_api.php'

// Date portée par les documents délivrés. Elle est fixe : tous les diplômes
// d'une même édition doivent porter la même date, quel que soit le jour du
// retrait.
const DATE_DOCUMENTS = '2026-08-09'

export default {
  name: 'MesDocuments',
  data() {
    return {
      senafoiBanner,
      etape: 'saisie',
      matricule: '',
      chargement: false,
      erreur: '',
      avertissement: '',
      enCours: '',
      personne: null,
      document: null,
      bulletin: null,
    }
  },
  computed: {
    initiales() {
      const p = this.personne
      if (!p) return '?'
      return `${(p.prenom || '')[0] || ''}${(p.nom || '')[0] || ''}`.toUpperCase() || '?'
    },
  },
  mounted() {
    this.$refs.champ?.focus()
  },
  methods: {
    // Le matricule est toujours en majuscules : on corrige la saisie au lieu
    // de la refuser, l'utilisateur n'a pas à connaître la convention.
    normaliser() {
      this.matricule = this.matricule.toUpperCase().replace(/[^A-Z0-9]/g, '')
    },

    async chercher() {
      if (!this.matricule) return
      this.chargement = true
      this.erreur = ''
      try {
        const res = await fetch(`${API}?action=chercher`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ matricule: this.matricule }),
        })
        const data = await res.json()
        if (!data.success) {
          this.erreur = data.message || 'Matricule introuvable.'
          return
        }
        this.personne = data.personne
        this.document = data.document
        this.bulletin = data.bulletin
        this.avertissement = ''
        this.etape = 'resultat'

        // Le modèle du diplôme est une grande image : on la télécharge pendant
        // que la personne lit son profil, pour que le clic soit immédiat.
        prechargerFond(this.document.modele)
      } catch (e) {
        this.erreur = 'Connexion impossible : ' + e.message
      } finally {
        this.chargement = false
      }
    },

    async telechargerDiplome() {
      this.enCours = 'diplome'
      this.erreur = ''
      try {
        const pose = await telechargerDocument(
          this.personne, this.document.modele, DATE_DOCUMENTS,
          `${this.nomFichier('diplome')}.pdf`
        )
        // Sans modèle déposé, le document sort avec le nom mais sans fond :
        // mieux vaut le dire que de laisser croire à un document conforme.
        this.avertissement = pose
          ? ''
          : "Le modèle de ce document n'a pas encore été déposé : le PDF sort sans fond. "
            + "Signalez-le à la commission Formation."
      } catch (e) {
        this.erreur = 'Génération impossible : ' + e.message
      } finally {
        this.enCours = ''
      }
    },

    async telechargerBulletinPdf() {
      this.enCours = 'bulletin'
      this.erreur = ''
      try {
        await telechargerBulletin(this.bulletin, `${this.nomFichier('bulletin')}.pdf`)
      } catch (e) {
        this.erreur = 'Génération impossible : ' + e.message
      } finally {
        this.enCours = ''
      }
    },

    nomFichier(prefixe) {
      const nom = `${this.personne.nom || ''}_${this.personne.prenom || ''}`
        .normalize('NFD').replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9_-]+/gi, '_').replace(/_+/g, '_').replace(/^_|_$/g, '')
      return `${prefixe}_${this.personne.matricule}_${nom}`
    },

    recommencer() {
      this.etape = 'saisie'
      this.matricule = ''
      this.personne = null
      this.document = null
      this.bulletin = null
      this.erreur = ''
      this.avertissement = ''
      this.$nextTick(() => this.$refs.champ?.focus())
    },
  },
}
</script>

<style scoped>
/* ── Cadre commun aux pages SENAFOI (voir Seminariste.vue) ─────────────── */
.app-container {
  min-height: 100vh;
  background: linear-gradient(160deg, var(--green-mist) 0%, var(--surface) 100%);
  font-family: var(--display);
}
.app-header { background: var(--green); box-shadow: var(--shadow); }
.header-content {
  max-width: 960px; margin: 0 auto; padding: .85rem 1.5rem;
  display: flex; justify-content: space-between; align-items: center;
}
.logo { display: flex; align-items: center; gap: .75rem; }
.logo-icon {
  width: 2.4rem; height: 2.4rem; background: rgba(255,255,255,0.2); color: #fff;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.logo-text h1 { font-size: 1.35rem; font-weight: 700; color: #fff; margin: 0; }
.logo-text p { font-size: .75rem; color: rgba(255,255,255,.8); margin: 0; }
.header-right { display: flex; align-items: center; gap: .75rem; }
.year-badge {
  background: #fff; color: var(--green); font-weight: 800; font-size: .85rem;
  padding: .3rem .9rem; border-radius: 0; letter-spacing: 2px; white-space: nowrap;
}
.main-content { max-width: 960px; margin: 0 auto; padding: 2rem 1rem; }
.welcome-card {
  background: #fff; border-radius: 0; box-shadow: var(--shadow);
  overflow: hidden;
}
.senafoi-banner-hero {
  background: #fff; padding: clamp(.75rem, 2vw, 1.15rem); border-bottom: 1px solid var(--green-mist);
}
.senafoi-banner-img {
  display: block; width: 100%; height: auto; max-height: 260px; object-fit: contain;
  border: 3px solid var(--green); border-radius: 0; box-shadow: var(--shadow);
}

/* ── Contenu propre au retrait des documents ──────────────────────────── */
.card-body { padding: clamp(1.25rem, 3vw, 2rem); }

.section-intro { text-align: center; margin-bottom: 1.5rem; }
.section-intro h2 { font-size: 1.4rem; color: var(--green); margin: 0 0 .5rem; }
.section-intro p { color: var(--muted); font-size: .9rem; line-height: 1.6; margin: 0 auto; max-width: 34rem; }

.md-champ { display: block; max-width: 30rem; margin: 0 auto; }
.md-champ span {
  display: block; font-size: .72rem; font-weight: 800; color: var(--ink-soft);
  text-transform: uppercase; letter-spacing: .04em; margin-bottom: .4rem;
}
.md-champ input {
  width: 100%; height: 3.3rem; border: 1.5px solid var(--line); border-radius: 0;
  padding: 0 1rem; font-size: 1.05rem; font-weight: 800; letter-spacing: .08em;
  background: var(--surface); color: #12171c; font-family: inherit;
}
.md-champ input:focus {
  outline: none; border-color: var(--green); background: #fff;
  box-shadow: 0 0 0 4px rgba(21,128,61,.1);
}
.md-aide {
  max-width: 30rem; margin: .5rem auto 1.25rem; color: var(--muted); font-size: .75rem;
}

.md-btn {
  border: 0; border-radius: 0; min-height: 3.1rem; padding: 0 1.25rem;
  font-size: .95rem; font-weight: 800; cursor: pointer; font-family: inherit;
  display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
  transition: transform .12s, opacity .12s;
}
.md-btn:disabled { opacity: .55; cursor: default; }
.md-btn:not(:disabled):active { transform: scale(.985); }
.md-btn--principal {
  background: var(--green); color: #fff; box-shadow: var(--shadow);
  width: 100%; max-width: 30rem; margin: 0 auto; display: flex;
}
.md-btn--fantome {
  background: transparent; color: var(--green); border: 1.5px solid var(--green-mist);
  margin-top: 1.25rem; min-height: 2.7rem; font-size: .85rem; width: 100%;
}
.md-btn--doc {
  width: auto; min-height: 2.5rem; padding: 0 1rem; font-size: .8rem;
  background: var(--green); color: #fff; flex-shrink: 0; border-radius: 0;
}

.md-alerte {
  background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 0;
  padding: .7rem .9rem; font-size: .8rem; font-weight: 700; margin: 0 auto 1rem;
  max-width: 30rem;
}
.md-avis {
  background: var(--gold-mist); color: #92400e; border: 1px solid #fde68a; border-radius: 0;
  padding: .7rem .9rem; font-size: .8rem; font-weight: 600; margin-top: 1rem; line-height: 1.5;
}

/* Résultat */
.md-identite {
  display: flex; align-items: center; gap: .9rem;
  padding-bottom: 1rem; border-bottom: 1px solid var(--surface); margin-bottom: 1rem;
}
.md-photo {
  width: 3.8rem; height: 3.8rem; border-radius: 50%; overflow: hidden; flex-shrink: 0;
  background: var(--green-mist); color: var(--green); display: grid; place-items: center;
  font-weight: 900; font-size: 1.25rem; border: 2px solid rgba(21,128,61,.15);
}
.md-photo img { width: 100%; height: 100%; object-fit: cover; }
.md-identite__txt { min-width: 0; }
.md-identite__txt strong { display: block; font-size: 1.05rem; line-height: 1.25; color: var(--green); }
.md-identite__txt small {
  display: block; color: var(--muted); font-size: .8rem; font-weight: 700;
  letter-spacing: .03em; margin: .15rem 0 .4rem;
}
.md-pastille {
  display: inline-block; background: var(--green-mist); color: var(--green); border-radius: 999px;
  padding: .2rem .7rem; font-size: .68rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: .05em;
}
.md-detail { color: var(--muted); font-size: .82rem; margin-bottom: 1rem; }

.md-titre-docs {
  font-size: .75rem; font-weight: 800; color: var(--muted); text-transform: uppercase;
  letter-spacing: .08em; margin: 0 0 .7rem;
}
.md-docs { display: flex; flex-direction: column; gap: .7rem; }
.md-doc {
  display: flex; align-items: center; gap: .85rem; padding: .9rem;
  border: 1.5px solid var(--line); border-radius: 0; background: #fcfdfc;
}
.md-doc--bulletin { border-color: rgba(21,128,61,.25); background: #f6fbf8; }
.md-doc__icone { font-size: 1.6rem; flex-shrink: 0; }
.md-doc__txt { flex: 1; min-width: 0; }
.md-doc__txt strong { display: block; font-size: .9rem; color: var(--green); }
.md-doc__txt small { color: var(--muted); font-size: .75rem; }
.md-doc__txt b.ok { color: var(--green); }
.md-doc__txt b.ko { color: #b91c1c; }
.md-sans-bulletin {
  color: var(--muted); font-size: .78rem; line-height: 1.55; background: #f8fafb;
  border-radius: 0; padding: .7rem .85rem; margin: 0;
}

.md-pied {
  text-align: center; color: var(--muted); font-size: .72rem; font-weight: 700;
  letter-spacing: .05em; margin-top: 1.25rem;
}

@media (max-width: 640px) {
  .header-content { padding: .7rem 1rem; }
  .logo-text h1 { font-size: 1.15rem; }
  .logo-text p { font-size: .65rem; }
  .main-content { padding: 1.25rem .75rem; }
  .card-body { padding: 1.1rem; }
  .md-doc { padding: .75rem; gap: .6rem; }
  .md-btn--doc { padding: 0 .8rem; font-size: .75rem; }
}
</style>
