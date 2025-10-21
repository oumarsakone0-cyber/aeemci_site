// src/router/index.js
import { createRouter, createWebHistory } from "vue-router"
import Dashboard from "../components/views/Dashboard.vue"
import Messages from "../components/pages/Messages.vue"
import About from "../components/clients/Clients.vue"
import Login from "../components/login/Login.vue"
import Register from "../components/login/Register.vue"
import Historique from "../components/views/Historique.vue"
import Charte from "../components/views/Charte.vue"
import Bureau from "../components/views/Bureau.vue"
import Chauffeurs from "../components/views/Chauffeurs.vue"
import Carburants from "../components/views/Carburants.vue"
import axios from 'axios'
import CarteMembre from "../components/views/CarteMembre.vue"
import CartePaye from "../components/views/CartePaye.vue"
import ListeMembre from "../components/views/ListeMembre.vue"
import Seminariste from "../components/views/Seminariste.vue"
import SeminairePaye from "../components/views/SeminairePaye.vue"
import Contact from "../components/views/Contact.vue"
import Organes from "../components/views/Organes.vue"
import Formations from "../components/views/Formations.vue"
import Images from "../components/views/Images.vue"
import ActualiteDetail from "../components/views/actualite-detail.vue"
import ListeSeminaire from "../components/views/ListeSeminaire.vue"
import EvaluationsManagement from "../components/views/evaluations-management.vue"
import EvaluationQuestionnaire from "../components/views/evaluation-questionnaire.vue"
import EvaluationCorrectionVue from "../components/views/evaluation-correction-vue.vue"
import CarteStatus from "../components/views/carteStatus.vue"
import Commission from "../components/views/Commission.vue"
import Bachelier from "../components/views/Bachelier.vue"
import ListeBachelier from "../components/views/ListeBachelier.vue"
import Retardataire from "../components/views/Retardataire.vue"
import Secretariat from "../components/views/Secretariat.vue"
import SrPaye from "../components/views/SrPaye.vue"
import InscriptionStands from "../components/views/inscription-stands.vue"
import StandConfirmation from "../components/views/stand-confirmation.vue"
import Seminariste2 from "../components/views/Seminariste2.vue"
import Seminariste3 from "../components/views/Seminariste3.vue"
import Seminariste6 from "../components/views/Seminariste6.vue"
import Seminariste4 from "../components/views/Seminariste4.vue"
import MembreSenafoi from "../components/views/MembreSenafoi.vue"
import Samedi from "../components/views/Samedi.vue"
import Avis from "../components/views/Avis.vue"
import ListeNiveau from "../components/views/ListeNiveau.vue"
import Resultat_matricule from "../components/views/Resultat_matricule.vue"
import EspaceOrganes from "../components/views/EspaceOrganes.vue"
import LoginOrganes from "../components/views/LoginOrganes.vue"
import DashboardOrgane from "../components/views/DashboardOrgane.vue"
import Organigramme from "../components/views/Organigramme.vue"
import Cellules from "../components/views/Cellules.vue"
import Tv from "../components/views/tv.vue"
import Don from "../components/views/Don.vue"
import Boutique from "../components/views/Boutique.vue"
import CV from "../components/views/CV.vue"

const routes = [
  {
    path: "/",
    name: "accueil",
    component: Dashboard,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/historique",
    name: "historique",
    component: Historique,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/cellules",
    name: "cellules",
    component: Cellules,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/don",
    name: "don",
    component: Don,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/cv",
    name: "cv",
    component: CV,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/tv",
    name: "tv",
    component: Tv,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/organigramme",
    name: "organigramme",
    component: Organigramme,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/charte",
    name: "charte",
    component: Charte,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/espace_organe",
    name: "espace_organe",
    component: EspaceOrganes,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/login_organe",
    name: "login_organe",
    component: LoginOrganes,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/dashboard_organe",
    name: "dashboard_organe",
    component: DashboardOrgane,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/boutique",
    name: "boutique",
    component: Boutique,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/liste_resultat",
    name: "liste_resultat",
    component: ListeNiveau,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/resultat_senafoi",
    name: "resultat_senafoi",
    component: Resultat_matricule,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/bureau",
    name: "bureau",
    component: Bureau,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/carte_membre",
    name: "carte_membre",
    component: CarteMembre,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaristes",
    name: "seminaristes",
    component: Seminariste,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/liste_inscrits_carte",
    name: "liste_inscrits_carte",
    component: ListeMembre,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/liste_inscrits_seminaire",
    name: "liste_inscrits_seminaire",
    component: ListeSeminaire,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/carte_paye/:matricule",
    name: "carte_paye",
    component: CartePaye,
    props: true, // permet de passer le paramètre à ton composant
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaire_sr_paye/:matricule",
    name: "seminaire_sr_paye",
    component: SrPaye,
    props: true, // permet de passer le paramètre à ton composant
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaire_paye/:matricule",
    name: "seminaire_paye",
    component: SeminairePaye,
    props: true, // permet de passer le paramètre à ton composant
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/stand_paye/:reference",
    name: "stand_paye",
    component: StandConfirmation,
    props: true, // permet de passer le paramètre à ton composant
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/contact",
    name: "contact",
    component: Contact,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/commission",
    name: "commission",
    component: Commission,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/verification_carte",
    name: "verification_carte",
    component: CarteStatus,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/evaluation_management",
    name: "evaluation_management",
    component: EvaluationsManagement,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/evaluation_question/:id",
    name: "evaluation_question",
    component: EvaluationQuestionnaire,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: '/correction',
    name: 'correction',
    component: EvaluationCorrectionVue,
    props: route => ({
      evaluationId: route.query.evaluation_id,
      matricule: route.query.matricule,
      sessionId: route.query.session_id
    })
  },
  {
    path: '/inscription_sr',
    name: 'inscription_sr',
    component: Secretariat,
    props: route => ({
      evaluationId: route.query.evaluation_id,
      matricule: route.query.matricule,
      sessionId: route.query.session_id
    })
  },
  {
    path: '/inscription_stands',
    name: 'inscription_stands',
    component: InscriptionStands,
    props: route => ({
      evaluationId: route.query.evaluation_id,
      matricule: route.query.matricule,
      sessionId: route.query.session_id
    })
  },
  {
    path: "/seminaristes_reduction_3007",
    name: "seminaristes_reduction_3007",
    component: Seminariste2,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaristes_reduction_009",
    name: "seminaristes_reduction_009",
    component: Seminariste3,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaristes_reduction_15000",
    name: "seminaristes_reduction_15000",
    component: Seminariste6,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/samedi",
    name: "samedi",
    component: Samedi,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/avis_senafoi",
    name: "avis_senafoi",
    component: Avis,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaristes_reduction_10000_01",
    name: "seminaristes_reduction_10000_01",
    component: Seminariste4,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/membre_senafoi",
    name: "membre_senafoi",
    component: MembreSenafoi,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: '/retardataires123',
    name: 'retardataires123',
    component: Retardataire,
    props: route => ({
      evaluationId: route.query.evaluation_id,
      matricule: route.query.matricule,
      sessionId: route.query.session_id
    })
  },
  {
    path: "/detail_actualite",
    name: "detail_actualite",
    component: ActualiteDetail,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/images",
    name: "images",
    component: Images,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/formations",
    name: "formations",
    component: Formations,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/bachelier",
    name: "bachelier",
    component: Bachelier,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/liste_bachelier",
    name: "liste_bachelier",
    component: ListeBachelier,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/organes",
    name: "organes",
    component: Organes,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/chauffeur",
    name: "chauffeur",
    component: Chauffeurs,
    meta: {
      requiresAuth: true,
      hideNavigation: false,
    },
  },
  {
    path: "/carburants",
    name: "carburants",
    component: Carburants,
    meta: {
      requiresAuth: true,
      hideNavigation: false,
    },
  },
  {
    path: "/messages",
    name: "messages",
    component: Messages,
    meta: {
      requiresAuth: true,
      hideNavigation: false,
    },
  },
  {
    path: "/clients",
    name: "clients",
    component: About,
    meta: {
      requiresAuth: true,
      hideNavigation: false,
    },
  },
  {
    path: "/login",
    name: "login",
    component: Login,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
    },
  },
  {
    path: "/register",
    name: "register",
    component: Register,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
    },
  },
  // Route de redirection pour les chemins non trouvés
  {
    path: "/:pathMatch(.*)*",
    redirect: "/",
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Configuration de l'API
const API_BASE_URL = 'https://sogetrag.com/api/auth.php'

// Store simple pour l'état d'authentification
const authStore = {
  user: null,
  isAuthenticated: false,
  lastLoginTime: null,
  
  setUser(user) {
    this.user = user
    this.isAuthenticated = true
    this.lastLoginTime = Date.now()
    localStorage.setItem('user', JSON.stringify(user))
    localStorage.setItem('lastLoginTime', this.lastLoginTime.toString())
    console.log('Utilisateur connecté:', user)
  },
  
  clearUser() {
    this.user = null
    this.isAuthenticated = false
    this.lastLoginTime = null
    localStorage.removeItem('user')
    localStorage.removeItem('lastLoginTime')
    console.log('Utilisateur déconnecté')
  },
  
  loadUser() {
    const userData = localStorage.getItem('user')
    const lastLoginTime = localStorage.getItem('lastLoginTime')
    
    if (userData && lastLoginTime) {
      try {
        this.user = JSON.parse(userData)
        this.lastLoginTime = parseInt(lastLoginTime)
        this.isAuthenticated = true
        
        // Vérifier si la session locale n'est pas trop ancienne (24h)
        const now = Date.now()
        const maxAge = 24 * 60 * 60 * 1000 // 24 heures
        
        if (now - this.lastLoginTime > maxAge) {
          console.log('Session locale expirée')
          this.clearUser()
          return false
        }
        
        console.log('Utilisateur chargé depuis localStorage:', this.user)
        return true
      } catch (error) {
        console.error('Erreur parsing user data:', error)
        this.clearUser()
        return false
      }
    }
    return false
  }
}

// Fonction pour vérifier le statut d'authentification côté serveur
async function checkServerAuth() {
  try {
    const response = await axios.get(`${API_BASE_URL}?action=check_session`, {
      headers: {
        'Content-Type': 'application/json'
      }
    })
    
    console.log('Réponse serveur check_session:', response.data)
    
    if (response.data.success && response.data.authenticated) {
      // Mettre à jour les infos utilisateur si nécessaire
      if (!authStore.user || authStore.user.id !== response.data.user.id) {
        authStore.setUser(response.data.user)
      }
      return true
    } else {
      console.log('Session serveur non authentifiée')
      return false
    }
  } catch (error) {
    console.error('Erreur vérification session serveur:', error)
    // En cas d'erreur réseau, on garde l'auth locale
    return authStore.isAuthenticated
  }
}

// Fonction pour vérifier le statut d'authentification
function checkAuthStatus() {
  const hasLocalUser = authStore.loadUser()
  console.log('Auth status local:', hasLocalUser)
  return hasLocalUser
}

// Variable pour éviter les redirections multiples
let isCheckingAuth = false

// Guard de navigation pour protéger les routes
router.beforeEach(async (to, from, next) => {
  console.log(`Navigation: ${from.path} -> ${to.path}`)
  
  // Charger l'utilisateur depuis le localStorage
  const hasLocalAuth = checkAuthStatus()
  
  // Si la route nécessite une authentification
  if (to.meta.requiresAuth) {
    if (!hasLocalAuth) {
      console.log('Redirection vers login - pas d\'auth locale')
      next("/login")
      return
    }
    
    // On a une auth locale, laisser passer
    console.log('Auth locale OK, accès autorisé à:', to.path)
    next()
    
    // Vérifier côté serveur en arrière-plan SANS rediriger automatiquement
    if (!isCheckingAuth) {
      isCheckingAuth = true
      checkServerAuth().then(isServerAuth => {
        if (!isServerAuth && authStore.isAuthenticated) {
          console.log('Session serveur expirée, mais on garde l\'auth locale')
          // Optionnel: afficher une notification à l'utilisateur
          // ou forcer une re-authentification silencieuse
        }
      }).catch(error => {
        console.error('Erreur vérification serveur:', error)
      }).finally(() => {
        isCheckingAuth = false
      })
    }
  }
  // Si l'utilisateur est connecté et essaie d'accéder aux pages d'auth
  else if ((to.name === "login" || to.name === "register") && hasLocalAuth) {
    console.log('Utilisateur connecté, redirection vers dashboard')
    next("/")
  }
  // Route publique ou pas d'authentification requise
  else {
    console.log('Route publique, accès libre à:', to.path)
    next()
  }
})

// Fonction utilitaire pour déconnecter l'utilisateur
export async function logout() {
  try {
    console.log('Déconnexion en cours...')
    await axios.post(`${API_BASE_URL}?action=logout`, {}, {
      headers: {
        'Content-Type': 'application/json'
      }
    })
    console.log('Déconnexion serveur réussie')
  } catch (error) {
    console.error('Erreur lors de la déconnexion serveur:', error)
  } finally {
    authStore.clearUser()
    router.push('/login')
  }
}

// Fonction utilitaire pour obtenir l'utilisateur actuel
export function getCurrentUser() {
  return authStore.user
}

// Fonction utilitaire pour vérifier si l'utilisateur est connecté
export function isAuthenticated() {
  return authStore.isAuthenticated
}

// Fonction pour forcer une vérification serveur (optionnelle)
export async function forceServerCheck() {
  const isValid = await checkServerAuth()
  if (!isValid) {
    authStore.clearUser()
    router.push('/login')
  }
  return isValid
}

// Exporter le store pour utilisation dans les composants
export { authStore }

export default router