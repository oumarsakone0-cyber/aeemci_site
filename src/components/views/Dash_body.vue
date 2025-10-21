<template>
  <div class="dashboard-body">
    <!-- Professional content header with title and actions -->
    <div class="content-header">
      <h2 class="content-title">Gestion des Membres</h2>
      <div class="header-actions">
        <div class="search-container">
          <div class="search-input-wrapper">
            <span class="search-icon">🔍</span>
            <input 
              type="text" 
              v-model="searchTerm" 
              @input="debouncedSearch"
              placeholder="Rechercher par nom, poste ou matricule..."
              class="search-input"
            />
            <button 
              v-if="searchTerm" 
              @click="clearSearch" 
              class="clear-search"
              title="Effacer la recherche"
            >
              ×
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Clean navigation tabs with professional styling -->
    <div class="nav-tabs">
      <button 
        @click="activeTab = 'bureau'" 
        :class="['tab-button', { active: activeTab === 'bureau' }]"
      >
        <svg class="tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        Bureau
      </button>
      <button 
        @click="activeTab = 'sous-comites'" 
        :class="['tab-button', { active: activeTab === 'sous-comites' }]"
      >
        <svg class="tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <line x1="9" y1="9" x2="15" y2="9"/>
          <line x1="9" y1="15" x2="15" y2="15"/>
        </svg>
        Sous-comités
      </button>
    </div>

    <!-- Bureau Tab Content -->
    <div v-if="activeTab === 'bureau'" class="tab-content">
      <!-- Professional toolbar with export and add buttons -->
      <div class="toolbar">
        <div class="toolbar-left">
          <span class="results-count">{{ filteredBureauMembers.length }} membre(s)</span>
        </div>
        <div class="toolbar-actions">
          <button @click="exportToExcel('bureau')" class="action-btn secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
            </svg>
            Excel
          </button>
          <button @click="exportToPDF('bureau')" class="action-btn secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            PDF
          </button>
          <button @click="showAddModal = true" class="action-btn primary">
            <span class="add-icon">+</span>
            <span>Nouveau Membre</span>
          </button>
        </div>
      </div>

      <!-- Professional table with proper column alignment -->
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 80px;">Photo</th>
              <th style="width: 200px;">Nom & Prénom</th>
              <th style="width: 160px;">Poste</th>
              <th style="width: 120px;">Matricule</th>
              <th style="width: 140px;">Contact</th>
              <th style="width: 120px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="membre in filteredBureauMembers" :key="membre.id" class="table-row" style="display: table-row;">
              <td>
                <div class="photo-cell">
                  <img v-if="membre.photo" 
                       :src="membre.photo" 
                       :alt="membre.nom + ' ' + membre.prenom" 
                       class="membre-photo">
                  <div v-else class="membre-avatar">{{ membre.prenom.charAt(0) }}{{ membre.nom.charAt(0) }}</div>
                </div>
              </td>
              <td class="name-cell">
                <div class="membre-info">
                  <span class="membre-nom">{{ membre.prenom }} {{ membre.nom }}</span>
                </div>
              </td>
              <td>
                <span class="status-badge" :class="getBadgeClass(membre.poste)">
                  {{ membre.poste }}
                </span>
              </td>
              <td class="matricule-cell">
                <span class="matricule-badge">{{ membre.matricule }}</span>
              </td>
              <td class="contact-cell">
                <span class="contact-phone">{{ membre.contact }}</span>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn view" @click="viewMember(membre)" title="Voir">
                    👁️
                  </button>
                  <button class="action-btn edit" @click="editMember(membre)" title="Modifier">
                    ✏️
                  </button>
                  <button class="action-btn delete" @click="deleteMember(membre)" title="Supprimer">
                    🗑️
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Added mobile card layout for bureau members -->
        <div class="mobile-cards">
          <div v-for="membre in filteredBureauMembers" :key="membre.id" class="member-card">
            <div class="card-header">
              <img v-if="membre.photo" 
                   :src="membre.photo" 
                   :alt="membre.nom + ' ' + membre.prenom" 
                   class="membre-photo">
              <div v-else class="membre-avatar">{{ membre.prenom.charAt(0) }}{{ membre.nom.charAt(0) }}</div>
              <div>
                <div class="membre-nom">{{ membre.prenom }} {{ membre.nom }}</div>
                <span class="status-badge" :class="getBadgeClass(membre.poste)">
                  {{ membre.poste }}
                </span>
              </div>
            </div>
            <div class="card-body">
              <div class="card-field">
                <span class="card-label">Matricule</span>
                <span class="matricule-badge">{{ membre.matricule }}</span>
              </div>
              <div class="card-field">
                <span class="card-label">Contact</span>
                <span class="card-value">{{ membre.contact }}</span>
              </div>
            </div>
            <div class="card-actions">
              <button class="action-btn view" @click="viewMember(membre)" title="Voir">
                👁️
              </button>
              <button class="action-btn edit" @click="editMember(membre)" title="Modifier">
                ✏️
              </button>
              <button class="action-btn delete" @click="deleteMember(membre)" title="Supprimer">
                🗑️
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sous-comités Tab Content -->
    <div v-if="activeTab === 'sous-comites'" class="tab-content">
      <!-- Professional toolbar for sous-comités -->
      <div class="toolbar">
        <div class="toolbar-left">
          <span class="results-count">{{ filteredSousComites.length }} sous-comité(s)</span>
        </div>
        <div class="toolbar-actions">
          <button @click="exportToExcel('sous-comites')" class="action-btn secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
            </svg>
            Excel
          </button>
          <button @click="exportToPDF('sous-comites')" class="action-btn secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            PDF
          </button>
          <button @click="showAddSousComiteModal = true" class="action-btn primary">
            <span class="add-icon">+</span>
            <span>Nouveau Sous-comité</span>
          </button>
        </div>
      </div>

      <!-- Professional sous-comités table -->
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 250px;">Sous-comité</th>
              <th style="width: 200px;">Président</th>
              <th style="width: 120px;">Sections</th>
              <th style="width: 120px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="sousComite in filteredSousComites" :key="sousComite.id" class="table-row" style="display: table-row;">
              <td class="name-cell">
                <div class="membre-info">
                  <div class="membre-avatar bg-blue-100 text-blue-600">
                    {{ sousComite.nom.charAt(0) }}
                  </div>
                  <span class="membre-nom">{{ sousComite.nom }}</span>
                </div>
              </td>
              <td>
                <span class="contact-phone">{{ sousComite.president }}</span>
              </td>
              <td>
                <span class="status-badge bg-gray-100 text-gray-700">{{ sousComite.nbSections }}</span>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn view" @click="viewSousComite(sousComite)" title="Voir">
                    👁️
                  </button>
                  <button class="action-btn edit" @click="editSousComite(sousComite)" title="Modifier">
                    ✏️
                  </button>
                  <button class="action-btn delete" @click="deleteSousComite(sousComite)" title="Supprimer">
                    🗑️
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Added mobile card layout for sous-comités -->
        <div class="mobile-cards">
          <div v-for="sousComite in filteredSousComites" :key="sousComite.id" class="member-card">
            <div class="card-header">
              <div class="membre-avatar bg-blue-100 text-blue-600">
                {{ sousComite.nom.charAt(0) }}
              </div>
              <div>
                <div class="membre-nom">{{ sousComite.nom }}</div>
              </div>
            </div>
            <div class="card-body">
              <div class="card-field">
                <span class="card-label">Président</span>
                <span class="card-value">{{ sousComite.president }}</span>
              </div>
              <div class="card-field">
                <span class="card-label">Sections</span>
                <span class="status-badge bg-gray-100 text-gray-700">{{ sousComite.nbSections }}</span>
              </div>
            </div>
            <div class="card-actions">
              <button class="action-btn view" @click="viewSousComite(sousComite)" title="Voir">
                👁️
              </button>
              <button class="action-btn edit" @click="editSousComite(sousComite)" title="Modifier">
                ✏️
              </button>
              <button class="action-btn delete" @click="deleteSousComite(sousComite)" title="Supprimer">
                🗑️
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Professional modal for adding members -->
    <div v-if="showAddModal" class="modal-overlay" @click="closeModal">
      <div class="modal-container add-modal" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Nouveau Membre</h3>
          <button class="modal-close" @click="closeModal">×</button>
        </div>
        <form class="add-form" @submit.prevent="addMember">
          <div class="form-group">
            <label>Poste *</label>
            <select v-model="newMember.poste" required>
              <option value="">Sélectionner un poste</option>
              <option value="Président">Président</option>
              <option value="Vice-Président">Vice-Président</option>
              <option value="Secrétaire Général">Secrétaire Général</option>
              <option value="Trésorier">Trésorier</option>
              <option value="Commissaire aux Comptes">Commissaire aux Comptes</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Prénom *</label>
              <input type="text" v-model="newMember.prenom" required placeholder="Prénom">
            </div>
            <div class="form-group">
              <label>Nom *</label>
              <input type="text" v-model="newMember.nom" required placeholder="Nom de famille">
            </div>
          </div>
          <div class="form-group">
            <label>Matricule *</label>
            <input type="text" v-model="newMember.matricule" required placeholder="Ex: MAT001">
          </div>
          <div class="form-group">
            <label>Contact *</label>
            <input type="tel" v-model="newMember.contact" required placeholder="+33 1 23 45 67 89">
          </div>
          <div class="form-actions">
            <button type="button" class="cancel-btn" @click="closeModal">Annuler</button>
            <button type="submit" class="submit-btn">Ajouter le membre</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Professional modal for adding sous-comités -->
    <div v-if="showAddSousComiteModal" class="modal-overlay" @click="closeSousComiteModal">
      <div class="modal-container add-modal" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Nouveau Sous-comité</h3>
          <button class="modal-close" @click="closeSousComiteModal">×</button>
        </div>
        <form class="add-form" @submit.prevent="addSousComite">
          <div class="form-group">
            <label>Nom du sous-comité *</label>
            <input type="text" v-model="newSousComite.nom" required placeholder="Ex: Comité Technique">
          </div>
          <div class="form-group">
            <label>Président *</label>
            <input type="text" v-model="newSousComite.president" required placeholder="Nom du président">
          </div>
          <div class="form-group">
            <label>Nombre de sections *</label>
            <input type="number" v-model="newSousComite.nbSections" min="1" required placeholder="1">
          </div>
          <div class="form-actions">
            <button type="button" class="cancel-btn" @click="closeSousComiteModal">Annuler</button>
            <button type="submit" class="submit-btn">Ajouter le sous-comité</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const activeTab = ref('bureau')
const searchTerm = ref('')
const showAddModal = ref(false)
const showAddSousComiteModal = ref(false)
const searchTimeout = ref(null)

// Sample data with more realistic content
const bureauMembers = ref([
  {
    id: 1,
    poste: 'Président',
    prenom: 'Jean',
    nom: 'Dupont',
    matricule: 'MAT001',
    contact: '+33 1 23 45 67 89',
    photo: null
  },
  {
    id: 2,
    poste: 'Vice-Président',
    prenom: 'Marie',
    nom: 'Martin',
    matricule: 'MAT002',
    contact: '+33 1 23 45 67 90',
    photo: null
  },
  {
    id: 3,
    poste: 'Secrétaire Général',
    prenom: 'Pierre',
    nom: 'Bernard',
    matricule: 'MAT003',
    contact: '+33 1 23 45 67 91',
    photo: null
  },
  {
    id: 4,
    poste: 'Trésorier',
    prenom: 'Sophie',
    nom: 'Leroy',
    matricule: 'MAT004',
    contact: '+33 1 23 45 67 92',
    photo: null
  }
])

const sousComites = ref([
  {
    id: 1,
    nom: 'Comité Technique',
    president: 'Alice Dubois',
    nbSections: 3
  },
  {
    id: 2,
    nom: 'Comité Social',
    president: 'Bob Wilson',
    nbSections: 2
  },
  {
    id: 3,
    nom: 'Comité Formation',
    president: 'Claire Moreau',
    nbSections: 4
  }
])

const newMember = ref({
  poste: '',
  prenom: '',
  nom: '',
  matricule: '',
  contact: ''
})

const newSousComite = ref({
  nom: '',
  president: '',
  nbSections: 1
})

const filteredBureauMembers = computed(() => {
  if (!searchTerm.value) return bureauMembers.value
  const search = searchTerm.value.toLowerCase()
  return bureauMembers.value.filter(membre => 
    `${membre.prenom} ${membre.nom}`.toLowerCase().includes(search) ||
    membre.poste.toLowerCase().includes(search) ||
    membre.matricule.toLowerCase().includes(search)
  )
})

const filteredSousComites = computed(() => {
  if (!searchTerm.value) return sousComites.value
  const search = searchTerm.value.toLowerCase()
  return sousComites.value.filter(comite => 
    comite.nom.toLowerCase().includes(search) ||
    comite.president.toLowerCase().includes(search)
  )
})

const debouncedSearch = () => {
  clearTimeout(searchTimeout.value)
  searchTimeout.value = setTimeout(() => {
    // Search is already reactive through computed properties
  }, 300)
}

const clearSearch = () => {
  searchTerm.value = ''
}

const getBadgeClass = (poste) => {
  const classes = {
    'Président': 'president',
    'Vice-Président': 'vice-president', 
    'Secrétaire Général': 'secretaire',
    'Trésorier': 'tresorier',
    'Commissaire aux Comptes': 'commissaire'
  }
  return classes[poste] || 'default'
}

const addMember = () => {
  const newId = Math.max(...bureauMembers.value.map(m => m.id)) + 1
  bureauMembers.value.push({
    id: newId,
    ...newMember.value
  })
  closeModal()
}

const addSousComite = () => {
  const newId = Math.max(...sousComites.value.map(s => s.id)) + 1
  sousComites.value.push({
    id: newId,
    ...newSousComite.value
  })
  closeSousComiteModal()
}

const closeModal = () => {
  showAddModal.value = false
  newMember.value = { poste: '', prenom: '', nom: '', matricule: '', contact: '' }
}

const closeSousComiteModal = () => {
  showAddSousComiteModal.value = false
  newSousComite.value = { nom: '', president: '', nbSections: 1 }
}

const viewMember = (membre) => {
  console.log('Voir membre:', membre)
}

const editMember = (membre) => {
  console.log('Modifier membre:', membre)
}

const deleteMember = (membre) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer ${membre.prenom} ${membre.nom} ?`)) {
    const index = bureauMembers.value.findIndex(m => m.id === membre.id)
    if (index > -1) {
      bureauMembers.value.splice(index, 1)
    }
  }
}

const viewSousComite = (comite) => {
  console.log('Voir sous-comité:', comite)
}

const editSousComite = (comite) => {
  console.log('Modifier sous-comité:', comite)
}

const deleteSousComite = (comite) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le sous-comité "${comite.nom}" ?`)) {
    const index = sousComites.value.findIndex(s => s.id === comite.id)
    if (index > -1) {
      sousComites.value.splice(index, 1)
    }
  }
}

const exportToExcel = (type) => {
  console.log('Export Excel:', type)
  // TODO: Implement Excel export functionality
}

const exportToPDF = (type) => {
  console.log('Export PDF:', type)
  // TODO: Implement PDF export functionality
}
</script>

<style scoped>
/* Professional CSS inspired by the reference design */
.dashboard-body {
  background: #f9fafb;
  min-height: 100vh;
  max-width: 1200px;
  margin: 0 auto;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  padding: 32px;
}

/* Added mobile responsive padding */
@media (max-width: 768px) {
  .dashboard-body {
    padding: 16px;
  }
}

.content-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

/* Mobile-first content header */
@media (max-width: 768px) {
  .content-header {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }
}

.content-title {
  font-size: 20px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

/* Smaller title on mobile */
@media (max-width: 768px) {
  .content-title {
    font-size: 18px;
    text-align: center;
  }
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

/* Full width search on mobile */
@media (max-width: 768px) {
  .header-actions {
    width: 100%;
  }
}

.search-container {
  position: relative;
}

/* Full width search container on mobile */
@media (max-width: 768px) {
  .search-container {
    width: 100%;
  }
}

.search-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.search-input {
  width: 320px;
  padding: 10px 12px 10px 40px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  background: white;
  color: #374151;
  transition: all 0.2s;
}

/* Full width search input on mobile */
@media (max-width: 768px) {
  .search-input {
    width: 100%;
    padding: 12px 12px 12px 40px;
  }
}

.search-input:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.search-input::placeholder {
  color: #9ca3af;
}

.search-icon {
  position: absolute;
  left: 12px;
  color: #6b7280;
  font-size: 14px;
  pointer-events: none;
}

.clear-search {
  position: absolute;
  right: 8px;
  width: 20px;
  height: 20px;
  border: none;
  background: #f3f4f6;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: #6b7280;
  transition: all 0.2s;
}

.clear-search:hover {
  background: #e5e7eb;
  color: #374151;
}

.nav-tabs {
  display: flex;
  gap: 4px;
  margin-bottom: 24px;
  background: #f3f4f6;
  padding: 4px;
  border-radius: 8px;
  width: fit-content;
}

/* Full width tabs on mobile */
@media (max-width: 768px) {
  .nav-tabs {
    width: 100%;
    justify-content: center;
  }
}

.tab-button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  border: none;
  background: transparent;
  color: #6b7280;
  font-size: 14px;
  font-weight: 500;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

/* Larger touch targets on mobile */
@media (max-width: 768px) {
  .tab-button {
    flex: 1;
    justify-content: center;
    padding: 14px 16px;
    font-size: 16px;
  }
}

.tab-button:hover {
  color: #374151;
  background: rgba(255, 255, 255, 0.5);
}

.tab-button.active {
  background: white;
  color: #16a34a;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tab-icon {
  width: 16px;
  height: 16px;
}

/* Hide icons on small mobile screens */
@media (max-width: 480px) {
  .tab-icon {
    display: none;
  }
}

.tab-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}

/* Mobile toolbar layout */
@media (max-width: 768px) {
  .toolbar {
    flex-direction: column;
    gap: 16px;
    padding: 16px;
  }
}

.toolbar-left {
  display: flex;
  align-items: center;
}

.results-count {
  font-size: 14px;
  color: #6b7280;
  font-weight: 500;
}

.toolbar-actions {
  display: flex;
  gap: 8px;
}

/* Mobile toolbar actions */
@media (max-width: 768px) {
  .toolbar-actions {
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
  }
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

/* Larger touch targets for mobile */
@media (max-width: 768px) {
  .action-btn {
    padding: 12px 20px;
    font-size: 16px;
    min-height: 44px;
  }
}

.action-btn.primary {
  background: #16a34a;
  color: white;
}

.action-btn.primary:hover {
  background: #15803d;
}

.action-btn.secondary {
  background: #f3f4f6;
  color: #6b7280;
  border: 1px solid #e5e7eb;
}

.action-btn.secondary:hover {
  background: #e5e7eb;
  color: #374151;
}

.action-btn svg {
  width: 16px;
  height: 16px;
}

.add-icon {
  font-size: 16px;
  font-weight: bold;
}

.table-container {
  overflow-x: auto;
}

/* Mobile card layout for tables */
@media (max-width: 768px) {
  .table-container {
    padding: 0;
  }
  
  .data-table {
    display: none;
  }
  
  .mobile-cards {
    display: block;
    padding: 16px;
  }
  
  .member-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }
  
  .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
  }
  
  .card-body {
    display: grid;
    gap: 8px;
  }
  
  .card-field {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
  }
  
  .card-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .card-value {
    font-size: 14px;
    color: #374151;
    font-weight: 500;
  }
  
  .card-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f3f4f6;
  }
}

/* Show table on desktop, hide mobile cards */
@media (min-width: 769px) {
  .mobile-cards {
    display: none;
  }
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  background: #f9fafb;
  padding: 16px;
  text-align: left;
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 1px solid #e5e7eb;
}

.table-row {
  border-bottom: 1px solid #f3f4f6;
  transition: all 0.2s;
}

.table-row:hover {
  background: #f9fafb;
}

.data-table td {
  padding: 16px;
  font-size: 14px;
  color: #374151;
  vertical-align: middle;
}

.photo-cell {
  display: flex;
  justify-content: center;
}

.membre-photo {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.membre-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #16a34a;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 14px;
}

.name-cell {
  min-width: 200px;
}

.membre-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.membre-nom {
  font-weight: 500;
  color: #1f2937;
}

.matricule-cell {
  text-align: center;
}

.matricule-badge {
  background: #eff6ff;
  color: #1d4ed8;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.contact-cell {
  min-width: 140px;
}

.contact-phone {
  font-size: 14px;
  color: #6b7280;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  text-align: center;
  display: inline-block;
  min-width: 80px;
}

.status-badge.president {
  background: #fef3c7;
  color: #d97706;
}

.status-badge.vice-president {
  background: #dbeafe;
  color: #1d4ed8;
}

.status-badge.secretaire {
  background: #dcfce7;
  color: #166534;
}

.status-badge.tresorier {
  background: #f3e8ff;
  color: #7c3aed;
}

.status-badge.commissaire {
  background: #fef2f2;
  color: #dc2626;
}

.status-badge.default {
  background: #f3f4f6;
  color: #6b7280;
}

.action-buttons {
  display: flex;
  gap: 4px;
  justify-content: center;
}

.action-btn.view,
.action-btn.edit,
.action-btn.delete {
  width: 32px;
  height: 32px;
  border: none;
  background: transparent;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  transition: all 0.2s;
  padding: 0;
}

/* Larger touch targets for mobile action buttons */
@media (max-width: 768px) {
  .action-btn.view,
  .action-btn.edit,
  .action-btn.delete {
    width: 40px;
    height: 40px;
    font-size: 16px;
  }
}

.action-btn.view:hover {
  background: #eff6ff;
  color: #1d4ed8;
}

.action-btn.edit:hover {
  background: #fef3c7;
  color: #d97706;
}

.action-btn.delete:hover {
  background: #fef2f2;
  color: #dc2626;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 16px;
}

.modal-container {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

/* Full screen modal on mobile */
@media (max-width: 768px) {
  .modal-container {
    width: 100%;
    height: 100%;
    max-height: 100vh;
    border-radius: 0;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
}

/* Mobile modal header */
@media (max-width: 768px) {
  .modal-header {
    padding: 16px;
  }
}

.modal-title {
  font-size: 18px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.modal-close {
  width: 32px;
  height: 32px;
  border: none;
  background: transparent;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #6b7280;
}

/* Larger close button on mobile */
@media (max-width: 768px) {
  .modal-close {
    width: 40px;
    height: 40px;
    font-size: 24px;
  }
}

.modal-close:hover {
  background: #f3f4f6;
}

.add-form {
  padding: 24px;
  overflow-y: auto;
  flex: 1;
}

/* Mobile form padding */
@media (max-width: 768px) {
  .add-form {
    padding: 16px;
  }
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

/* Single column on mobile */
@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}

.form-group {
  margin-bottom: 20px;
}

/* Reduced margin on mobile */
@media (max-width: 768px) {
  .form-group {
    margin-bottom: 16px;
  }
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  color: #374151;
  margin-bottom: 6px;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  background: white;
  color: #374151;
  transition: all 0.2s;
}

/* Larger inputs on mobile */
@media (max-width: 768px) {
  .form-group input,
  .form-group select {
    padding: 14px 16px;
    font-size: 16px;
    min-height: 48px;
  }
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
}

/* Mobile form actions */
@media (max-width: 768px) {
  .form-actions {
    flex-direction: column-reverse;
    gap: 12px;
    padding-top: 16px;
  }
}

.cancel-btn {
  padding: 8px 16px;
  background: transparent;
  color: #6b7280;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

/* Full width buttons on mobile */
@media (max-width: 768px) {
  .cancel-btn {
    width: 100%;
    padding: 14px 16px;
    font-size: 16px;
    min-height: 48px;
  }
}

.cancel-btn:hover {
  background: #f9fafb;
  color: #374151;
}

.submit-btn {
  padding: 8px 16px;
  background: #16a34a;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

/* Full width submit button on mobile */
@media (max-width: 768px) {
  .submit-btn {
    width: 100%;
    padding: 14px 16px;
    font-size: 16px;
    min-height: 48px;
  }
}

.submit-btn:hover {
  background: #15803d;
}

/* Utility classes for specific styling */
.bg-blue-100 { background-color: #dbeafe; }
.text-blue-600 { color: #2563eb; }
.bg-gray-100 { background-color: #f3f4f6; }
.text-gray-700 { color: #374151; }
</style>
