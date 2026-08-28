<template>
    <div class="sidebar">
      <div class="sidebar-section">
        <h3 class="sidebar-title">Catégories</h3>
        <ul class="category-list">
          <li v-for="category in categories" :key="category.id" class="category-item">
            <router-link :to="`/category/${category.id}`" class="category-link">
              {{ category.name }}
            </router-link>
          </li>
        </ul>
      </div>
  
      <div class="sidebar-section">
        <h3 class="sidebar-title">Fournisseurs</h3>
        <ul class="filter-list">
          <li v-for="supplier in filters.suppliers" :key="supplier.id" class="filter-item">
            <label class="checkbox-label">
              <input type="checkbox" class="filter-checkbox" />
              <span class="checkbox-text">{{ supplier.name }}</span>
              <span class="count">({{ supplier.count }})</span>
            </label>
          </li>
        </ul>
      </div>
  
      <div class="sidebar-section">
        <h3 class="sidebar-title">Prix</h3>
        <div class="price-filter">
          <div class="price-inputs">
            <input type="number" placeholder="Min" class="price-input" />
            <span class="price-separator">-</span>
            <input type="number" placeholder="Max" class="price-input" />
          </div>
          <button class="price-button">OK</button>
        </div>
      </div>
  
      <div class="sidebar-section">
        <h3 class="sidebar-title">Commande minimum</h3>
        <ul class="filter-list">
          <li v-for="order in filters.minOrder" :key="order.id" class="filter-item">
            <label class="checkbox-label">
              <input type="checkbox" class="filter-checkbox" />
              <span class="checkbox-text">{{ order.name }}</span>
              <span class="count">({{ order.count }})</span>
            </label>
          </li>
        </ul>
      </div>
  
      <div class="sidebar-section">
        <h3 class="sidebar-title">Pays/Région du fournisseur</h3>
        <ul class="filter-list">
          <li v-for="country in filters.countries" :key="country.id" class="filter-item">
            <label class="checkbox-label">
              <input type="checkbox" class="filter-checkbox" />
              <span class="checkbox-text">{{ country.name }}</span>
              <span class="count">({{ country.count }})</span>
            </label>
          </li>
        </ul>
        <button class="view-more-btn">Voir plus</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'SideBar',
    props: {
      categories: Array,
      filters: Object
    }
  }
  </script>
  
  <style scoped>
  .sidebar {
    width: 200px;
    flex-shrink: 0;
  }
  
  .sidebar-section {
    background-color: #fff;
    border-radius: 4px;
    margin-bottom: 15px;
    padding: 15px;
  }
  
  .sidebar-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
  }
  
  .category-list, .filter-list {
    list-style: none;
  }
  
  .category-item, .filter-item {
    margin-bottom: 8px;
  }
  
  .category-link {
    color: #333;
    text-decoration: none;
    font-size: 13px;
    display: block;
    padding: 4px 0;
  }
  
  .category-link:hover {
    color: #FF6A00;
  }
  
  .checkbox-label {
    display: flex;
    align-items: center;
    font-size: 13px;
    cursor: pointer;
  }
  
  .filter-checkbox {
    margin-right: 8px;
  }
  
  .count {
    margin-left: 4px;
    color: #999;
  }
  
  .price-filter {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .price-inputs {
    display: flex;
    align-items: center;
    flex: 1;
  }
  
  .price-input {
    width: 70px;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 13px;
  }
  
  .price-separator {
    margin: 0 5px;
  }
  
  .price-button {
    background-color: #FF6A00;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 13px;
    cursor: pointer;
  }
  
  .view-more-btn {
    background: none;
    border: none;
    color: #FF6A00;
    font-size: 13px;
    padding: 5px 0;
    cursor: pointer;
    margin-top: 5px;
  }
  
  @media (max-width: 768px) {
    .sidebar {
      width: 100%;
      margin-bottom: 20px;
    }
  }
  </style>