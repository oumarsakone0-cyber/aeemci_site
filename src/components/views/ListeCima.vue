<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 relative overflow-hidden">
    <!-- Animations de fond -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
      <div class="absolute top-40 left-40 w-80 h-80 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 p-4 lg:p-6" style="max-width: 1600px; margin: 0 auto;">
      <!-- Header -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="flex items-center space-x-3 lg:space-x-4">
          <button @click="goBack" class="p-2 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
          <div>
            <h1 class="text-xl lg:text-3xl font-bold text-gray-800 flex items-center">
              <svg class="w-6 h-6 lg:w-8 lg:h-8 text-green-600 mr-2 lg:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              CIMA {{ new Date().getFullYear() }}
            </h1>
            <p class="text-gray-600 text-xs lg:text-base">Assemblée Générale - Gestion des inscriptions</p>
          </div>
        </div>
        
        <div class="flex flex-wrap items-center justify-end gap-2 lg:gap-3">
          <button style="color: black" @click="exportExcel()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 lg:px-4 lg:py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 text-sm lg:text-base">
            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Excel</span>
          </button>
          <button style="color: black" @click="exportPDF()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 lg:px-4 lg:py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 text-sm lg:text-base">
            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <span>PDF</span>
          </button>
          <button style="color: black" @click="downloadSelectedBadges()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 lg:px-4 lg:py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 text-sm lg:text-base" :disabled="exporting || selectedRegistrations.length === 0">
            <svg v-if="exporting" class="animate-spin w-4 h-4 lg:w-5 lg:h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
            <svg v-else class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            <span>{{ exporting ? 'Génération...' : `Badges Sélectionnés (${selectedRegistrations.length})` }}</span>
          </button>
          <button style="color: black" @click="downloadAllBadges()" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 lg:px-4 lg:py-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 text-sm lg:text-base" :disabled="exporting">
            <svg v-if="exporting" class="animate-spin w-4 h-4 lg:w-5 lg:h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
            <svg v-else class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            <span>{{ exporting ? 'Génération...' : 'Tous les Badges' }}</span>
          </button>
        </div>
      </div>

      <!-- Statistiques Globales -->
      <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 overflow-hidden transform hover:scale-105 transition-transform">
          <div class="p-4 lg:p-6">
            <div class="flex items-center justify-between" style="padding: 10px;">
              <div>
                <p class="text-xs lg:text-sm font-medium text-gray-600">Total Inscriptions</p>
                <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.total_inscriptions || 0 }}</p>
              </div>
              <div class="p-2 lg:p-3 bg-green-100 rounded-full">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 overflow-hidden transform hover:scale-105 transition-transform">
          <div class="p-4 lg:p-6">
            <div class="flex items-center justify-between" style="padding: 10px;">
              <div>
                <p class="text-xs lg:text-sm font-medium text-gray-600">Paiements OK</p>
                <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.paid_count }}</p>
              </div>
              <div class="p-2 lg:p-3 bg-blue-100 rounded-full">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-purple-500 overflow-hidden transform hover:scale-105 transition-transform">
          <div class="p-4 lg:p-6">
            <div class="flex items-center justify-between" style="padding: 10px;">
              <div>
                <p class="text-xs lg:text-sm font-medium text-gray-600">Montant Collecté</p>
                <p class="text-lg lg:text-xl font-bold text-gray-900">{{ formatXOF(stats.total_amount) }}</p>
              </div>
              <div class="p-2 lg:p-3 bg-purple-100 rounded-full">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-teal-500 overflow-hidden transform hover:scale-105 transition-transform">
          <div class="p-4 lg:p-6">
            <div class="flex items-center justify-between" style="padding: 10px;">
              <div>
                <p class="text-xs lg:text-sm font-medium text-gray-600">Hommes / Femmes</p>
                <p class="text-xl lg:text-2xl font-bold text-gray-900">{{ stats.male_count }} / {{ stats.female_count }}</p>
              </div>
              <div class="p-2 lg:p-3 bg-teal-100 rounded-full">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- NOUVEAU: Statistique de présence -->
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-orange-500 overflow-hidden transform hover:scale-105 transition-transform">
          <div class="p-4 lg:p-6">
            <div class="flex items-center justify-between" style="padding: 10px;">
              <div>
                <p class="text-xs lg:text-sm font-medium text-gray-600">Présents</p>
                <p class="text-xl lg:text-2xl font-bold text-orange-600">{{ stats.present_count || 0 }}</p>
              </div>
              <div class="p-2 lg:p-3 bg-orange-100 rounded-full">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistiques CIMA vs AG -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Stats CIMA -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="margin-top: 15px">
          <div class="px-6 py-4 bg-gradient-to-r from-green-100 to-emerald-100 border-b" style="padding: 8px">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
              <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              CIMA (Membre CIMA)
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <div class="flex justify-between items-center" style="padding: 10px;">
              <span class="text-gray-600">Total inscriptions CIMA:</span>
              <span class="text-2xl font-bold text-green-600">{{ stats.cima_oui_count || 0 }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t" style="padding: 10px;">
              <div class="text-center">
                <div class="text-blue-600 text-xl font-bold">{{ stats.cima_oui_male || 0 }}</div>
                <div class="text-sm text-gray-500">♂ Frères</div>
              </div>
              <div class="text-center">
                <div class="text-pink-600 text-xl font-bold">{{ stats.cima_oui_female || 0 }}</div>
                <div class="text-sm text-gray-500">♀ Soeurs</div>
              </div>
            </div>
            <div class="pt-4 border-t" style="padding: 10px;">
              <div class="text-sm text-gray-600">Montant collecté</div>
              <div class="text-lg font-bold text-purple-600">{{ formatXOF(stats.cima_oui_amount) }}</div>
            </div>
          </div>
        </div>

        <!-- Stats AG -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="margin-top: 15px">
          <div class="px-6 py-4 bg-gradient-to-r from-orange-100 to-amber-100 border-b" style="padding: 8px">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
              <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              AG (Assemblée Générale)
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <div class="flex justify-between items-center" style="padding: 10px;">
              <span class="text-gray-600">Total inscriptions AG:</span>
              <span class="text-2xl font-bold text-orange-600">{{ stats.cima_non_count || 0 }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
              <div class="text-center">
                <div class="text-blue-600 text-xl font-bold" style="padding: 10px;">{{ stats.cima_non_male || 0 }}</div>
                <div class="text-sm text-gray-500">♂ Frères</div>
              </div>
              <div class="text-center">
                <div class="text-pink-600 text-xl font-bold">{{ stats.cima_non_female || 0 }}</div>
                <div class="text-sm text-gray-500">♀ Soeurs</div>
              </div>
            </div>
            <div class="pt-4 border-t" style="padding: 10px;">
              <div class="text-sm text-gray-600">Montant collecté</div>
              <div class="text-lg font-bold text-purple-600">{{ formatXOF(stats.cima_non_amount) }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 lg:mb-8" style="margin-top: 20px;">
        <div class="p-4 lg:p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-4" style="padding: 10px;">
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
              <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
              <select
                v-model="filters.cima"
                @change="applyFilters"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
              >
                <option value="">Tous</option>
                <option value="oui">CIMA</option>
                <option value="non">AG</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Ville/Commune</label>
              <select
                v-model="filters.ville"
                @change="applyFilters"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
              >
                <option value="">Toutes</option>
                <option v-for="ville in villes" :key="ville" :value="ville">{{ ville }}</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Statut Paiement</label>
              <select
                v-model="filters.payment_status"
                @change="applyFilters"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
              >
                <option value="">Tous</option>
                <option value="paye">Payé</option>
                <option value="pending">En attente</option>
                <option value="echoue">Échoué</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
              <select
                v-model="filters.sexe"
                @change="applyFilters"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
              >
                <option value="">Tous</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Par page</label>
              <select v-model="pagination.per_page" @change="changePerPage" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
              </select>
            </div>
          </div>
          
          <div class="flex justify-end">
            <button @click="resetFilters" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center space-x-2 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              <span>Réinitialiser</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Tableau -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="padding: 10px;">
        <div class="px-4 lg:px-6 py-4 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-2 lg:space-y-0">
          <h3 class="text-lg font-semibold text-gray-800">Liste des Inscriptions</h3>
          <div class="text-sm text-gray-500">
            Affichage {{ pagination.from || 0 }} à {{ pagination.to || 0 }} sur {{ pagination.total || 0 }} inscriptions
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
              <tr>
                <th class="px-3 lg:px-6 py-3 text-left">
                  <input 
                    type="checkbox" 
                    :checked="isAllSelected" 
                    @change="toggleSelectAll"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                  >
                </th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Photo</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Inscrit</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Matricule</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Contact</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Ville</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Montant</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Paiement</th>
                <!-- NOUVELLE COLONNE -->
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Présence</th>
                <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                  <div class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Chargement...</span>
                  </div>
                </td>
              </tr>
              
              <tr v-else-if="registrations.length === 0">
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                  <div class="flex flex-col items-center space-y-2">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-lg font-medium">Aucune inscription trouvée</span>
                  </div>
                </td>
              </tr>
              
              <tr v-else v-for="registration in registrations" :key="registration.id" class="hover:bg-green-50 transition-colors duration-150">
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <input 
                    type="checkbox" 
                    :checked="isSelected(registration.id)" 
                    @change="toggleSelect(registration)"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                  >
                </td>
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <img 
                    v-if="registration.photo_membre" 
                    :src="registration.photo_membre" 
                    :alt="`${registration.prenom} ${registration.nom}`"
                    class="h-10 w-10 lg:h-12 lg:w-12 rounded-full object-cover border-2 border-green-200"
                    @error="handleImageError"
                  >
                  <div v-else class="h-10 w-10 lg:h-12 lg:w-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm lg:text-base">
                    {{ getInitials(registration.prenom, registration.nom) }}
                  </div>
                </td>

                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div>
                      <div class="text-xs lg:text-sm font-medium text-gray-900">{{ registration.prenom }} {{ registration.nom }}</div>
                      <div class="text-xs text-gray-500 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ formatDate(registration.created_at) }}
                      </div>
                      <div class="text-xs text-gray-500 mt-1">
                        <span :class="registration.sexe === 'M' ? 'text-blue-600' : 'text-pink-600'">
                          {{ registration.sexe === 'M' ? '♂ Masculin' : '♀ Féminin' }}
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 font-mono border border-green-200">
                    {{ registration.matricule_membre }}
                  </span>
                  <br/>
                  <span class="text-blue-600" style="font-size: 11px;">
                    {{ registration.qualite_membre }}
                  </span>
                  <br/>
                  <span :class="registration.cima === 'non' ? 'text-orange-600' : 'text-green-600'" style="font-size: 11px; font-weight: 600;">
                    {{ registration.cima === 'non' ? 'AG' : 'CIMA' }}
                  </span>
                </td>
                
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <div class="text-xs lg:text-sm text-gray-900">{{ registration.telephone }}</div>
                  <div v-if="registration.contact_paiement && registration.contact_paiement !== registration.telephone" class="text-xs text-gray-500 mt-1">
                    Paiement: {{ registration.contact_paiement }}
                  </div>
                </td>

                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <span v-if="registration.ville_ou_commune" class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ registration.ville_ou_commune }}
                  </span>
                  <span v-else class="text-xs text-gray-400">Non spécifié</span>
                </td>
                
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <div class="text-xs lg:text-sm font-semibold text-green-700">{{ formatXOF(registration.somme_payee) }}</div>
                  <div v-if="registration.montant_inscription !== registration.somme_payee" class="text-xs text-gray-500">
                    Attendu: {{ formatXOF(registration.montant_inscription) }}
                  </div>
                </td>
                
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <div class="space-y-1">
                    <span v-if="registration.statut_paiement === 'paye'" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800 border border-teal-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      Payé
                    </span>
                    <span v-else-if="registration.statut_paiement === 'echoue'" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                      Échoué
                    </span>
                    <span v-else class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      En attente
                    </span>
                    <div v-if="registration.reference_paiement" class="text-xs text-gray-500 font-mono truncate max-w-[120px]" :title="registration.reference_paiement">
                      {{ registration.reference_paiement }}
                    </div>
                  </div>
                </td>

                <!-- NOUVELLE CELLULE PRÉSENCE -->
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                  <button 
                    @click="togglePresence(registration)" 
                    :disabled="updatingPresence"
                    :class="[
                      'px-3 py-1.5 rounded-lg font-medium text-xs transition-all duration-200 flex items-center space-x-1',
                      registration.present === 1 
                        ? 'bg-green-100 text-green-800 hover:bg-green-200 border border-green-300 shadow-sm' 
                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300'
                    ]"
                    :title="registration.present === 1 ? 'Marquer absent' : 'Marquer présent'"
                  >
                    <svg v-if="registration.present === 1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ registration.present === 1 ? 'Présent' : 'Absent' }}</span>
                  </button>
                </td>
                
                <td class="px-3 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-1 lg:space-x-2">
                    <button @click="viewRegistration(registration)" class="text-green-600 hover:text-green-900 p-1.5 rounded-lg hover:bg-green-50 transition-colors" title="Voir détails">
                      <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                    
                    <button @click="downloadFiche(registration)" class="text-purple-600 hover:text-purple-900 p-1.5 rounded-lg hover:bg-purple-50 transition-colors" title="Télécharger fiche">
                      <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                    </button>

                    <button @click="downloadBadge(registration)" class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded-lg hover:bg-indigo-50 transition-colors" title="Générer badge">
                      <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                      </svg>
                    </button>

                    <button v-if="registration.photo_membre" @click="viewPhoto(registration)" class="text-blue-600 hover:text-blue-900 p-1.5 rounded-lg hover:bg-blue-50 transition-colors" title="Voir photo">
                      <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          
          <!-- Pagination -->
          <div class="px-4 lg:px-6 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between border-t border-gray-200 space-y-4 lg:space-y-0 bg-gray-50">
            <div class="flex items-center">
              <span class="text-sm text-gray-700">
                Affichage de <span class="font-medium">{{ pagination.from }}</span> à <span class="font-medium">{{ pagination.to }}</span> sur <span class="font-medium">{{ pagination.total }}</span> résultats
              </span>
            </div>
            <div class="flex items-center space-x-2">
              <button 
                @click="changePage(pagination.current_page - 1)" 
                :disabled="pagination.current_page === 1"
                class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium"
              >
                Précédent
              </button>
              <div class="flex items-center space-x-1">
                <button 
                  v-for="page in paginationPages" 
                  :key="page"
                  @click="changePage(page)" 
                  :class="[
                    'px-3 py-1.5 rounded-md text-sm font-medium',
                    pagination.current_page === page 
                      ? 'bg-green-600 text-white shadow-lg' 
                      : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
              </div>
              <button 
                @click="changePage(pagination.current_page + 1)" 
                :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1.5 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium"
              >
                Suivant
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Détails -->
    <div v-if="showViewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeViewModal">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50" style="padding: 8px;">
          <h3 class="text-xl font-bold text-gray-800">Détails de l'inscription</h3>
          <button @click="closeViewModal" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div v-if="selectedRegistration" class="p-6 space-y-6" style="padding: 8px;">
          <div class="flex items-start space-x-4 pb-6 border-b">
            <img 
              v-if="selectedRegistration.photo_membre" 
              :src="selectedRegistration.photo_membre" 
              :alt="`${selectedRegistration.prenom} ${selectedRegistration.nom}`"
              class="h-24 w-24 rounded-full object-cover border-4 border-green-200"
            >
            <div v-else class="h-24 w-24 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-3xl">
              {{ getInitials(selectedRegistration.prenom, selectedRegistration.nom) }}
            </div>
            
            <div class="flex-1">
              <h4 class="text-2xl font-bold text-gray-900">{{ selectedRegistration.prenom }} {{ selectedRegistration.nom }}</h4>
              <p class="text-gray-600 mt-1">{{ selectedRegistration.sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
              <div class="mt-3 space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 font-mono">
                  {{ selectedRegistration.matricule_membre }}
                </span>
                <br/>
                <span :class="selectedRegistration.cima === 'non' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                  {{ selectedRegistration.cima === 'non' ? 'AG (Assemblée Générale)' : 'CIMA (Membre CIMA)' }}
                </span>
                <br/>
                <span :class="selectedRegistration.present === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                  {{ selectedRegistration.present === 1 ? '✓ Présent' : '✗ Absent' }}
                </span>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <h5 class="font-semibold text-gray-700 border-b pb-2">Informations de Contact</h5>
              <div>
                <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                <p class="text-gray-900 mt-1">{{ selectedRegistration.telephone }}</p>
              </div>
              <div v-if="selectedRegistration.contact_paiement">
                <label class="block text-sm font-medium text-gray-500">Contact Paiement</label>
                <p class="text-gray-900 mt-1">{{ selectedRegistration.contact_paiement }}</p>
              </div>
              <div v-if="selectedRegistration.email">
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="text-gray-900 mt-1">{{ selectedRegistration.email }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Ville/Commune</label>
                <p class="text-gray-900 mt-1">{{ selectedRegistration.ville_ou_commune || 'Non spécifié' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Qualité membre</label>
                <p class="text-gray-900 mt-1">{{ selectedRegistration.qualite_membre }}</p>
              </div>
            </div>

            <div class="space-y-4">
              <h5 class="font-semibold text-gray-700 border-b pb-2">Informations de Paiement</h5>
              <div>
                <label class="block text-sm font-medium text-gray-500">Statut</label>
                <span :class="getPaymentBadgeClass(selectedRegistration.statut_paiement)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1">
                  {{ getPaymentLabel(selectedRegistration.statut_paiement) }}
                </span>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Montant Payé</label>
                <p class="text-lg font-bold text-green-700 mt-1">{{ formatXOF(selectedRegistration.somme_payee) }}</p>
              </div>
              <div v-if="selectedRegistration.reference_paiement">
                <label class="block text-sm font-medium text-gray-500">Référence</label>
                <p class="text-gray-900 mt-1 font-mono text-sm">{{ selectedRegistration.reference_paiement }}</p>
              </div>
              <div v-if="selectedRegistration.ref_paiement">
                <label class="block text-sm font-medium text-gray-500">Transaction</label>
                <p class="text-gray-900 mt-1 font-mono text-sm">{{ selectedRegistration.ref_paiement }}</p>
              </div>
            </div>

            <div class="md:col-span-2 space-y-4">
              <h5 class="font-semibold text-gray-700 border-b pb-2">Autres Informations</h5>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Thème</label>
                  <p class="text-gray-900 mt-1">{{ selectedRegistration.theme || 'Non spécifié' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Date d'inscription</label>
                  <p class="text-gray-900 mt-1">{{ formatDateTime(selectedRegistration.created_at) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between bg-gray-50">
          <button @click="downloadFiche(selectedRegistration)" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Télécharger</span>
          </button>
          <button @click="downloadBadge(selectedRegistration)" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            <span>Badge</span>
          </button>
          <button @click="closeViewModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            Fermer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Photo -->
    <div v-if="showPhotoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4" @click="closePhotoModal">
      <div class="relative max-w-4xl max-h-[90vh]" @click.stop>
        <button @click="closePhotoModal" class="absolute -top-10 right-0 text-white hover:text-gray-300 p-2">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        <img :src="selectedPhoto" alt="Photo membre" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl">
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import QRCode from 'qrcode'

// Configuration API
const API_BASE_URL = 'https://api.aeemci-ce.ci'

// État réactif
const registrations = ref([])
const loading = ref(false)
const exporting = ref(false)
const showViewModal = ref(false)
const showPhotoModal = ref(false)
const selectedRegistration = ref(null)
const selectedPhoto = ref('')
const searchTimeout = ref(null)
const villes = ref([])
const selectedRegistrations = ref([])
const updatingPresence = ref(false) // NOUVEAU

const stats = ref({
  total_inscriptions: 0,
  paid_count: 0,
  total_amount: 0,
  male_count: 0,
  female_count: 0,
  present_count: 0, // NOUVEAU
  cima_oui_count: 0,
  cima_oui_male: 0,
  cima_oui_female: 0,
  cima_oui_amount: 0,
  cima_non_count: 0,
  cima_non_male: 0,
  cima_non_female: 0,
  cima_non_amount: 0
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 500,
  total: 0,
  from: 0,
  to: 0
})

const filters = ref({
  search: '',
  ville: '',
  payment_status: '',
  sexe: '',
  cima: '',
  theme: ''
})

const isAllSelected = computed(() => {
  return registrations.value.length > 0 && selectedRegistrations.value.length === registrations.value.length
})

const isSelected = (id) => {
  return selectedRegistrations.value.some(r => r.id === id)
}

const toggleSelect = (registration) => {
  const index = selectedRegistrations.value.findIndex(r => r.id === registration.id)
  if (index > -1) {
    selectedRegistrations.value.splice(index, 1)
  } else {
    selectedRegistrations.value.push(registration)
  }
}

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedRegistrations.value = []
  } else {
    selectedRegistrations.value = [...registrations.value]
  }
}

// Méthodes de formatage
const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatDateTime = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatXOF = (amount) => {
  if (!amount) return '0 FCFA'
  return `${parseInt(amount).toLocaleString('fr-FR')} FCFA`
}

const getInitials = (prenom, nom) => {
  const p = prenom ? prenom.charAt(0) : ''
  const n = nom ? nom.charAt(0) : ''
  return (p + n).toUpperCase()
}

const handleImageError = (e) => {
  e.target.style.display = 'none'
}

const getPaymentBadgeClass = (status) => {
  const classes = {
    'paye': 'bg-teal-100 text-teal-800 border-teal-200',
    'echoue': 'bg-red-100 text-red-800 border-red-200',
    'pending': 'bg-yellow-100 text-yellow-800 border-yellow-200'
  }
  return classes[status] || classes.pending
}

const getPaymentLabel = (status) => {
  const labels = {
    'paye': 'Payé',
    'echoue': 'Échoué',
    'pending': 'En attente'
  }
  return labels[status] || 'En attente'
}

const paginationPages = computed(() => {
  const pages = []
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  
  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) pages.push(i)
      pages.push('...')
      pages.push(last)
    } else if (current >= last - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = last - 4; i <= last; i++) pages.push(i)
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) pages.push(i)
      pages.push('...')
      pages.push(last)
    }
  }
  
  return pages.filter(p => p !== '...' || pages.indexOf(p) === pages.lastIndexOf(p))
})

// Chargement des données
const loadData = async () => {
  loading.value = true
  try {
    await Promise.all([loadRegistrations(), loadStats(), loadVilles()])
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
  } finally {
    loading.value = false
  }
}

const loadRegistrations = async () => {
  try {
    const random = Date.now()
    let url = `${API_BASE_URL}/cima_api.php?action=list_cima_registrations`
      + `&page=${pagination.value.current_page}`
      + `&limit=${pagination.value.per_page}`
      + `&r=${random}`

    if (filters.value.search) {
      url += `&search=${encodeURIComponent(filters.value.search)}`
    }
    if (filters.value.ville) {
      url += `&ville=${encodeURIComponent(filters.value.ville)}`
    }
    if (filters.value.payment_status) {
      url += `&statut_paiement=${filters.value.payment_status}`
    }
    if (filters.value.sexe) {
      url += `&sexe=${filters.value.sexe}`
    }
    if (filters.value.cima) {
      url += `&cima=${filters.value.cima}`
    }

    const response = await fetch(url)
    const data = await response.json()

    if (data.success) {
      registrations.value = data.registrations || []
      if (data.pagination) {
        pagination.value = { ...pagination.value, ...data.pagination }
      }
    } else {
      console.error('API Error:', data)
    }
  } catch (error) {
    console.error('Erreur loadRegistrations:', error)
  }
}

const loadStats = async () => {
  try {
    const rand = Math.random() // variable aléatoire

    const response = await fetch(
      `${API_BASE_URL}/cima_api.php?action=stats_cima&rand=${rand}`
    )

    const data = await response.json()
    if (!data.success) return

    const statsData = data.stats || data

    stats.value = {
      total_inscriptions: parseInt(statsData.total_inscriptions) || 0,
      paid_count: parseInt(statsData.paid_count) || 0,
      total_amount: parseFloat(statsData.total_amount) || 0,
      male_count: parseInt(statsData.male_count) || 0,
      female_count: parseInt(statsData.female_count) || 0,
      present_count: parseInt(statsData.present_count) || 0,
      cima_oui_count: parseInt(statsData.cima_oui_count) || 0,
      cima_oui_male: parseInt(statsData.cima_oui_male) || 0,
      cima_oui_female: parseInt(statsData.cima_oui_female) || 0,
      cima_oui_amount: parseFloat(statsData.cima_oui_amount) || 0,
      cima_non_count: parseInt(statsData.cima_non_count) || 0,
      cima_non_male: parseInt(statsData.cima_non_male) || 0,
      cima_non_female: parseInt(statsData.cima_non_female) || 0,
      cima_non_amount: parseFloat(statsData.cima_non_amount) || 0
    }
  } catch (error) {
    console.error('Erreur loadStats:', error)
  }
}


const loadVilles = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/cima_api.php?action=get_villes`)
    const data = await response.json()
    
    if (data.success) {
      villes.value = data.villes || []
    }
  } catch (error) {
    console.error('Erreur loadVilles:', error)
  }
}

// NOUVELLE FONCTION: Basculer la présence
const togglePresence = async (registration) => {
  if (updatingPresence.value) return
  
  try {
    updatingPresence.value = true
    
    const newPresenceValue = registration.present === 1 ? 0 : 1
    
    const response = await fetch(`${API_BASE_URL}/cima_api.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'update_presence',
        matricule: registration.matricule_membre,
        present: newPresenceValue
      })
    })
    
    const data = await response.json()
    
    if (data.success) {
      // Mettre à jour localement
      registration.present = newPresenceValue
      
      // Mettre à jour le compteur dans les stats
      if (newPresenceValue === 1) {
        stats.value.present_count++
      } else {
        stats.value.present_count = Math.max(0, stats.value.present_count - 1)
      }
      
      console.log('✅ Présence mise à jour:', registration.prenom, registration.nom, newPresenceValue === 1 ? 'Présent' : 'Absent')
    } else {
      console.error('Erreur:', data.error)
      alert('Erreur lors de la mise à jour de la présence: ' + (data.error || 'Erreur inconnue'))
    }
  } catch (error) {
    console.error('Erreur lors de la mise à jour de la présence:', error)
    alert('Erreur lors de la mise à jour de la présence')
  } finally {
    updatingPresence.value = false
  }
}

// Filtres et recherche
const debounceSearch = () => {
  if (searchTimeout.value) clearTimeout(searchTimeout.value)
  searchTimeout.value = setTimeout(() => {
    pagination.value.current_page = 1
    loadRegistrations()
  }, 500)
}

const applyFilters = () => {
  pagination.value.current_page = 1
  loadRegistrations()
}

const resetFilters = () => {
  filters.value = {
    search: '',
    ville: '',
    payment_status: '',
    sexe: '',
    cima: '',
    theme: ''
  }
  pagination.value.current_page = 1
  loadRegistrations()
}

// Pagination
const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page && page !== '...') {
    pagination.value.current_page = page
    selectedRegistrations.value = []
    loadRegistrations()
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const changePerPage = () => {
  pagination.value.current_page = 1
  selectedRegistrations.value = []
  loadRegistrations()
}

// Actions
const viewRegistration = (registration) => {
  selectedRegistration.value = registration
  showViewModal.value = true
}

const closeViewModal = () => {
  showViewModal.value = false
  selectedRegistration.value = null
}

const viewPhoto = (registration) => {
  selectedPhoto.value = registration.photo_membre
  showPhotoModal.value = true
}

const closePhotoModal = () => {
  showPhotoModal.value = false
  selectedPhoto.value = ''
}

const downloadFiche = (registration) => {
  window.open(`${API_BASE_URL}/cima_api.php?action=download_fiche&id=${registration.id}`, '_blank')
}

const exportExcel = async () => {
    try {
      exporting.value = true
      showAlert('success', 'Génération du fichier Excel en cours...')
      
      if (registrations.value.length === 0) {
        showAlert('error', 'Aucune donnée à exporter. Veuillez d\'abord charger des membres.')
        return
      }
      
      const data = registrations.value.map(membre => ({
        'cima': membre.cima,
        'matricule': membre.matricule_membre,
        'nom': membre.prenom,
        'prenom': membre.nom,
        'Sexe': membre.sexe === 'M' ? 'Masculin' : 'Féminin',
        'qualite': membre.qualite_membre,
        'theme_par_defaut': membre.theme
      }))
      
      const XLSX = await import('xlsx')
      const workbook = XLSX.utils.book_new()
      const worksheet = XLSX.utils.json_to_sheet(data)
      
      const colWidths = [
        { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 15 },
        { wch: 25 }, { wch: 50 }
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

const exportPDF = () => {
  const params = new URLSearchParams()
  if (filters.value.search) params.append('search', filters.value.search)
  if (filters.value.ville) params.append('ville', filters.value.ville)
  if (filters.value.payment_status) params.append('statut_paiement', filters.value.payment_status)
  if (filters.value.sexe) params.append('sexe', filters.value.sexe)
  if (filters.value.cima) params.append('cima', filters.value.cima)
  
  window.open(`${API_BASE_URL}/cima_api.php?action=export_pdf&${params.toString()}`, '_blank')
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

const generateSingleBadge = async (doc, registration, x, y, width, height) => {
  doc.saveGraphicsState()
  
  try {
    const background = new Image()
    background.crossOrigin = 'anonymous'
    
    if (registration.cima === 'oui') {
      background.src = 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1766995508/cjibnqurbkyniey6grbp.jpg'
    } else {
      background.src = 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1766995409/kmk90e5vcifz7hwowqli.jpg'
    }
    
    await new Promise((res, rej) => {
      background.onload = res
      background.onerror = rej
    })
    doc.addImage(background, 'PNG', x, y, width, height)
  } catch (e) {
    console.warn('Image de fond non chargée', e)
  }

  let yOffset = y + 40.5

  try {
    const qrUrl = `${registration.matricule_membre}`
    const qrDataURL = await QRCode.toDataURL(qrUrl, {
      width: 220,
      margin: 0
    })

    const qrWidth = 25
    const qrHeight = 26.5
    const offsetX = 0
    const offsetY = 8.5
    const qrX = x + (width - qrWidth) / 2 + offsetX
    const qrY = yOffset + offsetY
    const radius = 2
    const padding = 1.5

    doc.setFillColor(255, 255, 255)
    doc.roundedRect(
      qrX - padding,
      qrY - padding,
      qrWidth + padding * 2,
      qrHeight + padding * 2,
      radius,
      radius,
      'F'
    )

    doc.addImage(qrDataURL, 'PNG', qrX, qrY, qrWidth, qrHeight)
    yOffset += qrHeight + 11
  } catch (e) {
    console.warn('QR non chargé', e)
  }

  doc.setFontSize(16)
  doc.setFont('helvetica', 'bold')
  doc.setTextColor(255, 255, 255)
  doc.text(
    `${registration.prenom} ${registration.nom}`,
    x + width / 2,
    yOffset + 6,
    { align: 'center' }
  )
  
  const cimaOffsetY = registration.cima === 'oui' ? -2 : 0
  yOffset += 13.5 + cimaOffsetY

  doc.setFontSize(12)
  doc.setFont('helvetica', 'bold')
  doc.setTextColor(80, 80, 80)
  doc.text(
    `${registration.matricule_membre}`,
    x + width / 2,
    yOffset + 1.3,
    { align: 'center' }
  )

  doc.restoreGraphicsState()
}

const downloadBadge = async (registration) => {
  try {
    exporting.value = true
    const { jsPDF } = await import('jspdf')
    const doc = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: [100, 130],
    })

    await generateSingleBadge(doc, registration, 0, 0, 100, 130)
    
    doc.save(`badge_${registration.prenom}_${registration.nom}.pdf`)
  } catch (error) {
    console.error(error)
    alert('Erreur lors de la génération du badge.')
  } finally {
    exporting.value = false
  }
}

const downloadSelectedBadges = async () => {
  if (selectedRegistrations.value.length === 0) {
    alert('Aucune inscription sélectionnée.')
    return
  }
  
  try {
    exporting.value = true
    const { jsPDF } = await import('jspdf')
    const doc = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4',
    })

    const badgeWidth = 100
    const badgeHeight = 130
    const badgesPerRow = 2
    const badgesPerColumn = 2
    const badgesPerPage = badgesPerRow * badgesPerColumn
    const marginX = (210 - (badgesPerRow * badgeWidth)) / (badgesPerRow + 1)
    const marginY = (297 - (badgesPerColumn * badgeHeight)) / (badgesPerColumn + 1)

    let badgeCount = 0

    for (let i = 0; i < selectedRegistrations.value.length; i++) {
      const registration = selectedRegistrations.value[i]
      
      if (badgeCount > 0 && badgeCount % badgesPerPage === 0) {
        doc.addPage()
      }

      const row = Math.floor((badgeCount % badgesPerPage) / badgesPerRow)
      const col = (badgeCount % badgesPerPage) % badgesPerRow
      const x = marginX + col * (badgeWidth + marginX)
      const y = marginY + row * (badgeHeight + marginY)

      await generateSingleBadge(doc, registration, x, y, badgeWidth, badgeHeight)
      badgeCount++
    }

    const filename = `badges_selectionnes_cima_${new Date().toISOString().split('T')[0]}.pdf`
    doc.save(filename)
  } catch (error) {
    console.error('Erreur lors de la génération des badges sélectionnés:', error)
    alert('Erreur lors de la génération des badges sélectionnés.')
  } finally {
    exporting.value = false
  }
}

const downloadAllBadges = async () => {
  try {
    exporting.value = true
    const { jsPDF } = await import('jspdf')
    const doc = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4',
    })

    const badgeWidth = 100
    const badgeHeight = 130
    const badgesPerRow = 2
    const badgesPerColumn = 2
    const badgesPerPage = badgesPerRow * badgesPerColumn
    const marginX = (210 - (badgesPerRow * badgeWidth)) / (badgesPerRow + 1)
    const marginY = (297 - (badgesPerColumn * badgeHeight)) / (badgesPerColumn + 1)

    let badgeCount = 0

    for (let i = 0; i < registrations.value.length; i++) {
      const registration = registrations.value[i]
      
      if (badgeCount > 0 && badgeCount % badgesPerPage === 0) {
        doc.addPage()
      }

      const row = Math.floor((badgeCount % badgesPerPage) / badgesPerRow)
      const col = (badgeCount % badgesPerPage) % badgesPerRow
      const x = marginX + col * (badgeWidth + marginX)
      const y = marginY + row * (badgeHeight + marginY)

      await generateSingleBadge(doc, registration, x, y, badgeWidth, badgeHeight)
      badgeCount++
    }

    const filename = `tous_les_badges_cima_${new Date().toISOString().split('T')[0]}.pdf`
    doc.save(filename)
  } catch (error) {
    console.error('Erreur lors de la génération des badges:', error)
    alert('Erreur lors de la génération des badges.')
  } finally {
    exporting.value = false
  }
}

const goBack = () => {
  window.history.back()
}

onMounted(() => {
  loadData()
})
</script>

<style scoped>
@keyframes blob {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
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

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>