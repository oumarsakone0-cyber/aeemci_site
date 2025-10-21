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
            <button @click="goBack" class="p-2 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div>
              <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 lg:w-8 lg:h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                </svg>
                Bacheliers AEEMCI
              </h1>
              <p class="text-gray-600 text-sm lg:text-base">Gestion des informations des bacheliers</p>
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
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Total Bacheliers</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.total_bacheliers || 0 }}</p>
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
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Série BAC A / BAC C / BAC D</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.serie_A || 0 }} / {{ stats.serie_C || 0 }} / {{ stats.serie_D || 0 }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-emerald-100 rounded-full">
                  <svg class="w-5 h-5 lg:w-6 lg:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-teal-500 overflow-hidden">
            <div class="p-4 lg:p-6">
              <div class="flex items-center justify-between" style="padding: 15px">
                <div>
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Série BAC Compta & Gestion</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.serie_Comptable || 0 }} / {{ stats.serie_Assistanat_de_gestion || 0 }}</p>
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
                  <p class="text-xs lg:text-sm font-medium text-gray-600">Série Bac Techniques</p>
                  <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.serie_Technique || 0 }}</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Série BAC</label>
                <div class="relative">
                  <div
                    @click="toggleSerieBacDropdown"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent cursor-pointer bg-white flex items-center justify-between text-sm"
                    :class="{ 'ring-2 ring-green-500 border-transparent': showSerieBacDropdown }"
                  >
                    <span v-if="selectedSerieBacText" class="text-gray-900 truncate">{{ selectedSerieBacText }}</span>
                    <span v-else class="text-gray-500">Toutes les séries</span>
                    <div class="flex items-center space-x-1 flex-shrink-0">
                      <button
                        v-if="filters.serie_bac"
                        @click.stop="clearSerieBacSelection"
                        class="text-gray-400 hover:text-gray-600 p-1"
                        type="button"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                      <svg
                        class="w-4 h-4 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': showSerieBacDropdown }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
  
                  <div
                    v-if="showSerieBacDropdown"
                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden"
                  >
                    <div class="p-2 border-b border-gray-200">
                      <input
                        ref="serieBacSearchInput"
                        v-model="serieBacSearchTerm"
                        type="text"
                        placeholder="Rechercher une série..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @click.stop
                      >
                    </div>
  
                    <div class="max-h-48 overflow-y-auto">
                      <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-gray-500 text-sm" @click="selectSerieBac('', 'Toutes les séries')">
                        Toutes les séries
                      </div>
  
                      <div
                        v-for="serie in filteredSerieBacsForDropdown"
                        :key="serie"
                        @click="selectSerieBac(serie, serie)"
                        class="px-4 py-2 hover:bg-green-50 cursor-pointer flex items-center justify-between text-sm"
                        :class="{ 'bg-green-100 text-green-800': filters.serie_bac === serie }"
                      >
                        <span class="truncate">{{ serie }}</span>
                        <svg
                          v-if="filters.serie_bac === serie"
                          class="w-4 h-4 text-green-600 flex-shrink-0"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                      </div>
  
                      <div v-if="filteredSerieBacsForDropdown.length === 0" class="px-4 py-2 text-gray-500 text-center text-sm">
                        Aucune série trouvée
                      </div>
                    </div>
                  </div>
                </div>
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filière Souhaitée</label>
                <select
                  v-model="filters.filiere_souhaitee"
                  @change="applyFilters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
                  <option value="">Toutes les filières</option>
                  <option value="Informatique">Informatique</option>
                  <option value="Medecine">Médecine</option>
                  <option value="Droit">Droit</option>
                  <option value="Gestion">Gestion</option>
                  <option value="Sciences Sociales">Sciences Sociales</option>
                  <option value="Lettres Modernes">Lettres Modernes</option>
                  <option value="Mathematiques">Mathématiques</option>
                  <option value="Physique-Chimie">Physique-Chimie</option>
                  <option value="Biologie">Biologie</option>
                  <option value="Autres">Autres</option>
                </select>
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                <input
                  v-model="filters.ville"
                  @input="debounceSearch"
                  type="text"
                  placeholder="Ville de résidence..."
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                >
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
  
        <!-- Tableau des bacheliers -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="padding: 10px; margin-top: 10px">
          <div class="px-4 lg:px-6 py-4 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-2 lg:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Liste des Bacheliers</h3>
            <div class="text-sm text-gray-500">
              Affichage {{ pagination.from || 0 }} à {{ pagination.to || 0 }} sur {{ pagination.total || 0 }} bacheliers
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
                      <span>Bachelier</span>
                    </div>
                  </th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Point BAC</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Série BAC</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule BAC</th>
                  
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Filière Souhaitée</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Ville</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                  <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collante</th>
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
  
                <tr v-else-if="bacheliers.length === 0">
                  <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center space-y-2">
                      <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                      <span>Aucun bachelier trouvé</span>
                    </div>
                  </td>
                </tr>
  
                <tr v-else v-for="bachelier in bacheliers" :key="bachelier.id" class="hover:bg-gray-50 transition-colors duration-150" style="padding: 5px">
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8 lg:h-10 lg:w-10 relative">
                        <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-green-100 flex items-center justify-center">
                          <svg class="w-4 h-4 lg:w-5 lg:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                        </div>
                      </div>
                      <div class="ml-3 lg:ml-4">
                        <div class="text-xs lg:text-sm font-medium text-gray-900">{{ bachelier.prenom }} {{ bachelier.nom }}</div>
                        <div class="text-xs text-gray-500 lg:hidden">{{ bachelier.contact }}</div>
                      </div>
                    </div>
                  </td>

                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span style="padding: 5px" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 font-mono">
                      {{ bachelier.nombre_points_bac }}
                    </span>
                  </td>

                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                      :class="getSerieBacBadgeClass(bachelier.serie_bac)"
                    >
                      {{ bachelier.serie_bac }}
                    </span>
                  </td>
  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 font-mono">
                      {{ bachelier.matricule_bac }}
                    </span>
                  </td>
  
                  
  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900 hidden lg:table-cell">
                    {{ bachelier.filiere_souhaitee }}
                  </td>
  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900 hidden lg:table-cell">
                    {{ bachelier.ville }}
                  </td>
  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-xs lg:text-sm text-gray-900">
                    {{ bachelier.contact }}
                  </td>
  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap" style="padding: 5px">
                    <div class="space-y-1">
                      <span v-if="bachelier.collante_bac_url" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Disponible
                      </span>
                      <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Non disponible
                      </span>
                    </div>
                  </td>
  
                  <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-1 lg:space-x-2">
                      <button @click="viewBachelier(bachelier)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" title="Voir détails">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
  
                      <button @click="editBachelier(bachelier)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Modifier">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </button>
  
                      <button v-if="bachelier.collante_bac_url" @click="viewCollante(bachelier)" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition-colors" title="Voir/Imprimer Collante">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
  
      <!-- Modal Voir Bachelier -->
      <div v-if="showViewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" >
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" style="padding: 10px">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              Détails du Bachelier
            </h3>
          </div>
  
          <div v-if="selectedBachelier" class="p-6 space-y-6">
            <!-- Informations principales -->
            <div class="flex items-start space-x-6">
              <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-green-100 flex items-center justify-center border-4 border-green-200">
                  <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
              </div>
              <div class="flex-1">
                <h4 class="text-xl font-bold text-gray-900">{{ selectedBachelier.prenom }} {{ selectedBachelier.nom }}</h4>
                <p class="text-green-600 font-medium">{{ selectedBachelier.matricule_bac }}</p>
                <p class="text-gray-600">{{ selectedBachelier.serie_bac }} - {{ selectedBachelier.filiere_souhaitee }}</p>
                <div class="mt-2">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" :class="getCollanteStatusBadgeClass(selectedBachelier.collante_bac_url)">
                    {{ getCollanteStatusLabel(selectedBachelier.collante_bac_url) }}
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
                  <p class="text-gray-900">{{ formatDate(selectedBachelier.date_naissance) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Ville de résidence</label>
                  <p class="text-gray-900">{{ selectedBachelier.ville }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Contact</label>
                  <p class="text-gray-900">{{ selectedBachelier.contact }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Email</label>
                  <p class="text-gray-900">{{ selectedBachelier.email }}</p>
                </div>
              </div>
  
              <div class="space-y-4">
                <h5 class="font-semibold text-gray-800 border-b pb-2">Informations BAC</h5>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Série BAC</label>
                  <p class="text-gray-900">{{ selectedBachelier.serie_bac }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Nombre de points BAC</label>
                  <p class="text-gray-900">{{ selectedBachelier.nombre_points_bac }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Filière Souhaitée</label>
                  <p class="text-gray-900">{{ selectedBachelier.filiere_souhaitee }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Moyenne Classe</label>
                  <p class="text-gray-900">{{ selectedBachelier.moyenne_classe }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Centre d'examen</label>
                  <p class="text-gray-900">{{ selectedBachelier.centre_examen }}</p>
                </div>
              </div>
            </div>
  
            <div class="flex justify-end space-x-3 pt-4">
              <button style="background: green" v-if="selectedBachelier.collante_bac_url" @click="viewCollante(selectedBachelier)" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>Voir Collante</span>
              </button>
              <button @click="showViewModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal Modifier Bachelier -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="padding: 10px">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              Modifier Bachelier
            </h3>
          </div>
  
          <form @submit.prevent="updateBachelier()" class="p-6 space-y-6">
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
                    v-model="editForm.date_naissance"
                    type="date"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                  <input
                    v-model="editForm.ville"
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
                  <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                  <input
                    v-model="editForm.email"
                    type="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
              </div>
            </div>
  
            <!-- Informations BAC -->
            <div class="space-y-4">
              <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Informations BAC</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Matricule BAC *</label>
                  <input
                    v-model="editForm.matricule_bac"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Série BAC *</label>
                  <select
                    v-model="editForm.serie_bac"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                    <option value="">Sélectionner</option>
                    <option value="A">A</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="Autre">Autre</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de points BAC *</label>
                  <input
                    v-model="editForm.nombre_points_bac"
                    type="number"
                    step="0.01"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Filière Souhaitée *</label>
                  <select
                    v-model="editForm.filiere_souhaitee"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                    <option value="">Sélectionner</option>
                    <option value="Informatique">Informatique</option>
                    <option value="Medecine">Médecine</option>
                    <option value="Droit">Droit</option>
                    <option value="Gestion">Gestion</option>
                    <option value="Sciences Sociales">Sciences Sociales</option>
                    <option value="Lettres Modernes">Lettres Modernes</option>
                    <option value="Mathematiques">Mathématiques</option>
                    <option value="Physique-Chimie">Physique-Chimie</option>
                    <option value="Biologie">Biologie</option>
                    <option value="Autres">Autres</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Moyenne Classe</label>
                  <input
                    v-model="editForm.moyenne_classe"
                    type="number"
                    step="0.01"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Centre d'examen</label>
                  <input
                    v-model="editForm.centre_examen"
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
  const API_BASE_URL = 'https://sogetrag.com/api/bachelier-api.php' // REMPLACEZ PAR VOTRE VRAIE URL API
  const bacheliers = ref([])
  const serieBacs = ref(['A', 'C', 'D', 'E', 'F', 'G', 'Autre'])
  
  const loading = ref(false)
  const submitting = ref(false)
  const exporting = ref(false)
  const showViewModal = ref(false)
  const showEditModal = ref(false)
  const selectedBachelier = ref(null)
  const searchTimeout = ref(null)
  
  const stats = ref({
    total_bacheliers: 0,
    serie_A: 0,
    serie_C: 0,
    serie_D: 0,
    serie_Comptable: 0,
    serie_Assistanat_de_gestion: 0,
    serie_Technique: 0,
    // Ajoutez d'autres séries si nécessaire
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
    serie_bac: '',
    filiere_souhaitee: '',
    ville: ''
  })
  
  const editForm = ref({
    id: null, // Ajout de l'ID pour la mise à jour
    nom: '',
    prenom: '',
    date_naissance: '',
    serie_bac: '',
    nombre_points_bac: '',
    filiere_souhaitee: '',
    moyenne_classe: '',
    centre_examen: '',
    matricule_bac: '',
    ville: '',
    contact: '',
    email: '',
    collante_bac_url: null, // Ajout pour la mise à jour si nécessaire
    collante_bac_public_id: null, // Ajout pour la mise à jour si nécessaire
  })
  
  const alert = ref({
    show: false,
    type: 'success',
    message: ''
  })
  
  const showSerieBacDropdown = ref(false)
  const serieBacSearchTerm = ref('')
  
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
  
  const selectedSerieBacText = computed(() => {
    return filters.value.serie_bac || ''
  })
  
  const filteredSerieBacsForDropdown = computed(() => {
    if (!serieBacSearchTerm.value) {
      return serieBacs.value
    }
    return serieBacs.value.filter(serie =>
      serie.toLowerCase().includes(serieBacSearchTerm.value.toLowerCase())
    )
  })
  
  // Méthodes
  const goBack = () => {
    window.history.back()
  }
  
  const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
      showSerieBacDropdown.value = false
    }
  }
  
  const viewCollante = (bachelier) => {
    if (bachelier.collante_bac_url) {
      window.open(bachelier.collante_bac_url, '_blank')
      showAlert('success', 'Ouverture de la collante dans un nouvel onglet...')
    } else {
      showAlert('error', 'URL de la collante non disponible.')
    }
  }
  
  const changePage = async (page) => {
    if (page < 1 || page > pagination.value.last_page) return
    pagination.value.current_page = page
    await loadBacheliers()
  }
  
  const changePerPage = async () => {
    pagination.value.current_page = 1
    await loadBacheliers()
  }
  
  const applyFilters = async () => {
    pagination.value.current_page = 1
    await loadBacheliers()
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
      serie_bac: '',
      filiere_souhaitee: '',
      ville: ''
    }
    pagination.value.current_page = 1
    loadBacheliers()
  }
  
  const loadData = async () => {
    loading.value = true
    try {
      await Promise.all([
        loadBacheliers(),
        loadStats()
      ])
    } catch (error) {
      console.error('Erreur lors du chargement:', error)
      showAlert('error', 'Erreur lors du chargement des données')
    } finally {
      loading.value = false
    }
  }
  
  const loadBacheliers = async () => {
    try {
      let url = `${API_BASE_URL}?action=bacheliers&page=${pagination.value.current_page}&per_page=${pagination.value.per_page}`
  
      if (filters.value.search) {
        url += `&search=${encodeURIComponent(filters.value.search)}`
      }
      if (filters.value.serie_bac) {
        url += `&serie_bac=${encodeURIComponent(filters.value.serie_bac)}`
      }
      if (filters.value.filiere_souhaitee) {
        url += `&filiere_souhaitee=${encodeURIComponent(filters.value.filiere_souhaitee)}`
      }
      if (filters.value.ville) {
        url += `&ville=${encodeURIComponent(filters.value.ville)}`
      }
  
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
  
      if (data.success) {
        bacheliers.value = data.data || [];
        if (data.pagination) {
          pagination.value = {
            ...pagination.value,
            ...data.pagination
          };
        }
      } else {
        throw new Error(data.message || 'Erreur lors du chargement des bacheliers');
      }
    } catch (error) {
      console.error('Erreur loadBacheliers:', error);
      showAlert('error', `Erreur lors du chargement des bacheliers: ${error.message}`);
    }
  }
  
  const loadStats = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}?action=stats`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
  
      if (data.success) {
        stats.value = {
          total_bacheliers: data.data.total_bacheliers || 0,
          serie_A: data.data.serie_A || 0,
          serie_C: data.data.serie_C || 0,
          serie_D: data.data.serie_D || 0,
          serie_Assistanat_de_gestion: data.data.serie_Assistanat_de_gestion || 0,
          serie_Comptable: data.data.serie_Comptable || 0,
          serie_Technique: data.data.serie_Technique || 0,
          // Assurez-vous que toutes les séries sont initialisées à 0 si elles ne sont pas dans la réponse
        };
      } else {
        throw new Error(data.message || 'Erreur lors du chargement des statistiques');
      }
    } catch (error) {
      console.error('Erreur loadStats:', error);
      showAlert('error', `Erreur lors du chargement des statistiques: ${error.message}`);
    }
  }
  
  const exportExcel = async () => {
    try {
      exporting.value = true
      showAlert('success', 'Génération du fichier Excel en cours...')
  
      // Pour l'export, vous pourriez vouloir charger TOUTES les données, pas seulement la page actuelle
      // Ou bien, votre API pourrait avoir un endpoint spécifique pour l'export complet
      // Pour l'instant, cela exportera uniquement les données actuellement chargées dans `bacheliers.value`
      if (bacheliers.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des bacheliers.')
        return
      }
  
      const data = bacheliers.value.map(bachelier => ({
        'ID': bachelier.id,
        'Nom': bachelier.nom,
        'Prénom': bachelier.prenom,
        'Date de Naissance': formatDate(bachelier.date_naissance),
        'Série BAC': bachelier.serie_bac,
        'Nombre de Points BAC': bachelier.nombre_points_bac,
        'Filière Souhaitée': bachelier.filiere_souhaitee,
        'Moyenne Classe': bachelier.moyenne_classe,
        'Centre Examen': bachelier.centre_examen,
        'Matricule BAC': bachelier.matricule_bac,
        'Ville': bachelier.ville,
        'Contact': bachelier.contact,
        'Email': bachelier.email,
        'URL Collante BAC': bachelier.collante_bac_url || '',
        'Date Inscription': formatDate(bachelier.created_at),
        'Date Modification': formatDate(bachelier.updated_at)
      }))
  
      const XLSX = await import('xlsx')
      const workbook = XLSX.utils.book_new()
      const worksheet = XLSX.utils.json_to_sheet(data)
  
      const colWidths = [
        { wch: 5 }, { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 10 },
        { wch: 20 }, { wch: 25 }, { wch: 15 }, { wch: 20 }, { wch: 15 },
        { wch: 15 }, { wch: 15 }, { wch: 25 }, { wch: 40 }, { wch: 15 }, { wch: 15 }
      ]
      worksheet['!cols'] = colWidths
  
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Bacheliers AEEMCI')
  
      const today = new Date().toISOString().split('T')[0]
      const filename = `bacheliers_aeemci_${today}.xlsx`
      XLSX.writeFile(workbook, filename)
  
      showAlert('success', `Export Excel réussi ! ${data.length} bacheliers exportés.`)
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
  
      // Pour l'export, vous pourriez vouloir charger TOUTES les données, pas seulement la page actuelle
      // Ou bien, votre API pourrait avoir un endpoint spécifique pour l'export complet
      // Pour l'instant, cela exportera uniquement les données actuellement chargées dans `bacheliers.value`
      if (bacheliers.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des bacheliers.')
        return
      }
  
      const data = bacheliers.value
      const totalBacheliers = data.length
      const seriesUniques = [...new Set(data.map(bachelier => bachelier.serie_bac))].filter(Boolean)
  
      const { jsPDF } = await import('jspdf')
      const autoTable = (await import('jspdf-autotable')).default
  
      // Créer le PDF en format paysage
      const doc = new jsPDF('landscape')
  
      // En-tête
      doc.setFontSize(20)
      doc.setTextColor(40, 40, 40)
      doc.text('LISTE DES BACHELIERS AEEMCI', 148, 20, { align: 'center' })
  
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
      doc.text(`Total des bacheliers: ${totalBacheliers}`, 20, 60)
      doc.text(`Séries représentées: ${seriesUniques.join(', ')}`, 20, 68)
      doc.text(`Bacheliers avec collante disponible: ${data.filter(b => b.collante_bac_url).length}`, 20, 76)
  
      // Tableau des données
      const tableData = data.map(bachelier => [
        bachelier.matricule_bac || '',
        `${bachelier.prenom} ${bachelier.nom}`,
        bachelier.serie_bac || '',
        bachelier.filiere_souhaitee || '',
        bachelier.ville || '',
        bachelier.contact || '',
        getCollanteStatusLabel(bachelier.collante_bac_url),
        formatDate(bachelier.created_at) || ''
      ])
  
      autoTable(doc, {
        head: [['Matricule BAC', 'Nom Complet', 'Série BAC', 'Filière Souhaitée', 'Ville', 'Contact', 'Collante', 'Date Inscription']],
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
          2: { cellWidth: 20 },
          3: { cellWidth: 40 },
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
  
      const filename = `liste_bacheliers_aeemci_${new Date().toISOString().split('T')[0]}.pdf`
      doc.save(filename)
  
      showAlert('success', `Export PDF réussi ! ${totalBacheliers} bacheliers exportés.`)
    } catch (error) {
      console.error('Erreur lors de l\'export PDF:', error)
      showAlert('error', 'Erreur lors de l\'export PDF.')
    } finally {
      exporting.value = false
    }
  }
  
  const updateBachelier = async () => {
    if (!editForm.value.prenom || !editForm.value.nom || !editForm.value.contact || !editForm.value.matricule_bac || !editForm.value.serie_bac || !editForm.value.filiere_souhaitee) {
      showAlert('error', 'Veuillez remplir tous les champs obligatoires')
      return
    }
  
    submitting.value = true
    try {
      const response = await fetch(`${API_BASE_URL}?action=update_bachelier&id=${editForm.value.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(editForm.value)
      });
  
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
  
      if (data.success) {
        showAlert('success', 'Bachelier modifié avec succès')
        closeEditModal()
        await loadData() // Recharger les données après modification
      } else {
        throw new Error(data.message || 'Erreur lors de la modification');
      }
    } catch (error) {
      console.error('Erreur updateBachelier:', error)
      showAlert('error', error.message || 'Erreur lors de la modification du bachelier')
    } finally {
      submitting.value = false
    }
  }
  
  const viewBachelier = (bachelier) => {
    selectedBachelier.value = bachelier
    showViewModal.value = true
  }
  
  const editBachelier = (bachelier) => {
    selectedBachelier.value = bachelier
    // Assurez-vous que tous les champs de editForm sont mappés depuis bachelier
    editForm.value = {
      id: bachelier.id, // Très important pour la requête PUT
      nom: bachelier.nom,
      prenom: bachelier.prenom,
      date_naissance: bachelier.date_naissance,
      serie_bac: bachelier.serie_bac,
      nombre_points_bac: bachelier.nombre_points_bac,
      filiere_souhaitee: bachelier.filiere_souhaitee,
      moyenne_classe: bachelier.moyenne_classe,
      centre_examen: bachelier.centre_examen,
      matricule_bac: bachelier.matricule_bac,
      ville: bachelier.ville,
      contact: bachelier.contact,
      email: bachelier.email,
      collante_bac_url: bachelier.collante_bac_url,
      collante_bac_public_id: bachelier.collante_bac_public_id,
    }
    showEditModal.value = true
  }
  
  const closeEditModal = () => {
    showEditModal.value = false
    selectedBachelier.value = null
    // Réinitialiser editForm
    editForm.value = {
      id: null,
      nom: '',
      prenom: '',
      date_naissance: '',
      serie_bac: '',
      nombre_points_bac: '',
      filiere_souhaitee: '',
      moyenne_classe: '',
      centre_examen: '',
      matricule_bac: '',
      ville: '',
      contact: '',
      email: '',
      collante_bac_url: null,
      collante_bac_public_id: null,
    }
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
  
  const getSerieBacBadgeClass = (serie) => {
    const classes = {
      'A': 'bg-purple-100 text-purple-800',
      'C': 'bg-blue-100 text-blue-800',
      'D': 'bg-green-100 text-green-800',
      'E': 'bg-red-100 text-red-800',
      'F': 'bg-orange-100 text-orange-800',
      'G': 'bg-teal-100 text-teal-800',
      'Autre': 'bg-gray-100 text-gray-800'
    }
    return classes[serie] || 'bg-gray-100 text-gray-800'
  }
  
  const getCollanteStatusLabel = (url) => {
    return url ? 'Disponible' : 'Non disponible'
  }
  
  const getCollanteStatusBadgeClass = (url) => {
    return url ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'
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
  
  const toggleSerieBacDropdown = () => {
    showSerieBacDropdown.value = !showSerieBacDropdown.value
    if (showSerieBacDropdown.value) {
      serieBacSearchTerm.value = ''
    }
  }
  
  const selectSerieBac = (value, text) => {
    filters.value.serie_bac = value
    showSerieBacDropdown.value = false
    serieBacSearchTerm.value = ''
    applyFilters()
  }
  
  const clearSerieBacSelection = () => {
    filters.value.serie_bac = ''
    serieBacSearchTerm.value = ''
    applyFilters()
  }
  
  // Lifecycle hooks
  onMounted(() => {
    loadData()
    document.addEventListener('click', handleClickOutside)
  })
  
  onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
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
  