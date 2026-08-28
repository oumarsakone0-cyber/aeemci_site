<template>
  <div class="rc">
    <!-- ══ Barre supérieure ══════════════════════════════════════════ -->
    <header class="rc-bar">
      <div class="rc-bar-in">
        <div class="rc-bar-titre">
          <span>SENAFOI 2026 · Korhogo</span>
          <strong>Fiche de suivi des tâches</strong>
        </div>
        <div v-if="!soumis" class="rc-jauge" :title="`${sectionsRemplies} sections sur ${SECTIONS.length}`">
          <svg viewBox="0 0 36 36">
            <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="3.2" />
            <circle
              cx="18" cy="18" r="15.9" fill="none" stroke="#fff" stroke-width="3.2"
              stroke-linecap="round" :stroke-dasharray="`${avancement} 100`" transform="rotate(-90 18 18)"
            />
          </svg>
          <em>{{ avancement }}%</em>
        </div>
      </div>
    </header>

    <!-- ══ Rapport déposé ════════════════════════════════════════════ -->
    <main v-if="soumis" class="rc-fin">
      <section class="rc-succes">
        <div class="rc-coche">
          <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.6">
            <path d="M20 6L9 17l-5-5" />
          </svg>
        </div>
        <h2>Rapport soumis</h2>
        <p>
          Commission <strong>{{ form.commission }}</strong> · journée du
          <strong>{{ jourLisible(form.date_rapport) }}</strong>.
        </p>
        <p class="rc-discret">
          Le formulaire est clos pour cette journée. Vous pouvez encore imprimer votre rapport.
        </p>
        <div class="rc-succes-actions">
          <button class="rc-btn rc-btn--vert" :disabled="impression" @click="exporterPdf">
            {{ impression ? 'Préparation…' : 'Imprimer en PDF' }}
          </button>
          <button class="rc-btn" @click="recommencer">Déposer pour une autre commission</button>
        </div>
      </section>
    </main>

    <!-- ══ Formulaire ════════════════════════════════════════════════ -->
    <div v-else class="rc-page">
      <!-- Sommaire -->
      <aside class="rc-sommaire">
        <p class="rc-sommaire-titre">Sommaire</p>
        <nav>
          <button
            v-for="(s, i) in SECTIONS" :key="s.cle"
            type="button"
            :class="{ on: sectionActive === s.cle, faite: estRemplie(s.cle) }"
            @click="allerA(s.cle)"
          >
            <span class="rc-pastille">
              <svg v-if="estRemplie(s.cle)" viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="3.4">
                <path d="M20 6L9 17l-5-5" />
              </svg>
              <template v-else>{{ i + 1 }}</template>
            </span>
            <em>{{ s.titre }}</em>
          </button>
        </nav>
        <p class="rc-sommaire-pied">{{ sectionsRemplies }} / {{ SECTIONS.length }} renseignées</p>
      </aside>

      <main class="rc-corps">
        <div v-if="erreur" class="rc-alerte">{{ erreur }}</div>

        <div v-if="deja" class="rc-bloque">
          <strong>Rapport déjà déposé</strong>
          <p>{{ deja }}</p>
          <button class="rc-btn" @click="deja = ''">Choisir une autre journée</button>
        </div>

        <template v-else>
          <!-- 1 · Identification ─────────────────────────────────── -->
          <section id="sec-identification" ref="secs" data-cle="identification" class="rc-carte">
            <header><span class="rc-num">1</span><h2>Identification</h2></header>
            <div class="rc-contenu">
              <label class="rc-champ">
                <span>Journée concernée</span>
                <div class="rc-jours">
                  <button
                    v-for="j in jours" :key="j" type="button"
                    :class="{ on: form.date_rapport === j }" @click="choisirJour(j)"
                  >
                    <b>{{ jourCourt(j) }}</b>
                    <i>{{ moisCourt(j) }}</i>
                  </button>
                </div>
              </label>

              <div class="rc-duo">
                <label class="rc-champ">
                  <span>Commission</span>
                  <select v-model="commissionChoisie" @change="surChoixCommission">
                    <option value="">Choisir dans la liste…</option>
                    <option v-for="c in commissions" :key="c" :value="c">{{ c }}</option>
                    <option value="__autre__">Ma commission n'est pas dans la liste</option>
                  </select>
                </label>
                <label v-if="commissionChoisie === '__autre__'" class="rc-champ">
                  <span>Nom de la commission</span>
                  <input v-model="form.commission" placeholder="Saisissez le nom" @blur="verifierDepot" />
                </label>
              </div>

              <div class="rc-duo">
                <label class="rc-champ">
                  <span>Nom et prénoms</span>
                  <input v-model="form.nom_prenoms" placeholder="Qui rédige ce rapport ?" />
                </label>
                <label class="rc-champ">
                  <span>Fonction</span>
                  <input v-model="form.fonction" placeholder="Ex : Responsable de commission" />
                </label>
              </div>

              <div class="rc-duo">
                <label class="rc-champ">
                  <span>Heure de début</span>
                  <input v-model="form.heure_debut" type="time" />
                </label>
                <label class="rc-champ">
                  <span>Heure de fin</span>
                  <input v-model="form.heure_fin" type="time" />
                </label>
              </div>
            </div>
          </section>

          <!-- 2 · Missions planifiées ────────────────────────────── -->
          <section id="sec-missions" ref="secs" data-cle="missions" class="rc-carte">
            <header>
              <span class="rc-num">2</span>
              <h2>Missions planifiées</h2>
              <small>à remplir le matin</small>
            </header>
            <div class="rc-contenu">
              <div v-for="(m, i) in form.missions" :key="'m' + i" class="rc-item">
                <div class="rc-item-tete">
                  <span class="rc-puce">{{ i + 1 }}</span>
                  <button v-if="form.missions.length > 1" type="button" class="rc-x" @click="form.missions.splice(i, 1)">×</button>
                </div>
                <input v-model="m.tache" placeholder="Tâche prévue" />
                <div class="rc-duo">
                  <input v-model="m.heure" type="time" title="Heure prévue" />
                  <select v-model="m.priorite">
                    <option value="">Priorité</option>
                    <option value="Haute">Haute</option>
                    <option value="Moyenne">Moyenne</option>
                    <option value="Basse">Basse</option>
                  </select>
                </div>
              </div>
              <button type="button" class="rc-ajout" @click="form.missions.push({ tache: '', heure: '', priorite: '' })">
                + Ajouter une tâche
              </button>
            </div>
          </section>

          <!-- 3 · Réalisation ────────────────────────────────────── -->
          <section id="sec-realisation" ref="secs" data-cle="realisation" class="rc-carte">
            <header>
              <span class="rc-num">3</span>
              <h2>Réalisation</h2>
              <small>à remplir le soir</small>
            </header>
            <div class="rc-contenu">
              <div v-if="!form.realisations.length" class="rc-vide">
                Les tâches saisies plus haut apparaîtront ici automatiquement.
              </div>
              <div v-for="(r, i) in form.realisations" :key="'r' + i" class="rc-item">
                <strong class="rc-tache-nom">{{ r.tache }}</strong>
                <div class="rc-statuts">
                  <button
                    v-for="s in STATUTS" :key="s" type="button"
                    :class="['rc-statut', statutClasse(s), { on: r.statut === s }]"
                    @click="r.statut = s"
                  >{{ s }}</button>
                </div>
                <div class="rc-duo">
                  <label class="rc-champ">
                    <span>Heure de réalisation</span>
                    <input v-model="r.heure" type="time" />
                  </label>
                  <label class="rc-champ">
                    <span>Observation</span>
                    <input v-model="r.observation" placeholder="Facultatif" />
                  </label>
                </div>
              </div>
            </div>
          </section>

          <!-- 4 · Activités imprévues ────────────────────────────── -->
          <section id="sec-imprevus" ref="secs" data-cle="imprevus" class="rc-carte">
            <header><span class="rc-num">4</span><h2>Activités imprévues</h2></header>
            <div class="rc-contenu">
              <textarea v-model="form.imprevus" rows="3"
                        placeholder="Décrivez les activités non prévues effectuées."></textarea>
            </div>
          </section>

          <!-- 5 · Difficultés ────────────────────────────────────── -->
          <section id="sec-difficultes" ref="secs" data-cle="difficultes" class="rc-carte">
            <header><span class="rc-num">5</span><h2>Difficultés rencontrées</h2></header>
            <div class="rc-contenu">
              <label class="rc-bascule">
                <input v-model="form.difficultes_aucune" type="checkbox" />
                <span>Aucune difficulté rencontrée</span>
              </label>
              <textarea v-if="!form.difficultes_aucune" v-model="form.difficultes" rows="3"
                        placeholder="Précisez."></textarea>
            </div>
          </section>

          <!-- 6 · Besoins ────────────────────────────────────────── -->
          <section id="sec-besoins" ref="secs" data-cle="besoins" class="rc-carte">
            <header>
              <span class="rc-num">6</span>
              <h2>Besoins</h2>
              <small>pour mieux travailler demain</small>
            </header>
            <div class="rc-contenu">
              <textarea v-model="form.besoins" rows="3"
                        placeholder="Matériel, renfort, information, autorisation…"></textarea>
            </div>
          </section>

          <!-- 7 · Incidents ──────────────────────────────────────── -->
          <section id="sec-incidents" ref="secs" data-cle="incidents" class="rc-carte">
            <header><span class="rc-num">7</span><h2>Incidents</h2></header>
            <div class="rc-contenu">
              <div class="rc-oui-non">
                <button type="button" :class="{ on: form.incident_oui }" @click="activerIncident(true)">Oui</button>
                <button type="button" :class="{ on: !form.incident_oui }" @click="activerIncident(false)">Non</button>
              </div>

              <template v-if="form.incident_oui">
                <div v-for="(inc, i) in form.incidents" :key="'i' + i" class="rc-item">
                  <div class="rc-duo">
                    <label class="rc-champ">
                      <span>Heure</span>
                      <input v-model="inc.heure" type="time" />
                    </label>
                    <label class="rc-champ">
                      <span>Description</span>
                      <input v-model="inc.description" placeholder="Que s'est-il passé ?" />
                    </label>
                  </div>
                  <label class="rc-champ">
                    <span>Action entreprise</span>
                    <input v-model="inc.action" placeholder="Ce qui a été fait" />
                  </label>
                  <button v-if="form.incidents.length > 1" type="button" class="rc-lien-x" @click="form.incidents.splice(i, 1)">
                    Retirer cet incident
                  </button>
                </div>
                <button type="button" class="rc-ajout" @click="form.incidents.push({ heure: '', description: '', action: '' })">
                  + Ajouter un incident
                </button>
              </template>
            </div>
          </section>

          <!-- 8 · Temps consacré ─────────────────────────────────── -->
          <section id="sec-temps" ref="secs" data-cle="temps" class="rc-carte">
            <header><span class="rc-num">8</span><h2>Temps consacré</h2></header>
            <div class="rc-contenu">
              <label class="rc-champ rc-court">
                <span>Nombre approximatif d'heures de travail</span>
                <input v-model="form.heures_travail" type="number" min="0" max="24" step="0.5" placeholder="Ex : 9" />
              </label>
            </div>
          </section>

          <!-- 9 · Coordination ───────────────────────────────────── -->
          <section id="sec-coordination" ref="secs" data-cle="coordination" class="rc-carte">
            <header><span class="rc-num">9</span><h2>Coordination</h2></header>
            <div class="rc-contenu">
              <p class="rc-question">Avez-vous travaillé avec une autre commission ?</p>
              <div class="rc-oui-non">
                <button type="button" :class="{ on: form.coordination_oui }" @click="form.coordination_oui = true">Oui</button>
                <button type="button" :class="{ on: !form.coordination_oui }" @click="form.coordination_oui = false">Non</button>
              </div>
              <template v-if="form.coordination_oui">
                <label class="rc-champ">
                  <span>Laquelle ?</span>
                  <input v-model="form.coordination_commission" list="rc-commissions" placeholder="Nom de la commission" />
                </label>
                <datalist id="rc-commissions">
                  <option v-for="c in commissions" :key="c" :value="c" />
                </datalist>
                <label class="rc-champ">
                  <span>Objet de la collaboration</span>
                  <textarea v-model="form.coordination_objet" rows="2"></textarea>
                </label>
              </template>
            </div>
          </section>

          <!-- 10 · Satisfaction ──────────────────────────────────── -->
          <section id="sec-satisfaction" ref="secs" data-cle="satisfaction" class="rc-carte">
            <header><span class="rc-num">10</span><h2>Satisfaction de la journée</h2></header>
            <div class="rc-contenu">
              <div class="rc-etoiles">
                <button
                  v-for="n in 5" :key="n" type="button"
                  :class="{ on: n <= form.satisfaction }" :title="`${n} sur 5`"
                  @click="form.satisfaction = n"
                >★</button>
                <em v-if="form.satisfaction">{{ MENTIONS[form.satisfaction - 1] }}</em>
              </div>
            </div>
          </section>

          <!-- 11 · Priorités ─────────────────────────────────────── -->
          <section id="sec-priorites" ref="secs" data-cle="priorites" class="rc-carte">
            <header>
              <span class="rc-num">11</span>
              <h2>Priorités pour demain</h2>
              <small>les trois principales tâches</small>
            </header>
            <div class="rc-contenu">
              <div v-for="(p, i) in form.priorites" :key="'p' + i" class="rc-priorite">
                <span class="rc-puce">{{ i + 1 }}</span>
                <input v-model="p.tache" :placeholder="`Priorité ${i + 1}`" />
              </div>
            </div>
          </section>

          <!-- 12 · Commentaires ──────────────────────────────────── -->
          <section id="sec-commentaires" ref="secs" data-cle="commentaires" class="rc-carte">
            <header><span class="rc-num">12</span><h2>Commentaires libres</h2></header>
            <div class="rc-contenu">
              <textarea v-model="form.commentaires" rows="3"
                        placeholder="Tout ce qui mérite d'être remonté."></textarea>
            </div>
          </section>

          <!-- Récapitulatif -->
          <section class="rc-carte rc-carte--recap">
            <header><span class="rc-num">✓</span><h2>Avant d'envoyer</h2></header>
            <div class="rc-contenu">
              <dl class="rc-recap">
                <div><dt>Journée</dt><dd>{{ jourLisible(form.date_rapport) }}</dd></div>
                <div><dt>Commission</dt><dd>{{ form.commission || '—' }}</dd></div>
                <div><dt>Rédacteur</dt><dd>{{ form.nom_prenoms || '—' }}<template v-if="form.fonction"> · {{ form.fonction }}</template></dd></div>
                <div><dt>Tâches</dt><dd>{{ tachesSaisies.length }} prévue(s) · {{ compteStatut('Réalisée') }} réalisée(s)</dd></div>
                <div><dt>Incidents</dt><dd>{{ form.incident_oui ? incidentsSaisis.length + ' signalé(s)' : 'Aucun' }}</dd></div>
                <div><dt>Satisfaction</dt><dd>{{ form.satisfaction ? '★'.repeat(form.satisfaction) : '—' }}</dd></div>
              </dl>
              <p class="rc-avert">Le rapport ne pourra plus être modifié une fois soumis.</p>
            </div>
          </section>
        </template>
      </main>
    </div>

    <!-- ══ Barre d'envoi ═════════════════════════════════════════════ -->
    <footer v-if="!soumis && !deja" class="rc-envoi">
      <div class="rc-envoi-in">
        <div class="rc-envoi-etat">
          <strong>{{ sectionsRemplies }} / {{ SECTIONS.length }}</strong>
          <span>{{ manques.length ? manques[0] : 'Tout est prêt' }}</span>
        </div>
        <button class="rc-btn rc-btn--vert rc-btn--envoi" :disabled="envoi" @click="soumettre">
          {{ envoi ? 'Envoi…' : 'Soumettre le rapport' }}
        </button>
      </div>
    </footer>
  </div>
</template>

<script setup>
/**
 * Rapport journalier de commission — SENAFOI 2026
 *
 * Formulaire d'une seule page : les douze rubriques de la fiche officielle
 * s'enchaînent, un sommaire latéral suit la lecture et signale ce qui reste
 * à renseigner. Une seule soumission par commission et par jour ; après
 * l'envoi, seul l'export PDF reste ouvert.
 */
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { jsPDF } from 'jspdf'
import autoTable from 'jspdf-autotable'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_rapports_commissions_api.php'

const SECTIONS = [
  { cle: 'identification', titre: 'Identification' },
  { cle: 'missions', titre: 'Missions planifiées' },
  { cle: 'realisation', titre: 'Réalisation' },
  { cle: 'imprevus', titre: 'Activités imprévues' },
  { cle: 'difficultes', titre: 'Difficultés' },
  { cle: 'besoins', titre: 'Besoins' },
  { cle: 'incidents', titre: 'Incidents' },
  { cle: 'temps', titre: 'Temps consacré' },
  { cle: 'coordination', titre: 'Coordination' },
  { cle: 'satisfaction', titre: 'Satisfaction' },
  { cle: 'priorites', titre: 'Priorités demain' },
  { cle: 'commentaires', titre: 'Commentaires' },
]

const STATUTS = ['Réalisée', 'Partielle', 'Non réalisée']
const MENTIONS = ['Très difficile', 'Difficile', 'Correcte', 'Bonne', 'Excellente']

const erreur = ref('')
const deja = ref('')
const envoi = ref(false)
const impression = ref(false)
const soumis = ref(false)

const commissions = ref([])
const jours = ref([])
const commissionChoisie = ref('')

const secs = ref([])
const sectionActive = ref('identification')
let observateur = null

const form = reactive({
  date_rapport: '',
  commission: '',
  nom_prenoms: '',
  fonction: '',
  heure_debut: '',
  heure_fin: '',
  missions: [
    { tache: '', heure: '', priorite: '' },
    { tache: '', heure: '', priorite: '' },
    { tache: '', heure: '', priorite: '' },
  ],
  realisations: [],
  imprevus: '',
  difficultes_aucune: false,
  difficultes: '',
  besoins: '',
  incident_oui: false,
  incidents: [{ heure: '', description: '', action: '' }],
  heures_travail: '',
  coordination_oui: false,
  coordination_commission: '',
  coordination_objet: '',
  satisfaction: 0,
  priorites: [{ tache: '' }, { tache: '' }, { tache: '' }],
  commentaires: '',
})

const tachesSaisies = computed(() => form.missions.filter((m) => m.tache.trim()))
const incidentsSaisis = computed(() => form.incidents.filter((i) => (i.description || '').trim()))

/** Une rubrique est « renseignée » dès qu'elle porte une réponse exploitable. */
function estRemplie(cle) {
  switch (cle) {
    case 'identification':
      return Boolean(form.date_rapport && form.commission.trim() && form.nom_prenoms.trim())
    case 'missions': return tachesSaisies.value.length > 0
    case 'realisation':
      return form.realisations.length > 0 && form.realisations.every((r) => r.statut)
    case 'imprevus': return Boolean(form.imprevus.trim())
    case 'difficultes': return form.difficultes_aucune || Boolean(form.difficultes.trim())
    case 'besoins': return Boolean(form.besoins.trim())
    // Répondre « non » est une réponse : la rubrique compte comme traitée.
    case 'incidents': return !form.incident_oui || incidentsSaisis.value.length > 0
    case 'temps': return Boolean(String(form.heures_travail).trim())
    case 'coordination':
      return !form.coordination_oui || Boolean(form.coordination_commission.trim())
    case 'satisfaction': return form.satisfaction > 0
    case 'priorites': return form.priorites.some((p) => p.tache.trim())
    case 'commentaires': return Boolean(form.commentaires.trim())
    default: return false
  }
}

const sectionsRemplies = computed(() => SECTIONS.filter((s) => estRemplie(s.cle)).length)
const avancement = computed(() => Math.round((sectionsRemplies.value / SECTIONS.length) * 100))
const manques = computed(() =>
  SECTIONS.filter((s) => !estRemplie(s.cle)).map((s) => `Reste : ${s.titre.toLowerCase()}`)
)

/* ─── Chargement ──────────────────────────────────────────────────────── */

onMounted(async () => {
  try {
    const data = await (await fetch(`${API}?action=commissions&rand=${Date.now()}`)).json()
    if (data.success) {
      commissions.value = data.commissions || []
      jours.value = data.jours || []
      form.date_rapport = (data.jours || []).includes(data.aujourdhui)
        ? data.aujourdhui
        : (data.jours || [])[0] || data.aujourdhui
    }
  } catch {
    erreur.value = 'Impossible de charger la liste des commissions. Vérifiez votre connexion.'
  }

  await nextTick()
  installerObservateur()
})

onBeforeUnmount(() => observateur?.disconnect())

/** Met en évidence dans le sommaire la rubrique qu'on est en train de lire. */
function installerObservateur() {
  if (!('IntersectionObserver' in window)) return
  observateur = new IntersectionObserver(
    (entrees) => {
      const visible = entrees
        .filter((e) => e.isIntersecting)
        .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)[0]
      if (visible?.target?.dataset?.cle) sectionActive.value = visible.target.dataset.cle
    },
    { rootMargin: '-90px 0px -55% 0px', threshold: 0 }
  )
  for (const el of secs.value || []) observateur.observe(el)
}

function allerA(cle) {
  document.getElementById(`sec-${cle}`)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

/* ─── Saisie ──────────────────────────────────────────────────────────── */

function surChoixCommission() {
  form.commission = commissionChoisie.value === '__autre__' ? '' : commissionChoisie.value
  if (form.commission) verifierDepot()
}

function choisirJour(j) {
  form.date_rapport = j
  deja.value = ''
  if (form.commission) verifierDepot()
}

function jourCourt(j) {
  const d = new Date(`${j}T12:00:00`)
  return Number.isNaN(d.getTime()) ? j : String(d.getDate()).padStart(2, '0')
}

function moisCourt(j) {
  const d = new Date(`${j}T12:00:00`)
  return Number.isNaN(d.getTime()) ? '' : d.toLocaleDateString('fr-FR', { month: 'short' }).replace('.', '')
}

function jourLisible(j) {
  if (!j) return '—'
  const d = new Date(`${j}T12:00:00`)
  if (Number.isNaN(d.getTime())) return j
  return d.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' })
}

function activerIncident(valeur) {
  form.incident_oui = valeur
  if (valeur && !form.incidents.length) {
    form.incidents.push({ heure: '', description: '', action: '' })
  }
}

function statutClasse(s) {
  if (s === 'Réalisée') return 'ok'
  if (s === 'Partielle') return 'moyen'
  return 'ko'
}

function compteStatut(s) {
  return form.realisations.filter((r) => r.statut === s).length
}

// Le tableau de réalisation suit les tâches saisies, sans perdre ce qui a
// déjà été renseigné pour une tâche inchangée.
watch(() => form.missions.map((m) => m.tache).join('|'), () => {
  const anciennes = new Map(form.realisations.map((r) => [r.tache, r]))
  form.realisations = tachesSaisies.value.map((m) => anciennes.get(m.tache) || {
    tache: m.tache, statut: '', heure: '', observation: '',
  })
})

/** Contrôle précoce : inutile de faire remplir la fiche si elle est déjà déposée. */
async function verifierDepot() {
  if (!form.commission.trim() || !form.date_rapport) return
  try {
    const url = `${API}?action=etat&commission=${encodeURIComponent(form.commission)}`
      + `&date=${encodeURIComponent(form.date_rapport)}&rand=${Date.now()}`
    const data = await (await fetch(url)).json()
    if (data.success && data.depose) {
      deja.value = `Le rapport de la commission ${form.commission} pour le `
        + `${jourLisible(form.date_rapport)} a déjà été déposé`
        + (data.par ? ` par ${data.par}` : '') + '.'
    } else {
      deja.value = ''
    }
  } catch { /* le contrôle final côté serveur reste souverain */ }
}

/* ─── Envoi ───────────────────────────────────────────────────────────── */

async function soumettre() {
  erreur.value = ''

  if (!form.date_rapport) { manquant('identification', 'Choisissez la journée concernée.'); return }
  if (!form.commission.trim()) { manquant('identification', 'Indiquez votre commission.'); return }
  if (!form.nom_prenoms.trim()) { manquant('identification', 'Indiquez vos nom et prénoms.'); return }
  if (!tachesSaisies.value.length) { manquant('missions', 'Saisissez au moins une tâche prévue.'); return }

  envoi.value = true
  try {
    const res = await fetch(`${API}?action=soumettre&rand=${Date.now()}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        ...form,
        missions: tachesSaisies.value,
        incidents: form.incident_oui ? incidentsSaisis.value : [],
        priorites: form.priorites.filter((p) => p.tache.trim()),
      }),
    })
    const data = await res.json().catch(() => ({}))
    if (!data.success) {
      if (data.deja) { deja.value = data.message; return }
      throw new Error(data.message || `HTTP ${res.status}`)
    }
    soumis.value = true
    window.scrollTo({ top: 0, behavior: 'smooth' })
  } catch (e) {
    erreur.value = e.message
  } finally {
    envoi.value = false
  }
}

function manquant(cle, message) {
  erreur.value = message
  allerA(cle)
}

function recommencer() {
  window.location.reload()
}

/* ─── Export PDF ──────────────────────────────────────────────────────── */

function exporterPdf() {
  impression.value = true
  try {
    const doc = new jsPDF('p', 'mm', 'a4')
    const L = 210

    doc.setFillColor(15, 81, 50)
    doc.rect(0, 0, L, 30, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(14)
    doc.text('FICHE DE SUIVI DES TÂCHES ET RAPPORT JOURNALIER', L / 2, 13, { align: 'center' })
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    doc.text('SENAFOI 2026 — Korhogo', L / 2, 21, { align: 'center' })

    doc.setTextColor(17, 24, 39)
    autoTable(doc, {
      startY: 38,
      head: [['Identification', '']],
      body: [
        ['Date', jourLisible(form.date_rapport)],
        ['Commission', form.commission],
        ['Nom et prénoms', form.nom_prenoms],
        ['Fonction', form.fonction || '—'],
        ['Heure de début', form.heure_debut || '—'],
        ['Heure de fin', form.heure_fin || '—'],
      ],
      theme: 'grid',
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [15, 81, 50], textColor: 255 },
      columnStyles: { 0: { cellWidth: 55, fontStyle: 'bold' } },
    })

    autoTable(doc, {
      startY: doc.lastAutoTable.finalY + 6,
      head: [['N°', 'Tâche prévue', 'Heure', 'Priorité']],
      body: tachesSaisies.value.map((m, i) => [i + 1, m.tache, m.heure || '—', m.priorite || '—']),
      theme: 'grid',
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [15, 81, 50], textColor: 255 },
      columnStyles: { 0: { cellWidth: 10 }, 2: { cellWidth: 22 }, 3: { cellWidth: 26 } },
    })

    autoTable(doc, {
      startY: doc.lastAutoTable.finalY + 6,
      head: [['Tâche', 'Statut', 'Heure', 'Observation']],
      body: form.realisations.map((r) => [r.tache, r.statut || '—', r.heure || '—', r.observation || '—']),
      theme: 'grid',
      styles: { fontSize: 9, cellPadding: 2 },
      headStyles: { fillColor: [15, 81, 50], textColor: 255 },
      columnStyles: { 1: { cellWidth: 26 }, 2: { cellWidth: 20 } },
    })

    const lignes = [
      ['Activités imprévues', form.imprevus || '—'],
      ['Difficultés', form.difficultes_aucune ? 'Aucune' : (form.difficultes || '—')],
      ['Besoins', form.besoins || '—'],
      ['Incidents', form.incident_oui && incidentsSaisis.value.length
        ? incidentsSaisis.value.map((i) => `${i.heure || '—'} · ${i.description} → ${i.action || '—'}`).join('\n')
        : 'Aucun'],
      ['Heures de travail', form.heures_travail || '—'],
      ['Coordination', form.coordination_oui
        ? `${form.coordination_commission || '—'} — ${form.coordination_objet || '—'}`
        : 'Aucune'],
      ['Satisfaction', form.satisfaction ? `${'★'.repeat(form.satisfaction)} (${form.satisfaction}/5)` : '—'],
      ['Priorités pour demain', form.priorites.filter((p) => p.tache.trim())
        .map((p, i) => `${i + 1}. ${p.tache}`).join('\n') || '—'],
      ['Commentaires', form.commentaires || '—'],
    ]

    autoTable(doc, {
      startY: doc.lastAutoTable.finalY + 6,
      body: lignes,
      theme: 'grid',
      styles: { fontSize: 9, cellPadding: 2, valign: 'top' },
      columnStyles: { 0: { cellWidth: 45, fontStyle: 'bold', fillColor: [248, 250, 252] } },
    })

    doc.setFontSize(8)
    doc.setTextColor(120, 130, 140)
    doc.text(`Édité le ${new Date().toLocaleString('fr-FR')} — AEEMCI, pour une identité islamique !`,
      L / 2, 288, { align: 'center' })

    const nom = form.commission.toLowerCase().replace(/[^a-z0-9]+/g, '_')
    doc.save(`rapport_${nom}_${form.date_rapport}.pdf`)
  } finally {
    impression.value = false
  }
}
</script>

<style scoped>
/* Mobile d'abord ; le sommaire latéral n'apparaît qu'à partir de 1000 px. */

.rc {
  --vert: var(--green); --vert-f: #0f5132; --ligne: var(--line); --gris: var(--muted);
  min-height: 100vh; background: #f5f7fb; color: var(--ink);
  padding-bottom: calc(96px + env(safe-area-inset-bottom, 0px));
  -webkit-text-size-adjust: 100%;
}

/* ── Barre supérieure ─────────────────────────────────────────────── */
.rc-bar { position: sticky; top: 0; z-index: 30;
          background: linear-gradient(135deg, #0f5132, #198754); color: #fff;
          padding-top: env(safe-area-inset-top, 0px);
          box-shadow: var(--shadow); }
.rc-bar-in { max-width: 1180px; margin: 0 auto; padding: 13px 16px;
             display: flex; align-items: center; justify-content: space-between; gap: 14px; }
.rc-bar-titre span { display: block; font-size: 10.5px; font-weight: 800; letter-spacing: .07em;
                     text-transform: uppercase; color: var(--green-mist); }
.rc-bar-titre strong { display: block; font-size: 18px; line-height: 1.2; margin-top: 3px; }
.rc-jauge { position: relative; width: 44px; height: 44px; flex-shrink: 0; }
.rc-jauge svg { width: 100%; height: 100%; }
.rc-jauge em { position: absolute; inset: 0; display: grid; place-items: center;
               font-style: normal; font-size: 11px; font-weight: 800; }

/* ── Mise en page ─────────────────────────────────────────────────── */
.rc-page { max-width: 1180px; margin: 0 auto; padding: 16px;
           display: grid; gap: 18px; align-items: start; }
.rc-corps { display: grid; gap: 14px; min-width: 0; }
.rc-fin { max-width: 700px; margin: 0 auto; padding: 24px 16px; }

/* ── Sommaire ─────────────────────────────────────────────────────── */
.rc-sommaire { display: none; }

/* ── Rubriques ────────────────────────────────────────────────────── */
.rc-carte { background: #fff; border: 1px solid var(--ligne); border-radius: 0;
            overflow: hidden; scroll-margin-top: 88px; }
.rc-carte > header { display: flex; align-items: center; gap: 11px; padding: 15px 16px;
                     border-bottom: 1px solid var(--surface); background: #fbfdfc; flex-wrap: wrap; }
.rc-carte h2 { margin: 0; font-size: 16px; font-weight: 800; }
.rc-carte > header small { color: var(--gris); font-size: 11.5px; font-style: italic; }
.rc-num { width: 28px; height: 28px; border-radius: 0; background: var(--vert); color: #fff;
          display: grid; place-items: center; font-size: 13px; font-weight: 800; flex-shrink: 0; }
.rc-carte--recap .rc-num { background: var(--ink); }
.rc-contenu { display: grid; gap: 14px; padding: 16px; }

.rc-question { margin: 0; font-size: 14px; }

/* ── Champs ───────────────────────────────────────────────────────── */
.rc-champ { display: grid; gap: 7px; }
.rc-champ > span { font-size: 11px; font-weight: 800; color: var(--gris);
                   text-transform: uppercase; letter-spacing: .03em; }
.rc-champ input, .rc-champ select, .rc-champ textarea,
.rc-contenu > textarea, .rc-item input, .rc-item select, .rc-priorite input {
  width: 100%; border: 1px solid var(--ligne); border-radius: 0;
  padding: 12px 13px; font-size: 16px; font-family: inherit;
  background: #fff; color: var(--ink); -webkit-appearance: none; appearance: none;
}
.rc-champ select, .rc-item select {
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' fill='none' stroke='%2364748b' stroke-width='2'/></svg>");
  background-repeat: no-repeat; background-position: right 13px center; padding-right: 36px;
}
textarea { resize: vertical; min-height: 88px; line-height: 1.5; }
.rc-champ input:focus, .rc-champ select:focus, .rc-champ textarea:focus,
.rc-contenu > textarea:focus, .rc-item input:focus, .rc-item select:focus, .rc-priorite input:focus {
  outline: none; border-color: var(--vert); box-shadow: 0 0 0 3px rgba(21, 128, 61, .12);
}
.rc-duo { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.rc-court { max-width: 100%; }

/* ── Journée ──────────────────────────────────────────────────────── */
.rc-jours { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
.rc-jours button { display: grid; gap: 1px; padding: 9px 0; border-radius: 0;
                   border: 1px solid var(--ligne); background: #fff; cursor: pointer;
                   font-family: inherit; color: var(--ink-soft); }
.rc-jours b { font-size: 17px; font-weight: 800; }
.rc-jours i { font-style: normal; font-size: 10px; font-weight: 700; text-transform: uppercase;
              color: var(--gris); }
.rc-jours button.on { background: var(--vert); border-color: var(--vert); color: #fff; }
.rc-jours button.on i { color: var(--green-mist); }

/* ── Éléments répétables ──────────────────────────────────────────── */
.rc-item { display: grid; gap: 10px; padding: 14px; border: 1px solid var(--ligne);
           border-radius: 0; background: var(--surface); }
.rc-item-tete { display: flex; align-items: center; justify-content: space-between; }
.rc-tache-nom { font-size: 14.5px; line-height: 1.35; }
.rc-puce { width: 27px; height: 27px; border-radius: 0; background: var(--green-mist); color: var(--vert-f);
           display: grid; place-items: center; font-size: 12.5px; font-weight: 800; flex-shrink: 0; }
.rc-x { width: 34px; height: 34px; border-radius: 0; border: 1px solid #fecaca;
        background: #fff; color: #dc2626; font-size: 20px; line-height: 1; cursor: pointer; }
.rc-priorite { display: flex; align-items: center; gap: 10px; }
.rc-ajout { border: 1px dashed var(--line); background: var(--surface); border-radius: 0;
            padding: 12px 16px; font-size: 14px; font-weight: 700; color: var(--vert-f);
            cursor: pointer; font-family: inherit; }
.rc-lien-x { justify-self: start; border: 0; background: none; color: #dc2626; font-size: 13px;
             font-weight: 700; cursor: pointer; font-family: inherit; padding: 4px 0; }

.rc-statuts { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 7px; }
.rc-statut { border: 1px solid var(--ligne); background: #fff; border-radius: 0;
             padding: 11px 4px; font-size: 12.5px; font-weight: 700; cursor: pointer;
             font-family: inherit; color: var(--ink-soft); line-height: 1.2; }
.rc-statut.on.ok { background: var(--vert); border-color: var(--vert); color: #fff; }
.rc-statut.on.moyen { background: var(--gold); border-color: var(--gold); color: #fff; }
.rc-statut.on.ko { background: #dc2626; border-color: #dc2626; color: #fff; }

.rc-bascule { display: flex; align-items: center; gap: 11px; font-size: 15px; font-weight: 600;
              padding: 12px 14px; border: 1px solid var(--ligne); border-radius: 0; background: var(--surface); }
.rc-bascule input { width: 22px; height: 22px; accent-color: var(--vert); flex-shrink: 0; }
.rc-oui-non { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; max-width: 320px; }
.rc-oui-non button { height: 48px; border-radius: 0; border: 1px solid var(--ligne);
                     background: #fff; font-size: 15px; font-weight: 700; cursor: pointer;
                     font-family: inherit; color: var(--ink-soft); }
.rc-oui-non button.on { background: var(--vert); border-color: var(--vert); color: #fff; }

.rc-etoiles { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
.rc-etoiles button { border: 0; background: none; font-size: 38px; line-height: 1; cursor: pointer;
                     color: var(--line); padding: 0 2px; transition: transform .12s; }
.rc-etoiles button:hover { transform: scale(1.12); }
.rc-etoiles button.on { color: var(--gold); }
.rc-etoiles em { width: 100%; margin-top: 2px; font-style: normal; font-size: 13.5px;
                 font-weight: 700; color: var(--gris); }

.rc-vide { padding: 20px 14px; text-align: center; color: var(--muted); background: var(--surface);
           border: 1px dashed var(--ligne); border-radius: 0; font-size: 13.5px; }

/* ── Récapitulatif ────────────────────────────────────────────────── */
.rc-recap { display: grid; gap: 0; margin: 0; border: 1px solid var(--ligne);
            border-radius: 0; overflow: hidden; }
.rc-recap > div { display: grid; gap: 3px; padding: 11px 14px; border-bottom: 1px solid var(--surface); }
.rc-recap > div:last-child { border-bottom: 0; }
.rc-recap dt { font-size: 11px; font-weight: 800; color: var(--gris);
               text-transform: uppercase; letter-spacing: .03em; }
.rc-recap dd { margin: 0; font-size: 14.5px; line-height: 1.45; }
.rc-avert { margin: 0; font-size: 12.5px; color: #92400e; background: var(--gold-mist);
            border: 1px solid #fde68a; border-radius: 0; padding: 10px 13px; }

/* ── Messages ─────────────────────────────────────────────────────── */
.rc-alerte { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;
             border-radius: 0; padding: 12px 14px; font-weight: 600; font-size: 13.5px; }
.rc-bloque { background: var(--gold-mist); border: 1px solid #fde68a; border-radius: 0;
             padding: 24px 18px; text-align: center; display: grid; gap: 12px; justify-items: center; }
.rc-bloque strong { color: #92400e; font-size: 16px; }
.rc-bloque p { margin: 0; color: #78350f; font-size: 14px; line-height: 1.55; }

/* ── Barre d'envoi ────────────────────────────────────────────────── */
.rc-envoi { position: fixed; left: 0; right: 0; bottom: 0; z-index: 30;
            background: rgba(255, 255, 255, .96); backdrop-filter: blur(8px);
            border-top: 1px solid var(--ligne);
            padding-bottom: env(safe-area-inset-bottom, 0px); }
.rc-envoi-in { max-width: 1180px; margin: 0 auto; padding: 12px 16px;
               display: flex; align-items: center; gap: 12px; }
.rc-envoi-etat { flex: 1; min-width: 0; }
.rc-envoi-etat strong { display: block; font-size: 15px; font-weight: 800; }
.rc-envoi-etat span { display: block; font-size: 11.5px; color: var(--gris);
                      overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.rc-btn { height: 50px; padding: 0 22px; border: 1px solid var(--ligne); background: #fff;
          border-radius: 0; font-size: 15px; font-weight: 700; cursor: pointer;
          font-family: inherit; color: var(--ink-soft); }
.rc-btn--vert { background: var(--vert); border-color: var(--vert); color: #fff; }
.rc-btn--envoi { flex-shrink: 0; }
.rc-btn:disabled { opacity: .5; cursor: not-allowed; }

/* ── Confirmation ─────────────────────────────────────────────────── */
.rc-succes { background: #fff; border: 1px solid var(--green-mist); border-radius: 0; padding: 36px 22px;
             text-align: center; display: grid; gap: 12px; justify-items: center; }
.rc-coche { width: 68px; height: 68px; border-radius: 50%; background: var(--green-mist); color: var(--vert);
            display: grid; place-items: center; }
.rc-succes h2 { margin: 0; font-size: 24px; font-weight: 800; }
.rc-succes p { margin: 0; font-size: 15px; color: var(--ink-soft); line-height: 1.5; }
.rc-discret { color: var(--gris) !important; font-size: 13.5px !important; }
.rc-succes-actions { display: grid; gap: 10px; width: 100%; margin-top: 8px; }
.rc-succes-actions .rc-btn { width: 100%; }

/* ══ Tablette ════════════════════════════════════════════════════ */
@media (min-width: 640px) {
  .rc-page { padding: 22px 20px; }
  .rc-bar-titre strong { font-size: 21px; }
  .rc-jours { grid-template-columns: repeat(8, 1fr); }
  .rc-duo { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
  .rc-court { max-width: 320px; }
  .rc-recap > div { grid-template-columns: 180px 1fr; gap: 14px; align-items: baseline; }
  .rc-succes-actions { display: flex; justify-content: center; }
  .rc-succes-actions .rc-btn { width: auto; }
  .rc-contenu { padding: 20px; }
}

/* ══ Ordinateur : sommaire latéral fixe ══════════════════════════ */
@media (min-width: 1000px) {
  .rc { padding-bottom: 0; }
  .rc-page { grid-template-columns: 250px minmax(0, 1fr); gap: 26px; padding: 26px 24px 60px; }

  .rc-sommaire { display: block; position: sticky; top: 92px; align-self: start;
                 background: #fff; border: 1px solid var(--ligne); border-radius: 0;
                 padding: 16px 14px; }
  .rc-sommaire-titre { margin: 0 0 10px; font-size: 10.5px; font-weight: 800; color: var(--gris);
                       text-transform: uppercase; letter-spacing: .07em; }
  .rc-sommaire nav { display: grid; gap: 2px; }
  .rc-sommaire button { display: flex; align-items: center; gap: 9px; width: 100%;
                        padding: 7px 9px; border: 0; border-radius: 0; background: none;
                        cursor: pointer; font-family: inherit; text-align: left; color: var(--muted); }
  .rc-sommaire button:hover { background: var(--surface); }
  .rc-sommaire button.on { background: var(--green-mist); color: var(--vert-f); }
  .rc-sommaire button em { font-style: normal; font-size: 12.5px; font-weight: 700; }
  .rc-pastille { width: 21px; height: 21px; border-radius: 0; background: var(--surface);
                 display: grid; place-items: center; font-size: 10.5px; font-weight: 800;
                 flex-shrink: 0; color: var(--muted); }
  .rc-sommaire button.faite .rc-pastille { background: var(--vert); color: #fff; }
  .rc-sommaire button.on .rc-pastille { background: var(--vert-f); color: #fff; }
  .rc-sommaire-pied { margin: 12px 0 0; padding-top: 11px; border-top: 1px solid var(--surface);
                      font-size: 11.5px; font-weight: 700; color: var(--gris); }

  .rc-envoi { position: sticky; }
  .rc-btn--envoi { min-width: 240px; }
}
</style>
