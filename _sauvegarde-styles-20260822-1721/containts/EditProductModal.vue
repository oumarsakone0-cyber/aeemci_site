<template>
    <div 
      class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
      @click="handleBackdropClick"
    >
      <div 
        class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
        @click.stop
      >
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Modifier le produit</h2>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
  
        <!-- Messages d'état -->
        <div v-if="error || isLoading" class="mb-4">
          <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center space-x-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ error }}</span>
          </div>
  
          <div v-if="isLoading" class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg flex items-center space-x-3">
            <div class="animate-spin w-5 h-5 border-2 border-blue-500 border-t-transparent rounded-full"></div>
            <span>Sauvegarde en cours...</span>
          </div>
        </div>
  
        <form @submit.prevent="handleSubmit" v-if="editData" class="space-y-6">
          <!-- Informations de base -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de base</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom du produit *</label>
                <input 
                  v-model="editData.name"
                  type="text" 
                  required
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                  placeholder="Nom du produit"
                >
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="editData.description"
                  rows="3"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                  placeholder="Description du produit"
                ></textarea>
              </div>
  
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                  <select 
                    v-model="editData.category_id"
                    @change="updateSubcategories"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                  >
                    <option value="">Sélectionner une catégorie</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Sous-catégorie *</label>
                  <select 
                    v-model="editData.subcategory_id"
                    required
                    :disabled="!editData.category_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange disabled:bg-gray-100"
                  >
                    <option value="">Sélectionner une sous-catégorie</option>
                    <option v-for="subcategory in availableSubcategories" :key="subcategory.id" :value="subcategory.id">
                      {{ subcategory.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Prix et stock -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Prix et stock</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix unitaire (FCFA) *</label>
                <input 
                  v-model="editData.unit_price"
                  type="number" 
                  required
                  min="0"
                  step="0.01"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                  placeholder="Prix unitaire"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                <input 
                  v-model="editData.stock"
                  type="number" 
                  required
                  min="0"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                  placeholder="Quantité en stock"
                >
              </div>
            </div>
  
            <!-- Prix de gros -->
            <div class="border-t border-gray-200 pt-4">
              <div class="flex items-center mb-4">
                <input 
                  v-model="editData.hasWholesalePrice"
                  id="wholesale-price-edit"
                  type="checkbox"
                  class="w-4 h-4 text-orange bg-gray-100 border-gray-300 rounded focus:ring-orange focus:ring-2"
                >
                <label for="wholesale-price-edit" class="ml-2 text-sm font-medium text-gray-700">
                  Activer le prix de gros
                </label>
              </div>
              
              <div v-if="editData.hasWholesalePrice" class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Prix de gros (FCFA)</label>
                  <input 
                    v-model="editData.wholesale_price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                    placeholder="Prix de gros"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Quantité min. gros</label>
                  <input 
                    v-model="editData.wholesale_min_qty"
                    type="number"
                    min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
                    placeholder="Quantité minimale"
                  >
                </div>
              </div>
            </div>
          </div>
  
          <!-- Statut -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
            <select 
              v-model="editData.status"
              required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange focus:border-orange"
            >
              <option value="Actif">Actif</option>
              <option value="Brouillon">Brouillon</option>
              <option value="Stock faible">Stock faible</option>
              <option value="Rupture">Rupture</option>
            </select>
          </div>
  
          <!-- Couleurs -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 mb-3">Couleurs disponibles</label>
            <div class="grid grid-cols-4 gap-3">
              <div v-for="color in availableColors" :key="color.value" class="flex items-center p-2 rounded hover:bg-white transition-colors">
                <input 
                  :id="`edit-color-${color.value}`"
                  v-model="editData.colors"
                  :value="color.value"
                  type="checkbox"
                  class="w-4 h-4 text-orange bg-gray-100 border-gray-300 rounded focus:ring-orange focus:ring-2"
                >
                <label :for="`edit-color-${color.value}`" class="ml-2 flex items-center cursor-pointer">
                  <div 
                    class="w-5 h-5 rounded border border-gray-300 mr-2"
                    :style="{ backgroundColor: color.value }"
                  ></div>
                  <span class="text-xs font-medium text-gray-700">{{ color.name }}</span>
                </label>
              </div>
            </div>
          </div>
  
          <!-- Tailles -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 mb-3">Tailles disponibles</label>
            <div class="grid grid-cols-6 gap-2">
              <div v-for="size in availableSizes" :key="size" class="flex items-center p-2 rounded hover:bg-white transition-colors">
                <input 
                  :id="`edit-size-${size}`"
                  v-model="editData.sizes"
                  :value="size"
                  type="checkbox"
                  class="w-4 h-4 text-orange bg-gray-100 border-gray-300 rounded focus:ring-orange focus:ring-2"
                >
                <label :for="`edit-size-${size}`" class="ml-2 text-xs text-gray-700 cursor-pointer">{{ size }}</label>
              </div>
            </div>
          </div>
  
          <!-- Boutons d'action -->
          <div class="flex space-x-3 pt-4 border-t border-gray-200">
            <button 
              type="button"
              @click="$emit('close')"
              class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors"
              :disabled="isLoading"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="flex-1 bg-orange text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors disabled:opacity-50"
              :disabled="isLoading"
            >
              {{ isLoading ? 'Sauvegarde...' : 'Sauvegarder' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, computed, onMounted } from 'vue'
  
  const props = defineProps(['product', 'boutiqueId', 'userId'])
  const emit = defineEmits(['close', 'save'])
  
  const editData = ref(null)
  const isLoading = ref(false)
  const error = ref(null)
  
  // Données des catégories (identiques à addProductModal)
  const categories = ref([
    {
      id: 1,
      name: 'Électronique',
      subcategories: [
        { id: 1, name: 'Smartphones' },
        { id: 2, name: 'Ordinateurs portables' },
        { id: 3, name: 'Tablettes' },
        { id: 4, name: 'Montres connectées' },
        { id: 5, name: 'Écouteurs' },
        { id: 6, name: 'Accessoires' }
      ]
    },
    {
      id: 2,
      name: 'Vêtements',
      subcategories: [
        { id: 7, name: 'T-shirts' },
        { id: 8, name: 'Pantalons' },
        { id: 9, name: 'Robes' },
        { id: 10, name: 'Vestes' },
        { id: 11, name: 'Sous-vêtements' },
        { id: 12, name: 'Pyjamas' }
      ]
    },
    {
      id: 3,
      name: 'Chaussures',
      subcategories: [
        { id: 13, name: 'Baskets' },
        { id: 14, name: 'Chaussures de ville' },
        { id: 15, name: 'Sandales' },
        { id: 16, name: 'Bottes' },
        { id: 17, name: 'Chaussures de sport' }
      ]
    },
    {
      id: 4,
      name: 'Accessoires',
      subcategories: [
        { id: 18, name: 'Sacs' },
        { id: 19, name: 'Bijoux' },
        { id: 20, name: 'Montres' },
        { id: 21, name: 'Ceintures' },
        { id: 22, name: 'Chapeaux' },
        { id: 23, name: 'Lunettes' }
      ]
    },
    {
      id: 5,
      name: 'Maison & Jardin',
      subcategories: [
        { id: 24, name: 'Décoration' },
        { id: 25, name: 'Cuisine' },
        { id: 26, name: 'Salle de bain' },
        { id: 27, name: 'Jardin' },
        { id: 28, name: 'Outils' }
      ]
    }
  ])
  
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
  
  const availableSizes = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45'])
  
  // Computed pour les sous-catégories disponibles
  const availableSubcategories = computed(() => {
    if (!editData.value?.category_id) return []
    const category = categories.value.find(cat => cat.id === editData.value.category_id)
    return category ? category.subcategories : []
  })
  
  // Fonction pour trouver la catégorie par nom ou ID
  const findCategoryByName = (categoryName) => {
    return categories.value.find(cat => 
      cat.name.toLowerCase() === categoryName?.toLowerCase()
    )
  }
  
  // Fonction pour trouver la sous-catégorie par nom
  const findSubcategoryByName = (subcategoryName, categoryId) => {
    const category = categories.value.find(cat => cat.id === categoryId)
    if (!category) return null
    
    return category.subcategories.find(sub => 
      sub.name.toLowerCase() === subcategoryName?.toLowerCase()
    )
  }
  
  // Fonction pour mettre à jour les sous-catégories
  const updateSubcategories = () => {
    if (editData.value) {
      editData.value.subcategory_id = ''
    }
  }
  
  // Watcher pour initialiser les données du produit
  watch(() => props.product, (newProduct) => {
    if (newProduct) {
      console.log('🔄 Initialisation des données du produit:', newProduct)
      
      // Trouver les IDs des catégories
      const category = findCategoryByName(newProduct.category_name)
      const subcategory = category ? findSubcategoryByName(newProduct.subcategory_name, category.id) : null
      
      editData.value = {
        id: newProduct.id,
        name: newProduct.name || '',
        description: newProduct.description || '',
        category_id: category?.id || '',
        subcategory_id: subcategory?.id || '',
        unit_price: newProduct.unit_price || '',
        stock: newProduct.stock || '',
        wholesale_price: newProduct.wholesale_price || '',
        wholesale_min_qty: newProduct.wholesale_min_qty || '',
        hasWholesalePrice: !!(newProduct.wholesale_price && newProduct.wholesale_min_qty),
        status: newProduct.status || 'Actif',
        colors: Array.isArray(newProduct.colors) ? [...newProduct.colors] : [],
        sizes: Array.isArray(newProduct.sizes) ? [...newProduct.sizes] : [],
        sku: newProduct.sku || '',
        tags: newProduct.tags || ''
      }
      
      console.log('✅ Données initialisées:', editData.value)
    }
  }, { immediate: true })
  
  const handleSubmit = async () => {
    if (!editData.value) return
    
    try {
      isLoading.value = true
      error.value = null
      
      // Validation des données requises
      if (!editData.value.name || !editData.value.category_id || !editData.value.subcategory_id || !editData.value.unit_price || !editData.value.stock) {
        error.value = 'Veuillez remplir tous les champs obligatoires'
        return
      }
      
      // Préparer les données pour l'envoi (SANS boutique_id et user_id dans le body)
      const formData = {
        id: editData.value.id,
        name: editData.value.name,
        description: editData.value.description,
        category_id: parseInt(editData.value.category_id),
        subcategory_id: parseInt(editData.value.subcategory_id),
        unit_price: parseFloat(editData.value.unit_price),
        stock: parseInt(editData.value.stock),
        status: editData.value.status,
        colors: editData.value.colors || [],
        sizes: editData.value.sizes || [],
        sku: editData.value.sku,
        tags: editData.value.tags
      }
      
      // Ajouter les données de prix de gros si activées
      if (editData.value.hasWholesalePrice) {
        formData.wholesale_price = parseFloat(editData.value.wholesale_price) || null
        formData.wholesale_min_qty = parseInt(editData.value.wholesale_min_qty) || null
      } else {
        formData.wholesale_price = null
        formData.wholesale_min_qty = null
      }
      
      console.log('📤 Envoi des données (body):', formData)
      console.log('📤 Paramètres GET: boutique_id=' + props.boutiqueId + ', user_id=' + props.userId)
      
      // Émettre les données - les paramètres GET seront gérés dans le dashboard
      emit('save', formData)
      
    } catch (err) {
      console.error('❌ Erreur lors de la sauvegarde:', err)
      error.value = 'Erreur lors de la sauvegarde du produit'
    } finally {
      isLoading.value = false
    }
  }
  
  const handleBackdropClick = (event) => {
    if (event.target === event.currentTarget) {
      emit('close')
    }
  }
  
  // Initialisation au montage du composant
  onMounted(() => {
    console.log('🚀 EditProductModal monté avec le produit:', props.product)
    console.log('🏪 Boutique ID:', props.boutiqueId)
    console.log('👤 User ID:', props.userId)
  })
  </script>
  
  <style scoped>
  .bg-orange {
    background-color: #F65A11;
  }
  
  .text-orange {
    color: #F65A11;
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
  </style>
  