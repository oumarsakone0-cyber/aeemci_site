<template>
    <div class="app">
      <!-- Background animé -->
      <div class="animated-background">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
        <div class="floating-shape shape-5"></div>
        <div class="floating-shape shape-6"></div>
      </div>
  
      <div class="container">
        <div class="main-card">
          <div class="header">
            <h1>Consultation des Séminaristes</h1>
            <p>SENAFOI 2025 - Sélection par Niveau</p>
          </div>
  
          <!-- Sélection du niveau -->
          <div v-if="!selectedLevel" class="level-selection">
            <h2>Choisir votre niveau</h2>
            <div class="level-buttons">
              <button 
                v-for="level in levels" 
                :key="level"
                @click="selectLevel(level)"
                class="level-btn"
                :disabled="loading"
              >
                {{ level }}
              </button>
            </div>
          </div>
  
          <!-- Loading state -->
          <div v-else-if="loading" class="loading-section">
            <div class="loading-spinner"></div>
            <p>Chargement des séminaristes...</p>
          </div>
  
          <!-- Affichage des résultats -->
          <div v-else-if="students.length > 0" class="results-section">
            <div class="results-header">
              <h2>{{ selectedLevel }} - {{ filteredStudents.length }} séminariste(s)</h2>
              <button @click="goBack" class="back-btn">← Retour</button>
            </div>
  
            <!-- Barre de recherche -->
            <div class="search-section">
              <div class="search-input-container">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Rechercher par nom, prénom ou matricule..."
                  class="search-input"
                />
                <div class="search-icon">🔍</div>
              </div>
              <div class="search-info">
                {{ filteredStudents.length }} résultat(s) trouvé(s)
              </div>
            </div>
  
            <!-- Tableau des résultats -->
            <div class="table-container">
              <div class="table-wrapper">
                <table class="results-table">
                  <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Matricule</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="student in paginatedStudents" :key="student.matricule">
                      <td class="name-cell">{{ student.nom }} {{ student.prenom }} <br/><span class="matricule-cell">{{ student.matricule }}</span> </td>
                      <td class="matricule-cell"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
  
              <!-- Pagination -->
              <div v-if="totalPages > 1" class="pagination">
                <button 
                  @click="currentPage--" 
                  :disabled="currentPage === 1"
                  class="page-btn"
                >
                  ← Précédent
                </button>
                <span class="page-info">
                  Page {{ currentPage }} sur {{ totalPages }}
                </span>
                <button 
                  @click="currentPage++" 
                  :disabled="currentPage === totalPages"
                  class="page-btn"
                >
                  Suivant →
                </button>
              </div>
            </div>
          </div>
  
          <!-- Message d'erreur ou aucun résultat -->
          <div v-else-if="selectedLevel && !loading" class="no-results">
            <div class="no-results-icon">📋</div>
            <h3>{{ error || 'Aucun séminariste trouvé' }}</h3>
            <p v-if="error">{{ error }}</p>
            <p v-else>Aucun séminariste n'a été trouvé pour le {{ selectedLevel }}.</p>
            <button @click="goBack" class="back-btn">← Retour</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ConsultationSeminaristes',
    data() {
      return {
        selectedLevel: null,
        searchQuery: '',
        currentPage: 1,
        itemsPerPage: 25,
        loading: false,
        error: null,
        levels: ['NIVEAU 1', 'NIVEAU 2', 'NIVEAU 3', 'NIVEAU 4', 'NIVEAU 5'],
        students: [],
        apiUrl: 'https://sogetrag.com/api/get_resultat_final.php' // Remplacez par votre URL d'API
      }
    },
    computed: {
      filteredStudents() {
        if (!this.searchQuery) return this.students;
        
        const query = this.searchQuery.toLowerCase();
        return this.students.filter(student => 
          student.nom.toLowerCase().includes(query) || 
          student.prenom.toLowerCase().includes(query) ||
          student.matricule.toLowerCase().includes(query)
        );
      },
      paginatedStudents() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredStudents.slice(start, end);
      },
      totalPages() {
        return Math.ceil(this.filteredStudents.length / this.itemsPerPage);
      }
    },
    watch: {
      searchQuery() {
        this.currentPage = 1; // Reset to first page when searching
      }
    },
    methods: {
      async selectLevel(level) {
        this.selectedLevel = level;
        this.searchQuery = '';
        this.currentPage = 1;
        this.error = null;
        this.loading = true;
  
        try {
          const response = await fetch(this.apiUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              action: 'export_by_level',
              niveau: level
            })
          });
  
          const data = await response.json();
  
          if (data.success) {
            // Trier les étudiants par nom puis prénom
            this.students = data.data.sort((a, b) => {
              const nameA = `${a.nom} ${a.prenom}`.toLowerCase();
              const nameB = `${b.nom} ${b.prenom}`.toLowerCase();
              return nameA.localeCompare(nameB);
            });
          } else {
            this.error = data.error || 'Erreur lors de la récupération des données';
            this.students = [];
          }
        } catch (err) {
          console.error('Erreur API:', err);
          this.error = 'Erreur de connexion à l\'API';
          this.students = [];
        } finally {
          this.loading = false;
        }
      },
      goBack() {
        this.selectedLevel = null;
        this.searchQuery = '';
        this.currentPage = 1;
        this.students = [];
        this.error = null;
      }
    }
  }
  </script>
  
  <style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  .app {
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  }
  
  /* Background animé */
  .animated-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f8fafc 100%);
    z-index: -1;
  }
  
  .floating-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.08;
    animation: float 25s infinite linear;
  }
  
  .shape-1 {
    width: 120px;
    height: 120px;
    background: linear-gradient(45deg, #0ea5e9, #0284c7);
    top: 10%;
    left: 10%;
    animation-delay: 0s;
  }
  
  .shape-2 {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #f59e0b, #d97706);
    top: 20%;
    right: 15%;
    animation-delay: -8s;
  }
  
  .shape-3 {
    width: 100px;
    height: 100px;
    background: linear-gradient(45deg, #10b981, #059669);
    bottom: 20%;
    left: 20%;
    animation-delay: -15s;
  }
  
  .shape-4 {
    width: 60px;
    height: 60px;
    background: linear-gradient(45deg, #8b5cf6, #7c3aed);
    top: 60%;
    right: 25%;
    animation-delay: -20s;
  }
  
  .shape-5 {
    width: 90px;
    height: 90px;
    background: linear-gradient(45deg, #ef4444, #dc2626);
    bottom: 10%;
    right: 10%;
    animation-delay: -12s;
  }
  
  .shape-6 {
    width: 70px;
    height: 70px;
    background: linear-gradient(45deg, #06b6d4, #0891b2);
    top: 40%;
    left: 5%;
    animation-delay: -18s;
  }
  
  @keyframes float {
    0% { transform: translateY(0px) rotate(0deg) scale(1); }
    25% { transform: translateY(-30px) rotate(90deg) scale(1.1); }
    50% { transform: translateY(0px) rotate(180deg) scale(1); }
    75% { transform: translateY(-15px) rotate(270deg) scale(0.9); }
    100% { transform: translateY(0px) rotate(360deg) scale(1); }
  }
  
  .container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    margin: 0 auto;
  }
  
  .main-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 24px;
    box-shadow: 0 32px 64px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.3);
    width: 100%;
    max-width: 1000px;
    padding: 48px;
    text-align: center;
  }
  
  .header h1 {
    font-size: 2.8rem;
    color: #0f172a;
    margin-bottom: 12px;
    font-weight: 800;
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .header p {
    color: #64748b;
    font-size: 1.2rem;
    margin-bottom: 48px;
    font-weight: 500;
  }
  
  /* Sélection du niveau */
  .level-selection h2 {
    color: #1e293b;
    margin-bottom: 36px;
    font-size: 2rem;
    font-weight: 700;
  }
  
  .level-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 36px;
  }
  
  .level-btn {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    border: none;
    padding: 24px 36px;
    border-radius: 16px;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 12px 32px rgba(14, 165, 233, 0.3);
    position: relative;
    overflow: hidden;
  }
  
  .level-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
  }
  
  .level-btn:hover::before {
    left: 100%;
  }
  
  .level-btn:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(14, 165, 233, 0.4);
  }
  
  .level-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }
  
  /* Loading */
  .loading-section {
    text-align: center;
    padding: 60px 20px;
  }
  
  .loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #0ea5e9;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 24px;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .loading-section p {
    color: #64748b;
    font-size: 1.1rem;
    font-weight: 500;
  }
  
  /* Section des résultats */
  .results-section {
    text-align: left;
  }
  
  .results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 36px;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .results-header h2 {
    color: #0f172a;
    font-size: 2rem;
    font-weight: 700;
  }
  
  .back-btn {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
    border: none;
    padding: 14px 24px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
  }
  
  .back-btn:hover {
    background: linear-gradient(135deg, #475569, #334155);
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(100, 116, 139, 0.4);
  }
  
  /* Section de recherche */
  .search-section {
    margin-bottom: 36px;
  }
  
  .search-input-container {
    position: relative;
    margin-bottom: 16px;
  }
  
  .search-input {
    width: 100%;
    padding: 18px 60px 18px 24px;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-size: 1.1rem;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .search-input:focus {
    outline: none;
    border-color: #0ea5e9;
    box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1), 0 8px 20px rgba(0, 0, 0, 0.1);
  }
  
  .search-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 1.4rem;
  }
  
  .search-info {
    color: #64748b;
    font-size: 1rem;
    font-weight: 500;
    font-style: italic;
  }
  
  /* Tableau */
  .table-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(226, 232, 240, 0.8);
  }
  
  .table-wrapper {
    overflow-x: auto;
  }
  
  .results-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
  }
  
  .results-table th {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    padding: 24px 20px;
    text-align: left;
    font-weight: 700;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .results-table td {
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    transition: background-color 0.2s ease;
  }
  
  .results-table tr:hover td {
    background-color: #f8fafc;
  }
  
  .name-cell {
    font-weight: 600;
    color: #0f172a;
    min-width: 150px;
    font-size: 1.05rem;
  }
  
  .matricule-cell {
    font-family: 'Monaco', 'Menlo', monospace;
    font-weight: 500;
    color: #0ea5e9;
    background: #f0f9ff;
    padding: 8px 12px;
    border-radius: 8px;
    display: inline-block;
    font-size: 0.95rem;
  }
  
  /* No results */
  .no-results {
    text-align: center;
    padding: 80px 20px;
  }
  
  .no-results-icon {
    font-size: 4rem;
    margin-bottom: 24px;
    opacity: 0.6;
  }
  
  .no-results h3 {
    color: #1e293b;
    font-size: 1.8rem;
    margin-bottom: 16px;
    font-weight: 700;
  }
  
  .no-results p {
    color: #64748b;
    font-size: 1.1rem;
    margin-bottom: 32px;
    line-height: 1.6;
  }
  
  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 24px;
    padding: 24px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
  }
  
  .page-btn {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 16px rgba(14, 165, 233, 0.3);
  }
  
  .page-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(14, 165, 233, 0.4);
  }
  
  .page-btn:disabled {
    background: #94a3b8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
  }
  
  .page-info {
    color: #475569;
    font-weight: 600;
    font-size: 1.05rem;
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .main-card {
      padding: 32px 24px;
      margin: 16px;
    }
    
    .header h1 {
      font-size: 2.2rem;
    }
    
    .level-buttons {
      grid-template-columns: 1fr;
    }
    
    .results-header {
      flex-direction: column;
      align-items: stretch;
    }
    
    .results-header h2 {
      font-size: 1.6rem;
      text-align: center;
    }
    
    .results-table th,
    .results-table td {
      padding: 16px 12px;
      font-size: 0.95rem;
    }
    
    .name-cell {
      min-width: 120px;
    }
    
    .pagination {
      flex-direction: column;
      gap: 16px;
    }
  }
  
  @media (max-width: 480px) {
    .main-card {
      padding: 24px 16px;
    }
    
    .header h1 {
      font-size: 1.9rem;
    }
    
    .results-table {
      min-width: 500px;
    }
    
    .name-cell {
      min-width: 100px;
    }
  }
  </style>