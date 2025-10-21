<template>
    <div class="flex-1 min-h-screen bg-gray-50">
      <!-- Breadcrumb -->
      <div class="px-6 py-4 flex items-center text-sm text-gray-500">
        <router-link to="/" class="hover:text-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </router-link>
        <span class="mx-2">/</span>
        <span class="font-medium text-gray-700">Dashboard Produits</span>
      </div>
  
      <!-- Header -->
      <div class="px-6 pb-4" style="padding-top: 20px;">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Produits</h1>
            <p class="mt-1 text-sm text-gray-500">Gérez votre inventaire et vos produits</p>
          </div>
          <div class="mt-4 md:mt-0 flex space-x-3">
            <div class="relative">
              <button 
                @click="showExportDropdown = !showExportDropdown"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
              >
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter
                <svg class="ml-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div v-if="showExportDropdown" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                <div class="py-1" role="menu" aria-orientation="vertical">
                  <button @click="exportToPDF" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    <svg class="w-4 h-4 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    Exporter en PDF
                  </button>
                  <button @click="exportToExcel" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    <svg class="w-4 h-4 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    Exporter en Excel
                  </button>
                </div>
              </div>
            </div>
            <button 
              @click="showAddProductModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Ajouter un produit
            </button>
            <div class="relative">
              <button 
                @click="showMoreActions = !showMoreActions"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
              >
                Plus d'actions
                <svg class="ml-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div v-if="showMoreActions" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                <div class="py-1" role="menu" aria-orientation="vertical">
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Importer des produits</a>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Générer un rapport</a>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Archiver les produits</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Loading State -->
      <div v-if="loading" class="px-6 pb-6">
        <div class="bg-white rounded-lg p-8 text-center shadow">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-orange border-t-transparent"></div>
          <p class="mt-4 text-gray-600 font-medium">Chargement des produits...</p>
        </div>
      </div>
  
      <!-- Error State -->
      <div v-if="hasError" class="px-6 pb-6">
        <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-6 shadow">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
              <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-red-800 font-medium">{{ error }}</p>
            </div>
            <button @click="refresh" class="ml-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
              Réessayer
            </button>
          </div>
        </div>
      </div>
  
      <!-- Stats Cards - Remplacer toute la section des cartes de statistiques -->
      <div class="px-6 pb-6" v-if="!loading">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
          <!-- Sélecteur de période -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-5 py-6">
              <div class="flex items-center justify-center mb-3">
                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
              </div>
              <p class="text-sm text-gray-600 text-center mb-2">Période</p>
              <select 
                v-model="selectedPeriod" 
                @change="updateStats"
                class="w-full text-sm font-semibold text-gray-900 bg-transparent border border-gray-200 rounded-md px-3 py-2 focus:ring-2 focus:ring-orange focus:border-orange cursor-pointer"
              >
                <option value="all">Tout</option>
                <option value="today">Aujourd'hui</option>
                <option value="week">Cette semaine</option>
                <option value="month">Ce mois</option>
              </select>
            </div>
          </div>

          <!-- Total Produits -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-5 py-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-md p-3">
                  <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Produits</dt>
                    <dd>
                      <div class="text-lg font-bold text-gray-900">{{ currentStats.total_products }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center justify-between">
                  <div class="text-xs text-gray-500">Brouillons: {{ detailedStats.draft }}</div>
                  <div class="flex items-center text-sm font-medium text-blue-600">
                    <svg class="self-center flex-shrink-0 h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1">{{ Math.round((currentStats.active_products / Math.max(currentStats.total_products, 1)) * 100) }}% actifs</span>
                  </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                  <div class="bg-blue-600 h-1.5 rounded-full" :style="`width: ${Math.round((currentStats.active_products / Math.max(currentStats.total_products, 1)) * 100)}%`"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Produits Actifs -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-5 py-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-50 rounded-md p-3">
                  <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Produits Actifs</dt>
                    <dd>
                      <div class="text-lg font-bold text-gray-900">{{ currentStats.active_products }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center justify-between">
                  <div class="text-xs text-gray-500">Prix moyen: {{ formatPrice(detailedStats.averagePrice) }}</div>
                  <div class="flex items-center text-sm font-medium text-green-600">
                    <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>En ligne</span>
                  </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                  <div class="bg-green-600 h-1.5 rounded-full" style="width: 85%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Stock Total -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-5 py-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-50 rounded-md p-3">
                  <svg class="h-6 w-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Stock Total</dt>
                    <dd>
                      <div class="text-lg font-bold text-gray-900">{{ formatNumber(currentStats.total_stock) }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center justify-between">
                  <div class="text-xs text-gray-500">
                    <span class="text-red-600">Rupture: {{ detailedStats.outOfStock }}</span> | 
                    <span class="text-yellow-600">Faible: {{ detailedStats.lowStock }}</span>
                  </div>
                  <div class="flex items-center text-sm font-medium" :class="detailedStats.outOfStock > 0 ? 'text-red-600' : detailedStats.lowStock > 0 ? 'text-yellow-600' : 'text-green-600'">
                    <svg v-if="detailedStats.outOfStock > 0" class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <svg v-else-if="detailedStats.lowStock > 0" class="self-center flex-shrink-0 h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg v-else class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1">{{ detailedStats.outOfStock > 0 ? 'Attention' : detailedStats.lowStock > 0 ? 'Surveiller' : 'Bon' }}</span>
                  </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                  <div 
                    :class="detailedStats.outOfStock > 0 ? 'bg-red-600' : detailedStats.lowStock > 0 ? 'bg-yellow-600' : 'bg-green-600'" 
                    class="h-1.5 rounded-full" 
                    :style="`width: ${Math.min(100, Math.max(10, (currentStats.total_stock / Math.max(currentStats.total_products * 10, 1)) * 100))}%`"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Vues et Ventes -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-5 py-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-50 rounded-md p-3">
                  <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Vues Totales</dt>
                    <dd>
                      <div class="text-lg font-bold text-gray-900">{{ formatNumber(currentStats.total_views) }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center justify-between">
                  <div class="text-xs text-gray-500">Ventes: {{ formatNumber(detailedStats.totalSales) }}</div>
                  <div class="flex items-center text-sm font-medium text-purple-600">
                    <svg class="self-center flex-shrink-0 h-5 w-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span class="ml-1">Performance</span>
                </div>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-purple-600 h-1.5 rounded-full" style="width: 65%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Filters and Table -->
      <div class="px-6 pb-6" v-if="!loading">
        <div class="bg-white shadow rounded-lg overflow-hidden" style="padding: 0px; margin: 10px -23px;">
          <!-- Filter Tabs -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <ul class="flex flex-wrap gap-2 text-sm font-medium">
              <li v-for="tab in filterTabs" :key="tab.value">
                <button 
                  @click="handleFilterChange(tab.value)"
                  :class="[
                    'px-4 py-2 rounded-lg transition-all duration-200',
                    activeFilter === tab.value 
                      ? 'bg-orange text-white shadow-lg' 
                      : 'text-gray-600 hover:text-orange hover:bg-orange-50'
                  ]"
                >
                  {{ tab.label }} ({{ filterCounts[tab.value] }})
                </button>
              </li>
            </ul>
          </div>
  
          <!-- Search and Filters -->
          <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange focus:border-orange transition-all" 
                  placeholder="Rechercher un produit..."
                >
              </div>
              <div class="flex items-center gap-3">
                <button type="button" class="p-2 text-gray-500 bg-gray-50 border border-gray-300 rounded-md hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 transition-all">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                  </svg>
                </button>
                <button type="button" class="flex items-center gap-2 px-4 py-2 text-gray-500 bg-gray-50 border border-gray-300 rounded-md hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 transition-all">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                  </svg>
                  Filtrer
                </button>
              </div>
            </div>
          </div>
  
          <!-- Products Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center">
                      <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-orange bg-gray-100 border-gray-300 rounded focus:ring-orange focus:ring-2">
                      <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Couleur(s)</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taille(s)</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50 transition-colors">
                  <td class="w-4 px-6 py-4">
                    <div class="flex items-center">
                      <input :id="`checkbox-table-search-${product.id}`" type="checkbox" class="w-4 h-4 text-orange bg-gray-100 border-gray-300 rounded focus:ring-orange focus:ring-2">
                      <label :for="`checkbox-table-search-${product.id}`" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-3">
                      <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                        <img v-if="product.primary_image" :src="product.primary_image" :alt="product.name" class="w-full h-full object-cover">
                        <span v-else class="text-xl">{{ product.icon || '📦' }}</span>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                        <div class="text-sm text-gray-500">{{ product.sku }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                      <div class="text-sm text-gray-900">{{ product.category_name || 'Non catégorisé' }}</div>
                      <div class="text-sm text-gray-500">{{ product.subcategory_name || 'Non sous-catégorisé' }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ formatPrice(product.unit_price) }}</div>
                      <div v-if="product.wholesale_price" class="text-sm text-gray-500">
                        Gros: {{ formatPrice(product.wholesale_price) }} ({{ product.wholesale_min_qty }}+)
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ product.stock }}</div>
                    <div :class="getStockStatusClass(product.stock)" class="text-xs">
                      {{ getStockStatus(product.stock) }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusBadgeClass(product.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ product.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap" style="width: 80px;">
                    <div class="flex flex-wrap gap-1" style="width: 80px;">
                      <div 
                        v-for="(color, index) in product.colors" 
                        :key="index" 
                        class="w-6 h-6 rounded-lg border border-gray-200 cursor-pointer hover:scale-110 transition-transform shadow-sm" 
                        :style="{ backgroundColor: color }"
                        :title="getColorName(color)"
                      ></div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap" style="width: 80px;">
                    <div class="flex flex-wrap gap-1" style="width: 80px;">
                      <div 
                        v-for="(size, index) in product.sizes" 
                        :key="index" 
                        class="w-auto min-w-[28px] h-7 px-1.5 rounded-lg border border-gray-300 bg-gray-50 flex items-center justify-center text-xs font-medium text-gray-700 cursor-pointer hover:bg-gray-100 transition-colors"
                      >
                        {{ size }}
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-500">Ventes: {{ product.sales_count || 0 }}</span>
                      <span class="text-sm text-gray-500">Vues: {{ product.views_count || 0 }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-3">
                      <button @click="viewProduct(product)" class="text-gray-600 hover:text-gray-900">
                        Détails
                      </button>
                      <div class="relative">
                        <button @click="toggleActionMenu(product.id)" class="text-gray-400 hover:text-gray-500">
                          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                          </svg>
                        </button>
                        <div v-if="activeActionMenu === product.id" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                          <div class="py-1" role="menu" aria-orientation="vertical">
                            <button @click="viewProduct(product)" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Voir détails</button>
                            <button @click="editProduct(product)" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</button>
                            <button @click="duplicateProduct(product)" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dupliquer</button>
                            <button @click="deleteProductAction(product.id)" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Supprimer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
  
          <!-- Pagination -->
          <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
              <button 
                @click="handlePageChange(pagination.current_page - 1)"
                :disabled="isFirstPage"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Précédent
              </button>
              <button 
                @click="handlePageChange(pagination.current_page + 1)"
                :disabled="isLastPage"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Suivant
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Éléments par page
                  <select 
                    @change="handleItemsPerPageChange($event.target.value)"
                    class="ml-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange focus:border-orange p-1.5"
                  >
                    <option value="10" :selected="pagination.per_page === 10">10</option>
                    <option value="25" :selected="pagination.per_page === 25">25</option>
                    <option value="50" :selected="pagination.per_page === 50">50</option>
                  </select>
                  <span class="ml-4">
                    Affichage {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} à 
                    {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} 
                    sur {{ pagination.total }} résultats
                  </span>
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button 
                    @click="handlePageChange(pagination.current_page - 1)"
                    :disabled="isFirstPage"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span class="sr-only">Précédent</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <button 
                    v-for="page in Math.min(5, pagination.total_pages)" 
                    :key="page"
                    @click="handlePageChange(page)"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors',
                      pagination.current_page === page 
                        ? 'z-10 bg-orange border-orange text-white' 
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                  <button 
                    @click="handlePageChange(pagination.current_page + 1)"
                    :disabled="isLastPage"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span class="sr-only">Suivant</span>
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
  
      <!-- Modales -->
      <AddProductModal 
        v-if="showAddProductModal" 
        :boutique-id="currentBoutique?.id"
        :user-id="currentUser?.id"
        @close="showAddProductModal = false"
        @save="handleAddProduct"
      />
  
      <ViewProductModal 
        v-if="showViewModal" 
        :product="selectedProduct"
        @close="showViewModal = false"
      />
  
      <EditProductModal 
        v-if="showEditModal" 
        :product="selectedProduct"
        :boutique-id="currentBoutique?.id"
        :user-id="currentUser?.id"
        @close="showEditModal = false"
        @save="handleEditProduct"
      />
  
      <!-- Notification Toast -->
      <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-lg shadow-2xl transition-all duration-300 transform" :class="{'opacity-0 translate-y-2': !notification.show}">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          {{ notification.message }}
        </div>
      </div>
    </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
  import { productsApi } from '../../services/api'
  import AddProductModal from './AddProductModal.vue'
  import ViewProductModal from './ViewProductModal.vue'
  import EditProductModal from './EditProductModal.vue'
  
  // États réactifs (identiques à l'original)
  const products = ref([])
  const loading = ref(false)
  const error = ref('')
  const pagination = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    total_pages: 0
  })
  
  const currentUser = ref(null)
  const currentBoutique = ref(null)
  
  const showAddProductModal = ref(false)
  const showViewModal = ref(false)
  const showEditModal = ref(false)
  const showExportDropdown = ref(false)
  const showMoreActions = ref(false)
  const selectedProduct = ref(null)
  const activeActionMenu = ref(null)
  const searchQuery = ref('')
  const activeFilter = ref('all')
  const selectedPeriod = ref('all')
  const notification = ref({ show: false, message: '' })
  
  const stats = ref({
    all: { total_products: 0, active_products: 0, total_views: 0, total_stock: 0 },
    today: { total_products: 0, active_products: 0, total_views: 0, total_stock: 0 },
    week: { total_products: 0, active_products: 0, total_views: 0, total_stock: 0 },
    month: { total_products: 0, active_products: 0, total_views: 0, total_stock: 0 }
  })
  
  // Remplacer le computed currentStats par celui-ci :
  const currentStats = computed(() => {
    if (!products.value || products.value.length === 0) {
      return { total_products: 0, active_products: 0, total_views: 0, total_stock: 0 }
    }

    // Calculer les vraies statistiques à partir des données des produits
    const totalProducts = products.value.length
    const activeProducts = products.value.filter(product => product.status === 'Actif').length
    const totalStock = products.value.reduce((sum, product) => sum + (product.stock || 0), 0)
    const totalViews = products.value.reduce((sum, product) => sum + (product.views_count || 0), 0)

    return {
      total_products: totalProducts,
      active_products: activeProducts,
      total_stock: totalStock,
      total_views: totalViews
    }
  })

  // Ajouter ces nouveaux computed pour des statistiques plus détaillées
  const detailedStats = computed(() => {
    if (!products.value || products.value.length === 0) {
      return {
        lowStock: 0,
        outOfStock: 0,
        draft: 0,
        totalSales: 0,
        averagePrice: 0
      }
    }

    const lowStock = products.value.filter(product => product.stock > 0 && product.stock <= 5).length
    const outOfStock = products.value.filter(product => product.stock === 0).length
    const draft = products.value.filter(product => product.status === 'Brouillon').length
    const totalSales = products.value.reduce((sum, product) => sum + (product.sales_count || 0), 0)
    
    const totalPrice = products.value.reduce((sum, product) => sum + (product.unit_price || 0), 0)
    const averagePrice = products.value.length > 0 ? totalPrice / products.value.length : 0

    return {
      lowStock,
      outOfStock,
      draft,
      totalSales,
      averagePrice
    }
  })
  
  const filterTabs = [
    { value: 'all', label: 'Tous' },
    { value: 'active', label: 'Actifs' },
    { value: 'low-stock', label: 'Stock faible' },
    { value: 'out-of-stock', label: 'Rupture' },
    { value: 'draft', label: 'Brouillons' }
  ]
  
  // Computed properties (identiques à l'original)
  const filteredProducts = computed(() => {
    if (!products.value) return []
  
    let filtered = [...products.value]
  
    if (activeFilter.value !== 'all') {
      const statusMap = {
        'active': 'Actif',
        'low-stock': 'Stock faible', 
        'out-of-stock': 'Rupture',
        'draft': 'Brouillon'
      }
      const targetStatus = statusMap[activeFilter.value]
      if (targetStatus) {
        filtered = filtered.filter(product => product.status === targetStatus)
      }
    }
  
    if (searchQuery.value.trim()) {
      const query = searchQuery.value.toLowerCase()
      filtered = filtered.filter(product => 
        product.name.toLowerCase().includes(query) ||
        product.sku.toLowerCase().includes(query) ||
        product.description?.toLowerCase().includes(query)
      )
    }
  
    return filtered
  })
  
  const filterCounts = computed(() => {
    if (!products.value) return { all: 0, active: 0, 'low-stock': 0, 'out-of-stock': 0, draft: 0 }
  
    return {
      'all': products.value.length,
      'active': products.value.filter(product => product.status === 'Actif').length,
      'low-stock': products.value.filter(product => product.status === 'Stock faible').length,
      'out-of-stock': products.value.filter(product => product.status === 'Rupture').length,
      'draft': products.value.filter(product => product.status === 'Brouillon').length
    }
  })
  
  const hasError = computed(() => !!error.value)
  const isFirstPage = computed(() => pagination.value.current_page <= 1)
  const isLastPage = computed(() => pagination.value.current_page >= pagination.value.total_pages)
  
  // Fonctions utilitaires (identiques à l'original)
  const getStatusBadgeClass = (status) => {
    switch (status) {
      case 'Actif':
        return 'bg-green-100 text-green-800'
      case 'Stock faible':
        return 'bg-yellow-100 text-yellow-800'
      case 'Rupture':
        return 'bg-red-100 text-red-800'
      case 'Brouillon':
        return 'bg-gray-100 text-gray-800'
      default:
        return 'bg-gray-100 text-gray-800'
    }
  }
  
  const getStockStatus = (stock) => {
    if (stock === 0) return 'Rupture'
    if (stock <= 5) return 'Stock faible'
    return 'En stock'
  }
  
  const getStockStatusClass = (stock) => {
    if (stock === 0) return 'text-red-600'
    if (stock <= 5) return 'text-yellow-600'
    return 'text-green-600'
  }
  
  const getColorName = (color) => {
    const colorMap = {
      '#000000': 'Noir', '#FFFFFF': 'Blanc', '#C0C0C0': 'Argent',
      '#FF0000': 'Rouge', '#0000FF': 'Bleu', '#008000': 'Vert',
      '#FFFF00': 'Jaune', '#FFA500': 'Orange', '#FFC0CB': 'Rose',
      '#800080': 'Violet', '#FFD700': 'Or', '#8B4513': 'Marron'
    }
    return colorMap[color] || color
  }
  
  const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })
    .format(price || 0)
    .replace(/\s/g, ' '); // remplace tout type d’espace (y compris insécables)
    }
  
  const formatNumber = (number) => {
    return new Intl.NumberFormat('fr-FR').format(number || 0)
  }
  
  const formatDate = (dateString) => {
    if (!dateString) return ''
    return new Date(dateString).toLocaleDateString('fr-FR')
  }
  
  // Remarque: Nous utilisons l'import dynamique dans les fonctions pour optimiser le chargement
  // Aucun import statique n'est nécessaire ici
  
  // Toutes les autres fonctions restent identiques à l'original
  const initializeUserData = () => {
    try {
      const userData = localStorage.getItem('user')
      if (userData) {
        const user = JSON.parse(userData)
        currentUser.value = user
        
        if (user.boutiques && user.boutiques.length > 0) {
          currentBoutique.value = user.boutiques[0]
        }
      }
    } catch (err) {
      console.error('Erreur lors de la récupération des données utilisateur:', err)
      error.value = 'Erreur d\'authentification. Veuillez vous reconnecter.'
    }
  }
  
  const fetchProducts = async () => {
    if (!currentBoutique.value?.id || !currentUser.value?.id) {
      error.value = 'Informations utilisateur manquantes'
      return
    }
  
    try {
      loading.value = true
      error.value = ''
      
      const response = await productsApi.getProducts({
        boutique_id: currentBoutique.value.id,
        user_id: currentUser.value.id,
        page: pagination.value.current_page,
        limit: pagination.value.per_page,
        search: searchQuery.value.trim() || undefined
      })
      
      if (response.success) {
        products.value = response.data || []
        pagination.value = response.pagination || pagination.value
      } else {
        error.value = response.error || 'Erreur lors du chargement des produits'
      }
    } catch (err) {
      console.error('Erreur fetchProducts:', err)
      error.value = 'Erreur de connexion au serveur'
    } finally {
      loading.value = false
    }
  }
  
  const loadStats = async (period = 'all') => {
    if (!currentBoutique.value?.id) return
  
    try {
      const response = await productsApi.getStats({
        boutique_id: currentBoutique.value.id,
        period
      })
      
      if (response.success) {
        stats.value[period] = response.data
      }
    } catch (err) {
      console.error('Erreur chargement stats:', err)
    }
  }
  
  const updateStats = async () => {
    await loadStats(selectedPeriod.value)
  }
  
  const toggleActionMenu = (productId) => {
    activeActionMenu.value = activeActionMenu.value === productId ? null : productId
  }
  
  const viewProduct = (product) => {
    selectedProduct.value = product
    showViewModal.value = true
    activeActionMenu.value = null
  }
  
  const editProduct = (product) => {
    selectedProduct.value = product
    showEditModal.value = true
    activeActionMenu.value = null
  }
  
  const duplicateProduct = async (product) => {
    try {
      const duplicatedData = {
        name: product.name + ' (Copie)',
        description: product.description,
        category_id: product.category_id,
        subcategory_id: product.subcategory_id,
        unit_price: product.unit_price,
        wholesale_price: product.wholesale_price,
        wholesale_min_qty: product.wholesale_min_qty,
        stock: 0,
        colors: product.colors || [],
        sizes: product.sizes || [],
        is_active: false
      }
      
      const response = await productsApi.createProduct(duplicatedData, {
        boutique_id: currentBoutique.value.id,
        user_id: currentUser.value.id
      })
      
      if (response.success) {
        await fetchProducts()
        showNotification('Produit dupliqué avec succès!')
      } else {
        showNotification('Erreur lors de la duplication: ' + response.error)
      }
    } catch (err) {
      showNotification('Erreur lors de la duplication: ' + err.message)
    }
    activeActionMenu.value = null
  }
  
  const deleteProductAction = async (productId) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
      try {
        const response = await productsApi.deleteProduct(productId, {
          boutique_id: currentBoutique.value.id,
          user_id: currentUser.value.id
        })
        
        if (response.success) {
          await fetchProducts()
          await loadStats(selectedPeriod.value)
          showNotification('Produit supprimé avec succès!')
        } else {
          showNotification('Erreur lors de la suppression: ' + response.error)
        }
      } catch (err) {
        showNotification('Erreur lors de la suppression: ' + err.message)
      }
    }
    activeActionMenu.value = null
  }
  
  const handleFilterChange = async (filterValue) => {
    activeFilter.value = filterValue
    pagination.value.current_page = 1
    await fetchProducts()
  }
  
  const handlePageChange = async (page) => {
    if (page >= 1 && page <= pagination.value.total_pages) {
      pagination.value.current_page = page
      await fetchProducts()
    }
  }
  
  const handleItemsPerPageChange = async (itemsPerPage) => {
    pagination.value.per_page = parseInt(itemsPerPage)
    pagination.value.current_page = 1
    await fetchProducts()
  }
  
  async function exportToPDF() {
  try {
    showExportDropdown.value = false
    showNotification('Export PDF en cours...')
    
    // Importer jsPDF et autoTable dynamiquement
    const { jsPDF } = await import('jspdf')
    const { default: autoTable } = await import('jspdf-autotable')
    
    const doc = new jsPDF()
    
    // Ajouter le logo Bazaar en haut à gauche
    try {
      // Vous pouvez remplacer cette URL par le chemin vers votre logo
      const logoUrl = '/placeholder.svg?height=40&width=120&text=BAZAAR'
      
      // Créer une image pour le logo
      const img = new Image()
      img.crossOrigin = 'anonymous'
      
      await new Promise((resolve, reject) => {
        img.onload = () => {
          // Ajouter le logo (position x: 14, y: 10, largeur: 30, hauteur: 12)
          doc.addImage(img, 'PNG', 14, 10, 30, 12)
          resolve()
        }
        img.onerror = () => {
          // Si le logo ne peut pas être chargé, on continue sans
          console.warn('Logo non trouvé, continuation sans logo')
          resolve()
        }
        img.src = logoUrl
      })
    } catch (error) {
      console.warn('Erreur lors du chargement du logo:', error)
    }
    
    // Ajouter le titre et les informations (décalés vers le bas pour laisser place au logo)
    doc.setFontSize(18)
    doc.setTextColor(246, 90, 17) // Couleur orange
    doc.text('Liste des Produits', 14, 32)
    
    doc.setFontSize(11)
    doc.setTextColor(0, 0, 0) // Retour au noir
    doc.text(`Date d'exportation: ${new Date().toLocaleDateString('fr-FR')}`, 14, 40)
    doc.text(`Boutique: ${currentBoutique.value?.name || 'N/A'}`, 14, 46)
    doc.text(`Filtre: ${filterTabs.find(tab => tab.value === activeFilter.value)?.label || 'Tous'}`, 14, 52)
    doc.text(`Nombre de produits: ${filteredProducts.value.length}`, 14, 58)
    
    // Préparer les données pour le tableau
    const tableData = filteredProducts.value.map(product => [
      product.name,
      product.category_name || 'Non catégorisé',
      `${formatPrice(product.unit_price)}`,
      product.stock,
      product.status,
      (product.colors || []).length > 0 ? `${product.colors.length} couleur(s)` : 'Aucune',
      (product.sizes || []).length > 0 ? product.sizes.join(', ') : 'Unique'
    ])
    
    // Générer le tableau
    autoTable(doc, {
      head: [['Produit', 'Catégorie', 'Prix', 'Stock', 'Statut', 'Couleurs', 'Tailles']],
      body: tableData,
      startY: 65,
      styles: { 
        fontSize: 9, 
        cellPadding: 3,
        lineColor: [200, 200, 200],
        lineWidth: 0.1
      },
      headStyles: { 
        fillColor: [246, 90, 17],
        textColor: [255, 255, 255],
        fontStyle: 'bold'
      },
      alternateRowStyles: {
        fillColor: [248, 249, 250]
      },
      margin: { top: 65 }
    })
    
    // Ajouter les statistiques
    const finalY = doc.lastAutoTable.finalY || 70
    
    doc.setFontSize(14)
    doc.setTextColor(246, 90, 17)
    doc.text('Statistiques', 14, finalY + 15)
    
    autoTable(doc, {
      head: [['Métrique', 'Valeur']],
      body: [
        ['Total Produits', (currentStats.value.total_products || 0).toString()],
        ['Produits Actifs', (currentStats.value.active_products || 0).toString()],
        ['Stock Total', formatNumber(currentStats.value.total_stock || 0)],
        ['Vues Totales', formatNumber(currentStats.value.total_views || 0)]
      ],
      startY: finalY + 20,
      styles: { 
        fontSize: 10,
        cellPadding: 4
      },
      headStyles: { 
        fillColor: [246, 90, 17],
        textColor: [255, 255, 255],
        fontStyle: 'bold'
      },
      columnStyles: {
        0: { fontStyle: 'bold' },
        1: { halign: 'right' }
      }
    })
    
    // Ajouter le footer sur toutes les pages
    const pageCount = doc.internal.getNumberOfPages()
    
    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i)
      
      // Ligne de séparation du footer
      doc.setDrawColor(200, 200, 200)
      doc.setLineWidth(0.5)
      doc.line(14, 280, 196, 280)
      
      // Texte du footer
      doc.setFontSize(9)
      doc.setTextColor(100, 100, 100)
      
      // Footer gauche - informations du document
      doc.text('Document généré par Ali Adjamé', 14, 287)
      doc.text('Site web: https://ali-adjame.com', 14, 292)
      
      // Footer centre - date et heure
      const now = new Date()
      const dateTime = `${now.toLocaleDateString('fr-FR')} à ${now.toLocaleTimeString('fr-FR')}`
      doc.text(`Généré le ${dateTime}`, 105, 287, { align: 'center' })
      
      // Footer droit - numéro de page
      doc.text(`Page ${i} sur ${pageCount}`, 196, 287, { align: 'right' })
      
      // Petite note de confidentialité
      doc.setFontSize(8)
      doc.text('Document confidentiel - Usage interne uniquement', 105, 292, { align: 'center' })
    }
    
    // Enregistrer le PDF
    const fileName = `produits-${currentBoutique.value?.name || 'boutique'}-${new Date().toISOString().split('T')[0]}.pdf`
    doc.save(fileName)
    showNotification('Export PDF terminé avec succès!')
  } catch (error) {
    console.error('Erreur lors de l\'export PDF:', error)
    showNotification('Erreur lors de l\'export PDF. Veuillez réessayer.')
  }
}

  async function exportToExcel() {
    try {
      showExportDropdown.value = false
      showNotification('Export Excel en cours...')
      
      // Importer xlsx dynamiquement
      const XLSX = await import('xlsx')
      
      // Préparer les données pour le fichier Excel
      const excelData = filteredProducts.value.map(product => ({
        'Nom du produit': product.name,
        'SKU': product.sku,
        'Catégorie': product.category_name || 'Non catégorisé',
        'Sous-catégorie': product.subcategory_name || 'Non sous-catégorisé',
        'Prix unitaire': product.unit_price,
        'Prix de gros': product.wholesale_price || '',
        'Quantité min. gros': product.wholesale_min_qty || '',
        'Stock': product.stock,
        'Statut': product.status,
        'Couleurs': (product.colors || []).length > 0 ? product.colors.join(', ') : 'Aucune',
        'Tailles': (product.sizes || []).length > 0 ? product.sizes.join(', ') : 'Unique',
        'Ventes': product.sales_count || 0,
        'Vues': product.views_count || 0,
        'Date de création': formatDate(product.created_at)
      }))
      
      // Créer une feuille de calcul
      const worksheet = XLSX.utils.json_to_sheet(excelData)
      
      // Ajuster la largeur des colonnes
      const columnWidths = [
        { wch: 30 }, // Nom du produit
        { wch: 15 }, // SKU
        { wch: 20 }, // Catégorie
        { wch: 20 }, // Sous-catégorie
        { wch: 15 }, // Prix unitaire
        { wch: 15 }, // Prix de gros
        { wch: 15 }, // Quantité min. gros
        { wch: 10 }, // Stock
        { wch: 15 }, // Statut
        { wch: 20 }, // Couleurs
        { wch: 20 }, // Tailles
        { wch: 10 }, // Ventes
        { wch: 10 }, // Vues
        { wch: 15 }  // Date de création
      ]
      worksheet['!cols'] = columnWidths
      
      // Créer un classeur et y ajouter la feuille
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Produits')
      
      // Créer une feuille pour les statistiques
      const statsData = [
        { 'Métrique': 'Total Produits', 'Valeur': currentStats.value.total_products || 0 },
        { 'Métrique': 'Produits Actifs', 'Valeur': currentStats.value.active_products || 0 },
        { 'Métrique': 'Stock Total', 'Valeur': currentStats.value.total_stock || 0 },
        { 'Métrique': 'Vues Totales', 'Valeur': currentStats.value.total_views || 0 }
      ]
      const statsSheet = XLSX.utils.json_to_sheet(statsData)
      XLSX.utils.book_append_sheet(workbook, statsSheet, 'Statistiques')
      
      // Générer le fichier Excel et le télécharger
      XLSX.writeFile(workbook, `produits-${new Date().toISOString().split('T')[0]}.xlsx`)
      showNotification('Export Excel terminé avec succès!')
    } catch (error) {
      console.error('Erreur lors de l\'export Excel:', error)
      showNotification('Erreur lors de l\'export Excel. Veuillez réessayer.')
    }
  }

  const handleAddProduct = async (productData) => {
    try {
      const response = await productsApi.createProduct(productData, {
        boutique_id: currentBoutique.value.id,
        user_id: currentUser.value.id
      })
      
      if (response.success) {
        showAddProductModal.value = false
        await fetchProducts()
        await loadStats(selectedPeriod.value)
        showNotification('Produit ajouté avec succès!')
      } else {
        showNotification('Erreur lors de l\'ajout: ' + response.error)
      }
    } catch (err) {
      showNotification('Erreur lors de l\'ajout: ' + err.message)
    }
  }
  
  const handleEditProduct = async (productData) => {
    try {
      const response = await productsApi.updateProduct(productData.id, productData, {
        boutique_id: currentBoutique.value.id,
        user_id: currentUser.value.id
      })
      
      if (response.success) {
        showEditModal.value = false
        await fetchProducts()
        await loadStats(selectedPeriod.value)
        showNotification('Produit modifié avec succès!')
      } else {
        showNotification('Erreur lors de la modification: ' + response.error)
      }
    } catch (err) {
      showNotification('Erreur lors de la modification: ' + err.message)
    }
  }
  
  const showNotification = (message) => {
    notification.value.message = message
    notification.value.show = true
    setTimeout(() => {
      notification.value.show = false
    }, 3000)
  }
  
  const refresh = async () => {
    await fetchProducts()
    await loadStats(selectedPeriod.value)
  }
  
  const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
      showExportDropdown.value = false
      showMoreActions.value = false
      activeActionMenu.value = null
    }
  }
  
  // Watchers
  watch(searchQuery, () => {
    clearTimeout(searchQuery.timeoutId)
    searchQuery.timeoutId = setTimeout(() => {
      pagination.value.current_page = 1
      fetchProducts()
    }, 500)
  })
  
  watch(selectedPeriod, updateStats)
  
  // Lifecycle hooks
  onMounted(async () => {
    document.addEventListener('click', handleClickOutside)
  
    initializeUserData()
  
    if (!currentUser.value || !currentBoutique.value) {
      error.value = 'Informations d\'authentification manquantes. Veuillez vous reconnecter.'
      return
    }
  
    await Promise.all([
      fetchProducts(),
      loadStats('all'),
      loadStats('today'),
      loadStats('week'), 
      loadStats('month')
    ])
  })
  
  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
  })
  </script>
  
  <style scoped>
  .bg-orange {
    background-color: #F65A11;
  }
  
  .hover\:bg-orange-600:hover {
    background-color: #e54a0a;
  }
  
  .focus\:ring-orange:focus {
    --tw-ring-color: rgba(246, 90, 17, 0.5);
  }
  
  .focus\:border-orange:focus {
    border-color: #F65A11;
  }
  
  .focus\:ring-orange-500:focus {
    --tw-ring-color: rgba(246, 90, 17, 0.5);
  }
  
  .focus\:border-orange-500:focus {
    border-color: #F65A11;
  }
  
  .text-orange {
    color: #F65A11;
  }
  
  .border-orange {
    border-color: #F65A11;
  }
  
  .hover\:bg-orange-50:hover {
    background-color: rgba(246, 90, 17, 0.05);
  }
  
  .bg-orange-50 {
    background-color: rgba(246, 90, 17, 0.05);
  }
  
  /* Transitions fluides */
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }
  
  .transition-colors {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }
  
  .transition-transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }
  
  .hover\:scale-110:hover {
    transform: scale(1.1);
  }
  </style>
