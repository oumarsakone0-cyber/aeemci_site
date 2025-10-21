<template>
  <div class="app-container">
    <!-- Header -->
    <header class="app-header">
      <div class="header-content">
        <div class="logo">
          <div class="logo-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
            </svg>
          </div>
          <div class="logo-text">
            <h1>AEEMCI</h1>
            <p>Membres des Commissions</p>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="members-container">
        <!-- Header Section -->
        <div class="members-header">
          <h2 class="members-title">
            <svg class="title-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
            </svg>
            Membres des Commissions
          </h2>
          <p class="members-subtitle">Liste des membres inscrits dans les différentes commissions</p>
        </div>

        <!-- Search and Filters -->
        <div class="search-filters">
          <div class="search-container">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher par nom ou commission..."
              class="search-input"
              :disabled="isLoadingResults"
            />
            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
          </div>
          
          <div class="filter-container">
            <select v-model="selectedCommission" @change="filterMembers" class="commission-filter">
              <option value="">Toutes les commissions</option>
              <option 
                v-for="commission in commissionsDisponibles" 
                :key="commission" 
                :value="commission"
              >
                {{ commission }}
              </option>
            </select>
          </div>
        </div>

        <!-- Stats -->
        <div v-if="!isLoadingResults && !resultError" class="stats-container">
          <div class="stat-card">
            <div class="stat-number">{{ totalMembers }}</div>
            <div class="stat-label">Membres Total</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ filteredResults.length }}</div>
            <div class="stat-label">Résultats</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ uniqueCommissions }}</div>
            <div class="stat-label">Commissions</div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoadingResults" class="loading-state">
          <div class="loading-spinner-large"></div>
          <p>Chargement des membres...</p>
        </div>

        <!-- Error State -->
        <div v-if="resultError" class="error-state">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
          </svg>
          <p>{{ resultError }}</p>
          <button @click="fetchMembresCommissions" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            Réessayer
          </button>
        </div>

        <!-- Members Grid -->
        <div v-if="!isLoadingResults && !resultError" class="members-section">
          <!-- Desktop Table View -->
          <div class="desktop-view">
            <div class="table-container">
              <table class="members-table">
                <thead>
                  <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Commission</th>
                    <th>Contact</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="membre in paginatedResults" :key="membre.id" class="member-row">
                    <td class="photo-cell">
                      <div class="member-photo">
                        <img 
                          v-if="membre.photo_url" 
                          :src="membre.photo_url" 
                          :alt="`Photo de ${membre.prenom} ${membre.nom}`"
                          class="photo-img"
                          @error="handleImageError"
                        />
                        <div v-else class="photo-placeholder">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                          </svg>
                        </div>
                      </div>
                    </td>
                    <td class="name-cell">{{ membre.nom }}</td>
                    <td class="name-cell">{{ membre.prenom }}</td>
                    <td class="commission-cell">
                      <span class="commission-badge">{{ membre.commission }}</span>
                    </td>
                    <td class="contact-cell">{{ membre.contact }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Mobile Card View -->
          <div class="mobile-view">
            <div class="members-grid">
              <div 
                v-for="membre in paginatedResults" 
                :key="membre.id" 
                class="member-card"
              >
                <div class="card-header">
                  <div class="member-photo-large">
                    <img 
                      v-if="membre.photo_url" 
                      :src="membre.photo_url" 
                      :alt="`Photo de ${membre.prenom} ${membre.nom}`"
                      class="photo-img-large"
                      @error="handleImageError"
                    />
                    <div v-else class="photo-placeholder-large">
                      <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                      </svg>
                    </div>
                  </div>
                  <div class="member-info">
                    <h3 class="member-name">{{ membre.prenom }} {{ membre.nom }}</h3>
                    <span class="commission-badge-mobile">{{ membre.commission }}</span>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="contact-info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <span>{{ membre.contact }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- No Results -->
          <div v-if="filteredResults.length === 0 && membresCommissions.length > 0" class="no-results">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <p>Aucun résultat trouvé pour "{{ searchQuery }}"</p>
          </div>

          <div v-if="membresCommissions.length === 0" class="no-results">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
            </svg>
            <p>Aucun membre disponible</p>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="pagination-controls">
            <div class="pagination-buttons">
              <button
                @click="previousPage"
                :disabled="currentPage === 1"
                class="pagination-btn pagination-btn-nav"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Précédent
              </button>

              <div class="pagination-numbers">
                <button
                  v-for="page in visiblePages"
                  :key="page"
                  @click="goToPage(page)"
                  :class="['pagination-btn', { 'pagination-btn-active': page === currentPage }]"
                >
                  {{ page }}
                </button>
              </div>

              <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="pagination-btn pagination-btn-nav"
              >
                Suivant
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                </svg>
              </button>
            </div>

            <div class="items-per-page">
              <label for="itemsPerPage">Éléments par page :</label>
              <select
                id="itemsPerPage"
                v-model="itemsPerPage"
                @change="currentPage = 1"
                class="items-select"
              >
                <option value="6">6</option>
                <option value="12">12</option>
                <option value="24">24</option>
                <option value="48">48</option>
              </select>
            </div>
          </div>

          <!-- Results Info -->
          <div class="results-info">
            <p>
              Affichage de {{ paginationInfo.start }} à {{ paginationInfo.end }}
              sur {{ paginationInfo.total }} membre{{ paginationInfo.total > 1 ? 's' : '' }}
              <span v-if="searchQuery || selectedCommission"> 
                ({{ filteredResults.length }} résultat{{ filteredResults.length > 1 ? 's' : '' }} filtré{{ filteredResults.length > 1 ? 's' : '' }})
              </span>
            </p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

// Configuration API
const API_BASE_URL = 'https://sogetrag.com/api/'

// États pour les résultats
const searchQuery = ref('')
const selectedCommission = ref('')

// Données des résultats
const membresCommissions = ref([])
const isLoadingResults = ref(false)
const resultError = ref('')

// Variables de pagination
const currentPage = ref(1)
const itemsPerPage = ref(12)

// Liste des commissions disponibles
const commissionsDisponibles = [
  'ADMINISTRATION',
  'CADRE DE VIE',
  'COMMUNICATION',
  'FINANCE',
  'FORMATION',
  'INFORMATIQUE',
  'PEPINIERE',
  'PROTOCOLE',
  'RESTAURATION',
  'SANTE',
  'SECURITE',
  'TRANSPORT',
  'MANAGER GENERAL',
  'MANAGER GENERAL ADJOINT',
  'PCO',
  'PCO-A'
]

// Computed properties
const filteredResults = computed(() => {
  let filtered = [...membresCommissions.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(membre =>
      membre.nom.toLowerCase().includes(query) ||
      membre.prenom.toLowerCase().includes(query) ||
      membre.commission.toLowerCase().includes(query)
    )
  }

  if (selectedCommission.value) {
    filtered = filtered.filter(membre => 
      membre.commission === selectedCommission.value
    )
  }

  return filtered
})

const paginatedResults = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage.value
  const endIndex = startIndex + itemsPerPage.value
  return filteredResults.value.slice(startIndex, endIndex)
})

const totalPages = computed(() => 
  Math.ceil(filteredResults.value.length / itemsPerPage.value)
)

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

const paginationInfo = computed(() => {
  const total = filteredResults.value.length
  const startIndex = (currentPage.value - 1) * itemsPerPage.value + 1
  const endIndex = Math.min(currentPage.value * itemsPerPage.value, total)

  return {
    start: total > 0 ? startIndex : 0,
    end: endIndex,
    total: total
  }
})

const totalMembers = computed(() => membresCommissions.value.length)

const uniqueCommissions = computed(() => {
  const commissions = new Set(membresCommissions.value.map(m => m.commission))
  return commissions.size
})

// Fonction pour récupérer les membres des commissions
const fetchMembresCommissions = async () => {
  isLoadingResults.value = true
  resultError.value = ''

  try {
    const response = await axios.get(`${API_BASE_URL}/commission_api.php?action=membres`)

    if (response.data.success) {
      membresCommissions.value = response.data.data || []
    } else {
      throw new Error(response.data.message || 'Erreur lors de la récupération des données')
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des membres:', error)
    resultError.value = 'Erreur lors du chargement des membres. Veuillez réessayer.'
    membresCommissions.value = []
  } finally {
    isLoadingResults.value = false
  }
}

// Fonctions de pagination
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

// Fonction pour filtrer les membres
const filterMembers = () => {
  currentPage.value = 1
}

// Fonction pour gérer les erreurs d'image
const handleImageError = (event) => {
  event.target.style.display = 'none'
  const sibling = event.target.nextElementSibling
  if (sibling) {
    sibling.style.display = 'flex'
  }
}

// Watchers pour réinitialiser la pagination lors d'une recherche
watch([searchQuery, selectedCommission], () => {
  currentPage.value = 1
})

// Lifecycle hooks
onMounted(() => {
  fetchMembresCommissions()
})
</script>

<style scoped>
/* Reset et base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #dcfce7 0%, #f9fafb 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header */
.app-header {
  background: #ffffff;
  border-bottom: 1px solid #e5e7eb;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 2rem;
  display: flex;
  align-items: center;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-icon {
  width: 2.5rem;
  height: 2.5rem;
  background: #16a34a;
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.25rem;
}

.logo-text h1 {
  font-size: 1.5rem;
  font-weight: bold;
  color: #16a34a;
  margin: 0;
}

.logo-text p {
  font-size: 0.875rem;
  color: #4b5563;
  margin: 0;
}

/* Main Content */
.main-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.members-container {
  background: #ffffff;
  border-radius: 0.75rem;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
  overflow: hidden;
}

/* Header Section */
.members-header {
  background: linear-gradient(135deg, #16a34a, #15803d);
  color: white;
  padding: 2rem;
  text-align: center;
}

.members-title {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
}

.title-icon {
  color: white;
}

.members-subtitle {
  font-size: 1.125rem;
  opacity: 0.9;
  margin: 0;
}

/* Search and Filters */
.search-filters {
  padding: 2rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  align-items: center;
}

.search-container {
  position: relative;
  flex: 1;
  min-width: 250px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 2.5rem 0.75rem 1rem;
  border: 2px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.search-icon {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  pointer-events: none;
}

.filter-container {
  min-width: 200px;
}

.commission-filter {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  background: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.commission-filter:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

/* Stats */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  padding: 2rem;
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  text-align: center;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}

.stat-number {
  font-size: 2rem;
  font-weight: bold;
  color: #16a34a;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}

/* Loading and Error States */
.loading-state, .error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  text-align: center;
  color: #6b7280;
}

.loading-spinner-large {
  width: 48px;
  height: 48px;
  border: 4px solid #e5e7eb;
  border-top: 4px solid #16a34a;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

.error-state svg {
  color: #dc2626;
  margin-bottom: 1rem;
}

.error-state p {
  font-size: 1.125rem;
  font-weight: 500;
  margin-bottom: 1rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-primary {
  background: #16a34a;
  color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
  background: #15803d;
}

/* Members Section */
.members-section {
  padding: 2rem;
}

/* Desktop Table View */
.desktop-view {
  display: block;
}

.table-container {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
  margin-bottom: 2rem;
}

.members-table {
  width: 100%;
  border-collapse: collapse;
}

.members-table thead {
  background: #f8fafc;
}

.members-table th {
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.member-row {
  transition: background-color 0.2s ease;
}

.member-row:hover {
  background: #f9fafb;
}

.member-row:nth-child(even) {
  background: #f8fafc;
}

.member-row:nth-child(even):hover {
  background: #f3f4f6;
}

.members-table td {
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
  color: #374151;
  font-size: 0.875rem;
}

.photo-cell {
  width: 80px;
}

.member-photo {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid #e5e7eb;
  position: relative;
}

.photo-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.photo-placeholder {
  width: 100%;
  height: 100%;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
}

.name-cell {
  font-weight: 500;
}

.commission-cell {
  min-width: 150px;
}

.commission-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background: #dbeafe;
  color: #1e40af;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.contact-cell {
  font-family: monospace;
}

/* Mobile Card View */
.mobile-view {
  display: none;
}

.members-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.member-card {
  background: white;
  border-radius: 0.75rem;
  border: 1px solid #e5e7eb;
  overflow: hidden;
  transition: all 0.2s ease;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}

.member-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.card-header {
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.member-photo-large {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #e5e7eb;
  flex-shrink: 0;
}

.photo-img-large {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.photo-placeholder-large {
  width: 100%;
  height: 100%;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
}

.member-info {
  flex: 1;
  min-width: 0;
}

.member-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.5rem;
  word-break: break-word;
}

.commission-badge-mobile {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background: #16a34a;
  color: white;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.card-footer {
  padding: 1rem 1.5rem;
  background: #f8fafc;
  border-top: 1px solid #e5e7eb;
}

.contact-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #6b7280;
  font-size: 0.875rem;
  font-family: monospace;
}

/* No Results */
.no-results {
  padding: 3rem;
  text-align: center;
  color: #6b7280;
}

.no-results svg {
  margin-bottom: 1rem;
  opacity: 0.5;
}

.no-results p {
  font-size: 1.125rem;
  font-weight: 500;
}

/* Pagination */
.pagination-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 2rem;
  padding: 1rem;
  background: #f8fafc;
  border-radius: 0.75rem;
  border: 1px solid #e5e7eb;
  flex-wrap: wrap;
  gap: 1rem;
}

.pagination-buttons {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.pagination-numbers {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.pagination-btn {
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  background: #ffffff;
  color: #374151;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.pagination-btn:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #9ca3af;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f3f4f6;
}

.pagination-btn-active {
  background: #16a34a;
  color: #ffffff;
  border-color: #16a34a;
}

.pagination-btn-active:hover {
  background: #15803d;
  border-color: #15803d;
}

.pagination-btn-nav {
  padding: 0.5rem 1rem;
}

.items-per-page {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.items-per-page label {
  font-size: 0.875rem;
  color: #374151;
  font-weight: 500;
}

.items-select {
  padding: 0.375rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background: #ffffff;
  color: #374151;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.items-select:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

/* Results Info */
.results-info {
  margin-top: 1rem;
  padding: 1rem;
  background: #f0fdf4;
  border-radius: 0.5rem;
  border: 1px solid #bbf7d0;
  text-align: center;
}

.results-info p {
  font-size: 0.875rem;
  color: #15803d;
  margin: 0;
}

/* Animations */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
  .main-content {
    padding: 1rem;
  }

  .members-header {
    padding: 1.5rem;
  }

  .members-title {
    font-size: 1.5rem;
    flex-direction: column;
    gap: 0.5rem;
  }

  .members-subtitle {
    font-size: 1rem;
  }

  .search-filters {
    padding: 1.5rem;
    flex-direction: column;
    align-items: stretch;
  }

  .search-container {
    min-width: auto;
  }

  .filter-container {
    min-width: auto;
  }

  .stats-container {
    padding: 1.5rem;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  }

  .stat-card {
    padding: 1rem;
  }

  .stat-number {
    font-size: 1.5rem;
  }

  .members-section {
    padding: 1.5rem;
  }

  /* Switch to mobile view */
  .desktop-view {
    display: none;
  }

  .mobile-view {
    display: block;
  }

  .members-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .member-card {
    margin-bottom: 0;
  }

  .card-header {
    padding: 1rem;
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }

  .member-photo-large {
    width: 100px;
    height: 100px;
    align-self: center;
  }

  .member-info {
    text-align: center;
  }

  .member-name {
    font-size: 1rem;
  }

  .card-footer {
    padding: 1rem;
    text-align: center;
  }

  .pagination-controls {
    flex-direction: column;
    gap: 1rem;
  }

  .pagination-buttons {
    flex-wrap: wrap;
    justify-content: center;
  }

  .items-per-page {
    width: 100%;
    justify-content: space-between;
  }
}

@media (max-width: 480px) {
  .members-header {
    padding: 1rem;
  }

  .members-title {
    font-size: 1.25rem;
  }

  .search-filters {
    padding: 1rem;
  }

  .stats-container {
    padding: 1rem;
    grid-template-columns: repeat(2, 1fr);
  }

  .members-section {
    padding: 1rem;
  }

  .card-header {
    padding: 0.75rem;
  }

  .member-photo-large {
    width: 80px;
    height: 80px;
  }

  .pagination-btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.75rem;
  }

  .pagination-btn-nav {
    padding: 0.375rem 0.75rem;
  }
}
</style>
