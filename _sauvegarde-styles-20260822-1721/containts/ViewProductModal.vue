<template>
    <div 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click="handleBackdropClick"
    >
      <div 
        class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[95vh] overflow-hidden"
        @click.stop
      >
        <!-- Header moderne -->
        <div class="relative bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-orange to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Détails du produit</h2>
                <p class="text-sm text-gray-500 mt-1">Informations complètes et médias</p>
              </div>
            </div>
            <button 
              @click="$emit('close')" 
              class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all duration-200"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
  
          <!-- Onglets modernes -->
          <div class="mt-6">
            <nav class="flex space-x-1 bg-white rounded-xl p-1 shadow-sm">
              <button
                @click="activeTab = 'details'"
                :class="[
                  'flex-1 flex items-center justify-center space-x-2 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200',
                  activeTab === 'details'
                    ? 'bg-orange text-white shadow-lg transform scale-[1.02]'
                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                ]"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Détails du produit</span>
              </button>
              <button
                @click="activeTab = 'media'"
                :class="[
                  'flex-1 flex items-center justify-center space-x-2 py-3 px-4 rounded-lg font-medium text-sm transition-all duration-200',
                  activeTab === 'media'
                    ? 'bg-orange text-white shadow-lg transform scale-[1.02]'
                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                ]"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Galerie & Médias</span>
                <span v-if="mediaCount > 0" class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full font-medium">
                  {{ mediaCount }}
                </span>
              </button>
            </nav>
          </div>
        </div>
  
        <!-- Contenu avec scroll -->
        <div class="overflow-y-auto max-h-[calc(95vh-200px)]">
          <div v-if="product" class="p-8">
            <!-- Onglet 1: Détails du produit -->
            <div v-if="activeTab === 'details'" class="space-y-8">
              <!-- Hero Section avec image principale -->
              <div class="bg-gradient-to-br from-white-50 to-white-100 rounded-2xl p-8 shadow-inner">
                <div class="flex flex-col lg:flex-row items-start space-y-6 lg:space-y-0 lg:space-x-8">
                  <!-- Image principale -->
                  <div class="w-full lg:w-80 flex-shrink-0">
                    <div class="relative group">
                      <div class="w-full h-80 bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                        <img 
                          v-if="primaryImage" 
                          :src="primaryImage" 
                          :alt="product.name" 
                          class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-105"
                          @click="openImageModal"
                        >
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                          <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                          <span class="text-lg font-medium">Aucune image</span>
                        </div>
                      </div>
                      <!-- Badge image count -->
                      <div v-if="product.images && product.images.length > 1" class="absolute -top-3 -right-3 bg-orange text-white text-sm px-3 py-1 rounded-full font-medium shadow-lg">
                        +{{ product.images.length - 1 }}
                      </div>
                    </div>
                  </div>
  
                  <!-- Informations principales -->
                  <div class="flex-1 space-y-6">
                    <div>
                      <h3 class="text-3xl font-bold text-gray-900 mb-3">{{ product.name }}</h3>
                      <div class="flex items-center space-x-4 mb-4">
                        <span class="text-lg text-gray-600 bg-gray-100 px-3 py-1 rounded-lg font-mono">{{ product.sku }}</span>
                        <span :class="getStatusBadgeClass(product.status)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                          <div class="w-2 h-2 rounded-full mr-2" :class="getStatusDotClass(product.status)"></div>
                          {{ product.status }}
                        </span>
                      </div>
                    </div>
  
                    <!-- Prix et stock -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <h4 class="text-sm font-medium text-gray-500 mb-2">Prix de vente</h4>
                          <div class="text-3xl font-bold text-orange">
                            {{ formatPrice(product.unit_price) }} <span class="text-lg text-gray-500">FCFA</span>
                          </div>
                          <div v-if="product.wholesale_price" class="mt-2 text-gray-600">
                            <span class="text-sm">Prix de gros: </span>
                            <span class="font-semibold">{{ formatPrice(product.wholesale_price) }} FCFA</span>
                            <span class="text-sm text-gray-500"> ({{ product.wholesale_min_qty }}+ unités)</span>
                          </div>
                        </div>
                        <div>
                          <h4 class="text-sm font-medium text-gray-500 mb-2">Stock disponible</h4>
                          <div class="flex items-center space-x-3">
                            <span class="text-3xl font-bold text-gray-900">{{ product.stock }}</span>
                            <div>
                              <div class="text-sm text-gray-500">unités</div>
                              <div :class="getStockStatusClass(product.stock)" class="text-sm font-medium">
                                {{ getStockStatus(product.stock) }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
  
                    <!-- Performance -->
                    <div class="grid grid-cols-2 gap-4">
                      <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ product.sales_count || 0 }}</div>
                        <div class="text-sm text-blue-600 font-medium">Ventes</div>
                      </div>
                      <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ product.views_count || 0 }}</div>
                        <div class="text-sm text-purple-600 font-medium">Vues</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Description -->
              <div v-if="product.description" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  Description
                </h4>
                <p class="text-gray-700 leading-relaxed text-lg">{{ product.description }}</p>
              </div>
  
              <!-- Grille d'informations -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Catégorie -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                  <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Catégorie
                  </h4>
                  <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                      <span class="text-gray-600">Catégorie principale</span>
                      <span class="font-semibold text-gray-900">{{ product.category_name || 'Non définie' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                      <span class="text-gray-600">Sous-catégorie</span>
                      <span class="font-semibold text-gray-900">{{ product.subcategory_name || 'Non définie' }}</span>
                    </div>
                  </div>
                </div>
  
                <!-- Informations système -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                  <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informations
                  </h4>
                  <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                      <span class="text-gray-600">Créé le</span>
                      <span class="font-semibold text-gray-900">{{ formatDate(product.created_at) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                      <span class="text-gray-600">Modifié le</span>
                      <span class="font-semibold text-gray-900">{{ formatDate(product.updated_at) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                      <span class="text-gray-600">Créé par</span>
                      <span class="font-semibold text-gray-900">{{ product.created_by_name || 'Inconnu' }}</span>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Couleurs et tailles -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Couleurs -->
                <div v-if="product.colors && product.colors.length > 0" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                  <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                    </svg>
                    Couleurs disponibles
                    <span class="ml-2 text-sm bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ product.colors.length }}</span>
                  </h4>
                  <div class="grid grid-cols-2 gap-3">
                    <div 
                      v-for="(color, index) in product.colors" 
                      :key="index" 
                      class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors"
                    >
                      <div 
                        class="w-8 h-8 rounded-xl border-2 border-white shadow-lg flex-shrink-0" 
                        :style="{ backgroundColor: color }"
                      ></div>
                      <span class="text-sm font-medium text-gray-700 truncate">{{ getColorName(color) }}</span>
                    </div>
                  </div>
                </div>
  
                <!-- Tailles -->
                <div v-if="product.sizes && product.sizes.length > 0" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                  <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    Tailles disponibles
                    <span class="ml-2 text-sm bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ product.sizes.length }}</span>
                  </h4>
                  <div class="flex flex-wrap gap-2">
                    <div 
                      v-for="(size, index) in product.sizes" 
                      :key="index" 
                      class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors cursor-default"
                    >
                      {{ size }}
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Tags -->
              <div v-if="product.tags && product.tags.length > 0" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                  </svg>
                  Tags
                </h4>
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="(tag, index) in product.tags" 
                    :key="index"
                    class="px-4 py-2 bg-blue-100 text-blue-800 text-sm rounded-xl font-medium hover:bg-blue-200 transition-colors"
                  >
                    {{ tag }}
                  </span>
                </div>
              </div>
            </div>
  
            <!-- Onglet 2: Galerie et médias -->
            <div v-else-if="activeTab === 'media'" class="space-y-8">
              <!-- Galerie d'images -->
              <div v-if="product.images && product.images.length > 0" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                  <h4 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Galerie d'images
                  </h4>
                  <span class="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">
                    {{ product.images.length }} image{{ product.images.length > 1 ? 's' : '' }}
                  </span>
                </div>
                
                <!-- Image principale de la galerie -->
                <div class="mb-8">
                  <div class="relative w-full h-96 bg-gray-100 rounded-2xl overflow-hidden shadow-lg">
                    <img 
                      :src="selectedImage" 
                      :alt="product.name" 
                      class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105"
                      @click="openImageModal"
                    >
                    <!-- Boutons de navigation -->
                    <button 
                      v-if="product.images.length > 1"
                      @click="previousImage"
                      class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-70 transition-all backdrop-blur-sm"
                    >
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                      </svg>
                    </button>
                    <button 
                      v-if="product.images.length > 1"
                      @click="nextImage"
                      class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-70 transition-all backdrop-blur-sm"
                    >
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </button>
                    <!-- Indicateur de position -->
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm">
                      {{ currentImageIndex + 1 }} / {{ product.images.length }}
                    </div>
                    <!-- Badge image principale -->
                    <div v-if="currentImageIndex === 0" class="absolute top-4 left-4 bg-orange text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                      Image principale
                    </div>
                  </div>
                </div>
                
                <!-- Grille de miniatures -->
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-3">
                  <div 
                    v-for="(image, index) in product.images" 
                    :key="index"
                    class="relative cursor-pointer group"
                    @click="selectImage(index)"
                  >
                    <img 
                      :src="image" 
                      :alt="`${product.name} - Image ${index + 1}`" 
                      :class="[
                        'w-full h-20 object-cover rounded-xl border-2 transition-all duration-200',
                        currentImageIndex === index 
                          ? 'border-orange shadow-lg ring-2 ring-orange ring-opacity-50 scale-105' 
                          : 'border-gray-200 hover:border-gray-300 group-hover:shadow-md group-hover:scale-105'
                      ]"
                    >
                    <!-- Badge pour l'image principale -->
                    <div 
                      v-if="index === 0" 
                      class="absolute -top-2 -right-2 bg-orange text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg"
                    >
                      P
                    </div>
                    <!-- Overlay au survol -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-xl"></div>
                  </div>
                </div>
              </div>
  
              <!-- Vidéo du produit -->
              <div v-if="product.video_url" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                  <h4 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Vidéo du produit
                  </h4>
                  <span class="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">1 vidéo</span>
                </div>
                <div class="relative w-full h-96 bg-gray-100 rounded-2xl overflow-hidden shadow-lg">
                  <video 
                    :src="product.video_url" 
                    controls 
                    class="w-full h-full object-cover"
                    preload="metadata"
                  >
                    Votre navigateur ne supporte pas la lecture de vidéos.
                  </video>
                </div>
              </div>
  
              <!-- Message si aucun média -->
              <div v-if="!product.images?.length && !product.video_url" class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                  <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun média disponible</h3>
                <p class="text-gray-500 max-w-md mx-auto">Ce produit n'a pas d'images ou de vidéos associées. Vous pouvez en ajouter en modifiant le produit.</p>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Actions modernes -->

      </div>
  
      <!-- Modal pour affichage plein écran de l'image -->
      <div 
        v-if="showImageModal" 
        class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-[60] p-4"
        @click="closeImageModal"
      >
        <div class="relative max-w-7xl max-h-full">
          <img 
            :src="modalImage" 
            :alt="product.name" 
            class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl"
          >
          <button 
            @click="closeImageModal"
            class="absolute top-4 right-4 text-white bg-black bg-opacity-50 p-3 rounded-full hover:bg-opacity-70 transition-all backdrop-blur-sm"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
          <!-- Navigation dans le modal -->
          <button 
            v-if="product.images && product.images.length > 1"
            @click.stop="previousImage"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-3 rounded-full hover:bg-opacity-70 transition-all backdrop-blur-sm"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <button 
            v-if="product.images && product.images.length > 1"
            @click.stop="nextImage"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-3 rounded-full hover:bg-opacity-70 transition-all backdrop-blur-sm"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
          <!-- Indicateur dans le modal -->
          <div v-if="product.images && product.images.length > 1" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm">
            {{ currentImageIndex + 1 }} / {{ product.images.length }}
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue'
  
  const props = defineProps(['product'])
  const emit = defineEmits(['close', 'edit', 'duplicate', 'delete'])
  
  const activeTab = ref('details')
  const currentImageIndex = ref(0)
  const showImageModal = ref(false)
  
  // Image principale (première image ou image par défaut)
  const primaryImage = computed(() => {
    if (props.product?.images && props.product.images.length > 0) {
      return props.product.images[0]
    }
    return props.product?.primary_image || null
  })
  
  // Image sélectionnée dans la galerie
  const selectedImage = computed(() => {
    if (props.product?.images && props.product.images.length > 0) {
      return props.product.images[currentImageIndex.value]
    }
    return null
  })
  
  // Image à afficher dans le modal
  const modalImage = computed(() => {
    if (activeTab.value === 'media' && selectedImage.value) {
      return selectedImage.value
    }
    return primaryImage.value
  })
  
  // Nombre total de médias
  const mediaCount = computed(() => {
    let count = 0
    if (props.product?.images) count += props.product.images.length
    if (props.product?.video_url) count += 1
    return count
  })
  
  // Réinitialiser l'index quand le produit change
  watch(() => props.product, () => {
    currentImageIndex.value = 0
    activeTab.value = 'details'
  })
  
  const selectImage = (index) => {
    currentImageIndex.value = index
  }
  
  const nextImage = () => {
    if (props.product?.images && props.product.images.length > 0) {
      currentImageIndex.value = (currentImageIndex.value + 1) % props.product.images.length
    }
  }
  
  const previousImage = () => {
    if (props.product?.images && props.product.images.length > 0) {
      currentImageIndex.value = currentImageIndex.value === 0 
        ? props.product.images.length - 1 
        : currentImageIndex.value - 1
    }
  }
  
  const openImageModal = () => {
    showImageModal.value = true
  }
  
  const closeImageModal = () => {
    showImageModal.value = false
  }
  
  const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    return new Date(dateString).toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }
  
  const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR').format(price || 0)
  }
  
  const getStatusBadgeClass = (status) => {
    switch (status) {
      case 'Actif':
        return 'bg-green-100 text-green-800 border border-green-200'
      case 'Stock faible':
        return 'bg-yellow-100 text-yellow-800 border border-yellow-200'
      case 'Rupture':
        return 'bg-red-100 text-red-800 border border-red-200'
      case 'Brouillon':
        return 'bg-gray-100 text-gray-800 border border-gray-200'
      default:
        return 'bg-gray-100 text-gray-800 border border-gray-200'
    }
  }
  
  const getStatusDotClass = (status) => {
    switch (status) {
      case 'Actif':
        return 'bg-green-500'
      case 'Stock faible':
        return 'bg-yellow-500'
      case 'Rupture':
        return 'bg-red-500'
      case 'Brouillon':
        return 'bg-gray-500'
      default:
        return 'bg-gray-500'
    }
  }
  
  const getStockStatus = (stock) => {
    if (stock === 0) return 'Rupture de stock'
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
      '#000000': 'Noir',
      '#FFFFFF': 'Blanc',
      '#C0C0C0': 'Argent',
      '#808080': 'Gris',
      '#FF0000': 'Rouge',
      '#0000FF': 'Bleu',
      '#008000': 'Vert',
      '#FFFF00': 'Jaune',
      '#FFA500': 'Orange',
      '#FFC0CB': 'Rose',
      '#800080': 'Violet',
      '#FFD700': 'Or',
      '#8B4513': 'Marron',
      '#654321': 'Brun'
    }
    return colorMap[color] || color
  }
  
  const handleBackdropClick = (event) => {
    if (event.target === event.currentTarget) {
      emit('close')
    }
  }
  </script>
  
  <style scoped>
  .bg-orange {
    background-color: #F65A11;
  }
  
  .hover\:bg-orange-600:hover {
    background-color: #e54a0a;
  }
  
  .border-orange {
    border-color: #F65A11;
  }
  
  .text-orange {
    color: #F65A11;
  }
  
  .ring-orange {
    --tw-ring-color: #F65A11;
  }
  
  .from-orange {
    --tw-gradient-from: #F65A11;
  }
  
  .to-orange-600 {
    --tw-gradient-to: #e54a0a;
  }
  
  .hover\:from-orange-600:hover {
    --tw-gradient-from: #e54a0a;
  }
  
  .hover\:to-orange-700:hover {
    --tw-gradient-to: #d44409;
  }
  
  /* Animations et transitions */
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }
  
  .hover\:-translate-y-0\.5:hover {
    transform: translateY(-2px);
  }
  
  .hover\:scale-105:hover {
    transform: scale(1.05);
  }
  
  .scale-\[1\.02\] {
    transform: scale(1.02);
  }
  
  /* Backdrop blur */
  .backdrop-blur-sm {
    backdrop-filter: blur(4px);
  }
  
  /* Scrollbar styling */
  .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
  }
  
  .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
  }
  
  .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
  }
  
  .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
  </style>
  