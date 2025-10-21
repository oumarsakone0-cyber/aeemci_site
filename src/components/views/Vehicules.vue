<template>
  <div class="app-container">
    <!-- Sidebar (même que dashboard) -->
    <div class="sidebar">
      <div class="sidebar-header">
        <h1>🚕 TaxiManager</h1>
      </div>
      
      <nav class="sidebar-nav">
        <a href="#" class="nav-item">
          <BarChart3 class="nav-icon" />
          Dashboard
        </a>
        <a href="#" class="nav-item active">
          <Car class="nav-icon" />
          Véhicules
        </a>
        <a href="#" class="nav-item">
          <Users class="nav-icon" />
          Chauffeurs
        </a>
        <a href="#" class="nav-item">
          <Wrench class="nav-icon" />
          Réparations
        </a>
        <a href="#" class="nav-item">
          <DollarSign class="nav-icon" />
          Recettes
        </a>
        <a href="#" class="nav-item">
          <Clock class="nav-icon" />
          Checkpoints
        </a>
        <a href="#" class="nav-item">
          <FileText class="nav-icon" />
          Rapports
        </a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <header class="header">
        <div class="header-left">
          <h2>Gestion des Véhicules</h2>
          <p>{{ vehicles.length }} véhicules enregistrés</p>
        </div>
        <div class="header-right">
          <button class="btn-primary" @click="showAddModal = true">
            <Plus class="btn-icon" />
            Ajouter un véhicule
          </button>
        </div>
      </header>

      <!-- Vehicles Content -->
      <main class="vehicles-main">
        <!-- Filters and Search -->
        <div class="filters-section">
          <div class="search-container">
            <Search class="search-icon" />
            <input 
              type="text" 
              placeholder="Rechercher par plaque, marque, modèle..." 
              v-model="searchQuery"
              class="search-input"
            />
          </div>
          
          <div class="filters">
            <select v-model="statusFilter" class="filter-select">
              <option value="">Tous les statuts</option>
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
              <option value="maintenance">En maintenance</option>
            </select>
            
            <select v-model="sortBy" class="filter-select">
              <option value="plate">Trier par plaque</option>
              <option value="brand">Trier par marque</option>
              <option value="year">Trier par année</option>
              <option value="status">Trier par statut</option>
            </select>
          </div>
        </div>

        <!-- Vehicles Grid -->
        <div class="vehicles-grid">
          <div 
            v-for="vehicle in filteredVehicles" 
            :key="vehicle.id" 
            class="vehicle-card"
            :class="vehicle.status"
          >
            <div class="vehicle-header">
              <div class="vehicle-status-indicator" :class="vehicle.status"></div>
              <h3 class="vehicle-plate">{{ vehicle.plate }}</h3>
              <div class="vehicle-actions">
                <button class="action-btn" @click="editVehicle(vehicle)" title="Modifier">
                  <Edit class="action-icon" />
                </button>
                <button class="action-btn" @click="viewDocuments(vehicle)" title="Documents">
                  <FileText class="action-icon" />
                </button>
                <button class="action-btn danger" @click="deleteVehicle(vehicle.id)" title="Supprimer">
                  <Trash2 class="action-icon" />
                </button>
              </div>
            </div>

            <div class="vehicle-image">
              <img :src="vehicle.image" :alt="vehicle.brand + ' ' + vehicle.model" />
            </div>

            <div class="vehicle-info">
              <div class="vehicle-main-info">
                <h4>{{ vehicle.brand }} {{ vehicle.model }}</h4>
                <p class="vehicle-year">{{ vehicle.year }}</p>
              </div>

              <div class="vehicle-details">
                <div class="detail-item">
                  <span class="detail-label">Chauffeurs:</span>
                  <div class="drivers-list">
                    <span v-for="driver in vehicle.drivers" :key="driver" class="driver-tag">
                      {{ driver }}
                    </span>
                  </div>
                </div>

                <div class="detail-item">
                  <span class="detail-label">Kilométrage:</span>
                  <span class="detail-value">{{ formatNumber(vehicle.mileage) }} km</span>
                </div>

                <div class="detail-item">
                  <span class="detail-label">Assurance:</span>
                  <span class="detail-value" :class="getInsuranceStatus(vehicle.insuranceExpiry)">
                    {{ formatDate(vehicle.insuranceExpiry) }}
                  </span>
                </div>

                <div class="detail-item">
                  <span class="detail-label">Contrôle technique:</span>
                  <span class="detail-value" :class="getTechnicalStatus(vehicle.technicalExpiry)">
                    {{ formatDate(vehicle.technicalExpiry) }}
                  </span>
                </div>
              </div>

              <div class="vehicle-stats">
                <div class="stat">
                  <span class="stat-label">Recettes ce mois</span>
                  <span class="stat-value">{{ formatCurrency(vehicle.monthlyRevenue) }}</span>
                </div>
                <div class="stat">
                  <span class="stat-label">Dernière maintenance</span>
                  <span class="stat-value">{{ formatDate(vehicle.lastMaintenance) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredVehicles.length === 0" class="empty-state">
          <Car class="empty-icon" />
          <h3>Aucun véhicule trouvé</h3>
          <p>{{ searchQuery ? 'Essayez de modifier vos critères de recherche' : 'Commencez par ajouter votre premier véhicule' }}</p>
          <button class="btn-primary" @click="showAddModal = true" v-if="!searchQuery">
            <Plus class="btn-icon" />
            Ajouter un véhicule
          </button>
        </div>
      </main>
    </div>

    <!-- Add/Edit Vehicle Modal -->
    <div v-if="showAddModal || editingVehicle" class="modal-overlay" @click="closeModal">
      <div class="modal" @click.stop>
        <div class="modal-header">
          <h3>{{ editingVehicle ? 'Modifier le véhicule' : 'Ajouter un nouveau véhicule' }}</h3>
          <button class="modal-close" @click="closeModal">
            <X class="close-icon" />
          </button>
        </div>

        <form @submit.prevent="saveVehicle" class="modal-form">
          <div class="form-grid">
            <div class="form-group">
              <label>Plaque d'immatriculation *</label>
              <input 
                type="text" 
                v-model="vehicleForm.plate" 
                required 
                placeholder="Ex: ABC-123"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Marque *</label>
              <input 
                type="text" 
                v-model="vehicleForm.brand" 
                required 
                placeholder="Ex: Peugeot"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Modèle *</label>
              <input 
                type="text" 
                v-model="vehicleForm.model" 
                required 
                placeholder="Ex: 508"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Année *</label>
              <input 
                type="number" 
                v-model="vehicleForm.year" 
                required 
                min="1990"
                max="2024"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Couleur</label>
              <input 
                type="text" 
                v-model="vehicleForm.color" 
                placeholder="Ex: Blanc"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Kilométrage</label>
              <input 
                type="number" 
                v-model="vehicleForm.mileage" 
                placeholder="Ex: 150000"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Date d'expiration assurance *</label>
              <input 
                type="date" 
                v-model="vehicleForm.insuranceExpiry" 
                required 
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>Date d'expiration contrôle technique *</label>
              <input 
                type="date" 
                v-model="vehicleForm.technicalExpiry" 
                required 
                class="form-input"
              />
            </div>
          </div>

          <div class="form-group full-width">
            <label>Description</label>
            <textarea 
              v-model="vehicleForm.description" 
              placeholder="Description du véhicule, équipements spéciaux..."
              class="form-textarea"
              rows="3"
            ></textarea>
          </div>

          <div class="form-group full-width">
            <label>Chauffeurs assignés</label>
            <div class="drivers-selection">
              <div v-for="driver in availableDrivers" :key="driver.id" class="driver-checkbox">
                <input 
                  type="checkbox" 
                  :id="'driver-' + driver.id"
                  :value="driver.name"
                  v-model="vehicleForm.drivers"
                />
                <label :for="'driver-' + driver.id">{{ driver.name }}</label>
              </div>
            </div>
          </div>

          <div class="form-group full-width">
            <label>Documents</label>
            <div class="file-upload-area">
              <input 
                type="file" 
                multiple 
                accept=".pdf,.jpg,.jpeg,.png"
                @change="handleFileUpload"
                class="file-input"
                id="documents"
              />
              <label for="documents" class="file-upload-label">
                <Upload class="upload-icon" />
                <span>Cliquez pour ajouter des documents</span>
                <small>PDF, JPG, PNG (max 5MB chacun)</small>
              </label>
            </div>
            
            <div v-if="vehicleForm.documents.length > 0" class="uploaded-files">
              <div v-for="(doc, index) in vehicleForm.documents" :key="index" class="uploaded-file">
                <FileText class="file-icon" />
                <span>{{ doc.name }}</span>
                <button type="button" @click="removeDocument(index)" class="remove-file">
                  <X class="remove-icon" />
                </button>
              </div>
            </div>
          </div>

          <div class="modal-actions">
            <button type="button" class="btn-secondary" @click="closeModal">
              Annuler
            </button>
            <button type="submit" class="btn-primary">
              {{ editingVehicle ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Documents Modal -->
    <div v-if="showDocumentsModal" class="modal-overlay" @click="showDocumentsModal = false">
      <div class="modal documents-modal" @click.stop>
        <div class="modal-header">
          <h3>Documents - {{ selectedVehicle?.plate }}</h3>
          <button class="modal-close" @click="showDocumentsModal = false">
            <X class="close-icon" />
          </button>
        </div>

        <div class="documents-content">
          <div class="documents-grid">
            <div v-for="doc in selectedVehicle?.documents" :key="doc.id" class="document-item">
              <div class="document-icon">
                <FileText />
              </div>
              <div class="document-info">
                <h4>{{ doc.name }}</h4>
                <p>{{ doc.type }}</p>
                <small>Ajouté le {{ formatDate(doc.uploadDate) }}</small>
              </div>
              <div class="document-actions">
                <button class="action-btn" title="Télécharger">
                  <Download class="action-icon" />
                </button>
                <button class="action-btn danger" title="Supprimer">
                  <Trash2 class="action-icon" />
                </button>
              </div>
            </div>
          </div>

          <div class="add-document">
            <input 
              type="file" 
              multiple 
              accept=".pdf,.jpg,.jpeg,.png"
              @change="addDocuments"
              class="file-input"
              id="add-docs"
            />
            <label for="add-docs" class="file-upload-label">
              <Plus class="upload-icon" />
              Ajouter des documents
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  BarChart3, Car, Users, Wrench, DollarSign, Clock, FileText, 
  Plus, Search, Edit, Trash2, X, Upload, Download
} from 'lucide-vue-next'

// Reactive data
const searchQuery = ref('')
const statusFilter = ref('')
const sortBy = ref('plate')
const showAddModal = ref(false)
const showDocumentsModal = ref(false)
const editingVehicle = ref(null)
const selectedVehicle = ref(null)

const vehicleForm = ref({
  plate: '',
  brand: '',
  model: '',
  year: new Date().getFullYear(),
  color: '',
  mileage: 0,
  insuranceExpiry: '',
  technicalExpiry: '',
  description: '',
  drivers: [],
  documents: []
})

const vehicles = ref([
  {
    id: 1,
    plate: 'ABC-123',
    brand: 'Peugeot',
    model: '508',
    year: 2020,
    color: 'Blanc',
    status: 'active',
    mileage: 125000,
    insuranceExpiry: '2024-12-15',
    technicalExpiry: '2024-08-20',
    drivers: ['Mohamed Ali', 'Youssef Ben'],
    monthlyRevenue: 2450.50,
    lastMaintenance: '2024-01-15',
    image: 'https://static.vecteezy.com/ti/vecteur-libre/p1/3129062-taxi-voiture-cartoon-vector-illustration-vectoriel.jpg',
    documents: [
      { id: 1, name: 'Carte grise', type: 'PDF', uploadDate: '2024-01-10' },
      { id: 2, name: 'Assurance', type: 'PDF', uploadDate: '2024-01-10' }
    ]
  },
  {
    id: 2,
    plate: 'DEF-456',
    brand: 'Renault',
    model: 'Megane',
    year: 2019,
    color: 'Gris',
    status: 'active',
    mileage: 98000,
    insuranceExpiry: '2024-06-30',
    technicalExpiry: '2024-09-15',
    drivers: ['Ahmed Ben', 'Karim Said'],
    monthlyRevenue: 2180.30,
    lastMaintenance: '2024-01-20',
    image: 'https://static.vecteezy.com/ti/vecteur-libre/p1/3129062-taxi-voiture-cartoon-vector-illustration-vectoriel.jpg',
    documents: [
      { id: 3, name: 'Carte grise', type: 'PDF', uploadDate: '2024-01-12' }
    ]
  },
  {
    id: 3,
    plate: 'GHI-789',
    brand: 'Volkswagen',
    model: 'Passat',
    year: 2021,
    color: 'Noir',
    status: 'maintenance',
    mileage: 75000,
    insuranceExpiry: '2024-11-20',
    technicalExpiry: '2024-10-05',
    drivers: ['Omar Hassan', 'Ali Mohamed'],
    monthlyRevenue: 0,
    lastMaintenance: '2024-01-25',
    image: 'https://static.vecteezy.com/ti/vecteur-libre/p1/3129062-taxi-voiture-cartoon-vector-illustration-vectoriel.jpg',
    documents: []
  }
])

const availableDrivers = ref([
  { id: 1, name: 'Mohamed Ali' },
  { id: 2, name: 'Ahmed Ben' },
  { id: 3, name: 'Karim Said' },
  { id: 4, name: 'Omar Hassan' },
  { id: 5, name: 'Youssef Ben' },
  { id: 6, name: 'Ali Mohamed' }
])

// Computed
const filteredVehicles = computed(() => {
  let filtered = vehicles.value

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(vehicle => 
      vehicle.plate.toLowerCase().includes(query) ||
      vehicle.brand.toLowerCase().includes(query) ||
      vehicle.model.toLowerCase().includes(query)
    )
  }

  // Filter by status
  if (statusFilter.value) {
    filtered = filtered.filter(vehicle => vehicle.status === statusFilter.value)
  }

  // Sort
  filtered.sort((a, b) => {
    switch (sortBy.value) {
      case 'plate':
        return a.plate.localeCompare(b.plate)
      case 'brand':
        return a.brand.localeCompare(b.brand)
      case 'year':
        return b.year - a.year
      case 'status':
        return a.status.localeCompare(b.status)
      default:
        return 0
    }
  })

  return filtered
})

// Methods
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-TN', {
    style: 'currency',
    currency: 'TND'
  }).format(amount)
}

const formatNumber = (number) => {
  return new Intl.NumberFormat('fr-FR').format(number)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR')
}

const getInsuranceStatus = (expiryDate) => {
  const today = new Date()
  const expiry = new Date(expiryDate)
  const daysUntilExpiry = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24))
  
  if (daysUntilExpiry < 0) return 'expired'
  if (daysUntilExpiry <= 30) return 'warning'
  return 'valid'
}

const getTechnicalStatus = (expiryDate) => {
  return getInsuranceStatus(expiryDate)
}

const editVehicle = (vehicle) => {
  editingVehicle.value = vehicle
  vehicleForm.value = { ...vehicle }
  showAddModal.value = true
}

const deleteVehicle = (vehicleId) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')) {
    vehicles.value = vehicles.value.filter(v => v.id !== vehicleId)
  }
}

const viewDocuments = (vehicle) => {
  selectedVehicle.value = vehicle
  showDocumentsModal.value = true
}

const closeModal = () => {
  showAddModal.value = false
  editingVehicle.value = null
  resetForm()
}

const resetForm = () => {
  vehicleForm.value = {
    plate: '',
    brand: '',
    model: '',
    year: new Date().getFullYear(),
    color: '',
    mileage: 0,
    insuranceExpiry: '',
    technicalExpiry: '',
    description: '',
    drivers: [],
    documents: []
  }
}

const saveVehicle = () => {
  if (editingVehicle.value) {
    // Update existing vehicle
    const index = vehicles.value.findIndex(v => v.id === editingVehicle.value.id)
    vehicles.value[index] = { ...vehicleForm.value, id: editingVehicle.value.id }
  } else {
    // Add new vehicle
    const newVehicle = {
      ...vehicleForm.value,
      id: Date.now(),
      status: 'active',
      monthlyRevenue: 0,
      lastMaintenance: new Date().toISOString().split('T')[0],
      image: 'https://static.vecteezy.com/ti/vecteur-libre/p1/3129062-taxi-voiture-cartoon-vector-illustration-vectoriel.jpg',
      documents: vehicleForm.value.documents.map((doc, index) => ({
        id: Date.now() + index,
        name: doc.name,
        type: doc.type || 'PDF',
        uploadDate: new Date().toISOString().split('T')[0]
      }))
    }
    vehicles.value.push(newVehicle)
  }
  
  closeModal()
}

const handleFileUpload = (event) => {
  const files = Array.from(event.target.files)
  vehicleForm.value.documents.push(...files)
}

const removeDocument = (index) => {
  vehicleForm.value.documents.splice(index, 1)
}

const addDocuments = (event) => {
  const files = Array.from(event.target.files)
  // Simulate adding documents to selected vehicle
  console.log('Adding documents:', files)
}

onMounted(() => {
  console.log('Vehicles page loaded')
})
</script>

<style scoped>
/* Base styles (réutilise les styles du dashboard) */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-container {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Sidebar Styles (identique au dashboard) */
.sidebar {
  width: 280px;
  background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
  color: white;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
  padding: 24px 20px;
  border-bottom: 1px solid #475569;
  text-align: center;
}

.sidebar-header h1 {
  font-size: 24px;
  font-weight: bold;
  color: #f1f5f9;
}

.sidebar-nav {
  padding: 20px 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 16px 24px;
  color: #cbd5e1;
  text-decoration: none;
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
}

.nav-item:hover {
  background-color: #475569;
  color: white;
  border-left-color: #3b82f6;
}

.nav-item.active {
  background-color: #3b82f6;
  color: white;
  border-left-color: #60a5fa;
}

.nav-icon {
  width: 20px;
  height: 20px;
  margin-right: 12px;
}

/* Main Content */
.main-content {
  margin-left: 280px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Header */
.header {
  background: white;
  padding: 20px 32px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-bottom: 1px solid #e2e8f0;
}

.header-left h2 {
  font-size: 28px;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 4px;
}

.header-left p {
  color: #64748b;
  font-size: 14px;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-primary {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-icon {
  width: 18px;
  height: 18px;
}

/* Vehicles Main */
.vehicles-main {
  padding: 32px;
  flex: 1;
  overflow-y: auto;
}

/* Filters Section */
.filters-section {
  background: white;
  padding: 24px;
  border-radius: 16px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 1px solid #f1f5f9;
  margin-bottom: 32px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 24px;
}

.search-container {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  color: #64748b;
}

.search-input {
  width: 100%;
  padding: 12px 16px 12px 48px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.3s;
}

.search-input:focus {
  outline: none;
  border-color: #3b82f6;
}

.filters {
  display: flex;
  gap: 16px;
}

.filter-select {
  padding: 12px 16px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  font-size: 14px;
  min-width: 150px;
}

.filter-select:focus {
  outline: none;
  border-color: #3b82f6;
}

/* Vehicles Grid */
.vehicles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 24px;
}

.vehicle-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 2px solid #f1f5f9;
  transition: all 0.3s;
}

.vehicle-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.vehicle-card.active {
  border-color: #dcfce7;
}

.vehicle-card.inactive {
  border-color: #fef2f2;
  opacity: 0.8;
}

.vehicle-card.maintenance {
  border-color: #fef3c7;
}

.vehicle-header {
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #f1f5f9;
}

.vehicle-status-indicator {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin-right: 12px;
}

.vehicle-status-indicator.active {
  background: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}

.vehicle-status-indicator.inactive {
  background: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
}

.vehicle-status-indicator.maintenance {
  background: #f59e0b;
  box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
}

.vehicle-plate {
  font-size: 20px;
  font-weight: 700;
  color: #1e293b;
  flex: 1;
}

.vehicle-actions {
  display: flex;
  gap: 8px;
}

.action-btn {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 8px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s;
}

.action-btn:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
}

.action-btn.danger:hover {
  background: #fef2f2;
  border-color: #fecaca;
}

.action-icon {
  width: 16px;
  height: 16px;
  color: #64748b;
}

.action-btn.danger .action-icon {
  color: #ef4444;
}

/* Vehicle Image */
.vehicle-image {
  height: 200px;
  overflow: hidden;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  display: flex;
  align-items: center;
  justify-content: center;
  border-bottom: 1px solid #f1f5f9;
}

.vehicle-image img {
  width: 80%;
  height: 80%;
  object-fit: contain;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}

.vehicle-info {
  padding: 20px;
}

.vehicle-main-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.vehicle-main-info h4 {
  font-size: 18px;
  font-weight: 600;
  color: #1e293b;
}

.vehicle-year {
  color: #64748b;
  font-weight: 500;
}

.vehicle-details {
  margin-bottom: 20px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.detail-label {
  font-size: 14px;
  color: #64748b;
  font-weight: 500;
}

.detail-value {
  font-size: 14px;
  color: #1e293b;
  font-weight: 600;
}

.detail-value.expired {
  color: #ef4444;
}

.detail-value.warning {
  color: #f59e0b;
}

.detail-value.valid {
  color: #10b981;
}

.drivers-list {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.driver-tag {
  background: #e0f2fe;
  color: #0369a1;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.vehicle-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  padding-top: 16px;
  border-top: 1px solid #f1f5f9;
}

.stat {
  text-align: center;
}

.stat-label {
  display: block;
  font-size: 12px;
  color: #64748b;
  margin-bottom: 4px;
}

.stat-value {
  font-size: 14px;
  font-weight: 600;
  color: #1e293b;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: #64748b;
}

.empty-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 24px;
  color: #cbd5e1;
}

.empty-state h3 {
  font-size: 24px;
  margin-bottom: 12px;
  color: #1e293b;
}

.empty-state p {
  font-size: 16px;
  margin-bottom: 32px;
}

/* Modal Styles - CORRIGÉ POUR LE CENTRAGE */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  z-index: 1000;
  padding: 20px;
  backdrop-filter: blur(5px);
}

.modal {
  background: white;
  border-radius: 20px;
  max-width: 800px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
  position: relative;
  transform: scale(1);
  animation: modalAppear 0.3s ease-out;
}

@keyframes modalAppear {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.modal-header {
  padding: 24px 32px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8fafc;
  border-radius: 16px 16px 0 0;
}

.modal-header h3 {
  font-size: 24px;
  font-weight: 700;
  color: #1e293b;
}

.modal-close {
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
  border-radius: 6px;
  transition: background-color 0.3s;
}

.modal-close:hover {
  background: #f1f5f9;
}

.close-icon {
  width: 20px;
  height: 20px;
  color: #64748b;
}

.modal-form {
  padding: 32px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 24px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.form-input, .form-textarea {
  padding: 12px 16px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.3s;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.drivers-selection {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
  margin-top: 8px;
}

.driver-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
}

.driver-checkbox input[type="checkbox"] {
  width: 18px;
  height: 18px;
}

.file-upload-area {
  border: 2px dashed #cbd5e1;
  border-radius: 8px;
  padding: 32px;
  text-align: center;
  transition: border-color 0.3s;
}

.file-upload-area:hover {
  border-color: #3b82f6;
}

.file-input {
  display: none;
}

.file-upload-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: #64748b;
}

.upload-icon {
  width: 32px;
  height: 32px;
  color: #3b82f6;
}

.uploaded-files {
  margin-top: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.uploaded-file {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.file-icon {
  width: 20px;
  height: 20px;
  color: #3b82f6;
}

.remove-file {
  background: none;
  border: none;
  padding: 4px;
  cursor: pointer;
  border-radius: 4px;
  margin-left: auto;
}

.remove-file:hover {
  background: #fef2f2;
}

.remove-icon {
  width: 16px;
  height: 16px;
  color: #ef4444;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 16px;
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid #e2e8f0;
}

.btn-secondary {
  background: #f8fafc;
  color: #64748b;
  border: 2px solid #e2e8f0;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-secondary:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
}

/* Documents Modal */
.documents-modal {
  max-width: 600px;
}

.documents-content {
  padding: 32px;
}

.documents-grid {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 24px;
}

.document-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.document-icon {
  color: #3b82f6;
}

.document-info {
  flex: 1;
}

.document-info h4 {
  font-size: 16px;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 4px;
}

.document-info p {
  font-size: 14px;
  color: #64748b;
  margin-bottom: 2px;
}

.document-info small {
  font-size: 12px;
  color: #94a3b8;
}

.document-actions {
  display: flex;
  gap: 8px;
}

.add-document {
  text-align: center;
  padding: 24px;
  border: 2px dashed #cbd5e1;
  border-radius: 8px;
}

/* Responsive */
@media (max-width: 1200px) {
  .vehicles-grid {
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  }
}

@media (max-width: 768px) {
  .sidebar {
    width: 240px;
  }
  
  .main-content {
    margin-left: 240px;
  }
  
  .vehicles-grid {
    grid-template-columns: 1fr;
  }
  
  .filters-section {
    flex-direction: column;
    align-items: stretch;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .vehicles-main {
    padding: 20px;
  }

  .modal {
    margin: 10px;
    max-width: calc(100vw - 20px);
  }
  
  .modal-overlay {
    padding: 10px;
  }
}
</style>
