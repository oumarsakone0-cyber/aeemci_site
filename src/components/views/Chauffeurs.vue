<template>
    <!-- Navigation fixe en haut -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-lg border-b border-gray-200 z-50">
      <div class="max-w-full mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button 
              @click="$router?.go(-1)"
              class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg class="w-7 h-7 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Gestion des Chauffeurs
              </h1>
              <p class="text-sm text-gray-600">Gestion des chauffeurs et conducteurs</p>
            </div>
          </div>
          <div class="flex space-x-3">
            <button 
              @click="activeTab = 'nouveau'"
              class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Nouveau Chauffeur
            </button>
          </div>
        </div>
      </div>
    </nav>
  
    <!-- Contenu principal -->
    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-green-50 to-emerald-50 pt-24 relative overflow-hidden">
      <!-- Animations de fond similaires à carburant.vue -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        
        <!-- Petites animations supplémentaires -->
        <div class="absolute top-10 left-10 w-3 h-3 bg-green-400 rounded-full opacity-30 animate-float-fast"></div>
        <div class="absolute top-32 right-20 w-4 h-4 bg-emerald-500 rounded-full opacity-25 animate-float-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-2 h-2 bg-teal-500 rounded-full opacity-35 animate-float-zigzag"></div>
        <div class="absolute top-1/2 right-10 w-3 h-3 bg-green-500 rounded-full opacity-20 animate-float-spiral"></div>
      </div>
  
      <!-- Conteneur centré sur 1300px -->
      <div class="relative z-10 p-6" style="max-width: 1300px; margin: 0 auto;">
        <!-- Onglets -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
          <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6">
              <button
                @click="activeTab = 'liste'"
                :class="[
                  'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                  activeTab === 'liste' 
                    ? 'border-green-500 text-green-600' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Liste des Chauffeurs
              </button>
              <button
                @click="activeTab = 'nouveau'"
                :class="[
                  'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                  activeTab === 'nouveau' 
                    ? 'border-green-500 text-green-600' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                Nouveau Chauffeur
              </button>
            </nav>
          </div>
  
          <!-- Contenu des onglets -->
          <div class="p-6">
            <!-- Onglet Liste -->
            <div v-if="activeTab === 'liste'" class="space-y-6">
              <!-- Statistiques simplifiées -->
              <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-6 rounded-xl text-white">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-green-100">Total chauffeurs</p>
                        <p class="text-3xl font-bold">{{ stats.totalChauffeurs }}</p>
                      </div>
                      <svg class="w-8 h-8 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                      </svg>
                    </div>
                  </div>
  
                  <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-6 rounded-xl text-white">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-blue-100">Total missions</p>
                        <p class="text-3xl font-bold">{{ stats.totalMissions }}</p>
                      </div>
                      <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                      </svg>
                    </div>
                  </div>
  
                  <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6 rounded-xl text-white">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-purple-100">Moyenne missions</p>
                        <p class="text-3xl font-bold">{{ stats.moyenneMissions }}</p>
                      </div>
                      <svg class="w-8 h-8 text-purple-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Recherche simplifiée -->
              <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-800">Recherche</h3>
                </div>
                
                <div class="max-w-md">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher un chauffeur</label>
                  <input
                    v-model="searchTerm"
                    type="text"
                    placeholder="Nom, prénom ou contact..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                  />
                </div>
              </div>
  
              <!-- Liste des chauffeurs -->
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                  <h3 class="text-lg font-semibold text-gray-800">
                    Chauffeurs ({{ filteredChauffeurs.length }} résultats)
                  </h3>
                </div>
                
                <!-- État de chargement -->
                <div v-if="loadingChauffeurs" class="flex justify-center items-center py-12">
                  <div class="flex items-center space-x-3">
                    <svg class="animate-spin h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Chargement des chauffeurs...</span>
                  </div>
                </div>
                
                <!-- Grille des chauffeurs -->
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                  <div 
                    v-for="chauffeur in filteredChauffeurs" 
                    :key="chauffeur.id" 
                    class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow"
                  >
                    <div class="flex items-start justify-between mb-4">
                      <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-3">
                          <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                          </svg>
                        </div>
                        <div>
                          <h4 class="text-lg font-semibold text-gray-900">{{ chauffeur.prenom }} {{ chauffeur.nom }}</h4>
                          <p class="text-sm text-gray-500">{{ chauffeur.contact }}</p>
                        </div>
                      </div>
                    </div>
  
                    <div class="space-y-2 mb-4">
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Missions effectuées:</span>
                        <span class="font-medium text-green-600">{{ chauffeur.bons_count || 0 }}</span>
                      </div>
                    </div>
  
                    <div class="flex space-x-2">
                      <button
                        @click="editChauffeur(chauffeur)"
                        class="flex-1 px-3 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors"
                      >
                        Modifier
                      </button>
                      <button
                        @click="deleteChauffeur(chauffeur.id)"
                        class="px-3 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                      >
                        Supprimer
                      </button>
                    </div>
                  </div>
                </div>
  
                <!-- Message si aucun chauffeur -->
                <div v-if="!loadingChauffeurs && filteredChauffeurs.length === 0" class="text-center py-12">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun chauffeur trouvé</h3>
                  <p class="mt-1 text-sm text-gray-500">Commencez par ajouter un nouveau chauffeur.</p>
                </div>
              </div>
            </div>
  
            <!-- Onglet Nouveau Chauffeur -->
            <div v-if="activeTab === 'nouveau'" class="space-y-6">
              <h2 class="text-2xl font-semibold text-gray-800 mb-6">Ajouter un nouveau chauffeur</h2>
              
              <div class="max-w-2xl mx-auto">
                <form @submit.prevent="saveChauffeur" class="space-y-6">
                  <!-- Nom -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input
                      v-model="newChauffeur.nom"
                      type="text"
                      
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Ex: Dupont"
                    />
                  </div>
  
                  <!-- Prénom -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                    <input
                      v-model="newChauffeur.prenom"
                      type="text"
                      
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Ex: Jean"
                    />
                  </div>
  
                  <!-- Contact -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact *</label>
                    <input
                      v-model="newChauffeur.contact"
                      type="tel"
                      
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Ex: +33 6 12 34 56 78"
                    />
                  </div>
  
                  <!-- Boutons -->
                  <div class="flex justify-end space-x-4">
                    <button
                      type="button"
                      @click="resetForm"
                      class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
                    >
                      Annuler
                    </button>
                    <button
                      type="submit"
                      style="background-color: green;"
                      :disabled="savingChauffeur"
                      class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center disabled:opacity-50"
                    >
                      <svg v-if="savingChauffeur" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      {{ savingChauffeur ? 'Enregistrement...' : 'Enregistrer le chauffeur' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal Modification Chauffeur -->
      <div v-if="showEditModal && editingChauffeur" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Modifier le chauffeur</h3>
            <button @click="cancelEdit" class="text-gray-400 hover:text-gray-600">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <form @submit.prevent="saveEditChauffeur" class="space-y-6">
            <!-- Nom -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
              <input
                v-model="editingChauffeur.nom"
                type="text"
                
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
  
            <!-- Prénom -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
              <input
                v-model="editingChauffeur.prenom"
                type="text"
                
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
  
            <!-- Contact -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Contact *</label>
              <input
                v-model="editingChauffeur.contact"
                type="tel"
                
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
  
            <!-- Boutons -->
            <div class="flex justify-end space-x-4">
              <button
                type="button"
                @click="cancelEdit"
                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
              >
                Annuler
              </button>
              <button
                type="submit"
                style="background-color: green;"
                :disabled="savingChauffeur"
                class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center disabled:opacity-50"
              >
                <svg v-if="savingChauffeur" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ savingChauffeur ? 'Modification...' : 'Modifier le chauffeur' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, computed, onMounted } from 'vue'
  import axios from 'axios'
  
  // Configuration de l'API
  const API_BASE_URL = 'https://sogetrag.com/api/bons-voyage.php'
  
  // États de chargement
  const loadingChauffeurs = ref(false)
  const savingChauffeur = ref(false)
  
  // État des onglets
  const activeTab = ref('liste')
  
  // État des modals
  const showEditModal = ref(false)
  const editingChauffeur = ref(null)
  
  // Données
  const chauffeurs = ref([])
  const searchTerm = ref('')
  
  // Formulaire nouveau chauffeur (seulement 3 champs)
  const newChauffeur = reactive({
    nom: '',
    prenom: '',
    contact: ''
  })
  
  // Statistiques simplifiées
  const stats = ref({
    totalChauffeurs: 0,
    totalMissions: 0,
    moyenneMissions: 0
  })
  
  // Chauffeurs filtrés
  const filteredChauffeurs = computed(() => {
    let filtered = chauffeurs.value
  
    // Filtre par recherche
    if (searchTerm.value) {
      const search = searchTerm.value.toLowerCase()
      filtered = filtered.filter(c => 
        c.nom.toLowerCase().includes(search) ||
        c.prenom.toLowerCase().includes(search) ||
        c.contact.toLowerCase().includes(search)
      )
    }
  
    return filtered
  })
  
  // Cache buster
  const getCacheBuster = () => {
    return `_t=${Date.now()}&_r=${Math.random().toString(36).substr(2, 9)}`
  }
  
  // Charger les chauffeurs (utilise l'API existante)
  const fetchChauffeurs = async () => {
    loadingChauffeurs.value = true
    
    try {
      const cacheBuster = getCacheBuster()
      const response = await axios.get(`${API_BASE_URL}?action=chauffeurs&${cacheBuster}`)
      
      if (response.data.success) {
        chauffeurs.value = response.data.data
        
        // Calculer les statistiques simplifiées
        const totalMissions = chauffeurs.value.reduce((sum, c) => sum + (c.bons_count || 0), 0)
        stats.value = {
          totalChauffeurs: chauffeurs.value.length,
          totalMissions: totalMissions,
          moyenneMissions: chauffeurs.value.length > 0 ? Math.round(totalMissions / chauffeurs.value.length) : 0
        }
      }
      
    } catch (error) {
      console.error('Erreur lors du chargement des chauffeurs:', error)
      // Données de test
      chauffeurs.value = [
        {
          id: 1,
          nom: 'Dupont',
          prenom: 'Jean',
          contact: '+33 6 12 34 56 78',
          bons_count: 45
        },
        {
          id: 2,
          nom: 'Martin',
          prenom: 'Sophie',
          contact: '+33 6 98 76 54 32',
          bons_count: 32
        }
      ]
      
      stats.value = {
        totalChauffeurs: 2,
        totalMissions: 77,
        moyenneMissions: 39
      }
    } finally {
      loadingChauffeurs.value = false
    }
  }
  
  // Sauvegarder un chauffeur (utilise l'API existante)
  const saveChauffeur = async () => {
    savingChauffeur.value = true
    
    try {
      const chauffeurData = {
        nom: newChauffeur.nom,
        prenom: newChauffeur.prenom,
        contact: newChauffeur.contact
      }
      
      const response = await axios.post(`${API_BASE_URL}?action=create_chauffeur&${getCacheBuster()}`, chauffeurData)
      
      if (response.data.success) {
        resetForm()
        activeTab.value = 'liste'
        alert('Chauffeur enregistré avec succès !')
        await fetchChauffeurs()
      } else {
        throw new Error(response.data.error || 'Erreur lors de la sauvegarde')
      }
      
    } catch (error) {
      console.error('Erreur lors de la sauvegarde:', error)
      alert('Erreur lors de la sauvegarde: ' + (error.response?.data?.error || error.message))
    } finally {
      savingChauffeur.value = false
    }
  }
  
  // Réinitialiser le formulaire
  const resetForm = () => {
    newChauffeur.nom = ''
    newChauffeur.prenom = ''
    newChauffeur.contact = ''
  }
  
  // Modifier un chauffeur
  const editChauffeur = (chauffeur) => {
    editingChauffeur.value = { 
      id: chauffeur.id,
      nom: chauffeur.nom,
      prenom: chauffeur.prenom,
      contact: chauffeur.contact
    }
    showEditModal.value = true
  }
  
  // Sauvegarder les modifications (utilise l'API existante)
  const saveEditChauffeur = async () => {
    if (!editingChauffeur.value) return
    
    try {
      savingChauffeur.value = true
      
      const chauffeurData = {
        nom: editingChauffeur.value.nom,
        prenom: editingChauffeur.value.prenom,
        contact: editingChauffeur.value.contact
      }
      
      const response = await axios.put(`${API_BASE_URL}?action=update_chauffeur&id=${editingChauffeur.value.id}&${getCacheBuster()}`, chauffeurData)
      
      if (response.data.success) {
        showEditModal.value = false
        editingChauffeur.value = null
        alert('Chauffeur modifié avec succès !')
        await fetchChauffeurs()
      } else {
        throw new Error(response.data.error || 'Erreur lors de la modification')
      }
      
    } catch (error) {
      console.error('Erreur lors de la modification:', error)
      alert('Erreur lors de la modification: ' + (error.response?.data?.error || error.message))
    } finally {
      savingChauffeur.value = false
    }
  }
  
  // Annuler la modification
  const cancelEdit = () => {
    showEditModal.value = false
    editingChauffeur.value = null
  }
  
  // Supprimer un chauffeur (utilise l'API existante)
  const deleteChauffeur = async (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce chauffeur ?')) {
      try {
        const response = await axios.delete(`${API_BASE_URL}?action=delete_chauffeur&id=${id}&${getCacheBuster()}`)
        
        if (response.data.success) {
          alert('Chauffeur supprimé avec succès !')
          await fetchChauffeurs()
        } else {
          throw new Error(response.data.error || 'Erreur lors de la suppression')
        }
        
      } catch (error) {
        console.error('Erreur lors de la suppression:', error)
        alert('Erreur lors de la suppression: ' + (error.response?.data?.error || error.message))
      }
    }
  }
  
  // Navigation
  const goBack = () => {
    window.history.back()
  }
  
  // Initialisation
  onMounted(async () => {
    await fetchChauffeurs()
  })
  </script>
  
  <style scoped>
  /* Animations blob similaires à carburant.vue */
  @keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
  }
  
  .animate-blob {
    animation: blob 7s infinite;
  }
  
  .animation-delay-2000 {
    animation-delay: 2s;
  }
  
  .animation-delay-4000 {
    animation-delay: 4s;
  }
  
  /* Animations supplémentaires */
  @keyframes float-fast {
    0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
    25% { transform: translateY(-15px) rotate(90deg) scale(1.1); }
    50% { transform: translateY(-30px) rotate(180deg) scale(0.9); }
    75% { transform: translateY(-15px) rotate(270deg) scale(1.1); }
  }
  
  @keyframes float-bounce {
    0%, 100% { transform: translateY(0px) scale(1); }
    25% { transform: translateY(-20px) scale(1.2); }
    50% { transform: translateY(-40px) scale(0.8); }
    75% { transform: translateY(-20px) scale(1.2); }
  }
  
  @keyframes float-zigzag {
    0% { transform: translateX(0px) translateY(0px) rotate(0deg); }
    25% { transform: translateX(15px) translateY(-15px) rotate(90deg); }
    50% { transform: translateX(-15px) translateY(-30px) rotate(180deg); }
    75% { transform: translateX(15px) translateY(-15px) rotate(270deg); }
    100% { transform: translateX(0px) translateY(0px) rotate(360deg); }
  }
  
  @keyframes float-spiral {
    0% { transform: translateX(0px) translateY(0px) rotate(0deg) scale(1); }
    25% { transform: translateX(20px) translateY(-10px) rotate(90deg) scale(1.3); }
    50% { transform: translateX(0px) translateY(-20px) rotate(180deg) scale(0.7); }
    75% { transform: translateX(-20px) translateY(-10px) rotate(270deg) scale(1.3); }
    100% { transform: translateX(0px) translateY(0px) rotate(360deg) scale(1); }
  }
  
  .animate-float-fast { animation: float-fast 3s ease-in-out infinite; }
  .animate-float-bounce { animation: float-bounce 2.5s ease-in-out infinite; }
  .animate-float-zigzag { animation: float-zigzag 4s ease-in-out infinite; }
  .animate-float-spiral { animation: float-spiral 5s ease-in-out infinite; }
  </style>