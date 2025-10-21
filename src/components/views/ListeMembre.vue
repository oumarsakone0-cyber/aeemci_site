<template>
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 relative overflow-hidden">
      <!-- Animations de fond -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
      </div>
  
      <div class="relative z-10 p-6" style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <div class="flex items-center space-x-4">
            <button @click="$router.go(-1)" class="p-2 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div>
              <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 lg:w-8 lg:h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                </svg>
                Membres AEEMCI
              </h1>
              <p class="text-gray-600 text-sm lg:text-base">Gestion des cartes de membres</p>
            </div>
          </div>
          
          <div class="flex flex-wrap items-center justify-end gap-3">
            <button @click="exportExcel()" style="background: green" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 lg:px-4 lg:py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 text-sm lg:text-base">
              <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <span>Excel</span>
            </button>
            <button @click="exportPDF()" style="background: green" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 lg:px-4 lg:py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 text-sm lg:text-base">
              <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
              <span>PDF</span>
            </button>
          </div>
        </div>
  
        <!-- Statistiques -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 overflow-hidden" >
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Total Membres</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ pagination.total || 0 }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-green-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-emerald-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Cartes Imprimés</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.printed_cards + stats.retrieved_cards}}</p>
                </div>
                <div class="p-2 lg:p-3 bg-emerald-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-teal-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Total Paiement</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ formatXOF(stats.total_paye) }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-teal-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Cartes Retirées</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.retrieved_cards}}</p>
                </div>
                <div class="p-2 lg:p-3 bg-blue-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Filtres avancés -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8" style="margin-top: 10px">
          <div class="p-4 lg:p-6" style="padding: 10px">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input
                  v-model="filters.search"
                  @input="debounceSearch"
                  type="text"
                  placeholder="Nom, prénom, matricule..."
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secrétariat</label>
                <div class="relative">
                  <div 
                    @click="toggleSecretariatDropdown"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent cursor-pointer bg-white flex items-center justify-between text-sm"
                    :class="{ 'ring-2 ring-green-500 border-transparent': showSecretariatDropdown }"
                  >
                    <span v-if="selectedSecretariatText" class="text-gray-900 truncate">{{ selectedSecretariatText }}</span>
                    <span v-else class="text-gray-500">Tous les secrétariats</span>
                    <div class="flex items-center space-x-1 flex-shrink-0">
                      <button 
                        v-if="filters.secretariat" 
                        @click.stop="clearSecretariatSelection"
                        class="text-gray-400 hover:text-gray-600 p-1"
                        type="button"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                      <svg 
                        class="w-4 h-4 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': showSecretariatDropdown }"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                  
                  <div 
                    v-if="showSecretariatDropdown" 
                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                  >
                    <div class="p-2 border-b border-gray-200">
                      <input
                        ref="secretariatSearchInput"
                        v-model="secretariatSearchTerm"
                        type="text"
                        placeholder="Rechercher un secrétariat..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @click.stop
                      >
                    </div>
                    
                    <div class="max-h-48 overflow-y-auto">
                      <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-500 text-sm" @click="selectSecretariat('', 'Tous les secrétariats')">
                        Tous les secrétariats
                      </div>
                      
                      <div 
                        v-for="secretariat in filteredSecretariatsForDropdown" 
                        :key="secretariat"
                        @click="selectSecretariat(secretariat, secretariat)"
                        class="px-4 py-2 hover:bg-green-50 cursor-pointer flex items-center justify-between text-sm"
                        :class="{ 'bg-green-100 text-green-800': filters.secretariat === secretariat }"
                      >
                        <span class="truncate">{{ secretariat }}</span>
                        <svg 
                          v-if="filters.secretariat === secretariat"
                          class="w-4 h-4 text-green-600 flex-shrink-0" 
                          fill="none" 
                          stroke="currentColor" 
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                      </div>
                      
                      <div v-if="filteredSecretariatsForDropdown.length === 0" class="px-4 py-2 text-gray-500 text-center text-sm">
                        Aucun secrétariat trouvé
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select
                  v-model="filters.statut"
                  @change="applyFilters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
                  <option value="">Tous les statuts</option>
                  <option value="SR">Secrétaire Régional</option>
                  <option value="SOUS_COMITE">Membre du bureau (Sous-comité)</option>
                  <option value="BUREAU_SECTION">Membre du bureau (Section)</option>
                  <option value="MEMBRE_SIMPLE">Membre simple</option>
                  <option value="IMAM_REGIONAL">Imam Régional</option>
                  <option value="IMAM_SOUS_COMITE">Imam de Sous-comité</option>
                  <option value="IMAM_SECTION">Imam de Section</option>
                  <option value="FORMATEUR_SOUS_COMITE">Formateur de Sous-comité</option>
                  <option value="FORMATEUR_SECTION">Formateur de section</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut carte</label>
                <select
                  v-model="filters.card_status"
                  @change="applyFilters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
                  <option value="">Tous</option>
                  <option value="pending">En attente</option>
                  <option value="printed">Imprimée</option>
                  <option value="retrieved">Retirée</option>
                </select>
              </div>
            </div>
            
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
              <div class="flex items-center space-x-4">
                <label class="block text-sm font-medium text-gray-700">Éléments par page:</label>
                <select v-model="pagination.per_page" @change="changePerPage" class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </div>
              <button @click="resetFilters" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Réinitialiser</span>
              </button>
            </div>
          </div>
        </div>
  
        <!-- Tableau des membres -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="padding: 10px; margin-top: 10px">
          <div class="px-4 lg:px-6 py-4 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-2 lg:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Liste des Membres</h3>
            <div class="text-sm text-gray-500">
              Affichage {{ pagination.from || 0 }} à {{ pagination.to || 0 }} sur {{ pagination.total || 0 }} membres
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <span>Membre</span>
                    </div>
                  </th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Secrétariat</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Contact</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carte</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading" class="animate-pulse">
                  <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex items-center justify-center space-x-2">
                      <svg class="animate-spin h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span>Chargement...</span>
                    </div>
                  </td>
                </tr>
                
                <tr v-else-if="membres.length === 0">
                  <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center space-y-2">
                      <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                      <span>Aucun membre trouvé</span>
                    </div>
                  </td>
                </tr>
                
                <tr v-else v-for="membre in membres" :key="membre.id" class="hover:bg-gray-50 transition-colors duration-150" style="padding: 5px">
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8 lg:h-10 lg:w-10 relative">
                        <img 
                          v-if="membre.photo" 
                          :src="membre.photo" 
                          :alt="`Photo de ${membre.prenom} ${membre.nom}`" 
                          class="h-8 w-8 lg:h-10 lg:w-10 rounded-full object-cover border-2 border-green-200 cursor-pointer hover:scale-110 transition-transform" 
                          @click="openImageModal(membre.photo)"
                        >
                        <div v-else class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-green-100 flex items-center justify-center">
                          <svg class="w-4 h-4 lg:w-5 lg:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                        </div>
                        
                        <!-- Bouton pour agrandir l'image -->
                        <button 
                            style="background-color: #f9f9f900;"
                          v-if="membre.photo" 
                          @click="openImageModal(membre.photo)" 
                          class="absolute -top-1 -right-1 bg-blue-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs hover:bg-blue-600 transition-colors" 
                          title="Agrandir l'image"
                        >
                          <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                          </svg>
                        </button>
                      </div>
                      <div class="ml-3 lg:ml-4">
                        <div class="text-xs lg:text-sm font-medium text-gray-900">{{ membre.prenom }} {{ membre.nom }}</div>
                        <div class="text-xs text-gray-500 lg:hidden">{{ membre.contact }}</div>
                      </div>
                    </div>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 font-mono">
                      {{ membre.matricule }}
                    </span>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                      :class="getStatutBadgeClass(membre.statut || membre.qualite_membre)"
                    >
                      {{ getStatutLabel(membre.statut || membre.qualite_membre) }}
                    </span>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900 hidden lg:table-cell">
                    {{ membre.secretariat_poste || membre.sr_debut }}
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900 hidden lg:table-cell">
                    {{ membre.contact }}
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap" style="padding: 5px">
                    <div class="space-y-1">
                      <span v-if="membre.card_status === 'retrieved'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Retirée
                      </span>
                      <span v-else-if="membre.card_status === 'printed'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Établie
                      </span>
                      <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        En cours
                      </span>
                      
                      <!-- Détails supplémentaires -->
                      <div v-if="membre.printed_at" class="text-xs text-gray-500">
                        Imprimée: {{ formatDate(membre.printed_at) }}
                      </div>
                      <div v-if="membre.retrieved_at" class="text-xs text-gray-500">
                        Retirée: {{ formatDate(membre.retrieved_at) }}
                      </div>
                      <div v-if="membre.retrieved_by" class="text-xs text-gray-500 italic">
                        Par: {{ membre.retrieved_by }}
                      </div>
                    </div>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <div class="space-y-1">
                      <span v-if="membre.statut_paiement === 'PAYE'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Payé
                      </span>
                      <span v-else-if="membre.statut_paiement === 'EN_ATTENTE'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Échec
                      </span>
                      <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        Non payé
                      </span>
                      
                      <!-- Référence de paiement -->
                      <div v-if="membre.ref_paiement" class="text-xs text-gray-500 font-mono">
                        {{ membre.transaction_id }}
                      </div>
                    </div>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-1 lg:space-x-2">
                      <button @click="viewMembre(membre)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" title="Voir détails">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                      
                      <button @click="editMembre(membre)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Modifier">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </button>
                      
                      <button @click="downloadFicheMembre(membre)" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition-colors" title="Télécharger fiche">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </button>
                      
                      <!-- Bouton Print -->
                      <button @click="printCard(membre)" class="text-orange-600 hover:text-orange-900 p-1 rounded hover:bg-orange-50 transition-colors" title="Imprimer carte">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                      </button>
                      
                      <!-- Bouton Carte Imprimée / Carte Retirée -->
                      <button 
                        v-if="membre.card_status !== 'printed' && membre.card_status !== 'retrieved'" 
                        @click="markCardPrinted(membre)" 
                        class="text-teal-600 hover:text-teal-900 p-1 rounded hover:bg-teal-50 transition-colors" 
                        title="Marquer comme imprimée"
                      >
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </button>
                      
                      <button 
                        v-else-if="membre.card_status === 'printed'" 
                        @click="openRetrievalModal(membre)" 
                        class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" 
                        title="Marquer comme retirée"
                      >
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-4 lg:px-6 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between border-t border-gray-200 space-y-4 lg:space-y-0">
              <div class="flex items-center">
                <span class="text-sm text-gray-700">
                  Affichage de <span class="font-medium">{{ pagination.from }}</span> à <span class="font-medium">{{ pagination.to }}</span> sur <span class="font-medium">{{ pagination.total }}</span> résultats
                </span>
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="changePage(pagination.current_page - 1)" 
                  :disabled="pagination.current_page === 1"
                  class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                >
                  Précédent
                </button>
                <div class="flex items-center space-x-1">
                  <template v-for="page in paginationPages" :key="page">
                    <button 
                      @click="changePage(page)" 
                      :class="[
                        'px-3 py-1 rounded-md text-sm',
                        pagination.current_page === page 
                          ? 'bg-green-600 text-white' 
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
                  class="px-3 py-1 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                >
                  Suivant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal Agrandissement Image -->
      <div v-if="showImageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4" @click="closeImageModal">
        <div class="relative max-w-4xl max-h-full">
          <img :src="selectedImage" alt="Photo agrandie" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
          <button @click="closeImageModal" class="absolute top-4 right-4 bg-white text-gray-800 rounded-full p-2 hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
  
      <!-- Modal Voir Membre -->
      <div v-if="showViewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" >
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="padding: 10px">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              Détails du Membre
            </h3>
          </div>
          
          <div v-if="selectedMembre" class="p-6 space-y-6">
            <!-- Photo et informations principales -->
            <div class="flex items-start space-x-6">
              <div class="flex-shrink-0">
                <img v-if="selectedMembre.photo" :src="selectedMembre.photo" :alt="`Photo de ${selectedMembre.prenom} ${selectedMembre.nom}`" class="h-24 w-24 rounded-full object-cover border-4 border-green-200 cursor-pointer hover:scale-110 transition-transform" @click="openImageModal(selectedMembre.photo)">
                <div v-else class="h-24 w-24 rounded-full bg-green-100 flex items-center justify-center border-4 border-green-200">
                  <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
              </div>
              <div class="flex-1">
                <h4 class="text-xl font-bold text-gray-900">{{ selectedMembre.prenom }} {{ selectedMembre.nom }}</h4>
                <p class="text-green-600 font-medium">{{ selectedMembre.matricule }}</p>
                <p class="text-gray-600">{{ getStatutLabel(selectedMembre.statut) }}</p>
                <div class="mt-2">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" :class="getCardStatusBadgeClass(selectedMembre.card_status)">
                    {{ getCardStatusLabel(selectedMembre.card_status) }}
                  </span>
                </div>
              </div>
            </div>
  
            <!-- Informations personnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <h5 class="font-semibold text-gray-800 border-b pb-2">Informations Personnelles</h5>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Date de naissance</label>
                  <p class="text-gray-900">{{ formatDate(selectedMembre.date_naissance) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Lieu de naissance</label>
                  <p class="text-gray-900">{{ selectedMembre.lieu_naissance }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Sexe</label>
                  <p class="text-gray-900">{{ selectedMembre.sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Contact</label>
                  <p class="text-gray-900">{{ selectedMembre.contact }}</p>
                </div>
              </div>
  
              <div class="space-y-4">
                <h5 class="font-semibold text-gray-800 border-b pb-2">Informations Militantisme</h5>
                <div>
                  <label class="block text-sm font-medium text-gray-500">SR de début</label>
                  <p class="text-gray-900">{{ selectedMembre.sr_debut }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Sous-comité de début</label>
                  <p class="text-gray-900">{{ selectedMembre.sous_comite }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Année de début</label>
                  <p class="text-gray-900">{{ selectedMembre.annee_debut }}</p>
                </div>
                <div v-if="selectedMembre.poste">
                  <label class="block text-sm font-medium text-gray-500">Poste</label>
                  <p class="text-gray-900">{{ selectedMembre.poste }}</p>
                </div>
              </div>
            </div>
  
            <!-- Informations carte -->
            <div v-if="selectedMembre.card_status === 'retrieved'" class="space-y-4">
              <h5 class="font-semibold text-gray-800 border-b pb-2">Informations Carte</h5>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Date d'impression</label>
                  <p class="text-gray-900">{{ formatDate(selectedMembre.printed_at) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Date de retrait</label>
                  <p class="text-gray-900">{{ formatDate(selectedMembre.retrieved_at) }}</p>
                </div>
                <div v-if="selectedMembre.retrieved_by">
                  <label class="block text-sm font-medium text-gray-500">Retirée par</label>
                  <p class="text-gray-900">{{ selectedMembre.retrieved_by }}</p>
                </div>
              </div>
            </div>
  
            <!-- Informations complémentaires -->
            <div v-if="selectedMembre.commune_ville || selectedMembre.section" class="space-y-4">
              <h5 class="font-semibold text-gray-800 border-b pb-2">Informations Complémentaires</h5>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-if="selectedMembre.commune_ville">
                  <label class="block text-sm font-medium text-gray-500">Commune/Ville ( Sous-comité )</label>
                  <p class="text-gray-900">{{ selectedMembre.commune_ville }}</p>
                </div>
                <div v-if="selectedMembre.section">
                  <label class="block text-sm font-medium text-gray-500">Section</label>
                  <p class="text-gray-900">{{ selectedMembre.section }}</p>
                </div>
              </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button style="background: green" @click="downloadFicheMembre(selectedMembre)" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Télécharger Fiche</span>
              </button>
              <button @click="showViewModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal Modifier Membre -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="padding: 10px">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              Modifier Membre
            </h3>
          </div>
          
          <form @submit.prevent="updateMembre()" class="p-6 space-y-6">
            <!-- Informations personnelles -->
            <div class="space-y-4">
              <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Informations Personnelles</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                  <input
                    v-model="editForm.prenom"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                  <input
                    v-model="editForm.nom"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                  <input
                    v-model="editForm.dateNaissance"
                    type="date"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance *</label>
                  <input
                    v-model="editForm.lieuNaissance"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Contact *</label>
                  <input
                    v-model="editForm.contact"
                    type="tel"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Sexe *</label>
                  <select
                    v-model="editForm.sexe"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                    <option value="">Sélectionner</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                  </select>
                </div>
              </div>
            </div>
  
            <!-- Informations militantisme -->
            <div class="space-y-4">
              <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Informations Militantisme</h4>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">SR de début *</label>
                  <input
                    v-model="editForm.srDebut"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Sous-comité de début *</label>
                  <input
                    v-model="editForm.sousComite"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Année de début *</label>
                  <input
                    v-model="editForm.anneeDebut"
                    type="number"
                    min="1990"
                    max="2030"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
              </div>
            </div>
  
            <!-- Statut et poste -->
            <div class="space-y-4">
              <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Statut et Poste</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                  <select
                    v-model="editForm.statut"
                    
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                    <option value="">Sélectionner un statut</option>
                    <option value="SR">Secrétaire Régional</option>
                    <option value="SOUS_COMITE">Membre du bureau (Sous-comité)</option>
                    <option value="BUREAU_SECTION">Membre du bureau (Section)</option>
                    <option value="MEMBRE_SIMPLE">Membre simple</option>
                    <option value="IMAM_REGIONAL">Imam Régional</option>
                    <option value="IMAM_SOUS_COMITE">Imam de Sous-comité</option>
                    <option value="IMAM_SECTION">Imam de Section</option>
                    <option value="FORMATEUR_SOUS_COMITE">Formateur de Sous-comité</option>
                    <option value="FORMATEUR_SECTION">Formateur de section</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Secrétariat actuel</label>
                  <input
                    v-model="editForm.secretariatPoste"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Poste</label>
                  <input
                    v-model="editForm.poste"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Commune/Ville</label>
                  <input
                    v-model="editForm.communeVille"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                  <input
                    v-model="editForm.section"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
              </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button type="button" @click="closeEditModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Annuler
              </button>
              <button style="background: green" type="submit" :disabled="submitting" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 flex items-center space-x-2">
                <svg v-if="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Modifier</span>
              </button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Modal Retrait de Carte -->
      <div v-if="showRetrievalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Retrait de Carte
            </h3>
          </div>
          
          <form @submit.prevent="markCardRetrieved()" class="p-6 space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Membre</label>
              <p class="text-gray-900 font-medium">{{ selectedMembre?.prenom }} {{ selectedMembre?.nom }}</p>
              <p class="text-sm text-gray-500">{{ selectedMembre?.matricule }}</p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nom du retireur *</label>
              <input
                v-model="retrievalForm.retrieved_by"
                type="text"
                required
                placeholder="Nom de la personne qui retire la carte"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
              <textarea
                v-model="retrievalForm.comment"
                rows="3"
                placeholder="Commentaire sur le retrait..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              ></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button type="button" @click="closeRetrievalModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                Annuler
              </button>
              <button type="submit" :disabled="submitting" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 flex items-center space-x-2">
                <svg v-if="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Confirmer le retrait</span>
              </button>
            </div>
          </form>
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
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
  
  // Données réactives
  const API_BASE_URL = 'https://sogetrag.com/api/membres.php'
  const membres = ref([])
  const secretariats = ref([
    'ABIDJAN NORD', 'ABIDJAN SUD', 'ABIDJAN EST', 'ABIDJAN OUEST',
    'AGNEBI-TIASSA ET ME', 'BAGOUE', 'BELIER', 'BERE', 'BAFING', 'BOUNKANI',
    'CAVALLY', 'FOLON', 'GBEKE', 'GBOKLE', 'GOH', 'GONTOUGO',
    'GRANDS-PONTS', 'N\'ZI', 'HAUT-SASSANDRA', 'IFFOU', 'INDENIE-DJUABLIN',
    'KABADOUGOU', 'LOH-DJIBOUA', 'NAWA', 'MORONOU', 'PORO', 'WORODOUGOU',
    'MARAHOUE', 'TCHOLOGO', 'TONPKI', 'SAN-PEDRO', 'SUD COMOE', 'GUEMON', 'HAMBOL'
  ])
  
  const loading = ref(false)
  const submitting = ref(false)
  const exporting = ref(false)
  const showViewModal = ref(false)
  const showEditModal = ref(false)
  const showRetrievalModal = ref(false)
  const showImageModal = ref(false)
  const selectedMembre = ref(null)
  const selectedImage = ref('')
  const searchTimeout = ref(null)
  
  const stats = ref({
    total_members: 0,
    total_secretariats: 0,
    paid_cards: 0,
    retrieved_cards: 0
  })
  
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    from: 0,
    to: 0
  })
  
  const filters = ref({
    search: '',
    secretariat: '',
    statut: '',
    card_status: ''
  })
  
  const editForm = ref({
    prenom: '',
    nom: '',
    dateNaissance: '',
    lieuNaissance: '',
    contact: '',
    sexe: '',
    srDebut: '',
    sousComite: '',
    anneeDebut: '',
    statut: '',
    secretariatPoste: '',
    poste: '',
    communeVille: '',
    section: ''
  })
  
  const retrievalForm = ref({
    retrieved_by: '',
    comment: ''
  })
  
  const alert = ref({
    show: false,
    type: 'success',
    message: ''
  })
  
  const showSecretariatDropdown = ref(false)
  const secretariatSearchTerm = ref('')
  
  // Computed properties
  const paginationPages = computed(() => {
    const pages = []
    const maxPages = 5
    
    if (pagination.value.last_page <= maxPages) {
      for (let i = 1; i <= pagination.value.last_page; i++) {
        pages.push(i)
      }
    } else {
      let startPage = Math.max(1, pagination.value.current_page - Math.floor(maxPages / 2))
      let endPage = Math.min(pagination.value.last_page, startPage + maxPages - 1)
      
      if (endPage - startPage + 1 < maxPages) {
        startPage = Math.max(1, endPage - maxPages + 1)
      }
      
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i)
      }
    }
    
    return pages
  })
  
  const selectedSecretariatText = computed(() => {
    return filters.value.secretariat || ''
  })
  
  const filteredSecretariatsForDropdown = computed(() => {
    if (!secretariatSearchTerm.value) {
      return secretariats.value
    }
    return secretariats.value.filter(secretariat => 
      secretariat.toLowerCase().includes(secretariatSearchTerm.value.toLowerCase())
    )
  })
  
  // Méthodes
  const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
      showSecretariatDropdown.value = false
    }
  }
  
  const openImageModal = (imageUrl) => {
    selectedImage.value = imageUrl
    showImageModal.value = true
    document.body.style.overflow = 'hidden'
  }
  
  const closeImageModal = () => {
    showImageModal.value = false
    selectedImage.value = ''
    document.body.style.overflow = 'auto'
  }

  const formatXOF = (value) => {
  if (!value) return '0 XOF';
  return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(value) + ' XOF';
}
  
  const printCard = (membre) => {
    // Rediriger vers la page de génération de carte avec l'ID du membre
    window.open(`/fiche-membre/${membre.id}`, '_blank')
    showAlert('success', 'Ouverture de la page d\'impression de la carte...')
  }
  
  const changePage = async (page) => {
    if (page < 1 || page > pagination.value.last_page) return
    pagination.value.current_page = page
    await loadMembres()
  }
  
  const changePerPage = async () => {
    pagination.value.current_page = 1
    await loadMembres()
  }
  
  const applyFilters = async () => {
    pagination.value.current_page = 1
    await loadMembres()
  }
  
  const debounceSearch = () => {
    if (searchTimeout.value) {
      clearTimeout(searchTimeout.value)
    }
    searchTimeout.value = setTimeout(() => {
      applyFilters()
    }, 500)
  }
  
  const resetFilters = () => {
    filters.value = {
      search: '',
      secretariat: '',
      statut: '',
      card_status: ''
    }
    pagination.value.current_page = 1
    loadMembres()
  }
  
  const loadData = async () => {
    loading.value = true
    try {
      await Promise.all([
        loadMembres(),
        loadStats()
      ])
    } catch (error) {
      console.error('Erreur lors du chargement:', error)
      showAlert('error', 'Erreur lors du chargement des données')
    } finally {
      loading.value = false
    }
  }
  
  const loadMembres = async () => {
    try {
      let url = `${API_BASE_URL}?action=membres&page=${pagination.value.current_page}&per_page=${pagination.value.per_page}&rand=${Math.random()}`
      
      if (filters.value.search) {
        url += `&search=${encodeURIComponent(filters.value.search)}`
      }
      if (filters.value.secretariat) {
        url += `&secretariat=${encodeURIComponent(filters.value.secretariat)}`
      }
      if (filters.value.statut) {
        url += `&statut=${filters.value.statut}`
      }
      if (filters.value.card_status) {
        url += `&card_status=${filters.value.card_status}`
      }
      
      const response = await fetch(url)
      const data = await response.json()
      
      if (data.success) {
        // Simuler des données avec statut de carte pour la démo
        membres.value = (data.data || []).map(membre => ({
          ...membre,
          card_status: membre.card_status || (Math.random() > 0.7 ? 'printed' : Math.random() > 0.5 ? 'retrieved' : 'pending'),
          printed_at: membre.printed_at || (membre.card_status === 'printed' || membre.card_status === 'retrieved' ? new Date().toISOString() : null),
          retrieved_at: membre.retrieved_at || (membre.card_status === 'retrieved' ? new Date().toISOString() : null),
          retrieved_by: membre.retrieved_by || (membre.card_status === 'retrieved' ? 'John Doe' : null),
          statut_paiement: membre.statut_paiement || (Math.random() > 0.8 ? 'PAYE' : Math.random() > 0.5 ? 'EN_ATTENTE' : 'NON_PAYE')
        }))
        
        if (data.pagination) {
          pagination.value = {
            ...pagination.value,
            ...data.pagination
          }
        }
      } else {
        throw new Error(data.error || 'Erreur lors du chargement des membres')
      }
    } catch (error) {
      console.error('Erreur loadMembres:', error)
      showAlert('error', 'Erreur lors du chargement des membres')
    }
  }
  
  const loadStats = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}?action=stats&rand=${Math.random()}`)
      const data = await response.json()
      
      if (data.success) {
        stats.value = {
          ...data.data,
        //  paid_cards: data.data?.paid_cards || Math.floor(Math.random() * 100),
         // retrieved_cards: data.data?.retrieved_cards || Math.floor(Math.random() * 80)
        }
      }
    } catch (error) {
      console.error('Erreur loadStats:', error)
    }
  }
  
  const exportExcel = async () => {
    try {
      exporting.value = true
      showAlert('success', 'Génération du fichier Excel en cours...')
      
      if (membres.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des membres.')
        return
      }
      
      const data = membres.value.map(membre => ({
        'Matricule': membre.matricule,
        'Prénom': membre.prenom,
        'Nom': membre.nom,
        'Sexe': membre.sexe === 'M' ? 'Masculin' : 'Féminin',
        'Date de naissance': formatDate(membre.date_naissance),
        'Lieu de naissance': membre.lieu_naissance,
        'Contact': membre.contact,
        'Statut': getStatutLabel(membre.statut),
        'SR de début': membre.sr_debut,
        'Sous-comité': membre.sous_comite,
        'Année de début': membre.annee_debut,
        'Secrétariat actuel': membre.secretariat_poste,
        'Qualite': membre.qualite_membre || '',
        'Poste Abr': membre.poste || '',
        'Poste Def': membre.poste_definition || '',
        'Anciens/Nouveau': membre.type_membre || '',
        'Commune/Ville': membre.commune_ville || '',
        'Section': membre.section || '',
        'Statut carte': getCardStatusLabel(membre.card_status),
        'Statut paiement': getPaymentStatusLabel(membre.statut_paiement),
        'Date impression': formatDate(membre.printed_at),
        'Date retrait': formatDate(membre.retrieved_at),
        'Retirée par': membre.retrieved_by || '',
        'Photo': membre.photo || '',
        'Date d\'inscription': formatDate(membre.created_at)
      }))
      
      const XLSX = await import('xlsx')
      const workbook = XLSX.utils.book_new()
      const worksheet = XLSX.utils.json_to_sheet(data)
      
      const colWidths = [
        { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 15 },
        { wch: 20 }, { wch: 15 }, { wch: 25 }, { wch: 20 }, { wch: 15 },
        { wch: 10 }, { wch: 20 }, { wch: 20 }, { wch: 15 }, { wch: 15 }, 
        { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 15 }
      ]
      worksheet['!cols'] = colWidths
      
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Membres AEEMCI')
      
      const today = new Date().toISOString().split('T')[0]
      const filename = `membres_aeemci_${today}.xlsx`
      XLSX.writeFile(workbook, filename)
      
      showAlert('success', `Export Excel réussi ! ${data.length} membres exportés.`)
      
    } catch (error) {
      console.error('Erreur lors de l\'export Excel:', error)
      showAlert('error', 'Erreur lors de l\'export Excel.')
    } finally {
      exporting.value = false
    }
  }
  
  const exportPDF = async () => {
    try {
      exporting.value = true
      showAlert('success', 'Génération du PDF en cours...')
      
      if (membres.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des membres.')
        return
      }
      
      const data = membres.value
      const totalMembers = data.length
      const secretariatsUniques = [...new Set(data.map(membre => membre.secretariat_poste || membre.sr_debut))].filter(Boolean)
      
      const { jsPDF } = await import('jspdf')
      const autoTable = (await import('jspdf-autotable')).default
      
      // Créer le PDF en format paysage
      const doc = new jsPDF('landscape')
      
      // En-tête
      doc.setFontSize(20)
      doc.setTextColor(40, 40, 40)
      doc.text('LISTE DES MEMBRES AEEMCI', 148, 20, { align: 'center' })
      
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
      doc.text(`Généré le ${today}`, 148, 30, { align: 'center' })
      
      // Résumé
      doc.setFontSize(14)
      doc.setTextColor(40, 40, 40)
      doc.text('RÉSUMÉ EXÉCUTIF', 20, 45)
      
      doc.setDrawColor(200, 200, 200)
      doc.setFillColor(248, 249, 250)
      doc.rect(15, 50, 266, 35, 'FD')
      
      doc.setFontSize(11)
      doc.setTextColor(40, 40, 40)
      doc.text(`Total des membres: ${totalMembers}`, 20, 60)
      doc.text(`Secrétariats représentés: ${secretariatsUniques.length}`, 20, 68)
      doc.text(`Cartes payées: ${data.filter(m => m.statut_paiement === 'PAYE').length}`, 20, 76)
      
      // Tableau des données
      const tableData = data.map(membre => [
        membre.matricule || '',
        `${membre.prenom} ${membre.nom}`,
        getStatutLabel(membre.statut),
        membre.secretariat_poste || membre.sr_debut || '',
        membre.contact || '',
        getCardStatusLabel(membre.card_status),
        getPaymentStatusLabel(membre.statut_paiement),
        formatDate(membre.created_at) || ''
      ])
      
      autoTable(doc, {
        head: [['Matricule', 'Nom Complet', 'Statut', 'Secrétariat', 'Contact', 'Statut Carte', 'Paiement', 'Date Inscription']],
        body: tableData,
        startY: 95,
        styles: {
          fontSize: 8,
          cellPadding: 2
        },
        headStyles: {
          fillColor: [34, 197, 94],
          textColor: 255,
          fontStyle: 'bold'
        },
        alternateRowStyles: {
          fillColor: [248, 249, 250]
        },
        columnStyles: {
          0: { cellWidth: 25 }, 
          1: { cellWidth: 45 }, 
          2: { cellWidth: 40 }, 
          3: { cellWidth: 45 },
          4: { cellWidth: 25 },
          5: { cellWidth: 25 },
          6: { cellWidth: 25 },
          7: { cellWidth: 25 }
        }
      })
      
      // Pied de page
      const pageCount = doc.internal.getNumberOfPages()
      for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i)
        doc.setFontSize(8)
        doc.setTextColor(100, 100, 100)
        doc.text(`Page ${i} sur ${pageCount}`, 148, 200, { align: 'center' })
        doc.text('AEEMCI - Association des Étudiants et Élèves Musulmans de Côte d\'Ivoire', 148, 195, { align: 'center' })
      }
      
      const filename = `liste_membres_aeemci_${new Date().toISOString().split('T')[0]}.pdf`
      doc.save(filename)
      
      showAlert('success', `Export PDF réussi ! ${totalMembers} membres exportés.`)
      
    } catch (error) {
      console.error('Erreur lors de l\'export PDF:', error)
      showAlert('error', 'Erreur lors de l\'export PDF.')
    } finally {
      exporting.value = false
    }
  }
  
  const updateMembre = async () => {
    if (!editForm.value.prenom || !editForm.value.nom || !editForm.value.contact || !editForm.value.statut) {
      showAlert('error', 'Veuillez remplir tous les champs obligatoires')
      return
    }
    
    submitting.value = true
    try {
      const response = await fetch(`${API_BASE_URL}?action=update_membre&id=${selectedMembre.value.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(editForm.value)
      })
      const data = await response.json()
      
      if (data.success) {
        showAlert('success', 'Membre modifié avec succès')
        closeEditModal()
        await loadData()
      } else {
        throw new Error(data.error || 'Erreur lors de la modification')
      }
    } catch (error) {
      console.error('Erreur updateMembre:', error)
      showAlert('error', error.message || 'Erreur lors de la modification du membre')
    } finally {
      submitting.value = false
    }
  }
  
  const markCardPrinted = async (membre) => {
    try {
      submitting.value = true
      
      // Simuler l'appel API
      const response = await fetch(`${API_BASE_URL}?action=mark_printed&id=${membre.id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          printed_at: new Date().toISOString()
        })
      })
      
      // Mettre à jour localement pour la démo
      const index = membres.value.findIndex(m => m.id === membre.id)
      if (index !== -1) {
        membres.value[index].card_status = 'printed'
        membres.value[index].printed_at = new Date().toISOString()
      }
      
      showAlert('success', 'Carte marquée comme imprimée avec succès')
      await loadStats()
      
    } catch (error) {
      console.error('Erreur markCardPrinted:', error)
      showAlert('error', 'Erreur lors de la mise à jour du statut de la carte')
    } finally {
      submitting.value = false
    }
  }
  
  const openRetrievalModal = (membre) => {
    selectedMembre.value = membre
    retrievalForm.value = {
      retrieved_by: '',
      comment: ''
    }
    showRetrievalModal.value = true
  }
  
  const markCardRetrieved = async () => {
    if (!retrievalForm.value.retrieved_by.trim()) {
      showAlert('error', 'Veuillez saisir le nom du retireur')
      return
    }
    
    try {
      submitting.value = true
      
      // Simuler l'appel API
      const response = await fetch(`${API_BASE_URL}?action=mark_retrieved&id=${selectedMembre.value.id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          retrieved_by: retrievalForm.value.retrieved_by,
          retrieved_at: new Date().toISOString(),
          comment: retrievalForm.value.comment
        })
      })
      
      // Mettre à jour localement pour la démo
      const index = membres.value.findIndex(m => m.id === selectedMembre.value.id)
      if (index !== -1) {
        membres.value[index].card_status = 'retrieved'
        membres.value[index].retrieved_at = new Date().toISOString()
        membres.value[index].retrieved_by = retrievalForm.value.retrieved_by
      }
      
      showAlert('success', 'Carte marquée comme retirée avec succès')
      closeRetrievalModal()
      await loadStats()
      
    } catch (error) {
      console.error('Erreur markCardRetrieved:', error)
      showAlert('error', 'Erreur lors de la mise à jour du statut de la carte')
    } finally {
      submitting.value = false
    }
  }
  
  const viewMembre = (membre) => {
    selectedMembre.value = membre
    showViewModal.value = true
  }
  
  const editMembre = (membre) => {
    selectedMembre.value = membre
    editForm.value = {
      prenom: membre.prenom,
      nom: membre.nom,
      dateNaissance: membre.date_naissance,
      lieuNaissance: membre.lieu_naissance,
      contact: membre.contact,
      sexe: membre.sexe,
      srDebut: membre.sr_debut,
      sousComite: membre.sous_comite,
      anneeDebut: membre.annee_debut,
      statut: membre.statut,
      secretariatPoste: membre.secretariat_poste || '',
      poste: membre.poste || '',
      communeVille: membre.commune_ville || '',
      section: membre.section || ''
    }
    showEditModal.value = true
  }
  
  const closeEditModal = () => {
    showEditModal.value = false
    selectedMembre.value = null
    editForm.value = {
      prenom: '',
      nom: '',
      dateNaissance: '',
      lieuNaissance: '',
      contact: '',
      sexe: '',
      srDebut: '',
      sousComite: '',
      anneeDebut: '',
      statut: '',
      secretariatPoste: '',
      poste: '',
      communeVille: '',
      section: ''
    }
  }
  
  const closeRetrievalModal = () => {
    showRetrievalModal.value = false
    selectedMembre.value = null
    retrievalForm.value = {
      retrieved_by: '',
      comment: ''
    }
  }
  
  const downloadFicheMembre = async (membre) => {
  try {
    showAlert('success', 'Génération de la fiche membre en cours...')
    
    const { default: jsPDF } = await import('jspdf')
    const QRCode = await import('qrcode')
    
    const doc = new jsPDF('p', 'mm', 'a4')
    const today = new Date()
    const receiptNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0')
    const qrText = `https://aeemci.sastock.com/membre/${membre.matricule}`
    
    const qrDataURL = await QRCode.toDataURL(qrText, {
      width: 120,
      margin: 1,
    })

    // Filigrane AEEMCI en arrière-plan (réduit)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)
    doc.setTextColor(245, 245, 245)
    for (let y = 20; y <= 280; y += 20) {
      for (let x = 10; x <= 200; x += 40) {
        doc.text('AEEMCI', x, y)
      }
    }

    // Logo AEEMCI (plus petit)
    try {
      doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 8, 20, 18)
    } catch (e) {
      console.warn('Logo non chargé:', e)
    }

    // En-tête AEEMCI (compact)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(18)
    doc.setTextColor(0, 128, 0)
    doc.text('AEEMCI', 85, 18)
    
    doc.setFontSize(9)
    doc.setTextColor(0, 0, 0)
    doc.text("Association des Elèves et Etudiants Musulmans de Côte d'Ivoire", 105, 23, { align: 'center' })

    // Titre principal (plus compact)
    const cardX = 55
    const cardY = 28
    const cardWidth = 95
    const cardHeight = 15
    
    doc.setFillColor(0, 200, 100)
    doc.roundedRect(cardX, cardY, cardWidth, cardHeight, 3, 3, 'F')
    
    doc.setFont('helvetica', 'bold')
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(10)
    doc.text("FICHE MEMBRE AEEMCI", 102.5, cardY + 6, { align: 'center' })
    doc.text(`N° ${receiptNumber}`, 102.5, cardY + 11, { align: 'center' })

    // Matricule (compact)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.text(`MATRICULE: ${membre.matricule}`, 105, 50, { align: 'center' })
    
    // Ligne de séparation
    doc.setLineWidth(0.1)
    doc.line(20, 55, 190, 55)

    let currentY = 62

    // Photo du membre (plus petite)
    if (membre.photo) {
      try {
        doc.addImage(membre.photo, 'JPEG', 155, currentY, 30, 30)
      } catch (e) {
        console.warn('Photo non chargée:', e)
        doc.rect(155, currentY, 30, 30)
        doc.setFontSize(6)
        doc.setTextColor(0, 0, 0)
        doc.text('Photo non', 170, currentY + 13, { align: 'center' })
        doc.text('disponible', 170, currentY + 18, { align: 'center' })
      }
    } else {
      doc.rect(155, currentY, 30, 30)
      doc.setFontSize(6)
      doc.setTextColor(0, 0, 0)
      doc.text('Aucune photo', 170, currentY + 15, { align: 'center' })
    }

    // QR Code (plus petit et repositionné)
    doc.addImage(qrDataURL, 'PNG', 155, currentY + 35, 25, 25)

    // Fonction helper pour ajouter des champs (plus compact)
    const addField = (label, value, y, fontSize = 8) => {
      doc.setFont('helvetica', 'normal')
      doc.setTextColor(0, 0, 0)
      doc.setFontSize(fontSize)
      doc.text(`${label}:`, 20, y)
      doc.setFont('helvetica', 'bold')
      const displayValue = value || 'N/A'
      // Limiter la longueur du texte
      const maxLength = 25
      const truncatedValue = displayValue.length > maxLength ? displayValue.substring(0, maxLength) + '...' : displayValue
      doc.text(truncatedValue, 65, y)
    }

    // === SECTION 1: IDENTIFICATION (compact) ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.setTextColor(0, 128, 0)
    doc.text("1. IDENTIFICATION", 20, currentY)
    currentY += 8

    addField('Nom', membre.nom, currentY)
    addField('Prénom(s)', membre.prenom, currentY + 6)
    addField('Date naissance', formatDate(membre.date_naissance), currentY + 12)
    addField('Lieu naissance', membre.lieu_naissance, currentY + 18)
    addField('Sexe', membre.sexe === 'M' ? 'Masculin' : 'Féminin', currentY + 24)
    addField('Contact', membre.contact, currentY + 30)

    currentY += 42

    // === SECTION 2: MILITANTISME (compact) ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.setTextColor(0, 128, 0)
    doc.text("2. MILITANTISME", 20, currentY)
    currentY += 8

    addField('SR début', membre.sr_debut, currentY)
    addField('Sous-comité', membre.sous_comite, currentY + 6)
    addField('Année début', membre.annee_debut?.toString(), currentY + 12)

    currentY += 22

    // === SECTION 3: STATUT ACTUEL (compact) ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.setTextColor(0, 128, 0)
    doc.text("3. STATUT & POSTE", 20, currentY)
    currentY += 8

    addField('Statut', getStatutLabel(membre.qualite_membre), currentY)
    addField('Secrétariat', membre.secretariat_poste || membre.region, currentY + 6)
    addField('Poste Def', membre.poste_definition, currentY + 12)
    addField('Commune/Ville', membre.commune_ville, currentY + 18)

    currentY += 28

    // === SECTION 4: CARTE & PAIEMENT (compact) ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.setTextColor(0, 128, 0)
    doc.text("4. CARTE & PAIEMENT", 20, currentY)
    currentY += 8

    addField('Statut carte', getCardStatusLabel(membre.card_status), currentY)
    addField('Paiement', getPaymentStatusLabel(membre.statut_paiement), currentY + 6)
    
    if (membre.printed_at) {
      addField('Impression', formatDate(membre.printed_at), currentY + 12)
      currentY += 6
    }
    
    if (membre.retrieved_at) {
      addField('Retrait', formatDate(membre.retrieved_at), currentY + 12)
      addField('Retirée par', membre.retrieved_by, currentY + 18)
      currentY += 12
    }

    if (membre.ref_paiement) {
      addField('Réf. paiement', membre.transaction_id, currentY + 12)
      currentY += 6
    }

    currentY += 20

    // === INFORMATIONS TECHNIQUES (compact) ===
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.setTextColor(0, 128, 0)
    doc.text("5. INFOS TECHNIQUES", 20, currentY)
    currentY += 8

    addField('Inscription', formatDate(membre.created_at), currentY)
    addField('Modification', formatDate(membre.updated_at), currentY + 6)

    // === Date et lieu de génération (compact) ===
    const dateStr = today.toLocaleDateString('fr-FR')
    const timeStr = today.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })

    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.setTextColor(100, 100, 100)
    doc.text(`Généré à Abidjan le ${dateStr} à ${timeStr}`, 20, 260)

    // === PIED DE PAGE (compact) ===
    doc.setFontSize(6)
    doc.setFont('helvetica', 'normal')
    doc.setTextColor(120, 120, 120)
    doc.text("Siège : Mosquée An-Nour Riviera II - 08 BP 2462 Abidjan 08", 105, 270, { align: 'center' })
    doc.text("Tél: 27 33 75 42 12 / 07 09 99 55 31 - Email: aeemci@yahoo.fr", 105, 275, { align: 'center' })
    
    doc.setFont('helvetica', 'bold')
    doc.setTextColor(0, 128, 0)
    doc.setFontSize(7)
    doc.text("AEEMCI, pour une identité islamique !", 105, 282, { align: 'center' })

    // Sauvegarde
    const filename = `fiche_${membre.matricule}_${today.toISOString().split('T')[0]}.pdf`
    doc.save(filename)
    
    showAlert('success', 'Fiche membre téléchargée avec succès')
  } catch (error) {
    console.error('Erreur downloadFicheMembre:', error)
    showAlert('error', 'Erreur lors du téléchargement de la fiche')
  }
}
  
  const generateMatricule = (membre) => {
    if (!membre.sr_debut || !membre.sous_comite || !membre.annee_debut) {
      return 'XXX-XXX-XX-001'
    }
    
    const secretariatCode = membre.sr_debut.substring(0, 3).toUpperCase()
    const communeCode = membre.sous_comite.substring(0, 3).toUpperCase()
    const anneeCode = membre.annee_debut.toString().slice(-2)
    const numeroCode = '001' // Simplified for demo
    
    return `${secretariatCode}-${communeCode}-${anneeCode}-${numeroCode}`
  }
  
  const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}
  
  const getStatutLabel = (statut) => {
    const labels = {
      'SR': 'Secrétariat Régional',
      'SOUS_COMITE': 'Membre du bureau (Sous-comité)',
      'BUREAU_SECTION': 'Membre du bureau (Section)',
      'MEMBRE_SIMPLE': 'Membre simple',
      'IMAM_REGIONAL': 'Imam Régional',
      'IMAM_SOUS_COMITE': 'Imam de Sous-comité',
      'IMAM_SECTION': 'Imam de Section',
      'FORMATEUR_SOUS_COMITE': 'Formateur de Sous-comité',
      'FORMATEUR_SECTION': 'Formateur de section',
      'Imam': 'Imam',
    }
    return labels[statut] || statut
  }
  
  const getStatutBadgeClass = (statut) => {
    const classes = {
      'SR': 'bg-purple-100 text-purple-800',
      'SOUS_COMITE': 'bg-blue-100 text-blue-800',
      'BUREAU_SECTION': 'bg-indigo-100 text-indigo-800',
      'MEMBRE_SIMPLE': 'bg-gray-100 text-gray-800',
      'IMAM_REGIONAL': 'bg-green-100 text-green-800',
      'IMAM_SOUS_COMITE': 'bg-emerald-100 text-emerald-800',
      'IMAM_SECTION': 'bg-teal-100 text-teal-800',
      'FORMATEUR_SOUS_COMITE': 'bg-orange-100 text-orange-800',
      'FORMATEUR_SECTION': 'bg-amber-100 text-amber-800'
    }
    return classes[statut] || 'bg-gray-100 text-gray-800'
  }
  
  const getCardStatusLabel = (status) => {
    const labels = {
      'pending': 'En attente',
      'printed': 'Imprimée',
      'retrieved': 'Retirée'
    }
    return labels[status] || 'En attente'
  }
  
  const getCardStatusBadgeClass = (status) => {
    const classes = {
      'pending': 'bg-yellow-100 text-yellow-800',
      'printed': 'bg-teal-100 text-teal-800',
      'retrieved': 'bg-blue-100 text-blue-800'
    }
    return classes[status] || 'bg-yellow-100 text-yellow-800'
  }
  
  const getPaymentStatusLabel = (status) => {
    const labels = {
      'PAYE': 'Payé',
      'EN_ATTENTE': 'Échec',
      'NON_PAYE': 'Non payé'
    }
    return labels[status] || 'Non payé'
  }
  
  const showAlert = (type, message) => {
    alert.value = {
      show: true,
      type,
      message
    }
    
    setTimeout(() => {
      alert.value.show = false
    }, 5000)
  }
  
  const toggleSecretariatDropdown = () => {
    showSecretariatDropdown.value = !showSecretariatDropdown.value
    if (showSecretariatDropdown.value) {
      secretariatSearchTerm.value = ''
    }
  }
  
  const selectSecretariat = (value, text) => {
    filters.value.secretariat = value
    showSecretariatDropdown.value = false
    secretariatSearchTerm.value = ''
    applyFilters()
  }
  
  const clearSecretariatSelection = () => {
    filters.value.secretariat = ''
    secretariatSearchTerm.value = ''
    applyFilters()
  }
  
  // Lifecycle hooks
  onMounted(() => {
    loadData()
    document.addEventListener('click', handleClickOutside)
  })
  
  onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
    document.body.style.overflow = 'auto'
  })
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
  
  /* Responsive improvements */
  @media (max-width: 768px) {
    .overflow-x-auto {
      -webkit-overflow-scrolling: touch;
    }
    
    table {
      font-size: 0.875rem;
    }
    
    .px-6 {
      padding-left: 1rem;
      padding-right: 1rem;
    }
  }
  
  /* Custom scrollbar for dropdowns */
  .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
  }
  
  .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }
  
  .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }
  
  .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }
  </style>
  