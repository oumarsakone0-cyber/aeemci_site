<template>
  <div class="seminaires-app">
    <!-- Header -->
    <header class="app-header">
      <div class="header-wrapper">
        <div class="logo-section">
          <div class="logo-badge">🕌</div>
          <div class="logo-text">
            <h1>AEEMCI</h1>
            <p>Séminaires des Bases</p>
          </div>
        </div>
        <div class="header-right">
          <span class="header-time">{{ currentTime }}</span>
          <button v-if="isAuthenticated" @click="logout" class="btn-logout">
            🔒 Déconnexion
          </button>
        </div>
      </div>
    </header>

    <!-- Tabs -->
    <div class="tabs-bar">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        class="tab-btn"
        :class="{ active: currentTab === tab.id }"
        @click="switchTab(tab.id)"
      >
        {{ tab.icon }} {{ tab.label }}
      </button>
    </div>

    <!-- ===================== PAGE: SAISIE ===================== -->
    <main v-show="currentTab === 'saisie'" class="page-content">
      <div class="form-container">
        <div class="form-card">
          <h2 class="form-card-title">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            Nouveau rapport de séminaire
          </h2>

          <form @submit.prevent="submitForm" novalidate>
            <div class="form-grid">
              <!-- Date -->
              <div class="form-field">
                <label>Date du séminaire <span class="req">*</span></label>
                <input v-model="form.date_seminaire" type="date" required>
              </div>

              <!-- Région -->
              <div class="form-field">
                <label>Région (Secrétariat) <span class="req">*</span></label>
                <select v-model="form.region" required>
                  <option value="">-- Sélectionner la région --</option>
                  <option v-for="r in regions" :key="r">{{ r }}</option>
                </select>
              </div>

              <!-- Ville -->
              <div class="form-field">
                <label>Ville / Localité <span class="req">*</span></label>
                <input v-model="form.ville" type="text" placeholder="Ex: Adjamé, Bouaké..." required>
              </div>

              <!-- Missionné -->
              <div class="form-field fullwidth">
                <label>Nom du missionné <span class="req">*</span></label>
                <input v-model="form.missionne" type="text" placeholder="Prénom et nom complet du représentant" required>
              </div>

              <!-- ===== PRÉSENCES ===== -->
              <div class="form-field fullwidth">
                <label class="section-label">Présences <span class="req">*</span></label>
                <div class="presence-grid">
                  <div class="presence-field">
                    <label class="presence-sub-label">👨‍🎓 Séminaristes</label>
                    <input v-model.number="form.presents_seminariste" type="number" min="0" placeholder="0" required @input="updatePresences">
                  </div>
                  <div class="presence-field">
                    <label class="presence-sub-label">👨‍🏫 Formateurs</label>
                    <input v-model.number="form.presents_formateur" type="number" min="0" placeholder="0" required @input="updatePresences">
                  </div>
                  <div class="presence-field">
                    <label class="presence-sub-label">👥 Visiteurs</label>
                    <input v-model.number="form.presents_visiteur" type="number" min="0" placeholder="0" @input="updatePresences">
                  </div>
                  <div class="presence-field">
                    <label class="presence-sub-label">📚 Nombre de cours</label>
                    <input v-model.number="form.nombre_cours" type="number" min="0" placeholder="0" required>
                  </div>
                </div>
              </div>

              <!-- Abandons -->
              <div class="form-field">
                <label>Abandons</label>
                <input v-model.number="form.abandons" type="number" min="0" placeholder="0" @input="updatePresences">
              </div>

              <!-- Total cumulé (calculé auto) -->
              <div class="form-field">
                <label>Total présents (calculé auto)</label>
                <div class="computed-field">
                  <span class="computed-value">{{ computedTotal }}</span>
                  <span class="computed-hint">= Séminaristes + Formateurs + Visiteurs − Abandons</span>
                </div>
              </div>

              <!-- Organisation -->
              <div class="form-field fullwidth">
                <label>Organisation <span class="req">*</span></label>
                <div class="radio-row">
                  <label class="radio-opt" :class="{ selected: form.organisation === 'Bonne' }">
                    <input v-model="form.organisation" type="radio" value="Bonne" @change="updateScore"> ✅ Bonne <small>(+2 pts)</small>
                  </label>
                  <label class="radio-opt" :class="{ selected: form.organisation === 'Moyenne' }">
                    <input v-model="form.organisation" type="radio" value="Moyenne" @change="updateScore"> ⚠️ Moyenne <small>(+1 pt)</small>
                  </label>
                  <label class="radio-opt" :class="{ selected: form.organisation === 'Mauvaise' }">
                    <input v-model="form.organisation" type="radio" value="Mauvaise" @change="updateScore"> ❌ Mauvaise <small>(0 pt)</small>
                  </label>
                </div>
              </div>

              <!-- Discipline -->
              <div class="form-field fullwidth">
                <label>Discipline <span class="req">*</span></label>
                <div class="radio-row">
                  <label class="radio-opt" :class="{ selected: form.discipline === 'Bonne' }">
                    <input v-model="form.discipline" type="radio" value="Bonne" @change="updateScore"> ✅ Bonne <small>(+2 pts)</small>
                  </label>
                  <label class="radio-opt" :class="{ selected: form.discipline === 'Moyenne' }">
                    <input v-model="form.discipline" type="radio" value="Moyenne" @change="updateScore"> ⚠️ Moyenne <small>(+1 pt)</small>
                  </label>
                  <label class="radio-opt" :class="{ selected: form.discipline === 'Mauvaise' }">
                    <input v-model="form.discipline" type="radio" value="Mauvaise" @change="updateScore"> ❌ Mauvaise <small>(0 pt)</small>
                  </label>
                </div>
              </div>

              <!-- Activités -->
              <div class="form-field fullwidth">
                <label>Activités <span class="req">*</span></label>
                <div class="radio-row">
                  <label class="radio-opt" :class="{ selected: form.activites === 'Complètes' }">
                    <input v-model="form.activites" type="radio" value="Complètes" @change="updateScore"> ✅ Complètes <small>(+2 pts)</small>
                  </label>
                  <label class="radio-opt" :class="{ selected: form.activites === 'Partielles' }">
                    <input v-model="form.activites" type="radio" value="Partielles" @change="updateScore"> ⚠️ Partielles <small>(+1 pt)</small>
                  </label>
                  <label class="radio-opt" :class="{ selected: form.activites === 'Incomplètes' }">
                    <input v-model="form.activites" type="radio" value="Incomplètes" @change="updateScore"> ❌ Incomplètes <small>(0 pt)</small>
                  </label>
                </div>
              </div>

              <!-- Incidents -->
              <div class="form-field fullwidth">
                <label>Incidents signalés <span class="req">*</span></label>
                <div class="radio-row">
                  <label class="radio-opt" :class="{ selected: form.incidents === 'Non' }">
                    <input v-model="form.incidents" type="radio" value="Non" @change="updateScore"> ✅ Aucun incident
                  </label>
                  <label class="radio-opt" :class="{ selected: form.incidents === 'Oui' }">
                    <input v-model="form.incidents" type="radio" value="Oui" @change="updateScore"> 🚨 Incidents signalés <small>(−2 pts)</small>
                  </label>
                </div>
              </div>

              <!-- Score preview -->
              <div class="form-field fullwidth">
                <div class="score-preview">
                  <div class="score-details">
                    <div class="score-preview-label">Score calculé automatiquement</div>
                    <div class="score-chips">
                      <span v-for="d in scoreDetails" :key="d" class="score-chip">{{ d }}</span>
                      <span v-if="!scoreDetails.length" class="score-chip">Remplissez le formulaire</span>
                    </div>
                  </div>
                  <div class="score-big">
                    <div class="score-preview-label">Score estimé</div>
                    <div class="score-number">{{ computedScore }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
              <button type="button" class="btn btn-ghost" @click="resetForm">🔄 Réinitialiser</button>
              <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                <span v-if="isSubmitting" class="btn-spinner"></span>
                {{ isSubmitting ? 'Enregistrement...' : '💾 Enregistrer le rapport' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- ===================== PAGE: DASHBOARD ===================== -->
    <main v-show="currentTab === 'dashboard'" class="page-content">
      <div v-if="!isAuthenticated" class="auth-gate">
        <div class="auth-card">
          <div class="auth-icon">🔐</div>
          <h2>Accès restreint</h2>
          <p>Entrez le mot de passe pour consulter les statistiques</p>
          <div class="auth-field">
            <input
              v-model="passwordInput"
              type="password"
              placeholder="Mot de passe"
              @keyup.enter="authenticate"
              :class="{ error: authError }"
            >
            <p v-if="authError" class="auth-error">❌ Mot de passe incorrect</p>
          </div>
          <button @click="authenticate" class="btn btn-primary btn-full">
            🔓 Accéder au tableau de bord
          </button>
        </div>
      </div>

      <div v-else class="form-container">
        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-label">Total participants</div>
            <div class="stat-val">{{ stats.total_participants?.toLocaleString('fr-FR') || '—' }}</div>
            <div class="stat-sub">Tous séminaires confondus</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Séminaristes</div>
            <div class="stat-val">{{ stats.total_seminaristes?.toLocaleString('fr-FR') || '—' }}</div>
            <div class="stat-sub">Participants principaux</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Formateurs</div>
            <div class="stat-val">{{ stats.total_formateurs?.toLocaleString('fr-FR') || '—' }}</div>
            <div class="stat-sub">Encadreurs</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Visiteurs</div>
            <div class="stat-val">{{ stats.total_visiteurs?.toLocaleString('fr-FR') || '—' }}</div>
            <div class="stat-sub">Observateurs</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Abandons cumulés</div>
            <div class="stat-val">{{ stats.total_abandons?.toLocaleString('fr-FR') || '—' }}</div>
            <div class="stat-sub">Sur tous les séminaires</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Moy. cours / séminaire</div>
            <div class="stat-val">{{ stats.moyenne_cours || '—' }}</div>
            <div class="stat-sub">Cours dispensés</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Score moyen national</div>
            <div class="stat-val">{{ stats.score_moyen_national || '—' }}</div>
            <div class="stat-sub">Sur 8 points max</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Séminaires enregistrés</div>
            <div class="stat-val">{{ stats.nb_seminaires || 0 }}</div>
            <div class="stat-sub">Total</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Régions actives</div>
            <div class="stat-val">{{ stats.nb_regions_actives || 0 }}</div>
            <div class="stat-sub">Sur 34 secrétariats</div>
          </div>
        </div>

        <!-- Alertes -->
        <div v-if="stats.alertes_score_bas?.length" class="alert-box">
          <span class="alert-icon">⚠️</span>
          <div>
            <div class="alert-title">Régions à surveiller — score inférieur à 5</div>
            <div class="alert-text">
              <strong v-for="a in stats.alertes_score_bas" :key="a.region">
                {{ a.region }} ({{ a.score_moyen }}) &nbsp;
              </strong>
            </div>
          </div>
        </div>

        <!-- Classement -->
        <div class="section-header">
          <h3 class="section-title">🏆 Classement des régions</h3>
          <span class="section-sub">Cliquer pour voir le détail</span>
        </div>

        <div v-if="loadingStats" class="loading-block">
          <div class="spinner"></div>
          <p>Chargement...</p>
        </div>

        <div v-else-if="!stats.regions?.length" class="empty-block">
          <div class="empty-icon">📊</div>
          <p>Aucune donnée. Commencez par saisir un séminaire.</p>
        </div>

        <div v-else class="ranking-grid">
          <div
            v-for="(r, i) in stats.regions"
            :key="r.region"
            class="region-card"
            @click="openRegion(r.region)"
          >
            <div class="rank-num" :class="{ top3: i < 3 }">{{ i + 1 }}</div>
            <div class="region-info">
              <div class="region-name" :title="r.region">{{ r.region }}</div>
              <div class="region-sub">{{ r.nb_seminaires }} séminaire{{ r.nb_seminaires > 1 ? 's' : '' }} · {{ r.total_presents }} participants</div>
              <div class="progress-bar">
                <div class="progress-fill" :style="{ width: Math.round((r.score_moyen / 8) * 100) + '%' }"></div>
              </div>
            </div>
            <div class="region-score">
              <div class="region-score-val" :style="{ color: scoreColor(r.score_moyen) }">{{ r.score_moyen }}</div>
              <div class="region-score-label">score</div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- ===================== PAGE: HISTORIQUE ===================== -->
    <main v-show="currentTab === 'historique'" class="page-content">
      <div v-if="!isAuthenticated" class="auth-gate">
        <div class="auth-card">
          <div class="auth-icon">🔐</div>
          <h2>Accès restreint</h2>
          <p>Entrez le mot de passe pour consulter l'historique</p>
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
          <span class="filter-count">{{ listeTotal }} résultat{{ listeTotal > 1 ? 's' : '' }}</span>
        </div>

        <div class="table-card">
          <div v-if="loadingListe" class="loading-block">
            <div class="spinner"></div><p>Chargement...</p>
          </div>
          <div v-else-if="!listeData.length" class="empty-block">
            <div class="empty-icon">📋</div><p>Aucun séminaire trouvé</p>
          </div>
          <div v-else class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Date</th><th>Région</th><th>Ville</th><th>Missionné</th>
                  <th class="tc">👨‍🎓 Séminaristes</th>
                  <th class="tc">👨‍🏫 Formateurs</th>
                  <th class="tc">👥 Visiteurs</th>
                  <th class="tc">Abandons</th>
                  <th class="tc">Total présents</th>
                  <th class="tc">📚 Cours</th>
                  <th>Organisation</th><th>Discipline</th><th>Activités</th>
                  <th class="tc">Incidents</th><th class="tc">Score</th><th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="s in listeData" :key="s.id">
                  <td class="nowrap">{{ fmtDate(s.date_seminaire) }}</td>
                  <td class="region-cell" :title="s.region">{{ s.region }}</td>
                  <td>{{ s.ville }}</td>
                  <td class="bold">{{ s.missionne }}</td>
                  <td class="tc green bold">{{ s.presents_seminariste }}</td>
                  <td class="tc">{{ s.presents_formateur }}</td>
                  <td class="tc">{{ s.presents_visiteur }}</td>
                  <td class="tc red">{{ s.abandons }}</td>
                  <td class="tc bold">{{ s.total_cumule }}</td>
                  <td class="tc">{{ s.nombre_cours }}</td>
                  <td><span class="badge" :class="badgeClass(s.organisation)">{{ s.organisation }}</span></td>
                  <td><span class="badge" :class="badgeClass(s.discipline)">{{ s.discipline }}</span></td>
                  <td><span class="badge" :class="badgeClass(s.activites)">{{ s.activites }}</span></td>
                  <td class="tc"><span class="badge" :class="s.incidents === 'Oui' ? 'badge-bad' : 'badge-good'">{{ s.incidents }}</span></td>
                  <td class="tc">
                    <div class="score-bubble" :class="scoreBubbleClass(s.score_final ?? s.score)">{{ s.score_final ?? s.score ?? 0 }}</div>
                  </td>
                  <td>
                    <button class="btn-delete" @click="deleteSem(s.id)" title="Supprimer">🗑</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="pagination" v-if="listePages > 1">
          <button class="page-btn" @click="loadListe(listePage - 1)" :disabled="listePage === 1">‹</button>
          <button
            v-for="p in listePages" :key="p"
            class="page-btn"
            :class="{ active: p === listePage }"
            @click="loadListe(p)"
          >{{ p }}</button>
          <button class="page-btn" @click="loadListe(listePage + 1)" :disabled="listePage === listePages">›</button>
        </div>
      </div>
    </main>

    <!-- ===================== PAGE: RÉGIONS ===================== -->
    <main v-show="currentTab === 'regions'" class="page-content">
      <div v-if="!isAuthenticated" class="auth-gate">
        <div class="auth-card">
          <div class="auth-icon">🔐</div>
          <h2>Accès restreint</h2>
          <p>Entrez le mot de passe pour consulter les statistiques régionales</p>
          <div class="auth-field">
            <input v-model="passwordInput" type="password" placeholder="Mot de passe" @keyup.enter="authenticate" :class="{ error: authError }">
            <p v-if="authError" class="auth-error">❌ Mot de passe incorrect</p>
          </div>
          <button @click="authenticate" class="btn btn-primary btn-full">🔓 Accéder</button>
        </div>
      </div>
      <div v-else class="form-container">
        <div class="filter-bar">
          <label class="filter-label">Sélectionner une région :</label>
          <select v-model="selectedRegion" @change="loadRegionDetail" class="filter-select wide">
            <option value="">-- Choisir un secrétariat régional --</option>
            <option v-for="r in regions" :key="r">{{ r }}</option>
          </select>
        </div>

        <div v-if="!selectedRegion" class="empty-block">
          <div class="empty-icon">🗺️</div>
          <p>Sélectionner une région pour voir ses statistiques</p>
        </div>

        <div v-else-if="loadingRegion" class="loading-block">
          <div class="spinner"></div><p>Chargement...</p>
        </div>

        <div v-else-if="regionData">
          <div class="stats-grid">
            <div class="stat-card"><div class="stat-label">Séminaires</div><div class="stat-val">{{ regionData.nb_seminaires }}</div></div>
            <div class="stat-card"><div class="stat-label">Total séminaristes</div><div class="stat-val">{{ regionData.total_seminaristes?.toLocaleString('fr-FR') }}</div></div>
            <div class="stat-card"><div class="stat-label">Total formateurs</div><div class="stat-val">{{ regionData.total_formateurs?.toLocaleString('fr-FR') }}</div></div>
            <div class="stat-card"><div class="stat-label">Total visiteurs</div><div class="stat-val">{{ regionData.total_visiteurs?.toLocaleString('fr-FR') }}</div></div>
            <div class="stat-card"><div class="stat-label">Total abandons</div><div class="stat-val">{{ regionData.total_abandons?.toLocaleString('fr-FR') }}</div></div>
            <div class="stat-card"><div class="stat-label">Total présents nets</div><div class="stat-val">{{ regionData.total_presents?.toLocaleString('fr-FR') }}</div></div>
            <div class="stat-card"><div class="stat-label">Moy. participants</div><div class="stat-val">{{ regionData.moyenne_presents }}</div></div>
            <div class="stat-card">
              <div class="stat-label">Score moyen</div>
              <div class="stat-val" :style="{ color: scoreColor(regionData.score_moyen) }">{{ regionData.score_moyen }}</div>
              <div class="stat-sub">/ 8 points max</div>
            </div>
          </div>

          <div class="table-card">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Date</th><th>Ville</th><th>Missionné</th>
                    <th class="tc">👨‍🎓 Séminar.</th>
                    <th class="tc">👨‍🏫 Format.</th>
                    <th class="tc">👥 Visit.</th>
                    <th class="tc">Abandons</th>
                    <th class="tc">Total</th>
                    <th class="tc">📚 Cours</th>
                    <th>Organisation</th><th>Discipline</th><th>Activités</th>
                    <th class="tc">Incidents</th>
                    <th class="tc">Bonus présence</th>
                    <th class="tc">Score final</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="s in regionData.seminaires" :key="s.id">
                    <td class="nowrap">{{ fmtDate(s.date_seminaire) }}</td>
                    <td>{{ s.ville }}</td>
                    <td class="bold">{{ s.missionne }}</td>
                    <td class="tc green bold">{{ s.presents_seminariste }}</td>
                    <td class="tc">{{ s.presents_formateur }}</td>
                    <td class="tc">{{ s.presents_visiteur }}</td>
                    <td class="tc red">{{ s.abandons }}</td>
                    <td class="tc bold">{{ s.total_cumule }}</td>
                    <td class="tc">{{ s.nombre_cours }}</td>
                    <td><span class="badge" :class="badgeClass(s.organisation)">{{ s.organisation }}</span></td>
                    <td><span class="badge" :class="badgeClass(s.discipline)">{{ s.discipline }}</span></td>
                    <td><span class="badge" :class="badgeClass(s.activites)">{{ s.activites }}</span></td>
                    <td class="tc"><span class="badge" :class="s.incidents === 'Oui' ? 'badge-bad' : 'badge-good'">{{ s.incidents }}</span></td>
                    <td class="tc" :style="{ color: s.bonus_presence > 0 ? '#018404' : '#9ca3af', fontWeight: 700 }">
                      {{ s.bonus_presence > 0 ? '+2' : '—' }}
                    </td>
                    <td class="tc">
                      <div class="score-bubble" :class="scoreBubbleClass(s.score_final ?? s.score)">{{ s.score_final ?? s.score ?? 0 }}</div>
                    </td>
                  </tr>
                </tbody>
              </table>
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
            Le séminaire de <strong>{{ lastSaved.region }}</strong> — <strong>{{ lastSaved.ville }}</strong>
            a bien été enregistré avec un score de <strong>{{ lastSaved.score }}</strong>/8.
          </p>
          <div class="modal-details">
            <div class="modal-detail-item">
              <span class="modal-detail-label">Missionné</span>
              <span class="modal-detail-val">{{ lastSaved.missionne }}</span>
            </div>
            <div class="modal-detail-item">
              <span class="modal-detail-label">👨‍🎓 Séminaristes</span>
              <span class="modal-detail-val">{{ lastSaved.presents_seminariste }}</span>
            </div>
            <div class="modal-detail-item">
              <span class="modal-detail-label">👨‍🏫 Formateurs</span>
              <span class="modal-detail-val">{{ lastSaved.presents_formateur }}</span>
            </div>
            <div class="modal-detail-item">
              <span class="modal-detail-label">👥 Visiteurs</span>
              <span class="modal-detail-val">{{ lastSaved.presents_visiteur }}</span>
            </div>
            <div class="modal-detail-item">
              <span class="modal-detail-label">Total présents nets</span>
              <span class="modal-detail-val">{{ lastSaved.total_cumule }}</span>
            </div>
            <div class="modal-detail-item">
              <span class="modal-detail-label">📚 Cours dispensés</span>
              <span class="modal-detail-val">{{ lastSaved.nombre_cours }}</span>
            </div>
            <div class="modal-detail-item">
              <span class="modal-detail-label">Date</span>
              <span class="modal-detail-val">{{ fmtDate(lastSaved.date) }}</span>
            </div>
          </div>
          <button @click="showSuccessModal = false" class="btn btn-primary btn-full">
            Fermer
          </button>
        </div>
      </div>
    </Transition>

    <!-- Toast -->
    <Transition name="toast">
      <div v-if="toast.show" class="toast" :class="{ 'toast-error': toast.error }">
        {{ toast.message }}
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

// ==================== CONFIG ====================
const API_URL = 'https://api.aeemci-ce.ci/seminaires_stats.php'
const PASSWORD = 'aeemci2633'

// ==================== TABS ====================
const tabs = [
  { id: 'saisie', icon: '✏️', label: 'Saisie séminaire' },
  { id: 'dashboard', icon: '📊', label: 'Tableau de bord' },
  { id: 'historique', icon: '📋', label: 'Historique' },
  { id: 'regions', icon: '🗺️', label: 'Par région' },
]
const currentTab = ref('saisie')

function switchTab(id) {
  currentTab.value = id
  if (id === 'dashboard' && isAuthenticated.value) loadDashboard()
  if (id === 'historique' && isAuthenticated.value) loadListe(1)
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
    if (currentTab.value === 'dashboard') loadDashboard()
    if (currentTab.value === 'historique') loadListe(1)
  } else {
    authError.value = true
    setTimeout(() => { authError.value = false }, 2000)
  }
}

function logout() {
  isAuthenticated.value = false
  currentTab.value = 'saisie'
}

// ==================== TIME ====================
const currentTime = ref('')
function updateTime() {
  currentTime.value = '🕐 ' + new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

// ==================== RÉGIONS ====================
const regions = [
  'ABIDJAN NORD', 'ABIDJAN SUD', 'ABIDJAN EST', 'ABIDJAN OUEST',
  'AGNEBY-TIASSA ET ME', 'BAGOUE', 'BELIER', 'BERE', 'BAFING', 'BOUNKANI',
  'CAVALLY', 'FOLON', 'GBEKE', 'GBOKLE', 'GOH', 'GONTOUGO',
  'GRANDS-PONTS', "N'ZI", 'HAUT-SASSANDRA', 'IFFOU', 'INDENIE-DJUABLIN',
  'KABADOUGOU', 'LOH-DJIBOUA', 'NAWA', 'MORONOU', 'PORO', 'WORODOUGOU',
  'MARAHOUE', 'TCHOLOGO', 'TONPKI', 'SAN-PEDRO', 'SUD COMOE', 'GUEMON', 'HAMBOL'
]

// ==================== FORM ====================
const isSubmitting = ref(false)
const showSuccessModal = ref(false)
const lastSaved = ref({})

const defaultForm = () => ({
  date_seminaire: new Date().toISOString().split('T')[0],
  region: '',
  ville: '',
  missionne: '',
  presents_seminariste: '',
  presents_formateur: '',
  presents_visiteur: '',
  nombre_cours: '',
  abandons: 0,
  organisation: '',
  discipline: '',
  activites: '',
  incidents: 'Non',
})

const form = ref(defaultForm())

// Total calculé automatiquement
const computedTotal = computed(() => {
  const s = Number(form.value.presents_seminariste) || 0
  const f = Number(form.value.presents_formateur) || 0
  const v = Number(form.value.presents_visiteur) || 0
  const a = Number(form.value.abandons) || 0
  return Math.max(0, s + f + v - a)
})

function updatePresences() {
  // Déclenche la réactivité, computedTotal se met à jour automatiquement
}

const scoreDetails = ref([])
const computedScore = ref(0)

function calcScore(org, dis, act, inc) {
  let s = 0
  const details = []
  if (org === 'Bonne') { s += 2; details.push('+2 Organisation') }
  else if (org === 'Moyenne') { s += 1; details.push('+1 Organisation') }
  else if (org) details.push('+0 Organisation')
  if (dis === 'Bonne') { s += 2; details.push('+2 Discipline') }
  else if (dis === 'Moyenne') { s += 1; details.push('+1 Discipline') }
  else if (dis) details.push('+0 Discipline')
  if (act === 'Complètes') { s += 2; details.push('+2 Activités') }
  else if (act === 'Partielles') { s += 1; details.push('+1 Activités') }
  else if (act) details.push('+0 Activités')
  if (inc === 'Oui') { s -= 2; details.push('−2 Incidents') }
  return { score: s, details }
}

function updateScore() {
  const { score, details } = calcScore(form.value.organisation, form.value.discipline, form.value.activites, form.value.incidents)
  computedScore.value = score
  scoreDetails.value = details
}

function resetForm() {
  form.value = defaultForm()
  computedScore.value = 0
  scoreDetails.value = []
}

async function submitForm() {
  const f = form.value
  if (!f.date_seminaire || !f.region || !f.ville || !f.missionne) {
    showToast('⚠️ Veuillez remplir tous les champs obligatoires', true); return
  }
  if (f.presents_seminariste === '' || f.presents_formateur === '') {
    showToast('⚠️ Veuillez saisir le nombre de séminaristes et formateurs', true); return
  }
  if (!f.organisation || !f.discipline || !f.activites) {
    showToast('⚠️ Veuillez sélectionner Organisation, Discipline et Activités', true); return
  }

  isSubmitting.value = true
  try {
    const total = computedTotal.value
    const res = await axios.post(`${API_URL}?action=create`, {
      date_seminaire: f.date_seminaire,
      region: f.region,
      ville: f.ville,
      missionne: f.missionne,
      presents_seminariste: Number(f.presents_seminariste) || 0,
      presents_formateur: Number(f.presents_formateur) || 0,
      presents_visiteur: Number(f.presents_visiteur) || 0,
      nombre_cours: Number(f.nombre_cours) || 0,
      abandons: Number(f.abandons) || 0,
      total_cumule: total,
      organisation: f.organisation,
      discipline: f.discipline,
      activites: f.activites,
      incidents: f.incidents,
    })

    if (!res.data.success) throw new Error(res.data.error || 'Erreur serveur')

    lastSaved.value = {
      region: f.region,
      ville: f.ville,
      missionne: f.missionne,
      presents_seminariste: f.presents_seminariste,
      presents_formateur: f.presents_formateur,
      presents_visiteur: f.presents_visiteur,
      nombre_cours: f.nombre_cours,
      total_cumule: total,
      date: f.date_seminaire,
      score: computedScore.value,
    }

    resetForm()
    showSuccessModal.value = true
  } catch (err) {
    showToast('⚠️ ' + (err.response?.data?.error || err.message), true)
  } finally {
    isSubmitting.value = false
  }
}

// ==================== DASHBOARD ====================
const stats = ref({})
const loadingStats = ref(false)

async function loadDashboard() {
  loadingStats.value = true
  try {
    const res = await axios.get(`${API_URL}?action=stats&rand=${Math.random()}`)
    if (res.data.success) {
      stats.value = res.data.data
    }
  } catch (err) {
    showToast('Erreur chargement stats', true)
  } finally {
    loadingStats.value = false
  }
}

function openRegion(region) {
  selectedRegion.value = region
  currentTab.value = 'regions'
  loadRegionDetail()
}

// ==================== HISTORIQUE ====================
const listeData = ref([])
const listeTotal = ref(0)
const listePages = ref(0)
const listePage = ref(1)
const loadingListe = ref(false)
const searchQuery = ref('')
const filterRegion = ref('')

async function loadListe(page = 1) {
  listePage.value = page
  loadingListe.value = true
  try {
    const params = { action: 'seminaires', page, limit: 20 }
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

async function deleteSem(id) {
  if (!confirm('Supprimer ce séminaire ? Cette action est irréversible.')) return
  try {
    const res = await axios.delete(`${API_URL}?action=delete`, { data: { id } })
    if (!res.data.success) throw new Error(res.data.error)
    showToast('🗑️ Séminaire supprimé')
    loadListe(listePage.value)
    if (isAuthenticated.value) loadDashboard()
  } catch (err) {
    showToast('Erreur: ' + err.message, true)
  }
}

// ==================== RÉGION DETAIL ====================
const selectedRegion = ref('')
const regionData = ref(null)
const loadingRegion = ref(false)

async function loadRegionDetail() {
  if (!selectedRegion.value) { regionData.value = null; return }
  loadingRegion.value = true
  try {
    const res = await axios.get(API_URL, { params: { action: 'stats_region', region: selectedRegion.value } })
    if (res.data.success) regionData.value = res.data.data
  } catch (err) {
    showToast('Erreur chargement région', true)
  } finally {
    loadingRegion.value = false
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
  if (['Bonne', 'Complètes'].includes(v)) return 'badge-good'
  if (['Moyenne', 'Partielles'].includes(v)) return 'badge-mid'
  return 'badge-bad'
}

function scoreColor(v) {
  if (v >= 5) return '#16a34a'
  if (v >= 3) return '#d97706'
  return '#dc2626'
}

function scoreBubbleClass(v) {
  if (v >= 5) return 'bubble-hi'
  if (v >= 3) return 'bubble-mid'
  return 'bubble-low'
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

.seminaires-app {
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(1,132,4,0.04), rgba(251,146,60,0.04));
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
  font-size: 1.5rem; flex-shrink: 0;
}
.logo-text h1 { font-size: 1.6rem; font-weight: 900; color: #fff; line-height: 1.1; }
.logo-text p  { font-size: .85rem; color: rgba(255,255,255,.75); }
.header-right { display: flex; align-items: center; gap: 1rem; }
.header-time {
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.3);
  color: #fff; padding: .4rem 1rem;
  border-radius: 0; font-size: .8rem; font-weight: 600;
}
.btn-logout {
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.3);
  color: #fff; padding: .4rem 1rem;
  border-radius: 0; font-size: .8rem; font-weight: 600;
  cursor: pointer; font-family: var(--display);
  transition: background .2s;
}
.btn-logout:hover { background: rgba(255,255,255,.25); }

/* ===== TABS ===== */
.tabs-bar {
  background: white;
  border-bottom: 2px solid var(--line);
  display: flex; gap: .25rem;
  padding: .5rem 2rem 0;
  overflow-x: auto; scrollbar-width: none;
}
.tabs-bar::-webkit-scrollbar { display: none; }
.tab-btn {
  padding: .6rem 1.25rem;
  border-radius: 0;
  font-weight: 700; font-size: .85rem;
  cursor: pointer; border: none;
  background: transparent; color: var(--muted);
  transition: all .2s; white-space: nowrap;
  font-family: var(--display);
}
.tab-btn.active { background: var(--green); color: white; }
.tab-btn:hover:not(.active) { background: var(--surface); color: #111; }

/* ===== PAGE CONTENT ===== */
.page-content { padding-bottom: 3rem; }
.form-container {
  max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem;
}

/* ===== FORM CARD ===== */
.form-card {
  background: white;
  border-radius: 0;
  box-shadow: var(--shadow);
  padding: 2.5rem;
}
.form-card-title {
  display: flex; align-items: center; gap: .75rem;
  font-size: 1.6rem; font-weight: 900; color: var(--ink);
  margin-bottom: 2rem;
  padding-bottom: 1.25rem;
  border-bottom: 2px solid var(--line);
}
.form-card-title svg { color: var(--green); }

.form-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}
.form-field { display: flex; flex-direction: column; gap: .5rem; }
.form-field.fullwidth { grid-column: 1 / -1; }
.form-field label { font-weight: 700; color: var(--ink-soft); font-size: .9rem; }
.section-label { font-weight: 900; color: var(--ink); font-size: 1rem; }
.req { color: #dc2626; }

.form-field input,
.form-field select {
  padding: .8rem 1rem;
  border: 2px solid var(--line);
  border-radius: 0;
  font-size: 1rem;
  font-family: var(--display);
  transition: all .2s;
  background: white; color: #111;
}
.form-field input:focus,
.form-field select:focus {
  outline: none; border-color: var(--green);
  box-shadow: 0 0 0 4px rgba(1,132,4,.1);
}

/* ===== PRÉSENCES ===== */
.presence-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}
.presence-field { display: flex; flex-direction: column; gap: .4rem; }
.presence-sub-label {
  font-size: .8rem; font-weight: 700; color: var(--muted);
}
.presence-field input {
  padding: .8rem 1rem;
  border: 2px solid var(--line);
  border-radius: 0;
  font-size: 1rem;
  font-family: var(--display);
  transition: all .2s;
  background: white; color: #111;
  width: 100%;
}
.presence-field input:focus {
  outline: none; border-color: var(--green);
  box-shadow: 0 0 0 4px rgba(1,132,4,.1);
}

/* Champ calculé */
.computed-field {
  background: linear-gradient(135deg, rgba(1,132,4,.06), rgba(22,163,74,.04));
  border: 2px solid rgba(1,132,4,.25);
  border-radius: 0;
  padding: .8rem 1rem;
  display: flex; flex-direction: column; gap: .25rem;
}
.computed-value { font-size: 1.6rem; font-weight: 900; color: var(--green); line-height: 1; }
.computed-hint  { font-size: .72rem; color: var(--muted); }

/* Radio */
.radio-row { display: flex; gap: .75rem; flex-wrap: wrap; }
.radio-opt {
  display: flex; align-items: center; gap: .4rem;
  padding: .6rem 1rem;
  border: 2px solid var(--line); border-radius: 0;
  cursor: pointer; font-size: .9rem; font-weight: 600;
  color: var(--muted); transition: all .2s;
  user-select: none;
}
.radio-opt input { display: none; }
.radio-opt small { font-size: .72rem; opacity: .65; }
.radio-opt:hover { border-color: var(--green); color: var(--green); background: rgba(1,132,4,.05); }
.radio-opt.selected { border-color: var(--green); background: rgba(1,132,4,.08); color: var(--green); }

/* Score preview */
.score-preview {
  background: linear-gradient(135deg, #014d02, var(--green));
  border-radius: 0; padding: 1.5rem;
  display: flex; justify-content: space-between; align-items: center;
  color: white;
}
.score-preview-label { font-size: .8rem; font-weight: 600; opacity: .85; margin-bottom: .5rem; }
.score-chips { display: flex; gap: .4rem; flex-wrap: wrap; }
.score-chip {
  background: rgba(255,255,255,.2); border-radius: 0;
  padding: .2rem .6rem; font-size: .75rem; font-weight: 600;
}
.score-big { text-align: right; }
.score-number { font-size: 3rem; font-weight: 900; line-height: 1; }

/* Form actions */
.form-actions {
  display: flex; justify-content: space-between; align-items: center;
  margin-top: 2rem; padding-top: 1.5rem;
  border-top: 2px solid var(--line);
}

/* Buttons */
.btn {
  padding: .75rem 1.75rem; border-radius: 0;
  font-size: 1rem; font-weight: 700; border: none;
  cursor: pointer; font-family: var(--display);
  transition: all .25s;
  display: inline-flex; align-items: center; gap: .5rem;
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
  width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,.3);
  border-top-color: white; border-radius: 50%;
  animation: spin .8s linear infinite;
}

/* ===== AUTH GATE ===== */
.auth-gate {
  display: flex; justify-content: center; align-items: center;
  min-height: 70vh; padding: 2rem;
}
.auth-card {
  background: white; border-radius: 0;
  box-shadow: var(--shadow);
  padding: 3rem 2.5rem; max-width: 420px; width: 100%;
  text-align: center;
}
.auth-icon { font-size: 4rem; margin-bottom: 1rem; }
.auth-card h2 { font-size: 1.8rem; font-weight: 900; color: var(--ink); margin-bottom: .5rem; }
.auth-card p  { color: var(--muted); font-size: 1rem; margin-bottom: 1.5rem; }
.auth-field { margin-bottom: 1.25rem; }
.auth-field input {
  width: 100%; padding: .9rem 1.2rem;
  border: 2px solid var(--line); border-radius: 0;
  font-size: 1.1rem; font-family: var(--display);
  text-align: center; transition: all .2s;
}
.auth-field input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(1,132,4,.1); }
.auth-field input.error { border-color: #dc2626; animation: shake .3s ease; }
.auth-error { color: #dc2626; font-size: .85rem; margin-top: .5rem; font-weight: 600; }

/* ===== STATS GRID ===== */
.stats-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap: 1rem; margin-bottom: 1.5rem;
}
.stat-card {
  background: white; border: 1px solid var(--line);
  border-radius: 0; padding: 1.2rem 1.4rem;
  position: relative; overflow: hidden;
  box-shadow: var(--shadow);
}
.stat-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, #014d02, var(--green-2));
}
.stat-label { font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; }
.stat-val   { font-size: 2rem; font-weight: 900; color: #111; line-height: 1.2; margin: .2rem 0 .1rem; }
.stat-sub   { font-size: .75rem; color: var(--muted); }

/* Alert */
.alert-box {
  background: var(--gold-mist); border: 1px solid #fed7aa;
  border-left: 4px solid var(--gold); border-radius: 0;
  padding: 1rem 1.25rem; margin-bottom: 1.25rem;
  display: flex; align-items: flex-start; gap: .75rem;
}
.alert-icon { font-size: 1.2rem; flex-shrink: 0; }
.alert-title { font-weight: 700; color: #78350f; font-size: .88rem; margin-bottom: .25rem; }
.alert-text  { font-size: .82rem; color: #92400e; }

/* Section header */
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.section-title { font-size: 1.1rem; font-weight: 900; }
.section-sub   { font-size: .78rem; color: var(--muted); }

/* Ranking */
.ranking-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
  gap: .85rem; margin-bottom: 1.5rem;
}
.region-card {
  background: white; border: 1px solid var(--line);
  border-radius: 0; padding: 1rem 1.25rem;
  display: flex; align-items: center; gap: .85rem;
  cursor: pointer; transition: all .2s;
  box-shadow: var(--shadow);
}
.region-card:hover {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(1,132,4,.1);
  transform: translateY(-2px);
}
.rank-num { font-size: 1.25rem; font-weight: 900; color: var(--muted); min-width: 24px; text-align: center; }
.rank-num.top3 { color: var(--green); }
.region-info { flex: 1; min-width: 0; }
.region-name { font-weight: 700; font-size: .9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.region-sub  { font-size: .73rem; color: var(--muted); margin-top: .15rem; }
.progress-bar { height: 5px; background: var(--line); border-radius: 0; overflow: hidden; margin-top: .5rem; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--green), var(--green-2)); border-radius: 0; transition: width .6s; }
.region-score { text-align: right; flex-shrink: 0; }
.region-score-val   { font-size: 1.5rem; font-weight: 900; }
.region-score-label { font-size: .68rem; color: var(--muted); }

/* ===== FILTER BAR ===== */
.filter-bar {
  display: flex; align-items: center; gap: .75rem;
  margin-bottom: 1.25rem; flex-wrap: wrap;
}
.filter-input, .filter-select {
  padding: .55rem .95rem;
  border: 2px solid var(--line); border-radius: 0;
  font-size: .9rem; font-family: var(--display);
  background: white; color: #111; transition: border-color .2s;
}
.filter-input  { flex: 1; max-width: 300px; }
.filter-select.wide { flex: 1; max-width: 400px; }
.filter-input:focus, .filter-select:focus { outline: none; border-color: var(--green); }
.filter-label  { font-size: .9rem; font-weight: 700; white-space: nowrap; }
.filter-count  { font-size: .8rem; color: var(--muted); margin-left: auto; }

/* ===== TABLE ===== */
.table-card {
  background: white; border: 1px solid var(--line);
  border-radius: 0; overflow: hidden;
  box-shadow: var(--shadow);
  margin-bottom: 1.25rem;
}
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .83rem; }
th {
  background: var(--surface); padding: .65rem 1rem;
  text-align: left; font-weight: 700; font-size: .72rem;
  text-transform: uppercase; letter-spacing: .04em;
  color: var(--muted); border-bottom: 1px solid var(--line); white-space: nowrap;
}
td { padding: .65rem 1rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--surface); }
.tc  { text-align: center; }
.nowrap { white-space: nowrap; }
.bold   { font-weight: 700; }
.green  { color: var(--green); }
.red    { color: #dc2626; }
.region-cell { font-weight: 700; font-size: .78rem; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Badges */
.badge {
  display: inline-flex; align-items: center;
  padding: .2rem .65rem; border-radius: 0;
  font-size: .72rem; font-weight: 700; white-space: nowrap;
}
.badge-good { background: var(--green-mist); color: var(--green); }
.badge-mid  { background: var(--gold-mist); color: #854d0e; }
.badge-bad  { background: #fee2e2; color: #991b1b; }

/* Score bubble */
.score-bubble {
  display: inline-flex; align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: 50%;
  font-weight: 900; font-size: .9rem; margin: auto;
}
.bubble-hi  { background: var(--green-mist); color: var(--green); }
.bubble-mid { background: var(--gold-mist); color: #854d0e; }
.bubble-low { background: #fee2e2; color: #991b1b; }

.btn-delete {
  background: none; border: none; cursor: pointer;
  font-size: 1.1rem; opacity: .6; transition: opacity .2s; padding: .3rem;
}
.btn-delete:hover { opacity: 1; }

/* ===== LOADING / EMPTY ===== */
.loading-block { text-align: center; padding: 3rem; color: var(--muted); }
.empty-block   { text-align: center; padding: 4rem 2rem; color: var(--muted); }
.empty-icon    { font-size: 3rem; margin-bottom: .75rem; }
.empty-block p { font-size: .95rem; }
.spinner {
  width: 32px; height: 32px;
  border: 3px solid var(--line); border-top-color: var(--green);
  border-radius: 50%; animation: spin .7s linear infinite;
  margin: 0 auto .75rem;
}

/* ===== PAGINATION ===== */
.pagination { display: flex; align-items: center; justify-content: center; gap: .4rem; margin-top: 1rem; }
.page-btn {
  width: 36px; height: 36px; border-radius: 0;
  border: 1px solid var(--line); background: white;
  cursor: pointer; font-size: .85rem; font-weight: 700;
  font-family: var(--display); color: var(--muted);
  transition: all .2s; display: flex; align-items: center; justify-content: center;
}
.page-btn.active { background: var(--green); color: white; border-color: var(--green); }
.page-btn:hover:not(.active):not(:disabled) { background: #f3f4f6; color: #111; }
.page-btn:disabled { opacity: .4; cursor: not-allowed; }

/* ===== MODAL ===== */
.modal-overlay {
  position: fixed; inset: 0; z-index: 9000;
  background: rgba(0,0,0,.5);
  display: flex; align-items: center; justify-content: center;
  padding: 1.5rem;
  backdrop-filter: blur(4px);
}
.modal-card {
  background: white; border-radius: 0;
  box-shadow: var(--shadow);
  padding: 3rem 2.5rem; max-width: 460px; width: 100%;
  text-align: center;
}
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

/* ===== TOAST ===== */
.toast {
  position: fixed; bottom: 2rem; right: 2rem; z-index: 9999;
  background: var(--green); color: white;
  padding: .9rem 1.6rem; border-radius: 0;
  font-weight: 700; font-size: .95rem;
  box-shadow: var(--shadow);
  max-width: 340px;
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
  .presence-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .header-wrapper { flex-direction: column; gap: 1rem; padding: 1rem; }
  .form-card { padding: 1.5rem 1.25rem; }
  .form-grid { grid-template-columns: 1fr; }
  .form-field.fullwidth { grid-column: 1; }
  .presence-grid { grid-template-columns: repeat(2, 1fr); }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .ranking-grid { grid-template-columns: 1fr; }
  .tabs-bar { padding: .4rem 1rem 0; }
  .tab-btn { font-size: .78rem; padding: .5rem .85rem; }
  .score-preview { flex-direction: column; gap: 1rem; text-align: center; }
  .score-big { text-align: center; }
  .form-actions { flex-direction: column; gap: .75rem; }
  .form-actions .btn { width: 100%; justify-content: center; }
  .modal-card { padding: 2rem 1.5rem; }
}

@media (prefers-reduced-motion: reduce) {
  * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
}
</style>