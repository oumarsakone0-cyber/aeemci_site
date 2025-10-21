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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
                </svg>
                Séminairistes SENAFOI 25
              </h1>
              <p class="text-gray-600 text-sm lg:text-base">Gestion des inscriptions au séminaire</p>
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
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Total Inscrits</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ pagination.total || 0 }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-green-100 rounded-full" @click="envoyer">
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
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Paiements Validés</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ formatXOF(stats.total_paid) || 0 }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-emerald-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-teal-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Dortoirs Assignés</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.assigned_dortoirs || 0 }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-teal-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Cars Assignés</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.assigned_cars || 0 }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-blue-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0a2 2 0 01-2 2H8a2 2 0 01-2-2v0a2 2 0 01-2-2V9a2 2 0 012-2h2z"/>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau Séminaire</label>
                <select
                  v-model="filters.niveau_seminaire"
                  @change="applyFilters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
                  <option value="">Tous les niveaux</option>
                  <option value="1AS">1AS</option>
                  <option value="2AS">2AS</option>
                  <option value="3AS">3AS</option>
                  <option value="1AF">1AF</option>
                  <option value="2AF">2AF</option>
                  <option value="3AF">3AF</option>
                  <option value="1BS">1BS</option>
                  <option value="2BS">2BS</option>
                  <option value="3BS">3BS</option>
                  <option value="1BF">1BF</option>
                  <option value="2BF">2BF</option>
                  <option value="3BF">3BF</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="PEPINIERE">PEPINIERE</option>
                  <option value="TEST_ENTREE">Test d'entrée</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut Paiement</label>
                <select
                  v-model="filters.statut_paiement"
                  @change="applyFilters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
                  <option value="">Tous</option>
                  <option value="PAYE">Payé</option>
                  <option value="EN_ATTENTE">En attente</option>
                  <option value="ECHEC">Échec</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut Inscription</label>
                <select
                  v-model="filters.statut_inscription"
                  @change="applyFilters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
                  <option value="">Tous</option>
                  <option value="VALIDEE">Validée</option>
                  <option value="EN_COURS">En cours</option>
                  <option value="REJETEE">Rejetée</option>
                  <option value="ANNULEE">Annulée</option>
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
  
        <!-- Tableau des séminairistes -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="padding: 10px; margin-top: 10px">
          <div class="px-4 lg:px-6 py-4 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-2 lg:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Liste des Séminairistes</h3>
            <div class="text-sm text-gray-500">
              Affichage {{ pagination.from || 0 }} à {{ pagination.to || 0 }} sur {{ pagination.total || 0 }} inscrits
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
                      <span>Séminairiste</span>
                    </div>
                  </th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Secrétariat</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Contact</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dortoir</th>
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
                
                <tr v-else-if="seminairistes.length === 0">
                  <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center space-y-2">
                      <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                      <span>Aucun séminairiste trouvé</span>
                    </div>
                  </td>
                </tr>
                
                <tr v-else v-for="seminairiste in seminairistes" :key="seminairiste.id_seminaire" class="hover:bg-gray-50 transition-colors duration-150" style="padding: 5px">
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8 lg:h-10 lg:w-10 relative">
                        <img 
                          v-if="seminairiste.photo" 
                          :src="seminairiste.photo" 
                          :alt="`Photo de ${seminairiste.prenom} ${seminairiste.nom}`" 
                          class="h-8 w-8 lg:h-10 lg:w-10 rounded-full object-cover border-2 border-green-200 cursor-pointer hover:scale-110 transition-transform" 
                          @click="openImageModal(seminairiste.photo)"
                        >
                        <div v-else class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-green-100 flex items-center justify-center">
                          <svg class="w-4 h-4 lg:w-5 lg:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                        </div>
                      </div>
                      <div class="ml-3 lg:ml-4">
                        <div class="text-xs lg:text-sm font-medium text-gray-900">{{ seminairiste.prenom }} {{ seminairiste.nom }}</div>
                        <div class="text-xs text-gray-500 lg:hidden">{{ seminairiste.contact }}</div>
                      </div>
                    </div>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 font-mono">
                      {{ seminairiste.matricule_seminaire }}
                    </span>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="padding: 5px"
                      :class="getNiveauBadgeClass(seminairiste.niveau_seminaire)"
                    >
                      {{ seminairiste.niveau_seminaire }}
                    </span>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900 hidden lg:table-cell">
                    {{ seminairiste.secretariat_regional }}
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900 hidden lg:table-cell">
                    {{ seminairiste.contact }}
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap" style="padding: 5px">
                    <div class="space-y-1">
                        <div v-if="seminairiste.dortoir">
                            <span v-if="seminairiste.sexe == 'M'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800" style="padding: 5px">
                                {{ getDortoirLabel(seminairiste.dortoir) }}
                            </span>
                            <span v-if="seminairiste.sexe == 'F'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800" style="padding: 5px">
                                {{ getDortoirLabel(seminairiste.dortoir) }}
                            </span>
                        </div>
                        <div v-else>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Non assigné
                            </span>
                        </div>
                      
                      <div v-if="seminairiste.car_transport" class="text-xs text-gray-500">
                        Car: {{ getCarLabel(seminairiste.car_transport) }}
                      </div>
                    </div>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <div class="space-y-1">
                      <span v-if="seminairiste.statut_paiement === 'PAYE'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800" style="padding: 5px">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Payé
                      </span>
                      <span v-else-if="seminairiste.statut_paiement === 'EN_ATTENTE'" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        En attente
                      </span>
                      <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800" style="padding: 5px">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Échec
                      </span>
                      
                      <div v-if="seminairiste.ref_paiement" class="text-xs text-gray-800 font-mono">
                        {{ seminairiste.somme_paye }} 
                      </div>
                      <div v-if="seminairiste.ref_paiement" class="text-xs text-gray-500 font-mono">
                        {{ seminairiste.ref_paiement }} 
                      </div>
                    </div>
                  </td>
                  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-1 lg:space-x-2">
                      <button @click="viewSeminairiste(seminairiste)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" title="Voir détails">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                      
                      <button @click="editSeminairiste(seminairiste)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Modifier">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </button>
                      
                      <button @click="downloadFicheSeminairiste(seminairiste)" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition-colors" title="Télécharger fiche">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </button>
                      
                      <button @click="assignDortoir(seminairiste)" class="text-orange-600 hover:text-orange-900 p-1 rounded hover:bg-orange-50 transition-colors" title="Assigner dortoir">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
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
  
      <!-- Modal Détails Séminairiste -->
      <div v-if="showDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click="closeDetailModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
          <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Détails du Séminairiste</h3>
            <button @click="closeDetailModal" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <div v-if="selectedSeminairiste" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Photo et infos principales -->
              <div class="lg:col-span-1">
                <div class="text-center mb-6">
                  <div class="w-32 h-32 mx-auto mb-4 relative">
                    <img 
                      v-if="selectedSeminairiste.photo" 
                      :src="selectedSeminairiste.photo" 
                      :alt="`Photo de ${selectedSeminairiste.prenom} ${selectedSeminairiste.nom}`"
                      class="w-32 h-32 rounded-full object-cover border-4 border-green-200"
                    >
                    <div v-else class="w-32 h-32 rounded-full bg-green-100 flex items-center justify-center border-4 border-green-200">
                      <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                    </div>
                  </div>
                  <h4 class="text-xl font-bold text-gray-900" style="margin-top: 50px">{{ selectedSeminairiste.prenom }} {{ selectedSeminairiste.nom }}</h4>
                  <p class="text-green-600 font-medium">{{ selectedSeminairiste.matricule_seminaire }}</p>
                </div>
                
                <!-- Statuts -->
                <div class="space-y-3">
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-600">Inscription</span>
                    <span :class="[
                      'px-2 py-1 rounded-full text-xs font-medium',
                      selectedSeminairiste.statut_inscription === 'VALIDEE' ? 'bg-green-100 text-green-800' :
                      selectedSeminairiste.statut_inscription === 'EN_COURS' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-red-100 text-red-800'
                    ]">
                      {{ getInscriptionStatusLabel(selectedSeminairiste.statut_inscription) }}
                    </span>
                  </div>
                  
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-600">Paiement</span>
                    <span :class="[
                      'px-2 py-1 rounded-full text-xs font-medium',
                      selectedSeminairiste.statut_paiement === 'PAYE' ? 'bg-green-100 text-green-800' :
                      selectedSeminairiste.statut_paiement === 'EN_ATTENTE' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-red-100 text-red-800'
                    ]">
                      {{ getPaymentStatusLabel(selectedSeminairiste.statut_paiement) }}
                    </span>
                  </div>
                </div>
              </div>
              
              <!-- Informations détaillées -->
              <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Informations personnelles -->
                  <div>
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Informations Personnelles</h5>
                    <div class="space-y-3">
                      <div>
                        <label class="text-sm font-medium text-gray-600">Sexe</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Contact</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.contact || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Contact Parent</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.contact_parent || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Niveau d'Étude</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.niveau_etude || 'Non renseigné' }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Informations séminaire -->
                  <div>
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Informations Séminaire</h5>
                    <div class="space-y-3">
                      <div>
                        <label class="text-sm font-medium text-gray-600">Niveau Séminaire</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.niveau_seminaire }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Secrétariat Régional</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.secretariat_regional || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Sous-comité</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.sous_comite || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Qualité</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.qualite || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Taille T-shirt</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.taille_tshirt || 'Non renseigné' }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Logistique -->
                  <div>
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Logistique</h5>
                    <div class="space-y-3">
                      <div>
                        <label class="text-sm font-medium text-gray-600">Dortoir</label>
                        <p class="text-gray-900">{{ getDortoirLabel(selectedSeminairiste.dortoir) }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Car de Transport</label>
                        <p class="text-gray-900">{{ getCarLabel(selectedSeminairiste.car_transport) }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Paiement -->
                  <div>
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Paiement</h5>
                    <div class="space-y-3">
                      <div>
                        <label class="text-sm font-medium text-gray-600">Numéro Wave</label>
                        <p class="text-gray-900">{{ selectedSeminairiste.numero_wave || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Référence Paiement</label>
                        <p class="text-gray-900 font-mono">{{ selectedSeminairiste.ref_paiement || 'Non renseigné' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-600">Date d'Inscription</label>
                        <p class="text-gray-900">{{ formatDate(selectedSeminairiste.created_at) }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex flex-wrap gap-3 justify-center">
              <button @click="downloadFicheSeminairiste(selectedSeminairiste)" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Télécharger Fiche</span>
              </button>
              <button @click="editSeminairiste(selectedSeminairiste); closeDetailModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Modifier</span>
              </button>
              <button @click="assignDortoir(selectedSeminairiste); closeDetailModal()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v11"/>
                </svg>
                <span>Assigner Logistique</span>
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal Édition Séminairiste -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click="closeEditModal">
        <div style="padding: 10px" class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
          <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Modifier le Séminairiste</h3>
            <button @click="closeEditModal" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <form @submit.prevent="saveEdit" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                <input v-model="editForm.prenom" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                <input v-model="editForm.nom" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                <select v-model="editForm.sexe" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                  <option value="M">Masculin</option>
                  <option value="F">Féminin</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact *</label>
                <input v-model="editForm.contact" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Parent</label>
                <input v-model="editForm.contact_parent" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'Étude</label>
                <input v-model="editForm.niveau_etude" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secrétariat Régional</label>
                <input v-model="editForm.secretariat_regional" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sous-comité</label>
                <input v-model="editForm.sous_comite" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Qualité</label>
                <input v-model="editForm.qualite" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Taille T-shirt</label>
                <select v-model="editForm.taille_tshirt" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                  <option value="">Sélectionner</option>
                  <option value="XS">XS</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                  <option value="XL">XL</option>
                  <option value="XXL">XXL</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau Séminaire</label>
                <select v-model="editForm.niveau_seminaire" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                  <option value="">Sélectionner</option>
                  <option value="1AS">1AS</option>
                  <option value="2AS">2AS</option>
                  <option value="3AS">3AS</option>
                  <option value="1AF">1AF</option>
                  <option value="2AF">2AF</option>
                  <option value="3AF">3AF</option>
                  <option value="1BS">1BS</option>
                  <option value="2BS">2BS</option>
                  <option value="3BS">3BS</option>
                  <option value="1BF">1BF</option>
                  <option value="2BF">2BF</option>
                  <option value="3BF">3BF</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="TEST_ENTREE">Test d'entrée</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut Inscription</label>
                <select v-model="editForm.statut_inscription" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                  <option value="EN_COURS">En cours</option>
                  <option value="VALIDEE">Validée</option>
                  <option value="REJETEE">Rejetée</option>
                  <option value="ANNULEE">Annulée</option>
                </select>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="closeEditModal" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                Annuler
              </button>
              <button type="submit" :disabled="submitting" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 flex items-center space-x-2">
                <svg v-if="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ submitting ? 'Sauvegarde...' : 'Sauvegarder' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Modal Assignation Dortoir/Car -->
      <div v-if="showDortoirModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click="closeDortoirModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" @click.stop>
          <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-xl font-bold text-gray-900">Assignation Logistique</h3>
            <p class="text-sm text-gray-600 mt-1">{{ selectedSeminairiste?.prenom }} {{ selectedSeminairiste?.nom }}</p>
          </div>
          
          <form @submit.prevent="saveDortoirAssignment" class="p-6">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dortoir</label>
                <select v-model="dortoirForm.dortoir" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                  <option value="">Sélectionner un dortoir</option>
                  <option value="DORTOIR_A">Dortoir A - Hommes</option>
                  <option value="DORTOIR_B">Dortoir B - Hommes</option>
                  <option value="DORTOIR_C">Dortoir C - Femmes</option>
                  <option value="DORTOIR_D">Dortoir D - Femmes</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Car de Transport</label>
                <select v-model="dortoirForm.car_transport" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                  <option value="">Sélectionner un car</option>
                  <option value="CAR_1">Car 1 - Abidjan Nord</option>
                  <option value="CAR_2">Car 2 - Abidjan Sud</option>
                  <option value="CAR_3">Car 3 - Intérieur</option>
                  <option value="CAR_4">Car 4 - Bouaké</option>
                  <option value="CAR_5">Car 5 - San Pedro</option>
                </select>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="closeDortoirModal" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                Annuler
              </button>
              <button type="submit" :disabled="submitting" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 flex items-center space-x-2">
                <svg v-if="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ submitting ? 'Assignation...' : 'Assigner' }}</span>
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
  import { sendSMS } from '../../services/smsService.js'
  
  // Données réactives
  const API_BASE_URL = 'https://sogetrag.com/api/seminaire_list_api.php'
  const seminairistes = ref([])
  const loading = ref(false)
  const submitting = ref(false)
  const exporting = ref(false)
  const showImageModal = ref(false)
  const selectedImage = ref('')
  const searchTimeout = ref(null)

  const phone = ref('')
  const message = ref('')
  const result = ref(null)
  const error = ref(null)
  
  // Modals
  const showDetailModal = ref(false)
  const showEditModal = ref(false)
  const showDortoirModal = ref(false)
  const selectedSeminairiste = ref(null)
  const editForm = ref({})
  const dortoirForm = ref({
    dortoir: '',
    car_transport: ''
  })
  
  const stats = ref({
    total_inscrits: 0,
    paid_count: 0,
    assigned_dortoirs: 0,
    assigned_cars: 0
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
    niveau_seminaire: '',
    statut_paiement: '',
    statut_inscription: ''
  })
  
  const alert = ref({
    show: false,
    type: 'success',
    message: ''
  })

  
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

  const formatXOF = (amount) => {
  if (!amount) return '0 F CFA';
  return new Intl.NumberFormat('fr-FR').format(amount) + ' F CFA';
}
  
  // Méthodes
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
  
  const changePage = async (page) => {
    if (page < 1 || page > pagination.value.last_page) return
    pagination.value.current_page = page
    await loadSeminairistes()
  }
  
  const changePerPage = async () => {
    pagination.value.current_page = 1
    await loadSeminairistes()
  }
  
  const applyFilters = async () => {
    pagination.value.current_page = 1
    await loadSeminairistes()
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
      niveau_seminaire: '',
      statut_paiement: '',
      statut_inscription: ''
    }
    pagination.value.current_page = 1
    loadSeminairistes()
  }
  
  const loadData = async () => {
    loading.value = true
    try {
      await Promise.all([
        loadSeminairistes(),
        loadStats()
      ])
    } catch (error) {
      console.error('Erreur lors du chargement:', error)
      showAlert('error', 'Erreur lors du chargement des données')
    } finally {
      loading.value = false
    }
  }
  
  const envoyer = async () => {
  try {
    result.value = await sendSMS(phone.value, message.value)
    error.value = null
  } catch (err) {
    error.value = err.message
    result.value = null
  }
}

  const loadSeminairistes = async () => {
    try {
      let url = `${API_BASE_URL}?action=list_seminairistes&page=${pagination.value.current_page}&per_page=${pagination.value.per_page}&rand=${Math.random()}`
      
      if (filters.value.search) {
        url += `&search=${encodeURIComponent(filters.value.search)}`
      }
      if (filters.value.niveau_seminaire) {
        url += `&niveau_seminaire=${filters.value.niveau_seminaire}`
      }
      if (filters.value.statut_paiement) {
        url += `&statut_paiement=${filters.value.statut_paiement}`
      }
      if (filters.value.statut_inscription) {
        url += `&statut_inscription=${filters.value.statut_inscription}`
      }
      
      const response = await fetch(url)
      const data = await response.json()
      
      if (data.success) {
        seminairistes.value = data.data || []
        
        if (data.pagination) {
          pagination.value = {
            ...pagination.value,
            ...data.pagination
          }
        }
      } else {
        throw new Error(data.error || 'Erreur lors du chargement des séminairistes')
      }
    } catch (error) {
      console.error('Erreur loadSeminairistes:', error)
      showAlert('error', 'Erreur lors du chargement des séminairistes')
    }
  }
  
  const loadStats = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}?action=stats&rand=${Math.random()}`)
      const data = await response.json()
      
      if (data.success) {
        stats.value = data.data || {}
      }
    } catch (error) {
      console.error('Erreur loadStats:', error)
    }
  }
  
  const exportExcel = async () => {
    try {
      exporting.value = true
      showAlert('success', 'Génération du fichier Excel en cours...')
      
      if (seminairistes.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter.')
        return
      }
      
      const data = seminairistes.value.map(seminairiste => ({
        'Matricule': seminairiste.matricule_seminaire,
        'Prénom': seminairiste.prenom,
        'Nom': seminairiste.nom,
        'Sexe': seminairiste.sexe === 'M' ? 'Masculin' : 'Féminin',
        'Contact': seminairiste.contact,
        'Niveau séminaire': seminairiste.niveau_seminaire,
        'Niveau Etude': seminairiste.niveau_etude,
        'Secrétariat': seminairiste.secretariat_regional,
        'Sous-comité': seminairiste.sous_comite,
        'Qualité': seminairiste.qualite,
        'Photo': seminairiste.photo,
        'T-shirt': seminairiste.taille_tshirt,
        'Dortoir': getDortoirLabel(seminairiste.dortoir),
        'Car': getCarLabel(seminairiste.car_transport),
        'Statut paiement': getPaymentStatusLabel(seminairiste.statut_paiement),
        'Statut inscription': getInscriptionStatusLabel(seminairiste.statut_inscription),
        'Date inscription': formatDate(seminairiste.created_at)
      }))
      
      const XLSX = await import('xlsx')
      const workbook = XLSX.utils.book_new()
      const worksheet = XLSX.utils.json_to_sheet(data)
      
      const colWidths = [
        { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 15 },
        { wch: 20 }, { wch: 20 }, { wch: 15 }, { wch: 20 }, { wch: 15 },
        { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 15 }
      ]
      worksheet['!cols'] = colWidths
      
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Séminairistes SENAFOI 25')
      
      const today = new Date().toISOString().split('T')[0]
      const filename = `seminairistes_senafoi25_${today}.xlsx`
      XLSX.writeFile(workbook, filename)
      
      showAlert('success', `Export Excel réussi ! ${data.length} séminairistes exportés.`)
      
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
      
      if (seminairistes.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter.')
        return
      }
      
      const { jsPDF } = await import('jspdf')
      const autoTable = (await import('jspdf-autotable')).default
      
      const doc = new jsPDF('landscape')
      
      // En-tête
      doc.setFontSize(20)
      doc.setTextColor(40, 40, 40)
      doc.text('LISTE DES SÉMINAIRISTES SENAFOI 25', 148, 20, { align: 'center' })
      
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
      
      // Tableau des données
      const tableData = seminairistes.value.map(seminairiste => [
        seminairiste.matricule_seminaire || '',
        `${seminairiste.prenom} ${seminairiste.nom}`,
        getNiveauLabel(seminairiste.niveau_seminaire),
        seminairiste.secretariat_regional || '',
        seminairiste.contact || '',
        getDortoirLabel(seminairiste.dortoir),
        getPaymentStatusLabel(seminairiste.statut_paiement),
        formatDate(seminairiste.created_at) || ''
      ])
      
      autoTable(doc, {
        head: [['Matricule', 'Nom Complet', 'Niveau', 'Secrétariat', 'Contact', 'Dortoir', 'Paiement', 'Date Inscription']],
        body: tableData,
        startY: 40,
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
        }
      })
      
      const filename = `liste_seminairistes_senafoi25_${new Date().toISOString().split('T')[0]}.pdf`
      doc.save(filename)
      
      showAlert('success', `Export PDF réussi ! ${seminairistes.value.length} séminairistes exportés.`)
      
    } catch (error) {
      console.error('Erreur lors de l\'export PDF:', error)
      showAlert('error', 'Erreur lors de l\'export PDF.')
    } finally {
      exporting.value = false
    }
  }
  
  const viewSeminairiste = async (seminairiste) => {
    try {
      const response = await fetch(`${API_BASE_URL}?action=view_seminairiste&id=${seminairiste.id_seminaire}`)
      const data = await response.json()
      
      if (data.success) {
        selectedSeminairiste.value = data.data
        showDetailModal.value = true
        document.body.style.overflow = 'hidden'
      } else {
        showAlert('error', 'Erreur lors du chargement des détails')
      }
    } catch (error) {
      console.error('Erreur viewSeminairiste:', error)
      showAlert('error', 'Erreur lors du chargement des détails')
    }
  }
  
  const editSeminairiste = (seminairiste) => {
    selectedSeminairiste.value = seminairiste
    editForm.value = {
      prenom: seminairiste.prenom,
      nom: seminairiste.nom,
      sexe: seminairiste.sexe,
      contact: seminairiste.contact,
      contact_parent: seminairiste.contact_parent,
      niveau_etude: seminairiste.niveau_etude,
      secretariat_regional: seminairiste.secretariat_regional,
      sous_comite: seminairiste.sous_comite,
      qualite: seminairiste.qualite,
      taille_tshirt: seminairiste.taille_tshirt,
      niveau_seminaire: seminairiste.niveau_seminaire,
      statut_inscription: seminairiste.statut_inscription
    }
    showEditModal.value = true
    document.body.style.overflow = 'hidden'
  }
  
  const downloadFicheSeminairiste = async (seminairiste) => {
    try {
      exporting.value = true
      showAlert('success', 'Génération de la fiche en cours...')
      
      const { jsPDF } = await import('jspdf')
      const doc = new jsPDF()
      
      // En-tête avec logo/titre
      doc.setFontSize(20)
      doc.setTextColor(34, 197, 94)
      doc.text('FICHE SÉMINAIRISTE SENAFOI 25', 105, 20, { align: 'center' })
      
      // Ligne de séparation
      doc.setDrawColor(34, 197, 94)
      doc.setLineWidth(0.5)
      doc.line(20, 25, 190, 25)
      
      // Informations personnelles
      doc.setFontSize(14)
      doc.setTextColor(40, 40, 40)
      doc.text('INFORMATIONS PERSONNELLES', 20, 40)
      
      doc.setFontSize(10)
      let yPos = 50
      
      const personalInfo = [
        ['Matricule:', seminairiste.matricule_seminaire || 'Non défini'],
        ['Nom complet:', `${seminairiste.prenom} ${seminairiste.nom}`],
        ['Sexe:', seminairiste.sexe === 'M' ? 'Masculin' : 'Féminin'],
        ['Contact:', seminairiste.contact || 'Non renseigné'],
        ['Contact parent:', seminairiste.contact_parent || 'Non renseigné'],
        ['Niveau d\'étude:', seminairiste.niveau_etude || 'Non renseigné']
      ]
      
      personalInfo.forEach(([label, value]) => {
        doc.setFont(undefined, 'bold')
        doc.text(label, 20, yPos)
        doc.setFont(undefined, 'normal')
        doc.text(value, 70, yPos)
        yPos += 8
      })
      
      // Informations séminaire
      yPos += 10
      doc.setFontSize(14)
      doc.setFont(undefined, 'bold')
      doc.text('INFORMATIONS SÉMINAIRE', 20, yPos)
      
      yPos += 10
      doc.setFontSize(10)
      
      const seminarInfo = [
        ['Niveau séminaire:', getNiveauLabel(seminairiste.niveau_seminaire)],
        ['Secrétariat régional:', seminairiste.secretariat_regional || 'Non renseigné'],
        ['Sous-comité:', seminairiste.sous_comite || 'Non renseigné'],
        ['Qualité:', seminairiste.qualite || 'Non renseigné'],
        ['Taille T-shirt:', seminairiste.taille_tshirt || 'Non renseigné']
      ]
      
      seminarInfo.forEach(([label, value]) => {
        doc.setFont(undefined, 'bold')
        doc.text(label, 20, yPos)
        doc.setFont(undefined, 'normal')
        doc.text(value, 70, yPos)
        yPos += 8
      })
      
      // Logistique
      yPos += 10
      doc.setFontSize(14)
      doc.setFont(undefined, 'bold')
      doc.text('LOGISTIQUE', 20, yPos)
      
      yPos += 10
      doc.setFontSize(10)
      
      const logisticInfo = [
        ['Dortoir:', getDortoirLabel(seminairiste.dortoir)],
        ['Car de transport:', getCarLabel(seminairiste.car_transport)]
      ]
      
      logisticInfo.forEach(([label, value]) => {
        doc.setFont(undefined, 'bold')
        doc.text(label, 20, yPos)
        doc.setFont(undefined, 'normal')
        doc.text(value, 70, yPos)
        yPos += 8
      })
      
      // Statuts
      yPos += 10
      doc.setFontSize(14)
      doc.setFont(undefined, 'bold')
      doc.text('STATUTS', 20, yPos)
      
      yPos += 10
      doc.setFontSize(10)
      
      const statusInfo = [
        ['Statut inscription:', getInscriptionStatusLabel(seminairiste.statut_inscription)],
        ['Statut paiement:', getPaymentStatusLabel(seminairiste.statut_paiement)],
        ['Référence paiement:', seminairiste.ref_paiement || 'Non renseigné'],
        ['Date inscription:', formatDate(seminairiste.created_at)]
      ]
      
      statusInfo.forEach(([label, value]) => {
        doc.setFont(undefined, 'bold')
        doc.text(label, 20, yPos)
        doc.setFont(undefined, 'normal')
        doc.text(value, 70, yPos)
        yPos += 8
      })
      
      // Pied de page
      doc.setFontSize(8)
      doc.setTextColor(100, 100, 100)
      doc.text(`Fiche générée le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}`, 105, 280, { align: 'center' })
      
      const filename = `fiche_${seminairiste.prenom}_${seminairiste.nom}_${seminairiste.matricule_seminaire}.pdf`
      doc.save(filename)
      
      showAlert('success', 'Fiche téléchargée avec succès !')
      
    } catch (error) {
      console.error('Erreur lors de la génération de la fiche:', error)
      showAlert('error', 'Erreur lors de la génération de la fiche')
    } finally {
      exporting.value = false
    }
  }
  
  const assignDortoir = (seminairiste) => {
    selectedSeminairiste.value = seminairiste
    dortoirForm.value = {
      dortoir: seminairiste.dortoir || '',
      car_transport: seminairiste.car_transport || ''
    }
    showDortoirModal.value = true
    document.body.style.overflow = 'hidden'
  }
  
  const closeDetailModal = () => {
    showDetailModal.value = false
    selectedSeminairiste.value = null
    document.body.style.overflow = 'auto'
  }
  
  const closeEditModal = () => {
    showEditModal.value = false
    selectedSeminairiste.value = null
    editForm.value = {}
    document.body.style.overflow = 'auto'
  }
  
  const closeDortoirModal = () => {
    showDortoirModal.value = false
    selectedSeminairiste.value = null
    dortoirForm.value = { dortoir: '', car_transport: '' }
    document.body.style.overflow = 'auto'
  }
  
  const saveEdit = async () => {
    try {
      submitting.value = true
      
      const response = await fetch(`${API_BASE_URL}?action=update_seminairiste&id=${selectedSeminairiste.value.id_seminaire}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(editForm.value)
      })
      
      const data = await response.json()
      
      if (data.success) {
        showAlert('success', 'Séminairiste modifié avec succès')
        closeEditModal()
        await loadSeminairistes()
      } else {
        showAlert('error', data.message || 'Erreur lors de la modification')
      }
    } catch (error) {
      console.error('Erreur saveEdit:', error)
      showAlert('error', 'Erreur lors de la modification')
    } finally {
      submitting.value = false
    }
  }
  
  const saveDortoirAssignment = async () => {
    try {
      submitting.value = true
      
      // Assigner le dortoir
      if (dortoirForm.value.dortoir) {
        const dortoirResponse = await fetch(`${API_BASE_URL}?action=assign_dortoir`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            id_seminaire: selectedSeminairiste.value.id_seminaire,
            dortoir: dortoirForm.value.dortoir
          })
        })
        
        const dortoirData = await dortoirResponse.json()
        if (!dortoirData.success) {
          throw new Error(dortoirData.message || 'Erreur lors de l\'assignation du dortoir')
        }
      }
      
      // Assigner le car
      if (dortoirForm.value.car_transport) {
        const carResponse = await fetch(`${API_BASE_URL}?action=assign_car`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            id_seminaire: selectedSeminairiste.value.id_seminaire,
            car_transport: dortoirForm.value.car_transport
          })
        })
        
        const carData = await carResponse.json()
        if (!carData.success) {
          throw new Error(carData.message || 'Erreur lors de l\'assignation du car')
        }
      }
      
      showAlert('success', 'Assignations effectuées avec succès')
      closeDortoirModal()
      await loadSeminairistes()
      
    } catch (error) {
      console.error('Erreur saveDortoirAssignment:', error)
      showAlert('error', error.message || 'Erreur lors des assignations')
    } finally {
      submitting.value = false
    }
  }
  
  const formatDate = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }
  
  const getNiveauLabel = (niveau) => {
    const labels = {
      '1AS': '1ère Année Secondaire',
      '2AS': '2ème Année Secondaire', 
      '3AS': '3ème Année Secondaire',
      '1AF': '1ère Année Formation',
      '2AF': '2ème Année Formation',
      '3AF': '3ème Année Formation',
      '1BS': '1ère Année Supérieure',
      '2BS': '2ème Année Supérieure',
      '3BS': '3ème Année Supérieure',
      '1BF': '1ère Année Formation Avancée',
      '2BF': '2ème Année Formation Avancée',
      '3BF': '3ème Année Formation Avancée',
      '4': 'Niveau 4',
      'TEST_ENTREE': 'Test d\'entrée requis'
    }
    return labels[niveau] || niveau
  }
  
  const getNiveauBadgeClass = (niveau) => {
    const classes = {
      '1AS': 'bg-blue-100 text-blue-800',
      '2AS': 'bg-blue-100 text-blue-800',
      '3AS': 'bg-blue-100 text-blue-800',
      '1AF': 'bg-green-100 text-green-800',
      '2AF': 'bg-green-100 text-green-800',
      '3AF': 'bg-green-100 text-green-800',
      '1BS': 'bg-purple-100 text-purple-800',
      '2BS': 'bg-purple-100 text-purple-800',
      '3BS': 'bg-purple-100 text-purple-800',
      '1BF': 'bg-orange-100 text-orange-800',
      '2BF': 'bg-orange-100 text-orange-800',
      '3BF': 'bg-orange-100 text-orange-800',
      '4': 'bg-indigo-100 text-indigo-800',
      'TEST_ENTREE': 'bg-gray-100 text-gray-800'
    }
    return classes[niveau] || 'bg-gray-100 text-gray-800'
  }
  
  const getDortoirLabel = (dortoir) => {
    const labels = {
      'DORTOIR_A': 'Dortoir A - Hommes',
    'DORTOIR_B': 'Dortoir B - Hommes',
    'DORTOIR_C': 'Dortoir C - Hommes',
    'DORTOIR_D': 'Dortoir D - Hommes',
    'DORTOIR_E': 'Dortoir E - Hommes',
    'DORTOIR_F': 'Dortoir F - Hommes',
    'DORTOIR_G': 'Dortoir G - Hommes',
    'DORTOIR_H': 'Dortoir H - Hommes',
    'DORTOIR_I': 'Dortoir I - Femmes',
    'DORTOIR_J': 'Dortoir J - Femmes',
    'DORTOIR_K': 'Dortoir K - Femmes',
    'DORTOIR_L': 'Dortoir L - Femmes',
    'DORTOIR_M': 'Dortoir M - Femmes',
    'DORTOIR_N': 'Dortoir N - Femmes',
    'DORTOIR_O': 'Dortoir O - Femmes',
    'DORTOIR_P': 'Dortoir P - Femmes',
    'DORTOIR_Q': 'Dortoir Q - Femmes',
    'DORTOIR_R': 'Dortoir R - Femmes',
    'DORTOIR_S': 'Dortoir S - Femmes',
    'DORTOIR_T': 'Dortoir T - Femmes',
    'DORTOIR_U': 'Dortoir U - Femmes',
    'DORTOIR_V': 'Dortoir V - Femmes',
    'DORTOIR_W': 'Dortoir W - Femmes',
    'DORTOIR_X': 'Dortoir X - Femmes',
    'DORTOIR_Y': 'Dortoir Y - Femmes',
    'DORTOIR_Z': 'Dortoir Z - Femmes',
    'EXTERNE': 'Externe'
    }
    return labels[dortoir] || (dortoir || 'Non assigné')
  }
  
  const getCarLabel = (car) => {
    const labels = {
      'CAR_1': 'Car 1 - Abidjan Nord',
      'CAR_2': 'Car 2 - Abidjan Sud',
      'CAR_3': 'Car 3 - Intérieur',
      'CAR_4': 'Car 4 - Bouaké',
      'CAR_5': 'Car 5 - San Pedro'
    }
    return labels[car] || (car || 'Non assigné')
  }
  
  const getPaymentStatusLabel = (status) => {
    const labels = {
      'PAYE': 'Payé',
      'EN_ATTENTE': 'En attente',
      'ECHEC': 'Échec'
    }
    return labels[status] || 'Non payé'
  }
  
  const getInscriptionStatusLabel = (status) => {
    const labels = {
      'VALIDEE': 'Validée',
      'EN_COURS': 'En cours',
      'REJETEE': 'Rejetée',
      'ANNULEE': 'Annulée'
    }
    return labels[status] || status
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
  
  // Lifecycle hooks
  onMounted(() => {
    loadData()
  })
  
  onBeforeUnmount(() => {
    document.body.style.overflow = 'auto'
    if (searchTimeout.value) {
      clearTimeout(searchTimeout.value)
    }
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
  input, select {
    padding: 8px
  }
  </style>
  