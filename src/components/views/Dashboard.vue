<template>
  <div class="home">
    <BannerComponent />
    <BodyComponent />
  </div>
</template>

<script setup>
import BannerComponent from '../components/BannerComponent.vue'
import BodyComponent from '../components/BodyComponent.vue'
</script>

<style scoped>
/* La typographie et les couleurs viennent du système de design (src/assets/theme.css). */
.home{ background:var(--white); }
</style>
