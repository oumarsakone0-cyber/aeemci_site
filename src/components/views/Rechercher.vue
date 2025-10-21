<template>
    <div class="app-container">
      <!-- Header -->
      <header class="app-header">
        <div class="header-content">
          <div class="header-left">
            <div class="logo">
              <div class="logo-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                </svg>
              </div>
              <div class="logo-text">
                <h1>AEEMCI</h1>
                <p>Recherche de Membre par Matricule</p>
              </div>
            </div>
          </div>
          <div class="header-right">
            <button @click="goBack" class="btn btn-secondary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
              </svg>
              Retour
            </button>
          </div>
        </div>
      </header>
  
      <!-- Main Content -->
      <main class="main-content">
        <div class="search-container">
          <!-- Formulaire de recherche -->
          <div class="search-form">
            <h2 class="search-title">
              <svg class="title-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
              </svg>
              Rechercher un Membre
            </h2>
            
            <div class="search-input-container">
              <div class="input-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                  </svg>
                  Matricule <span class="required">*</span>
                </label>
                <div class="input-container">
                  <input
                    v-model="matricule"
                    @input="formatMatricule"
                    @keyup.enter="rechercherMembre"
                    type="text"
                    class="form-input"
                    placeholder="XXX-XXX-XX-001"
                    maxlength="15"
                    :disabled="isLoading"
                  />
                  <button
                    @click="rechercherMembre"
                    :disabled="!matricule.trim() || isLoading"
                    class="search-btn"
                  >
                    <div v-if="isLoading" class="loading-spinner"></div>
                    <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                  </button>
                </div>
                <small class="form-help">Format: ABC-DEF-20-001</small>
              </div>
            </div>
          </div>
  
          <!-- Message d'erreur -->
          <div v-if="errorMessage" class="error-message">
            <svg class="error-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
            </svg>
            {{ errorMessage }}
          </div>
  
          <!-- Résultats de la recherche -->
          <div v-if="membre" class="member-card">
            <div class="card-header">
              <div class="status-badge" :class="getStatusClass(membre.statut_paiement)">
                <svg class="status-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path v-if="membre.statut_paiement === 'PAYE'" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  <path v-else-if="membre.statut_paiement === 'EN_ATTENTE'" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  <path v-else d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
                {{ getStatusLabel(membre.statut_paiement) }}
              </div>
              <div class="matricule-display">{{ membre.matricule_gen }}</div>
            </div>
  
            <div class="card-body">
              <!-- Photo et informations principales -->
              <div class="member-header">
                <div class="photo-section">
                  <div class="photo-container">
                    <img 
                      v-if="membre.photo_membre" 
                      :src="membre.photo_membre" 
                      :alt="`Photo de ${membre.prenom} ${membre.nom}`"
                      class="member-photo"
                      @error="handleImageError"
                    />
                    <div v-else class="photo-placeholder">
                      <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <div class="member-info">
                  <h3 class="member-name">{{ membre.prenom }} {{ membre.nom }}</h3>
                  <p class="member-status">{{ getStatutLabel(membre.je_suis) }}</p>
                  <div class="member-details">
                    <div class="detail-item">
                      <svg class="detail-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                      </svg>
                      <span>{{ formatDate(membre.date_naiss) }}</span>
                    </div>
                    <div class="detail-item">
                      <svg class="detail-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                      <span>{{ membre.lieu_naiss }}</span>
                    </div>
                    <div class="detail-item">
                      <svg class="detail-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                      </svg>
                      <span>{{ membre.contact }}</span>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Informations détaillées -->
              <div class="member-sections">
                <!-- Militantisme -->
                <div class="info-section">
                  <h4 class="section-title">
                    <svg class="section-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Parcours Militant
                  </h4>
                  <div class="section-content">
                    <div class="info-grid">
                      <div class="info-item">
                        <span class="info-label">SR de début</span>
                        <span class="info-value">{{ membre.secretariat_debut }}</span>
                      </div>
                      <div class="info-item">
                        <span class="info-label">Sous-comité début</span>
                        <span class="info-value">{{ membre.souscomite_debut }}</span>
                      </div>
                      <div class="info-item">
                        <span class="info-label">Année de début</span>
                        <span class="info-value">{{ membre.annee_debut }}</span>
                      </div>
                    </div>
                  </div>
                </div>
  
                <!-- Poste actuel -->
                <div class="info-section">
                  <h4 class="section-title">
                    <svg class="section-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
                    </svg>
                    Poste Actuel
                  </h4>
                  <div class="section-content">
                    <div class="info-grid">
                      <div class="info-item" v-if="membre.qualite_membre">
                        <span class="info-label">Poste</span>
                        <span class="info-value highlight">{{ membre.qualite_membre }}</span>
                      </div>
                      <div class="info-item" v-if="membre.poste_definition">
                        <span class="info-label">Définition</span>
                        <span class="info-value">{{ membre.poste_definition }}</span>
                      </div>
                      <div class="info-item" v-if="membre.region">
                        <span class="info-label">Région</span>
                        <span class="info-value">{{ membre.region }}</span>
                      </div>
                      <div class="info-item" v-if="membre.ville_ou_commune">
                        <span class="info-label">Commune/Ville</span>
                        <span class="info-value">{{ membre.ville_ou_commune }}</span>
                      </div>
                      <div class="info-item" v-if="membre.section">
                        <span class="info-label">Section</span>
                        <span class="info-value">{{ membre.section }}</span>
                      </div>
                    </div>
                  </div>
                </div>
  
                <!-- Informations d'inscription -->
                <div class="info-section">
                  <h4 class="section-title">
                    <svg class="section-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    Informations d'Inscription
                  </h4>
                  <div class="section-content">
                    <div class="info-grid">
                      <div class="info-item">
                        <span class="info-label">Date d'inscription</span>
                        <span class="info-value">{{ formatDate(membre.created_at) }}</span>
                      </div>
                      <div class="info-item" v-if="membre.numero_wave">
                        <span class="info-label">Numéro Wave</span>
                        <span class="info-value">{{ membre.numero_wave }}</span>
                      </div>
                      <div class="info-item" v-if="membre.ref_paiement">
                        <span class="info-label">Référence paiement</span>
                        <span class="info-value">{{ membre.ref_paiement }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Actions -->
            <div class="card-actions">
              <button @click="imprimerFiche" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                </svg>
                Imprimer la fiche
              </button>
              <button @click="telechargerPDF" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                </svg>
                Télécharger PDF
              </button>
            </div>
          </div>
  
          <!-- État vide -->
          <div v-if="!membre && !isLoading && !errorMessage" class="empty-state">
            <svg class="empty-icon" width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <h3>Rechercher un membre</h3>
            <p>Saisissez un matricule pour afficher les informations du membre</p>
          </div>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  // État de l'application
  const matricule = ref('')
  const membre = ref(null)
  const isLoading = ref(false)
  const errorMessage = ref('')
  
  // Formatage du matricule
  const formatMatricule = (event) => {
    let value = event.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '')
    
    // Supprimer les tirets existants
    value = value.replace(/-/g, '')
    
    // Ajouter les tirets aux bonnes positions
    if (value.length > 3) {
      value = value.substring(0, 3) + '-' + value.substring(3)
    }
    if (value.length > 7) {
      value = value.substring(0, 7) + '-' + value.substring(7)
    }
    if (value.length > 10) {
      value = value.substring(0, 10) + '-' + value.substring(10)
    }
    
    matricule.value = value
  }
  
  // Recherche du membre
  const rechercherMembre = async () => {
    if (!matricule.value.trim()) {
      errorMessage.value = 'Veuillez saisir un matricule'
      return
    }
  
    isLoading.value = true
    errorMessage.value = ''
    membre.value = null
  
    try {
      const response = await axios.get(`https://sogetrag.com/api/rechercher_membre.php?matricule=${encodeURIComponent(matricule.value)}`)
      
      if (response.data.success) {
        membre.value = response.data.membre
      } else {
        errorMessage.value = response.data.message || 'Membre non trouvé'
      }
    } catch (error) {
      console.error('Erreur lors de la recherche:', error)
      if (error.response?.status === 404) {
        errorMessage.value = 'Aucun membre trouvé avec ce matricule'
      } else {
        errorMessage.value = 'Erreur lors de la recherche. Veuillez réessayer.'
      }
    } finally {
      isLoading.value = false
    }
  }
  
  // Fonctions utilitaires
  const formatDate = (dateString) => {
    if (!dateString) return 'Non renseigné'
    return new Date(dateString).toLocaleDateString('fr-FR')
  }
  
  const getStatusClass = (statut) => {
    switch (statut) {
      case 'PAYE': return 'status-paid'
      case 'EN_ATTENTE': return 'status-pending'
      case 'ECHEC': return 'status-failed'
      default: return 'status-unknown'
    }
  }
  
  const getStatusLabel = (statut) => {
    switch (statut) {
      case 'PAYE': return 'Payé'
      case 'EN_ATTENTE': return 'En attente'
      case 'ECHEC': return 'Échec'
      default: return 'Inconnu'
    }
  }
  
  const getStatutLabel = (statut) => {
    const labels = {
      'SR': 'Secrétaire Régional',
      'SOUS_COMITE': 'Membre du Sous-comité',
      'BUREAU_SECTION': 'Membre du bureau de Section',
      'MEMBRE_SIMPLE': 'Membre simple'
    }
    return labels[statut] || statut
  }
  
  const handleImageError = (event) => {
    event.target.style.display = 'none'
    event.target.nextElementSibling?.style.display = 'flex'
  }
  
  // Actions
  const imprimerFiche = () => {
    window.print()
  }
  
  const telechargerPDF = () => {
    // Logique de téléchargement PDF
    console.log('Téléchargement PDF pour:', membre.value.matricule_gen)
  }
  
  const goBack = () => {
    window.history.back()
  }
  </script>
  
  <style scoped>
  /* Reset et base */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  .app-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #dcfce7 0%, #f9fafb 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  
  /* Header */
  .app-header {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  }
  
  .header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .header-left {
    display: flex;
    align-items: center;
  }
  
  .logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  
  .logo-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #16a34a;
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.25rem;
  }
  
  .logo-text h1 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #16a34a;
    margin: 0;
  }
  
  .logo-text p {
    font-size: 0.875rem;
    color: #4b5563;
    margin: 0;
  }
  
  /* Main Content */
  .main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  .search-container {
    max-width: 800px;
    margin: 0 auto;
  }
  
  /* Formulaire de recherche */
  .search-form {
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 2rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    margin-bottom: 2rem;
  }
  
  .search-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    justify-content: center;
  }
  
  .title-icon {
    color: #16a34a;
  }
  
  .search-input-container {
    max-width: 400px;
    margin: 0 auto;
  }
  
  .input-group {
    display: flex;
    flex-direction: column;
  }
  
  .form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .label-icon {
    color: #16a34a;
  }
  
  .required {
    color: #dc2626;
  }
  
  .input-container {
    position: relative;
    display: flex;
  }
  
  .form-input {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem 0 0 0.5rem;
    font-size: 1rem;
    font-family: 'Courier New', monospace;
    text-transform: uppercase;
    transition: all 0.2s ease;
    background: #ffffff;
  }
  
  .form-input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  
  .form-input:disabled {
    background: #f9fafb;
    cursor: not-allowed;
  }
  
  .search-btn {
    padding: 0.75rem 1rem;
    background: #16a34a;
    color: #ffffff;
    border: 2px solid #16a34a;
    border-radius: 0 0.5rem 0.5rem 0;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .search-btn:hover:not(:disabled) {
    background: #15803d;
    border-color: #15803d;
  }
  
  .search-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .form-help {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
    text-align: center;
  }
  
  /* Message d'erreur */
  .error-message {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .error-icon {
    flex-shrink: 0;
  }
  
  /* Carte du membre */
  .member-card {
    background: #ffffff;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
  }
  
  .card-header {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
  }
  
  .status-paid {
    background: #dcfce7;
    color: #15803d;
  }
  
  .status-pending {
    background: #fef3c7;
    color: #d97706;
  }
  
  .status-failed {
    background: #fecaca;
    color: #dc2626;
  }
  
  .status-unknown {
    background: #f3f4f6;
    color: #6b7280;
  }
  
  .status-icon {
    flex-shrink: 0;
  }
  
  .matricule-display {
    font-size: 1.25rem;
    font-weight: bold;
    color: #16a34a;
    font-family: 'Courier New', monospace;
  }
  
  .card-body {
    padding: 2rem;
  }
  
  /* En-tête du membre */
  .member-header {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .photo-section {
    flex-shrink: 0;
  }
  
  .photo-container {
    width: 120px;
    height: 150px;
    border: 2px solid #16a34a;
    border-radius: 0.5rem;
    overflow: hidden;
    background: #f9fafb;
  }
  
  .member-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
  }
  
  .member-info {
    flex: 1;
  }
  
  .member-name {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .member-status {
    font-size: 1rem;
    color: #16a34a;
    font-weight: 600;
    margin-bottom: 1rem;
  }
  
  .member-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #4b5563;
  }
  
  .detail-icon {
    color: #16a34a;
    flex-shrink: 0;
  }
  
  /* Sections d'informations */
  .member-sections {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .info-section {
    background: #f9fafb;
    border-radius: 0.5rem;
    padding: 1.5rem;
  }
  
  .section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .section-icon {
    color: #16a34a;
  }
  
  .section-content {
    background: #ffffff;
    border-radius: 0.5rem;
    padding: 1rem;
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
  }
  
  .info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .info-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .info-value {
    font-size: 0.875rem;
    color: #1f2937;
    font-weight: 500;
  }
  
  .info-value.highlight {
    color: #16a34a;
    font-weight: 600;
  }
  
  /* Actions */
  .card-actions {
    padding: 1.5rem;
    background: #f9fafb;
    display: flex;
    gap: 1rem;
    justify-content: center;
  }
  
  .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }
  
  .btn-primary {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  
  .btn-primary:hover {
    background: #15803d;
    border-color: #15803d;
    transform: translateY(-1px);
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
  }
  
  .btn-secondary {
    background: #ffffff;
    color: #374151;
    border-color: #d1d5db;
  }
  
  .btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
  }
  
  /* État vide */
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
  }
  
  .empty-icon {
    margin-bottom: 1rem;
    opacity: 0.5;
  }
  
  .empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
  }
  
  .empty-state p {
    font-size: 0.875rem;
  }
  
  /* Animation de chargement */
  .loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .header-content {
      padding: 1rem;
      flex-direction: column;
      gap: 1rem;
    }
  
    .main-content {
      padding: 1rem;
    }
  
    .search-form {
      padding: 1.5rem;
    }
  
    .member-header {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
  
    .photo-container {
      width: 100px;
      height: 125px;
    }
  
    .info-grid {
      grid-template-columns: 1fr;
    }
  
    .card-actions {
      flex-direction: column;
    }
  
    .btn {
      width: 100%;
    }
  }
  
  @media (max-width: 480px) {
    .search-title {
      font-size: 1.25rem;
      flex-direction: column;
      gap: 0.5rem;
    }
  
    .input-container {
      flex-direction: column;
    }
  
    .form-input {
      border-radius: 0.5rem 0.5rem 0 0;
    }
  
    .search-btn {
      border-radius: 0 0 0.5rem 0.5rem;
      border-top: none;
    }
  
    .member-name {
      font-size: 1.25rem;
    }
  
    .card-header {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }
  }
  </style>
  