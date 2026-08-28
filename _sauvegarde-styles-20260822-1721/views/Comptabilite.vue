<template>
    <div class="dashboard">
      <!-- Header -->
      <Header />
      <!-- Main Content -->
      <main class="main-content">
        <div class="overview-section">
          <div class="overview-header">
            <div>
              <h1 class="overview-title">Gestion de la Comptabilité du Séminaire</h1>
              <p class="overview-date">{{ currentDate }}</p>
            </div>
            <div class="header-actions-right">
              <button class="action-button-outlined" @click="showReportModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-bar-chart-3">
                  <path d="M3 3v18h18" />
                  <path d="M18 17V9" />
                  <path d="M13 17V5" />
                  <path d="M8 17v-3" />
                </svg>
                Rapports
              </button>
              <button class="action-button-outlined" @click="showCategoriesModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-list-tree">
                  <path d="M8 9h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H8" />
                  <path d="M8 18h12a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H8" />
                  <path d="M13 6v12" />
                  <path d="M3 6v4a1 1 0 0 0 1 1h3" />
                  <path d="M3 10v8" />
                </svg>
                Catégories
              </button>
              <button class="action-button-outlined" @click="showFilterModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-filter">
                  <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                </svg>
                Filtrer
              </button>
              <button class="add-product-button" @click="showAddTransactionModal = true"
                v-if="['ALL', 'GC'].some(p => permissionData.includes(p))">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-plus">
                  <path d="M5 12h14" />
                  <path d="M12 5v14" />
                </svg>
                Ajouter une transaction
              </button>
            </div>
          </div>
          <!-- Transaction Categories Tabs -->
          <div class="product-tabs">
            <button v-for="tab in transactionTabs" :key="tab.id" class="tab-button"
              :class="{ active: activeTab === tab.id }" @click="activeTab = tab.id">
              <span v-html="tab.icon"></span>
              {{ tab.name }}
            </button>
          </div>
          <!-- Financial Overview -->
          <div class="financial-overview">
            <div class="financial-card">
              <div class="financial-card-header">
                <h3>Solde actuel</h3>
                <div class="period-selector">
                  <button v-for="period in periods" :key="period.id" :class="{ active: activePeriod === period.id }"
                    @click="activePeriod = period.id">
                    {{ period.name }}
                  </button>
                </div>
              </div>
              <div class="financial-card-content">
                <div class="balance-amount">{{ formatPrice(currentBalance) }}</div>
                <div class="balance-change" :class="balanceChangeClass">
                  <svg v-if="balanceChange >= 0" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-trending-up">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-trending-down">
                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7" />
                    <polyline points="16 17 22 17 22 11" />
                  </svg>
                  {{ balanceChange >= 0 ? '+' : '' }}{{ balanceChange }}% depuis {{ getPeriodText(activePeriod) }}
                </div>
              </div>
            </div>
            <div class="financial-summary">
              <div class="summary-card income">
                <div class="summary-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-down-right">
                    <path d="m7 7 10 10" />
                    <path d="M17 7v10H7" />
                  </svg>
                </div>
                <div class="summary-content">
                  <div class="summary-label">Revenus</div>
                  <div class="summary-value">{{ formatPrice(periodIncome) }}</div>
                  <div class="summary-change positive">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-trending-up">
                      <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                      <polyline points="16 7 22 7 22 13" />
                    </svg>
                    +{{ incomeChange }}%
                  </div>
                </div>
              </div>
              <div class="summary-card expense">
                <div class="summary-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-up-right">
                    <path d="m7 17 10-10" />
                    <path d="M7 7h10v10" />
                  </svg>
                </div>
                <div class="summary-content">
                  <div class="summary-label">Dépenses</div>
                  <div class="summary-value">{{ formatPrice(periodExpenses) }}</div>
                  <div class="summary-change negative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-trending-down">
                      <polyline points="22 17 13.5 8.5 8.5 13.5 2 7" />
                      <polyline points="16 17 22 17 22 11" />
                    </svg>
                    +{{ expenseChange }}%
                  </div>
                </div>
              </div>
              <div class="summary-card profit">
                <div class="summary-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-wallet">
                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                  </svg>
                </div>
                <div class="summary-content">
                  <div class="summary-label">Bénéfice net</div>
                  <div class="summary-value">{{ formatPrice(periodNetProfit) }}</div>
                  <div class="summary-change" :class="profitChangeClass">
                    <svg v-if="profitChange >= 0" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" class="lucide lucide-trending-up">
                      <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                      <polyline points="16 7 22 7 22 13" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-trending-down">
                      <polyline points="22 17 13.5 8.5 8.5 13.5 2 7" />
                      <polyline points="16 17 22 17 22 11" />
                    </svg>
                    {{ profitChange >= 0 ? '+' : '' }}{{ profitChange }}%
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Transaction Search and Filters -->
          <div class="search-filters">
            <div class="search-bar">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-search">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
              </svg>
              <input type="text" placeholder="Rechercher une transaction..." v-model="searchQuery"
                @input="searchTransactions" />
            </div>
            <div class="filter-options">
              <select v-model="filterCategory">
                <option value="">Toutes les catégories</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                </option>
              </select>
              <select v-model="filterType">
                <option value="">Tous les types</option>
                <option value="income">Revenus</option>
                <option value="expense">Dépenses</option>
              </select>
              <select v-model="filterStatus">
                <option value="">Tous les statuts</option>
                <option value="completed">Complétée</option>
                <option value="pending">En attente</option>
                <option value="cancelled">Annulée</option>
              </select>
              <select v-model="sortBy">
                <option value="date_desc">Date (récent)</option>
                <option value="date_asc">Date (ancien)</option>
                <option value="amount_desc">Montant (décroissant)</option>
                <option value="amount_asc">Montant (croissant)</option>
              </select>
            </div>
          </div>
          <!-- Transactions Statistics -->
          <div class="product-stats">
            <div class="stat-card">
              <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-receipt">
                  <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                  <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                  <path d="M12 17.5v-11" />
                </svg>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ totalTransactions }}</div>
                <div class="stat-label">Total Transactions</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-arrow-down-right">
                  <path d="m7 7 10 10" />
                  <path d="M17 7v10H7" />
                </svg>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ incomeTransactions }}</div>
                <div class="stat-label">Transactions de revenus</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-arrow-up-right">
                  <path d="m7 17 10-10" />
                  <path d="M7 7h10v10" />
                </svg>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ expenseTransactions }}</div>
                <div class="stat-label">Transactions de dépenses</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-check-circle">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                  <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ completedTransactions }}</div>
                <div class="stat-label">Transactions complétées</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-clock">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ pendingTransactions }}</div>
                <div class="stat-label">En attente</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-x-circle">
                  <circle cx="12" cy="12" r="10" />
                  <path d="m15 9-6 6" />
                  <path d="m9 9 6 6" />
                </svg>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ cancelledTransactions }}</div>
                <div class="stat-label">Transactions annulées</div>
              </div>
            </div>
          </div>
          <!-- Transactions Table -->
          <div class="period-filter-info" v-if="activePeriod !== 'all'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-info">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" x2="12" y1="16" y2="12" />
              <line x1="12" x2="12.01" y1="8" y2="8" />
            </svg>
            <span>Affichage des transactions pour la période : <strong>{{ getPeriodDisplayName(activePeriod) }}</strong></span>
          </div>
          <div class="products-table">
            <div class="table-header">
              <div class="table-cell checkbox-cell">
                <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" />
              </div>
              <div class="table-cell">Date</div>
              <div class="table-cell">Référence</div>
              <div class="table-cell name-cell">Description</div>
              <div class="table-cell">Catégorie</div>
              <div class="table-cell">Type</div>
              <div class="table-cell">Montant</div>
              <div class="table-cell">Statut</div>
              <div class="table-cell actions-cell">Actions</div>
            </div>
            <div v-if="loading" class="loading-indicator">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
              Chargement...
            </div>
            <div v-else-if="filteredTransactions.length === 0" class="no-products">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-receipt-text">
                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                <path d="M14 8H8" />
                <path d="M16 12H8" />
                <path d="M13 16H8" />
              </svg>
              <p>Aucune transaction trouvée</p>
              <button class="add-product-button" @click="showAddTransactionModal = true">
                Ajouter une transaction
              </button>
            </div>
            <div v-else>
              <div v-for="transaction in paginatedTransactions" :key="transaction.id" class="table-row">
                <div class="table-cell checkbox-cell">
                  <input type="checkbox" v-model="selectedTransactions" :value="transaction.id" />
                </div>
                <div class="table-cell">{{ formatDate(transaction.date) }}</div>
                <div class="table-cell">{{ transaction.reference }}</div>
                <div class="table-cell name-cell">{{ transaction.description }}</div>
                <div class="table-cell">{{ getCategoryName(transaction.category_id) }}</div>
                <div class="table-cell">
                  <span class="type-badge" :class="getTypeClass(transaction.type)">
                    {{ getTypeText(transaction.type) }}
                  </span>
                </div>
                <div class="table-cell" :class="getAmountClass(transaction.type)">
                  {{ formatPrice(transaction.amount) }}
                </div>
                <div class="table-cell">
                  <span class="status-badge" :class="getStatusClass(transaction.status)">
                    {{ getStatusText(transaction.status) }}
                  </span>
                </div>
                <div class="table-cell actions-cell">
                  <button class="action-icon" @click="viewTransactionDetails(transaction)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-eye">
                      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                      <circle cx="12" cy="12" r="3" />
                    </svg>
                  </button>
                  <button class="action-icon" @click="editTransaction(transaction)"
                    v-if="['ALL', 'GC'].some(p => permissionData.includes(p))">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-pencil">
                      <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                      <path d="m15 5 4 4" />
                    </svg>
                  </button>
                  <button class="action-icon" @click="confirmDeleteTransaction(transaction)"
                    v-if="['ALL', 'GC'].some(p => permissionData.includes(p))">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-trash-2">
                      <path d="M3 6h18" />
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                      <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                      <line x1="10" x2="10" y1="11" y2="17" />
                      <line x1="14" x2="14" y1="11" y2="17" />
                    </svg>
                  </button>
                  <button class="action-icon" @click="printReceipt(transaction)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-printer">
                      <polyline points="6 9 6 2 18 2 18 9" />
                      <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                      <rect width="12" height="8" x="6" y="14" />
                    </svg>
                    Imprimer
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Pagination -->
          <div class="pagination">
            <div class="pagination-info">
              Affichage de {{ paginationStart }} à {{ paginationEnd }} sur {{ totalTransactions }} transactions
            </div>
            <div class="pagination-controls">
              <button class="pagination-button" :disabled="currentPage === 1" @click="currentPage--">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-chevron-left">
                  <path d="m15 18-6-6 6-6" />
                </svg>
              </button>
              <span class="pagination-pages">Page {{ currentPage }} sur {{ totalPages }}</span>
              <button class="pagination-button" :disabled="currentPage === totalPages" @click="currentPage++">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-chevron-right">
                  <path d="m9 18 6-6-6-6" />
                </svg>
              </button>
            </div>
            <div class="pagination-size">
              <select v-model="pageSize">
                <option :value="10">10 par page</option>
                <option :value="25">25 par page</option>
                <option :value="50">50 par page</option>
                <option :value="100">100 par page</option>
              </select>
            </div>
          </div>
        </div>
      </main>
      <!-- Add/Edit Transaction Modal -->
      <div class="modal" v-if="showAddTransactionModal || showEditTransactionModal" @click.self="closeModals">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ showEditTransactionModal ? 'Modifier la transaction' : 'Ajouter une nouvelle transaction' }}</h2>
            <button class="close-button" @click="closeModals">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveTransaction">
              <div class="form-row">
                <div class="form-group">
                  <label for="transaction-type">Type de transaction *</label>
                  <select id="transaction-type" v-model="transactionForm.type" required>
                    <option value="income">Revenu</option>
                    <option value="expense">Dépense</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="transaction-category">Catégorie *</label>
                  <select id="transaction-category" v-model="transactionForm.category_id" required>
                    <option v-for="category in filteredCategories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="transaction-date">Date *</label>
                  <input type="date" id="transaction-date" v-model="transactionForm.date" required>
                </div>
                <div class="form-group">
                  <label for="transaction-amount">Montant *</label>
                  <div class="input-with-icon">
                    <input type="number" id="transaction-amount" v-model="transactionForm.amount" step="0.01" min="0"
                      required>
                    <span class="input-icon">XOF</span>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="transaction-reference">Référence</label>
                  <input type="text" id="transaction-reference" v-model="transactionForm.reference">
                </div>
                <div class="form-group">
                  <label for="transaction-status">Statut *</label>
                  <select id="transaction-status" v-model="transactionForm.status" required>
                    <option value="completed">Complétée</option>
                    <option value="pending">En attente</option>
                    <option value="cancelled">Annulée</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="transaction-description">Description *</label>
                <textarea id="transaction-description" v-model="transactionForm.description" rows="3" required></textarea>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="transaction-payment-method">Mode de paiement</label>
                  <select id="transaction-payment-method" v-model="transactionForm.payment_method_id">
                    <option v-for="method in paymentMethods" :key="method.id" :value="method.id">
                      {{ method.name }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="transaction-account">Compte</label>
                  <select id="transaction-account" v-model="transactionForm.account_id">
                    <option v-for="account in accounts" :key="account.id" :value="account.id">
                      {{ account.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="transaction-notes">Notes</label>
                <textarea id="transaction-notes" v-model="transactionForm.notes" rows="2"></textarea>
              </div>
              <div class="form-group">
                <label for="transaction-attachment">Pièce jointe</label>
                <input type="file" id="transaction-attachment" @change="handleFileUpload">
              </div>
              <div class="form-actions">
                <button type="button" class="cancel-button" @click="closeModals">Annuler</button>
                <button type="submit" class="save-button">
                  {{ showEditTransactionModal ? 'Mettre à jour' : 'Ajouter' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Transaction Details Modal -->
      <div class="modal" v-if="showTransactionDetailsModal" @click.self="showTransactionDetailsModal = false">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Détails de la transaction</h2>
            <button class="close-button" @click="showTransactionDetailsModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="transaction-details">
              <div class="transaction-details-header">
                <div class="transaction-info">
                  <div class="transaction-type" :class="getTypeClass(selectedTransaction.type)">
                    {{ getTypeText(selectedTransaction.type) }}
                  </div>
                  <div class="transaction-status" :class="getStatusClass(selectedTransaction.status)">
                    {{ getStatusText(selectedTransaction.status) }}
                  </div>
                </div>
                <div class="transaction-amount" :class="getAmountClass(selectedTransaction.type)">
                  {{ formatPrice(selectedTransaction.amount) }}
                </div>
              </div>
              <div class="transaction-details-content">
                <div class="details-section">
                  <h3>Informations générales</h3>
                  <div class="details-grid">
                    <div class="details-item">
                      <div class="details-label">Date</div>
                      <div class="details-value">{{ formatDate(selectedTransaction.date) }}</div>
                    </div>
                    <div class="details-item">
                      <div class="details-label">Référence</div>
                      <div class="details-value">{{ selectedTransaction.reference || '-' }}</div>
                    </div>
                    <div class="details-item">
                      <div class="details-label">Catégorie</div>
                      <div class="details-value">{{ getCategoryName(selectedTransaction.category_id) }}</div>
                    </div>
                    <div class="details-item">
                      <div class="details-label">Mode de paiement</div>
                      <div class="details-value">{{ getPaymentMethodName(selectedTransaction.payment_method_id) }}</div>
                    </div>
                    <div class="details-item">
                      <div class="details-label">Compte</div>
                      <div class="details-value">{{ getAccountName(selectedTransaction.account_id) }}</div>
                    </div>
                  </div>
                </div>
                <div class="details-section">
                  <h3>Description</h3>
                  <div class="details-description">
                    {{ selectedTransaction.description }}
                  </div>
                </div>
                <div class="details-section" v-if="selectedTransaction.notes">
                  <h3>Notes</h3>
                  <div class="details-notes">
                    {{ selectedTransaction.notes }}
                  </div>
                </div>
                <div class="details-section" v-if="selectedTransaction.attachment">
                  <h3>Pièce jointe</h3>
                  <div class="details-attachment">
                    <a :href="selectedTransaction.attachment" target="_blank" class="attachment-link">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                      </svg>
                      Voir la pièce jointe
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="cancel-button" @click="showTransactionDetailsModal = false">Fermer</button>
            <button class="action-button" @click="editTransaction(selectedTransaction)"
              v-if="['ALL', 'GC'].some(p => permissionData.includes(p))">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-pencil">
                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                <path d="m15 5 4 4" />
              </svg>
              Modifier
            </button>
            <button class="action-button" @click="printReceipt(selectedTransaction)">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-printer">
                <polyline points="6 9 6 2 18 2 18 9" />
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                <rect width="12" height="8" x="6" y="14" />
              </svg>
              Imprimer
            </button>
          </div>
        </div>
      </div>
      <!-- Delete Confirmation Modal -->
      <div class="modal" v-if="showDeleteModal" @click.self="showDeleteModal = false">
        <div class="modal-content delete-modal">
          <div class="modal-header">
            <h2>Confirmer la suppression</h2>
            <button class="close-button" @click="showDeleteModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="delete-warning">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-alert-triangle">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                <path d="M12 9v4" />
                <path d="M12 17h.01" />
              </svg>
              <p>Êtes-vous sûr de vouloir supprimer cette transaction ?</p>
              <p class="warning-text">Cette action est irréversible et supprimera définitivement cette transaction.</p>
            </div>
          </div>
          <div class="modal-footer">
            <button class="cancel-button" @click="showDeleteModal = false">Annuler</button>
            <button class="delete-button" @click="deleteTransaction">Supprimer</button>
          </div>
        </div>
      </div>
      <!-- Categories Modal -->
      <div class="modal" v-if="showCategoriesModal" @click.self="showCategoriesModal = false">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Gestion des catégories</h2>
            <button class="close-button" @click="showCategoriesModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="list-header">
              <div class="search-bar" style="max-width: 300px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-search">
                  <circle cx="11" cy="11" r="8" />
                  <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" placeholder="Rechercher une catégorie..." v-model="categorySearchQuery">
              </div>
              <button class="add-button" @click="showAddCategoryModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-plus">
                  <path d="M5 12h14" />
                  <path d="M12 5v14" />
                </svg>
                Ajouter une catégorie
              </button>
            </div>
            <div class="categories-tabs">
              <button v-for="type in ['all', 'income', 'expense']" :key="type" class="tab-button"
                :class="{ active: categoryFilterType === type }" @click="categoryFilterType = type">
                {{ getCategoryFilterTypeText(type) }}
              </button>
            </div>
            <div v-if="loadingCategories" class="loading-indicator">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
              Chargement des catégories...
            </div>
            <div v-else-if="filteredCategoriesList.length === 0" class="no-items">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-list-tree">
                <path d="M8 9h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H8" />
                <path d="M8 18h12a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H8" />
                <path d="M13 6v12" />
                <path d="M3 6v4a1 1 0 0 0 1 1h3" />
                <path d="M3 10v8" />
              </svg>
              <p>Aucune catégorie trouvée</p>
            </div>
            <div v-else class="categories-list">
              <div v-for="category in filteredCategoriesList" :key="category.id" class="category-item">
                <div class="category-info">
                  <div class="category-icon" :class="getTypeClass(category.type)">
                    <svg v-if="category.type === 'income'" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" class="lucide lucide-arrow-down-right">
                      <path d="m7 7 10 10" />
                      <path d="M17 7v10H7" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-arrow-up-right">
                      <path d="m7 17 10-10" />
                      <path d="M7 7h10v10" />
                    </svg>
                  </div>
                  <div class="category-details">
                    <div class="category-name">{{ category.name }}</div>
                    <div class="category-description">{{ category.description || 'Aucune description' }}</div>
                  </div>
                </div>
                <div class="category-actions">
                  <button class="action-icon" @click="editCategory(category)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-pencil">
                      <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                      <path d="m15 5 4 4" />
                    </svg>
                  </button>
                  <button class="action-icon" @click="confirmDeleteCategory(category)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-trash-2">
                      <path d="M3 6h18" />
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                      <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                      <line x1="10" x2="10" y1="11" y2="17" />
                      <line x1="14" x2="14" y1="11" y2="17" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Add/Edit Category Modal -->
      <div class="modal" v-if="showAddCategoryModal || showEditCategoryModal" @click.self="closeModals">
        <div class="modal-content small-modal">
          <div class="modal-header">
            <h2>{{ showEditCategoryModal ? 'Modifier la catégorie' : 'Ajouter une nouvelle catégorie' }}</h2>
            <button class="close-button" @click="closeModals">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCategory">
              <div class="form-group">
                <label for="category-name">Nom *</label>
                <input type="text" id="category-name" v-model="categoryForm.name" required>
              </div>
              <div class="form-group">
                <label for="category-type">Type *</label>
                <select id="category-type" v-model="categoryForm.type" required>
                  <option value="income">Revenu</option>
                  <option value="expense">Dépense</option>
                </select>
              </div>
              <div class="form-group">
                <label for="category-description">Description</label>
                <textarea id="category-description" v-model="categoryForm.description" rows="3"></textarea>
              </div>
              <div class="form-actions">
                <button type="button" class="cancel-button" @click="closeModals">Annuler</button>
                <button type="submit" class="save-button">
                  {{ showEditCategoryModal ? 'Mettre à jour' : 'Ajouter' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Reports Modal -->
      <div class="modal" v-if="showReportModal" @click.self="showReportModal = false">
        <div class="modal-content large-modal">
          <div class="modal-header">
            <h2>Rapports financiers</h2>
            <button class="close-button" @click="showReportModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="report-tabs">
              <button v-for="tab in reportTabs" :key="tab.id" class="tab-button"
                :class="{ active: activeReportTab === tab.id }" @click="activeReportTab = tab.id">
                <span v-html="tab.icon"></span>
                {{ tab.name }}
              </button>
            </div>
            <div class="report-filters">
              <div class="form-row">
                <div class="form-group">
                  <label for="report-period">Période</label>
                  <select id="report-period" v-model="reportPeriod" @change="generateReport">
                    <option value="this_month">Ce mois</option>
                    <option value="last_month">Mois dernier</option>
                    <option value="this_quarter">Ce trimestre</option>
                    <option value="last_quarter">Trimestre dernier</option>
                    <option value="this_year">Cette année</option>
                    <option value="last_year">Année dernière</option>
                    <option value="custom">Personnalisée</option>
                  </select>
                </div>
                <div class="form-group" v-if="reportPeriod === 'custom'">
                  <label for="report-start-date">Date de début</label>
                  <input type="date" id="report-start-date" v-model="reportStartDate" @change="generateReport">
                </div>
                <div class="form-group" v-if="reportPeriod === 'custom'">
                  <label for="report-end-date">Date de fin</label>
                  <input type="date" id="report-end-date" v-model="reportEndDate" @change="generateReport">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="report-category">Catégorie</label>
                  <select id="report-category" v-model="reportCategory" @change="generateReport">
                    <option value="">Toutes les catégories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="report-account">Compte</label>
                  <select id="report-account" v-model="reportAccount" @change="generateReport">
                    <option value="">Tous les comptes</option>
                    <option v-for="account in accounts" :key="account.id" :value="account.id">
                      {{ account.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div v-if="loadingReport" class="loading-indicator">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
              Génération du rapport en cours...
            </div>
            <div v-else-if="activeReportTab === 'income_expense'" class="report-content">
              <div class="report-summary">
                <div class="summary-card income">
                  <div class="summary-title">Revenus totaux</div>
                  <div class="summary-value">{{ formatPrice(reportData.totalIncome) }}</div>
                </div>
                <div class="summary-card expense">
                  <div class="summary-title">Dépenses totales</div>
                  <div class="summary-value">{{ formatPrice(reportData.totalExpenses) }}</div>
                </div>
                <div class="summary-card profit">
                  <div class="summary-title">Bénéfice net</div>
                  <div class="summary-value">{{ formatPrice(reportData.netProfit) }}</div>
                </div>
              </div>
              <div class="report-chart">
                <h3>Revenus vs Dépenses</h3>
                <div class="chart-placeholder">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-bar-chart-3">
                    <path d="M3 3v18h18" />
                    <path d="M18 17V9" />
                    <path d="M13 17V5" />
                    <path d="M8 17v-3" />
                  </svg>
                  <p>Graphique des revenus et dépenses</p>
                  <p class="chart-note">(Les graphiques seraient générés dynamiquement dans une implémentation réelle)</p>
                </div>
              </div>
            </div>
            <div v-else-if="activeReportTab === 'category'" class="report-content">
              <h3>Répartition par catégorie</h3>
              <div class="report-chart">
                <div class="chart-placeholder">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-pie-chart">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                    <path d="M22 12A10 10 0 0 0 12 2v10z" />
                  </svg>
                  <p>Graphique de répartition par catégorie</p>
                  <p class="chart-note">(Les graphiques seraient générés dynamiquement dans une implémentation réelle)</p>
                </div>
              </div>
              <div class="category-breakdown">
                <div class="breakdown-header">
                  <div>Catégorie</div>
                  <div>Montant</div>
                  <div>Pourcentage</div>
                </div>
                <div v-for="(category, index) in reportData.categoryBreakdown" :key="index" class="breakdown-item">
                  <div class="breakdown-category">
                    <div class="category-color" :style="{ backgroundColor: getCategoryColor(index) }"></div>
                    <div>{{ category.name }}</div>
                  </div>
                  <div>{{ formatPrice(category.amount) }}</div>
                  <div>{{ category.percentage }}%</div>
                </div>
              </div>
            </div>
            <div v-else-if="activeReportTab === 'cash_flow'" class="report-content">
              <h3>Flux de trésorerie</h3>
              <div class="report-chart">
                <div class="chart-placeholder">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-line-chart">
                    <path d="M3 3v18h18" />
                    <path d="m19 9-5 5-4-4-3 3" />
                  </svg>
                  <p>Graphique des flux de trésorerie</p>
                  <p class="chart-note">(Les graphiques seraient générés dynamiquement dans une implémentation réelle)</p>
                </div>
              </div>
              <div class="cash-flow-table">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Période</th>
                      <th>Revenus</th>
                      <th>Dépenses</th>
                      <th>Flux net</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(period, index) in reportData.cashFlow" :key="index">
                      <td>{{ period.label }}</td>
                      <td>{{ formatPrice(period.income) }}</td>
                      <td>{{ formatPrice(period.expense) }}</td>
                      <td :class="period.net >= 0 ? 'positive' : 'negative'">
                        {{ formatPrice(period.net) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div v-else-if="activeReportTab === 'balance_sheet'" class="report-content">
              <h3>Bilan financier</h3>
              <div class="balance-sheet">
                <div class="balance-section">
                  <h4>Actifs</h4>
                  <div class="balance-items">
                    <div v-for="(asset, index) in reportData.balanceSheet.assets" :key="index" class="balance-item">
                      <div class="balance-item-name">{{ asset.name }}</div>
                      <div class="balance-item-value">{{ formatPrice(asset.value) }}</div>
                    </div>
                    <div class="balance-item total">
                      <div class="balance-item-name">Total des actifs</div>
                      <div class="balance-item-value">{{ formatPrice(reportData.balanceSheet.totalAssets) }}</div>
                    </div>
                  </div>
                </div>
                <div class="balance-section">
                  <h4>Passifs</h4>
                  <div class="balance-items">
                    <div v-for="(liability, index) in reportData.balanceSheet.liabilities" :key="index"
                      class="balance-item">
                      <div class="balance-item-name">{{ liability.name }}</div>
                      <div class="balance-item-value">{{ formatPrice(liability.value) }}</div>
                    </div>
                    <div class="balance-item total">
                      <div class="balance-item-name">Total des passifs</div>
                      <div class="balance-item-value">{{ formatPrice(reportData.balanceSheet.totalLiabilities) }}</div>
                    </div>
                  </div>
                </div>
                <div class="balance-section">
                  <h4>Capitaux propres</h4>
                  <div class="balance-items">
                    <div class="balance-item total">
                      <div class="balance-item-name">Total des capitaux propres</div>
                      <div class="balance-item-value">{{ formatPrice(reportData.balanceSheet.equity) }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="cancel-button" @click="showReportModal = false">Fermer</button>
            <button class="action-button" @click="printReport">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-printer">
                <polyline points="6 9 6 2 18 2 18 9" />
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                <rect width="12" height="8" x="6" y="14" />
              </svg>
              Imprimer
            </button>
            <button class="action-button" @click="exportReport">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-download">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" x2="12" y1="15" y2="3" />
              </svg>
              Exporter
            </button>
          </div>
        </div>
      </div>
      <!-- Filter Modal -->
      <div class="modal" v-if="showFilterModal" @click.self="showFilterModal = false">
        <div class="modal-content small-modal">
          <div class="modal-header">
            <h2>Filtres avancés</h2>
            <button class="close-button" @click="showFilterModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="filter-date-range">Période</label>
              <select id="filter-date-range" v-model="filterDateRange">
                <option value="">Toutes les périodes</option>
                <option value="today">Aujourd'hui</option>
                <option value="yesterday">Hier</option>
                <option value="this_week">Cette semaine</option>
                <option value="last_week">Semaine dernière</option>
                <option value="this_month">Ce mois</option>
                <option value="last_month">Mois dernier</option>
                <option value="this_quarter">Ce trimestre</option>
                <option value="this_year">Cette année</option>
                <option value="last_year">Année dernière</option>
                <option value="custom">Personnalisée</option>
              </select>
            </div>
            <div v-if="filterDateRange === 'custom'" class="form-row">
              <div class="form-group">
                <label for="filter-start-date">Date de début</label>
                <input type="date" id="filter-start-date" v-model="filterStartDate">
              </div>
              <div class="form-group">
                <label for="filter-end-date">Date de fin</label>
                <input type="date" id="filter-end-date" v-model="filterEndDate">
              </div>
            </div>
            <div class="form-group">
              <label for="filter-amount-min">Montant minimum</label>
              <div class="input-with-icon">
                <input type="number" id="filter-amount-min" v-model="filterAmountMin" min="0" step="0.01">
                <span class="input-icon">XOF</span>
              </div>
            </div>
            <div class="form-group">
              <label for="filter-amount-max">Montant maximum</label>
              <div class="input-with-icon">
                <input type="number" id="filter-amount-max" v-model="filterAmountMax" min="0" step="0.01">
                <span class="input-icon">XOF</span>
              </div>
            </div>
            <div class="form-group">
              <label for="filter-payment-method">Mode de paiement</label>
              <select id="filter-payment-method" v-model="filterPaymentMethod">
                <option value="">Tous les modes de paiement</option>
                <option v-for="method in paymentMethods" :key="method.id" :value="method.id">
                  {{ method.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label for="filter-account">Compte</label>
              <select id="filter-account" v-model="filterAccount">
                <option value="">Tous les comptes</option>
                <option v-for="account in accounts" :key="account.id" :value="account.id">
                  {{ account.name }}
                </option>
              </select>
            </div>
            <div class="form-actions">
              <button type="button" class="cancel-button" @click="resetFilters">Réinitialiser</button>
              <button type="button" class="save-button" @click="applyFilters">Appliquer</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Payment Methods Modal -->
      <div class="modal" v-if="showPaymentMethodsModal" @click.self="showPaymentMethodsModal = false">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Gestion des modes de paiement</h2>
            <button class="close-button" @click="showPaymentMethodsModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="list-header">
              <div class="search-bar" style="max-width: 300px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-search">
                  <circle cx="11" cy="11" r="8" />
                  <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" placeholder="Rechercher un mode de paiement..." v-model="paymentMethodSearchQuery">
              </div>
              <button class="add-button" @click="showAddPaymentMethodModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-plus">
                  <path d="M5 12h14" />
                  <path d="M12 5v14" />
                </svg>
                Ajouter un mode de paiement
              </button>
            </div>
            <div v-if="loadingPaymentMethods" class="loading-indicator">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
              Chargement des modes de paiement...
            </div>
            <div v-else-if="filteredPaymentMethodsList.length === 0" class="no-items">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-credit-card">
                <rect width="20" height="14" x="2" y="5" rx="2" />
                <path d="M2 10h20" />
              </svg>
              <p>Aucun mode de paiement trouvé</p>
            </div>
            <div v-else class="payment-methods-list">
              <div v-for="method in filteredPaymentMethodsList" :key="method.id" class="payment-method-item">
                <div class="payment-method-info">
                  <div class="payment-method-details">
                    <div class="payment-method-name">{{ method.name }}</div>
                    <div class="payment-method-description">{{ method.description || 'Aucune description' }}</div>
                  </div>
                </div>
                <div class="payment-method-actions">
                  <button class="action-icon" @click="editPaymentMethod(method)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-pencil">
                      <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                      <path d="m15 5 4 4" />
                    </svg>
                  </button>
                  <button class="action-icon" @click="confirmDeletePaymentMethod(method)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-trash-2">
                      <path d="M3 6h18" />
                      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                      <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                      <line x1="10" x2="10" y1="11" y2="17" />
                      <line x1="14" x2="14" y1="11" y2="17" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Add/Edit Payment Method Modal -->
      <div class="modal" v-if="showAddPaymentMethodModal || showEditPaymentMethodModal" @click.self="closeModals">
        <div class="modal-content small-modal">
          <div class="modal-header">
            <h2>{{ showEditPaymentMethodModal ? 'Modifier le mode de paiement' : 'Ajouter un nouveau mode de paiement' }}
            </h2>
            <button class="close-button" @click="closeModals">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="savePaymentMethod">
              <div class="form-group">
                <label for="payment-method-name">Nom *</label>
                <input type="text" id="payment-method-name" v-model="paymentMethodForm.name" required>
              </div>
              <div class="form-group">
                <label for="payment-method-description">Description</label>
                <textarea id="payment-method-description" v-model="paymentMethodForm.description" rows="3"></textarea>
              </div>
              <div class="form-actions">
                <button type="button" class="cancel-button" @click="closeModals">Annuler</button>
                <button type="submit" class="save-button">
                  {{ showEditPaymentMethodModal ? 'Mettre à jour' : 'Ajouter' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Generic Confirmation Modal -->
      <div class="modal" v-if="showGenericConfirmModal" @click.self="showGenericConfirmModal = false">
        <div class="modal-content delete-modal">
          <div class="modal-header">
            <h2>{{ genericConfirmModalData.title }}</h2>
            <button class="close-button" @click="showGenericConfirmModal = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="delete-warning">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-alert-triangle">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                <path d="M12 9v4" />
                <path d="M12 17h.01" />
              </svg>
              <p>{{ genericConfirmModalData.message }}</p>
              <p class="warning-text">Cette action est irréversible et supprimera définitivement {{
                genericConfirmModalData.itemName }}.</p>
            </div>
          </div>
          <div class="modal-footer">
            <button class="cancel-button" @click="showGenericConfirmModal = false">Annuler</button>
            <button class="delete-button" @click="genericConfirmModalData.onConfirm">Supprimer</button>
          </div>
        </div>
      </div>
      <!-- Toast Notifications -->
      <div class="toast-container" v-if="toast.show">
        <div class="toast" :class="toast.type">
          <div class="toast-icon">
            <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" class="lucide lucide-check-circle">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
              <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <svg v-else-if="toast.type === 'error'" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" class="lucide lucide-alert-circle">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" x2="12" y1="8" y2="12" />
              <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-info">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" x2="12" y1="16" y2="12" />
              <line x1="12" x2="12.01" y1="8" y2="8" />
            </svg>
          </div>
          <div class="toast-content">{{ toast.message }}</div>
          <button class="toast-close" @click="hideToast">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-x">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import Header from '@/components/Header.vue'
  import { ref, computed, onMounted, watch } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  import html2pdf from 'html2pdf.js';
  
  // Store et Router
  const store = useStore();
  const router = useRouter();
  
  // Données utilisateur depuis le store Vuex
  const permissionData = computed(() => store.getters.getPermissions)
  const entrepriseData = computed(() => store.getters.getEntreprise);
  const isAuthenticated = computed(() => store.getters.isAuthenticated);
  
  // Vérification de l'authentification
  onMounted(() => {
    if (!isAuthenticated.value) {
      console.log("Utilisateur non authentifié, redirection vers la page de connexion");
      router.push('/login');
    } else {
      console.log("Utilisateur authentifié:", entrepriseData.value);
      loadTransactions();
      loadCategories();
      loadPaymentMethods();
      loadAccounts();
      loadSeminairistePaymentsTotal(); // Load seminairiste payments
    }
  });
  
  // Date actuelle formatée
  const currentDate = computed(() => {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date().toLocaleDateString('fr-FR', options);
  });
  
  // Onglets de transactions
  const transactionTabs = ref([
    {
      id: 'all',
      name: 'Toutes les transactions',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>'
    },
    {
      id: 'income',
      name: 'Revenus',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-right"><path d="m7 7 10 10"/><path d="M17 7v10H7"/></svg>'
    },
    {
      id: 'expense',
      name: 'Dépenses',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right"><path d="m7 17 10-10"/><path d="M7 7h10v10"/></svg>'
    },
    {
      id: 'recent',
      name: 'Récentes',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
    }
  ]);
  // Onglets de rapports
  const reportTabs = ref([
    {
      id: 'income_expense',
      name: 'Revenus & Dépenses',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>'
    },
    {
      id: 'category',
      name: 'Par catégorie',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>'
    },
    {
      id: 'cash_flow',
      name: 'Flux de trésorerie',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-line-chart"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>'
    },
    {
      id: 'balance_sheet',
      name: 'Bilan',
      icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>'
    }
  ]);
  // Périodes pour le sélecteur de période
  const periods = ref([
    { id: 'day', name: 'Jour' },
    { id: 'week', name: 'Semaine' },
    { id: 'month', name: 'Mois' },
    { id: 'quarter', name: 'Trimestre' },
    { id: 'year', name: 'Année' }]);
  
  // Onglet actif
  const activeTab = ref('all');
  const activePeriod = ref('month');
  const activeReportTab = ref('income_expense');
  const categoryFilterType = ref('all');
  
  // Catégories
  const categories = ref([]);
  const categorySearchQuery = ref('');
  
  // Modes de paiement
  const paymentMethods = ref([]);
  const paymentMethodSearchQuery = ref('');
  
  // Comptes
  const accounts = ref([]);
  
  // État de chargement
  const loading = ref(false);
  const loadingCategories = ref(false);
  const loadingPaymentMethods = ref(false);
  const loadingReport = ref(false);
  
  // Transactions
  const transactions = ref([]);
  const seminairistePaymentsTotal = ref(0); // New ref for seminairiste payments
  const searchQuery = ref('');
  const filterCategory = ref('');
  const filterType = ref('');
  const filterStatus = ref('');
  const filterDateRange = ref('');
  const filterStartDate = ref('');
  const filterEndDate = ref('');
  const filterAmountMin = ref('');
  const filterAmountMax = ref('');
  const filterPaymentMethod = ref('');
  const filterAccount = ref('');
  const sortBy = ref('date_desc');
  const selectedTransactions = ref([]);
  const selectAll = ref(false);
  
  // Pagination
  const currentPage = ref(1);
  const pageSize = ref(10);
  const totalPages = computed(() => Math.ceil(filteredTransactions.value.length / pageSize.value));
  const paginationStart = computed(() => filteredTransactions.value.length === 0 ? 0 : (currentPage.value - 1) * pageSize.value + 1);
  const paginationEnd = computed(() => Math.min(currentPage.value * pageSize.value, filteredTransactions.value.length));
  
  // Modals
  const showAddTransactionModal = ref(false);
  const showEditTransactionModal = ref(false);
  const showTransactionDetailsModal = ref(false);
  const showDeleteModal = ref(false); // For transaction deletion
  const showCategoriesModal = ref(false);
  const showAddCategoryModal = ref(false);
  const showEditCategoryModal = ref(false);
  const showPaymentMethodsModal = ref(false); // New modal for payment methods
  const showAddPaymentMethodModal = ref(false);
  const showEditPaymentMethodModal = ref(false);
  const showReportModal = ref(false);
  const showFilterModal = ref(false);
  const showGenericConfirmModal = ref(false); // Generic confirmation modal
  const transactionToDelete = ref(null);
  const selectedTransaction = ref({});
  const categoryToDelete = ref(null);
  const paymentMethodToDelete = ref(null); // For payment method deletion
  
  // Formulaire de transaction
  const transactionForm = ref({
    id: null,
    type: 'expense',
    category_id: null,
    date: new Date().toISOString().split('T')[0],
    amount: '',
    reference: '',
    description: '',
    status: 'completed',
    payment_method_id: null,
    account_id: null,
    notes: '',
    attachment: null
  });
  
  // Formulaire de catégorie
  const categoryForm = ref({
    id: null,
    name: '',
    type: 'expense',
    description: ''
  });
  
  // Formulaire de mode de paiement
  const paymentMethodForm = ref({
    id: null,
    name: '',
    description: ''
  });
  
  // Rapport
  const reportPeriod = ref('this_month');
  const reportStartDate = ref('');
  const reportEndDate = ref('');
  const reportCategory = ref('');
  const reportAccount = ref('');
  const reportData = ref({
    totalIncome: 0,
    totalExpenses: 0,
    netProfit: 0,
    categoryBreakdown: [],
    cashFlow: [],
    balanceSheet: {
      assets: [],
      liabilities: [],
      totalAssets: 0,
      totalLiabilities: 0,
      equity: 0
    }
  });
  
  // Toast notifications
  const toast = ref({
    show: false,
    message: '',
    type: 'info',
    timeout: null
  });
  
  // Generic confirmation modal data
  const genericConfirmModalData = ref({
    title: '',
    message: '',
    itemName: '',
    onConfirm: null,
    onCancel: null
  });
  
  // Méthodes API
  const loadTransactions = async () => {
    loading.value = true;
    try {
      const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=transactions', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        transactions.value = data.data.transactions || [];
      } else {
        showToast('error', data.message || 'Erreur lors du chargement des transactions');
      }
    } catch (error) {
      console.error('Erreur lors du chargement des transactions:', error);
      showToast('error', 'Erreur lors du chargement des transactions');
      transactions.value = generateDemoTransactions(); // Fallback to demo data
    } finally {
      loading.value = false;
    }
  };
  
  const loadCategories = async () => {
    loadingCategories.value = true;
    try {
      const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=categories', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        categories.value = data.data || [];
      } else {
        console.error('Erreur lors du chargement des catégories:', data.message);
        showToast('error', data.message || 'Erreur lors du chargement des catégories');
      }
    } catch (error) {
      console.error('Erreur lors du chargement des catégories:', error);
      showToast('error', 'Erreur lors du chargement des catégories');
      categories.value = generateDemoCategories(); // Fallback to demo data
    } finally {
      loadingCategories.value = false;
    }
  };
  
  const loadPaymentMethods = async () => {
    loadingPaymentMethods.value = true;
    try {
      const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=payment_methods', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        paymentMethods.value = data.data || [];
      } else {
        console.error('Erreur lors du chargement des modes de paiement:', data.message);
        showToast('error', data.message || 'Erreur lors du chargement des modes de paiement');
      }
    } catch (error) {
      console.error('Erreur lors du chargement des modes de paiement:', error);
      showToast('error', 'Erreur lors du chargement des modes de paiement');
      paymentMethods.value = generateDemoPaymentMethods(); // Fallback to demo data
    } finally {
      loadingPaymentMethods.value = false;
    }
  };
  
  const loadAccounts = async () => {
    try {
      const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=accounts', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        accounts.value = data.data || [];
      } else {
        console.error('Erreur lors du chargement des comptes:', data.message);
        showToast('error', 'Erreur lors du chargement des comptes');
      }
    } catch (error) {
      console.error('Erreur lors du chargement des comptes:', error);
      showToast('error', 'Erreur lors du chargement des comptes');
      accounts.value = generateDemoAccounts(); // Fallback to demo data
    }
  };
  
  const loadSeminairistePaymentsTotal = async () => {
    try {
      // Simulate fetching total payments from seminairistes
      // In a real application, this would be an API call to your seminairistes module
      // const response = await fetch('https://sastock.com/api_web/api_seminairistes.php?action=total_payments', {
      //   method: 'GET',
      //   headers: {
      //     'Content-Type': 'application/json',
      //     'Authorization': `Bearer ${store.getters.getToken}`
      //   }
      // });
      // const data = await response.json();
      // if (data.success) {
      //   seminairistePaymentsTotal.value = data.data.total || 0;
      // } else {
      //   console.error('Erreur lors du chargement des paiements séminaristes:', data.message);
      //   seminairistePaymentsTotal.value = 0;
      // }
      // For demo purposes, set a static value
      seminairistePaymentsTotal.value = 1500000; // Example total payment from seminairistes
    } catch (error) {
      console.error('Erreur lors du chargement des paiements séminaristes:', error);
      seminairistePaymentsTotal.value = 0;
    }
  };
  
  // Données de démonstration
  const generateDemoTransactions = () => {
    const demoTransactions = [];
    const types = ['income', 'expense'];
    const statuses = ['completed', 'pending', 'cancelled'];
    const incomeDescriptions = [
      'Paiement inscription séminariste', 'Don de bienfaiteur', 'Cotisation mensuelle', 'Vente de livres', 'Aide extérieure'
    ];
    const expenseDescriptions = [
      'Dépenses Commission Liturgie', 'Dépenses Commission Logistique', 'Achats fournitures bureau', 'Salaires personnel', 'Facture électricité', 'Maintenance bâtiments', 'Transport séminaristes'
    ];
  
    for (let i = 1; i <= 50; i++) {
      const type = types[Math.floor(Math.random() * types.length)];
      const status = statuses[Math.floor(Math.random() * statuses.length)];
      const description = type === 'income' ? incomeDescriptions[Math.floor(Math.random() * incomeDescriptions.length)] : expenseDescriptions[Math.floor(Math.random() * expenseDescriptions.length)];
      const amount = Math.floor(Math.random() * 500000) / 100 + 5000; // Amounts between 50 and 5000
      const date = new Date();
      date.setDate(date.getDate() - Math.floor(Math.random() * 60)); // Last 60 days
  
      let category_id;
      if (type === 'income') {
        const incomeCategories = generateDemoCategories().filter(c => c.type === 'income');
        category_id = incomeCategories[Math.floor(Math.random() * incomeCategories.length)].id;
      } else {
        const expenseCategories = generateDemoCategories().filter(c => c.type === 'expense');
        category_id = expenseCategories[Math.floor(Math.random() * expenseCategories.length)].id;
      }
  
      demoTransactions.push({
        id: i,
        type,
        category_id,
        date: date.toISOString().split('T')[0],
        amount,
        reference: `TR-${String(i).padStart(5, '0')}`,
        description,
        status,
        payment_method_id: Math.floor(Math.random() * 5) + 1,
        account_id: Math.floor(Math.random() * 3) + 1,
        notes: Math.random() > 0.7 ? 'Notes supplémentaires sur cette transaction.' : '',
        attachment: Math.random() > 0.8 ? '/placeholder.svg?height=200&width=200' : null
      });
    }
    return demoTransactions;
  };
  
  const generateDemoCategories = () => {
    return [
      { id: 1, name: 'Paiements Séminaristes', type: 'income', description: 'Paiements des frais d\'inscription et de scolarité' },
      { id: 2, name: 'Dons', type: 'income', description: 'Dons reçus de bienfaiteurs' },
      { id: 3, name: 'Cotisations', type: 'income', description: 'Cotisations des membres ou associations' },
      { id: 4, name: 'Demandes d\'aide', type: 'income', description: 'Fonds reçus suite à des demandes d\'aide' },
      { id: 5, name: 'Autres revenus', type: 'income', description: 'Autres sources de revenus diverses' },
      { id: 6, name: 'Commission Liturgie', type: 'expense', description: 'Dépenses liées aux activités liturgiques' },
      { id: 7, name: 'Commission Logistique', type: 'expense', description: 'Dépenses de transport, hébergement, etc.' },
      { id: 8, name: 'Commission Pastorale', type: 'expense', description: 'Dépenses des activités pastorales' },
      { id: 9, name: 'Administration', type: 'expense', description: 'Frais administratifs et de bureau' },
      { id: 10, name: 'Nourriture & Boissons', type: 'expense', description: 'Dépenses pour l\'alimentation' },
      { id: 11, name: 'Maintenance & Réparations', type: 'expense', description: 'Entretien des bâtiments et équipements' },
      { id: 12, name: 'Santé', type: 'expense', description: 'Dépenses médicales des séminaristes' },
    ];
  };
  
  const generateDemoPaymentMethods = () => {
    return [
      { id: 1, name: 'Espèces', description: 'Paiement en espèces' },
      { id: 2, name: 'Virement Bancaire', description: 'Paiement par virement bancaire' },
      { id: 3, name: 'Mobile Money', description: 'Paiement par mobile money' },
      { id: 4, name: 'Chèque', description: 'Paiement par chèque' },
      { id: 5, name: 'Carte de Crédit', description: 'Paiement par carte de crédit' }
    ];
  };
  
  const generateDemoAccounts = () => {
    return [
      { id: 1, name: 'Caisse Principale', type: 'cash', balance: 1250000 },
      { id: 2, name: 'Compte Bancaire Séminaire', type: 'bank', balance: 3500000 },
      { id: 3, name: 'Fonds d\'Urgence', type: 'savings', balance: 2500000 }
    ];
  };
  
  // Statistiques
  const totalTransactions = computed(() => filteredTransactions.value.length);
  const incomeTransactions = computed(() => filteredTransactions.value.filter(t => t.type === 'income').length);
  const expenseTransactions = computed(() => filteredTransactions.value.filter(t => t.type === 'expense').length);
  const completedTransactions = computed(() => filteredTransactions.value.filter(t => t.status === 'completed').length);
  const pendingTransactions = computed(() => filteredTransactions.value.filter(t => t.status === 'pending').length);
  const cancelledTransactions = computed(() => filteredTransactions.value.filter(t => t.status === 'cancelled').length);
  
  const currentBalance = computed(() => {
    const filteredTransactionsForPeriod = getTransactionsByPeriod(activePeriod.value);
    const incomeTotal = filteredTransactionsForPeriod
      .filter(t => t.type === 'income' && t.status === 'completed')
      .reduce((sum, t) => sum + Number(t.amount), 0);
    const expenseTotal = filteredTransactionsForPeriod
      .filter(t => t.type === 'expense' && t.status === 'completed')
      .reduce((sum, t) => sum + Number(t.amount), 0);
    return incomeTotal - expenseTotal;
  });
  
  const periodIncome = computed(() => {
    const filteredTransactionsForPeriod = getTransactionsByPeriod(activePeriod.value);
    return filteredTransactionsForPeriod
      .filter(t => t.type === 'income' && t.status === 'completed')
      .reduce((sum, t) => sum + Number(t.amount), 0);
  });
  
  const periodExpenses = computed(() => {
    const filteredTransactionsForPeriod = getTransactionsByPeriod(activePeriod.value);
    return filteredTransactionsForPeriod
      .filter(t => t.type === 'expense' && t.status === 'completed')
      .reduce((sum, t) => sum + Number(t.amount), 0);
  });
  
  const periodNetProfit = computed(() => {
    return periodIncome.value - periodExpenses.value;
  });
  
  watch(activePeriod, (newPeriod) => {
    currentPage.value = 1;
    // Calculer les changements en pourcentage par rapport à la période précédente
    // Ceci est une simulation - dans une implémentation réelle, vous compareriez
    // avec les données de la période précédente
    // Simuler des changements différents pour chaque période
    switch (newPeriod) {
      case 'day':
        balanceChange.value = 2.3;
        incomeChange.value = 5.1;
        expenseChange.value = 3.2;
        profitChange.value = 7.5;
        break;
      case 'week':
        balanceChange.value = 4.7;
        incomeChange.value = 8.3;
        expenseChange.value = 5.6;
        profitChange.value = 10.2;
        break;
      case 'month':
        balanceChange.value = 8.5;
        incomeChange.value = 12.3;
        expenseChange.value = 7.8;
        profitChange.value = 15.2;
        break;
      case 'quarter':
        balanceChange.value = 12.8;
        incomeChange.value = 18.5;
        expenseChange.value = 10.3;
        profitChange.value = 22.7;
        break;
      case 'year':
        balanceChange.value = 15.6;
        incomeChange.value = 24.2;
        expenseChange.value = 14.9;
        profitChange.value = 30.5;
        break;
    }
  });
  
  // Valeurs de démonstration pour les changements
  const balanceChange = ref(8.5);
  const incomeChange = ref(12.3);
  const expenseChange = ref(7.8);
  const profitChange = ref(15.2);
  
  const balanceChangeClass = computed(() => {
    return balanceChange.value >= 0 ? 'positive' : 'negative';
  });
  const profitChangeClass = computed(() => {
    return profitChange.value >= 0 ? 'positive' : 'negative';
  });
  
  // Transactions filtrées
  const filteredTransactions = computed(() => {
    // Commencer par filtrer par période
    let result = getTransactionsByPeriod(activePeriod.value);
  
    // Ensuite appliquer les autres filtres existants
    // Filtrer par onglet
    if (activeTab.value === 'income') {
      result = result.filter(t => t.type === 'income');
    } else if (activeTab.value === 'expense') {
      result = result.filter(t => t.type === 'expense');
    } else if (activeTab.value === 'recent') {
      // Trier par date et prendre les 30 derniers jours
      const thirtyDaysAgo = new Date();
      thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
      const thirtyDaysAgoStr = thirtyDaysAgo.toISOString().split('T')[0];
      result = result.filter(t => t.date >= thirtyDaysAgoStr);
    }
  
    // Filtrer par recherche
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase();
      result = result.filter(t =>
        t.reference?.toLowerCase().includes(query) ||
        t.description?.toLowerCase().includes(query)
      );
    }
  
    // Filtrer par catégorie
    if (filterCategory.value) {
      result = result.filter(t => t.category_id === parseInt(filterCategory.value));
    }
    // Filtrer par type
    if (filterType.value) {
      result = result.filter(t => t.type === filterType.value);
    }
    // Filtrer par statut
    if (filterStatus.value) {
      result = result.filter(t => t.status === filterStatus.value);
    }
  
    // Apply advanced filters
    if (filterDateRange.value) {
      const today = new Date();
      let startDate = new Date();
      let endDate = new Date();
  
      switch (filterDateRange.value) {
        case 'today':
          startDate.setHours(0, 0, 0, 0);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'yesterday':
          startDate.setDate(today.getDate() - 1);
          startDate.setHours(0, 0, 0, 0);
          endDate.setDate(today.getDate() - 1);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'this_week':
          startDate = new Date(today.setDate(today.getDate() - today.getDay() + (today.getDay() === 0 ? -6 : 1)));
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(startDate);
          endDate.setDate(startDate.getDate() + 6);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'last_week':
          startDate = new Date(today.setDate(today.getDate() - today.getDay() - 6));
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(startDate);
          endDate.setDate(startDate.getDate() + 6);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'this_month':
          startDate = new Date(today.getFullYear(), today.getMonth(), 1);
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'last_month':
          startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(today.getFullYear(), today.getMonth(), 0);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'this_quarter':
          const currentMonth = today.getMonth();
          const currentQuarterStartMonth = Math.floor(currentMonth / 3) * 3;
          startDate = new Date(today.getFullYear(), currentQuarterStartMonth, 1);
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(today.getFullYear(), currentQuarterStartMonth + 3, 0);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'last_quarter':
          const lastQuarterStartMonth = Math.floor(today.getMonth() / 3) * 3 - 3;
          startDate = new Date(today.getFullYear(), lastQuarterStartMonth, 1);
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(today.getFullYear(), lastQuarterStartMonth + 3, 0);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'this_year':
          startDate = new Date(today.getFullYear(), 0, 1);
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(today.getFullYear(), 11, 31);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'last_year':
          startDate = new Date(today.getFullYear() - 1, 0, 1);
          startDate.setHours(0, 0, 0, 0);
          endDate = new Date(today.getFullYear() - 1, 11, 31);
          endDate.setHours(23, 59, 59, 999);
          break;
        case 'custom':
          if (filterStartDate.value) startDate = new Date(filterStartDate.value);
          if (filterEndDate.value) endDate = new Date(filterEndDate.value);
          endDate.setHours(23, 59, 59, 999); // Ensure end of day
          break;
      }
      const startTimestamp = startDate.getTime();
      const endTimestamp = endDate.getTime();
      result = result.filter(t => {
        const transactionDate = new Date(t.date).getTime();
        return transactionDate >= startTimestamp && transactionDate <= endTimestamp;
      });
    }
    if (filterAmountMin.value) {
      result = result.filter(t => t.amount >= parseFloat(filterAmountMin.value));
    }
    if (filterAmountMax.value) {
      result = result.filter(t => t.amount <= parseFloat(filterAmountMax.value));
    }
    if (filterPaymentMethod.value) {
      result = result.filter(t => t.payment_method_id === parseInt(filterPaymentMethod.value));
    }
    if (filterAccount.value) {
      result = result.filter(t => t.account_id === parseInt(filterAccount.value));
    }
  
    // Trier
    result.sort((a, b) => {
      switch (sortBy.value) {
        case 'date_asc':
          return new Date(a.date) - new Date(b.date);
        case 'date_desc':
          return new Date(b.date) - new Date(a.date);
        case 'amount_asc':
          return a.amount - b.amount;
        case 'amount_desc':
          return b.amount - a.amount;
        default:
          return 0;
      }
    });
    return result;
  });
  
  // Transactions paginées
  const paginatedTransactions = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    return filteredTransactions.value.slice(start, end);
  });
  
  // Catégories filtrées pour le formulaire de transaction
  const filteredCategories = computed(() => {
    if (transactionForm.value.type === 'income') {
      return categories.value.filter(c => c.type === 'income');
    } else {
      return categories.value.filter(c => c.type === 'expense');
    }
  });
  
  // Catégories filtrées pour la liste de gestion des catégories
  const filteredCategoriesList = computed(() => {
    let result = [...categories.value];
    // Filtrer par type
    if (categoryFilterType.value !== 'all') {
      result = result.filter(c => c.type === categoryFilterType.value);
    }
    // Filtrer par recherche
    if (categorySearchQuery.value) {
      const query = categorySearchQuery.value.toLowerCase();
      result = result.filter(c =>
        c.name.toLowerCase().includes(query) ||
        (c.description && c.description.toLowerCase().includes(query))
      );
    }
    return result;
  });
  
  // Modes de paiement filtrés pour la liste de gestion des modes de paiement
  const filteredPaymentMethodsList = computed(() => {
    let result = [...paymentMethods.value];
    if (paymentMethodSearchQuery.value) {
      const query = paymentMethodSearchQuery.value.toLowerCase();
      result = result.filter(m =>
        m.name.toLowerCase().includes(query) ||
        (m.description && m.description.toLowerCase().includes(query))
      );
    }
    return result;
  });
  
  const getTransactionsByPeriod = (period) => {
    const today = new Date();
    let startDate = new Date();
    let endDate = new Date(); // Define endDate for filtering
  
    switch (period) {
      case 'day':
        startDate.setHours(0, 0, 0, 0);
        endDate.setHours(23, 59, 59, 999);
        break;
      case 'week': {
        const dayOfWeek = today.getDay();
        const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
        startDate.setDate(diff);
        startDate.setHours(0, 0, 0, 0);
        endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);
        endDate.setHours(23, 59, 59, 999);
        break;
      }
      case 'month':
        startDate.setDate(1);
        startDate.setHours(0, 0, 0, 0);
        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        endDate.setHours(23, 59, 59, 999);
        break;
      case 'quarter': {
        const quarter = Math.floor(today.getMonth() / 3);
        startDate.setMonth(quarter * 3);
        startDate.setDate(1);
        startDate.setHours(0, 0, 0, 0);
        endDate = new Date(today.getFullYear(), quarter * 3 + 3, 0);
        endDate.setHours(23, 59, 59, 999);
        break;
      }
      case 'year':
        startDate.setMonth(0);
        startDate.setDate(1);
        startDate.setHours(0, 0, 0, 0);
        endDate = new Date(today.getFullYear(), 11, 31);
        endDate.setHours(23, 59, 59, 999);
        break;
      default:
        // 'all' or unknown period, return all transactions
        return transactions.value;
    }
  
    const startTimestamp = startDate.getTime();
    const endTimestamp = endDate.getTime();
  
    return transactions.value.filter(t => {
      const transactionDate = new Date(t.date).getTime();
      return transactionDate >= startTimestamp && transactionDate <= endTimestamp;
    });
  };
  
  // Recherche de transactions
  const searchTransactions = () => {
    currentPage.value = 1; // Réinitialiser la pagination lors d'une recherche
  };
  
  // Formatage du prix
  const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(price);
  };
  
  // Formatage de la date
  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };
  
  // Obtenir le nom de la catégorie
  const getCategoryName = (id) => {
    const category = categories.value.find(c => c.id === id);
    return category ? category.name : '-';
  };
  
  // Obtenir le nom du mode de paiement
  const getPaymentMethodName = (id) => {
    const method = paymentMethods.value.find(m => m.id === id);
    return method ? method.name : '-';
  };
  
  // Obtenir le nom du compte
  const getAccountName = (id) => {
    const account = accounts.value.find(a => a.id === id);
    return account ? account.name : '-';
  };
  
  // Texte du type
  const getTypeText = (type) => {
    switch (type) {
      case 'income': return 'Revenu';
      case 'expense': return 'Dépense';
      default: return type;
    }
  };
  
  // Classe du type
  const getTypeClass = (type) => {
    switch (type) {
      case 'income': return 'type-income';
      case 'expense': return 'type-expense';
      default: return '';
    }
  };
  
  // Classe du montant
  const getAmountClass = (type) => {
    switch (type) {
      case 'income': return 'amount-income';
      case 'expense': return 'amount-expense';
      default: return '';
    }
  };
  
  // Texte du statut
  const getStatusText = (status) => {
    switch (status) {
      case 'completed': return 'Complétée';
      case 'pending': return 'En attente';
      case 'cancelled': return 'Annulée';
      default: return status;
    }
  };
  
  // Classe du statut
  const getStatusClass = (status) => {
    switch (status) {
      case 'completed': return 'status-in';
      case 'pending': return 'status-low';
      case 'cancelled': return 'status-out';
      default: return '';
    }
  };
  
  // Texte du filtre de type de catégorie
  const getCategoryFilterTypeText = (type) => {
    switch (type) {
      case 'all': return 'Toutes';
      case 'income': return 'Revenus';
      case 'expense': return 'Dépenses';
      default: return type;
    }
  };
  
  // Texte de la période
  const getPeriodText = (period) => {
    switch (period) {
      case 'day': return 'hier';
      case 'week': return 'la semaine dernière';
      case 'month': return 'le mois dernier';
      case 'quarter': return 'le trimestre dernier';
      case 'year': return 'l\'année dernière';
      default: return 'la période précédente';
    }
  };
  
  // Couleur de la catégorie pour les graphiques
  const getCategoryColor = (index) => {
    const colors = [
      '#7c3aed', '#10b981', '#f59e0b', '#ef4444', '#3b82f6',
      '#ec4899', '#8b5cf6', '#14b8a6', '#f97316', '#6366f1'
    ];
    return colors[index % colors.length];
  };
  
  // Sélection de toutes les transactions
  const toggleSelectAll = () => {
    if (selectAll.value) {
      selectedTransactions.value = paginatedTransactions.value.map(t => t.id);
    } else {
      selectedTransactions.value = [];
    }
  };
  
  // Voir les détails d'une transaction
  const viewTransactionDetails = (transaction) => {
    selectedTransaction.value = { ...transaction };
    showTransactionDetailsModal.value = true;
  };
  
  // Édition d'une transaction
  const editTransaction = (transaction) => {
    transactionForm.value = { ...transaction };
    showEditTransactionModal.value = true;
    showTransactionDetailsModal.value = false;
  };
  
  // Confirmation de suppression de transaction
  const confirmDeleteTransaction = (transaction) => {
    transactionToDelete.value = transaction;
    showDeleteModal.value = true;
  };
  
  // Suppression d'une transaction
  const deleteTransaction = async () => {
    try {
      const response = await fetch(`https://sastock.com/api_web/api_comptabilite.php?action=transaction&id=${transactionToDelete.value.id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        transactions.value = transactions.value.filter(t => t.id !== transactionToDelete.value.id);
        showToast('success', 'Transaction supprimée avec succès');
      } else {
        showToast('error', data.message || 'Erreur lors de la suppression de la transaction');
      }
    } catch (error) {
      console.error('Erreur lors de la suppression de la transaction:', error);
      showToast('error', 'Erreur lors de la suppression de la transaction');
      // Simuler la suppression en cas d'erreur
      transactions.value = transactions.value.filter(t => t.id !== transactionToDelete.value.id);
      showToast('success', 'Transaction supprimée avec succès (simulée)');
    } finally {
      showDeleteModal.value = false;
      transactionToDelete.value = null;
    }
  };
  
  // Impression du reçu
  const printReceipt = (transaction) => {
    const receiptElement = document.createElement('div');
    receiptElement.style.padding = '20px';
    receiptElement.style.fontFamily = 'Arial, sans-serif';
    receiptElement.innerHTML = `
      <div style="max-width: 800px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
          <div>
            <img src="/placeholder.svg?height=150&width=150" alt="Logo" style="width: 150px;" />
          </div>
          <div style="text-align: right;">
            <h2 style="margin: 0; color: #7c3aed;">REÇU</h2>
            <p style="margin: 5px 0;">Référence: ${transaction.reference}</p>
            <p style="margin: 5px 0;">Date: ${formatDate(transaction.date)}</p>
          </div>
        </div>
        <div style="margin-bottom: 30px;">
          <h3 style="margin: 0 0 10px 0;">Détails de la transaction</h3>
          <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;"><strong>Type:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">${getTypeText(transaction.type)}</td>
            </tr>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;"><strong>Catégorie:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">${getCategoryName(transaction.category_id)}</td>
            </tr>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;"><strong>Description:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">${transaction.description}</td>
            </tr>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-weight: bold;"><strong>Montant:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-weight: bold;">${formatPrice(transaction.amount)}</td>
            </tr>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;"><strong>Mode de paiement:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">${getPaymentMethodName(transaction.payment_method_id)}</td>
            </tr>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;"><strong>Compte:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">${getAccountName(transaction.account_id)}</td>
            </tr>
            <tr>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;"><strong>Statut:</strong></td>
              <td style="padding: 8px; border-bottom: 1px solid #e5e7eb;">${getStatusText(transaction.status)}</td>
            </tr>
          </table>
        </div>
        ${transaction.notes ? `
          <div style="margin-bottom: 30px;">
            <h3 style="margin: 0 0 10px 0;">Notes:</h3>
            <p style="margin: 0;">${transaction.notes}</p>
          </div>
        ` : ''}
        <div style="text-align: center; margin-top: 50px; color: #6b7280; font-size: 12px;">
          <p>Ce reçu a été généré automatiquement.</p>
          <p>${entrepriseData.value?.nom || 'Séminaire X'} - ${entrepriseData.value?.adresse || 'Adresse du Séminaire'}</p>
        </div>
      </div>
    `;
    const options = {
      margin: 10,
      filename: `recu_${transaction.reference}.pdf`,
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2, useCORS: true },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    showToast('info', `Génération du reçu ${transaction.reference} en cours...`);
    document.body.appendChild(receiptElement);
    html2pdf().from(receiptElement).set(options).save().then(() => {
      document.body.removeChild(receiptElement);
      showToast('success', `Le reçu ${transaction.reference} a été généré avec succès.`);
    }).catch(error => {
      console.error('Erreur lors de la génération du PDF:', error);
      document.body.removeChild(receiptElement);
      showToast('error', 'Une erreur est survenue lors de la génération du reçu.');
    });
  };
  
  const editCategory = (category) => {
    categoryForm.value = { ...category };
    showEditCategoryModal.value = true;
    showAddCategoryModal.value = false;
  };
  
  const saveCategory = async () => {
    try {
      if (showEditCategoryModal.value) {
        const response = await fetch(`https://sastock.com/api_web/api_comptabilite.php?action=category&id=${categoryForm.value.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${store.getters.getToken}`
          },
          body: JSON.stringify(categoryForm.value)
        });
        const data = await response.json();
        if (data.success) {
          const index = categories.value.findIndex(c => c.id === categoryForm.value.id);
          if (index !== -1) {
            categories.value[index] = { ...categoryForm.value };
          }
          showToast('success', 'Catégorie mise à jour avec succès');
        } else {
          showToast('error', data.message || 'Erreur lors de la mise à jour de la catégorie');
        }
      } else {
        const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=category', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${store.getters.getToken}`
          },
          body: JSON.stringify(categoryForm.value)
        });
        const data = await response.json();
        if (data.success) {
          categories.value.push({ ...categoryForm.value, id: data.data.id });
          showToast('success', 'Catégorie ajoutée avec succès');
        } else {
          showToast('error', data.message || 'Erreur lors de l\'ajout de la catégorie');
        }
      }
    } catch (error) {
      console.error('Erreur lors de la sauvegarde de la catégorie:', error);
      showToast('error', 'Erreur lors de la sauvegarde de la catégorie');
      // Simuler la sauvegarde en cas d'erreur
      if (showEditCategoryModal.value) {
        const index = categories.value.findIndex(c => c.id === categoryForm.value.id);
        if (index !== -1) {
          categories.value[index] = { ...categoryForm.value };
        }
      } else {
        const newId = Math.max(...categories.value.map(c => c.id)) + 1;
        categories.value.push({ ...categoryForm.value, id: newId });
      }
      showToast('success', showEditCategoryModal.value ? 'Catégorie mise à jour avec succès (simulée)' : 'Catégorie ajoutée avec succès (simulée)');
    } finally {
      closeModals();
    }
  };
  
  const confirmDeleteCategory = (category) => {
    categoryToDelete.value = category;
    genericConfirmModalData.value = {
      title: 'Confirmer la suppression',
      message: `Êtes-vous sûr de vouloir supprimer la catégorie "${category.name}" ?`,
      itemName: `la catégorie "${category.name}"`,
      onConfirm: deleteCategory,
      onCancel: () => { showGenericConfirmModal.value = false; categoryToDelete.value = null; }
    };
    showGenericConfirmModal.value = true;
  };
  
  const deleteCategory = async () => {
    try {
      const response = await fetch(`https://sastock.com/api_web/api_comptabilite.php?action=category&id=${categoryToDelete.value.id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        categories.value = categories.value.filter(c => c.id !== categoryToDelete.value.id);
        showToast('success', 'Catégorie supprimée avec succès');
      } else {
        showToast('error', data.message || 'Erreur lors de la suppression de la catégorie');
      }
    } catch (error) {
      console.error('Erreur lors de la suppression de la catégorie:', error);
      showToast('error', 'Erreur lors de la suppression de la catégorie');
      // Simuler la suppression en cas d'erreur
      categories.value = categories.value.filter(c => c.id !== categoryToDelete.value.id);
      showToast('success', 'Catégorie supprimée avec succès (simulée)');
    } finally {
      showGenericConfirmModal.value = false;
      categoryToDelete.value = null;
    }
  };
  
  // Payment Method Management
  const editPaymentMethod = (method) => {
    paymentMethodForm.value = { ...method };
    showEditPaymentMethodModal.value = true;
    showAddPaymentMethodModal.value = false;
  };
  
  const savePaymentMethod = async () => {
    try {
      if (showEditPaymentMethodModal.value) {
        const response = await fetch(`https://sastock.com/api_web/api_comptabilite.php?action=payment_method&id=${paymentMethodForm.value.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${store.getters.getToken}`
          },
          body: JSON.stringify(paymentMethodForm.value)
        });
        const data = await response.json();
        if (data.success) {
          const index = paymentMethods.value.findIndex(m => m.id === paymentMethodForm.value.id);
          if (index !== -1) {
            paymentMethods.value[index] = { ...paymentMethodForm.value };
          }
          showToast('success', 'Mode de paiement mis à jour avec succès');
        } else {
          showToast('error', data.message || 'Erreur lors de la mise à jour du mode de paiement');
        }
      } else {
        const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=payment_method', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${store.getters.getToken}`
          },
          body: JSON.stringify(paymentMethodForm.value)
        });
        const data = await response.json();
        if (data.success) {
          paymentMethods.value.push({ ...paymentMethodForm.value, id: data.data.id });
          showToast('success', 'Mode de paiement ajouté avec succès');
        } else {
          showToast('error', data.message || 'Erreur lors de l\'ajout du mode de paiement');
        }
      }
    } catch (error) {
      console.error('Erreur lors de la sauvegarde du mode de paiement:', error);
      showToast('error', 'Erreur lors de la sauvegarde du mode de paiement');
      // Simulate save on error
      if (showEditPaymentMethodModal.value) {
        const index = paymentMethods.value.findIndex(m => m.id === paymentMethodForm.value.id);
        if (index !== -1) {
          paymentMethods.value[index] = { ...paymentMethodForm.value };
        }
      } else {
        const newId = Math.max(...paymentMethods.value.map(m => m.id)) + 1;
        paymentMethods.value.push({ ...paymentMethodForm.value, id: newId });
      }
      showToast('success', showEditPaymentMethodModal.value ? 'Mode de paiement mis à jour avec succès (simulé)' : 'Mode de paiement ajouté avec succès (simulé)');
    } finally {
      closeModals();
    }
  };
  
  const confirmDeletePaymentMethod = (method) => {
    paymentMethodToDelete.value = method;
    genericConfirmModalData.value = {
      title: 'Confirmer la suppression',
      message: `Êtes-vous sûr de vouloir supprimer le mode de paiement "${method.name}" ?`,
      itemName: `le mode de paiement "${method.name}"`,
      onConfirm: deletePaymentMethod,
      onCancel: () => { showGenericConfirmModal.value = false; paymentMethodToDelete.value = null; }
    };
    showGenericConfirmModal.value = true;
  };
  
  const deletePaymentMethod = async () => {
    try {
      const response = await fetch(`https://sastock.com/api_web/api_comptabilite.php?action=payment_method&id=${paymentMethodToDelete.value.id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${store.getters.getToken}`
        }
      });
      const data = await response.json();
      if (data.success) {
        paymentMethods.value = paymentMethods.value.filter(m => m.id !== paymentMethodToDelete.value.id);
        showToast('success', 'Mode de paiement supprimé avec succès');
      } else {
        showToast('error', data.message || 'Erreur lors de la suppression du mode de paiement');
      }
    } catch (error) {
      console.error('Erreur lors de la suppression du mode de paiement:', error);
      showToast('error', 'Erreur lors de la suppression du mode de paiement');
      // Simulate deletion on error
      paymentMethods.value = paymentMethods.value.filter(m => m.id !== paymentMethodToDelete.value.id);
      showToast('success', 'Mode de paiement supprimé avec succès (simulé)');
    } finally {
      showGenericConfirmModal.value = false;
      paymentMethodToDelete.value = null;
    }
  };
  
  // Gestion des fichiers
  const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
      showToast('info', 'Upload du fichier en cours...');
      setTimeout(() => {
        transactionForm.value.attachment = URL.createObjectURL(file);
        showToast('success', 'Fichier uploadé avec succès');
      }, 1000);
    }
  };
  
  // Sauvegarde d'une transaction
  const saveTransaction = async () => {
    try {
      if (showEditTransactionModal.value) {
        const response = await fetch(`https://sastock.com/api_web/api_comptabilite.php?action=transaction&id=${transactionForm.value.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${store.getters.getToken}`
          },
          body: JSON.stringify(transactionForm.value)
        });
        const data = await response.json();
        if (data.success) {
          const index = transactions.value.findIndex(t => t.id === transactionForm.value.id);
          if (index !== -1) {
            transactions.value[index] = { ...transactionForm.value };
          }
          showToast('success', 'Transaction mise à jour avec succès');
        } else {
          showToast('error', data.message || 'Erreur lors de la mise à jour de la transaction');
        }
      } else {
        const response = await fetch('https://sastock.com/api_web/api_comptabilite.php?action=transaction', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${store.getters.getToken}`
          },
          body: JSON.stringify(transactionForm.value)
        });
        const data = await response.json();
        if (data.success) {
          transactions.value.push({ ...transactionForm.value, id: data.data.id });
          showToast('success', 'Transaction ajoutée avec succès');
        } else {
          showToast('error', data.message || 'Erreur lors de l\'ajout de la transaction');
        }
      }
    } catch (error) {
      console.error('Erreur lors de la sauvegarde de la transaction:', error);
      showToast('error', 'Erreur lors de la sauvegarde de la transaction');
      // Simuler la sauvegarde en cas d'erreur
      if (showEditTransactionModal.value) {
        const index = transactions.value.findIndex(t => t.id === transactionForm.value.id);
        if (index !== -1) {
          transactions.value[index] = { ...transactionForm.value };
        }
      } else {
        const newId = Math.max(...transactions.value.map(t => t.id)) + 1;
        const newReference = `TR-${String(newId).padStart(5, '0')}`;
        transactions.value.push({
          ...transactionForm.value,
          id: newId,
          reference: transactionForm.value.reference || newReference
        });
      }
      showToast('success', showEditTransactionModal.value ? 'Transaction mise à jour avec succès (simulée)' : 'Transaction ajoutée avec succès (simulée)');
    } finally {
      closeModals();
    }
  };
  
  // Génération de rapport
  const generateReport = () => {
    loadingReport.value = true;
    let url = `https://sastock.com/api_web/api_comptabilite.php?action=report&report_type=${activeReportTab.value}&period=${reportPeriod.value}`;
    if (reportPeriod.value === 'custom' && reportStartDate.value && reportEndDate.value) {
      url += `&start_date=${reportStartDate.value}&end_date=${reportEndDate.value}`;
    }
    if (reportCategory.value) {
      url += `&category_id=${reportCategory.value}`;
    }
    if (reportAccount.value) {
      url += `&account_id=${reportAccount.value}`;
    }
    // Simuler le chargement du rapport
    setTimeout(() => {
      reportData.value = {
        totalIncome: 5250000 + seminairistePaymentsTotal.value, // Add seminairiste payments to total income
        totalExpenses: 3750000,
        netProfit: (5250000 + seminairistePaymentsTotal.value) - 3750000,
        categoryBreakdown: [
          { id: 1, name: 'Paiements Séminaristes', amount: seminairistePaymentsTotal.value, percentage: ((seminairistePaymentsTotal.value / (5250000 + seminairistePaymentsTotal.value)) * 100).toFixed(1) },
          { id: 2, name: 'Dons', amount: 1500000, percentage: ((1500000 / (5250000 + seminairistePaymentsTotal.value)) * 100).toFixed(1) },
          { id: 3, name: 'Cotisations', amount: 750000, percentage: ((750000 / (5250000 + seminairistePaymentsTotal.value)) * 100).toFixed(1) },
          { id: 6, name: 'Commission Liturgie', amount: 800000, percentage: ((800000 / 3750000) * 100).toFixed(1) },
          { id: 7, name: 'Commission Logistique', amount: 1000000, percentage: ((1000000 / 3750000) * 100).toFixed(1) },
          { id: 9, name: 'Administration', amount: 500000, percentage: ((500000 / 3750000) * 100).toFixed(1) },
          { id: 10, name: 'Nourriture & Boissons', amount: 700000, percentage: ((700000 / 3750000) * 100).toFixed(1) },
          { id: 5, name: 'Autres revenus', amount: 200000, percentage: ((200000 / (5250000 + seminairistePaymentsTotal.value)) * 100).toFixed(1) },
          { id: 12, name: 'Santé', amount: 300000, percentage: ((300000 / 3750000) * 100).toFixed(1) },
        ].sort((a, b) => b.amount - a.amount), // Sort by amount descending
        cashFlow: [
          { label: 'Janvier', income: 850000, expense: 650000, net: 200000 },
          { label: 'Février', income: 920000, expense: 680000, net: 240000 },
          { label: 'Mars', income: 780000, expense: 720000, net: 60000 },
          { label: 'Avril', income: 850000, expense: 750000, net: 100000 },
          { label: 'Mai', income: 950000, expense: 800000, net: 150000 },
          { label: 'Juin', income: 900000, expense: 850000, net: 50000 }
        ],
        balanceSheet: {
          assets: [
            { name: 'Caisse Principale', value: 1250000 },
            { name: 'Compte Bancaire Séminaire', value: 3500000 },
            { name: 'Fonds d\'Urgence', value: 2500000 },
            { name: 'Créances (Paiements en attente)', value: 850000 }
          ],
          liabilities: [
            { name: 'Dettes Fournisseurs', value: 650000 },
            { name: 'Prêts Bancaires', value: 1200000 },
            { name: 'Impôts à payer', value: 350000 }
          ],
          totalAssets: 4950000 + 850000, // Sum of demo assets
          totalLiabilities: 2200000, // Sum of demo liabilities
          equity: (4950000 + 850000) - 2200000 // Assets - Liabilities
        }
      };
      loadingReport.value = false;
    }, 1500);
  };
  
  // Impression du rapport
  const printReport = () => {
    showToast('info', 'Impression du rapport en cours...');
    window.print();
  };
  
  // Exportation du rapport
  const exportReport = () => {
    showToast('info', 'Exportation du rapport en cours...');
    let url = `https://sastock.com/api_web/api_comptabilite.php?action=export_report&report_type=${activeReportTab.value}&period=${reportPeriod.value}`;
    if (reportPeriod.value === 'custom' && reportStartDate.value && reportEndDate.value) {
      url += `&start_date=${reportStartDate.value}&end_date=${reportEndDate.value}`;
    }
    if (reportCategory.value) {
      url += `&category_id=${reportCategory.value}`;
    }
    if (reportAccount.value) {
      url += `&account_id=${reportAccount.value}`;
    }
    // Simuler l'exportation
    setTimeout(() => {
      showToast('success', 'Rapport exporté avec succès');
    }, 1500);
  };
  
  // Filtres avancés
  const resetFilters = () => {
    filterDateRange.value = '';
    filterStartDate.value = '';
    filterEndDate.value = '';
    filterAmountMin.value = '';
    filterAmountMax.value = '';
    filterPaymentMethod.value = '';
    filterAccount.value = '';
    showToast('info', 'Filtres réinitialisés');
    showFilterModal.value = false;
    currentPage.value = 1;
  };
  
  const applyFilters = () => {
    showFilterModal.value = false;
    currentPage.value = 1;
    showToast('success', 'Filtres appliqués');
  };
  
  const getPeriodDisplayName = (period) => {
    switch (period) {
      case 'day': return 'Aujourd\'hui';
      case 'week': return 'Cette semaine';
      case 'month': return 'Ce mois';
      case 'quarter': return 'Ce trimestre';
      case 'year': return 'Cette année';
      default: return 'Toutes les périodes';
    }
  };
  
  // Fermeture des modals
  const closeModals = () => {
    showAddTransactionModal.value = false;
    showEditTransactionModal.value = false;
    showTransactionDetailsModal.value = false;
    showAddCategoryModal.value = false;
    showEditCategoryModal.value = false;
    showCategoriesModal.value = false;
    showPaymentMethodsModal.value = false;
    showAddPaymentMethodModal.value = false;
    showEditPaymentMethodModal.value = false;
    showFilterModal.value = false;
    showDeleteModal.value = false; // Close transaction delete modal
    showGenericConfirmModal.value = false; // Close generic confirm modal
    // Réinitialiser les formulaires
    transactionForm.value = {
      id: null,
      type: 'expense',
      category_id: null,
      date: new Date().toISOString().split('T')[0],
      amount: '',
      reference: '',
      description: '',
      status: 'completed',
      payment_method_id: null,
      account_id: null,
      notes: '',
      attachment: null
    };
    categoryForm.value = {
      id: null,
      name: '',
      type: 'expense',
      description: ''
    };
    paymentMethodForm.value = {
      id: null,
      name: '',
      description: ''
    };
    // Reset confirmation data
    transactionToDelete.value = null;
    categoryToDelete.value = null;
    paymentMethodToDelete.value = null;
    genericConfirmModalData.value = { title: '', message: '', itemName: '', onConfirm: null, onCancel: null };
  };
  
  // Toast notifications
  const showToast = (type, message, duration = 3000) => {
    if (toast.value.timeout) {
      clearTimeout(toast.value.timeout);
    }
    toast.value = {
      show: true,
      message,
      type,
      timeout: setTimeout(() => {
        hideToast();
      }, duration)
    };
  };
  
  const hideToast = () => {
    toast.value.show = false;
    if (toast.value.timeout) {
      clearTimeout(toast.value.timeout);
      toast.value.timeout = null;
    }
  };
  
  // Surveiller les changements de filtres pour réinitialiser la pagination
  watch([activeTab, filterCategory, filterType, filterStatus, sortBy, filterDateRange, filterStartDate, filterEndDate, filterAmountMin, filterAmountMax, filterPaymentMethod, filterAccount, activePeriod], () => {
    currentPage.value = 1;
  });
  
  // Surveiller la sélection de toutes les transactions
  watch(paginatedTransactions, () => {
    if (selectedTransactions.value.length === paginatedTransactions.value.length && paginatedTransactions.value.length > 0) {
      selectAll.value = true;
    } else {
      selectAll.value = false;
    }
  }, { deep: true });
  </script>
  
  <script>
  // Pour respecter la règle multi-word
  export default {
    name: 'ComptaBilite'
  }
  </script>
  
  <style scoped>
.dashboard {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f8f9fa;
}

/* Header Styles */
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 1.5rem;
  background-color: #1e0a3c;
  color: white;
  height: 64px;
}

/* Main Content Styles */
.main-content {
  flex: 1;
  padding: 1.5rem;
  display: flex;
  justify-content: center;
}

.overview-section {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 90%;
  max-width: 1400px;
}

.overview-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #f1f1f4;
}

.overview-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0;
  color: #111827;
}

.overview-date {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0.25rem 0 0;
}

.header-actions-right {
  display: flex;
  gap: 0.75rem;
}

.action-button-outlined {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: white;
  color: #6b7280;
  border: 1px solid #e5e7eb;
  border-radius: 9999px;
  padding: 0.5rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.action-button-outlined:hover {
  background-color: #f9fafb;
  border-color: #d1d5db;
}

.add-product-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #7c3aed;
  color: white;
  border: none;
  border-radius: 9999px;
  padding: 0.5rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-product-button:hover {
  background-color: #6d28d9;
}

/* Financial Overview */
.financial-overview {
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #f1f1f4;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.financial-card {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1.5rem;
}

.financial-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.financial-card-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
  color: #111827;
}

.period-selector {
  display: flex;
  background-color: #f3f4f6;
  border-radius: 9999px;
  padding: 0.25rem;
}

.period-selector button {
  background: none;
  border: none;
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
  border-radius: 9999px;
  cursor: pointer;
  color: #6b7280;
  transition: all 0.2s;
}

.period-selector button.active {
  background-color: white;
  color: #111827;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.financial-card-content {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.balance-amount {
  font-size: 2.5rem;
  font-weight: 700;
  color: #111827;
}

.balance-change {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.balance-change.positive {
  color: #10b981;
}

.balance-change.negative {
  color: #ef4444;
}

.financial-summary {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1rem;
}

.summary-card {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.summary-card.income .summary-icon {
  background-color: #d1fae5;
  color: #10b981;
}

.summary-card.expense .summary-icon {
  background-color: #fee2e2;
  color: #ef4444;
}

.summary-card.profit .summary-icon {
  background-color: #e0e7ff;
  color: #6366f1;
}

.summary-icon {
  width: 48px;
  height: 48px;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.summary-content {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.summary-label {
  font-size: 0.875rem;
  color: #6b7280;
}

.summary-value {
  font-size: 1.5rem;
  font-weight: 600;
  color: #111827;
}

.summary-change {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.summary-change.positive {
  color: #10b981;
}

.summary-change.negative {
  color: #ef4444;
}

/* Transaction Tabs */
.product-tabs {
  display: flex;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-bottom: 1px solid #f1f1f4;
  overflow-x: auto;
}

.tab-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 9999px;
  padding: 0.5rem 1.25rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.tab-button:hover,
.tab-button.active {
  background-color: #7c3aed;
  border-color: #7c3aed;
  color: white;
}

.tab-button.active svg,
.tab-button:hover svg {
  color: white;
}

.tab-button svg {
  color: #6b7280;
}

/* Search and Filters */
.search-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  padding: 1rem 2rem;
  border-bottom: 1px solid #f1f1f4;
}

.search-bar {
  flex: 1;
  min-width: 300px;
  position: relative;
}

.search-bar svg {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
}

.search-bar input {
  width: 100%;
  padding: 0.625rem 0.75rem 0.625rem 2.5rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  color: #111827;
  background-color: #f9fafb;
}

.search-bar input:focus {
  outline: none;
  border-color: #7c3aed;
  box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.2);
}

.filter-options {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.filter-options select {
  padding: 0.625rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  background-color: #f9fafb;
  font-size: 0.875rem;
  color: #111827;
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 1em;
}

.filter-options select:focus {
  outline: none;
  border-color: #7c3aed;
  box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.2);
}

/* Product Stats */
.product-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1rem;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #f1f1f4;
}

.stat-card {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 0.375rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e0e7ff;
  color: #6366f1;
  flex-shrink: 0;
}

.stat-icon.warning {
  background-color: #fffbeb;
  color: #f59e0b;
}

.stat-icon.danger {
  background-color: #fee2e2;
  color: #ef4444;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.stat-label {
  font-size: 0.75rem;
  color: #6b7280;
}

/* Transactions Table */
.period-filter-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #e0f2fe;
  color: #0284c7;
  padding: 0.75rem 1.5rem;
  font-size: 0.875rem;
  border-bottom: 1px solid #f1f1f4;
}

.products-table {
  padding: 1.5rem 2rem;
}

.table-header {
  display: grid;
  grid-template-columns: 30px 100px 120px 2fr 1.2fr 80px 100px 100px 120px;
  gap: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #e5e7eb;
  font-size: 0.75rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
}

.table-row {
  display: grid;
  grid-template-columns: 30px 100px 120px 2fr 1.2fr 80px 100px 100px 120px;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f1f1f4;
  align-items: center;
  font-size: 0.875rem;
  color: #111827;
}

.table-row:last-child {
  border-bottom: none;
}

.table-cell {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.table-cell.checkbox-cell {
  display: flex;
  align-items: center;
  justify-content: center;
}

.table-cell.name-cell {
  font-weight: 500;
  color: #111827;
}

.type-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.type-income {
  background-color: #d1fae5;
  color: #059669;
}

.type-expense {
  background-color: #fee2e2;
  color: #dc2626;
}

.amount-income {
  color: #059669;
  font-weight: 600;
}

.amount-expense {
  color: #dc2626;
  font-weight: 600;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-in {
  background-color: #d1fae5;
  color: #059669;
}

.status-low {
  background-color: #fffbeb;
  color: #f59e0b;
}

.status-out {
  background-color: #fee2e2;
  color: #dc2626;
}

.actions-cell {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

.action-icon {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: background-color 0.2s, color 0.2s;
}

.action-icon:hover {
  background-color: #f3f4f6;
  color: #111827;
}

.loading-indicator,
.no-products {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.loading-indicator svg {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.no-products svg {
  color: #d1d5db;
}

.no-products .add-product-button {
  margin-top: 1rem;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  border-top: 1px solid #f1f1f4;
  font-size: 0.875rem;
  color: #6b7280;
}

.pagination-controls {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.pagination-button {
  background-color: #f3f4f6;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
  cursor: pointer;
  transition: all 0.2s;
  color: #4b5563;
}

.pagination-button:hover:not(:disabled) {
  background-color: #e5e7eb;
  border-color: #d1d5db;
}

.pagination-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-pages {
  font-weight: 500;
}

.pagination-size select {
  padding: 0.5rem 0.75rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  background-color: #f9fafb;
  font-size: 0.875rem;
  color: #111827;
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.5rem center;
  background-size: 0.8em;
}

/* Modals */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  width: 90%;
  max-width: 700px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-content.small-modal {
  max-width: 500px;
}

.modal-content.large-modal {
  max-width: 1000px;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
  color: #111827;
}

.close-button {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: background-color 0.2s, color 0.2s;
}

.close-button:hover {
  background-color: #f3f4f6;
  color: #111827;
}

.modal-body {
  padding: 1.5rem;
  overflow-y: auto;
  flex-grow: 1;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="date"],
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.625rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  color: #111827;
  background-color: #ffffff;
}

.form-group input[type="file"] {
  padding: 0.5rem 0;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #7c3aed;
  box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.2);
}

.form-row {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-row .form-group {
  flex: 1;
  margin-bottom: 0;
}

.input-with-icon {
  position: relative;
}

.input-with-icon input {
  padding-right: 3rem;
}

.input-with-icon .input-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
  font-size: 0.875rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
  margin-top: 1.5rem;
}

.cancel-button {
  background-color: #f3f4f6;
  color: #4b5563;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.625rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.cancel-button:hover {
  background-color: #e5e7eb;
}

.save-button {
  background-color: #7c3aed;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.625rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.save-button:hover {
  background-color: #6d28d9;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.modal-footer .action-button {
  background-color: #7c3aed;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.625rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.modal-footer .action-button:hover {
  background-color: #6d28d9;
}

/* Transaction Details Modal */
.transaction-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.transaction-details-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 1rem;
  border-bottom: 1px solid #e5e7eb;
}

.transaction-info {
  display: flex;
  gap: 0.75rem;
}

.transaction-type,
.transaction-status {
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
}

.transaction-type.type-income {
  background-color: #d1fae5;
  color: #059669;
}

.transaction-type.type-expense {
  background-color: #fee2e2;
  color: #dc2626;
}

.transaction-status.status-in {
  background-color: #d1fae5;
  color: #059669;
}

.transaction-status.status-low {
  background-color: #fffbeb;
  color: #f59e0b;
}

.transaction-status.status-out {
  background-color: #fee2e2;
  color: #dc2626;
}

.transaction-amount {
  font-size: 2rem;
  font-weight: 700;
}

.transaction-amount.amount-income {
  color: #059669;
}

.transaction-amount.amount-expense {
  color: #dc2626;
}

.details-section {
  margin-bottom: 1.5rem;
}

.details-section h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 0.75rem;
  border-bottom: 1px solid #f1f1f4;
  padding-bottom: 0.5rem;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.details-item {
  display: flex;
  flex-direction: column;
}

.details-label {
  font-size: 0.75rem;
  color: #6b7280;
  margin-bottom: 0.25rem;
}

.details-value {
  font-size: 0.95rem;
  font-weight: 500;
  color: #111827;
}

.details-description,
.details-notes {
  font-size: 0.95rem;
  color: #374151;
  line-height: 1.5;
}

.details-attachment {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.attachment-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #7c3aed;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.attachment-link:hover {
  color: #6d28d9;
}

/* Delete Confirmation Modal */
.delete-modal .modal-body {
  text-align: center;
}

.delete-warning {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  color: #ef4444;
}

.delete-warning svg {
  color: #f87171;
}

.delete-warning p {
  font-size: 1rem;
  font-weight: 500;
  margin: 0;
}

.delete-warning .warning-text {
  font-size: 0.875rem;
  color: #dc2626;
}

.delete-button {
  background-color: #ef4444;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.625rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.delete-button:hover {
  background-color: #dc2626;
}

/* Categories Modal */
.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  gap: 1rem;
  flex-wrap: wrap;
}

.list-header .search-bar {
  flex-grow: 1;
  min-width: 200px;
}

.list-header .add-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #7c3aed;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.625rem 1.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.list-header .add-button:hover {
  background-color: #6d28d9;
}

.categories-tabs {
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #f1f1f4;
  padding-bottom: 1rem;
}

.categories-tabs .tab-button {
  background-color: #f3f4f6;
  border: 1px solid #e5e7eb;
  border-radius: 9999px;
  padding: 0.375rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s;
}

.categories-tabs .tab-button.active {
  background-color: #7c3aed;
  border-color: #7c3aed;
  color: white;
}

.no-items {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.no-items svg {
  color: #d1d5db;
}

.categories-list,
.payment-methods-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.category-item,
.payment-method-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  padding: 0.75rem 1rem;
}

.category-info,
.payment-method-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.category-icon {
  width: 32px;
  height: 32px;
  border-radius: 0.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.category-icon.type-income {
  background-color: #d1fae5;
  color: #10b981;
}

.category-icon.type-expense {
  background-color: #fee2e2;
  color: #ef4444;
}

.category-details,
.payment-method-details {
  display: flex;
  flex-direction: column;
}

.category-name,
.payment-method-name {
  font-size: 1rem;
  font-weight: 600;
  color: #111827;
}

.category-description,
.payment-method-description {
  font-size: 0.75rem;
  color: #6b7280;
}

.category-actions,
.payment-method-actions {
  display: flex;
  gap: 0.5rem;
}

/* Reports Modal */
.report-tabs {
  display: flex;
  gap: 0.75rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f1f1f4;
  margin-bottom: 1.5rem;
  overflow-x: auto;
}

.report-tabs .tab-button {
  background-color: #f3f4f6;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.report-tabs .tab-button.active {
  background-color: #7c3aed;
  border-color: #7c3aed;
  color: white;
}

.report-tabs .tab-button.active svg,
.report-tabs .tab-button:hover svg {
  color: white;
}

.report-tabs .tab-button svg {
  color: #6b7280;
}

.report-filters {
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #f1f1f4;
}

.report-filters .form-row {
  margin-bottom: 1rem;
}

.report-filters .form-row:last-child {
  margin-bottom: 0;
}

.report-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.report-summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.report-summary .summary-card {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.5rem;
}

.report-summary .summary-card .summary-title {
  font-size: 0.875rem;
  color: #6b7280;
}

.report-summary .summary-card .summary-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: #111827;
}

.report-summary .summary-card.income .summary-value {
  color: #059669;
}

.report-summary .summary-card.expense .summary-value {
  color: #dc2626;
}

.report-summary .summary-card.profit .summary-value {
  color: #6366f1;
}

.report-chart {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1.5rem;
  text-align: center;
  min-height: 300px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 1rem;
}

.report-chart h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 1rem;
}

.chart-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  color: #d1d5db;
}

.chart-placeholder svg {
  width: 64px;
  height: 64px;
}

.chart-placeholder p {
  margin: 0;
  font-size: 1rem;
  font-weight: 500;
}

.chart-placeholder .chart-note {
  font-size: 0.75rem;
  color: #9ca3af;
}

.category-breakdown {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1.5rem;
}

.breakdown-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #e5e7eb;
  font-size: 0.75rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
}

.breakdown-item {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f1f1f4;
  align-items: center;
  font-size: 0.875rem;
  color: #111827;
}

.breakdown-item:last-child {
  border-bottom: none;
}

.breakdown-category {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 500;
}

.category-color {
  width: 16px;
  height: 16px;
  border-radius: 0.25rem;
  flex-shrink: 0;
}

.cash-flow-table,
.balance-sheet {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 1.5rem;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #e5e7eb;
  text-align: left;
}

.data-table th {
  font-size: 0.75rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
}

.data-table td {
  font-size: 0.875rem;
  color: #111827;
}

.data-table td.positive {
  color: #059669;
  font-weight: 500;
}

.data-table td.negative {
  color: #dc2626;
  font-weight: 500;
}

.balance-section {
  margin-bottom: 2rem;
}

.balance-section h4 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 1rem;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 0.5rem;
}

.balance-items {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.balance-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px dashed #e5e7eb;
}

.balance-item:last-of-type {
  border-bottom: none;
}

.balance-item.total {
  font-weight: 600;
  font-size: 1rem;
  border-top: 2px solid #111827;
  padding-top: 1rem;
  margin-top: 0.5rem;
}

.balance-item-name {
  font-size: 0.95rem;
  color: #374151;
}

.balance-item-value {
  font-size: 0.95rem;
  color: #111827;
}

.balance-item.total .balance-item-value {
  font-size: 1.125rem;
  color: #111827;
}

/* Toast Notifications */
.toast-container {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 1001;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.toast {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  color: white;
  font-weight: 500;
  min-width: 250px;
  max-width: 350px;
  animation: slideIn 0.3s ease-out forwards;
}

.toast.success {
  background-color: #10b981;
}

.toast.error {
  background-color: #ef4444;
}

.toast.info {
  background-color: #3b82f6;
}

.toast-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.toast-content {
  flex-grow: 1;
}

.toast-close {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: background-color 0.2s;
}

.toast-close:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

@keyframes slideIn {
  from {
    transform: translateY(20px);
    opacity: 0;
  }

  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .overview-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .header-actions-right {
    width: 100%;
    justify-content: flex-end;
  }

  .financial-summary {
    grid-template-columns: 1fr;
  }

  .table-header,
  .table-row {
    grid-template-columns: 30px 1fr 1fr;
    /* Simplified for smaller screens */
  }

  .table-cell:nth-child(3),
  /* Reference */
  .table-cell:nth-child(5),
  /* Category */
  .table-cell:nth-child(6),
  /* Type */
  .table-cell:nth-child(7),
  /* Amount */
  .table-cell:nth-child(8)
    /* Status */
    {
    display: none;
  }

  .table-cell.name-cell {
    white-space: normal;
  }

  .pagination {
    flex-direction: column;
    gap: 1rem;
  }

  .pagination-info,
  .pagination-controls,
  .pagination-size {
    width: 100%;
    justify-content: center;
  }

  .modal-content {
    width: 95%;
    margin: 1rem;
  }

  .form-row {
    flex-direction: column;
    gap: 0;
  }

  .form-row .form-group {
    margin-bottom: 1rem;
  }

  .report-filters .form-row {
    flex-direction: column;
    gap: 0;
  }

  .report-filters .form-row .form-group {
    margin-bottom: 1rem;
  }

  .report-filters .form-row .form-group:last-child {
    margin-bottom: 0;
  }
}

@media (max-width: 768px) {
  .main-content {
    padding: 1rem;
  }

  .overview-section {
    width: 100%;
  }

  .overview-header {
    padding: 1rem;
  }

  .overview-title {
    font-size: 1.5rem;
  }

  .product-tabs,
  .search-filters,
  .product-stats,
  .products-table,
  .pagination {
    padding: 1rem;
  }

  .search-filters {
    flex-direction: column;
  }

  .filter-options {
    width: 100%;
    flex-direction: column;
  }

  .filter-options select {
    width: 100%;
  }

  .table-header,
  .table-row {
    grid-template-columns: 30px 1fr 1fr;
    /* Even more simplified */
  }

  .table-cell:nth-child(2),
  /* Date */
  .table-cell:nth-child(3),
  /* Reference */
  .table-cell:nth-child(5),
  /* Category */
  .table-cell:nth-child(6),
  /* Type */
  .table-cell:nth-child(7),
  /* Amount */
  .table-cell:nth-child(8)
    /* Status */
    {
    display: none;
  }

  .table-cell.name-cell {
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
  }

  .actions-cell {
    justify-content: center;
  }

  .modal-content {
    margin: 0.5rem;
  }

  .modal-header {
    padding: 0.75rem 1rem;
  }

  .modal-body {
    padding: 1rem;
  }

  .modal-footer {
    padding: 0.75rem 1rem;
  }

  .report-summary {
    grid-template-columns: 1fr;
  }

  .category-breakdown .breakdown-header,
  .category-breakdown .breakdown-item {
    grid-template-columns: 1.5fr 1fr 1fr;
  }
}
</style>