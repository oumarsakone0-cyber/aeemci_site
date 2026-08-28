<template>
  <div class="rapport-app">
    <!-- Header -->
    <header class="app-header">
      <div class="header-wrapper">
        <div class="logo-section">
          <div class="logo-badge">🕌</div>
          <div class="logo-text">
            <h1>AEEMCI</h1>
            <p>Rapport Final de Séminaire</p>
          </div>
        </div>
        <div class="header-right">
          <span class="header-time">{{ currentTime }}</span>
          <button v-if="isAuthenticated" @click="logout" class="btn-logout">🔒 Déconnexion</button>
        </div>
      </div>
    </header>

    <!-- Tabs -->
    <div class="tabs-bar">
      <button v-for="tab in tabs" :key="tab.id" class="tab-btn" :class="{ active: currentTab === tab.id }" @click="switchTab(tab.id)">
        {{ tab.icon }} {{ tab.label }}
      </button>
    </div>

    <!-- ===================== PAGE: SAISIE ===================== -->
    <main v-show="currentTab === 'saisie'" class="page-content">
      <div class="form-container">
        <form @submit.prevent="submitForm" novalidate>

          <!-- ===== SECTION: INFORMATIONS ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">01</div>
              <div>
                <h2 class="section-title">Informations générales</h2>
                <p class="section-desc">Identification du missionné et du cadre du séminaire</p>
              </div>
            </div>
            <div class="form-grid">
              <div class="form-field fullwidth-2">
                <label>Nom et prénom du missionné <span class="req">*</span></label>
                <input v-model="form.missionne" type="text" placeholder="Prénom et nom complet" required>
              </div>
              <div class="form-field">
                <label>Secrétariat régional <span class="req">*</span></label>
                <select v-model="form.region" required>
                  <option value="">-- Sélectionner la région --</option>
                  <option v-for="r in regions" :key="r">{{ r }}</option>
                </select>
              </div>
              <div class="form-field">
                <label>Ville / Localité <span class="req">*</span></label>
                <input v-model="form.ville" type="text" placeholder="Ex: Bouaké, Yamoussoukro...">
              </div>
              <div class="form-field">
                <label>Date de début du séminaire <span class="req">*</span></label>
                <input v-model="form.date_debut" type="date" required>
              </div>
              <div class="form-field">
                <label>Date de fin du séminaire <span class="req">*</span></label>
                <input v-model="form.date_fin" type="date" required>
              </div>
              <div class="form-field">
                <label>Date d'arrivée du missionné au camp <span class="req">*</span></label>
                <input v-model="form.date_arrivee" type="date" required>
              </div>
              <div class="form-field">
                <label>Date de départ du missionné du camp <span class="req">*</span></label>
                <input v-model="form.date_depart" type="date" required>
              </div>
            </div>
          </div>

          <!-- ===== SECTION: PRÉSENCES ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">02</div>
              <div>
                <h2 class="section-title">Présences</h2>
                <p class="section-desc">Effectifs enregistrés durant le séminaire</p>
              </div>
            </div>
            <div class="presence-grid">
              <div class="presence-card">
                <div class="presence-icon">👨‍🎓</div>
                <label>Séminaristes <span class="req">*</span></label>
                <input v-model.number="form.nb_seminaristes" type="number" min="0" placeholder="0" @input="calcParticipants">
              </div>
              <div class="presence-card">
                <div class="presence-icon">👨‍🏫</div>
                <label>Formateurs <span class="req">*</span></label>
                <input v-model.number="form.nb_formateurs" type="number" min="0" placeholder="0" @input="calcParticipants">
              </div>
              <div class="presence-card">
                <div class="presence-icon">👥</div>
                <label>Visiteurs</label>
                <input v-model.number="form.nb_visiteurs" type="number" min="0" placeholder="0" @input="calcParticipants">
              </div>
              <div class="presence-card">
                <div class="presence-icon">🤝</div>
                <label>CO (Chargés d'Organisation)</label>
                <input v-model.number="form.nb_co" type="number" min="0" placeholder="0" @input="calcParticipants">
              </div>
              <div class="presence-card abandon-card">
                <div class="presence-icon">🚪</div>
                <label>Abandons</label>
                <input v-model.number="form.nb_abandons" type="number" min="0" placeholder="0" @input="calcParticipants">
              </div>
              <div class="presence-card total-card">
                <div class="presence-icon">📊</div>
                <label>Total participants <small>(auto)</small></label>
                <div class="total-display">{{ totalParticipants }}</div>
                <div class="total-hint">= Séminar. + Format. + Visit. + CO − Abandons</div>
              </div>
            </div>
          </div>

          <!-- ===== SECTION: FORMATION ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">03</div>
              <div>
                <h2 class="section-title">Formation</h2>
                <p class="section-desc">Données pédagogiques du séminaire</p>
              </div>
            </div>
            <div class="form-grid">
              <div class="form-field">
                <label>Nombre de niveaux de formation <span class="req">*</span></label>
                <input v-model.number="form.nb_niveaux" type="number" min="0" placeholder="0">
              </div>
              <div class="form-field">
                <label>Nombre total de cours dispensés <span class="req">*</span></label>
                <input v-model.number="form.nb_cours" type="number" min="0" placeholder="0">
              </div>
            </div>
          </div>

          <!-- ===== SECTION: OBJECTIFS ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">04</div>
              <div>
                <h2 class="section-title">Objectifs de formation</h2>
                <p class="section-desc">Tenue des activités prévues au programme</p>
              </div>
            </div>
            <div class="objectifs-list">

              <div v-for="obj in objectifs" :key="obj.key" class="objectif-item">
                <div class="objectif-header">
                  <span class="objectif-label">{{ obj.label }}</span>
                  <div class="objectif-toggle">
                    <label class="toggle-opt" :class="{ on: form.objectifs[obj.key].tenu === 'Oui' }">
                      <input type="radio" :name="'obj_' + obj.key" value="Oui" v-model="form.objectifs[obj.key].tenu"> ✅ Oui
                    </label>
                    <label class="toggle-opt" :class="{ off: form.objectifs[obj.key].tenu === 'Non' }">
                      <input type="radio" :name="'obj_' + obj.key" value="Non" v-model="form.objectifs[obj.key].tenu"> ❌ Non
                    </label>
                  </div>
                </div>
                <!-- Motif si Non (ou précision si activité environnementale Oui) -->
                <div v-if="form.objectifs[obj.key].tenu === 'Non'" class="objectif-motif">
                  <input v-model="form.objectifs[obj.key].motif" type="text" :placeholder="'Motif : pourquoi ' + obj.label.toLowerCase() + ' n\'a pas eu lieu ?'">
                </div>
                <div v-if="obj.key === 'activite_env' && form.objectifs[obj.key].tenu === 'Oui'" class="objectif-motif">
                  <input v-model="form.objectifs[obj.key].precision" type="text" placeholder="Préciser l'activité environnementale réalisée">
                </div>
              </div>

            </div>
          </div>

          <!-- ===== SECTION: OBSERVATIONS ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">05</div>
              <div>
                <h2 class="section-title">Observations</h2>
                <p class="section-desc">Évaluation qualitative du déroulement du séminaire</p>
              </div>
            </div>
            <div class="obs-grid">

              <div class="obs-item" v-for="obs in observations" :key="obs.key">
                <label class="obs-label">{{ obs.label }} <span class="req">*</span></label>
                <div class="radio-row">
                  <label v-for="opt in obs.options" :key="opt.val"
                    class="radio-opt"
                    :class="{ selected: form.observations[obs.key].valeur === opt.val, 'opt-good': opt.cls === 'good', 'opt-mid': opt.cls === 'mid', 'opt-bad': opt.cls === 'bad' }">
                    <input type="radio" :name="'obs_' + obs.key" :value="opt.val" v-model="form.observations[obs.key].valeur">
                    {{ opt.label }}
                  </label>
                </div>
                <input
                  v-model="form.observations[obs.key].commentaire"
                  type="text"
                  placeholder="Commentaire (optionnel)..."
                  class="obs-comment"
                >
              </div>

            </div>
          </div>

          <!-- ===== SECTION: REMONTÉE CRITIQUE ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">06</div>
              <div>
                <h2 class="section-title">Remontée critique</h2>
                <p class="section-desc">Incidents, difficultés et suggestions identifiés</p>
              </div>
            </div>
            <div class="form-grid">
              <div class="form-field fullwidth">
                <label>Incidents signalés</label>
                <textarea v-model="form.incidents" rows="3" placeholder="Décrire tout incident notable survenu durant le séminaire..."></textarea>
              </div>
              <div class="form-field fullwidth">
                <label>Difficultés majeures de la région</label>
                <textarea v-model="form.difficultes" rows="3" placeholder="Difficultés structurelles, logistiques, humaines rencontrées..."></textarea>
              </div>
              <div class="form-field fullwidth">
                <label>Suggestions pour la région</label>
                <textarea v-model="form.suggestions" rows="3" placeholder="Recommandations, améliorations proposées pour les prochains séminaires..."></textarea>
              </div>
            </div>
          </div>

          <!-- ===== SECTION: RAPPORT REMONTÉ ===== -->
          <div class="form-section">
            <div class="section-header-block">
              <div class="section-num">07</div>
              <div>
                <h2 class="section-title">Rapport remonté</h2>
                <p class="section-desc">Données de recensement collectées</p>
              </div>
            </div>
            <div class="form-grid">
              <div class="form-field">
                <label>Recensement base de la région <small>(SENAMO)</small></label>
                <div class="rapport-toggle">
                  <label class="toggle-opt" :class="{ on: form.senamo_remonte === 'Oui' }">
                    <input type="radio" name="senamo" value="Oui" v-model="form.senamo_remonte"> ✅ Remis
                  </label>
                  <label class="toggle-opt" :class="{ off: form.senamo_remonte === 'Non' }">
                    <input type="radio" name="senamo" value="Non" v-model="form.senamo_remonte"> ❌ Non remis
                  </label>
                </div>
                <input v-if="form.senamo_remonte === 'Non'" v-model="form.senamo_motif" type="text" placeholder="Motif..." style="margin-top:.5rem">
              </div>
              <div class="form-field">
                <label>Recensement SERACEF & Vice-présidentes <small>(SENACEF)</small></label>
                <div class="rapport-toggle">
                  <label class="toggle-opt" :class="{ on: form.senacef_remonte === 'Oui' }">
                    <input type="radio" name="senacef" value="Oui" v-model="form.senacef_remonte"> ✅ Remis
                  </label>
                  <label class="toggle-opt" :class="{ off: form.senacef_remonte === 'Non' }">
                    <input type="radio" name="senacef" value="Non" v-model="form.senacef_remonte"> ❌ Non remis
                  </label>
                </div>
                <input v-if="form.senacef_remonte === 'Non'" v-model="form.senacef_motif" type="text" placeholder="Motif..." style="margin-top:.5rem">
              </div>
            </div>
          </div>

          <!-- ACTIONS -->
          <div class="form-actions">
            <button type="button" class="btn btn-ghost" @click="resetForm">🔄 Réinitialiser</button>
            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
              <span v-if="isSubmitting" class="btn-spinner"></span>
              {{ isSubmitting ? 'Enregistrement...' : '💾 Soumettre le rapport final' }}
            </button>
          </div>

        </form>
      </div>
    </main>

    <!-- ===================== PAGE: HISTORIQUE ===================== -->
    <main v-show="currentTab === 'historique'" class="page-content">
      <div v-if="!isAuthenticated" class="auth-gate">
        <div class="auth-card">
          <div class="auth-icon">🔐</div>
          <h2>Accès restreint</h2>
          <p>Entrez le mot de passe pour consulter les rapports</p>
          <div class="auth-field">
            <input v-model="passwordInput" type="password" placeholder="Mot de passe" @keyup.enter="authenticate" :class="{ error: authError }">
            <p v-if="authError" class="auth-error">❌ Mot de passe incorrect</p>
          </div>
          <button @click="authenticate" class="btn btn-primary btn-full">🔓 Accéder</button>
        </div>
      </div>
      <div v-else class="form-container">
        <div class="filter-bar">
          <input v-model="searchQuery" type="text" placeholder="🔍 Missionné, ville, région..." @input="loadListe(1)" class="filter-input">
          <select v-model="filterRegion" @change="loadListe(1)" class="filter-select">
            <option value="">Toutes les régions</option>
            <option v-for="r in regions" :key="r">{{ r }}</option>
          </select>
          <span class="filter-count">{{ listeTotal }} rapport{{ listeTotal > 1 ? 's' : '' }}</span>
        </div>
        <div class="table-card">
          <div v-if="loadingListe" class="loading-block"><div class="spinner"></div><p>Chargement...</p></div>
          <div v-else-if="!listeData.length" class="empty-block"><div class="empty-icon">📋</div><p>Aucun rapport trouvé</p></div>
          <div v-else class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Région</th><th>Ville</th><th>Missionné</th>
                  <th class="tc">Début</th><th class="tc">Fin</th>
                  <th class="tc">👨‍🎓</th><th class="tc">👨‍🏫</th><th class="tc">👥</th><th class="tc">🤝 CO</th>
                  <th class="tc">Abandons</th><th class="tc">Total</th>
                  <th class="tc">Cours</th><th class="tc">Niveaux</th>
                  <th>Organisation</th><th>Cadre</th><th>Aînés</th><th>Autorités</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in listeData" :key="r.id">
                  <td class="region-cell" :title="r.region">{{ r.region }}</td>
                  <td>{{ r.ville }}</td>
                  <td class="bold">{{ r.missionne }}</td>
                  <td class="tc nowrap">{{ fmtDate(r.date_debut) }}</td>
                  <td class="tc nowrap">{{ fmtDate(r.date_fin) }}</td>
                  <td class="tc green bold">{{ r.nb_seminaristes }}</td>
                  <td class="tc">{{ r.nb_formateurs }}</td>
                  <td class="tc">{{ r.nb_visiteurs }}</td>
                  <td class="tc">{{ r.nb_co }}</td>
                  <td class="tc red">{{ r.nb_abandons }}</td>
                  <td class="tc bold">{{ r.total_participants }}</td>
                  <td class="tc">{{ r.nb_cours }}</td>
                  <td class="tc">{{ r.nb_niveaux }}</td>
                  <td><span class="badge" :class="badgeClass(r.organisation)">{{ r.organisation }}</span></td>
                  <td><span class="badge" :class="badgeClass(r.cadre_site)">{{ r.cadre_site }}</span></td>
                  <td><span class="badge" :class="badgeClassPresence(r.presence_aines)">{{ r.presence_aines }}</span></td>
                  <td><span class="badge" :class="badgeClassPresence(r.presence_autorites)">{{ r.presence_autorites }}</span></td>
                  <td>
                    <button class="btn-action" @click="viewRapport(r)" title="Voir détails">👁</button>
                    <button class="btn-delete" @click="deleteRapport(r.id)" title="Supprimer">🗑</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="pagination" v-if="listePages > 1">
          <button class="page-btn" @click="loadListe(listePage - 1)" :disabled="listePage === 1">‹</button>
          <button v-for="p in listePages" :key="p" class="page-btn" :class="{ active: p === listePage }" @click="loadListe(p)">{{ p }}</button>
          <button class="page-btn" @click="loadListe(listePage + 1)" :disabled="listePage === listePages">›</button>
        </div>
      </div>
    </main>

    <!-- ===================== PAGE: STATS ===================== -->
    <main v-show="currentTab === 'stats'" class="page-content">
      <div v-if="!isAuthenticated" class="auth-gate">
        <div class="auth-card">
          <div class="auth-icon">🔐</div>
          <h2>Accès restreint</h2>
          <p>Entrez le mot de passe pour accéder aux statistiques</p>
          <div class="auth-field">
            <input v-model="passwordInput" type="password" placeholder="Mot de passe" @keyup.enter="authenticate" :class="{ error: authError }">
            <p v-if="authError" class="auth-error">❌ Mot de passe incorrect</p>
          </div>
          <button @click="authenticate" class="btn btn-primary btn-full">🔓 Accéder</button>
        </div>
      </div>
      <div v-else class="form-container">
        <div v-if="loadingStats" class="loading-block"><div class="spinner"></div><p>Chargement...</p></div>
        <div v-else>
          <div class="stats-grid">
            <div class="stat-card"><div class="stat-label">Rapports soumis</div><div class="stat-val">{{ stats.nb_rapports || 0 }}</div></div>
            <div class="stat-card"><div class="stat-label">Total séminaristes</div><div class="stat-val">{{ stats.total_seminaristes?.toLocaleString('fr-FR') || '—' }}</div></div>
            <div class="stat-card"><div class="stat-label">Total formateurs</div><div class="stat-val">{{ stats.total_formateurs?.toLocaleString('fr-FR') || '—' }}</div></div>
            <div class="stat-card"><div class="stat-label">Total visiteurs</div><div class="stat-val">{{ stats.total_visiteurs?.toLocaleString('fr-FR') || '—' }}</div></div>
            <div class="stat-card"><div class="stat-label">Total CO</div><div class="stat-val">{{ stats.total_co?.toLocaleString('fr-FR') || '—' }}</div></div>
            <div class="stat-card"><div class="stat-label">Total participants nets</div><div class="stat-val">{{ stats.total_participants?.toLocaleString('fr-FR') || '—' }}</div></div>
            <div class="stat-card"><div class="stat-label">Moy. cours / séminaire</div><div class="stat-val">{{ stats.moyenne_cours || '—' }}</div></div>
            <div class="stat-card"><div class="stat-label">Régions actives</div><div class="stat-val">{{ stats.nb_regions || 0 }} <small style="font-size:.9rem;font-weight:400">/ 34</small></div></div>
          </div>

          <!-- Taux de réalisation objectifs -->
          <div class="section-header">
            <h3 class="section-title">🎯 Taux de réalisation des objectifs</h3>
          </div>
          <div class="objectif-stats-grid" v-if="stats.taux_objectifs">
            <div v-for="(taux, label) in stats.taux_objectifs" :key="label" class="obj-stat-card">
              <div class="obj-stat-label">{{ label }}</div>
              <div class="obj-stat-bar">
                <div class="obj-stat-fill" :style="{ width: taux + '%', background: taux >= 75 ? '#018404' : taux >= 50 ? '#d97706' : '#dc2626' }"></div>
              </div>
              <div class="obj-stat-pct" :style="{ color: taux >= 75 ? '#018404' : taux >= 50 ? '#d97706' : '#dc2626' }">{{ taux }}%</div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- ===================== MODAL SUCCÈS ===================== -->
    <Transition name="modal">
      <div v-if="showSuccessModal" class="modal-overlay" @click.self="showSuccessModal = false">
        <div class="modal-card">
          <div class="modal-icon">✅</div>
          <h2 class="modal-title">Rapport enregistré !</h2>
          <p class="modal-text">
            Le rapport final de <strong>{{ lastSaved.region }}</strong> — <strong>{{ lastSaved.ville }}</strong>
            soumis par <strong>{{ lastSaved.missionne }}</strong> a bien été enregistré.
          </p>
          <div class="modal-details">
            <div class="modal-detail-item"><span class="modal-detail-label">Période</span><span class="modal-detail-val">{{ fmtDate(lastSaved.date_debut) }} → {{ fmtDate(lastSaved.date_fin) }}</span></div>
            <div class="modal-detail-item"><span class="modal-detail-label">Participants nets</span><span class="modal-detail-val">{{ lastSaved.total_participants }}</span></div>
            <div class="modal-detail-item"><span class="modal-detail-label">Cours dispensés</span><span class="modal-detail-val">{{ lastSaved.nb_cours }}</span></div>
          </div>
          <button @click="showSuccessModal = false" class="btn btn-primary btn-full">Fermer</button>
        </div>
      </div>
    </Transition>

    <!-- ===================== MODAL DETAIL ===================== -->
    <Transition name="modal">
      <div v-if="showDetailModal && selectedRapport" class="modal-overlay" @click.self="showDetailModal = false">
        <div class="modal-card modal-large">
          <div class="modal-close" @click="showDetailModal = false">✕</div>
          <h2 class="modal-title" style="font-size:1.3rem;text-align:left;margin-bottom:1.25rem">
            📄 {{ selectedRapport.region }} — {{ selectedRapport.ville }}
          </h2>
          <div class="detail-grid">
            <div class="detail-section">
              <h4>Informations</h4>
              <p><strong>Missionné :</strong> {{ selectedRapport.missionne }}</p>
              <p><strong>Période :</strong> {{ fmtDate(selectedRapport.date_debut) }} → {{ fmtDate(selectedRapport.date_fin) }}</p>
              <p><strong>Présence missionné :</strong> {{ fmtDate(selectedRapport.date_arrivee) }} → {{ fmtDate(selectedRapport.date_depart) }}</p>
            </div>
            <div class="detail-section">
              <h4>Présences</h4>
              <p>👨‍🎓 Séminaristes : <strong>{{ selectedRapport.nb_seminaristes }}</strong></p>
              <p>👨‍🏫 Formateurs : <strong>{{ selectedRapport.nb_formateurs }}</strong></p>
              <p>👥 Visiteurs : <strong>{{ selectedRapport.nb_visiteurs }}</strong></p>
              <p>🤝 CO : <strong>{{ selectedRapport.nb_co }}</strong></p>
              <p>🚪 Abandons : <strong>{{ selectedRapport.nb_abandons }}</strong></p>
              <p>📊 <strong>Total : {{ selectedRapport.total_participants }}</strong></p>
            </div>
            <div class="detail-section">
              <h4>Observations</h4>
              <p>Organisation : <span class="badge" :class="badgeClass(selectedRapport.organisation)">{{ selectedRapport.organisation }}</span></p>
              <p>Cadre : <span class="badge" :class="badgeClass(selectedRapport.cadre_site)">{{ selectedRapport.cadre_site }}</span></p>
              <p>Aînés : <span class="badge" :class="badgeClassPresence(selectedRapport.presence_aines)">{{ selectedRapport.presence_aines }}</span></p>
              <p>Autorités : <span class="badge" :class="badgeClassPresence(selectedRapport.presence_autorites)">{{ selectedRapport.presence_autorites }}</span></p>
            </div>
            <div class="detail-section fullwidth-detail" v-if="selectedRapport.incidents || selectedRapport.difficultes || selectedRapport.suggestions">
              <h4>Remontée critique</h4>
              <p v-if="selectedRapport.incidents"><strong>Incidents :</strong> {{ selectedRapport.incidents }}</p>
              <p v-if="selectedRapport.difficultes"><strong>Difficultés :</strong> {{ selectedRapport.difficultes }}</p>
              <p v-if="selectedRapport.suggestions"><strong>Suggestions :</strong> {{ selectedRapport.suggestions }}</p>
            </div>
          </div>
          <button @click="showDetailModal = false" class="btn btn-primary btn-full" style="margin-top:1.5rem">Fermer</button>
        </div>
      </div>
    </Transition>

    <!-- Toast -->
    <Transition name="toast">
      <div v-if="toast.show" class="toast" :class="{ 'toast-error': toast.error }">{{ toast.message }}</div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

// ==================== CONFIG ====================
const API_URL = 'https://api.aeemci-ce.ci/rapport_final.php'
const PASSWORD = 'aeemci2633'

// ==================== TABS ====================
const tabs = [
  { id: 'saisie',     icon: '✏️', label: 'Nouveau rapport' },
  { id: 'historique', icon: '📋', label: 'Historique' },
  { id: 'stats',      icon: '📊', label: 'Statistiques' },
]
const currentTab = ref('saisie')

function switchTab(id) {
  currentTab.value = id
  if (id === 'historique' && isAuthenticated.value) loadListe(1)
  if (id === 'stats' && isAuthenticated.value) loadStats()
}

// ==================== AUTH ====================
const isAuthenticated = ref(false)
const passwordInput = ref('')
const authError = ref(false)

function authenticate() {
  if (passwordInput.value === PASSWORD) {
    isAuthenticated.value = true
    authError.value = false
    passwordInput.value = ''
    if (currentTab.value === 'historique') loadListe(1)
    if (currentTab.value === 'stats') loadStats()
  } else {
    authError.value = true
    setTimeout(() => { authError.value = false }, 2000)
  }
}
function logout() { isAuthenticated.value = false; currentTab.value = 'saisie' }

// ==================== TIME ====================
const currentTime = ref('')
function updateTime() {
  currentTime.value = '🕐 ' + new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

// ==================== RÉGIONS ====================
const regions = [
  'ABIDJAN NORD','ABIDJAN SUD','ABIDJAN EST','ABIDJAN OUEST',
  'AGNEBY-TIASSA ET ME','BAGOUE','BELIER','BERE','BAFING','BOUNKANI',
  'CAVALLY','FOLON','GBEKE','GBOKLE','GOH','GONTOUGO',
  'GRANDS-PONTS',"N'ZI",'HAUT-SASSANDRA','IFFOU','INDENIE-DJUABLIN',
  'KABADOUGOU','LOH-DJIBOUA','NAWA','MORONOU','PORO','WORODOUGOU',
  'MARAHOUE','TCHOLOGO','TONPKI','SAN-PEDRO','SUD COMOE','GUEMON','HAMBOL'
]

// ==================== OBJECTIFS ====================
const objectifs = [
  { key: 'test_entree',       label: 'Tenue du test d\'entrée' },
  { key: 'test_sortie',       label: 'Tenue du test de sortie' },
  { key: 'dictee_regionale',  label: 'Tenue de la dictée régionale' },
  { key: 'grin_soeurs',       label: 'Tenue du grin des sœurs' },
  { key: 'concours_ililm',    label: 'Tenue du concours Al-Ilm' },
  { key: 'darul_hifz',        label: 'Tenue du Darul Hifz' },
  { key: 'ceremony_cinquantenaires', label: 'Tenue de la cérémonie des cinquantenaires' },
  { key: 'table_ronde',       label: 'Tenue de la table ronde' },
  { key: 'ceremonie_officielle', label: 'Tenue de la cérémonie officielle' },
  { key: 'activite_env',      label: 'Tenue d\'une activité environnementale' },
]

// ==================== OBSERVATIONS ====================
const observations = [
  {
    key: 'organisation',
    label: 'Organisation',
    options: [
      { val: 'Bonne', label: '✅ Bonne', cls: 'good' },
      { val: 'Moyenne', label: '⚠️ Moyenne', cls: 'mid' },
      { val: 'Mauvaise', label: '❌ Mauvaise', cls: 'bad' },
    ]
  },
  {
    key: 'cadre_site',
    label: 'Cadre du site',
    options: [
      { val: 'Bon', label: '✅ Bon', cls: 'good' },
      { val: 'Moyen', label: '⚠️ Moyen', cls: 'mid' },
      { val: 'Mauvais', label: '❌ Mauvais', cls: 'bad' },
    ]
  },
  {
    key: 'presence_aines',
    label: 'Présence des aînés',
    options: [
      { val: 'Mobilisés', label: '✅ Mobilisés', cls: 'good' },
      { val: 'Acceptable', label: '⚠️ Acceptable', cls: 'mid' },
      { val: 'Absents', label: '❌ Absents', cls: 'bad' },
    ]
  },
  {
    key: 'presence_autorites',
    label: 'Présence des autorités',
    options: [
      { val: 'Mobilisées', label: '✅ Mobilisées', cls: 'good' },
      { val: 'Acceptable', label: '⚠️ Acceptable', cls: 'mid' },
      { val: 'Absentes', label: '❌ Absentes', cls: 'bad' },
    ]
  },
]

// ==================== FORM ====================
const isSubmitting = ref(false)
const showSuccessModal = ref(false)
const lastSaved = ref({})

function defaultObjectifs() {
  const o = {}
  objectifs.forEach(obj => { o[obj.key] = { tenu: '', motif: '', precision: '' } })
  return o
}
function defaultObservations() {
  const o = {}
  observations.forEach(obs => { o[obs.key] = { valeur: '', commentaire: '' } })
  return o
}

const defaultForm = () => ({
  missionne: '', region: '', ville: '',
  date_debut: '', date_fin: '',
  date_arrivee: '', date_depart: '',
  nb_seminaristes: '', nb_formateurs: '', nb_visiteurs: '', nb_co: 0, nb_abandons: 0,
  nb_niveaux: '', nb_cours: '',
  objectifs: defaultObjectifs(),
  observations: defaultObservations(),
  incidents: '', difficultes: '', suggestions: '',
  senamo_remonte: '', senamo_motif: '',
  senacef_remonte: '', senacef_motif: '',
})

const form = ref(defaultForm())

const totalParticipants = computed(() => {
  const s = Number(form.value.nb_seminaristes) || 0
  const f = Number(form.value.nb_formateurs) || 0
  const v = Number(form.value.nb_visiteurs) || 0
  const co = Number(form.value.nb_co) || 0
  const a = Number(form.value.nb_abandons) || 0
  return Math.max(0, s + f + v + co - a)
})

function calcParticipants() {} // total is computed reactively

function resetForm() { form.value = defaultForm() }

async function submitForm() {
  const f = form.value
  if (!f.missionne || !f.region || !f.ville || !f.date_debut || !f.date_fin) {
    showToast('⚠️ Veuillez remplir tous les champs obligatoires', true); return
  }
  if (f.nb_seminaristes === '' || f.nb_formateurs === '') {
    showToast('⚠️ Veuillez saisir le nombre de séminaristes et formateurs', true); return
  }

  isSubmitting.value = true
  try {
    const payload = {
      missionne: f.missionne,
      region: f.region,
      ville: f.ville,
      date_debut: f.date_debut,
      date_fin: f.date_fin,
      date_arrivee: f.date_arrivee,
      date_depart: f.date_depart,
      nb_seminaristes: Number(f.nb_seminaristes) || 0,
      nb_formateurs: Number(f.nb_formateurs) || 0,
      nb_visiteurs: Number(f.nb_visiteurs) || 0,
      nb_co: Number(f.nb_co) || 0,
      nb_abandons: Number(f.nb_abandons) || 0,
      total_participants: totalParticipants.value,
      nb_niveaux: Number(f.nb_niveaux) || 0,
      nb_cours: Number(f.nb_cours) || 0,
      objectifs: f.objectifs,
      observations: f.observations,
      incidents: f.incidents,
      difficultes: f.difficultes,
      suggestions: f.suggestions,
      senamo_remonte: f.senamo_remonte,
      senamo_motif: f.senamo_motif,
      senacef_remonte: f.senacef_remonte,
      senacef_motif: f.senacef_motif,
    }

    const res = await axios.post(`${API_URL}?action=create`, payload)
    if (!res.data.success) throw new Error(res.data.error || 'Erreur serveur')

    lastSaved.value = {
      region: f.region, ville: f.ville, missionne: f.missionne,
      date_debut: f.date_debut, date_fin: f.date_fin,
      total_participants: totalParticipants.value,
      nb_cours: f.nb_cours,
    }
    resetForm()
    showSuccessModal.value = true
  } catch (err) {
    showToast('⚠️ ' + (err.response?.data?.error || err.message), true)
  } finally {
    isSubmitting.value = false
  }
}

// ==================== HISTORIQUE ====================
const listeData = ref([])
const listeTotal = ref(0)
const listePages = ref(0)
const listePage = ref(1)
const loadingListe = ref(false)
const searchQuery = ref('')
const filterRegion = ref('')
const showDetailModal = ref(false)
const selectedRapport = ref(null)

async function loadListe(page = 1) {
  listePage.value = page
  loadingListe.value = true
  try {
    const params = { action: 'liste', page, limit: 20 }
    if (searchQuery.value) params.search = searchQuery.value
    if (filterRegion.value) params.region = filterRegion.value
    const res = await axios.get(API_URL, { params })
    if (res.data.success) {
      listeData.value = res.data.data
      listeTotal.value = res.data.pagination.total
      listePages.value = res.data.pagination.pages
    }
  } catch (err) {
    showToast('Erreur chargement historique', true)
  } finally {
    loadingListe.value = false
  }
}

function viewRapport(r) {
  selectedRapport.value = r
  showDetailModal.value = true
}

async function deleteRapport(id) {
  if (!confirm('Supprimer ce rapport ? Cette action est irréversible.')) return
  try {
    const res = await axios.delete(`${API_URL}?action=delete`, { data: { id } })
    if (!res.data.success) throw new Error(res.data.error)
    showToast('🗑️ Rapport supprimé')
    loadListe(listePage.value)
  } catch (err) {
    showToast('Erreur: ' + err.message, true)
  }
}

// ==================== STATS ====================
const stats = ref({})
const loadingStats = ref(false)

async function loadStats() {
  loadingStats.value = true
  try {
    const res = await axios.get(`${API_URL}?action=stats`)
    if (res.data.success) stats.value = res.data.data
  } catch (err) {
    showToast('Erreur chargement statistiques', true)
  } finally {
    loadingStats.value = false
  }
}

// ==================== TOAST ====================
const toast = ref({ show: false, message: '', error: false })
let toastTimer = null
function showToast(message, error = false) {
  clearTimeout(toastTimer)
  toast.value = { show: true, message, error }
  toastTimer = setTimeout(() => { toast.value.show = false }, error ? 4500 : 3000)
}

// ==================== HELPERS ====================
function fmtDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' })
}
function badgeClass(v) {
  if (!v) return 'badge-bad'
  if (['Bonne', 'Bon'].includes(v)) return 'badge-good'
  if (['Moyenne', 'Moyen'].includes(v)) return 'badge-mid'
  return 'badge-bad'
}
function badgeClassPresence(v) {
  if (!v) return 'badge-bad'
  if (['Mobilisés', 'Mobilisées'].includes(v)) return 'badge-good'
  if (v === 'Acceptable') return 'badge-mid'
  return 'badge-bad'
}

// ==================== INIT ====================
onMounted(() => {
  updateTime()
  setInterval(updateTime, 60000)
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@300;400;600;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

.rapport-app {
  min-height: 100vh;
  background: linear-gradient(160deg, rgba(1,77,2,0.03) 0%, rgba(251,146,60,0.03) 100%);
  font-family: var(--display);
  color: var(--ink);
}

/* ===== HEADER ===== */
.app-header {
  background: linear-gradient(135deg, #014d02 0%, var(--green) 60%, #2d9e30 100%);
  box-shadow: var(--shadow);
  position: sticky; top: 0; z-index: 200;
}
.header-wrapper {
  max-width: 1300px; margin: 0 auto;
  padding: 1rem 2rem;
  display: flex; justify-content: space-between; align-items: center;
}
.logo-section { display: flex; align-items: center; gap: 1rem; }
.logo-badge {
  width: 52px; height: 52px;
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.3);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem;
}
.logo-text h1 { font-size: 1.6rem; font-weight: 900; color: #fff; line-height: 1.1; }
.logo-text p  { font-size: .85rem; color: rgba(255,255,255,.75); }
.header-right { display: flex; align-items: center; gap: 1rem; }
.header-time {
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
  color: #fff; padding: .4rem 1rem; border-radius: 0; font-size: .8rem; font-weight: 600;
}
.btn-logout {
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
  color: #fff; padding: .4rem 1rem; border-radius: 0; font-size: .8rem; font-weight: 600;
  cursor: pointer; font-family: var(--display); transition: background .2s;
}
.btn-logout:hover { background: rgba(255,255,255,.25); }

/* ===== TABS ===== */
.tabs-bar {
  background: white; border-bottom: 2px solid var(--line);
  display: flex; gap: .25rem; padding: .5rem 2rem 0;
  overflow-x: auto; scrollbar-width: none;
}
.tabs-bar::-webkit-scrollbar { display: none; }
.tab-btn {
  padding: .6rem 1.25rem; border-radius: 0;
  font-weight: 700; font-size: .85rem; cursor: pointer; border: none;
  background: transparent; color: var(--muted); transition: all .2s;
  white-space: nowrap; font-family: var(--display);
}
.tab-btn.active { background: var(--green); color: white; }
.tab-btn:hover:not(.active) { background: var(--surface); color: #111; }

/* ===== PAGE ===== */
.page-content { padding-bottom: 3rem; }
.form-container { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; }

/* ===== FORM SECTIONS ===== */
.form-section {
  background: white; border-radius: 0;
  box-shadow: var(--shadow);
  padding: 2rem 2.5rem;
  margin-bottom: 1.5rem;
  border-left: 4px solid var(--green);
}
.section-header-block {
  display: flex; align-items: flex-start; gap: 1rem;
  margin-bottom: 1.75rem; padding-bottom: 1.25rem;
  border-bottom: 1px solid #f3f4f6;
}
.section-num {
  width: 44px; height: 44px; flex-shrink: 0;
  background: linear-gradient(135deg, #014d02, var(--green));
  color: white; border-radius: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; font-weight: 900;
}
.section-title { font-size: 1.25rem; font-weight: 900; color: var(--ink); }
.section-desc  { font-size: .83rem; color: var(--muted); margin-top: .2rem; }

/* ===== FORM GRID ===== */
.form-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}
.form-field { display: flex; flex-direction: column; gap: .45rem; }
.form-field.fullwidth   { grid-column: 1 / -1; }
.form-field.fullwidth-2 { grid-column: span 2; }
.form-field label { font-weight: 700; color: var(--ink-soft); font-size: .88rem; }
.form-field label small { font-weight: 400; color: var(--muted); }
.req { color: #dc2626; }

.form-field input, .form-field select, .form-field textarea {
  padding: .75rem 1rem; border: 2px solid var(--line); border-radius: 0;
  font-size: .95rem; font-family: var(--display);
  background: white; color: #111; transition: all .2s; resize: vertical;
}
.form-field input:focus, .form-field select:focus, .form-field textarea:focus {
  outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(1,132,4,.1);
}

/* ===== PRESENCE GRID ===== */
.presence-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
}
.presence-card {
  background: var(--surface); border: 2px solid var(--line);
  border-radius: 0; padding: 1rem 1.25rem;
  display: flex; flex-direction: column; gap: .4rem;
  transition: border-color .2s;
}
.presence-card:focus-within { border-color: var(--green); }
.presence-icon { font-size: 1.5rem; }
.presence-card label { font-size: .8rem; font-weight: 700; color: var(--ink-soft); }
.presence-card label small { font-weight: 400; color: var(--muted); }
.presence-card input {
  padding: .65rem .9rem; border: 2px solid var(--line); border-radius: 0;
  font-size: 1.1rem; font-family: var(--display); font-weight: 700;
  background: white; color: #111; transition: all .2s; width: 100%;
}
.presence-card input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(1,132,4,.1); }
.abandon-card { border-color: #fee2e2; background: #fff5f5; }
.total-card {
  background: linear-gradient(135deg, rgba(1,132,4,.07), rgba(22,163,74,.04));
  border: 2px solid rgba(1,132,4,.3);
}
.total-display {
  font-size: 2.2rem; font-weight: 900; color: var(--green); line-height: 1;
}
.total-hint { font-size: .68rem; color: var(--muted); }

/* ===== OBJECTIFS ===== */
.objectifs-list { display: flex; flex-direction: column; gap: .75rem; }
.objectif-item {
  background: var(--surface); border: 1px solid var(--line); border-radius: 0; padding: .9rem 1.25rem;
}
.objectif-header {
  display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
}
.objectif-label { font-weight: 700; font-size: .9rem; color: #111; }
.objectif-toggle { display: flex; gap: .5rem; }
.toggle-opt {
  display: flex; align-items: center; gap: .35rem;
  padding: .35rem .85rem; border: 2px solid var(--line); border-radius: 0;
  cursor: pointer; font-size: .82rem; font-weight: 600; color: var(--muted);
  transition: all .2s; user-select: none;
}
.toggle-opt input { display: none; }
.toggle-opt.on  { border-color: var(--green); background: rgba(1,132,4,.08); color: var(--green); }
.toggle-opt.off { border-color: #dc2626; background: rgba(220,38,38,.06); color: #dc2626; }
.objectif-motif { margin-top: .65rem; }
.objectif-motif input {
  width: 100%; padding: .6rem 1rem; border: 2px solid var(--line); border-radius: 0;
  font-size: .88rem; font-family: var(--display); background: white; color: #111; transition: all .2s;
}
.objectif-motif input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 4px rgba(249,115,22,.1); }

/* ===== OBSERVATIONS ===== */
.obs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
.obs-item {
  background: var(--surface); border: 1px solid var(--line); border-radius: 0; padding: 1.1rem 1.25rem;
  display: flex; flex-direction: column; gap: .7rem;
}
.obs-label { font-weight: 700; font-size: .88rem; color: var(--ink-soft); }
.radio-row { display: flex; gap: .6rem; flex-wrap: wrap; }
.radio-opt {
  display: flex; align-items: center; gap: .35rem;
  padding: .4rem .9rem; border: 2px solid var(--line); border-radius: 0;
  cursor: pointer; font-size: .82rem; font-weight: 600; color: var(--muted);
  transition: all .2s; user-select: none;
}
.radio-opt input { display: none; }
.radio-opt.selected.opt-good { border-color: var(--green); background: rgba(1,132,4,.08); color: var(--green); }
.radio-opt.selected.opt-mid  { border-color: var(--gold); background: rgba(217,119,6,.08); color: var(--gold); }
.radio-opt.selected.opt-bad  { border-color: #dc2626; background: rgba(220,38,38,.08); color: #dc2626; }
.radio-opt:hover:not(.selected) { border-color: var(--green); color: var(--green); background: rgba(1,132,4,.04); }
.obs-comment {
  padding: .55rem .9rem; border: 2px solid var(--line); border-radius: 0;
  font-size: .85rem; font-family: var(--display); background: white; color: #111; transition: all .2s;
}
.obs-comment:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(1,132,4,.1); }

/* ===== RAPPORT TOGGLE ===== */
.rapport-toggle { display: flex; gap: .6rem; flex-wrap: wrap; }

/* ===== FORM ACTIONS ===== */
.form-actions {
  display: flex; justify-content: space-between; align-items: center;
  padding: 1.5rem 0 .5rem;
}

/* ===== BUTTONS ===== */
.btn {
  padding: .75rem 1.75rem; border-radius: 0;
  font-size: 1rem; font-weight: 700; border: none;
  cursor: pointer; font-family: var(--display);
  transition: all .25s; display: inline-flex; align-items: center; gap: .5rem;
}
.btn-primary {
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white; box-shadow: var(--shadow);
}
.btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: var(--shadow); }
.btn-primary:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.btn-ghost { background: white; color: var(--green); border: 2px solid var(--green); }
.btn-ghost:hover { background: var(--green); color: white; }
.btn-full { width: 100%; justify-content: center; }
.btn-spinner {
  width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.3);
  border-top-color: white; border-radius: 50%; animation: spin .8s linear infinite;
}

/* ===== AUTH ===== */
.auth-gate { display: flex; justify-content: center; align-items: center; min-height: 70vh; padding: 2rem; }
.auth-card {
  background: white; border-radius: 0; box-shadow: var(--shadow);
  padding: 3rem 2.5rem; max-width: 420px; width: 100%; text-align: center;
}
.auth-icon { font-size: 4rem; margin-bottom: 1rem; }
.auth-card h2 { font-size: 1.8rem; font-weight: 900; color: var(--ink); margin-bottom: .5rem; }
.auth-card p  { color: var(--muted); font-size: 1rem; margin-bottom: 1.5rem; }
.auth-field { margin-bottom: 1.25rem; }
.auth-field input {
  width: 100%; padding: .9rem 1.2rem; border: 2px solid var(--line); border-radius: 0;
  font-size: 1.1rem; font-family: var(--display); text-align: center; transition: all .2s;
}
.auth-field input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(1,132,4,.1); }
.auth-field input.error { border-color: #dc2626; animation: shake .3s ease; }
.auth-error { color: #dc2626; font-size: .85rem; margin-top: .5rem; font-weight: 600; }

/* ===== FILTER ===== */
.filter-bar { display: flex; align-items: center; gap: .75rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.filter-input, .filter-select {
  padding: .55rem .95rem; border: 2px solid var(--line); border-radius: 0;
  font-size: .9rem; font-family: var(--display); background: white; color: #111; transition: border-color .2s;
}
.filter-input { flex: 1; max-width: 300px; }
.filter-select { max-width: 320px; }
.filter-input:focus, .filter-select:focus { outline: none; border-color: var(--green); }
.filter-count { font-size: .8rem; color: var(--muted); margin-left: auto; }

/* ===== TABLE ===== */
.table-card {
  background: white; border: 1px solid var(--line); border-radius: 0; overflow: hidden;
  box-shadow: var(--shadow); margin-bottom: 1.25rem;
}
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .82rem; }
th {
  background: var(--surface); padding: .6rem 1rem; text-align: left; font-weight: 700;
  font-size: .71rem; text-transform: uppercase; letter-spacing: .04em;
  color: var(--muted); border-bottom: 1px solid var(--line); white-space: nowrap;
}
td { padding: .6rem 1rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--surface); }
.tc     { text-align: center; }
.nowrap { white-space: nowrap; }
.bold   { font-weight: 700; }
.green  { color: var(--green); }
.red    { color: #dc2626; }
.region-cell { font-weight: 700; font-size: .78rem; max-width: 110px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Badges */
.badge { display: inline-flex; align-items: center; padding: .18rem .6rem; border-radius: 0; font-size: .72rem; font-weight: 700; white-space: nowrap; }
.badge-good { background: var(--green-mist); color: var(--green); }
.badge-mid  { background: var(--gold-mist); color: #854d0e; }
.badge-bad  { background: #fee2e2; color: #991b1b; }

.btn-action, .btn-delete {
  background: none; border: none; cursor: pointer; font-size: 1rem;
  opacity: .55; transition: opacity .2s; padding: .25rem .3rem;
}
.btn-action:hover, .btn-delete:hover { opacity: 1; }

/* ===== STATS ===== */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.stat-card {
  background: white; border: 1px solid var(--line); border-radius: 0;
  padding: 1.2rem 1.4rem; position: relative; overflow: hidden;
  box-shadow: var(--shadow);
}
.stat-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, #014d02, var(--green-2));
}
.stat-label { font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; }
.stat-val   { font-size: 2rem; font-weight: 900; color: #111; line-height: 1.2; margin: .2rem 0 .1rem; }
.stat-sub   { font-size: .75rem; color: var(--muted); }

.section-header { display: flex; align-items: center; justify-content: space-between; margin: 1.25rem 0 1rem; }
.section-title  { font-size: 1.1rem; font-weight: 900; }

.objectif-stats-grid { display: flex; flex-direction: column; gap: .6rem; background: white; border-radius: 0; padding: 1.5rem; box-shadow: var(--shadow); }
.obj-stat-card { display: flex; align-items: center; gap: 1rem; }
.obj-stat-label { font-size: .82rem; font-weight: 600; color: var(--ink-soft); min-width: 240px; }
.obj-stat-bar   { flex: 1; height: 8px; background: var(--line); border-radius: 0; overflow: hidden; }
.obj-stat-fill  { height: 100%; border-radius: 0; transition: width .6s; }
.obj-stat-pct   { font-size: .82rem; font-weight: 700; min-width: 40px; text-align: right; }

/* ===== PAGINATION ===== */
.pagination { display: flex; align-items: center; justify-content: center; gap: .4rem; margin-top: 1rem; }
.page-btn {
  width: 36px; height: 36px; border-radius: 0; border: 1px solid var(--line);
  background: white; cursor: pointer; font-size: .85rem; font-weight: 700;
  font-family: var(--display); color: var(--muted); transition: all .2s;
  display: flex; align-items: center; justify-content: center;
}
.page-btn.active { background: var(--green); color: white; border-color: var(--green); }
.page-btn:hover:not(.active):not(:disabled) { background: #f3f4f6; color: #111; }
.page-btn:disabled { opacity: .4; cursor: not-allowed; }

/* ===== LOADING / EMPTY ===== */
.loading-block { text-align: center; padding: 3rem; color: var(--muted); }
.empty-block   { text-align: center; padding: 4rem 2rem; color: var(--muted); }
.empty-icon    { font-size: 3rem; margin-bottom: .75rem; }
.spinner {
  width: 32px; height: 32px; border: 3px solid var(--line); border-top-color: var(--green);
  border-radius: 50%; animation: spin .7s linear infinite; margin: 0 auto .75rem;
}

/* ===== MODAL ===== */
.modal-overlay {
  position: fixed; inset: 0; z-index: 9000;
  background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center;
  padding: 1.5rem; backdrop-filter: blur(4px);
}
.modal-card {
  background: white; border-radius: 0; box-shadow: var(--shadow);
  padding: 3rem 2.5rem; max-width: 520px; width: 100%; text-align: center;
  max-height: 90vh; overflow-y: auto; position: relative;
}
.modal-large { max-width: 780px; text-align: left; padding: 2rem 2.5rem; }
.modal-close {
  position: absolute; top: 1rem; right: 1.25rem;
  cursor: pointer; font-size: 1.1rem; color: var(--muted);
  width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
  border-radius: 0; transition: all .2s;
}
.modal-close:hover { background: #f3f4f6; color: #111; }
.modal-icon  { font-size: 5rem; margin-bottom: 1rem; }
.modal-title { font-size: 2rem; font-weight: 900; color: var(--ink); margin-bottom: .75rem; }
.modal-text  { color: var(--muted); font-size: 1rem; line-height: 1.6; margin-bottom: 1.5rem; }
.modal-details {
  background: linear-gradient(135deg, rgba(1,132,4,.06), rgba(251,146,60,.06));
  border: 1px solid var(--line); border-radius: 0;
  padding: 1.25rem; margin-bottom: 1.75rem;
  display: flex; flex-direction: column; gap: .75rem;
}
.modal-detail-item { display: flex; justify-content: space-between; align-items: center; }
.modal-detail-label { font-size: .82rem; color: var(--muted); font-weight: 600; }
.modal-detail-val   { font-size: .95rem; font-weight: 900; color: #111; }

.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.detail-section { background: var(--surface); border-radius: 0; padding: 1.1rem 1.25rem; }
.detail-section.fullwidth-detail { grid-column: 1 / -1; }
.detail-section h4 { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); margin-bottom: .75rem; }
.detail-section p  { font-size: .88rem; color: var(--ink-soft); margin-bottom: .35rem; line-height: 1.5; }

/* ===== TOAST ===== */
.toast {
  position: fixed; bottom: 2rem; right: 2rem; z-index: 9999;
  background: var(--green); color: white; padding: .9rem 1.6rem; border-radius: 0;
  font-weight: 700; font-size: .95rem; box-shadow: var(--shadow); max-width: 340px;
}
.toast-error { background: #dc2626; box-shadow: var(--shadow); }

/* ===== TRANSITIONS ===== */
.modal-enter-active, .modal-leave-active { transition: all .3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-card, .modal-leave-to .modal-card { transform: scale(.9) translateY(20px); }
.toast-enter-active, .toast-leave-active { transition: all .3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(40px); }

/* ===== ANIMATIONS ===== */
@keyframes spin  { to { transform: rotate(360deg); } }
@keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 75%{transform:translateX(6px)} }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
  .form-grid { grid-template-columns: repeat(2, 1fr); }
  .form-field.fullwidth-2 { grid-column: 1 / -1; }
  .presence-grid { grid-template-columns: repeat(2, 1fr); }
  .obs-grid { grid-template-columns: 1fr; }
  .objectif-stats-grid .obj-stat-label { min-width: 160px; }
  .detail-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .header-wrapper { flex-direction: column; gap: 1rem; padding: 1rem; }
  .form-section { padding: 1.5rem 1.25rem; }
  .form-grid { grid-template-columns: 1fr; }
  .form-field.fullwidth-2, .form-field.fullwidth { grid-column: 1; }
  .presence-grid { grid-template-columns: repeat(2, 1fr); }
  .tabs-bar { padding: .4rem 1rem 0; }
  .tab-btn { font-size: .78rem; padding: .5rem .75rem; }
  .form-actions { flex-direction: column; gap: .75rem; }
  .form-actions .btn { width: 100%; justify-content: center; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .obj-stat-label { min-width: 120px; font-size: .72rem; }
}
</style>