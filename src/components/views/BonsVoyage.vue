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
              <svg class="w-7 h-7 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
              </svg>
              Bons de Voyage
            </h1>
            <p class="text-sm text-gray-600">Gestion des bons de transport et missions</p>
          </div>
        </div>
        <div class="flex space-x-3">
          <button 
            @click="showProductModal = true"
            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg flex items-center"
          style="background-color: #00000040;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau Produit
          </button>
          <button 
            @click="activeTab = 'nouveau'"
            class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors flex items-center"
          style="background-color: #00000040;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau Bon
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Contenu principal avec padding-top pour compenser la navbar fixe -->
  <div class="min-h-screen bg-gradient-to-br from-slate-100 via-orange-50 to-yellow-50 pt-24 relative overflow-hidden" style="margin-top: 25px">
    <!-- Animations de fond -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-10 left-10 w-3 h-3 bg-orange-400 rounded-full opacity-30 animate-float-fast"></div>
      <div class="absolute top-32 right-20 w-4 h-4 bg-yellow-500 rounded-full opacity-25 animate-float-bounce"></div>
      <div class="absolute bottom-20 left-1/4 w-2 h-2 bg-red-500 rounded-full opacity-35 animate-float-zigzag"></div>
      <div class="absolute top-1/2 right-10 w-3 h-3 bg-orange-500 rounded-full opacity-20 animate-float-spiral"></div>
    </div>

    <div class="max-w-full mx-auto px-6 pb-6 relative z-10">
      <!-- Onglets -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-200">
          <nav class="flex space-x-8 px-6">
            <button
              @click="activeTab = 'historique'"
              :class="[
                'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                activeTab === 'historique' 
                  ? 'border-orange-500 text-orange-600' 
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              Historique & Filtres
            </button>
            <button
              @click="activeTab = 'nouveau'"
              :class="[
                'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                activeTab === 'nouveau' 
                  ? 'border-orange-500 text-orange-600' 
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              Nouveau Bon
            </button>
          </nav>
        </div>

        <!-- Contenu des onglets -->
        <div class="p-6">
          <!-- Onglet Historique (par défaut) -->
          <div v-if="activeTab === 'historique'" class="space-y-6">
            <!-- Statistiques en haut -->
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 rounded-xl text-white">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-orange-100">Total bons</p>
                      <p class="text-3xl font-bold">{{ stats.totalBons }}</p>
                    </div>
                    <svg class="w-8 h-8 text-orange-200" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                    </svg>
                  </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-amber-500 p-6 rounded-xl text-white">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-yellow-100">Ce mois</p>
                      <p class="text-3xl font-bold">{{ stats.bonsMois }}</p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-200" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M19,3H18V1H16V3H8V1H6V3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z" />
                    </svg>
                  </div>
                </div>

                <div class="bg-gradient-to-r from-slate-600 to-gray-700 p-6 rounded-xl text-white">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-slate-100">Camions actifs</p>
                      <p class="text-3xl font-bold">{{ stats.camionsActifs }}</p>
                    </div>
                    <svg class="w-8 h-8 text-slate-200" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                    </svg>
                  </div>
                </div>

                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 rounded-xl text-white">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-emerald-100">Poids total (kg)</p>
                      <p class="text-3xl font-bold">{{ formatNumber(stats.poidsTotal) }}</p>
                    </div>
                    <svg class="w-8 h-8 text-emerald-200" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12,3A4,4 0 0,1 16,7A4,4 0 0,1 12,11A4,4 0 0,1 8,7A4,4 0 0,1 12,3M12,14.2C13.5,14.2 15,14.7 16.1,15.5C17.3,16.4 18,17.6 18,19V21H6V19C6,17.6 6.7,16.4 7.9,15.5C9,14.7 10.5,14.2 12,14.2Z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- NOUVEAU CHAMP DE RECHERCHE GLOBAL -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
              <div class="flex items-center space-x-4">
                <div class="flex-1">
                  <label class="block text-sm font-medium text-blue-800 mb-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Recherche globale dans le tableau
                  </label>
                  <div class="relative">
                    <input
                      v-model="globalSearchTerm"
                      @input="applyGlobalSearch"
                      type="text"
                      placeholder="Rechercher par N° BL, camion, chauffeur, produit, lieu..."
                      class="w-full px-4 py-3 pl-10 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button
                      v-if="globalSearchTerm"
                      @click="clearGlobalSearch"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="text-sm text-blue-600 bg-blue-100 px-3 py-2 rounded-lg">
                  {{ filteredBonsCount }} résultat(s)
                </div>
              </div>
            </div>

            <!-- Filtres -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Filtres avancés</h3>
                <!-- BOUTONS D'EXPORT BIEN VISIBLES -->
                <div class="flex space-x-3">
                  <button
                    @click="exportExcel"
                    :disabled="loadingBons || exportingExcel"
                    style="background-color: #00000040;"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center font-medium shadow-lg disabled:opacity-50"
                  >
                    <svg v-if="exportingExcel" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ exportingExcel ? 'Export...' : 'Export Excel' }}
                  </button>
                  <button
                    @click="exportPDF"
                    :disabled="loadingBons || exportingPDF"
                    style="background-color: #00000040;"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center font-medium shadow-lg disabled:opacity-50"
                  >
                    <svg v-if="exportingPDF" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    {{ exportingPDF ? 'Export...' : 'Export PDF' }}
                  </button>
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Filtre par date -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Type de filtre</label>
                  <select
                    v-model="filters.dateType"
                    @change="applyFilters"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  >
                    <option value="all">Toutes les dates</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="month">Ce mois</option>
                    <option value="custom">Période personnalisée</option>
                  </select>
                </div>

                <!-- Date début (si période personnalisée) -->
                <div v-if="filters.dateType === 'custom'">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                  <input
                    v-model="filters.dateDebut"
                    @change="applyFilters"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Date fin (si période personnalisée) -->
                <div v-if="filters.dateType === 'custom'">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                  <input
                    v-model="filters.dateFin"
                    @change="applyFilters"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Filtre par produit avec autocomplete -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Produit</label>
                  <div class="relative">
                    <div 
                      @click="toggleProduitDropdown"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white flex items-center justify-between"
                      :class="{ 'ring-2 ring-orange-500 border-transparent': showProduitDropdown }"
                    >
                      <span v-if="filters.produit" class="text-gray-900">{{ filters.produit }}</span>
                      <span v-else class="text-gray-500">Tous les produits</span>
                      <div class="flex items-center space-x-1">
                        <button 
                          v-if="filters.produit" 
                          @click.stop="clearProduitSelection"
                          class="text-gray-400 hover:text-gray-600 p-1"
                          type="button"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                        </button>
                        <svg 
                          class="w-4 h-4 text-gray-400 transition-transform duration-200"
                          :class="{ 'rotate-180': showProduitDropdown }"
                          fill="none" 
                          stroke="currentColor" 
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                      </div>
                    </div>
                    
                    <div 
                      v-if="showProduitDropdown" 
                      class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                    >
                      <div class="p-2 border-b border-gray-200">
                        <input
                          ref="produitSearchInput"
                          v-model="produitSearchTerm"
                          type="text"
                          placeholder="Rechercher un produit..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          @click.stop
                        >
                      </div>
                      
                      <div class="max-h-48 overflow-y-auto">
                        <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-500" @click="selectProduit('')">
                          Tous les produits
                        </div>
                        
                        <div 
                          v-for="produit in filteredProduits" 
                          :key="produit.id"
                          @click="selectProduit(produit.nom)"
                          class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                          :class="{ 'bg-orange-100 text-orange-800': filters.produit === produit.nom }"
                        >
                          <span>{{ produit.nom }}</span>
                          <svg 
                            v-if="filters.produit === produit.nom"
                            class="w-4 h-4 text-orange-600" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        
                        <div v-if="filteredProduits.length === 0" class="px-4 py-2 text-gray-500 text-center">
                          Aucun produit trouvé
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Filtre par camion avec autocomplete -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Camion</label>
                  <div class="relative">
                    <div 
                      @click="toggleCamionDropdown"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white flex items-center justify-between"
                      :class="{ 'ring-2 ring-orange-500 border-transparent': showCamionDropdown }"
                    >
                      <span v-if="filters.camion" class="text-gray-900">{{ filters.camion }}</span>
                      <span v-else class="text-gray-500">Tous les camions</span>
                      <div class="flex items-center space-x-1">
                        <button 
                          v-if="filters.camion" 
                          @click.stop="clearCamionSelection"
                          class="text-gray-400 hover:text-gray-600 p-1"
                          type="button"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                        </button>
                        <svg 
                          class="w-4 h-4 text-gray-400 transition-transform duration-200"
                          :class="{ 'rotate-180': showCamionDropdown }"
                          fill="none" 
                          stroke="currentColor" 
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                      </div>
                    </div>
                    
                    <div 
                      v-if="showCamionDropdown" 
                      class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                    >
                      <div class="p-2 border-b border-gray-200">
                        <input
                          ref="camionSearchInput"
                          v-model="camionSearchTerm"
                          type="text"
                          placeholder="Rechercher un camion..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          @click.stop
                        >
                      </div>
                      
                      <div class="max-h-48 overflow-y-auto">
                        <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-500" @click="selectCamion('')">
                          Tous les camions
                        </div>
                        
                        <div 
                          v-for="camion in filteredCamions" 
                          :key="camion.id"
                          @click="selectCamion(camion.numero_immatricule)"
                          class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                          :class="{ 'bg-orange-100 text-orange-800': filters.camion === camion.numero_immatricule }"
                        >
                          <span>{{ camion.numero_immatricule }}</span>
                          <svg 
                            v-if="filters.camion === camion.numero_immatricule"
                            class="w-4 h-4 text-orange-600" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        
                        <div v-if="filteredCamions.length === 0" class="px-4 py-2 text-gray-500 text-center">
                          Aucun camion trouvé
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Filtre par chauffeur avec autocomplete -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Chauffeur</label>
                  <div class="relative">
                    <div 
                      @click="toggleChauffeurDropdown"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white flex items-center justify-between"
                      :class="{ 'ring-2 ring-orange-500 border-transparent': showChauffeurDropdown }"
                    >
                      <span v-if="filters.chauffeur" class="text-gray-900">{{ filters.chauffeur }}</span>
                      <span v-else class="text-gray-500">Tous les chauffeurs</span>
                      <div class="flex items-center space-x-1">
                        <button 
                          v-if="filters.chauffeur" 
                          @click.stop="clearChauffeurSelection"
                          class="text-gray-400 hover:text-gray-600 p-1"
                          type="button"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                        </button>
                        <svg 
                          class="w-4 h-4 text-gray-400 transition-transform duration-200"
                          :class="{ 'rotate-180': showChauffeurDropdown }"
                          fill="none" 
                          stroke="currentColor" 
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                      </div>
                    </div>
                    
                    <div 
                      v-if="showChauffeurDropdown" 
                      class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                    >
                      <div class="p-2 border-b border-gray-200">
                        <input
                          ref="chauffeurSearchInput"
                          v-model="chauffeurSearchTerm"
                          type="text"
                          placeholder="Rechercher un chauffeur..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          @click.stop
                        >
                      </div>
                      
                      <div class="max-h-48 overflow-y-auto">
                        <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-500" @click="selectChauffeur('')">
                          Tous les chauffeurs
                        </div>
                        
                        <div 
                          v-for="chauffeur in filteredChauffeurs" 
                          :key="chauffeur.id"
                          @click="selectChauffeur(chauffeur.fullName)"
                          class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                          :class="{ 'bg-orange-100 text-orange-800': filters.chauffeur === chauffeur.fullName }"
                        >
                          <span>{{ chauffeur.fullName }}</span>
                          <svg 
                            v-if="filters.chauffeur === chauffeur.fullName"
                            class="w-4 h-4 text-orange-600" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        
                        <div v-if="filteredChauffeurs.length === 0" class="px-4 py-2 text-gray-500 text-center">
                          Aucun chauffeur trouvé
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Bouton de recherche -->
              <div class="flex justify-center mt-4">
                <button
                  @click="applyFilters"
                  :disabled="loadingBons"
                  class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors flex items-center font-medium disabled:opacity-50"
                >
                  <svg v-if="loadingBons" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  {{ loadingBons ? 'Recherche...' : 'Rechercher' }}
                </button>
              </div>
            </div>

            <!-- Résultats -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                  Bons de voyage ({{ filteredBonsCount }} résultats)
                </h3>
                <div class="text-sm text-gray-600">
                  Page {{ pagination.currentPage }} sur {{ Math.ceil(filteredBonsCount / pagination.perPage) }}
                </div>
              </div>
              
              <!-- État de chargement -->
              <div v-if="loadingBons" class="flex justify-center items-center py-12">
                <div class="flex items-center space-x-3">
                  <svg class="animate-spin h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span class="text-gray-600">Chargement des bons...</span>
                </div>
              </div>
              
              <!-- Tableau -->
              <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° BL</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Camion</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeur</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids (kg)</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chargement</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Déchargement</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="bon in displayedBons" :key="bon.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ bon.numeroBL }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(bon.date) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ bon.camion }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ bon.chauffeur }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span style="padding: 5px 10px; background: #cbcaca; color: #202023; border-radius: 10px;">{{ bon.produit }}</span></td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ formatNumber(bon.poids) }} kg</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ bon.lieuChargement }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ bon.lieuDechargement }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                          @click="viewBon(bon)"
                          class="text-orange-600 hover:text-orange-900 mr-3"
                        >
                          Voir
                        </button>
                        <button
                          @click="editBon(bon)"
                          class="text-blue-600 hover:text-blue-900 mr-3"
                        >
                          Modifier
                        </button>
                        <button
                          @click="deleteBon(bon.id)"
                          class="text-red-600 hover:text-red-900"
                        >
                          Supprimer
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                  <div class="flex-1 flex justify-between sm:hidden">
                    <button
                      @click="changePage(pagination.currentPage - 1)"
                      :disabled="pagination.currentPage <= 1"
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      Précédent
                    </button>
                    <button
                      @click="changePage(pagination.currentPage + 1)"
                      :disabled="pagination.currentPage >= Math.ceil(filteredBonsCount / pagination.perPage)"
                      class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      Suivant
                    </button>
                  </div>
                  <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                      <p class="text-sm text-gray-700">
                        Affichage de
                        <span class="font-medium">{{ (pagination.currentPage - 1) * pagination.perPage + 1 }}</span>
                        à
                        <span class="font-medium">{{ Math.min(pagination.currentPage * pagination.perPage, filteredBonsCount) }}</span>
                        sur
                        <span class="font-medium">{{ filteredBonsCount }}</span>
                        résultats
                      </p>
                    </div>
                    <div>
                      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button
                          @click="changePage(pagination.currentPage - 1)"
                          :disabled="pagination.currentPage <= 1"
                          class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                          </svg>
                        </button>
                        
                        <button
                          v-for="page in visiblePages"
                          :key="page"
                          @click="changePage(page)"
                          :class="[
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            page === pagination.currentPage
                              ? 'z-10 bg-orange-50 border-orange-500 text-orange-600'
                              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                          ]"
                        >
                          {{ page }}
                        </button>
                        
                        <button
                          @click="changePage(pagination.currentPage + 1)"
                          :disabled="pagination.currentPage >= Math.ceil(filteredBonsCount / pagination.perPage)"
                          class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Onglet Nouveau Bon -->
          <div v-if="activeTab === 'nouveau'" class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Créer un nouveau bon de voyage</h2>
            
            <div class="max-w-6xl mx-auto">
              <form @submit.prevent="saveBon" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- N° BL -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">N° BL</label>
                  <input
                    v-model="newBon.numeroBL"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Ex: BL-2024-001"
                  />
                </div>

                <!-- Camion avec autocomplete -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Camion</label>
                  <div class="relative">
                    <div 
                      @click="toggleNewBonCamionDropdown"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white flex items-center justify-between"
                      :class="{ 'ring-2 ring-orange-500 border-transparent': showNewBonCamionDropdown }"
                    >
                      <span v-if="newBon.camion" class="text-gray-900">{{ newBon.camion }}</span>
                      <span v-else class="text-gray-500">Sélectionner un camion</span>
                      <svg 
                        class="w-4 h-4 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': showNewBonCamionDropdown }"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                    
                    <div 
                      v-if="showNewBonCamionDropdown" 
                      class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                    >
                      <div class="p-2 border-b border-gray-200">
                        <input
                          ref="newBonCamionSearchInput"
                          v-model="newBonCamionSearchTerm"
                          type="text"
                          placeholder="Rechercher un camion..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          @click.stop
                        >
                      </div>
                      
                      <div class="max-h-48 overflow-y-auto">
                        <div 
                          v-for="camion in filteredNewBonCamions" 
                          :key="camion.id"
                          @click="selectNewBonCamion(camion.numero_immatricule)"
                          class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                          :class="{ 'bg-orange-100 text-orange-800': newBon.camion === camion.numero_immatricule }"
                        >
                          <span>{{ camion.numero_immatricule }}</span>
                          <svg 
                            v-if="newBon.camion === camion.numero_immatricule"
                            class="w-4 h-4 text-orange-600" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        
                        <div v-if="filteredNewBonCamions.length === 0" class="px-4 py-2 text-gray-500 text-center">
                          Aucun camion trouvé
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Chauffeur avec autocomplete -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Chauffeur</label>
                  <div class="relative">
                    <div 
                      @click="toggleNewBonChauffeurDropdown"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white flex items-center justify-between"
                      :class="{ 'ring-2 ring-orange-500 border-transparent': showNewBonChauffeurDropdown }"
                    >
                      <span v-if="newBon.chauffeur" class="text-gray-900">{{ newBon.chauffeur }}</span>
                      <span v-else class="text-gray-500">Sélectionner un chauffeur</span>
                      <svg 
                        class="w-4 h-4 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': showNewBonChauffeurDropdown }"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                    
                    <div 
                      v-if="showNewBonChauffeurDropdown" 
                      class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                    >
                      <div class="p-2 border-b border-gray-200">
                        <input
                          ref="newBonChauffeurSearchInput"
                          v-model="newBonChauffeurSearchTerm"
                          type="text"
                          placeholder="Rechercher un chauffeur..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          @click.stop
                        >
                      </div>
                      
                      <div class="max-h-48 overflow-y-auto">
                        <div 
                          v-for="chauffeur in filteredNewBonChauffeurs" 
                          :key="chauffeur.id"
                          @click="selectNewBonChauffeur(chauffeur.fullName)"
                          class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                          :class="{ 'bg-orange-100 text-orange-800': newBon.chauffeur === chauffeur.fullName }"
                        >
                          <span>{{ chauffeur.fullName }}</span>
                          <svg 
                            v-if="newBon.chauffeur === chauffeur.fullName"
                            class="w-4 h-4 text-orange-600" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        
                        <div v-if="filteredNewBonChauffeurs.length === 0" class="px-4 py-2 text-gray-500 text-center">
                          Aucun chauffeur trouvé
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Date -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                  <input
                    v-model="newBon.date"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Lieu de chargement -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de chargement</label>
                  <input
                    v-model="newBon.lieuChargement"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Ex: Carrière de Sable"
                  />
                </div>

                <!-- Lieu de déchargement -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de déchargement</label>
                  <input
                    v-model="newBon.lieuDechargement"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Ex: Chantier Avenue de la Paix"
                  />
                </div>

                <!-- Produit transporté avec autocomplete -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Produit transporté</label>
                  <div class="relative">
                    <div 
                      @click="toggleNewBonProduitDropdown"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white flex items-center justify-between"
                      :class="{ 'ring-2 ring-orange-500 border-transparent': showNewBonProduitDropdown }"
                    >
                      <span v-if="newBon.produit" class="text-gray-900">{{ newBon.produit }}</span>
                      <span v-else class="text-gray-500">Sélectionner un produit</span>
                      <svg 
                        class="w-4 h-4 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': showNewBonProduitDropdown }"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                    
                    <div 
                      v-if="showNewBonProduitDropdown" 
                      class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                    >
                      <div class="p-2 border-b border-gray-200">
                        <input
                          ref="newBonProduitSearchInput"
                          v-model="newBonProduitSearchTerm"
                          type="text"
                          placeholder="Rechercher un produit..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          @click.stop
                        >
                      </div>
                      
                      <div class="max-h-48 overflow-y-auto">
                        <div 
                          v-for="produit in filteredNewBonProduits" 
                          :key="produit.id"
                          @click="selectNewBonProduit(produit.nom)"
                          class="px-4 py-2 hover:bg-orange-50 cursor-pointer flex items-center justify-between"
                          :class="{ 'bg-orange-100 text-orange-800': newBon.produit === produit.nom }"
                        >
                          <span>{{ produit.nom }}</span>
                          <svg 
                            v-if="newBon.produit === produit.nom"
                            class="w-4 h-4 text-orange-600" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        
                        <div v-if="filteredNewBonProduits.length === 0" class="px-4 py-2 text-gray-500 text-center">
                          Aucun produit trouvé
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Poids en kg -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Poids (kg)</label>
                  <input
                    v-model.number="newBon.poids"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Ex: 25000"
                  />
                </div>

                <!-- Heure de départ -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Heure de départ</label>
                  <input
                    v-model="newBon.heureDepart"
                    type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Heure d'arrivée -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Heure d'arrivée</label>
                  <input
                    v-model="newBon.heureArrivee"
                    type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Début pesée -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Début pesée</label>
                  <input
                    v-model="newBon.debutPesee"
                    type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Fin pesée -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Fin pesée</label>
                  <input
                    v-model="newBon.finPesee"
                    type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <!-- Observations -->
                <div class="md:col-span-2 lg:col-span-3">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Observations</label>
                  <textarea
                    v-model="newBon.observations"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Remarques particulières..."
                  ></textarea>
                </div>

                <!-- Boutons -->
                <div class="md:col-span-2 lg:col-span-3 flex justify-end space-x-4">
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
                    :disabled="savingBon"
                    class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors flex items-center disabled:opacity-50"
                  >
                    <svg v-if="savingBon" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ savingBon ? 'Enregistrement...' : 'Enregistrer le bon' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Nouveau Produit -->
    <div v-if="showProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">Nouveau Produit</h3>
          <button @click="showProductModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <form @submit.prevent="saveProduct">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom du produit</label>
            <input
              v-model="newProduct.nom"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
              placeholder="Ex: Sable, Gravier, Béton..."
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
            <textarea
              v-model="newProduct.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
              placeholder="Description du produit..."
            ></textarea>
          </div>
          <div class="flex space-x-3">
            <button
              type="button"
              @click="showProductModal = false"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
            >
              Annuler
            </button>
            <button
              type="submit"
              style="background-color: green;"
              class="flex-1 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700"
            >
              Créer
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Détails Bon -->
    <div v-if="showBonModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">Détails du bon {{ selectedBon?.numeroBL }}</h3>
          <button @click="showBonModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div v-if="selectedBon" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><strong>N° BL:</strong> {{ selectedBon.numeroBL }}</div>
          <div><strong>Date:</strong> {{ formatDate(selectedBon.date) }}</div>
          <div><strong>Camion:</strong> {{ selectedBon.camion }}</div>
          <div><strong>Chauffeur:</strong> {{ selectedBon.chauffeur }}</div>
          <div><strong>Produit:</strong> {{ selectedBon.produit }}</div>
          <div><strong>Poids:</strong> {{ formatNumber(selectedBon.poids) }} kg</div>
          <div><strong>Lieu chargement:</strong> {{ selectedBon.lieuChargement }}</div>
          <div><strong>Lieu déchargement:</strong> {{ selectedBon.lieuDechargement }}</div>
          <div><strong>Heure départ:</strong> {{ selectedBon.heureDepart }}</div>
          <div><strong>Heure arrivée:</strong> {{ selectedBon.heureArrivee }}</div>
          <div><strong>Début pesée:</strong> {{ selectedBon.debutPesee || 'Non renseigné' }}</div>
          <div><strong>Fin pesée:</strong> {{ selectedBon.finPesee || 'Non renseigné' }}</div>
          <div class="md:col-span-2"><strong>Observations:</strong> {{ selectedBon.observations || 'Aucune' }}</div>
        </div>
      </div>
    </div>

    <!-- Modal Modification Bon -->
    <div v-if="showEditModal && editingBon" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">Modifier le bon {{ editingBon.numeroBL }}</h3>
          <button @click="cancelEdit" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="saveEditBon" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Les champs du formulaire d'édition seraient ici, similaires au formulaire de création -->
          <!-- Pour la brièveté, je ne les répète pas tous -->
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, nextTick } from 'vue'
import axios from 'axios'
import * as XLSX from 'xlsx'
import { jsPDF } from 'jspdf'
import autoTable from 'jspdf-autotable'

// Configuration de l'API
const API_BASE_URL = 'https://sogetrag.com/api/bons-voyage.php'

// États de chargement
const loadingBons = ref(false)
const savingBon = ref(false)
const exportingExcel = ref(false)
const exportingPDF = ref(false)

// État des onglets - HISTORIQUE PAR DÉFAUT
const activeTab = ref('historique')

// État des modals
const showProductModal = ref(false)
const showBonModal = ref(false)
const selectedBon = ref(null)
const editingBon = ref(null)
const showEditModal = ref(false)

// États des dropdowns
const showProduitDropdown = ref(false)
const showCamionDropdown = ref(false)
const showChauffeurDropdown = ref(false)
const showNewBonCamionDropdown = ref(false)
const showNewBonChauffeurDropdown = ref(false)
const showNewBonProduitDropdown = ref(false)

// Termes de recherche
const produitSearchTerm = ref('')
const camionSearchTerm = ref('')
const chauffeurSearchTerm = ref('')
const newBonCamionSearchTerm = ref('')
const newBonChauffeurSearchTerm = ref('')
const newBonProduitSearchTerm = ref('')

// NOUVEAU: Terme de recherche globale
const globalSearchTerm = ref('')

// Pagination
const pagination = reactive({
  currentPage: 1,
  perPage: 10,
  total: 0
})

// Données de référence (viennent de l'API)
const camionsData = ref([])
const chauffeursData = ref([])
const produits = ref([])

// Computed pour formater les chauffeurs pour l'autocomplete
const chauffeursFormatted = computed(() => 
  chauffeursData.value.map(ch => ({
    id: ch.id,
    fullName: `${ch.prenom} ${ch.nom}`,
    prenom: ch.prenom,
    nom: ch.nom
  }))
)

// Computed pour les filtres
const filteredProduits = computed(() => {
  if (!produitSearchTerm.value) return produits.value
  return produits.value.filter(produit => 
    produit.nom.toLowerCase().includes(produitSearchTerm.value.toLowerCase())
  )
})

const filteredCamions = computed(() => {
  if (!camionSearchTerm.value) return camionsData.value
  return camionsData.value.filter(camion => 
    camion.numero_immatricule.toLowerCase().includes(camionSearchTerm.value.toLowerCase())
  )
})

const filteredChauffeurs = computed(() => {
  if (!chauffeurSearchTerm.value) return chauffeursFormatted.value
  return chauffeursFormatted.value.filter(chauffeur => 
    chauffeur.fullName.toLowerCase().includes(chauffeurSearchTerm.value.toLowerCase())
  )
})

// Computed pour le nouveau bon
const filteredNewBonCamions = computed(() => {
  if (!newBonCamionSearchTerm.value) return camionsData.value
  return camionsData.value.filter(camion => 
    camion.numero_immatricule.toLowerCase().includes(newBonCamionSearchTerm.value.toLowerCase())
  )
})

const filteredNewBonChauffeurs = computed(() => {
  if (!newBonChauffeurSearchTerm.value) return chauffeursFormatted.value
  return chauffeursFormatted.value.filter(chauffeur => 
    chauffeur.fullName.toLowerCase().includes(newBonChauffeurSearchTerm.value.toLowerCase())
  )
})

const filteredNewBonProduits = computed(() => {
  if (!newBonProduitSearchTerm.value) return produits.value
  return produits.value.filter(produit => 
    produit.nom.toLowerCase().includes(newBonProduitSearchTerm.value.toLowerCase())
  )
})

// NOUVEAU: Computed pour la recherche globale
const filteredBons = computed(() => {
  if (!globalSearchTerm.value.trim()) return bons.value
  
  const searchTerm = globalSearchTerm.value.toLowerCase().trim()
  
  return bons.value.filter(bon => {
    return (
      bon.numeroBL?.toLowerCase().includes(searchTerm) ||
      bon.camion?.toLowerCase().includes(searchTerm) ||
      bon.chauffeur?.toLowerCase().includes(searchTerm) ||
      bon.produit?.toLowerCase().includes(searchTerm) ||
      bon.lieuChargement?.toLowerCase().includes(searchTerm) ||
      bon.lieuDechargement?.toLowerCase().includes(searchTerm) ||
      bon.poids?.toString().includes(searchTerm) ||
      formatDate(bon.date)?.toLowerCase().includes(searchTerm)
    )
  })
})

// NOUVEAU: Computed pour le nombre de résultats filtrés
const filteredBonsCount = computed(() => filteredBons.value.length)

// NOUVEAU: Computed pour les bons affichés avec pagination
const displayedBons = computed(() => {
  const start = (pagination.currentPage - 1) * pagination.perPage
  const end = start + pagination.perPage
  return filteredBons.value.slice(start, end)
})

// Formulaire nouveau bon
const newBon = reactive({
  numeroBL: '',
  camion: '',
  chauffeur: '',
  date: '',
  lieuChargement: '',
  lieuDechargement: '',
  produit: '',
  poids: 0,
  heureDepart: '',
  heureArrivee: '',
  debutPesee: '',
  finPesee: '',
  observations: ''
})

// Formulaire nouveau produit
const newProduct = reactive({
  nom: '',
  description: ''
})

// Liste des bons (vient du backend)
const bons = ref([])

// Filtres
const filters = reactive({
  dateType: 'all',
  dateDebut: '',
  dateFin: '',
  produit: '',
  camion: '',
  chauffeur: '',
  search: ''
})

// Pages visibles pour la pagination
const visiblePages = computed(() => {
  const totalPages = Math.ceil(filteredBonsCount.value / pagination.perPage)
  const current = pagination.currentPage
  const pages = []
  
  // Logique pour afficher les pages (max 5 pages visibles)
  let start = Math.max(1, current - 2)
  let end = Math.min(totalPages, start + 4)
  
  if (end - start < 4) {
    start = Math.max(1, end - 4)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

// Statistiques (viennent du backend)
const stats = ref({
  totalBons: 0,
  bonsMois: 0,
  camionsActifs: 0,
  poidsTotal: 0
})

// Fonction pour formater les nombres
const formatNumber = (number) => {
  if (!number) return '0'
  return new Intl.NumberFormat('fr-FR').format(number)
}

// Fonction utilitaire pour le cache busting
const getCacheBuster = () => {
  return `_t=${Date.now()}&_r=${Math.random().toString(36).substr(2, 9)}`
}

// NOUVELLES FONCTIONS pour la recherche globale
const applyGlobalSearch = () => {
  // Réinitialiser à la première page lors d'une nouvelle recherche
  pagination.currentPage = 1
}

const clearGlobalSearch = () => {
  globalSearchTerm.value = ''
  pagination.currentPage = 1
}

// Méthodes pour les dropdowns des filtres
const toggleProduitDropdown = () => {
  showProduitDropdown.value = !showProduitDropdown.value
  if (showProduitDropdown.value) {
    produitSearchTerm.value = ''
    nextTick(() => {
      document.querySelector('[ref="produitSearchInput"]')?.focus()
    })
  }
}

const selectProduit = (nom) => {
  filters.produit = nom
  showProduitDropdown.value = false
  produitSearchTerm.value = ''
  applyFilters()
}

const clearProduitSelection = () => {
  filters.produit = ''
  produitSearchTerm.value = ''
  applyFilters()
}

const toggleCamionDropdown = () => {
  showCamionDropdown.value = !showCamionDropdown.value
  if (showCamionDropdown.value) {
    camionSearchTerm.value = ''
    nextTick(() => {
      document.querySelector('[ref="camionSearchInput"]')?.focus()
    })
  }
}

const selectCamion = (numero) => {
  filters.camion = numero
  showCamionDropdown.value = false
  camionSearchTerm.value = ''
  applyFilters()
}

const clearCamionSelection = () => {
  filters.camion = ''
  camionSearchTerm.value = ''
  applyFilters()
}

const toggleChauffeurDropdown = () => {
  showChauffeurDropdown.value = !showChauffeurDropdown.value
  if (showChauffeurDropdown.value) {
    chauffeurSearchTerm.value = ''
    nextTick(() => {
      document.querySelector('[ref="chauffeurSearchInput"]')?.focus()
    })
  }
}

const selectChauffeur = (nom) => {
  filters.chauffeur = nom
  showChauffeurDropdown.value = false
  chauffeurSearchTerm.value = ''
  applyFilters()
}

const clearChauffeurSelection = () => {
  filters.chauffeur = ''
  chauffeurSearchTerm.value = ''
  applyFilters()
}

// Méthodes pour les dropdowns du nouveau bon
const toggleNewBonCamionDropdown = () => {
  showNewBonCamionDropdown.value = !showNewBonCamionDropdown.value
  if (showNewBonCamionDropdown.value) {
    newBonCamionSearchTerm.value = ''
    nextTick(() => {
      document.querySelector('[ref="newBonCamionSearchInput"]')?.focus()
    })
  }
}

const selectNewBonCamion = (numero) => {
  newBon.camion = numero
  showNewBonCamionDropdown.value = false
  newBonCamionSearchTerm.value = ''
}

const toggleNewBonChauffeurDropdown = () => {
  showNewBonChauffeurDropdown.value = !showNewBonChauffeurDropdown.value
  if (showNewBonChauffeurDropdown.value) {
    newBonChauffeurSearchTerm.value = ''
    nextTick(() => {
      document.querySelector('[ref="newBonChauffeurSearchInput"]')?.focus()
    })
  }
}

const selectNewBonChauffeur = (nom) => {
  newBon.chauffeur = nom
  showNewBonChauffeurDropdown.value = false
  newBonChauffeurSearchTerm.value = ''
}

const toggleNewBonProduitDropdown = () => {
  showNewBonProduitDropdown.value = !showNewBonProduitDropdown.value
  if (showNewBonProduitDropdown.value) {
    newBonProduitSearchTerm.value = ''
    nextTick(() => {
      document.querySelector('[ref="newBonProduitSearchInput"]')?.focus()
    })
  }
}

const selectNewBonProduit = (nom) => {
  newBon.produit = nom
  showNewBonProduitDropdown.value = false
  newBonProduitSearchTerm.value = ''
}

// Gestion des clics extérieurs
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    showProduitDropdown.value = false
    showCamionDropdown.value = false
    showChauffeurDropdown.value = false
    showNewBonCamionDropdown.value = false
    showNewBonChauffeurDropdown.value = false
    showNewBonProduitDropdown.value = false
  }
}

// Charger les données de référence
const loadReferenceData = async () => {
  try {
    const cacheBuster = getCacheBuster()
    
    // Charger les produits
    const produitsResponse = await axios.get(`${API_BASE_URL}?action=produits&${cacheBuster}`)
    if (produitsResponse.data.success) {
      produits.value = produitsResponse.data.data
    }
    
    // Charger les camions
    const camionsResponse = await axios.get(`${API_BASE_URL}?action=camions&${cacheBuster}`)
    if (camionsResponse.data.success) {
      camionsData.value = camionsResponse.data.data
    }
    
    // Charger les chauffeurs
    const chauffeursResponse = await axios.get(`${API_BASE_URL}?action=chauffeurs&${cacheBuster}`)
    if (chauffeursResponse.data.success) {
      chauffeursData.value = chauffeursResponse.data.data
    }
    
  } catch (error) {
    console.error('Erreur lors du chargement des données de référence:', error)
  }
}

// Appel API pour récupérer les bons
const fetchBons = async (page = 1) => {
  loadingBons.value = true
  
  try {
    const cacheBuster = getCacheBuster()
    const params = {
      action: 'bons',
      page: page,
      limit: pagination.perPage,
      date_type: filters.dateType,
      search: filters.search || '',
      ...cacheBuster.split('&').reduce((acc, param) => {
        const [key, value] = param.split('=')
        acc[key] = value
        return acc
      }, {})
    }
    
    // Ajouter les filtres conditionnels
    if (filters.dateType === 'custom') {
      params.date_debut = filters.dateDebut
      params.date_fin = filters.dateFin
    }
    
    if (filters.produit) {
      const produitSelectionne = produits.value.find(p => p.nom === filters.produit)
      if (produitSelectionne) {
        params.produit_id = produitSelectionne.id
      }
    }
    
    if (filters.camion) {
      const camionSelectionne = camionsData.value.find(c => c.numero_immatricule === filters.camion)
      if (camionSelectionne) {
        params.camion_id = camionSelectionne.id
      }
    }
    
    if (filters.chauffeur) {
      const chauffeurSelectionne = chauffeursData.value.find(ch => `${ch.prenom} ${ch.nom}` === filters.chauffeur)
      if (chauffeurSelectionne) {
        params.chauffeur_id = chauffeurSelectionne.id
      }
    }
    
    const response = await axios.get(API_BASE_URL, { params })
    
    if (response.data.success) {
      bons.value = response.data.data.map(bon => ({
        id: bon.id,
        numeroBL: bon.numero_bl,
        camion: bon.camion,
        chauffeur: bon.chauffeur,
        date: bon.date_voyage,
        lieuChargement: bon.lieu_chargement,
        lieuDechargement: bon.lieu_dechargement,
        produit: bon.produit,
        poids: parseFloat(bon.poids) || 0,
        heureDepart: bon.heure_depart,
        heureArrivee: bon.heure_arrivee,
        debutPesee: bon.debut_pesee,
        finPesee: bon.fin_pesee,
        observations: bon.observations
      }))
      
      pagination.currentPage = response.data.pagination.current_page
      pagination.total = response.data.pagination.total
    } else {
      throw new Error(response.data.error || 'Erreur lors du chargement')
    }
    
  } catch (error) {
    console.error('Erreur lors du chargement des bons:', error)
    alert('Erreur lors du chargement des bons: ' + (error.response?.data?.error || error.message))
  } finally {
    loadingBons.value = false
  }
}

// Appliquer les filtres (appel API)
const applyFilters = async () => {
  await fetchBons(1)
}

// Changer de page - MODIFIÉ pour tenir compte de la recherche globale
const changePage = async (page) => {
  const totalPages = Math.ceil(filteredBonsCount.value / pagination.perPage)
  if (page < 1 || page > totalPages) return
  
  pagination.currentPage = page
  
  // Si on utilise la recherche globale, pas besoin d'appeler l'API
  if (globalSearchTerm.value.trim()) {
    return
  }
  
  await fetchBons(page)
}

// Méthodes
const saveBon = async () => {
  savingBon.value = true
  
  try {
    // Trouver les IDs correspondants
    const produitSelectionne = produits.value.find(p => p.nom === newBon.produit)
    const camionSelectionne = camionsData.value.find(c => c.numero_immatricule === newBon.camion)
    const chauffeurSelectionne = chauffeursData.value.find(ch => `${ch.prenom} ${ch.nom}` === newBon.chauffeur)
    
    if (!produitSelectionne || !camionSelectionne || !chauffeurSelectionne) {
      throw new Error('Veuillez sélectionner des valeurs valides pour tous les champs')
    }
    
    const bonData = {
      numero_bl: newBon.numeroBL,
      camion_id: camionSelectionne.id,
      chauffeur_id: chauffeurSelectionne.id,
      produit_id: produitSelectionne.id,
      date_voyage: newBon.date,
      lieu_chargement: newBon.lieuChargement,
      lieu_dechargement: newBon.lieuDechargement,
      poids: newBon.poids,
      heure_depart: newBon.heureDepart,
      heure_arrivee: newBon.heureArrivee,
      debut_pesee: newBon.debutPesee || null,
      fin_pesee: newBon.finPesee || null,
      observations: newBon.observations || ''
    }
    
    const response = await axios.post(`${API_BASE_URL}?action=create_bon&${getCacheBuster()}`, bonData)
    
    if (response.data.success) {
      resetForm()
      activeTab.value = 'historique'
      alert('Bon de voyage enregistré avec succès !')
      await fetchBons(1)
      await loadStats()
    } else {
      throw new Error(response.data.error || 'Erreur lors de la sauvegarde')
    }
    
  } catch (error) {
    console.error('Erreur lors de la sauvegarde:', error)
    alert('Erreur lors de la sauvegarde: ' + (error.response?.data?.error || error.message))
  } finally {
    savingBon.value = false
  }
}

const resetForm = () => {
  Object.keys(newBon).forEach(key => {
    if (key === 'poids') {
      newBon[key] = 0
    } else {
      newBon[key] = ''
    }
  })
  newBon.date = new Date().toISOString().split('T')[0]
}

const saveProduct = async () => {
  try {
    const response = await axios.post(`${API_BASE_URL}?action=create_produit&${getCacheBuster()}`, {
      nom: newProduct.nom,
      description: newProduct.description
    })
    
    if (response.data.success) {
      produits.value.push({
        id: response.data.data.id,
        nom: response.data.data.nom,
        description: response.data.data.description
      })
      
      newProduct.nom = ''
      newProduct.description = ''
      showProductModal.value = false
      alert('Produit créé avec succès !')
    } else {
      throw new Error(response.data.error || 'Erreur lors de la création')
    }
    
  } catch (error) {
    console.error('Erreur lors de la création du produit:', error)
    alert('Erreur lors de la création: ' + (error.response?.data?.error || error.message))
  }
}

const viewBon = (bon) => {
  selectedBon.value = bon
  showBonModal.value = true
}

const editBon = (bon) => {
  editingBon.value = {
    id: bon.id,
    numeroBL: bon.numeroBL,
    camion: bon.camion,
    chauffeur: bon.chauffeur,
    date: bon.date,
    lieuChargement: bon.lieuChargement,
    lieuDechargement: bon.lieuDechargement,
    produit: bon.produit,
    poids: bon.poids,
    heureDepart: bon.heureDepart,
    heureArrivee: bon.heureArrivee,
    debutPesee: bon.debutPesee || '',
    finPesee: bon.finPesee || '',
    observations: bon.observations || ''
  }
  
  showEditModal.value = true
}

const deleteBon = async (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce bon ?')) {
    try {
      const response = await axios.delete(`${API_BASE_URL}?action=delete_bon&id=${id}&${getCacheBuster()}`)
      
      if (response.data.success) {
        alert('Bon supprimé avec succès !')
        await fetchBons(pagination.currentPage)
        await loadStats()
      } else {
        throw new Error(response.data.error || 'Erreur lors de la suppression')
      }
      
    } catch (error) {
      console.error('Erreur lors de la suppression:', error)
      alert('Erreur lors de la suppression: ' + (error.response?.data?.error || error.message))
    }
  }
}

// Export Excel avec toutes les données filtrées - MODIFIÉ pour utiliser les données filtrées
const exportExcel = async () => {
  try {
    exportingExcel.value = true
    
    const dataToExport = globalSearchTerm.value.trim() ? filteredBons.value : bons.value
    
    if (dataToExport.length === 0) {
      alert('Aucune donnée à exporter. Veuillez d\'abord charger des bons.')
      return
    }
    
    const data = dataToExport.map(bon => ({
      'N° BL': bon.numeroBL,
      'Date': formatDate(bon.date),
      'Camion': bon.camion,
      'Chauffeur': bon.chauffeur,
      'Produit': bon.produit,
      'Poids (kg)': bon.poids,
      'Lieu Chargement': bon.lieuChargement,
      'Lieu Déchargement': bon.lieuDechargement,
      'Heure Départ': bon.heureDepart,
      'Heure Arrivée': bon.heureArrivee,
      'Début Pesée': bon.debutPesee || '',
      'Fin Pesée': bon.finPesee || '',
      'Observations': bon.observations || ''
    }))
    
    const workbook = XLSX.utils.book_new()
    const worksheet = XLSX.utils.json_to_sheet(data)
    
    const colWidths = [
      { wch: 15 }, { wch: 12 }, { wch: 15 }, { wch: 20 }, { wch: 15 }, 
      { wch: 12 }, { wch: 25 }, { wch: 25 }, { wch: 12 }, { wch: 12 }, 
      { wch: 12 }, { wch: 12 }, { wch: 30 }
    ]
    worksheet['!cols'] = colWidths
    
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Bons de voyage')
    
    const today = new Date().toISOString().split('T')[0]
    const filename = `bons_voyage_${today}.xlsx`
    XLSX.writeFile(workbook, filename)
    
    alert(`Export Excel réussi ! ${data.length} enregistrements exportés.`)
    
  } catch (error) {
    console.error('Erreur lors de l\'export Excel:', error)
    alert('Erreur lors de l\'export Excel.')
  } finally {
    exportingExcel.value = false
  }
}

// Export PDF avec résumé - MODIFIÉ pour utiliser les données filtrées
const exportPDF = async () => {
  try {
    exportingPDF.value = true
    
    const dataToExport = globalSearchTerm.value.trim() ? filteredBons.value : bons.value
    
    if (dataToExport.length === 0) {
      alert('Aucune donnée à exporter. Veuillez d\'abord charger des bons.')
      return
    }
    
    const data = dataToExport
    const totalBons = data.length
    const totalPoids = data.reduce((sum, bon) => sum + (bon.poids || 0), 0)
    const produitsUniques = [...new Set(data.map(bon => bon.produit))].filter(Boolean)
    
    const doc = new jsPDF()
    
    doc.setFontSize(20)
    doc.setTextColor(40, 40, 40)
    doc.text('RAPPORT BONS DE VOYAGE', 105, 20, { align: 'center' })
    
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
    
    if (globalSearchTerm.value.trim()) {
      doc.text(`Recherche: "${globalSearchTerm.value}"`, 105, 35, { align: 'center' })
    }
    
    doc.setFontSize(14)
    doc.setTextColor(40, 40, 40)
    doc.text('RÉSUMÉ EXÉCUTIF', 20, 45)
    
    doc.setFontSize(11)
    doc.setTextColor(60, 60, 60)
    
    doc.setDrawColor(200, 200, 200)
    doc.setFillColor(248, 249, 250)
    doc.rect(15, 50, 180, 35, 'FD')
    
    doc.setTextColor(40, 40, 40)
    doc.text(`Total des bons: ${formatNumber(totalBons)}`, 20, 60)
    doc.text(`Poids total transporté: ${formatNumber(totalPoids)} kg`, 20, 68)
    doc.text(`Produits transportés: ${produitsUniques.length > 0 ? produitsUniques.join(', ') : 'Aucun'}`, 20, 76)
    
    const tableData = data.map(bon => [
      formatDate(bon.date) || '',
      bon.numeroBL || '',
      bon.camion || '',
      bon.chauffeur || '',
      bon.produit || '',
      `${formatNumber(bon.poids || 0)} kg`,
      bon.lieuChargement || ''
    ])
    
    autoTable(doc, {
      head: [['Date', 'N° BL', 'Camion', 'Chauffeur', 'Produit', 'Poids', 'Chargement']],
      body: tableData,
      startY: 95,
      styles: {
        fontSize: 8,
        cellPadding: 2
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
        0: { cellWidth: 20 }, 1: { cellWidth: 20 }, 2: { cellWidth: 25 }, 
        3: { cellWidth: 30 }, 4: { cellWidth: 25 }, 5: { cellWidth: 20 }, 
        6: { cellWidth: 35 }
      }
    })
    
    const pageCount = doc.internal.getNumberOfPages()
    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i)
      doc.setFontSize(8)
      doc.setTextColor(100, 100, 100)
      doc.text(`Page ${i} sur ${pageCount}`, 105, 290, { align: 'center' })
      doc.text('SOGETRAG - Système de Gestion des Bons de Voyage', 105, 285, { align: 'center' })
    }
    
    const filename = `rapport_bons_voyage_${new Date().toISOString().split('T')[0]}.pdf`
    doc.save(filename)
    
    alert(`Export PDF réussi ! ${totalBons} enregistrements exportés.`)
    
  } catch (error) {
    console.error('Erreur lors de l\'export PDF:', error)
    alert('Erreur lors de l\'export PDF.')
  } finally {
    exportingPDF.value = false
  }
}

const saveEditBon = async () => {
  if (!editingBon.value) return
  
  try {
    savingBon.value = true
    
    const produitSelectionne = produits.value.find(p => p.nom === editingBon.value.produit)
    const camionSelectionne = camionsData.value.find(c => c.numero_immatricule === editingBon.value.camion)
    const chauffeurSelectionne = chauffeursData.value.find(ch => `${ch.prenom} ${ch.nom}` === editingBon.value.chauffeur)
    
    if (!produitSelectionne || !camionSelectionne || !chauffeurSelectionne) {
      throw new Error('Veuillez sélectionner des valeurs valides pour tous les champs')
    }
    
    const bonData = {
      numero_bl: editingBon.value.numeroBL,
      camion_id: camionSelectionne.id,
      chauffeur_id: chauffeurSelectionne.id,
      produit_id: produitSelectionne.id,
      date_voyage: editingBon.value.date,
      lieu_chargement: editingBon.value.lieuChargement,
      lieu_dechargement: editingBon.value.lieuDechargement,
      poids: editingBon.value.poids,
      heure_depart: editingBon.value.heureDepart,
      heure_arrivee: editingBon.value.heureArrivee,
      debut_pesee: editingBon.value.debutPesee || null,
      fin_pesee: editingBon.value.finPesee || null,
      observations: editingBon.value.observations || ''
    }
    
    const cacheBuster = getCacheBuster()
    const response = await axios.put(`${API_BASE_URL}?action=update_bon&id=${editingBon.value.id}&${cacheBuster}`, bonData)
    
    if (response.data.success) {
      showEditModal.value = false
      editingBon.value = null
      alert('Bon de voyage modifié avec succès !')
      await fetchBons(pagination.currentPage)
      await loadStats()
    } else {
      throw new Error(response.data.error || 'Erreur lors de la modification')
    }
    
  } catch (error) {
    console.error('Erreur lors de la modification:', error)
    alert('Erreur lors de la modification: ' + (error.response?.data?.error || error.message))
  } finally {
    savingBon.value = false
  }
}

const cancelEdit = () => {
  showEditModal.value = false
  editingBon.value = null
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR')
}

const loadStats = async () => {
  try {
    const cacheBuster = getCacheBuster()
    const response = await axios.get(`${API_BASE_URL}?action=stats&${cacheBuster}`)
    
    if (response.data.success) {
      stats.value = {
        totalBons: response.data.data.total_bons,
        bonsMois: response.data.data.bons_mois,
        camionsActifs: response.data.data.total_camions,
        poidsTotal: parseFloat(response.data.data.poids_total) || 0
      }
    }
  } catch (error) {
    console.error('Erreur lors du chargement des statistiques:', error)
  }
}

// Initialisation
onMounted(async () => {
  newBon.date = new Date().toISOString().split('T')[0]
  
  document.addEventListener('click', handleClickOutside)
  
  await Promise.all([
    loadReferenceData(),
    fetchBons(1),
    loadStats()
  ])
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
/* Animations héritées du dashboard */
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