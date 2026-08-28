// src/router/index.js
import { createRouter, createWebHistory } from "vue-router"
import Dashboard from "../components/views/Dashboard.vue"
import axios from 'axios'

const Messages = () => import("../components/pages/Messages.vue")
const About = () => import("../components/clients/Clients.vue")
const Login = () => import("../components/login/Login.vue")
const Register = () => import("../components/login/Register.vue")
const Historique = () => import("../components/views/Historique.vue")
const Charte = () => import("../components/views/Charte.vue")
const Bureau = () => import("../components/views/Bureau.vue")
const Chauffeurs = () => import("../components/views/Chauffeurs.vue")
const Carburants = () => import("../components/views/Carburants.vue")
const CarteMembre = () => import("../components/views/CarteMembre.vue")
const CartePaye = () => import("../components/views/CartePaye.vue")
const ListeMembre = () => import("../components/views/ListeMembre.vue")
const Seminariste = () => import("../components/views/Seminariste.vue")
const SeminairePaye = () => import("../components/views/SeminairePaye.vue")
const Contact = () => import("../components/views/Contact.vue")
const Organes = () => import("../components/views/Organes.vue")
const Formations = () => import("../components/views/Formations.vue")
const Images = () => import("../components/views/Images.vue")
const ActualiteDetail = () => import("../components/views/actualite-detail.vue")
const Programmes = () => import("../components/views/Programmes.vue")
const ProgrammeDetail = () => import("../components/views/ProgrammeDetail.vue")
const ListeSeminaire = () => import("../components/views/ListeSeminaire.vue")
const EvaluationsManagement = () => import("../components/views/evaluations-management.vue")
const EvaluationQuestionnaire = () => import("../components/views/evaluation-questionnaire.vue")
const TestSortie = () => import("../components/views/test-sortie.vue")
const ConduiteSeminaristes = () => import("../components/views/ConduiteSeminaristes.vue")
const MesDocuments = () => import("../components/views/MesDocuments.vue")
const PresenceCamp = () => import("../components/views/PresenceCamp.vue")
const RapportCommission = () => import("../components/views/RapportCommission.vue")
const SortieCamp = () => import("../components/views/SortieCamp.vue")
const CritiquesSenafoi = () => import("../components/views/CritiquesSenafoi.vue")
const SenafoiVideo = () => import("../components/views/SenafoiVideo.vue")
const GrandQuiz = () => import("../components/views/grand-quiz.vue")
const ConcoursPhoto = () => import("../components/views/concours-photo.vue")
const CritiqueAnonyme = () => import("../components/views/critique-anonyme.vue")
const MonAffiche = () => import("../components/views/mon-affiche.vue")
const EvaluationCorrectionVue = () => import("../components/views/evaluation-correction-vue.vue")
const CarteStatus = () => import("../components/views/carteStatus.vue")
const Commission = () => import("../components/views/Commission.vue")
const Bachelier = () => import("../components/views/Bachelier.vue")
const ListeBachelier = () => import("../components/views/ListeBachelier.vue")
const Retardataire = () => import("../components/views/Retardataire.vue")
const Secretariat = () => import("../components/views/Secretariat.vue")
const SeminaristesSrMembres = () => import("../components/views/SeminaristesSrMembres.vue")
const SeminaristesCcrMembres = () => import("../components/views/SeminaristesCcrMembres.vue")
const CommissionSenafoiMembres = () => import("../components/views/CommissionSenafoiMembres.vue")
const FormateursImamsSenafoi = () => import("../components/views/FormateursImamsSenafoi.vue")
const ImaratSenafoi = () => import("../components/views/ImaratSenafoi.vue")
const SrPaye = () => import("../components/views/SrPaye.vue")
const InscriptionStands = () => import("../components/views/inscription-stands.vue")
const StandConfirmation = () => import("../components/views/stand-confirmation.vue")
const Seminariste2 = () => import("../components/views/Seminariste2.vue")
const Seminariste3 = () => import("../components/views/Seminariste3.vue")
const Seminariste6 = () => import("../components/views/Seminariste6.vue")
const Seminariste4 = () => import("../components/views/Seminariste4.vue")
const MembreSenafoi = () => import("../components/views/MembreSenafoi.vue")
const Samedi = () => import("../components/views/Samedi.vue")
const Avis = () => import("../components/views/Avis.vue")
const ListeNiveau = () => import("../components/views/ListeNiveau.vue")
const Resultat_matricule = () => import("../components/views/Resultat_matricule.vue")
const EspaceOrganes = () => import("../components/views/EspaceOrganes.vue")
const LoginOrganes = () => import("../components/views/LoginOrganes.vue")
const DashboardOrgane = () => import("../components/views/DashboardOrgane.vue")
const Organigramme = () => import("../components/views/Organigramme.vue")
const Cellules = () => import("../components/views/Cellules.vue")
const Tv = () => import("../components/views/tv.vue")
const Don = () => import("../components/views/Don.vue")
const Boutique = () => import("../components/views/Boutique.vue")
const CV = () => import("../components/views/CV.vue")
const ListeCima = () => import("../components/views/ListeCima.vue")
const InscriptionCima = () => import("../components/views/InscriptionCima.vue")
const VerifierCima = () => import("../components/views/VerifierCima.vue")
const CarteAncien = () => import("../components/views/CarteAncien.vue")
const ListeCImaCommission = () => import("../components/views/ListeCImaCommission.vue")
const PolitiqueConf = () => import("../components/views/politiqueConf.vue")
const Opportunites = () => import("../components/views/Opportunites.vue")
const RuptureInscription = () => import("../components/views/RuptureInscription.vue")
const BancoInscription = () => import("../components/views/BancoInscription.vue")
const ConcoursApplicationsIslamiques = () => import("../components/views/ConcoursApplicationsIslamique.vue")
const SeminaireStats = () => import("../components/views/SeminaireStats.vue")
const RapportFinal = () => import("../components/views/RapportFinal.vue")

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
    path: "/politique_confidentialite",
    name: "politique_confidentialite",
    component: PolitiqueConf,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
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
    path: "/seminaire_stats",
    name: "seminaire_stats",
    component: SeminaireStats,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaire_rapport_final",
    name: "seminaire_rapport_final",
    component: RapportFinal,
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
    path: "/opportunites",
    name: "opportunites",
    component: Opportunites,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/carte_ancien",
    name: "carte_ancien",
    component: CarteAncien,
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
    path: "/seminaristes_sr",
    name: "seminaristes_sr",
    component: SeminaristesSrMembres,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaristes_ccr",
    name: "seminaristes_ccr",
    component: SeminaristesCcrMembres,
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
    path: "/liste_inscrits_cima",
    name: "liste_inscrits_cima",
    component: ListeCima,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/liste_inscrits_cima_commission",
    name: "liste_inscrits_cima_commission",
    component: ListeCImaCommission,
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
    path: "/inscription_rupture",
    name: "inscription_rupture",
    component: RuptureInscription,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/inscription_banco",
    name: "inscription_banco",
    component: BancoInscription,
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
    path: "/verification_cima/:matricule",
    name: "verification_cima",
    component: VerifierCima,
    props: true, // permet de passer le paramètre à ton composant
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/seminaire_paye/:matricule?",
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
    path: "/concours_applications_islamique",
    name: "concours_applications_islamique",
    component: ConcoursApplicationsIslamiques,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/commission_senafoi",
    name: "commission_senafoi",
    component: CommissionSenafoiMembres,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/formateurs_senafoi",
    name: "formateurs_senafoi",
    component: FormateursImamsSenafoi,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/imarat_senafoi",
    name: "imarat_senafoi",
    component: ImaratSenafoi,
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
    // Lien unique du test de sortie : le niveau du séminariste route seul
    // vers le bon test, aucun identifiant d'évaluation dans l'adresse.
    path: "/test_sortie",
    name: "test_sortie",
    component: TestSortie,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    // Retrait public des documents : chacun saisit son matricule et récupère
    // son diplôme, accompagné de son bulletin lorsqu'il en a un.
    path: "/mes_documents",
    name: "mes_documents",
    component: MesDocuments,
    meta: {
      // Même cadre que la page d'inscription des séminaristes : la navigation
      // du site reste visible au-dessus du bandeau SENAFOI.
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    // Poste de scan de la conduite : profil du séminariste + notation 12-16
    path: "/conduite_seminaristes",
    name: "conduite_seminaristes",
    component: ConduiteSeminaristes,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    // Plateau de jeu : plein écran, sans en-tête ni pied de page du site
    path: "/grand_quiz",
    name: "grand_quiz",
    component: GrandQuiz,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    path: "/concours_photo",
    name: "concours_photo",
    component: ConcoursPhoto,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/critique_anonyme",
    name: "critique_anonyme",
    component: CritiqueAnonyme,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/mon_affiche",
    name: "mon_affiche",
    component: MonAffiche,
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
    path: "/seminaristes_reduction_26",
    name: "seminaristes_reduction_26",
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
    path: '/commission_cima_2025',
    name: 'retardataires123',
    component: Retardataire,
    props: route => ({
      evaluationId: route.query.evaluation_id,
      matricule: route.query.matricule,
      sessionId: route.query.session_id
    })
  },

  {
    path: '/participant_cima_2025',
    name: 'retardataires1233',
    component: InscriptionCima,
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
    path: "/programmes",
    name: "programmes",
    component: Programmes,
    meta: {
      requiresAuth: false,
      hideNavigation: false,
    },
  },
  {
    path: "/programmes/:slug",
    name: "programme-detail",
    component: ProgrammeDetail,
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
  {
    // Poste de scan tenu à l'entrée du camp : page nue, sans en-tête
    // ni pied de page, pensée pour un téléphone ou une tablette.
    path: "/presence_camp",
    name: "presence_camp",
    component: PresenceCamp,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    // Rapport journalier des commissions : un dépôt par commission et par jour
    path: "/rapport_commission",
    name: "rapport_commission",
    component: RapportCommission,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    // Poste des sorties provisoires, tenu au portail du camp
    path: "/sortie_camp",
    name: "sortie_camp",
    component: SortieCamp,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    // Mur public des critiques : page autonome, sans cadre du site
    path: "/critiques_senafoi",
    name: "critiques_senafoi",
    component: CritiquesSenafoi,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
    },
  },
  {
    // Fil vidéo du séminaire : plein écran, réservé aux porteurs de matricule
    path: "/senafoi_video",
    name: "senafoi_video",
    component: SenafoiVideo,
    meta: {
      requiresAuth: false,
      hideNavigation: true,
      sansCadre: true,
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
const API_BASE_URL = 'https://api.aeemci-ce.ci/auth.php'

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
