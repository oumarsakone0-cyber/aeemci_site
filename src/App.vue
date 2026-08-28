<template>
  <div class="app-shell">
    <!-- Certaines pages (poste de scan, plein écran) se passent du cadre du site -->
    <AppHeader v-if="!sansCadre" />
    <main :class="{ 'has-frame': !sansCadre }">
      <router-view />
    </main>
    <AppFooter v-if="!sansCadre" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppHeader from './components/components/NavbarComponent.vue'
import AppFooter from './components/components/FooterComponent.vue'

const route = useRoute()

// Pages qui s'affichent seules, sans en-tête ni pied de page
const sansCadre = computed(() => Boolean(route.meta?.sansCadre))
</script>

<style scoped>
.app-shell{ background:var(--white); min-height:100vh; }

/*
  L'en-tête est fixe : les pages sans héros plein écran doivent compenser sa hauteur.
  Le héros, lui, passe volontairement dessous (il porte la classe .hero).
*/
main.has-frame{ padding-top:130px; }
main.has-frame:has(.hero--home){ padding-top:0; }

@media (max-width:900px){
  main.has-frame{ padding-top:108px; }
}
</style>
