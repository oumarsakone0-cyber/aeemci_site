<template>
  <div class="login-container">
    <div class="login-wrapper">
      <!-- Logo et titre -->
      <div class="header-section">
        <div class="logo-circle">
          <svg class="lock-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h1 class="main-title">Espace Organes</h1>
        <p class="subtitle">Connectez-vous à votre compte</p>
      </div>

      <!-- Formulaire de connexion -->
      <div class="form-container">
        <form @submit.prevent="handleLogin" class="login-form">
          <!-- Champ Matricule -->
          <div class="input-group">
            <label for="matricule" class="input-label">Matricule</label>
            <div class="input-wrapper">
              <input
                id="matricule"
                v-model="formData.matricule"
                @input="formatMatricule"
                @keydown="handleMatriculeKeydown"
                type="text"
                placeholder="XXX-XXX-XX-XXX"
                maxlength="15"
                class="form-input"
                :class="{ 'input-error': matriculeError }"
                required
              />
              <div class="input-icon">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
            </div>
            <p v-if="matriculeError" class="error-message">{{ matriculeError }}</p>
          </div>

          <!-- Champ Mot de passe -->
          <div class="input-group">
            <label for="password" class="input-label">Mot de passe</label>
            <div class="input-wrapper">
              <input
                id="password"
                v-model="formData.password"
                @input="handlePasswordInput"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Code à 6 chiffres"
                maxlength="6"
                class="form-input password-input"
                :class="{ 'input-error': passwordError }"
                required
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="password-toggle"
              >
                <svg v-if="showPassword" class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                </svg>
                <svg v-else class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
            <p v-if="passwordError" class="error-message">{{ passwordError }}</p>
          </div>

          <!-- Message d'erreur général -->
          <div v-if="loginError" class="error-container">
            <div class="error-content">
              <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="error-text">{{ loginError }}</p>
            </div>
          </div>

          <!-- Bouton de connexion -->
          <button
            type="submit"
            :disabled="!isFormValid || isLoading"
            class="submit-button"
            :class="{ 'button-disabled': !isFormValid || isLoading }"
          >
            <span v-if="isLoading" class="loading-content">
              <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="spinner-circle" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="spinner-path" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Connexion...
            </span>
            <span v-else>Se connecter</span>
          </button>
        </form>

        <!-- Lien d'aide -->
        <div class="help-section">
          <p class="help-text">
            Problème de connexion ? 
            <a href="#" class="help-link">Contactez l'administrateur</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// État du formulaire
const formData = reactive({
  matricule: '',
  password: ''
})

// États de l'interface
const showPassword = ref(false)
const isLoading = ref(false)
const matriculeError = ref('')
const passwordError = ref('')
const loginError = ref('')

// Validation du matricule
const isMatriculeValid = computed(() => {
  const matriculePattern = /^[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{2}-[A-Za-z0-9]{3}$/
  return matriculePattern.test(formData.matricule)
})

// Validation du mot de passe
const isPasswordValid = computed(() => {
  return /^\d{6}$/.test(formData.password)
})

// Validation générale du formulaire
const isFormValid = computed(() => {
  return isMatriculeValid.value && isPasswordValid.value && !matriculeError.value && !passwordError.value
})

// Formatage automatique du matricule
const formatMatricule = (event) => {
  let value = event.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase() // Garde seulement lettres et chiffres et convertit en majuscules
  
  // Limiter à 11 caractères maximum
  if (value.length > 11) {
    value = value.substring(0, 11)
  }
  
  // Appliquer le format XXX-XXX-XX-XXX
  if (value.length >= 3) {
    value = value.substring(0, 3) + '-' + value.substring(3)
  }
  if (value.length >= 7) {
    value = value.substring(0, 7) + '-' + value.substring(7)
  }
  if (value.length >= 10) {
    value = value.substring(0, 10) + '-' + value.substring(10)
  }
  
  formData.matricule = value
  
  // Validation en temps réel
  if (value && !isMatriculeValid.value) {
    matriculeError.value = 'Format invalide. Utilisez XXX-XXX-XX-XXX (lettres ou chiffres)'
  } else {
    matriculeError.value = ''
  }
}

// Validation du mot de passe en temps réel
const validatePassword = () => {
  if (formData.password && !isPasswordValid.value) {
    passwordError.value = 'Le mot de passe doit contenir exactement 6 chiffres'
  } else {
    passwordError.value = ''
  }
}

// Fonction de connexion
const handleLogin = async () => {
  // Réinitialiser les erreurs
  loginError.value = ''
  matriculeError.value = ''
  passwordError.value = ''
  
  // Validation finale
  if (!isMatriculeValid.value) {
    matriculeError.value = 'Format de matricule invalide'
    return
  }
  
  if (!isPasswordValid.value) {
    passwordError.value = 'Le mot de passe doit contenir 6 chiffres'
    return
  }
  
  isLoading.value = true
  
  try {
    const formDataToSend = new FormData()
    formDataToSend.append('action', 'login')
    formDataToSend.append('matricule', formData.matricule)
    formDataToSend.append('password', formData.password)
    
    const response = await fetch('https://sogetrag.com/api/organes-api.php?action=login', {
      method: 'POST',
      body: formDataToSend
    })
    
    const data = await response.json()
    
    if (data.success) {
      localStorage.setItem('user_session', JSON.stringify({
        matricule: formData.matricule,
        nom: data.user.nom,
        prenom: data.user.prenom,
        poste: data.user.poste.actuel,
        organe: data.user.organe.nom,
        type_organe: data.user.organe.type,
        photo: data.user.photo,
        organe_id: data.user.organe.id,
        token: data.session.token,
        session_id: data.session_id
      }))
      
      router.push('/dashboard_organe')
    } else {
      loginError.value = data.message || 'Identifiants incorrects'
    }
  } catch (error) {
    console.error('Erreur de connexion:', error)
    loginError.value = 'Erreur de connexion. Veuillez réessayer.'
  } finally {
    isLoading.value = false
  }
}

// Validation en temps réel du mot de passe
const handlePasswordInput = () => {
  formData.password = formData.password.replace(/\D/g, '')
  validatePassword()
}

const handleMatriculeKeydown = (event) => {
  // Si c'est la touche Backspace et que le curseur est sur un tiret
  if (event.key === 'Backspace') {
    const input = event.target
    const cursorPosition = input.selectionStart
    const value = input.value
    
    // Si le curseur est juste après un tiret, supprimer le tiret et le caractère précédent
    if (cursorPosition > 0 && value[cursorPosition - 1] === '-') {
      event.preventDefault()
      const newValue = value.substring(0, cursorPosition - 2) + value.substring(cursorPosition)
      formData.matricule = newValue
      
      // Repositionner le curseur
      setTimeout(() => {
        input.setSelectionRange(cursorPosition - 2, cursorPosition - 2)
      }, 0)
      
      // Reformater le matricule
      const fakeEvent = { target: { value: newValue.replace(/[^A-Za-z0-9]/g, '') } }
      formatMatricule(fakeEvent)
    }
  }
}
</script>

<style scoped>
/* Container principal */
.login-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #f0f9f0 0%, #e8f5e8 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.login-wrapper {
  width: 100%;
  max-width: 28rem;
}

/* Section header */
.header-section {
  text-align: center;
  margin-bottom: 2rem;
}

.logo-circle {
  width: 4rem;
  height: 4rem;
  background-color: #018404;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  box-shadow: 0 4px 12px rgba(1, 132, 0, 0.2);
}

.lock-icon {
  width: 2rem;
  height: 2rem;
  color: white;
}

.main-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 0.5rem 0;
}

.subtitle {
  color: #6b7280;
  margin: 0;
  font-size: 1rem;
}

/* Container du formulaire */
.form-container {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  padding: 2rem;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Groupes d'inputs */
.input-group {
  display: flex;
  flex-direction: column;
}

.input-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}

.input-wrapper {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 1rem;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.form-input:focus {
  outline: none;
  border-color: #018404;
  box-shadow: 0 0 0 3px rgba(1, 132, 0, 0.1);
}

.password-input {
  padding-right: 3rem;
}

.input-error {
  border-color: #ef4444 !important;
}

.input-error:focus {
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
}

.input-icon {
  position: absolute;
  top: 50%;
  right: 0.75rem;
  transform: translateY(-50%);
  pointer-events: none;
}

.password-toggle {
  position: absolute;
  top: 50%;
  right: 0.75rem;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: #9ca3af;
  transition: color 0.2s ease;
  padding: 0;
}

.password-toggle:hover {
  color: #6b7280;
}

.icon {
  width: 1.25rem;
  height: 1.25rem;
}

.error-message {
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #dc2626;
}

/* Container d'erreur */
.error-container {
  background-color: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 0.5rem;
  padding: 1rem;
}

.error-content {
  display: flex;
  align-items: flex-start;
}

.error-icon {
  width: 1.25rem;
  height: 1.25rem;
  color: #f87171;
  margin-right: 0.5rem;
  flex-shrink: 0;
}

.error-text {
  font-size: 0.875rem;
  color: #b91c1c;
  margin: 0;
}

/* Bouton de soumission */
.submit-button {
  width: 100%;
  background-color: #018404;
  color: white;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 1rem;
}

.submit-button:hover:not(.button-disabled) {
  background-color: #016a33;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(1, 132, 0, 0.3);
}

.submit-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(1, 132, 0, 0.2);
}

.button-disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

.loading-content {
  display: flex;
  align-items: center;
  justify-content: center;
}

.spinner {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.75rem;
  animation: spin 1s linear infinite;
}

.spinner-circle {
  opacity: 0.25;
}

.spinner-path {
  opacity: 0.75;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Section d'aide */
.help-section {
  margin-top: 1.5rem;
  text-align: center;
}

.help-text {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0;
}

.help-link {
  color: #018404;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s ease;
}

.help-link:hover {
  color: #016a33;
  text-decoration: underline;
}

/* Responsive */
@media (max-width: 640px) {
  .login-container {
    padding: 0.5rem;
  }
  
  .form-container {
    padding: 1.5rem;
  }
  
  .main-title {
    font-size: 1.5rem;
  }
  
  .logo-circle {
    width: 3.5rem;
    height: 3.5rem;
  }
  
  .lock-icon {
    width: 1.75rem;
    height: 1.75rem;
  }
}

/* Animations */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
