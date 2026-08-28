<template>
  <div 
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    @click="handleBackdropClick"
  >
    <div 
      class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
      @click.stop
    >
      <!-- Header avec gradient -->
      <div class="relative bg-gradient-to-r from-orange-600 via-white-600 to-orange-800 p-4">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-white">Ajouter un nouveau produit</h2>
              <p class="text-blue-100 text-sm">Créez un nouveau produit en quelques étapes simples</p>
            </div>
          </div>
          <button 
            @click="closeModal" 
            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-black hover:bg-white/30 transition-all duration-200"
          >
            X
          </button>
        </div>
      </div>

      <!-- Messages d'état -->
      <div v-if="error || isLoading || categoriesError || categoriesLoading" class="px-4 pt-3">
        <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center space-x-3">
          <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span>{{ error }}</span>
        </div>

        <div v-if="categoriesError" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center space-x-3">
          <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div class="flex-1">
            <span>{{ categoriesError }}</span>
            <button 
              @click="fetchCategories" 
              class="ml-3 px-3 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors"
            >
              Réessayer
            </button>
          </div>
        </div>

        <div v-if="isLoading" class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl flex items-center space-x-3">
          <div class="animate-spin w-5 h-5 border-2 border-blue-500 border-t-transparent rounded-full"></div>
          <span>{{ loadingMessage }}</span>
        </div>

        <div v-if="categoriesLoading" class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl flex items-center space-x-3">
          <div class="animate-spin w-5 h-5 border-2 border-blue-500 border-t-transparent rounded-full"></div>
          <span>Chargement des catégories...</span>
        </div>
      </div>

      <!-- Indicateur de progression moderne -->
      <div class="px-4 py-3 bg-gray-50">
        <div class="flex items-center justify-between mb-4">
          <div v-for="(step, index) in steps" :key="index" class="flex items-center">
            <div class="relative">
              <div :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300',
                currentStep > index 
                  ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg scale-110' 
                  : currentStep === index 
                    ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg scale-110' 
                    : 'bg-gray-200 text-gray-500'
              ]">
                <span v-if="currentStep > index" class="text-white">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                </span>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <div v-if="currentStep === index" class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-orange-500 rounded-full opacity-10 animate-pulse"></div>
            </div>
            <div v-if="index < steps.length - 1" :class="[
              'w-12 h-1 mx-3 rounded-full transition-all duration-500',
              currentStep > index 
                ? 'bg-gradient-to-r from-green-500 to-emerald-500' 
                : 'bg-gray-200'
            ]"></div>
          </div>
        </div>
        <div class="flex justify-between">
          <span v-for="(step, index) in steps" :key="index" :class="[
            'text-sm font-medium transition-colors duration-300',
            currentStep >= index ? 'text-gray-900' : 'text-gray-500'
          ]">
            {{ step.title }}
          </span>
        </div>
      </div>

      <!-- Contenu du formulaire -->
      <div class="p-4 overflow-y-auto max-h-[55vh]">
        <form @submit.prevent="handleSubmit">
          <!-- Étape 1: Informations de base -->
          <div v-if="currentStep === 0" class="space-y-4">
            <div class="grid grid-cols-1 gap-6">
              <div class="bg-gradient-to-br from-white-50 to-orange-50 p-4 rounded-xl border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Informations principales
                </h3>
                
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom du produit *</label>
                    <input 
                      v-model="productData.name"
                      type="text" 
                      required
                      class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                      placeholder="Ex: MacBook Air M1"
                    >
                  </div>
                  
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description courte</label>
                    <textarea 
                      v-model="productData.description"
                      rows="3"
                      class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                      placeholder="Description courte du produit..."
                    ></textarea>
                  </div>

                  <div>
                    <div class="flex items-center mb-3">
                      <input 
                        v-model="productData.hasDetailedDescription"
                        id="detailed-description-toggle"
                        type="checkbox"
                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                      >
                      <label for="detailed-description-toggle" class="ml-3 text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Activer la description détaillée (WYSIWYG)
                      </label>
                    </div>
                    
                    <div v-if="productData.hasDetailedDescription">
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Description détaillée (WYSIWYG)</label>
                      <div class="border-2 border-gray-200 rounded-xl focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200">
                        <!-- Barre d'outils WYSIWYG -->
                        <div class="flex items-center gap-1 p-2 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                          <button type="button" @click="formatText('bold')" class="p-2 hover:bg-gray-200 rounded text-sm font-bold" title="Gras">B</button>
                          <button type="button" @click="formatText('italic')" class="p-2 hover:bg-gray-200 rounded text-sm italic" title="Italique">I</button>
                          <button type="button" @click="formatText('underline')" class="p-2 hover:bg-gray-200 rounded text-sm underline" title="Souligné">U</button>
                          <div class="w-px h-6 bg-gray-300 mx-1"></div>
                          <button type="button" @click="formatText('insertUnorderedList')" class="p-2 hover:bg-gray-200 rounded text-sm" title="Liste à puces">•</button>
                          <button type="button" @click="formatText('insertOrderedList')" class="p-2 hover:bg-gray-200 rounded text-sm" title="Liste numérotée">1.</button>
                          <div class="w-px h-6 bg-gray-300 mx-1"></div>
                          <select @change="formatHeading($event)" class="text-sm border border-gray-300 rounded px-2 py-1">
                            <option value="">Titre</option>
                            <option value="h1">Titre 1</option>
                            <option value="h2">Titre 2</option>
                            <option value="h3">Titre 3</option>
                          </select>
                        </div>
                        <!-- Zone d'édition -->
                        <div 
                          ref="wysiwygEditor"
                          contenteditable="true"
                          @input="updateDetailedDescription"
                          class="min-h-[200px] p-4 focus:outline-none"
                          style="white-space: pre-wrap;"
                          placeholder="Décrivez votre produit en détail..."
                        ></div>
                      </div>
                      <p class="text-xs text-gray-500 mt-1">Utilisez la barre d'outils pour formater votre texte</p>
                    </div>
                    
                    <div v-else class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                      <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-blue-700">
                          Cochez la case ci-dessus pour activer l'éditeur de description détaillée avec formatage avancé
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-gradient-to-br from-white-50 to-white-50 p-4 rounded-xl border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                  </svg>
                  Catégorisation
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie principale *</label>
                    <select 
                      v-model="productData.category_id"
                      @change="updateSubcategories"
                      required
                      :disabled="categoriesLoading"
                      class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 disabled:bg-gray-100"
                    >
                      <option value="">{{ categoriesLoading ? 'Chargement...' : 'Sélectionner une catégorie' }}</option>
                      <option v-for="category in categories" :key="category.id" :value="category.id">
                        <span v-if="category.icon">{{ category.icon }}</span>
                        {{ category.name }}
                      </option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sous-catégorie *</label>
                    <select 
                      v-model="productData.subcategory_id"
                      @change="updateSubSubcategories"
                      required
                      :disabled="!productData.category_id || categoriesLoading"
                      class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 disabled:bg-gray-100"
                    >
                      <option value="">Sélectionner une sous-catégorie</option>
                      <option v-for="subcategory in availableSubcategories" :key="subcategory.id" :value="subcategory.id">
                        {{ subcategory.name }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sous-sous-catégorie</label>
                    <select 
                      v-model="productData.subsubcategory_id"
                      :disabled="!productData.subcategory_id || categoriesLoading"
                      class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 disabled:bg-gray-100"
                    >
                      <option value="">Sélectionner une sous-sous-catégorie (optionnel)</option>
                      <option v-for="subsubcategory in availableSubSubcategories" :key="subsubcategory.id" :value="subsubcategory.id">
                        {{ subsubcategory.name }}
                      </option>
                    </select>
                  </div>
                </div>

                <div class="mt-4">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Tags (optionnel)</label>
                  <input 
                    v-model="productData.tags"
                    type="text" 
                    class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                    placeholder="Ex: nouveau, tendance, promotion (séparés par des virgules)"
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Étape 2: Prix et stock -->
          <div v-if="currentStep === 1" class="space-y-4">
            <div class="bg-gradient-to-br from-white-50 to-white-50 p-4 rounded-xl border border-gray-100">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
                Tarification
              </h3>
              
              <div class="grid grid-cols-1 gap-3">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Prix unitaire (FCFA) *</label>
                  <input 
                    v-model="productData.unit_price"
                    type="number" 
                    required
                    class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                    placeholder="0"
                  >
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Stock initial *</label>
                  <div class="flex space-x-2">
                    <input 
                      v-model="productData.stock"
                      type="number" 
                      required
                      class="flex-1 border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                      placeholder="Quantité"
                    >
                    <select
                      v-model="productData.unit_type"
                      class="border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                    >
                      <option v-for="unit in availableUnitTypes" :key="unit.value" :value="unit.value">
                        {{ unit.label }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-red-50 p-4 rounded-xl border border-orange-100">
              <div class="flex items-center mb-4">
                <input 
                  v-model="productData.hasWholesalePrice"
                  id="wholesale-price"
                  type="checkbox"
                  class="w-5 h-5 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2"
                >
                <label for="wholesale-price" class="ml-3 text-sm font-semibold text-gray-700 flex items-center">
                  <svg class="w-4 h-4 text-orange-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                  Activer le prix de gros
                </label>
              </div>
              
              <div v-if="productData.hasWholesalePrice" class="grid grid-cols-2 gap-4 mt-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Prix de gros (FCFA)</label>
                  <input 
                    v-model="productData.wholesale_price"
                    type="number" 
                    class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                    placeholder="Prix pour les achats en gros"
                  >
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Quantité minimale</label>
                  <input 
                    v-model="productData.wholesale_min_qty"
                    type="number" 
                    class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                    placeholder="Quantité min"
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Étape 3: Variantes -->
          <div v-if="currentStep === 2" class="space-y-4">
            <div class="bg-gradient-to-br from-white-50 to-white-50 p-4 rounded-xl border border-gray-100">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-pink-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4M13 13h4a2 2 0 012 2v4a2 2 0 01-2 2h-4"/>
                </svg>
                Couleurs disponibles
              </h3>
              
              <div class="grid grid-cols-4 gap-3 mb-4">
                <div v-for="color in availableColors" :key="`color-${color.value}`" class="flex items-center p-2 rounded-lg hover:bg-white transition-colors">
                  <input 
                    :id="`color-${color.value}`"
                    v-model="productData.colors"
                    :value="color.value"
                    type="checkbox"
                    class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500 focus:ring-2"
                  >
                  <label :for="`color-${color.value}`" class="ml-2 flex items-center cursor-pointer">
                    <div 
                      class="w-5 h-5 rounded-full border-2 border-gray-300 mr-2 shadow-sm"
                      :style="{ backgroundColor: color.value }"
                    ></div>
                    <span class="text-xs font-medium text-gray-700">{{ color.name }}</span>
                  </label>
                </div>
              </div>
              
              <div class="border-t border-pink-200 pt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ajouter une couleur personnalisée</label>
                <div class="flex gap-2">
                  <input 
                    v-model="customColor.name"
                    type="text" 
                    placeholder="Nom de la couleur"
                    class="flex-1 border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                  >
                  <input 
                    v-model="customColor.value"
                    type="color" 
                    class="w-12 h-10 border-2 border-gray-200 rounded-xl cursor-pointer"
                  >
                  <button 
                    @click="addCustomColor"
                    type="button"
                    class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-500 text-white rounded-xl hover:from-orange-600 hover:to-black-600 transition-all duration-200 font-medium"
                  >
                    Ajouter
                  </button>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-white-50 to-orange-50 p-4 rounded-xl border border-gray-100">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4a1 1 0 011-1h4m0 0V1m0 2h2m0 0V1m0 2h2m0 0V1m0 2h4a1 1 0 011 1v4M4 8H2m2 0v2m0 0H2m2 0v2m0 0H2m2 0v4a1 1 0 001 1h4m0 0v2m0-2h2m0 0v2m0-2h2m0 0v2m0-2h4a1 1 0 001-1v-4"/>
                </svg>
                Tailles disponibles
              </h3>

              <!-- Type de taille -->
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Type de taille</label>
                <select 
                  v-model="productData.sizeType"
                  @change="updateAvailableSizes"
                  class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                >
                  <option value="">Sélectionner le type de taille</option>
                  <option v-for="sizeType in sizeTypes" :key="sizeType.value" :value="sizeType.value">
                    {{ sizeType.label }}
                  </option>
                </select>
              </div>
              
              <div v-if="productData.sizeType" class="grid grid-cols-6 gap-2 mb-4">
                <div v-for="size in currentAvailableSizes" :key="`size-${size}`" class="flex items-center p-2 rounded-lg hover:bg-white transition-colors">
                  <input 
                    :id="`size-${size}`"
                    v-model="productData.sizes"
                    :value="size"
                    type="checkbox"
                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                  >
                  <label :for="`size-${size}`" class="ml-2 text-xs font-medium text-gray-700 cursor-pointer">{{ size }}</label>
                </div>
              </div>
              
              <div v-if="productData.sizeType" class="border-t border-indigo-200 pt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ajouter une taille personnalisée</label>
                <div class="flex gap-2">
                  <input 
                    v-model="customSize"
                    type="text" 
                    :placeholder="getSizePlaceholder(productData.sizeType)"
                    class="flex-1 border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                  >
                  <button 
                    @click="addCustomSize"
                    type="button"
                    class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-500 text-white rounded-xl hover:from-black-600 hover:to-blue-600 transition-all duration-200 font-medium"
                  >
                    Ajouter
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Étape 4: Médias -->
          <div v-if="currentStep === 3" class="space-y-4">
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-4 rounded-xl border border-yellow-100">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Images du produit (Maximum 8 images)
              </h3>
              
              <div class="border-2 border-dashed border-yellow-300 rounded-xl p-6 text-center bg-gradient-to-br from-yellow-50 to-orange-50 hover:from-yellow-100 hover:to-orange-100 transition-all duration-200">
                <svg class="mx-auto h-12 w-12 text-yellow-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                  <label for="images-upload" class="cursor-pointer">
                    <span class="block text-lg font-semibold text-gray-900 mb-2">
                      Cliquez pour télécharger des images
                    </span>
                    <span class="block text-sm text-gray-600">
                      PNG, JPG, GIF jusqu'à 10MB par image
                    </span>
                  </label>
                  <input 
                    id="images-upload" 
                    name="images-upload" 
                    type="file" 
                    multiple 
                    accept="image/*" 
                    @change="handleImageUpload"
                    class="sr-only"
                  >
                </div>
              </div>
              
              <div v-if="productData.images.length > 0" class="grid grid-cols-4 gap-4 mt-6">
                <div v-for="(image, index) in productData.images" :key="index" class="relative group">
                  <img :src="image.preview" :alt="`Image ${index + 1}`" class="w-full h-24 object-cover rounded-xl border-2 border-gray-200">
                  
                  <div v-if="image.uploading" class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-xl">
                    <div class="text-white text-sm font-medium">{{ image.uploadProgress }}%</div>
                  </div>
                  
                  <div v-if="image.uploaded && image.url" class="absolute top-2 right-2 bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  
                  <button 
                    @click="removeImage(index)"
                    type="button"
                    class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center text-sm hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100"
                  >
                    ×
                  </button>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-4 rounded-xl border border-purple-100">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Vidéo du produit (optionnel)
              </h3>
              
              <div class="border-2 border-dashed border-purple-300 rounded-xl p-6 text-center bg-gradient-to-br from-purple-50 to-indigo-50 hover:from-purple-100 hover:to-indigo-100 transition-all duration-200">
                <svg class="mx-auto h-12 w-12 text-purple-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <div>
                  <label for="video-upload" class="cursor-pointer">
                    <span class="block text-lg font-semibold text-gray-900 mb-2">
                      Cliquez pour télécharger une vidéo
                    </span>
                    <span class="block text-sm text-gray-600">
                      MP4, MOV, AVI jusqu'à 50MB
                    </span>
                  </label>
                  <input 
                    id="video-upload" 
                    name="video-upload" 
                    type="file" 
                    accept="video/*" 
                    @change="handleVideoUpload"
                    class="sr-only"
                  >
                </div>
              </div>
              
              <div v-if="productData.video" class="mt-6">
                <video :src="productData.video.preview" controls class="w-full max-w-md mx-auto rounded-xl shadow-lg">
                  Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
                <button 
                  @click="removeVideo"
                  type="button"
                  class="mt-4 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-200 font-medium"
                >
                  Supprimer la vidéo
                </button>
              </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-slate-50 p-4 rounded-xl border border-gray-200">
              <div class="flex items-center mb-4">
                <input 
                  v-model="productData.is_active"
                  id="is-active"
                  type="checkbox"
                  class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                >
                <label for="is-active" class="ml-3 text-sm font-semibold text-gray-700 flex items-center">
                  <svg class="w-4 h-4 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Publier immédiatement ce produit
                </label>
              </div>

              <!-- Résumé du produit -->
              <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  Résumé du produit
                </h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div class="space-y-2">
                    <p><span class="font-medium text-gray-700">Nom:</span> <span class="text-gray-900">{{ productData.name || 'Non défini' }}</span></p>
                    <p><span class="font-medium text-gray-700">Catégorie:</span> <span class="text-gray-900">{{ getCategoryName(productData.category_id) || 'Non définie' }}</span></p>
                    <p><span class="font-medium text-gray-700">Sous-catégorie:</span> <span class="text-gray-900">{{ getSubcategoryName(productData.subcategory_id) || 'Non définie' }}</span></p>
                    <p v-if="productData.subsubcategory_id"><span class="font-medium text-gray-700">Sous-sous-catégorie:</span> <span class="text-gray-900">{{ getSubSubcategoryName(productData.subsubcategory_id) || 'Non définie' }}</span></p>
                    <p><span class="font-medium text-gray-700">Prix unitaire:</span> <span class="text-gray-900">{{ productData.unit_price ? productData.unit_price + ' FCFA' : 'Non défini' }}</span></p>
                    <p v-if="productData.hasWholesalePrice"><span class="font-medium text-gray-700">Prix de gros:</span> <span class="text-gray-900">{{ productData.wholesale_price ? productData.wholesale_price + ' FCFA' : 'Non défini' }} ({{ productData.wholesale_min_qty }}+ unités)</span></p>
                    <p><span class="font-medium text-gray-700">Description détaillée:</span> <span class="text-gray-900">{{ productData.hasDetailedDescription ? 'Activée' : 'Désactivée' }}</span></p>
                  </div>
                  <div class="space-y-2">
                    <p><span class="font-medium text-gray-700">Stock:</span> <span class="text-gray-900">{{ productData.stock || 'Non défini' }}</span></p>
                    <p><span class="font-medium text-gray-700">Type d'unité:</span> <span class="text-gray-900">{{ availableUnitTypes.find(u => u.value === productData.unit_type)?.label || 'Quantité (unités)' }}</span></p>
                    <p><span class="font-medium text-gray-700">Type de taille:</span> <span class="text-gray-900">{{ sizeTypes.find(s => s.value === productData.sizeType)?.label || 'Non défini' }}</span></p>
                    <p><span class="font-medium text-gray-700">Couleurs:</span> <span class="text-gray-900">{{ productData.colors.length }} sélectionnée(s)</span></p>
                    <p><span class="font-medium text-gray-700">Tailles:</span> <span class="text-gray-900">{{ productData.sizes.length }} sélectionnée(s)</span></p>
                    <p><span class="font-medium text-gray-700">Images:</span> <span class="text-gray-900">{{ productData.images.length }}/8</span></p>
                    <p><span class="font-medium text-gray-700">Vidéo:</span> <span class="text-gray-900">{{ productData.video ? 'Oui' : 'Non' }}</span></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Navigation -->
      <div class="flex justify-between items-center p-4 bg-gray-50 border-t border-gray-200">
        <button 
          v-if="currentStep > 0"
          @click="currentStep--"
          type="button"
          class="flex items-center px-4 py-2 text-gray-600 border-2 border-gray-300 rounded-xl hover:bg-gray-100 hover:border-gray-400 transition-all duration-200 font-medium"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Précédent
        </button>
        <div v-else></div>

        <button 
          v-if="currentStep < steps.length - 1"
          @click="nextStep"
          type="button"
          class="flex items-center px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-500 text-white rounded-xl hover:from-blue-600 hover:to-orange-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
        >
          Suivant
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
        <button 
          v-else
          @click="handleSubmit"
          type="button"
          :disabled="isLoading"
          class="flex items-center px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          {{ isLoading ? 'Création en cours...' : 'Créer le produit' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import { categoriesApi } from '../../services/api'

const emit = defineEmits(['close', 'save'])

const currentStep = ref(0)
const customColor = ref({ name: '', value: '#000000' })
const customSize = ref('')
const isLoading = ref(false)
const loadingMessage = ref('')
const error = ref(null)
const wysiwygEditor = ref(null)

// États pour les catégories API
const categories = ref([])
const categoriesLoading = ref(false)
const categoriesError = ref(null)

// Configuration Cloudinary
const cloudinaryConfig = {
  cloudName: 'dqk65objc',
  uploadPreset: 'sadeal',
  apiKey: '784574272958761',
  uploadUrl: 'https://api.cloudinary.com/v1_1/dqk65objc/image/upload'
}

const steps = [
  { title: 'Informations de base' },
  { title: 'Prix & Stock' },
  { title: 'Variantes' },
  { title: 'Médias & Finalisation' }
]

const productData = reactive({
  name: '',
  description: '',
  detailed_description: '',
  hasDetailedDescription: false,
  category_id: '',
  subcategory_id: '',
  subsubcategory_id: '',
  tags: '',
  unit_price: '',
  stock: '',
  unit_type: 'quantity',
  hasWholesalePrice: false,
  wholesale_price: '',
  wholesale_min_qty: '',
  colors: [],
  sizes: [],
  sizeType: '',
  images: [],
  imageUrls: [],
  video: null,
  videoUrl: '',
  is_active: true
})

// Types de tailles
const sizeTypes = ref([
  { 
    value: 'clothing', 
    label: 'Tailles de vêtements',
    sizes: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']
  },
  { 
    value: 'shoes', 
    label: 'Pointures de chaussures',
    sizes: ['35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47']
  },
  { 
    value: 'storage', 
    label: 'Capacité de stockage',
    sizes: ['16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB', '2TB']
  },
  { 
    value: 'screen', 
    label: 'Taille d\'écran',
    sizes: ['5.5"', '6.1"', '6.7"', '13"', '15"', '17"', '21"', '24"', '27"', '32"']
  },
  { 
    value: 'ring', 
    label: 'Taille de bague',
    sizes: ['48', '50', '52', '54', '56', '58', '60', '62', '64', '66', '68']
  },
  { 
    value: 'watch', 
    label: 'Taille de bracelet de montre',
    sizes: ['38mm', '40mm', '42mm', '44mm', '45mm', '49mm']
  },
  { 
    value: 'custom', 
    label: 'Tailles personnalisées',
    sizes: []
  }
])

const currentAvailableSizes = computed(() => {
  const sizeType = sizeTypes.value.find(type => type.value === productData.sizeType)
  return sizeType ? sizeType.sizes : []
})

const availableSubcategories = computed(() => {
  const category = categories.value.find(cat => cat.id === productData.category_id)
  return category ? category.subcategories || [] : []
})

const availableSubSubcategories = computed(() => {
  for (const category of categories.value) {
    const subcategory = (category.subcategories || []).find(sub => sub.id === productData.subcategory_id)
    if (subcategory && subcategory.sub_subcategories) {
      return subcategory.sub_subcategories
    }
  }
  return []
})

const getCategoryName = (id) => {
  const category = categories.value.find(cat => cat.id === id)
  return category ? category.name : ''
}

const getSubcategoryName = (id) => {
  for (const category of categories.value) {
    const subcategory = (category.subcategories || []).find(sub => sub.id === id)
    if (subcategory) return subcategory.name
  }
  return ''
}

const getSubSubcategoryName = (id) => {
  for (const category of categories.value) {
    for (const subcategory of (category.subcategories || [])) {
      if (subcategory.sub_subcategories) {
        const subsubcategory = subcategory.sub_subcategories.find(subsub => subsub.id === id)
        if (subsubcategory) return subsubcategory.name
      }
    }
  }
  return ''
}

const availableColors = ref([
  { name: 'Noir', value: '#000000' },
  { name: 'Blanc', value: '#FFFFFF' },
  { name: 'Gris', value: '#808080' },
  { name: 'Rouge', value: '#FF0000' },
  { name: 'Bleu', value: '#0000FF' },
  { name: 'Vert', value: '#008000' },
  { name: 'Jaune', value: '#FFFF00' },
  { name: 'Orange', value: '#FFA500' },
  { name: 'Rose', value: '#FFC0CB' },
  { name: 'Violet', value: '#800080' },
  { name: 'Marron', value: '#8B4513' },
  { name: 'Or', value: '#FFD700' }
])

const availableUnitTypes = ref([
  { value: 'quantity', label: 'Quantité (unités)' },
  { value: 'weight_kg', label: 'Poids (kg)' },
  { value: 'weight_g', label: 'Poids (g)' },
  { value: 'volume_l', label: 'Volume (L)' },
  { value: 'volume_ml', label: 'Volume (mL)' },
  { value: 'length_m', label: 'Longueur (m)' },
  { value: 'length_cm', label: 'Longueur (cm)' },
  { value: 'area_m2', label: 'Surface (m²)' }
])

// Fonction pour récupérer les catégories depuis l'API
const fetchCategories = async () => {
  try {
    categoriesLoading.value = true
    categoriesError.value = null
    
    const response = await categoriesApi.getCategories()
    categories.value = response.data || []
    
  } catch (err) {
    console.error('Erreur lors du chargement des catégories:', err)
    categoriesError.value = 'Impossible de charger les catégories. Veuillez réessayer.'
  } finally {
    categoriesLoading.value = false
  }
}

// Fonctions WYSIWYG
const formatText = (command) => {
  document.execCommand(command, false, null)
  wysiwygEditor.value.focus()
}

const formatHeading = (event) => {
  const heading = event.target.value
  if (heading) {
    document.execCommand('formatBlock', false, heading)
    event.target.value = ''
    wysiwygEditor.value.focus()
  }
}

const updateDetailedDescription = () => {
  productData.detailed_description = wysiwygEditor.value.innerHTML
}

const updateSubcategories = () => {
  productData.subcategory_id = ''
  productData.subsubcategory_id = ''
}

const updateSubSubcategories = () => {
  productData.subsubcategory_id = ''
}

const updateAvailableSizes = () => {
  productData.sizes = []
}

const getSizePlaceholder = (sizeType) => {
  const placeholders = {
    'clothing': 'Ex: M, L, XL...',
    'shoes': 'Ex: 42, 43, 44...',
    'storage': 'Ex: 128GB, 256GB...',
    'screen': 'Ex: 15", 17"...',
    'ring': 'Ex: 54, 56, 58...',
    'watch': 'Ex: 42mm, 44mm...',
    'custom': 'Ex: Taille personnalisée...'
  }
  return placeholders[sizeType] || 'Ex: Taille personnalisée...'
}

const addCustomColor = () => {
  if (customColor.value.name && customColor.value.value) {
    const exists = availableColors.value.some(color => 
      color.value === customColor.value.value || 
      color.name.toLowerCase() === customColor.value.name.toLowerCase()
    )
    
    if (!exists) {
      availableColors.value.push({
        name: customColor.value.name,
        value: customColor.value.value
      })
      productData.colors.push(customColor.value.value)
    }
    
    customColor.value = { name: '', value: '#000000' }
  }
}

const addCustomSize = () => {
  if (customSize.value && !currentAvailableSizes.value.includes(customSize.value)) {
    const sizeType = sizeTypes.value.find(type => type.value === productData.sizeType)
    if (sizeType) {
      sizeType.sizes.push(customSize.value)
      productData.sizes.push(customSize.value)
    }
    customSize.value = ''
  }
}

const handleImageUpload = (event) => {
  const files = Array.from(event.target.files)
  const remainingSlots = 8 - productData.images.length
  
  files.slice(0, remainingSlots).forEach(file => {
    if (file.size <= 10 * 1024 * 1024) {
      const reader = new FileReader()
      reader.onload = (e) => {
        productData.images.push({
          file: file,
          preview: e.target.result,
          uploading: false,
          uploadProgress: 0,
          uploaded: false,
          url: null
        })
      }
      reader.readAsDataURL(file)
    }
  })
}

const removeImage = (index) => {
  productData.images.splice(index, 1)
  if (productData.imageUrls[index]) {
    productData.imageUrls.splice(index, 1)
  }
}

const handleVideoUpload = (event) => {
  const file = event.target.files[0]
  if (file && file.size <= 50 * 1024 * 1024) {
    const reader = new FileReader()
    reader.onload = (e) => {
      productData.video = {
        file: file,
        preview: e.target.result,
        uploading: false,
        uploadProgress: 0,
        uploaded: false,
        url: null
      }
    }
    reader.readAsDataURL(file)
  }
}

const removeVideo = () => {
  productData.video = null
  productData.videoUrl = ''
}

const nextStep = () => {
  if (validateCurrentStep()) {
    currentStep.value++
  }
}

const validateCurrentStep = () => {
  switch (currentStep.value) {
    case 0:
      return productData.name && productData.category_id && productData.subcategory_id
    case 1:
      return productData.unit_price && productData.stock
    case 2:
      return true
    case 3:
      return true
    default:
      return true
  }
}

const uploadImageToCloudinary = async (image, index) => {
  try {
    productData.images[index].uploading = true
    productData.images[index].uploadProgress = 0
    
    const fileName = `product_${Date.now()}_${index}_${image.file.name.replace(/\s+/g, '_')}`
    
    const formData = new FormData()
    formData.append('file', image.file)
    formData.append('upload_preset', cloudinaryConfig.uploadPreset)
    formData.append('api_key', cloudinaryConfig.apiKey)
    formData.append('public_id', fileName)
    
    const response = await axios.post(cloudinaryConfig.uploadUrl, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        productData.images[index].uploadProgress = percentCompleted
      }
    })
    
    if (response.data && response.data.secure_url) {
      productData.images[index].url = response.data.secure_url
      productData.imageUrls[index] = response.data.secure_url
    } else {
      throw new Error('Réponse Cloudinary invalide')
    }
    
    productData.images[index].uploading = false
    productData.images[index].uploaded = true
    
    return true
  } catch (error) {
    console.error(`Erreur lors du téléchargement de l'image ${index}:`, error)
    productData.images[index].uploading = false
    return false
  }
}

const uploadVideoToCloudinary = async (video) => {
  try {
    productData.video.uploading = true
    productData.video.uploadProgress = 0
    
    const fileName = `product_video_${Date.now()}_${video.file.name.replace(/\s+/g, '_')}`
    
    const formData = new FormData()
    formData.append('file', video.file)
    formData.append('upload_preset', cloudinaryConfig.uploadPreset)
    formData.append('api_key', cloudinaryConfig.apiKey)
    formData.append('public_id', fileName)
    formData.append('resource_type', 'video')
    
    const response = await axios.post(
      `https://api.cloudinary.com/v1_1/${cloudinaryConfig.cloudName}/video/upload`, 
      formData, 
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        onUploadProgress: (progressEvent) => {
          const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          productData.video.uploadProgress = percentCompleted
        }
      }
    )
    
    if (response.data && response.data.secure_url) {
      productData.video.url = response.data.secure_url
      productData.videoUrl = response.data.secure_url
    } else {
      throw new Error('Réponse Cloudinary invalide')
    }
    
    productData.video.uploading = false
    productData.video.uploaded = true
    
    return true
  } catch (error) {
    console.error('Erreur lors du téléchargement de la vidéo:', error)
    productData.video.uploading = false
    return false
  }
}

const uploadAllMedia = async () => {
  loadingMessage.value = 'Téléchargement des médias en cours...'
  
  const imagePromises = []
  for (let i = 0; i < productData.images.length; i++) {
    imagePromises.push(uploadImageToCloudinary(productData.images[i], i))
  }
  
  await Promise.all(imagePromises)
  
  if (productData.video && productData.video.file) {
    await uploadVideoToCloudinary(productData.video)
  }
  
  loadingMessage.value = 'Médias téléchargés avec succès!'
}

const prepareDataForSubmission = () => {
  const formData = {
    name: productData.name,
    description: productData.description,
    category_id: productData.category_id,
    subcategory_id: productData.subcategory_id,
    subsubcategory_id: productData.subsubcategory_id,
    unit_price: parseFloat(productData.unit_price),
    stock: parseInt(productData.stock),
    unit_type: productData.unit_type,
    tags: productData.tags,
    is_active: productData.is_active,
    colors: productData.colors,
    sizes: productData.sizes,
    size_type: productData.sizeType
  }
  
  // N'inclure la description détaillée que si elle est activée
  if (productData.hasDetailedDescription && productData.detailed_description) {
    formData.detailed_description = productData.detailed_description
  }
  
  if (productData.hasWholesalePrice) {
    formData.wholesale_price = parseFloat(productData.wholesale_price)
    formData.wholesale_min_qty = parseInt(productData.wholesale_min_qty)
  }
  
  formData.images = productData.imageUrls.map((url, index) => ({
    url: url,
    alt_text: `${productData.name} - Image ${index + 1}`,
    is_primary: index === 0 ? 1 : 0,
    sort_order: index
  }))
  
  if (productData.videoUrl) {
    formData.video_url = productData.videoUrl
  }
  
  return formData
}

const handleSubmit = async () => {
  if (!validateCurrentStep()) return
  
  try {
    isLoading.value = true
    error.value = null
    
    await uploadAllMedia()
    
    loadingMessage.value = 'Préparation des données...'
    const formData = prepareDataForSubmission()
    
    loadingMessage.value = 'Finalisation...'
    emit('save', formData)
    
  } catch (err) {
    console.error('Erreur lors de la préparation:', err)
    error.value = 'Erreur lors du téléchargement des médias'
  } finally {
    isLoading.value = false
  }
}

const closeModal = () => {
  emit('close')
}

const handleBackdropClick = (event) => {
  if (event.target === event.currentTarget) {
    closeModal()
  }
}

// Charger les catégories au montage du composant
onMounted(() => {
  fetchCategories()
})
</script>

<style scoped>
/* Styles pour l'éditeur WYSIWYG */
[contenteditable="true"]:empty:before {
  content: attr(placeholder);
  color: #9CA3AF;
  font-style: italic;
}

[contenteditable="true"]:focus {
  outline: none;
}

/* Styles pour le contenu formaté */
[contenteditable="true"] h1 {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0.5rem 0;
}

[contenteditable="true"] h2 {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 0.5rem 0;
}

[contenteditable="true"] h3 {
  font-size: 1.125rem;
  font-weight: bold;
  margin: 0.5rem 0;
}

[contenteditable="true"] ul {
  list-style-type: disc;
  margin-left: 1.5rem;
  margin: 0.5rem 0 0.5rem 1.5rem;
}

[contenteditable="true"] ol {
  list-style-type: decimal;
  margin-left: 1.5rem;
  margin: 0.5rem 0 0.5rem 1.5rem;
}

[contenteditable="true"] li {
  margin: 0.25rem 0;
}

[contenteditable="true"] strong {
  font-weight: bold;
}

[contenteditable="true"] em {
  font-style: italic;
}

[contenteditable="true"] u {
  text-decoration: underline;
}
</style>