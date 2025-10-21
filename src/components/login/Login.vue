<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
      <!-- Logo et titre -->
      <div class="text-center">
        <div class="mx-auto h-16 w-16 bg-orange-500 rounded-full flex items-center justify-center mb-4">
          <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900">Connexion</h2>
        <p class="mt-2 text-sm text-gray-600">
          Gestion des bons de camions bennes et carburants
        </p>
      </div>

      <!-- Formulaire de connexion -->
      <div class="bg-white rounded-lg shadow-xl p-8">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Champ Email/Nom d'utilisateur -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email ou nom d'utilisateur
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
              </div>
              <input
                id="email"
                v-model="form.email"
                type="text"
                required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                :class="{ 'border-red-500': errors.email }"
                placeholder="Entrez votre email"
              />
            </div>
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>

          <!-- Champ Mot de passe -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Mot de passe
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
              </div>
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                :class="{ 'border-red-500': errors.password }"
                placeholder="Entrez votre mot de passe"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                  <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                </svg>
              </button>
            </div>
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
          </div>

          <!-- Options -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                v-model="form.rememberMe"
                type="checkbox"
                class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                Se souvenir de moi
              </label>
            </div>
            <button
              type="button"
              @click="showForgotPassword = true"
              class="text-sm text-orange-600 hover:text-orange-500 font-medium"
            >
              Mot de passe oublié ?
            </button>
          </div>

          <!-- Message d'erreur général -->
          <div v-if="loginError" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <div class="flex">
              <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              <div class="ml-3">
                <p class="text-sm text-red-800">{{ loginError }}</p>
              </div>
            </div>
          </div>

          <!-- Message de succès -->
          <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex">
              <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <div class="ml-3">
                <p class="text-sm text-green-800">{{ successMessage }}</p>
              </div>
            </div>
          </div>

          <!-- Bouton de connexion -->
          <button
            type="submit"
            :disabled="loading"
            style="background-color: green;"
            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Connexion...' : 'Se connecter' }}
          </button>
        </form>
      </div>

      <!-- Modal Mot de passe oublié -->
      <div v-if="showForgotPassword" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Mot de passe oublié</h3>
            <button @click="showForgotPassword = false" class="text-gray-400 hover:text-gray-600">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <p class="text-sm text-gray-600 mb-4">
            Entrez votre adresse email pour recevoir un lien de réinitialisation.
          </p>
          <form @submit.prevent="handleForgotPassword">
            <input
              v-model="forgotEmail"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 mb-4"
              placeholder="Votre adresse email"
            />
            <div v-if="forgotError" class="mb-4 text-sm text-red-600">{{ forgotError }}</div>
            <div v-if="forgotSuccess" class="mb-4 text-sm text-green-600">{{ forgotSuccess }}</div>
            <div class="flex space-x-3">
              <button
                type="button"
                @click="showForgotPassword = false"
                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
              >
                Annuler
              </button>
              <button
                type="submit"
                :disabled="forgotLoading"
                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 disabled:opacity-50"
              >
                {{ forgotLoading ? 'Envoi...' : 'Envoyer' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '../../router/index.js'
import axios from 'axios'

const router = useRouter()

// Configuration de l'API
const API_BASE_URL = 'https://sogetrag.com/api/auth.php'

// État du formulaire
const form = reactive({
  email: '',
  password: '',
  rememberMe: false
})

// État de l'interface
const loading = ref(false)
const showPassword = ref(false)
const showForgotPassword = ref(false)
const forgotEmail = ref('')
const forgotLoading = ref(false)

// Gestion des erreurs et messages
const errors = reactive({
  email: '',
  password: ''
})
const loginError = ref('')
const successMessage = ref('')
const forgotError = ref('')
const forgotSuccess = ref('')

// Vérifier si déjà connecté au chargement
onMounted(() => {
  if (authStore.loadUser()) {
    console.log('Utilisateur déjà connecté, redirection vers dashboard')
    router.push('/')
  }
})

// Validation du formulaire
const validateForm = () => {
  errors.email = ''
  errors.password = ''
  
  if (!form.email) {
    errors.email = 'L\'email est requis'
    return false
  }
  
  if (!form.password) {
    errors.password = 'Le mot de passe est requis'
    return false
  }
  
  if (form.password.length < 6) {
    errors.password = 'Le mot de passe doit contenir au moins 6 caractères'
    return false
  }
  
  return true
}

// Gestion de la connexion
const handleLogin = async () => {
  if (!validateForm()) return
  
  loading.value = true
  loginError.value = ''
  successMessage.value = ''
  
  try {
    console.log('Tentative de connexion pour:', form.email)
    
    const response = await axios.post(`${API_BASE_URL}?action=login`, {
      email: form.email,
      password: form.password,
      rememberMe: form.rememberMe
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    })
    
    console.log('Réponse login:', response.data)
    
    if (response.data.success) {
      successMessage.value = `Connexion réussie ! Bienvenue ${response.data.user.nom}`
      
      // Stocker les informations utilisateur dans le store
      authStore.setUser(response.data.user)
      
      console.log('Utilisateur stocké, redirection vers dashboard')
      
      // Redirection immédiate
      setTimeout(() => {
        router.push('/')
      }, 1000)
    } else {
      loginError.value = response.data.error || 'Erreur de connexion'
      
      // Gestion des erreurs spécifiques par champ
      if (response.data.field) {
        errors[response.data.field] = response.data.error
      }
    }
    
  } catch (error) {
    console.error('Erreur de connexion:', error)
    
    if (error.response && error.response.data) {
      loginError.value = error.response.data.error || 'Erreur de connexion'
      
      if (error.response.data.field) {
        errors[error.response.data.field] = error.response.data.error
      }
    } else {
      loginError.value = 'Erreur de connexion au serveur'
    }
  } finally {
    loading.value = false
  }
}

// Gestion du mot de passe oublié
const handleForgotPassword = async () => {
  if (!forgotEmail.value || !forgotEmail.value.includes('@')) {
    forgotError.value = 'Veuillez entrer une adresse email valide'
    return
  }
  
  forgotLoading.value = true
  forgotError.value = ''
  forgotSuccess.value = ''
  
  try {
    const response = await axios.post(`${API_BASE_URL}?action=forgot_password`, {
      email: forgotEmail.value
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    })
    
    if (response.data.success) {
      forgotSuccess.value = response.data.message
      forgotEmail.value = ''
      
      // Fermer la modal après 3 secondes
      setTimeout(() => {
        showForgotPassword.value = false
        forgotSuccess.value = ''
      }, 3000)
    } else {
      forgotError.value = response.data.error || 'Erreur lors de l\'envoi'
    }
    
  } catch (error) {
    console.error('Erreur mot de passe oublié:', error)
    forgotError.value = 'Erreur lors de l\'envoi de l\'email'
  } finally {
    forgotLoading.value = false
  }
}
</script>