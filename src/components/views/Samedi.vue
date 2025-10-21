<template>
    <div class="dashboard">
      <main class="main-content">
        <div class="overview-section">
           Header 
          <div class="overview-header">
            <div class="header-left">
              <button @click="$router.go(-1)" class="action-button-outlined">
                <ArrowLeftIcon class="w-5 h-5" />
              </button>
              <div>
                <h1 class="overview-title flex items-center">
                  <UtensilsCrossedIcon class="w-6 h-6 lg:w-8 lg:h-8 text-blue-600 mr-3" />
                  Gestion de la Restauration SENAFOI 25
                </h1>
                <p class="overview-date">Service de restauration - {{ currentDateFormatted }}</p>
              </div>
            </div>
            <div class="header-actions-right">
              <div class="period-selector">
                <button
                  v-for="mealType in mealTypes"
                  :key="mealType.id"
                  :class="{ active: selectedMealType === mealType.id }"
                  @click="selectedMealType = mealType.id"
                  class="meal-type-btn"
                >
                  <component :is="mealType.icon" class="w-4 h-4" />
                  {{ mealType.name }}
                </button>
              </div>
              <input type="date" v-model="selectedDate" class="date-input" />
              <button @click="exportMealReport()" class="export-button green-button">
                <DownloadIcon class="w-4 h-4 lg:w-5 lg:h-5" />
                <span>Rapport Excel</span>
              </button>
            </div>
          </div>
           Statistiques 
          <div class="product-stats">
            <div class="stat-card">
              <div class="stat-icon stat-icon-blue">
                <UsersIcon class="w-5 h-5 lg:w-6 lg:h-6" />
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ totalParticipants }}</div>
                <div class="stat-label">Total Participants</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon stat-icon-purple">
                <GraduationCapIcon class="w-5 h-5 lg:w-6 lg:h-6" />
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ totalSeminarists }}</div>
                <div class="stat-label">Séminaristes</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon stat-icon-orange">
                <UserCheckIcon class="w-5 h-5 lg:w-6 lg:h-6" />
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ totalSRs }}</div>
                <div class="stat-label">SR</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon stat-icon-green">
                <CheckCircleIcon class="w-5 h-5 lg:w-6 lg:h-6" />
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ eatenCount }}</div>
                <div class="stat-label">Ont mangé</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon stat-icon-red">
                <ClockIcon class="w-5 h-5 lg:w-6 lg:h-6" />
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ remainingCount }}</div>
                <div class="stat-label">N'ont pas mangé</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon stat-icon-indigo">
                <ZapIcon class="w-5 h-5 lg:w-6 lg:h-6" />
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ mealProgress.toFixed(1) }}%</div>
                <div class="stat-label">Progression</div>
              </div>
            </div>
          </div>
           Loading indicator 
          <div v-if="loadingParticipants" class="loading-section">
            <div class="loading-indicator">
              <Loader2Icon class="w-8 h-8 animate-spin" />
              <p>Chargement des participants... ({{ loadedCount }}/{{ totalExpected }})</p>
              <div class="progress-bar-loading">
                <div class="progress-fill-loading" :style="{ width: loadingProgress + '%' }"></div>
              </div>
            </div>
          </div>
           Section de scan QR 
          <div class="quick-search-section">
            <div class="quick-search-card">
              <div class="card-header">
                <QrCodeIcon class="w-6 h-6 text-blue-600" />
                <h3>Scanner QR Code - {{ selectedMealTypeName }}</h3>
              </div>
              <div class="search-form">
                <div class="search-input-group">
                  <input
                    v-model="matriculeInput"
                    @keyup.enter="handleScan"
                    type="text"
                    placeholder="Scanner QR code ou entrer le matricule"
                    class="search-input"
                  />
                  <button @click="startQRScanner" class="qr-button" :class="{ 'active': showQRScanner }">
                    <ScanLineIcon class="w-5 h-5" />
                    {{ showQRScanner ? 'Arrêter' : 'Scanner QR' }}
                  </button>
                  <button @click="handleScan" class="search-button">
                    <SearchIcon class="w-4 h-4" />
                    Vérifier
                  </button>
                </div>
                 QR Scanner 
                <div v-if="showQRScanner" class="qr-scanner-container">
                  <div class="qr-scanner-header">
                    <h4>Scanner le QR Code du participant</h4>
                    <button @click="stopQRScanner" class="close-scanner">
                      <XIcon class="w-5 h-5" />
                    </button>
                  </div>
                  <div class="qr-scanner-video">
                    <video ref="qrVideo" autoplay playsinline class="qr-video"></video>
                    <div class="qr-overlay">
                      <div class="qr-frame"></div>
                    </div>
                  </div>
                  <p class="qr-instructions">Placez le QR code dans le cadre pour le scanner</p>
                </div>
              </div>
               Résultat du scan 
              <div v-if="participantStatus" class="seminarist-result-card" :class="participantStatus.levelStatus">
                <div class="result-header">
                  <div class="result-icon">
                    <CheckCircleIcon v-if="participantStatus.levelStatus === 'authorized'" class="w-6 h-6" />
                    <AlertCircleIcon v-else-if="participantStatus.levelStatus === 'not-authorized'" class="w-6 h-6" />
                    <InfoIcon v-else class="w-6 h-6" />
                  </div>
                  <div class="result-content">
                    <p class="result-message" :class="{
                      'text-green-700 text-3xl': participantStatus.levelStatus === 'authorized',
                      'text-red-700 text-3xl': participantStatus.levelStatus === 'not-authorized',
                      'text-gray-900 text-lg': !participantStatus.levelStatus
                    }">
                      {{ participantStatus.levelMessage }}
                    </p>
                    <p class="font-semibold text-gray-800 mb-2">{{ participantStatus.message }}</p>
                    <div v-if="participantStatus.participant" class="seminarist-details">
                      <div class="seminarist-profile">
                        <img :src="participantStatus.participant.photo" alt="Photo" class="profile-photo" />
                        <div class="profile-info">
                          <h4 class="profile-name">{{ participantStatus.participant.name }}</h4>
                          <p class="profile-matricule">Matricule: {{ participantStatus.participant.matricule }}</p>
                          <p class="profile-type">Niveau: <strong>{{ participantStatus.participant.niveau }}</strong></p>
                          <p class="profile-meal">Repas: <strong>{{ selectedMealTypeName }}</strong></p>
                          <p v-if="participantStatus.lastMealTime" class="profile-time">
                            Dernier repas: {{ participantStatus.lastMealTime }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                 Bouton de validation 
                <div v-if="participantStatus.action === 'validate'" class="meal-validation-form">
                  <button @click="validateMeal(participantStatus.participant.matricule)" class="validate-button">
                    <CheckCircleIcon class="w-4 h-4" />
                    Valider le depart
                  </button>
                </div>
              </div>
            </div>
          </div>
           Section des listes 
          <div class="meal-lists-section">
            <div class="section-header">
              <ListChecksIcon class="w-6 h-6 text-blue-600" />
              <h2 class="section-title">Listes des Repas - {{ selectedMealTypeName }}</h2>
            </div>
            <div v-if="loadingParticipants" class="loading-indicator">
              <Loader2Icon class="w-6 h-6" />
              Chargement des données...
            </div>
            <div v-else class="meal-lists-grid">
               Liste de ceux qui ont mangé 
              <div class="meal-list-card eaten-list">
                <div class="list-header">
                  <div class="list-icon eaten-icon">
                    <CheckCircleIcon class="w-6 h-6" />
                  </div>
                  <div class="list-info">
                    <h4 class="list-title">Ont mangé</h4>
                    <p class="list-count">{{ eatenParticipants.length }} participants</p>
                    <p class="list-breakdown">{{ eatenSeminarists.length }} séminaristes • {{ eatenSRs.length }} SR</p>
                  </div>
                  <button @click="openMealListModal('eaten', eatenParticipants)" class="view-list-btn">
                    <EyeIcon class="w-4 h-4" />
                    Voir la liste
                  </button>
                </div>
                <div class="list-preview">
                  <div v-for="participant in eatenParticipants.slice(0, 3)" :key="participant.matricule" class="preview-item">
                    <img :src="participant.photo" alt="Photo" class="preview-photo" />
                    <div class="preview-info">
                      <span class="preview-name">{{ participant.name }}</span>
                      <span class="preview-type">{{ participant.type }}</span>
                    </div>
                  </div>
                  <div v-if="eatenParticipants.length > 3" class="preview-more">
                    +{{ eatenParticipants.length - 3 }} autres
                  </div>
                </div>
              </div>
               Liste de ceux qui n'ont pas mangé 
              <div class="meal-list-card not-eaten-list">
                <div class="list-header">
                  <div class="list-icon not-eaten-icon">
                    <ClockIcon class="w-6 h-6" />
                  </div>
                  <div class="list-info">
                    <h4 class="list-title">N'ont pas mangé</h4>
                    <p class="list-count">{{ notEatenParticipants.length }} participants</p>
                    <p class="list-breakdown">{{ notEatenSeminarists.length }} séminaristes • {{ notEatenSRs.length }} SR</p>
                  </div>
                  <button @click="openMealListModal('not-eaten', notEatenParticipants)" class="view-list-btn">
                    <EyeIcon class="w-4 h-4" />
                    Voir la liste
                  </button>
                </div>
                <div class="list-preview">
                  <div v-for="participant in notEatenParticipants.slice(0, 3)" :key="participant.matricule" class="preview-item">
                    <img :src="participant.photo" alt="Photo" class="preview-photo" />
                    <div class="preview-info">
                      <span class="preview-name">{{ participant.name }}</span>
                      <span class="preview-type">{{ participant.type }}</span>
                    </div>
                  </div>
                  <div v-if="notEatenParticipants.length > 3" class="preview-more">
                    +{{ notEatenParticipants.length - 3 }} autres
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
       Modal pour les listes de repas 
      <div v-if="showMealListModal" class="modal" @click.self="closeMealListModal">
        <div class="modal-content view-modal">
          <div class="modal-header">
            <h2>{{ modalTitle }}</h2>
            <button @click="closeMealListModal" class="close-button">
              <XIcon class="w-6 h-6" />
            </button>
          </div>
          <div class="modal-body">
            <div v-if="selectedMealList.length === 0" class="no-items">
              <UsersIcon class="w-12 h-12" />
              <p>Aucun participant dans cette liste</p>
            </div>
            <div v-else>
               Filtres par type 
              <div class="modal-filters">
                <button
                  v-for="filter in typeFilters"
                  :key="filter.key"
                  @click="selectedTypeFilter = filter.key"
                  :class="{ active: selectedTypeFilter === filter.key }"
                  class="filter-btn"
                >
                  <component :is="filter.icon" class="w-4 h-4" />
                  {{ filter.label }} ({{ filter.count }})
                </button>
              </div>
              <div class="seminarists-grid">
                <div v-for="participant in filteredMealList" :key="participant.matricule" class="seminarist-card">
                  <img :src="participant.photo" alt="Photo" class="seminarist-photo" />
                  <div class="seminarist-info">
                    <h4 class="seminarist-name">{{ participant.name }}</h4>
                    <p class="seminarist-id">{{ participant.matricule }}</p>
                    <p class="seminarist-type">{{ participant.type }}</p>
                    <p v-if="participant.mealTime" class="seminarist-meal-time">
                      Repas pris à: {{ participant.mealTime }}
                    </p>
                  </div>
                  <button v-if="selectedMealListType === 'not-eaten'" @click="quickValidateMeal(participant)" class="quick-validate-btn">
                    <CheckCircleIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button @click="exportMealList(selectedMealListType)" class="save-button download-button-purple">
              <DownloadIcon class="w-4 h-4" />
              <span>Exporter Liste</span>
            </button>
            <button @click="closeMealListModal" class="cancel-button">Fermer</button>
          </div>
        </div>
      </div>
       Alert Messages 
      <div class="toast-container" v-if="alert.show">
        <div class="toast" :class="alert.type">
          <div class="toast-icon">
            <CheckCircleIcon v-if="alert.type === 'success'" class="w-5 h-5" />
            <AlertCircleIcon v-else-if="alert.type === 'error'" class="w-5 h-5" />
            <AlertTriangleIcon v-else-if="alert.type === 'warning'" class="w-5 h-5" />
            <InfoIcon v-else class="w-5 h-5" />
          </div>
          <div class="toast-content">{{ alert.message }}</div>
          <button class="toast-close" @click="alert.show = false">
            <XIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch, onBeforeUnmount } from 'vue';
  import {
    ArrowLeftIcon,
    UtensilsCrossedIcon,
    DownloadIcon,
    UsersIcon,
    CheckCircleIcon,
    ClockIcon,
    ZapIcon,
    QrCodeIcon,
    ScanLineIcon,
    SearchIcon,
    XIcon,
    AlertCircleIcon,
    AlertTriangleIcon,
    InfoIcon,
    ListChecksIcon,
    EyeIcon,
    Loader2Icon,
    CoffeeIcon, // For breakfast
    SoupIcon, // For lunch
    MoonIcon, // For dinner
    GraduationCapIcon, // For seminarists
    UserCheckIcon // For SR
  } from 'lucide-vue-next';
  
  // API Base URLs
  const API_BASE_URL_SEMINARISTES_LIST = 'https://sogetrag.com/api/seminaire_list_api.php';
  const API_BASE_URL_SR = 'https://sogetrag.com/api/api_sr.php';
  const API_BASE_URL_RESTAURATION = 'https://sogetrag.com/api/restauration_api.php';
  
  // Reactive states
  const seminarists = ref([]); // All seminarists
  const srs = ref([]); // All SR
  const loadingParticipants = ref(false);
  const selectedDate = ref(new Date().toISOString().split('T')[0]);
  const selectedMealType = ref('lunch');
  const matriculeInput = ref('');
  const participantStatus = ref(null);
  
  // Loading progress tracking
  const loadedCount = ref(0);
  const totalExpected = ref(0);
  const loadingProgress = ref(0);
  
  // QR Scanner states
  const showQRScanner = ref(false);
  const qrVideo = ref(null);
  const qrStream = ref(null);
  const qrCodeReader = ref(null);
  
  // Modal states
  const showMealListModal = ref(false);
  const selectedMealList = ref([]);
  const selectedMealListType = ref('');
  const selectedTypeFilter = ref('all');
  
  // Meal types
  const mealTypes = ref([
    { id: 'breakfast', name: 'Petit-déjeuner', icon: CoffeeIcon, time: '07:00' },
    { id: 'lunch', name: 'Déjeuner', icon: SoupIcon, time: '12:00' },
    { id: 'dinner', name: 'Dîner', icon: MoonIcon, time: '19:00' }
  ]);
  
  // Meal records: Format: { 'matricule': { time: timestamp } }
  const mealRecords = ref({});
  
  // Toast notifications
  const alert = ref({
    show: false,
    type: 'success',
    message: ''
  });
  
  // Load data on mount
  onMounted(async () => {
    await loadAllParticipants(); // Load both seminarists and SR
    await fetchMealRecords(); // Fetch meal records for the current date/meal type
    await fetchRestaurationStats(); // Fetch stats
  
    // Initialize QR code reader
    if (typeof window !== 'undefined') {
      try {
        const { BrowserQRCodeReader } = await import('@zxing/library');
        qrCodeReader.value = new BrowserQRCodeReader();
      } catch (error) {
        console.warn('QR Code reader not available:', error);
      }
    }
  });
  
  // Cleanup on unmount
  onBeforeUnmount(() => {
    stopQRScanner();
  });
  
  // Watch for date or meal type changes
  watch([selectedDate, selectedMealType], async () => {
    participantStatus.value = null;
    matriculeInput.value = '';
    await fetchMealRecords();
    await fetchRestaurationStats();
  });
  
  // Computed properties
  const currentDateFormatted = computed(() => {
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(selectedDate.value).toLocaleDateString('fr-FR', options);
  });
  
  const selectedMealTypeName = computed(() => {
    const meal = mealTypes.value.find(m => m.id === selectedMealType.value);
    return meal ? meal.name : '';
  });
  
  // All participants (seminarists + SR)
  const allParticipants = computed(() => {
    const participants = [];
  
    // Add seminarists
    seminarists.value.forEach(s => {
      participants.push({
        ...s,
        type: 'Séminariste',
        // Add a dummy level for demonstration if not provided by API
        level: s.level || (Math.random() > 0.5 ? "NIVEAU 1 - TEST" : "NIVEAU 2 - AUTORISE"),
      });
    });
  
    // Add SR
    srs.value.forEach(sr => {
      participants.push({
        ...sr,
        type: 'SR',
        // Add a dummy level for demonstration if not provided by API
        level: sr.level || (Math.random() > 0.5 ? "NIVEAU 1 - TEST" : "NIVEAU A - AUTORISE"),
      });
    });
  
    return participants;
  });
  
  const totalParticipants = computed(() => allParticipants.value.length);
  const totalSeminarists = computed(() => seminarists.value.length);
  const totalSRs = computed(() => srs.value.length);
  
  const eatenCount = computed(() => {
    return Object.keys(mealRecords.value).length;
  });
  
  const remainingCount = computed(() => totalParticipants.value - eatenCount.value);
  
  const mealProgress = computed(() => {
    if (totalParticipants.value === 0) return 0;
    return (eatenCount.value / totalParticipants.value) * 100;
  });
  
  const eatenParticipants = computed(() => {
    const eatenMatricules = new Set(Object.keys(mealRecords.value));
    return allParticipants.value.filter(p => eatenMatricules.has(p.matricule))
      .map(p => ({
        ...p,
        mealTime: mealRecords.value[p.matricule]?.time || ''
      }));
  });
  
  const notEatenParticipants = computed(() => {
    const eatenMatricules = new Set(Object.keys(mealRecords.value));
    return allParticipants.value.filter(p => !eatenMatricules.has(p.matricule));
  });
  
  const eatenSeminarists = computed(() => {
    return eatenParticipants.value.filter(p => p.type === 'Séminariste');
  });
  
  const eatenSRs = computed(() => {
    return eatenParticipants.value.filter(p => p.type === 'SR');
  });
  
  const notEatenSeminarists = computed(() => {
    return notEatenParticipants.value.filter(p => p.type === 'Séminariste');
  });
  
  const notEatenSRs = computed(() => {
    return notEatenParticipants.value.filter(p => p.type === 'SR');
  });
  
  const modalTitle = computed(() => {
    const mealName = selectedMealTypeName.value;
    const date = new Date(selectedDate.value).toLocaleDateString('fr-FR');
    if (selectedMealListType.value === 'eaten') {
      return `Participants qui ont pris le ${mealName} - ${date}`;
    } else {
      return `Participants qui n'ont pas pris le ${mealName} - ${date}`;
    }
  });
  
  // Type filters for modal
  const typeFilters = computed(() => {
    const list = selectedMealList.value;
    return [
      {
        key: 'all',
        label: 'Tous',
        icon: UsersIcon,
        count: list.length
      },
      {
        key: 'Séminariste',
        label: 'Séminaristes',
        icon: GraduationCapIcon,
        count: list.filter(p => p.type === 'Séminariste').length
      },
      {
        key: 'SR',
        label: 'SR',
        icon: UserCheckIcon,
        count: list.filter(p => p.type === 'SR').length
      }
    ];
  });
  
  const filteredMealList = computed(() => {
    if (selectedTypeFilter.value === 'all') {
      return selectedMealList.value;
    }
    return selectedMealList.value.filter(p => p.type === selectedTypeFilter.value);
  });
  
  // Methods
  const loadAllParticipants = async () => {
    loadingParticipants.value = true;
    loadedCount.value = 0;
    totalExpected.value = 0;
    loadingProgress.value = 0;
  
    try {
      // Load seminarists and SR in parallel
      await Promise.all([
        loadAllSeminarists(),
        loadAllSRs()
      ]);
  
      showAlert('success', `${totalParticipants.value} participants chargés avec succès ! (${totalSeminarists.value} séminaristes + ${totalSRs.value} SR)`);
    } catch (error) {
      console.error('Error loading participants:', error);
      showAlert('error', 'Erreur lors du chargement des participants.');
    } finally {
      loadingParticipants.value = false;
      loadingProgress.value = 100;
    }
  };
  
  const loadAllSeminarists = async () => {
    try {
      // Get total count
      const countUrl = `${API_BASE_URL_SEMINARISTES_LIST}?action=count_seminairistes&rand=${Math.random()}`;
      const countResponse = await fetch(countUrl);
      const countData = await countResponse.json();
  
      if (countData.success) {
        totalExpected.value += countData.total || 0;
      }
  
      // Load all seminarists in batches
      const allSeminarists = [];
      const batchSize = 100;
      let page = 1;
      let hasMore = true;
      while (hasMore) {
        const url = `${API_BASE_URL_SEMINARISTES_LIST}?action=list_seminairistes&page=${page}&per_page=${batchSize}&rand=${Math.random()}`;
        const response = await fetch(url);
        const data = await response.json();
        if (data.success && data.data && data.data.length > 0) {
          const batchSeminarists = data.data.map(s => ({
            matricule: s.matricule_seminaire,
            name: `${s.prenom} ${s.nom}`,
            photo: s.photo || `/placeholder.svg?height=80&width=80&query=profile+picture`,
            level: s.level, // Assuming level is returned by the 
            niveau: s.niveau_seminaire,
          }));
          allSeminarists.push(...batchSeminarists);
          loadedCount.value = allSeminarists.length;
          loadingProgress.value = totalExpected.value > 0 ? Math.round((loadedCount.value / totalExpected.value) * 100) : 0;
          hasMore = data.pagination?.has_more === true;
          page++;
          await new Promise(resolve => setTimeout(resolve, 50));
        } else {
          hasMore = false;
        }
      }
      seminarists.value = allSeminarists;
    } catch (error) {
      console.error('Error loading seminarists:', error);
      seminarists.value = [];
    }
  };
  
  const loadAllSRs = async () => {
    try {
      // Load all SR in batches
      const allSRs = [];
      const batchSize = 100;
      let page = 1;
      let hasMore = true;
      while (hasMore) {
        const url = `${API_BASE_URL_SR}?action=list_srs&page=${page}&per_page=${batchSize}&rand=${Math.random()}`;
        const response = await fetch(url);
        const data = await response.json();
        if (data.success && data.data && data.data.length > 0) {
          const batchSRs = data.data.map(sr => ({
            matricule: sr.matricule_seminaire,
            name: `${sr.prenom} ${sr.nom}`,
            photo: sr.photo || `/placeholder.svg?height=80&width=80&query=profile+picture`,
            level: sr.level, // Assuming level is returned by the API
            niveau: sr.niveau_seminaire, // Assuming level is returned by the API
          }));
          allSRs.push(...batchSRs);
          loadedCount.value += batchSRs.length;
  
          // Update total expected if we didn't get it initially
          if (totalExpected.value === 0) {
            totalExpected.value = data.pagination?.total || allSRs.length;
          }
  
          loadingProgress.value = totalExpected.value > 0 ? Math.round((loadedCount.value / totalExpected.value) * 100) : 0;
          hasMore = data.pagination?.current_page < data.pagination?.last_page;
          page++;
          await new Promise(resolve => setTimeout(resolve, 50));
        } else {
          hasMore = false;
        }
      }
      srs.value = allSRs;
    } catch (error) {
      console.error('Error loading SRs:', error);
      srs.value = [];
    }
  };
  
  const fetchMealRecords = async () => {
    try {
      const url = `${API_BASE_URL_RESTAURATION}?action=get_meal_entries&date=${selectedDate.value}&meal_type=${selectedMealType.value}`;
      const response = await fetch(url);
      const data = await response.json();
  
      if (data.success && data.data) {
        mealRecords.value = data.data.reduce((acc, entry) => {
          acc[entry.matricule] = { time: entry.mealTime };
          return acc;
        }, {});
      } else {
        console.error('Failed to fetch meal records:', data.error || 'Unknown error');
        mealRecords.value = {};
      }
    } catch (error) {
      console.error('Error fetching meal records:', error);
      mealRecords.value = {};
    }
  };
  
  const fetchRestaurationStats = async () => {
    try {
      const url = `${API_BASE_URL_RESTAURATION}?action=get_restauration_stats&date=${selectedDate.value}&meal_type=${selectedMealType.value}`;
      const response = await fetch(url);
      const data = await response.json();
  
      if (data.success && data.data) {
        // Stats are already computed reactively
      } else {
        console.error('Failed to fetch restauration stats:', data.error || 'Unknown error');
      }
    } catch (error) {
      console.error('Error fetching restauration stats:', error);
    }
  };
  
  // QR Scanner methods
  const startQRScanner = async () => {
    if (!qrCodeReader.value) {
      showAlert('error', 'Scanner QR non disponible');
      return;
    }
    try {
      showQRScanner.value = true;
      await new Promise(resolve => setTimeout(resolve, 100)); // Give time for video element to render
      qrStream.value = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
  
      if (qrVideo.value) {
        qrVideo.value.srcObject = qrStream.value;
        qrCodeReader.value.decodeFromVideoDevice(undefined, qrVideo.value, (result, error) => {
          if (result) {
            const scannedText = result.getText();
            const matriculeMatch = scannedText.match(/SEM\d+/);
            if (matriculeMatch) {
              matriculeInput.value = matriculeMatch[0];
              handleScan();
              stopQRScanner();
            } else {
              matriculeInput.value = scannedText;
              handleScan();
              stopQRScanner();
            }
          }
        });
      }
    } catch (error) {
      console.error('Error starting QR scanner:', error);
      showAlert('error', 'Erreur lors de l\'accès à la caméra');
      stopQRScanner();
    }
  };
  
  const stopQRScanner = () => {
    showQRScanner.value = false;
    if (qrStream.value) {
      qrStream.value.getTracks().forEach(track => track.stop());
      qrStream.value = null;
    }
    if (qrCodeReader.value) {
      qrCodeReader.value.reset();
    }
  };
  
  const handleScan = () => {
  const matricule = matriculeInput.value.trim();
  if (!matricule) {
    participantStatus.value = { type: 'info', message: 'Veuillez entrer un matricule ou scanner un code QR.', participant: null };
    return;
  }

  const participant = allParticipants.value.find(p => String(p.matricule) === String(matricule));

  if (!participant) {
    participantStatus.value = {
      type: 'error',
      message: `Participant avec le matricule "${matricule}" non trouvé.`,
      participant: null
    };
    showAlert('error', `Participant "${matricule}" non trouvé.`);
    return;
  }

  // Liste des niveaux refusés
  const niveauxRefuses = ['NIVEAU 1AF', 'NIVEAU 1AS', 'NIVEAU 1BF', 'NIVEAU 1BS'];

  let levelStatus = null; // 'authorized' | 'not-authorized' | null
  let levelMessage = "";

  if (participant.niveau && niveauxRefuses.includes(participant.niveau.toUpperCase())) {
    levelStatus = "not-authorized";
    levelMessage = "PAS AUTORISÉ";
  } else {
    levelStatus = "authorized";
    levelMessage = "AUTORISÉ";
  }

  const hasReceivedMeal = mealRecords.value[participant.matricule];

  const action = hasReceivedMeal ? null : 'validate';

  participantStatus.value = {
    type: levelStatus === 'authorized' ? 'success' : (levelStatus === 'not-authorized' ? 'error' : 'info'),
    message: `Vérification pour ${participant.name} (${participant.type}).`,
    participant: participant,
    lastMealTime: hasReceivedMeal ? hasReceivedMeal.time : undefined,
    action: action,
    levelStatus: levelStatus,
    levelMessage: levelMessage,
  };

  if (levelStatus === "not-authorized") {
    showAlert('error', `${participant.name} n'est PAS AUTORISÉ.`);
  } else if (levelStatus === "authorized") {
    showAlert('success', `${participant.name} est AUTORISÉ.`);
  } else {
    showAlert('info', `Niveau de ${participant.name}: ${participant.niveau || "Non spécifié"}.`);
  }

  matriculeInput.value = '';
};

  
  const validateMeal = async (matricule) => {
    try {
      const response = await fetch(API_BASE_URL_RESTAURATION + '?action=validate_meal_entry', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          matricule: matricule,
          date: selectedDate.value,
          meal_type: selectedMealType.value
        })
      });
      const data = await response.json();
      if (data.success) {
        // Update local mealRecords and participantStatus
        const newMealTime = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        mealRecords.value[matricule] = { time: newMealTime };
        const participant = allParticipants.value.find(p => String(p.matricule) === String(matricule));
  
        // Re-evaluate level status for the display
        let levelStatus = null;
        let levelMessage = "";
        if (participant?.level && participant.level.startsWith("NIVEAU 1")) {
          levelStatus = "not-authorized";
          levelMessage = "PAS AUTORISÉ";
        } else if (
          participant?.level &&
          (participant.level.startsWith("NIVEAU 2") ||
           participant.level.startsWith("NIVEAU 3") ||
           participant.level.startsWith("NIVEAU 4") ||
           participant.level.startsWith("NIVEAU 5") ||
           participant.level.startsWith("NIVEAU A"))
        ) {
          levelStatus = "authorized";
          levelMessage = "AUTORISÉ";
        } else {
          levelStatus = null;
          levelMessage = `Niveau: ${participant?.level || "Non spécifié"}`;
        }
  
        participantStatus.value = {
          type: levelStatus === 'authorized' ? 'success' : (levelStatus === 'not-authorized' ? 'error' : 'info'),
          message: `Repas validé pour ${participant?.name} (${participant?.type}).`,
          participant: participant,
          action: null,
          lastMealTime: newMealTime,
          levelStatus: levelStatus,
          levelMessage: levelMessage,
        };
        showAlert('success', `Repas validé pour ${participant?.name}.`);
        await fetchRestaurationStats(); // Refresh stats after validation
      } else {
        console.error('Failed to validate meal:', data.message);
        participantStatus.value = {
          type: 'error',
          message: data.message,
          participant: allParticipants.value.find(p => String(p.matricule) === String(matricule)),
          action: null
        };
        showAlert('error', `Erreur: ${data.message}`);
      }
    } catch (error) {
      console.error('Error validating meal:', error);
      showAlert('error', 'Erreur réseau lors de la validation du repas.');
    }
  };
  
  const quickValidateMeal = async (participant) => {
    await validateMeal(participant.matricule);
    // Refresh the modal data if it's open
    if (showMealListModal.value) {
      if (selectedMealListType.value === 'not-eaten') {
        selectedMealList.value = notEatenParticipants.value;
      }
    }
  };
  
  const openMealListModal = (type, list) => {
    selectedMealListType.value = type;
    selectedMealList.value = list;
    selectedTypeFilter.value = 'all';
    showMealListModal.value = true;
    document.body.style.overflow = 'hidden';
  };
  
  const closeMealListModal = () => {
    showMealListModal.value = false;
    selectedMealList.value = [];
    selectedMealListType.value = '';
    selectedTypeFilter.value = 'all';
    document.body.style.overflow = 'auto';
  };
  
  const exportMealList = async (type) => {
    try {
      const list = type === 'eaten' ? eatenParticipants.value : notEatenParticipants.value;
      if (list.length === 0) {
        showAlert('error', 'Aucune donnée à exporter.');
        return;
      }
      const { jsPDF } = await import('jspdf');
      const autoTable = (await import('jspdf-autotable')).default;
      const doc = new jsPDF();
      const title = type === 'eaten' ? 'ONT MANGÉ' : 'N\'ONT PAS MANGÉ';
      const date = new Date(selectedDate.value).toLocaleDateString('fr-FR');
      doc.setFontSize(20);
      doc.setTextColor(40, 40, 40);
      doc.text(`${title} - ${selectedMealTypeName.value}`, 105, 20, { align: 'center' });
      doc.setFontSize(10);
      doc.setTextColor(100, 100, 100);
      doc.text(`${date} - Généré le ${new Date().toLocaleDateString('fr-FR')}`, 105, 30, { align: 'center' });
      const tableData = list.map((p, index) => {
        const row = [index + 1, p.name, p.matricule, p.type];
        if (type === 'eaten' && p.mealTime) {
          row.push(p.mealTime);
        }
        return row;
      });
      const headers = ['N°', 'Nom Complet', 'Matricule', 'Type'];
      if (type === 'eaten') {
        headers.push('Heure du repas');
      }
      autoTable(doc, {
        head: [headers],
        body: tableData,
        startY: 40,
        styles: {
          fontSize: 10,
          cellPadding: 3
        },
        headStyles: {
          fillColor: type === 'eaten' ? [16, 185, 129] : [245, 158, 11],
          textColor: 255,
          fontStyle: 'bold'
        },
        alternateRowStyles: {
          fillColor: [248, 249, 250]
        }
      });
      const filename = `${type === 'eaten' ? 'ont_mange' : 'nont_pas_mange'}_${selectedMealType.value}_${selectedDate.value}.pdf`;
      doc.save(filename);
      showAlert('success', 'Liste exportée avec succès !');
    } catch (error) {
      console.error('Erreur lors de l\'export:', error);
      showAlert('error', 'Erreur lors de l\'export.');
    }
  };
  
  const exportMealReport = async () => {
    try {
      showAlert('info', 'Génération du rapport Excel en cours...');
      const data = [];
  
      allParticipants.value.forEach(p => {
        const hasEaten = mealRecords.value[p.matricule];
        const mealTime = hasEaten ? mealRecords.value[p.matricule].time : '';
        data.push({
          'Matricule': p.matricule,
          'Nom Complet': p.name,
          'Type': p.type,
          'Repas': selectedMealTypeName.value,
          'Date': selectedDate.value,
          'Statut': hasEaten ? 'A mangé' : 'N\'a pas mangé',
          'Heure': mealTime,
          'Niveau': p.level || 'Non spécifié', // Include level in export
        });
      });
      if (data.length === 0) {
        showAlert('error', 'Aucune donnée à exporter.');
        return;
      }
      const XLSX = await import('xlsx');
      const workbook = XLSX.utils.book_new();
      const worksheet = XLSX.utils.json_to_sheet(data);
      const colWidths = [
        { wch: 15 }, { wch: 30 }, { wch: 12 }, { wch: 15 }, { wch: 12 }, { wch: 15 }, { wch: 10 }, { wch: 20 } // Width for Niveau column
      ];
      worksheet['!cols'] = colWidths;
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Restauration');
      const filename = `restauration_${selectedMealType.value}_${selectedDate.value}.xlsx`;
      XLSX.writeFile(workbook, filename);
      showAlert('success', `Export Excel réussi ! ${data.length} enregistrements exportés.`);
    } catch (error) {
      console.error('Erreur lors de l\'export Excel:', error);
      showAlert('error', 'Erreur lors de l\'export Excel.');
    }
  };
  
  const showAlert = (type, message) => {
    alert.value = {
      show: true,
      type,
      message
    };
    setTimeout(() => {
      alert.value.show = false;
    }, 5000);
  };
  </script>
  
  <style scoped>
  .dashboard {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #f8f9fa;
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
  
  .header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
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
    align-items: center;
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
  
  .period-selector {
    display: flex;
    background-color: #f3f4f6;
    border-radius: 9999px;
    padding: 0.25rem;
  }
  
  .meal-type-btn {
    background: none;
    border: none;
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 9999px;
    cursor: pointer;
    color: #6b7280;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }
  
  .meal-type-btn.active {
    background-color: white;
    color: #111827;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }
  
  .date-input {
    padding: 0.5rem 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #111827;
    background-color: white;
  }
  
  .export-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    border-radius: 9999px;
    padding: 0.5rem 1.25rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .green-button {
    background-color: #10b981;
    color: white;
    border: 1px solid #10b981;
  }
  
  .green-button:hover {
    background-color: #059669;
    border-color: #059669;
  }
  
  /* Product Stats */
  .product-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
    gap: 1rem;
  }
  
  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .stat-icon-blue {
    background-color: #dbeafe;
    color: #3b82f6;
  }
  
  .stat-icon-green {
    background-color: #d1fae5;
    color: #10b981;
  }
  
  .stat-icon-orange {
    background-color: #fff7ed;
    color: #f97316;
  }
  
  .stat-icon-purple {
    background-color: #ede9fe;
    color: #7c3aed;
  }
  
  .stat-icon-red {
    background-color: #fee2e2;
    color: #ef4444;
  }
  
  .stat-icon-indigo {
    background-color: #e0e7ff;
    color: #6366f1;
  }
  
  .stat-info {
    display: flex;
    flex-direction: column;
  }
  
  .stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
  }
  
  .stat-label {
    font-size: 0.875rem;
    color: #6b7280;
  }
  
  /* Loading Progress Bar */
  .progress-bar-loading {
    width: 100%;
    height: 6px;
    background-color: #e5e7eb;
    border-radius: 9999px;
    overflow: hidden;
    margin-top: 0.5rem;
  }
  
  .progress-fill-loading {
    height: 100%;
    background-color: #3b82f6;
    border-radius: 9999px;
    transition: width 0.3s ease;
  }
  
  /* Quick Search Section */
  .quick-search-section {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f1f1f4;
  }
  
  .quick-search-card {
    background-color: #f8fafc;
    border-radius: 0.5rem;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
  }
  
  .card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
  }
  
  .card-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
  }
  
  .search-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .search-input-group {
    display: flex;
    gap: 0.75rem;
    align-items: center;
  }
  
  .search-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 1rem;
    color: #1e293b;
  }
  
  .search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }
  
  .qr-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .qr-button:hover {
    background-color: #e5e7eb;
  }
  
  .qr-button.active {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
  }
  
  .search-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  
  .search-button:hover {
    background-color: #2563eb;
  }
  
  /* QR Scanner */
  .qr-scanner-container {
    background-color: white;
    border-radius: 0.5rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
  }
  
  .qr-scanner-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .qr-scanner-header h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
  }
  
  .close-scanner {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: all 0.2s;
  }
  
  .close-scanner:hover {
    background-color: #f3f4f6;
    color: #374151;
  }
  
  .qr-scanner-video {
    position: relative;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    border-radius: 0.5rem;
    overflow: hidden;
  }
  
  .qr-video {
    width: 100%;
    height: 300px;
    object-fit: cover;
    background-color: #000;
  }
  
  .qr-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .qr-frame {
    width: 200px;
    height: 200px;
    border: 2px solid #3b82f6;
    border-radius: 0.5rem;
    background: transparent;
    position: relative;
  }
  
  .qr-frame::before,
  .qr-frame::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 3px solid #3b82f6;
  }
  
  .qr-frame::before {
    top: -3px;
    left: -3px;
    border-right: none;
    border-bottom: none;
  }
  
  .qr-frame::after {
    bottom: -3px;
    right: -3px;
    border-left: none;
    border-top: none;
  }
  
  .qr-instructions {
    text-align: center;
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 1rem;
    margin-bottom: 0;
  }
  
  /* Seminarist Result Card */
  .seminarist-result-card {
    background-color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    border: 1px solid;
    margin-top: 1rem;
  }
  
  .seminarist-result-card.authorized {
    border-color: #10b981;
    background-color: #f0fdf4;
  }
  
  .seminarist-result-card.not-authorized {
    border-color: #ef4444;
    background-color: #fef2f2;
  }
  
  .seminarist-result-card.info {
    border-color: #3b82f6;
    background-color: #eff6ff;
  }
  
  .result-header {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
  }
  
  .result-icon {
    flex-shrink: 0;
  }
  
  .seminarist-result-card.authorized .result-icon {
    color: #10b981;
  }
  
  .seminarist-result-card.not-authorized .result-icon {
    color: #ef4444;
  }
  
  .seminarist-result-card.info .result-icon {
    color: #3b82f6;
  }
  
  .result-content {
    flex: 1;
  }
  
  .result-message {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1e293b;
  }
  
  .seminarist-details {
    margin-top: 1rem;
  }
  
  .seminarist-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background-color: white;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
  }
  
  .profile-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e2e8f0;
  }
  
  .profile-info {
    flex: 1;
  }
  
  .profile-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem;
  }
  
  .profile-matricule,
  .profile-type,
  .profile-meal,
  .profile-time {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.125rem 0;
  }
  
  /* Meal Validation Form */
  .meal-validation-form {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
  }
  
  .validate-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #10b981;
    color: white;
    border: none;
    border-radius: 0.375rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
    width: 100%;
    justify-content: center;
  }
  
  .validate-button:hover {
    background-color: #059669;
  }
  
  /* Meal Lists Section */
  .meal-lists-section {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f1f1f4;
  }
  
  .section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
  }
  
  .section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
  }
  
  .meal-lists-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
  }
  
  .meal-list-card {
    background-color: white;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 1.5rem;
    transition: all 0.2s;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }
  
  .meal-list-card:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-color: #3b82f6;
    transform: translateY(-2px);
  }
  
  .eaten-list {
    border-left: 4px solid #10b981;
  }
  
  .not-eaten-list {
    border-left: 4px solid #f59e0b;
  }
  
  .list-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
  }
  
  .list-icon {
    width: 48px;
    height: 48px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .eaten-icon {
    background-color: #d1fae5;
    color: #10b981;
  }
  
  .not-eaten-icon {
    background-color: #fef3c7;
    color: #f59e0b;
  }
  
  .list-info {
    flex: 1;
  }
  
  .list-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem;
  }
  
  .list-count {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 0.25rem;
  }
  
  .list-breakdown {
    font-size: 0.75rem;
    color: #9ca3af;
    margin: 0;
  }
  
  .view-list-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .view-list-btn:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
  }
  
  .list-preview {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .preview-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background-color: #f8fafc;
    border-radius: 0.375rem;
  }
  
  .preview-photo {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
  }
  
  .preview-info {
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  
  .preview-name {
    font-size: 0.875rem;
    color: #1e293b;
    font-weight: 500;
    margin: 0;
  }
  
  .preview-type {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
  }
  
  .preview-more {
    font-size: 0.75rem;
    color: #64748b;
    text-align: center;
    padding: 0.5rem;
    background-color: #f1f5f9;
    border-radius: 0.375rem;
  }
  
  /* Loading and Empty States */
  .loading-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 0;
    color: #6b7280;
    gap: 1rem;
  }
  
  .loading-indicator svg {
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
  
  /* Modal */
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
  }
  
  .modal-content {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
  }
  
  .view-modal {
    max-width: 1000px;
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f1f4;
  }
  
  .modal-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }
  
  .close-button {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    transition: all 0.2s;
  }
  
  .close-button:hover {
    background-color: #f3f4f6;
    color: #111827;
  }
  
  .modal-body {
    padding: 1.5rem;
  }
  
  .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #f1f1f4;
  }
  
  .modal-filters {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
  }
  
  .filter-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background-color: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .filter-btn:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
  }
  
  .filter-btn.active {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
  }
  
  .no-items {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 0;
    color: #6b7280;
    gap: 1rem;
    text-align: center;
  }
  
  .seminarists-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
  }
  
  .seminarist-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    background-color: #f8fafc;
    transition: all 0.2s;
  }
  
  .seminarist-card:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
  }
  
  .seminarist-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e2e8f0;
    flex-shrink: 0;
  }
  
  .seminarist-info {
    flex: 1;
  }
  
  .seminarist-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
    margin: 0 0 0.25rem;
  }
  
  .seminarist-id {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0 0 0.25rem;
  }
  
  .seminarist-type {
    font-size: 0.75rem;
    color: #7c3aed;
    font-weight: 500;
    margin: 0 0 0.25rem;
  }
  
  .seminarist-meal-time {
    font-size: 0.75rem;
    color: #10b981;
    font-weight: 500;
    margin: 0;
  }
  
  .quick-validate-btn {
    background-color: #10b981;
    color: white;
    border: none;
    border-radius: 0.375rem;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
  }
  
  .quick-validate-btn:hover {
    background-color: #059669;
  }
  
  .cancel-button {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    background-color: white;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .cancel-button:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
  }
  
  .save-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .download-button-purple {
    background-color: #7c3aed;
  }
  
  .download-button-purple:hover {
    background-color: #6d28d9;
  }
  
  /* Toast */
  .toast-container {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 100;
  }
  
  .toast {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 0.5rem;
    background-color: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    min-width: 300px;
    max-width: 450px;
  }
  
  .toast.success {
    border-left: 4px solid #10b981;
  }
  
  .toast.error {
    border-left: 4px solid #ef4444;
  }
  
  .toast.warning {
    border-left: 4px solid #f59e0b;
  }
  
  .toast.info {
    border-left: 4px solid #3b82f6;
  }
  
  .toast-icon {
    margin-right: 0.75rem;
  }
  
  .toast.success .toast-icon {
    color: #10b981;
  }
  
  .toast.error .toast-icon {
    color: #ef4444;
  }
  
  .toast.warning .toast-icon {
    color: #f59e0b;
  }
  
  .toast.info .toast-icon {
    color: #3b82f6;
  }
  
  .toast-content {
    flex: 1;
    font-size: 0.875rem;
    color: #111827;
  }
  
  .toast-close {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 0.75rem;
  }
  
  /* Mobile Responsive */
  @media (max-width: 768px) {
    .main-content {
      padding: 1rem;
    }
    .overview-section {
      width: 100%;
    }
    .overview-header {
      flex-direction: column;
      gap: 1rem;
      align-items: stretch;
      padding: 1rem;
    }
    .header-actions-right {
      flex-direction: column;
      width: 100%;
      gap: 0.5rem;
    }
    .period-selector {
      width: 100%;
      justify-content: center;
    }
    .product-stats {
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      padding: 1rem;
    }
    .quick-search-section {
      padding: 1rem;
    }
    .search-input-group {
      flex-direction: column;
    }
    .meal-lists-section {
      padding: 1rem;
    }
    .meal-lists-grid {
      grid-template-columns: 1fr;
    }
    .modal-content {
      margin: 0.5rem;
      max-width: calc(100vw - 1rem);
    }
    .seminarists-grid {
      grid-template-columns: 1fr;
    }
    .seminarist-profile {
      flex-direction: column;
      text-align: center;
    }
    .modal-filters {
      justify-content: center;
    }
    .filter-btn {
      font-size: 0.75rem;
      padding: 0.375rem 0.5rem;
    }
  }
  </style>
  