<template>
  <div class="sidebar">
    <!-- Liens Utiles -->
    <div class="sidebar-section">
      <h3 class="sidebar-title"><span class="title-icon">🔗</span> Liens Utiles</h3>
      <ul class="links-list">
        <li v-for="link in liensUtiles" :key="link.id">
          <a :href="link.url" class="sidebar-link" target="_blank">
            <span class="link-icon">{{ link.icon }}</span>
            {{ link.name }}
          </a>
        </li>
      </ul>
    </div>

    <!-- Organes -->
    <div class="sidebar-section">
      <h3 class="sidebar-title"><span class="title-icon">🏛️</span> Organes de l'AEEMCI</h3>
      <div class="organes-list">
        <div v-for="organe in organes" :key="organe.id" class="organe-item">
          <div class="organe-icon">{{ organe.icon }}</div>
          <div class="organe-info">
            <h5 class="organe-name">{{ organe.name }}</h5>
            <p class="organe-description">{{ organe.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Horaires de Prière -->
    <div class="sidebar-section">
      <h3 class="sidebar-title"><span class="title-icon">🕌</span> Horaires de Prière</h3>
      <div class="prayer-widget">
        <div class="current-time">{{ currentTime }}</div>
        <div class="prayers-list">
          <div class="prayer-item" v-for="prayer in prayerTimes" :key="prayer.name">
            <span class="prayer-name">{{ prayer.name }}</span>
            <span class="prayer-time">{{ prayer.time }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  liensUtiles: Array,
  organes: Array,
  prayerTimes: Array,
  currentTime: String
})
</script>
