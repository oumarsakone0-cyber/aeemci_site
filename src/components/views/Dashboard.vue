<template>
  <div class="app">
    <BannerComponent />
    <BodyComponent />
  </div>
</template>

<script setup>
import BannerComponent from '../components/BannerComponent.vue'
import BodyComponent from '../components/BodyComponent.vue'
</script>

<style>
/* Reset and Base Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: #2d3748;
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}
</style>
