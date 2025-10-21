<template>
  <div class="organes-page">
    <DashboardHeader 
      :userData="userData"
      :membresCount="membresCount"
      :activitesCount="activitesCount"
    />
    
    <DashboardBody 
      :bureauMembers="bureauMembers"
      :sousComites="sousComites"
      @add-member="addMember"
      @view-member="viewMember"
      @edit-member="editMember"
      @delete-member="deleteMember"
      @view-sous-comite="viewSousComite"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import DashboardHeader from './Dash_head.vue'
import DashboardBody from './Dash_body.vue'

// État réactif
const userData = ref(null)
const bureauMembers = ref([
  {
    id: 1,
    poste: 'Président',
    prenom: 'Jean',
    nom: 'KOUAME',
    matricule: 'MAT001',
    contact: '+225 07 00 00 00'
  },
  {
    id: 2,
    poste: 'Secrétaire Général',
    prenom: 'Marie',
    nom: 'TRAORE',
    matricule: 'MAT002',
    contact: '+225 07 11 11 11'
  }
])

const sousComites = ref([
  {
    id: 1,
    nom: 'Sous-comité Nord',
    president: 'Paul DIALLO',
    nbSections: 5
  },
  {
    id: 2,
    nom: 'Sous-comité Sud',
    president: 'Alice KONE',
    nbSections: 3
  }
])

// Computed
const membresCount = computed(() => {
  return bureauMembers.value.length
})

const activitesCount = computed(() => {
  return 12 // Exemple
})

// Méthodes
const loadUserData = () => {
  const userDataFromStorage = localStorage.getItem('user_session')
  if (userDataFromStorage) {
    userData.value = JSON.parse(userDataFromStorage)
  }
}

const addMember = (memberData) => {
  const newId = Math.max(...bureauMembers.value.map(m => m.id)) + 1
  bureauMembers.value.push({
    id: newId,
    ...memberData
  })
}

const viewMember = (membre) => {
  console.log('Voir membre:', membre)
}

const editMember = (membre) => {
  console.log('Modifier membre:', membre)
}

const deleteMember = (membre) => {
  const index = bureauMembers.value.findIndex(m => m.id === membre.id)
  if (index > -1) {
    bureauMembers.value.splice(index, 1)
  }
}

const viewSousComite = (sousComite) => {
  console.log('Voir sous-comité:', sousComite)
}

onMounted(() => {
  loadUserData()
})
</script>

<style scoped>
.organes-page {
  background: #f9fafb;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>