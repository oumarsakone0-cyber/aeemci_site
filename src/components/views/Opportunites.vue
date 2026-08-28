<template>
  <div class="opportunities-app">
    <!-- Header -->
    <header class="app-header">

    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="container">
        <!-- Filters Section -->
        <section class="filters-section">
          <h2 class="section-title">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
              <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
            </svg>
            Filtrer les opportunités
          </h2>
          <div class="filters-grid">
            <div class="filter-group">
              <label>Type</label>
              <select v-model="filters.type" @change="fetchOpportunities">
                <option value="">Tous</option>
                <option value="emploi">Emploi</option>
                <option value="stage">Stage</option>
                <option value="alternance">Alternance</option>
                <option value="freelance">Freelance</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Catégorie</label>
              <select v-model="filters.category" @change="switchCategory">
                <option value="emplois">Emplois / Stages</option>
                <option value="bourses">Bourses</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Rechercher</label>
              <input v-model="searchQuery" type="text" placeholder="Titre, entreprise..." @input="debounceSearch">
            </div>
          </div>
        </section>

        <!-- Tabs -->
        <div class="tabs-nav">
          <button 
            :class="['tab-btn', { active: activeTab === 'emplois' }]" 
            @click="switchTab('emplois')"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
            </svg>
            Emplois & Stages
          </button>
          <button 
            :class="['tab-btn', { active: activeTab === 'bourses' }]" 
            @click="switchTab('bourses')"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
            </svg>
            Bourses
          </button>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="loading-state">
          <div class="spinner"></div>
          <p>Chargement des opportunités...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error-state">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <p>{{ error }}</p>
          <button @click="fetchOpportunities" class="retry-btn">Réessayer</button>
        </div>

        <!-- Two Columns Layout -->
        <div v-else class="two-columns-layout">
          <!-- Left Column: List -->
          <div class="list-column">
            <!-- Emplois List -->
            <section v-if="activeTab === 'emplois'" class="opportunities-list">
              <div v-if="paginatedEmplois.length === 0" class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
                </svg>
                <p>Aucune opportunité d'emploi disponible pour le moment</p>
              </div>
              
              <!-- Nouvelle carte emploi enrichie -->
              <article 
                v-for="emploi in paginatedEmplois" 
                :key="emploi.id" 
                :class="['opportunity-card', { selected: selectedItem?.id === emploi.id }]"
                @click="selectItem(emploi, 'emploi')"
              >
                <!-- Card Header with badges -->
                <div class="card-header">
                  <div class="badges-row">
                    <span :class="['type-badge', `type-${emploi.type_opportunite}`]">
                      {{ formatType(emploi.type_opportunite) }}
                    </span>
                    <span v-if="emploi.type_contrat" class="contract-badge">
                      {{ emploi.type_contrat }}
                    </span>
                  </div>
                  <div class="header-right">
                    <span v-if="emploi.priorite === 'haute'" class="priority-star">⭐</span>
                    <span v-if="emploi.jours_restants !== null" :class="['deadline-badge', { urgent: emploi.jours_restants <= 7, warning: emploi.jours_restants > 7 && emploi.jours_restants <= 14 }]">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                      </svg>
                      {{ emploi.jours_restants }} jours
                    </span>
                  </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                  <h3 class="card-title">{{ emploi.titre_poste }}</h3>
                  
                  <div class="company-row">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
                    </svg>
                    <span class="company-name">{{ emploi.entreprise }}</span>
                  </div>

                  <!-- Info Grid -->
                  <div class="info-grid">
                    <div class="info-item">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                      <span>{{ emploi.localisation }}<span v-if="emploi.pays">, {{ emploi.pays }}</span></span>
                    </div>
                    <div v-if="emploi.niveau_etude" class="info-item">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                      </svg>
                      <span>{{ emploi.niveau_etude }}</span>
                    </div>
                    <div v-if="emploi.experience_requise" class="info-item">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
                      </svg>
                      <span>{{ emploi.experience_requise }}</span>
                    </div>
                  </div>

                  <!-- Salary if available -->
                  <div v-if="emploi.salaire_min || emploi.salaire_max" class="salary-preview">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                    <span>
                      {{ emploi.salaire_min ? formatSalary(emploi.salaire_min) : '' }}
                      {{ emploi.salaire_min && emploi.salaire_max ? ' - ' : '' }}
                      {{ emploi.salaire_max ? formatSalary(emploi.salaire_max) : '' }}
                      {{ emploi.devise || 'FCFA' }}
                    </span>
                  </div>

                  <!-- Skills preview -->
                  <div v-if="emploi.competences_requises?.length" class="skills-preview">
                    <span 
                      v-for="(skill, idx) in emploi.competences_requises.slice(0, 3)" 
                      :key="idx" 
                      class="skill-chip"
                    >
                      {{ skill }}
                    </span>
                    <span v-if="emploi.competences_requises.length > 3" class="more-skills">
                      +{{ emploi.competences_requises.length - 3 }}
                    </span>
                  </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                  <div class="footer-left">
                    <span class="views-count">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                      </svg>
                      {{ emploi.nombre_vues || 0 }}
                    </span>
                    <span class="publish-date">
                      Publié {{ formatRelativeDate(emploi.date_publication) }}
                    </span>
                  </div>
                  <button class="view-details-btn">
                    Voir détails
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                    </svg>
                  </button>
                </div>
              </article>

              <!-- Pagination -->
              <div v-if="totalPagesEmplois > 1" class="pagination">
                <button 
                  class="page-btn" 
                  :disabled="currentPage === 1" 
                  @click="changePage(currentPage - 1)"
                >
                  ←
                </button>
                <span class="page-info">{{ currentPage }} / {{ totalPagesEmplois }}</span>
                <button 
                  class="page-btn" 
                  :disabled="currentPage === totalPagesEmplois" 
                  @click="changePage(currentPage + 1)"
                >
                  →
                </button>
              </div>
            </section>

            <!-- Bourses List -->
            <section v-else-if="activeTab === 'bourses'" class="opportunities-list">
              <div v-if="paginatedBourses.length === 0" class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                </svg>
                <p>Aucune bourse disponible pour le moment</p>
              </div>
              
              <!-- Nouvelle carte bourse enrichie -->
              <article 
                v-for="bourse in paginatedBourses" 
                :key="bourse.id" 
                :class="['opportunity-card bourse-card', { selected: selectedItem?.id === bourse.id }]"
                @click="selectItem(bourse, 'bourse')"
              >
                <!-- Card Header with badges -->
                <div class="card-header">
                  <div class="badges-row">
                    <span :class="['type-badge', `type-${bourse.type_bourse}`]">
                      {{ formatTypeBourse(bourse.type_bourse) }}
                    </span>
                    <span v-if="bourse.niveau_etude" class="level-badge">
                      {{ bourse.niveau_etude }}
                    </span>
                  </div>
                  <div class="header-right">
                    <span v-if="bourse.priorite === 'haute'" class="priority-star">⭐</span>
                    <span v-if="bourse.jours_restants !== null" :class="['deadline-badge', { urgent: bourse.jours_restants <= 7, warning: bourse.jours_restants > 7 && bourse.jours_restants <= 14 }]">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                      </svg>
                      {{ bourse.jours_restants }} jours
                    </span>
                  </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                  <h3 class="card-title">{{ bourse.titre }}</h3>
                  
                  <div class="company-row">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                    </svg>
                    <span class="company-name">{{ bourse.organisme }}</span>
                  </div>

                  <!-- Info Grid -->
                  <div class="info-grid">
                    <div class="info-item">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                      </svg>
                      <span>{{ bourse.pays }}</span>
                    </div>
                    <div v-if="bourse.duree" class="info-item">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                      </svg>
                      <span>{{ bourse.duree }}</span>
                    </div>
                  </div>

                  <!-- Amount if available -->
                  <div v-if="bourse.montant" class="salary-preview bourse-amount">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                    <span>{{ bourse.montant }}</span>
                  </div>

                  <!-- Domains preview -->
                  <div v-if="bourse.domaines?.length" class="skills-preview">
                    <span 
                      v-for="(domaine, idx) in bourse.domaines.slice(0, 3)" 
                      :key="idx" 
                      class="skill-chip domain-chip"
                    >
                      {{ domaine }}
                    </span>
                    <span v-if="bourse.domaines.length > 3" class="more-skills">
                      +{{ bourse.domaines.length - 3 }}
                    </span>
                  </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                  <div class="footer-left">
                    <span class="views-count">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                      </svg>
                      {{ bourse.nombre_vues || 0 }}
                    </span>
                    <span class="publish-date">
                      Publié {{ formatRelativeDate(bourse.date_publication) }}
                    </span>
                  </div>
                  <button class="view-details-btn">
                    Voir détails
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                    </svg>
                  </button>
                </div>
              </article>

              <!-- Pagination -->
              <div v-if="totalPagesBourses > 1" class="pagination">
                <button 
                  class="page-btn" 
                  :disabled="currentPage === 1" 
                  @click="changePage(currentPage - 1)"
                >
                  ←
                </button>
                <span class="page-info">{{ currentPage }} / {{ totalPagesBourses }}</span>
                <button 
                  class="page-btn" 
                  :disabled="currentPage === totalPagesBourses" 
                  @click="changePage(currentPage + 1)"
                >
                  →
                </button>
              </div>
            </section>
          </div>

          <!-- Right Column: Detail (unchanged) -->
          <div class="detail-column">
            <div v-if="!selectedItem" class="no-selection">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
              </svg>
              <p>Sélectionnez une opportunité pour voir les détails</p>
            </div>

            <!-- Emploi Detail -->
            <div v-else-if="selectedType === 'emploi'" class="detail-content">
              <div class="detail-header">
                <span :class="['badge', `badge-${selectedItem.type_opportunite}`]">
                  {{ formatType(selectedItem.type_opportunite) }}
                </span>
                <h2>{{ selectedItem.titre_poste }}</h2>
                <p class="detail-company">{{ selectedItem.entreprise }}</p>
              </div>
              
              <div class="detail-body">
                <div class="info-row">
                  <div class="info-item">
                    <span class="info-label">📍 Localisation</span>
                    <span class="info-value">{{ selectedItem.localisation }}, {{ selectedItem.pays }}</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">📋 Type de contrat</span>
                    <span class="info-value">{{ selectedItem.type_contrat || 'Non précisé' }}</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">🎓 Niveau d'étude</span>
                    <span class="info-value">{{ selectedItem.niveau_etude || 'Non précisé' }}</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">💼 Expérience</span>
                    <span class="info-value">{{ selectedItem.experience_requise || 'Non précisé' }}</span>
                  </div>
                </div>
                
                <div v-if="selectedItem.salaire_min || selectedItem.salaire_max" class="salary-box">
                  <span class="salary-label">💰 Salaire</span>
                  <span class="salary-value">
                    {{ selectedItem.salaire_min ? formatSalary(selectedItem.salaire_min) : '' }}
                    {{ selectedItem.salaire_min && selectedItem.salaire_max ? ' - ' : '' }}
                    {{ selectedItem.salaire_max ? formatSalary(selectedItem.salaire_max) : '' }}
                    {{ selectedItem.devise || 'FCFA' }}
                  </span>
                </div>

                <div class="detail-section">
                  <h3>📝 Description du poste</h3>
                  <p>{{ selectedItem.description_poste }}</p>
                </div>

                <div v-if="selectedItem.missions" class="detail-section">
                  <h3>🎯 Missions</h3>
                  <p>{{ selectedItem.missions }}</p>
                </div>

                <div v-if="selectedItem.profil_recherche" class="detail-section">
                  <h3>👤 Profil recherché</h3>
                  <p>{{ selectedItem.profil_recherche }}</p>
                </div>

                <div v-if="selectedItem.competences_requises?.length" class="detail-section">
                  <h3>🛠️ Compétences requises</h3>
                  <div class="skills-list">
                    <span v-for="(skill, idx) in selectedItem.competences_requises" :key="idx" class="skill-tag">
                      {{ skill }}
                    </span>
                  </div>
                </div>

                <div v-if="selectedItem.avantages" class="detail-section">
                  <h3>✨ Avantages</h3>
                  <p>{{ selectedItem.avantages }}</p>
                </div>

                <div class="detail-section deadline-section">
                  <h3>⏰ Date limite de candidature</h3>
                  <p class="deadline-date">{{ formatDate(selectedItem.date_limite) }}</p>
                </div>

                <div class="detail-stats">
                  <span class="stat">👁️ {{ selectedItem.nombre_vues || 0 }} vues</span>
                  <span class="stat">📅 Publié le {{ formatDate(selectedItem.date_publication) }}</span>
                </div>
              </div>

              <div class="detail-footer">
                <a v-if="selectedItem.lien_candidature" :href="selectedItem.lien_candidature" target="_blank" class="btn btn-primary">
                  Postuler maintenant →
                </a>
                <a v-else-if="selectedItem.email_candidature" :href="`mailto:${selectedItem.email_candidature}`" class="btn btn-primary">
                  Envoyer ma candidature →
                </a>
                <p v-if="selectedItem.contact_recruteur" class="contact-info">
                  📞 Contact: {{ selectedItem.contact_recruteur }}
                </p>
              </div>
            </div>

            <!-- Bourse Detail -->
            <div v-else-if="selectedType === 'bourse'" class="detail-content">
              <div class="detail-header">
                <span :class="['badge', `badge-${selectedItem.type_bourse}`]">
                  {{ formatTypeBourse(selectedItem.type_bourse) }}
                </span>
                <h2>{{ selectedItem.titre }}</h2>
                <p class="detail-company">{{ selectedItem.organisme }}</p>
              </div>
              
              <div class="detail-body">
                <div class="info-row">
                  <div class="info-item">
                    <span class="info-label">📍 Pays</span>
                    <span class="info-value">{{ selectedItem.pays }}</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">🎓 Niveau d'étude</span>
                    <span class="info-value">{{ selectedItem.niveau_etude }}</span>
                  </div>
                  <div class="info-item">
                    <span class="info-label">⏱️ Durée</span>
                    <span class="info-value">{{ selectedItem.duree || 'Non précisé' }}</span>
                  </div>
                </div>

                <div v-if="selectedItem.montant" class="salary-box">
                  <span class="salary-label">💰 Montant de la bourse</span>
                  <span class="salary-value">{{ selectedItem.montant }}</span>
                </div>

                <div v-if="selectedItem.domaines?.length" class="detail-section">
                  <h3>📚 Domaines d'étude</h3>
                  <div class="skills-list">
                    <span v-for="(domaine, idx) in selectedItem.domaines" :key="idx" class="skill-tag">
                      {{ domaine }}
                    </span>
                  </div>
                </div>

                <div class="detail-section">
                  <h3>📝 Description</h3>
                  <p>{{ selectedItem.description }}</p>
                </div>

                <div v-if="selectedItem.criteres_eligibilite" class="detail-section">
                  <h3>✅ Critères d'éligibilité</h3>
                  <p>{{ selectedItem.criteres_eligibilite }}</p>
                </div>

                <div v-if="selectedItem.documents_requis?.length" class="detail-section">
                  <h3>📄 Documents requis</h3>
                  <ul class="documents-list">
                    <li v-for="(doc, idx) in selectedItem.documents_requis" :key="idx">{{ doc }}</li>
                  </ul>
                </div>

                <div class="detail-section deadline-section">
                  <h3>⏰ Dates importantes</h3>
                  <p><strong>Ouverture:</strong> {{ formatDate(selectedItem.date_ouverture) }}</p>
                  <p class="deadline-date"><strong>Date limite:</strong> {{ formatDate(selectedItem.date_limite) }}</p>
                </div>

                <div class="detail-stats">
                  <span class="stat">👁️ {{ selectedItem.nombre_vues || 0 }} vues</span>
                  <span class="stat">📅 Publié le {{ formatDate(selectedItem.date_publication) }}</span>
                </div>
              </div>

              <div class="detail-footer">
                <a v-if="selectedItem.lien_candidature" :href="selectedItem.lien_candidature" target="_blank" class="btn btn-primary">
                  Postuler maintenant →
                </a>
                <p v-if="selectedItem.email_organisme" class="contact-info">
                  📧 {{ selectedItem.email_organisme }}
                </p>
                <p v-if="selectedItem.contact_organisme" class="contact-info">
                  📞 {{ selectedItem.contact_organisme }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const API_BASE = 'https://api.aeemci-ce.ci/senasip_api.php'

// State
const emplois = ref([])
const bourses = ref([])
const isLoading = ref(false)
const error = ref(null)
const activeTab = ref('emplois')
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

// Selected item for detail view
const selectedItem = ref(null)
const selectedType = ref('')

// Filters
const filters = ref({
  type: '',
  category: 'emplois'
})

// Helper: Check if date is not expired
const isNotExpired = (dateStr) => {
  if (!dateStr) return true
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  const date = new Date(dateStr)
  return date >= today
}

// Computed
const totalOpportunites = computed(() => {
  const validEmplois = emplois.value.filter(e => isNotExpired(e.date_limite))
  const validBourses = bourses.value.filter(b => isNotExpired(b.date_limite))
  return validEmplois.length + validBourses.length
})

const filteredEmplois = computed(() => {
  let results = emplois.value.filter(e => isNotExpired(e.date_limite))
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    results = results.filter(e => 
      e.titre_poste?.toLowerCase().includes(query) ||
      e.entreprise?.toLowerCase().includes(query) ||
      e.localisation?.toLowerCase().includes(query)
    )
  }
  if (filters.value.type) {
    results = results.filter(e => e.type_opportunite === filters.value.type)
  }
  return results
})

const filteredBourses = computed(() => {
  let results = bourses.value.filter(b => isNotExpired(b.date_limite))
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    results = results.filter(b => 
      b.titre?.toLowerCase().includes(query) ||
      b.organisme?.toLowerCase().includes(query) ||
      b.pays?.toLowerCase().includes(query)
    )
  }
  return results
})

const totalPagesEmplois = computed(() => Math.ceil(filteredEmplois.value.length / itemsPerPage))
const totalPagesBourses = computed(() => Math.ceil(filteredBourses.value.length / itemsPerPage))

const paginatedEmplois = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredEmplois.value.slice(start, start + itemsPerPage)
})

const paginatedBourses = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredBourses.value.slice(start, start + itemsPerPage)
})

// Methods
const formatType = (type) => {
  const types = {
    emploi: 'Emploi',
    stage: 'Stage',
    alternance: 'Alternance',
    freelance: 'Freelance'
  }
  return types[type] || type
}

const formatTypeBourse = (type) => {
  const types = {
    complete: 'Bourse complète',
    partielle: 'Bourse partielle',
    excellence: 'Excellence',
    recherche: 'Recherche'
  }
  return types[type] || type
}

const formatDate = (dateStr) => {
  if (!dateStr) return 'Non précisé'
  return new Date(dateStr).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const formatRelativeDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  const now = new Date()
  const diffTime = now - date
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffDays === 0) return "aujourd'hui"
  if (diffDays === 1) return 'hier'
  if (diffDays < 7) return `il y a ${diffDays} jours`
  if (diffDays < 30) return `il y a ${Math.floor(diffDays / 7)} sem.`
  return `il y a ${Math.floor(diffDays / 30)} mois`
}

const formatSalary = (amount) => {
  return new Intl.NumberFormat('fr-FR').format(amount)
}

const selectItem = (item, type) => {
  selectedItem.value = item
  selectedType.value = type
}

const switchTab = (tab) => {
  activeTab.value = tab
  currentPage.value = 1
  selectedItem.value = null
  selectedType.value = ''
}

const switchCategory = () => {
  activeTab.value = filters.value.category
  currentPage.value = 1
  selectedItem.value = null
}

const changePage = (page) => {
  currentPage.value = page
}

let debounceTimer = null
const debounceSearch = () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    currentPage.value = 1
  }, 300)
}

const fetchOpportunities = async () => {
  isLoading.value = true
  error.value = null
  
  try {
    // Fetch emplois
    const emploisRes = await fetch(`${API_BASE}?request=emplois&statut=active&limit=50`)
    const emploisData = await emploisRes.json()
    if (emploisData.success) {
      emplois.value = emploisData.data.map(e => ({
        ...e,
        jours_restants: e.date_limite ? Math.ceil((new Date(e.date_limite) - new Date()) / (1000 * 60 * 60 * 24)) : null
      }))
    }

    // Fetch bourses
    const boursesRes = await fetch(`${API_BASE}?request=bourses&statut=active&limit=50`)
    const boursesData = await boursesRes.json()
    if (boursesData.success) {
      bourses.value = boursesData.data.map(b => ({
        ...b,
        jours_restants: b.date_limite ? Math.ceil((new Date(b.date_limite) - new Date()) / (1000 * 60 * 60 * 24)) : null
      }))
    }
  } catch (err) {
    error.value = 'Erreur lors du chargement des opportunités'
    console.error(err)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchOpportunities()
})
</script>

<style scoped>
/* Base styles */
.opportunities-app {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--surface) 0%, var(--line) 100%);
  font-family: var(--display);
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

.main-content {
  padding: 2rem 0;
}

/* Filters Section */
.filters-section {
  background: white;
  border-radius: 0;
  border: 1px solid grey;
  background: var(--green-mist);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--shadow);
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 1rem;
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.filter-group label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--muted);
  margin-bottom: 0.5rem;
}

.filter-group select,
.filter-group input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid var(--line);
  border-radius: 0;
  font-size: 0.95rem;
  transition: all 0.2s;
}

.filter-group select:focus,
.filter-group input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Tabs */
.tabs-nav {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.tab-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.875rem 1.5rem;
  border: none;
  background: white;
  border-radius: 0;
  font-weight: 500;
  color: var(--muted);
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: var(--shadow);
}

.tab-btn:hover {
  background: var(--surface);
}

.tab-btn.active {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
  box-shadow: var(--shadow);
}

/* Loading & Error States */
.loading-state,
.error-state,
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  color: var(--muted);
  text-align: center;
}

.spinner {
  width: 48px;
  height: 48px;
  border: 4px solid var(--line);
  border-top-color: #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0;
  cursor: pointer;
  font-weight: 500;
}

/* Two Columns Layout */
.two-columns-layout {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: 1.5rem;
}

@media (max-width: 1024px) {
  .two-columns-layout {
    grid-template-columns: 1fr;
  }
  
  .detail-column {
    order: -1;
  }
}

/* List Column */
.list-column {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.opportunities-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* Nouveau style pour les cartes d'opportunités enrichies */
.opportunity-card {
  background: white;
  border-radius: 0;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  box-shadow: var(--shadow);
}

.opportunity-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
  border-color: #3b82f6;
}

.opportunity-card.selected {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2), 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 1rem 1.25rem 0;
}

.badges-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.type-badge {
  display: inline-flex;
  padding: 0.35rem 0.75rem;
  border-radius: 0;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.type-emploi { background: #dbeafe; color: #1e40af; }
.type-stage { background: var(--gold-mist); color: #92400e; }
.type-alternance { background: var(--green-mist); color: var(--green); }
.type-freelance { background: #fce7f3; color: #9d174d; }
.type-complete { background: var(--green-mist); color: var(--green); }
.type-partielle { background: #dbeafe; color: #1e40af; }
.type-excellence { background: var(--gold-mist); color: #92400e; }
.type-recherche { background: #e0e7ff; color: #3730a3; }

.contract-badge,
.level-badge {
  display: inline-flex;
  padding: 0.35rem 0.75rem;
  border-radius: 0;
  font-size: 0.7rem;
  font-weight: 500;
  background: var(--surface);
  color: var(--ink-soft);
}

.header-right {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.priority-star {
  font-size: 1rem;
}

.deadline-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.35rem 0.75rem;
  border-radius: 0;
  font-size: 0.75rem;
  font-weight: 600;
  background: var(--surface);
  color: var(--muted);
}

.deadline-badge.warning {
  background: var(--gold-mist);
  color: #92400e;
}

.deadline-badge.urgent {
  background: #fee2e2;
  color: #dc2626;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.card-body {
  padding: 1rem 1.25rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--ink);
  margin: 0 0 0.75rem;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.company-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  color: var(--muted);
}

.company-row svg {
  color: var(--muted);
  flex-shrink: 0;
}

.company-name {
  font-weight: 500;
  color: var(--ink-soft);
}

.info-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem 1.5rem;
  margin-bottom: 1rem;
}

.info-grid .info-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.85rem;
  color: var(--muted);
}

.info-grid .info-item svg {
  color: var(--muted);
  flex-shrink: 0;
}

.salary-preview {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, var(--green-mist) 0%, var(--green-mist) 100%);
  border-radius: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--green);
  margin-bottom: 1rem;
}

.salary-preview svg {
  color: #059669;
}

.bourse-amount {
  background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
  color: #1e40af;
}

.bourse-amount svg {
  color: #3b82f6;
}

.skills-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.skill-chip {
  padding: 0.3rem 0.75rem;
  background: var(--surface);
  border-radius: 0;
  font-size: 0.75rem;
  font-weight: 500;
  color: var(--ink-soft);
  transition: all 0.2s;
}

.skill-chip:hover {
  background: var(--line);
}

.domain-chip {
  background: #eff6ff;
  color: #1e40af;
}

.more-skills {
  padding: 0.3rem 0.75rem;
  background: #e0e7ff;
  border-radius: 0;
  font-size: 0.75rem;
  font-weight: 600;
  color: #4338ca;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  background: var(--surface);
  border-top: 1px solid var(--line);
}

.footer-left {
  display: flex;
  align-items: center;
  gap: 1rem;
  font-size: 0.8rem;
  color: var(--muted);
}

.views-count {
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.publish-date {
  opacity: 0.8;
}

.view-details-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.5rem 1rem;
  background: transparent;
  border: 2px solid #3b82f6;
  border-radius: 0;
  font-size: 0.85rem;
  font-weight: 600;
  color: #3b82f6;
  cursor: pointer;
  transition: all 0.2s;
}

.view-details-btn:hover {
  background: #3b82f6;
  color: white;
}

/* Bourse card specific */
.bourse-card .card-title {
  color: #1e3a5f;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 1.5rem;
  padding: 1rem;
}

.page-btn {
  width: 40px;
  height: 40px;
  border: none;
  background: white;
  border-radius: 0;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: var(--shadow);
}

.page-btn:hover:not(:disabled) {
  background: #3b82f6;
  color: white;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  font-weight: 500;
  color: var(--muted);
}

/* Detail Column */
.detail-column {
  position: sticky;
  top: 1rem;
  height: fit-content;
}

.no-selection {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  background: white;
  border-radius: 0;
  color: var(--muted);
  text-align: center;
  padding: 2rem;
}

.detail-content {
  background: white;
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
}

.detail-header {
  padding: 1.5rem;
  background: linear-gradient(135deg, var(--surface) 0%, var(--surface) 100%);
  border-bottom: 1px solid var(--line);
}

.detail-header h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--ink);
  margin: 0.75rem 0 0.5rem;
}

.detail-company {
  font-size: 1rem;
  color: var(--muted);
  font-weight: 500;
}

.detail-body {
  padding: 1.5rem;
}

.info-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.info-row .info-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.info-label {
  font-size: 0.85rem;
  color: var(--muted);
}

.info-value {
  font-weight: 500;
  color: #1e293b;
}

.salary-box {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  padding: 1rem;
  background: linear-gradient(135deg, var(--green-mist) 0%, var(--green-mist) 100%);
  border-radius: 0;
  margin-bottom: 1.5rem;
}

.salary-label {
  font-size: 0.85rem;
  color: var(--green);
}

.salary-value {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--green);
}

.detail-section {
  margin-bottom: 1.5rem;
}

.detail-section h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.75rem;
}

.detail-section p {
  color: var(--ink-soft);
  line-height: 1.7;
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.skill-tag {
  padding: 0.5rem 1rem;
  background: var(--surface);
  border-radius: 0;
  font-size: 0.875rem;
  color: var(--ink-soft);
}

.documents-list {
  padding-left: 1.25rem;
  color: var(--ink-soft);
}

.documents-list li {
  margin-bottom: 0.5rem;
}

.deadline-section {
  padding: 1rem;
  background: var(--gold-mist);
  border-radius: 0;
}

.deadline-date {
  font-weight: 600;
  color: #92400e;
}

.detail-stats {
  display: flex;
  gap: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid var(--line);
  font-size: 0.875rem;
  color: var(--muted);
}

.detail-footer {
  padding: 1.5rem;
  background: var(--surface);
  border-top: 1px solid var(--line);
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 2rem;
  border-radius: 0;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-primary {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
  box-shadow: var(--shadow);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

.contact-info {
  margin-top: 1rem;
  font-size: 0.9rem;
  color: var(--muted);
}

/* Badge styles */
.badge {
  display: inline-flex;
  padding: 0.35rem 0.75rem;
  border-radius: 0;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-emploi { background: #dbeafe; color: #1e40af; }
.badge-stage { background: var(--gold-mist); color: #92400e; }
.badge-alternance { background: var(--green-mist); color: var(--green); }
.badge-freelance { background: #fce7f3; color: #9d174d; }
.badge-complete { background: var(--green-mist); color: var(--green); }
.badge-partielle { background: #dbeafe; color: #1e40af; }
.badge-excellence { background: var(--gold-mist); color: #92400e; }
.badge-recherche { background: #e0e7ff; color: #3730a3; }

/* Responsive */
@media (max-width: 768px) {
  .info-row {
    grid-template-columns: 1fr;
  }
  
  .card-footer {
    flex-direction: column;
    gap: 1rem;
  }
  
  .footer-left {
    width: 100%;
    justify-content: space-between;
  }
  
  .view-details-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
