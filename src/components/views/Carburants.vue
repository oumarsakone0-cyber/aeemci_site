<template>
  <div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 relative overflow-hidden">
    <!-- Animations de fond -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
      <div class="absolute top-40 left-40 w-80 h-80 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 p-6" style="max-width: 1300px; margin: 0 auto;">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
          <button @click="$router.go(-1)" class="p-2 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
          <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
              <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 716.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0720 13a7.975 7.975 0 01-2.343 5.657z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
              </svg>
              Gestion Carburants
            </h1>
            <p class="text-gray-600">Suivi de la consommation de carburant</p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <button style="background-color: green;" @click="exportExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Excel</span>
          </button>
          <button style="background-color: #ab7078;" @click="exportPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <span>PDF</span>
          </button>
          <button style="background: gray" @click="showAddModal = true" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Ajouter Carburant</span>
          </button>
        </div>
      </div>

      <!-- Statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Entrées</p>
              <p class="text-2xl font-bold text-gray-900">{{ pagination.total || 0 }}</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Litres</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_litres || 0 }}L</p>
            </div>
            <div class="p-3 bg-amber-100 rounded-full">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 716.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0720 13a7.975 7.975 0 01-2.343 5.657z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Ce Mois</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.month_litres || 0 }}L</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Véhicules Actifs</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.active_vehicles || 0 }}</p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtres avancés -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
            <input
              v-model="filters.search"
              @input="debounceSearch"
              type="text"
              placeholder="Rechercher par véhicule..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Véhicule</label>
            <div class="relative">
              <div 
                @click="toggleVehicleDropdown"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent cursor-pointer bg-white flex items-center justify-between"
                :class="{ 'ring-2 ring-orange-500 border-transparent': showVehicleDropdown }"
              >
                <span v-if="selectedVehicleText" class="text-gray-900">{{ selectedVehicleText }}</span>
                <span v-else class="text-gray-500">Tous les véhicules</span>
                <div class="flex items-center space-x-1">
                  <button 
                    v-if="filters.vehicule_id" 
                    @click.stop="clearVehicleSelection"
                    class="text-gray-400 hover:text-gray-600 p-1"
                    type="button"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                  <svg 
                    class="w-4 h-4 text-gray-400 transition-transform duration-200"
                    :class="{ 'rotate-180': showVehicleDropdown }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>
              
              <div 
                v-if="showVehicleDropdown" 
                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
              >
                <div class="p-2 border-b border-gray-200">
                  <input
                    ref="vehicleSearchInput"
                    v-model="vehicleSearchTerm"
                    type="text"
                    placeholder="Rechercher un véhicule..."
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    @click.stop
                  >
                </div>
                
                <div class="max-h-48 overflow-y-auto">
                  <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-500" @click="selectVehicle('', 'Tous les véhicules')">
                    Tous les véhicules
                  </div>
                  
                  <div 
                    v-for="vehicule in filteredVehiclesForDropdown" 
                    :key="vehicule.id"
                    @click="selectVehicle(vehicule.id, vehicule.numero_immatricule)"
                    class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                    :class="{ 'bg-orange-100 text-orange-800': filters.vehicule_id === vehicule.id }"
                  >
                    <span>{{ vehicule.numero_immatricule }}</span>
                    <svg 
                      v-if="filters.vehicule_id === vehicule.id"
                      class="w-4 h-4 text-orange-600" 
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  
                  <div v-if="filteredVehiclesForDropdown.length === 0" class="px-4 py-2 text-gray-500 text-center">
                    Aucun véhicule trouvé
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
            <input
              v-model="filters.date_debut"
              @change="applyFilters"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
            <input
              v-model="filters.date_fin"
              @change="applyFilters"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <label class="block text-sm font-medium text-gray-700">Éléments par page:</label>
            <select v-model="pagination.per_page" @change="changePerPage" class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
          <button @click="resetFilters" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>Réinitialiser</span>
          </button>
        </div>
      </div>

      <!-- Tableau des carburants -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800">Liste des Carburants</h3>
          <div class="text-sm text-gray-500">
            Affichage {{ pagination.from || 0 }} à {{ pagination.to || 0 }} sur {{ pagination.total || 0 }} entrées
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Véhicule</span>
                  </div>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Litrage</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                  <div class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Chargement...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="carburants.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                  <div class="flex flex-col items-center space-y-2">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <span>Aucun carburant trouvé</span>
                  </div>
                </td>
              </tr>
              <tr v-else v-for="carburant in carburants" :key="carburant.id" class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ carburant.vehicule }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    {{ carburant.litrage }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(carburant.date_carburant) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ carburant.bl }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-2">
                    <button @click="viewCarburant(carburant)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                    <button @click="editCarburant(carburant)" class="text-orange-600 hover:text-orange-900 p-1 rounded hover:bg-orange-50 transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button @click="deleteCarburant(carburant)" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          
          <!-- Pagination -->
          <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div class="flex items-center">
              <span class="text-sm text-gray-700">
                Affichage de <span class="font-medium">{{ pagination.from }}</span> à <span class="font-medium">{{ pagination.to }}</span> sur <span class="font-medium">{{ pagination.total }}</span> résultats
              </span>
            </div>
            <div class="flex items-center space-x-2">
              <button 
                @click="changePage(pagination.current_page - 1)" 
                :disabled="pagination.current_page === 1"
                class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Précédent
              </button>
              <div class="flex items-center space-x-1">
                <template v-for="page in paginationPages" :key="page">
                  <button 
                    @click="changePage(page)" 
                    :class="[
                      'px-3 py-1 rounded-md',
                      pagination.current_page === page 
                        ? 'bg-orange-600 text-white' 
                        : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                </template>
              </div>
              <button 
                @click="changePage(pagination.current_page + 1)" 
                :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Suivant
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Ajouter/Modifier Carburant -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-100">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 716.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0720 13a7.975 7.975 0 01-2.343 5.657z"></path>
            </svg>
            {{ showAddModal ? 'Ajouter Carburant' : 'Modifier Carburant' }}
          </h3>
        </div>
        
        <form @submit.prevent="showAddModal ? addCarburant() : updateCarburant()" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
            <div class="relative">
              <div 
                @click="toggleModalVehicleDropdown"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent cursor-pointer bg-white flex items-center justify-between"
                :class="{ 'ring-2 ring-orange-500 border-transparent': showModalVehicleDropdown }"
              >
                <span v-if="selectedModalVehicleText" class="text-gray-900">{{ selectedModalVehicleText }}</span>
                <span v-else class="text-gray-500">Sélectionner un véhicule</span>
                <div class="flex items-center space-x-1">
                  <button 
                    v-if="form.vehicule_id" 
                    @click.stop="clearModalVehicleSelection"
                    class="text-gray-400 hover:text-gray-600 p-1"
                    type="button"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                  <svg 
                    class="w-4 h-4 text-gray-400 transition-transform duration-200"
                    :class="{ 'rotate-180': showModalVehicleDropdown }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>
              
              <div 
                v-if="showModalVehicleDropdown" 
                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
              >
                <div class="p-2 border-b border-gray-200">
                  <input
                    ref="modalVehicleSearchInput"
                    v-model="modalVehicleSearchTerm"
                    type="text"
                    placeholder="Rechercher un véhicule..."
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    @click.stop
                  >
                </div>
                
                <div class="max-h-48 overflow-y-auto">
                  <div 
                    v-for="vehicule in filteredVehiclesForModal" 
                    :key="vehicule.id"
                    @click="selectModalVehicle(vehicule.id, vehicule.numero_immatricule)"
                    class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                    :class="{ 'bg-orange-100 text-orange-800': form.vehicule_id === vehicule.id }"
                  >
                    <span>{{ vehicule.numero_immatricule }}</span>
                    <svg 
                      v-if="form.vehicule_id === vehicule.id"
                      class="w-4 h-4 text-orange-600" 
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  
                  <div v-if="filteredVehiclesForModal.length === 0" class="px-4 py-2 text-gray-500 text-center">
                    Aucun véhicule trouvé
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">BL </label>
            <input
              v-model="form.bl"
              type="text"
              placeholder="numéro Bl"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Litrage *</label>
            <input
              v-model="form.litrage"
              type="number"
              step="0.01"
              min="0"
              placeholder="Ex: 50.5"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
            <input
              v-model="form.date_carburant"
              type="date"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
              Annuler
            </button>
            <button type="submit" :disabled="submitting" style="background-color: green;" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 flex items-center space-x-2">
              <svg v-if="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ showAddModal ? 'Ajouter' : 'Modifier' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Voir Carburant -->
    <div v-if="showViewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Détails du Carburant
          </h3>
        </div>
        
        <div v-if="selectedCarburant" class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-500">Véhicule</label>
              <p class="text-lg font-semibold text-gray-900">{{ selectedCarburant.vehicule }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-500">Litrage</label>
              <p class="text-lg font-semibold text-amber-600">{{ selectedCarburant.litrage }}L</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-500">Date</label>
              <p class="text-lg font-semibold text-gray-900">{{ formatDate(selectedCarburant.date_carburant) }}</p>
            </div>
          </div>
          
          <div class="flex justify-end pt-4">
            <button @click="showViewModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Messages -->
    <div v-if="alert.show" :class="[
      'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300',
      alert.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    ]">
      <div class="flex items-center space-x-2">
        <svg v-if="alert.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span>{{ alert.message }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Carburants',
  components: {
  },
  data() {
    return {
      API_BASE_URL: 'https://sogetrag.com/api/carburants.php',
      carburants: [],
      vehicules: [],
      loading: false,
      submitting: false,
      exporting: false,
      showAddModal: false,
      showEditModal: false,
      showViewModal: false,
      selectedCarburant: null,
      searchTimeout: null,
      stats: {
        total_entries: 0,
        total_litres: 0,
        month_litres: 0,
        active_vehicles: 0
      },
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
        from: 0,
        to: 0
      },
      filters: {
        search: '',
        vehicule_id: '',
        date_debut: '',
        date_fin: ''
      },
      form: {
        vehicule_id: '',
        litrage: '',
        bl: '',
        date_carburant: new Date().toISOString().split('T')[0]
      },
      alert: {
        show: false,
        type: 'success',
        message: ''
      },
      showVehicleDropdown: false,
      vehicleSearchTerm: '',
      showModalVehicleDropdown: false,
      modalVehicleSearchTerm: '',
    }
  },
  computed: {
    visiblePages() {
      const current = this.pagination.current_page;
      const last = this.pagination.last_page;
      const pages = [];
      
      if (last <= 7) {
        for (let i = 1; i <= last; i++) {
          pages.push(i);
        }
      } else {
        if (current <= 4) {
          for (let i = 1; i <= 5; i++) {
            pages.push(i);
          }
          pages.push('...');
          pages.push(last);
        } else if (current >= last - 3) {
          pages.push(1);
          pages.push('...');
          for (let i = last - 4; i <= last; i++) {
            pages.push(i);
          }
        } else {
          pages.push(1);
          pages.push('...');
          for (let i = current - 1; i <= current + 1; i++) {
            pages.push(i);
          }
          pages.push('...');
          pages.push(last);
        }
      }
      
      return pages;
    },
    paginationPages() {
      const pages = [];
      const maxPages = 5;
      
      if (this.pagination.last_page <= maxPages) {
        for (let i = 1; i <= this.pagination.last_page; i++) {
          pages.push(i);
        }
      } else {
        let startPage = Math.max(1, this.pagination.current_page - Math.floor(maxPages / 2));
        let endPage = Math.min(this.pagination.last_page, startPage + maxPages - 1);
        
        if (endPage - startPage + 1 < maxPages) {
          startPage = Math.max(1, endPage - maxPages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
          pages.push(i);
        }
      }
      
      return pages;
    },
    selectedVehicleText() {
      if (this.filters.vehicule_id) {
        const vehicle = this.vehicules.find(v => v.id == this.filters.vehicule_id)
        return vehicle ? vehicle.numero_immatricule : ''
      }
      return ''
    },
    selectedModalVehicleText() {
      if (this.form.vehicule_id) {
        const vehicle = this.vehicules.find(v => v.id == this.form.vehicule_id)
        return vehicle ? vehicle.numero_immatricule : ''
      }
      return ''
    },
    filteredVehiclesForDropdown() {
      if (!this.vehicleSearchTerm) {
        return this.vehicules
      }
      return this.vehicules.filter(vehicle => 
        vehicle.numero_immatricule.toLowerCase().includes(this.vehicleSearchTerm.toLowerCase())
      )
    },
    filteredVehiclesForModal() {
      if (!this.modalVehicleSearchTerm) {
        return this.vehicules
      }
      return this.vehicules.filter(vehicle => 
        vehicle.numero_immatricule.toLowerCase().includes(this.modalVehicleSearchTerm.toLowerCase())
      )
    }
  },
  mounted() {
    this.loadData();
    document.addEventListener('click', this.handleClickOutside)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  },
  methods: {
    handleClickOutside(event) {
      if (!event.target.closest('.relative')) {
        this.showVehicleDropdown = false
        this.showModalVehicleDropdown = false
      }
    },
    // Méthodes de pagination et filtres manquantes
    async changePage(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.pagination.current_page = page;
      await this.loadCarburants();
    },

    async changePerPage() {
      this.pagination.current_page = 1;
      await this.loadCarburants();
    },

    async applyFilters() {
      this.pagination.current_page = 1;
      await this.loadCarburants();
    },

    debounceSearch() {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      this.searchTimeout = setTimeout(() => {
        this.applyFilters();
      }, 500);
    },

    resetFilters() {
      this.filters = {
        search: '',
        vehicule_id: '',
        date_debut: '',
        date_fin: ''
      };
      this.pagination.current_page = 1;
      this.loadCarburants();
    },
    async loadData() {
      this.loading = true;
      try {
        await Promise.all([
          this.loadCarburants(),
          this.loadVehicules(),
          this.loadStats()
        ]);
      } catch (error) {
        console.error('Erreur lors du chargement:', error);
        this.showAlert('error', 'Erreur lors du chargement des données');
      } finally {
        this.loading = false;
      }
    },

    async loadCarburants() {
  try {
    let random = Math.random(); // Génère un nombre aléatoire entre 0 et 1
    let url = `${this.API_BASE_URL}?action=carburants&page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&rand=${random}`;
    
    if (this.filters.search) {
      url += `&search=${encodeURIComponent(this.filters.search)}`;
    }
    if (this.filters.vehicule_id) {
      url += `&vehicule_id=${this.filters.vehicule_id}`;
    }
    if (this.filters.date_debut) {
      url += `&date_debut=${this.filters.date_debut}`;
    }
    if (this.filters.date_fin) {
      url += `&date_fin=${this.filters.date_fin}`;
    }
    
    const response = await fetch(url);
    const data = await response.json();
    
    if (data.success) {
      this.carburants = data.data || [];
      if (data.pagination) {
        this.pagination = {
          ...this.pagination,
          ...data.pagination
        };
      }
    } else {
      throw new Error(data.error || 'Erreur lors du chargement des carburants');
    }
  } catch (error) {
    console.error('Erreur loadCarburants:', error);
    this.showAlert('error', 'Erreur lors du chargement des carburants');
  }
},

async loadVehicules() {
  try {
    const response = await fetch(`${this.API_BASE_URL}?action=camions&rand=${Math.random()}`);
    const data = await response.json();
    
    if (data.success) {
      this.vehicules = data.data || [];
    } else {
      throw new Error(data.error || 'Erreur lors du chargement des véhicules');
    }
  } catch (error) {
    console.error('Erreur loadVehicules:', error);
    this.showAlert('error', 'Erreur lors du chargement des véhicules');
  }
},

async loadStats() {
  try {
    const response = await fetch(`${this.API_BASE_URL}?action=stats&rand=${Math.random()}`);
    const data = await response.json();
    
    if (data.success) {
      this.stats = data.data || this.stats;
    }
  } catch (error) {
    console.error('Erreur loadStats:', error);
  }
},

async exportExcel() {
  try {
    this.exporting = true
    this.showAlert('success', 'Génération du fichier Excel en cours...')
    
    // Utiliser les données déjà chargées dans le tableau
    if (this.carburants.length === 0) {
      this.showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des carburants.')
      return
    }
    
    const data = this.carburants.map(carburant => ({
      'Véhicule': carburant.vehicule,
      'Litrage (L)': carburant.litrage,
      'Date': this.formatDate(carburant.date_carburant),
      'Date d\'ajout': carburant.created_at ? this.formatDate(carburant.created_at) : ''
    }))
    
    // Créer le workbook Excel
    const XLSX = await import('xlsx')
    const workbook = XLSX.utils.book_new()
    const worksheet = XLSX.utils.json_to_sheet(data)
    
    // Ajuster la largeur des colonnes
    const colWidths = [
      { wch: 20 }, { wch: 15 }, { wch: 15 }, { wch: 20 }
    ]
    worksheet['!cols'] = colWidths
    
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Carburants')
    
    // Télécharger le fichier
    const today = new Date().toISOString().split('T')[0]
    const filename = `carburants_export_${today}.xlsx`
    XLSX.writeFile(workbook, filename)
    
    this.showAlert('success', `Export Excel réussi ! ${data.length} enregistrements exportés.`)
    
  } catch (error) {
    console.error('Erreur lors de l\'export Excel:', error)
    this.showAlert('error', 'Erreur lors de l\'export Excel. Vérifiez que la bibliothèque xlsx est installée.')
  } finally {
    this.exporting = false
  }
},

async exportPDF() {
  try {
    this.exporting = true
    this.showAlert('success', 'Génération du PDF en cours...')
    
    // Utiliser les données déjà chargées
    if (this.carburants.length === 0) {
      this.showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des carburants.')
      return
    }
    
    const data = this.carburants
    
    // Calculer les statistiques pour le résumé
    const totalEntries = data.length
    const totalLitres = data.reduce((sum, carburant) => sum + (carburant.litrage || 0), 0)
    const vehiculesUniques = [...new Set(data.map(carburant => carburant.vehicule))].filter(Boolean)
    
    // Importer jsPDF dynamiquement
    const { jsPDF } = await import('jspdf')
    const autoTable = (await import('jspdf-autotable')).default
    
    // Créer le PDF
    const doc = new jsPDF()
    
    // En-tête
    doc.setFontSize(20)
    doc.setTextColor(40, 40, 40)
    doc.text('RAPPORT CARBURANTS', 105, 20, { align: 'center' })
    
    // Date du rapport
    doc.setFontSize(10)
    doc.setTextColor(100, 100, 100)
    const today = new Date().toLocaleDateString('fr-FR', { 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
    doc.text(`Généré le ${today}`, 105, 30, { align: 'center' })
    
    // Résumé en haut
    doc.setFontSize(14)
    doc.setTextColor(40, 40, 40)
    doc.text('RÉSUMÉ EXÉCUTIF', 20, 45)
    
    doc.setFontSize(11)
    doc.setTextColor(60, 60, 60)
    
    // Cadre pour le résumé
    doc.setDrawColor(200, 200, 200)
    doc.setFillColor(248, 249, 250)
    doc.rect(15, 50, 180, 35, 'FD')
    
    // Contenu du résumé
    doc.setTextColor(40, 40, 40)
    doc.text(`Total des entrées: ${totalEntries}`, 20, 60)
    doc.text(`Total litres consommés: ${totalLitres.toFixed(2)} L`, 20, 68)
    doc.text(`Véhicules concernés: ${vehiculesUniques.length > 0 ? vehiculesUniques.join(', ') : 'Aucun'}`, 20, 76)
    
    // Tableau des données
    const tableData = data.map(carburant => [
      carburant.vehicule || '',
      `${carburant.litrage || 0} L`,
      this.formatDate(carburant.date_carburant) || '',
      carburant.created_at ? this.formatDate(carburant.created_at) : ''
    ])
    
    autoTable(doc, {
      head: [['Véhicule', 'Litrage', 'Date', 'Date d\'ajout']],
      body: tableData,
      startY: 95,
      styles: {
        fontSize: 10,
        cellPadding: 3
      },
      headStyles: {
        fillColor: [255, 140, 0],
        textColor: 255,
        fontStyle: 'bold'
      },
      alternateRowStyles: {
        fillColor: [248, 249, 250]
      },
      columnStyles: {
        0: { cellWidth: 50 }, 
        1: { cellWidth: 30 }, 
        2: { cellWidth: 40 }, 
        3: { cellWidth: 40 }
      }
    })
    
    // Pied de page
    const pageCount = doc.internal.getNumberOfPages()
    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i)
      doc.setFontSize(8)
      doc.setTextColor(100, 100, 100)
      doc.text(`Page ${i} sur ${pageCount}`, 105, 290, { align: 'center' })
      doc.text('SOGETRAG - Système de Gestion des Carburants', 105, 285, { align: 'center' })
    }
    
    // Télécharger le PDF
    const filename = `rapport_carburants_${new Date().toISOString().split('T')[0]}.pdf`
    doc.save(filename)
    
    this.showAlert('success', `Export PDF réussi ! ${totalEntries} enregistrements exportés.`)
    
  } catch (error) {
    console.error('Erreur lors de l\'export PDF:', error)
    this.showAlert('error', 'Erreur lors de l\'export PDF. Vérifiez que les bibliothèques jspdf et jspdf-autotable sont installées.')
  } finally {
    this.exporting = false
  }
},

async addCarburant() {
  if (!this.form.vehicule_id || !this.form.litrage || !this.form.date_carburant) {
    this.showAlert('error', 'Veuillez remplir tous les champs obligatoires');
    return;
  }

  this.submitting = true;
  try {
    const response = await fetch(`${this.API_BASE_URL}?action=create_carburant`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.form)
    });

    const data = await response.json();
    
    if (data.success) {
      this.showAlert('success', 'Carburant ajouté avec succès');
      this.closeModal();
      await this.loadData();
    } else {
      throw new Error(data.error || 'Erreur lors de l\'ajout');
    }
  } catch (error) {
    console.error('Erreur addCarburant:', error);
    this.showAlert('error', error.message || 'Erreur lors de l\'ajout du carburant');
  } finally {
    this.submitting = false;
  }
},

async updateCarburant() {
  if (!this.form.vehicule_id || !this.form.litrage || !this.form.date_carburant) {
    this.showAlert('error', 'Veuillez remplir tous les champs obligatoires');
    return;
  }

  this.submitting = true;
  try {
    const response = await fetch(`${this.API_BASE_URL}?action=update_carburant&id=${this.selectedCarburant.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.form)
    });

    const data = await response.json();
    
    if (data.success) {
      this.showAlert('success', 'Carburant modifié avec succès');
      this.closeModal();
      await this.loadData();
    } else {
      throw new Error(data.error || 'Erreur lors de la modification');
    }
  } catch (error) {
    console.error('Erreur updateCarburant:', error);
    this.showAlert('error', error.message || 'Erreur lors de la modification du carburant');
  } finally {
    this.submitting = false;
  }
},

async deleteCarburant(carburant) {
  if (!confirm(`Êtes-vous sûr de vouloir supprimer ce carburant (${carburant.vehicule} - ${carburant.litrage}L) ?`)) {
    return;
  }

  try {
    const response = await fetch(`${this.API_BASE_URL}?action=delete_carburant&id=${carburant.id}`, {
      method: 'DELETE'
    });

    const data = await response.json();
    
    if (data.success) {
      this.showAlert('success', 'Carburant supprimé avec succès');
      await this.loadData();
    } else {
      throw new Error(data.error || 'Erreur lors de la suppression');
    }
  } catch (error) {
    console.error('Erreur deleteCarburant:', error);
    this.showAlert('error', error.message || 'Erreur lors de la suppression du carburant');
  }
},

    viewCarburant(carburant) {
      this.selectedCarburant = carburant;
      this.showViewModal = true;
    },

    editCarburant(carburant) {
      this.selectedCarburant = carburant;
      this.form = {
        vehicule_id: carburant.vehicule_id,
        litrage: carburant.litrage,
        bl: carburant.bl,
        date_carburant: carburant.date_carburant
      };
      this.showEditModal = true;
    },

    closeModal() {
      this.showAddModal = false;
      this.showEditModal = false;
      this.showViewModal = false;
      this.selectedCarburant = null;
      this.form = {
        vehicule_id: '',
        litrage: '',
        bl: '',
        date_carburant: new Date().toISOString().split('T')[0]
      };
    },

    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },

    showAlert(type, message) {
      this.alert = {
        show: true,
        type,
        message
      };
      
      setTimeout(() => {
        this.alert.show = false;
      }, 5000);
    },
    toggleVehicleDropdown() {
      this.showVehicleDropdown = !this.showVehicleDropdown
      if (this.showVehicleDropdown) {
        this.vehicleSearchTerm = ''
        this.$nextTick(() => {
          this.$refs.vehicleSearchInput?.focus()
        })
      }
    },
    selectVehicle(id, text) {
      this.filters.vehicule_id = id
      this.showVehicleDropdown = false
      this.vehicleSearchTerm = ''
      this.applyFilters()
    },
    clearVehicleSelection() {
      this.filters.vehicule_id = ''
      this.vehicleSearchTerm = ''
      this.applyFilters()
    },
    toggleModalVehicleDropdown() {
      this.showModalVehicleDropdown = !this.showModalVehicleDropdown
      if (this.showModalVehicleDropdown) {
        this.modalVehicleSearchTerm = ''
        this.$nextTick(() => {
          this.$refs.modalVehicleSearchInput?.focus()
        })
      }
    },
    selectModalVehicle(id, text) {
      this.form.vehicule_id = id
      this.showModalVehicleDropdown = false
      this.modalVehicleSearchTerm = ''
    },
    clearModalVehicleSelection() {
      this.form.vehicule_id = ''
      this.modalVehicleSearchTerm = ''
    },
    handleClickOutside(event) {
      if (!event.target.closest('.relative')) {
        this.showVehicleDropdown = false
        this.showModalVehicleDropdown = false
      }
    }
  },
  watch: {
    'filters.search': function(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.pagination.current_page = 1;
        this.loadCarburants();
      }
    },
    'filters.vehicule_id': function(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.pagination.current_page = 1;
        this.loadCarburants();
      }
    }
  }
}
</script>

<style scoped>
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
</style>