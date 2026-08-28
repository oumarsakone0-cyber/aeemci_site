<template>
  <div class="product-list">
    <div class="product-grid">
      <div v-for="product in products" :key="product.id" class="product-card">
        <div class="product-image-container">
          <img :src="product.image" :alt="product.name" class="product-image" />
          <div class="product-badges" v-if="product.badges && product.badges.length">
            <span 
              v-for="(badge, index) in product.badges" 
              :key="index" 
              class="product-badge"
              :class="getBadgeClass(badge)"
            >
              <span class="badge-icon" v-html="getBadgeIcon(badge)"></span>
              {{ badge }}
            </span>
          </div>
        </div>
        <div class="product-content">
          <div class="product-info">
            <h3 class="product-name">{{ product.name }}</h3>
            <div class="product-price">
              <span class="price-range">{{ product.priceRange }}</span>
              <span class="min-order">{{ product.minOrder }} pièce(s) (Min. commande)</span>
            </div>
            <div class="supplier-info">
              <div class="supplier-name">{{ product.supplier.name }}</div>
              <div class="supplier-badges">
                <span v-if="product.supplier.verified" class="badge verified">Vérifié</span>
                <span v-if="product.supplier.years" class="badge years">{{ product.supplier.years }}A</span>
              </div>
            </div>
          </div>
          <div class="product-actions">
            <button class="action-btn chat-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              Discuter
            </button>
            <button class="action-btn cart-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
              Détail Produit
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="pagination">
      <button class="pagination-btn prev-btn" :disabled="currentPage === 1" @click="changePage(currentPage - 1)">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <button 
        v-for="page in displayedPages" 
        :key="page" 
        class="pagination-btn page-btn" 
        :class="{ 'active': page === currentPage }"
        @click="changePage(page)"
      >
        {{ page }}
      </button>
      <span v-if="totalPages > maxDisplayedPages && currentPage < totalPages - 2" class="pagination-ellipsis">...</span>
      <button 
        v-if="totalPages > maxDisplayedPages && currentPage < totalPages - 2" 
        class="pagination-btn page-btn"
        :class="{ 'active': totalPages === currentPage }"
        @click="changePage(totalPages)"
      >
        {{ totalPages }}
      </button>
      <button class="pagination-btn next-btn" :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductList',
  props: {
    products: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentPage: 1,
      itemsPerPage: 8,
      maxDisplayedPages: 5
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.products.length / this.itemsPerPage)
    },
    displayedPages() {
      if (this.totalPages <= this.maxDisplayedPages) {
        return Array.from({ length: this.totalPages }, (_, i) => i + 1)
      }
      
      let startPage = Math.max(1, this.currentPage - 2)
      let endPage = Math.min(this.totalPages, startPage + this.maxDisplayedPages - 1)
      
      if (endPage - startPage < this.maxDisplayedPages - 1) {
        startPage = Math.max(1, endPage - this.maxDisplayedPages + 1)
      }
      
      return Array.from({ length: Math.min(this.maxDisplayedPages, endPage - startPage + 1) }, (_, i) => startPage + i)
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.products.slice(start, end)
    }
  },
  methods: {
    changePage(page) {
      this.currentPage = page
      // Émettre un événement pour informer le parent du changement de page
      this.$emit('page-changed', page)
      // Faire défiler vers le haut de la liste
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    getBadgeClass(badge) {
      if (badge === "Stock épuisé") {
        return 'badge-red'
      } else if (badge === "Bientôt à baby") {
        return 'badge-blue'
      } else if (badge === "Nouvelle Arrivage") {
        return 'badge-green'
      } else if (badge.startsWith("Réduction")) {
        return 'badge-orange'
      }
      return 'badge-default' // Couleur par défaut (orange)
    },
    getBadgeIcon(badge) {
      if (badge === "Stock épuisé") {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>'
      } else if (badge === "Bientôt à baby") {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plane"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>'
      } else if (badge === "Nouvelle Arrivage") {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>'
      } else if (badge.startsWith("Réduction")) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-percent"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>'
      }
      return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>'
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;900&display=swap');

/* Product List Styles */
.product-list {
  margin-bottom: 40px;
  font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 30px;
}

.product-card {
  background-color: #fff;
  border-radius: 12px;
  overflow: hidden;
  transition: box-shadow 0.3s;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.product-card:hover {
  box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

.product-image-container {
  position: relative;
  padding: 10px;
  background-color: #fff;
  border-bottom: 1px solid #f5f5f5;
}

.product-image {
  width: 100%;
  aspect-ratio: 1;
  object-fit: contain;
  display: block;
  border-radius: 12px;
}

.product-badges {
  position: absolute;
  top: 15px;
  left: 15px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.product-badge {
  color: white;
  font-size: 11px;
  padding: 3px 6px;
  border-radius: 2px;
  font-weight: 500;
  display: flex;
  align-items: center;
}

.badge-icon {
  display: flex;
  align-items: center;
  margin-right: 4px;
}

.badge-green {
  background-color: #10b981; /* Vert émeraude */
  color: white;
}

.badge-icon svg {
  stroke: currentColor;
}

.badge-default {
  background-color: #FF6A00;
}

.badge-red {
  background-color: #f5222d;
}

.badge-blue {
  background-color: #1890ff;
}

.badge-orange {
  background-color: #FF6A00;
}

.product-content {
  display: flex;
  flex-direction: column;
  flex: 1;
  padding: 15px;
}

.product-info {
  flex: 1;
}

.product-name {
  font-size: 14px;
  font-weight: 400;
  margin-bottom: 10px;
  line-height: 1.4;
  height: 40px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  color: #333;
}

.product-price {
  margin-bottom: 10px;
}

.price-range {
  display: block;
  font-size: 18px;
  line-height: 30px;
  font-weight: 900;
  color: black;
  margin-bottom: 5px;
}

.min-order {
  display: block;
  font-size: 12px;
  color: #666;
}

.supplier-info {
  margin-bottom: 15px;
}

.supplier-name {
  font-size: 13px;
  margin-bottom: 5px;
  color: #666;
}

.supplier-badges {
  display: flex;
  gap: 5px;
}

.badge {
  display: inline-block;
  padding: 2px 6px;
  font-size: 11px;
  border-radius: 3px;
}

.verified {
  background-color: #e6f7ff;
  color: #1890ff;
  border: 1px solid #91d5ff;
}

.years {
  background-color: #f6ffed;
  color: #52c41a;
  border: 1px solid #b7eb8f;
}

.product-actions {
  display: flex;
  gap: 10px;
  margin-top: auto;
  padding-top: 15px;
}

.action-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  padding: 8px 0;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid;
}

.chat-btn {
  background-color: white;
  color: #FF6A00;
  border-color: #FF6A00;
}

.chat-btn:hover {
  background-color: #fff5f0;
}

.cart-btn {
  background-color: white;
  color: #1890ff;
  border-color: #1890ff;
}

.cart-btn:hover {
  background-color: #f0f7ff;
}

/* Pagination Styles */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 5px;
  margin-top: 30px;
}

.pagination-btn {
  min-width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #d9d9d9;
  background-color: white;
  border-radius: 4px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  color: #FF6A00;
  border-color: #FF6A00;
}

.pagination-btn.active {
  color: white;
  background-color: #FF6A00;
  border-color: #FF6A00;
}

.pagination-btn:disabled {
  color: #d9d9d9;
  cursor: not-allowed;
}

.pagination-ellipsis {
  display: inline-block;
  vertical-align: middle;
  line-height: 32px;
  color: #666;
}

@media (max-width: 992px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .product-grid {
    grid-template-columns: 1fr;
  }
  
  .product-actions {
    flex-direction: column;
  }
}
</style>