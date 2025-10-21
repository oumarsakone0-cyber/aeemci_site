<template>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 relative overflow-hidden">
      <!-- Animations de fond -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
      </div>
  
      <div class="relative z-10" style="max-width: 1300px; margin: 0 auto; padding: 2rem;">
        <!-- Header -->
        <div class="flex items-center justify-between" style="margin-bottom: 2rem;">
          <div class="flex items-center space-x-4">
            <button @click="$router.go(-1)" class="p-2 bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
              <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div>
              <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Gestion Évaluations
              </h1>
              <p class="text-gray-600">Création et suivi des évaluations QCM</p>
            </div>
          </div>
  
          <div class="flex items-center space-x-3">
            <button @click="exportExcel()" class="bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2" style="padding: 0.75rem 1.5rem; background: green">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <span>Excel</span>
            </button>
            <button @click="exportPDF()" class="bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2" style="padding: 0.75rem 1.5rem; background: red">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
              <span>PDF</span>
            </button>
            <button @click="showAddModal = true" class="bg-orange-600 hover:bg-orange-700 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 flex items-center space-x-2" style="padding: 1rem 2rem; background: blue">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <span>Créer Évaluation</span>
            </button>
          </div>
        </div>
  
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8" style="margin-bottom: 2rem;">
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-orange-500" style="padding: 2rem;">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Évaluations</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_evaluations || 0 }}</p>
              </div>
              <div class="p-3 bg-orange-100 rounded-full">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-amber-500" style="padding: 2rem;">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Participants</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_participants || 0 }}</p>
              </div>
              <div class="p-3 bg-amber-100 rounded-full">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500" style="padding: 2rem;">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Évaluations Actives</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.active_evaluations || 0 }}</p>
              </div>
              <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
            </div>
          </div>
  
          <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500" style="padding: 2rem;">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Moyenne Générale</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.average_score || 0 }}%</p>
              </div>
              <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Filtres avancés -->
        <div class="bg-white rounded-xl shadow-lg" style="padding: 2rem; margin-bottom: 2rem;">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6" style="margin-bottom: 1.5rem;">
            <div>
              <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Rechercher</label>
              <input
                v-model="filters.search"
                @input="debounceSearch"
                type="text"
                placeholder="Rechercher par titre..."
                class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                style="padding: 1rem 1.5rem;"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Statut</label>
              <select v-model="filters.statut" @change="applyFilters" class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" style="padding: 1rem 1.5rem;">
                <option value="">Tous les statuts</option>
                <option value="brouillon">Brouillon</option>
                <option value="active">Active</option>
                <option value="terminee">Terminée</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Date début</label>
              <input
                v-model="filters.date_debut"
                @change="applyFilters"
                type="date"
                class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                style="padding: 1rem 1.5rem;"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Date fin</label>
              <input
                v-model="filters.date_fin"
                @change="applyFilters"
                type="date"
                class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                style="padding: 1rem 1.5rem;"
              >
            </div>
          </div>
  
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <label class="block text-sm font-medium text-gray-700">Éléments par page:</label>
              <select v-model="pagination.per_page" @change="changePerPage" class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" style="padding: 0.5rem 1rem;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <button @click="resetFilters" class="text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center space-x-2" style="padding: 0.75rem 1.5rem;">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              <span>Réinitialiser</span>
            </button>
          </div>
        </div>
  
        <!-- Tableau des évaluations -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="border-b border-gray-200 flex items-center justify-between" style="padding: 1.5rem 2rem;">
            <h3 class="text-lg font-semibold text-gray-800">Liste des Évaluations</h3>
            <div class="text-sm text-gray-500">
              Affichage {{ pagination.from || 0 }} à {{ pagination.to || 0 }} sur {{ pagination.total || 0 }} entrées
            </div>
          </div>
  
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1.5rem 2rem;">
                    <div class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <span>Titre</span>
                    </div>
                  </th>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1.5rem 2rem;">Questions</th>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1.5rem 2rem;">Participants</th>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1.5rem 2rem;">Statut</th>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1.5rem 2rem;">Accès</th>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1.5rem 2rem;">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading" class="animate-pulse">
                  <td colspan="6" class="text-center text-gray-500" style="padding: 2rem;">
                    <div class="flex items-center justify-center space-x-2">
                      <svg class="animate-spin h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span>Chargement...</span>
                    </div>
                  </td>
                </tr>
                <tr v-else-if="evaluations.length === 0">
                  <td colspan="6" class="text-center text-gray-500" style="padding: 2rem;">
                    <div class="flex flex-col items-center space-y-2">
                      <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <span>Aucune évaluation trouvée</span>
                    </div>
                  </td>
                </tr>
                <tr v-else v-for="evaluation in evaluations" :key="evaluation.id" class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="whitespace-nowrap" style="padding: 1.5rem 2rem;">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                          <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                          </svg>
                        </div>
                      </div>
                      <div style="margin-left: 1rem;">
                        <div class="text-sm font-medium text-gray-900">{{ evaluation.titre }}</div>
                        <div class="text-sm text-gray-500">{{ evaluation.duree }} min</div>
                      </div>
                    </div>
                  </td>
                  <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1.5rem 2rem;">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      {{ evaluation.nb_questions || 0 }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1.5rem 2rem;">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      {{ evaluation.nb_participants || 0 }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap" style="padding: 1.5rem 2rem;">
                    <span :class="getStatutClass(evaluation.statut)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ getStatutLabel(evaluation.statut) }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap" style="padding: 1.5rem 2rem;">
                    <span :class="evaluation.acces_type === 'libre' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ evaluation.acces_type === 'libre' ? 'Libre' : 'Restreint' }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap text-sm font-medium" style="padding: 1.5rem 2rem;">
                    <div class="flex items-center space-x-2">
                      <!-- Nouveau bouton QR Code -->
                      <button @click="showQRCode(evaluation)" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition-colors" title="Générer QR Code">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M4 12h4.01M4 16h4.01M4 8h4.01M8 8h.01M8 12h.01M8 16h.01M12 8h.01"></path>
                        </svg>
                      </button>
                      
                      <!-- Boutons Export Excel et PDF pour cette évaluation -->
                      <button @click="exportEvaluationExcel(evaluation)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="Exporter Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </button>
                      
                      <button @click="exportEvaluationPDF(evaluation)" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" title="Exporter PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                      </button>
                      
                      <button @click="viewResults(evaluation)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" title="Voir résultats">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                      </button>
                      <button @click="editEvaluation(evaluation)" class="text-orange-600 hover:text-orange-900 p-1 rounded hover:bg-orange-50 transition-colors" title="Modifier">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </button>
                      <button @click="duplicateEvaluation(evaluation)" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition-colors" title="Dupliquer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                      </button>
                      <button @click="deleteEvaluation(evaluation)" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" title="Supprimer">
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
            <div class="border-t border-gray-200 flex items-center justify-between" style="padding: 1.5rem 2rem;">
              <div class="flex items-center">
                <span class="text-sm text-gray-700">
                  Affichage de <span class="font-medium">{{ pagination.from }}</span> à <span class="font-medium">{{ pagination.to }}</span> sur <span class="font-medium">{{ pagination.total }}</span> résultats
                </span>
              </div>
              <div class="flex items-center space-x-2">
                <button
                  @click="changePage(pagination.current_page - 1)"
                  :disabled="pagination.current_page === 1"
                  class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed rounded-md"
                  style="padding: 0.5rem 1rem;"
                >
                  Précédent
                </button>
                <div class="flex items-center space-x-1">
                  <template v-for="page in paginationPages" :key="page">
                    <button
                      @click="changePage(page)"
                      :class="[
                        'rounded-md',
                        pagination.current_page === page
                          ? 'bg-orange-600 text-white'
                          : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
                      ]"
                      style="padding: 0.5rem 1rem;"
                    >
                      {{ page }}
                    </button>
                  </template>
                </div>
                <button
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="pagination.current_page === pagination.last_page"
                  class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed rounded-md"
                  style="padding: 0.5rem 1rem;"
                >
                  Suivant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal QR Code -->
      <div v-if="showQRModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="padding: 1rem;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-100">
          <div class="border-b border-gray-200" style="padding: 2rem;">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M4 12h4.01M4 16h4.01M4 8h4.01M8 8h.01M8 12h.01M8 16h.01M12 8h.01"></path>
              </svg>
              Code QR - {{ selectedEvaluation?.titre }}
            </h3>
          </div>
  
          <div style="padding: 2rem;" class="text-center">
            <div class="mb-6">
              <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 mb-2">Lien d'accès à l'évaluation :</p>
                <div class="bg-white border rounded-lg p-3 text-sm font-mono text-gray-800 break-all">
                  {{ qrCodeData.url }}
                </div>
              </div>
  
              <!-- Zone QR Code -->
              <div class="flex justify-center mb-4">
                <div id="qrcode" class="bg-white p-4 rounded-lg shadow-inner border-2 border-gray-100"></div>
              </div>
  
              <p class="text-xs text-gray-500">
                Scannez ce code QR pour accéder directement à l'évaluation
              </p>
            </div>
  
            <div class="flex justify-center space-x-3">
              <button @click="copyQRLink" class="bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2" style="padding: 0.75rem 1.5rem; background-color: blue">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <span>Copier le lien</span>
              </button>
              <button @click="downloadQRCode" class="bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2" style="padding: 0.75rem 1.5rem; background-color: green">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Télécharger</span>
              </button>
            </div>
          </div>
  
          <div class="border-t border-gray-200 flex justify-end" style="padding: 1.5rem 2rem;">
            <button @click="closeQRModal" class="bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" style="padding: 0.75rem 1.5rem;">
              Fermer
            </button>
          </div>
        </div>
      </div>
  
      <!-- Modal Ajouter/Modifier Évaluation -->
      <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="padding: 1rem;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-screen overflow-y-auto transform transition-all duration-300 scale-100">
          <div class="border-b border-gray-200" style="padding: 2rem;">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              {{ showAddModal ? 'Créer Évaluation' : 'Modifier Évaluation' }}
            </h3>
          </div>
  
          <form @submit.prevent="showAddModal ? addEvaluation() : updateEvaluation()" style="padding: 2rem;" class="space-y-8">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Titre *</label>
                <input
                  v-model="form.titre"
                  type="text"
                  placeholder="Ex: Évaluation Histoire Sainte"
                  class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  style="padding: 1rem 1.5rem;"
                  required
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Durée (minutes) *</label>
                <input
                  v-model="form.duree"
                  type="number"
                  min="5"
                  max="180"
                  placeholder="60"
                  class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  style="padding: 1rem 1.5rem;"
                  required
                >
              </div>
            </div>
  
            <div>
              <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Description de l'évaluation..."
                class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                style="padding: 1rem 1.5rem;"
              ></textarea>
            </div>
  
            <!-- Configuration d'accès -->
            <div>
              <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 1rem;">Type d'accès</label>
              <div class="space-y-4">
                <div class="flex items-center">
                  <input
                    id="acces_libre"
                    v-model="form.acces_type"
                    type="radio"
                    value="libre"
                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300"
                  >
                  <label for="acces_libre" class="ml-3 block text-sm font-medium text-gray-700">
                    Accès libre - Tous les séminaristes peuvent participer
                  </label>
                </div>
                <div class="flex items-center">
                  <input
                    id="acces_restreint"
                    v-model="form.acces_type"
                    type="radio"
                    value="restreint"
                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300"
                  >
                  <label for="acces_restreint" class="ml-3 block text-sm font-medium text-gray-700">
                    Accès restreint - Seuls certains matricules peuvent participer
                  </label>
                </div>
              </div>
  
              <div v-if="form.acces_type === 'restreint'" style="margin-top: 1rem;">
                <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">
                  Matricules autorisés (séparés par des virgules)
                </label>
                <textarea
                  v-model="form.matricules_autorises"
                  rows="3"
                  placeholder="Ex: SEM001, SEM002, SEM003..."
                  class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  style="padding: 1rem 1.5rem;"
                ></textarea>
                <p class="text-xs text-gray-500" style="margin-top: 0.5rem;">
                  Entrez les matricules séparés par des virgules
                </p>
              </div>
            </div>
  
            <!-- Questions -->
            <div>
              <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
                <label class="block text-sm font-medium text-gray-700">Questions QCM *</label>
                <button type="button" @click="addQuestion" class="bg-orange-600 hover:bg-orange-700 text-white rounded-lg flex items-center space-x-2 transition-colors" style="padding: 0.5rem 1rem; background: blue">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                  <span>Ajouter Question</span>
                </button>
              </div>
  
              <div v-if="form.questions.length === 0" class="text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg" style="padding: 3rem;">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Aucune question ajoutée</p>
                <p class="text-sm">Cliquez sur "Ajouter Question" pour commencer</p>
              </div>
  
              <div v-else class="space-y-6">
                <div v-for="(question, index) in form.questions" :key="index" class="border border-gray-200 rounded-lg" style="padding: 1.5rem;">
                  <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
                    <h4 class="text-sm font-medium text-gray-700">Question {{ index + 1 }}</h4>
                    <button type="button" @click="removeQuestion(index)" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
  
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Question *</label>
                      <textarea
                        v-model="question.question"
                        rows="2"
                        placeholder="Posez votre question ici..."
                        class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        style="padding: 1rem 1.5rem;"
                        required
                      ></textarea>
                    </div>
  
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Réponse A *</label>
                        <input
                          v-model="question.reponse_a"
                          type="text"
                          placeholder="Première option"
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          style="padding: 1rem 1.5rem;"
                          required
                        >
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Réponse B *</label>
                        <input
                          v-model="question.reponse_b"
                          type="text"
                          placeholder="Deuxième option"
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          style="padding: 1rem 1.5rem;"
                          required
                        >
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Réponse C *</label>
                        <input
                          v-model="question.reponse_c"
                          type="text"
                          placeholder="Troisième option"
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          style="padding: 1rem 1.5rem;"
                          required
                        >
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Réponse D *</label>
                        <input
                          v-model="question.reponse_d"
                          type="text"
                          placeholder="Quatrième option"
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          style="padding: 1rem 1.5rem;"
                          required
                        >
                      </div>
                    </div>
  
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Bonne réponse *</label>
                        <select
                          v-model="question.bonne_reponse"
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          style="padding: 1rem 1.5rem;"
                          required
                        >
                          <option value="">Sélectionner...</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="D">D</option>
                        </select>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700" style="margin-bottom: 0.5rem;">Points *</label>
                        <input
                          v-model="question.points"
                          type="number"
                          min="1"
                          max="10"
                          placeholder="1"
                          class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          style="padding: 1rem 1.5rem;"
                          required
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
            <div class="flex justify-end space-x-3" style="padding-top: 1.5rem;">
              <button type="button" @click="closeModal()" class="text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" style="padding: 0.75rem 1.5rem;">
                Annuler
              </button>
              <button type="submit" :disabled="submitting || form.questions.length === 0" class="bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 flex items-center space-x-2" style="padding: 0.75rem 2rem; background: green">
                <svg v-if="submitting" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ showAddModal ? 'Créer' : 'Modifier' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Modal Résultats -->
      <div v-if="showResultsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="padding: 1rem;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl max-h-screen overflow-y-auto">
          <div class="border-b border-gray-200" style="padding: 2rem;">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
              <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              Résultats - {{ selectedEvaluation?.titre }}
            </h3>
          </div>
  
          <div v-if="results" style="padding: 2rem;">
            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6" style="margin-bottom: 2rem;">
              <div class="bg-blue-50 rounded-lg" style="padding: 1.5rem;">
                <div class="text-2xl font-bold text-blue-600">{{ results.total_participants }}</div>
                <div class="text-sm text-blue-800">Participants</div>
              </div>
              <div class="bg-green-50 rounded-lg" style="padding: 1.5rem;">
                <div class="text-2xl font-bold text-green-600">{{ results.moyenne }}</div>
                <div class="text-sm text-green-800">Moyenne</div>
              </div>
              <div class="bg-yellow-50 rounded-lg" style="padding: 1.5rem;">
                <div class="text-2xl font-bold text-yellow-600">{{ results.meilleur_score }}</div>
                <div class="text-sm text-yellow-800">Meilleur Score</div>
              </div>
              <div class="bg-purple-50 rounded-lg" style="padding: 1.5rem;">
                <div class="text-2xl font-bold text-purple-600">{{ results.taux_reussite }}%</div>
                <div class="text-sm text-purple-800">Taux Réussite</div>
              </div>
            </div>
  
            <!-- Tableau des résultats -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <div class="border-b border-gray-200" style="padding: 1rem 1.5rem;">
                <h4 class="text-lg font-medium text-gray-900">Détails par participant</h4>
              </div>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Matricule</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Nom</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Etude</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Sexe</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Score</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Affectation</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Pourcentage</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Temps</th>
                      <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="padding: 1rem 1.5rem;">Date</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="results.length === 0">
                      <td colspan="6" class="text-center text-gray-500" style="padding: 2rem;">
                        Aucun participant pour le moment
                      </td>
                    </tr>
                    <tr v-else v-for="participant in results.participants" :key="participant.matricule" class="hover:bg-gray-50">
                      <td class="whitespace-nowrap text-sm font-medium text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ participant.matricule }}
                      </td>
                      <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ participant.nom +' '+ participant.prenom || 'N/A' }}
                      </td>
                      <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ participant.niveau_etude || 'N/A' }}
                      </td>
                      <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ participant.sexe || 'N/A' }}
                      </td>
                      <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ participant.score_obtenu }}/{{ participant.score_total }}
                      </td>
                      <td class="whitespace-nowrap text-sm font-medium text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ participant.niveau_affecte || 'N/A' }}
                      </td>
                      <td class="whitespace-nowrap" style="padding: 1rem 1.5rem;">
                        <span :class="getScoreClass(participant.score_pourcentage )" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="padding: 10px">
                          {{ participant.score_pourcentage }}%
                        </span>
                      </td>
                      <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ formatTime(participant.temps_ecoule) }}
                      </td>
                      <td class="whitespace-nowrap text-sm text-gray-900" style="padding: 1rem 1.5rem;">
                        {{ formatDate(participant.completed_at) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
  
          <div class="border-t border-gray-200 flex justify-end" style="padding: 1.5rem 2rem;">
            <button @click="showResultsModal = false" class="bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" style="padding: 0.75rem 1.5rem;">
              Fermer
            </button>
          </div>
        </div>
      </div>
  
      <!-- Alert Messages -->
      <div v-if="alert.show" :class="[
        'fixed top-4 right-4 z-50 rounded-lg shadow-lg transform transition-all duration-300',
        alert.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
      ]" style="padding: 1rem;">
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
  import QRCode from 'qrcode'
  
  export default {
    name: 'EvaluationsManagement',
    data() {
      return {
        API_BASE_URL: 'https://sogetrag.com/api/evaluations-api.php',
        evaluations: [],
        loading: false,
        submitting: false,
        exporting: false,
        showAddModal: false,
        showEditModal: false,
        showResultsModal: false,
        showQRModal: false, // Nouveau modal QR
        selectedEvaluation: null,
        results: null,
        searchTimeout: null,
        qrCodeData: { // Données pour le QR Code
          url: '',
          qrInstance: null,
          imageDataURL: null
        },
        stats: {
          total_evaluations: 0,
          total_participants: 0,
          active_evaluations: 0,
          average_score: 0
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
          statut: '',
          date_debut: '',
          date_fin: ''
        },
        form: {
          titre: '',
          description: '',
          duree: 60,
          acces_type: 'libre',
          matricules_autorises: '',
          questions: []
        },
        alert: {
          show: false,
          type: 'success',
          message: ''
        }
      }
    },
    computed: {
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
      }
    },
    mounted() {
      this.loadData();
    },
    methods: {
      async loadData() {
        this.loading = true;
        try {
          await Promise.all([
            this.loadEvaluations(),
            this.loadStats()
          ]);
        } catch (error) {
          console.error('Erreur lors du chargement:', error);
          this.showAlert('error', 'Erreur lors du chargement des données');
        } finally {
          this.loading = false;
        }
      },
  
      async loadEvaluations() {
        try {
          let url = `${this.API_BASE_URL}?action=evaluations&page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&rand=${Math.random()}`;
  
          if (this.filters.search) {
            url += `&search=${encodeURIComponent(this.filters.search)}`;
          }
          if (this.filters.statut) {
            url += `&statut=${this.filters.statut}`;
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
            this.evaluations = data.data || [];
            if (data.pagination) {
              this.pagination = {
                ...this.pagination,
                ...data.pagination
              };
            }
          } else {
            throw new Error(data.error || 'Erreur lors du chargement des évaluations');
          }
        } catch (error) {
          console.error('Erreur loadEvaluations:', error);
          this.showAlert('error', 'Erreur lors du chargement des évaluations');
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
  
      async changePage(page) {
        if (page < 1 || page > this.pagination.last_page) return;
        this.pagination.current_page = page;
        await this.loadEvaluations();
      },
  
      async changePerPage() {
        this.pagination.current_page = 1;
        await this.loadEvaluations();
      },
  
      async applyFilters() {
        this.pagination.current_page = 1;
        await this.loadEvaluations();
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
          statut: '',
          date_debut: '',
          date_fin: ''
        };
        this.pagination.current_page = 1;
        this.loadEvaluations();
      },
  
      addQuestion() {
        this.form.questions.push({
          question: '',
          reponse_a: '',
          reponse_b: '',
          reponse_c: '',
          reponse_d: '',
          bonne_reponse: '',
          points: 1
        });
      },
  
      removeQuestion(index) {
        this.form.questions.splice(index, 1);
      },
  
      async addEvaluation() {
        if (!this.form.titre || this.form.questions.length === 0) {
          this.showAlert('error', 'Veuillez remplir tous les champs obligatoires et ajouter au moins une question');
          return;
        }
  
        this.submitting = true;
        try {
          const response = await fetch(`${this.API_BASE_URL}?action=create_evaluation&rand=${Math.random()}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.form)
          });
          const data = await response.json();
  
          if (data.success) {
            this.showAlert('success', 'Évaluation créée avec succès');
            this.closeModal();
            await this.loadData();
          } else {
            throw new Error(data.error || 'Erreur lors de la création');
          }
        } catch (error) {
          console.error('Erreur addEvaluation:', error);
          this.showAlert('error', error.message || 'Erreur lors de la création de l\'évaluation');
        } finally {
          this.submitting = false;
        }
      },
  
      async updateEvaluation() {
        if (!this.form.titre || this.form.questions.length === 0) {
          this.showAlert('error', 'Veuillez remplir tous les champs obligatoires et ajouter au moins une question');
          return;
        }
  
        this.submitting = true;
        try {
          const response = await fetch(`${this.API_BASE_URL}?action=update_evaluation&id=${this.selectedEvaluation.id}&rand=${Math.random()}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.form)
          });
          const data = await response.json();
  
          if (data.success) {
            this.showAlert('success', 'Évaluation modifiée avec succès');
            this.closeModal();
            await this.loadData();
          } else {
            throw new Error(data.error || 'Erreur lors de la modification');
          }
        } catch (error) {
          console.error('Erreur updateEvaluation:', error);
          this.showAlert('error', error.message || 'Erreur lors de la modification de l\'évaluation');
        } finally {
          this.submitting = false;
        }
      },
  
      async deleteEvaluation(evaluation) {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer l'évaluation "${evaluation.titre}" ?`)) {
          return;
        }
  
        try {
          const response = await fetch(`${this.API_BASE_URL}?action=delete_evaluation&id=${evaluation.id}&rand=${Math.random()}`, {
            method: 'DELETE'
          });
          const data = await response.json();
  
          if (data.success) {
            this.showAlert('success', 'Évaluation supprimée avec succès');
            await this.loadData();
          } else {
            throw new Error(data.error || 'Erreur lors de la suppression');
          }
        } catch (error) {
          console.error('Erreur deleteEvaluation:', error);
          this.showAlert('error', error.message || 'Erreur lors de la suppression de l\'évaluation');
        }
      },
  
      async duplicateEvaluation(evaluation) {
        try {
          const response = await fetch(`${this.API_BASE_URL}?action=duplicate_evaluation&id=${evaluation.id}&rand=${Math.random()}`, {
            method: 'POST'
          });
          const data = await response.json();
  
          if (data.success) {
            this.showAlert('success', 'Évaluation dupliquée avec succès');
            await this.loadData();
          } else {
            throw new Error(data.error || 'Erreur lors de la duplication');
          }
        } catch (error) {
          console.error('Erreur duplicateEvaluation:', error);
          this.showAlert('error', error.message || 'Erreur lors de la duplication de l\'évaluation');
        }
      },
  
      editEvaluation(evaluation) {
        this.selectedEvaluation = evaluation;
        this.form = {
          titre: evaluation.titre,
          description: evaluation.description || '',
          duree: evaluation.duree,
          acces_type: evaluation.acces_type || 'libre',
          matricules_autorises: evaluation.matricules_autorises || '',
          questions: []
        };
  
        // Charger les questions existantes
        this.loadEvaluationQuestions(evaluation.id);
        this.showEditModal = true;
      },
  
      async loadEvaluationQuestions(evaluationId) {
        try {
          const response = await fetch(`${this.API_BASE_URL}?action=questions&evaluation_id=${evaluationId}&rand=${Math.random()}`);
          const data = await response.json();
  
          if (data.success) {
            this.form.questions = data.data || [];
          }
        } catch (error) {
          console.error('Erreur loadEvaluationQuestions:', error);
        }
      },
  
      async viewResults(evaluation) {
        this.selectedEvaluation = evaluation;
        try {
          const response = await fetch(`${this.API_BASE_URL}?action=resultats&evaluation_id=${evaluation.id}&rand=${Math.random()}`);
          const data = await response.json();
  
          if (data.success) {
            this.results = data.data;
            this.showResultsModal = true;
          } else {
            throw new Error(data.error || 'Erreur lors du chargement des résultats');
          }
        } catch (error) {
          console.error('Erreur viewResults:', error);
          this.showAlert('error', error.message || 'Erreur lors du chargement des résultats');
        }
      },
  
      // Nouvelle méthode pour afficher le QR Code
      async showQRCode(evaluation) {
        this.selectedEvaluation = evaluation;
        this.qrCodeData.url = `https://aeemci.sastock.com/evaluation_question/${evaluation.id}`;
        this.showQRModal = true;
  
        // Attendre que le modal soit rendu avant de générer le QR code
        this.$nextTick(() => {
          this.generateQRCode();
        });
      },
  
      async generateQRCode() {
        try {
          // Charger la bibliothèque QRCode dynamiquement
          const QRCode = await import('qrcode');
  
          // Générer le QR code comme image data URL
          const qrDataURL = await QRCode.toDataURL(this.qrCodeData.url, {
            width: 200,
            margin: 2,
            color: {
              dark: '#000000',
              light: '#FFFFFF'
            }
          });
  
          // Afficher l'image dans le conteneur
          const qrContainer = document.getElementById('qrcode');
          if (qrContainer) {
            qrContainer.innerHTML = `<img src="${qrDataURL}" alt="QR Code" style="max-width: 100%; height: auto; border-radius: 8px;">`;
  
            // Stocker l'image pour le téléchargement
            this.qrCodeData.imageDataURL = qrDataURL;
          }
        } catch (error) {
          console.error('Erreur lors de la génération du QR code:', error);
          this.showAlert('error', 'Erreur lors de la génération du QR code');
        }
      },
  
      async copyQRLink() {
        try {
          await navigator.clipboard.writeText(this.qrCodeData.url);
          this.showAlert('success', 'Lien copié dans le presse-papiers !');
        } catch (error) {
          console.error('Erreur lors de la copie:', error);
          this.showAlert('error', 'Erreur lors de la copie du lien');
        }
      },
  
      async downloadQRCode() {
        try {
          if (!this.qrCodeData.imageDataURL) {
            this.showAlert('error', 'QR Code non généré');
            return;
          }
  
          // Créer un lien de téléchargement
          const link = document.createElement('a');
          link.download = `qr-code-evaluation-${this.selectedEvaluation.id}.png`;
          link.href = this.qrCodeData.imageDataURL;
          link.click();
  
          this.showAlert('success', 'QR Code téléchargé avec succès !');
        } catch (error) {
          console.error('Erreur lors du téléchargement:', error);
          this.showAlert('error', 'Erreur lors du téléchargement du QR code');
        }
      },
  
      closeQRModal() {
        this.showQRModal = false;
        this.selectedEvaluation = null;
        this.qrCodeData = {
          url: '',
          qrInstance: null,
          imageDataURL: null
        };
      },
  
      closeModal() {
        this.showAddModal = false;
        this.showEditModal = false;
        this.showResultsModal = false;
        this.selectedEvaluation = null;
        this.results = null;
        this.form = {
          titre: '',
          description: '',
          duree: 60,
          acces_type: 'libre',
          matricules_autorises: '',
          questions: []
        };
      },
  
      async exportExcel() {
        try {
          this.exporting = true;
          this.showAlert('success', 'Génération du fichier Excel en cours...');
  
          const response = await fetch(`${this.API_BASE_URL}?action=export_results&rand=${Math.random()}`);
          const data = await response.json();
  
          if (!data.success) {
            throw new Error(data.error || 'Erreur lors de l\'export');
          }
  
          const results = data.data;
          const XLSX = await import('xlsx');
          const workbook = XLSX.utils.book_new();
          const worksheet = XLSX.utils.json_to_sheet(results);
  
          const colWidths = [
            { wch: 25 }, { wch: 15 }, { wch: 25 }, { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 20 }
          ];
          worksheet['!cols'] = colWidths;
  
          XLSX.utils.book_append_sheet(workbook, worksheet, 'Résultats Évaluations');
  
          const today = new Date().toISOString().split('T')[0];
          const filename = `evaluations_resultats_${today}.xlsx`;
          XLSX.writeFile(workbook, filename);
  
          this.showAlert('success', `Export Excel réussi ! ${results.length} enregistrements exportés.`);
          } catch (error) {
          console.error('Erreur lors de l\'export Excel:', error);
          this.showAlert('error', 'Erreur lors de l\'export Excel');
        } finally {
          this.exporting = false;
        }
      },
  
      async exportPDF() {
        try {
          this.exporting = true;
          this.showAlert('success', 'Génération du PDF en cours...');
  
          const response = await fetch(`${this.API_BASE_URL}?action=export_results&rand=${Math.random()}`);
          const data = await response.json();
  
          if (!data.success) {
            throw new Error(data.error || 'Erreur lors de l\'export');
          }
  
          const results = data.data;
          const { jsPDF } = await import('jspdf');
          const autoTable = (await import('jspdf-autotable')).default;
  
          const doc = new jsPDF();
  
          doc.setFontSize(20);
          doc.setTextColor(40, 40, 40);
          doc.text('RAPPORT ÉVALUATIONS', 105, 20, { align: 'center' });
  
          const today = new Date().toLocaleDateString('fr-FR', { 
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          });
          doc.setFontSize(10);
          doc.setTextColor(100, 100, 100);
          doc.text(`Généré le ${today}`, 105, 30, { align: 'center' });
  
          const tableData = results.map(result => [
            result.evaluation_titre || '',
            result.matricule || '',
            result.nom_complet || '',
            `${result.score}/${result.total_points}`,
            `${result.pourcentage}%`,
            result.temps_ecoule ? `${Math.floor(result.temps_ecoule / 60)}min` : '',
            result.date_completion || ''
          ]);
  
          autoTable(doc, {
            head: [['Évaluation', 'Matricule', 'Nom', 'Score', '%', 'Temps', 'Date']],
            body: tableData,
            startY: 45,
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
            }
          });
  
          const filename = `rapport_evaluations_${new Date().toISOString().split('T')[0]}.pdf`;
          doc.save(filename);
  
          this.showAlert('success', `Export PDF réussi ! ${results.length} enregistrements exportés.`);
          } catch (error) {
          console.error('Erreur lors de l\'export PDF:', error);
          this.showAlert('error', 'Erreur lors de l\'export PDF');
        } finally {
          this.exporting = false;
        }
      },
  
      // Nouvelles méthodes d'export pour évaluations individuelles
      async exportEvaluationExcel(evaluation) {
        try {
          this.exporting = true;
          this.showAlert('success', `Génération du fichier Excel pour "${evaluation.titre}" en cours...`);
  
          // Charger les résultats de l'évaluation
          const response = await fetch(`${this.API_BASE_URL}?action=resultats&evaluation_id=${evaluation.id}&rand=${Math.random()}`);
          const data = await response.json();
  
          if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement des données');
          }
  
          const evaluationData = data.data;
          const XLSX = await import('xlsx');
          const workbook = XLSX.utils.book_new();
  
          // Feuille 1: Statistiques de l'évaluation
          const statsData = [
            ['STATISTIQUES DE L\'ÉVALUATION'],
            [''],
            ['Titre', evaluation.titre],
            ['Description', evaluation.description || 'N/A'],
            ['Durée', `${evaluation.duree} minutes`],
            ['Type d\'accès', evaluation.acces_type === 'libre' ? 'Libre' : 'Restreint'],
            ['Nombre de questions', evaluation.nb_questions || 0],
            [''],
            ['STATISTIQUES GÉNÉRALES'],
            [''],
            ['Nombre de participants', evaluationData.total_participants || 0],
            ['Score moyen', `${evaluationData.moyenne || 0}`],
            ['Meilleur score', `${evaluationData.meilleur_score || 0}`],
            ['Taux de réussite (≥60%)', `${evaluationData.taux_reussite || 0}%`],
          ];
  
          const statsSheet = XLSX.utils.aoa_to_sheet(statsData);
          
          // Mise en forme de la feuille statistiques
          const statsColWidths = [{ wch: 25 }, { wch: 30 }];
          statsSheet['!cols'] = statsColWidths;
  
          XLSX.utils.book_append_sheet(workbook, statsSheet, 'Statistiques');
  
          // Feuille 2: Détails des participants
          if (evaluationData.participants && evaluationData.participants.length > 0) {
            const participantsData = evaluationData.participants.map(participant => ({
              'Matricule': participant.matricule,
              'Nom complet': `${participant.nom} ${participant.prenom}`,
              'Score obtenu': participant.score_obtenu,
              'Score total': participant.score_total,
              'Pourcentage': `${participant.score_pourcentage}%`,
              'Temps utilisé': this.formatTime(participant.temps_ecoule),
              'Date de completion': this.formatDate(participant.completed_at),
              'Statut': participant.score_pourcentage >= 60 ? 'Réussi' : 'Échoué'
            }));
  
            const participantsSheet = XLSX.utils.json_to_sheet(participantsData);
            
            const participantsColWidths = [
              { wch: 15 }, { wch: 25 }, { wch: 12 }, { wch: 12 }, 
              { wch: 12 }, { wch: 15 }, { wch: 20 }, { wch: 10 }
            ];
            participantsSheet['!cols'] = participantsColWidths;
  
            XLSX.utils.book_append_sheet(workbook, participantsSheet, 'Participants');
          }
  
          const today = new Date().toISOString().split('T')[0];
          const filename = `evaluation_${evaluation.titre.replace(/[^a-zA-Z0-9]/g, '_')}_${today}.xlsx`;
          XLSX.writeFile(workbook, filename);
  
          this.showAlert('success', `Export Excel réussi ! Évaluation "${evaluation.titre}" exportée.`);
        } catch (error) {
          console.error('Erreur lors de l\'export Excel de l\'évaluation:', error);
          this.showAlert('error', 'Erreur lors de l\'export Excel de l\'évaluation');
        } finally {
          this.exporting = false;
        }
      },
  
      async exportEvaluationPDF(evaluation) {
        try {
          this.exporting = true;
          this.showAlert('success', `Génération du PDF pour "${evaluation.titre}" en cours...`);
  
          // Charger les résultats de l'évaluation
          const response = await fetch(`${this.API_BASE_URL}?action=resultats&evaluation_id=${evaluation.id}&rand=${Math.random()}`);
          const data = await response.json();
  
          if (!data.success) {
            throw new Error(data.error || 'Erreur lors du chargement des données');
          }
  
          const evaluationData = data.data;
          const { jsPDF } = await import('jspdf');
          const autoTable = (await import('jspdf-autotable')).default;
  
          const doc = new jsPDF();
          let yPosition = 20;
  
          // En-tête du document
          doc.setFontSize(18);
          doc.setTextColor(40, 40, 40);
          doc.text('RAPPORT D\'ÉVALUATION', 105, yPosition, { align: 'center' });
          yPosition += 10;
  
          doc.setFontSize(14);
          doc.setTextColor(255, 140, 0);
          doc.text(evaluation.titre, 105, yPosition, { align: 'center' });
          yPosition += 15;
  
          // Informations générales
          doc.setFontSize(12);
          doc.setTextColor(40, 40, 40);
          doc.text('INFORMATIONS GÉNÉRALES', 20, yPosition);
          yPosition += 10;
  
          const infoData = [
            ['Durée', `${evaluation.duree} minutes`],
            ['Nombre de questions', (evaluation.nb_questions || 0).toString()],
            ['Nombre de participants', (evaluationData.total_participants || 0).toString()],
            ['Meilleur score', `${evaluationData.meilleur_score || 0}`],
            ['Taux de réussite (=+50%)', `${evaluationData.taux_reussite || 0}%`],
          ];
  
          autoTable(doc, {
            body: infoData,
            startY: yPosition,
            theme: 'plain',
            styles: { fontSize: 10 },
            columnStyles: {
              0: { fontStyle: 'bold', cellWidth: 40 },
              1: { cellWidth: 140 }
            }
          });
  
          yPosition = doc.lastAutoTable.finalY + 15;
  
          // Statistiques
  
          
  
          // Participants (si il y en a)
          if (evaluationData.participants && evaluationData.participants.length > 0) {
            // Nouvelle page si nécessaire
            if (yPosition > 250) {
              doc.addPage();
              yPosition = 20;
            }
  
            doc.setFontSize(12);
            doc.text('DÉTAILS DES PARTICIPANTS', 20, yPosition);
            yPosition += 10;
  
            const participantsTableData = evaluationData.participants.map(participant => [
              participant.matricule,
              `${participant.nom} ${participant.prenom}`,
              `${participant.score_obtenu}/${participant.score_total}`,
              `${participant.score_pourcentage}%`,
              this.formatTime(participant.temps_ecoule),
              participant.score_pourcentage >= 60 ? 'Réussi' : 'Échoué'
            ]);
  
            autoTable(doc, {
              head: [['Matricule', 'Nom', 'Score', '%', 'Temps', 'Statut']],
              body: participantsTableData,
              startY: yPosition,
              styles: { fontSize: 8, cellPadding: 2 },
              headStyles: {
                fillColor: [255, 140, 0],
                textColor: 255,
                fontStyle: 'bold'
              },
              alternateRowStyles: { fillColor: [248, 249, 250] },
              columnStyles: {
                0: { cellWidth: 25 },
                1: { cellWidth: 45 },
                2: { cellWidth: 20 },
                3: { cellWidth: 15 },
                4: { cellWidth: 20 },
                5: { cellWidth: 20 }
              }
            });
          }
  
          // Pied de page avec date de génération
          const pageCount = doc.internal.getNumberOfPages();
          for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(100, 100, 100);
            const today = new Date().toLocaleDateString('fr-FR', { 
              year: 'numeric',
              month: 'long',
              day: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
            });
            doc.text(`Généré le ${today} - Page ${i}/${pageCount}`, 105, 290, { align: 'center' });
          }
  
          const filename = `rapport_${evaluation.titre.replace(/[^a-zA-Z0-9]/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
          doc.save(filename);
  
          this.showAlert('success', `Export PDF réussi ! Rapport de "${evaluation.titre}" généré.`);
        } catch (error) {
          console.error('Erreur lors de l\'export PDF de l\'évaluation:', error);
          this.showAlert('error', 'Erreur lors de l\'export PDF de l\'évaluation');
        } finally {
          this.exporting = false;
        }
      },
  
      getStatutClass(statut) {
        switch (statut) {
          case 'active':
            return 'bg-green-100 text-green-800';
          case 'terminee':
            return 'bg-gray-100 text-gray-800';
          case 'brouillon':
            return 'bg-yellow-100 text-yellow-800';
          default:
            return 'bg-gray-100 text-gray-800';
        }
      },
  
      getStatutLabel(statut) {
        switch (statut) {
          case 'active':
            return 'Active';
          case 'terminee':
            return 'Terminée';
          case 'brouillon':
            return 'Brouillon';
          default:
            return statut;
        }
      },
  
      getScoreClass(pourcentage) {
  console.log('Avant conversion:', pourcentage, typeof pourcentage);
  pourcentage = parseFloat(pourcentage);
  console.log('Après conversion:', pourcentage, typeof pourcentage);

  if (pourcentage >= 80) return 'bg-green-100 text-green-800';
  if (pourcentage >= 60) return 'bg-yellow-100 text-yellow-800';
  if (pourcentage >= 40) return 'bg-orange-100 text-orange-800';
  return 'bg-red-100 text-red-800';
},
  
      formatTime(seconds) {
        if (!seconds) return 'N/A';
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}min ${remainingSeconds}s`;
      },
  
      formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
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
  