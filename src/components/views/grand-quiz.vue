<template>
  <div class="gqp-root">
    <div class="gqp-halo"></div>

    <header class="gqp-tete">
      <span class="gqp-trophee">🏆</span>
      <div>
        <strong>Grand Quiz AEEMCI 2026</strong>
        <small>Podium des 4 meilleurs</small>
      </div>
      <span class="gqp-live" :class="{ on: !chargement }">
        <i></i>{{ chargement ? 'Actualisation…' : 'En direct' }}
      </span>
    </header>

    <main class="gqp-corps">
      <div v-if="chargement && !classement.length" class="gqp-etat">Chargement du classement…</div>
      <div v-else-if="erreur" class="gqp-etat gqp-etat--err">{{ erreur }}</div>
      <div v-else-if="!classement.length" class="gqp-etat">Aucun participant classé pour le moment.</div>

      <ol v-else class="gqp-liste">
        <li
          v-for="r in classement"
          :key="r.matricule"
          class="gqp-carte"
          :class="'gqp-carte--' + r.rang"
        >
          <span class="gqp-rang">
            <span class="gqp-rang__chiffre">{{ r.rang }}</span>
            <span v-if="r.rang <= 3" class="gqp-rang__medaille">{{ ['🥇','🥈','🥉'][r.rang - 1] }}</span>
          </span>

          <span class="gqp-photo">
            <img v-if="r.photo" :src="r.photo" alt="" />
            <span v-else class="gqp-init">{{ initiales(r) }}</span>
          </span>

          <span class="gqp-identite">
            <strong>{{ r.prenom }} {{ r.nom }}</strong>
            <small>{{ r.matricule }}</small>
          </span>

          <span class="gqp-points">
            <span class="gqp-points__val">{{ r.total_points }}</span>
            <small>points</small>
          </span>
        </li>
      </ol>
    </main>
  </div>
</template>

<script>
const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_quiz_api.php'
const LIMITE = 4
const INTERVALLE = 15000

export default {
  name: 'GrandQuiz',
  data() {
    return {
      classement: [],
      chargement: true,
      erreur: '',
      minuteur: null,
    }
  },
  mounted() {
    this.charger()
    this.minuteur = setInterval(this.charger, INTERVALLE)
  },
  beforeUnmount() {
    if (this.minuteur) clearInterval(this.minuteur)
  },
  methods: {
    initiales(r) {
      return ((r.prenom?.[0] || '') + (r.nom?.[0] || '')).toUpperCase() || '?'
    },
    async charger() {
      this.chargement = true
      try {
        const res = await fetch(`${API}?action=classement&limit=${LIMITE}&rand=${Math.random()}`)
        const data = await res.json()
        if (!data.success) throw new Error(data.message || 'Classement indisponible.')
        this.classement = (data.classement || []).slice(0, LIMITE)
        this.erreur = ''
      } catch (e) {
        this.erreur = 'Connexion impossible : ' + e.message
      } finally {
        this.chargement = false
      }
    },
  },
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.gqp-root {
  min-height: 100vh; min-height: 100dvh; position: relative; overflow: hidden;
  background:
    radial-gradient(120% 80% at 12% 0%, #4c1d95 0%, transparent 60%),
    radial-gradient(120% 80% at 88% 100%, #0e7490 0%, transparent 58%),
    linear-gradient(165deg, #170c3d 0%, #241154 48%, #0f0a2e 100%);
  color: #fff; font-family: var(--display);
  display: flex; flex-direction: column;
  padding: max(18px, env(safe-area-inset-top)) 18px max(18px, env(safe-area-inset-bottom));
}

.gqp-halo { position: absolute; left: 50%; top: -28vh; width: 130vw; height: 70vh;
            transform: translateX(-50%); pointer-events: none;
            background: radial-gradient(closest-side, rgba(250,204,21,.20), transparent 72%);
            animation: gqpHalo 5s ease-in-out infinite; }
@keyframes gqpHalo { 50% { opacity: .45; transform: translateX(-50%) scale(1.12); } }

.gqp-tete { position: relative; display: flex; align-items: center; gap: 14px;
            max-width: 880px; margin: 0 auto 26px; width: 100%; padding-top: 6px; }
.gqp-trophee { font-size: 34px; filter: drop-shadow(0 4px 14px rgba(250,204,21,.4)); }
.gqp-tete strong { display: block; font-size: clamp(18px, 3vw, 24px); letter-spacing: -.01em; }
.gqp-tete small { color: #c4b5fd; font-size: 12.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
.gqp-live { margin-left: auto; display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16);
            border-radius: 999px; padding: 7px 14px; font-size: 11.5px; font-weight: 800;
            color: rgba(255,255,255,.7); flex-shrink: 0; }
.gqp-live i { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,.35); }
.gqp-live.on i { background: var(--green-2); box-shadow: var(--shadow); animation: gqpPulse 1.8s infinite; }
@keyframes gqpPulse { 50% { opacity: .4; } }

.gqp-corps { position: relative; flex: 1; display: flex; align-items: center; justify-content: center; }
.gqp-etat { color: rgba(255,255,255,.6); font-size: 15px; font-weight: 700; text-align: center; padding: 40px; }
.gqp-etat--err { color: #fca5a5; }

.gqp-liste { list-style: none; display: flex; flex-direction: column; gap: 14px;
             width: 100%; max-width: 880px; margin: 0 auto; }

.gqp-carte { display: flex; align-items: center; gap: 18px; padding: 16px 22px;
             border-radius: 0; border: 1.5px solid rgba(255,255,255,.14);
             background: linear-gradient(135deg, rgba(255,255,255,.1), rgba(255,255,255,.03));
             box-shadow: var(--shadow);
             animation: gqpEntree .5s both; }
.gqp-carte:nth-child(1) { animation-delay: .05s; }
.gqp-carte:nth-child(2) { animation-delay: .15s; }
.gqp-carte:nth-child(3) { animation-delay: .25s; }
.gqp-carte:nth-child(4) { animation-delay: .35s; }
@keyframes gqpEntree { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

.gqp-carte--1 { border-color: rgba(250,204,21,.55); background: linear-gradient(135deg, rgba(250,204,21,.16), rgba(255,255,255,.04));
                box-shadow: var(--shadow); transform: scale(1.03); }
.gqp-carte--2 { border-color: rgba(203,213,225,.5); }
.gqp-carte--3 { border-color: rgba(217,119,6,.5); }

.gqp-rang { position: relative; flex-shrink: 0; width: 50px; height: 50px; border-radius: 0;
            display: grid; place-items: center; font-weight: 900; font-size: 20px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); }
.gqp-carte--1 .gqp-rang { background: linear-gradient(140deg, #fde047, var(--gold)); color: #3b1d00; }
.gqp-carte--2 .gqp-rang { background: linear-gradient(140deg, var(--line), var(--muted)); color: var(--ink); }
.gqp-carte--3 .gqp-rang { background: linear-gradient(140deg, #fbbf78, var(--gold)); color: #3b1d00; }
.gqp-rang__medaille { position: absolute; top: -10px; right: -8px; font-size: 20px;
                      filter: drop-shadow(0 2px 5px rgba(0,0,0,.4)); }

.gqp-photo { flex-shrink: 0; width: 58px; height: 58px; border-radius: 50%; overflow: hidden;
             border: 2px solid rgba(255,255,255,.25); background: rgba(255,255,255,.08); }
.gqp-photo img { width: 100%; height: 100%; object-fit: cover; }
.gqp-init { width: 100%; height: 100%; display: grid; place-items: center;
            font-weight: 900; font-size: 20px; color: #e9d5ff; }

.gqp-identite { flex: 1; min-width: 0; }
.gqp-identite strong { display: block; font-size: clamp(15px, 2.4vw, 19px); font-weight: 800;
                        overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.gqp-identite small { color: rgba(255,255,255,.55); font-size: 12.5px; font-weight: 700;
                       letter-spacing: .03em; }

.gqp-points { flex-shrink: 0; text-align: right; }
.gqp-points__val { display: block; font-size: clamp(24px, 4vw, 34px); font-weight: 900;
                    color: #fde047; text-shadow: 0 0 22px rgba(250,204,21,.35); line-height: 1; }
.gqp-points small { color: rgba(255,255,255,.5); font-size: 10.5px; font-weight: 800;
                     text-transform: uppercase; letter-spacing: .08em; }

@media (max-width: 620px) {
  .gqp-carte { padding: 13px 15px; gap: 12px; }
  .gqp-photo { width: 46px; height: 46px; }
  .gqp-rang { width: 42px; height: 42px; font-size: 17px; }
}
</style>
